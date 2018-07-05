<?php 
namespace MiProyecto{
    require_once ($strPath.'/aplication/utils/utiRequire_once_all.php');
    class fac_cron{
        public $_request;
        public $_utiSsn;

        function __construct(){
            spl_autoload_register(array($this, '__autoload'));
        }
		
        /***/
        function __autoload($className){
            $className = substr($className,strrpos($className,"\\")+1);
            if(substr($className,0,2)=='vo'){
                require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/model/cl_vo/'.$className.'.php');
            }else{
                if(substr($className,0,2)=='da'){
                    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/model/cl_dao/'.$className.'.php');
                }
            }
        }
		
        /*
         * action para buscar un producto por su nombre
         */
        public function publicar_foto_facebookPublic(){
            try{
                /**obtenemos el token de acceso**/
                $_SESSION['objCnx']=null;
                $obj_dao_parametros = new dao_parametros(1);
                require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/BD/clConexion.php');
                $obj_cx = new Conexion();
                $obj_cx->Conexion();
                $obj_cx->fnQuery("
                    SELECT
                        name
                    FROM
                        modaplan_galle94.gal_items
                    WHERE
                        parent_id = 1
                    ",
                    "OBJECT",
                    $arr_datos
                );
                
                /****/
                if($arr_datos!=null){
                    for($i=0;$i<3;$i++){
                        $num_r = rand (0, count($arr_datos) );
                        /****/
                        require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/facebook_publish.php');
                        $obj_face = new facebook_publish($obj_dao_parametros->_vo->get_access_token_facebook());
                        $obj_face->publish_message(
                                    "Espectacular este modelo!!! :) ;)",
                                    "Ven por el tuyo!",
                                    "Espectacular este modelo! :) ;)",
                                    array(
                                        realpath(dirname($_SERVER['DOCUMENT_ROOT']).'/var/albums/'.$arr_datos[$num_r]->name),
                                        "image/jpg"
                                    )
                                );
                    }
                }
                
                /**verificamos si esta expirado el token para renovarlo**/
                if(!$this->process_update_token($obj_dao_parametros->_vo->get_access_token_facebook())){
                    throw new \Exception("Verificando el expires del token");
                }
                
                utilidades::set_response(array("msj"=>"Proceso terminado correctamente"));
            }catch(\Exception $e){
                utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
            }
        }
        
        /***/
        public function process_update_token($token){
            try{
                require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/facebook_publish.php');
                $obj_face = new facebook_publish($token);
                
                $datetime_int = $obj_face->get_expires_token();
                
                $fecha_hoy = @date('Ymd');
                
                //echo $fecha_hoy.'--->';
                //echo $datetime_int;
                /**comparamos**/
                if($fecha_hoy >= $datetime_int){
                    /**actualizamos el token de acceso*/
                    $new_token = $obj_face->get_new_token_long($token);
                    $obj_dao_parametros = new dao_parametros();
                    $obj_dao_parametros->_vo->set_id(1);
                    $obj_dao_parametros->_vo->set_access_token_facebook($new_token);
                    $obj_dao_parametros->_vo->set_fecha_modificacion(utilidades::get_current_timestamp());
                    $obj_dao_parametros->update_rows();
                }
                
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }
    }
}