@include('partials.auth-notices')
<form method="POST" action="{{ route($registerRoute) }}" class="store-auth__form">
    @csrf
    <div class="store-auth__fields">
        @include('partials.auth-field', ['name' => 'name', 'label' => $isReseller ? 'Reseller name' : 'Full name', 'autocomplete' => 'name', 'placeholder' => 'Enter your name'])
        @if ($isReseller)
            @include('partials.auth-field', ['name' => 'shop_name', 'label' => 'Shop name', 'placeholder' => 'Your shop or business name'])
        @endif
        @include('partials.auth-field', ['name' => 'email', 'label' => 'Email address', 'type' => 'email', 'autocomplete' => 'email', 'wide' => true, 'placeholder' => 'you@example.com'])
        @if ($isReseller)
            @include('partials.auth-field', ['name' => 'phone_number', 'label' => 'Phone number', 'type' => 'tel', 'autocomplete' => 'tel', 'placeholder' => 'Enter your phone number'])
            @include('partials.auth-field', ['name' => 'bkash_number', 'label' => 'bKash number', 'type' => 'tel', 'placeholder' => 'Enter your bKash number'])
        @endif
        @include('partials.auth-field', ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'autocomplete' => 'new-password', 'placeholder' => 'Create a password'])
        @include('partials.auth-field', ['name' => 'password_confirmation', 'label' => 'Confirm password', 'type' => 'password', 'autocomplete' => 'new-password', 'placeholder' => 'Re-enter your password'])
        @if ($isReseller)
            @include('partials.auth-field', ['name' => 'referral_code', 'label' => 'Referral code', 'optional' => true, 'wide' => true, 'value' => old('referral_code', request('ref', request('referral_code', session('ref')))), 'placeholder' => 'e.g. HK123456', 'hint' => 'Invited by another reseller? Enter their referral code.'])
        @endif
    </div>
    <button type="submit" class="store-auth__submit">Create account <span aria-hidden="true">&rarr;</span></button>
</form>
<p class="store-auth__switch">Already have an account? <a class="store-auth__link" href="{{ route($loginRoute) }}">Sign in</a></p>
