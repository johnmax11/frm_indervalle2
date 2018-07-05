<?php
    namespace MiProyecto;
    if(!isset($_SESSION)){        	
        session_start();
    }
?> 
<!DOCTYPE html> 
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache"/>
    <script>    	
            var gblSubmit=0;    	
            var gblSalir=0;    	
            /*if (window.history){*/		
                    /*function noBack(){			
                            window.history.forward()		
                    }             		
                    noBack();             		
                    window.onload=noBack;            		
                    window.onpageshow = function(evt){									
                                                                    if(evt.persisted){															
                                                                            noBack();
                                                                    }											
                                                            };						
                    window.onunload=function(){
                                                            void(0);						
                                                    }   
                    */	
            /*}*/	
            /*if (history.forward(1)){		
                    /*location.replace(history.forward(1))  */	
            /*}*/
    </script>
    <style>    	
            * {        		
                    font-family: Arial,"Microsoft Sans Serif","Lucida Sans Unicode";        		
                    line-height: normal;        
                    margin: 0;        
                    padding: 0;    	
            }    	
            body {       		
                    font-size: 8pt;
                    line-height: 18px;        
                    margin: 0px;        
                    padding: 0;    	
            }
    </style>
	<div id="divPrincipal"  style="width: 100%" >   	
            <div>        		
                <div id="header_navigation" class="ui-state-active" style="padding:5px;">            			
                    <div id="top_navigation" align="left" style=" width:70%;">              				
                        <span>					
                            <img src="images/acercade.png" title="Acerca de"/>				
                        </span>              				
                        <a href="javascript:void(0)" onclick='fnOpenAcercaDe();' title="Acerca de" style="margin:5px; border:0px; text-decoration:none;"> 					
                            Acerca de              				
                        </a>              				
                        &nbsp;|&nbsp;               				
                        <span>					
                            <img src="images/offline.png" alt="Cerrar sesion" title="Cerrar sesion" />				
                        </span>              				
                            <a  href="<?php
                                    echo $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_SITE'].'/views/Utils/utiCerrarSession.php';
                                ?>" 
                                title="Cerrar sesion" 
                                style="margin:5px; border:0px; text-decoration:none;"
                            >
                                Salir              				
                            </a>              				
                            &nbsp;&nbsp;|&nbsp;&nbsp;              				
                            <?php               					
                                if(!isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS'])){              				
                            ?>                    					
                                    <img src="images/offline2.png" title="No ha iniciado session"/>              				
                            <?php                					
                                }else{
                            ?>        
                                    <img src="images/online.png" title="Usuario en session"/>
                                    Bienvenido: 						
                                    <i style='color:red;'>							
                                        <?php echo (isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['USUARIO_USERS'])?$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['USUARIO_USERS']:'NN');?>
                                    </i>                   						
                                    &nbsp;|&nbsp;                   						
                                    <img src="images/connect.png" title="Logueado en"/>                   						
                                    Conectado a:						
                                    <i>							
                                        <?php echo (isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_NOMBRE'])?$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_NOMBRE']:'NN'); ?>
                                        &nbsp;-&nbsp;							
                                        <?php echo (isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['OFICINA_NOMBRE'])?$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['OFICINA_NOMBRE']:'NN'); ?>			
                                    </i>              					
                            <?php
                                }
                            ?>            			
                    </div>		
                    <!-- #top_navigation -->        		
                </div>
                <!-- #header_navigation -->        		
                <div align="left" style="width:100%;">            			
                    <div style="width:80%;" id="divHeadLogo">                				
                        <div style="float:left;width:10%;">&nbsp;</div>
                        <div style="width:90%;">                    					
                            <span>						
                                <img src="images/logo.png" alt="logo"/>					
                            </span>                				
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
	</div>