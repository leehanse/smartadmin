<?php 

return array(
    // layouts
    'master' => 'core::layouts.dashboard.c1-master',
    'header' => 'core::layouts.dashboard.c1-header',
    'navbar' => 'core::layouts.dashboard.c1-navbar',
    'footer' => 'core::layouts.dashboard.c1-footer',
    'login-layout' => 'core::layouts.dashboard.c1-login',
    'breadcumb'    => 'core::layouts.dashboard.c1-breadcumb',
    
    // dashboard
    'dashboard-index' => 'core::dashboard.index',
    'login' => 'core::dashboard.c1-login',
    'error' => 'core::dashboard.error',

    // users
    'users-index' => 'core::user.c1-index-user',
    'users-list' => 'core::user.c1-list-users',
    'user-create' => 'core::user.c1-new-user',
    'user-informations' => 'core::user.c1-user-informations',
    'user-profile' => 'core::user.c1-show-user',
    'user-activation' => 'core::user.c1-activation',

    // groups
    'groups-index' => 'core::group.c1-index-group',
    'groups-list' => 'core::group.c1-list-groups',
    'group-create' => 'core::group.c1-new-group',
    'users-in-group' => 'core::group.c1-list-users-group',
    'group-edit' => 'core::group.c1-show-group',

    // permissions
    'permissions-index' => 'core::permission.index-permission',
    'permissions-list' => 'core::permission.list-permissions',
    'permission-create' => 'core::permission.new-permission',
    'permission-edit' => 'core::permission.show-permission',
);