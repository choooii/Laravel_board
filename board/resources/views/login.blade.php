@extends('layout.layout')

@section('title', 'Login')

@section('contents')
    <h1>로그인</h1>
    @include('layout.errorsValidate')
    <div>{{session()->has('success') ? session('success') : ""}}</div>
    <form action="{{route('users.login.post')}}" method="post">
        @csrf
        <label for="email">ID : </label>
        <input type="text" name="email" id="email">
        <br>
        <label for="password">PW : </label>
        <input type="password" name="password" id="password">
        <br>
        <button type="button" onclick="location.href='{{route('users.registration')}}'">회원가입</button>
        <button type="submit">로그인</button>
    </form>
@endsection