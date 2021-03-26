<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SortArticlesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_sort_articles_by_titles_asc(): void
    {
        Article::factory()->create(['title' => 'C Title']);
        Article::factory()->create(['title' => 'A Title']);
        Article::factory()->create(['title' => 'B Title']);

        $url = route('api.v1.articles.index', ['sort' => 'title']);

        $this->getJson($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title'
        ]);
    }

    /**
     * @test
     */
    public function it_can_sort_articles_by_title_desc(): void
    {
        Article::factory()->create(['title' => 'C Title']);
        Article::factory()->create(['title' => 'A Title']);
        Article::factory()->create(['title' => 'B Title']);

        $url = route('api.v1.articles.index', ['sort' => '-title,content']);

        $this->getJson($url)->assertSeeInOrder([
            'C Title',
            'B Title',
            'A Title'
        ]);
    }

    /**
     * @test
     */
    public function it_can_sort_articles_by_title_and_content(): void
    {
        Article::factory()->create(['title' => 'C Title', 'content' => 'B Content']);
        Article::factory()->create(['title' => 'A Title', 'content' => 'C Content']);
        Article::factory()->create(['title' => 'B Title', 'content' => 'D Content']);

        $url = route('api.v1.articles.index', ['sort' => 'title,-content']);

        $this->getJson($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title'
        ]);

        $url = route('api.v1.articles.index', ['sort' => '-content,title']);

        $this->getJson($url)->assertSeeInOrder([
            'D Content',
            'C Content',
            'B Content'
        ]);
    }
}
