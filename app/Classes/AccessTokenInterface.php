<?php

interface AccessTokenInterface
{
    public function createAccessToken(UserCredentialsInterface $user);
    public function CheckReceivedToken($token);
}
