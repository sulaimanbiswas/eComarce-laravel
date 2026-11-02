@extends('layouts.yellow.master')

@section('title', 'Create an admin account')

@section('content')
    @component('partials.auth-shell', [
        'title' => 'Create an admin account',
        'portal' => 'Administration',
        'headline' => 'Your store workspace awaits.',
        'description' => 'Create your administrator account to get started with store management.',
        'subtitle' => 'Enter your details to create your account.',
        'benefits' => ['A connected store workspace', 'Products and orders together', 'Tools for daily operations'],
    ])
        @include('partials.auth-register-form', ['registerRoute' => 'admin.register', 'loginRoute' => 'admin.login', 'isReseller' => false, 'idPrefix' => 'admin-register'])
    @endcomponent
@endsection
