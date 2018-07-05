document.write("<script type='text/javascript' src='js/utilidades/utilidades.js'></script>");
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var gblInterval = gblIntervalPC = null;
$(document).ready(function(){
    $('.class_bttn').button();
    $.fn.ucwordsAll();
    // insertamos el ingreso del usuario track //
    if($('#divMenu').html()!=undefined){
        fnInsert_track();
    }
	
    /***/
    setInterval(
        function(){
                $.ajax({url:'aplication/index.php'});
        },
        60000
    );
});

/***/
function fnInsert_track(){
    $.ajax({
        url: $.fn.encoded_url_controller('seguridad/usuarios_track/addeditrows'),
        type:'POST',
        data: 'url_track='+encodeURIComponent(location.href),
        dataType:'json',
        success: function(msg){
            if(msg!==null){
                if(msg.error===true){
                    alert(msg.msj);
                }
            }else{
                $("#divAlert").html($.fn.errorStyle2("Error: Ocurrio un error al ingresar el track del usuario"));
            }
        }
    });
}

/**/
function fnOpenAcercaDe(){
    $("#divAcercaDe").remove();
    
    var strhtml = "<div id='divAcercaDe'>";
        strhtml += "</div>";
    $('body').append(strhtml);
    
    // dialogos //
    $("#divAcercaDe").load('aplication/views/Utils/visAcercaDe.php').dialog({
            autoOpen: true,
            modal: true,
            draggable: true,
            resizable:false,
            width:400,
            buttons:{
                "Cerrar":function(){
                    $(this).dialog('close');
                }
            },
            close:function(){
                $("#divAcercaDe").remove();
            }
    });
}
