<?php
namespace MiProyecto{
    class myErrorHandler{
        /**	funcion para capturar los errores del sistema*/
        static function myErrorHandler($errno, $errstr, $errfile, $errline){
            if (!(error_reporting() & $errno)) {        
                /* This error code is not included in error_reporting*/        
                return;    
            }
            switch ($errno) {		
                case E_USER_ERROR:			
                    echo "<b>My ERROR</b> [$errno] $errstr<br />\n";			
                    echo "  Fatal error on line $errline in file $errfile";			
                    echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";			
                    echo "Aborting...<br />\n";			
                    //exit(1);			
                    break;	 		
                case E_USER_WARNING:			
                    echo "<b>My WARNING</b> [$errno] $errstr<br />\n";			
                    //exit(1);			
                    break;	
                case E_USER_NOTICE:
                    echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
                    //exit(1);
                    break;
                default:	
                    echo "Unknown error type: [$errno][FILE: $errfile][LINE: $errline] $errstr<br />\n";		
                    //exit(1);		
                    break;    
            }
            /* insert log errores */	
            require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/utils/log_errores/log_errores.php");
            $obj_log_errores = new log_errores_sys();
            $error = debug_backtrace();
            $obj_log_errores->crear_errores_bd($error);
            
            /* Don't execute PHP internal error handler */    
            return true;
        }
    }
}
