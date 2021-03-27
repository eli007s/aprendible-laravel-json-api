<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_all_article(): void
    {
        $number_of_articles = 1;

        $articles = Article::factory()->count($number_of_articles)->create();

        $url = route('api.v1.articles.index');

        $response = $this->jsonApi()->get($url);

        $articles_json = [];

        foreach ($articles as $article) {
            $articles_json[] = [
                'type' => 'articles',
                'id' => (string)$article->getRouteKey(),
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content,
                    'created-at' => $article->created_at,
                    'updated-at' => $article->updated_at,
                ],
                'links' => [
                    'self' => route('api.v1.articles.read', $article)
                ]
            ];
        }

        $response->assertJsonFragment([
            'data' => $articles_json
        ]);
    }

    /** @test */
    public function can_fetch_single_article(): void
    {
        $article = Article::factory()->create()->first();

        $url = route('api.v1.articles.read', $article);

        $response = $this->jsonApi()->get($url);

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content,
                    'created-at' => $article->created_at,
                    'updated-at' => $article->updated_at,
                ],
                'links' => [
                    'self' => route('api.v1.articles.read', $article)
                ]
            ]
        ]);
    }
}
