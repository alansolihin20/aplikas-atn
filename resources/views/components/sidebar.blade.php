<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!-- Sidebar Brand -->
  <div class="sidebar-brand">
    <a href="{{ url('/dashboard') }}" class="brand-link">
      <img src="{{ asset('adminlte/dist/assets/img/Abiraya4.png') }}" 
           alt="Logo" class="opacity-75"
           style="height: 170px; width: auto;" />
    </a>
  </div>

  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ url('/dashboard') }}" class="nav-link active">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Kelola Data -->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-table"></i>
            <p>Kelola Data <i class="nav-arrow bi bi-chevron-right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="./tables/simple.html" class="nav-link"><i class="nav-icon bi bi-circle"></i> Data Pelanggan</a></li>
            <li class="nav-item"><a href="./tables/simple.html" class="nav-link"><i class="nav-icon bi bi-circle"></i> Data Tipe Pembayaran</a></li>
            <li class="nav-item"><a href="./tables/simple.html" class="nav-link"><i class="nav-icon bi bi-circle"></i> Data Notifikasi</a></li>
            <li class="nav-item"><a href="./tables/simple.html" class="nav-link"><i class="nav-icon bi bi-circle"></i> Data Rekening</a></li>
            <li class="nav-item"><a href="{{ url('/karyawan') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i> Data Karyawan</a></li>
          </ul>
        </li>

        <!-- Transaksi -->
        <li class="nav-header">Transaksi</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-book"></i>
            <p>Pembukuan <i class="nav-arrow bi bi-chevron-right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="{{ url('superadmin/pembukuan/pemasukan') }}" class="nav-link"><i class="nav-icon bi bi-box-arrow-in-right"></i> Pemasukan</a></li>
            <li class="nav-item"><a href="{{ url('superadmin/pembukuan/pengeluaran') }}" class="nav-link"><i class="nav-icon bi bi-box-arrow-in-left"></i> Pengeluaran</a></li>
            <li class="nav-item"><a href="{{ url('superadmin/pembukuan/buku-besar') }}" class="nav-link"><i class="nav-icon fa-solid fa-book-open"></i> Buku Besar</a></li>
            <li class="nav-item"><a href="{{ url('superadmin/pembukuan/category') }}" class="nav-link"><i class="nav-icon fa-solid fa-list"></i> Kategori Transaksi</a></li>
          </ul>
        </li>

        <!-- Inventori -->
        <li class="nav-header">Inventori</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-boxes-stacked"></i>
            <p>Inventori Barang <i class="nav-arrow bi bi-chevron-right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="{{ url('inventori') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i> Item Barang</a></li>
            <li class="nav-item"><a href="{{ url('barang-in') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i> Barang Masuk</a></li>
            <li class="nav-item"><a href="{{ url('barang-out') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i> Barang Keluar</a></li>
            <li class="nav-item"><a href="{{ url('supplier') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i> Supplier</a></li>
            <li class="nav-item"><a href="{{ url('opname') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i> Stok Opname</a></li>
          </ul>
        </li>

        <!-- Manajemen Teknisi -->
        <li class="nav-header">Manajemen Teknisi</li>
        <li class="nav-item"><a href="{{ url('/admin/shift-schedules') }}" class="nav-link"><i class="nav-icon bi bi-calendar-week"></i> Jadwal Teknisi</a></li>
        <li class="nav-item"><a href="{{ url('/admin/shift-schedules/weekly') }}" class="nav-link"><i class="nav-icon bi bi-calendar-week"></i> Jadwal Mingguan</a></li>
        <li class="nav-item"><a href="{{ url('/admin/riwayat') }}" class="nav-link"><i class="nav-icon bi bi-person-check"></i> Absensi Teknisi</a></li>
        <li class="nav-item"><a href="{{ url('/slip-gaji') }}" class="nav-link"><i class="nav-icon bi bi-cash-stack"></i> Slip Gaji Teknisi</a></li>

        <!-- Laporan -->
        <li class="nav-header">Laporan</li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon bi bi-bar-chart-line"></i> Laporan Keuangan</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon bi bi-box-seam"></i> Laporan Inventori</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon bi bi-people"></i> Laporan Pelanggan</a></li>

        <!-- User Management -->
        <li class="nav-header">Management Mikrotik</li>
        <li class="nav-item"><a href="{{ url('admin/mikrotik') }}" class="nav-link"><i class="fa-brands fa-nfc-symbol m-1"></i> Koneksi</a></li>
      <li class="nav-item">
    <a href="{{ route('pppoe.index') }}" class="nav-link">
        <i class="fa-solid fa-network-wired m-1"></i> PPPoE
    </a>
</li>


         <li class="nav-header">User Management</li>
        <li class="nav-item"><a href="{{ url('user') }}" class="nav-link"><i class="nav-icon fa-solid fa-user"></i> User</a></li>

        <!-- Logout -->
        <li class="nav-item">
          <form method="POST" action="{{ url('/logout') }}" class="nav-link">
            @csrf
            <i class="nav-icon fa-solid fa-right-from-bracket text-danger"></i>
            <button type="submit" class="btn btn-link nav-link p-2" style="text-decoration: none;">Log Out</button>
          </form>
        </li>

      </ul>
    </nav>
  </div>
</aside>
