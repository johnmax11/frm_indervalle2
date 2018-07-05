<?php
namespace MiProyecto{
    if(!isset($_SESSION)){
        session_Start();
    }
    error_reporting(E_ALL);
    class Conexion{
        var $objResult=null;
        public $objCnx = null;
        var $_bolDebug=true;
        public $_msj_error;
        private $_sql;
        public $_bol_upd = null;
        private $_arr_cols_update = array();
        private $_strtabla = null;
        private $_count_select_rows_grilla = 0;
        public $_bol_cache_query = true;
				
        public function Conexion(){
            $this->_count_select_rows_grilla = 0;
            require_once (dirname(dirname(__FILE__)).'/configuration/config.php');
            /***/
            if(
                (isset($this->objCnx) && $this->objCnx!=null) || 
                (isset($_SESSION['objCnx']) && $_SESSION['objCnx']!=null)
            ){
                if(isset($this->objCnx) && $this->objCnx!=null){
                    $this->objCnx = $this->objCnx;
                }else{
                    if(isset($_SESSION['objCnx']) && $_SESSION['objCnx']!=null){
                        $this->objCnx = $_SESSION['objCnx'];
                    }
                }
            }
            else{
                $cx = mysqli_connect(_HOST_CX_, _USER_CX_, _PASS_CX_,_BD_NAME_CX_);
                if(!$cx){
                        die ('No se pudo abrir la conexion '._BD_NAME_CX_.' : ' . mysqli_error($cx).'&nbsp;<img src="themes/Default/images/warning.png" />');
                        exit;
                }
               /***/
               $_SESSION['objCnx'] = $this->objCnx = $cx;
               //$_SESSION['lastID_log_sql'] = $this->insert_log_querys_principal();
               $bolResult = mysqli_select_db($cx,_BD_NAME_CX_);
               if (!$bolResult) {
                    die ('No se pudo conectar '._BD_NAME_CX_.' : ' . mysqli_error($cx).'&nbsp;<img src="themes/Default/images/warning.png" />');
                    exit;
               }
               /**set names utf8**/
               $this->fnQuery('SET NAMES utf8');
            }
            if(!$this->objCnx){
                die('Error:'. mysql_error());
                exit;
            }
        }
        public function fnQuery($strSQL=null,$strTipo=null,&$objResult=array(),$bolDebug=false){
            $this->objResult = array();
            $objResult = array();
            if(!$strSQL || $strSQL == ''){
                $this->fnQuery('ROLLBACK');
                throw new \Exception ('Error: No se a enviado el query a ejecutar, proceso abortado &nbsp;<img src="themes/Default/images/warning.png ---> '.$this->_msj_error);
                return false;
            }
            /**verificamos si el query esta cacheado en el proceso*/
            $res_cache = -2;
            if($this->_bol_cache_query){
                $res_cache = $this->verificar_objeto_cache($strSQL);
                if($res_cache!=-1 && $res_cache!=-2){
                    $objResult = $this->objResult = $res_cache;
                    $result = true;
                }
            }
            /***/
            if($res_cache<0){
                $result = $this->fnEjecutaQuery($strSQL,$bolDebug);
                if($result && $strTipo != null){
                    $this->fnObtieneResult($result,$strTipo);
                    $objResult = $this->objResult;
                    /**set cache result*/
                    if($res_cache==-1){
                            array_push($_SESSION['querys_cache']['result'],$objResult);
                    }
                }
            }

            // verificamos si hizo un update //
            if($this->_bol_upd!=null){
                $this->set_insert_audit();
            }

            // flat update //
            $this->_bol_upd = null;
            return $result;
        }
        private function fnEjecutaQuery($strQuery,$bolDebug=false){
            /// verificamos si hay debug
            if($bolDebug){
                $this->fnDebug($strQuery);
            }
            //echo '-->aca-->bds-->'._BD_NAME_CX_;
            // Ejecutar Consulta
            mysqli_select_db($this->objCnx,_BD_NAME_CX_);
            //echo $strQuery;
            //$this->time_start();
            $result = mysqli_query($this->objCnx,$strQuery);
            if(!$result){
                $this->_msj_error = mysqli_error($this->objCnx);
                // inser log errores //
                require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/utils/log_errores/log_errores.php");
                $obj_log_errores = new log_errores_sys();
                $error = debug_backtrace();
                echo $this->_msj_error.'==>'.$strQuery;
                $obj_log_errores->crear_errores_bd($error);
                ///////////////////////
                $this->rollback();
                throw new \Exception ('Error: Ocurrio un error en la ejecuci&oacute;n de una consulta ---> '.($this->_bolDebug?'<br/>'.$this->_msj_error:'').'&nbsp;<img src="themes/Default/images/warning.png" /><br/><br/>'.'<pre>'.($this->_bolDebug?$strQuery:'').'</pre>');
            }
            /**insert log querys*/
            //$this->insert_log_querys_users($strQuery, $this->time_end());
            
            return $result;
        }
        public function fnObtieneResult($result,$strTipo='OBJECT'){
            switch($strTipo){
                case 'OBJECT':
                    $this->fnSetResultObject($result);
                    break;
                case 'ARRAY':
                    $this->fnSetResultArray($result);
                    break;
                case 'ASSOC':
                    $this->fnSetResultAssoc($result);
                    break;
            }
        }
        private function fnSetResultObject($result){
                $cont = 0;
                $this->objResult = array();
                while ($row = mysqli_fetch_object($result)){
                        $this->objResult[$cont] = $row;
                        $cont++;
                }	
        }
        private function fnSetResultArray($result){
            while ($row = mysqli_fetch_array($result,MYSQL_NUM)) {
                $this->objResult[] = $row;
            }	
        }
        private function fnSetResultAssoc($result){
            while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
                $this->objResult[] = $row;
            }	
        }
        public function fnCloseConexion(){
            mysqli_close($this->objCnx);	
        }
        public function fnDebug($strSQL){
            echo "<pre>Query: ".date('Y-m-d H:i:s').' - '.print_r($strSQL,true)."</pre><br/>";	
        }
        public function begin(){
            $_SESSION['querys_cache']['timestamp'] = utilidades::get_current_timestamp();
            return $this->fnQuery('BEGIN');
        }
        public function commit(){
            return $this->fnQuery('COMMIT');
        }
        public function rollback(){
            return $this->fnQuery('ROLLBACK');
        }

