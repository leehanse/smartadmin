<?php

return array(
    array(
        'route' => 'indexDashboard',
        'icon'  => 'fa fa-home',
        'title' => 'Dashboard',
    ),
    array(
        'title' => 'Users Management',
        'icon'  => 'fa fa-user',
        'sub-menus' => array(
            array(
                'route' => 'listUsers',
                'icon'  => 'clip-users-2',
                'title' => 'Users',
                'permission' => 'view-users-list'
            ),            
            array(
                'route' => 'listGroups',
                'icon'  => 'clip-users',
                'title' => 'Groups',
                'permission' => 'groups-management'
            )
        ),
        'references-routes' => array('newUser','showUser','listGroups','newGroup','showGroup')
    )
);

