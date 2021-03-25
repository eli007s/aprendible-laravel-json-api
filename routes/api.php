<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/')->group(function() {
    Route::get('articles/{article}', [ArticleController::class, 'show'])->name('api.v1.articles.show');
});
