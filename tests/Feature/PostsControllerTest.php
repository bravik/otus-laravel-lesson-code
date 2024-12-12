<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Services\Repositories\PostsRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Feature\Mocks\InMemoryPostsRepository;
use Tests\TestCase;

class PostsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example with seeding database
     */
    public function testIndexPageWithDatabase(): void
    {
        $user = User::factory()->editor()->create();
        Post::factory()->count(3)->create();

        $this->actingAs($user);

        $this->get('/posts')
            ->assertStatus(Response::HTTP_OK)
            ->assertSeeText('Posts')
            ->assertSee('<div class="posts-list">', false)
            ->assertSee('<div class="mt-6 post">', false)
        ;
    }

    /**
     * No database queries are made in this test.
     */
    public function testIndexPageWithMockObjects(): void
    {
        $user = new User([
            'name' => 'John Doe',
            'role' => 'editor',
            'email' => 'example@example.com'
        ]);
        $this->actingAs($user);

        $post = Post::create('Sample Post', 'This is a sample post content', $user);
        $post->id = 123;

        $this->app->instance(PostsRepositoryInterface::class, new InMemoryPostsRepository([$post]));

        $this->get('/posts')
            ->assertStatus(Response::HTTP_OK)
            ->assertSeeText('Posts')
            ->assertSee('<div class="posts-list">', false)
            ->assertSee('<div class="mt-6 post">', false)
        ;
    }
}
