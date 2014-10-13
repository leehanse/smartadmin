@extends(Config::get('core::views.master'))

@section('footer-script')
<script src="{{ asset('public/packages/smartcms/core/assets/js/dashboard/group.js?v=2.0') }}"></script>
@stop

@section('content')
<div class="container" id="main-container">
    @include('core::layouts.dashboard.c1-breadcumb',array('links' => Config::get('core::breadcrumbs.create_group'), 'header_text' => 'Create new group' ))
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <div class="panel-heading">
                    <i class='clip-users'></i>
                    <b>{{ trans('core::groups.new') }}</b>
                </div>
                <div class="panel-body">
                    <form class="form-group" id="create-group-form" method="POST">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('core::groups.name') }}</label>
                                    <input class="col-lg-12 form-control" type="text" id="groupname" name="groupname">
                               </div>
                            </div>
                            <div class="col-lg-4">
                            @if($currentUser->hasAccess('permissions-management'))
                                @include('core::layouts.dashboard.permissions-select', array('permissions'=> $permissions))
                            @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="control-group">
                                    <button id="create-group" class="btn btn-primary">{{ trans('core::all.create') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
@stop