
// Actualizar la versión para refrescar la caché
const cacheVersion = 'v2';

// Instala el Worker y inicializa la caché
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(cacheVersion).then(cache => {
            return cache.addAll([
                '/',
                '/css/app.css',
                '/js/app.js'
            ]).then(() => self.skipWaiting());
        })
    );
});


// Activa el Worker y elimina la caché antigua
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheName !== cacheVersion) {
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(function() {
            return self.clients.claim();
        })
    );
});


// Obtiene el Response desde la caché o desde la red
self.addEventListener('fetch', event => {

    var request = event.request;

    if (request.url.indexOf('/api/') < 0) {

        if (request.headers.get('accept').includes('text/html')) {
            request = '/'; // SPA
        }

        event.respondWith(
            caches.match(request).then(response => {
                return response || fetch(event.request);
            })
        );
    }
});
