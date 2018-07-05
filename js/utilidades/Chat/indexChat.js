/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var gblUltiIdMsj = -1;
var gblParar = false;
var gblTimeScaneo = null;
var gblPos = null;
if (typeof jQuery == "undefined") {
    // jQuery no esta cargado
    alert('Atenci\u00fan!: La libreria Jquery no se encuentra cargada,\n\n-debe hacerlo para que funcione correctamente el chat!');
} else {
    // jQuery esta cargado
    $(document).ready(function(){
        if($.ui) {
            //jQuery UI is loaded
            if(gblUrlEnvioMensajes == null){
                alert('Debe parametrizar la url de envio de mensajes del chat al servidor');
                return;
            }
            if(glbUrlScaneoMsjNuevos == null){
                alert('Debe parametrizar la url de escaneo de mensajes del chat desde servidor');
                return;
            }
            // pos del menu
            gblPos = getAbsoluteElementPosition("tdMenuIndex");
            //alert(pos.top);
            //alert($('#tdMenuIndex').height());
            // creamos la caja del chat
            fnCreateBoxChat();
            // iniciamos el escaneo del chat
            fnInitEscaneo();
            //
            
        } else {
            //jQuery UI is not loaded
            alert('Atenci\u00fan: La extensi\u00fan Jquery.ui no se encuetra cargada,\n\n-Debe hacerlo para que funcione correctamente el chat');
        }
    });
}


// envio de msj al servidor
function fnEnvioMsjServidor(){
    $.ajax({
      type: "POST",
      url: gblUrlEnvioMensajes,
      dataType: "json",
      data:'txtmsj='+$('#txtMsjNew').val(),
      success: function(msg) {
          if(msg!=null){
              if(msg.error == true){
                  alert(msg.msj);
              }else{
                 // gblUltiIdMsj = msg.ult;
              }
              $('#txtMsjNew').val('');
          }
      }
    });
}
// iniciamos el escaneo del chat
function fnInitEscaneo(){
    gblTimeScaneo = setInterval("fnEscaneoMsjChat()",2000);
}
// hacemos el escaneo del msj de chat
function fnEscaneoMsjChat(){
    $.ajax({
      type: "POST",
      url: glbUrlScaneoMsjNuevos,
      dataType: "json",
      data: 'ultid='+gblUltiIdMsj,
      success: function(msg) {
          if(msg!=null){
              if(msg.ultid == gblUltiIdMsj){
                  return;
              }
              for(var i=0;i<msg.rows.length;i++){
                  $('#divCajaTextoChat').append(fnArmaHtmlMsjChat(msg.rows[i]));
              }
              gblUltiIdMsj = msg.ultid;
          }
          scrollWin();
      }
    });
}
/// arma msj pa mostrar en el chat
function fnArmaHtmlMsjChat(row){
   var strHtml = "";
   
   strHtml += "<div>["+row.fecha_creacion+"]: <b>"+row.usuario+"</b></div><br/>";
   strHtml += "<div>"+row.mensaje+"</div>";
   strHtml += "<hr/>";
   
   return strHtml;
}
/// creamos el cuadro de dialogo del chat
function fnCreateBoxChat(){
    $('#divChat').html(fnCreateHtmlDivChat());
    $('input').button();
    /// set evento de envio
    $('#aSend').click(function(){
        if($('#txtMsjNew').val()==''){
           alert('Debe escribir un mensaje'); 
           return;
        }
        fnEnvioMsjServidor();
    });
    //
    /// dialog de reto
    $( "#divChat" ).dialog({
            autoOpen: true,
            modal: false,
            draggable: true,
            resizable:true,
            width:180,
            height:250,
            title: 'Chat',
            open: function(event, ui) {
                $(event.target).parent().css('position', 'fixed');
                $(event.target).parent().css('left', gblPos.left+'px');
                $(event.target).parent().css('top', (gblPos.top + $('#divMenu').height() + 10)+'px');
            }

    }).parent('.ui-dialog').find('.ui-dialog-titlebar-close').remove();
}

/// creal html del div del cuadro de dialog
function fnCreateHtmlDivChat(){
    var strHtml = "";
    
    strHtml += "<div id='divContenedor'>";
    
    strHtml += " <div id='divCajaTextoChat' style='overflow:auto'>&nbsp;";
    
    strHtml += " </div>";
    
    strHtml += " <hr/><br/><div id='divCajaMsjNewChat' style='width:90%;bottom: 0;width:90%;'>";
    strHtml += "    <textarea id='txtMsjNew' name='txtMsjNew' cols='5' style='width:80%;'></textarea>";
    strHtml += "    <a id='aSend' href='javascript:void(0)'><img src='images/chat_send.png' alt='Enviar' title='Enviar'/></a>";
    strHtml += " </div><br/><br/>";
    
    strHtml += "</div>";
    
    return strHtml;
}
function scrollWin() {
    if(gblParar==true){
        return;
    }
    document.getElementById('divChat').scrollTop=document.getElementById('divChat').scrollHeight;
}