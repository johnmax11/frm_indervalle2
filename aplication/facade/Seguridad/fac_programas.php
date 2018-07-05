<?php 
namespace MiProyecto{
    class fac_programas{
        function __construct(){}
		
        /*     * action para buscar un producto por su nombre     */   
        public function searchrowsPublic(){
            try{
                $this->set_filters_grilla_basPrivate($page,$limit,$sidx,$sord);        
                /* get the direction */        
                if(!$sidx){             
                $sidx =1;         }        
                /* set valores */        
                $obj_dao_seguridad_programas = new dao_seguridad_programas();        
                $obj_dao_seguridad_programas->new_vo();        
                $obj_dao_seguridad_programas->_vo->set_estado('A');        
                /* set filters */        
                if(request::get_parameter('filters')!=null){            
                    utilidades::parsear_filters($obj_dao_seguridad_programas->_vo);
                }        

                /* connect to the database */        
                /* sacamos los registros en estado activo*/        
                $arrDatos = $obj_dao_seguridad_programas->select_rows()->fetch_object_vo();        
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
                $arrDatos = $obj_dao_seguridad_programas->select_rows($sidx, $sord, $start, $limit)->fetch_object_vo();        
                $numRows = count($arrDatos);        
                for($i=0;$i<$numRows;$i++){            
                        $obj_vo_seguridad_programas = $arrDatos[$i];            
                        /**/            
                        /* sacamos el nombre del usuario */            
                        $obj_dao_usuarios = new dao_usuarios();            
                        $obj_dao_usuarios->new_vo();            
                        $obj_dao_usuarios->_vo->set_id($obj_vo_seguridad_programas->get_creado_por());            
                        /* ejecutamos */            
                        $arrDatos_usuarios = $obj_dao_usuarios->select_rows()->fetch_object_vo();            
                        $obj_dao_usuarios->_vo = $arrDatos_usuarios[0];            
                        /**********/            

                        /* sacamos el nombre del modulo */            
                        $obj_dao_seguridad_modulos = new dao_seguridad_modulos();            
                        $obj_dao_seguridad_modulos->new_vo();            

                        /* set datos */            
                        $obj_dao_seguridad_modulos->_vo->set_id($obj_vo_seguridad_programas->get_seguridad_modulos_id());
                        /* execute */           
                        $arrDMod = $obj_dao_seguridad_modulos->select_rows()->fetch_object_vo();
                        $obj_dao_seguridad_modulos->_vo = $arrDMod[0];            
                        /**************************************/            
                        $responce->rows[$i]['id']=$obj_vo_seguridad_programas->get_id();            
                        $responce->rows[$i]['cell']=array(                
                                $obj_vo_seguridad_programas->get_id(),                
                                utf8_encode(ucwords(strtolower($obj_vo_seguridad_programas->get_nombre()))),               
                                $obj_dao_seguridad_modulos->_vo->get_nombre(),                
                                strtoupper($obj_dao_usuarios->_vo->get_usuario()),                
                                substr($obj_vo_seguridad_programas->get_fecha_creacion(),0,10)            
                        );        
                }                
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

        /**/   
        public function addeditrowsPublic(){        
            try{            
                /* start transaccion */            
                $obj_dao_seguridad_programas = new dao_seguridad_programas();            
                $obj_dao_seguridad_programas->begin();                        
                /* consultamos el nombre del modulo */            
                $obj_dao_seguridad_modulos = new dao_seguridad_modulos();            
                $obj_dao_seguridad_modulos->new_vo();            
                $obj_dao_seguridad_modulos->_vo->set_id(request::get_parameter('selSeguridad_modulos'));            
                /*execute */            
                $arrDMod = $obj_dao_seguridad_modulos->select_rows()->fetch_object_vo();
                $obj_dao_seguridad_modulos->_vo = $arrDMod[0];           
                $strNom_modulo = $obj_dao_seguridad_modulos->_vo->get_nombre();            
                /* consultamos si existe el producto */           
                $obj_dao_seguridad_programas->new_vo();            
                $obj_dao_seguridad_programas->_vo->set_id((request::get_parameter('hdnid')!=''?request::get_parameter('hdnid'):-1)); 
                /* ejecutamos */            
                $arrDatos_seguridad_programas = $obj_dao_seguridad_programas->select_rows()->fetch_object_vo();
                /**********************************************/            
                if($arrDatos_seguridad_programas == null){                
                    /*insertamos*/                
                    $obj_dao_seguridad_programas->_vo->set_nombre(strtoupper(request::get_parameter('txtNombre_programa'))); 
                    $obj_dao_seguridad_programas->_vo->set_alias(strtoupper(request::get_parameter('txtAlias_programa'))); 
                    $obj_dao_seguridad_programas->_vo->set_estado('A');               
                    $obj_dao_seguridad_programas->_vo->set_imagen(request::get_parameter('txtClass_imagen'));  
                    $obj_dao_seguridad_programas->_vo->set_seguridad_modulos_id(request::get_parameter('selSeguridad_modulos'));

                    $bolresult = $obj_dao_seguridad_programas->insert_rows();      
                    /* creamos la relacion del nuevo programa con los roles */               
                    $this->set_programa_roles(
                        $obj_dao_seguridad_programas->get_last_insert_id(),request::get_parameter('selSeguridad_modulos')
                    );                               
                    /* creamos el nuevo directorio del modulo */                
                    $this->crear_archivo_programaPrivate($strNom_modulo);            
                }else{                
                    $obj_dao_seguridad_programas->_vo->set_id(request::get_parameter('hdnid'));  
                    /* select datos old */                
                    $arrDOld = $obj_dao_seguridad_programas->select_rows()->fetch_object_vo(); 
                    /* actualizamos */              
                    $obj_dao_seguridad_programas->_vo->set_estado(request::get_parameter('selEstado_programa')); 
                    $obj_dao_seguridad_programas->_vo->set_imagen(request::get_parameter('txtClass_imagen'));  
                    $obj_dao_seguridad_programas->_vo->set_seguridad_modulos_id(request::get_parameter('selSeguridad_modulos')); 
                    $obj_dao_seguridad_programas->_vo->set_nombre(strtoupper(request::get_parameter('txtNombre_programa')));   
                    $obj_dao_seguridad_programas->_vo->set_alias(strtoupper(request::get_parameter('txtAlias_programa'))); 

                    $bolresult = $obj_dao_seguridad_programas->update_rows();     
                    /* renombrar la carpeta del modulo */                
                    $obj_dao_seguridad_programas->new_vo();   
                    $obj_dao_seguridad_programas->_vo = $arrDOld[0];                
                    $strOldNombre = $obj_dao_seguridad_programas->_vo->get_nombre();                
                    $this->renombrar_archivo_programaPrivate($strOldNombre,$strNom_modulo);            
                }            
                $obj_dao_seguridad_programas->commit();            
                /*******/            
                utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));        
            }catch(\Exception $ex){
                $obj_dao_seguridad_programas->rollback();            
                new ExceptionHandler($ex->getMessage());
            }
        }    
        /**/    
        public function deleterowsPublic(){        
            try{            /* set datos */            
                $obj_dao_seguridad_programas = new dao_seguridad_programas();            
                $obj_dao_seguridad_programas->new_vo();            
                $obj_dao_seguridad_programas->_vo->set_id(request::get_parameter('idrow'));            
                $obj_dao_seguridad_programas->_vo->set_estado('C'); 
                $result = $obj_dao_seguridad_programas->update_rows();         
                utilidades::set_response(array("msj"=>"Proceso terminado correctamente"));        
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }    
        }    

