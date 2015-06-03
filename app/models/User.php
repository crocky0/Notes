<?php

class User extends Eloquent
{
    protected $table = 'users';

    protected $hidden = array('password');

    protected $fillable = array('email', 'password');

    public static function getValidationRules()
    {
        $validation = array
        (
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        );
        return $validation;
    }
}


