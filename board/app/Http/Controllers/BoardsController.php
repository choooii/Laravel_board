<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;

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
