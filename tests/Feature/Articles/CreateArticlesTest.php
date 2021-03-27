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
            ->content()
            ->post($url);
    }
}
