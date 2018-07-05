<?php
namespace MiProyecto{
    /**
     * Description of log_errores
     *
     * @author user
     */

    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/model/cl_dao/dao_log_errores_principal.php');
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/model/cl_dao/dao_log_errores_track.php');
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/model/cl_dao/dao_usuarios.php');
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/mail/mail_plantillas.php');
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/mail/mail_envios.php');

    class log_errores_sys {
        private $_bean;
        private $_mail_destino = 'johnmax11@hotmail.com';

        public function __construct(){}

        /***/
        public function crear_errores_bd($errores){
            if($errores == '' || $errores==null || !is_array($errores)){
                return false;
            }
            
            try{
                $dao_log_errores = new dao_log_errores_principal();
                
                /**se hace rollback automaticamente para garantizar la consistencia de datos*/
                $dao_log_errores->rollback();
                
                /**nueva transaccion por insert de errores**/
                //$this->_bean->_origen = true;
                $dao_log_errores->begin();
                /**
                 * creamos el registro principal del error
                 */
                if(!$this->crear_log_errores_principal_bd()){
                    return false;
                }
                $idlogprincipal = $dao_log_errores->get_last_insert_id();
                foreach($errores as $key => $value){
                    $dao_log_errores_track = new dao_log_errores_track();
                    $dao_log_errores_track->new_vo();

                    $dao_log_errores_track->_vo->set_log_errores_principal_id($idlogprincipal);
                    $dao_log_errores_track->_vo->set_file(isset($value['file'])?$value['file']:null);
                    $dao_log_errores_track->_vo->set_line(isset($value['line'])?$value['line']:null);
                    $dao_log_errores_track->_vo->set_function($value['function']);
                    $dao_log_errores_track->_vo->set_class(isset($value['class'])?$value['class']:null);
                    $dao_log_errores_track->_vo->set_object(isset($value['object'])?('============'.chr(13).chr(10).$this->get_datos_object_error_bd((array)$value['object'])):null);
                    $dao_log_errores_track->_vo->set_type(isset($value['type'])?$value['type']:null);
                    $dao_log_errores_track->_vo->set_args(isset($value['args'])?'============'.chr(13).chr(10).$this->get_datos_args_error_bd($value['args']):null);
                    // execute //
                    $dao_log_errores_track->insert_rows($dao_log_errores_track->_vo);
                }
                $dao_log_errores->commit();
                // enviamos email al usuario destino //
                //$this->enviar_email_usuario_destino($idlogprincipal);
                /**cerramos el script matando el proceso**/
                exit;
            }catch(\Exception $e){
                $dao_log_errores->rollback();
                echo $e->getMessage().'--->'.debug_print_backtrace();
                return false;
            }
        }
        /**
         * crear registro principal del error
         */
        private function crear_log_errores_principal_bd(){
            try{
                $obj_dao_log_errores_principal = new dao_log_errores_principal();
                // execute //
                $bolresult = $obj_dao_log_errores_principal->insert_rows();
                return true;
            }catch(\Exception $e){
                return false;
            }
        }

        /**
         * @return string divide los parametros del objeto y los une retornando un string
         */
        private function get_datos_object_error_bd($object){
                $str_datos_objeto = '';
                foreach($object as $key => $value) {
                        $str_datos_objeto .= utf8_encode($key).'=>'.(serialize($value)).';|;';
                }
                return substr($str_datos_objeto,0,strlen($str_datos_objeto)-3);
        }

        /**
         * @return string divide los parametros del objeto args, son parametros de la funcion donde se ejecuto
         */
        private function get_datos_args_error_bd($object){
                $str_datos_objeto = '';
                foreach($object as $key => $value) {
                        $str_datos_objeto .= $key.'=>'.(serialize($value)).';|;';
                }
                return substr($str_datos_objeto,0,strlen($str_datos_objeto)-3);
        }

        /**
         * @return boolean metodod que recibe como parametro el idlogprincipal y con este consulta
         * los datos del error para proceder a armar un html para enviar por correo electronico
         */
        private function enviar_email_usuario_destino($idlogprincipal){
                $dao_log_errores_track = new dao_log_errores_track();
                $dao_log_errores_track->new_vo();
                $dao_log_errores_track->_vo->set_log_errores_principal_id($idlogprincipal);
                 // generamos los datos de la plantilla //
                 $arrRespuesta = $dao_log_errores_track->select_rows()->fetch_object_vo();
                 // constuimos el html con los datos mas relevantes //
                 $strhtml = "<table width=100%>";
                 $strhtml .= "<tr>";
                 $strhtml .= "  <th>date_entered</th>";
                 $strhtml .= "  <th>created_by</th>";
                 $strhtml .= "  <th>file</th>";
                 $strhtml .= "  <th>line</th>";
                 $strhtml .= "  <th>function</th>";
                 $strhtml .= "  <th>class</th>";
                 $strhtml .= "  <th>object</th>";
                 $strhtml .= "  <th>params_object</th>";
                 $strhtml .= "  <th>type</th>";
                 $strhtml .= "  <th>args</th>";
                 $strhtml .= "</tr>";

                 if(isset($arrRespuesta) && count($arrRespuesta)>0){
                        for($i=0;$i<count($arrRespuesta);$i++){
                                $dao_log_errores_track->new_vo();
                                $dao_log_errores_track->_vo = $arrRespuesta[$i];
                                // consultamos el nombrte de usuario //
                                $dao_usuarios = new dao_usuarios();
                                $dao_usuarios->new_vo();
                                $dao_usuarios->_vo->set_id($dao_log_errores_track->_vo->get_creado_por());
                                // execute //
                                $arrDUs = $dao_usuarios->select_rows()->fetch_object_vo();
                                $dao_usuarios->_vo = $arrDUs[0];

                                $strhtml .= "<tr>";
                                $strhtml .= "   <td>".$dao_log_errores_track->_vo->get_fecha_creacion()."</td>";
                                $strhtml .= "   <td>".($dao_usuarios->_vo!=null?$dao_usuarios->_vo->get_usuario():'Users')."</td>";
                                $strhtml .= "   <td>".$dao_log_errores_track->_vo->get_file()."</td>";
                                $strhtml .= "   <td>".$dao_log_errores_track->_vo->get_line()."</td>";
                                $strhtml .= "   <td>".$dao_log_errores_track->_vo->get_function()."</td>";
                                $strhtml .= "   <td>".$dao_log_errores_track->_vo->get_class()."</td>";
                                $strhtml .= "   <td>".$dao_log_errores_track->_vo->get_object()."</td>";
                                $strhtml .= "   <td>".$dao_log_errores_track->_vo->get_params_object()."</td>";
                                $strhtml .= "   <td>".$dao_log_errores_track->_vo->get_type()."</td>";
                                $strhtml .= "   <td>".$dao_log_errores_track->_vo->get_args()."</td>";
                                $strhtml .= "</tr>";
                        }
                 }
                $strhtml .= "</table>";
                 // datos de email //
                $mail_envios = new mail_envios();
                $mail_envios->inicialice_mail($strhtml);
                $mail_envios->_strEmail = $this->_mail_destino;
                $mail_envios->set_asunto("Error - Vamels");
                $mail_envios->envio_mail();
        }
    }
}
