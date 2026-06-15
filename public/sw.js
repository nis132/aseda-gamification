// =============================================
// INSTALL — SW langsung aktif tanpa tunggu reload
// =============================================
self.addEventListener('install', function (event) {
    console.log('[SW] Install');
    self.skipWaiting();
});

// =============================================
// ACTIVATE — ambil alih semua client sekarang
// =============================================
self.addEventListener('activate', function (event) {
    console.log('[SW] Activate');
    event.waitUntil(clients.claim());
});

// =============================================
// FETCH — wajib ada agar browser aktifkan SW
// =============================================
self.addEventListener('fetch', function (event) {
    // passthrough, tidak cache apapun
});

// =============================================
// PUSH — terima notifikasi dari server
// =============================================
self.addEventListener('push', function (event) {
    console.log('[SW] Push event diterima');

    if (Notification.permission !== 'granted') {
        console.warn('[SW] Izin notifikasi belum diberikan');
        return;
    }

    let data = {};
    try {
        data = event.data ? event.data.json() : {};
    } catch (e) {
        data = { body: event.data ? event.data.text() : 'Ada pesan baru untukmu.' };
    }

    const title   = data.title || 'Tantangan Baru!';
    const options = {
        body    : data.body || 'Ayo cek aplikasi ASEDA sekarang.',
        icon    : data.icon || '/storage/logo_aseda.webp',
        badge   : '/storage/logo_aseda.webp',
        vibrate : [200, 100, 200],
        tag     : 'tantangan-baru',
        renotify: true,
        data    : {
            url: (data.data && data.data.url) ? data.data.url : (data.url || '/siswa/tantangan')
        }
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// =============================================
// NOTIFICATION CLICK — buka / fokus tab tantangan
// =============================================
self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const targetUrl = event.notification.data.url || '/siswa/tantangan';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (const client of clientList) {
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});