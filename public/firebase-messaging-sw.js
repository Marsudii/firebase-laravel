importScripts('https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.1.1/firebase-messaging.js');

const firebaseConfig = JSON.parse(atob("eyJhcGlLZXkiOiJBSXphU3lDLUNZcmFxdTZkMldZY3QyZ3VvRXVrRzRmWE9qV2dGSEUiLCJhdXRoRG9tYWluIjoiZmlyLWxhcmF2ZWwtNjQ3ZWQuZmlyZWJhc2VzdG9yYWdlLmFwcCIsInByb2plY3RJZCI6ImZpci1sYXJhdmVsLTY0N2VkIiwic3RvcmFnZUJ1Y2tldCI6ImZpci1sYXJhdmVsLTY0N2VkLmFwcHNwb3QuY29tIiwibWVzc2FnaW5nU2VuZGVySWQiOiI2OTYwNzg4MTI0OTciLCJhcHBJZCI6IjE6Njk2MDc4ODEyNDk3OndlYjowNWRjYjg4YzMyZGFhYjFhNDhmZjcwIn0="));


// const firebaseConfig = {
//     apiKey: 'AIzaSyC-CYraqu6d2WYct2guoEukG4fXOjWgFHE',
//     authDomain: 'fir-laravel-647ed.firebasestorage.app',
//     projectId: 'fir-laravel-647ed',
//     storageBucket: 'fir-laravel-647ed.appspot.com',
//     messagingSenderId: '696078812497',
//     projectId: 'fir-laravel-647ed',
//     appId: '1:696078812497:web:05dcb88c32daab1a48ff70',
// }


firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// Background message handler
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message', JSON.stringify(payload, null, 2));

    // Kirim pesan ke semua tab yang terbuka
    self.clients.matchAll().then(clients => {
        clients.forEach(client => {
            client.postMessage({
                type: 'NEW_FCM_MESSAGE',
                payload: payload
            });
        });
    });

    // Tampilkan notifikasi sistem
    const notificationTitle = payload.notification?.title || 'Pesan Baru';
    const notificationOptions = {
        body: payload.notification?.body || 'Anda menerima pesan baru',
        icon: '/icons/notification-icon.png',
        data: payload.data || {}
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const urlToOpen = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window' }).then(windowClients => {
            const matchingClient = windowClients.find(client =>
                client.url === urlToOpen
            );
            return matchingClient
                ? matchingClient.focus()
                : clients.openWindow(urlToOpen);
        })
    );
});
