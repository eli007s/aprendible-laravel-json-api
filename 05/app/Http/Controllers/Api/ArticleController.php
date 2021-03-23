<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => Article::all()->map(function($article) {
                return [
                    'type' => 'articles',
                    'id' => (string)$article->getRouteKey(),
                    'attributes' => [
                        'title' => $article->title,
                        'slug' => $article->slug,
                        'content' => $article->content
                    ],
                    'links' => [
                        'self' => url('/api/v1/articles/' . $article->getRouteKey())
                    ]
                ];
            })
        ]);
    }

    public function show(Article $article): array
    {
        return [
            'data' => [
                'type' => 'articles',
                'id' => (string)$article->getRouteKey(),
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content
                ],
                'links' => [
                    'self' => url('/api/v1/articles/' . $article->getRouteKey())
                ]
            ]
        ];
    }
}
