<?php
namespace MiProyecto{
    class fac_gestion_productos{
        function __construct(){}
		
        /*
         * action para buscar un producto por su nombres
         */
        public function get_datos_grillaPublic(){
            try{
                $this->set_filters_grilla_basPrivate($page,$limit,$sidx,$sord);
                /* sacamos los registros en estado activo*/
                $arrDatos = $this->get_productos_datos_basicos();
                $count = count($arrDatos); 
                if( $count >0 ) { 
                    $total_pages = ceil($count/$limit);
                } else {
                    $total_pages = 0;
                } 
                if ($page > $total_pages) 
                    $page=$total_pages; 
                $start = $limit*$page - $limit; 
                if($start<0){
                    $start = 0;
                }
                /* do not put $limit*($page - 1)*/
                $responce = new \stdClass();
                $responce->page = $page; 
                $responce->total = $total_pages; 
                $responce->records = $count;

                $arrDatos = array();
                /* verificamos campos fk */
                if(utilidades::verifica_campo_fk($sidx)==false){
                    $arrDatos = $this->get_productos_datos_basicos($sidx,$sord, $start, $limit);
                }
                $numRows = count($arrDatos);
                /***/
                for($i=0;$i<$numRows;$i++){
                    $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos();
                    $obj_dao_productos_datos_basicos->_vo = $arrDatos[$i];

                    /* sacamos el nombre del usuario */
                    $obj_dao_usuarios = new dao_usuarios($obj_dao_productos_datos_basicos->_vo->get_creado_por());
                    /** sacamos el nombre de la categoria*/
                    $obj_dao_productos_categorias = new dao_productos_categorias($obj_dao_productos_datos_basicos->_vo->get_productos_categorias_id());
                    // ** //
                    $responce->rows[$i]['id'] = $obj_dao_productos_datos_basicos->_vo->get_id();
                    $responce->rows[$i]['cell']=array(
                        $obj_dao_productos_datos_basicos->_vo->get_id(),
                        $obj_dao_productos_datos_basicos->_vo->get_nombres(),
                        $obj_dao_productos_datos_basicos->_vo->get_descripcion(),
                        $obj_dao_productos_datos_basicos->_vo->get_imagen(),
                        $obj_dao_productos_datos_basicos->_vo->get_valor_compra(),
                        $obj_dao_productos_datos_basicos->_vo->get_valor_final(),
                        ($obj_dao_productos_categorias->_vo!=null?$obj_dao_productos_categorias->_vo->get_nombre():''),
                        strtoupper($obj_dao_usuarios->_vo->get_usuario()),
                        substr($obj_dao_productos_datos_basicos->_vo->get_fecha_creacion(),0,10)
                    );
                }
                /***/
                utilidades::set_response($responce);
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }

        /***/
        private function set_filters_grilla_basPrivate(&$page,&$limit,&$sidx,&$sord){
            try{
                $page = (request::get_parameter('page')!=null?request::get_parameter('page'):1); 
                /* get the requested page */
                $limit = (request::get_parameter('rows')!=null?request::get_parameter('rows'):999999999);
                /* get how many rows we want to have into the grid */
                $sidx = (request::get_parameter('sidx')!=null?request::get_parameter('sidx'):'id');
                /* get index row - i.e. user click to sort */
                $sord = (request::get_parameter('sord')!=null?request::get_parameter('sord'):'ASC');
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * consulta la tbla productos datos basicos
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @modified 07-12-2014
         * @author_m john jairo cortes garcia <johnmax11@hotmail.com>
         * @param string $sidx
         * @param string $sord
         * @param int $start
         * @param int $limit
         * @param char $estado
         * @param array $arrParametros
         * @return array
         */
        public function get_productos_datos_basicos($sidx=null,$sord=null, $start=null, $limit=null,$estado='A',$arrParametros=array(),$group_by=null){
            try{
                $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos();
                
                $obj_dao_productos_datos_basicos->_vo->set_estado($estado);
                
                if(request::get_parameter('idrow')!=null){
                    $obj_dao_productos_datos_basicos->_vo->set_id(request::get_parameter('idrow'));
                }else{
                    if(isset($arrParametros->id) && $arrParametros->id!=''){
                        $obj_dao_productos_datos_basicos->_vo->set_id($arrParametros->id);
                    }
                }
                
                if(request::get_parameter('term')!=null){
                    switch(request::get_parameter('field')){
                        case "nombres":
                            $obj_dao_productos_datos_basicos->_vo->set_nombres("EXP||LIKE||%".ucwords(request::get_parameter('term'))."%");
                            break;
                    }
                }
                
                /* set filters */
                if(request::get_parameter('filters')!=null){
                    utilidades::parsear_filters($obj_dao_productos_datos_basicos->_vo);
                }
                
                if($group_by!=null){
                    return $obj_dao_productos_datos_basicos->select_rows($sidx,$sord, $start, $limit)->group_by($group_by)->fetch_object_vo();
                }else{
                    /***/
                    return $obj_dao_productos_datos_basicos->select_rows($sidx,$sord, $start, $limit)->fetch_object_vo();
                }
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /***/
        public function crear_productoPublic(){
            try{
                $this->insert_productos_datos_basicos();

                echo "<script>
                            alert('Proceso terminado correctamente');
                            location.href = '".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."/index.php?module=Productos&action=Gestion';
                        </script>";
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * 
         * @return boolean
         */
        private function insert_productos_datos_basicos(){
            try{
                /***/
                $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos();
                
                /**validamos la referencia del producto*/
                if($this->validate_nombre_referenciaPrivate(request::get_parameter('txtreferencia'))==true){
                        echo '<script>
                                    alert("La referencia ('.request::get_parameter('txtreferencia').') ya existe");
                                    location.href = "'.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'].'/index.php?module=Productos&action=Gestion";
                                </script>';
                        return false;
                }
                
                $obj_dao_productos_datos_basicos->_vo->set_nombres(request::get_parameter("txtreferencia"));
                $obj_dao_productos_datos_basicos->_vo->set_descripcion(request::get_parameter("txtDescripcion"));
                $obj_dao_productos_datos_basicos->_vo->set_valor_compra(request::get_parameter("txtvalor_compra"));
                $obj_dao_productos_datos_basicos->_vo->set_valor_final(request::get_parameter("txtvalor"));
                $obj_dao_productos_datos_basicos->_vo->set_productos_categorias_id(request::get_parameter("selProductos_categorias"));
                $obj_dao_productos_datos_basicos->_vo->set_estado("A");
                $obj_dao_productos_datos_basicos->insert_rows();
                $ult_id = $obj_dao_productos_datos_basicos->get_last_insert_id();
                /**verificamos para mover la imagen*/
                if(request::get_files('txtExaminar')!=null && request::get_files('txtExaminar','name')!=null){
                    $ext = substr(request::get_files('txtExaminar','name'),strrpos(request::get_files('txtExaminar','name'),".")+1);
                    copy(request::get_files('txtExaminar','tmp_name'),$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/files/img_productos/".$ult_id.".".$ext);
                    /**actualizamos con el nombre de la imagen*/
                    $obj_dao_productos_datos_basicos->new_vo();
                    $obj_dao_productos_datos_basicos->_vo->set_id($ult_id);
                    $obj_dao_productos_datos_basicos->_vo->set_imagen($ult_id.".".$ext);
                    /****/
                    $obj_dao_productos_datos_basicos->update_rows();
                }
                
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }

        private function validate_nombre_referenciaPrivate($n_referencia){
            try{
                /***/
                $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos();
                $obj_dao_productos_datos_basicos->_vo->set_nombres($n_referencia);
                $obj_dao_productos_datos_basicos->_vo->set_estado("A");
                $arrDVo = $obj_dao_productos_datos_basicos->select_rows()->fetch_object_vo();

                if($arrDVo==null){
                    return false;
                }else{
                    return true;
                }

            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /***/
        public function mostrar_categorias_productosPublic(){
            try{
                $arrObj_cat = $this->get_productos_categorias();
                
                $arr_response = new \stdClass();
                for($i=0;$i<count($arrObj_cat);$i++){
                    $arr_response->rows[$i] = new \stdClass();
                    
                    $arr_response->rows[$i]->id = $arrObj_cat[$i]->get_id();
                    $arr_response->rows[$i]->nombre = $arrObj_cat[$i]->get_nombre();
                }
                
                utilidades::set_response($arr_response);
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }

        /***/
        private function get_productos_categorias($estado='A'){
            try{
                /***/
                $obj_dao_productos_categorias = new dao_productos_categorias();
                /**execute*/
                $obj_dao_productos_categorias->_vo->set_estado($estado);
                return $obj_dao_productos_categorias->select_rows("nombre","ASC")->fetch_object_vo();
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }

        /***/
        public function mostrar_producto_by_idPublic(){
            try{
                $arrObj_prod = $this->get_productos_datos_basicos();
                
                $arr_response = new \stdClass();
                if($arrObj_prod!=null){
                    $arr_response->rows[0] = new \stdClass();
                    
                    $arr_response->rows[0]->nombres = $arrObj_prod[0]->get_nombres();
                    $arr_response->rows[0]->productos_categorias_id = $arrObj_prod[0]->get_productos_categorias_id();
                    $arr_response->rows[0]->descripcion = $arrObj_prod[0]->get_descripcion();
                    $arr_response->rows[0]->valor_compra = $arrObj_prod[0]->get_valor_compra();
                    $arr_response->rows[0]->valor_final = $arrObj_prod[0]->get_valor_final();
                    $arr_response->rows[0]->imagen = $arrObj_prod[0]->get_imagen();
                }
                
                utilidades::set_response($arr_response);
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }

        /***/
        public function actualizar_producto_by_idPublic(){
            try{
                $this->update_productos_datos_basicos();
                echo "<script>
                            alert('Proceso terminado correctamente');
                            location.href = '".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."/index.php?module=Productos&action=Gestion';
                        </script>";
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /****/
        private function update_productos_datos_basicos(){
            try{
                /***/
                $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos();
                $obj_dao_productos_datos_basicos->_vo->set_id(request::get_parameter("hdnid"));
                $obj_dao_productos_datos_basicos->_vo->set_descripcion(mb_convert_encoding(mb_convert_case(request::get_parameter("txtDescripcion"),MB_CASE_TITLE),'UTF-8'));
                $obj_dao_productos_datos_basicos->_vo->set_valor_compra(request::get_parameter("txtvalor_compra"));
                $obj_dao_productos_datos_basicos->_vo->set_valor_final(request::get_parameter("txtvalor"));
                $obj_dao_productos_datos_basicos->_vo->set_productos_categorias_id(request::get_parameter("selProductos_categorias"));
                /**verificamos para mover la imagen*/
                if(request::get_files('txtExaminar')!=null && request::get_files('txtExaminar','name')!=null){
                    $ext = substr(request::get_files('txtExaminar','name'),strrpos(request::get_files('txtExaminar','name'),".")+1);
                    copy(request::get_files('txtExaminar','tmp_name'),$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/files/img_productos/".request::get_parameter("hdnid").".".$ext);
                    $obj_dao_productos_datos_basicos->_vo->set_imagen(request::get_parameter("hdnid").".".$ext);
                }
                $obj_dao_productos_datos_basicos->update_rows();
                
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /***/
        public function delete_producto_by_idPublic(){
            try{
                /****/
                $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos();
                $obj_dao_productos_datos_basicos->_vo->set_id(request::get_parameter('idrow'));
                $obj_dao_productos_datos_basicos->_vo->set_estado("I");
                /***/
                $obj_dao_productos_datos_basicos->update_rows();

                utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * encargado de consultar un producto(s) por autocomplete
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @return json
         * @param void
         */
        public function voidBuscarProductoAutocompleteByCampo(){
            try{
                $arrDR = $this->get_productos_datos_basicos();
                
                /***/
                $arrResponse = array();
                // verificamos //
                if(count($arrDR)>0){
                    for($i=0;$i<count($arrDR);$i++){
                        $arrResponse[$i]['id'] = $arrDR[$i]->get_id();
                        switch(request::get_parameter('field')){
                            case "nombres":
                                $arrResponse[$i]['label'] = ("(".$arrDR[$i]->get_nombres().")".$arrDR[$i]->get_descripcion());
                                $arrResponse[$i]['value'] = $arrDR[$i]->get_nombres();
                                $arrResponse[$i]['descripcion'] = $arrDR[$i]->get_descripcion();
                                $arrResponse[$i]['valor_final'] = $arrDR[$i]->get_valor_final();
                                $arrResponse[$i]['productos_categorias_id'] = $arrDR[$i]->get_productos_categorias_id();
                                break;
                        }
                    }
                }
                utilidades::set_response($arrResponse);
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        public function voidConsultarConsecutivosAgrupados(){
            try{
                $arrDatosProductos = $this->get_productos_datos_basicos("nombres","ASC");
                $arrDResponse = new \stdClass();
                if($arrDatosProductos!=null){
                    $numVrC = count($arrDatosProductos);
                    
                    /**recorremos los datos para sacar los agrupados*/
                    $arrRef = array();
                    $arrDesc = array();
                    $strRef = null;
                    for($i=0;$i<$numVrC;$i++){
                        if($strRef != substr($arrDatosProductos[$i]->get_nombres(),0,1)){
                            $strRef = substr($arrDatosProductos[$i]->get_nombres(),0,1);
                            array_push($arrRef,$arrDatosProductos[$i]->get_nombres());
                            array_push($arrDesc,$arrDatosProductos[$i]->get_descripcion());
                        }else{
                            $arrRef[count($arrRef)-1] = $arrDatosProductos[$i]->get_nombres();
                            $arrDesc[count($arrDesc)-1] = $arrDatosProductos[$i]->get_descripcion();
                        }
                    }
                    
                    /**arr response*/
                    for($i=0;$i<count($arrRef);$i++){
                        $arrDResponse->rows[$i] = new \stdClass();
                        
                        $arrDResponse->rows[$i]->referencia = $arrRef[$i];
                        $arrDResponse->rows[$i]->descripcion = $arrDesc[$i];
                    }
                }
                
                utilidades::set_response($arrDResponse);
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
    }
}
