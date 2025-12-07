<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="#" class="brand-link">
      <img
        src="{{ asset('adminlte/dist/assets/img/Abiraya4.png') }}"
        alt="Logo"
        class="opacity-75"
        style="height: 170px; width: auto;"
      />
    </a>
  </div>

  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="/dashboard" class="nav-link active">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Transaksi -->
        <li class="nav-header">Transaksi</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-book"></i>
            <p>Pemasukan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-money-bill-wave"></i>
            <p>Pengeluaran</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/slip-saya') }}" class="nav-link">
            <i class="nav-icon fa-solid fa-file-invoice"></i>
            <p>Slip Gaji</p>
          </a>
        </li>

        <!-- Absensi -->
        <li class="nav-header">Absensi</li>
        <li class="nav-item">
          <a href="{{ url('/absensi') }}" class="nav-link">
            <i class="nav-icon fa-solid fa-calendar-check"></i>
            <p>Kehadiran</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-business-time"></i>
            <p>Lembur</p>
          </a>
        </li>

        <!-- Inventori -->
        <li class="nav-header">Inventori</li>
        <li class="nav-item">
          <a href="{{ url('/teknisi/inventory/item-out') }}" class="nav-link">
            <i class="nav-icon fa-solid fa-boxes-stacked"></i>
            <p>Barang Keluar</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/teknisi/inventory/item-entry') }}" class="nav-link">
            <i class="nav-icon fa-solid fa-box-open"></i>
            <p>Barang Masuk</p>
          </a>
        <li class="nav-item">
          <a href="{{ url('/teknisi/inventory/requests') }}" class="nav-link">
            <i class="nav-icon fa-solid fa-clipboard-list"></i>
            <p>Request Barang</p>
          </a>
        </li>

        <!-- Laporan -->
        <li class="nav-header">Laporan</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-chart-line"></i>
            <p>Rekap Bulanan</p>
          </a>
        </li>

        <!-- Logout -->
        <li class="nav-header">Akun</li>
        <li class="nav-item">
          <a href="#" class="nav-link text-danger">
            <i class="nav-icon fa-solid fa-right-from-bracket"></i>
            <p>Log Out</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>
