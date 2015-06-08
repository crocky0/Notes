<?php

interface ClientCredentialsInterface
{
    public function checkInputs(array $clientData);
    public function getClientId();
    public function getClienSecret();
}