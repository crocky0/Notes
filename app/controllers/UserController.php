<?php

class UserController extends \BaseController
{
	public function store()
	{
        $json = array();
        if (!Request::isJson())
        {
            $json['message'] = "Request is not JSON";
            $json['state'] = 400;
            return Response::json($json);
        }
        $validation = Validator::make(Input::all(), User::getValidationRules());
        if ($validation->fails())
        {
            //$json['error Messages'] = $validator->messages()->toArray();
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
