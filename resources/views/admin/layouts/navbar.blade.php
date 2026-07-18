<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="ti ti-menu-2 ti-md"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Search -->
    <div class="navbar-nav align-items-center flex-grow-1">
      <div class="nav-item w-100 px-2 position-relative">
        <div class="input-group input-group-merge">
          <span class="input-group-text"><i class="ti ti-search"></i></span>
          <input type="text" id="admin-global-search" class="form-control" placeholder="{{ __('admin.navbar.search_placeholder') }}" autocomplete="off">
        </div>
        <div id="admin-global-search-results" class="dropdown-menu w-100 shadow-sm" style="display:none; max-height: 320px; overflow-y: auto;"></div>
      </div>
    </div>
    <!-- /Search -->
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <li class="nav-item me-2">
        @include('layouts.locale-switcher')
      </li>
      <li class="nav-item dropdown-style-switcher dropdown">
        <a
          class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
          href="javascript:void(0);"
          data-bs-toggle="dropdown">
          <i class="ti ti-md"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
              <span class="align-middle"><i class="ti ti-sun ti-md me-3"></i>Light</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
              <span class="align-middle"><i class="ti ti-moon-stars ti-md me-3"></i>Dark</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
              <span class="align-middle"><i class="ti ti-device-desktop-analytics ti-md me-3"></i>System</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
        <a
          class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
          href="javascript:void(0);"
          data-bs-toggle="dropdown"
          data-bs-auto-close="outside"
          aria-expanded="false">
          <span class="position-relative">
            <i class="ti ti-bell ti-md"></i>
            <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end p-0">
          <li class="dropdown-menu-header border-bottom">
            <div class="dropdown-header d-flex align-items-center py-3">
              <h6 class="mb-0 me-auto">Notification</h6>
              <div class="d-flex align-items-center h6 mb-0">
                <span class="badge bg-label-primary me-2">8 New</span>
                <a
                  href="javascript:void(0)"
                  class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all"
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  title="Mark all as read"><i class="ti ti-mail-opened text-heading"></i></a>
              </div>
            </div>
          </li>
          <li class="dropdown-notifications-list scrollable-container">
            <ul class="list-group list-group-flush">
              <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar">
                      <img src="{{ asset('admin/assets/img/avatars/9.png') }}" alt class="rounded-circle" />
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="mb-1 small">Application has been approved 🚀</h6>
                    <small class="mb-1 d-block text-body">Your ABC project application has been approved.</small>
                    <small class="text-muted">2 days ago</small>
                  </div>
                  <div class="flex-shrink-0 dropdown-notifications-actions">
                    <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                    <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                  </div>
                </div>
              </li>
            </ul>
          </li>
          <li class="border-top">
            <div class="d-grid p-4">
              <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                <small class="align-middle">View all notifications</small>
              </a>
            </div>
          </li>
        </ul>
      </li>
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a
          class="nav-link dropdown-toggle hide-arrow p-0"
          href="javascript:void(0);"
          data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="{{ asset('assets/admin/assets/img/avatars/1.png') }}" alt class="rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item mt-0" href="{{ route('admin.profile.edit') }}">
              <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-2">
                  <div class="avatar avatar-online">
                    <img src="{{ asset('assets/admin/assets/img/avatars/1.png') }}" alt class="rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0">{{ auth('admin')->user()->name }}</h6>
                  <small class="text-muted">{{ strtoupper(auth('admin')->user()->level) }}</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider my-1 mx-n2"></div>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
              <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">{{ __('admin.navbar.my_profile') }}</span>
            </a>
          </li>
          <li>
            <div class="d-grid px-2 pt-2 pb-1">
              <a class="btn btn-sm btn-danger d-flex" href="{{ route('admin.logout') }}">
                <small class="align-middle">{{ __('admin.navbar.logout') }}</small>
                <i class="ti ti-logout ms-2 ti-14px"></i>
              </a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>