<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
      <img src="{{ asset('assets/logo/letter-logo.png') }}" width="200" alt="">
    </a>
  </div>
  <div class="menu-inner-shadow"></div>
  <ul class="menu-inner py-1">
    <li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div>{{ __('admin.sidebar.dashboard') }}</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('admin/batches*') || request()->is('admin/categories*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-database"></i>
        <div>{{ __('admin.sidebar.master_data') }}</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.batches.index') ? 'active' : '' }}">
          <a href="{{ route('admin.batches.index') }}" class="menu-link">
            <div>{{ __('admin.sidebar.batch_list') }}</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
          <a href="{{ route('admin.categories.index') }}" class="menu-link">
            <div>{{ __('admin.sidebar.category_list') }}</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item {{ request()->is('admin/jobs*') ? 'active' : '' }}">
      <a href="{{ route('admin.jobs.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-files"></i>
        <div>{{ __('admin.sidebar.job_list') }}</div>
      </a>
    </li>

    <li class="menu-header small">
      <span class="menu-header-text">{{ __('admin.sidebar.candidate_management') }}</span>
    </li>

    <li class="menu-item {{ request()->is('admin/candidates*') || request()->is('admin/applies*') || request()->is('admin/schedule-interviews*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-users"></i>
        <div>{{ __('admin.sidebar.candidates') }}</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.candidates.index') ? 'active' : '' }}">
          <a href="{{ route('admin.candidates.index') }}" class="menu-link">
            <div>{{ __('admin.sidebar.candidate_list') }}</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.applies.*') ? 'active' : '' }}">
          <a href="{{ route('admin.applies.index') }}" class="menu-link">
            <div>{{ __('admin.sidebar.apply_list') }}</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.schedule-interviews.index') ? 'active' : '' }}">
          <a href="{{ route('admin.schedule-interviews.index') }}" class="menu-link">
            <div>{{ __('admin.sidebar.schedule_interview') }}</div>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</aside>
