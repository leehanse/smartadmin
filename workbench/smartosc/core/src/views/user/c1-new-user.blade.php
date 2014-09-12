@extends(Config::get('core::views.master'))

@section('footer-script')
    <script src="{{ asset('public/packages/smartosc/core/assets/js/dashboard/user.js?v=2.0') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click','#add-user', function(){
               if($("#sltGroups :selected").size() > 0){
                   $('#has_group').val(1);
               }else{
                   $('#has_group').val('');
               }
               $('#create-user-form').submit();
            });
        });
    </script>
@stop

@section('content')
<div class="container" id="main-container">
    @include('core::layouts.dashboard.c1-breadcumb',array('links' => Config::get('core::breadcrumbs.create_user'), 'header_text' => 'Create new user '))
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <div class="panel-heading">
                    <i class='clip-user'></i>
                    <b>{{ trans('core::users.new') }}</b>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" id="create-user-form" method="POST">
                            <div class="col-sm-6">
                                 <div class="form-group">
                                     <label class="control-label">{{ trans('core::users.username') }}</label> <span class="required">(*)</span>
                                    <p><input class="form-control" type="text" id="username" name="username"></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ trans('core::all.email') }}</label> <span class="required">(*)</span>
                                    <p><input class="form-control" type="text" id="email" name="email"></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ trans('core::all.password') }}</label> <span class="required">(*)</span>
                                    <p><input class="form-control" type="password"  id="pass" name="pass"></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Arranger number</label> <span class="required">(*)</span>
                                    <p><input class="form-control" type="text" id="arranger_number" name="arranger_number"></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ trans('core::users.first-name') }}</label>
                                    <p><input class="form-control" type="text" placeholder="{{ trans('core::users.first-name') }}" id="first_name" name="first_name"></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Middle name</label>
                                    <p><input class="form-control" type="text"  name="middle_name"></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ trans('core::users.last-name') }}</label>
                                    <p><input class="form-control" type="text" id="last_name" name="last_name"></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Type</label> <span class="required">(*)</span>
                                    <select class="form-control" multiple="multiple" name="groups[]" id="sltGroups"/>
                                        @foreach($groups as $group)
                                            @if($currentUser->hasAccess('user-group-management'))
                                            <option value="{{ $group->getId() }}">
                                                {{ $group->getName() }}
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>    
                                    <input type="hidden" name="has_group" id="has_group" value=""/>
                                </div>                                
                                <div class="form-group">
                                    <label class="control-label">Branch</label> <span class="required">(*)</span>
                                    @include ('brand::partials.sltBranch', array('branch_id' => ''))
                                </div>
                            </div>
                            <div class="col-sm-6">                            
                            <!--
                            <div class="form-group">
                            @if($currentUser->hasAccess('permissions-management'))
                                @include('core::layouts.dashboard.permissions-select')
                            @endif
                            </div>
                            -->
                            </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group" style="text-align: right;">
                                <button id="add-user" class="btn btn-primary" style="margin-top: 15px;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
@stop