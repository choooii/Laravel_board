<?php

namespace Tests\Feature;

use App\Models\Boards;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BoardsTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    // 메소드명이 test로 시작해야 함
    public function test_index_게스트_리다이렉트()
    {
        $response = $this->get('/boards');

        $response->assertRedirect('/users/login');
    }

    public function test_index_유저인증() 
    {
        // 테스트용 유저 생성
        $user = new User([
            'email'     => 'aaa@aa.aaa'
            ,'name'     => '테스트'
            ,'password' => 'asdasd'
        ]);
        $user->save();

        $response = $this->actingAs($user)->get('/boards');
        $this->assertAuthenticatedAs($user);
    }

    public function test_index_유저인증_뷰반환() 
    {
        // 테스트용 유저 생성
        $user = new User([
            'email'     => 'aaa@aa.aaa'
            ,'name'     => '테스트'
            ,'password' => 'asdasd'
        ]);
        $user->save();

        $response = $this->actingAs($user)->get('/boards');
        $response->assertViewIs('list');
    }

    public function test_index_유저인증_뷰반환_데이터반환() 
    {
        // 테스트용 유저 생성
        $user = new User([
            'email'     => 'aaa@aa.aaa'
            ,'name'     => '테스트'
            ,'password' => 'asdasd'
        ]);
        $user->save();

        $board1 = new Boards([
            'title' => 'test1'
            ,'content' => 'content1'
        ]);
        $board1->save();
        $board2 = new Boards([
            'title' => 'test2'
            ,'content' => 'content2'
        ]);
        $board2->save();

        $response = $this->actingAs($user)->get('/boards');

        $response->assertViewHas('data');
        $response->assertSee('test1');
        $response->assertSee('test2');
    }

    // 테스트 실행 명령어: php artisan test
}