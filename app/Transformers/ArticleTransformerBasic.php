<?php


namespace App\Transformers;
use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleTransformerBasic
{
    public function withPagination(LengthAwarePaginator $articles){
        $payload=[
            'total' => (int) $articles->total(),
            'per_page' => (int) $articles->perPage(),
            'current_page' => (int) $articles->currentPage(),
            'last_page'=> (int) $articles->lastPage(),
            'next_page' => $articles->nextPageUrl(),
            'prev_page' => $articles->previousPageUrl(),
            'data' => array_map([$this, 'transform'], $articles->items())
        ];
        return response()->json($payload, 200, [], JSON_PRETTY_PRINT);
    }

    public function withItem(Article $article){
        return response()->json($this->transform($article), 200, [], JSON_PRETTY_PRINT);
    }

    public function transform(Article $article){
        return[
            'id' => (int) $article->id,
            'title' => $article->title,
            'content'=> $article->content,
            'content_html' => markdown($article->content),
            'author' =>[
                'name'=> $article->user->name,
                'email' => $article->user->email,
                'avatar' => 'http:'.gravatar_profile_url($article->user->email),
            ],
            'tags' => $article->tags->pluck('slug'),
            'view_count' => (int) $article->view_count,
            'created' => $article->created_at->toIs8610String(),
            'attachments' => (int) $article->attachments->count(),
            'comments' => (int) $article->comments->count(),
            'links'=>[
                [
                    'rel' => 'self',
                    'href' => route('apiviarticles.show', $article->id)
                ],
                [
                    'rel' => 'api.v1.articles.attachments.index',
                    'href' => route('apiv1articles.attachments.index', $article->id)
                ],
                [
                  'rel' => 'api.vi.articles.comments.index',
                  'href' => route('apiv1articles.comments.index', $article->id),
                ],
            ],
        ];
    }
}
