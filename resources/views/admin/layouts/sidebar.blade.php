<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
	<div class="app-brand demo">
		<a href="{{ url('admin/dashboard') }}" class="app-brand-link">
			<img src="{{ asset('logo.png') }}" width="200" alt="">
		</a>
	</div>
	<div class="menu-inner-shadow"></div>
	<ul class="menu-inner py-1">
		<!-- Dashboard -->
		<li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
			<a href="{{ url('admin/dashboard') }}" class="menu-link">
				<i class="menu-icon tf-icons ti ti-smart-home"></i>
				<div data-i18n="Dashboard">Dashboard</div>
			</a>
		</li>
		<!-- Batch -->
		<li class="menu-item {{ request()->is('admin/batches*') ? 'active open' : '' }}">
			<a href="javascript:void(0);" class="menu-link menu-toggle">
				<i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
				<div data-i18n="Batch">Batch</div>
			</a>
			<ul class="menu-sub">
				<li class="menu-item {{ request()->routeIs('admin.batches.index') ? 'active' : '' }}">
					<a href="{{ route('admin.batches.index') }}" class="menu-link">
						<div data-i18n="Batch List">Batch List</div>
					</a>
				</li>
				<li class="menu-item {{ request()->routeIs('admin.batches.create') ? 'active' : '' }}">
					<a href="{{ route('admin.batches.create') }}" class="menu-link">
						<div data-i18n="Create New Batch">Create New Batch</div>
					</a>
				</li>
			</ul>
		</li>

		<!-- Job Management -->
		<li class="menu-item {{ request()->is('admin/jobs*') || request()->is('admin/categories*') ? 'active open' : '' }}">
			<a href="javascript:void(0);" class="menu-link menu-toggle">
				<i class="menu-icon tf-icons ti ti-files"></i>
				<div data-i18n="Job Management">Job Management</div>
			</a>
			<ul class="menu-sub">
				<li class="menu-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
					<a href="{{ route('admin.categories.index') }}" class="menu-link">
						<div data-i18n="Category List">Category List</div>
					</a>
				</li>
				<li class="menu-item {{ request()->routeIs('admin.jobs.index') ? 'active' : '' }}">
					<a href="{{ route('admin.jobs.index') }}" class="menu-link">
						<div data-i18n="Job List">Job List</div>
					</a>
				</li>
				<li class="menu-item {{ request()->routeIs('admin.jobs.create') ? 'active' : '' }}">
					<a href="{{ route('admin.jobs.create') }}" class="menu-link">
						<div data-i18n="Create New Job">Create New Job</div>
					</a>
				</li>
			</ul>
		</li>
		<li class="menu-header small">
			<span class="menu-header-text" data-i18n="OTHER MENU">OTHER MENU</span>
		</li>
		<li class="menu-item {{ request()->is('admin/schedule-interviews*') ? 'active open' : '' }}">
			<a href="javascript:void(0);" class="menu-link menu-toggle">
				<i class="menu-icon tf-icons ti ti-paperclip"></i>
				<div data-i18n="Interview">Interview</div>
			</a>
			<ul class="menu-sub">
				<li class="menu-item {{ request()->routeIs('admin.schedule-interviews.index') ? 'active' : '' }}">
					<a href="{{ route('admin.schedule-interviews.index') }}" class="menu-link">
						<div data-i18n="Schedule Interview">Schedule Interview</div>
					</a>
				</li>
			</ul>
		</li>
		<li class="menu-item {{ request()->is('admin/candidates*') || request()->is('admin/applies*') ? 'active open' : '' }}">
			<a href="javascript:void(0);" class="menu-link menu-toggle">
				<i class="menu-icon tf-icons ti ti-user"></i>
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
				<li class="menu-item  {{ request()->is('admin/applies?status=IN REVIEW') ? 'active' : '' }}">
					<a href="{{ url('admin/applies?status=IN REVIEW') }}" class="menu-link">
						<div data-i18n="In Review">In Review</div>
					</a>
				</li>
				<li class="menu-item  {{ request()->is('admin/applies?status=NOT SUITABLE') ? 'active' : '' }}">
					<a href="{{ url('admin/applies?status=NOT SUITABLE') }}" class="menu-link">
						<div data-i18n="Not Suitable">Not Suitable</div>
					</a>
				</li>
				<li class="menu-item  {{ request()->is('admin/applies?status=SHORTLISTED') ? 'active' : '' }}">
					<a href="{{ url('admin/applies?status=SHORTLISTED') }}" class="menu-link">
						<div data-i18n="Shortlisted">Shortlisted</div>
					</a>
				</li>
				<li class="menu-item  {{ request()->is('admin/applies?status=HIRED') ? 'active' : '' }}">
					<a href="{{ url('admin/applies?status=HIRED') }}" class="menu-link">
						<div data-i18n="Hired">Hired</div>
					</a>
				</li>
			</ul>
		</li>
	</ul>
</aside>