        /***/
        public function order_by($col='id',$tipo='ASC'){
            return " ORDER BY ".$col.' '.$tipo;
        }

        /***/
        public function limit($limit){
            return " LIMIT ".$limit;
        }
		
        /***/
        public function get_columnas_by_tabla($tabla=null){
            if($tabla==null){
                    return '';
            }
            $sql = "DESCRIBE ".$tabla;
            // execute //
            $this->fnQuery($sql,'ASSOC',$arrDatos);
            // recorremos //
            $cols = '';
            for($i=0;$i<count($arrDatos);$i++){
                foreach ($arrDatos[$i] as $key => $value) {
                    if($key == 'Field'){
                        $cols .= "".$value.",";
                    }
                }
            }
            $cols = substr($cols,0,strlen($cols)-1);
            return $cols;
        }

        /**/
        public function get_last_insert_id(){
            return mysqli_insert_id($this->objCnx);
        }

        /**/
        public function delete($strtabla,$arrCols = array()){
            if($strtabla=='' || $strtabla==null){
                $this->_msj_error = "Nombre de tabla esta vacio";
                throw new \Exception ('Error: '.$this->_msj_error);
                return false;
            }
            if(!is_array($arrCols) || count($arrCols)==0){
                $this->_msj_error = "Array de columnas esta vacio";
                throw new \Exception ('Error: '.$this->_msj_error);
                return false;
            }
            //
            $this->_sql = "";
            $this->_sql .= " DELETE FROM ".$strtabla;
            $this->_sql .= " WHERE 1=1 ";
            foreach($arrCols as $clave => $valor){
                    // conultamos el tipo de dato de la columna en la tabla //
                    $tipo = $this->get_tipo_dato_by_columna($clave,$strtabla);
                    switch($tipo){
                            case "int":
                            case "real":
                                    $valor = mysqli_real_escape_string($this->objCnx,$valor)."";
                                    break;
                            default:
                                    $valor = "'".mysqli_real_escape_string($this->objCnx,$valor)."'";
                    }
                    $this->_sql .= " AND ".$clave." = ".$valor." ";
            }
            $this->_sql = substr($this->_sql,0,strlen($this->_sql)-1);
            return $this->_sql;
        }

