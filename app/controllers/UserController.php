<?php

class UserController extends \BaseController
{
    public function store()
    {
        $json = array();
        $validation = Validator::make(Input::all(), User::getValidationRules());
        if ($validation->fails())
        {
            $json['Messages'] = $validation->messages()->toArray();
            $json['state'] = 400;
        }
        else
        {
            $user = new User();
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->save();
            $json['state'] = 200;
            $json['message'] = "user is registered";
        }
        return Response::json($json);
    }
}
