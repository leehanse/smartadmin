<?php 

namespace Smartcms\Core\Controllers;

use Smartcms\Core\Controllers\BaseController;
use Smartcms\Core\Services\Validators\Group as GroupValidator;
use PermissionProvider;
use View;
use Input;
use Config;
use Response;
use Sentry;
use Request;
use DB;
use URL;

class GroupController extends BaseController 
{
    /**
    * List of groups
    */
    public function getIndex()
    {
        $emptyGroup =  Sentry::getGroupProvider()->createModel();

        // Ajax search
        $groupId = Input::get('groupIdSearch');
        if(!empty($groupId))
        {
            $emptyGroup = $emptyGroup->where('id', $groupId);
        }
        $groupname = Input::get('groupnameSearch');
        if(!empty($groupname))
        {
            $emptyGroup = $emptyGroup->where('name', 'LIKE', '%'.$groupname.'%');
        }

        $groups = $emptyGroup->paginate(Config::get('core::config.item-perge-page'));

        // ajax: reload only the content container
        if(Request::ajax())
        {
            $html = View::make(Config::get('core::views.groups-list'), array('groups' => $groups))->render();
            
            return Response::json(array('html' => $html));
        }
        
        $this->layout = View::make(Config::get('core::views.groups-index'), array('groups' => $groups));
        $this->layout->title = trans('core::groups.titles.list');
    }
    
    /**
    * Show create group view
    */
    public function getCreate()
    {
        $permissions = PermissionProvider::findAll();

        $this->layout = View::make(Config::get('core::views.group-create'), array('permissions' => $permissions));
        $this->layout->title = trans('core::groups.titles.new');
    }

    /**
    * Create group
    */
    public function postCreate()
    {
        \Core\Services\FilterData::removeHtmlExclude();
        $groupname = Input::get('groupname');
        $permissions = array();
        
        $errors = $this->_validateGroup(Input::get('permission'), $groupname, $permissions);
        if(!empty($errors))
        {
            return Response::json(array('groupCreated' => false, 'errorMessages' => $errors));
        }
        else 
        {
            try
            {
                // create group
                Sentry::getGroupProvider()->create(array(
                    'name' => $groupname,
                    'permissions' => $permissions,
                ));
            }
            catch (\Cartalyst\Sentry\Groups\NameRequiredException $e) {}
            catch (\Cartalyst\Sentry\Groups\GroupExistsException $e)
            {
                return Response::json(array('groupCreated' => false, 'message' => trans('core::groups.messages.exists'), 'messageType' => 'danger'));
            }
        }
        $message = array('groupCreated' => true,'message' => 'Created group successfully.', 'redirectUrl' => URL::route('listGroups'), 'messageType' => 'success');
        \Session::put('message', $message);
        return Response::json($message);
    }
    
