<?php namespace Smartcms\Core\Services\Validators;

use Config;

class Permission extends \Smartcms\Core\Services\Validators\Validator
{
    public function __construct($data = null, $level = null)
    {
        parent::__construct($data, $level);

        static::$rules = Config::get('core::validator.permission');
    }
}