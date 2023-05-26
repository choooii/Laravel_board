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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('title', 30);
            $table->string('content', 2000);
            $table->integer('hits');
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
        Schema::dropIfExists('boards');
    }
};
