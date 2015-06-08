<?php


class AuthorizeController extends BaseController
{
    public function __construct()
    {
    }
    public function Login()
    {
        $data = array();
        $data['grant_type'] = Input::get('grant_type') ? Input::get('grant_type') : '';
        $data['client_id'] = Input::get('client_id') ? Input::get('client_id') : '';
        $data['client_secret'] = Input::get('client_secret') ? Input::get('client_secret') : '';
        $data['email'] = Input::get('username') ? Input::get('username') : '';
        $data['password'] = Input::get('password') ? Input::get('password') : '';
        $userCredentials = new UserCredentials();
        if (!$userCredentials->checkInputs($data))
        {
            $json = array();
            $json['message'] = "Email and password are not correct. Please,  try again with correct user credentials.";
            $json['state'] = 401;
            return Response::json($json);
        }
        if($data['grant_type'] != $userCredentials->getQuerystringIdentifier())
        {
            $json = array();
            $json['message'] = "Wrong grant type. Must be password";
            $json['state'] = 400;
            return Response::json($json);
        }
        $clientCredentials = new ClientCredentials();
        if(!$clientCredentials->CheckInputs($data))
        {
            $json = array();
            $json['message'] = "Clien_id and client_secret are not correct. Please,  try again with correct client credentials.";
            $json['state'] = 401;
            return Response::json($json);
        }
        $accessToken = new AccessToken();
        $token = $accessToken->createAccessToken($userCredentials);
        $token['state'] = 200;
        $token['message'] = "You are Logged in now";
        return Response::json($token);
    }
}