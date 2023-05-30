<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password'); // 라라벨에서 비밀번호는 최소 60자 이상
            $table->string('name');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken(); // 로그인 유지하기 기능
            $table->timestamps();
            $table->softDeletes(); // 엘로퀀트 사용시 플래그 관리를 하지 않아도 됨
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
