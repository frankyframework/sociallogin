<?php
use Sociallogin\model\facebookx;

$MyFacebook = new facebookx(getCoreConfig('sociallogin/facebook/api'),getCoreConfig('sociallogin/facebook/secret'));
$MyFacebook->setPermissions(getCoreConfig('sociallogin/facebook/permission'));

$provider = $MyRequest->getUrlParam("provider","facebook");

if($provider == "facebook")
{
    $result = $MyFacebook->callback();
}

?>
