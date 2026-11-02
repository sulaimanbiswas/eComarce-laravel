<script>
    if (!window.__storefrontHeaderEventsBound) {
        window.__storefrontHeaderEventsBound = true;
        let headerScrollFrame = 0;
        let headerAnimationFrame = 0;
        let lastHeaderScrollY = Math.max(0, window.scrollY);
        let headerScrollDistance = 0;
        let headerScrollDirection = 0;
        let observedHeader = null;
        const updateStorefrontHeaderLayout = function() {
            const header = document.querySelector('.storefront-header');
            if (!header) {
                return;
            }
            const main = header.querySelector('.storefront-header__main');
            header.style.setProperty('--storefront-header-main-height', (main?.offsetHeight ?? 0) + 'px');
            header.style.setProperty('--storefront-header-top-gap', getComputedStyle(header).paddingTop);
            header.style.setProperty('--storefront-header-bottom', header.getBoundingClientRect().bottom + 'px');
            const morePanel = header.querySelector('.storefront-header__more[open] > .storefront-header__more-panel');
            if (morePanel) {
                const viewportBottom = window.visualViewport
                    ? window.visualViewport.offsetTop + window.visualViewport.height
                    : window.innerHeight;
                const availableHeight = Math.max(0, Math.floor(viewportBottom - morePanel.getBoundingClientRect().top - 16));
                morePanel.style.setProperty('--store-more-available-height', availableHeight + 'px');
            }
        };
        document.addEventListener('toggle', function(event) {
            if (event.target.matches('.storefront-header__more[open]')) {
                updateStorefrontHeaderLayout();
            }
        }, true);
        window.visualViewport?.addEventListener('resize', updateStorefrontHeaderLayout);
        const setHeaderScrollState = function(header, compact) {
            const state = compact ? 'compact' : 'expanded';
            if (header.dataset.scrollState !== state) {
                header.dataset.scrollState = state;
                cancelAnimationFrame(headerAnimationFrame);
                const started = performance.now();
                const followHeader = function(now) {
                    updateStorefrontHeaderLayout();
                    if (now - started < 400) {
                        headerAnimationFrame = requestAnimationFrame(followHeader);
                    }
                };
                headerAnimationFrame = requestAnimationFrame(followHeader);
            }
        };
        const headerResizeObserver = typeof ResizeObserver === 'function' ? new ResizeObserver(updateStorefrontHeaderLayout) : null;
        const initializeHeaderScroll = function() {
            const header = document.querySelector('.storefront-header');
            if (header !== observedHeader) {
                headerResizeObserver?.disconnect();
                observedHeader = header;
                if (header) {
                    headerResizeObserver?.observe(header);
                }
            }
            lastHeaderScrollY = Math.max(0, window.scrollY);
            headerScrollDistance = 0;
            headerScrollDirection = 0;
            if (header) {
                setHeaderScrollState(header, false);
            }
            updateStorefrontHeaderLayout();
        };
        window.addEventListener('scroll', function() {
            if (headerScrollFrame) {
                return;
            }
            headerScrollFrame = requestAnimationFrame(function() {
                headerScrollFrame = 0;
                const header = document.querySelector('.storefront-header');
                const scrollY = Math.max(0, Math.min(window.scrollY, document.documentElement.scrollHeight - window.innerHeight));
                const delta = scrollY - lastHeaderScrollY;
                lastHeaderScrollY = scrollY;
                if (!header) {
                    return;
                }
                const direction = Math.sign(delta);
                if (direction && direction !== headerScrollDirection) {
                    headerScrollDistance = 0;
                    headerScrollDirection = direction;
                }
                headerScrollDistance += Math.abs(delta);
                const focusedElement = document.activeElement;
                const interacting = header.querySelector('details[open]') || (header.contains(focusedElement) && focusedElement.matches('input, textarea, select, :focus-visible'));
                if (!window.matchMedia('(min-width: 1024px)').matches || scrollY < header.offsetHeight + 24 || interacting) {
                    setHeaderScrollState(header, false);
                } else if (direction < 0 && headerScrollDistance >= 6) {
                    setHeaderScrollState(header, false);
                } else if (direction > 0 && headerScrollDistance >= 20) {
                    setHeaderScrollState(header, true);
                }
                updateStorefrontHeaderLayout();
            });
        }, { passive: true });
        document.addEventListener('focusin', function(event) {
            const header = event.target.closest('.storefront-header');
            if (header) {
                setHeaderScrollState(header, false);
            }
        });
        window.addEventListener('resize', initializeHeaderScroll);
        window.addEventListener('pageshow', initializeHeaderScroll);
        document.addEventListener('livewire:navigated', initializeHeaderScroll);
        initializeHeaderScroll();
        document.addEventListener('click', function(event) {
            const searchToggle = event.target.closest('[data-storefront-search-toggle]');
            if (searchToggle) {
                const header = searchToggle.closest('.storefront-header');
                const isSearchOpen = header.dataset.searchOpen !== 'true';
                header.dataset.searchOpen = String(isSearchOpen);
                searchToggle.setAttribute('aria-expanded', String(isSearchOpen));
                searchToggle.setAttribute('aria-label', isSearchOpen ? 'Close search' : 'Open search');
                if (isSearchOpen) {
                    header.querySelector('#storefront-search').focus();
                }
            }
            updateStorefrontHeaderLayout();
            document.querySelectorAll('.storefront-header details[open]').forEach(function(details) {
                if (!details.contains(event.target) || event.target.closest('a')) {
                    details.open = false;
                }
            });
        });
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const header = document.querySelector('.storefront-header[data-search-open="true"]');
                if (header && header.contains(document.activeElement)) {
                    header.dataset.searchOpen = 'false';
                    const searchToggle = header.querySelector('[data-storefront-search-toggle]');
                    searchToggle.setAttribute('aria-expanded', 'false');
                    searchToggle.setAttribute('aria-label', 'Open search');
                    if (window.matchMedia('(max-width: 767px)').matches) {
                        searchToggle.focus();
                    }
                    updateStorefrontHeaderLayout();
                }
                document.querySelectorAll('.storefront-header details[open]').forEach(function(details) {
                    if (details.contains(document.activeElement)) {
                        details.querySelector('summary').focus();
                    }
                    details.open = false;
                });
            }
        });
    }
    runWhenJQueryReady(function($) {
        // Desktop search
        var $desktopInput = $("input[name='search']").filter(function() {
            return $(this).closest('.site-header__search').length > 0;
        });
        var $desktopSuggestions = $('#desktop-search-suggestions');
        $desktopInput.on('input', function() {
            var query = $(this).val();
            if (query.length < 2) {
                $desktopSuggestions.hide();
                return;
            }
            $.get('/api/search/suggestions.json', {
                q: query
            }, function(data) {
                var html = '';
                if (data && data.length) {
                    html += '<ul style="list-style:none;margin:0;padding:0">';
                    data.forEach(function(item) {
                        html +=
                            '<li style="padding:8px 12px;border-bottom:1px solid #eee">';
                        html += '<a href="/products/' + item.slug +
                            '" style="display:flex;align-items:center;text-decoration:none;color:#222">';
                        if (item.thumbnail) html += '<img src="' + item.thumbnail +
                            '" style="width:32px;height:32px;margin-right:8px">';
                        html += '<span>' + item.name + '</span>';
                        html += '</a>';
                        html += '</li>';
                    });
                    html += '</ul>';
                } else {
                    html = '<div style="padding:12px;color:#888">No products found</div>';
                }
                $desktopSuggestions.html(html).show();
                $desktopSuggestions.css({
                    width: '100%',
                    left: 0,
                    top: 'calc(100% + 6px)'
                });
            });
        });
        $desktopInput.on('blur', function() {
            setTimeout(function() {
                $desktopSuggestions.hide();
            }, 200);
        });

        // Mobile search
        var $mobileInput = $('#bb-search-input');
        var $mobileSuggestions = $('#mobile-search-suggestions');
        $mobileInput.on('input', function() {
            var query = $(this).val();
            if (query.length < 2) {
                $mobileSuggestions.hide();
                return;
            }
            $.get('/api/search/suggestions.json', {
                q: query
            }, function(data) {
                var html = '';
                if (data && data.length) {
                    html += '<ul style="list-style:none;margin:0;padding:0">';
                    data.forEach(function(item) {
                        html +=
                            '<li style="padding:8px 12px;border-bottom:1px solid #eee">';
                        html += '<a href="/products/' + item.slug +
                            '" style="display:flex;align-items:center;text-decoration:none;color:#222">';
                        if (item.thumbnail) html += '<img src="' + item.thumbnail +
                            '" style="width:32px;height:32px;margin-right:8px">';
                        html += '<span>' + item.name + '</span>';
                        html += '</a>';
                        html += '</li>';
                    });
                    html += '</ul>';
                } else {
                    html = '<div style="padding:12px;color:#888">No products found</div>';
                }
                $mobileSuggestions.html(html).show();
                $mobileSuggestions.css({
                    width: $mobileInput.outerWidth() || $mobileInput[0].offsetWidth,
                    left: $mobileInput.position().left,
                    top: $mobileInput.position().top + $mobileInput.outerHeight()
                });
            });
        });
        $mobileInput.on('blur', function() {
            setTimeout(function() {
                $mobileSuggestions.hide();
            }, 200);
        });
    });
