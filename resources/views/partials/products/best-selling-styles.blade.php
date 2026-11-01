<style>
    .best-selling {
        --card-button-bg: {{ $color->order_now->background_color ?? '#e5a839' }};
        --card-button-text: {{ $color->order_now->text_color ?? '#17291e' }};
        --card-cart-color: {{ $color->add_to_cart->background_color ?? '#db8b25' }};
        padding: 40px 0 44px;
        background: var(--showcase-background);
    }
    .best-selling *, .best-selling *::before, .best-selling *::after { box-sizing: border-box; }
    .best-selling__container { max-width: 1536px; margin: 0 auto; padding: 0 clamp(20px, 3vw, 48px); }
    .best-selling__heading { position: relative; margin: 0 0 30px; padding-bottom: 20px; color: #18473d; text-align: center; font-size: clamp(23px, 2vw, 28px); font-weight: 700; line-height: 1.3; }
    .best-selling__heading::after { content: ''; position: absolute; width: 48px; height: 3px; left: calc(50% - 24px); bottom: 0; border-radius: 3px; background: var(--showcase-accent); }
    .best-selling__heading a, .best-selling__heading a:hover { color: inherit; text-decoration: none; }
    .best-selling__layout { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 3fr); gap: 16px; align-items: stretch; }
    .best-selling__collection { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 16px; min-width: 0; }
    .best-selling__cell { grid-column: span var(--showcase-card-span, 2); min-width: 0; }
    .best-selling__cell--horizontal { grid-column: span 3; }
    .best-selling__featured { min-width: 0; }
    .best-selling__layout--compact { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 24px; }
    .best-selling__layout--compact .best-selling__cell { grid-column: auto; }
    .showcase-card { position: relative; display: flex; flex-direction: column; min-width: 0; height: 100%; overflow: hidden; background: #fff; border: 1px solid #e4e5dc; border-radius: 14px; box-shadow: 0 2px 3px #18332203; transition: box-shadow .2s ease, border-color .2s ease; }
    .showcase-card__media { position: relative; display: grid; place-items: center; width: 100%; height: clamp(190px, 18vw, 280px); padding: 24px; overflow: hidden; background: #fff; text-decoration: none; }
    .showcase-card__media img { position: static; display: block; width: 100%; height: 100%; min-width: 0; min-height: 0; object-fit: contain; transition: transform .25s ease; }
    .showcase-card__media img[hidden], .showcase-card__placeholder[hidden] { display: none; }
    .showcase-card__placeholder { display: flex; flex-direction: column; align-items: center; gap: 16px; padding: 20px; color: #a6b1a8; text-align: center; font-size: 12px; }
    .showcase-card__placeholder span { max-width: 160px; }
    .showcase-card__body { display: flex; flex-direction: column; gap: 12px; flex: 1; min-width: 0; padding: 16px; background: #f8f6f0; }
    .showcase-card__name { margin: 0; font-size: 14px; font-weight: 500; line-height: 1.5; }
    .showcase-card__name a { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; color: #17483c; text-decoration: none; overflow-wrap: anywhere; }
    .showcase-card__pricing { display: flex; align-items: baseline; flex-wrap: wrap; gap: 4px 8px; margin-top: auto; line-height: 1.4; }
    .showcase-card__price { color: #13251c; font-size: 16px; font-weight: 700; }
    .showcase-card__old-price { color: #929a94; font-size: 12px; font-weight: 400; }
    .showcase-card__retail { flex-basis: 100%; font-size: 12px; color: #737d75; }
    .showcase-card__price-note { color: #647567; font-size: 13px; }
    .showcase-card__buttons { display: flex; flex-wrap: wrap; gap: 8px; }
    .best-selling .showcase-card__button { display: inline-flex; flex: 1 1 100px; align-items: center; justify-content: center; gap: 6px; min-height: 42px; padding: 9px 12px; border: 1px solid transparent; border-radius: 8px; font: inherit; font-size: 12px; font-weight: 600; line-height: 1.3; cursor: pointer; text-decoration: none; transition: box-shadow .2s ease; }
    .showcase-card__button svg { flex-shrink: 0; }
    .best-selling .showcase-card__button--cart { background: #fff; border-color: var(--card-cart-color); color: var(--card-cart-color); }
    .best-selling .showcase-card__button--buy { background: var(--card-button-bg); color: var(--card-button-text); border-color: var(--card-button-bg); }
    .showcase-card__button:disabled { opacity: .65; cursor: wait; }
    .showcase-card__buttons .spinner-border { width: 15px; height: 15px; flex-shrink: 0; }
    .showcase-card__badge { position: absolute; top: 12px; right: 12px; z-index: 1; padding: 5px 9px; border-radius: 6px; background: #f5eee1; color: #996822; font-size: 10px; font-weight: 600; letter-spacing: .02em; }
    .showcase-card__unavailable { color: #a15242; font-size: 13px; }
    .best-selling__featured .showcase-card__media { flex: 1; min-height: 300px; height: auto; }
    .best-selling__featured .showcase-card__media img { position: absolute; inset: 32px; width: calc(100% - 64px); height: calc(100% - 64px); }
    .best-selling__featured .showcase-card__body { flex: 0; }
    .best-selling__cell--horizontal .showcase-card, .best-selling__layout--compact .showcase-card { display: grid; grid-template-columns: minmax(0, 39%) minmax(0, 1fr); min-height: 200px; }
    .best-selling__cell--horizontal .showcase-card__media, .best-selling__layout--compact .showcase-card__media { height: 100%; min-height: 200px; padding: 22px; }
    .best-selling__cell--horizontal .showcase-card__media img, .best-selling__layout--compact .showcase-card__media img { position: absolute; inset: 22px; width: calc(100% - 44px); height: calc(100% - 44px); }
    .best-selling__cell--horizontal .showcase-card__body, .best-selling__layout--compact .showcase-card__body { justify-content: center; background: #fff; padding: 28px 22px 28px 8px; gap: 16px; }
    .best-selling__cell--horizontal .showcase-card__pricing, .best-selling__layout--compact .showcase-card__pricing { margin-top: 0; }
    .best-selling__cell--horizontal .showcase-card__name, .best-selling__layout--compact .showcase-card__name { font-size: 17px; font-weight: 500; }
    .best-selling__cell--horizontal .showcase-card__price, .best-selling__layout--compact .showcase-card__price { font-size: 19px; }
    .best-selling__layout--compact .showcase-card { min-height: 280px; }
    .best-selling__layout--compact .showcase-card__media { min-height: 280px; }
    .best-selling a:focus-visible, .best-selling button:focus-visible { outline: 2px solid var(--showcase-accent); outline-offset: -3px; }
    @media (hover: hover) and (pointer: fine) {
        .showcase-card:hover { border-color: #cbd9ce; box-shadow: 0 10px 26px #1c3b2510; }
        .showcase-card:hover .showcase-card__media img { transform: scale(1.035); }
        .showcase-card__name a:hover { text-decoration: underline; text-underline-offset: 4px; }
        .showcase-card__button:hover { box-shadow: 0 3px 10px #132a2018; }
    }
    @media (max-width: 1199px) {
        .best-selling__layout--compact .showcase-card__body { padding: 28px 18px 24px 4px; }
        .best-selling__cell--horizontal .showcase-card__buttons { flex-direction: column; }
        .best-selling__cell--horizontal .showcase-card__button { flex: auto; }
    }
    @media (max-width: 1023px) {
        .best-selling__layout { grid-template-columns: minmax(0, 1fr) minmax(0, 2fr); }
        .best-selling__collection { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .best-selling__cell, .best-selling__cell--horizontal { grid-column: auto; }
        .best-selling__cell--horizontal .showcase-card { display: flex; }
        .best-selling__cell--horizontal .showcase-card__media { height: 200px; min-height: 0; }
        .best-selling__cell--horizontal .showcase-card__media img { position: static; width: 100%; height: 100%; }
        .best-selling__cell--horizontal .showcase-card__body { flex: 1; padding: 16px; background: #f8f6f0; gap: 12px; }
        .best-selling__cell--horizontal .showcase-card__name { font-size: 14px; }
        .best-selling__cell--horizontal .showcase-card__price { font-size: 16px; }
        .best-selling__cell--horizontal .showcase-card__buttons { flex-direction: row; }
        .best-selling__layout--compact { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .best-selling__layout--compact .showcase-card { grid-template-columns: minmax(0, 1fr); }
        .best-selling__layout--compact .showcase-card__media { height: 220px; min-height: 0; }
        .best-selling__layout--compact .showcase-card__body { padding: 18px; }
    }
    @media (max-width: 767px) {
        .best-selling { padding: 30px 0; }
        .best-selling__container { padding: 0 16px; }
        .best-selling__layout, .best-selling__layout--compact { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .best-selling__collection { display: contents; }
        .showcase-card { border-radius: 12px; }
        .showcase-card__media, .best-selling__cell--horizontal .showcase-card__media, .best-selling__layout--compact .showcase-card__media, .best-selling__featured .showcase-card__media { height: clamp(160px, 40vw, 240px); min-height: 0; flex: 0 0 auto; padding: 18px; }
        .best-selling__featured .showcase-card__media img { position: static; width: 100%; height: 100%; }
        .showcase-card__body, .best-selling__cell--horizontal .showcase-card__body, .best-selling__layout--compact .showcase-card__body { padding: 12px; gap: 12px; background: #f8f6f0; flex: 1; }
        .showcase-card__name, .best-selling__cell--horizontal .showcase-card__name, .best-selling__layout--compact .showcase-card__name { font-size: 13px; }
        .showcase-card__price, .best-selling__layout--compact .showcase-card__price { font-size: 16px; }
        .showcase-card__buttons, .best-selling__cell--horizontal .showcase-card__buttons { flex-direction: column; }
        .best-selling .showcase-card__button { flex: auto; width: 100%; min-height: 44px; font-size: 12px; padding: 9px 6px; }
        .showcase-card__badge { top: 8px; right: 8px; font-size: 9px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .showcase-card, .showcase-card__media img, .showcase-card__button { transition: none !important; }
        .showcase-card:hover .showcase-card__media img { transform: none; }
    }
</style>
