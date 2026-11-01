@extends('layouts.yellow.master')

@section('seo_tags')
    @if (!empty($company->seo_title))
        <title>{{ $company->seo_title }}</title>
        @if (!empty($company->meta_description))
            <meta name="description" content="{{ $company->meta_description }}">
        @endif
    @endif
@endsection

@section('title', 'Home')

@push('head')
    @if (empty($company->seo_title) && !empty($company->meta_description))
        <meta name="description" content="{{ $company->meta_description }}">
    @endif
@endpush

@push('head')
  {{-- Preconnect to unpkg.com for AOS.js to reduce latency --}}
  <link rel="preconnect" href="https://unpkg.com" crossorigin>
  <link rel="dns-prefetch" href="https://unpkg.com">
@endpush

@push('styles')
  {{-- Defer AOS CSS to prevent render blocking - load asynchronously --}}
  <link rel="preload" href="https://unpkg.com/aos@next/dist/aos.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"></noscript>
  <style>
    .content-accordion .card {
      border: 1px solid #e3e3e3;
      margin-bottom: 1rem;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .content-accordion .card-header {
      background-color: #ffffff;
      border-bottom: 1px solid #e3e3e3;
      padding: 1rem 1.5rem;
      border-radius: 8px 8px 0 0;
    }
    .content-accordion .btn-link {
      color: #333;
      text-decoration: none;
      font-weight: 600;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      text-align: left;
      padding: 0;
      background: none;
      border: none;
    }
    .content-accordion .btn-link:hover {
      color: #007bff;
      text-decoration: none;
    }
    .content-accordion .btn-link:focus {
      box-shadow: none;
      outline: none;
    }
    .content-accordion .btn-link::after {
      content: '−';
      font-size: 1.5rem;
      font-weight: bold;
      color: #666;
    }
    .content-accordion .btn-link.collapsed::after {
      content: '+';
    }
    .content-accordion .card-body {
      padding: 1.5rem;
      line-height: 1.6;
      color: #555;
      font-size: 0.95rem;
    }
    .content-accordion .collapse.show {
      display: block;
    }
    .content-accordion .card-body h1,
    .content-accordion .card-body h2,
    .content-accordion .card-body h3,
    .content-accordion .card-body h4,
    .content-accordion .card-body h5,
    .content-accordion .card-body h6 {
      color: #333;
      margin-bottom: 1rem;
      font-weight: 600;
    }
    .content-accordion .card-body p {
      margin-bottom: 1rem;
    }
    .content-accordion .card-body ul,
    .content-accordion .card-body ol {
      margin-bottom: 1rem;
      padding-left: 1.5rem;
    }
    .content-accordion .card-body li {
      margin-bottom: 0.5rem;
    }

  </style>
@endpush

@section('content')

@include('partials.slides')
@include('partials.featured-categories')

@if(isOninda() && config('app.resell') && auth('user')->guest())
@include('partials.auth-forms')
@endif

@include('partials.trust-services')
@if(isOninda())
<div class="block">
    <div class="container">
        <x-reseller-verification-alert />
    </div>
</div>
@endif

<!-- Home Page Heading (e.g: Trusted Online Shopping in Bangladesh) -->
@if (!empty($company->home_heading) && slides()->isNotEmpty())
    <div class="block my-4">
        <div class="container">
            <h1 class="text-center home-page-heading" style="font-size: 2rem; font-weight: 700; color: #3d464d; margin: 1.5rem 0;">{{ $company->home_heading }}</h1>
        </div>
    </div>
@endif

@if(($show_option = setting('show_option'))->brand_carousel ?? false)
<div class="block block-products-carousel" data-layout="grid-cat">
    <div class="container">
        @include('partials.section-heading', ['headingSection' => null, 'headingTitle' => 'Brands', 'headingId' => 'featured-brands-title', 'headingUrl' => route('brands'), 'headingPosition' => 'top'])
        <div class="d-flex justify-content-end mb-3">
            <div class="block-header__arrows-list">
                <button class="block-header__arrow block-header__arrow--left" type="button" aria-label="Previous">
                    <svg width="7px" height="11px" viewBox="0 0 7 11"><path d="M6.7.3c-.4-.4-.9-.4-1.3 0L0 5.5l5.4 5.2c.4.4.9.3 1.3 0 .4-.4.4-1 0-1.3l-4-3.9 4-3.9c.4-.4.4-1 0-1.3z"/></svg>
                </button>
                <button class="block-header__arrow block-header__arrow--right" type="button" aria-label="Next">
                    <svg width="7px" height="11px" viewBox="0 0 7 11"><path d="M.3 10.7c.4.4.9.4 1.3 0L7 5.5 1.6.3C1.2-.1.7 0 .3.3c-.4.4-.4 1 0 1.3l4 3.9-4 3.9c-.4.4-.4 1 0 1.3z"/></svg>
                </button>
            </div>
        </div>
        <div class="block-products-carousel__slider">
            <div class="block-products-carousel__preloader"></div>
            <div class="owl-carousel">
                @foreach(brands()->chunk(1) as $brands)
                <div>
                    @foreach($brands as $brand)
                    <div class="products-list__item">
                        <div class="product-card">
                            <div class="product-card__image">
                                <a href="{{ route('brands.products', $brand) }}" wire:navigate.hover>
                                    <img src="{{ cdn($brand->image_src, 100, 100) }}" alt="Product Image">
                                </a>
                            </div>
                            <div class="product-card__info">
                                <div class="product-card__name">
                                    <h4 style="overflow: hidden;text-overflow:ellipsis; font-size: 16px; font-weight: 700;">
                                        <a href="{{ route('brands.products', $brand) }}" wire:navigate.hover title="{{$brand->name}}">{{ $brand->name }}</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

@if ($show_option->brand_carousel ?? false)
    <div class="container">@include('partials.section-heading', ['headingSection' => null, 'headingTitle' => 'Brands', 'headingId' => 'featured-brands-title', 'headingUrl' => route('brands'), 'headingPosition' => 'bottom'])</div>
@endif
@php
    $mobileBannerSections = sections()->filter(fn ($candidate) => in_array($candidate->type, ['pure-grid', 'carousel-grid']) && ($candidate->data->section_enabled ?? true) && $candidate->storefrontLayout() !== 'all-products');
    $mobileHeroBannerSectionId = ($mobileBannerSections->first(fn ($candidate) => $candidate->storefrontLayout() === 'showcase') ?? $mobileBannerSections->first())?->id;
@endphp
@foreach(sections() as $section)
@if (($section->data->layout ?? '') === 'all-products' || !($section->data->section_enabled ?? true)) @continue @endif
@if(in_array($section->type, ['pure-grid', 'carousel-grid']))
    @if (($company->hero_banner_enabled ?? true) && $section->id === $mobileHeroBannerSectionId)
        <div class="mobile-hero-promotion"><div class="storefront-hero__container">@include('partials.hero-promotion', ['mobilePromotion' => true])</div></div>
    @endif
    @include('partials.products.best-selling', ['section' => $section, 'products' => $section->products()])
@elseif($section->type == 'banner')
    @include('partials.promotion-banners', ['section' => $section])
@elseif($section->type == 'content')
    @php
        $pageId = $section->data->page_id ?? null;
        $page = $pageId ? cacheMemo()->remember('page:'.$pageId, 3600, fn() => \App\Models\Page::find($pageId)) : null;
    @endphp
    @if($page)
    <div class="block">
        <div class="container">
            <div class="accordion content-accordion" id="content-accordion-{{ $section->id }}">
                <div class="card">
                    <div class="card-header" id="heading-{{ $section->id }}">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-{{ $section->id }}" aria-expanded="true" aria-controls="collapse-{{ $section->id }}">
                            {{ $page->title }}
                        </button>
                    </div>
                    <div id="collapse-{{ $section->id }}" class="collapse show" aria-labelledby="heading-{{ $section->id }}" data-parent="#content-accordion-{{ $section->id }}">
                        <div class="card-body">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endif
<!-- .block-products-carousel / end -->
@endforeach

@foreach (sections()->filter(fn ($catalogSection) => ($catalogSection->data->layout ?? '') === 'all-products' && ($catalogSection->data->section_enabled ?? true)) as $catalogSection)
    <livewire:home-product-catalog :section-id="$catalogSection->id" :key="'home-catalog-'.$catalogSection->id" />
@endforeach

@endsection

@push('scripts')
  {{-- Defer AOS.js - it's only for animations, not critical for initial render --}}
  <script src="https://unpkg.com/aos@next/dist/aos.js" defer></script>
  <script defer>
    // Wait for AOS to load before initializing
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        if (typeof AOS !== 'undefined') {
          AOS.init();
        } else {
          // Fallback: wait for script to load
          window.addEventListener('load', function() {
            if (typeof AOS !== 'undefined') {
              AOS.init();
            }
          });
        }
      });
    } else {
      // DOM already loaded
      if (typeof AOS !== 'undefined') {
        AOS.init();
      } else {
        window.addEventListener('load', function() {
          if (typeof AOS !== 'undefined') {
            AOS.init();
          }
        });
      }
    }
  </script>
@endpush
