<?php

class UserCredentials implements UserCredentialsInterface
{
    public $userData;
    public function __construct()
    {
        $this->userData = array('email' => '', 'id' => 0, 'key' => '');
    }
    public function getQuerystringIdentifier()
    {
        return 'password';
    }
    public function checkInputs(array $userData)
    {
        if (!$userData['email'] || !$userData['password'])
            return false;
        $results = DB::table('users')->where('email', '=', $userData['email'])->first();
        if(!$results)
            return false;
        if(!Hash::check($userData['password'], $results->password))
            return false;
        $this->userData['id'] = $results->id;
        $this->userData['email'] = $results->email;
        $this->userData['key'] =  $this->userData['id'].'_#_' .$this->userData['email'].'_#_'. microtime(true) . uniqid(mt_rand(), true);
        return true;
    }
    public function getUserId()
    {
        return $this->userData['id'];
    }
    public function getUserKey()
    {
        return $this->userData['key'];
    }
    public function getUserEmail()
    {
        return $this->userData['email'];
    }
    public function checkReceivedToken(array $receivedData)
    {
        if (!$receivedData['email'] || !$receivedData['id'])
            return array();
        $results = DB::table('users')->where('id', '=', $receivedData['id'])
            ->where('email', '=', $receivedData['email'])
            ->first();
        if(!$results)
            return array();
        return ['id' => $results->id, 'email' => $results->email];
    }
}
