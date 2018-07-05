var myProyecto = null;
$(document).ready(function () {
    myProyecto = (function () {
        var __gblIdRow = null;

        var __searchFacturas = function () {
            /* grilla*/
            jQuery("#griddatoscx").jqGrid({
                url: $.fn.encoded_url_controller('cxc/pagos/select_all_facturas_pendientes') + $.fn.get_datos_filtros('divFiltros_tab1'),
                datatype: "json",
                colNames: ['id', 'NOMBRE', 'IDENTIFICACION', 'DOCUMENTO', 'TOTAL', 'TOTAL PAGADO', 'SALDO', '', 'CREADO POR', 'FECHA CREACION'],
                colModel: [
                    {name: 'id', index: 'id', width: 55, editable: false, hidden: true},
                    {name: 'nombre', index: 'nombre', width: 200, editable: false, hidden: false},
                    {name: 'identificacion', index: 'identificacion', width: 150, editable: false, hidden: false},
                    {name: 'numero_factura', index: 'numero_factura', align: 'left', editable: true, formatter: __getFormatter},
                    {name: 'total', index: 'total', align: 'right', editable: true, formatter: 'integer'},
                    {name: 'total_pagado', index: 'total_pagado', align: 'right', editable: true, formatter: 'integer'},
                    {name: 'saldo', index: 'saldo', align: 'right', editable: true, formatter: 'integer'},
                    {name: 'pago', index: 'pago', align: 'center', editable: true, formatter: __getImageDocumento},
                    {name: 'creado_por', index: 'creado_por', align: "center", editable: true},
                    {name: 'fecha_creacion', index: 'fecha_creacion', align: "center", editable: true}
                ],
                loadError: function (xhr, status, error) {
                    var msg = eval(xhr.responseText);
                    alert('Error: En el response de la grilla ---> ');
                },
                rowNum: 100,
                rowList: [100, 200, 300, 500],
                pager: '#pagerdatoscx',
                sortname: 'fecha_creacion',
                width: 1000,
                height: 300,
                viewrecords: true,
                footerrow: true,
                userDataOnFooter: true,
                rownumbers: true,
                sortorder: "DESC",
                caption: "Pagos"
            });
            $.fn.config_grilla(jQuery("#griddatoscx"));
            $.fn.add_crud_grilla(jQuery("#griddatoscx"), false, false, false, false, undefined, myProyecto);
        }

        /**
         * @returns {undefined}
         */
        var __ini_dialog_registrar_pago = function (autoOpen) {
            $("#divAddEdit").dialog({
                autoOpen: false,
                modal: true,
                draggable: true,
                resizable: true,
                width: 900,
                buttons: {
                    "Guardar": function () {
                        __validar_envio_datos();
                    },
                    "Cerrar": function () {
                        $(this).dialog('close');
                    }
                },
                close: function () {
                }
            });
            /***/
            if (autoOpen == true) {
                $("#divAddEdit").dialog('open');
            }
        }

        var __getImageDocumento = function (cellvalue, options, rowObject) {
            return "<img src='images/money.png' alt='pagar' onclick='myProyecto.fnGetPlanPagos(" + options.rowId + ")'>";
            $("#hdnPagosFacturasPrincipal").val(options.rowId);
        }

        var __getFormatter = function (cellvalue, options, rowObject) {
            return "<a href='javascript:void(0)' onclick='myProyecto.fnGetPlanPagosConsulta(" + options.rowId + ")'>" + cellvalue + "</a>";
        }


        var __getPlanPagos = function (idPagosFacturasPrincipal) {
            $.ajax_frm({
                url: $.fn.encoded_url_controller('cxc/pagos/select_plan_pagos'),
                data: "id=" + idPagosFacturasPrincipal,
                params: {
                    myProyecto: myProyecto
                },
                success: function (params, msg) {
                    $("#bodyPagos").empty();
                    params.myProyecto.fnIniDialog(msg);

                }
            });
        }

        var __crearHtmlPagos = function (matriz) {
            var strhtml = '';
            for (i = 0; i < matriz[0].estado.length; i++) {
                strhtml += '<tr class="ui-widget-content jqgrow ui-row-ltr">';
                strhtml += '  <td>&nbsp;';
                strhtml += matriz[0].valor_pagado[i];
                strhtml += '  &nbsp;</td>';
                strhtml += '  <td>&nbsp;';
                strhtml += ((matriz[0].estado[i] == 'C') ? ("<span style='color:green;'>Cancelado</span>") : ("<span style='color:red;'>Pendiente</span>"));
                strhtml += '  &nbsp;</td>';
                strhtml += '  <td>&nbsp;';
                strhtml += matriz[0].fecha[i];
                strhtml += '  &nbsp;</td>';
                strhtml += '  <td>&nbsp;';
                strhtml += '<span id="saldo' + i + '" value=' + matriz[0].saldo[i] + '>' + matriz[0].saldo[i] + '</span>';
                strhtml += '  &nbsp;</td>';
                strhtml += '  <td>';
                if (parseInt(matriz[0].saldo[i]) == 0) {
                    strhtml += '    <input type="text" name="txt_pago' + i + '" id="txt_pago' + i + '" class="ui-button ui-widget ui-corner-all" disabled style="background-color:graid;"> ';
                } else {
                    strhtml += '    <input type="text" name="txt_pago' + i + '" id="txt_pago' + i + '" class="ui-button ui-widget ui-corner-all" onkeyup="this.value=number_format(this.value);"> ';
                }
                strhtml += '    <input type="hidden" name="hdn_idPlan' + i + '" id="hdn_idPlan' + i + '" value="' + matriz[0].id[i] + '">';
                strhtml += '  </td>';
                strhtml += '</tr>';
                $("#contadorCuotas").val(i);
                $("#hdnPagosFacturasPrincipal").val(matriz[0].pagos_facturas_principal_id[i]);
            }

            $(strhtml).appendTo("#bodyPagos");
        }

        var __validar_envio_datos = function () {
            var saldo, valorPagado = 0;
            var acumPagado = 0;
            for (var i = 0; i <= $("#contadorCuotas").val(); i++) {
                if (!document.getElementById('saldo' + i)) {
                    continue;
                }
                saldo = parseInt($("#saldo" + i).html().replace(/,/gi, ""));
                valorPagado = parseInt($("#txt_pago" + i).val().replace(/,/gi, ""));
                if (valorPagado > saldo) {
                    $("#txt_pago" + i).val('');
                    $("#txt_pago" + i).css("background-color", "red");
                    alert("No se puede pagar un valor mas alto que el del saldo");
                    return;
                }
                if (valorPagado <= 0) {
                    $("#txt_pago" + i).val('');
                    $("#txt_pago" + i).css("background-color", "red");
                    alert("No se puede pagar un valor inferior o igual a 0");
                    return;
                }
                if (isNaN(valorPagado)) {
                    acumPagado += 0;
                } else {
                    acumPagado += valorPagado;
                }

            }
            if (acumPagado <= 0 || isNaN(acumPagado)) {
                alert("Debe pagar al menos una parte de la cuota");
                return;
            } else {
                $.show_confirm_dialog({
                    msj:
                            "¿Realmente desea realizar este pago?",
                    si: function () {
                        __setPago();
                    }
                });
            }
        }

        var __setPago = function () {
            $.ajax_frm({
                url: $.fn.encoded_url_controller('cxc/pagos/insert_pago_cuota'),
                data: $("#frmAddEdit").serialize(),
                params: {
                    myProyecto: myProyecto
                },
                success: function (params, msg) {
                    $.fn.alert_dialog({
                        mensaje:
                                '<b>' + msg.msj + '</b>' +
                                '<br/><br/><i>Gracias por su pago</i>',
                        'buttons': {
                            "Ok": function () {
                                location.reload();
                            }}
                    });
                }
            });
        }

        var __getPlanPagosConsulta = function (id) {
            $.ajax_frm({
                url: $.fn.encoded_url_controller('tesoreria/gestion/select_plan_pagos'),
                data: "id=" + id,
                params: {
                    myProyecto: myProyecto
                },
                success: function (params, msg) {
                    $.fn.create_grilla_tmp({
                        create_dialog: true,
                        data: msg.rows,
                        width: 450,
                        height: 150,
                        arr_totalizar: ["Saldo", "A Pagar", "Pagado"]
                    });
                }
            });
        }

        var __ini_bttn_buscar_filtros = function () {
            $('#bttn_tab_1').click(function () {
                $.fn.fnRefreshGrilla(jQuery("#griddatoscx"), "divFiltros_tab1");
            });
        }

        var __ini_calendar_filtros = function () {
            $("#txtFechaDe_filtro_tab1").datepicker({
                changeMonth: true,
                numberOfMonths: 1,
                showOn: 'button',
                buttonImage: 'images/calendar.png',
                buttonImageOnly: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                maxDate: '0D',
                onClose: function (selectedDate) {
                    $("#txtFechaHasta_filtro_tab1").datepicker("option", "minDate", selectedDate);
                }
            });
            $("#txtFechaHasta_filtro_tab1").datepicker({
                changeMonth: true,
                numberOfMonths: 1,
                showOn: 'button',
                buttonImage: 'images/calendar.png',
                buttonImageOnly: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                maxDate: '0D',
                onClose: function (selectedDate) {
                    $("#txtFechaDe_filtro_tab1").datepicker("option", "maxDate", selectedDate);
                }
            });
        }

        var __ini_event_filtros_principal = function () {
            $('#selTipo_rango').change(function () {
                $('#divMes_filtro_tab1').hide();
                $('#divMes_filtro_tab2').hide();
                $('#divMes_filtro_tab3').hide();
                switch ($(this).val()) {
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
        /**metodo principal***/
        return {
            main: function () {
                /**buscamos las facturas actuales*/
                __searchFacturas();
                __ini_calendar_filtros();
                __ini_event_filtros_principal();
                __ini_bttn_buscar_filtros();
            },
            fnGetPlanPagos: function (idPagosFacturasPrincipal) {
                __getPlanPagos(idPagosFacturasPrincipal);
            },
            fnIniDialog: function (msg, idPagosFacturasPrincipal) {
                __ini_dialog_registrar_pago(true);
                __crearHtmlPagos(msg);
            },
            fnGetPlanPagosConsulta: function (idPagosFacturasPrincipal) {
                __getPlanPagosConsulta(idPagosFacturasPrincipal)
            }
        }

    })();
    /* iniciamos objeto**/
    myProyecto.main();
});