@extends('layouts.yellow.master')

@section('title', 'Welcome back')

@section('content')
    @component('partials.auth-shell', [
        'title' => 'Welcome back',
        'portal' => 'Reseller account',
        'headline' => 'Your next chapter starts here.',
        'description' => 'Sign in to discover products, manage your orders and keep your business moving.',
        'subtitle' => 'Enter your details to sign in to your account.',
        'benefits' => ['Explore the product catalog', 'Manage orders in one place', 'Stay connected to your business'],
    ])
        @include('partials.auth-login-form', ['loginRoute' => 'user.login', 'registerRoute' => 'user.register', 'passwordRoute' => 'user.password.request', 'idPrefix' => 'user-login'])
    @endcomponent
@endsection
