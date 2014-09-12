<?php
/**
 * @url    : 
 * @method : get,post,delete,put. Default <=> get
 * @login  : = 1  <=> required login, 
 *             0  <=> not required login , 
 *             -1 <=> notAuth (filter) check authenticate and redirect to session URL
 * @action : - Controller@action <=> action of this package controller
 *           - {PackageName}\Controller@Action
 * @permissions <=> list permission required
 * @frontend: 1 <=> is frontend link (url not contain prefix "/admin")
 *            0 <=> is backend link ( url contain prefix "/admin") : default
 */
return array(
    'image'      => array(
        'url'            => Config::get('core::config.image-uri') . '/{module?}/{src?}/{size?}',
        'action'         => 'CoreController@getImage',
        'login'          => 0,
    ),
    
    'indexBackend' => array(
        'url'            => '',
        'action'         => 'DashboardController@getIndex',
        'login'          => 1,
    ),
    'indexDashboard' => array(
        'url'            => '/dashboard',
        'action'         => 'DashboardController@indexDashboard',
        'login'          => 1,
    ),
    'logout'        => array(
        'url'           => 'logout',
        'action'        => 'DashboardController@getLogout',
        'login'         => 1        
    ),
    'accessDenied' => array(
        'url'           => 'access-denied',
        'action'        => 'DashboardController@getAccessDenied',
        'login'         => 1
    ),
    'listUsers' => array(
        'url'           => 'users',
        'action'        => 'UserController@getIndex',
        'login'         => 1,
        'permissions'   => 'view-users-list'        
    ),
    'deleteUsers' => array(
        'url'           => 'delete-users',
        'method'        => 'delete',
        'action'        => 'UserController@delete',
        'login'         => 1,
        'permissions'   => 'delete-user'
    ),
    'newUserPost' => array(
        'url'           => 'user/new',
        'method'        => 'post',
        'action'        => 'UserController@postCreate',
        'login'         => 1,
        'permissions'   => 'create-user'        
    ),
    'newUser'   => array(
        'url'           => 'user/new',
        'method'        => 'get',
        'action'        => 'UserController@getCreate',
        'login'         => 1,
        'permissions'   => 'create-user'
    ),
    'showUser'  => array(
        'url'           => 'user/{userId}',
        'action'        => 'UserController@getShow',
        'login'         => 1,
        'permissions'   => 'update-user-info'
    ),
    'putUser'   => array(
        'url'           => 'user/{userId}',
        'method'        => 'put',
        'action'        => 'UserController@putShow',
        'login'         => 1,
        'permissions'   => 'update-user-info'
    ),
    'putActivateUser'   => array(
        'url'           => 'user/{userId}/activate',
        'method'        => 'put',
        'action'        => 'UserController@putActivate',
        'login'         => 1,
        'permissions'   => 'update-user-info'
    ),
    'listGroups'   => array(
        'url'           => 'groups',
        'method'        => 'get',
        'action'        => 'GroupController@getIndex',
        'login'         => 1,
        'permissions'   => 'groups-management'
    ),
    'newGroupPost'   => array(
        'url'           => 'group/new',
        'method'        => 'post',
        'action'        => 'GroupController@postCreate',
        'login'         => 1,
        'permissions'   => 'groups-management'
    ),
    'newGroup'   => array(
        'url'           => 'group/new',
        'method'        => 'get',
        'action'        => 'GroupController@getCreate',
        'login'         => 1,
        'permissions'   => 'groups-management'
    ),
    'deleteGroup'   => array(
        'url'           => 'delete-groups',
        'method'        => 'delete',
        'action'        => 'GroupController@delete',
        'login'         => 1,
        'permissions'   => 'groups-management'
    ),
    'showGroup'   => array(
        'url'           => 'group/{groupId}',
        'method'        => 'get',
        'action'        => 'GroupController@getShow',
        'login'         => 1,
        'permissions'   => 'groups-management'
    ),
    'putGroup'   => array(
        'url'           => 'group/{groupId}',
        'method'        => 'put',
        'action'        => 'GroupController@putShow',
        'login'         => 1,
        'permissions'   => 'groups-management'
    ),
    'deleteUserGroup'   => array(
        'url'           => 'group/{groupId}/user/{userId}',
        'method'        => 'delete',
        'action'        => 'GroupController@deleteUserFromGroup',
        'login'         => 1,
        'permissions'   => 'user-group-management'
    ),
    'addUserGroup'   => array(
        'url'           => 'group/{groupId}/user/{userId}',
        'method'        => 'post',
        'action'        => 'GroupController@addUserInGroup',
        'login'         => 1,
        'permissions'   => 'user-group-management'
    ),
    'getLogin'   => array(
        'url'           => 'login',
        'method'        => 'get',
        'action'        => 'DashboardController@getLogin',
        'login'         => -1,
        'permissions'   => ''
    ),
    'postLogin'   => array(
        'url'           => 'login',
        'method'        => 'post',
        'action'        => 'DashboardController@postLogin',
        'login'         => -1,
        'permissions'   => ''
    ),
    'getActivate'   => array(
        'url'           => 'user/activation/{activationCode}',
        'method'        => '',
        'action'        => 'UserController@getActivate',
        'login'         => 0,
        'permissions'   => ''
    ),
    'getProfile'    => array(
        'url'           => 'user/profile',
        'method'        => '',
        'action'        => 'DashboardController@getProfile',
        'login'         => 1,
        'permissions'   => ''
    ),
    'postProfile'    => array(
        'url'           => 'user/profile',
        'method'        => 'post',
        'action'        => 'DashboardController@postProfile',
        'login'         => 1,
        'permissions'   => ''
    )
);