var myProyecto = null;
$(document).ready(function(){
    myProyecto = (function(){
        var __gblIdRow = null;
        var __gblArrIndex = new Array(false,true,false,false,false,false);

        /**eventos*************************************************************/
        /******/
        var __ini_bttn_tab_ch1 = function(obj){
            obj.click(function(){
                /** guardamos los datos del cliente en popup de datos basicos */
                __fnGuardarCambios(true);
            });
            return true;
        }
        
        /*****/
        var __ini_sel_change_referido = function(obj){
            $('#selOrigenReferido').change(function(){
                __fnGestionReferido();
            });
            return true;
        }
        
        /* dialogos ***********************************************************/
        /****/
        var __ini_dialog_add_edit_cliente = function(parametros){
            $("#divAddEdit").dialog({
                autoOpen: false,
                modal: true,
                draggable: true,
                resizable:true,
                width:700,
                buttons:{
                    "Guardar Cambios":function(){
                        __fnGuardarCambios();
                    },
                    "Cerrar":function(){
                        $(this).dialog('close');
                    }
                }
            });
            /****/
            if(parametros.autoOpen==true){
                $("#divAddEdit").dialog('open');
            }
        }
        /***/
        var __ini_dialog_historial_cliente = function(parametros){
            $("#divShowHistorialCliente").dialog({
                autoOpen: false,
                modal: true,
                draggable: true,
                resizable:true,
                width:700,
                buttons:{
                    "Cerrar":function(){
                        __gblIdRow = null;
                        document.getElementById('frmTabHC1').reset();
                        __fnGestionReferido('_TabHC1');
                        gblArrIndex = new Array(false,true,false,false,false,false,false);
                        $( "#divTabsHistorialCliente" ).tabs( {active:1} );
                        $(this).dialog('close');
                    }
                },
                close:function(){
                    __gblIdRow = null;
                    document.getElementById('frmTabHC1').reset();
                    gblArrIndex = new Array(false,true,false,false,false,false,false);
                    $( "#divTabsHistorialCliente" ).tabs( {active:1} );
                }
            });
            /***/
            if(parametros.autoOpen==true){
                $("#divShowHistorialCliente").dialog('open');
            }
        }
        
        /** autocomplete ******************************************************/
        /**/
        var __ini_autocomplete_ciudad = function(){
            $("#txtCiudad_n_clientes,#txtCiudad_n_clientes_TabHC1").autocomplete({
                /*source: "control/cue_hist_pedidos/cue_inventarios_ept.php?bttnAction=buscacuerosAction&scolor=true&categoria",*/
                source: function( request, response ) {
                    $.ajax({
                        type: "GET",
                        url: $.fn.encoded_url_controller('parametros/parametros_ciudades/buscar_ciudad'),
                        dataType: "json",
                        data: {term: request.term,field:'nombres'},
                        success: function( data ) {response(data);}
                    });
                },
                minLength: 2,
                dataType:'json',
                select: function( event, ui ){
                    $('#hdnCiudad_clientes').val(ui.item.id);
                    $('#hdnCiudad_clientes_TabHC1').val(ui.item.id);
                },
                search:function(event,ui){
                    $('#hdnCiudad_clientes').val('');
                    $('#hdnCiudad_clientes_TabHC1').val('');
                }
            }).blur(function(){
		if($(this).val()==''){$('#hdnCiudad_clientes').val('');$('#hdnCiudad_clientes_TabHC1').val('');}
            });
        }
    
        /***/
        var __ini_autocomplete_referido = function(){
            $("#txtOrReferido_1").autocomplete({
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
                    $('#hdnOrReferido_1').val(ui.item.id);
                    $('#lblNameOrReferido_1').html(ui.item.label+' - '+ui.item.identificacion);
                },
                search:function(event,ui){
                    $('#hdnOrReferido_1').val('');
                    $('#lblNameOrReferido_1').html('');
                }
            }).blur(function(){
		if($(this).val()==''){$('#hdnOrReferido_1').val('');$('#lblNameOrReferido_1').html('');}
            });
        }
        
        /** tabs **************************************************************/
        var __ini_tabs_historial = function(){
            /** tabs */
            $('#divTabsHistorialCliente').tabs({
                activate: function(event, ui) {
                    switch(ui.newTab.index()){
                        case 0:
                            if(__gblArrIndex[ui.newTab.index()]==false){
                                __gblArrIndex[ui.newTab.index()] = true;
                                /** buscamos los datos del cliente */
                                __fnGetDatosRows(__gblIdRow,'_TabHC'+(parseInt(ui.newTab.index())+1));
                            }
                            break;
                        case 1:
                            if(__gblArrIndex[ui.newTab.index()]==false){
                                /** buscamos los datos de las facturas realizadas del sistema nuevo **/
                                //__fnGetHisFacturasSisNew(__gblIdRow);
                                __gblArrIndex[ui.newTab.index()] = true;
                            }
                            break;
                        case 2:
                            if(__gblArrIndex[ui.newTab.index()]==false){
                                /** buscamos los datos de los referidos realizadas del sistema nuevo **/
                                __fnGetReferidosSisNew(__gblIdRow);
                                __gblArrIndex[ui.newTab.index()] = true;
                            }
                            break;
                        case 4:
                            if(__gblArrIndex[ui.newTab.index()]==false){
                                /** buscamos los datos de las devoluciones realizadas del sistema nuevo **/
                                fnGetPlanPagosSisNew(__gblIdRow);
                                __gblArrIndex[ui.newTab.index()] = true;
                            }
                            break;
                    }
                },
                active:1
            });
        }
        
        /** otras funciones ***************************************************/
        /****/
        var __fnSearchRows = function(var_caption){
            $('#divPreload').preload('Cargando Informaci&oacute;n...');
            /* grilla*/
           jQuery("#griddatoscx").jqGrid({ 
               url:__fnGetUrlGrilla(),
               datatype: "json", 
               colNames:['id','NOMBRES','APELLIDOS','IDENTIFICACION','CELULAR','F. CUMPLE','','CREADO POR','F. CREACION'],
               colModel:[ 
                   {name:'id',index:'id', width:55,editable:false,hidden:true},
                   {name:'nombres',index:'nombres',align:'left',editable: true},
                   {name:'apellidos',index:'apellidos',align:'left',editable: true},
                   {name:'identificacion',index:'identificacion',align:'left',editable: true},
                   {name:'celular',index:'celular',align:'left',editable: true},
                   {name:'f_cumple',index:'f_cumple',align:'left',editable: true},
                   {name:'show_historial_sh',index:'show_historial_sh',align:'left',editable: false,align:"center",width:50,search:false,formatter:__fnFormatShowHistorial},
                   {name:'creado_por_fk6str',index:'creado_por_fk6str', align:"center",editable: true},
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
               rowList:[250,100,200,300,500],
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
            
            return true;
        }
        
        /***/
        var __fnFormatShowHistorial = function(cellvalue, options, rowObject){
            return "<a id='aShowHist-"+rowObject[0]+"' href='javascript:void(0)' onclick='myProyecto.fnShowHistorialCliente(\"fnShowHistorialCliente\",\""+rowObject[0]+"\",\""+rowObject[1]+" "+rowObject[2]+"\")'><img src='images/search-strong.png' title='Mostrar Historial Cliente'/></a>";
        }
        
        /****/
        var __fnGetUrlGrilla = function(){
            return $.fn.encoded_url_controller('clientes/gestion/select_rows_grilla');
        }
        
        /***/
        var __fnGestionReferido = function(ex_s){
            if(ex_s==undefined){
                ex_s = '';
            }
            $('#trDatosReferido'+ex_s).hide();
            /**/
            $('#txtOrReferido_1'+ex_s).hide();
            $('#txtOrReferido_2'+ex_s).hide();
            $('#txtOrReferido_2'+ex_s).hide();
            $('#lblNameOrReferido_1'+ex_s).hide();
            /**/
            $('#txtOrReferido_1'+ex_s).val('');
            $('#hdnOrReferido_1'+ex_s).val('');
            $('#txtOrReferido_2'+ex_s).val('');
            $('#txtOrReferido_2'+ex_s).val('');
            $('#lblNameOrReferido_1'+ex_s).html('');
            $('#lblOrReferido_1'+ex_s).hide();
            $('#lblOrReferido_2'+ex_s).hide();
            if(parseInt($('#selOrigenReferido'+ex_s).val())==1){
                $('#trDatosReferido'+ex_s).show();
                $('#lblOrReferido_1'+ex_s).show();
                $('#txtOrReferido_1'+ex_s).show();
                $('#lblNameOrReferido_1'+ex_s).show();
            }

            if(parseInt($('#selOrigenReferido'+ex_s).val())==5){
                $('#trDatosReferido'+ex_s).show();
                $('#lblOrReferido_2'+ex_s).show();
                $('#txtOrReferido_2'+ex_s).show();
            }
        }
        
        /**/
        var __fnGuardarCambios = function(bol_ex){
            /** verificamos q form validar */
            if(bol_ex==undefined){
                if(!$.fn.validateForm("frmAddEdit",true)){
                    return;
                }
            }else{
                if(!$.fn.validateForm("frmTabHC1",true)){
                    return;
                }
            }
            var url_event = '';
            if(bol_ex==undefined){
                if($('#hdnid').val()==''){
                    url_event = 'create_row';
                }else{
                    url_event = 'update_row';
                }
            }else{
                if($('#hdnid_TabHC1').val()==''){
                    url_event = 'create_row';
                }else{
                    url_event = 'update_row';
                }
            }
            $.ajax({
                url: $.fn.encoded_url_controller('clientes/gestion/'+url_event),
                type:'POST',
                data: (bol_ex==undefined?$('#frmAddEdit').serialize():$('#frmTabHC1').serialize()),
                dataType:'json',
                beforeSend:function(){
                    if(bol_ex){
                        $().preload('Guardando Cambios...',true);
                    }else{
                        $('#divPreload_TabHC1').preload('Guardando Cambios...',true);
                    }
                },
                error:function(jqXHR){$.fn.validate_error(jqXHR)},
                success: function(msg){
                    if($.fn.validateResponse(msg)){
                        alert(msg.msj);
                        if(bol_ex==undefined){
                            location.reload();
                        }else{
                            $('#divPreload_TabHC1').html('');
                        }
                    }
                }
            });
        }
        
        /**/
        var __fnGetDatosRows = function(id,ex_s){
            $.ajax({
                url: $.fn.encoded_url_controller('clientes/gestion/select_datos_clientes_row'),
                type:'POST',
                data: 'idrow='+id,
                dataType:'json',
                beforeSend:function(){
                    if(ex_s==''){$('#divPreloadAddEdit').preload('Cargando Datos...');}
                    else{$('#divPreload'+ex_s).preload('Cargando Datos...');}
                },
                error:function(jqXHR){$.fn.validate_error(jqXHR)},
                success: function(msg){
                    if($.fn.validateResponse(msg,(ex_s==''?'divPreloadAddEdit':'divPreload'+ex_s))){
                        $('#selTipoDocumento_clientes'+ex_s).val(msg.rows[0].tipo_identificacion);
                        $('#txtIdentificacion_clientes'+ex_s).val(msg.rows[0].identificacion);
                        $('#txtNombres_clientes'+ex_s).val(msg.rows[0].nombres);
                        $('#txtApellidos_clientes'+ex_s).val(msg.rows[0].apellidos);
                        $('#txtFecha_Nacimiento_clientes'+ex_s).val(msg.rows[0].dia_nac);
                        $('#txtFecha_Nacimiento_clientes_mes'+ex_s).val(msg.rows[0].mes_nac);
                        $('#selEstado_clientes'+ex_s).val(msg.rows[0].estado);
                        /***/
                        $('#txtTelfono_fijo_clientes'+ex_s).val(msg.rows[0].telefono_fijo);
                        $('#txtTelefono_celular_clientes'+ex_s).val(msg.rows[0].telefono_celular);
                        $('#txtTelefono_celular_whatsapp_clientes'+ex_s).val(msg.rows[0].telefono_celular_whatsapp);
                        $('#txtEmail_clientes'+ex_s).val(msg.rows[0].email);
                        /***/
                        $('#txtDireccion_clientes'+ex_s).val(msg.rows[0].direccion);
                        $('#txtBarrio_clientes'+ex_s).val(msg.rows[0].barrio);
                        $('#txtCiudad_n_clientes'+ex_s).val(msg.rows[0].nombre_ciudad);
                        $('#hdnCiudad_clientes'+ex_s).val(msg.rows[0].parametros_ciudades_id);
                        $('#selZona_clientes'+ex_s).val(msg.rows[0].zona);
                        /***/
                        $('#selOrigenReferido'+ex_s).attr('disabled',true);
                        $('#txtOrReferido_2'+ex_s).attr('disabled',true);
                        $('#txtOrReferido_1'+ex_s).attr('disabled',true);
                        if(msg.rows[0].origen_referido!=null){
                            $('#selOrigenReferido'+ex_s).val(msg.rows[0].origen_referido);
                            __fnGestionReferido(ex_s);
                            if(msg.rows[0].origen_referido_otro!=null){
                                $('#txtOrReferido_2'+ex_s).val(msg.rows[0].origen_referido_otro);
                            }else{
                                if(msg.rows[0].origen_referido_cliente_id!=null){
                                    $('#txtOrReferido_1'+ex_s).val(msg.rows[0].nombre_referidor);
                                    $('#lblNameOrReferido_1'+ex_s).val(msg.rows[0].nombre_referidor_completo);
                                }
                            }
                        }
                        /***/
                        $('#hdnid'+ex_s).val(id);
                        /**inicamos el autocomplete de ciudad*/
                        __ini_autocomplete_ciudad();
                    }
                }
            });
        }
        
        /***metodo main********************************************************/
        /**metodo principal***/
        return {
            main: function(){
                /**eventos**/
                __ini_bttn_tab_ch1($('#bttnTabHC1'));
                
                /**llamados iniciales**/
                __fnSearchRows("Clientes");
                return true;
            },
            
            /**/
            fnAddRows : function(){
                document.getElementById('frmAddEdit').reset();
                $('#hdnid').val('');
                /**/
                $('#selOrigenReferido').attr('disabled',false);
                $('#txtOrReferido_2').attr('disabled',false);
                $('#txtOrReferido_1').attr('disabled',false);
                $('#selOrigenReferido_TabHC1').attr('disabled',false);
                $('#txtOrReferido_2_TabHC1').attr('disabled',false);
                $('#txtOrReferido_1_TabHC1').attr('disabled',false);
                /***/
                __ini_dialog_add_edit_cliente({autoOpen:true});
                /**inicamos el autocomplete de ciudad*/
                __ini_autocomplete_ciudad();
                /**iniciamos el autocomplete de referido**/
                __ini_autocomplete_referido();
                /**iniciamos el evento del change referido*/
                __ini_sel_change_referido();
            },
            
            /**/
            fnEditRows : function(){
                document.getElementById('frmAddEdit').reset();

                if(__gblIdRow!==null){
                    __ini_dialog_add_edit_cliente({autoOpen:true});
                    __fnGetDatosRows(__gblIdRow,'');
                }else{
                    alert('Debe seleccionar la fila que quiere editar');
                }
            },
        
            /***/
            fnDelRows : function(){
                if(__gblIdRow!==null){
                    if(confirm('Realmente desea borrar este registro?')){
                        $.ajax_frm({
                            url: $.fn.encoded_url_controller('clientes/gestion/delete_row'),
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
        
            /***otros metodos***************************************************/
            /** funcion para mostrar el poup del historial del cliente */
            fnShowHistorialCliente : function(idcliente,n_cli){
                __gblIdRow = idcliente;
                __ini_dialog_historial_cliente({autoOpen:true});
                $('#divShowHistorialCliente').dialog("option","title","Datos Cliente: ("+n_cli.toUpperCase()+")");

                /** buscamos los datos de las facturas realizadas del sistema nuevo **/
                //__fnGetHisFacturasSisNew(__gblIdRow);
            }
        }
    })();
    /* iniciamos objeto**/
    myProyecto.main();
});