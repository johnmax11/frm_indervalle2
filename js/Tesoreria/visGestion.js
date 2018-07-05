var myProyecto = null;
$(document).ready(function() {
    myProyecto = (function() {

        var __searchAbonosFacturas = function() {
            /* grilla*/
            jQuery("#griddatoscx").jqGrid({
                url: $.fn.encoded_url_controller('tesoreria/gestion/select_row')+ $.fn.get_datos_filtros('divFiltros_tab1'),
                datatype: "json",
                colNames: ['id', 'RCA', 'VALOR PAGADO', 'CLIENTE', 'IDENTIFICACION', 'TIPO', 'CREADO POR', 'FECHA CREACION', ''],
                colModel: [
                    {name: 'id', index: 'id', editable: false, hidden: true},
                    {name: 'id', index: 'id', editable: false, hidden: false},
                    {name: 'valor_pagado', index: 'valor_pagado', editable: false, formatter: 'integer', formatoption: {thousandsSeparator: " ", defaultValue: '0'}},
                    {name: 'cliente', index: 'cliente', editable: false},
                    {name: 'identificacion', index: 'identificacion', editable: false},
                    {name: 'tipo', index: 'tipo', align: "center", editable: false},
                    {name: 'creado_por', index: 'creado_por', align: "center", editable: true},
                    {name: 'fecha_creacion', index: 'fecha_creacion', align: "center", editable: true},
                    {name: 'id_pagos_principal', index: 'id_pagos_principal', align: "center", editable: true, hidden: true}
                ],
                loadError: function(xhr, status, error) {
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
                caption: "Tesoreria"
            });
            $.fn.config_grilla(jQuery("#griddatoscx"));
            $.fn.add_crud_grilla(jQuery("#griddatoscx"), false, false, false, false, undefined, myProyecto);
        }

        var __ini_bttn_buscar_filtros = function() {
            $('#bttn_tab_1').click(function() {
                $.fn.fnRefreshGrilla(jQuery("#griddatoscx"), "divFiltros_tab1");
            });
        }

        var __ini_calendar_filtros = function() {
            $("#txtFechaDe_filtro_tab1").datepicker({
                changeMonth: true,
                numberOfMonths: 1,
                showOn: 'button',
                buttonImage: 'images/calendar.png',
                buttonImageOnly: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                maxDate: '0D',
                onClose: function(selectedDate) {
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
                onClose: function(selectedDate) {
                    $("#txtFechaDe_filtro_tab1").datepicker("option", "maxDate", selectedDate);
                }
            });
        }

        var __ini_event_filtros_principal = function() {
            $('#selTipo_rango').change(function() {
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

        var __getPlanPagos = function(id) {
            $.ajax_frm({
                url: $.fn.encoded_url_controller('tesoreria/gestion/select_plan_pagos'),
                data: "id=" + id,
                params: {
                    myProyecto: myProyecto
                },
                success: function(params, msg) {
                    $.fn.create_grilla_tmp({
                        create_dialog: true,
                        data: msg.rows
                    });
                }
            });
        }

        /**metodo principal***/
        return {
            main: function() {
                /**buscamos las facturas actuales*/
                __searchAbonosFacturas();
                __ini_calendar_filtros();
                __ini_event_filtros_principal();
                __ini_bttn_buscar_filtros();
            },
            getPlanPagos: function(id) {
                __getPlanPagos(id);
            }
        }

    })();
    /* iniciamos objeto**/
    myProyecto.main();
});