        /**/    
        public function searchdatosrowsPublic(){        
            try{            
                $arrResponse = array();            
                $obj_dao_seguridad_programas = new dao_seguridad_programas();            
                $obj_dao_seguridad_programas->new_vo();            
                $obj_dao_seguridad_programas->_vo->set_id(request::get_parameter('idrow'));            
                $arrResult = $obj_dao_seguridad_programas->select_rows()->fetch_object_vo();            
                $obj_dao_seguridad_programas->_vo = $arrResult[0];           
                $arrResponse['rows'][0]['id'] = $obj_dao_seguridad_programas->_vo->get_id();  
                $arrResponse['rows'][0]['nombre'] = ($obj_dao_seguridad_programas->_vo->get_nombre());  
                $arrResponse['rows'][0]['alias'] = ($obj_dao_seguridad_programas->_vo->get_alias());  
                $arrResponse['rows'][0]['estado'] = utf8_encode($obj_dao_seguridad_programas->_vo->get_estado()); 
                $arrResponse['rows'][0]['seguridad_modulos_id'] = utf8_encode($obj_dao_seguridad_programas->_vo->get_seguridad_modulos_id());      
                $arrResponse['rows'][0]['imagen'] = utf8_encode($obj_dao_seguridad_programas->_vo->get_imagen());  
                utilidades::set_response($arrResponse);
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }    
        }        

