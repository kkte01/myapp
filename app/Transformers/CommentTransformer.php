<?php


namespace App\Transformers;

use App\Models\Comment;
use Appkr\Api\TransformerAbstract;
class CommentTransformer extends TransformerAbstract
{
    public function transform(Comment $comment)
    {
        //$obfuscatedId = optimus($comment->id);

        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'content_html' => markdown($comment->content),
            'author' => [
                'name' => $comment->user->name,
                'email' => $comment->user->email,
                'avatar' => 'http:' . gravatar_profile_url($comment->user->email),
            ],
            'created' => $comment->created_at->toIso8601String(),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('api.v1.comments.show', $comment->id),
                ],
            ],
        ];
    }
}
