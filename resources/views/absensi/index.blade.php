@extends('layouts.teknisi')

@section('title', 'Absensi Teknisi')

@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0"><i class="fas fa-user-check me-2 text-success"></i>Absensi Teknisi</h3>
                </div>
                <div class="col-sm-6 text-end text-muted">
                    <small>Tanggal: {{ now()->translatedFormat('d F Y') }}</small>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- STATUS HARI INI --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-calendar-day me-2"></i>Status Hari Ini</h5>
                </div>
                <div class="card-body">
                    @if($absenHariIni)
                        <div class="row mb-3">
                            <div class="col-md-6"><strong>Jam Masuk:</strong> {{ $absenHariIni->check_in ?? '-' }}</div>
                            <div class="col-md-6"><strong>Jam Pulang:</strong> {{ $absenHariIni->check_out ?? '-' }}</div>
                        </div>
                    @else
                        <p class="text-muted">Belum melakukan absensi hari ini.</p>
                    @endif

                    <div class="d-flex gap-2">
                        <button id="btnMasuk" class="btn btn-success" {{ $absenHariIni ? 'disabled' : '' }}>
                            <i class="fas fa-sign-in-alt me-1"></i> Absen Masuk
                        </button>
                        <button id="btnPulang" class="btn btn-danger" 
                            {{ !$absenHariIni || $absenHariIni->check_out ? 'disabled' : '' }}>
                            <i class="fas fa-sign-out-alt me-1"></i> Absen Pulang
                        </button>
                    </div>

                    {{-- Card Konfirmasi Lokasi --}}
                    <div id="lokasiCard" class="card mt-4 d-none border-success">
                        <div class="card-body text-center">
                            <h5 class="text-success">
                                <i class="fas fa-map-marker-alt me-2"></i>Anda berada di kantor
                            </h5>
                            <button id="confirmMasuk" class="btn btn-primary mt-3">Konfirmasi Absen Masuk</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIWAYAT ABSENSI --}}
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Riwayat Absensi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $r)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($r->created_at)->translatedFormat('d F Y') }}</td>
                                        <td>{{ $r->check_in ?? '-' }}</td>
                                        <td>{{ $r->check_out ?? '-' }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($r->status) {
                                                    'hadir' => 'bg-success',
                                                    'terlambat' => 'bg-warning text-dark',
                                                    'izin' => 'bg-info text-dark',
                                                    'alpha' => 'bg-danger',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($r->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada data absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

{{-- SCRIPT UNTUK CEK LOKASI --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnMasuk = document.getElementById('btnMasuk');
    const btnPulang = document.getElementById('btnPulang');
    const lokasiCard = document.getElementById('lokasiCard');
    const lokasiCardBody = lokasiCard.querySelector('.card-body');

    const kantor = { 
        lat: {{ $kantor->latitude }}, 
        lng: {{ $kantor->longitude }} 
    };
    const radius = {{ $kantor->radius }};

    function hitungJarak(lat1, lon1, lat2, lon2) {
        const R = 6371e3;
        const φ1 = lat1 * Math.PI / 180;
        const φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(Δφ / 2) ** 2 +
                  Math.cos(φ1) * Math.cos(φ2) *
                  Math.sin(Δλ / 2) ** 2;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // =====================
    // ABSEN MASUK (WAJIB DI DALAM KANTOR)
    // =====================
    btnMasuk?.addEventListener('click', () => {
        if (!navigator.geolocation) return alert('Browser tidak mendukung geolokasi!');

        navigator.geolocation.getCurrentPosition(pos => {
            const jarak = hitungJarak(pos.coords.latitude, pos.coords.longitude, kantor.lat, kantor.lng);
            lokasiCard.classList.remove('d-none');

            if (jarak <= radius) {
                // ✅ Dalam area kantor
                lokasiCard.classList.remove('border-danger');
                lokasiCard.classList.add('border-success');
                lokasiCardBody.innerHTML = `
                    <h5 class="text-success"><i class="fas fa-map-marker-alt me-2"></i>Anda berada di area kantor</h5>
                    <button id="confirmMasuk" class="btn btn-primary mt-3">Konfirmasi Absen Masuk</button>
                `;
            } else {
                // ❌ Di luar kantor → TOLAK
                lokasiCard.classList.remove('border-success');
                lokasiCard.classList.add('border-danger');
                lokasiCardBody.innerHTML = `
                    <h5 class="text-danger"><i class="fas fa-map-marker-alt me-2"></i>Anda di luar area kantor</h5>
                    <p class="text-muted">Absen masuk hanya dapat dilakukan di area kantor.</p>
                `;
            }
        }, () => alert('Gagal mendapatkan lokasi!'));
    });

    // =====================
    // ABSEN KELUAR (BOLEH DI LUAR KANTOR DENGAN FOTO)
    // =====================
    btnPulang?.addEventListener('click', async () => {
        if (!navigator.geolocation) {
            alert('Browser tidak mendukung geolokasi!');
            return;
        }

        navigator.geolocation.getCurrentPosition(async pos => {
            const jarak = hitungJarak(pos.coords.latitude, pos.coords.longitude, kantor.lat, kantor.lng);

            // Kalau masih dalam kantor → langsung simpan
            if (jarak <= radius) {
                const data = {
                    latitude: pos.coords.latitude,
                    longitude: pos.coords.longitude,
                    _token: "{{ csrf_token() }}"
                };

                fetch("{{ route('absensi.checkOut') }}", {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(r => {
                    alert(r.message);
                    location.reload();
                })
                .catch(() => alert('Terjadi kesalahan saat menyimpan absen pulang!'));
            } else {
                // Kalau di luar area → wajib foto
                lokasiCard.classList.remove('d-none');
                lokasiCard.classList.remove('border-success');
                lokasiCard.classList.add('border-warning');
                lokasiCardBody.innerHTML = `
                    <h5 class="text-warning"><i class="fas fa-map-marker-alt me-2"></i>Anda di luar area kantor</h5>
                    <p class="text-muted">Silakan ambil foto sebagai bukti absen keluar.</p>
                    <input type="file" id="fotoPulang" accept="image/*" capture="camera" class="form-control mt-2" />
                    <button id="confirmPulang" class="btn btn-primary mt-3">Kirim Absen Keluar dengan Foto</button>
                `;
            }
        }, () => alert('Gagal mendapatkan lokasi!'));
    });

    // =====================
    // KONFIRMASI ABSEN MASUK
    // =====================
    document.addEventListener('click', async function (e) {
        if (e.target && e.target.id === 'confirmMasuk') {
            const pos = await new Promise(resolve => navigator.geolocation.getCurrentPosition(resolve));

            const formData = new FormData();
            formData.append('latitude', pos.coords.latitude);
            formData.append('longitude', pos.coords.longitude);
            formData.append('_token', "{{ csrf_token() }}");

            fetch("{{ route('absensi.checkIn') }}", {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(r => {
                alert(r.message);
                location.reload();
            })
            .catch(() => alert('Terjadi kesalahan saat menyimpan absen!'));
        }

        // =====================
        // KONFIRMASI ABSEN KELUAR DENGAN FOTO
        // =====================
        if (e.target && e.target.id === 'confirmPulang') {
            const pos = await new Promise(resolve => navigator.geolocation.getCurrentPosition(resolve));
            const fotoInput = document.getElementById('fotoPulang');
            if (!fotoInput.files.length) return alert('Silakan ambil foto terlebih dahulu!');

            const formData = new FormData();
            formData.append('latitude', pos.coords.latitude);
            formData.append('longitude', pos.coords.longitude);
            formData.append('photo', fotoInput.files[0]);
            formData.append('_token', "{{ csrf_token() }}");

            fetch("{{ route('absensi.confirmPhoto') }}", {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(r => {
                alert(r.message);
                location.reload();
            })
            .catch(() => alert('Terjadi kesalahan saat menyimpan absen pulang dengan foto!'));
        }
    });
});
</script>


@endsection
