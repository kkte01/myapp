<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    // tinker에서 엘로퀸트로 새로운 레코드를 만들경우 save()를 쓰게되는데 이 함수는 컬럼에 수정날짜(update_at),작성날짜(created_at)이 있다고 가정하고
    // 저장할 때 현재의 타임스탬프 값을 할당하기에 없을 경우는 false로 기능을 꺼야 한다.
    // 왜 public $timestamps를 쓰는것인가 부모클래스의 프로퍼티를 오버라이딩 해야해서
    public $timestamps = false;
    //대량할당 취약점에서 보호하기
    protected $fillable = ['email', 'password'];
}
