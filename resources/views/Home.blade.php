<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Firebase Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="style.css" rel="stylesheet">

    <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-messaging.js"></script>
    <script src="/init-firebase.js"></script>
</head>
<body>
    <h1>LARAVEL FIREBASE</h1>
    <div class="card">
        <h2>SEND BY FCM</h2>
        <form id="fcm-form">
            <div>
                <label for="token">Token</label>
                <input type="text" id="token" name="token" placeholder="Token"/>
            </div>
            <div>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Title"/>
            </div>
            <div>
                <label for="body">Body</label>
                <input type="text" id="body" name="body" placeholder="Body"/>
            </div>
            <button type="submit">Send</button>
        </form>
    </div>
    <div class="card">
        <h2>SEND BY TOPIC</h2>
        <form id="topic-form">
            <div>
                <label for="topic">Topic</label>
                <input type="text" id="topic" name="topic" placeholder="Topic"/>
            </div>
            <div>
                <label for="title-topic">Title</label>
                <input type="text" id="title-topic" name="title" placeholder="Title"/>
            </div>
            <div>
                <label for="body-topic">Body</label>
                <input type="text" id="body-topic" name="body" placeholder="Body"/>
            </div>
            <button type="submit">Send</button>
        </form>
    </div>


{{-- INIT FIREBASE SETUP --}}


{{-- FIREBASE JS --}}
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', () => {

    // POST DATA API
    async function postData(url = '', data = {}) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        return response.json();
    }

    // === FORM 1: SEND BY FCM (per Token) ===
    const fcmForm = document.getElementById('fcm-form');
    if (fcmForm) {
        fcmForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const token = document.getElementById('token').value;
            const title = document.getElementById('title').value;
            const body = document.getElementById('body').value;
            try {
                const url = "{{ route('send-fcm') }}"; // Ganti sesuai kebutuhan
                const result = await postData(url, { token, title, body });
                if (result.success) {
                    alert('Notification sent successfully!');
                } else {
                    alert('Error: ' + (result.message || 'Failed to send notification.'));
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while sending the notification.');
            }
        });
    }

    // === FORM 2: SEND BY TOPIC ===
    const topicForm = document.getElementById('topic-form');
    if (topicForm) {
        topicForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const topic = document.getElementById('topic').value;
            const title = document.getElementById('title-topic').value;
            const body = document.getElementById('body-topic').value;
            try {
                const url = "{{ route('send-topic') }}"; // Ganti sesuai kebutuhan
                const result = await postData(url, { topic, title, body });
                if (result.success) {
                    alert('Notification sent successfully!');
                } else {
                    alert('Error: ' + (result.message || 'Failed to send notification.'));
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while sending the notification.');
            }
        });
    }
});
</script>
</body>
</html>
