<?php 

namespace Smartosc\Core\Controllers;

use Smartosc\Core\Controllers\BaseController;
use Smartosc\Core\Services\Validators\User as UserValidator;
use View;
use Input;
use Response;
use Request;
use Sentry;
use Config;
use URL;
use PermissionProvider;
use DB;
use Mail;

class UserController extends BaseController 
{

    /**
    * Display a list of all users
    *
    * @return Response
    */
    public function getIndex()
    {
        // get alls users
        
        
        $arr_user_groups = array();
        $user_groups_alias     = array('Super Administrator','Administrator','Supporter','Arranger','Visitor');
        $user_groups     = DB::table('groups')->whereIn('name',$user_groups_alias)->get();
        foreach($user_groups as $ug)
            $arr_user_groups[] = $ug->id;
        
        $emptyUsers      =  Sentry::getUserProvider()->getEmptyUser();
        
//        $emptyUsers = $emptyUsers->leftJoin('users_groups','users_groups.user_id', '=' , 'users.id')
//            ->where(function($query) use ($arr_user_groups) {
//                $query->whereIn('users_groups.group_id',$arr_user_groups);
//                //$query->where('users_groups.group_id','=',5);
//                $query->orWhereNull('users_groups.group_id');                
//                return $query;
//            });

        // users search
        $userId = Input::get('userIdSearch');
        if(!empty($userId))
        {
            $emptyUsers = $emptyUsers->where('users.id', $userId);
        }
        $username = Input::get('usernameSearch');
        if(!empty($username))
        {
            $emptyUsers = $emptyUsers->where('username', 'LIKE', '%'.$username.'%');
        }
        $email = Input::get('emailSearch');
        if(!empty($email))
        {
            $emptyUsers = $emptyUsers->where('email', 'LIKE', '%'.$email.'%');
        }
        $bannedUsers = Input::get('bannedSearch');
        if(isset($bannedUsers) && $bannedUsers !== "")
        {
            $emptyUsers = $emptyUsers->join('throttle', 'throttle.user_id', '=', 'users.id')
                ->where('throttle.banned', '=', $bannedUsers)
                ->select('users.id', 'users.username', 'users.last_name', 'users.first_name', 'users.email', 'users.permissions', 'users.activated');
        }
        $emptyUsers = $emptyUsers->orderBy('id','DESC');
        
        $users = $emptyUsers->paginate(20);        
        
        $datas['links'] = $users->links();
        $datas['users'] = $users;

        // ajax request : reload only content container
        if(Request::ajax())
        {
            $html = View::make(Config::get('core::views.users-list'), array('datas' => $datas))->render();

            return Response::json(array('html' => $html));
        }
        
        $this->layout = View::make(Config::get('core::views.users-index'), array('datas' => $datas));
        $this->layout->title = trans('core::users.titles.list');
        $this->layout->breadcrumb = Config::get('core::breadcrumbs.users');
    }

    /**
    * Show new user form view
    */
    public function getCreate()
    {
        $groups = Sentry::getGroupProvider()->findAll();
        $permissions = PermissionProvider::findAll();

        $this->layout = View::make(Config::get('core::views.user-create'), array('groups' => $groups, 'permissions' => $permissions));
        $this->layout->title = trans('core::users.titles.new');
        $this->layout->breadcrumb = Config::get('core::breadcrumbs.create_user');
    }

