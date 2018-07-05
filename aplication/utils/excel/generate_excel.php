<?php
namespace MiProyecto{
    require_once('excelwriter.inc.php');
    class generate_excel{
        public $_nombreArchivo;
        public $_url_archivo;
        public $_arrDatos;
        public $_bolGetTitle = true;
        public $_bolGetTitlePrint = false;
        private $objExcel = null;
        public $_bolTotalizar = false;
        public $_arrTotales = array();
        private $_arrVrTotal = array();
        public $_arrColExcepciones = array();
        public $_titulo = null;
        public $_arr_cols_colspan = array();
        private $_numCols = null;
        public $_comodin = '|';
        public $_border = false;
        private $_controws_tmp = 0;
        private $_numfrac = 0;
        
        
        function __contruct(){
            
        }
        public function generar(){
            try{
                $this->objExcel = new ExcelWriter($this->_url_archivo.$this->_nombreArchivo);
                
                if($this->objExcel==false) { 
                    echo $this->objExcel->error;
                    return false;
                }
                
                // verificamos si vienen datos //
                if(!isset($this->_arrDatos['rows'])){
                    $this->objExcel->writeRow();
                    $this->objExcel->writeCol('NO HAY DATOS PARA MOSTRAR EN ESTE ARCHIVO');
                    return false;
                }
                if($this->_border == true){
                    $this->objExcel->_border = true;
                }
                // set num cols //
                $this->set_num_cols();
                
                // verificamos titulo del document //
                if($this->_titulo=null){
                    $this->set_title_document();
                }
                
                // verificamos colspan //
                if(count($this->_arr_cols_colspan)>0){
                    $this->generar_col_colspan();
                }
                /*
                 * verificamos si se deben pintar los titulos
                */
                if($this->_bolGetTitle && $this->_bolGetTitlePrint == false){
                    $this->generar_col_head();
                }
                /**
                 * recorremos la matriz para ir generandos las difernetes columnas del archivo
                 */
                $this->_controws_tmp = 0;
                $mod_f = 33;
                if(count($this->_arr_cols_colspan)==0){
                    $mod_f = 34;
                }
                ////////////////////////////////////////////////////////////////
                // paint las filas //
                $this->_numfrac = 0;
                foreach($this->_arrDatos['rows'] as $key => $valor){
                    // aumentamos la fila //
                    $this->_controws_tmp ++;
                    ////////////////////////////////////////////////////////
                    // verificamos para poner los titulos de nuevo //
                    if($this->_controws_tmp > 0 && ($this->_controws_tmp % $mod_f) == 0){
                        if($this->_bolGetTitle){
                            if(count($this->_arr_cols_colspan)>0){
                                $this->generar_col_colspan();
                            }
                            $this->generar_col_head();
                            $this->_controws_tmp = 1;
                        }
                    }
                    $this->objExcel->writeRow();
                    ////////////////////////
                    foreach ($valor as $keyint => $valorint){
                        if(in_array($keyint,$this->_arrColExcepciones)==true){
                            continue;
                        }
                        ////////////////////////////////////////////////////////
                        $this->objExcel->writeCol(utf8_decode(str_replace(',','',$valorint)));
                        
                        if($this->_bolTotalizar == true){
                            if(in_array($keyint,$this->_arrTotales)){
                                if(!isset($this->_arrVrTotal[$keyint]) || $this->_arrVrTotal[$keyint] == ''){
                                    $this->_arrVrTotal[$keyint] = 0;
                                }
                                $this->_arrVrTotal[$keyint] += str_replace(',','',$valorint);
                            }else{
                                $this->_arrVrTotal[$keyint] = "";
                            }
                        }
                    }
                    $this->_bolGetTitlePrint = true;
                }
                /*
                 * verificamos si hay que totalizar
                 */
                if($this->_bolTotalizar == true){
                    $this->objExcel->writeRow();
                    foreach($this->_arrVrTotal as $key => $valor){
                        $this->objExcel->writeCol($valor);
                    }
                }
                $this->objExcel->close();
            }catch(Exception $e){
                echo "Error ---> ".$e->getMessage().' ---> '.$this->objExcel->error;
            }
        }
        private function generar_col_head(){
            /**
             * recorremos la matriz para ir generandos las diferentes columnas del archivo
            */
            $this->objExcel->writeRow();
            foreach($this->_arrDatos['rows'] as $key => $valor){
                foreach ($valor as $keyint => $valorint){
                    if(in_array($keyint,$this->_arrColExcepciones)==true){
                        continue;
                    }
                    /*
                     * verificamos si se deben pintar los titulos
                    */
                    $this->objExcel->writeCol($this->set_cols_comodin($keyint));
                }
                return;
            }
        }
        /**/
        private function set_cols_comodin($keyint){
            // separamos el comodin //
            $arr_com = explode($this->_comodin,$keyint);
            $keyint = (isset($arr_com[1])?$arr_com[1]:$arr_com[0]);
            
            return $keyint;
        }
        /**/
        private function generar_col_colspan(){
            $this->objExcel->writeRow();
            $contcols = 0;
            for($i=0;$i<count($this->_arr_cols_colspan);$i++){
                if($this->get_num_cols() < $this->_arr_cols_colspan[$i]['colini']){
                    continue;
                }
                $colstart = ($this->_arr_cols_colspan[$i]['colini']-$contcols);
                if($colstart>0){
                    for($j=0;$j<$colstart;$j++){
                        $this->objExcel->writeCol('');
                        $contcols ++;
                    }
                    $this->objExcel->writeCol($this->_arr_cols_colspan[$i]['title'],$this->_arr_cols_colspan[$i]['cant']);
                    $contcols += $this->_arr_cols_colspan[$i]['cant'];
                }else{
                    $this->objExcel->writeCol($this->_arr_cols_colspan[$i]['title'],$this->_arr_cols_colspan[$i]['cant']);
                    $contcols += $this->_arr_cols_colspan[$i]['cant'];
                }
            }
        }
        /**/
        private function set_num_cols(){
            foreach($this->_arrDatos['rows'] as $key => $valor){
                foreach ($valor as $keyint => $valorint){
                    if(in_array($keyint,$this->_arrColExcepciones)==true){
                        continue;
                    }
                    // contamos las col head //
                    $this->_numCols ++;
                }
                return;
            }
        }
        /**/
        private function get_num_cols(){
            return $this->_numCols;
        }
        
        /**/
        private function set_title_document(){
            $this->objExcel->writeRow();
            $this->objExcel->writeCol($this->_titulo,20);
        }
    }
}