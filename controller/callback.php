<?php
use Sociallogin\model\facebookx;

$provider = $MyRequest->getUrlParam("provider","facebook");

if($provider == "facebook")
{
    $MyFacebook = new facebookx(getCoreConfig('sociallogin/facebook/api'),getCoreConfig('sociallogin/facebook/secret'));
    $MyFacebook->setPermissions(getCoreConfig('sociallogin/facebook/permission'));
    $result = $MyFacebook->callback();
}

?>
