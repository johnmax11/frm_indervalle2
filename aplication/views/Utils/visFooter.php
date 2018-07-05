<?php
	namespace MiProyecto;
    if(!isset($_SESSION)){
        session_start();
    }
    $obj_ssn = new utisetVarSession();
?>
<div id="divPrincipal"  style="width: 100%" align="center">
    <div class="ui-state-active" style="padding:5px;">
        <p style="">
            &copy; 
            <i><?php echo (isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_NOMBRE'])?"Software licenciado a: ".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_NOMBRE']:'Amada Colombia S.A.S'); ?></i>
            &nbsp;-&nbsp;<?php echo @date('Y'); ?> -
            Powered by <a href="http://innovates.io" target="_blank"><u>Innovates</u></a>
        </p>
    </div>
    <br/>
	<?php if(isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS'])){ ?>
		<div>
                    <select id="selEstilosFooter" onchange="fnChangeTheme(this.value)">
                            <option value="">...</option>
                    </select>
		</div>
	<?php } ?>
</div>
<?php
    if(isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS'])){
?>
        <script>
            $(document).ready(function(){
                if(!localStorage.getItem('obj_styles')){
                    $.ajax({
                        url: $.fn.encoded_url_controller('parametros/parametros_miscelaneos/search_estilos'),
                        type:'POST',
                        dataType:'json',
                        error:function(jqXHR,textStatus,errorThrown){
                            $.fn.errorResponse(jqXHR.responseText);
                        },
                        success: function(msg){
                            if($.fn.validateResponse(msg,'divPreloadAddEdit')){
                                localStorage.setItem('obj_styles',JSON.stringify(msg.rows));
                                /***/
                                $.fn.set_combo(msg.rows,'selEstilosFooter',false);
                                /***/
                                $('#selEstilosFooter').val("<?php echo $obj_ssn->get_ssn_estilo(); ?>");
                            }
                        }
                    });
                }else{
                    var arr_st = JSON.parse(localStorage.getItem('obj_styles'));
                    $.fn.set_combo(arr_st,'selEstilosFooter',false);
                    /***/
                    $('#selEstilosFooter').val($.fn.ucwords("<?php echo $obj_ssn->get_ssn_estilo(); ?>"));
                }
            });
			

            /***/
            function fnChangeTheme(value){
                $.ajax({
                    url: $.fn.encoded_url_controller('seguridad/usuarios/save_estilos'),
                    type:'POST',
                    data:'a_estilo='+value,
                    dataType:'json',
                    beforeSend:function(){
                        $('#divPreloadAddEdit').preload('Guardando Tema...',true);
                    },
                    error:function(jqXHR,textStatus,errorThrown){
                        $.fn.errorResponse(jqXHR.responseText);
                    },
                    success: function(msg){
                        if($.fn.validateResponse(msg,'divPreloadAddEdit')){
                            location.reload();
                        }
                    }
                });
            }
        </script>
<?php
    }
?>