    /**
    * Create new user
    */
    public function postCreate()
    {
        \Core\Services\FilterData::removeHtmlExclude();
        try
        {
            $validator = new UserValidator(Input::all(), 'create');

            $permissionsValues = Input::get('permission');
            $permissions = $this->_formatPermissions($permissionsValues);

            if(!$validator->passes())
            {
                return Response::json(array('userCreated' => false, 'errorMessages' => $validator->getErrors()));
            }

            // create user
            $user = Sentry::getUserProvider()->create(array(
                'email'    => Input::get('email'),
                'password' => Input::get('pass'),
                'username' => Input::get('username'),
                'last_name' => (string)Input::get('last_name'),
                'middle_name' => (string)Input::get('middle_name'),
                'first_name' => (string)Input::get('first_name'),
                'permissions' => $permissions,
                'branch_id'   => Input::get('branch_id'),
                'arranger_number' => Input::get('arranger_number')
            ));

            // activate user
            $activationCode = $user->getActivationCode();
            if(Config::get('core::config.user-activation') === 'auto')
            {
                $user->attemptActivation($activationCode);
            }
            elseif(Config::get('core::config.user-activation') === 'email')
            {
                $datas = array(
                    'code' => $activationCode,
                    'username' => $user->username
                );

                // send email
                Mail::queue(Config::get('core::mails.user-activation-view'), $datas, function($message) use ($user)
                {
                    $message->from(Config::get('core::mails.email'), Config::get('core::mails.contact'))
                            ->subject(Config::get('core::mails.user-activation-object'));
                    $message->to($user->getLogin());
                });
            }

            $groups = Input::get('groups');
            if(isset($groups) && is_array($groups))
            {
                foreach($groups as $groupId)
                {
                    $group = Sentry::getGroupProvider()->findById($groupId);
                    $user->addGroup($group);
                }
            }
        }
        catch (\Cartalyst\Sentry\Users\LoginRequiredException $e){} // already catch by validators
        catch (\Cartalyst\Sentry\Users\PasswordRequiredException $e){} // already catch by validators
        catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e){}
        catch (\Cartalyst\Sentry\Users\UserExistsException $e)
        {
            return json_encode(array('userCreated' => false, 'message' => 'Username or email already exists.', 'messageType' => 'danger'));
        }
        catch(\Exception $e)
        {
            return Response::json(array('userCreated' => false, 'message' => 'Username or email already exists.', 'messageType' => 'danger'));
        }
        $message = array('userCreated' => true, 'message' => 'Created user successfully.', 'redirectUrl' => URL::route('listUsers'), 'messageType' => 'success');
        \Session::put('message', $message);
        return json_encode($message);
    }

    /**
     * Delete user
     * @param  int $userId
     * @return  Response
     */
    public function delete()
    {
        $ids = Input::get('ids', array());
        if(count($ids)){
            foreach($ids as $userId){
                if($userId !== Sentry::getUser()->getId())
                {
                    try{
                        $user = Sentry::getUserProvider()->findById($userId);
                        if($user){
                            $user->delete();
                        }
                    }catch(Exception $ex){
                        
                    }
                }
            }
        }
        return Response::json(array('deletedUser' => true, 'message' => 'Users removed with success.', 'messageType' => 'success'));
    }

    /**
     * Activate a user since the dashboard
     * @param  int $userId
     * @return Response
     */
    public function putActivate($userId)
    {
        try
        {
            $user = Sentry::getUserProvider()->findById($userId);            
            if($user->activated == 1){
               $currentUser = Sentry::getUser();
               if($user->id == $currentUser->id){
                    return Response::json(array('deletedUser' => false, 'message' => trans('core::users.messages.deactive-current-user'), 'messageType' => 'danger'));
               }else{
                    $user->activated = 0;
                    $user->save();
                    return Response::json(array('deletedUser' => true, 'message' => trans('core::users.messages.deactivate-success'), 'messageType' => 'success'));               
               }
            }else{
                $activationCode = $user->getActivationCode();
                $user->attemptActivation($activationCode);
            }
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return Response::json(array('deletedUser' => false, 'message' => trans('core::users.messages.not-found'), 'messageType' => 'danger'));
        }
        catch (\Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
        {
            return Response::json(array('deletedUser' => false, 'message' => trans('core::users.messages.activate-already'), 'messageType' => 'danger'));
        }

        return Response::json(array('deletedUser' => true, 'message' => trans('core::users.messages.activate-success'), 'messageType' => 'success'));
    }

    /**
     * Activate a user (from an email)
     * @param  string $activationCode
     */
    public function getActivate($activationCode)
    {
        $activated = false;
        try
        {
            // Find the user using the activation code
            $user = Sentry::getUserProvider()->findByActivationCode($activationCode);

            // Attempt to activate the user
            if($user->attemptActivation($activationCode))
            {
                $message = trans("Your account is successfully activated.");
                $activated = true;
            }
            else
            {
                // User activation failed
                $message = trans("Your account could not be activated.");
            }
        }
        catch(\Exception $e)
        {
            // User not found, activation found or other errors
            $message = trans("Your account could not be activated.");
        }

        $this->layout = View::make(Config::get('core::views.user-activation'), array('activated' => $activated, 'message' => $message));
    }

    /**
    * View user account
    * @param int $userId
    */
    public function getShow($userId)
    {        
        try
        {
            $user = Sentry::getUserProvider()->findById($userId);
            $throttle = Sentry::getThrottleProvider()->findByUserId($userId);
            $groups = Sentry::getGroupProvider()->findAll();

            $userPermissions = $user->getPermissions();

            // ajax request : reload only content container
            if(Request::ajax())
            {
                $html = View::make(Config::get('core::views.user-informations'), array('user' => $user, 'throttle' => $throttle))->render();

                return Response::json(array('html' => $html));
            }

            $this->layout = View::make(Config::get('core::views.user-profile'), array(
                'user' => $user,
                'throttle' => $throttle,
                'groups' => $groups,
                'ownPermissions' => $userPermissions
            ));
            
            $this->layout->title = 'Edit user: '.$user->username;
            $this->layout->breadcrumb = array(
                    array(
                        'title' => trans('core::breadcrumbs.users'),
                        'link' => URL::route('listUsers'),
                        'icon' => 'glyphicon-user'
                    ),
                    array(
                     'title' => $user->username,
                     'link' => URL::current(),
                     'icon' => ''
                    )
            );
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $this->layout = View::make(Config::get('core::views.error'), array('message' => trans('core::users.messages.not-found')));
        }
    }

    /**
    * Update user account
    * @param int $userId
    * @return Response
    */
    public function putShow($userId)
    {
        \Core\Services\FilterData::removeHtmlExclude();
        try
        {
            $validator = new UserValidator(Input::all(), 'update');

            if(!$validator->passes())
            {
                return Response::json(array('userUpdated' => false, 'errorMessages' => $validator->getErrors()));
            }

            $permissionsValues = Input::get('permission');
            $permissions = $this->_formatPermissions($permissionsValues);

            // Find the user using the user id
            $user = Sentry::getUserProvider()->findById($userId);
            $user->username = Input::get('username');
            $user->email = Input::get('email');
            $user->last_name = Input::get('last_name');
            $user->first_name = Input::get('first_name');
            $user->middle_name = Input::get('middle_name');
            $user->permissions = $permissions;
            $user->branch_id = Input::get('branch_id');
            $user->arranger_number = Input::get('arranger_number');
            
            $permissions = (empty($permissions)) ? '' : json_encode($permissions);
            // delete permissions in db
            DB::table('users')
                ->where('id', $userId)
                ->update(array('permissions' => $permissions));

            $pass = Input::get('pass');
            if(!empty($pass))
            {
                $user->password = $pass;
            }

            // Update the user
            if($user->save())
            {
                // if the user has permission to update
                $banned = Input::get('banned');
                if(isset($banned) && Sentry::getUser()->getId() !== $user->getId())
                {
                    $this->_banUser($userId, $banned);
                }

                if(Sentry::getUser()->hasAccess('user-group-management'))
                {
                    $groups = (Input::get('groups') === null) ? array() : Input::get('groups');
                    $userGroups = $user->getGroups()->toArray();
                    
                    foreach($userGroups as $group)
                    {
                        if(!in_array($group['id'], $groups))
                        {
                            $group = Sentry::getGroupProvider()->findById($group['id']);
                            $user->removeGroup($group);
                        }
                    }
                    if(isset($groups) && is_array($groups))
                    {
                        foreach($groups as $groupId)
                        {
                            $group = Sentry::getGroupProvider()->findById($groupId);
                            $user->addGroup($group);
                        }
                    }
                }
                $message = array('userUpdated' => true, 'message' => trans('core::users.messages.update-success'), 'messageType' => 'success', 'redirectUrl' => URL::route('listUsers'));
                \Session::put('message', $message);
                return Response::json($message);
            }
            else 
            {
                return Response::json(array('userUpdated' => false, 'message' => trans('core::users.messages.update-fail'), 'messageType' => 'danger'));
            }
        }
        catch(\Cartalyst\Sentry\Users\UserExistsException $e)
        {
            return Response::json(array('userUpdated' => false, 'message' => trans('core::users.messages.user-email-exists'), 'messageType' => 'danger'));
        }
        catch(\Exception $e)
        {
            return Response::json(array('userUpdated' => false, 'message' => trans('core::users.messages.user-name-exists'), 'messageType' => 'danger'));
        }
    }

    protected function _formatPermissions($permissionsValues)
    {
        $permissions = array();
        if(!empty($permissionsValues))
        {
            foreach($permissionsValues as $key => $permission)
            {
               $permissions[$key] = 1;
            }
        }

        return $permissions;
    }

    protected function _banUser($userId, $value)
    {
        $throttle = Sentry::findThrottlerByUserId($userId);
        if($value === 'no' && $throttle->isBanned() === true)
        {
            $throttle->unBan();
        }
        elseif($value === 'yes' && $throttle->isBanned() === false)
        {
            $throttle->ban();
        }
    }
}