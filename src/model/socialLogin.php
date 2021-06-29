<?php
namespace Sociallogin\model;

class socialLogin extends \Franky\Core\LOGIN
{
        
        public $m_social_data;

        function __construct($tabla = "",$user = "",$pass="",$extra = array())
        {
            $this->m_social_data = array();
            parent::__construct($tabla, $user, $pass, $extra);
        }
        
        function authSocial($id, $provider)
        {
                $consulta  = "SELECT ".$this->m_tabla.".*,redes_sociales.*  FROM redes_sociales inner join ".$this->m_tabla." "
                        . "ON redes_sociales.id_user = ".$this->m_tabla.".id WHERE red='$provider' and id_red='$id'";
               
                if (($result = $this->m_ibd->Query("Login", $consulta))!= IBD_SUCCESS)
                {
                        return $result;
                }

                if (($result = $this->m_ibd->NumeroRegistros("Login")) < 1 )
                {
                        $this->m_ibd->Liberar("Login");
                        return LOGIN_BADLOGIN; 
                }

                $registro = $this->m_ibd->Fetch("Login");

                if ( ! $registro )
                {
                        return LOGIN_DBFAILURE;
                }
                else
                {  
                  
                   
                    $this->m_social_data[$registro["red"]] = json_decode($registro["info"],true);
                  
                    if($this->setLogin($registro[$this->m_user], 1) != LOGIN_SUCCESS)
                    {
                        
                        return LOGIN_DBFAILURE;
                    }
    
                }

                $this->m_ibd->Liberar("Login");
                return LOGIN_SUCCESS;
        }
        
        public function getSocial($id)
        {
                $consulta  = "SELECT *  FROM redes_sociales WHERE id_user='$id'";
                //echo $consulta; exit;
                if (($result = $this->m_ibd->Query("Login", $consulta))!= IBD_SUCCESS)
                {
                        return $result;
                }

                if (($result = $this->m_ibd->NumeroRegistros("Login")) < 1 )
                {
                        $this->m_ibd->Liberar("Login");
                        return LOGIN_BADLOGIN; 
                }

                $registro = $this->m_ibd->Fetch("Login");

                if ( ! $registro )
                {
                        return LOGIN_DBFAILURE;
                }
                else
                {  
                    
                    $this->m_social_data[$registro["red"]] = json_decode($registro["info"],true);
                    
                  
                }

                $this->m_ibd->Liberar("Login");
                return LOGIN_SUCCESS;
        }
}
?>