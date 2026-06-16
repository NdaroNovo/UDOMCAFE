/**
 * CIVE Cafeteria Service Worker
 * Provides offline support for the menu page
 */

const CACHE_NAME = 'cive-cafeteria-v1';
const urlsToCache = [
    '/',
    '/index.php',
    '/assets/css/style.css',
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap'
];

// Install event - cache assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
            .then(() => self.skipWaiting())
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - serve from cache when offline
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Return cached version or fetch from network
                if (response) {
                    return response;
                }
                return fetch(event.request);
            })
            .catch(() => {
                // If both fail, return offline fallback
                if (event.request.mode === 'navigate') {
                    return caches.match('/index.php');
                }
            })
    );
});