    /**
     * Show group
     * @param type $groupId
     */
    public function getShow($groupId)
    {
        try
        {
            $group = Sentry::getGroupProvider()->findById($groupId);
            $groupPermissions = $group->getPermissions();
            
            /*
            $permissions = PermissionProvider::findAll();

            $groupPermissions = array();
            foreach($group->getPermissions() as $permissionValue => $key)
            {
                try
                {
                    $p = PermissionProvider::findByValue($permissionValue);
                    foreach($permissions as $key => $permission)
                    {
                        if($p->getId() === $permission->getId())
                        {
                            $groupPermissions[] = $permission;
                            unset($permissions[$key]);
                        }
                    }
                }
                catch(\Smartcms\Core\Models\Permissions\PermissionNotFoundException $e){}
            }
            */
            
            $userids = array();
            foreach(Sentry::getUserProvider()->findAllInGroup($group) as $user) 
            {
                $userids[] = $user->id;
            }

            // get users in group
            $users = Sentry::getUserProvider()->createModel()->join('users_groups', 'users.id', '=', 'users_groups.user_id')->where('users_groups.group_id', '=', $group->getId())
                    ->paginate(Config::get('core::config.item-perge-page'));

            // users not in group
            $candidateUsers = array();
            $allUsers = Sentry::getUserProvider()->findAll();
            foreach($allUsers as $user)
            {
                if(!$user->inGroup($group))
                {
                    $candidateUsers[] = $user;
                }
            }

            // ajax request : reload only content container
            if(Request::ajax())
            {
                $html = View::make(Config::get('core::views.users-in-group'), array('group' => $group, 'users' => $users, 'candidateUsers' => $candidateUsers))->render();
                
                return Response::json(array('html' => $html));
            }
            
            $this->layout = View::make(Config::get('core::views.group-edit'), array('group' => $group, 'users' => $users, 'candidateUsers' => $candidateUsers, 'ownPermissions' => $groupPermissions));
            $this->layout->title = 'Edit Group : '.$group->getName();           
        }
        catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            $this->layout = View::make(Config::get('core::views.error'), array('message' => trans('core::groups.messages.not-found')));
        }
    }

    /**
     * Edit group action
     * @param int $groupId
     */
    public function putShow($groupId)
    {
        \Core\Services\FilterData::removeHtmlExclude();
        $groupname = Input::get('groupname');
        $permissions = array();

        $errors = $this->_validateGroup(Input::get('permission'), $groupname, $permissions);
        if(!empty($errors))
        {
            return Response::json(array('groupUpdated' => false, 'errorMessages' => $errors));
        }
        else 
        {
            try
            {
                $group = Sentry::getGroupProvider()->findById($groupId);
                $group->name = $groupname;
                $group->permissions = $permissions;

                $permissions = (empty($permissions)) ? '' : json_encode($permissions);
                // delete permissions in db
                DB::table('groups')
                    ->where('id', $groupId)
                    ->update(array('permissions' => $permissions));

                if($group->save())
                {
                    $message = array('groupUpdated' => true, 'message' => trans('core::groups.messages.success'), 'messageType' => 'success', 'redirectUrl' => URL::route('listGroups'));
                    \Session::put('message', $message);
                    return Response::json($message);
                }
                else 
                {
                    return Response::json(array('groupUpdated' => false, 'message' => trans('core::groups.messages.try'), 'messageType' => 'danger'));
                }
            }
            catch (\Cartalyst\Sentry\Groups\NameRequiredException $e) {}
            catch (\Cartalyst\Sentry\Groups\GroupExistsException $e)
            {
                return Response::json(array('groupUpdated' => false, 'message' => trans('core::groups.messages.exists'), 'messageType' => 'danger'));
            }
        }
    }
       
    /**
     * Delete group
     * @param  int $groupId
     * @return Response
     */
    public function delete()
    {
        $ids = Input::get('ids', array());
        if(count($ids)){
            foreach($ids as $groupId){
                try{
                    $group = Sentry::getGroupProvider()->findById($groupId);
                    $group->delete();                        
                }catch(Exception $ex){

                }
            }
        }        
        return Response::json(array('deletedGroup' => true, 'message' => trans('core::groups.messages.delete-success'), 'messageType' => 'success'));
    }
    
    /**
     * Remove user from group
     * @param int $groupId
     * @param int $userId
     * @return Response
     */
    public function deleteUserFromGroup($groupId, $userId)
    {
        try
        {
            $user = Sentry::getUserProvider()->findById($userId);
            $group = Sentry::getGroupProvider()->findById($groupId);
            $user->removeGroup($group);
            
            return Response::json(array('userDeleted' => true, 'message' => trans('core::groups.messages.user-removed-success'), 'messageType' => 'success'));
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return Response::json(array('userDeleted' => false, 'message' => trans('core::users.messages.not-found'), 'messageType' => 'danger'));
        }
        catch(\Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            return Response::json(array('userDeleted' => false, 'message' => trans('core::groups.messages.not-found'), 'messageType' => 'danger'));
        }
    }
    
    /**
     * Add a user in a group
     * @return Response
     */
    public function addUserInGroup()
    {
        try
        {
            $user = Sentry::getUserProvider()->findById(Input::get('userId'));
            $group = Sentry::getGroupProvider()->findById(Input::get('groupId'));
            $user->addGroup($group);

            return Response::json(array('userAdded' => true, 'message' => trans('core::groups.messages.user-add-success'), 'messageType' => 'success'));
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return Response::json(array('userAdded' => false, 'message' => trans('core::users.messages.not-found'), 'messageType' => 'danger'));
        }
        catch(\Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            return Response::json(array('userAdded' => false, 'message' => trans('core::groups.messages.not-found'), 'messageType' => 'danger'));
        }
    }

    /**
     * Validate group informations
     * @param array $permissionsValues
     * @param string $groupname
     * @return array
     */
    protected function _validateGroup($permissionsValues, $groupname, &$permissions)
    {
        $errors = array();
        // validate permissions
        if(!empty($permissionsValues))
        {
            foreach($permissionsValues as $key => $permission)
            {
               $permissions[$key] = 1;
            }
        }
        // validate group name
        $validator = new GroupValidator(Input::all());

        $gnErrors = array();
        if(!$validator->passes())
        {
            $gnErrors = $validator->getErrors();
        }
        
        return $gnErrors;
    }
}
