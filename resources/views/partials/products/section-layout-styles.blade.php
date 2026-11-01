<style>
    .product-section__grid { display: grid; grid-template-columns: repeat(var(--section-columns, 5), minmax(0, 1fr)); gap: 16px; }
    .product-section__grid .showcase-card__media, .product-section__track .showcase-card__media { height: auto; aspect-ratio: 1; }
    .product-section__grid .showcase-card__media img, .product-section__track .showcase-card__media img { position: absolute; inset: 20px; width: calc(100% - 40px); height: calc(100% - 40px); }
    .product-section--horizontal .product-section__grid { grid-template-columns: repeat(var(--section-columns, 3), minmax(0, 1fr)); gap: 20px; }
    .product-section--horizontal .showcase-card { display: grid; grid-template-columns: minmax(0, 40%) minmax(0, 1fr); min-height: 210px; }
    .product-section--horizontal .showcase-card__media { height: 100%; aspect-ratio: auto; }
    .product-section--horizontal .showcase-card__body { justify-content: center; padding: 24px 18px 24px 4px; background: #fff; }
    .product-section--horizontal .showcase-card__pricing { margin-top: 0; }
    .product-section--horizontal .showcase-card__name { font-size: 15px; }
    .product-section--horizontal .showcase-card__price { font-size: 19px; }
    .product-section__footer { display: flex; justify-content: center; padding-top: 24px; }
    .product-section__pill { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 42px; padding: 10px 22px; border: 1px solid #345749; border-radius: 999px; background: transparent; color: #245347; font-size: 13px; cursor: pointer; }
    .product-section__pill:disabled { opacity: .5; cursor: wait; }
    .product-section--compact .showcase-card, .product-section--slider .showcase-card { border-radius: 4px; }
    .product-section--compact .showcase-card__body, .product-section--slider .showcase-card__body { background: #fff; padding: 12px; }
    .product-section__slider { position: relative; }
    .product-section__track { display: flex; gap: 16px; overflow-x: auto; scroll-snap-type: x mandatory; scrollbar-width: none; overscroll-behavior-x: contain; padding-bottom: 8px; }
    .product-section__track::-webkit-scrollbar { display: none; }
    .product-section__item { flex: 0 0 calc((100% - (var(--section-columns) - 1) * 16px) / var(--section-columns)); min-width: 0; scroll-snap-align: start; }
    .product-section__arrow { position: absolute; z-index: 2; top: 40%; display: grid; place-items: center; width: 44px; height: 44px; border: 0; border-radius: 50%; background: #fff; box-shadow: 0 2px 12px #0002; font-size: 30px; color: #245347; cursor: pointer; }
    .product-section__arrow--previous { left: -18px; }.product-section__arrow--next { right: -18px; }.product-section__arrow:disabled { opacity: .35; cursor: default; }
    .product-section__filters { display: flex; flex-wrap: wrap; gap: 10px; margin: 0 0 30px; }
    .product-section__filter { min-height: 36px; padding: 7px 17px; border: 1px solid #e7e1d7; border-radius: 999px; color: #254b40; background: #fff; font-size: 13px; cursor: pointer; }
    .product-section__filter[aria-pressed="true"] { background: #254b40; border-color: #254b40; color: #fff; }
    .product-section__status { text-align: center; color: #6b786e; padding-top: 20px; font-size: 13px; }
    @media (max-width: 1199px) {
        .product-section[data-columns="5"], .product-section[data-columns="6"] { --section-columns: 4 !important; }
        .product-section--horizontal { --section-columns: 2 !important; }
    }
    @media (max-width: 767px) {
        .product-section[data-columns] { --section-columns: 2 !important; }.product-section__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .product-section--horizontal .showcase-card { display: flex; min-height: 0; }.product-section--horizontal .showcase-card__media { aspect-ratio: 1; height: auto; }.product-section--horizontal .showcase-card__body { padding: 12px; }
        .product-section--horizontal .showcase-card__name { font-size: 13px; }.product-section--horizontal .showcase-card__price { font-size: 16px; }
        .product-section__filters { flex-wrap: nowrap; overflow-x: auto; padding-bottom: 8px; gap: 8px; }.product-section__filter { flex-shrink: 0; }
        .product-section__arrow--previous { left: -8px; }.product-section__arrow--next { right: -8px; }
    }
</style>
