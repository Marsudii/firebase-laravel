document.addEventListener('DOMContentLoaded', function () {
    function getDeviceInfo() {
        const userAgent = navigator.userAgent;
        let device = "Unknown Device";
        let os = "Unknown OS";

        // Detect OS
        if (userAgent.match(/Windows NT 10.0/)) {
            os = "Windows 10";
        } else if (userAgent.match(/Windows NT 6.3/)) {
            os = "Windows 8.1";
        } else if (userAgent.match(/Windows NT 6.2/)) {
            os = "Windows 8";
        } else if (userAgent.match(/Windows NT 6.1/)) {
            os = "Windows 7";
        } else if (userAgent.match(/Mac OS X/)) {
            os = "Mac OS X";
        } else if (userAgent.match(/Android/)) {
            os = "Android";
        } else if (userAgent.match(/iPhone/)) {
            os = "iOS";
        } else if (userAgent.match(/iPad/)) {
            os = "iOS";
        } else if (userAgent.match(/Linux/)) {
            os = "Linux";
        }

        // Detect Device Model
        if (userAgent.match(/iPhone/)) {
            device = "iPhone";
        } else if (userAgent.match(/iPad/)) {
            device = "iPad";
        } else if (userAgent.match(/Android/)) {
            const androidDevice = userAgent.match(/Android\\s+([\\d.]+).*;\\s+([a-zA-Z0-9\\s]+)\\s+Build/);
            if (androidDevice) {
                device = androidDevice[2];
            } else {
                device = "Android Device";
            }
        } else if (userAgent.match(/Windows/)) {
            device = "Windows PC";
        } else if (userAgent.match(/Macintosh/)) {
            device = "Macintosh";
        }

        return { device, os };
    }

    // Firebase configuration
    const firebaseConfig = {
        apiKey: "",
        authDomain: "react-native-237ab.firebaseapp.com",
        projectId: "react-native-237ab",
        storageBucket: "",
        messagingSenderId: "606955867336",
        appId: "",
        measurementId: ""
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Service worker registration and FCM token retrieval
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('firebase-messaging-sw.js')
            .then((registration) => {
                console.log('Service Worker registered');
                // Request permission for notifications
                messaging.requestPermission()
                    .then(() => {
                        return messaging.getToken({ vapidKey: 'urG8nt9NFUPOldmyEUI3_REeaanw' });
                    })
                    .then((currentToken) => {
                        if (currentToken) {
                            console.log("FCM Token:", currentToken);
                            const deviceInfo = getDeviceInfo();
                            console.log(`Device: ${deviceInfo.device}`);
                            // console.log(`OS: ${deviceInfo.os}`);

                            // Send the token and device info to the server using Fetch API
                            fetch('localhost:8000', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    fcm_token: currentToken,
                                    device: deviceInfo.device,
                                    os: deviceInfo.os
                                })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    console.log('Successfully');
                                })
                                .catch((error) => {
                                    console.error('Error inserting data:', error);
                                });
                        } else {
                            console.log("No registration token available.");
                        }
                    })
                    .catch((error) => {
                        console.error("Unable to get permission to notify:", error);
                        if (error.code === 'messaging/permission-blocked') {
                            console.log("Notification permission blocked. Please enable it in your browser settings.");
                        }
                    });
            })
            .catch((error) => {
                console.error('Service Worker registration failed:', error);
            });
    } else {
        console.log('Service Worker is not supported');
    }
});
