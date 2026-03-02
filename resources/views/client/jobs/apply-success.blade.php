@extends('client.layouts.header')
@section('content')
	<section class="bg-half page-next-level" style="background: url('https://img.freepik.com/premium-photo/workspace-wide-light-office_280538-7380.jpg?semt=ais_hybrid') no-repeat center center; background-size: cover;">
		<div class="bg-overlay"></div>
		<div class="container">
			<div class="row justify-content-center text-center">
				<div class="col-md-6 col-sm-12">
					<img src="{{ asset('check.png') }}" class="d-block mx-auto text-center" width="125" alt="">
					<h5 class="text-white mb-0 pt-4">Terima kasih sudah melamar pada posisi pekerjaan ini.</h5>
					<p class="text-white mb-0 pb-3 pt-2">Selanjutnya kamu dapat lihat email dan status lamaran kamu pada tombol di bawah ini atau di menu Lamaran Saya.</p>
					<a href="{{ route('client.apply.my') }}" class="mx-auto text-center btn btn-primary">Lihat Lamaran Saya</a>
				</div>
			</div>
		</div>
	</section>
@endsection
