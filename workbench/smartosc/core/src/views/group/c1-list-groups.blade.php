<div class="pagination-box">
    <?php 
        $arrPaginationAppend = array();
        echo $groups->appends($arrPaginationAppend)->links();
    ?>   
</div>
<table class="table table-striped table-bordered table-hover" id="sample-table-2">
<thead>
    <tr>
        <th class="col-lg-1" style="text-align: center;"><input type="checkbox" class="check-all"></th>
        <th class="col-lg-1" style="text-align: center;">Id</th>
        <th class="col-lg-2">{{ trans('core::all.name') }}</th>
        <th class="col-lg-7">{{ trans('core::navigation.permissions') }}</th>
        <th class="col-lg-1" style="text-align: center;">Action</th>
    </tr>
</thead>
<tbody>
    @foreach ($groups as $group)
    <tr>
        <td style="text-align: center;">
            <input type="checkbox" data-group-id="{{ $group->getId(); }}">
        </td>
        <td style="text-align: center;">{{ $group->getId() }}</td>
        <td>{{ $group->getName() }}</td>
        <td>
            <?php 
                $p_permissions   = Smartosc\Core\Services\Core\CoreService::getAllPermissions();
                $ownPermissions = $group->getPermissions();
                
                $arr_own_permissions_key = array_keys($ownPermissions);   
                $arr_own_permissions     = array();
                $list_permissions        = $p_permissions;

                if(count($p_permissions)){
                    foreach($p_permissions as $md => $permission_arr){
                        if(is_array($permission_arr) && count($permission_arr) ){
                            foreach($permission_arr as $key => $permission){
                                if(is_array($permission)){
                                    $value       = isset($permission['value']) ? $permission['value'] : '';
                                    $description = isset($permission['description']) ? $permission['description'] : '';
                                    if($value){                            
                                        if(!$description){
                                            $permission['description'] = $value;
                                        }
                                        if(in_array($value, $arr_own_permissions_key)){
                                            unset($list_permissions[$md][$key]);                                
                                            $p = array(
                                                "value"         => $value,
                                                "description"   => $description,
                                                "module"        => $md
                                            );
                                            $arr_own_permissions[$value] = $p;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }            
            ?>
            <?php if(isset($arr_own_permissions) && !empty($arr_own_permissions)): ?>
                <ul style="padding:0px;margin-left:10px;list-style:circle;">
                    <?php foreach($arr_own_permissions as $permission_arr): ?>
                        <li><?php echo $permission_arr["description"]; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </td>
        <td style="text-align: center;">
            <a class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="Edit" href="{{ URL::route('showGroup', $group->getId()) }}"><i class="fa fa-edit"></i></a>
            <a href="javascript:void(0);" class="btn-remove btn btn-xs btn-bricky tooltips" data-placement="top" data-original-title="Remove"><i class="fa fa-times fa fa-white"></i></a>
        </td>
    </tr>
    @endforeach
</tbody>
</table>