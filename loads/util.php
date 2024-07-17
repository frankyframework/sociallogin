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


function downloadAvatar($url,$id)
{
    global $MyConfigure;
    $ch = curl_init($url); 
    $dir = $MyConfigure->getServerUploadDir()."/avatar/facebook/";
    if (!file_exists($dir))
    {
         mkdir($dir, 0777);
    }
    $save_file_loc = $dir . $id.".jpg"; 
  
    $fp = fopen($save_file_loc, 'wb'); 
  
    curl_setopt($ch, CURLOPT_FILE, $fp); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_exec($ch); 
  
    curl_close($ch); 

    fclose($fp); 
}
?>