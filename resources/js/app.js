import './bootstrap';
import '@hotwired/turbo';

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;

Alpine.plugin(intersect);
Alpine.plugin(collapse);

// Alpine.start() only bootstraps [x-data] roots by default. Register
// [x-intersect] as an additional root selector so standalone reveal
// elements (no x-data wrapper) get their x-intersect directive initialized.
// NB: the directive attribute is literally "x-intersect.once" (the ".once"
// modifier is part of the attribute name), so it must be matched with a
// CSS-escaped selector.
Alpine.addRootSelector(() => '[x-intersect\\.once], [x-intersect]');

// ==========================================================================
// Landing Page: Hero Parallax (scroll-driven, rAF-throttled, reduced-motion safe)
// ==========================================================================
Alpine.data('heroParallax', () => ({
    y: 0,
    ticking: false,
    reduced: false,
    init() {
        this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.update();
        if (!this.reduced) {
            this.observeMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            this.observeMotion.addEventListener?.('change', (e) => {
                this.reduced = e.matches;
                if (this.reduced) this.y = 0;
            });
        }
    },
    onScroll() {
        if (this.ticking || this.reduced) return;
        this.ticking = true;
        requestAnimationFrame(() => {
            // Cap at hero height so bindings stop updating once the hero is off-screen
            this.y = Math.min(window.scrollY || 0, window.innerHeight || 0);
            this.ticking = false;
        });
    },
    update() {
        // Ensure the cap logic is applied on first paint too
        this.y = Math.min(window.scrollY || 0, window.innerHeight || 0);
    },
}));

// ==========================================================================
// Landing Page: Count-up numbers (stats cards)
// ==========================================================================
Alpine.data('countUp', (target, duration = 1600, suffix = '') => ({
    value: target, // server-rendered value shown by default (SEO / no-JS fallback)
    start() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        const from = 0;
        this.value = from; // start from zero (avoid flash of the full number)
        const startTime = performance.now();
        const step = (now) => {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
            this.value = Math.round(from + (target - from) * eased);
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    },
    get display() {
        return this.value.toLocaleString('id-ID') + suffix;
    },
}));

Alpine.start();

// Register service worker for offline shell / installability (production only).
// Guarded: requires HTTPS or localhost to avoid caching across environments.
if (
    'serviceWorker' in navigator &&
    import.meta.env.PROD &&
    (location.protocol === 'https:' || ['localhost', '127.0.0.1', '::1'].includes(location.hostname))
) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {
            /* SW unavailable - app continues to work normally. */
        });
    });
}
