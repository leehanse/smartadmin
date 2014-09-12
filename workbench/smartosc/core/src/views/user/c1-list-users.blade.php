<!-- start: TABLE WITH IMAGES PANEL -->
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="clip-users-2"></i>
        <b>Total : {{$datas['users']->getTotal()}} users</b>
        <div class="panel-tools">
            @if($currentUser->hasAccess('delete-user'))
            <a id="delete-item" class="btn btn-danger btn-sm" data-route-delete="{{URL::route('deleteUsers')}}">{{ trans('core::all.delete') }}</a>
            @endif

            @if($currentUser->hasAccess('create-user'))
            <a class="btn btn-info btn-new btn-sm" href="{{ URL::route('newUser') }}">Create User</a>
            @endif
        </div>                    
    </div>
    <div class="panel-body">
        <div class="pagination-box">
            <?php 
                $arrPaginationAppend = array();
                echo $datas['users']->appends($arrPaginationAppend)->links();
            ?>   
        </div>        
        <table class="table table-striped table-bordered table-hover" id="sample-table-2">
            <thead>
                <tr>
                    @if($currentUser->hasAccess('delete-user'))
                    <th class="center">
                        <div>
                            <input type="checkbox" class="check-all">
                        </div>
                    </th>
                    @endif    
                    <th>ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Middle name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <!--<th>Branch</th>-->
                    <th>Brand</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datas['users'] as $user)
                <?php
                    $throttle = $throttle = Sentry::findThrottlerByUserId($user->getId());
                ?>                
                <tr>
                    @if($currentUser->hasAccess('delete-user'))
                    <td style="text-align: center;">
                        <input type="checkbox" data-user-id="{{ $user->getId(); }}">
                    </td>
                    @endif     
                    <td>{{$user->getId();}}</td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->first_name}}</td>
                    <td>{{$user->middle_name}}</td>
                    <td>{{$user->last_name}}</td>
                    <td>
                        <a rel="nofollow" href="javascript:void(0);">
                            {{$user->email}}            
                        </a>                        
                    </td>
                    <td>
                        <?php 
                            $arr_group = array();
                            $tmp_arr_groups = $user->getGroups()->toArray();
                            if(count($tmp_arr_groups)){
                                foreach($tmp_arr_groups as $group){
                                    $arr_group[] = $group['name'];
                                }                            
                            }
                        ?>
                        {{ implode(',',$arr_group) }}                                            
                    </td>
                    <!--
                    <td>
                        <?php 
                              if($user->branch_id){
                                  $branch = Branch::find($user->branch_id);
                                  if($branch) echo $branch->name;
                              }
                        ?>
                    </td>
                    -->
                    <td>
                        <?php 
                              if($user->branch_id){
                                  $branch = Branch::find($user->branch_id); 
                                  if($branch && isset($branch->brand_id) && $branch->brand_id){
                                      $brand = Brand::find($branch->brand_id);
                                      if($brand)
                                          echo $brand->name;
                                  }
                              }
                        ?>
                    </td>
                    <td>
                        {{ $user->isActivated() ? trans('core::all.yes') : trans('core::all.no') }}
                    </td>
                    <td class="center" width="100">
                        <div>
                            @if($currentUser->hasAccess('update-user-info'))
                                <a class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="Edit" href="{{ URL::route('showUser', $user->getId()) }}"><i class="fa fa-edit"></i></a>
                            @endif    
                            @if($currentUser->hasAccess('delete-user'))
                            <a href="javascript:void(0);" class="btn-remove btn btn-xs btn-bricky tooltips" data-placement="top" data-original-title="Remove"><i class="fa fa-times fa fa-white"></i></a>
                            @endif
                            @if($currentUser->hasAccess('user-active-management'))                            
                                @if($user->isActivated())
                                    <a href="javascript:void(0);" class="activate-user btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Deactive"><i class="clip-locked"></i></a>
                                @else
                                    <a href="#" class="activate-user btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Active"><i class="clip-unlocked"></i></a>
                                @endif                            
                            @endif    
                        </div>
                    </td>
                </tr>   
                @endforeach
            </tbody>
        </table>
        <div class="pagination-box">
            <?php 
                $arrPaginationAppend = array();
                echo $datas['users']->appends($arrPaginationAppend)->links();
            ?>   
        </div>        
    </div>
</div>
<!-- end: TABLE WITH IMAGES PANEL -->