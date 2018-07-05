var url = location.href;
    url = url.split('?');
    url = url[1];
    url = url.split('&');
    
    var mod = null;
    var act = null;
    for(var i=0;i<url.length;i++){
        var arrD = url[i].split('=');
        if(arrD[0] == 'module'){
            mod = arrD[1];
        }
        if(arrD[0] == 'action'){
            act = arrD[1];
        }
        if(mod!=null && act!=null){
            break;
        }
    }
    if(mod==null || act==null){
        alert('Error de include ---> archivo js');
    }else{
        document.write('<script type="text/javascript" src="js/'+mod+'/vis'+act+'.js"></script>');
    }
    $(document).ready(function(){
        $.fn.cargar_condiciones_iniciales();
    });
function UrlExists(url)
{
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status!=404;
}
