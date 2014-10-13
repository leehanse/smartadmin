@extends(Config::get('core::views.master'))

@section('footer-script')
    <script src="{{ asset('public/packages/smartcms/core/assets/js/dashboard/user.js?v=2.0') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click','#update-user', function(){
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
    @include('core::layouts.dashboard.c1-breadcumb',array('links' => Config::get('core::breadcrumbs.show_user'), 'header_text' => 'Edit user : '.$user->username ))
    
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class='panel-heading'>
                    <i class='fa fa-edit'></i>
                    Edit User Information
                </div>
                <div class="panel-body">
                    <form class="form-group" id="edit-user-form" method="PUT">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{ trans('core::users.username') }}</label> <span class="required">(*)</span>
                                <input class="form-control" type="text" id="username" name="username" value="{{ $user->username}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ trans('core::all.email') }}</label> <span class="required">(*)</span>
                                <input class="form-control" type="text" id="email" name="email" value="{{ $user->email }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ trans('core::all.password') }}</label>
                                <input class="form-control" type="password" placeholder="{{ trans('core::all.password') }}" id="pass" name="pass" onfocus="this.placeholder = ''"/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Arranger number</label> <span class="required">(*)</span>
                                <p><input class="form-control" type="text" id="arranger_number" name="arranger_number" value="{{ $user->arranger_number }}"></p>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{ trans('core::users.first-name') }}</label>
                                <input class="form-control" type="text" id="first_name" name="first_name" value="{{ $user->first_name }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Middle name</label>
                                <input class="form-control" type="text" id="middle_name" name="middle_name" value="{{ $user->middle_name  }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ trans('core::users.last-name') }}</label>
                                <input class="form-control" type="text" id="last_name" name="last_name" value="{{ $user->last_name  }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Type</label> <span class="required">(*)</span>
                                <select class="form-control" multiple="multiple" name="groups[]" id="sltGroups"/>
                                    @foreach($groups as $group)
                                        @if($currentUser->hasAccess('user-group-management'))
                                        <option value="{{ $group->getId() }}" {{ ($user->inGroup($group)) ? 'selected="selected"' : ''}}>
                                            {{ $group->getName() }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>    
                                <input type="hidden" name="has_group" id="has_group" value=""/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Branch</label> <span class="required">(*)</span>
                                @include ('brand::partials.sltBranch', array('branch_id' => $user->branch_id))
                            </div>
                        </div>                       
                        <!--
                        <div class="col-sm-5">
                            @if($currentUser->hasAccess('permissions-management'))
                                @include('core::layouts.dashboard.permissions-select',array('ownPermissions' => $ownPermissions))
                            @endif
                        </div>
                        -->                        
                        @if($user->getId() !== $currentUser->getId())
                        <div class="col-sm-2" style="display: none;">
                            <label>{{ trans('core::users.banned') }}</label>
                            <div>
                                <input id="no" name="banned" type="radio" value="no" {{ ($throttle->isBanned() === false) ? 'checked' : '' }}>
                                <label for="no" onclick="">{{ trans('core::all.no') }}</label>

                                <input id="yes" name="banned" type="radio" value="yes" {{ ($throttle->isBanned() === true) ? 'checked' : '' }}>
                                <label for="yes" onclick="">{{ trans('core::all.yes') }}</label>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <br>
                            <div class="form-group" style="text-align: right;">
                                <button id="update-user" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-4" style='display:none;'>
            <section class="panel panel-default">
            <div class="panel-heading">
                <i class='clip-info'></i>
                <b>{{ trans('core::users.information') }}</b>
            </div>
            <div class="panel-body ajax-content">
                @include('core::user.c1-user-informations')
            </div>
        </div>
    </div>
</div>
@stop