@extends('layouts.yellow.master')

@section('title', 'Admin sign in')

@section('content')
    @component('partials.auth-shell', [
        'title' => 'Admin sign in',
        'portal' => 'Administration',
        'headline' => 'Everything you need. One place.',
        'description' => 'Access your store workspace to manage products, orders and daily operations.',
        'subtitle' => 'Sign in with your administrator credentials.',
        'benefits' => ['Manage your storefront', 'Stay on top of orders', 'Keep your team connected'],
    ])
        @include('partials.auth-login-form', ['loginRoute' => 'admin.login', 'passwordRoute' => 'admin.password.request', 'portalType' => 'admin', 'idPrefix' => 'admin-login'])
    @endcomponent
@endsection
