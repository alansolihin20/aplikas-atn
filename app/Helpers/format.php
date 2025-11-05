<?php

if (!function_exists('formatTanggal')) {
    function formatTanggal($tanggal)
    {
        return \Carbon\Carbon::parse($tanggal)->format('d-m-Y');
    }
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}
