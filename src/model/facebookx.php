<?php

namespace Sociallogin\model;


class facebookx {

    private $fb_api_key;
    private $fb_api_secret;
    private $permissions;
    private $version;


    public function __construct($key, $secret,$version = 'v3.0'){
        $this->fb_api_key = $key;
        $this->fb_api_secret = $secret;
        $this->version = $version;
        $this->permissions = ['email','public_profile'];

    }

    public function setPermissions($data)
    {
      if(!empty($data))
      {
        $this->permissions = $data;
      }

    }

    public function pasarela()
    {

         $fb = new \Facebook\Facebook([
            'app_id' => $this->fb_api_key,
            'app_secret' => $this->fb_api_secret,
            'default_graph_version' => $this->version,
          ]);

        $helper = $fb->getRedirectLoginHelper();

        return $helper->getLoginUrl($this->getUrlCallback(), $this->permissions);

    }

    public function callback()
    {
        global $MySession;
       $fb = new \Facebook\Facebook([
            'app_id' => $this->fb_api_key,
            'app_secret' => $this->fb_api_secret,
            'default_graph_version' => $this->version,
          ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken($this->getUrlCallback());
          } catch(\Facebook\Exceptions\FacebookResponseException $e) {

            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }

          if (isset($accessToken)) {

            $MySession->SetVar('facebook_access_token',(string) $accessToken);
            $fb->setDefaultAccessToken((string) $accessToken);
            try {
                $response = $fb->get('/me?fields=name,email,first_name,birthday,gender,picture.width(90).height(90)');
                $userNode = $response->getGraphUser();
              

                $me["id"]   = $userNode->getId();
                $me["name"] = $userNode->getName();
                $me["nickname"] = $userNode->getFirstName();
                $f = explode("/",$userNode->getBirthday());
                $me["birthday"] = $f[2]."-".$f[0]."-".$f[1];
                $me["gender"] = $userNode->getGender();
                $me["email"] = $userNode->getEmail();
                $me["avatar"] = $userNode->getPicture()->getUrl();
         
                $_SESSION['my_social_data']["provider"] = "facebook";
                $_SESSION['my_social_data']["facebook"] = $me;

                return "success";

            } catch(\Facebook\Exceptions\FacebookResponseException $e) {

              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(\Facebook\Exceptions\FacebookSDKException $e) {

              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }

        }
        else
        {

            return "error";
        }

    }

    public function tokenOffline()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://graph.facebook.com/oauth/access_token?client_id='. $this->fb_api_key.'&client_secret='.$this->fb_api_secret.'&grant_type=client_credentials'
        ));

        $resp = curl_exec($curl);

        curl_close($curl);
        $token = json_decode($resp,true);
        return  $token["access_token"];

    }

    private function getUrlCallback()
    {
        global $MyRequest;
        return "https://".$MyRequest->getSERVER()."/social-login/callback/facebook/";
    }

}
