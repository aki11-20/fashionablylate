@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Gorditas:wght@400;700&family=Noto+Serif+JP:wght@200..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

@endsection

@section('header_actions')
<a class="header-btn" href="{{ route('login') }}">login</a>
@endsection

@section('content')
<div class="register-wrapper">
    <div class="register-form__heading">
        <p class="register-title">Register</p>
    </div>

    <div class="register-form__content">
        <form class="form" action="{{ route('register') }}" method="POST" novalidate>
            @csrf

            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">お名前</span>
                </div>
                <div class="form__group-content">
                    <input type="text" name="name" placeholder="例:山田　太郎" value="{{ old('name') }}" />
                    @error('name')
                    <div class="form__error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">メールアドレス</span>
                </div>
                <div class="form__group-content">
                    <input type="email" name="email" placeholder="例:test@example.com" value="{{ old('email') }}" />
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
                    <input type="password" name="password" placeholder="例:coachtech1106" />
                    @error('password')
                    <div class="form__error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="form__button">
                <button class="form__button-submit" type="submit">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection