<?php
namespace MiProyecto{
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/utiRequire_once_all.php');
    class autoload{
        private $_modulo;
        private $_className;
        function __construct($modulo){
            $this->_modulo = $modulo;
            spl_autoload_register(array($this, '__autoload'));
        }
		
        /***/
        function __autoload($className){
            $this->_className = $className;
            $className = substr($className,strrpos($className,"\\")+1);
            if(substr($className,0,2)=='vo'){
                require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/model/cl_vo/'.$className.'.php');
            }else{
                if(substr($className,0,2)=='da'){
                    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/model/cl_dao/'.$className.'.php');
                }else{
                    if(substr($className,0,2)=='fa'){
                        /**se esta incluyendo un facade, por lo cual buscamos en todas las carpetas del facades**/
                        $path = $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade';
                        $dir_handle = @opendir($path) or die("No se pudo abrir $path");
                        while ($file = readdir($dir_handle)) {
                            if($file == "." || $file == ".." || $file == "index.php" ) continue;
                            
                            /**buscamos en los subdirectorios*/
                            $n_path = $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/'.$file;
                            $dir_handle_new = @opendir($n_path) or die("No se pudo abrir $n_path");
                            while ($file_n = readdir($dir_handle_new)) {
                                if($file_n == "." || $file_n == ".." || $file_n == "index.php" ) continue;
                                /**verificamos si es el archivo q nos interesa**/
                                if($file_n == $className.'.php'){
                                    /* incluimos el archivo */
                                    require_once ($path.'/'.$file.'/'.$file_n);
                                    return;
                                }
                            }
                        }
                        /**si llega a este punto es porq no encontro el facade**/
                        $arrPath = explode("\\",$this->_className);
                        if(count($arrPath)==3){
                            echo $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/'.$arrPath[1].'/'.$arrPath[2].'.php';
                            require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/'.$arrPath[1].'/'.$arrPath[2].'.php');
                        }
                        closedir($dir_handle);
                    }
                }
            }
        }
    }
}