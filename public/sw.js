const CACHE_NAME = "moneti-v4";
const STATIC_CACHE = [
    "/offline.html",
    "/manifest.json?v=4",
    "/favicon.ico?v=4",
    "/favicon-16x16.png?v=4",
    "/favicon-32x32.png?v=4",
    "/apple-touch-icon.png?v=4",
    "/icons/icon-192.png?v=4",
    "/icons/icon-512.png?v=4",
    "/logo-nav.png?v=4"
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_CACHE))
    );
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            )
        )
    );
    self.clients.claim();
});

self.addEventListener("fetch", (event) => {
    const request = event.request;

    if (request.method !== "GET") return;

    const url = new URL(request.url);

    if (request.mode === "navigate") {
        event.respondWith(
            fetch(request).catch(() => caches.match("/offline.html"))
        );
        return;
    }

    const isStaticAsset =
        url.pathname.startsWith("/icons/") ||
        url.pathname === "/favicon.ico" ||
        url.pathname === "/favicon-16x16.png" ||
        url.pathname === "/favicon-32x32.png" ||
        url.pathname === "/apple-touch-icon.png" ||
        url.pathname === "/logo-nav.png" ||
        url.pathname === "/manifest.json";

    if (isStaticAsset) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                return cachedResponse || fetch(request);
            })
        );
    }
});