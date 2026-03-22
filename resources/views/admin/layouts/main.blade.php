<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/admin/assets') }}/"
  data-template="vertical-menu-template"
  data-style="light">

<head>
  @include('admin.layouts.header')
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      @include('admin.layouts.sidebar')

      <div class="layout-page">
        @include('admin.layouts.navbar')
        <div class="content-wrapper">
          @yield('content')
          @include('admin.layouts.footer')
          <div class="content-backdrop fade"></div>
        </div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
    <div class="drag-target"></div>
  </div>
  @yield('scripts')
</body>

</html>