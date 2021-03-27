<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateArticlesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_create_articles(): void
    {
        $article = Article::factory()->raw();

        $url = route('api.v1.articles.create');

        $this->assertDatabaseMissing('articles', $article);

        $this->jsonApi()
            ->content([
                'data' => [
                    'type' => 'articles',
                    'attributes' => $article
                ]
            ])
            ->post($url)
            ->assertCreated();

        $this->assertDatabaseHas('articles', $article);
    }

    /**
     * @test
     */
    public function it_has_required_title(): void
    {
        $article = Article::factory()->raw(['title' => '']);

        $url = route('api.v1.articles.create');

        $this->jsonApi()
            ->content([
                'data' => [
                    'type' => 'articles',
                    'attributes' => $article
                ]
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

        $this->jsonApi()
            ->content([
                'data' => [
                    'type' => 'articles',
                    'attributes' => $article
                ]
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

        $this->jsonApi()
            ->content([
                'data' => [
                    'type' => 'articles',
                    'attributes' => $article
                ]
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

        $this->jsonApi()
            ->content([
                'data' => [
                    'type' => 'articles',
                    'attributes' => $article
                ]
            ])
            ->post($url)
            ->assertStatus(422)
            ->assertSee('data\/attributes\/content');

        $this->assertDatabaseMissing('articles', $article);
    }
}
