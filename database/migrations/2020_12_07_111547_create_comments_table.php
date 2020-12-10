<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            //User 모델과 일대다 관께를 담기 위한 열이다.
            $table->unsignedBigInteger('user_id')->index();
            //댓글의 댓글을 위한 재귀적 일대 관계를 표현한다. 이 열에 값이 없으면 최상위 댓글이란 뜻
            //외래키 제약사항을 연결하는 구문에서 ondelte 사용 X
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('commentable_type');
            $table->unsignedInteger('commentable_id');
            $table->text('content');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table){
            //역의 마이그레이션을 정의할 때 외래 키 제약이 걸려있으면 롤백시 문제가 발생될수도 있기에 외래키를 먼저 제거
           $table->dropForeign('comments_parent_id_foreign');
           $table->dropForeign('comments_user_id_foreign');
        });
        Schema::dropIfExists('comments');
    }
}
