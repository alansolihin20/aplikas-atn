@extends('layouts.app')

@section('title', 'Admin')
<meta http-equiv="Cache-Control" content="no-store" />

@section('content')
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Dashboard Admin</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      
      {{-- ===================== CHART PEMBUKUAN FULL WIDTH ===================== --}}
      <div class="row mb-4">
        <div class="col-12">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
              <h3 class="card-title mb-0">📊 Grafik Pembukuan Bulanan</h3>
            </div>
            <div class="card-body">
              <div id="chartPembukuan" style="height: 350px;"></div>
            </div>
          </div>
        </div>
      </div>
      {{-- ====================================================================== --}}

      {{-- ======= BAGIAN BAWAH (KONTEN LAIN) ======= --}}
      <div class="row">
        <div class="col-lg-6">
          <div class="card mb-4">
            <div class="card-header border-0 d-flex justify-content-between">
              <h3 class="card-title">Online Store Visitors</h3>
              <a href="#" class="link-primary">View Report</a>
            </div>
            <div class="card-body">
              <div class="d-flex justify-content-between mb-3">
                <p><span class="fw-bold fs-5">820</span><br>Visitors Over Time</p>
                <p class="text-success text-end"><i class="bi bi-arrow-up"></i> 12.5%<br><span class="text-secondary">Since last week</span></p>
              </div>
              <div id="visitors-chart" style="height: 200px;"></div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card mb-4">
            <div class="card-header border-0 d-flex justify-content-between">
              <h3 class="card-title">Sales</h3>
              <a href="#" class="link-primary">View Report</a>
            </div>
            <div class="card-body">
              <div class="d-flex justify-content-between mb-3">
                <p><span class="fw-bold fs-5">$18,230.00</span><br>Sales Over Time</p>
                <p class="text-success text-end"><i class="bi bi-arrow-up"></i> 33.1%<br><span class="text-secondary">Since Past Year</span></p>
              </div>
              <div id="sales-chart" style="height: 200px;"></div>
            </div>
          </div>
        </div>
      </div>
      {{-- ========================================== --}}

    </div>
  </div>
</main>

{{-- ======================= SCRIPTS ======================= --}}
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // === Chart Pembukuan ===
  var pembukuanChart = new ApexCharts(document.querySelector("#chartPembukuan"), {
    chart: {
      type: 'line',
      height: 350,
      toolbar: { show: false },
    },
    series: [
      {
        name: 'Pemasukan',
        data: [12000000, 15000000, 18000000, 20000000, 25000000, 22000000, 30000000, 28000000, 32000000, 34000000, 37000000, 40000000],
      },
      {
        name: 'Pengeluaran',
        data: [8000000, 9000000, 10000000, 11000000, 13000000, 14000000, 16000000, 15000000, 17000000, 18000000, 19000000, 20000000],
      },
    ],
    xaxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
    },
    colors: ['#28a745', '#dc3545'],
    stroke: { curve: 'smooth', width: 3 },
    dataLabels: { enabled: false },
    legend: { position: 'top' },
    tooltip: {
      y: { formatter: function (val) { return 'Rp ' + val.toLocaleString(); } }
    },
  });
  pembukuanChart.render();
});
</script>
@endsection
{{-- ======================================================= --}}
@endsection
