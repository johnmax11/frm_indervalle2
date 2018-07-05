<?php 
namespace MiProyecto{
    /*set_time_limit(0);*/
    ini_set('memory_limit','256M');
    /** * Description of utilidades * * @author vanessa */
    class utilidades{ 
        /*put your code here*/    
        static function RandomString($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE){        
            $source = 'abcdefghijklmnopqrstuvwxyz';        
            if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';        
            if($n==1) $source .= '1234567890';        
            if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';        
            if($length>0){            
                    $rstr = "";            
                    $source = str_split($source,1);            
                    for($i=1; $i<=$length; $i++){                
                        mt_srand((double)microtime() * 1000000);                
                        $num = mt_rand(1,count($source));                
                        $rstr .= $source[$num-1];            
                    }        
            }        
            return $rstr;    
        }
        /*     * calcula la fecha     */    
        static function fnAddDiasFecha($strFecha,$numDias,$numRow,$bolExit=true){
            $fecha = $strFecha;            
            $dias  = $numDias;            
            if($bolExit){                    
                $dia = substr($fecha,6,2);                    
                $mes = substr($fecha,4,2);            
            }            
            else{                    
                $dia = substr($fecha,8,2);                    
                $mes = substr($fecha,5,2);            
            }            
            $anio = substr($fecha,0,4);            
            $ultimo_dia = @date( "d", mktime(0, 0, 0, $mes + 1, 0, $anio));            
            $dias_adelanto = $dias;            
            $siguiente = $dia + $dias_adelanto;            
            if ($ultimo_dia < $siguiente){                
                $dia_final = $siguiente - $ultimo_dia;                
                $mes++;                
                if ($mes == '13'){                    
                    $anio++;                    
                    $mes = '01';                
                }                
                $fecha_final = $anio.'-'.str_pad($mes,2,'0',STR_PAD_LEFT).'-'.str_pad($dia_final,2,'0',STR_PAD_LEFT);            
            }            
            else{                
                $fecha_final = $anio .'-'.str_pad($mes,2,'0',STR_PAD_LEFT).'-'.str_pad($siguiente,2,'0',STR_PAD_LEFT);            
            }             
            if($bolExit){                
                echo $numRow.'|'.$fecha_final;                
                exit;            
            }            
            else{                    
                return $fecha_final;	            
            }    
        }        
        /**/    
        static function verifica_rol_acceso_in_array($objSes,$_req,$tipo){        
            $module = isset($_req['module'])?$_req['module']:null;        
            $action = isset($_req['action'])?$_req['action']:null;                
            /* volvemos y evaluamos */        
            if($module==null || $action==null){            
                return false;        
            }        
            $arrayacc = $objSes->get_ssn_accesos_rol();        
            if(isset($arrayacc['rows'])){            
                for($i=0;$i<count($arrayacc['rows']);$i++){                
                    if(strtolower($arrayacc['rows'][$i]['seguridad_modulos_nombre']) == strtolower($module) &&
                       strtolower($arrayacc['rows'][$i]['seguridad_programas_nombre']) == strtolower($action)                  
                    ){                    
                        if($arrayacc['rows'][$i][$tipo]=='S'){                        
                            $obj_ssn = new utisetVarSession();                        
                            $obj_ssn->set_ssn_seguridad_programa_id($arrayacc['rows'][$i]['seguridad_programas_id']);
                            return true;                    
                        }else{                        
                            return false;                    
                        }                
                    }            
                }        
            }        
            return true;    
        }    
        /**/    
        static function set_response($array,$error=false){
                /** verificamos  si es por order by*/
                unset($_SESSION[$_SESSION['_SFT_NAME_']]['arrVariablesDatos']);
                unset($_SESSION['querys_cache']);
                if(
                    isset($_REQUEST['sord'])&&$_REQUEST['sord']!='' &&
                    isset($_REQUEST['sidx'])&&$_REQUEST['sidx']!=''
                ){
                        /** verificamos q el campo no sea una fk */
                        $ind_p = (strrpos($_REQUEST['sidx'],"_")+1);
                        /* substr */
                        $str_fk = substr($_REQUEST['sidx'],$ind_p,2);
                        if($str_fk=="fk"){
                                if(isset($array->rows) && isset($array->rows[0]['cell'])){
                                        /******************************************/
                                        /* obtenemos el indice de la columna ******/
                                        $str_fk = substr($_REQUEST['sidx'],$ind_p+2,2);
                                        /**/
                                        $str_fk = (is_numeric($str_fk)?$str_fk:substr($_REQUEST['sidx'],$ind_p+2,1));
                                        /*******************************************/
                                        /* obtenemos el tipo del campo *************/
                                        $tipo_fk = substr($_REQUEST['sidx'],($ind_p+2+strlen($str_fk)));
                                        /* ordenamos el resultado por el campo de order by */
                                        $array = utilidades::order_matriz($array,$str_fk,$tipo_fk,$_REQUEST['sord']);
                                }
                        }
                        /** verificamos los filters */
                        if(isset($_REQUEST['filters']) && $_REQUEST['filters']!=''){
                                $array = utilidades::filtrar_campos_fk($array);
                        }
                }else{
                        if(is_object($array)){
                                $array->error = $error;
                        }else{
                                $array['error'] = $error;
                        }
                }
                echo json_encode(               
                        $array             
                );
                /**matamos el proceso*/
                exit;
        }    
        /**/    
        static function get_mes_string_spanish($mes){        
                switch((int)$mes){            
                        case 1: return "Enero";            
                        case 2: return "Febrero";            
                        case 3: return "Marzo";            
                        case 4: return "Abril";            
                        case 5: return "Mayo";            
                        case 6: return "Junio";            
                        case 7: return "Julio";            
                        case 8: return "Agosto";            
                        case 9: return "Septiembre";            
                        case 10: return "Octubre";            
                        case 11: return "Noviembre";            
                        case 12: return "Diciembre";        
                }    
        }    
        /**/    
        static function parsear_filters(&$vo){
            if($_REQUEST['filters']==''){
                return;
            }
            $filters_json = $_REQUEST['filters'];        
            if($filters_json == ''){            
                return;        
            }        
            $arrFilters = json_decode(str_replace('\\','',$filters_json));        
            if(!isset($arrFilters->rules)){            
                return;        
            }
            for($i=0;$i<count($arrFilters->rules);$i++){            
                if(method_exists($vo,'set_'.$arrFilters->rules[$i]->field)){
                    switch($arrFilters->rules[$i]->op){                    
                        case "eq":                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("'.$arrFilters->rules[$i]->data.'");');
                            break;                    
                        case 'ne':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||NOT IN||'.$arrFilters->rules[$i]->data.'");');
                            break;                    
                        case 'lt':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||<||'.$arrFilters->rules[$i]->data.'");');
                            break;                    
                        case 'le':                       
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||<=||'.$arrFilters->rules[$i]->data.'");');
                            break;                    
                        case 'gt':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||>||'.$arrFilters->rules[$i]->data.'");');
                            break;                    
                        case 'ge':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||>=||'.$arrFilters->rules[$i]->data.'");');
                            break;                    
                        case 'bw':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||LIKE||'.$arrFilters->rules[$i]->data.'%");');
                            break;                    
                        case 'bn':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||NOT LIKE||'.$arrFilters->rules[$i]->data.'%");');
                            break;                    
                        case 'in':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||IN||'.$arrFilters->rules[$i]->data.'");'); 
                            break;                    
                        case 'ni':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||NOT IN||'.$arrFilters->rules[$i]->data.'");');
                            break;                    
                        case 'ew':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||LIKE||%'.$arrFilters->rules[$i]->data.'");');
                            break;                    
                        case 'en':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||NOT LIKE||%'.$arrFilters->rules[$i]->data.'");');
                            break;                    
                        case 'cn':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||LIKE||%'.$arrFilters->rules[$i]->data.'%");');
                            break;                    
                        case 'nc':                        
                            eval('$vo->set_'.$arrFilters->rules[$i]->field.'("EXP||NOT LIKE||%'.$arrFilters->rules[$i]->data.'%");');
                            break;                    
                        case 'nu':                        
                            break;                    
                        case 'nn':                        
                            break;                
                    }            
                }else{                
                    $_REQUEST[$arrFilters->rules[$i]->field] = $arrFilters->rules[$i]->data;            
                }        
            }
        }
        /**/    
        static function get_operacion($op){        
            switch($op){            
                case "":                
                    return ;        
            }    
        }        
        /**     * @return string calcula la diferencia entre 2 fechas de tipo date     */    
        static function get_diff_date($fecha_inicio,$fecha_fin){        
            $dFecIni = str_replace("-","",$fecha_inicio);        
            $dFecFin = str_replace("-","",$fecha_fin);        
            @ereg( "([0-9]{4})([0-9]{1,2})([0-9]{1,2})", $dFecIni, $aFecIni);        
            @ereg( "([0-9]{4})([0-9]{1,2})([0-9]{1,2})", $dFecFin, $aFecFin);        
            $date1 = @mktime(0,0,0,$aFecIni[2], $aFecIni[3], $aFecIni[1]);        
            $date2 = @mktime(0,0,0,$aFecFin[2], $aFecFin[3], $aFecFin[1]);        
            return round(($date2 - $date1) / (60 * 60 * 24));    
        }        
        /**     
         * * @return array recibe un objeto vo y el nombre de la clase para parsear los datos     
         * * en un array asociativo     
         */    
        /***/    
        static function get_array_assoc($vo,$class){
            $arrReturn = array();
            if($vo!=null){
                $metodos_clase = get_class_methods(__NAMESPACE__.'\vo_'.substr($class,4));
                foreach($metodos_clase as $key => $valor){                
                    if(substr($valor,0,3)=='get' && !is_null(eval('return $vo->'.$valor.'();'))){                    
                        $arrReturn[substr($valor,4)] = eval('return $vo->'.$valor.'();');                
                    }
                }        
            }        
            return $arrReturn;
        }
        /**retorna las columnas de un objeto vo**/
        static function get_array_cols($class){
            $arrReturn = array();
            if($class!=null){
                $metodos_clase = get_class_methods(__NAMESPACE__.'\vo_'.substr(utilidades::get_str_name_table($class),4));
                
                foreach($metodos_clase as $key => $valor){
                    if(substr($valor,0,3)=='get' && substr($valor,4)!='standar_'){
                        $arrReturn[] = substr($valor,4);
                    }
                }        
            }
            return $arrReturn;
        }
        /**
         *      *      
         */    
        static function set_parsear_objeto_vo_nativo($arrDatos,$table){        
            $vo = null;        
            $metodos_clase = get_class_methods(__NAMESPACE__.'\vo_'.$table);        
            $arrDat_return = array();
            if($arrDatos!=null){
                for($i=0;$i<count($arrDatos);$i++){
                    eval('$vo = new '.__NAMESPACE__.'\vo_'.$table.'();');            
                    foreach($metodos_clase as $key => $valor){                
                        if(substr($valor,0,3)=='set'){                    
                            $prop = substr($valor,4);                                        
                            if($prop == 'standar_'){                        
                                continue;                    
                            }                                        
                            eval('$vo->$valor($arrDatos[$i]->'.$prop.');');                
                        }            
                    }                        
                    $arrDat_return[] = $vo;        
                }
            }
            return $arrDat_return;    
        }        
        /**     * @return timestamp retorna la fecha y hora actual del servidor     */    
        static function get_current_timestamp(){        
            return @date('Y-m-d H:i:s');    
        }        
        /***/    
        static function isAjax(){        
            return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&                
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';    
        }

