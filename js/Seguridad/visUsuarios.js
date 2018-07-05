var myProyecto = null;
$(document).ready(function(){
    myProyecto = (function(){
        var __gblIdRow = null;
        
        /***/
        var __fnGetUrlGrilla = function(){
            return $.fn.encoded_url_controller('seguridad/usuarios/searchrows');
        }
        
        /**/
        var __fnSearchRows = function(var_caption){
            $('#divPreload').preload('Cargando Informaci&oacute;n...');
            // grilla
           jQuery("#griddatoscx").jqGrid({ 
               url:__fnGetUrlGrilla(),
               datatype: "json", 
               colNames:['id','USUARIO','NOMBRE','CREADO POR','F. CREACION'],
               colModel:[ 
                   {name:'id',index:'id', width:55,editable:false,hidden:true},
                   {name:'usuario',index:'usuario',align:'left',editable: true},
                   {name:'nombre',index:'nombre',align:'left',editable: true},
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
                __ini_event_chk_reset_password();
                __ini_blur_usuario();
            }
        }
        
        var __ini_event_chk_reset_password = function(){
            // eventos //
            $('#chkResetPassword').click(function(){
                if($('#hdnid').val()!=''){
                    if(confirm('Realmente desea resetear el password del usuario?')){
                        __fnResetPassword($('#hdnid').val());
                    }else{
                        $('#chkResetPassword').attr('checked',false);
                    }
                }
            });
        }
        
        var __ini_blur_usuario = function(){
            ///// validar nombre de usuario /////
            $('#txtUsuario_usuario').blur(function(){
                __fnValidar_nombre_usuario(this);
            });
        }
        
        /**/
        var __fnGetDatosRows = function(idrol){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('seguridad/usuarios/searchdatosrows'),
                data: 'idrow='+idrol,
                success: function(params,msg){
                    $('#txtUsuario_usuario').attr('disabled',true);
                    $('#txtUsuario_usuario').val(msg.rows[0].usuario);
                    $('#txtNombre_usuario').val(msg.rows[0].nombre);
                    $('#selEstado_usuario').val(msg.rows[0].estado);
                    $('#txtApellidos_usuario').val(msg.rows[0].apellido);
                    $('#txtEmail_usuario').val(msg.rows[0].email);
                    $('#selSeguridad_roles').val(msg.rows[0].seguridad_roles_id);
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
                url: $.fn.encoded_url_controller('seguridad/usuarios/addeditrows'),
                data: $('#frmAddEdit').serialize(),
                success: function(params,msg){
                    alert(msg.msj);
                    location.reload();
                }
            });
        }
        
        /***/
        var __fnSearchSeguridad_roles = function(){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('seguridad/usuarios/searchroles'),
                success: function(params,msg){
                    $.fn.set_combo(msg.rows,'selSeguridad_roles','Seleccione...',true);
                }
            });
        }
        
        /**
        * validar el nombre de usuario en el sistema para verificar que no se vaya a 
        * ingresar duplicado
        **/
       var __fnValidar_nombre_usuario = function(obj){
            if($(obj).val().trim()==''){
               return;
            }
            $.ajax_frm({
               url: $.fn.encoded_url_controller('seguridad/usuarios/validar_usuario'),
               data: 'ajax_nombre_u='+$(obj).val(),
               beforeSend:function(){
                   $('#img_rsp_'+$(obj).attr('id')).remove();
                   $(obj).after_animation();
               },
               params:{obj:obj},
               success: function(params,msg){
                    if($.fn.validateResponse(msg)){
                       var strHtml = "";
                       if(msg.existe == false){
                           strHtml += "<img id='img_rsp_"+$(params.obj).attr('id')+"' src='images/ok_peque.png' title='Nombre De Usuario Disponible'/>";
                       }else{
                           strHtml += "<img id='img_rsp_"+$(params.obj).attr('id')+"' src='images/remove.gif' title='Nombre De Usuario No Disponible'/>";
                           $(params.obj).val('');
                           $(params.obj).focus();
                       }
                       $(params.obj).after(strHtml);
                    }
               }
           });
        }
        
        /**
        * resetea el password del usuario enviando un email
        **/
        var __fnResetPassword = function(idu){
           $.ajax_frm({
               url: $.fn.encoded_url_controller('seguridad/usuarios/reset_password'),
               data: 'ajax_id_u='+idu,
               success: function(params,msg){
                    alert(msg.msj);
                    $('#chkResetPassword').attr('checked',false);
               }
           });
        }

        /****/
        return {
            main: function(){
                // search //
                __fnSearchRows("Usuarios");
                // search productos //
                __fnSearchSeguridad_roles();
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
                            url: $.fn.encoded_url_controller('seguridad/usuarios/delete_rows'),
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