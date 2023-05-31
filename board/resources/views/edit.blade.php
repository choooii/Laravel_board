@extends('layout.layout')

@section('title', 'Edit')

@section('contents')
    <div>
        @if(count($errors) > 0)
            @foreach($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        @endif
    </div>
    <form action="{{route('boards.update', ['board' => $data->id])}}" method="post">
        @csrf
        @method('put')
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title" value="{{count($errors) > 0 ? old('title') : $data->title}}">
        <br>
        <label for="content">내용 : </label>
        <textarea name="content" id="content">{{count($errors) > 0 ? old('content') : $data->content}}</textarea>
        <br>
        <button type="button" onclick="location.href='{{route('boards.show', ['board' => $data->id])}}'">취소</button>
        <button type="submit">수정</button>
    </form>
@endsection