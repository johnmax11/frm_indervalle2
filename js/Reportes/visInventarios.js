/**/ 
var gblIdRow = null;
$(document).ready(function(){
    /**trigger de eventos**/
    fnSearchRows("Rotaci&oacute;n de todo el inventario");
    /**eventos**/
    /**tabs*/
    $('#divTabs').tabs({
        activate:function(event,ui){
            switch(ui.newTab.index()){
                case 0:
                    //fnSearchDatosComprasClientes();
                    break;
            }
        }
    });
});


/**/
function fnSearchRows(var_caption){
    $('#divPreload_tab1').preload('Cargando Informaci&oacute;n...');
    /* grilla*/
   jQuery("#griddatoscx").jqGrid({ 
       url:$.fn.encoded_url_controller('reportes/inventarios/search_rotacion_inventarios'),
       datatype: "json", 
       colNames:['id','REFERENCIA','DESCRIPCION','TALLA','CANT. INV','VR. INV','ULT. COMPRA','VENDIDAS','ULT. VENTA','D. ROTACI&Oacute;N'],
       colModel:[ 
            {name:'id',index:'id',editable:false,hidden:true},
            {name:'referencia',index:'referencia', align:"center",editable:false},
            {name:'descripcion',index:'descripcion', editable:false},
            {name:'talla',index:'talla', align:"center",editable:false},
            {name:'cant_inv',index:'cant_inv', align:"center",editable:false,formatter:$.fn.funct_a_link_formatter},
            {name:'vr_inv',index:'vr_inv', align:"center",editable:false,formatter:"integer"},
            {name:'ult_compra',index:'ult_compra', align:"center",editable:false},
            {name:'vendidas',index:'vendidas', align:"center",editable:false,formatter:$.fn.funct_a_link_formatter},
            {name:'ult_venta',index:'ult_venta', align:"center",editable:false},
            {name:'dias_inv',index:'dias_inv', align:"center",editable:false}
       ],
       loadError:function(xhr,status,error){
           var msg = eval(xhr.responseText);
           alert('Error: En el response de la grilla ---> ');
       },
       onSelectRow: function(id){
           if(id!=null){
               gblIdRow = id;
           }
       },
       loadComplete:function(){
           $('#divPreload_tab1').html('');
       },
       rowNum:250, 
       rowList:[500],
       pager: '#pagerdatoscx', 
       sortname: 'id',
       width:1000,
       height:300,
       footerrow : true,
       userDataOnFooter : true,
       viewrecords: true,  
       rownumbers: true,
       sortorder: "asc",
       caption:var_caption
    }); 
    $.fn.config_grilla(jQuery("#griddatoscx"));
    $.fn.add_crud_grilla(jQuery("#griddatoscx"),false,false,false);
}

/**buscar los datos del cantidad inventario**/
function fn_cant_inv(cellvalue, options, rowObject){
    if(cellvalue=='0'){
        return '';
    }
    /****/
    $.fn.add_dialog({
        title:"Detalle Inventario: "+rowObject[1],
        id_dialog:"div_detalle_inventarios_by_item",
        autoOpen:true,
        width:630
    });
    
    $.ajax({
        url: $.fn.encoded_url_controller('reportes/inventarios/search_detalles_inventarios_by_item'),
        type:'POST',
        data:'id_item_inventario='+rowObject[0],
        dataType:'json',
        beforeSend:function(){$().preload("Cargando Informaci&oacute;n...",true);},
        error:function(jqXHR){$.fn.validate_error(jqXHR)},
        success: function(msg){
            if($.fn.validateResponse(msg,null)){
                /***/
                $.fn.create_grilla_tmp(
                    {
                        "div_destino":"div_detalle_inventarios_by_item",
                        "width":610,
                        "viewrecords":true,
                        "rownumbers":true,
                        "data":msg.rows
                    }
                );
            }
	}
    });
}

/***/
function fn_vendidas(cellvalue, options, rowObject){
    console.log(isNaN(parseInt(cellvalue)));
    if(isNaN(parseInt(cellvalue))==true){
        return '';
    }
    /****/
    $.fn.add_dialog({
        title:"Detalle Vendidas: "+rowObject[1],
        id_dialog:"div_detalle_vendidas_by_item",
        autoOpen:true,
        width:630
    });
    
    $.ajax({
        url: $.fn.encoded_url_controller('reportes/inventarios/search_vendidas_by_item'),
        type:'POST',
        data:'id_item_inventario='+rowObject[0],
        dataType:'json',
        beforeSend:function(){$().preload("Cargando Informaci&oacute;n...",true);},
        error:function(jqXHR){$.fn.validate_error(jqXHR)},
        success: function(msg){
            if($.fn.validateResponse(msg,null)){
                /***/
                $.fn.create_grilla_tmp(
                    {
                        "div_destino":"div_detalle_vendidas_by_item",
                        "width":610,
                        "viewrecords":true,
                        "rownumbers":true,
                        "data":msg.rows
                    }
                );
            }
	}
    });
}