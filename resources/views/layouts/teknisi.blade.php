<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Abiraya Net | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ===== FONT & ICONS ===== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- ===== CORE CSS ===== -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />

    <!-- ===== CUSTOM FONTS ===== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />

    <!-- ===== OPTIONAL LIBRARIES ===== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" />

    <!-- ===== JQUERY & BOOTSTRAP (WAJIB) ===== -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  </head>

  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <!-- ===== WRAPPER ===== -->
    <div class="app-wrapper">
      <!-- Header -->
      <x-teknisinav />

      <!-- Sidebar -->
      <x-teknisidebar />

      <!-- Content -->
      @yield('content')

      <!-- Footer -->
      <x-footer />
    </div>

    <!-- ===== JS PLUGINS ===== -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>

    <!-- ===== OPTIONAL CHART LIBRARIES ===== -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ===== OVERLAY SCROLLBAR CONFIG ===== -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        console.log('✅ Layout loaded successfully.');
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: 'os-theme-light',
              autoHide: 'leave',
              clickScroll: true,
            },
          });
        }
      });
    </script>

    <!-- ===== CUSTOM SCRIPT PLACEHOLDER ===== -->
    @stack('scripts')

  </body>
</html>
