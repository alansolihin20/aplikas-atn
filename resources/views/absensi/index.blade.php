@section('title', 'Absensi Teknisi')
@extends('layouts.teknisi')
@section('content')
@php
// --- Variabel untuk Display (Menggunakan data yang diasumsikan tersedia) ---
// Pastikan user tersedia, jika tidak berikan fallback
$userName = auth()->user()->name ?? 'Teknisi Handal';

// Ambil info shift dari schedule jika ada
$shiftName = $schedule->shift->name ?? 'Tidak Ada Shift';
$shiftTimes = isset($schedule) 
    ? \Carbon\Carbon::parse($schedule->shift->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($schedule->shift->end_time)->format('H:i')
    : 'Belum Terjadwal';

// Variabel Absensi Hari Ini (Diasumsikan dari Controller)
$absenHariIni = $absenHariIni ?? null;
$canCheckIn = $canCheckIn ?? false;
$canCheckOut = $canCheckOut ?? false;
$riwayat = $riwayat ?? collect([]);
$errorMessage = $errorMessage ?? null;

@endphp

<main class="app-main">
<!--begin::App Content Header-->
<div class="app-content-header">
<div class="container-fluid">
<div class="row mb-2 align-items-center">
{{-- Kiri: Judul Halaman --}}
<div class="col-md-6 col-12">
<h3 class="mb-0"><i class="fas fa-user-check me-2 text-success"></i>Absensi Teknisi</h3>
</div>

            {{-- Kanan: Info User & Shift --}}
            <div class="col-md-6 col-12 text-md-end text-start mt-2 mt-md-0">
                <div class="p-2 rounded bg-light d-inline-block shadow-sm w-100 w-md-auto border">
                    <p class="mb-1 fw-bold text-dark"><i class="fas fa-user me-1"></i> {{ $userName }}</p>
                    <p class="mb-0 text-muted small"><i class="fas fa-business-time me-1"></i> Shift: {{ $shiftName }} ({{ $shiftTimes }})</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">

        {{-- CONVERSATIONAL GREETING BAR --}}
        <div class="alert alert-info border-info alert-dismissible fade show shadow-sm" role="alert">
            <h6 class="alert-heading mb-1 fw-bold">
                <i class="fas fa-handshake me-2"></i>Halo {{ $userName }}, Semangat Bekerja!
            </h6>
            <p class="mb-0">
                Ini Absensi untuk **{{ now()->translatedFormat('l, d F Y') }}**. Shift Anda hari ini adalah **{{ $shiftName }}** ({{ $shiftTimes }}). 
                Peraturan: Absen Masuk **WAJIB** di kantor. Absen Pulang boleh di luar area.
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        
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
        {{-- Tempat pesan dinamis (dari JS) --}}
        <div id="dynamicAlertContainer"></div>

        {{-- STATUS HARI INI --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-calendar-day me-2"></i>Status Absensi Hari Ini</h5>
            </div>
            <div class="card-body">
                @if(isset($errorMessage))
                    <p class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $errorMessage }}</p>
                @endif
                
                @if(isset($schedule))
                    @if(!$canCheckIn)
                        <p class="text-muted mt-2"><i class="fas fa-clock me-1"></i>Waktu absen masuk dimulai jam {{ \Carbon\Carbon::parse($schedule->shift->start_time)->format('H:i') }}</p>
                    @endif
                    @if(!$canCheckOut && $absenHariIni && $absenHariIni->check_in)
                        <p class="text-muted mt-2"><i class="fas fa-clock me-1"></i>Waktu absen pulang dimulai jam {{ \Carbon\Carbon::parse($schedule->shift->end_time)->format('H:i') }}</p>
                    @endif
                @endif
                
                @if($absenHariIni)
                    @if($absenHariIni->check_out)
                             <p class="text-success fw-bold">Anda sudah Absen Masuk dan Pulang hari ini. Jam kerja selesai.</p>
                    @else
                             <p class="text-success fw-bold">Anda sudah Absen Masuk hari ini. Selamat bekerja!</p>
                    @endif
                @else
                    <p class="text-muted">Belum melakukan absensi hari ini.</p>
                @endif

                <div class="d-flex gap-2">
                    {{-- Kondisi disabled diperbaiki --}}
                    <button id="btnMasuk" class="btn btn-success" 
                        {{!$canCheckIn || ($absenHariIni && $absenHariIni->check_in) ? 'disabled' : '' }}>
                        <i class="fas fa-sign-in-alt me-1"></i> Absen Masuk
                    </button>
                    <button id="btnPulang" class="btn btn-danger" 
                        {{ !$canCheckOut || !$absenHariIni || $absenHariIni->check_out ? 'disabled' : '' }}>
                        <i class="fas fa-sign-out-alt me-1"></i> Absen Pulang
                    </button>
                </div>

                {{-- Card Konfirmasi Lokasi --}}
                <div id="lokasiCard" class="card mt-4 d-none">
                    <div class="card-body text-center" id="lokasiCardBody">
                        {{-- Konten diisi oleh JS --}}
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
                                    {{-- Gunakan check_in untuk tanggal absensi --}}
                                    <td>{{ \Carbon\Carbon::parse($r->check_in)->translatedFormat('d F Y') }}</td> 
                                    <td>{{ $r->check_in ? \Carbon\Carbon::parse($r->check_in)->format('H:i') : '-' }}</td>
                                    <td>{{ $r->check_out ? \Carbon\Carbon::parse($r->check_out)->format('H:i') : '-' }}</td>
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
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($r->status ?? 'Menunggu') }}</span>
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

