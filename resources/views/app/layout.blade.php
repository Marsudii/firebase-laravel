<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Firebase Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-messaging.js"></script>
    <script src="{{ url('init-firebase.js') }}"></script>
</head>

<body>
    {{-- TITLE  + IMAGE FIREBASE --}}
    <div class="col-md-12 text-center">
        <br />
        <div class="d-flex justify-content-center align-items-center gap-3 my-2">
            <img src="https://www.gstatic.com/mobilesdk/240501_mobilesdk/firebase_28dp.png" alt="Firebase Logo"
                style="height: 40px;">
            <img src="https://www.gstatic.com/mobilesdk/240926_mobilesdk/workmark_light_grey.svg"
                alt="Firebase Wordmark" style="height: 40px;">
        </div>
        <br />
        <p>Firebase Cloud Messaging Send notifications via FCM and Topic</p>
    </div>
    <div class="container">
        <div class="mb-3">
            <label for="your-fcm-token" class="form-label">Your Fcm Token</label>
            <div class="input-group">
                <input type="text" id="your-fcm-token" class="form-control" value="abc123xyz456" readonly>
                <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard()">Copy</button>
            </div>
        </div>
        @yield('content')
    </div>
    {{-- API JS LARAVEL --}}
    <script src="{{ url('client-side.js') }}"></script>
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('utils.script')

    <!--  Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
