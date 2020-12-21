<?php


namespace App\Transformers;

use App\Models\Article;
use Appkr\Api\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\ParamBag;
class ArticleTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include using url query string.
     * e.g. collection case -> ?include=comments:limit(5|1):order(created_at|desc)
     *      item case       -> ?include=author
     *
     * @var  array
     */
    protected $availableIncludes = [
        'comments',
    ];
    public function transform(Article $article){
        $obfuscatedId = optimus($article->id);
        return[
            'id' => $obfuscatedId,
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
            'created' => $article->created_at->toString(),
            'attachments' => (int) $article->attachments->count(),
            'comments' => (int) $article->comments->count(),
            'links'=>[
                [
                    'rel' => 'self',
                    'href' => route('apiv1articles.show', $obfuscatedId)
                ],
                [
                    'rel' => 'api.v1.articles.attachments.index',
                    'href' => route('apiv1articles.attachments.index', $obfuscatedId)
                ],
                [
                    'rel' => 'api.vi.articles.comments.index',
                    'href' => route('apiv1articles.comments.index', $obfuscatedId),
                ],
            ],
        ];
    }

    /**
     * Include comments.
     *
     * @param Article $article
     * @param ParamBag|null $params
     * @return  Collection
     */
    public function includeComments(Article $article, ParamBag $params = null)
    {
        $transformer = new CommentTransformer($params);

        $parsed = $transformer->getParsedParams();

        $comments = $article->comments()
            ->limit($parsed['limit'])
            ->offset($parsed['offset'])
            ->orderBy($parsed['sort'], $parsed['order'])
            ->get();

        return $this->collection($comments, $transformer);
    }
}
