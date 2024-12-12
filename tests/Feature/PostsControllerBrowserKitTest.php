<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Post;
use App\Models\User;
use App\Services\Repositories\PostsRepositoryInterface;
use Tests\Feature\Mocks\InMemoryPostsRepository;

class PostsControllerBrowserKitTest extends \Laravel\BrowserKitTesting\TestCase
{
    public string $baseUrl = '/';

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
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

       $this->visit('/posts')
            ->assertResponseOk()
            ->seeInElement('h1', 'Posts')
            ->seeElement(".posts-list")
            ->seeElementCount(".posts-list > .post",1)
            ->seeInElement(".posts-list > .post .post-title", "Sample Post")
        ;
    }
}