        /**/
        public function update($strtabla,$arrCols = array(),$id=null){
            try{
                if($strtabla=='' || $strtabla==null){
                    $this->_msj_error = "Nombre de tabla esta vacio";
                    throw new \Exception ('Error: '.$this->_msj_error);
                }
                if(!is_array($arrCols) || count($arrCols)==0){
                    $this->_msj_error = "Array de columnas esta vacio";
                    throw new \Exception ('Error: '.$this->_msj_error);
                }

                //
                $this->_sql = " UPDATE ".$strtabla." SET ";
                foreach($arrCols as $clave => $valor){
                    if($clave=='id'){
                        continue;
                    }
                    // conultamos el tipo de dato de la columna en la tabla //
                    $tipo = "varchar";//$this->get_tipo_dato_by_columna($clave,$strtabla);

                    switch($tipo){
                        case "int":
                        case "bigint":
                        case "real":
                        case "decimal":
                            $valor = mysqli_real_escape_string($this->objCnx,$valor)."";
                            break;
                        default:
                            $valor = "'".mysqli_real_escape_string($this->objCnx,$valor)."'";
                    }
                    $this->_sql .= " ".$clave." = ".$valor.",";
                }
                $this->_sql .= "modificado_por = '".utisetVarSession::get_ssn_id_users()."',";
                $this->_sql .= "fecha_modificacion = '".(isset($_SESSION['querys_cache']['timestamp'])?$_SESSION['querys_cache']['timestamp']:utilidades::get_current_timestamp())."',";
                $this->_sql = substr($this->_sql,0,strlen($this->_sql)-1);

                $sql = " WHERE ";
                if(!is_array($id)){
                    $sql .= " id = ".$id." ";
                }else{
                    foreach($id as $clave => $valor){
                        // conultamos el tipo de dato de la columna en la tabla //
                        $tipo = "varchar";//$this->get_tipo_dato_by_columna($clave,$strtabla);

                        switch($tipo){
                            case "int":
                            case "bigint":
                            case "real":
                                $valor = mysqli_real_escape_string($this->objCnx,$valor)."";
                                break;
                            default:
                                $valor = "'".mysqli_real_escape_string($this->objCnx,$valor)."'";
                        }
                        $sql .= ' '.$clave." = ".$valor." AND";
                    }
                    $sql = substr($sql,0,strlen($sql)-3);
                }
                // consultamos los datos before //
                $this->fnQuery("SELECT * FROM ".$strtabla." ".$sql,"OBJECT",$this->_arr_cols_update['result_before']);
                $this->_arr_cols_update['cols'] = $arrCols;
                $this->_arr_cols_update['id'] = $id;
                $this->_bol_upd = $strtabla;
                $this->_strtabla = $strtabla;
                return $this->_sql.$sql;
            }catch(\Exception $e){
                throw new \Exception ($e->getMessage());
            }
        }

