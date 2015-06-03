<?php

class Note extends Eloquent
{
	protected $table = 'notes';

    protected $fillable = array('title', 'note', 'userid');

    public static function getValidationRules()
    {
        $validation = array
        (
            'title' => 'required|max:50',
            'note' => 'max:1000',
        );
        return $validation;
    }
}
