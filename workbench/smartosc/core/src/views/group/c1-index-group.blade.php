@extends(Config::get('core::views.master'))

@section('footer-script')
<script src="{{ asset('public/packages/smartosc/core/assets/js/dashboard/group.js?v=2.0') }}"></script>
@stop

@section('content')
<div class="container" id="main-container">
 @include('core::layouts.dashboard.c1-confirmation-modal',  array('title' => trans('core::all.confirm-delete-title'), 'content' => trans('core::all.confirm-delete-message'), 'type' => 'delete-group'))
 @include('core::layouts.dashboard.c1-breadcumb',array('links' => Config::get('core::breadcrumbs.groups'), 'header_text' => 'List Groups' )) 
    <?php if(Session::get('message')):?>
        <input type='hidden' name='messages' id='messages' value='<?php echo json_encode(Session::get('message'));?>'/>
        <?php Session::forget('message'); ?>
    <?php endif;?>    
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <div class="panel-heading">
                    <i class='clip-users'></i>
                    <b>{{ trans('core::groups.all') }}</b>
                    <div class="panel-tools">
                        <a id="delete-item" class="btn btn-danger groups btn-sm" data-route-delete="{{URL::route('deleteGroup')}}">{{ trans('core::all.delete') }}</a>
                        <a class="btn btn-info btn-new btn-sm" href="{{ URL::route('newGroup') }}">{{ trans('core::groups.new') }}</a>
                    </div>                    
                </div>
                <div class="panel-body ajax-content">
                     @include('core::group.c1-list-groups')
                </div>
            </section>
        </div>        
    </div>
</div>
@stop