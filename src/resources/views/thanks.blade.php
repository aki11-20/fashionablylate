@extends('layouts.app')

@php($hideHeader = true)

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}" />
<link href="https://fonts.googleapis.com/css2?family=Gorditas:wght@400;700&family=Noto+Serif+JP:wght@200..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

@endsection

@section('content')
<div class="thanks__content">
    <p class="thanks__text">Thank you</p>
    <div class="thanks__overlay">
        <div class="thanks__heading">
            <h3>お問い合わせありがとうございました</h3>
        </div>
        <div class="form__button">
            <a class="form__button-submit" href="{{ url('/') }}">HOME</a>
        </div>
    </div>
</div>
@endsection