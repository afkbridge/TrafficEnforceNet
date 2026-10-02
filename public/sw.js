// Place in /public/sw.js (scope "/")
const CACHE = "trafficenforcenet-v1";

self.addEventListener("install", function () {
    self.skipWaiting();
});

self.addEventListener("activate", function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys
                    .filter(function (key) { return key !== CACHE; })
                    .map(function (key) { return caches.delete(key); })
            );
        }).then(function () { return self.clients.claim(); })
    );
});

self.addEventListener("fetch", function (event) {
    const request = event.request;

    // Never touch POSTs (ticket submit, OCR, sync) or the geocoder
    if (request.method !== "GET") return;
    if (request.url.includes("nominatim.openstreetmap.org")) return;

    // Network first, fall back to the last cached copy when offline
    event.respondWith(
        fetch(request)
            .then(function (response) {
                if (response && (response.ok || response.type === "opaque")) {
                    const copy = response.clone();
                    caches.open(CACHE).then(function (cache) {
                        cache.put(request, copy);
                    });
                }
                return response;
            })
            .catch(function () {
                return caches.match(request);
            })
    );
});