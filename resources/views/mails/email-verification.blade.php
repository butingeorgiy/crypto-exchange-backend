@extends('layouts.mail')

@section('title', 'Email Verification')

@section('styles')
    <style>
        .button {
            width: 100%;
            position: relative;
            padding: 1em;
            background: #3B82F6;
            text-align: center;
            color: #FFFFFF !important;
            border-radius: 0.5em;
            font-size: 1.2em;
        }
    </style>
@endsection

@section('body')
    <h3>Вы успешно создали аккаунт Coin Exchange!</h3>

    <p>Чтобы получить доступ к своему аккаунту, вам необходимо подтвердить E-mail адрес.</p>

    <div class="button-container">
        <a href="{{ $verificationUrl }}" class="button">Подтвердить</a>
    </div>
@endsection