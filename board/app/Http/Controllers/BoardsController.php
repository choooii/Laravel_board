<?php
/*****************************************************
 * 프로젝트명   : laravel_board
 * 디렉토리     : Controllers
 * 파일명       : BoardsController.php
 * 이력         : v001 0526 AR.Choe new
 *                v002 0530 AR.Choe 유효성체크 추가
 *****************************************************/


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator; // v002 add

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Boards::select(['id', 'title', 'hits', 'created_at', 'updated_at'])
                        ->orderBy('hits', 'desc')
                        ->get();
        return view('list')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // *** v002 add start
        $req->validate([ // 에러가 발생하면 view에 자동으로 errors 변수 선언
            'title' => 'required|between:3,30'
            ,'content' => 'required|max:1000'
        ]);
        // *** v002 add end

        // 인서트는 새로운 객체를 생성해서 저장
        $boards = new Boards([
            'title'     => $req->input('title')
            ,'content'  => $req->input('content')
        ]);
        $boards->save();
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id);
        $boards->hits++;
        $boards->save();

        return view('detail')->with('data', Boards::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('edit')->with('data', Boards::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        // *** v002 add start
        // id를 리퀘스트 객체에 merge
        $arr = ['id' => $id];
        // $req->merge($arr);
        $req->request->add($arr);

        // $req->validate([
        //     'id'         => 'required|integer'
        //     ,'title'     => 'required|between:3,30'
        //     ,'content'   => 'required|max:1000'
        // ]);

        // * id만 따로 체크
        // $validator = Validator::make(['id' => $id], [
        //     'id' => 'required|exists:boards|integer'
        // ]);
        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        // *** v002 add end

        // *** 참고: 다른 유효성 검사 방법 ***
        $validator = Validator::make($req->only('id', 'title', 'content'), [
            'id'         => 'required|integer'
            ,'title'     => 'required|between:3,30'
            ,'content'   => 'required|max:1000'
        ]);
        if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($req->only('title', 'content')); // 필요한 값만 세션에 전달 가능
            }
        // ***********************************

        $boards = Boards::find($id);
        $boards->title = $req->title;
        $boards->content = $req->content;
        $boards->save();

        // url이 바뀔 때는 꼭 redirect
        return redirect()->route('boards.show', ['board' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // destroy() : pk를 받아서 처리
        Boards::destroy($id);

        // delete() : 인수가 없기 때문에 객체를 받아서 처리
        // Boards::find($id)->delete();

        // todo 에러처리, 트랜잭션 처리 해야함
        return redirect('/boards');
    }
}
