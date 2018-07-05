var myProyecto = null;
$(document).ready(function(){
    myProyecto = (function(){
        var __gblIdRow = null;

        /***/
        var __fnGetUrlGrilla = function(){
            return $.fn.encoded_url_controller('productos/gestion/select_rows_grilla'); // modulo, programa y bttn action
        }
        
        /***/
        var __ini_dialog_add_edit = function(autoOpen){
            /* dialogos */
            $("#divAddEdit").dialog({
                autoOpen: false,
                modal: true,
                draggable: true,
                resizable:true,
                width:900,
                buttons:{
                    "Guardar Cambios":function(){
                        __fnGuardarCambios();
                    },
                    "Cerrar":function(){
                        $(this).dialog('close');
                    }
                }
            });
            /***/
            if(autoOpen){
                $("#divAddEdit").dialog('open');
            }
        }
        
        /**/
        var __fnSearchRows = function(var_caption){
            $('#divPreload').preload('Cargando Informaci&oacute;n...');
            /* grilla*/
           jQuery("#griddatoscx").jqGrid({ 
               url:__fnGetUrlGrilla(),
               datatype: "json", 
               colNames:['id','REFERENCIA','DESCR','IMAGEN','VR COMPRA','VALOR','CATEGORIA','CREADO POR','FECHA CREACION'],
               colModel:[ 
                   {name:'id',index:'id', width:55,editable:false,hidden:true},
                   {name:'nombres',index:'nombres',align:'left',editable: true},
                   {name:'descripcion',index:'descripcion',align:'left',editable: true},
                   {name:'imagen',index:'imagen',align:'center',editable: true, formatter:__fnParsearImage},
                   {name:'valor_compra',index:'valor_compra',align:'left',editable: true,formatter:'integer'},
                   {name:'valor',index:'valor',align:'left',editable: true,formatter:'integer'},
                   {name:'categoria',index:'categoria',align:'left',editable: true},
                   {name:'creado_por',index:'creado_por', align:"center",editable: true},
                   {name:'fecha_creacion',index:'fecha_creacion', align:"center",editable: true}
               ],
               loadError:function(xhr,status,error){
                   var msg = eval(xhr.responseText);
                   alert('Error: En el response de la grilla ---> ');
               },
               onSelectRow: function(id){
                   if(id!=null){
                       __gblIdRow = id;
                   }
               },
               loadComplete:function(){
                   $('#divPreload').html('');
               },
               rowNum:250, 
               rowList:[50,100,200,300,500],
               pager: '#pagerdatoscx', 
               sortname: 'nombres',
               width:1000,
               height:300,
               viewrecords: true,  
               rownumbers: true,
               sortorder: "asc",
               caption:var_caption
            }); 
            $.fn.config_grilla(jQuery("#griddatoscx"));
            $.fn.add_crud_grilla(jQuery("#griddatoscx"),true,true,true,false,undefined,myProyecto);
        }

        /***/
        var __fnParsearImage = function(imagen){
                if(imagen!=null){
                    return "<img src='files/img_productos/"+imagen+"' alt='zapato' width='70px' height='70px'>";
                }else{
                    return "";
                }
        }

        /***/
        var __fnSearchProductos_categorias = function(){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('productos/gestion/select_categorias'),
                success: function(params,msg){
                    $.fn.set_combo(msg.rows,"selProductos_categorias","Seleccione...",true);
                }
            });

        }
        
        /**/
        var __fnGuardarCambios = function(){
            if(!$.fn.validateForm("frmAddEdit",true)){
                return;
            }
            
            /*validamos que el valor de venta sea mayor que el valor compra**/
            var numVrCompra = $('#txtvalor_compra').val().replace(/,/gi, "");
                numVrCompra = parseFloat(isNaN(numVrCompra)==true||numVrCompra==''?0:numVrCompra);
            var numVrVenta = $('#txtvalor').val().replace(/,/gi, "");
                numVrVenta = parseFloat(isNaN(numVrVenta)==true||numVrVenta==''?0:numVrVenta);
                console.log(numVrCompra);
                console.log(numVrVenta);
            if(numVrVenta < numVrCompra){
                $.fn.alert_dialog({
                    mensaje:"El valor de venta("+$('#txtvalor').val()+") debe ser mayor o igual al valor de compra("+$('#txtvalor_compra').val()+")"
                });
                return;
            }
            
            /***/
            $.show_confirm_dialog({
                msj:"Realmente desea guardar los cambios?",si:function(){__fnSendDatosProducto()}
            });
        }
        
        var __fnGetDatosRows = function(id){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('productos/gestion/select_producto_by_id'),
                data: 'idrow='+id,
                params:{id:id},
                success: function(params,msg){
                    //AQUI SE LLENA LOS DATOS BASICOS DEL PRODUCTO //
                    $("#hdnid").val(params.id);
                    $("#txtreferencia").val(msg.rows[0].nombres);
                    $("#txtreferencia").prop('disabled',true);
                    /***/
                    $("#selProductos_categorias").val(msg.rows[0].productos_categorias_id);
                    $("#txtDescripcion").val(msg.rows[0].descripcion);
                    $("#txtvalor_compra").val(parseInt(msg.rows[0].valor_compra));
                    $("#txtvalor").val(parseInt(msg.rows[0].valor_final));
                    if(msg.rows[0].imagen!=null){
                        $("#espacioimagen").prop('src','files/img_productos/'+msg.rows[0].imagen);
                    }
                }
            });
        }

        

        /**
         * 
         */
        var __fnSendDatosProducto = function(){
            /***/
            if(__gblIdRow==null){
                $('#frmAddEdit').prop('action',$.fn.encoded_url_controller('productos/gestion/create_row'));
            }else{
                $('#frmAddEdit').prop('action',$.fn.encoded_url_controller('productos/gestion/update_row'));
            }
            $('#frmAddEdit').submit();
        }
        
        /**
         * 
         * 
         * @returns {undefined}
         */
        var __ini_bttn_verificar_consec = function(){
            $("#bttnVerificarConsec").button({
                text: false,
                icons: {primary: 'ui-icon-search'}
            }).click(function(){
                __verificar_consecutivo_productos();
                return false;
            });
        };
        
        var __verificar_consecutivo_productos = function(){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('productos/gestion/select_consecutivos_agrupados'),
                success: function(params,msg){
                    $.fn.create_grilla_tmp({
                        caption:'Detalle Consecutivos',
                        create_dialog: true,
                        data: msg.rows,
                        width: 350,
                        page_use:false,
                        height: 150
                    });
                }
            });
        }


        /***metodo main********************************************************/
        /**metodo principal***/
        return {
            main: function(){
                /**eventos**/
                /**llamados iniciales**/
                __fnSearchRows("Productos");
                __fnSearchProductos_categorias();
                __ini_bttn_verificar_consec();
                return true;
            },
            fnAddRows : function(){
                __ini_dialog_add_edit({autoOpen:true});
                document.getElementById('frmAddEdit').reset();
                $('#hdnid').val('');
                __gblIdRow = null;
                $("#txtreferencia").prop('disabled',false);
                /***/
                $.fn.ucwordsAll();
            },
            /**/
            fnEditRows : function(){
                document.getElementById('frmAddEdit').reset();

                if(__gblIdRow!==null){
                    __ini_dialog_add_edit({autoOpen:true});
                    __fnGetDatosRows(__gblIdRow);
                }else{
                    alert('Debe seleccionar la fila que quiere editar');
                }
            },
            
            /***/
            fnDelRows : function(){
                if(__gblIdRow!==null){
                    if(confirm('Realmente desea borrar este registro?')){
                        $.ajax_frm({
                            url: $.fn.encoded_url_controller('productos/gestion/delete_row'),
                            data: 'idrow='+__gblIdRow,
                            success: function(params,msg){
                                alert(msg.msj);
                                location.reload();
                            }
                        });
                    }
                }else{
                    alert('Debe seleccionar la fila que quiere borrar');
                }
            },
        }
    })();
    /* iniciamos objeto**/
    myProyecto.main();
});