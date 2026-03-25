@if ($message = Session::get('error'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-danger" role="alert">
                <p class="mb-0">{!! $message !!}</p>
            </div>
        </div>
    </div>
@endif
@if ($message = Session::get('warning'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-warning" role="alert">
                <p class="mb-0">{!! $message !!}</p>
            </div>
        </div>
    </div>
@endif
@if ($message = Session::get('success'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success" role="alert">
                <p class="mb-0">{!! $message !!}</p>
            </div>
        </div>
    </div>
@endif
@if ($message = Session::get('info'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info" role="alert">
                <p class="mb-0">{!! $message !!}</p>
            </div>
        </div>
    </div>
@endif
