@include('partials.auth-styles')

<section class="store-auth" aria-label="{{ $title }}">
    <div class="store-auth__wrap">
        <a class="store-auth__back" href="{{ route('/') }}"><span aria-hidden="true">&larr;</span> Back to home</a>
        <div class="store-auth__card">
            <aside class="store-auth__story">
                <div class="store-auth__brand">{{ setting('company')->name ?? config('app.name') }}</div>
                <div>
                    <span class="store-auth__eyebrow">{{ $portal }}</span>
                    <h2>{{ $headline }}</h2>
                    <p>{{ $description }}</p>
                </div>
                <ul class="store-auth__benefits">
                    @foreach ($benefits as $benefit)
                        <li>{{ $benefit }}</li>
                    @endforeach
                </ul>
            </aside>
            <div class="store-auth__panel">
                <span class="store-auth__eyebrow">{{ $portal }}</span>
                <h1>{{ $title }}</h1>
                <p class="store-auth__intro">{{ $subtitle }}</p>
                {{ $slot }}
            </div>
        </div>
    </div>
</section>
