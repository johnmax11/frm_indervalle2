<?php     
if(!isset($_SESSION)){        
    session_start();    
}    
ini_set('error_reporting', E_ALL);    
ini_set('display_errors',1);
set_time_limit(0);
ini_set('memory_limit','256M');
require_once('aplication/configuration/config_ini.php');
if(!isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS'])){
    if(isset($_REQUEST['module']) || isset($_REQUEST['action'])){
        header('location: http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'?')));
    }
}else{
    if(!isset($_REQUEST['module']) || !isset($_REQUEST['action'])){
        header('location: index.php?module=Home&action=Home');
    }
}

require_once('aplication/configuration/config_ssn.php');        
$style = (!isset($_COOKIE['style'])?$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['ESTILO_DEFAULT']:$_COOKIE['style']);
if(isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ESTILO'])){        
    $style = $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ESTILO'];    
}    
$arrStyle = explode(' ',$style);    
$style = '';    
for($i=0;$i<count($arrStyle);$i++){        
    $style .= strtolower($arrStyle[$i]).'-';    
}    
$style = substr($style,0,strlen($style)-1);
setcookie("style",$style);
?>
<!DOCTYPE html>
	<html>
            <head>    
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <link rel="icon" type="image/png" href="images/mifavicon_icon.png" />
                <title>.:: Variedades Fam ::.</title>
                <link type="text/css" href="css/<?php echo $style; ?>/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
                <link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
                <style>
                    .ui-autocomplete-loading { background: white url('images/ui-anim_basic_16x16.gif') right center no-repeat; }
                </style>

                <script src="js/default/jquery-1.11.1.js" type="text/javascript"></script>
                <script src="js/default/jquery-ui-1.10.0.custom.js" type="text/javascript"></script>
                <script src="js/i18n/grid.locale-es.js" type="text/javascript"></script>
                <script src="js/default/jquery.jqGrid.min.js" type="text/javascript"></script>
	        <script src="js/index/index.js" type="text/javascript"></script>
            </head>
            <body>
                <div id="divBody" style="width:100%;" align="center">
                    <?php    
                        /**       incluimos el archivo del head    **/
                        require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/views/Utils/visHead.php");
                        if(isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS'])){
                            /*echo "<script src='js/utilidades/AccDir/indexAccDir.js' type='text/javascript'></script>";*/
                            //require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/views/Utils/visHistorial.php');
                        }
                        echo "<script>var _PATH_SITE_ = '".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_SITE']."'; </script>"; 
                        echo "<script>var _PATH_WEB_ = '".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."'; </script>";
                    ?>
			<div id="divContent" style="width:98%;" align="center">
				<p>            
					<table align="center" width="100%">
						<tr>
							<?php
								if(isset($_GET['action']) && $_GET['action']!='Login'){
							?>
									<td valign="top" id="tdMenuIndex">
										<?php
											require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/views/Utils/visMenu.php");
										?>
									</td>
							<?php
								}
							?>                    
							<td width="85%" valign="top">
                                                            <input type="hidden" id="hdnmodule" name="hdnmodule" value="<?php echo isset($_GET['module'])?$_GET['module']:''; ?>"/>
                                                            <input type="hidden" id="hdnaction" name="hdnaction" value="<?php echo isset($_GET['action'])?$_GET['action']:''; ?>"/>
                                                            <div style="width:100%;"></div>
                                                            <?php
                                                                if(!isset($_GET['module']) || !isset($_GET['action'])){
                                                                    $_GET['module'] = 'Usuarios';
                                                                    $_GET['action'] = 'Login';
                                                                }
                                                                if(file_exists($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/views/".$_GET['module']."/vis".$_GET['action'].".php")){
                                                                    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/views/".$_GET['module']."/vis".$_GET['action'].".php");
                                                                }else{
                                                                    echo "<label style='color:red'>Programa No Existe!</label>";
                                                                }
                                                            ?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<div id="divFooter" style="width:100%;">
									<p>
										<?php
											/** incluimos el archivo del footer **/
											require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/views/Utils/visFooter.php");
										?>
									</p>
								</div>
							</td>
						</tr>            
					</table>
				</p>
			</div>
		   </div>
		</body>
</html>