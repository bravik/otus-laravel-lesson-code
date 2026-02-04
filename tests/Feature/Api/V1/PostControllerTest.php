<?php

namespace Tests\Feature\Api\V1;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(PostController::class)]
class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->editor()->create();
    }

    public function test_list(): void
    {
        Post::factory()->count(3)->create();

        $this->actingAs($this->user, 'jwt');

        $response = $this->getJson('/api/v1/posts');
            // Method chaining
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'createdAt'],
                ],
            ])
        ;
    }

    public function testCreateNewPost(): void
    {
        $user = User::factory()->editor()->create();

        $postData = [
            'title' => 'Valid Post Title',
            'text' => 'This is a valid post text.',
        ];

        $this->actingAs($user, 'jwt')
            ->postJson('/api/v1/posts', $postData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'text',
                ]
            ])
            ->assertJsonFragment([
                'title' => $postData['title'],
                'text' => $postData['text'],
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => $postData['title'],
            'text' => $postData['text'],
            'author_id' => $user->id,
        ]);
    }

    public function testUserIsNotAllowedToCreatePost(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'jwt')
            ->postJson('/api/v1/posts', [
                'title' => 'Valid Post Title',
                'text' => 'This is a valid post text.',
            ])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    #[DataProvider('invalidPostInputProvider')]
    public function testCreatePostWithInvalidData(string $title, string $text, string $errorKey): void
    {
        $invalidPostData = [
            'title' => $title,
            'text' => $text,
        ];

        $this->actingAs($this->user, 'jwt')
            ->postJson('/api/v1/posts', $invalidPostData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([$errorKey]);

        $this->assertDatabaseMissing('posts', [
            'title' => $invalidPostData['title'],
        ]);
    }

    public static function invalidPostInputProvider(): array
    {
        return [
            'short title' => ['12345', 'This is a valid post text.', 'title'],
            'short text' => ['Valid Post Title', '12345', 'text'],
        ];
    }

    public function testStoreWithoutAuthentication(): void
    {
        $postData = [
            'title' => 'Valid Post Title',
            'text' => 'This is a valid post text.',
        ];

        $this->postJson('/api/v1/posts', $postData)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseMissing('posts', [
            'title' => $postData['title'],
            'text' => $postData['text'],
        ]);
    }
}
