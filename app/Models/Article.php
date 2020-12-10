<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = ['title','content'];

    public function user(){
        //Article은 user소속이다.
        return $this->belongsTo(User::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function attachments(){
        return $this->hasMany(Attachment::class);
    }
    //comments 테이블엔 일대 또는 일대일 관계처럼 article_id열이 없다.
    //다형적 관계에서는 hasMany() 대신 morphMany()를 이용한다.
    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }
    //글 목록에 댓글 수를 출력하기 위해 새로운 접근자를 하나 만든다.
    public function getCommentCountAttribute(){
        return (int)$this->comments()->count();
    }
}
