@foreach (['status', 'success', 'token:sent'] as $notice)
    @if (session($notice))
        <div class="store-auth__notice" role="status">{{ session($notice) }}</div>
    @endif
@endforeach
@if (session('error'))
    <div class="store-auth__notice store-auth__notice--error" role="alert">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="store-auth__notice store-auth__notice--error" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
