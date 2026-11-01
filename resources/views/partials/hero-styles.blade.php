<style>
    .storefront-hero, .featured-categories { --hero-accent: {{ $color->primary->background_color ?? '#1c4435' }}; --hero-gutter: clamp(20px, 3vw, 48px); background: #fbf9f5; }
    .storefront-hero { padding: 24px 0 0; }
    .mobile-hero-promotion { display: none; }
    .storefront-hero__container { max-width: 1536px; padding: 0 var(--hero-gutter); margin: 0 auto; }
    .storefront-hero__grid { display: grid; grid-template-columns: minmax(0, 2.05fr) minmax(0, 1fr); gap: 20px; align-items: stretch; }
    .storefront-hero__grid--single { grid-template-columns: minmax(0, 1fr); }
    .storefront-hero__slider.block-slideshow { position: relative; min-width: 0; margin: 0; overflow: hidden; border-radius: 12px; background: #f6f3eb; }
    .storefront-hero__slide, .storefront-hero__welcome { position: relative; display: block; aspect-ratio: 2.5 / 1; width: 100%; overflow: hidden; }
    .storefront-hero__slide picture, .storefront-hero__slide img { display: block; width: 100%; height: 100%; }
    .storefront-hero__slide img { object-fit: var(--hero-image-fit, cover); }
    .storefront-hero__slider .owl-carousel:not(.owl-loaded) { display: block; }
    .storefront-hero__slider .owl-carousel:not(.owl-loaded) > .storefront-hero__slide:not(:first-child) { display: none; }
    .storefront-hero__slider .owl-nav { position: absolute; inset: 0; pointer-events: none; margin: 0; }
    .storefront-hero__slider .owl-nav button.owl-prev, .storefront-hero__slider .owl-nav button.owl-next { position: absolute; top: 50%; transform: translateY(-50%); display: grid; place-items: center; width: 44px; height: 44px; margin: 0; padding: 0 !important; border: 0; background: #fff; color: var(--hero-accent); border-radius: 0; pointer-events: auto; transition: color .2s ease; }
    .storefront-hero__slider .owl-prev { left: 0; }
    .storefront-hero__slider .owl-next { right: 0; }
    .storefront-hero__slider .owl-nav svg { width: 22px; height: 22px; }
    .storefront-hero__slider .owl-dots { position: absolute; bottom: 18px; left: 24px; display: flex; gap: 0; margin: 0; }
    .storefront-hero__slider button.owl-dot { display: grid; place-items: center; width: 26px; height: 28px; padding: 0 !important; background: transparent; border: 0; }
    .storefront-hero__slider button.owl-dot span { display: block; width: 8px; height: 8px; margin: 0; border-radius: 50%; background: #fff; box-shadow: 0 0 0 1px #00000010; }
    .storefront-hero__slider button.owl-dot.active span { background: var(--hero-accent); }
    .storefront-hero__slider .owl-nav.disabled, .storefront-hero__slider .owl-dots.disabled { display: none; }
    .storefront-hero__pause { position: absolute; right: 14px; bottom: 14px; z-index: 2; display: grid; place-items: center; width: 36px; height: 36px; border: 0; border-radius: 50%; background: #ffffffed; color: var(--hero-accent); cursor: pointer; }
    .storefront-hero__pause .hero-play-icon, .storefront-hero__pause[data-paused="true"] .hero-pause-icon { display: none; }
    .storefront-hero__pause[data-paused="true"] .hero-play-icon { display: block; }
    .storefront-hero__caption { position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: center; align-items: flex-start; padding: 32px 60px; max-width: 75%; color: #25382d; }
    .storefront-hero__caption h2 { font-size: clamp(20px, 2.4vw, 36px); line-height: 1.2; margin: 0 0 12px; }
    .storefront-hero__description { font-size: 15px; margin-bottom: 18px; }
    .storefront-hero__welcome { display: flex; align-items: center; aspect-ratio: auto; min-height: clamp(300px, 26vw, 440px); background: radial-gradient(ellipse at 80% 100%, #fff7dd, transparent 60%), linear-gradient(120deg, #f6edda, #ffefd0); }
    .storefront-hero__welcome-copy { position: relative; z-index: 1; width: 66%; padding: clamp(24px, 3vw, 48px); }
    .storefront-hero__eyebrow { display: block; font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #6e6656; margin-bottom: 14px; }
    .storefront-hero__welcome h1 { font-size: clamp(24px, 2.7vw, 40px); font-weight: 700; line-height: 1.15; color: #283c2e; margin: 0 0 14px; overflow-wrap: anywhere; }
    .storefront-hero__welcome p { color: #746c5e; font-size: 14px; line-height: 1.6; margin: 0 0 22px; }
    .storefront-hero__cta { display: inline-flex; align-items: center; gap: 18px; min-height: 44px; padding: 10px 18px; background: var(--hero-accent); color: #fff; font-size: 13px; font-weight: 600; border-radius: 7px; text-decoration: none; }
    .storefront-hero__cta:hover { color: #fff; text-decoration: none; }
    .storefront-hero__cta span { transition: transform .2s ease; }
    .storefront-hero__cta:hover span { transform: translateX(3px); }
    .storefront-hero__art { position: absolute; width: 33%; right: 3%; bottom: 5%; color: var(--hero-accent); transform: rotate(-8deg); }
    .storefront-hero__art svg { display: block; width: 100%; }
    .storefront-hero__promotion { position: relative; display: block; min-width: 0; min-height: 0; overflow: hidden; border-radius: 12px; background: linear-gradient(135deg, #e8eedb, #d8e3bf); color: #263d2d; text-decoration: none; }
    .storefront-hero__promotion > img:not(.storefront-hero__category-art) { position: absolute; width: 100%; height: 100%; object-fit: cover; }
    .storefront-hero__promotion-copy { position: relative; z-index: 1; padding: clamp(24px, 2.5vw, 40px); max-width: 90%; }
    .storefront-hero__promotion-copy h2 { font-size: clamp(24px, 2.3vw, 34px); font-weight: 600; line-height: 1.15; margin: 0 0 20px; }
    .storefront-hero__promotion-link { display: inline-flex; align-items: center; gap: 14px; font-size: 13px; border-bottom: 1px solid currentColor; padding-bottom: 5px; }
    .storefront-hero__promotion:hover { color: #263d2d; text-decoration: none; }
    .storefront-hero__promotion-orbit { position: absolute; right: -12%; bottom: -45%; width: 90%; aspect-ratio: 1; border: 30px solid #ffffff35; border-radius: 50%; box-shadow: 0 0 0 30px #ffffff18; }
    .storefront-hero__category-art { position: absolute; width: 44%; height: 44%; object-fit: contain; bottom: 8%; right: 6%; mix-blend-mode: multiply; }
    .storefront-hero a:focus-visible, .storefront-hero button:focus-visible, .featured-categories a:focus-visible { outline: 2px solid var(--hero-accent); outline-offset: -4px; }
    .featured-categories { padding: 22px 0 30px; }
    .featured-categories__slider { position: relative; min-width: 0; }
    .featured-categories__list { --category-columns: 8; --category-gap: clamp(18px, 2vw, 36px); display: flex; gap: var(--category-gap); overflow-x: auto; padding: 6px 0 12px; margin: 0; scrollbar-width: none; scroll-behavior: smooth; scroll-snap-type: x mandatory; overscroll-behavior-x: contain; -webkit-overflow-scrolling: touch; }
    .featured-categories__list::-webkit-scrollbar { display: none; }
    .featured-categories[data-overflow="false"] .featured-categories__list { overflow-x: hidden; scroll-snap-type: none; }
    .featured-categories__item { flex: 0 0 calc((100% - (var(--category-columns) - 1) * var(--category-gap)) / var(--category-columns)); min-width: 0; display: flex; flex-direction: column; align-items: center; gap: 14px; color: #34453a; text-align: center; font-size: clamp(13px, 1.1vw, 17px); font-weight: 500; line-height: 1.4; text-decoration: none; scroll-snap-align: start; overflow-wrap: anywhere; }
    .featured-categories__image { display: grid; place-items: center; width: 100%; aspect-ratio: 1; border: 1px solid transparent; border-radius: 24px; background: #fff; transition: transform .2s ease, border-color .2s ease; }
    .featured-categories__image img { width: 78%; height: 78%; object-fit: contain; }
    .featured-categories__arrow { position: absolute; z-index: 2; top: calc((100% - 44px) / 2); transform: translateY(-50%); display: grid; place-items: center; width: 44px; height: 44px; border: 0; border-radius: 50%; background: var(--hero-accent); color: #fff; cursor: pointer; box-shadow: 0 3px 12px #0000000c; transition: opacity .2s ease, box-shadow .2s ease; }
    .featured-categories__arrow--previous { left: -22px; }
    .featured-categories__arrow--next { right: -22px; }
    .featured-categories__arrow[hidden] { display: none; }
    .featured-categories__arrow:disabled { opacity: .28; cursor: default; box-shadow: none; }
    .featured-categories__arrow:not(:disabled):hover { box-shadow: 0 4px 18px #00000024; }
    .featured-categories__arrow:focus-visible, .featured-categories__list:focus-visible { outline: 2px solid var(--hero-accent); outline-offset: 4px; }
    .featured-categories__initial { color: var(--hero-accent); font-size: 36px; font-weight: 600; }
    .featured-categories__item:hover { color: var(--hero-accent); text-decoration: none; }
    .featured-categories__item:hover .featured-categories__image { transform: translateY(-3px); border-color: var(--hero-accent); }
    @media (max-width: 1279px) {
        .featured-categories__list { --category-columns: 6; }
    }
    @media (max-width: 1023px) {
        .featured-categories__list { --category-columns: 4; }
        .storefront-hero__grid { grid-template-columns: minmax(0, 1.8fr) minmax(0, 1fr); gap: 16px; }
        .storefront-hero__welcome-copy { padding: 24px; }
        .storefront-hero__welcome h1 { font-size: 26px; }
        .storefront-hero__welcome p { margin-bottom: 14px; font-size: 12px; }
        .storefront-hero__promotion-copy { padding: 24px; }
        .storefront-hero__promotion-copy h2 { font-size: 24px; }
        .storefront-hero__eyebrow { font-size: 10px; margin-bottom: 8px; }
    }
    @media (max-width: 767px) {
        .storefront-hero__grid .storefront-hero__promotion--desktop { display: none; }
        .mobile-hero-promotion { display: block; padding: 16px 0; background: #fbf9f5; --hero-gutter: 16px; }
        .storefront-hero, .featured-categories { --hero-gutter: 16px; }
        .storefront-hero { padding-top: 16px; }
        .storefront-hero__grid { grid-template-columns: minmax(0, 1fr); gap: 14px; }
        .storefront-hero__slide { aspect-ratio: 2 / 1; }
        .storefront-hero__welcome { aspect-ratio: auto; min-height: 250px; }
        .storefront-hero__welcome-copy { width: 72%; padding: 26px 24px; }
        .storefront-hero__welcome h1 { font-size: clamp(25px, 6vw, 32px); }
        .storefront-hero__art { width: 29%; right: 1%; bottom: 10%; }
        .storefront-hero__promotion { aspect-ratio: 600 / 490; }
        .storefront-hero__promotion-copy { max-width: 72%; }
        .storefront-hero__caption { padding: 20px 42px; max-width: 88%; }
        .storefront-hero__caption h2 { font-size: 20px; margin-bottom: 8px; }
        .storefront-hero__description { font-size: 12px; margin-bottom: 10px; }
        .storefront-hero__slider .owl-nav button.owl-prev, .storefront-hero__slider .owl-nav button.owl-next { width: 36px; height: 44px; }
        .storefront-hero__slider .owl-dots { bottom: 8px; left: 14px; }
        .featured-categories { padding-top: 22px; }
        .featured-categories__list { --category-columns: 3; --category-gap: 16px; }
        .featured-categories__item { font-size: 13px; gap: 10px; }
        .featured-categories__image { border-radius: 20px; }
        .featured-categories__arrow--previous { left: -10px; }
        .featured-categories__arrow--next { right: -10px; }
    }
    @media (max-width: 479px) {
        .featured-categories__list { --category-columns: 2; --category-gap: 18px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .storefront-hero *, .featured-categories * { transition: none !important; }
        .featured-categories__list { scroll-behavior: auto; }
        .storefront-hero__cta:hover span, .featured-categories__item:hover .featured-categories__image { transform: none; }
    }
</style>
