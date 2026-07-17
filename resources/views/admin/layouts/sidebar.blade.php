<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
      <img src="{{ asset('assets/logo/letter-logo.png') }}" width="200" alt="">
    </a>
  </div>
  <div class="menu-inner-shadow"></div>
  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>

    <!-- Master Data -->
    <li class="menu-item {{ request()->is('admin/batches*') || request()->is('admin/categories*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-database"></i>
        <div data-i18n="Master Data">Master Data</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.batches.index') ? 'active' : '' }}">
          <a href="{{ route('admin.batches.index') }}" class="menu-link">
            <div data-i18n="Batch List">Batch List</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
          <a href="{{ route('admin.categories.index') }}" class="menu-link">
            <div data-i18n="Category List">Category List</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Job Management -->
    <li class="menu-item {{ request()->is('admin/jobs*') ? 'active' : '' }}">
      <a href="{{ route('admin.jobs.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-files"></i>
        <div data-i18n="Job List">Job List</div>
      </a>
    </li>

    <li class="menu-header small">
      <span class="menu-header-text" data-i18n="CANDIDATE MANAGEMENT">CANDIDATE MANAGEMENT</span>
    </li>

    <!-- Candidates -->
    <li class="menu-item {{ request()->is('admin/candidates*') || request()->is('admin/applies*') || request()->is('admin/schedule-interviews*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-users"></i>
        <div data-i18n="Candidates">Candidates</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.candidates.index') ? 'active' : '' }}">
          <a href="{{ route('admin.candidates.index') }}" class="menu-link">
            <div data-i18n="Candidate List">Candidate List</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.applies.*') ? 'active' : '' }}">
          <a href="{{ route('admin.applies.index') }}" class="menu-link">
            <div data-i18n="Apply List">Apply List</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.schedule-interviews.index') ? 'active' : '' }}">
          <a href="{{ route('admin.schedule-interviews.index') }}" class="menu-link">
            <div data-i18n="Schedule Interview">Schedule Interview</div>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</aside>