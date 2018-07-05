var myProyecto = null;
$(document).ready(function(){
    myProyecto = (function(){
        var __gblIdRow = null;
        
        /***/
        var __fnGetUrlGrilla = function(){
            return $.fn.encoded_url_controller('seguridad/programas/searchrows');
        }
        
        /**/
        var __fnSearchRows = function(var_caption){
            $('#divPreload').preload('Cargando Informaci&oacute;n...');
            // grilla
            jQuery("#griddatoscx").jqGrid({ 
               url:__fnGetUrlGrilla(),
               datatype: "json", 
               colNames:['id','NOMBRE','MODULO','CREADO POR','F. CREACION'],
               colModel:[ 
                   {name:'id',index:'id', width:55,editable:false,hidden:true},
                   {name:'nombre',index:'nombre',align:'left',editable: true},
                   {name:'modulo',index:'modulo',align:'left',editable: true,sorteable:false},
                   {name:'creado_por',index:'creado_por', align:"center",editable: true},
                   {name:'fecha_creacion',index:'fecha_creacion', align:"center",editable: true}
               ],
               loadError:function(xhr,status,error){
                   var msg = eval(xhr.responseText);
                   alert(msg.msj);
               },
               onSelectRow: function(id){
                   if(id!=null){
                       __gblIdRow = id;
                   }
               },
               loadComplete:function(){
                   $('#divPreload').html('');
               },
               rowNum:500, 
               rowList:[50,100,200,300,500],
               pager: '#pagerdatoscx', 
               sortname: 'nombre',
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
        
        /**/
        var __fnGetDatosRows = function(idrol){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('seguridad/programas/searchdatosrows'),
                data: 'idrow='+idrol,
                success: function(params,msg){
                    $('#txtNombre_programa').val(msg.rows[0].nombre);
                    $('#selEstado_usuario').val(msg.rows[0].estado);
                    $('#selSeguridad_modulos').val(msg.rows[0].seguridad_modulos_id);
                    $('#txtClass_imagen').val(msg.rows[0].imagen);
                    $('#hdnid').val(msg.rows[0].id);
                }
            });
        }
        
        /**/
        var __fnGuardarCambios = function(){
            if(!$.fn.validateForm("frmAddEdit")){
                return;
            }
            $.ajax_frm({
                url: $.fn.encoded_url_controller('seguridad/programas/addeditrows'),
                data: $('#frmAddEdit').serialize(),
                success: function(params,msg){
                    alert(msg.msj);
                    location.reload();
                }
            });
        }
        
        /***/
        var __fnSearchSeguridad_modulos = function(){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('seguridad/programas/search_modulos'),
                success: function(params,msg){
                    $.fn.set_combo(msg.rows,'selSeguridad_modulos','Seleccione...',true);
                }
            });
        }
        
        /***/
        var __ini_dialog_add_edit = function(autoOpen){
            // dialogos //
            $("#divAddEdit").dialog({
                autoOpen: false,
                modal: true,
                draggable: true,
                resizable:true,
                width:300,
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
        
        /****/
        return {
            main: function(){
                // search //
                __fnSearchRows("Programas");
                // search productos //
                __fnSearchSeguridad_modulos();
            },
            
            /**/
            fnAddRows : function(){
                __ini_dialog_add_edit(true);
                document.getElementById('frmAddEdit').reset();
                $('#txtUsuario_usuario').attr('disabled',false);
                $('#hdnid').val('');
            },
            /**/
            fnEditRows : function(){
                document.getElementById('frmAddEdit').reset();
                if(__gblIdRow!==null){
                    __ini_dialog_add_edit(true);
                    __fnGetDatosRows(__gblIdRow);
                }else{
                    alert('Debe seleccionar la fila que quiere editar');
                }
            },
            
            /**/
            fnDelRows : function(){
                if(__gblIdRow!==null){
                    if(confirm('Realmente desea borrar este registro?')){
                        $.ajax_frm({
                            url: $.fn.encoded_url_controller('seguridad/programas/delete_rows'),
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
            }
        }
        
    })();
    /* iniciamos objeto**/
    myProyecto.main();
});