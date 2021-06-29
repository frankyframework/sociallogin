<?php
function _sociallogin($txt)
{
    return dgettext("sociallogin",$txt);
}


function get_facebook_cookie($app_id, $app_secret)
{
    $args = array();
    parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
    ksort($args);
    $payload = '';
    foreach ($args as $key => $value)
    {
        if ($key != 'sig')
        {
            $payload .= $key . '=' . $value;
        }
    }
    if (md5($payload . $app_secret) != $args['sig'])
    {
        return null;
    }
    return $args;
}

?>