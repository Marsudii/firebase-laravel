<script>
    // === COPY TO CLIPBOARD ===
    function copyToClipboard() {
        const input = document.getElementById('your-fcm-token');
        input.select();
        input.setSelectionRange(0, 99999); // untuk mobile
        document.execCommand("copy");

    }
    // LOAD DOM CONTENT
    document.addEventListener('DOMContentLoaded', () => {

        // == POST DATA API LARAVEL ===
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


        // === FORM 1: SEND BY FCM (Single Token) ===
        const singleFcm = document.getElementById('single-fcm-form');
        if (singleFcm) {
            singleFcm.addEventListener('submit', async (event) => {
                event.preventDefault();
                // GET VALUE FROM INPUT
                const title = document.getElementById('fcm-single-title').value;
                const text = document.getElementById('fcm-single-text').value;
                const token = document.getElementById('fcm-single-token').value;
                const payload = {
                    notification_title: title,
                    notification_text: text,
                    token: token
                }
                try {
                    const url = "{{ route('fcm') }}";
                    const result = await postData(url, payload);
                    console.log(result);
                    if (result.status === true) {
                        console.log(result.message);
                    } else {
                        console.error(result.errors);
                        Swal.fire({
                            title: 'Error!',
                            text: `${result.errors}`,
                            icon: 'error',
                            confirmButtonText: 'Close'
                        })
                    }
                } catch (err) {
                    console.error(err);
                }
            });
        }


        // === FORM 2: SEND BY FCM (Multiple Tokens) ===
        const multiFcm = document.getElementById('multidevice-fcm-form');
        if (multiFcm) {
            // === + ADD TOKEN BUTTON ===
            const addTokenBtn = document.getElementById('add-token-multidevice');
            if (addTokenBtn) {
                addTokenBtn.addEventListener('click', function() {
                    const container = document.getElementById('token-container-multidevice');
                    const div = document.createElement('div');
                    div.classList.add('input-group', 'mb-2');
                    div.innerHTML = `
                            <input type="text" name="fcm-multidevice-token[]" class="form-control" placeholder="Fcm Token">
                            <button type="button" class="btn btn-outline-danger remove-token-multidevice">−</button>
                        `;
                    container.appendChild(div);
                    // count appendChild max 500
                    if (container.children.length >= 500) {
                        addTokenBtn.disabled = true;
                        addTokenBtn.classList.add('disabled');
                        alert('Maksimal 500 token');
                    }
                });
            }
            // === REMOVE TOKEN BUTTON ===
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-token-multidevice')) {
                    e.target.parentElement.remove();
                }
            });
            // === SUBMIT BUTTON ===
            multiFcm.addEventListener('submit', async (event) => {
                event.preventDefault();
                // GET VALUE FROM INPUT
                const title = document.getElementById('fcm-multidevice-title').value;
                const text = document.getElementById('fcm-multidevice-text').value;
                const tokens = Array.from(document.querySelectorAll(
                        'input[name="fcm-multidevice-token[]"]'))
                    .map(input => input.value.trim())
                    .filter(token => token !== '');
                const payload = {
                    notification_title: title,
                    notification_text: text,
                    tokens: tokens
                }

                try {
                    const url = "{{ route('fcm-multicast') }}"; // Ganti sesuai kebutuhan
                    const result = await postData(url, payload);
                    if (result.status === true) {
                        console.log(result.message);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: result.errors.map(e => `Token: ${e.token} - ${e.error}`)
                                .join('\n'),
                            icon: 'error',
                            confirmButtonText: 'Close'
                        })
                    }
                } catch (err) {
                    Swal.fire({
                        title: 'Error!',
                        text: err,
                        icon: 'error',
                        confirmButtonText: 'Close'
                    })
                }
            });
        }


        // === FORM 3: SUBSCRIBE TOPIC (Multiple Tokens) ===
        const subscribeTopic = document.getElementById('subscribe-topic-form');
        if (subscribeTopic) {
            // === + ADD TOKEN BUTTON ===
            const addTokenBtn = document.getElementById('add-token-subscribe');
            if (addTokenBtn) {
                addTokenBtn.addEventListener('click', function() {
                    const container = document.getElementById('token-container-subscribe');
                    const div = document.createElement('div');
                    div.classList.add('input-group', 'mb-2');
                    div.innerHTML = `
                            <input type="text" name="fcm-subscribe-token[]" class="form-control" placeholder="Fcm Token">
                            <button type="button" class="btn btn-outline-danger remove-token-subscribe">−</button>
                        `;
                    container.appendChild(div);
                    // count appendChild max 500
                    if (container.children.length >= 500) {
                        addTokenBtn.disabled = true;
                        addTokenBtn.classList.add('disabled');
                        alert('Maksimal 500 token');
                    }
                });
            }
            // === REMOVE TOKEN BUTTON ===
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-token-subscribe')) {
                    e.target.parentElement.remove();
                }
            });
            // === SUBMIT BUTTON ===
            subscribeTopic.addEventListener('submit', async (event) => {
                event.preventDefault();
                // GET VALUE FROM INPUT
                const topic = document.getElementById('fcm-subscribe-topic').value;
                const tokens = Array.from(document.querySelectorAll(
                        'input[name="fcm-subscribe-token[]"]'))
                    .map(input => input.value.trim())
                    .filter(token => token !== '');
                const payload = {
                    topic: topic,
                    tokens: tokens
                }

                try {
                    const url = "{{ route('topic-subscribe') }}"; // Ganti sesuai kebutuhan
                    const result = await postData(url, payload);
                    if (result.status === true) {
                        Swal.fire({
                            title: 'Success!',
                            text: result.message,
                            icon: 'info',
                            confirmButtonText: 'Tutup'
                        });
                        // console.log(result.message);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: result.errors.map(e => `Token: ${e.token} - ${e.error}`)
                                .join('\n'),
                            icon: 'error',
                            confirmButtonText: 'Close'
                        })
                    }
                } catch (err) {
                    console.error(err);
                }
            });
        }




        // === FORM 4: UNSUBSCRIBE TOPIC (Multiple Tokens) ===
        const unsubscribeTopic = document.getElementById('unsubscribe-topic-form');
        if (unsubscribeTopic) {
            // === + ADD TOKEN BUTTON ===
            const addTokenBtn = document.getElementById('add-token-unsubscribe');
            if (addTokenBtn) {
                addTokenBtn.addEventListener('click', function() {
                    const container = document.getElementById('token-container-unsubscribe');
                    const div = document.createElement('div');
                    div.classList.add('input-group', 'mb-2');
                    div.innerHTML = `
                            <input type="text" name="fcm-unsubscribe-token[]" class="form-control" placeholder="Fcm Token">
                            <button type="button" class="btn btn-outline-danger remove-token-unsubscribe">−</button>
                        `;
                    container.appendChild(div);
                    // count appendChild max 500
                    if (container.children.length >= 500) {
                        addTokenBtn.disabled = true;
                        addTokenBtn.classList.add('disabled');
                        alert('Maksimal 500 token');
                    }
                });
            }
            // === REMOVE TOKEN BUTTON ===
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-token-unsubscribe')) {
                    e.target.parentElement.remove();
                }
            });

            // === SUBMIT BUTTON ===
            unsubscribeTopic.addEventListener('submit', async (event) => {
                event.preventDefault();
                // GET VALUE FROM INPUT
                const topic = document.getElementById('fcm-unsubscribe-topic').value;
                const tokens = Array.from(document.querySelectorAll(
                        'input[name="fcm-unsubscribe-token[]"]'))
                    .map(input => input.value.trim())
                    .filter(token => token !== '');
                const payload = {
                    topic: topic,
                    tokens: tokens
                }

                try {
                    const url = "{{ route('topic-unsubscribe') }}"; // Ganti sesuai kebutuhan
                    const result = await postData(url, payload);
                    if (result.status === true) {
                        Swal.fire({
                            title: 'Success!',
                            text: result.message,
                            icon: 'info',
                            confirmButtonText: 'Tutup'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: result.errors.map(e => `Token: ${e.token} - ${e.error}`)
                                .join('\n'),
                            icon: 'error',
                            confirmButtonText: 'Close'
                        })
                    }
                } catch (err) {
                    console.error(err);
                }
            });
        }


        // === FORM 5: PUBLISH TOPIC ===
        const publishTopic = document.getElementById('publish-topic-form');
        if (publishTopic) {
            // === SUBMIT BUTTON ===
            publishTopic.addEventListener('submit', async (event) => {
                event.preventDefault();
                // GET VALUE FROM INPUT
                const topic = document.getElementById('fcm-topic-topic').value;
                const title = document.getElementById('fcm-topic-title').value;
                const text = document.getElementById('fcm-topic-text').value;
                const payload = {
                    topic: topic,
                    notification_title: title,
                    notification_text: text
                }

                try {
                    const url = "{{ route('topic-publish') }}";
                    const result = await postData(url, payload);
                    if (result.status === true) {
                        console.log(result.message);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: result.errors.map(e => `Token: ${e.token} - ${e.error}`)
                                .join('\n'),
                            icon: 'error',
                            confirmButtonText: 'Close'
                        })
                    }
                } catch (err) {
                    console.error(err);
                }
            });
        }

    });
</script>
