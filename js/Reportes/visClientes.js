/**/ 
var gblIdRow = null;
$(document).ready(function(){
    /**trigger de eventos**/
    fnSearchRows("Ranking Compras Clientes");
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
       url:$.fn.encoded_url_controller('reportes/clientes/search_compras_clientes'),
       datatype: "json", 
       colNames:['id','CLIENTE','IDENT','VR. COMPRADO','CANT. FACTURAS','ULT. COMPRA'],
       colModel:[ 
           {name:'id',index:'id',editable:false,hidden:true},
            {name:'nombres',index:'nombres', align:"center",editable:false},
            {name:'identificacion',index:'identificacion', align:"center",editable:false},
            {name:'vr_comprado',index:'vr_comprado', align:"center",editable:false,formatter:'integer'},
            {name:'cant_facturas',index:'cant_facturas', align:"center",editable:false,formatter:$.fn.funct_a_link_formatter},
            {name:'ult_compra',index:'ult_compra', align:"center",editable:false}
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
       rowList:[50],
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

/**buscar los datos del anio actual**/
function fn_cant_facturas(cellvalue, options, rowObject){
    /****/
	$.fn.add_dialog({
		title:"Detalle Cliente: "+rowObject[1],
		id_dialog:"div_detalle_facturas_clientes",
		autoOpen:true,
		width:630
	});
    
    $.ajax({
        url: $.fn.encoded_url_controller('reportes/clientes/search_detalles_compras_clientes'),
        type:'POST',
        data:'id_cliente='+rowObject[0],
        dataType:'json',
        beforeSend:function(){$().preload("Cargando Informaci&oacute;n...",true);},
        error:function(jqXHR){$.fn.validate_error(jqXHR)},
        success: function(msg){
            if($.fn.validateResponse(msg,null)){
                /***/
                $.fn.create_grilla_tmp(
                    {
                        "title":"Detalle Facturas",
                        "div_destino":"divBody_div_detalle_facturas_clientes",
                        "width":610,
                        "viewrecords":true,
                        "rownumbers":true,
                        "data":msg.rows,
                        "arr_totalizar":['valor_factura']
                    }
                );
            }
	}
    });
}