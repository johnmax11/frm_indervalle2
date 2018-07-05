var myProyecto = null;
$(document).ready(function(){
    myProyecto = (function(){
        var __gblIdRow = null;
        
        /***/
        var __fnGetUrlGrilla = function(){
            return $.fn.encoded_url_controller('seguridad/roles/searchroles');
        }
        
        /**/
        var __fnSearchRows = function(var_caption){
            $('#divPreload').preload('Cargando Informaci&oacute;n...');
            // grilla
           jQuery("#griddatoscx").jqGrid({ 
               url:__fnGetUrlGrilla(),
               datatype: "json", 
               colNames:['id','NOMBRE','CONFIGURAR','CREADO POR','F. CREACION'],
               colModel:[ 
                   {name:'id',index:'id', width:55,editable:false,hidden:true},
                   {name:'nombre',index:'nombre',align:'left',editable: true},
                   {name:'configurar',index:'configurar',align:'center',editable: true,formatter:__fnFormatConfigurar},
                   {name:'creado_por',index:'creado_por', align:"center",editable: true},
                   {name:'fecha_creacion',index:'fecha_creacion', align:"center",editable: true},
               ],
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
        
        /**/
        var __fnGetDatosRows = function(idrol){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('seguridad/roles/searchdatosrows'),
                data: 'idrol='+idrol,
                success: function(params,msg){
                    $('#txtNombre_roles').val(msg.rows[0].nombre);
                    $('#selEstadoRoles').val(msg.rows[0].estado);
                    $('#hdnid').val(msg.rows[0].id);
                }
            });
        }
        
        /**/
        var __fnGuardarCambios = function(){
            if(!__fnValidaCampos()){
                return;
            }
            $.ajax_frm({
                url: $.fn.encoded_url_controller('seguridad/roles/create_row'),
                data: $('#frmAddEdit').serialize(),
                success: function(params,msg){
                    alert(msg.msj);
                    location.reload();
                }
            });
        }
        
        /**/
        var __fnValidaCampos = function(){
            if($('#txtNombre_roles').val().trim()===''){
                alert('El nombre del (ROL) no puede ser vacio');
                $('#txtNombre_roles').focus();
                return false;
            }
            return true;
        }
        
        /**/
        var __fnFormatConfigurar = function(cellvalue, options, rowObject){
            return "<a href='javascript:void(0)' onclick='myProyecto.fnConfigurarAccesosRol("+rowObject[0]+")'>Configurar</a>";
        }
        
        /**/
        var __fnGuardarConfiguracion = function(){
            // validamos campos //
            if(!$.fn.validateForm("frmConfigRoles")){
                return;
            }

            $.ajax({
                url: $.fn.encoded_url_controller('seguridad/roles/guardarconfiguracion'),
                type:'POST',
                data: $('#frmConfigRoles').serialize(),
                dataType:'json',
                beforeSend:function(){
                    __ini_dialog_config_roles();
                    $('#divPreloadConfigAccesos').preload('Guardando Datos...');
                },
                error:function(jqXHR,textStatus,errorThrown){
                    $.fn.errorResponse(jqXHR.responseText,true);
                },
                success: function(msg){
                    if($.fn.validateResponse(msg,'divPreloadConfigAccesos')){
                        alert(msg.msj);
                    }
                }
            });
        }
        
        /****/
        var __ini_dialog_config_roles = function(autoOpen){
            $("#divConfigAccesos").dialog({
                autoOpen: false,
                modal: true,
                draggable: true,
                resizable:true,
                width:600,
                buttons:{
                    "Guardar Configuracion":function(){
                        __fnGuardarConfiguracion();
                    },
                    "Cerrar":function(){
                        $(this).dialog('close');
                    }
                }
            }).parent('.ui-dialog').find('.ui-dialog-titlebar-close').remove();
            /***/
            if(autoOpen){
                $("#divConfigAccesos").dialog('open');
            }
        }




        
        /****/
        return {
            main: function(){
                // search //
                __fnSearchRows("Roles");
            },
            
            /**/
            fnAddRows : function(){
                __ini_dialog_add_edit(true);
                document.getElementById('frmAddEdit').reset();
                $('#hdnid').val('');
            },
            
            /**/
            fnEditRows : function(){
                document.getElementById('frmAddEdit').reset();
                if(__gblIdRow!=null){
                    __ini_dialog_add_edit(true);
                    __fnGetDatosRows(__gblIdRow);
                }else{
                    alert('Debe seleccionar la fila que quiere editar');
                }
            },
            
            /**/
            fnDelRows : function(){
                if(__gblIdRow!=null){
                    if(confirm('Realmente desea borrar este rol?')){
                        $.ajax_frm({
                            url: $.fn.encoded_url_controller('seguridad/roles/delete_rows'),
                            data: 'idrol='+__gblIdRow,
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
            
            /**/
            fnConfigurarAccesosRol : function(idrol){
                $.ajax({
                    url: $.fn.encoded_url_controller('seguridad/roles/cargaraccesosbyrol'),
                    data: 'idrol='+idrol,
                    type:'POST',
                    dataType:'json',
                    beforeSend:function(){
                        __ini_dialog_config_roles(true);
                        $('#divPreloadConfigAccesos').preload('Cargando Datos...');
                    },
                    success: function(msg){
                        if($.fn.validateResponse(msg,'divPreloadConfigAccesos')){
                            if(msg!=null && msg.rows!=null){

                                var strHtml = '';

                                strHtml += "<table cellspacing=0 width=100%>";
                                strHtml += "<tr>";
                                strHtml += "    <th>NOMBRE</th>";
                                strHtml += "    <th width=10% >VISIBLE</th>";
                                strHtml += "    <th width=10% >CREAR</th>";
                                strHtml += "    <th width=10% >LEER</th>";
                                strHtml += "    <th width=10% >ACTUALIZAR</th>";
                                strHtml += "    <th width=10% >BORRAR</th>";
                                strHtml += "</tr>";
                                $('#hdncantrows').val(msg.rows.length);
                                for(var i=0;i<msg.rows.length;i++){
                                    // verificamos si es un modulo //
                                    if(msg.rows[i].seguridad_programas_id===null){
                                       strHtml += "<tr>";
                                       strHtml += "     <th style='border-style:solid; border-width:1px; color:blue;'>";
                                       strHtml += "         <input type='hidden' id='hdnid-"+i+"' name='hdnid-"+i+"' value='"+msg.rows[i].id+"'/>";
                                       strHtml += "         *&nbsp;"+msg.rows[i].seguridad_modulos_nombre+"&nbsp;*";
                                       strHtml += "     </th>";
                                       strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>&nbsp;<select id='selSINO-"+i+"-1' name='selSINO-"+i+"-1' class='bttn_class validate'>"+myProyecto.fnGetHtmlSelectSINO(msg.rows[i].visible)+"</select>&nbsp;</th>";
                                       strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>&nbsp;<select id='selSINO-"+i+"-2' name='selSINO-"+i+"-2' class='bttn_class validate'>"+myProyecto.fnGetHtmlSelectSINO(msg.rows[i].insertar)+"</select>&nbsp;</th>";
                                       strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>&nbsp;<select id='selSINO-"+i+"-3' name='selSINO-"+i+"-3' class='bttn_class validate'>"+myProyecto.fnGetHtmlSelectSINO(msg.rows[i].seleccionar)+"</select>&nbsp;</th>";
                                       strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>&nbsp;<select id='selSINO-"+i+"-4' name='selSINO-"+i+"-4' class='bttn_class validate'>"+myProyecto.fnGetHtmlSelectSINO(msg.rows[i].actualizar)+"</select>&nbsp;</th>";
                                       strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>&nbsp;<select id='selSINO-"+i+"-5' name='selSINO-"+i+"-5' class='bttn_class validate'>"+myProyecto.fnGetHtmlSelectSINO(msg.rows[i].borrar)+"</select>&nbsp;</th>";
                                       strHtml += "</tr>";
                                    }else{
                                        strHtml += "<tr>";
                                        // agregamos los permisos //
                                        strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>";
                                        strHtml += "        <input type='hidden' id='hdnid-"+i+"' name='hdnid-"+i+"' value='"+msg.rows[i].id+"'/>";
                                        strHtml += "        &nbsp;"+msg.rows[i].seguridad_programas_nombre+"&nbsp;";
                                        strHtml += "     </th>";
                                        strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>&nbsp;<select id='selSINO-"+i+"-1' name='selSINO-"+i+"-1' class='bttn_class validate'>"+myProyecto.fnGetHtmlSelectSINO(msg.rows[i].visible)+"</select>&nbsp;</th>";
                                        strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>&nbsp;<select id='selSINO-"+i+"-2' name='selSINO-"+i+"-2' class='bttn_class validate'>"+myProyecto.fnGetHtmlSelectSINO(msg.rows[i].insertar)+"</select>&nbsp;</th>";
                                        strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>&nbsp;<select id='selSINO-"+i+"-3' name='selSINO-"+i+"-3' class='bttn_class validate'>"+myProyecto.fnGetHtmlSelectSINO(msg.rows[i].seleccionar)+"</select>&nbsp;</th>";
                                        strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>&nbsp;<select id='selSINO-"+i+"-4' name='selSINO-"+i+"-4' class='bttn_class validate'>"+myProyecto.fnGetHtmlSelectSINO(msg.rows[i].actualizar)+"</select>&nbsp;</th>";
                                        strHtml += "     <th width=10% style='border-style:solid; border-width:1px;'>&nbsp;<select id='selSINO-"+i+"-5' name='selSINO-"+i+"-5' class='bttn_class validate'>"+myProyecto.fnGetHtmlSelectSINO(msg.rows[i].borrar)+"</select>&nbsp;</th>";
                                        strHtml += "</tr>";
                                    }
                                }
                                strHtml += "</table>";
                                $('#divHtmlConfigRoles').html(strHtml);
                            }
                        }
                    }
                });
            },
            
            /**/
            fnGetHtmlSelectSINO : function(sino){
                var strHtml = "";
                strHtml += "<option value='S' "+(sino==='S'?'selected="selected"':'')+">SI</option>";
                strHtml += "<option value='N' "+(sino==='N'?'selected="selected"':'')+">NO</option>";
                return strHtml;
            }
        

        }
        
    })();
    /* iniciamos objeto**/
    myProyecto.main();
});