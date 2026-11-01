<style>
    .storefront-header {
        --store-header-bg: {{ $color->header->background_color ?? '#1c4435' }};
        --store-header-text: {{ $color->header->text_color ?? '#ffffff' }};
        --store-header-hover: {{ $color->header->text_hover ?? '#d1eadb' }};
        --store-nav-bg: {{ $color->navbar->background_color ?? '#153a2b' }};
        --store-nav-text: {{ $color->navbar->text_color ?? '#ffffff' }};
        --store-nav-hover-text: {{ $color->navbar->text_hover ?? '#ffffff' }};
        --store-search-bg: {{ $color->search->background_color ?? '#1c4435' }};
        --store-search-text: {{ $color->search->text_color ?? '#ffffff' }};
        --store-search-hover-text: {{ $color->search->text_hover ?? '#ffffff' }};
        --store-category-bg: {{ $color->category_menu->background_color ?? '#ffffff' }};
        --store-category-text: {{ $color->category_menu->text_color ?? '#153a2b' }};
        --store-category-hover-text: {{ $color->category_menu->text_hover ?? '#153a2b' }};
        --store-gutter: clamp(20px, 3vw, 48px);
        position: sticky !important;
        top: 0;
        width: 100%;
        z-index: 100 !important;
        box-shadow: 0 4px 24px #14291d0d;
    }
    .header-fixed .site__body { padding-top: 0 !important; }
    .storefront-header *, .storefront-header *::before, .storefront-header *::after { box-sizing: border-box; }
    .storefront-header a { text-decoration: none; }
    .storefront-header__main { background: var(--store-header-bg); color: var(--store-header-text); }
    .storefront-header__inner { max-width: 1536px; margin: auto; padding: 14px var(--store-gutter); display: flex; align-items: center; justify-content: space-between; gap: clamp(24px, 3vw, 48px); min-height: 74px; }
    .storefront-header__logo { color: inherit; display: flex; align-items: center; flex: 0 1 200px; min-width: 0; font-size: 25px; font-weight: 800; line-height: 1.2; overflow-wrap: anywhere; }
    .storefront-header__logo picture { display: block; width: 200px; max-width: 100%; min-width: 0; }
    .storefront-header__logo img { display: block; max-width: 100%; width: auto; height: auto; max-height: 40px; object-fit: contain; }
    .storefront-header__logo small { display: block; font-size: 10px; letter-spacing: 3px; margin-top: 5px; font-weight: 500; }
    .storefront-header__search { position: relative; flex: 1 1 0; margin: 0; min-width: 0; }
    .storefront-header__search form { position: relative; display: flex; align-items: center; background: #fff; border: 1px solid #ffffff80; border-radius: 10px; height: 46px; box-shadow: 0 2px 8px #00000008; transition: box-shadow .2s ease; }
    .storefront-header__search input { width: 100%; min-width: 0; height: 100%; padding: 12px 56px 12px 16px; border: 0; outline: 0; background: transparent; color: #26352d; border-radius: 8px; font-size: 14px; line-height: 20px; }
    .storefront-header__search input::placeholder { color: #9a9a8e !important; opacity: 1; }
    .storefront-header__search form:focus-within { outline: 2px solid var(--store-header-text); outline-offset: 3px; box-shadow: 0 4px 18px #00000014; }
    .storefront-header__search button { position: absolute; right: 3px; top: 3px; bottom: 3px; width: 44px; display: grid; place-items: center; background: var(--store-search-bg); color: var(--store-search-text); border: 0; border-radius: 6px; cursor: pointer; }
    .storefront-header__search button svg { width: 18px; height: 18px; transition: transform .2s ease; }
    .storefront-header__search button:hover { color: var(--store-search-hover-text); }
    .storefront-header__suggestions { position: absolute; top: calc(100% + 6px); left: 0; width: 100%; z-index: 10; background: #fff; color: #26352d; border-radius: 8px; box-shadow: 0 8px 24px #0002; max-height: 350px; overflow-y: auto; }
    .storefront-header__actions { display: flex; flex-shrink: 0; align-items: center; gap: 24px; }
    .storefront-header__icon { display: block; flex-shrink: 0; width: 22px; height: 22px; }
    .storefront-header__action { position: relative; display: flex; flex-direction: column; gap: 5px; align-items: center; justify-content: center; color: var(--store-header-text); font-size: 12px; font-weight: 400; line-height: 1; white-space: nowrap; min-width: 44px; min-height: 44px; cursor: pointer; transition: color .2s ease; -webkit-tap-highlight-color: transparent; }
    .storefront-header__action::after { content: ''; position: absolute; bottom: -5px; left: calc(50% - 9px); width: 18px; height: 2px; border-radius: 2px; background: currentColor; transform: scaleX(0); transition: transform .2s ease; }
    .storefront-header__action > .storefront-header__icon, .storefront-header__cart-icon { transition: transform .2s ease; }
    .storefront-header__search-toggle, .storefront-header__mobile-account, .storefront-header__mobile-categories { display: none; }
    .storefront-header__more-panel .storefront-header__mobile-account { display: none; }
    .storefront-header__action:hover, .storefront-header__logo:hover { color: var(--store-header-hover); }
    .storefront-header__disclosure[open] > .storefront-header__action::after { transform: scaleX(1); }
    .storefront-header summary { list-style: none; }
    .storefront-header summary::-webkit-details-marker { display: none; }
    .storefront-header a:focus-visible, .storefront-header summary:focus-visible, .storefront-header button:focus-visible { outline: 2px solid currentColor; outline-offset: 3px; border-radius: 4px; }
    .storefront-header__disclosure { position: relative; }
    .storefront-header__cart-icon { position: relative; display: flex; }
    .storefront-header .indicator__value { position: absolute; top: -8px; right: -10px; margin: 0; min-width: 16px; height: 16px; padding: 0 4px; border-radius: 999px; background: #d6992c !important; color: #fff !important; font-size: 10px; font-weight: 700; line-height: 16px; text-align: center; }
    .storefront-header__dropdown { position: absolute; top: calc(100% + 18px); right: 0; z-index: 20; border-radius: 12px; background: #fff; color: #25382d; box-shadow: 0 12px 40px #162c1f24; border: 1px solid #0000000a; overscroll-behavior: contain; }
    .storefront-header__cart-panel { width: 340px; max-width: calc(100vw - 24px); max-height: min(65vh, 520px); overflow: auto; }
    .storefront-header__cart-panel .dropcart { width: 100%; }
    .storefront-header__more-panel { width: 260px; max-height: 65vh; overflow-y: auto; overflow-x: hidden; padding: 10px; }
    .storefront-header__more-panel a { display: block; color: #25382d; padding: 12px 14px; min-height: 44px; border-radius: 5px; font-size: 14px; transition: transform .18s ease; text-underline-offset: 5px; }
    .storefront-header__more-panel a:hover { text-decoration: underline; }
    .storefront-header__more-panel .nav-links__list { display: block; }
    .storefront-header__more-panel .nav-links { width: 100%; margin: 0; }
    .storefront-header__more-panel .nav-links__item { display: block; height: auto; }
    .storefront-header__more-panel .nav-links__item > a span { color: inherit !important; background: none !important; padding: 0; height: auto; }
    .storefront-header__more-panel .nav-links__item > a { height: auto; font-weight: 400; line-height: 1.5; letter-spacing: normal; }
    .storefront-header__help { border-top: 1px solid #e9eeeb; margin-top: 8px; }
    .storefront-header__help small { display: block; color: #78867d; margin-bottom: 4px; }
    .storefront-header__announcement { padding: 12px 14px; font-size: 13px; border-top: 1px solid #e9eeeb; }
    .storefront-header__navigation { background: var(--store-nav-bg); color: var(--store-nav-text); scrollbar-width: none; overflow-x: auto; border-top: 1px solid #ffffff12; }
    .storefront-header__navigation::-webkit-scrollbar { display: none; }
    .storefront-header__categories { max-width: 1536px; margin: auto; padding: 0 var(--store-gutter); list-style: none; display: flex; align-items: flex-start; flex-wrap: nowrap; gap: 8px; }
    .storefront-header__categories > li { flex-shrink: 0; }
    .storefront-header__categories > li > a, .storefront-header__category > summary { display: flex; align-items: center; gap: 8px; min-height: 49.5px; padding: 12px 16px; border-bottom: 2px solid transparent; color: var(--store-nav-text); font-size: 15px; font-weight: 600; line-height: 1.5; white-space: nowrap; cursor: pointer; transition: color .2s ease, border-color .2s ease; }
    .storefront-header__category .storefront-header__icon--chevron { width: 14px; height: 14px; transition: transform .18s ease; }
    .storefront-header__category[open] .storefront-header__icon--chevron { transform: rotate(180deg); }
    .storefront-header__category > summary a { color: inherit; }
    .storefront-header__categories > li > a:hover, .storefront-header__category > summary:hover, .storefront-header__category[open] > summary { color: var(--store-nav-hover-text); border-bottom-color: currentColor; }
    .storefront-header__children { position: fixed; left: max(var(--store-gutter), calc((100vw - 1536px) / 2 + var(--store-gutter))); right: auto; top: var(--storefront-header-bottom, 124px); min-width: 240px; width: max-content; max-width: 85vw; max-height: min(55vh, calc(100dvh - var(--storefront-header-bottom, 124px) - 12px)); overflow: auto; padding: 8px; list-style: none; background: var(--store-category-bg); color: var(--store-category-text); }
    .storefront-header__children ul { list-style: none; padding-left: 16px; }
    .storefront-header__children a { display: block; min-height: 44px; padding: 11px 12px; color: inherit; font-size: 14px; text-underline-offset: 5px; }
    .storefront-header__children a:hover { color: var(--store-category-hover-text); text-decoration: underline; }
    @media (hover: hover) and (pointer: fine) {
        .storefront-header__action:hover::after { transform: scaleX(1); }
        .storefront-header__action:hover > .storefront-header__icon, .storefront-header__action:hover > .storefront-header__cart-icon { transform: translateY(-2px); }
        .storefront-header__search button:hover svg { transform: translateX(2px); }
        .storefront-header__more-panel a:hover { transform: translateX(3px); }
    }
    @media (max-width: 1023px) {
        .storefront-header__inner { gap: 20px; }
        .storefront-header__logo { flex-basis: 160px; }
        .storefront-header__actions { gap: 12px; }
        .storefront-header__navigation { display: none; }
        .storefront-header__mobile-categories { display: block; }
        .storefront-header__mobile-categories > summary { display: flex; align-items: center; justify-content: space-between; padding: 11px 14px; font-size: 14px; cursor: pointer; }
        .storefront-header__mobile-categories ul { list-style: none; padding-left: 12px; margin: 0; }
        .storefront-header__mobile-categories > summary svg { width: 14px; height: 14px; }
    }
    @media (max-width: 767px) {
        .storefront-header { --store-gutter: 16px; }
        .storefront-header__inner { flex-wrap: wrap; gap: 14px; min-height: 72px; padding: 14px var(--store-gutter); }
        .storefront-header__logo { flex: 1 1 0; font-size: 20px; }
        .storefront-header__logo picture { width: 150px; }
        .storefront-header__search { display: none; order: 3; flex: 1 0 100%; margin: 0; }
        .storefront-header[data-search-open="true"] .storefront-header__search { display: block; }
        .storefront-header__search-toggle { display: flex; border: 0; padding: 0; background: transparent; }
        .storefront-header__actions { gap: 4px; }
        .storefront-header__action { min-width: 44px; min-height: 44px; }
        .storefront-header__action > span:not(.storefront-header__cart-icon), .storefront-header__account { display: none; }
        .storefront-header__more-panel .storefront-header__mobile-account { display: block; }
        .storefront-header__cart-panel, .storefront-header__more-panel { position: fixed; right: var(--store-gutter); top: calc(var(--storefront-header-bottom, 72px) + 8px); max-width: calc(100vw - 32px); max-height: calc(100dvh - var(--storefront-header-bottom, 72px) - 24px); }
    }
    @media (max-width: 359px) {
        .storefront-header__logo picture { width: 110px; }
        .storefront-header__logo { font-size: 17px; }
        .storefront-header__logo small { font-size: 8px; letter-spacing: 1px; }
        .storefront-header__actions { gap: 0; }
    }
    @media (prefers-reduced-motion: reduce) {
        .storefront-header *, .storefront-header *::before, .storefront-header *::after { transition: none !important; }
        .storefront-header__action:hover > .storefront-header__icon, .storefront-header__action:hover > .storefront-header__cart-icon, .storefront-header__search button:hover svg, .storefront-header__more-panel a:hover { transform: none; }
    }
</style>
