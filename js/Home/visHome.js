$(document).ready(function(){
    // traemos la info //
    fnGetInformacionImportante();
    // dialogos //
    $("#divCambioPassword").dialog({
            autoOpen: false,
            modal: true,
            draggable: true,
            resizable:true,
            width:300,
            buttons:{
                "Guardar Cambios":function(){
                    fnGuardarCambios();
                }
            }
    }).parent('.ui-dialog').find('.ui-dialog-titlebar-close').remove();
});
/**
 */
function fnGetInformacionImportante(){
    $.ajax({
        url: $.fn.encoded_url_controller('home/home/select_info_importante'),
        type:'POST',
        dataType:'html',
        success: function(msg){
            msg = $.fn.splitDivJson(msg);
            if($.fn.validateResponse(msg,'divPreloadAddEdit')){
                if(msg.cambio_password!=null && msg.cambio_password==true){
                    document.getElementById('frmCambioPassword').reset();
                    $("#divCambioPassword").dialog('open');
                }else{
                    var strHtml = "";
                    if(msg.rows!=null && msg.rows.length>0){
                        for(var i=0;i<msg.rows.length;i++){
                            strHtml += "<table>";
                            strHtml += "    <tr>";
                            if(i==0){
                                strHtml += "        <th><img src='images/info_peque.png' title='Ingreso Actual del usuario'/><label style='color:red;'>&nbsp;Ingreso Actual:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></th>";
                            }else{
                                strHtml += "        <th><img src='images/info_peque.png' title='Ingreso anterior del usuario'/>&nbsp;Ingreso Anterior:&nbsp;</th>";
                            }
                            strHtml += "        <th><i>";
                            strHtml += "            "+msg.rows[i].date_entered+" - "+msg.rows[i].nombre_empresas+" - "+msg.rows[i].nombre_empresas_oficinas;
                            strHtml += "        </i></th>";
                            strHtml += "    </tr>";
                            strHtml += "</table>";
                        }
                    }
                    $('#divInformacion').html(strHtml);
                }
            }
        }
    });
}

/**
 * guardar los cambios de clave de clave
 **/
function fnGuardarCambios(){
    if(!$.fn.validateForm('frmCambioPassword')){
        return;
    }
    // validamos los campos de clave nueva para q sean iguales //
    if(!$.fn.fnValidaCamposDesIguales($('#txtClave_nueva'),$('#txtConfirmar_clave'))){
        return;
    }
    // guardamos cambios //
    $.ajax({
        url: $.fn.encoded_url_controller('home/home/save_cambio_password'),
        type:'POST',
        data:$('#frmCambioPassword').serialize(),
        beforeSend:function(){
            $().preload('Guardando Cambios...',true);
        },
        dataType:'json',
        error:function(jqXHR,textStatus,errorThrown){
            $.fn.errorResponse(jqXHR.responseText);
        },
        success: function(msg){
            if($.fn.validateResponse(msg)){
                alert(msg.msj);
                location.reload();
            }
        }
    });
}