<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfirmCodeColumnOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //사용자 등록 폼에서 사용자가 등록한 이메일 주소로 활성화 코드를 포함한 가입확인메일을 보낸다.
        //메일에는 가입 확인을 위해 우리 서비스로 다시 들어오는 URL 포함 URL에 포함된 활성화코드로 사용자를 찾아서 사용자 정보를 업데이트한다.
        //이 기능을 구현을 위해 테이블에 활성화 코드(confirm_code)와 가입확인 여부(activated) 추가필요
        Schema::table('users', function (Blueprint $table){
            $table->string('confirm_code', 60)->nullable();
            $table->boolean('activated')->default(0);
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
    }
}
