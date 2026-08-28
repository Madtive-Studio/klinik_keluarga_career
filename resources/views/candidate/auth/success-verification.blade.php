@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.verification.success_title', ['app' => config('app.name')]))
@section('content')
	<section class="section py-5">
		<div class="container py-4">
			<div class="row justify-content-center">
				<div class="col-lg-6 col-md-8 text-center">
					<div class="card border rounded shadow-sm p-4 p-md-5 bg-white">
						<img src="{{ asset('check.png') }}" class="d-block mx-auto mb-3" width="110" alt="Success">
						<h4 class="text-primary fw-bold mb-3">{{ __('candidate.verification.success_message') }}</h4>
						<p class="text-muted mb-4">{{ __('candidate.verification.success_next') }}</p>
						<div>
							<a href="{{ route('candidate.login.form') }}" class="btn btn-primary px-4 py-2">
								<i class="mdi mdi-login me-1"></i> {{ __('common.login') }}
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
