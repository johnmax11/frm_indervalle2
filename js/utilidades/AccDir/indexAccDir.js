/**
 * init proceso de inclusion acc dir a otro sistemas
 * */
function fnIni_acc_dir(){
    // incluimos el div del button //
    $('#divHeadLogo').before(fnGet_html_div());
    // consultamos los acc dir //
    $.getJSON("js/utilidades/AccDir/ini.json", function(msg){
        if(msg!=null){
            var strhtml  = "<div id='divLinkPanelRigth' style='float:right;display:none;'>";
                strhtml +=" <ul class='ul_p_c'>"
            for(var i=0;i<msg.root.length;i++){
                strhtml += "<li class='li_p_c' style='background-color:#555;'>";
                strhtml += "    <a href='"+msg.root[i].sys.url+"' >";
                if(msg.root[i].sys.imagen!=''){
                    strhtml += "        <img src='images/"+msg.root[i].sys.imagen+"' title='Ingresar a: "+msg.root[i].sys.title+"'/>";
                }
                strhtml += "        <span>"+msg.root[i].sys.title+"</span>";
                strhtml += "    </a>";
                strhtml += "</li>";
            }
            strhtml += "</ul>";
            strhtml += "</div>";
            $('#divOpenPanel').before(strhtml);
        }
    });
}
/***/
function fnGet_html_div(){
    var strhtml  = "";
        strhtml += '<div id="divOpenPanel" style="float:right;">';
        strhtml += '    <a href="javascript:void(0)" onclick="fnOpenPanelRigth()">';
        strhtml += '        <span><img id="imgdivOpenPanel" src="images/open_panel.png" title="Abrir Accesos Directos"/></span>';
        strhtml += '    </a>';
        strhtml += '</div>';
    return strhtml;
}
/***/
function fnOpenPanelRigth(){
    if($('#imgdivOpenPanel').attr('src')=='images/open_panel.png'){
        $('#divLinkPanelRigth').show('slow');
        fnChangeImagenPanelRigth(true);
    }else{
        $('#divLinkPanelRigth').hide('slow');
        fnChangeImagenPanelRigth(false);
    }
}
/**/
function fnChangeImagenPanelRigth(bolac){
    if(bolac){
        $('#imgdivOpenPanel').attr('src','images/close_panel.png');
        $('#imgdivOpenPanel').attr('title','Cerrar Accesos Directos');
    }else{
        $('#imgdivOpenPanel').attr('src','images/open_panel.png');
        $('#imgdivOpenPanel').attr('title','Abrir Accesos Directos');
    }
}
//
fnIni_acc_dir();
