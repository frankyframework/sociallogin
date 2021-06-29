<?php
namespace Sociallogin\model;

class UsersSocial  extends \Franky\Database\Mysql\objectOperations
{

           public function __construct()
    {
        parent::__construct();
        $this->from()->addTable('redes_sociales');
    }
 function updateSocial($id,$id_red,$red, $info)
    {

            $nvoregistro = array(
                'info'  => $info
             );
             $this->where()->addAnd('id_user',$id,'=');
               $this->where()->addAnd('id_red',$id_red,'=');
                 $this->where()->addAnd('red',$red,'=');
            return $this->editarRegistro($nvoregistro);

    }

    function saveSocial($id,$id_red,$red, $info,$registro)
    {

            $nvoregistro = array(
                "id_user" => $id,
                'info'  => $info,
                "id_red" => $id_red,
                "red" => $red,
                "registro" => $registro
             );

            return $this->guardarRegistro($nvoregistro);

    }

    function removeSocial($id,$red= "")
    {
      $this->where()->addAnd('id_user',$id,'=');

             if(!empty($red))
             {
                 $this->where()->addAnd('red',$red,'=');
             }

            return $this->eliminarRegistro();
    }

    function findSocial($id_red,$red,$id="")
    {
            $campos = array("id","info");

            if(!empty($id_red))
            {
              $this->where()->addAnd('id_red',$id_red,'=');
            }
            if(!empty($red))
            {
              $this->where()->addAnd('red',$red,'=');
            }
            if(!empty($id))
            {
              $this->where()->addAnd('id_user',$id,'=');
            }

            return $this->getColeccion($campos);

    }

}
