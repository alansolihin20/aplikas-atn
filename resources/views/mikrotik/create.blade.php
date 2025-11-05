@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ isset($item) ? 'Edit' : 'Tambah' }} Mikrotik Connection</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="mk-form" method="POST" action="{{ isset($item) ? route('mikrotik.update', $item->id) : route('mikrotik.store') }}">
        @csrf
        @if(isset($item))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Nama koneksi</label>
            <input name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Host (IP)</label>
                <input name="host" class="form-control" value="{{ old('host', $item->host ?? '') }}" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Port</label>
                <input name="port" class="form-control" value="{{ old('port', $item->port ?? 8728) }}" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Use SSL</label>
                <select name="use_ssl" class="form-control">
                    <option value="0" {{ old('use_ssl', $item->use_ssl ?? 0) ? '' : 'selected' }}>No</option>
                    <option value="1" {{ old('use_ssl', $item->use_ssl ?? 0) ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" value="{{ old('username', $item->username ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" value="" {{ isset($item) ? '' : 'required' }}>
            @if(isset($item))
                <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control">{{ old('notes', $item->notes ?? '') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button id="test-conn" type="button" class="btn btn-outline-primary">Test Connection</button>
            <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Update' : 'Save' }}</button>
            <a href="{{ route('mikrotik.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>

    <div id="test-result" class="mt-3"></div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('test-conn').addEventListener('click', async function() {
    const form = document.getElementById('mk-form');
    const formData = new FormData(form);

    // if editing existing record, include id so controller uses DB values
    const connectionId = {{ isset($item) ? $item->id : 'null' }};

    const payload = {};
    if (connectionId) {
        payload.connection_id = connectionId;
    } else {
        // ad-hoc test: send values from form
        payload.host = formData.get('host');
        payload.port = formData.get('port');
        payload.username = formData.get('username');
        payload.password = formData.get('password');
        payload.use_ssl = formData.get('use_ssl') ? 1 : 0;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const resEl = document.getElementById('test-result');
    resEl.innerHTML = 'Testing...';

    try {
        const res = await fetch("{{ route('mikrotik.test') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (data.ok) {
            resEl.innerHTML = '<pre class="p-2 bg-light border">' + JSON.stringify(data.resp, null, 2) + '</pre>';
        } else {
            resEl.innerHTML = '<div class="alert alert-danger">' + (data.error || 'Unknown error') + '</div>';
        }
    } catch (err) {
        resEl.innerHTML = '<div class="alert alert-danger">Request failed: ' + err.message + '</div>';
    }
});
</script>
@endpush
