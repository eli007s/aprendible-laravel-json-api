<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Tests\TestCase;

class ListArticleTest extends TestCase
{
    /** @test */
    public function can_fetch_all_article(): void
    {
        $articles = Article::factory()->count(1)->create();

        $response = $this->getJson(route('api.v1.articles.index'));

        $data = [];

        foreach ($articles as $article) {
            $data[] = [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
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

        $response->assertJson([
            'data' => $data
        ]);
    }

    /** @test */
    public function can_fetch_single_article(): void
    {
        $article = Article::factory()->create()->first();

        $response = $this->getJson(route('api.v1.articles.show', $article));

        $response->assertJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
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
}