        /**************************************************************************/    
        /**
         * @author john jairo cortes <johnmax11@hotmail.com>
         * @return null
         * @param null
         */
        public function searchmodulosPublic(){        
            try{
                $objFacadeModulos = new \MiProyecto\fac_modulos();
                $arrD = $objFacadeModulos->get_seguridad_modulos('nombre','ASC');
                $arrResponse = array();            
                for($i=0;$i<count($arrD);$i++){
                    $arrResponse['rows'][$i]['id'] = $arrD[$i]->get_id();  
                    $arrResponse['rows'][$i]['nombre'] = $arrD[$i]->get_nombre(); 
                }                        
                utilidades::set_response($arrResponse);
                return null;
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }    
        }        

        /**     * crea la relacion programa-rol     */    
        public function set_programa_roles($idprograma,$idmod){        
            try{            
                /* consultamos los roles */            
                $obj_dao_seguridad_roles = new dao_seguridad_roles();            
                $arrD = $obj_dao_seguridad_roles->select_rows()->fetch_object_vo();            
                $obj_dao_seguridad_roles_acc = new dao_seguridad_roles_accesos();            
                for($i=0;$i<count($arrD);$i++){                
                    $obj_dao_seguridad_roles->new_vo();                
                    $obj_dao_seguridad_roles->_vo = $arrD[$i];                
                    /* hacemos un insert por cada rol */                

                    $obj_dao_seguridad_roles_acc->new_vo();				                
                    /* set datos */                
                    $obj_dao_seguridad_roles_acc->_vo->set_visible(($obj_dao_seguridad_roles->_vo->get_id()==1?'S':'N'));  
                    $obj_dao_seguridad_roles_acc->_vo->set_insertar(($obj_dao_seguridad_roles->_vo->get_id()==1?'S':'N')); 
                    $obj_dao_seguridad_roles_acc->_vo->set_seleccionar(($obj_dao_seguridad_roles->_vo->get_id()==1?'S':'N'));
                    $obj_dao_seguridad_roles_acc->_vo->set_actualizar(($obj_dao_seguridad_roles->_vo->get_id()==1?'S':'N')); 
                    $obj_dao_seguridad_roles_acc->_vo->set_borrar(($obj_dao_seguridad_roles->_vo->get_id()==1?'S':'N'));   
                    $obj_dao_seguridad_roles_acc->_vo->set_seguridad_roles_id($obj_dao_seguridad_roles->_vo->get_id());  
                    $obj_dao_seguridad_roles_acc->_vo->set_seguridad_modulos_id($idmod);                

                    /*** verificamos si el moduilo existe en las relaciones **/				
                    if(!$this->verifica_modulo_in_relacion($idmod,$obj_dao_seguridad_roles->_vo->get_id())){	
                        /** insertamos modulo - rol */					
                        /* execute */					
                        $obj_dao_seguridad_roles_acc->insert_rows();				
                    }				
                    /** insertamos modulo - programa */				
                    $obj_dao_seguridad_roles_acc->_vo->set_seguridad_programas_id($idprograma);    
                    /* execute */                
                    $obj_dao_seguridad_roles_acc->insert_rows();  
                }        
            }catch(\Exception $ex){         
                new ExceptionHandler($ex->getMessage());
            }    
        }	

        /***/	
        private function verifica_modulo_in_relacion($idmod,$idrol){		
            try{			
                $obj_dao_seguridad_roles_acc = new dao_seguridad_roles_accesos();			
                $obj_dao_seguridad_roles_acc->new_vo();            
                /* set datos */			
                $obj_dao_seguridad_roles_acc->_vo->set_seguridad_modulos_id($idmod);			
                $obj_dao_seguridad_roles_acc->_vo->set_seguridad_roles_id($idrol);			
                $arrDrsp = $obj_dao_seguridad_roles_acc->select_rows()->fetch_object_vo();			
                if(count($arrDrsp)>0){				
                    return true;			
                }else{				
                    return false;			
                }		
            }catch(\Exception $ex){            
                new ExceptionHandler($ex->getMessage());
            }	
        }

