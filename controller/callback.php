<?php
use Sociallogin\model\facebookx;
use Sociallogin\model\google;




$provider = $MyRequest->getUrlParam("provider","facebook");

if($provider == "facebook")
{
    $MyFacebook = new facebookx(getCoreConfig('sociallogin/facebook/api'),getCoreConfig('sociallogin/facebook/secret'));
    $MyFacebook->setPermissions(getCoreConfig('sociallogin/facebook/permission'));
    $result = $MyFacebook->callback();
}
if($provider == "google")
{
    $MyGoogle = new google(getCoreConfig('sociallogin/google/api'),getCoreConfig('sociallogin/google/secret'));
    $result = $MyGoogle->callback();
}

?>
