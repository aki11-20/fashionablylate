@extends('layouts.app')

@php($hideHeader = true)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}" />
@endsection

@section('content')
    <div class="thanks__content">
        <p class="thanks__text">Thank you</p>
        <div class="thanks__heading">
            <h3>お問い合わせありがとうございました</h3>
        </div>
        <div class="form__button">
            <a class="form__button-submit" href="{{ url('/') }}">HOME</a>
        </div>
    </div>
@endsection
