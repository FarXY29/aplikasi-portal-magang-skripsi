/**
 * Service Worker — Portal Magang Pemerintah Kota Banjarmasin
 *
 * Lightweight, safe offline shell with LRU cache bounds & sensitive path exclusion.
 */
const CACHE_NAME = 'portal-magang-v2';
const CORE_ASSETS = [
  '/',
  '/manifest.json',
  '/images/icon-192.png',
  '/images/icon-512.png',
  '/favicon.ico'
];

const MAX_RUNTIME_ENTRIES = 30;

// Trim cache to prevent memory/storage bloat on client devices
async function trimCache(cacheName, maxItems) {
  try {
    const cache = await caches.open(cacheName);
    const keys = await cache.keys();
    if (keys.length > maxItems) {
      await cache.delete(keys[0]);
      trimCache(cacheName, maxItems);
    }
  } catch (e) {
    // Graceful fallback if cache access is restricted
  }
}

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(CORE_ASSETS))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(
        keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
      ))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const request = event.request;
  if (request.method !== 'GET') return;

  const url = new URL(request.url);
  if (url.origin !== self.location.origin) return;

  // Never cache sensitive documents, auth endpoints, or live scanning
  if (
    url.pathname.startsWith('/storage-access/') ||
    url.pathname.startsWith('/scan-qr') ||
    url.pathname === '/logout'
  ) {
    return;
  }

  // Navigations: network-first, fall back to cached shell (offline).
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then((response) => {
          if (response && response.status === 200) {
            const copy = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(request, copy);
              trimCache(CACHE_NAME, MAX_RUNTIME_ENTRIES);
            });
          }
          return response;
        })
        .catch(() =>
          caches.match(request).then((cached) => cached || caches.match('/'))
        )
    );
    return;
  }

  // Static assets: stale-while-revalidate.
  event.respondWith(
    caches.match(request).then((cached) => {
      const fetched = fetch(request)
        .then((response) => {
          if (response && response.status === 200) {
            const copy = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(request, copy);
              trimCache(CACHE_NAME, MAX_RUNTIME_ENTRIES);
            });
          }
          return response;
        })
        .catch(() => cached);
      return cached || fetched;
    })
  );
});
