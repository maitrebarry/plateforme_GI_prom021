/* NGAKODON — Service Worker (PWA)
 * Strategie prudente pour une app PHP dynamique (auth + CSRF) :
 *  - Navigations (HTML)  : RESEAU D'ABORD  -> contenu toujours frais ; repli page hors-ligne.
 *  - Assets statiques    : CACHE D'ABORD   -> rapide ; mise a jour en arriere-plan.
 *  Aucune page HTML n'est mise en cache (evite de servir une session/CSRF perimes).
 */
const CACHE = 'nkadon-static-v2';
const OFFLINE_URL = './offline.html';
const PRECACHE = [
    './offline.html',
    './assets/icons/nk-192.png'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE)
            .then((cache) => cache.addAll(PRECACHE))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.map((k) => (k === CACHE ? null : caches.delete(k)))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const req = event.request;
    if (req.method !== 'GET') {
        return;
    }

    let url;
    try {
        url = new URL(req.url);
    } catch (e) {
        return;
    }

    // On ne gere que notre propre origine.
    if (url.origin !== self.location.origin) {
        return;
    }

    // Navigations (pages) : reseau d'abord, repli hors-ligne.
    if (req.mode === 'navigate') {
        event.respondWith(
            fetch(req).catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    // Assets statiques : cache d'abord + revalidation en arriere-plan.
    if (/\.(?:css|js|mjs|png|jpg|jpeg|gif|webp|svg|ico|woff2?|ttf|eot)$/i.test(url.pathname)) {
        event.respondWith(
            caches.match(req).then((cached) => {
                const fromNetwork = fetch(req)
                    .then((res) => {
                        if (res && res.status === 200 && res.type === 'basic') {
                            const copy = res.clone();
                            caches.open(CACHE).then((cache) => cache.put(req, copy));
                        }
                        return res;
                    })
                    .catch(() => cached);
                return cached || fromNetwork;
            })
        );
    }
});
