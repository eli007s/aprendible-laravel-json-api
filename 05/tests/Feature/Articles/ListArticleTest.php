<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_fetch_single_articles(): void
    {
        $article = Article::factory()->create()->first();

        $response = $this->getJson('/api/v1/articles/' . $article->getRouteKey());

        $response->assertJson([
            'data' => [
                'type' => 'articles',
                'id' => (string)$article->getRouteKey(),
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ]
        ]);
    }


    /**
     * @test
     */
    public function it_can_fetch_all_articles(): void
    {
        $number_of_articles = 1;

        $articles = Article::factory()->count($number_of_articles)->create();

        $response = $this->getJson(route('api.v1.articles.index'));

        $articles_json = [];

        foreach ($articles as $k => $article) {
            $articles_json[] = [
                'type' => 'articles',
                'id' => (string)$article->getRouteKey(),
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ];
        }

        $response->assertJsonFragment([
            'data' => $articles_json
        ]);
    }
}
