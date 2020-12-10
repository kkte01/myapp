<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;
    //연결할 테이블 명
    protected $table = 'memos';
    //content 에 대량할당 선언
    protected $fillable = ['content'];
}
