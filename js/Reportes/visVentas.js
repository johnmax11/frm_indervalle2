/**/
var myProyecto = null;
$(document).ready(function(){
    myProyecto = (function(){
        var __gblIdRow = null;
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_event_buscar = function(){
            $('#bttnBuscar').click(function(){
                __fnSearchDatosYear();
            });
        }
        
        /**
         * 
         * @param {type} anio
         * @returns {undefined}
         */
        var __fnSearchDatosYear = function(anio){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('reportes/ventas/search_ventas_anuales'),
                data:'anio='+(anio==undefined?$('#selAnio_ventas').val():anio),
                params:{myProyecto:myProyecto},
                success: function(params,msg){
                    /**construct new grafico**/
                    /**separamos eje x y eje y**/
                    var xAxis = [];
                    var yAxis = [];
                    var colors = Highcharts.getOptions().colors;
                    for(var i=0;i<msg.rows.length;i++){
                        $.each(msg.rows[i],function(index,value){
                            if(index!='comprado' && index!='ganado'){
                                xAxis.push(index);
                                yAxis.push({y:value,data:index,color:colors[i],drilldown:index,comprado:msg.rows[i].comprado,ganado:msg.rows[i].ganado});
                            }
                        });
                    }
                    params.myProyecto.ini_grafico_anual_ventas(xAxis,yAxis,'Ventas');
                }
            });
        }
        
        /**
         * 
         * @param {type} mes_s
         * @returns {String}
         */
        var __fnGetNumberMesOfString = function(mes_s){
            switch(mes_s){
                case "Enero":
                    return "01";
                case "Febrero":
                    return "02";
                case "Marzo":
                    return "03";
                case "Abril":
                    return "04";
                case "Mayo":
                    return "05";
                case "Junio":
                    return "06";
                case "Julio":
                    return "07";
                case "Agosto":
                    return "08";
                case "Septiembre":
                    return "09";
                case "Octubre":
                    return "10";
                case "Noviembre":
                    return "11";
                case "Diciembre":
                    return "12";
            }
        }
        
        /**
         * 
         * @returns {undefined}
         */
        var __ini_tabs = function(){
            $('#divTabs').tabs({
                activate:function(event,ui){
                    switch(ui.newTab.index()){
                        case 0:
                            __fnSearchDatosYear();
                            break;
                        case 1:
                            __fnSearchDatosYearSeparado();
                            break;
                        case 2:
                            __fnSearchDatosYearIngresos();
                            break;
                    }
                }
            });
        }

        /**
         * 
         * @param {type} div
         * @param {type} anio
         * @param {type} xAxis
         * @param {type} yAxis
         * @param {type} tipo
         * @returns {undefined}s
         */
        var __fnConstrucGraficoVentasFacturacion = function (div,anio,xAxis,yAxis,tipo){
            $('#'+div).html('');
            var chart = new Highcharts.Chart({
                chart: {
                    renderTo: div,
                    type: 'column',
                    margin: 75,
                    options3d: {
                        enabled: true,
                        alpha: 0,
                        beta: 0,
                        depth: 0,
                        viewDistance: 0
                    }
                },
                title: {
                    text: tipo+' Año: <b>'+anio+'</b>'
                },
                subtitle: {
                    text: "Generado por facturacion sistema Vamel's"
                },
                xAxis: {
                    categories:xAxis
                },
                yAxis:{
                    labels: {
                        formatter: function() {
                            return '$'+number_format(this.value);
                        }
                    }
                },
                tooltip: {
                    valuePrefix:"$",
                    formatter:function(){
                        //console.log(this);
                        return  '<b>'+this.key+':</b><br/>'+
                                (this.point.comprado!=undefined||(this.series.userOptions.comprado!=undefined&&this.series.userOptions.comprado[this.point.index]!=undefined)?
                                          'Invertido: $ '+number_format(this.point.comprado!=undefined?this.point.comprado:this.series.userOptions.comprado[this.point.index])+'<br/>':
                                          ''
                                )+
                                (tipo+': $ '+number_format(this.y)+'<br/>')+
                                (this.point.ganado!=undefined||(this.series.userOptions.ganado!=undefined&&this.series.userOptions.ganado[this.point.index]!=undefined)?
                                    'Ganancias: $ '+number_format(this.point.ganado?this.point.ganado:this.series.userOptions.ganado[this.point.index])+'<br/>':
                                    ''
                                )
                    }
                },
                colors: ['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', '#f15c80', 
                            '#e4d354', '#8085e8', '#8d4653', '#91e8e1', '#91e000', '#0008e1'],
                series: [{
                    name:tipo,
                    data: yAxis
                }],
                plotOptions: {
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function() {
                                    switch($("#divTabs").tabs('option', 'active')){
                                        case 0:
                                            __fnBuscarVentasByMes(chart,anio,this.category,this.color);
                                            break;
                                        case 1:
                                            __fnBuscarVentasByMesSeparados(chart,anio,this.category,this.color);
                                            break;
                                        case 2:
                                            __fnBuscarVentasByMesIngresos(chart,anio,this.category,this.color);
                                            break;
                                    }
                                }
                            }
                        }
                    }
                }
            });
        }
        
        /**
         * 
         * @param {type} chart
         * @param {type} anio
         * @param {type} mes_s
         * @param {type} color
         * @param {type} xAx_old
         * @param {type} yAx_old
         * @returns {undefined}
         */
        var __fnBuscarVentasByMes = function(chart,anio,mes_s,color){
            /****/
            if(isNaN(mes_s)==false){
                __fnSearchDatosYear(anio);
                return;
            }

            $.ajax_frm({
                url: $.fn.encoded_url_controller('reportes/ventas/search_ventas_mensuales'),
                data:'anio='+anio+'&mes='+__fnGetNumberMesOfString(mes_s),
                params:{myProyecto:myProyecto,anio:anio,chart:chart,color:color},
                success: function(params,msg){
                    /**construct new grafico**/
                    /**separamos eje x y eje y**/
                    var xAxis = [];
                    var yAxis = [];
                    var arr_data = [];
                    var arr_comprado = [];
                    var arr_ganado = [];
                    for(var i=0;i<msg.rows.length;i++){
                        $.each(msg.rows[i],function(index,value){
                            if(index!='comprado' && index!='ganado'){
                                xAxis.push(index);
                                arr_data.push(value);
                                arr_comprado.push(msg.rows[i].comprado);
                                arr_ganado.push(msg.rows[i].ganado);
                            }
                        });
                    }
                    yAxis.push({name:"Dias",data:arr_data,color:params.color,comprado:arr_comprado,ganado:arr_ganado});
                    params.myProyecto.ini_grafico_mensual(params.chart,params.anio,xAxis,yAxis);
                }
            });
        }

        /**
         * 
         * @param {type} chart
         * @param {type} anio
         * @param {type} mes_s
         * @param {type} color
         * @param {type} xAx_old
         * @param {type} yAx_old
         * @returns {undefined}
         */
        var __fnBuscarVentasByMesSeparados = function(chart,anio,mes_s,color){
            /****/
            if(isNaN(mes_s)==false){
                __fnSearchDatosYearSeparado(anio);
                return;
            }

            $.ajax_frm({
                url: $.fn.encoded_url_controller('reportes/ventas/search_ventas_mensuales_separados'),
                data:'anio='+anio+'&mes='+__fnGetNumberMesOfString(mes_s),
                params:{myProyecto:myProyecto,chart:chart,color:color,anio:anio},
                success: function(params,msg){
                    /**construct new grafico**/
                    /**separamos eje x y eje y**/
                    var xAxis = [];
                    var yAxis = [];
                    var arr_data = [];
                    for(var i=0;i<msg.rows.length;i++){
                        $.each(msg.rows[i],function(index,value){
                            xAxis.push(index);
                            arr_data.push(value);
                        });
                    }
                    yAxis.push({name:"Dias",data:arr_data,color:params.color});
                    params.myProyecto.ini_grafico_abonos_mensual(params.chart,params.anio,xAxis,yAxis);
                }
            });
        }

        /**
         * 
         * @param {type} chart
         * @param {type} anio
         * @param {type} mes_s
         * @param {type} color
         * @param {type} xAx_old
         * @param {type} yAx_old
         * @returns {undefined}
         */
        var __fnBuscarVentasByMesIngresos = function(chart,anio,mes_s,color){
            /****/
            if(isNaN(mes_s)==false){
                __fnSearchDatosYearIngresos(anio);
                return;
            }

            $.ajax_frm({
                url: $.fn.encoded_url_controller('reportes/ventas/search_ventas_mensuales_ingresos'),
                data:'anio='+anio+'&mes='+__fnGetNumberMesOfString(mes_s),
                params:{myProyecto:myProyecto,chart:chart,color:color,anio:anio},
                success: function(params,msg){
                        /**construct new grafico**/
                        /**separamos eje x y eje y**/
                        var xAxis = [];
                        var yAxis = [];
                        var arr_data = [];
                        for(var i=0;i<msg.rows.length;i++){
                            $.each(msg.rows[i],function(index,value){
                                xAxis.push(index);
                                arr_data.push(value);
                            });
                        }
                        yAxis.push({name:"Dias",data:arr_data,color:params.color});
                        params.myProyecto.ini_grafico_mensual_ingresos(params.chart,params.anio,xAxis,yAxis);
                }
            });
        }

        /**
         * 
         * @param {type} anio
         * @returns {undefined}
         */
        var __fnSearchDatosYearSeparado = function(anio){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('reportes/ventas/search_separados_anuales'),
                data:'anio='+(anio==undefined?$('#selAnio_ventas').val():anio),
                params:{myProyecto:myProyecto},
                success: function(params,msg){
                    /**construct new grafico**/
                    /**separamos eje x y eje y**/
                    var xAxis = [];
                    var yAxis = [];
                    var colors = Highcharts.getOptions().colors;
                    if(msg.rows!=null){
                        for(var i=0;i<msg.rows.length;i++){
                            $.each(msg.rows[i],function(index,value){
                                if(index!='comprado' && index!='ganado'){
                                    xAxis.push(index);
                                    yAxis.push({y:value,data:index,color:colors[i],drilldown:index});
                                }
                            });
                        }
                    }
                    params.myProyecto.ini_grafico_abonos_anuales(xAxis,yAxis);
                }
            });
        }
        
        /**
         * 
         * @param {type} anio
         * @returns {undefined}
         */
        var __fnSearchDatosYearIngresos = function (anio){
            $.ajax_frm({
                url: $.fn.encoded_url_controller('reportes/ventas/search_ingresos_anuales'),
                data:'anio='+(anio==undefined?$('#selAnio_ventas').val():anio),
                params:{myProyecto:myProyecto},
                success: function(params,msg){
                    /**construct new grafico**/
                    /**separamos eje x y eje y**/
                    var xAxis = [];
                    var yAxis = [];
                    var colors = Highcharts.getOptions().colors;
                    for(var i=0;i<msg.rows.length;i++){
                        $.each(msg.rows[i],function(index,value){
                            if(index!='comprado' && index!='ganado'){
                                xAxis.push(index);
                                yAxis.push({y:value,data:index,color:colors[i],drilldown:index});
                            }
                        });
                    }
                    params.myProyecto.ini_grafico_ingresos_anuales(xAxis,yAxis);
                }
            });
        }
        
        /****/
        var __fnConstructGraficaMensual = function(chart,anio,xAxis,yAxis){
            chart.xAxis[0].setCategories(xAxis);
            while (chart.series.length > 0) {
                chart.series[0].remove(true);
            }
            chart.addSeries(yAxis[0]);
        }

        
        /***metodo main********************************************************/
        /**metodo principal***/
        return {
            /**
             * 
             * @returns {undefined}
             */
            main: function(){
                __fnSearchDatosYear();
                __ini_tabs();
                __ini_event_buscar();
            },
            
            /**
             * 
             * @param {type} xAxis
             * @param {type} yAxis
             * @param {type} tipo
             * @returns {undefined}
             */
            ini_grafico_anual_ventas : function(xAxis,yAxis,tipo){
                __fnConstrucGraficoVentasFacturacion("divGraficoVentasFacturacion",$('#selAnio_ventas').val(),xAxis,yAxis,tipo);
            },
            
            /**
             * 
             * @param {type} chart
             * @param {type} anio
             * @param {type} xAxis
             * @param {type} yAxis
             * @returns {undefined}
             */
            ini_grafico_mensual : function(chart,anio,xAxis,yAxis){
                __fnConstructGraficaMensual(chart,anio,xAxis,yAxis);
            },
            
            /**
             * 
             * @param {type} xAxis
             * @param {type} yAxis
             * @returns {undefined}
             */
            ini_grafico_ingresos_anuales : function(xAxis,yAxis){
                __fnConstrucGraficoVentasFacturacion("divGraficoVentasIngresos",$('#selAnio_ventas').val(),xAxis,yAxis,"Ingresos");
            },
            
            /**
             * 
             * @param {type} chart
             * @param {type} anio
             * @param {type} xAxis
             * @param {type} yAxis
             * @returns {undefined}
             */
            ini_grafico_mensual_ingresos : function(chart,anio,xAxis,yAxis){
                __fnConstructGraficaMensual(chart,anio,xAxis,yAxis);
            },
            
            /**
             * 
             * @param {type} xAxis
             * @param {type} yAxis
             * @returns {undefined}
             */
            ini_grafico_abonos_anuales : function(xAxis,yAxis){
                __fnConstrucGraficoVentasFacturacion("divGraficoVentasSeparados",$('#selAnio_ventas').val(),xAxis,yAxis,"Abonos");
            },
            
            /**
             * 
             * @param {type} chart
             * @param {type} anio
             * @param {type} xAxis
             * @param {type} yAxis
             * @returns {undefined}
             */
            ini_grafico_abonos_mensual : function(chart,anio,xAxis,yAxis){
                __fnConstructGraficaMensual(chart,anio,xAxis,yAxis);
            }
        }
    })();
    /* iniciamos objeto**/
    myProyecto.main();
});
