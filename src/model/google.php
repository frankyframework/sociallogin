<?php

namespace Sociallogin\model;


class google {

    private $api_key;
    private $api_secret;


    public function __construct($key, $secret){
        $this->api_key = $key;
        $this->api_secret = $secret;
    }
   
    public function callback()
    {
        global $MySession;
        global $MyRequest;
        $id_token = $MyRequest->getRequest('id_token');
        $client = new Google_Client(['client_id' =>  $this->api_key ]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            $MySession->SetVar('google_access_token',(string) $id_token);
                $me["id"]   = $payload['sub'];
                $me["name"] = $payload['name']." ".$payload['family_name'];
                $me["birthday"] = "--";
                $me["gender"] = "";
                $me["email"] = $payload['e,ail'];
                $me["avatar"] = $payload['picture'];
         
                $_SESSION['my_social_data']["provider"] = "google";
                $_SESSION['my_social_data']["google"] = $me;

                return "success";
        }
        else
        {
            return "error";
        }
    }
}
