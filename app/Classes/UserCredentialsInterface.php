<?php

interface UserCredentialsInterface
{
    public function checkInputs(array $userData);
    public function getUserId();
    public function getUserEmail();
    public function getUserKey();
    public function checkReceivedToken(array $token);
}