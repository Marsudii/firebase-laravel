document.addEventListener('DOMContentLoaded', function () {
    // Firebase configuration
    const firebaseConfig = JSON.parse(atob(window.SETUP_INIT_FIREBASE));

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // client message handler
    messaging.onMessage((payload) => {
        //  console.log('Client side message received:', payload.notification);
        Swal.fire({
            title: payload.notification.title || 'Notifikasi Baru',
            text: payload.notification.body || 'Ada pesan baru untukmu.',
            icon: 'info',
            confirmButtonText: 'Tutup'
        });
    });


    // Request permission and get token
    function initializeMessaging() {
        return messaging.requestPermission()
            .then(() => messaging.getToken({
                vapidKey: 'BLAc5HHMzMl2mw03hVVTJB-UHN-rAQZ3Xy0KYPjqhpEsDkpOgkCMiUP2z5Vydjln4YVlEF9WGUg0vXjr0_ZVoeg'
            }))
            .then((currentToken) => {
                if (currentToken) {
                    console.log('FCM Token:', currentToken);
                    document.getElementById('your-fcm-token').value = currentToken;

                }
                return currentToken;
            });
    }


    // Register service worker and initialize
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/firebase-messaging-sw.js')
            .then(() => initializeMessaging())
            .catch(error => console.error('Service Worker registration failed:', error));
    }

    // Token refresh handler
    messaging.onTokenRefresh(() => {
        messaging.getToken({ vapidKey: 'BLAc5HHMzMl2mw03hVVTJB-UHN-rAQZ3Xy0KYPjqhpEsDkpOgkCMiUP2z5Vydjln4YVlEF9WGUg0vXjr0_ZVoeg' })
            .then(saveTokenToServer)
            .catch(error => console.error('Token refresh failed:', error));
    });
});
