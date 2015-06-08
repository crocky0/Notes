<?php

class ClientCredentials implements ClientCredentialsInterface
{
    private $clientData;
    public function __construct()
    {
        $this->clientData = array('client_id' => '', 'client_secret' => '');
    }
    public function checkInputs(array $clientData)
    {
        if (!$clientData)
            return false;
        if (!isset($clientData['client_id']) || !isset($clientData['client_secret']))
            return false;
        $results = DB::table('clients')->where('client_id','=', $clientData['client_id'])
            ->where('client_secret','=', $clientData['client_secret'])
            ->first();
        if(!$results)
            return false;
        $this->clientData['client_id'] = $results->client_id;
        $this->clientData['client_secret'] = $results->client_secret;
        return true;
    }
    public function getClientId()
    {
        return $this->clientData['client_id'];
    }
    public function getClienSecret()
    {
        return $this->clientData['client_secret'];
    }
}
