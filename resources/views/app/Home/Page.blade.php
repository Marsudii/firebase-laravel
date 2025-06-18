@extends('app.layout')

@section('content')
    <div class="container mt-5">
        <hr />
        <div class="row mb-4">
            <div class="col-md-6">
                <br />
                {{-- FCM SINGLE --}}
                <form id="single-fcm-form">
                    <h5>Send By Fcm Single</h5>
                    <div class="mb-3">
                        <label for="fcm-single-title" class="form-label">Notification title</label>
                        <input type="text" class="form-control" id="fcm-single-title" placeholder="Notification title"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="fcm-single-text" class="form-label">Notification text</label>
                        <textarea class="form-control" id="fcm-single-text" rows="3" placeholder="Notification text" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="fcm-single-token" class="form-label">FCM Token</label>
                        <input type="text" class="form-control" id="fcm-single-token" placeholder="Fcm Token" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="col-md-6">
                <br />
                {{-- FCM MULTIDEVICE --}}
                <form id="multidevice-fcm-form">
                    <h5>Send By Fcm Multidevice (500)</h5>
                    <div class="mb-3">
                        <label for="fcm-multidevice-title" class="form-label">Notification title </label>
                        <input type="text" class="form-control" id="fcm-multidevice-title"
                            placeholder="Notification title" required>
                    </div>
                    <div class="mb-3">
                        <label for="fcm-multidevice-text" class="form-label">Notification text</label>
                        <textarea class="form-control" id="fcm-multidevice-text" rows="3" placeholder="Notification text" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">FCM Token(s)</label>
                        <div id="token-container-multidevice">
                            <div class="input-group mb-2">
                                <input type="text" name="fcm-multidevice-token[]" class="form-control"
                                    placeholder="Fcm Token" required>
                                <button type="button"
                                    class="btn btn-outline-danger remove-token-multidevice d-none">−</button>
                            </div>
                        </div>
                        <button type="button" id="add-token-multidevice" class="btn btn-sm btn-outline-secondary">+ Add
                            Token</button>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <br />
        </div>
        <br />
        <br />
        <br />
        <hr />
        <br />
        <br />
        <br />
        <div class="row mb-4">
            <div class="col-md-4">
                <br />
                {{-- SUBSCRIBE TOPIC --}}
                <form id="subscribe-topic-form">
                    <h5>Subscribe Topic</h5>
                    <div class="mb-3">
                        <label for="fcm-subscribe-topic" class="form-label">Topic</label>
                        <input type="text" class="form-control" id="fcm-subscribe-topic" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">FCM Token</label>
                        <div id="token-container-subscribe">
                            <div class="input-group mb-2">
                                <input type="text" name="fcm-subscribe-token[]" class="form-control"
                                    placeholder="Fcm Token" required>
                                <button type="button"
                                    class="btn btn-outline-danger remove-token-multidevice d-none">−</button>
                            </div>
                        </div>
                        <button type="button" id="add-token-subscribe" class="btn btn-sm btn-outline-secondary">+ Add
                            Token</button>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="col-md-4">
                <br />
                {{-- UNSUBSCRIBE TOPIC --}}
                <form id="unsubscribe-topic-form">
                    <h5>Unsubscribe Topic</h5>
                    <div class="mb-3">
                        <label for="fcm-unsubscribe-title" class="form-label">Topic</label>
                        <input type="text" class="form-control" id="fcm-unsubscribe-topic" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">FCM Token</label>
                        <div id="token-container-unsubscribe">
                            <div class="input-group mb-2">
                                <input type="text" name="fcm-unsubscribe-token[]" class="form-control"
                                    placeholder="Fcm Token">
                                <button type="button" class="btn btn-outline-danger remove-token d-none">−</button>
                            </div>
                        </div>
                        <button type="button" id="add-token-unsubscribe" class="btn btn-sm btn-outline-secondary">+ Add
                            Token</button>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <div class="col-md-4">
                <br />
                {{-- PUBLISH TOPIC --}}
                <form id="publish-topic-form">
                    <h5>Publish Topic</h5>
                    <div class="mb-3">
                        <label for="fcm-topic-topic" class="form-label">Topic</label>
                        <input type="text" class="form-control" id="fcm-topic-topic" required>
                    </div>
                    <div class="mb-3">
                        <label for="fcm-topic-title" class="form-label">Notification Title</label>
                        <input type="text" class="form-control" id="fcm-topic-title" required>
                    </div>
                    <div class="mb-3">
                        <label for="fcm-topic-text" class="form-label">Notification text</label>
                        <textarea class="form-control" id="fcm-topic-text" rows="3" placeholder="Notification text" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
