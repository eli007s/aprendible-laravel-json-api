<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Article::all()->map(function($article) {
                return [
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
            })
        ]);
    }

    public function show(Article $article): \Illuminate\Http\JsonResponse
    {
        return response()->json([
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
