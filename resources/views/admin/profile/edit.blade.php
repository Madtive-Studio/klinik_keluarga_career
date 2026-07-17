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
						<h5 class="mb-0">My Profile</h5>
					</div>
					<div class="card-body">
						<form method="POST" action="{{ route('admin.profile.update') }}">
							@csrf
							@method('PUT')

							<h6 class="mb-3">Informasi Akun</h6>
							<div class="mb-3">
								<label class="form-label">Nama</label>
								<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
								@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">Email</label>
								<input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
								@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div class="mb-4">
								<label class="form-label">Level</label>
								<input type="text" class="form-control" value="{{ strtoupper($user->level) }}" readonly>
							</div>

							<h6 class="mb-3">Ubah Password</h6>
							<p class="text-muted small">Kosongkan jika tidak ingin mengubah password.</p>
							<div class="mb-3">
								<label class="form-label">Password Saat Ini</label>
								<input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
								@error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">Password Baru</label>
								<input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
								@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div class="mb-4">
								<label class="form-label">Konfirmasi Password Baru</label>
								<input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
							</div>

							<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