        /**     * crea un directorio con el nombre del modulo     */    
        public function crear_archivo_programaPrivate($strNom_modulo){    
            try{
                /* verificamos si el archivo existe */        
                if(!is_file($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                   '/aplication/control/'.ucfirst(strtolower($strNom_modulo)).
                   '/con_'.strtolower(request::get_parameter('txtNombre_programa').'.php')))
                {
                   /* creamos el archivo en la carpeta control */            
                   $nuevoarchivo = fopen($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                   '/aplication/control/'.ucfirst(strtolower($strNom_modulo)).
                   '/con_'.strtolower(request::get_parameter('txtNombre_programa').'.php'), "w+");
                   /*fwrite($nuevoarchivo,"<?php echo \"texto qe contiene el nuevo archivo\"; ?>");*/            
                   fclose($nuevoarchivo);            
                   chmod($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                        '/aplication/control/'.ucfirst(strtolower($strNom_modulo)).
                        '/con_'.strtolower(request::get_parameter('txtNombre_programa').'.php'),0755
                    );        
                }        

                /* verificamos si existe el directorio en la carpeta js */        
                if(!is_file($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                    '/js/'.ucfirst(strtolower($strNom_modulo)).'/vis'.
                        ucfirst(strtolower(request::get_parameter('txtNombre_programa').'.php'))))
                {        
                    /* creamos el archivo en la carpeta js */            
                    $nuevoarchivo = fopen($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']
                    .'/js/'.ucfirst(strtolower($strNom_modulo)).'/vis'.
                    ucfirst(strtolower(request::get_parameter('txtNombre_programa').'.js')), "w+");

                    /*fwrite($nuevoarchivo,"<?php echo \"texto qe contiene el nuevo archivo\"; ?>");*/
                    fclose($nuevoarchivo);            
                    chmod($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                          '/js/'.ucfirst(strtolower($strNom_modulo))
                              .'/vis'.ucfirst(strtolower(request::get_parameter('txtNombre_programa').'.js')),0755
                    );        
                }        

                /* verificamos si existe el directorio en la carpeta views */        
                if(!is_file($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                    '/aplication/views/'.ucfirst(strtolower($strNom_modulo)).
                    '/vis'.ucfirst(strtolower(request::get_parameter('txtNombre_programa').'.php'))))
                {
                    /* creamos el archivo en la carpeta js */            
                    $nuevoarchivo = fopen($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                                        '/aplication/views/'.ucfirst(strtolower($strNom_modulo)).
                                        '/vis'.ucfirst(strtolower(request::get_parameter('txtNombre_programa').'.php')), "w+"
                                    );            
                    /*fwrite($nuevoarchivo,"<?php echo \"texto qe contiene el nuevo archivo\"; ?>");*/            
                    fclose($nuevoarchivo);            
                    chmod($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                        '/aplication/views/'.ucfirst(strtolower($strNom_modulo)).'/vis'.
                        ucfirst(strtolower(request::get_parameter('txtNombre_programa').'.php')),0755
                    );        
                }  
            }catch(\Exception $ex){            
                new ExceptionHandler($ex->getMessage());
            }
        }    

        /**     * modifica el nombre de un directorio     */   
        public function renombrar_archivo_programaPrivate($oldname,$strNom_modulo){        
            try{
                /* verificamos si el archivo existe */        
                if(is_file($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                    '/aplication/control/'.ucfirst(strtolower($strNom_modulo)).'/con_'.strtolower($oldname.'.php')))
                {         
                    /* creamos el archivo en la carpeta control */            
                    rename(
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                        "/aplication/control/".ucfirst(strtolower($strNom_modulo))."/con_".strtolower($oldname.'.php'),
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/control/".
                        ucfirst(strtolower($strNom_modulo))."/con_".strtolower(request::get_parameter('txtNombre_programa').'.php')
                    );        
                }        

                /* verificamos si existe el archivo en la carpeta js */        
                if(is_file($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/js/'.
                            ucfirst(strtolower($strNom_modulo)).'/vis'.ucfirst(strtolower($oldname.'.js'))))
                { 
                    /* creamos el archivo en la carpeta js */            
                    rename(                    
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/js/".
                        ucfirst(strtolower($strNom_modulo))."/vis".ucfirst(strtolower($oldname.'.js')),  
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/js/".
                        ucfirst(strtolower($strNom_modulo))."/vis".
                        ucfirst(strtolower(request::get_parameter('txtNombre_programa').'.js'))                  
                    );        
                }        
                /* verificamos si existe el archivo en la carpeta views */        
                if(is_file($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                            '/aplication/views/'.ucfirst(strtolower($strNom_modulo)).'/vis'.ucfirst(strtolower($oldname.'.php')))
                )
                {
                    /* creamos el archivo en la carpeta js */            
                    rename(
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].
                        "/aplication/views/".ucfirst(strtolower($strNom_modulo))."/vis".ucfirst(strtolower($oldname.'.php')), 
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/views/".
                        ucfirst(strtolower($strNom_modulo))."/vis".ucfirst(strtolower(request::get_parameter('txtNombre_programa').'.php'))
                    );        
                }
            }catch(\Exception $ex){            
                new ExceptionHandler($ex->getMessage());
            }
        }
    }
}