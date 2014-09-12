<?php

return array(
    'user' => array(
        'create' => array(
                'email' => array('required', 'email'),
                'pass' => array('required', 'min:6', 'max:255'),
                'username' => array('required', 'min:3', 'max:255'),
                'last_name' => array('min:3', 'max:255'),
                'first_name' => array('min:3', 'max:255')
            ),
        'update' => array(
                'email' => array('required', 'email'),
                'pass' => array('min:6', 'max:255'),
                'username' => array('required', 'min:3', 'max:255'),
                'last_name' => array('min:3', 'max:255',),
                'first_name' => array('min:3', 'max:255')
            ),
        'login' => array(
                'username' => array('required'),
                'password' => array('required', 'min:6', 'max:255'),
            ),
    ),
    'group' => array(
        'groupname' => array('required', 'min:3', 'max:50'),
    ),
    'permission' => array(
        'name' => array('required', 'min:3', 'max:100'),
        'value' => array('required', 'alpha_dash', 'min:3', 'max:100'),
        'description' => array('required', 'min:3', 'max:255')
    ),
);