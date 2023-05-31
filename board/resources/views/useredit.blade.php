@extends('layout.layout')

@section('title', 'User Edit')

@section('contents')
    <h1>회원정보 수정</h1>
    @include('layout.errorsValidate')
    <form action="{{route('users.edit.post')}}" method="post">
        @csrf
        <label for="name">NAME : </label>
        <input type="text" name="name" id="name" value="{{count($errors) > 0 ? old('name') : $data->name}}">
        <br>
        <label>EMAIL : </label>
        <span>{{$data->email}}</span>
        <br>
        <label for="password">PW : </label>
        <input type="text" name="password" id="password">
        <br>
        <label for="passwordChk">PW확인 : </label>
        <input type="text" name="passwordChk" id="passwordChk">
        <br>
        <button type="button" onclick="window.history.back()">취소</button>
        <button type="button" onclick="location.href='{{route('users.withdraw')}}'">회원탈퇴</button>
        <button type="submit">수정완료</button>
    </form>
@endsection