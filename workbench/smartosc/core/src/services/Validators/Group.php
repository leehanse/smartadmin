<?php namespace Smartosc\Core\Services\Validators;

use Config;

class Group extends \Smartosc\Core\Services\Validators\Validator
{
    public function __construct($data = null, $level = null)
    {
        parent::__construct($data, $level);

        static::$rules = Config::get('core::validator.group');
    }
}