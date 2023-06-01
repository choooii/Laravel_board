<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator;

class ApiListController extends Controller
{
    function getlist($id) {
        // todo select의 경우 검색이 0건인 경우 아닌 경우 나눠서 처리
        $board = Boards::find($id);
        // return response()->json($board, 200);
        return $board;
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

    function putlist(Request $req, $id) {
        // 업데이트

        $arr = [
            'errorcode' => '0'
            ,'msg'      => ''
        ];

        // 유효성 체크
        $data =$req->only('title', 'content');
        $data['id'] = $id;

        $validator = Validator::make($data, [
            'id'         => 'required|integer'
            ,'title'     => 'required|between:3,30'
            ,'content'   => 'required|max:1000'
        ]);

        if ($validator->fails()) {
            $arr['errorcode'] = '1';
            $arr['msg'] = 'Validate Error';
            $arr['errmsg'] = $validator->errors()->all();
        } else {
            $boards = Boards::find($id);
            $boards->title = $req->title;
            $boards->content = $req->content;
            $boards->save();

            $arr['errorcode'] = '0';
            $arr['msg'] = 'Success';
        }
        return $arr;
    }

    function deletelist($id) {
        // 삭제

        $arr = [
            'errorcode' => '0'
            ,'msg'      => ''
        ];

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:boards|integer'
        ]);
        if ($validator->fails()) {
            $arr['errorcode'] = 'E01';
            $arr['msg'] = 'validation error';
        } else {
            $board = Boards::find($id);
            if($board) {
                $board->delete();
                $arr['msg'] = 'success';
                $arr['data'] = $id;
            } else {
                $arr['errorcode'] = 'E02';
                $arr['msg'] = 'already deleted';
                $arr['data'] = $id;
            }
        }

        return $arr;
    }
}