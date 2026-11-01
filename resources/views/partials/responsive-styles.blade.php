<style>
    .site, .site__body, .site__body main { min-width: 0; }
    .site__body .row > [class*="col-"], .site__body .product-section__grid > *, .site__body .best-selling__layout > *, .site__body .best-selling__collection > * { min-width: 0; }
    .site__body img { max-width: 100%; }
    .site__body iframe, .site__body video { max-width: 100%; }
    .site__body .showcase-card__body, .site__body .storefront-hero__caption, .site__body .section-heading { min-width: 0; }
    .site__body .product-card__name, .site__body .showcase-card__name, .site__body .page-header__title, .site__body .product__name { overflow-wrap: anywhere; }
    .site__body .form-control, .site__body select, .site__body textarea { max-width: 100%; }
    .site__body .table-responsive { max-width: 100%; overscroll-behavior-x: contain; }
    .storefront-header__children { max-height: min(55vh, calc(100dvh - var(--storefront-header-bottom, 124px) - 12px)); }
    @media (min-width: 992px) {
        .site__body .product__content { grid-template-columns: [gallery] minmax(0, 1fr) [info] minmax(0, 1fr); gap: 24px; }
        .site__body .product__content:has(> .product-detail__aside) { grid-template-columns: [gallery] minmax(0, 1fr) [info] minmax(0, 1fr) [sidebar] minmax(180px, .6fr); }
    }
    @media (max-width: 1199px) {
        .storefront-header__inner { gap: 24px; }
        .storefront-header__logo { flex-basis: 170px; }
    }
    @media (min-width: 768px) and (max-width: 991px) {
        .storefront-header__logo { flex-basis: 130px; }
        .storefront-header__inner { gap: 16px; }
        .storefront-header__actions { gap: 10px; }
        .storefront-header__action { font-size: 11px; }
    }
    @media (max-width: 991px) {
        .site__body .product__content { grid-template-columns: minmax(0, 1fr); gap: 24px; }
        .site__body .product__content > * { grid-column: 1 / -1; min-width: 0; }
        .site__body .cart-summary { position: static; }
        .site__body .product__content, .site__body .product__gallery, .site__body .product__info, .site__body .checkout__totals { min-width: 0; max-width: 100%; }
        .site__body .products-list[data-layout^="grid"] .products-list__body { display: grid !important; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin: 0; }
        .site__body .products-list[data-layout^="grid"] .products-list__item { width: 100% !important; padding: 0; margin: 0; min-width: 0; }
    }
    @media (max-width: 767px) {
        .storefront-header__search input { font-size: 16px; }
        .site__body input.form-control, .site__body select.form-control, .site__body textarea.form-control { font-size: 16px; min-height: 44px; }
        .site__body .products-list[data-layout^="grid"] .products-list__body { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .site__body .product-card__buttons { display: flex; flex-wrap: wrap; gap: 8px; }
        .site__body .product-card__buttons .btn { min-height: 44px; height: auto; white-space: normal; margin: 0; }
        .site__body .product-card__actions { min-width: 0; }
        .site__body .product-card__prices { overflow-wrap: anywhere; }
        .site__body .product__actions { flex-wrap: wrap; gap: 10px; }
        .site__body .product__actions-item { max-width: 100%; margin: 0; }
        .site__body .product__actions .btn { min-height: 44px; white-space: normal; }
        .site__body .page-header__title { font-size: 26px; line-height: 1.3; }
        .site__body .section-heading { align-items: start; }
        .site__body .product-section__filters { scroll-padding-inline: 8px; }
        .site__body .product-section__filter { min-height: 44px; }
        .site__body .featured-categories__list { scroll-padding-inline: 0; }
        .site__body .store-trust__grid { margin: 0; }
        .site__body .storefront-hero__caption { max-width: 100%; }
        .site__body .storefront-hero__description { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .site__body .showcase-card__button span { overflow-wrap: anywhere; }
        body > a[data-whatsapp-url], body > a[data-contact-type="messenger"] { width: 48px !important; height: 48px !important; right: 16px !important; bottom: calc(16px + env(safe-area-inset-bottom)) !important; font-size: 24px !important; }
        body > a[data-whatsapp-url] i, body > a[data-contact-type="messenger"] i { margin-top: 12px !important; }
        .widget-connect { right: 16px; bottom: calc(16px + env(safe-area-inset-bottom)); }
    }
    @media (max-width: 359px) {
        .site__body .showcase-card__body { padding: 10px; }
        .site__body .showcase-card__name { font-size: 12px; }
        .site__body .showcase-card__price { font-size: 14px; }
        .site__body .product-section__grid { gap: 10px; }
        .storefront-header__logo picture { width: 96px; }
        .site__body .storefront-hero__welcome-copy { width: 76%; padding: 22px 18px; }
        .site__body .storefront-hero__welcome h1 { font-size: 25px; }
        .site__body .storefront-hero__art { right: -2%; width: 27%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .site__body *, .modern-footer *, .storefront-header * { scroll-behavior: auto !important; }
    }
</style>
