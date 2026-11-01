@foreach ($headerCategories->where('is_enabled', true) as $childCategory)
    <li>
        <a href="{{ route('categories.products', $childCategory->slug) }}" wire:navigate.hover>{{ $childCategory->name }}</a>
        @if ($childCategory->childrens->where('is_enabled', true)->isNotEmpty())
            <ul>@include('partials.header.category-children', ['headerCategories' => $childCategory->childrens])</ul>
        @endif
    </li>
@endforeach
