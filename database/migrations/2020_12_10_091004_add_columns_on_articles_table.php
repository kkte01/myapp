<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //컬럼을 추가하는 마이그레이션
        Schema::table('articles', function (Blueprint $table){
            //댓글이 달렸을 경우 글 작성자에게 이메일 알림을 보낼지 말지 결정하기 위한 플래그다
            //기본값은 1 이고 UI에서 사용자가 명시적으로 끄지 않으면 알림메일을 보낸다.
           $table->boolean('notification')->default(1);
           //이 열은 글의 조회수를 담는 열이다. 이렇게 컬럼을 추가를 했으면 모델을 $fillable에 추가해주어야 한다.
            //$guarded 모든속성을 대량할당 할경우 상관없음
           $table->tinyInteger('view_count')->default(0);

           if(config('database.default') == 'mysql'){
               //일반적인 쿼리 구문을 실행할 때 이용한다. statement
               DB::statement('ALTER TABLE articles ADD FULLTEXT search (title, content)');
           }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('articles',function(Blueprint $table){
            //메서드 인자를 배열로 사용가능하다.
            //배열로쓰면 편할것 같은곳에 배열을 써보면 문제없이 잘 작동한다.
            $table->dropColumn(['notification', 'view_count']);
        });
    }
}
