<?php
namespace App;

class Googl
{
    public function client()
    {
        $client = new \Google_Client();
        $client->setAuthConfig('C:\wamp64\www\rhx\client_secret.json');
        // $client->setClientId(env('GOOGLE_CLIENT_ID'));
        // $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT'));
        $client->setScopes(array(               
            'https://www.googleapis.com/auth/drive.file',
            'https://www.googleapis.com/auth/drive'
        ));
        $client->setAccessType("offline");
        $client->setApprovalPrompt("force");

        return $client;
    }


    public function drive($client)
    {
        //Calling Google APIs
        $drive = new \Google_Service_Drive($client);
        return $drive;
    }
}