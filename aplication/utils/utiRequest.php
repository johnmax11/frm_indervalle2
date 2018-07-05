<?php
/**se encarga de cargar los datos que vienen desde el request***/
namespace MiProyecto{
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/inputfilter-2005-05-09/class.inputfilter.php5');
    class request{
        static $_request;
        private $_files;
        public function __construct() {}
        
        /** get variable request***/
        static function get_parameter($n_variable){
            try{
                if(!isset($_REQUEST[$n_variable])){
                    return null;
                }
                /*se instancia la clase*/
                $obFilter = new InputFilter();
                /*Variable Global $_POST libre de XSS e Inyecciones SQL*/
                $var_par = $obFilter->process($_REQUEST[$n_variable]);
                
                return $var_par;
            }catch(\Exception $e){
                return false;
            }
        }
        
        /**set dato en array request**/
        static function set_request($n_var,$n_value){
            $_REQUEST[$n_var] = $n_value;
        }
        
        
        /**get variable files**/
        static function get_files($n_files,$index=null){
            try{
                if($index==null && !isset($_FILES[$n_files])){
                    return null;
                }else{
                    if($index!=null && !isset($_FILES[$n_files][$index])){
                        return null;
                    }
                }
                /*se instancia la clase*/
                $obFilter = new InputFilter();
                /*Variable Global $_POST libre de XSS e Inyecciones SQL*/
                if($index==null && isset($_FILES[$n_files])){
                    return $obFilter->process($_FILES[$n_files]);
                }else{
                    if($index!=null && isset($_FILES[$n_files][$index])){
                        return $obFilter->process($_FILES[$n_files][$index]);
                    }
                }
            }catch(\Exception $e){
                return false;
            }
        }
    }
}