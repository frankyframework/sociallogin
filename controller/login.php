<?php
use Base\entity\users as entityUser;
use Base\model\AvataresModel;
use Base\entity\AvataresEntity;
use Franky\Core\ObserverManager;
$MyUser             = new \Base\model\USERS();
$ObserverManager = new ObserverManager;
$MySocialLogin = new \Sociallogin\model\socialLogin("users","usuario",1,array("status" => "1"));


$usuario	= $MyRequest->getRequest('usuario');
$contrasena	= $MyRequest->getRequest('contrasena');
$_callback	= $MyRequest->getRequest('callback');
$callback = 1;

$error = false;


if($MySocialLogin->authSocial($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["id"],$_SESSION['my_social_data']["provider"]) == LOGIN_SUCCESS)
{

    $MyUserEntity    = new entityUser();
    $MyUserEntity->setId($MySocialLogin->id);
    $MyUserEntity->setUltimoAcceso( date('Y-m-d'));

    $MyUser->save($MyUserEntity->getArrayCopy());
    $inputs = $MySocialLogin->getInputs();


    foreach($inputs as $k)
    {

        $MySession->SetVar($k,   	$MySocialLogin->{$k});
    }


    $MySession->SetVar('is_login',    true);
    $MySession->SetVar('social',    $MySocialLogin->m_social_data);

    $ObserverManager->dispatch('sociallogin_user');
    $ObserverManager->dispatch('sociallogin_user_'.$MySocialLogin->nivel,[$MySocialLogin->id]);
    $ObserverManager->dispatch('login_user');
    $ObserverManager->dispatch('login_user_'.$MySocialLogin->nivel,[$MySocialLogin->id]);

    if(!empty($_callback))
    {
        $location = $_callback;
    }
    else
    {
        $location = $MyRequest->url(ADMIN);
    }


    

    $MyUserSocial->updateSocial($MySocialLogin->id,
            $_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["id"],
            $_SESSION['my_social_data']["provider"],
            addslashes(json_encode($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]])));

}
else
{


   $f =  explode ("-",$_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["birthday"]);//aaaa-mm-dd


    if($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["email"] != "" && $MyUser->findEmail($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["email"]) == REGISTRO_SUCCESS)
    {

         $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("email_duplicate",$_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["email"]));
        $location = $MyRequest->getReferer();
    }
    else
    {
        $email = $_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["email"];
        $nickname =  $_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["nickname"];

        if(empty($email))
        {
            if(empty($email))
            {
                $email = $nickname."@facebook.com";
            }
        }


        $contrasena = substr(md5(uniqid()),0,10);

        if(isset($_SESSION['my_social_data']["provider"]))
        {

            $MyUserEntity    = new entityUser();

            $nickname = $_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["nickname"];
            $i =1;
            while($MyUser->findUser($nickname) == REGISTRO_SUCCESS)
            {
                $nickname = $nickname.$i++;
            }
            if($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["birthday"] != '--')
            {
                $MyUserEntity->setFecha_nacimiento($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["birthday"]);
            }

            $MyUserEntity->setUsuario($nickname);
            $MyUserEntity->setEmail($email);
            $MyUserEntity->setNombre($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["name"]);
            $MyUserEntity->setRole(getCoreConfig("base/user/default-role"));
            $MyUserEntity->setSexo($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["gender"]);
            
            $MyUserEntity->setStatus(1);
            $MyUserEntity->setFecha(date('Y-m-d H:i:s'));
            $MyUserEntity->setVerificado(1);
            $contrasena = substr(md5(time()),0,8);
            $MyUserEntity->setContrasena(password_hash($contrasena,PASSWORD_DEFAULT));
            $result = $MyUser->save($MyUserEntity->getArrayCopy());


            if($result == REGISTRO_SUCCESS)
            {
                 $ult_id = $MyUser->getUltimoID();
                 $MyUserSocial->saveSocial($ult_id, $_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["id"],
                        $_SESSION['my_social_data']["provider"], addslashes(json_encode($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]])),1);



                $MySocialLogin->authSocial($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["id"],$_SESSION['my_social_data']["provider"]);

                $MyUserEntity    = new entityUser();
                $MyUserEntity->setId($MySocialLogin->id);
                $MyUserEntity->setUltimoAcceso( date('Y-m-d'));
                $MyUser->save($MyUserEntity->getArrayCopy());
                $inputs = $MySocialLogin->getInputs();
                foreach($inputs as $k)
                {
                    $MySession->SetVar($k,   	$MySocialLogin->{$k});
                }
                $MySession->SetVar('is_login',    true);
                $MySession->SetVar('social',     $MySocialLogin->m_social_data);


               
                $AvataresEntity = new AvataresEntity;
                $AvataresModel = new AvataresModel();
                $AvataresEntity->id_user($ult_id);
                $AvataresEntity->name($_SESSION['my_social_data']["provider"]);
                $AvataresEntity->url($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]["avatar"]);

                $AvataresModel->save($AvataresEntity->getArrayCopy());


                $AvataresEntity->name('gravatar');
                $AvataresEntity->url("https://www.gravatar.com/avatar/" . md5( strtolower( trim( $MyUserEntity->getEmail() ) ) ));
                $AvataresEntity->status(0);
                $AvataresModel->save($AvataresEntity->getArrayCopy());

                $ObserverManager->dispatch('register_new_user',[$MySocialLogin->id]);
                $ObserverManager->dispatch('socialregister_new_user',[$MySocialLogin->id]);

                if(!empty($_callback))
                {
                    $location =$_callback;
                }
                else if(!empty($_SESSION["url_location"]))
                {
                    $location = $_SESSION["url_location"];
                }
                else
                {
                    $location =$MyRequest->url(ADMIN);
                }
            }
            else {
                  $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("error_conexion"));
                  $location = $MyRequest->getReferer();
             }
        }
        else {
              $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("error_conexion"));
              $location = $MyRequest->getReferer();
         }

    }
}


$MyRequest->redirect($location);

?>