        /***/
        public function insert($strtabla,$arrCols = array()){
            try{
                if($strtabla=='' || $strtabla==null){
                    $this->_msj_error = "Nombre de tabla esta vacio";
                    throw new \Exception ('Error: '.$this->_msj_error);
                }
                //
                $this->_sql = "";
                $this->_sql .= " INSERT INTO ".$strtabla." ( ";
                foreach($arrCols as $clave => $valor){
                    if($clave=='id'){
                        continue;
                    }
                    $this->_sql .= " ".$clave.",";
                }
                $this->_sql .= " creado_por,";
                $this->_sql .= " fecha_creacion,";
                $this->_sql = substr($this->_sql,0,strlen($this->_sql)-1);
                $this->_sql .= " )";
                $this->_sql .= " VALUES( ";
                foreach($arrCols as $clave => $valor){
                    if($clave=='id'){
                        continue;
                    }
                    // conultamos el tipo de dato de la columna en la tabla //
                    $tipo = "varchar";//$this->get_tipo_dato_by_columna($clave,$strtabla);
                    switch($tipo){
                        case "int":
                        case "real":
                            $valor = mysqli_real_escape_string($this->objCnx,$valor)."";
                            break;
                        default:
                            $valor = "'".mysqli_real_escape_string($this->objCnx,$valor)."'";
                    }
                    $this->_sql .= "".$valor.",";
                }
                $this->_sql .= "'".utisetVarSession::get_ssn_id_users()."',";
                $this->_sql .= "'".(isset($_SESSION['querys_cache']['timestamp'])?$_SESSION['querys_cache']['timestamp']:utilidades::get_current_timestamp())."',";
                $this->_sql = substr($this->_sql,0,strlen($this->_sql)-1);
                $this->_sql .= " )";
                //echo $this->_sql;
                return $this->_sql;
            }catch(\Exception $e){
                throw new \Exception ($e->getMessage());
            }
        }

        /***/
        public function select($arr_cols_select,$strtabla,$arrCols = array(),$bol_str_sel=false){
            /**quitamos la palabra dao_ */
            $strtabla = substr($strtabla,4);
            /** verificamos si es un select */
            if($bol_str_sel==false){
                if($strtabla=='' || $strtabla==null){
                    $this->_msj_error = "Nombre de tabla esta vacio";
                    throw new \Exception ('Error: '.$this->_msj_error);
                    return false;
                }
                if(!is_array($arrCols)){
                    $this->_msj_error = "Array de columnas esta vacio";
                    throw new \Exception ('Error: '.$this->_msj_error);
                    return false;
                }
                $this->_sql = "";
                $this->_sql .= $this->sql_select_all($arr_cols_select);
                $this->_sql .= " FROM ";
                $this->_sql .= "    ".$strtabla." ";
                $this->_sql .= " WHERE 1=1 ";
            }else{
                $this->_sql = $strtabla;
            }
            foreach($arrCols as $clave => $valor){
                // conultamos el tipo de dato de la columna en la tabla //
                if($bol_str_sel==false){
                    $tipo = "varchar";//$this->get_tipo_dato_by_columna($clave,$strtabla);
                }else{
                    $tipo = "varchar";
                }
                switch($tipo){
                    case "int":
                    case "real":
                    case "decimal":
                        $this->_sql .= $this->get_condicion_where($clave,$valor);
                        break;
                    case "datetime":
                    case "timestamp":
                        $this->_sql .= $this->get_condicion_where($clave,$valor,true,true);
                        break;
                    default:
                        $this->_sql .= $this->get_condicion_where($clave,$valor,true,true);
                }

            }
            return $this->_sql;
        }
		
        /***/
        private function get_condicion_where($col,$arrcols,$strint=false,$boltmtmp=false){
            $sql = "";
            if(is_array($arrcols)){
                for($i=0;$i<count($arrcols);$i++){
                    if($i%2==0 || $i==0){
                        $sql .= " AND ".$col."  >= ".$this->get_commas($strint)."".mysqli_real_escape_string($this->objCnx,$arrcols[$i])."".$this->get_extras_timestmp($boltmtmp,$i).$this->get_commas($strint);
                    }else{
                        $sql .= " AND ".$col." <= ".$this->get_commas($strint)."".mysqli_real_escape_string($this->objCnx,$arrcols[$i])."".$this->get_extras_timestmp($boltmtmp,$i).$this->get_commas($strint);
                    }
                }
            }else{
                $arr_r = explode("||",$arrcols);
                if(is_array($arr_r) && count($arr_r)>1){
                    if($arr_r[1]=='IN' || $arr_r[1]=='NOT IN'){
                        $sql .= " AND UPPER(".$col.") ".$arr_r[1]." (";
                        $arr_r[2] = explode(',',$arr_r[2]);
                        foreach($arr_r[2] as $clave_e => $valor_e){
                                $sql .= " (UPPER(".$this->get_commas($strint).mysqli_real_escape_string($this->objCnx,$valor_e).$this->get_commas($strint).")),";
                        }
                        $sql = substr($sql,0,strlen($sql)-1);
                        $sql .= ")";
                    }else{
                        $sql .= " AND UPPER(".$col.") ".$arr_r[1]." (UPPER(".$this->get_commas($strint).mysqli_real_escape_string($this->objCnx,$arr_r[2]).$this->get_commas($strint)."))";
                    }
                }else{
                    $sql .= " AND ".$col." = ".$this->get_commas($strint)."".mysqli_real_escape_string($this->objCnx,$arrcols)."".$this->get_commas($strint);
                }
            }
            return $sql;
        }
        /***/
        private function get_extras_timestmp($boltmtmp,$ind){
            if($boltmtmp==false){
                return "";
            }
            if($ind % 2 == 0 || $ind==0){
                return " 00:00:00";
            }else{
                return " 23:59:59";
            }
        }