</script>
{{-- Bootstrap is needed early for UI components, but can be deferred --}}
<script src="{{ cdnAsset('bootstrap.js', 'strokya/vendor/bootstrap-4.2.1/js/bootstrap.bundle.min.js') }}"
    crossorigin="anonymous" referrerpolicy="no-referrer" defer
    onerror="window.__loadLocalAsset && window.__loadLocalAsset('bootstrap')"></script>
{{-- Owl Carousel can be deferred - carousels load after initial render --}}
<script src="{{ cdnAsset('owl-carousel.js', 'strokya/vendor/owl-carousel-2.3.4/owl.carousel.min.js') }}"
    crossorigin="anonymous" referrerpolicy="no-referrer" defer
    onerror="window.__loadLocalAsset && window.__loadLocalAsset('owl')"></script>
{{-- <script src="{{ asset('strokya/vendor/nouislider-12.1.0/nouislider.min.js') }}"></script> --}}
<!-- <script src="{{ asset('strokya/js/number.js') }}"></script> -->
{{-- Main.js can be deferred - most functionality is not critical for initial render --}}
<script src="{{ versionedAsset('strokya/js/main.js') }}" defer></script>
{{-- Bootstrap notify can be deferred - notifications appear after page load --}}
<script src="{{ versionedAsset('assets/js/notify/bootstrap-notify.min.js') }}" defer></script>
<!-- Include AlgoliaSearch JS Client and autocomplete.js library -->
{{-- <script src="https://cdn.jsdelivr.net/npm/algoliasearch@3/dist/algoliasearchLite.min.js"></script>
<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
<script src="{{ asset('strokya/js/algolia.js') }}"></script>
<script src="{{ asset('strokya/vendor/jquery.bootstrap-growl.min.js') }}"></script> --}}
