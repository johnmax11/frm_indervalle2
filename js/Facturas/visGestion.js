var myProyecto = null;
$(document).ready(function() {
    myProyecto = (function() {
        var __gblIdRow = null;
        
        var __searchFacturas = function(){
            /* grilla*/
            jQuery("#griddatoscx").jqGrid({
                url: $.fn.encoded_url_controller('facturas/gestion/select_all_facturas')+$.fn.get_datos_filtros('divFiltros_tab1'),
                datatype: "json",
                colNames: ['id', 'CLIENTE','IDENTIFICACION', 'DOCUMENTO', 'TOTAL','SALDO', 'CREADO POR', 'FECHA CREACION'],
                colModel: [
                    {name: 'id', index: 'id', width: 55, editable: false, hidden: true},
                    {name: 'nombres', index: 'nombres', align: 'left', editable: true},
                    {name: 'identificacion', index: 'identificacion', align: 'left',number:false, editable: true,},
                    {name: 'numero_factura', index: 'numero_factura', align: 'center', editable: true,formatter:$.fn.funct_a_link_formatter},
                    {name: 'total', index: 'total', align: 'right', editable: true,formatter:'integer'},
                    {name: 'saldo', index: 'saldo', align: 'right', editable: true,formatter:'integer'},
                    {name: 'creado_por', index: 'creado_por', align: "center", editable: true},
                    {name: 'fecha_creacion', index: 'fecha_creacion', align: "center", editable: true}
                ],
                loadError: function(xhr, status, error) {
                    var msg = eval(xhr.responseText);
                    alert('Error: En el response de la grilla ---> ');
                },
                rowNum: 100,
                rowList: [100, 200, 300, 500],
                pager: '#pagerdatoscx',
                sortname: 'numero_factura',
                width: 1000,
                height: 300,
                viewrecords: true,
                footerrow : true,
                userDataOnFooter : true,
                rownumbers: true,
                sortorder: "DESC",
                caption: "Facturas"
            });
            $.fn.config_grilla(jQuery("#griddatoscx"));
            $.fn.add_crud_grilla(jQuery("#griddatoscx"), true, false, false, false, undefined, myProyecto);
        }
        
        /**
         * @returns {undefined}
         */
        var __ini_dialog_create_factura = function(autoOpen){
            $("#divAddEdit").dialog({
                autoOpen: false,
                modal: true,
                draggable: true,
                resizable:true,
                width:1050,
                buttons:{
                    "Guardar":function(){
                        __validar_envio_datos();  
                    },
                    "Cerrar":function(){
                        $(this).dialog('close');
                    }
                },
                close:function(){
                }
            });
            /***/
            if(autoOpen==true){
                $("#divAddEdit").dialog('open');
            }
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_auto_complete_cliente = function(){
            $("#txtIdentificacion_cliente").autocomplete({
                /*source: "control/cue_hist_pedidos/cue_inventarios_ept.php?bttnAction=buscacuerosAction&scolor=true&categoria",*/
                source: function( request, response ) {
                    $.ajax({
                        type: "GET",
                        url: $.fn.encoded_url_controller('clientes/gestion/search_by_campo'),
                        dataType: "json",
                        data: {term: request.term,field:'identificacion'},
                        success: function( data ) {response(data);}
                    });
                },
                minLength: 2,
                dataType:'json',
                select: function( event, ui ){
                    $('#hdnIdentificacion_cliente').val(ui.item.id);

                    $('#txtNombres_cliente').val(ui.item.nombres);
                    $('#txtApellidos_cliente').val(ui.item.apellidos);
                },
                search:function(event,ui){
                    /***/
                    $('#chkClienteSinDatos').prop('checked',false);
                    $('#hdnIdentificacion_cliente').val('');

                    $('#txtNombres_cliente').val('');
                    $('#txtApellidos_cliente').val('');
                }
            }).blur(function(){
		if($(this).val()==''){$('#hdnIdentificacion_cliente').val('')}
            });
            
            $("#txtNombres_cliente").autocomplete({
                /*source: "control/cue_hist_pedidos/cue_inventarios_ept.php?bttnAction=buscacuerosAction&scolor=true&categoria",*/
                source: function( request, response ) {
                    $.ajax({
                        type: "GET",
                        url: $.fn.encoded_url_controller('clientes/gestion/search_by_campo'),
                        dataType: "json",
                        data: {term: request.term,field:'nombres'},
                        success: function( data ) {response(data);}
                    });
                },
                minLength: 2,
                dataType:'json',
                select: function( event, ui ){
                    $('#hdnIdentificacion_cliente').val(ui.item.id);

                    $('#txtIdentificacion_cliente').val(ui.item.identificacion);
                    $('#txtApellidos_cliente').val(ui.item.apellidos);
                },
                search:function(event,ui){
                    /***/
                    $('#chkClienteSinDatos').prop('checked',false);
                    $('#hdnIdentificacion_cliente').val('');

                    $('#txtIdentificacion_cliente').val('');
                    $('#txtApellidos_cliente').val('');
                }
            }).blur(function(){
		if($(this).val()==''){$('#hdnIdentificacion_cliente').val('')}
            });
            
            $("#txtApellidos_cliente").autocomplete({
                /*source: "control/cue_hist_pedidos/cue_inventarios_ept.php?bttnAction=buscacuerosAction&scolor=true&categoria",*/
                source: function( request, response ) {
                    $.ajax({
                        type: "GET",
                        url: $.fn.encoded_url_controller('clientes/gestion/search_by_campo'),
                        dataType: "json",
                        data: {term: request.term,field:'apellidos'},
                        success: function( data ) {response(data);}
                    });
                },
                minLength: 2,
                dataType:'json',
                select: function( event, ui ){
                    $('#hdnIdentificacion_cliente').val(ui.item.id);

                    $('#txtIdentificacion_cliente').val(ui.item.identificacion);
                    $('#txtApellidos_cliente').val(ui.item.apellidos);
                },
                search:function(event,ui){
                    /***/
                    $('#chkClienteSinDatos').prop('checked',false);
                    $('#hdnIdentificacion_cliente').val('');

                    $('#txtIdentificacion_cliente').val('');
                    $('#txtApellidos_cliente').val('');
                }
            }).blur(function(){
		if($(this).val()==''){$('#hdnIdentificacion_cliente').val('')}
            });
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __get_html_row_detalle = function(index){
            return $.fn.create_row_html({
                icon_delete:true,
                afterDelete:function(){
                    __set_total_row_footer();
                },
                index:index,
                fields:[
                    {
                        type:'label',
                        id:'lbl#',
                        value:true
                    },
                    {
                        type:'label',
                        id:'lblCategoria'
                    },
                    {
                        type:'input',
                        id:'txtReferenciaDetalle',
                        class:'validate',
                        maxlength:6,
                        autocomplete:true,
                        autocomplete_url:$.fn.encoded_url_controller('productos/gestion/select_producto_autocomplete_by_field'),
                        autocomplete_field:'nombres',
                        autocomplete_onselect:function(index,obj,ui){
                            __autocomplete_select(index,obj,ui);
                        },
                        autocomplete_onsearch:function(index,obj,ui){
                            __autocomplete_search(index,obj,ui);
                        },
                        icons:{
                            after:[
                                {
                                    icon:'ui-icon-plus',
                                    click:function(index){
                                        __add_producto_new(index);
                                    },
                                    title:"Agregar Producto"
                                }
                            ]
                        }
                    },
                    {
                        type:'number',
                        id:'txtCantidad',
                        class:'validate',
                        size:'1',
                        value:1,
                        maxlength:3,
                        events:{
                            keyup:function(obj,index){
                                var numPrProducto = ($('#txtPrecioVenta-'+index).val()!=undefined?$('#txtPrecioVenta-'+index).val().replace(/,/gi, ""):0);
                                var numVrCantidad = ($('#txtCantidad-'+index).val()!=undefined?$('#txtCantidad-'+index).val().replace(/,/gi, ""):0);
                                    numPrProducto = (numPrProducto*numVrCantidad);
                                __set_total_label_detalle(numPrProducto,($('#txtDctoProducto-'+index).val()!=undefined?$('#txtDctoProducto-'+index).val().replace(/,/gi, ""):0),index);
                            }
                        }
                    },
                    {
                        type:'label',
                        id:'lblDescripcion'
                    },
                    {
                        type:'number',
                        id:'txtPrecioVenta',
                        class:'validate',
                        size:6,
                        events:{
                            keyup:function(obj,index){
                                __set_total_label_detalle(obj.value.replace(/,/gi, ""),($('#txtDctoProducto-'+index).val()!=undefined?$('#txtDctoProducto-'+index).val().replace(/,/gi, ""):0),index);
                            }
                        }
                    },
                    {
                        type:'number',
                        id:'txtDctoProducto',
                        class:'validate',
                        value:0,
                        size:6,
                        events:{
                            keyup:function(obj,index){
                                __calcula_dcto_producto(obj,index);
                            }
                        }
                    },
                    {
                        type:'label',
                        id:'lblTotalDetalle'
                    },
                ]
            });
        }
        
        /***
         * calcula el descuento del producto ingresado
         * 
         * @param {type} obj
         * @returns {undefined}
         */
        var __calcula_dcto_producto = function(obj,index){
            var numPrProducto = ($('#txtPrecioVenta-'+index).val()!=undefined?$('#txtPrecioVenta-'+index).val().replace(/,/gi, ""):0);
            var numVrCantidad = ($('#txtCantidad-'+index).val()!=undefined?$('#txtCantidad-'+index).val().replace(/,/gi, ""):0);
                numPrProducto = (numPrProducto*numVrCantidad);
            var numDcto = obj.value.replace(/,/gi, "");
            /**validamos el dcto*/
            if(parseFloat(numDcto) > parseFloat(numPrProducto)){
                numDcto = obj.value = 0;
            }
            __set_total_label_detalle(numPrProducto,numDcto,index);
        }
        
        /**
         * 
         * @param {type} index
         * @returns {undefined}
         */
        var __add_producto_new = function(index){
            $.fn.load_view({
                title:"Crear Productos",
                id:"divDialogCreateProductos",
                view:'productos/gestion',
                width:950,
                height:300,
                load:'fnAddRows',
                hideCallback:'#divBody'
            });
        }
        
        /**
         * 
         * @param {type} numPrProducto
         * @param {type} numDcto
         * @param {type} index
         * @returns {undefined}
         */
        var __set_total_label_detalle = function(numPrProducto,numDcto,index){
            numPrProducto = (isNaN(numPrProducto)==true||numPrProducto==''?0:numPrProducto);
            numDcto = (isNaN(numDcto)==true||numDcto==''?0:numDcto);
            $('#lblTotalDetalle-'+index).html(number_format(parseFloat(numPrProducto) - parseFloat(numDcto)));
            /***/
            __set_total_row_footer();
        }
        
        /**
         * 
         * @returns {Number}
         */
        var __set_total_row_footer = function(){
            var t_prec_venta = 0;
            var t_dcto = 0;
            var t_total = 0;
            for(var i=0;i<parseInt($('#hdnContRowsDetalle').val());i++){
                if(!document.getElementById('txtPrecioVenta-'+i)){
                    continue;
                }
                var numTotal = $('#txtPrecioVenta-'+i).val().trim().replace(/,/gi, "");
                    numTotal = (isNaN(numTotal)==true||numTotal==''?0:numTotal);
                t_prec_venta += parseInt(numTotal);
                    
                var numTotal = $('#txtDctoProducto-'+i).val().trim().replace(/,/gi, "");
                    numTotal = (isNaN(numTotal)==true||numTotal==''?0:numTotal);
                t_dcto += parseInt(numTotal);
                
                var numTotal = $('#lblTotalDetalle-'+i).html().trim().replace(/,/gi, "");
                    numTotal = (isNaN(numTotal)==true||numTotal==''?0:numTotal);
                t_total += parseInt(numTotal);
            }
            /***/
            $('#lblSubtotalFooter').html(number_format(t_prec_venta));
            /***/
            $('#lblDctoFooter').html(number_format(t_dcto));
            /***/
            $('#lblTotalFooter').html(number_format(t_total));
            
            $('#lblTotalRecibir').html(number_format(t_total));
            __set_finalizar_pago(t_total);
            return t_total;
        }
        
        /**
         * 
         * @param {type} t_total
         * @returns {undefined}
         */
        var __set_finalizar_pago = function(t_total){
            /**set total a recibir */
            if($('#radFormaPagoPT').is(':checked')){
                $('#lblTotalARecibir').html(number_format(t_total));
                if(t_total==undefined){
                    __set_total_row_footer();
                }
            }else{
                if($('#radFormaPagoAB').is(':checked')){
                    $('#lblTotalARecibir').html(number_format($('#txtValorAbono').val()));
                }else{
                    $('#lblTotalARecibir').html("0");
                }
            }
        }
        
        /**
         * 
         * @param {type} index
         * @param {type} productos_categorias_id
         * @returns {undefined}
         */
        var __search_categoria_by_producto = function(index,productos_categorias_id){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('productos/gestion/select_producto_categoria'),
                beforeSend:function(){
                    $('#lblCategoria-'+index).after($.fn.after_animation(index,'cat_'));
                },
                params:{index:index},
                data: 'idrow='+productos_categorias_id,
                success: function(params,msg){
                    $('#img_animtcat_-'+params.index).remove();
                    $('#lblCategoria-'+params.index).html(msg.rows[0].nombre);
                }
            });
        }
        
        /**
         * 
         * @param {type} index
         * @param {type} obj
         * @param {type} ui
         * @returns {undefined}
         */
        var __autocomplete_select = function(index,obj,ui){
            $('#hdn_txtReferenciaDetalle-'+index).val(ui.item.id);
            /**set nombre descripcion*/
            $('#lblDescripcion-'+index).html(ui.item.descripcion);
            /**set precio final*/
            $('#txtPrecioVenta-'+index).val(ui.item.valor_final).trigger('keyup');
            /**buscamos la categoria del producto**/
            __search_categoria_by_producto(index,ui.item.productos_categorias_id);
            /**set ttotal detalle*/
            var numPrProducto = (ui.item.valor_final!=undefined?ui.item.valor_final.replace(/,/gi, ""):0);
            var numVrCantidad = ($('#txtCantidad-'+index).val()!=undefined?$('#txtCantidad-'+index).val().replace(/,/gi, ""):0);
                numPrProducto = (numPrProducto*numVrCantidad);
            __set_total_label_detalle(numPrProducto,$('#txtDctoProducto-'+index).val().replace(/,/gi, ""),index);
        }
        
        /**
         * 
         * @param {type} index
         * @param {type} obj
         * @param {type} ui
         * @returns {undefined}
         */
        var __autocomplete_search = function(index,obj,ui){
            /***/
            $('#hdn_txtReferenciaDetalle-'+index).val('');
            /**set nombre descripcion*/
            $('#lblDescripcion-'+index).html('');
            /**set precio final*/
            $('#txtPrecioVenta-'+index).html('');
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_event_chk_sin_datos = function(){
            $('#chkClienteSinDatos').click(function(){
                if($(this).is(':checked')){
                    $.ajax_frm({
                        url: $.fn.encoded_url_controller('clientes/gestion/select_datos_clientes_row'),
                        data: 'idrow=1',
                        success: function(params,msg){
                            $('#hdnIdentificacion_cliente').val(msg.rows[0].id);
                            $('#txtIdentificacion_cliente').val(msg.rows[0].identificacion);
                            $('#txtNombres_cliente').val(msg.rows[0].nombres);
                            $('#txtApellidos_cliente').val(msg.rows[0].apellidos);
                        }
                    });
                }else{
                    $('#hdnIdentificacion_cliente').val("");
                    $('#txtIdentificacion_cliente').val("");
                    $('#txtNombres_cliente').val("");
                    $('#txtApellidos_cliente').val("");
                }
            });
        }
        
        var __get_ind_cont_rows = function(){
            return $('#hdnContRowsDetalle').val();
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __add_registro_detalle = function(){
            $('#tBody_tblDetalleFactura').append(__get_html_row_detalle(__get_ind_cont_rows()));
            $.fn.ucwordsAll();
            __increment_cont_rows();
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_event_add_reg_detalle = function(){
            $("#bttnAgregar").button({
                text: false,
                icons: {primary: 'ui-icon-plus'}
            }).click(function(){
                __add_registro_detalle();
                return false;
            });
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __increment_cont_rows = function(){
            var ind = $('#hdnContRowsDetalle').val();
                $('#hdnContRowsDetalle').val(parseInt($('#hdnContRowsDetalle').val())+1);
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_event_forma_pago = function(){
            $('#radFormaPagoPT').click(function(){
                $('#txtDineroRecibido').prop('disabled',false);
                $('#txtValorAbono').hide();
                __set_finalizar_pago();
            });
            $('#radFormaPagoAB').click(function(){
                $('#txtDineroRecibido').prop('disabled',false);
                $('#txtValorAbono').show(500,'');
                __set_finalizar_pago();
            });
            $('#radFormaPagoSA').click(function(){
                $('#txtValorAbono').hide();
                __set_finalizar_pago();
                $('#txtDineroRecibido').val('0');
                $('#txtDineroRecibido').prop('disabled',true);
            });
            /**/
            
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_event_campo_anticipo = function(){
            $('#txtValorAbono').keyup(function(){
                $(this).val(number_format($(this).val()));
                /**/
                __set_finalizar_pago();
            });
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __validar_envio_datos = function(){
            if(!$.fn.validateForm('frmAddEdit',true)){
                return;
            }
            
            /**
             * validamos el dinero recibido
             */
            if(!__validar_dinero_recibido()){
                return;
            }
            
            var str_proceso = '';
            if($('#radTipoProcesoFAC').is(':checked')){
                str_proceso = 'FACTURA';
            }
            /***/
            $.show_confirm_dialog({
                msj:
                    "Documento a crear: <b>"+str_proceso+'</b><br/><br/>'+
                    "Total A Recibir: <span style='color:green;font-size:12pt;'>$"+($('#lblTotalARecibir').html())+'</span><br/>'+
                    "Total Recibido: <span style='color:red;font-size:12pt;'>$"+($('#txtDineroRecibido').val())+'</span>',
                si:function(){
                    __send_datos_documento();
                }
            });
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __send_datos_documento = function(){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('facturas/gestion/create_documento_venta'),
                data: $('#frmAddEdit').serialize(),
                success: function(params,msg){
                    var numVrDevolver = 0;
                    if($('#radFormaPagoPT').is(':checked')){
                        numVrDevolver = msg.total_dinero-msg.total_documento;
                    }else{
                        if($('#radFormaPagoAB').is(':checked')){
                            numVrDevolver = (msg.total_dinero-$('#txtValorAbono').val().replace(/,/gi, ""));
                        }
                    }
                    $.fn.alert_dialog({
                        mensaje:
                                '<b>'+msg.msj+'</b>'+
                                '<br/><br/>Valor a Devolver: <span style="color:red;font-size:12pt;">$ <u>'+number_format(numVrDevolver)+'</u></span>'+
                                '<br/><br/><i>Gracias por su compra!</i>',
                        'buttons': {
                            "Ok":function(){
                               location.reload();
                        }}
                    });
                }
            });
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_event_dinero_recibido = function(){
            $.fn.hasEventListener($('#txtDineroRecibido'),'blur')  || $('#txtDineroRecibido').blur(function(){
                __validar_dinero_recibido();
            });
        }
        
        /**
         * 
         * @returns {Boolean}
         */
        var __validar_dinero_recibido = function(){
            var vr_a_recibir = $('#lblTotalARecibir').html().replace(/,/gi, "");
            var vr_dinero = $('#txtDineroRecibido').val().replace(/,/gi, "");
                vr_dinero = (vr_dinero==''||isNaN(vr_dinero)?0:vr_dinero);

            if(parseFloat(vr_dinero) < parseFloat(vr_a_recibir)){
                $.fn.alert_dialog({
                    mensaje:"El valor del dinero recibido debe ser mayor o igual al valor a RECIBIR del documento generado"
                });
                return false;
            }
            return true;
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_event_reg_clientes = function(){
            $("#bttnCreateClientes").button({
                text: false,
                icons: {primary: 'ui-icon-plus'}
            }).click(function(){
                __create_cliente_dialog();
                return false;
            });
            $("#bttnSearchClientes").button({
                text: false,
                icons: {primary: 'ui-icon-search'}
            }).click(function(){
                __search_all_clientes_grid();
                return false;
            });
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __create_cliente_dialog = function(){
            $.fn.load_view({
                title:"Crear Clientes",
                id:"divDialogCreateClientes",
                view:'clientes/gestion',
                width:760,
                height:450,
                load:'fnAddRows',
                hideCallback:'#divBody'
            });
        }
        
        var __search_all_clientes_grid = function(){
            /*creamos el dialogo*/
            $.fn.add_dialog({
                title:"Clientes",
                id_dialog:"divDialogClientes",
                width:910
            });
            
            /***/
            $.ajax_frm({
                url: $.fn.encoded_url_controller('clientes/gestion/select_rows_grilla'),
                success: function(params,msg){
                    $.fn.create_grilla_tmp({
                        div_destino:"divBody_divDialogClientes",
                        width:890,
                        data:msg.rows
                    });
                }
            });
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_event_filtros_principal = function(){
            $('#selTipo_rango').change(function(){
                $('#divMes_filtro_tab1').hide();
                $('#divMes_filtro_tab2').hide();
                $('#divMes_filtro_tab3').hide();
                switch($(this).val()){
                    case 'M':
                        $('#divMes_filtro_tab1').show();
                        break;
                    case 'A':
                        $('#divMes_filtro_tab2').show();
                        break;
                    case 'R':
                        $('#divMes_filtro_tab3').show();
                        break;
                }
            });
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_calendar_filtros = function(){
            $( "#txtFechaDe_filtro_tab1" ).datepicker({
                changeMonth: true,
                numberOfMonths: 1,
                showOn: 'button',
                buttonImage: 'images/calendar.png',
                buttonImageOnly: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                maxDate:'0D',
                onClose: function( selectedDate ) {
                  $( "#txtFechaHasta_filtro_tab1" ).datepicker( "option", "minDate", selectedDate );
                }
            });
            $( "#txtFechaHasta_filtro_tab1" ).datepicker({
                changeMonth: true,
                numberOfMonths: 1,
                showOn: 'button',
                buttonImage: 'images/calendar.png',
                buttonImageOnly: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                maxDate:'0D',
                onClose: function( selectedDate ) {
                  $( "#txtFechaDe_filtro_tab1" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
        }
        
        var __ini_bttn_buscar_filtros = function(){
            $('#bttn_tab_1').click(function(){
                $.fn.fnRefreshGrilla(jQuery("#griddatoscx"),"divFiltros_tab1");
            });
        }
        
        /**metodo principal***/
        return {
            main: function() {
                /**buscamos las facturas actuales*/
                __searchFacturas();
                __ini_event_filtros_principal();
                __ini_calendar_filtros();
                __ini_bttn_buscar_filtros();
            },
            
            fnAddRows : function(){
                $.fn.ucwordsAll();
                __ini_dialog_create_factura(true);
                __ini_auto_complete_cliente();
                __ini_event_chk_sin_datos();
                __ini_event_reg_clientes();
                __ini_event_add_reg_detalle();
                __ini_event_forma_pago();
                __ini_event_campo_anticipo();
                __ini_event_dinero_recibido();
                /**creamos 2 filas por defecto**/
                $('#hdnContRowsDetalle').val('0');
                $('#tBody_tblDetalleFactura').html('');
                $('#tHead_tblDetalleFactura').html('');
                
                /*set titles**/
                $('#tHead_tblDetalleFactura').append(
                    $.fn.create_row_html({
                        index:-1,
                        fields:[
                            {type:'label',id:'lbl#',value:"#"},
                            {type:'label',id:'lblCategoria',value:"Categoria"},
                            {type:'label',id:'lblReferencia',value:"Referencia"},
                            {type:'label',id:'lblCantidad',value:"Cant"},
                            {type:'label',id:'lblDescripcion',value:"Descripcion"},
                            {type:'label',id:'lblPrecioVenta',value:"Precio Venta"},
                            {type:'label',id:'lblDctoProducto',value:"Dcto"},
                            {type:'label',id:'lblTotalDetalle',value:"Total"},
                            {type:'label',id:'lblRemover',value:""},
                        ]
                    })
                );
                for(var i=0;i<1;i++){
                    $('#tBody_tblDetalleFactura').append(__get_html_row_detalle(i));
                    __increment_cont_rows();
                }
                $.fn.ucwordsAll();
            },
        
            /**
             * 
             * @param {type} cellvalue
             * @param {type} options
             * @param {type} rowObject
             * @returns {undefined}
             */
            fn_numero_factura : function(cellvalue, options, rowObject){
                $.ajax_frm({
                    url: $.fn.encoded_url_controller('facturas/gestion/search_detalles_factura'),
                    data: 'id_factura='+rowObject[0],
                    params:{rowObject:rowObject},
                    success: function(params,msg){
                        $.fn.create_grilla_tmp({
                            caption:'Detalle Factura: '+params.rowObject[3],
                            create_dialog: true,
                            data: msg.rows,
                            width: 650,
                            height: 150,
                            arr_totalizar:["total"],
                            arr_hidden_col:['id'],
                            buttons:{
                                right:[
                                    {
                                        icon:"refresh.png",
                                        fn:function(){
                                            alert('aca-->');
                                        }
                                    }
                                ]
                            }
                        });
                    }
                });
            }
        }
        
    })();
    /* iniciamos objeto**/
    myProyecto.main();
});