        /***/
        private function get_commas($strint){
                return ($strint?"'":"'");
        }

        /**/
        private function get_tipo_dato_by_columna($col,$tabla){
            $result = 
                    $this->fnEjecutaQuery("
                                          /*qc=on*/SELECT
                                                  ".$col."
                                          FROM
                                                  ".$tabla."
                                          WHERE
                                                  1=2
                                   ");
            $info_campo = $result->fetch_fields();
            foreach ($info_campo as $val) {
                    return $val->type;
            }
        }

        public function sql_select_all($arr_cols_select){
            $str_cols_s = "/*qc=on*/SELECT";
            for($i=0;$i<count($arr_cols_select);$i++){
                $str_cols_s .= " ".$arr_cols_select[$i].",";
            }
            return substr($str_cols_s,0,strlen($str_cols_s)-1);
        }

        /***/
        private function verifica_tabla_audit($tabla){
                $arrRsp = array();
                $sql  = " SELECT COUNT(*) AS contador ";
                $sql .= " FROM information_schema.tables";
                $sql .= " WHERE table_schema = '"._BD_NAME_CX_."' AND ";
                $sql .= "       table_name = '".$tabla."_auditorias'";
                $this->fnQuery($sql,'OBJECT',$arrRsp);
                return ($arrRsp[0]->contador>0?true:false);
        }
        /**
         * 
         */
        public function sql_extra_grilla_order_by($sidx,$sord){
                return " ORDER BY $sidx $sord ";
        }
        
        public function group_by_sql($campos){
            return " GROUP BY ".$campos." ";
        }
        
        /**
         * 
         */
        public function sql_extra_grilla_limit($start,$limit){
                /* verificamos lacolumna de order by */
                if(isset($_REQUEST['sidx'])){
                        /** verificamos q el campo no sea una fk */
                        $ind_p = (strrpos($_REQUEST['sidx'],"_")+1);
                        /* substr */
                        $str_fk = substr($_REQUEST['sidx'],$ind_p,2);
                        if($str_fk=="fk"){
                                return "";
                        }
                }

                return " LIMIT $start , $limit";
        }

        /***/
        public function verifica_col_tabla($tabla,$campo){
                /** verificamos q el campo no sea una fk */
                $ind_p = (strrpos($campo,"_")+1);
                /* substr */
                $str_fk = substr($campo,$ind_p,2);
                if($str_fk=="fk"){
                        return false;
                }

                $sql = " SHOW COLUMNS FROM ".$tabla." LIKE '".mysqli_real_escape_string($this->objCnx,$campo)."' ";
                $this->fnQuery($sql,'OBJECT',$arrRsp);
                return (count($arrRsp)>0?true:false);
        }

        /***/
        function set_insert_audit(){
                $strtabla = $this->_strtabla;
                $this->_bol_upd = null;
                if($this->verifica_tabla_audit($strtabla)){
                        // buscamos las columna id //
                        $id = $this->_arr_cols_update['id'];
                        if(is_array($id)){
                                foreach($id as $clave => $valor){
                                        if($clave=='id'){
                                                $id = $valor;
                                                break;
                                        }
                                }
                        }
                        foreach($this->_arr_cols_update['cols'] as $clave => $valor){
                                if($clave=='id'){
                                        continue;
                                }
                                // buscamos el valor de la columna before //
                                $b_value = $this->get_valor_before_in_array($this->_arr_cols_update['result_before'],$clave);
                                // hacemos el insert en la tabla auditorias //
                                $sql = $this->insert(
                                            $strtabla.'_auditorias',
                                            array(
                                                 'parent_id'=>$id,
                                                 'columna'=>$clave,
                                                 'before_value'=>$b_value,
                                                 'after_value'=>$valor 
                                           )
                                        );
                                // execute //
                                $this->fnQuery($sql);
                        }
                        $this->_arr_cols_update = array();
                }
        }
        /***/
        private function get_valor_before_in_array($array,$col){
                foreach ($array[0] as $key => $value) {
                        if($key == $col){
                                return $value;
                        }
                }
        }

        /**verifica si la consulta esta cacheada*/
        private function verificar_objeto_cache($strSQL){
            if(isset($_GET['sord'])){
                //echo '---->'.substr($strSQL,0,15);
            }
            if(isset($strSQL) && 
                (strtolower(substr($strSQL,0,6))=='select' || substr($strSQL,0,15)=='/*qc=on*/SELECT' ||substr($strSQL,0,8) == 'DESCRIBE')
            ){
                if(!isset($_SESSION['querys_cache']['sql'])){
                    $_SESSION['querys_cache']['sql'] = array();
                    $_SESSION['querys_cache']['result'] = array();
                }
                if(!in_array($strSQL,$_SESSION['querys_cache']['sql'])){
                    array_push($_SESSION['querys_cache']['sql'],$strSQL);
                    return -1;
                }else{
                    $clave = array_search($strSQL,$_SESSION['querys_cache']['sql']);
                    //if(isset($_GET['sord'])){
                            //echo '<pre>'.print_r($_SESSION['querys_cache']['sql'],true).'</pre>';
                            //echo '<pre>'.print_r($_SESSION['querys_cache']['result'],true).'</pre>';
                    //}
                    return $_SESSION['querys_cache']['result'][$clave];
                }
            }
            return -2;
        }
                
        private function time_start() {
            global $starttime;
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $starttime = $mtime; 
            return $starttime;
        }	

        private function time_end() {
            global $starttime;
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            return ($mtime - $starttime); 
        }
        
        /****/
        private function insert_log_querys_principal(){
            if(utisetVarSession::get_ssn_empresa_id()==null){
                return;
            }
            try{
                $sql = "INSERT INTO log_querys_principal(
                            empresas_id,
                            empresas_oficinas_id,
                            creado_por,
                            fecha_creacion
                        )VALUES(
                            ".utisetVarSession::get_ssn_empresa_id().",
                            ".utisetVarSession::get_ssn_oficina_id().",
                            ".utisetVarSession::get_ssn_id_users().",
                            '".(isset($_SESSION['querys_cache']['timestamp'])?$_SESSION['querys_cache']['timestamp']:utilidades::get_current_timestamp())."'
                        )";
                $result = mysqli_query($this->objCnx,$sql);
                if(!$result){
                    echo mysqli_error($this->objCnx);
                }
                return $this->get_last_insert_id();
            }catch(\Exception $ex){
                echo $ex->getMessage();
            }
        }
        
        /***/
        private function insert_log_querys_users($query,$t_end){
            if(utisetVarSession::get_ssn_empresa_id()==null){
                return;
            }
            try{
                $sql = "
                    INSERT INTO
                        log_querys_users(
                            log_querys_principal_id,
                            query_txt,
                            time_end,
                            creado_por,
                            fecha_creacion
                        )VALUES(
                            ".$_SESSION['lastID_log_sql'].",
                            '".addslashes($query)."',
                            ".$t_end.",
                            ".utisetVarSession::get_ssn_id_users().",
                            '".(isset($_SESSION['querys_cache']['timestamp'])?$_SESSION['querys_cache']['timestamp']:utilidades::get_current_timestamp())."'
                        )
                    ";
                $result = mysqli_query($this->objCnx,$sql);
                if(!$result){
                    echo mysqli_error($this->objCnx).$sql;exit;
                }
            }catch(\Exception $ex){
                echo $ex->getMessage();
            }
        }
    }
}
