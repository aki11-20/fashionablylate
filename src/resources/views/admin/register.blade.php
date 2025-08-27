@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('header_actions')
<a class="header-btn" href="{{ route('login') }}">login</a>
@endsection

@section('content')
<div class="register-form__content">
    <div class="register-form__heading">
        <h2>Register</h2>
    </div>
    <form class="form" action="{{ route('register.store') }}" method="POST">
        @csrf

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">お名前</span>
            </div>
            <div class="form__group-content">
                <input type="text" name="name" value="{{ old('name') }}" />
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
                <input type="email" name="email" value="{{ old('email') }}" />
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
                <input type="password" name="password" />
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
@endsection
