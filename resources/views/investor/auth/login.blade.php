@extends('layouts.yellow.master')

@section('title', 'Investor sign in')

@section('content')
    @component('partials.auth-shell', [
        'title' => 'Investor sign in',
        'portal' => 'Investor portal',
        'headline' => 'Stay close to your investments.',
        'description' => 'Access your investor workspace and keep track of your account in one place.',
        'subtitle' => 'Use the account details provided by administration.',
        'benefits' => ['Your dedicated investor workspace', 'Account information in one place', 'Access for registered investors'],
    ])
        @include('partials.auth-login-form', ['loginRoute' => 'investor.login.submit', 'portalType' => 'investor', 'idPrefix' => 'investor-login', 'buttonLabel' => 'Sign in to investor portal'])
        <p class="store-auth__switch">Investor accounts are created and managed by administration.</p>
    @endcomponent
@endsection
