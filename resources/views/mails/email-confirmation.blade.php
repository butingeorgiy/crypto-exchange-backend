@extends('layouts.mail')

@section('title', 'Email Confirmation')

@section('body')
    <p>Код подтверждения: {{ $code }}</p>
    <br />
    <p>Никому не сообщайте данный код!</p>
@endsection