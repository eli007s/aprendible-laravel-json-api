<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterArticlesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_filter_articles_by_title(): void
    {
        Article::factory()->create([
            'title' => 'My Laravel Json API'
        ]);

        Article::factory()->create([
            'title' => 'Another Article'
        ]);

        $url = route('api.v1.articles.index', ['filter[title]' => 'Laravel']);

        $this->jsonApi()
            ->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('My Laravel Json API')
            ->assertDontSee('Another Article');
    }

    /**
     * @test
     */
    public function it_can_filter_articles_by_content(): void
    {
        Article::factory()->create([
            'content' => '<div>My Laravel Json API</div>'
        ]);

        Article::factory()->create([
            'content' => '<div>Another Article</div>'
        ]);

        $url = route('api.v1.articles.index', ['filter[content]' => 'Laravel']);

        $this->jsonApi()
            ->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('My Laravel Json API')
            ->assertDontSee('Another Article');
    }

    /**
     * @test
     */
    public function it_can_filter_articles_by_year(): void
    {
        Article::factory()->create([
            'title' => 'Article from 2020',
            'created_at' => now()->year(2020)
        ]);

        Article::factory()->create([
            'title' => 'Article from 2021',
            'created_at' => now()->year(2021)
        ]);

        $url = route('api.v1.articles.index', ['filter[year]' => 2020]);

        $this->jsonApi()
            ->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Article from 2020')
            ->assertDontSee('Article from 2021');
    }

    /**
     * @test
     */
    public function it_can_filter_articles_by_month(): void
    {
        Article::factory()->create([
            'title' => 'Article from February',
            'created_at' => now()->month(2)
        ]);

        Article::factory()->create([
            'title' => 'Article from January',
            'created_at' => now()->month(1)
        ]);

        $url = route('api.v1.articles.index', ['filter[month]' => 2]);

        $this->jsonApi()
            ->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Article from February')
            ->assertDontSee('Article from January');
    }

    /**
     * @test
     */
    public function it_cannot_filter_articles_by_unknown_filters(): void
    {
        Article::factory()->create();

        $url = route('api.v1.articles.index', ['filter[unknown]' => 'unknown']);

        $this->getJson($url)->assertStatus(400);
    }

    /**
     * @test
     */
    public function it_can_search_articles_by_title_and_content(): void
    {
        Article::factory()->create([
            'title' => 'Article from Aprendible',
            'content' => 'Content'
        ]);

        Article::factory()->create([
            'title' => 'Article from Aprendible',
            'content' => 'Content Aprendible...'
        ]);

        Article::factory()->create([
            'title' => 'Dummy Article',
            'content' => 'Dummy content'
        ]);

        $url = route('api.v1.articles.index', ['filter[search]' => 'Aprendible']);

        $this->jsonApi()
            ->get($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Article from Aprendible')
            ->assertDontSee('Another Article')
            ->assertDontSee('Dummy Article');
    }

    /**
     * @test
     */
    public function it_can_search_articles_by_title_and_content_with_multiple_terms(): void
    {
        Article::factory()->create([
            'title' => 'Article from Aprendible Laravel',
            'content' => 'Content'
        ]);

        Article::factory()->create([
            'title' => 'Another Article from Aprendible Laravel',
            'content' => 'Content Aprendible...'
        ]);

        Article::factory()->create([
            'title' => 'Dummy Article',
            'content' => 'Dummy content'
        ]);

        $url = route('api.v1.articles.index', ['filter[search]' => 'Aprendible Laravel']);

        $this->jsonApi()
            ->get($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Article from Aprendible')
            ->assertDontSee('Dummy Article');
    }
}
