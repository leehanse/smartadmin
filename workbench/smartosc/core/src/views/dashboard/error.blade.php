@extends(Config::get('core::views.master'))

@section('content')
<script src="{{ asset('public/packages/smartosc/core/assets/js/dashboard/user.js?v=2.0') }}"></script> 

<div class="container" id="main-container">
    <div class="row">
        <div class="col-lg-12 offset3">
            <section class="module">
                <div class="module-head">
                    <b>{{ trans('core::all.error') }}</b>
                </div>
                <div class="module-body">
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@stop