<?php

namespace App\Http\Controllers\Api\v1;

use App\EtagTrait;
use App\Http\Controllers\Cacheable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ArticlesController as ParentController;
use App\Models\Article;
use App\Models\Tag;
use App\Transformers\ArticleTransformer;
use App\Transformers\ArticleTransformerBasic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticlesController extends ParentController implements Cacheable
{
    use EtagTrait;
    public function __construct()
    {
        parent::__construct();
        $this->middleware = [];
        $this->middleware('auth.basic.once', ['except'=>['index', 'show', 'tags']]);
    }
    //p.421 code 35 - 3
    protected function respondCollection(LengthAwarePaginator $articles, $cacheKey){
        //p.405 code 34-1
        //return $articles->toJson(JSON_PRETTY_PRINT);
        //p.411 code 34-7
        //return (new ArticleTransformerBasic)->withPagination($articles);
        //return json()->withPagination($articles, new ArticleTransformer);
        //p.421 code 35-3
        $reqEtag = request()->getETags();
        $genEtag = $this->etags($articles, $cacheKey);
        if(config('project.etag') and isset($reqEtag[0]) and $reqEtag[0] === $genEtag){
            return json()->notModified();
        }
        return json()->setHeaders(['Etag'=> $genEtag])->withPagination($articles, new ArticleTransformer);
    }

    protected function respondInstance(Article $article, $comments){

        //return (new ArticleTransformerBasic)->withItem($article);
        //p.422 code 35-4
        $cacheKey = cache_key('articles.'.$article->id);
        $reqEtag = request()->getETags();
        $genEtag = $this->etag($article, $cacheKey);
        if(config('project.etag') and isset($reqEtag[0]) and $reqEtag[0] === $genEtag){
            return json()->notModified();
        }
        return json()->setHeaders(['Etag'=> $genEtag])->withItem($article, new ArticleTransformer);
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

    /**
     * @param Article $article
     * @return JsonResponse
     */
    protected function respondUpdated(Article $article)
    {
        return json()->success('updated');
    }
}
