<?php
namespace MiProyecto{
    /**
     * Description of ExceptionHandler
     *
     * @author vanessa-port
     */
    class ExceptionHandler {
        private $_msj_error;
        function __construct($message){
            $this->_msj_error = $message;
            /**end process**/
            $this->end_process();
        }
        
        private function end_process(){
            try{
                utilidades::set_response(array('msj'=>$this->_msj_error),true);
            } catch (Exception $ex) {
                return false;
            }
        }
    }
}