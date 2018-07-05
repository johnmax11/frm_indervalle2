var myProyecto = null;
$(document).ready(function() {
    myProyecto = (function() {
        var __gblIdRow = null;
        var __gblContadorFila = 0;


        /***/
        var __fnGetUrlGrilla = function() {
            return $.fn.encoded_url_controller('inventarios/gestion/select_rows_grilla'); // modulo, programa y bttn action
        }

        /***/
        var __ini_dialog_add_edit = function(autoOpen) {
            /* dialogos */
            $("#divAddEdit").dialog({
                autoOpen: false,
                modal: true,
                draggable: true,
                resizable: true,
                width: 700,
                buttons: {
                    "Guardar Cambios": function() {
                        __fnGuardarCambios();
                    },
                    "Cerrar": function() {
                        $(this).dialog('close');
                        __fnCleanForm("frmAddEdit");
                    }
                },
                close: function() {
                    __fnCleanForm("frmAddEdit");
                }
            });
            /***/
            if (autoOpen) {
                $("#divAddEdit").dialog('open');
                //fnSetFilasInventarios();
                __fnSetFilasInventarios();
            }
        }

        /**/
        var __fnSearchRows = function(var_caption) {
            $('#divPreload').preload('Cargando Informaci&oacute;n...');
            /* grilla*/
            jQuery("#griddatoscx").jqGrid({
                url: __fnGetUrlGrilla(),
                datatype: "json",
                colNames: ['id', 'REFERENCIA', 'DESCRIPCION', 'CANTIDAD', 'CREADO POR', 'FECHA CREACION'],
                colModel: [
                    {name: 'id', index: 'id', width: 55, editable: false, hidden: true},
                    {name: 'referencia', index: 'referencia', align: 'left', editable: true},
                    {name: 'descripcion', index: 'descripcion', align: 'left', editable: true},
                    {name: 'cantidad', index: 'cantidad', align: 'left', editable: true, formatter: __fnFormatDetalles},
                    {name: 'creado_por', index: 'creado_por', align: "center", editable: true},
                    {name: 'fecha_creacion', index: 'fecha_creacion', align: "center", editable: true}
                ],
                loadError: function(xhr, status, error) {
                    var msg = eval(xhr.responseText);
                    alert('Error: En el response de la grilla ---> ');
                },
                onSelectRow: function(id) {
                    if (id != null) {
                        __gblIdRow = id;
                    }
                },
                loadComplete: function() {
                    $('#divPreload').html('');
                },
                rowNum: 250,
                rowList: [50, 100, 200, 300, 500],
                pager: '#pagerdatoscx',
                sortname: 'nombres',
                width: 1000,
                height: 300,
                viewrecords: true,
                rownumbers: true,
                sortorder: "asc",
                caption: var_caption
            });
            $.fn.config_grilla(jQuery("#griddatoscx"));
            $.fn.add_crud_grilla(jQuery("#griddatoscx"), true, false, false, false, undefined, myProyecto);
        }
        //
        var __fnFormatDetalles = function(cantidad, objModelo) {
            if (cantidad != '') {
                return "<a href='javascript:void(0)' onclick='myProyecto.fnGetDetallesInventarios(" + objModelo['rowId'] + ")'>" + cantidad + "</a>";
            }
            else {
                return cantidad;
            }
        }

        var __fnGetInventariosDetalles = function(idInventariosPrincipal) {
            $.ajax_frm({
                url: $.fn.encoded_url_controller('inventarios/gestion/select_inventarios_detalles'),
                params: {
                    myProyecto: myProyecto
                },
                data: {
                    idInventariosPrincipal: idInventariosPrincipal
                },
                success: function(params, msg) {

                    $.fn.create_grilla_tmp(
                            {
                                page_use: false,
                                div_destino: "frmDialog_divDialogDetalleInventarios",
                                width: 600,
                                data: msg.rows,
                                height: 200,
                                create_dialog:true
                            }
                    );
                    /*for (i = 0; i < msg.rows.length; i++) {
                     params.myProyecto.__gblArrBodegas[i] = [msg.rows[i].id, msg.rows[i].nombre];
                     }
                     */
                }
            });
        }

        /**/

        var __fnGetBodegas = function(idSelect) {

            $.ajax_frm({
                url: $.fn.encoded_url_controller('inventarios/gestion/select_bodegas'),
                params: {
                    myProyecto: myProyecto
                },
                success: function(params, msg) {

                    for (i = 0; i < msg.rows.length; i++) {
                        params.myProyecto.__gblArrBodegas[i] = [msg.rows[i].id, msg.rows[i].nombre];
                    }
                }
            });
        }
        var __fnGuardarCambios = function() {
            if (!$.fn.validateForm("frmAddEdit", true)) {
                return;
            }
            /***/
            $.show_confirm_dialog({
                msj:"Realmente desea guardar los cambios?",si:function(){__fnSendDatosInventario()}
            });
        }

        var __fnGetDatosRows = function(id) {
            $.ajax_frm({
                url: $.fn.encoded_url_controller('productos/gestion/select_producto_by_id'),
                data: 'idrow=' + id,
                params: {id: id},
                success: function(params, msg) {
                    //AQUI SE LLENA LOS DATOS BASICOS DEL PRODUCTO //
                    $("#hdnid").val(params.id);
                    $("#txtreferencia").val(msg.rows[0].nombres);
                    $("#txtreferencia").prop('disabled', true);
                    /***/
                    $("#selProductos_categorias").val(msg.rows[0].productos_categorias_id);
                    $("#txtDescripcion").val(msg.rows[0].descripcion);
                    $("#txtvalor_compra").val(parseInt(msg.rows[0].valor_compra));
                    $("#txtvalor").val(parseInt(msg.rows[0].valor_final));
                    if (msg.rows[0].imagen != null) {
                        $("#espacioimagen").prop('src', 'files/img_productos/' + msg.rows[0].imagen);
                    }
                }
            });
        }


        var __fnSetFilasInventarios = function(nuevaFila) {

            strhtml = "<tr id='fila" + __gblContadorFila + "'>";
            strhtml += "   <td class='ui-state-default ui-th-column ui-th-ltr'>";
            strhtml += "      " + (__gblContadorFila + 1);
            strhtml += "   </td>";
            strhtml += "   <td>";
            strhtml += "       <input type='text' name='txtReferencia" + __gblContadorFila + "' id='txtReferencia" + __gblContadorFila + "' style='width:100%;' class='validate'>";
            strhtml += "       <input type='hidden' id='hdntxtReferencia" + __gblContadorFila + "' name='hdntxtReferencia" + __gblContadorFila + "' >";
            strhtml += "   </td>";
            strhtml += "   <td>";
            strhtml += "        <span id='labeltxtReferencia" + __gblContadorFila + "' style='background-color: #66FF66;' class='camposGrandes'></span>";
            strhtml += "   </td>";
            strhtml += "   <td>";
            strhtml += "       <input type='text' name='txtCantidad" + __gblContadorFila + "' style='width:100%;' class='validate camposPequenios'>";
            strhtml += "   </td>";
            strhtml += "   <td>";
            strhtml += "       <select id='selInventariosBodegas" + __gblContadorFila + "' name='selInventariosBodegas" + __gblContadorFila + "' class='validate' style='width:100%;'>";
            for (i = 0; i < myProyecto.__gblArrBodegas.length; i++) {
                strhtml += "       <option value='" + myProyecto.__gblArrBodegas[i][0] + "'>" + myProyecto.__gblArrBodegas[i][1] + "</option>";
            }
            strhtml += "       </select>";
            strhtml += "   </td>";
            strhtml += "   <td>";
            strhtml += "       <span class='ui-icon ui-icon-plusthick' onclick='myProyecto.fnSetFilasInventarios(\"nueva\")'>";
            strhtml += "   </td>";
            if (nuevaFila != undefined) {

                strhtml += "   <td>";
                strhtml += "       <span class='ui-icon ui-icon-closethick' onclick='myProyecto.fnBorrarFila(\"fila" + __gblContadorFila + "\")'>";
                strhtml += "   </td>";
            }
            strhtml += "</tr>";
            $(strhtml).appendTo("#tBodyInventario");
            __fnGetAutoComplete("txtReferencia" + __gblContadorFila);
            $("#contador").val(__gblContadorFila);
            __gblContadorFila++;


        }

        var __fnBorrarFila = function(idFila) {
            $("#" + idFila).remove();
        }

        var __fnGetAutoComplete = function(idCampo) {
            $("#" + idCampo).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: $.fn.encoded_url_controller('inventarios/gestion/select_rows_autocomplete'),
                        dataType: "json",
                        type: "POST",
                        data: 'term=' + request.term,
                        success: function(data) {
                            response(data);
                            //descripcion
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $("#hdn" + idCampo).val(ui.item.id);
                    $("#label" + idCampo).html(ui.item.descripcion);
                },
                search: function(event, ui) {
                    $("#hdn" + idCampo).val('');
                    $("#label" + idCampo).html('');
                }
            });

        }

        var __fnCleanForm = function(idForm) {
            $("#" + idForm).each(function() {
                this.reset();
            });
            $("#tBodyInventario").empty();
            __gblContadorFila = 0;
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __fnSendDatosInventario = function() {
            /***/
            if (__gblIdRow == null) {
                $.ajax_frm({
                    url: $.fn.encoded_url_controller('inventarios/gestion/create_row'),
                    data: $("#frmAddEdit").serialize(),
                    success: function(params, msg) {
                        alert("Proceso terminado correctamente");
                        location.reload();
                    }
                });
            } else {
                $('#frmAddEdit').prop('action', $.fn.encoded_url_controller('productos/gestion/update_row'));
            }
            //$('#frmAddEdit').submit();
        }


        /***metodo main********************************************************/
        /**metodo principal***/
        return {
            __gblArrBodegas: [],
            main: function() {
                /**eventos**/

                /**llamados iniciales**/
                __fnSearchRows("Inventarios");
                __fnGetBodegas();

                return true;
            },
            fnAddRows: function() {
                __ini_dialog_add_edit({autoOpen: true});
                document.getElementById('frmAddEdit').reset();
                $('#hdnid').val('');
                __gblIdRow = null;
                $("#txtreferencia").prop('disabled', false);
            },
            /**/
            fnEditRows: function() {
                document.getElementById('frmAddEdit').reset();

                if (__gblIdRow !== null) {
                    __ini_dialog_add_edit({autoOpen: true});
                    __fnGetDatosRows(__gblIdRow);
                } else {
                    alert('Debe seleccionar la fila que quiere editar');
                }
            },
            /***/
            fnDelRows: function() {
                if (__gblIdRow !== null) {
                    if (confirm('Realmente desea borrar este registro?')) {
                        $.ajax_frm({
                            url: $.fn.encoded_url_controller('productos/gestion/delete_row'),
                            data: 'idrow=' + __gblIdRow,
                            success: function(params, msg) {
                                alert(msg.msj);
                                location.reload();
                            }
                        });
                    }
                } else {
                    alert('Debe seleccionar la fila que quiere borrar');
                }
            },
            fnSetFilasInventarios: function(nuevaFila) {
                __fnSetFilasInventarios(nuevaFila);
            },
            fnBorrarFila: function(idFila) {
                __fnBorrarFila(idFila);
            },
            fnSetArr: function(iteracion, idBodega, nombreBodega) {
                __setArray(iteracion, idBodega, nombreBodega)
            },
            fnGetDetallesInventarios: function(idInventariosPrincipal) {
                __fnGetInventariosDetalles(idInventariosPrincipal)
            }
        }
    })();
    /* iniciamos objeto**/
    myProyecto.main();

});