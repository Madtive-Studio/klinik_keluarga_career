@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('content')
	<section class="bg-half page-next-level" style="background: url('https://img.freepik.com/premium-photo/workspace-wide-light-office_280538-7380.jpg?semt=ais_hybrid') no-repeat center center; background-size: cover;">
		<div class="bg-overlay"></div>
		<div class="container">
			<div class="row justify-content-center text-center">
				<div class="col-md-12">
					<img src="{{ asset('check.png') }}" class="d-block mx-auto text-center" width="125" alt="">
					<h5 class="text-white mb-0 py-3">Email kamu berhasil di verifikasi <br> Selanjutnya kamu dapat login pada tombol di bawah ini.</h5>
					<a href="{{ route('candidate.login') }}" class="mx-auto text-center btn btn-primary">Login</a>
				</div>
			</div>
		</div>
	</section>
@endsection
