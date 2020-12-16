<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Comment extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['commentable_type','commentable_id','user_id','parent_id','content'];
    protected $with = ['user', 'votes'];
    //Comment::up_count 와 Comment::down_count 는 모델에 없던 속성이다. 서버 측 코드에서 App\Comment::find(1)->up_count 와 같이
    //속성값을 쉽게 조회할 수 있다. 그런데 App\Comment::find(1)->toArray() 또는 toJson()으로 출력할 때는 원래 모델에 없던 속성값은 출력되지 않는다.
    //이때 엘로퀀트의 $appends 프로버티를 이용할 수 있다.
    protected $appends = ['up_count','down_count'];
    protected $dates = ['deleted_at'];
    public function user(){
        return $this->belongsTo(User::class);
    }
    //다형적 관계 morphTo로 연결
    public function commentable(){
        return $this->morphTo();
    }
    //댓글끼리 재귀적인 일대다 관계를 표현한다. 현재 댓글 인스턴스에 여러 개의 답글이 있는 상황이며, 둘 사이의 관계를 연결하는 참조 키는
    //parent_id 이다. 역순으로 쓸 것 이므로 latest() 이용
    public function replies(){
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }
    //replies의 반대 관계를 표현한 함수 현재 댓글 인스턴스가 최상위 댓글이 아니라 부모댓글에 종속된 경우다.
    //부모의 댓글 인스턴스를 조회살 수 있다.
    public function parent(){
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }

    //코멘트와 투표는 일대다 관계
    public function votes(){

        return $this->hasMany(Vote::class);
    }

    public function getUpCountAttribute(){
        //$this->>votes()까지는 엘로퀀트 컬렉션을 반환한다. sum()은 컬렉션에서 쓸 수 있는 메서드로 인자로 받은 속성값의 합을 반환한다.
        //count(), max(), avg()등도 있다.
        return (int) $this->votes()->sum('up');
    }

    public function getDownCountAttribute(){
        return (int) $this->votes()->sum('down');
    }
}
