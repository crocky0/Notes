<?php

interface AccessTokenInterface
{
    public function createAccessToken(UserCredentialsInterface $user);
    public function checkReceivedToken($token);
}
