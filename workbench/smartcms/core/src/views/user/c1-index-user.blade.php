@extends(Config::get('core::views.master'))

@section('content')
<div class="container" id="main-container">
    @include('core::layouts.dashboard.c1-confirmation-modal', array('title' => trans('core::all.confirm-delete-title'), 'content' => trans('core::all.confirm-delete-message'), 'type' => 'delete-user'))    
    @include('core::layouts.dashboard.c1-breadcumb',array('links' => Config::get('core::breadcrumbs.users'), 'header_text' => 'List Users'))
    <?php if(Session::get('message')):?>
        <input type='hidden' name='messages' id='messages' value='<?php echo json_encode(Session::get('message'));?>'/>
        <?php Session::forget('message'); ?>
    <?php endif;?>    
    <div class="row">
        <div class="col-lg-12">
            <section class="module">
                <div class="module-head">                    
                </div>
                <div class="module-body ajax-content">
                    @include('core::user.c1-list-users')
                </div>
            </section>
        </div>      
    </div>
</div>
@stop

@section('footer-script')
    <script src="{{ asset('public/packages/smartcms/core/assets/js/dashboard/user.js?v=2.0') }}"></script>    
@stop