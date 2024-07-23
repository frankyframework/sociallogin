<?php
include 'constantes.php';
include 'util.php';
__bindtextdomain("sociallogin","sociallogin");

if (function_exists('bind_textdomain_codeset')) 
{
    bind_textdomain_codeset("sociallogin", 'UTF-8');
}



$MyMetatag->setJs("/modulos/sociallogin/web/js/ajax.sociallogin.js");

if($MySession->LoggedIn() && $MySession->GetVar( 'social' ) == false)
{
    
    $MySocialLogin = new \Sociallogin\model\socialLogin("users",array("telefono","email"),"contrasena",array("status" => "1"));
    $MySocialLogin->getSocial($MySession->GetVar('id'));
    
    $MySession->SetVar('social',     $MySocialLogin->m_social_data);
}

$MyUserSocial = new \Sociallogin\model\UsersSocial();

$socialProviders = getCoreConfig('sociallogin/config/redes');

if(in_array("google", $socialProviders)) {

    $MyMetatag->setCode("<script src=\"https://apis.google.com/js/platform.js?onload=loadGoogleLogin\" async defer></script>");
    $MyMetatag->setCode("<meta name=\"google-signin-client_id\" content=\"".getCoreConfig('sociallogin/google/api').".apps.googleusercontent.com\">");
}

$MyMetatag->setCss("/modulos/sociallogin/web/css/sociallogin.css");
?>