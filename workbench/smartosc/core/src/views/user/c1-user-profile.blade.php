@extends(Config::get('core::views.master'))

@section('header-style')
    <link rel="stylesheet" href="{{ asset('public/clipone/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css') }}">
@stop

@section('footer-script')
    <script src="{{ asset('public/clipone/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js') }}"></script>
@stop
@section('content')
<form action="" role="form" id="form" method='post' enctype="multipart/form-data">
<div class="panel panel-default">   
    <div class="panel-body">
        <?php if(isset($messages) && $messages):?>
            <input type='hidden' name='messages' id='messages' value='<?php echo json_encode($messages);?>'/>
        <?php endif;?>        
        <div class="col-md-12">
                <h3>Account Info</h3>
                <hr>
        </div>
        <div id='main-container'></div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">
                        First Name
                </label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{$user->first_name}}">
            </div>
            <div class="form-group">
                <label class="control-label">
                        Middle Name
                </label>
                <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{$user->middle_name}}">
            </div>
            <div class="form-group">
                <label class="control-label">
                        Last Name
                </label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{$user->last_name}}">
            </div>
            <div class="form-group">
                    <label class="control-label">
                            Email Address
                    </label>
                    <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}">
            </div>
            <div class="form-group">
                    <label class="control-label">
                            Username
                    </label>
                    <input type="text" class="form-control" disabled='disabled' id="username" name="username" value="{{$user->username}}">
            </div>            
            <div class="form-group">
                    <label class="control-label">
                            Password
                    </label>
                    <input type="password" class="form-control" name="password" id="password">
            </div>
            <div class="form-group">
                    <label class="control-label">
                            Confirm Password
                    </label>
                    <input type="password"  class="form-control" id="password_again" name="password_again">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>
                        Image Upload
                </label>
                <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="width: 150px;">
                            @if($user->image)
                                <img src="{{URL::route('image',array('module' => 'user','src'=> $user->image, 'size'=> '150x'))}}" alt="">
                            @else
                                <img src="{{URL::route('image',array('module' => 'core','src'=> 'default.jpg', 'size'=> '150x'))}}" alt="">
                            @endif
                        </div>
                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
                        <div class="user-edit-image-buttons">
                                <span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
                                    <input type="file" name='image'>
                                </span>
                                <a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
                                        <i class="fa fa-times"></i> Remove
                                </a>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
            <div class="col-md-8">                   
            </div>
            <div class="col-md-4">
                    <button class="btn btn-teal btn-block" type="submit">
                            Update <i class="fa fa-arrow-circle-right"></i>
                    </button>
            </div>
    </div>
</div>    
</form>
@stop
