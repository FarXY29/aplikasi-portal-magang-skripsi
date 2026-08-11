import './bootstrap';
import '@hotwired/turbo';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

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
