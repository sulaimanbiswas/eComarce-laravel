@php
    $footerBrandPalette = setting('brand_palette');
    $footerPalette = app(\App\Services\BrandPalette::class)->footerColors(
        data_get($footerBrandPalette, 'primary') ?? $color->header->background_color ?? '#1c4435',
        data_get($footerBrandPalette, 'accent') ?? $color->badge->background_color ?? '#d6992c',
    );
@endphp
<style>
    .modern-footer {
        --footer-bg: {{ $footerPalette['background_color'] }};
        --footer-text: {{ $footerPalette['text_color'] }};
        --footer-hover: {{ $footerPalette['text_hover'] }};
        padding: 0; background: var(--footer-bg); color: var(--footer-text); border-top: 3px solid var(--footer-hover);
    }
    .modern-footer *, .modern-footer *::before, .modern-footer *::after { box-sizing: border-box; }
    .modern-footer__container { max-width: 1536px; margin: 0 auto; padding: 0 clamp(20px, 3vw, 48px); }
    .modern-footer a { color: inherit; text-decoration: none; transition: color .18s ease; }
    .modern-footer a:hover { color: var(--footer-hover); text-decoration: none; }
    .modern-footer a:focus-visible { outline: 2px solid currentColor; outline-offset: 4px; border-radius: 4px; }
    .modern-footer__support { display: flex; justify-content: space-between; align-items: center; gap: 30px; padding: 36px 0; border-bottom: 1px solid color-mix(in srgb, currentColor 16%, transparent); }
    .modern-footer__support-copy { min-width: 0; }
    .modern-footer__eyebrow { display: block; font-size: 10px; font-weight: 600; letter-spacing: .14em; opacity: .7; margin-bottom: 10px; }
    .modern-footer__support h2 { font-size: clamp(22px, 2.2vw, 30px); font-weight: 600; line-height: 1.3; color: inherit; margin: 0 0 9px; }
    .modern-footer__support p { margin: 0; opacity: .78; font-size: 13px; line-height: 1.6; }
    .modern-footer__support-actions { display: flex; flex-wrap: wrap; gap: 10px; flex-shrink: 0; }
    .modern-footer .modern-footer__contact-button { display: inline-flex; align-items: center; justify-content: center; gap: 10px; min-height: 46px; padding: 11px 18px; border-radius: 9px; border: 1px solid var(--footer-text); background: var(--footer-text); color: var(--footer-bg); font-size: 13px; font-weight: 600; overflow-wrap: anywhere; }
    .modern-footer .modern-footer__contact-button--outline { background: transparent; color: var(--footer-text); border-color: currentColor; }
    .modern-footer .modern-footer__contact-button:hover,
    .modern-footer .modern-footer__contact-button:focus-visible { background: var(--footer-text); color: var(--footer-bg); box-shadow: 0 3px 12px #00000014; text-decoration: underline; text-underline-offset: 4px; }
    .modern-footer .modern-footer__contact-button--outline:hover,
    .modern-footer .modern-footer__contact-button--outline:focus-visible { background: transparent; color: var(--footer-text); }
    .modern-footer__grid { display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, .8fr) minmax(0, .8fr) minmax(0, 1.2fr); gap: 40px; padding: 46px 0; }
    .modern-footer__grid > * { min-width: 0; }
    .modern-footer__grid--compact { grid-template-columns: minmax(0, 1.4fr) minmax(0, .8fr) minmax(0, 1.2fr); }
    .modern-footer__logo { display: inline-flex; align-items: center; max-width: 220px; font-size: 25px; font-weight: 700; overflow-wrap: anywhere; }
    .modern-footer__logo img { display: block; width: auto; height: auto; max-width: 100%; max-height: 48px; object-fit: contain; }
    .modern-footer__about { margin: 16px 0 20px; max-width: 300px; opacity: .78; font-size: 13px; line-height: 1.8; overflow-wrap: anywhere; }
    .modern-footer__socials { display: flex; flex-wrap: wrap; gap: 9px; }
    .modern-footer__socials a { display: inline-grid; place-items: center; width: 40px; height: 40px; border: 1px solid color-mix(in srgb, currentColor 22%, transparent); border-radius: 10px; font-size: 16px; }
    .modern-footer h3 { margin: 0 0 18px; color: inherit; font-size: 14px; font-weight: 600; line-height: 1.4; }
    .modern-footer ul { list-style: none; margin: 0; padding: 0; }
    .modern-footer__navigation li + li { margin-top: 5px; }
    .modern-footer__navigation a { display: inline-flex; align-items: center; min-height: 32px; font-size: 13px; line-height: 1.5; opacity: .85; overflow-wrap: anywhere; }
    .modern-footer__contact ul { display: grid; gap: 15px; }
    .modern-footer__contact li { display: flex; align-items: flex-start; gap: 12px; font-size: 13px; line-height: 1.65; opacity: .85; overflow-wrap: anywhere; }
    .modern-footer svg { flex-shrink: 0; }
    .modern-footer__contact svg { margin-top: 1px; }
    .modern-footer address { margin: 0; font: inherit; }
    .modern-footer__bottom { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; padding: 22px 0 max(22px, env(safe-area-inset-bottom)); border-top: 1px solid color-mix(in srgb, currentColor 16%, transparent); }
    .modern-footer__bottom p { font-size: 12px; line-height: 1.5; margin: 0; opacity: .7; }
    .modern-footer__top { display: inline-flex; align-items: center; gap: 10px; min-height: 40px; font-size: 12px; }
    .modern-footer__top svg { width: 16px; height: 16px; }
    .modern-footer--studio .modern-footer__support { padding-block: 44px; }
    .modern-footer--studio .modern-footer__grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 36px 64px; }
    .modern-footer--studio .modern-footer__grid--compact { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .modern-footer--studio .modern-footer__brand { grid-column: 1 / -1; display: flex; flex-direction: column; align-items: center; text-align: center; padding-bottom: 30px; border-bottom: 1px solid color-mix(in srgb, currentColor 16%, transparent); }
    .modern-footer--studio .modern-footer__about { max-width: 520px; margin-bottom: 16px; }
    .modern-footer--studio .modern-footer__navigation a:hover { text-decoration: underline; text-underline-offset: 5px; }
    .modern-footer--floating { padding-top: 30px; border-radius: 28px 28px 0 0; }
    .modern-footer--floating .modern-footer__support { padding: 30px; border: 1px solid color-mix(in srgb, currentColor 20%, transparent); border-radius: 20px; background: color-mix(in srgb, currentColor 4%, transparent); }
    .modern-footer--floating .modern-footer__grid { gap: 16px; padding-block: 24px; }
    .modern-footer--floating .modern-footer__grid > * { padding: 24px; border-radius: 16px; border: 1px solid color-mix(in srgb, currentColor 16%, transparent); }
    .modern-footer--floating .modern-footer__contact-button { border-radius: 999px; }
    @media (max-width: 1023px) {
        .modern-footer__support { align-items: flex-start; flex-direction: column; gap: 22px; }
        .modern-footer__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 32px; padding: 34px 0; }
        .modern-footer__brand { order: 0; }.modern-footer__contact { order: 1; }.modern-footer__navigation { order: 2; }
        .modern-footer--studio .modern-footer__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 28px; }
        .modern-footer--studio .modern-footer__brand { order: 0; }
        .modern-footer--studio .modern-footer__contact { order: 3; grid-column: 1 / -1; }
    }
    @media (max-width: 575px) {
        .modern-footer__container { padding: 0 20px; }
        .modern-footer__support { padding: 28px 0; }
        .modern-footer__support-actions { flex-shrink: 1; width: 100%; }
        .modern-footer__contact-button { flex: 1 1 150px; }
        .modern-footer__grid { gap: 28px 20px; }
        .modern-footer__brand { grid-column: 1 / -1; }.modern-footer__contact { grid-column: 1 / -1; order: 3; }
        .modern-footer__about { max-width: none; }
        .modern-footer__socials a { width: 44px; height: 44px; }
        .modern-footer__navigation a { min-height: 40px; }
        .modern-footer__bottom { align-items: flex-start; gap: 10px; }
        .modern-footer__bottom p { flex-basis: 100%; }
        .modern-footer--floating .modern-footer__support { padding: 22px; }
        .modern-footer--floating .modern-footer__grid { gap: 12px; }
        .modern-footer--floating .modern-footer__grid > * { padding: 18px; }
    }
    @media (prefers-reduced-motion: reduce) { .modern-footer a { transition: none; } }
</style>
