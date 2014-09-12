<?php 

return array(
    'dashboard' => array(          
    ),
    'login' => array(
        array(
            'title' => trans('core::breadcrumbs.login'),
            'link' => URL::route('getLogin'),
            'icon' => 'glyphicon-user'
        )
    ),
    'users' => array(
        array(
            'title' => trans('core::breadcrumbs.users'),
            'link' => URL::route('listUsers'),
            'icon' => 'clip-user'
        )
    ),
    'show_user' => array(
        array(
            'title' => trans('core::breadcrumbs.users'),
            'link' => URL::route('listUsers'),
            'icon' => 'clip-user'
        ),
        array(
            'title' => trans('core::breadcrumbs.edit-user'),
            'link' => URL::route('newUser'),
            'icon' => 'clip-user'
        )        
    ),
    'create_user' => array(
        array(
            'title' => trans('core::breadcrumbs.users'),
            'link' => URL::route('listUsers'),
            'icon' => 'clip-user'
        ), 
        array(
            'title' => trans('core::breadcrumbs.new-user'),
            'link' => URL::current(),
            'icon' => ''
        )
    ),
    'groups' => array(
        array(
            'title' => trans('core::breadcrumbs.groups'),
            'link' => URL::route('listGroups'),
            'icon' => 'clip-users'
        )
    ),
    'edit_group' => array(
        array(
            'title' => trans('core::breadcrumbs.groups'),
            'link' => URL::route('listGroups'),
            'icon' => 'clip-users'
        ),
        array(
            'title' => 'Edit group',
            'link' => URL::current(),
            'icon' => 'fa fa-edit'
        )
    ),
    'create_group' => array(
        array(
            'title' => trans('core::breadcrumbs.groups'),
            'link' => URL::route('listGroups'),
            'icon' => 'clip-users'
        ),
        array(
            'title' => trans('core::breadcrumbs.new-group'),
            'link' => URL::current(),
            'icon' => 'clip-add'
        )
    )    
);