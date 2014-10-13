<?php

namespace Smartcms\Core\Controllers;

use Smartcms\Core\Controllers\BaseController;
use Smartcms\Core\Services\Validators\User as UserValidator;
use View;
use Input;
use Sentry;
use Redirect;
use Config;
use Response;
use Validator;
use Image;
use Hash;

class DashboardController extends BaseController
{
    public function indexDashboard(){
        return '<h1>Dash Board</h1>';
    }
    
    /**
    * Index loggued page
    */
    public function getIndex()
    {
        $this->layout = View::make(Config::get('core::views.dashboard-index'));
        $this->layout->title = trans('core::all.titles.index');
        $this->layout->breadcrumb = Config::get('core::breadcrumbs.dashboard');
    }

    /**
    * Login page
    */
    public function getLogin()
    {
        $this->layout = View::make(Config::get('core::views.login'));
        $this->layout->title = trans('core::all.titles.login');
        $this->layout->breadcrumb = Config::get('core::breadcrumbs.login');
    }

    /**
    * Login post authentication
    */
    public function postLogin()
    {
        $credentials = array(
            'username'    => Input::get('username'),
            'password'    => Input::get('password'),
        );
        try
        {
            $validator = new UserValidator(Input::all(), 'login');

            if(!$validator->passes())
            {
                 return View::make(Config::get('core::views.login'),
                                            array('errorMessages' => $validator->getErrors()));
                 //return Response::json(array('logged' => false, 'errorMessages' => $validator->getErrors()));
            }

            // authenticate user
            Sentry::authenticate($credentials, Input::get('remember'));
        }
        catch(\Cartalyst\Sentry\Throttling\UserBannedException $e)
        {
            return View::make(Config::get('core::views.login'),
                                        array(
                                                'errorMessages' => trans('core::all.messages.banned'),
                                                'credentials'   => $credentials
                                            ));
            //return Response::json(array('logged' => false, 'errorMessage' => trans('core::all.messages.banned'), 'errorType' => 'danger'));
        }
        catch(\Cartalyst\Sentry\Users\UserNotActivatedException $e ){
            return View::make(Config::get('core::views.login'),
                                        array(
                                            'errorMessages' => 'Sorry, this user is not active.',
                                            'credentials'   => $credentials
                                        ));
        }
        catch (\RuntimeException $e)
        {
            return View::make(Config::get('core::views.login'),
                                        array(
                                            'errorMessages' => $e->getMessage(),
                                            'credentials'   => $credentials
                                        ));
            //return Response::json(array('logged' => false, 'errorMessage' => trans('core::all.messages.login-failed'), 'errorType' => 'danger'));
        }
        return Redirect::route("indexDashboard");
        //return Response::json(array('logged' => true));
    }

    /**
    * Logout user
    */
    public function getLogout()
    {
        Sentry::logout();

        return Redirect::route('indexDashboard');
    }

    /**
    * Access denied page
    */
    public function getAccessDenied()
    {
        $this->layout = View::make(Config::get('core::views.error'), array('message' => trans('core::all.messages.denied')));
        $this->layout->title = trans('core::all.titles.error');
        $this->layout->breadcrumb = Config::get('core::breadcrumbs.dashboard');
    }
    
    public function getProfile(){
        $user = Sentry::getUser();
        return View::make('core::user.c1-user-profile',array('user' => $user));
    }

    public function postProfile(){
        $user = Sentry::getUser();
        $user->first_name = Input::get('first_name');
        $user->middle_name = Input::get('middle_name');
        $user->last_name = Input::get('last_name');
        $user->email = Input::get('email');
        $password = Input::get('password');
        $confirm_password = Input::get('password_again');                
        
        $validator = Validator::make(
            Input::all(),
            array(
                'email' => 'required',
                'password' => 'same:password_again'
            ),
            array(
                'required' => 'This field is required.',
            )
        );
        if($validator->fails()){
                $messages = array('profileUpdated' => false, 'errorMessages' => $validator->messages()->getMessages());
            }else{        
                $image = Input::file('image');
                if($image){
                    $extension = $image->getClientOriginalExtension();
                    $image = Image::make($image)->save('public/images/user/'.uniqid().'.'.$extension); 
                    $user->image = $image->basename;
                }   
                if($password){
                    $user->password = $password;
                }
                $user->save();
                if(!$password)
                    $messages = array('profileUpdated' => true, 'message' => 'Updated user profile successfully.' , 'messageType' => 'success');                    
                else
                    $messages = array('profileUpdated' => true, 'message' => 'Updated user profile successfully. Logout to check password changed.' , 'messageType' => 'success');                    
            }
        return View::make('core::user.c1-user-profile',array('user' => $user, 'messages' => $messages));
    }    
}