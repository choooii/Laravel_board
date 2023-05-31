@extends('layout.layout')

@section('title', 'Registration')

@section('contents')
    <h1>회원가입</h1>
    @include('layout.errorsValidate')
    <form action="{{route('users.registration.post')}}" method="post">
        @csrf
        <label for="name">NAME : </label>
        <input type="text" name="name" id="name">
        <br>
        <label for="email">EMAIL : </label>
        <input type="text" name="email" id="email">
        <br>
        <label for="password">PW : </label>
        <input type="text" name="password" id="password">
        <br>
        <label for="passwordChk">PW 확인 : </label>
        <input type="text" name="passwordChk" id="passwordChk">
        <br>
        <button type="button" onclick="location.href='{{route('users.login')}}'">취소</button>
        <button type="submit">회원가입</button>
    </form>
@endsection