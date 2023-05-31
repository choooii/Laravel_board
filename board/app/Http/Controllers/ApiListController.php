<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;

class ApiListController extends Controller
{
    function getlist($id) {
        $board = Boards::find($id);
        return response()->json($board, 200);
    }

    function postlist(Request $req) {
        // todo 유저인증 절차
        // 토큰 저장용 데이터베이스 테이블이 따로 필요

        // todo 유효성 체크 필요


        $boards = new Boards([
            'title'     => $req->title
            ,'content'  => $req->content
        ]);
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id', 'title');

        return $arr; // 라라벨이 자동으로 배열을 json 형태로 변경하여 리턴, 헤더에 들어가는 세팅을 자동으로 해줌

        
    }
}