{{-- SCRIPT ABSENSI --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnMasuk = document.getElementById('btnMasuk');
    const btnPulang = document.getElementById('btnPulang');
    const lokasiCard = document.getElementById('lokasiCard');
    const lokasiCardBody = document.getElementById('lokasiCardBody');
    const dynamicAlertContainer = document.getElementById('dynamicAlertContainer');

    let currentAttendanceId = null;
    let isSubmitting = false; // 🔒 mencegah double submit

    // =====================
    // Helper Alert
    // =====================
    function showAlert(message, type = 'danger') {
        dynamicAlertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas ${type === 'danger' ? 'fa-times-circle' : 'fa-check-circle'} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }

    // =====================
    // Absen MASUK (WAJIB di kantor)
    // =====================
    btnMasuk?.addEventListener('click', () => {
        if (!navigator.geolocation) {
            return showAlert('Browser tidak mendukung geolokasi!', 'danger');
        }

        if (isSubmitting) return;
        isSubmitting = true;

        btnMasuk.disabled = true;
        btnPulang.disabled = true;
        lokasiCard.classList.remove('d-none');
        lokasiCardBody.innerHTML = `<h5 class="text-primary"><i class="fas fa-spinner fa-spin me-2"></i>Mendapatkan lokasi...</h5>`;

        navigator.geolocation.getCurrentPosition(async pos => {
            const formData = new FormData();
            formData.append('latitude', pos.coords.latitude);
            formData.append('longitude', pos.coords.longitude);
            formData.append('_token', "{{ csrf_token() }}");

            try {
                const response = await fetch("{{ route('absensi.checkIn') }}", { method: 'POST', body: formData });
                const r = await response.json();

                if (!response.ok) throw new Error(r.message || 'Gagal Absen Masuk.');

                if (r.status === 'in_office') {
                    showAlert(r.message || 'Absen Masuk Berhasil!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    const distance = r.distance ? Math.round(r.distance) : '—';
                    const radius = r.radius ? r.radius : '—';
                    showAlert(`Absen GAGAL. Jarak ${distance} m (max ${radius}m).`, 'danger');
                    lokasiCardBody.innerHTML = `
                        <h5 class="text-danger"><i class="fas fa-times me-2"></i>Anda di luar area kantor</h5>
                        <p class="text-muted">Absen Masuk wajib di dalam area kantor.</p>
                    `;
                    btnMasuk.disabled = false;
                }
            } catch (error) {
                console.error(error);
                showAlert('Terjadi kesalahan saat Absen Masuk.', 'danger');
                btnMasuk.disabled = false;
            } finally {
                isSubmitting = false;
            }
        }, () => {
            showAlert('Tidak dapat mengambil lokasi. Pastikan GPS aktif.', 'danger');
            btnMasuk.disabled = false;
            isSubmitting = false;
        }, { enableHighAccuracy: true });
    });

    // =====================
    // Absen PULANG (boleh di luar area)
    // =====================
    btnPulang?.addEventListener('click', async () => {
        if (!navigator.geolocation) {
            return showAlert('Browser tidak mendukung geolokasi!', 'danger');
        }

        if (isSubmitting) return;
        isSubmitting = true;

        btnMasuk.disabled = true;
        btnPulang.disabled = true;
        lokasiCard.classList.remove('d-none');
        lokasiCardBody.innerHTML = `<h5 class="text-primary"><i class="fas fa-spinner fa-spin me-2"></i>Mendapatkan lokasi...</h5>`;

        navigator.geolocation.getCurrentPosition(async pos => {
            const formData = new FormData();
            formData.append('latitude', pos.coords.latitude);
            formData.append('longitude', pos.coords.longitude);
            formData.append('_token', "{{ csrf_token() }}");

            try {
                const response = await fetch("{{ route('absensi.checkOut') }}", { method: 'POST', body: formData });
                const r = await response.json();

                if (!response.ok) throw new Error(r.message || 'Gagal Absen Pulang.');
                currentAttendanceId = r.attendance_id ?? null;

                if (r.status === 'in_office') {
                    showAlert(r.message || 'Absen Pulang Berhasil!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else if (r.status === 'outside_office') {
                    const distance = r.distance ? Math.round(r.distance) : '—';
                    const radius = r.radius ? r.radius : '—';
                    showAlert(`Anda ${distance} m dari kantor (radius ${radius}m). Diperlukan foto konfirmasi.`, 'warning');

                    lokasiCardBody.innerHTML = `
                        <h5 class="text-warning"><i class="fas fa-map-marker-alt me-2"></i>Konfirmasi Absen Pulang</h5>
                        <p class="text-muted">Unggah foto sebagai bukti pulang di luar area.</p>
                        <input type="file" id="fotoPulang" accept="image/*" capture="camera" class="form-control mt-2 border-warning" required />
                        <textarea id="reasonPulang" class="form-control mt-2" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                        <button id="confirmPulangPhoto" class="btn btn-warning mt-3 w-100"><i class="fas fa-camera me-1"></i> Kirim Absen Pulang dengan Foto</button>
                    `;
                } else {
                    showAlert('Status absensi tidak dikenali.', 'danger');
                    btnPulang.disabled = false;
                }
            } catch (error) {
                console.error(error);
                showAlert('Terjadi kesalahan saat Absen Pulang.', 'danger');
                btnPulang.disabled = false;
            } finally {
                isSubmitting = false;
            }
        }, () => {
            showAlert('Tidak dapat mengambil lokasi. Pastikan GPS aktif.', 'danger');
            btnPulang.disabled = false;
            isSubmitting = false;
        }, { enableHighAccuracy: true });
    });

    // =====================
    // Konfirmasi Foto Pulang (delegated listener)
    // =====================
    document.addEventListener('click', async (e) => {
        if (e.target.id !== 'confirmPulangPhoto') return;

        const fotoInput = document.getElementById('fotoPulang');
        const reasonInput = document.getElementById('reasonPulang');
        const targetButton = e.target;

        if (!fotoInput?.files.length) {
            return showAlert('Silakan ambil foto terlebih dahulu!', 'danger');
        }
        if (!currentAttendanceId) {
            return showAlert('ID Absensi tidak ditemukan, ulangi proses Absen Pulang.', 'danger');
        }

        const formData = new FormData();
        formData.append('attendance_id', currentAttendanceId);
        formData.append('photo', fotoInput.files[0]);
        formData.append('reason', reasonInput.value);
        formData.append('_token', "{{ csrf_token() }}");

        try {
            targetButton.disabled = true;
            targetButton.innerHTML = `<i class="fas fa-spinner fa-spin me-1"></i> Mengirim...`;

            const response = await fetch("{{ route('absensi.confirmCheckOutPhoto') }}", { method: 'POST', body: formData });
            const r = await response.json();

            if (!response.ok) throw new Error(r.message || 'Gagal konfirmasi foto.');

            showAlert(r.message || 'Absen Pulang Berhasil Dikonfirmasi!', 'success');
            setTimeout(() => location.reload(), 1500);
        } catch (error) {
            console.error(error);
            showAlert('Terjadi kesalahan saat mengirim foto.', 'danger');
            targetButton.disabled = false;
            targetButton.innerHTML = '<i class="fas fa-camera me-1"></i> Kirim Absen Pulang dengan Foto';
        }
    });
});
</script>


@endsection
