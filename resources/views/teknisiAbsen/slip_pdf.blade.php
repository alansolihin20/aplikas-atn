<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ ucfirst($slip->periode) }}</title>
    <style>
        /* Menggunakan font yang aman untuk PDF */
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .slip-container {
            width: 90%;              /* Lebar fleksibel */
            max-width: 800px;        /* Pas ukuran A4 */
            margin: 20px auto;       /* Pusat horizontal */
            padding: 30px;
            background-color: #ffffff;
            border: 1px solid #ddd;
        }
            .header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        /* !!! MODIFIKASI UKURAN LOGO DI SINI !!! */
            .logo-placeholder {
            width: 120px;
            height: auto;
            margin-right: 20px;
        }

        .logo-placeholder img {
            width: 100%;
            height: auto;
            display: block;
        }
        /* Akhir Modifikasi Logo CSS */

        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            font-size: 14px;
            color: #555;
            margin: 0;
        }

        /* Informasi Karyawan */
        .info-karyawan {
            margin-bottom: 25px;
            font-size: 14px;
        }
        .info-karyawan table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-karyawan td {
            padding: 5px 0;
            border: none;
        }
        .info-karyawan strong {
            display: inline-block;
            width: 120px;
        }

        /* Tabel Detail Gaji */
        .gaji-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }
        .gaji-table th, .gaji-table td {
            border: 1px solid #eee;
            padding: 10px 15px;
            text-align: left;
        }
        .gaji-table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
            width: 50%;
        }
        .gaji-table td:last-child {
            text-align: right;
            font-weight: 500;
        }

        /* Summary Total */
        .total-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9f7ff; 
            border-radius: 5px;
            border-left: 5px solid #007bff;
        }
        .total-section table {
            width: 100%;
        }
        .total-section td {
            padding: 8px 0;
            font-size: 16px;
            border: none;
        }
        .total-section td:first-child {
            font-weight: bold;
        }
        .total-section td:last-child {
            text-align: right;
            font-weight: bold;
            color: #007bff;
            font-size: 18px;
        }

        /* Footer/Tanda Tangan */
        .footer-section {
            margin-top: 50px;
            text-align: right;
            font-size: 13px;
        }
        .ttd-box {
            display: inline-block;
            width: 200px;
            text-align: center;
        }
        .ttd-box p {
            margin: 5px 0 30px 0;
        }
        .ttd-box .signature-line {
            border-bottom: 1px solid #000;
            display: block;
            margin-top: 50px;
            padding-bottom: 5px;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div class="slip-container">
        <div class="header">
            <div class="logo-placeholder">
                <img src="{{ public_path('adminlte/dist/assets/img/Abiraya.png') }}" 
                    alt="Logo Perusahaan" 
                    style="width: 100%; height: auto; display: block;" 
                />
            </div>
            <div>
                <h1>SLIP GAJI</h1>
                <p>Periode: **{{ ucfirst($slip->periode) }}**</p>
                <p>Tanggal Cetak: {{ date('d F Y') }}</p>
            </div>
        </div>
        
            <div class="info-karyawan">
            <table>
                <tr>
                    <td><strong>Nama Karyawan:</strong></td>
                    <td>{{ $slip->karyawan->name ?? $slip->user->name ?? 'Nama Karyawan' }}</td>

                    <td><strong>NIK:</strong></td>
                    <td>{{ $slip->karyawan->nik ?? $slip->user->nik ?? '-' }}</td>
                </tr>

                <tr>
                    <td><strong>Jabatan:</strong></td>
                    <td>{{ $slip->karyawan->jabatan ?? $slip->user->jabatan ?? '-' }}</td>

                </tr>
            </table>
        </div>


        <table class="gaji-table">
            <thead>
                <tr>
                    <th style="background-color: #007bff; color: white;">KOMPONEN</th>
                    <th style="background-color: #007bff; color: white; text-align: right;">JUMLAH (IDR)</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="2" style="background-color: #f0f0f0; font-weight: bold;">PENGHASILAN (BRUTO)</td></tr>
                <tr><th>Gaji Pokok</th><td>Rp {{ number_format($slip->gaji_pokok, 0, ',', '.') }}</td></tr>
                <tr><th>Insentif Harian</th><td>Rp {{ number_format($slip->insentif_harian, 0, ',', '.') }}</td></tr>
                <tr><th style="font-weight: bold; background-color: #fffacd;">TOTAL GAJI BRUTO</th><td style="font-weight: bold; text-align: right; background-color: #fffacd;">Rp {{ number_format($slip->gaji_bruto, 0, ',', '.') }}</td></tr>

                <tr><td colspan="2" style="background-color: #f0f0f0; font-weight: bold; margin-top: 10px;">POTONGAN</td></tr>
                <tr><th>Potongan BPJS Ketenagakerjaan</th><td>Rp {{ number_format($slip->bpjs_tk, 0, ',', '.') }}</td></tr>
                <tr><th>Potongan BPJS Kesehatan</th><td>Rp {{ number_format($slip->bpjs_kes, 0, ',', '.') }}</td></tr>
                <tr><th>Pinjaman/Cicilan</th><td>Rp {{ number_format($slip->pinjaman, 0, ',', '.') }}</td></tr>
                
                @php
                    $total_potongan = $slip->bpjs_tk + $slip->bpjs_kes + $slip->pinjaman;
                @endphp
                <tr><th style="font-weight: bold; background-color: #ffcccb;">TOTAL POTONGAN</th><td style="font-weight: bold; text-align: right; background-color: #ffcccb;">Rp {{ number_format($total_potongan, 0, ',', '.') }}</td></tr>
            </tbody>
        </table>

        <div class="total-section">
            <table>
                <tr>
                    <td>GAJI BERSIH (NETTO)</td>
                    <td>**Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}**</td>
                </tr>
            </table>
        </div>

        <div class="footer-section">
            <div class="ttd-box">
                <p>Hormat kami,</p>
                <span class="signature-line">
                    ( HRD/Finance Manager )
                </span>
            </div>
        </div>

    </div>
</body>
</html>