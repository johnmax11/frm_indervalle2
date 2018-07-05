<?php
namespace MiProyecto{
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/phpmailer_v5.1/class.phpmailer.php');
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/utils/phpmailer_v5.1/class.smtp.php");
    /**
     * Description of mail_envios
     *
     * @author vanessa
     */
    class mail_envios {
        //put your code here
        private $_mail;
        public $_strEmail;
        private $_archivo;

        public function inicialice_mail($strPlantilla){
            $this->_mail = new \PHPMailer();

           // $mail = new PHPMailer();
            //$this->_mail->IsSMTP();
            $this->_mail->Host = "localhost";
            $this->_mail->Port = 25;				
            $this->_mail->From = 'info@vamels.com';        
            $this->_mail->FromName = 'Vamels.com';


            $this->_mail->SMTPDebug = 1;
            //$mail->AddAddress($objDatos->correoelectronico);
            $this->_mail->IsHTML(true);
            $this->_mail->Body = $strPlantilla;
            $this->_mail->SMTPAuth = false;

        }
        private function set_dir_correo($correoelectronico){
                $this->_mail->AddAddress($correoelectronico);
        }
        public function set_asunto($asunto){
                $this->_mail->Subject = $asunto;
        }
        public function envio_mail(){
                // separamos los correos //
                if(!$this->separar_email()){
                        return false;
                }

                // verificamos si hay archivos adjuntos //
                if ($this->getArchivo() != ''){
                        $this->_mail->AddAttachment($this->getArchivo());
                }

                $exito = $this->_mail->Send();
                $intentos=1;
                while ((!$exito) && ($intentos < 5)) {
                                //sleep(5);
                                echo $this->_mail->ErrorInfo;
                                $exito = $this->_mail->Send();
                                $intentos=$intentos+1;
                }
                $this->_mail->ClearAddresses();
                return true;
        }
        private function separar_email(){
                $arrDatos = explode(',',$this->_strEmail);

                if(count($arrDatos)==0){
                        return false;
                }

                for($i=0;$i<count($arrDatos);$i++){
                        $this->set_dir_correo($arrDatos[$i]);
                }
                return true;
        }

        public function setArchivo($archivo)
        {
                $this->_archivo = $archivo;
        }

        public function getArchivo()
        {
                return $this->_archivo;
        }
    }
}
