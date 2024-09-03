var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
    'lte/dist/css/adminlte.min.css',
    'app-assets/asset/css/screen.css',
    'app-assets/asset/css/comon.css',
    'app-assets/asset/js/bootstrap-4.0.0/bootstrap.min.css',
    'app-assets/asset/css/styles.css',
    'app-assets/asset/css/style.deory.css',
    'app-assets/asset/css/pager.css',
    'app-assets/asset/css/animate.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
    'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
    '/images/icons/icon-72x72.png',
    '/images/icons/icon-96x96.png',
    '/images/icons/icon-128x128.png',
    '/images/icons/icon-144x144.png',
    '/images/icons/icon-152x152.png',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-384x384.png',
    '/images/icons/icon-512x512.png',
    '/images/icons/splash-640x1136.png',
    '/images/icons/splash-750x1334.png',
    '/images/icons/splash-828x1792.png',
    '/images/icons/splash-1125x2436.png',
    '/images/icons/splash-1242x2208.png',
    '/images/icons/splash-1242x2688.png',
    '/images/icons/splash-1536x2048.png',
    '/images/icons/splash-1668x2224.png',
    '/images/icons/splash-1668x2388.png',
    '/images/icons/splash-2048x2732.png',
    'app-assets/asset/images/favicon.png',
    'app-assets/asset/images/phone-white.png',
    'app-assets/asset/images/logo-header.png',
    'app-assets/asset/images/since.png',
    'app-assets/asset/images/logo-header-sticky.png'
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});