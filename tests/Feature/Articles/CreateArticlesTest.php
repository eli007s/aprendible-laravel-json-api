<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateArticlesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_cannot_let_guest_users_create_articles(): void
    {
        $article = array_filter(Article::factory()->raw(['user_id' => '']));

        $url = route('api.v1.articles.create');

        $this->assertDatabaseMissing('articles', $article);

        $this->jsonApi()
            ->withData([
                'type' => 'articles',
                'attributes' => $article
            ])
            ->post($url)
            ->assertStatus(401);

        $this->assertDatabaseMissing('articles', $article);
    }

    /**
     * @test
     */
    public function it_can_let_authorized_users_create_articles(): void
    {
        $user = User::factory()->create();

        $article = array_filter(Article::factory()->raw(['user_id' => '']));

        $url = route('api.v1.articles.create');

        $this->assertDatabaseMissing('articles', $article);

        Sanctum::actingAs($user);

        $this->jsonApi()
            ->withData([
                'type' => 'articles',
                'attributes' => $article
            ])
            ->post($url)
            ->assertCreated();

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $article['title'],
            'slug' => $article['slug'],
            'content' => $article['content']
        ]);
    }

    /**
     * @test
     */
    public function it_has_required_title(): void
    {
        $article = Article::factory()->raw(['title' => '']);

        $url = route('api.v1.articles.create');

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()
            ->withData([
                'type' => 'articles',
                'attributes' => $article
            ])
            ->post($url)
            ->assertStatus(422)
            ->assertSee('data\/attributes\/title');

        $this->assertDatabaseMissing('articles', $article);
    }

    /**
     * @test
     */
    public function it_has_required_slug(): void
    {
        $article = Article::factory()->raw(['slug' => '']);

        $url = route('api.v1.articles.create');

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()
            ->withData([
                'type' => 'articles',
                'attributes' => $article
            ])
            ->post($url)
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug');

        $this->assertDatabaseMissing('articles', $article);
    }

    /**
     * @test
     */
    public function it_has_unique_slug(): void
    {
        Article::factory()->create(['slug' => 'some-slug']);

        $article = Article::factory()->raw(['slug' => 'some-slug']);

        $url = route('api.v1.articles.create');

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()
            ->withData([
                'type' => 'articles',
                'attributes' => $article
            ])
            ->post($url)
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug');

        $this->assertDatabaseMissing('articles', $article);
    }

    /**
     * @test
     */
    public function it_has_required_content(): void
    {
        $article = Article::factory()->raw(['content' => '']);

        $url = route('api.v1.articles.create');

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()
            ->withData([
                'type' => 'articles',
                'attributes' => $article
            ])
            ->post($url)
            ->assertStatus(422)
            ->assertSee('data\/attributes\/content');

        $this->assertDatabaseMissing('articles', $article);
    }
}
