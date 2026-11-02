@include('partials.auth-notices')
<form method="POST" action="{{ route($loginRoute) }}" class="store-auth__form">
    @csrf
    @include('partials.auth-field', ['name' => 'login', 'label' => 'Mobile or email', 'autocomplete' => 'username', 'placeholder' => 'Enter your mobile or email'])
    @include('partials.auth-field', ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'autocomplete' => 'current-password', 'placeholder' => 'Enter your password'])
    @if (($portalType ?? 'user') === 'admin' && session()->has('token:sent'))
        @include('partials.auth-field', ['name' => 'otp', 'label' => 'Verification code', 'autocomplete' => 'one-time-code', 'placeholder' => 'Enter the OTP sent to your phone'])
    @endif
    <div class="store-auth__options">
        <label class="store-auth__remember" for="{{ $idPrefix }}-remember">
            <input type="checkbox" name="remember" id="{{ $idPrefix }}-remember" @checked(old('remember'))> Remember me
        </label>
        @if (!empty($passwordRoute) && Route::has($passwordRoute))
            <a class="store-auth__link" href="{{ route($passwordRoute) }}">Forgot password?</a>
        @endif
    </div>
    <button type="submit" class="store-auth__submit">{{ $buttonLabel ?? 'Sign in' }} <span aria-hidden="true">&rarr;</span></button>
</form>
@if (!empty($registerRoute) && Route::has($registerRoute))
    <p class="store-auth__switch">New here? <a class="store-auth__link" href="{{ route($registerRoute) }}">Create an account</a></p>
@endif
