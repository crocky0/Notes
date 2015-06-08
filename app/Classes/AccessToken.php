<?php

class AccessToken implements AccessTokenInterface
{
    private $SECRET_KEY;
    public function __construct()
    {
        //Some random key
        $this->SECRET_KEY = "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3";
    }
    public function createAccessToken(UserCredentialsInterface $user)
    {
        $token = array(
            "access_token" => $this->generateAccessToken($user),
            "expires_in" => 10000000,
            "token_type" => 'Bearer',
            "refresh_token" => ''
        );
        return $token;
    }
    protected function generateAccessToken(UserCredentialsInterface $user)
    {
        $key_str = $this->GetKey();
        $key = pack('H*', $key_str);
        $key_size =  strlen($key);
        $plaintext = $user->getUserKey();
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
        $ciphertext = $iv . $ciphertext;
        return $ciphertext_base64 = base64_encode($ciphertext);
    }
    public function checkReceivedToken($token)
    {
        try
        {
            $key_str = $this->GetKey();
            $key = pack('H*', $key_str);
            $key_size = strlen($key);
            $ciphertext_dec = base64_decode($token);
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $iv_dec = substr($ciphertext_dec, 0, $iv_size);
            $ciphertext_dec = substr($ciphertext_dec, $iv_size);
            $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
            $pieces = explode("_#_", $plaintext_dec);
            if (sizeof($pieces) < 2)
                return array('id' => 0, 'email' => '');
            $data = ['id' => $pieces[0], 'email' => $pieces[1]];
            $user = new UserCredentials();
            return $user->checkReceivedToken($data);
        }
        catch(Exception $e)
        {
            return array('id' => 0, 'email' => '');
        }
    }
    protected function GetKey()
    {
        //for better security, we can create mutable key, which will be changed one time in the day.
        $mutable_key = intval(time () / 86400);
        $n = 1 + $mutable_key % 7;
        $mutable_key_str = '';
        for($i = 0; $i < $n; ++$i)
            $mutable_key_str .= strval($mutable_key);
        $mutable_key_str .= $this->SECRET_KEY;
        $mutable_key_str = substr($mutable_key_str, 0, 64);
        return $mutable_key_str;
    }
}
