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
        $articles1 = Article::factory()->create(['title' => 'C Title']);
        $articles2 = Article::factory()->create(['title' => 'A Title']);
        $articles3 = Article::factory()->create(['title' => 'B Title']);

        $url = route('api.v1.articles.index', ['sort' => 'title']);
    }
}