        /** ordenar matriz por campo */
        static function order_matriz($matriz,$campo,$tipo_campo,$tipo_order='ASC'){
            /* valimos q tenga l estrctura correcta */
            try{
                    if(!isset($matriz->rows) && !isset($matriz['rows'])){
                            throw new \Exception ('Error: La matriz no tiene la estrctura correcta $array["rows"]');
                    }

                    /** verificamos el tipo de matriz normal */
                    if(!isset($matriz->rows[0]['cell'])){
                            for($i=0;$i<count($matriz['rows']);$i++){
                                    for($j=$i+1;$j<count($matriz['rows']);$j++){
                                            switch(strtoupper($tipo_campo)){
                                                    case "STR":
                                                            if(strtoupper($tipo_order)=="ASC"){
                                                                    if(strcmp($matriz['rows'][$i][$campo],$matriz['rows'][$j][$campo])>0){
                                                                            $arr_a = $matriz['rows'][$i];
                                                                            $matriz['rows'][$i] = $matriz['rows'][$j];
                                                                            $matriz['rows'][$j] = $arr_a;
                                                                    }
                                                            }else{
                                                                    if(strcmp($matriz['rows'][$i][$campo],$matriz['rows'][$j][$campo])<0){
                                                                            $arr_a = $matriz['rows'][$j];
                                                                            $matriz['rows'][$j] = $matriz['rows'][$i];
                                                                            $matriz['rows'][$i] = $arr_a;
                                                                    }
                                                            }
                                                    break;
                                                    case "INT":
                                                            if(strtoupper($tipo_order)=="ASC"){
                                                                    if((float)$matriz['rows'][$i][$campo]>(float)$matriz['rows'][$j][$campo]){
                                                                            $arr_a = $matriz['rows'][$i];
                                                                            $matriz['rows'][$i] = $matriz['rows'][$j];
                                                                            $matriz['rows'][$j] = $arr_a;
                                                                    }
                                                            }else{
                                                                    if((float)$matriz['rows'][$i][$campo]<(float)$matriz['rows'][$j][$campo]){
                                                                            $arr_a = $matriz['rows'][$j];
                                                                            $matriz['rows'][$j] = $matriz['rows'][$i];
                                                                            $matriz['rows'][$i] = $arr_a;
                                                                    }
                                                            }
                                                            break;
                                                    case "TIM":
                                                            if(strtoupper($tipo_order)=="ASC"){
                                                                    if((float)str_replace(' ','',str_replace(':','',str_replace('-','',$matriz['rows'][$i][$campo])))>
                                                                       (float)str_replace(' ','',str_replace(':','',str_replace('-','',$matriz['rows'][$j][$campo])))){
                                                                            $arr_a = $matriz['rows'][$i];
                                                                            $matriz['rows'][$i] = $matriz['rows'][$j];
                                                                            $matriz['rows'][$j] = $arr_a;
                                                                    }
                                                            }else{
                                                                    if((float)str_replace(' ','',str_replace(':','',str_replace('-','',$matriz['rows'][$i][$campo])))<
                                                                       (float)str_replace(' ','',str_replace(':','',str_replace('-','',$matriz['rows'][$j][$campo])))){
                                                                            $arr_a = $matriz['rows'][$j];
                                                                            $matriz['rows'][$j] = $matriz['rows'][$i];
                                                                            $matriz['rows'][$i] = $arr_a;
                                                                    }
                                                            }
                                                            break;
                                            } // fin switch //
                                    } // fin for second //
                            } // fin for principal //
                    }
                    /** verificamos el tipo de matriz de grilla */
                    if(isset($matriz->rows[0]['cell'])){
                            for($i=0;$i<count($matriz->rows);$i++){
                                    for($j=$i+1;$j<count($matriz->rows);$j++){
                                            switch(strtoupper($tipo_campo)){
                                                    case "STR":
                                                            if(strtoupper($tipo_order)=="ASC"){
                                                                    if(strcmp($matriz->rows[$i]['cell'][$campo],$matriz->rows[$j]['cell'][$campo])<0){
                                                                            $arr_a = $matriz->rows[$i];
                                                                            $matriz->rows[$i] = $matriz->rows[$j];
                                                                            $matriz->rows[$j] = $arr_a;
                                                                    }
                                                            }else{
                                                                    if(strcmp($matriz->rows[$i]['cell'][$campo],$matriz->rows[$j]['cell'][$campo])>0){
                                                                            $arr_a = $matriz->rows[$j];
                                                                            $matriz->rows[$j] = $matriz->rows[$i];
                                                                            $matriz->rows[$i] = $arr_a;
                                                                    }
                                                            }
                                                            break;
                                                    case "INT":
                                                            if(strtoupper($tipo_order)=="ASC"){
                                                                    if((float)$matriz->rows[$i]['cell'][$campo]>(float)$matriz->rows[$j]['cell'][$campo]){
                                                                            $arr_a = $matriz->rows[$i];
                                                                            $matriz->rows[$i] = $matriz->rows[$j];
                                                                            $matriz->rows[$j] = $arr_a;
                                                                    }
                                                            }else{
                                                                    if((float)$matriz->rows[$i]['cell'][$campo]<(float)$matriz->rows[$j]['cell'][$campo]){
                                                                            $arr_a = $matriz->rows[$j];
                                                                            $matriz->rows[$j] = $matriz->rows[$i];
                                                                            $matriz->rows[$i] = $arr_a;
                                                                    }
                                                            }
                                                            break;
                                                    case "TIM":
                                                            if(strtoupper($tipo_order)=="ASC"){
                                                                    if((float)str_replace(' ','',str_replace(':','',str_replace('-','',$matriz->rows[$i]['cell'][$campo])))>
                                                                       (float)str_replace(' ','',str_replace(':','',str_replace('-','',$matriz->rows[$j]['cell'][$campo])))){
                                                                            $arr_a = $matriz->rows[$i];
                                                                            $matriz->rows[$i] = $matriz->rows[$j];
                                                                            $matriz->rows[$j] = $arr_a;
                                                                    }
                                                            }else{
                                                                    if((float)str_replace(' ','',str_replace(':','',str_replace('-','',$matriz->rows[$i]['cell'][$campo])))<
                                                                       (float)str_replace(' ','',str_replace(':','',str_replace('-','',$matriz->rows[$j]['cell'][$campo])))){
                                                                            $arr_a = $matriz->rows[$j];
                                                                            $matriz->rows[$j] = $matriz->rows[$i];
                                                                            $matriz->rows[$i] = $arr_a;
                                                                    }
                                                            }
                                                            break;
                                            } // fin switch //
                                    } // fin for second //
                            } // fin for principal //
                            /* verificamos si existe el parametro del order by para dividir */
                            if(isset($_REQUEST['sidx'])){
                                    if(utilidades::verifica_campo_fk($_REQUEST['sidx'])){
                                            $start = $_REQUEST['rows']*$_REQUEST['page'] - $_REQUEST['rows'];
                                            if($start<0){
                                                    $start = 0;
                                            }
                                            /* cortamos el array en el limit escojido */
                                            $matriz->rows = array_slice($matriz->rows,$start,$_REQUEST['rows']);
                                    }
                            }
                    }

                    return $matriz;
            }catch(Exception $e){
                    return false;
            }
        }
        /* verificamo si el campo es un campo fk de la grilla */
        static function verifica_campo_fk($campo){
                try{
                        /** verificamos q el campo no sea una fk */
                        $ind_p = (strrpos($campo,"_")+1);
                        /* substr */
                        $str_fk = substr($campo,$ind_p,2);
                        if($str_fk=="fk"){
                                return true;
                        }else{
                                return false;
                        }
                }catch(Exception $e){
                        return false;
                }
        }
        /* filtrar campos fk */
        static function filtrar_campos_fk($matriz){
                $filters_json = $_REQUEST['filters'];
                if($filters_json == ''){            
                        return;        
                }        
                $arrFilters = json_decode(str_replace('\\','',$filters_json));        
                if(!isset($arrFilters->rules)){    
                        return;        
                }
                $arr_campos = array();
                /* sacamos las columnas q se filtraran */
                for($i=0;$i<count($arrFilters->rules);$i++){
                        /* verificamos cuales son los campos fk */
                        if(utilidades::verifica_campo_fk($arrFilters->rules[$i]->field)){
                                /** verificamos q el campo no sea una fk */
                                $ind_p = (strrpos($arrFilters->rules[$i]->field,"_")+1);
                                /******************************************/
                                /* obtenemos el indice de la columna ******/
                                $str_fk = substr($arrFilters->rules[$i]->field,$ind_p+2,2);
                                /**/
                                $str_fk = (is_numeric($str_fk)?$str_fk:substr($arrFilters->rules[$i]->field,$ind_p+2,1));
                                array_push(
                                        $arr_campos,
                                        array(
                                                $str_fk,
                                                $arrFilters->rules[$i]->field,
                                                $arrFilters->rules[$i]->op,
                                                $arrFilters->rules[$i]->data
                                        )
                                );
                        }
                }
                /* verificamos */
                if(is_array($arr_campos) && count($arr_campos)==0){
                        return $matriz;
                }
                $new_matriz->rows = array();
                /* recorremos la matriz */
                for($i=0;$i<count($matriz->rows);$i++){
                        $bol_cumple = false;
                        /* recorremos el array de reglas para validar */
                        for($j=0;$j<count($arr_campos);$j++){
                                switch($arr_campos[$j][2]){
                                        case "eq":                        
                                                if(strtolower($matriz->rows[$i]['cell'][$arr_campos[$j][0]]) == strtolower($arr_campos[$j][3])){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'ne':                        
                                                if(strtolower($matriz->rows[$i]['cell'][$arr_campos[$j][0]]) != strtolower($arr_campos[$j][3])){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'lt':
                                                if($matriz->rows[$i]['cell'][$arr_campos[$j][0]] < $arr_campos[$j][3]){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'le':
                                                if($matriz->rows[$i]['cell'][$arr_campos[$j][0]] <= $arr_campos[$j][3]){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'gt':
                                                if($matriz->rows[$i]['cell'][$arr_campos[$j][0]] > $arr_campos[$j][3]){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'ge':
                                                if($matriz->rows[$i]['cell'][$arr_campos[$j][0]] >= $arr_campos[$j][3]){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'bw':
                                                $leng = strlen($arr_campos[$j][3]);
                                                if(substr($matriz->rows[$i]['cell'][$arr_campos[$j][0]],$leng) == $arr_campos[$j][3]){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'bn':
                                                $leng = strlen($arr_campos[$j][3]);
                                                if(substr($matriz->rows[$i]['cell'][$arr_campos[$j][0]],$leng) != $arr_campos[$j][3]){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'in':
                                                if(strtolower($matriz->rows[$i]['cell'][$arr_campos[$j][0]]) == strtolower($arr_campos[$j][3])){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'ni':
                                                if(strtolower($matriz->rows[$i]['cell'][$arr_campos[$j][0]]) != strtolower($arr_campos[$j][3])){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'ew':
                                                $leng = (strlen($arr_campos[$j][3])-(strlen($arr_campos[$j][3])*2));
                                                if(substr($matriz->rows[$i]['cell'][$arr_campos[$j][0]],$leng) == $arr_campos[$j][3]){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'en':
                                                $leng = (strlen($arr_campos[$j][3])-(strlen($arr_campos[$j][3])*2));
                                                if(substr($matriz->rows[$i]['cell'][$arr_campos[$j][0]],$leng) != $arr_campos[$j][3]){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'cn':
                                                if(strpos($matriz->rows[$i]['cell'][$arr_campos[$j][0]],$arr_campos[$j][3])!==false){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'nc':
                                                if(strpos($matriz->rows[$i]['cell'][$arr_campos[$j][0]],$arr_campos[$j][3])===false){
                                                        $bol_cumple = true;
                                                }
                                                break;                    
                                        case 'nu':                        
                                                break;                    
                                        case 'nn':                        
                                                break;                
                                } /** fin switch */
                        } /* fin for interno */
                        if($bol_cumple==true){
                                $new_matriz->rows[] = $matriz->rows[$i];
                        }
                } /** fin for externo */
                if( count($new_matriz->rows) >0 ) { 
                        $total_pages = ceil(count($new_matriz->rows)/$_REQUEST['rows']);
                } else {
                        $total_pages = 0;
                }
                $page = $_REQUEST['page'];
                if ($page > $total_pages) 
                        $page=$total_pages;
                $new_matriz->page = $page; 
                $new_matriz->total = $total_pages; 
                $new_matriz->records = count($new_matriz->rows);

                return $new_matriz;
        }

        /****/
        static function clean_field($dato,$v_return=''){
                $v_return = ($v_return=='NULL'||$v_return=='null'||$v_return==null?'':$v_return);
                return $dato=='NULL'||$dato=='null'||$dato==null ? $v_return : $dato ;
        }


        /**guarda y transporte variables mediante arrays de session*/
        static function Var_Global($nombrevar,$datos=null){
                if(!is_null($datos)){
                        $_SESSION[$_SESSION['_SFT_NAME_']]['arrVariablesDatos'][$nombrevar]=$datos;
                }
                else{
                        if(isset($_SESSION[$_SESSION['_SFT_NAME_']]['arrVariablesDatos'][$nombrevar])){
                                return $_SESSION[$_SESSION['_SFT_NAME_']]['arrVariablesDatos'][$nombrevar];
                        }
                        else{
                                return null;
                        }
                        //return $_SESSION[$_SESSION['_SFT_NAME_']]['arrVariablesDatos'][$nombrevar];
                }
        }

        /**retorna el nombre del dao/tabla quitando namesapace y palabra reservada dao**/
        static function get_str_name_table($str_class){
                $str_end = substr($str_class,strrpos($str_class,"\\")+1);
                return ($str_end);
        }

        static function begin_script(){
                $_SESSION['objCnx'] = null;
        }

        /***/
        static function commit_script(){
                unset($_SESSION['objCnx']);
        }
        
        static function time_start() {
            global $starttime;
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $starttime = $mtime; 
        }	

        static function time_end() {
            global $starttime;
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            return ($mtime - $starttime); 
        }
        
        /***********************************************************************/
        /***********************************************************************/
        static function get_date_sum_res_dias($fecha,$cant_d=1){
            if(strlen($fecha)>10){
                    $fecha = substr($fecha,0,10);
            }
            $fecha = $fecha; 

            $oper = '';
            if((int)$cant_d>0){
                $oper = '+';
            }

            $nuevafecha = strtotime ( $oper.$cant_d.' day' , strtotime ( $fecha ) ) ;
            return date ( 'Y-m-d' , $nuevafecha );
        }
    
    }
}
