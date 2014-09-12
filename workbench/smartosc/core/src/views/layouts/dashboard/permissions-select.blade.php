<?php 
    $p_permissions   = Smartosc\Core\Services\Core\CoreService::getAllPermissions();
    if(!isset($ownPermissions)) $ownPermissions = array();
    
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
<style>
    .input-group-addon{
        cursor: pointer;
    }    
</style>
<label class="control-label">{{ trans('core::permissions.permissions')}}</label>
<div class="input-group">
    <span class="input-group-addon"><span class="glyphicon glyphicon-plus-sign add-input"></span></span>
    <select class="form-control permissions-select">
        <option value=''> -- Select permission --</option>
        <?php if(count($list_permissions)):?>
            <?php foreach($list_permissions as $md => $permission_arr): ?>
                <optgroup class="optgroup-{{$md}}" label="<?php echo ucfirst($md);?>">
                    <?php if(is_array($permission_arr) && count($permission_arr) ): ?>
                            <?php foreach($permission_arr as $permission):?>
                                <?php if(is_array($permission) && isset($permission['value'])):?>
                                    <option value="permission[<?php echo $permission['value']; ?>]"><?php echo $permission['description'];?></option>
                                <?php endif;?>
                        <?php endforeach;?>
                    <?php endif;?>
                </optgroup>
            <?php endforeach;?>
        <?php endif;?>
    </select>
</div>
<br>
<div class="input-container">
<?php if(isset($arr_own_permissions) && !empty($arr_own_permissions)): ?>
    <?php foreach($arr_own_permissions as $permission_arr): ?>
        <div class="form-group">
            <p class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-minus-sign remove-input"></span></span>
                <input readonly type="text" class="form-control" data-module="{{ $permission_arr['module'] }}" name="permission[{{ $permission_arr["value"] }}]" value="{{ $permission_arr["description"] }}"/>
            </p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>
<br>