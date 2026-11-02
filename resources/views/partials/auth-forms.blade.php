@include('partials.auth-styles')

<section class="store-auth store-auth--inline" aria-label="Reseller account">
    <div class="store-auth__wrap">
        <div class="store-auth__card">
            <div class="store-auth__panel">
                <span class="store-auth__eyebrow">Your reseller account</span>
                <h2>Welcome back</h2>
                <p class="store-auth__intro">Sign in to manage your shop and pick up where you left off.</p>
                @include('partials.auth-login-form', ['loginRoute' => 'user.login', 'passwordRoute' => 'user.password.request', 'idPrefix' => 'home-login'])
            </div>
            <div class="store-auth__panel">
                <span class="store-auth__eyebrow">Start something new</span>
                <h2>Become a reseller</h2>
                <p class="store-auth__intro">Create your account and take the next step for your business.</p>
                @include('partials.auth-register-form', ['registerRoute' => 'user.register', 'loginRoute' => 'user.login', 'isReseller' => true, 'idPrefix' => 'home-register'])
            </div>
        </div>
    </div>
</section>
