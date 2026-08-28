@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		@if ($message = Session::get('success'))
			<div class="alert alert-success" role="alert">
				<strong>{{ $message }}</strong>
			</div>
		@endif
		<div class="row">
			<div class="col-md-8">
				<div class="card mb-4">
					<div class="card-header">
						<h5 class="mb-0">{{ __('admin.profile.title') }}</h5>
					</div>
					<div class="card-body">
						<form method="POST" action="{{ route('admin.profile.update') }}">
							@csrf
							@method('PUT')

							<h6 class="mb-3">{{ __('admin.profile.account_info') }}</h6>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.profile.name') }}</label>
								<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
								@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.profile.email') }}</label>
								<input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
								@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">Username</label>
								<input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
								@error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div class="mb-4">
								<label class="form-label">{{ __('admin.profile.level') }}</label>
								<input type="text" class="form-control" value="{{ strtoupper($user->level) }}" readonly>
							</div>

							<h6 class="mb-3">{{ __('admin.profile.change_password') }}</h6>
							<p class="text-muted small">{{ __('admin.profile.password_hint') }}</p>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.profile.current_password') }}</label>
								<input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
								@error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.profile.new_password') }}</label>
								<input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
								@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div class="mb-4">
								<label class="form-label">{{ __('admin.profile.confirm_password') }}</label>
								<input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
							</div>

							<button type="submit" class="btn btn-primary">{{ __('admin.profile.save') }}</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
