(() => {
    'use strict';

    if (window.storefrontCategorySlider) {
        window.storefrontCategorySlider.refresh();
        return;
    }

    const sliders = new Map();
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    function createSlider(section) {
        const track = section.querySelector('[data-category-track]');
        const previous = section.querySelector('[data-category-previous]');
        const next = section.querySelector('[data-category-next]');
        const abortController = new AbortController();
        const listenerOptions = { signal: abortController.signal };
        let frame = null;

        function update() {
            frame = null;
            const maximumScroll = Math.max(0, track.scrollWidth - track.clientWidth);
            const hasOverflow = maximumScroll > 2;
            section.dataset.overflow = String(hasOverflow);
            previous.hidden = !hasOverflow;
            next.hidden = !hasOverflow;
            previous.disabled = !hasOverflow || track.scrollLeft <= 2;
            next.disabled = !hasOverflow || track.scrollLeft >= maximumScroll - 2;

            if (hasOverflow) {
                track.setAttribute('tabindex', '0');
                section.setAttribute('aria-roledescription', 'carousel');
            } else {
                track.removeAttribute('tabindex');
                section.removeAttribute('aria-roledescription');
            }
        }

        function scheduleUpdate() {
            if (frame === null) {
                frame = window.requestAnimationFrame(update);
            }
        }

        function scrollToPosition(position) {
            const maximumScroll = Math.max(0, track.scrollWidth - track.clientWidth);
            track.scrollTo({
                left: Math.min(maximumScroll, Math.max(0, position)),
                behavior: reducedMotion.matches ? 'instant' : 'smooth',
            });
        }

        function move(direction) {
            const item = track.querySelector('.featured-categories__item');
            if (!item) {
                return;
            }

            const styles = window.getComputedStyle(track);
            const gap = parseFloat(styles.columnGap) || 0;
            const columns = parseInt(styles.getPropertyValue('--category-columns'), 10) || 1;
            scrollToPosition(track.scrollLeft + direction * (item.getBoundingClientRect().width + gap) * columns);
        }

        previous.addEventListener('click', () => move(-1), listenerOptions);
        next.addEventListener('click', () => move(1), listenerOptions);
        track.addEventListener('scroll', scheduleUpdate, { ...listenerOptions, passive: true });
        track.addEventListener('keydown', (event) => {
            if (event.target !== track || section.dataset.overflow !== 'true') {
                return;
            }

            if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                event.preventDefault();
                move(event.key === 'ArrowLeft' ? -1 : 1);
            } else if (event.key === 'Home' || event.key === 'End') {
                event.preventDefault();
                scrollToPosition(event.key === 'Home' ? 0 : track.scrollWidth);
            }
        }, listenerOptions);

        const observer = new ResizeObserver(scheduleUpdate);
        observer.observe(track);
        update();

        return {
            update: scheduleUpdate,
            destroy() {
                observer.disconnect();
                abortController.abort();
                if (frame !== null) {
                    window.cancelAnimationFrame(frame);
                }
            },
        };
    }

    function refresh() {
        sliders.forEach((slider, section) => {
            if (!section.isConnected) {
                slider.destroy();
                sliders.delete(section);
            }
        });

        document.querySelectorAll('[data-category-slider]').forEach((section) => {
            if (!sliders.has(section)) {
                sliders.set(section, createSlider(section));
            }
            sliders.get(section).update();
        });
    }

    window.storefrontCategorySlider = { refresh };
    document.addEventListener('livewire:navigated', refresh);
    window.addEventListener('resize', refresh, { passive: true });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', refresh, { once: true });
    } else {
        refresh();
    }
})();
