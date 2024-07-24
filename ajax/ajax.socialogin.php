<?php
function addSocialData() {
    global $MySession;
    global $MyUserSocial;
    global $MyConfigure;
    global $MyRequest;
    $ObserverManager =  new Franky\Core\ObserverManager;
    if($MySession->LoggedIn())
    {
        $MySocialLogin = new \Sociallogin\model\socialLogin("users","email","contrasena",array("status" => "1"));
        if ($MyUserSocial->findSocial($_SESSION["my_social_data"][$_SESSION["my_social_data"]["provider"]]["id"], $_SESSION["my_social_data"]["provider"], $MySession->GetVar('id')) == REGISTRO_SUCCESS) {
            if ($MyUserSocial->updateSocial($MySession->GetVar('id'), $_SESSION["my_social_data"][$_SESSION["my_social_data"]["provider"]]["id"], $_SESSION["my_social_data"]["provider"], json_encode($_SESSION["my_social_data"][$_SESSION["my_social_data"]["provider"]])) == REGISTRO_SUCCESS) {
                $respuesta[0]["message"] = "success";
                $MySocialLogin->getSocial($MySession->GetVar('id'));
                $MySession->SetVar('social',     $MySocialLogin->m_social_data);

                $ObserverManager->dispatch('sociallogin_rel_update');

            } else {
                $respuesta[0]["message"] = "error";
            }
        } else {
            if ($MyUserSocial->findSocial($_SESSION["my_social_data"][$_SESSION["my_social_data"]["provider"]]["id"], $_SESSION["my_social_data"]["provider"]) == REGISTRO_SUCCESS) {
                $respuesta[0]["message"] = "duplicate";
            } else {
                if ($MyUserSocial->saveSocial($MySession->GetVar('id'), $_SESSION["my_social_data"][$_SESSION["my_social_data"]["provider"]]["id"], $_SESSION["my_social_data"]["provider"], json_encode($_SESSION["my_social_data"][$_SESSION["my_social_data"]["provider"]]),0) == REGISTRO_SUCCESS) {
                    $respuesta[0]["message"] = "success";


                    $MySocialLogin->getSocial($MySession->GetVar('id'));
                    $MySession->SetVar('social',     $MySocialLogin->m_social_data);

                    if(file_exists($MyConfigure->getServerUploadDir()."/avatar/".$_SESSION['my_social_data']["provider"]."/".$_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]['id'].".jpg")) {
                        unlink($MyConfigure->getServerUploadDir()."/avatar/".$_SESSION['my_social_data']["provider"]."/".$_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]['id'].".jpg");
                    }
                   
                    downloadAvatar($_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]['avatar'],$_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]['id'], $_SESSION['my_social_data']["provider"]);
                    
                    $AvataresModel = new \Base\model\AvataresModel();
                    $AvataresEntity = new \Base\entity\AvataresEntity();

                    $AvataresEntity->id_user($MySession->GetVar('id'));
                    $AvataresEntity->name($_SESSION['my_social_data']["provider"]);
                    $AvataresEntity->url($MyRequest->link($MyConfigure->getUploadDir()."/avatar/". $_SESSION['my_social_data']["provider"]."/".$_SESSION['my_social_data'][$_SESSION['my_social_data']["provider"]]['id'].".jpg",false,true));
                    $AvataresEntity->status(0);
                    $AvataresModel->save($AvataresEntity->getArrayCopy());

                } else {
                    $respuesta[0]["message"] = "error";
                }
            }
        }
    }
    else
    {
         $respuesta[0]["message"] = "login";
    }
    return $respuesta;
}

function addGoogleSocialData($str) {
    
    $google = new \Sociallogin\model\google( getCoreConfig('sociallogin/google/api'), getCoreConfig('sociallogin/google/secret'));
    
    $result = $google->callback($str);
   
    if ($result) {
        return addSocialData(); 
    } else {
        $respuesta[0]["message"] = "error";
        return $respuesta;
    }
}

function removeConnection($provider) {
    global $MySession;
    global $MyUserSocial;
    if($MySession->LoggedIn())
    {
        $MySocialLogin = new \Sociallogin\model\socialLogin("users",array("telefono","email"),"contrasena",array("status" => "1"));

        if ($MyUserSocial->removeSocial($MySession->GetVar('id'), $provider) == REGISTRO_SUCCESS) {
            $respuesta[0]["message"] = "success";

            $MySocialLogin->getSocial($MySession->GetVar('id'));
            $MySession->SetVar('social',     $MySocialLogin->m_social_data);

            $AvataresModel = new \Base\model\AvataresModel();
            $AvataresEntity = new \Base\entity\AvataresEntity();

            $AvataresEntity->id_user($MySession->GetVar('id'));
            $AvataresEntity->name($provider);


            $AvataresModel->getData($AvataresEntity->getArrayCopy());

            $registro = $AvataresModel->getRows();
            $AvataresEntity->id($registro["id"]);
            $AvataresModel->delete($AvataresEntity->getArrayCopy());


        } else {
            $respuesta[0]["message"] = "error";
        }

    }

    return $respuesta;
}


$MyAjax->register("addSocialData");
$MyAjax->register("removeConnection");
$MyAjax->register("addGoogleSocialData");