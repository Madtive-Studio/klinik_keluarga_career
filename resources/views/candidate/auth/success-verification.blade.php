@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('content')
	<section class="bg-half page-next-level">
		<div class="bg-overlay"></div>
		<div class="container">
			<div class="row justify-content-center text-center">
				<div class="col-md-12">
					<img src="{{ asset('check.png') }}" class="d-block mx-auto text-center" width="125" alt="">
					<h5 class="text-white mb-0 py-3">{{ __('candidate.verification.success_message') }} <br> {{ __('candidate.verification.success_next') }}</h5>
					<a href="{{ route('candidate.login.form') }}" class="mx-auto text-center btn btn-primary">{{ __('common.login') }}</a>
				</div>
			</div>
		</div>
	</section>
@endsection
