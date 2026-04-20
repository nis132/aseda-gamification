self.addEventListener('push', function (event) {
    console.log('Push event diterima!');

    if (!(self.Notification && self.Notification.permission === 'granted')) {
        console.error('Izin notifikasi tidak diberikan atau tidak didukung.');
        return;
    }

    let data = {};
    try {
        // Cek apakah ada data payload dari server
        data = event.data ? event.data.json() : {};
    } catch (e) {
        console.warn('Payload bukan JSON, mencoba teks biasa...');
        data = { body: event.data ? event.data.text() : 'Ada pesan baru untukmu' };
    }

    const title = data.title || "Tantangan Baru!";
    const options = {
        body: data.body || "Ayo cek aplikasi ASEDA sekarang.",
        icon: data.icon || '/icons/icon-192x192.png',
        badge: '/icons/icon-192x192.png',
        data: {
            // Pastikan URL selalu ada agar tidak error saat diklik
            url: data.url || (data.data ? data.data.url : '/')
        }
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});