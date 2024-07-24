<?php

namespace Sociallogin\model;


class google {

    private $api_key;
    private $api_secret;


    public function __construct($key, $secret){
        $this->api_key = $key;
        $this->api_secret = $secret;
    }
   
    public function callback($id_token)
    {
        global $MySession;

        $client = new \Google_Client(['client_id' =>  $this->api_key.".apps.googleusercontent.com" ]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            $MySession->SetVar('google_access_token',"");

               
                $me["id"]   = $payload['sub'];
                $me["name"] = $payload['name']." ".$payload['family_name'];
                $me["birthday"] = "--";
                $me["gender"] = "";
                $me["email"] = $payload['email'];
                $me["avatar"] = $payload['picture'];
         
                $_SESSION['my_social_data']["provider"] = "google";
                $_SESSION['my_social_data']["google"] = $me;
                return true;
        }
        else
        {
            return false;
        }
    }
}
