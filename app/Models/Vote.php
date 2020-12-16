<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['user_id', 'comment_id', 'up', 'down', 'voted_at'];
    protected $dates =['voted_at'];

    //코멘트와 다대일 관계 형성
    public function comment(){
        return $this->belongsTo(Comment::class);
    }

    //유저와 다대일 관계 설정
    public function user(){
        return $this->belongsTo(User::class);
    }

    //28.4.6. 투표 요청처리
    public function setUpAttribute($value){
        $this->attributes['up'] = $value ? 1: null;
    }

    public function setDownAttribute($value){
        $this->attributes['down'] = $value ? 1: null;
    }
}
