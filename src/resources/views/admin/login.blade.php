@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Gorditas:wght@400;700&family=Noto+Serif+JP:wght@200..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

@endsection

@section('header_actions')
<a class="header-btn" href="{{ route('register.form') }}">register</a>
@endsection

@section('content')
<div class="login-wrapper">
    <div class="login-form__heading">
        <p class="login-title">Login</p>
    </div>
    <div class="login-form__content">
        <form class="form" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">メールアドレス</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="email" name="email" placeholder="例:test@example.com" value="{{ old('email') }}" />
                    </div>
                    @error('email')
                    <div class="form__error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">パスワード</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="password" name="password" placeholder="例:coachtech1106" />
                    </div>
                    @error('password')
                    <div class="form__error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">ログイン</button>
            </div>
        </form>
    </div>
</div>
@endsection