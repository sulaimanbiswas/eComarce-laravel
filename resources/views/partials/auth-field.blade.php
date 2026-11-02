<div class="store-auth__field {{ !empty($wide) ? 'store-auth__field--wide' : '' }}">
    <label for="{{ $idPrefix }}-{{ $name }}">{{ $label }} @if (!empty($optional))<span class="font-weight-normal">(Optional)</span>@endif</label>
    <input id="{{ $idPrefix }}-{{ $name }}" type="{{ $type ?? 'text' }}" name="{{ $name }}"
        class="@error($name) is-invalid @enderror"
        @if (($type ?? 'text') !== 'password') value="{{ $value ?? old($name) }}" @endif
        @if (empty($optional)) required @endif
        autocomplete="{{ $autocomplete ?? 'off' }}" placeholder="{{ $placeholder ?? $label }}"
        @error($name) aria-invalid="true" aria-describedby="{{ $idPrefix }}-{{ $name }}-error" @enderror>
    @error($name)
        <span class="invalid-feedback" id="{{ $idPrefix }}-{{ $name }}-error" role="alert">{{ $message }}</span>
    @enderror
    @if (!empty($hint))
        <small class="store-auth__hint">{{ $hint }}</small>
    @endif
</div>
