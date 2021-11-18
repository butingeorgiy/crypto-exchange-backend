@extends('layouts.mail')

@section('title', 'Credentials Updating')

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
    <h3>Запрос на изменения данных Безопасности!</h3>

    <p>С вашего аккаунт был создан запрос на изменение данных Безопасности.
       Если вы хотите его применить, то перейдите по ссылке ниже.
       Если же вы ничего не изменяли, то проигнорируйте это письмо.</p>

    <div class="button-container">
        <a href="{{ $confirmationUrl }}" class="button">Подтвердить</a>
    </div>
@endsection