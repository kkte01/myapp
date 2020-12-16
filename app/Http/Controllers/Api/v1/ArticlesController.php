<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ArticlesController as ParentController;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticlesController extends ParentController
{
    //
    public function __construct()
    {
    }

    protected function respondCollection(LengthAwarePaginator $articles){

        return $articles->toJson(JSON_PRETTY_PRINT);
    }

    protected function respondCreate(Article $article)
    {
        return response()->json(
            ['success' => 'created'],
            201,
            ['Location' => route('apiv1articles.show', $article->id)],
            JSON_PRETTY_PRINT
        );
    }

    public function tags(){
        return Tag::all();
    }
}
