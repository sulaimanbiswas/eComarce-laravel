@extends('layouts.yellow.master')

@section('title', 'Create your account')

@section('content')
    @component('partials.auth-shell', [
        'title' => 'Create your account',
        'portal' => 'Become a reseller',
        'headline' => 'A fresh start for your business.',
        'description' => 'Join our reseller community and take the next step with your own shop.',
        'subtitle' => 'A few details, and you are ready to get started.',
        'benefits' => ['Find products for your shop', 'Keep your orders organized', 'Build your reseller business'],
    ])
        @include('partials.auth-register-form', ['registerRoute' => 'user.register', 'loginRoute' => 'user.login', 'isReseller' => true, 'idPrefix' => 'user-register'])
    @endcomponent
@endsection
