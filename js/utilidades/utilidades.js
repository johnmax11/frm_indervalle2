var gbl_array_col = ['blue','red','green','purple','black','gray'];
var gbl_cont_array = 0;
var gbl_array_used_col = [];
var gbl_array_used_col_names = [];
(function($) {
    $.fn.errorStyle2 = function(html, obj) {
        // removemos los errores de todos los campos //
        $('.ui-state-error').each(function() {
            $(this).removeClass('ui-state-error');
        });
        //
        if (obj != undefined) {
            $(obj).addClass('ui-state-error');
        }
        var StyledError = "<div id='errortyle' class=\"ui-state-error ui-corner-all\" style=\"padding: 0 .7em;\">";
        StyledError += "<br/><p><span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\">";
        StyledError += "</span><strong>Atenci&oacute;n : </strong>";
        StyledError += html;
        StyledError += "</p><br/></div>";
        return StyledError;
    }
    $.fn.successStyle2 = function(html) {
        var StyledError = "<div class=\"ui-state-highlight ui-corner-all\" style=\"padding: 0 .7em;\">";
        StyledError += "<br/><p><span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\">";
        StyledError += "</span><strong>Atenci&oacute;n : </strong>";
        StyledError += html;
        StyledError += "</p><br/></div>";
        return StyledError;
    }
    /***/
    jQuery.fn.preload = function(texto, boldialog) {
        if (boldialog) {
            $.fn.dialog_preload(texto);
            return;
        }
        var strHtml = "";
        strHtml = "<div align=center id='divPreload-ui' style='width: 95%; position: relative;'>";
        strHtml += "    <div class='ui-corner-all' style='position: absolute; left:" + (texto != undefined ? ($(this).width() / 2) - (texto.length * 6) / 2 : '0') + "px;top:2px;bottom:1px;color:black; font-size:small;'>&nbsp;<label class='ui-state-default ui-state-active' style='background-color:white !important;color:black !important;'>" + (texto != undefined ? texto : '') + "</label>&nbsp;</div>"
        strHtml += "</div>"
        $(this).html(strHtml).init_preload();
    }
    /***/
    $.fn.init_preload = function() {
        $('#divPreload-ui').progressbar({value: false});
        //$('#divPreload-ui').find( ".ui-progressbar-value" ).css({"background-color":'#93c3cd'});
    }

    /***/
    $.fn.dialog_preload = function(texto) {
        $("#divDialogPreloadFullScreen").remove();
        var strhtml = "<div id='divDialogPreloadFullScreen' title='Procesando...'>";
        strhtml += "</div>";
        $('body').append(strhtml);
        $("#divDialogPreloadFullScreen").dialog({
            autoOpen: true,
            modal: true,
            draggable: false,
            resizable: false,
            height: 120,
            width: 'auto',
            close: function() {
                //$('#divDialogPreloadFullScreen').remove();
            }
        }).parent('.ui-dialog').find('.ui-dialog-titlebar-close').remove();
        // set html //
        strhtml = '';
        //strhtml = "     <div align=center id='divPreload-ui' style='width: 95%; position: relative;'>";
        /*strhtml += "        <div class='ui-corner-all' style='position: absolute; left:" +
                (texto != undefined ? ($('#divDialogPreloadFullScreen').width() / 2) - (texto.length * 3) : 0) +
                "px;top:6px;bottom:1px;color:black; font-size:x-small;'><center>&nbsp;" +
                "<label class='loading ui-state-default ui-state-active' style='background-color:white !important;color:black !important;'>" + (texto != undefined ? texto : '') + "</label>" +
                "&nbsp;</center>" +
                "</div>";
        strhtml += "    </div>";*/
        $('#divDialogPreloadFullScreen').html(strhtml).init_preload();
        $("#divDialogPreloadFullScreen").dialog('widget').
                find(".ui-dialog-titlebar").css({
                    "float": "right",
                    border: 0,
                    padding: 0
                })
                .find(".ui-dialog-title").css({
                    display: "none"
                }).end()
                .find(".ui-dialog-titlebar-close").css({
                    top: 0,
                    right: 0,
                    margin: 0,
                    "z-index": 999
                });
        $("#divDialogPreloadFullScreen").dialog('open');
        $("#divDialogPreloadFullScreen").html('<center><img src="images/preload.gif"></center>');
        //console.log($("#divDialogPreloadFullScreen").html());
    }

    $.fn.validateForm = function(form, tipo) {
        tipo = (tipo == undefined ? 'alert' : 'msj');

        $.fn.removerClassValidate(form);
        var bolvalidate = true;
        var arrStr = [];
        $("#" + form).find('.validate').each(function() {
            var elemento = this;
            $('#img_rsp_' + elemento.id).remove();
            // separamos las palabras //
            arrStr = $.fn.separarPalabras(elemento.id.substring(3));
            // validamos que el campo no este vacio //
            if (jQuery.trim(elemento.value) == '') {
                bolvalidate = false;

                $(this).addClass("ui-state-error");
                if (tipo == 'alert') {
                    alert("Campo Requerido: (" + arrStr.toUpperCase() + ")");
                } else {
                    $.fn.remove_lbl_error($(this));
                    $(this).after('<br id="b' + elemento.id + '"/><span id="s' + elemento.id + '" style="color:red;">Campo Obligatorio</span><br id="ulb' + elemento.id + '"/>');
                }
                $.fn.reset_campo($(this));
                return false;
            }
            // validamos la longitud del campo //
            if ($(this).attr('pattern')) {
                var arr_m_m = $.fn.get_min_max_length(this);
                if (!$.fn.check_length($(this), arrStr.toUpperCase(), arr_m_m, tipo)) {
                    bolvalidate = false;
                    $.fn.reset_campo($(this));
                    return false;
                }
            }
            // verificamos si es el usuario //
            if (elemento.id == 'txtUsuario_usuario') {
                if ($.fn.test_rex($(this), $.fn.validate_word_unique()) == false) {
                    bolvalidate = false;
                    $(this).addClass("ui-state-error");
                    var strmsj = "En el campo : (" + arrStr.toUpperCase() + ") unicamente se permite una palabra con solo letras y numeros sin espacios a-z, 0-9, comenzando con una letra";
                    if (tipo == 'alert') {
                        alert(strmsj);
                    } else {
                        $.fn.remove_lbl_error($(this));
                        $(this).after('<br id="b' + elemento.id + '"/><span id="s' + elemento.id + '" style="color:red;">' + strmsj + '</span><br id="ulb' + elemento.id + '"/>');
                    }
                    $.fn.reset_campo($(this));
                    return false;
                }
            }
            // validamos los tipo email //
            if ($(this).attr('type') == 'email' && $.fn.test_rex($(this), $.fn.rexp_validate_email()) == false) {
                bolvalidate = false;
                $(this).addClass("ui-state-error");
                var strmsj = "En el campo : (" + arrStr.toUpperCase() + ") el email esta incorrecto, ej. example@correo.com";
                if (tipo == 'alert') {
                    alert(strmsj);
                } else {
                    $.fn.remove_lbl_error($(this));
                    $(this).after('<br id="b' + elemento.id + '"/><span id="s' + elemento.id + '" style="color:red;">' + strmsj + '</span><br id="ulb' + elemento.id + '"/>');
                }
                $.fn.reset_campo($(this));
                return false;
            }
            /***/
            if ($(this).attr('type') == 'number' && $.fn.test_rex($(this), $.fn.rex_validate_numeros_positivos_enteros()) == false) {
                bolvalidate = false;
                $(this).addClass("ui-state-error");
                var strmsj = "El campo : (" + arrStr.toUpperCase() + ") solo recibe numero(s) entero(s)";
                if (tipo == 'alert') {
                    alert(strmsj);
                } else {
                    $.fn.remove_lbl_error($(this));
                    $(this).after('<br id="b' + elemento.id + '"/><span id="s' + elemento.id + '" style="color:red;">Campo Numerico Positivo</span><br id="ulb' + elemento.id + '"/>');
                }
                $.fn.reset_campo($(this));
                return false;
            }
        });
        if (bolvalidate == false) {
            return false;
        } else {
            return true;
        }
    }

    /***/
    $.fn.remove_lbl_error = function(obj) {
        $('#b' + obj.attr('id')).remove();
        $('#ulb' + obj.attr('id')).remove();
        $('#s' + obj.attr('id')).remove();
    }

    /***/
    $.fn.reset_campo = function(obj) {
        obj.val('');
        obj.focus();
    }

    /**
     * retorna el minimi y maximo de un campo input
     **/
    $.fn.get_min_max_length = function(obj) {
        var strpat = $(obj).attr('pattern');
        strpat = (strpat.substring(strpat.indexOf('{') + 1, (strpat.lastIndexOf('}') - strpat.indexOf('{')) + 1));

        return(eval('[' + strpat + ']'));
    }

    /**
     * valida un campo de tamaño min y maximo
     **/
    $.fn.check_length = function(o, n, min_max, tipo_alert, tips) {
        min_max[1] = (min_max[1] == null ? 999 : min_max[1])
        if (o.val().length < min_max[0] || o.val().length > min_max[1]) {
            var strmsj = '';
            if (min_max[1] != 999) {
                strmsj = "La cantidad de caracteres del campo (" + n + ") debe estar entre " + min_max[0] + " y " + min_max[1];
            } else {
                strmsj = "La cantidad de caracteres del campo (" + n + ") debe ser mayor a " + min_max[0];
            }
            o.addClass("ui-state-error");
            if (tipo_alert == 'alert') {
                alert(strmsj);
            } else {
                if (tipo_alert == 'msj') {
                    $.fn.remove_lbl_error(o);
                    o.after('<br id="b' + o.attr('id') + '"/><span id="s' + o.attr('id') + '" style="color:red;">' + strmsj + '</span>');
                } else {
                    if (tipo_alert == 'div_up') {
                        $.fn.update_tips(strmsj, tips);
                    }
                }
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * valida email
     **/
    $.fn.rexp_validate_email = function() {
        return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i
    }

    /**
     * valida solo caracteres alfanumerico
     **/
    $.fn.rex_validate_alfanumericos = function() {
        return /^([0-9a-zA-Z])+$/
    }
    
    /*
     * valdiate numeros 1-9
     * **/
    $.fn.rex_validate_numeros_positivos_enteros = function() {
        return /[0-9]+/
    }

    /**
     * valida palabra unica
     **/
    $.fn.validate_word_unique = function() {
        return /^[a-z]([0-9a-z_])+$/i
    }

    $.fn.update_tips = function(t, tips) {
        tips.html('<img src="images/info_peque.png"/>' + t).addClass("ui-state-highlight");
        /*setTimeout(function() {
         tips.removeClass( "ui-state-highlight", 1500 );
         }, 500 );*/
    }

    /**
     * evalua reg exp
     **/
    $.fn.test_rex = function(o, regexp) {
        if (!(regexp.test(o.val()))) {
            return false;
        } else {
            return true;
        }
    }

    /***/
    $.fn.removerClassValidate = function(form) {
        $("#" + form).find('.validate').each(function() {
            $(this).removeClass("ui-state-error");
            $('#s' + this.id).remove();
            $('#b' + this.id).remove();
            $('#ulb' + this.id).remove();
        });
    }

    /***/
    $.fn.separarPalabras = function(str) {
        var arrStr = str.split("_");
        var strEnd = "";
        for (var i = 0; i < arrStr.length; i++) {
            strEnd += arrStr[i] + " ";
        }
        return strEnd.substring(0, strEnd.length - 1);
    }

    /**
     * valida que 2 campos sean iguales
     **/
    $.fn.fnValidaCamposDesIguales = function(obj, obj2, tipo) {
        tipo = (tipo == undefined ? 'alert' : 'msj');
        if ($(obj).val().trim() != '' && $(obj2).val().trim() != '' && $(obj).val().trim() != $(obj2).val().trim()) {
            obj.addClass("ui-state-error");
            obj2.addClass("ui-state-error");

            var strmsj = "El valor de estos 2 campos no coinciden ";

            if (tipo == 'alert') {
                alert(strmsj);
            } else {
                $.fn.remove_lbl_error(obj);
                $.fn.remove_lbl_error(obj2);
                obj.after('<br id="b' + obj.attr('id') + '"/><span id="s' + obj.attr('id') + '" style="color:red;">' + strmsj + '</span>');
                obj2.after('<br id="b' + obj2.attr('id') + '"/><span id="s' + obj2.attr('id') + '" style="color:red;">' + strmsj + '</span>');
            }
            $(obj2).val("");
            $(obj).val("");
            $(obj).focus();
            return false;
        } else {
            return true;
        }
    }
})(jQuery);





function getAbsoluteElementPosition(element) {
    //console.log(element);
    if (typeof element == "string") {
        element = document.getElementById(element);
    }
    var elemento = $(element);
    var posicion = elemento.position();
    return {top: posicion.top, left: posicion.left};
}
function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}


function fnValidaExt(obj, extPermitidas, tips) {
    var ext = $(obj).val().split(".").pop().toLowerCase();

    if ($.inArray(ext, extPermitidas) == -1) {
        obj.addClass("ui-state-error");
        updateTips("Las extensiones permitidas son: " + extPermitidas, tips);
        return false;

    } else {
        return true;
    }
}

function fnValidaCamposIguales(obj, obj2, tips) {
    if ($(obj).val().trim() == $(obj2).val().trim()) {
        obj2.addClass("ui-state-error");
        updateTips("El valor de este campo ya existe en otro: ", tips);
        $(obj2).val("");
        $(obj2).focus();
        return false;

    } else {
        return true;
    }
}


(function($) {
    $.fn.errorStyle = function(html) {
        this.replaceWith(function() {
            var StyledError = "<div class=\"ui-state-error ui-corner-all\" style=\"padding: 0 .7em;\">";
            StyledError += "<br/><p><span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\">";
            StyledError += "</span><strong>Atenci&oacute;n : </strong>";
            StyledError += html;
            StyledError += "</p><br/></div>";
            return StyledError;
        });
    }
    $.fn.successStyle = function(html) {
        this.replaceWith(function() {
            var StyledError = "<div class=\"ui-state-highlight ui-corner-all\" style=\"padding: 0 .7em;\">";
            StyledError += "<br/><p><span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\">";
            StyledError += "</span><strong>Atenci&oacute;n : </strong>";
            StyledError += html;
            StyledError += "</p><br/></div>";
            return StyledError;
        });
    }
    $.fn.splitDivJson = function(msg) {
        if (msg.indexOf('<div') == null) {
            return msg;
        }
        var ind = msg.indexOf('<div');
        if (msg != '[]') {
            if (ind > 0) {
                msg = eval('(' + trim(msg.substring(0, ind)) + ')');
            } else {
                msg = eval('(' + msg + ')');
            }
        } else {
            msg = null;
        }
        return msg;
    }
    $.fn.cargar_condiciones_iniciales = function() {
        $('.bttn_class').button();
        // set tab defecto //
        var cmenu = parseInt($.fn.readCookie('pmenu'));
        $('#divMenu').accordion({active: cmenu});
        // init tabs //
        $('#divTabs').tabs();
    }
    /***/
    $.fn.set_combo = function(datos, idcombo, stroptini, icon) {
        if (datos === null || datos=='' || datos==undefined) {
            return false;
        }
        if (stroptini === undefined || stroptini === null) {
            stroptini = 'Seleccione...';
        }

        var stroption = '';
        if (stroptini != false && datos.length > 1) {
            stroption += '<option value="" selected="selected">' + stroptini + '</option>';
        }
        for (var i = 0; i < datos.length; i++) {
            if (datos[i].cell != null) {
                stroption += "<option value='" + datos[i].cell[0] + "'>" + datos[i].cell[1] + "</option>";
            } else {
                stroption += "<option value='" + datos[i].id + "'>" + datos[i].nombre + "</option>";
            }
        }
        // set combo //
        $('#' + idcombo).html(stroption);

        if (datos.length == 1) {
            if (!$('#' + idcombo).is(':visible')) {
                setTimeout(
                        function() {
                            $('#' + idcombo).next().children().val($('#' + idcombo + ' option:selected').text());
                        },
                        500
                        );

            }
        }

        if (icon !== undefined && icon === true) {
            // add icon //
            $.fn.append_icon_combo(idcombo);
        }
        return true;
    }

    /***/
    $.fn.append_icon_combo = function(idobj) {
        $('#btt' + idobj).remove();
        var html_i = "&nbsp;<button id='btt" + idobj + "' style='width:18px; height:18px;'>Agregar Registro</button>";
        $('#' + idobj).after(html_i);
        $("#btt" + idobj).button({
            text: false,
            icons: {
                primary: "ui-icon-plus"
            }
        }).click(function() {
            // genera dialog de agregar item //
            var title = idobj.substring(3).split('_');
            $.fn.append_dialog_combo(title);
            return false;
        });
    }
    /***/
    $.fn.append_dialog_combo = function(arrtitle, id_extra) {
        var strtitle = '';
        for (var i = 0; i < arrtitle.length; i++) {
            strtitle += arrtitle[i] + " ";
        }
        var strContr = '';
        for (var i = 1; i < arrtitle.length; i++) {
            strContr += arrtitle[i] + "_";
        }
        strContr = strContr.substring(0, strContr.length - 1);
        $("#" + (id_extra == undefined ? "divDialog_new_item" : id_extra)).remove();
        var strhtml = "<div id='" + (id_extra == undefined ? "divDialog_new_item" : id_extra) + "' title='" + (strtitle) + "'>";
        strhtml += "   <div id='divPreloadDialog_new_item' style='color:red; font-family: verdana; font-size: xx-small;'></div>";
        strhtml += "   <div id='divBodyDialog_new_item'>";
        if (id_extra == undefined) {
            strhtml += "        <form id='frmDialog_new_item'>";
            strhtml += "            Nombre: <input type='text' id='txtNombre_nuevo_item' name='txtNombre_" + strContr + "' class='bttn_class validate'  style='text-transform:uppercase;'/>";
            strhtml += "        </form>";
        }
        strhtml += "   </div>";
        strhtml += "</div>";
        $('body').append(strhtml);
        $('.bttn_class').button();
        $("#" + (id_extra == undefined ? "divDialog_new_item" : id_extra)).dialog({
            autoOpen: false,
            modal: true,
            draggable: true,
            resizable: true,
            width: 300,
            buttons: {
                "Guardar": function() {
                    if (!$.fn.validateForm('frmDialog_new_item',true)) {
                        return;
                    }
                    $.fn.send_ajax_new_item(arrtitle, strContr);
                },
                "Cerrar": function() {
                    $(this).dialog('close');
                }
            },
            close: function() {
                $("#" + (id_extra == undefined ? "divDialog_new_item" : id_extra)).remove();
            }
        });
        // open dialog //
        $("#" + (id_extra == undefined ? "divDialog_new_item" : id_extra)).dialog('open');
    }

    /***/
    $.fn.send_ajax_new_item = function(arrtitle, strContr) {
        $.ajax({
            url: $.fn.encoded_url_controller(arrtitle[0] + '/' + strContr + '/create_row'),
            type: 'POST',
            data: 'hdnid=&' + $('#frmDialog_new_item').serialize()+'&row_base=true',
            dataType: 'json',
            beforeSend: function() {$().preload('Guardando Cambios...',true)},
            error: function(jqXHR, textStatus, errorThrown) {$.fn.validate_error(jqXHR.responseText);},
            success: function(msg) {
                $('#divPreloadDialog_new_item').html("");
                if($.fn.validateResponse(msg,null)){
                    alert(msg.msj);
                    $("#txtNombre_nuevo_item").val('');
                    $("#divDialog_new_item").dialog('close');
                    eval('fnSearch' + arrtitle[0] + '_' + strContr + '();');
                } else {
                    alert('Error: Ocurrio un error al guardar los datos');
                }
            }
        });
    }

    /***/
    $.fn.ucwords = function(str) {
        return (str + '').replace(/^([a-z])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
            return $1.toUpperCase();
        });
    }

    /***/
    $.fn.get_pdf = function(obj, obj_preload) {
        obj_preload = (obj_preload === undefined ? $('#divPreload') : obj_preload);
        var sortname = obj.jqGrid('getGridParam', 'sortname');
        var sortorder = obj.jqGrid('getGridParam', 'sortorder');
        var url = obj.jqGrid('getGridParam', 'url');
        url = url.substring(0, url.indexOf('?'));
        $.ajax({
            url: url,
            type: 'POST',
            data: 'bttnAction=get_pdf_grilla&sidx=' + sortname + '&sord=' + sortorder,
            dataType: 'json',
            error: function() {
                //alert();
            },
            beforeSend: function() {
                obj_preload.html($.fn.preload('Generando Archivo...'));
            },
            success: function(msg) {
                obj_preload.html('');
                if (msg !== null) {
                    $.fn.pre_print_document(msg.url_archivo);
                } else {
                    alert('Error creando el archivo');
                }
            }
        });
    }
    /**/
    $.fn.pre_print_document = function(arrstrUrl) {
        if (arrstrUrl == undefined || arrstrUrl == null || arrstrUrl == '') {
            alert('Formato de url de archivo invalido!');
            return false;
        }
        var arrUrl = arrstrUrl.split(',');
        var strhtml = "";
        strhtml += "<div id='divDialogPrePrintDocument' title='Descargar Documento'>";
        strhtml += "  <div>";
        for (var i = 0; i < arrUrl.length; i++) {
            // sacamos la extension //
            var ext = arrUrl[i].substring(arrUrl[i].lastIndexOf('.') + 1);
            // sacamos el nombre de la carpeta //
            var carpeta = arrUrl[i].substring(arrUrl[i].lastIndexOf('/') + 1);
            carpeta = carpeta.substring(0, carpeta.lastIndexOf('.'));
            carpeta = carpeta.substring(carpeta.indexOf('_') + 1);
            carpeta = carpeta.substring(0, carpeta.lastIndexOf('_'));
            // sacamos el nombre del archivo //
            var archivo = arrUrl[i].substring(arrUrl[i].lastIndexOf('/') + 1);
            //
            strhtml += "    <div align='center'>";
            strhtml += "        <a href='" + _PATH_WEB_ + "/tmp/" + ext + '_' + carpeta + "/download.php?id=" + archivo + "'>";
            strhtml += "            <img src='images/document_" + ext + ".png' title='Descargar documento (" + ext + ")'/><br/>";
            strhtml += "            <span>Descargar</span>";
            strhtml += "        </a>";
            strhtml += "    </div>";
        }
        strhtml += "  </div>";
        strhtml += "</div>";
        // delete dialog //
        $('#divDialogPrePrintDocument').remove();
        // paint dialogo //
        $('#divOculto').append(strhtml);
        // set dialogo //
        $("#divDialogPrePrintDocument").dialog({
            autoOpen: false,
            modal: true,
            draggable: true,
            resizable: true,
            width: 300,
            buttons: {
                "Cerrar": function() {
                    $(this).dialog('close');
                }
            }
        });
        // open dialogo //
        $("#divDialogPrePrintDocument").dialog('open');
        return true;
    }
    /*
     refrescamos la grilla
     */
    $.fn.fnRefreshGrilla = function(objgrilla, id_div_filtros) {
        id_div_filtros = (id_div_filtros == undefined ? 'divFiltros' : id_div_filtros);
        var url = objgrilla.jqGrid('getGridParam', 'url');
        if(url.lastIndexOf('&middle')!=-1){
            url = url.substring(0,url.lastIndexOf('&middle'));
        }
        // buscamos los valores de los filtros //
        url += $.fn.get_datos_filtros(id_div_filtros);
        //
        objgrilla.jqGrid('setGridParam', {
            url: url,
            loadComplete: function(data) {
                $('#divPreload').html('');
                //colorder = data.sidx;
                //orderby  = data.sord;
            }
        }
        ).trigger("reloadGrid");
    }
    /***/
    $.fn.get_datos_filtros = function(id_div_filtros) {
        id_div_filtros = (id_div_filtros == undefined ? 'divFiltros' : id_div_filtros);
        var url = '&middle';
        if (document.getElementById(id_div_filtros)) {
            $('#' + id_div_filtros + ' input').each(function(indice, valor) {
                if ($(this).attr('type') === 'text') {
                    url = url + '&' + $(this).attr('name') + '=' + $(this).val();
                }
                if ($(this).attr('type') === 'checkbox') {
                    if($(this).prop('checked')){
                        url = url + '&' + $(this).attr('name') + '=' + $(this).val();
                    }
                }
            });
            $('#' + id_div_filtros + ' select').each(function(indice, valor) {
                url = url + '&' + $(this).attr('name') + '=' + $(this).val();
            });
        }
        return url;
    }

    /***/
    $.fn.gup = function(name) {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexS = "[\\?&]" + name + "=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(window.location.href);
        if (results == null)
            return "";
        else
            return results[1];
    }
    /**/
    $.fn.add_crud_grilla = function(obj, add, edit, dele, exp, id_page_ex,__object) {
        $.ajax({
            url: $.fn.encoded_url_controller($.fn.gup('module').toLowerCase() + '/' + $.fn.gup('action').toLowerCase() + '/validate_crud'),
            type: 'POST',
            data: 'event=validate_crud',
            dataType: 'json',
            success: function(msg) {
                /**/
                if (add == true && msg[0][0] == true) {
					
                    obj.jqGrid('navButtonAdd', (id_page_ex == undefined ? '#pagerdatoscx' : '#' + id_page_ex), {
                        caption: "",
                        buttonicon: "ui-icon-plus",
                        cursor: "pointer",
                        title: "Agregar registro",
                        onClickButton: function() {
                            __object.fnAddRows();
                        }
                    });
                }
                /**/
                if (edit == true && msg[0][2] == true) {
                    obj.jqGrid('navButtonAdd', (id_page_ex == undefined ? '#pagerdatoscx' : '#' + id_page_ex), {
                        caption: "",
                        buttonicon: "ui-icon-pencil",
                        cursor: "pointer",
                        title: "Editar registro",
                        onClickButton: function() {
                            __object.fnEditRows();
                        }
                    });
                }
                /**/
                if (dele == true && msg[0][3] == true) {
                    obj.jqGrid('navButtonAdd', (id_page_ex == undefined ? '#pagerdatoscx' : '#' + id_page_ex), {
                        caption: "",
                        buttonicon: "ui-icon-trash",
                        cursor: "pointer",
                        title: "Borrar registro",
                        onClickButton: function() {
                            __object.fnDelRows();
                        }
                    });
                }
                /**/
                if (exp == true) {
                    jQuery("#" + obj.substring(1)).jqGrid('navButtonAdd', (id_page_ex == undefined ? '#pagerdatoscx' : '#' + id_page_ex), {
                        caption: "",
                        buttonicon: "ui-icon-print",
                        cursor: "pointer",
                        title: "Exportar a PDF",
                        onClickButton: function() {
                            $.fn.get_pdf(jQuery((id_page_ex == undefined ? '#pagerdatoscx' : '#' + id_page_ex)));
                        }
                    });
                }
            }
        });
    }
    /**/
    $.fn.config_grilla = function(obj, id_page_ex, search) {
        obj.jqGrid('navGrid',
                (id_page_ex == undefined ? '#pagerdatoscx' : '#' + id_page_ex),
                {edit: false, add: false, del: false, search: (search == undefined ? true : (search == false ? false : true)), refresh: true},
        //options 
        {height: 280, reloadAfterSubmit: false},
        // edit options 
        {height: 280, reloadAfterSubmit: false},
        // add options 
        {reloadAfterSubmit: false},
        // del options 
        {closeOnEscape: true, multipleSearch: true, closeAfterSearch: true, sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge', 'bw', 'bn', 'in', 'ni', 'ew', 'en', 'cn', 'nc', 'nu', 'nn']}//
        // search options
        );
    }
    /***/
    $.fn.errorResponse = function(text, umsj) {
        if (umsj === undefined) {
            alert(text);
        } else {
            $('#divAlert').html($.fn.errorStyle2(text));
        }
    }
    /**/
    $.fn.writeCookie = function(name, value, days) {
        var date, expires;
        if (days) {
            date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    /**/
    $.fn.readCookie = function(name) {
        var i, c, ca, nameEQ = name + "=";
        ca = document.cookie.split(';');
        for (i = 0; i < ca.length; i++) {
            c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1, c.length);
            }
            if (c.indexOf(nameEQ) == 0) {
                return c.substring(nameEQ.length, c.length);
            }
        }
        return '';
    }

    /**
     * validar error en reponse ajax y limpiar preload y animations after
     **/
    $.fn.validateResponse = function(msg, id_div_pre, ind_anim) {
        /** validamos si existe session cerrada */
        if (msg.code != null) {
            alert(msg.msj);
            eval(msg.code);
            return;
        }

        if (id_div_pre != undefined) {
            $('#' + id_div_pre).html('');
        } else {
            id_div_pre = 'divDialogPreloadFullScreen';
            $('#' + id_div_pre).dialog('close');
            $('#' + id_div_pre).remove();
        }

        ind_anim = (ind_anim == undefined ? '' : ind_anim);
        $('#img_animt-' + ind_anim).remove();
        if (msg.error != null && msg.error == true) {
            alert((msg.msj == null ? 'Error: en response del request' : msg.msj));
            return false;
        } else {
            if (msg == null || msg.error == null) {
                alert('Error: Ocurrio un error en el response del request');
                return false;
            }
        }
        return true;
    }

    /**
     * agrega un animation despues de un objeto
     **/
    $.fn.after_animation = function(ind, prefijo) {
        if (prefijo == undefined) {
            prefijo = '';
        }
        ind = (ind == undefined ? '' : ind);
        $('#img_animt-' + ind).remove();
        return ('<img id="img_animt' + prefijo + '-' + ind + '" src="images/ui-anim_basic_16x16.gif" title="Procesando...">');
    }
    /***************************************************************************/
    /**
     * parsear url de controller
     **/
    $.fn.encoded_url_controller = function(url) {
        var arrFolder = url.split('/');
        if (arrFolder < 3) {
            alert('Error: Enrutando la peticion');
            return null;
        } else {
            arrFolder[arrFolder.length - 1] = (arrFolder[arrFolder.length - 1] == '' ? arrFolder[arrFolder.length - 2] : arrFolder[arrFolder.length - 1]);
            return 'aplication/router/?module=' + arrFolder[arrFolder.length - 3] + '&action=' + arrFolder[arrFolder.length - 2] + '&event=' + arrFolder[arrFolder.length - 1];
        }
    }
    /***
     valida el posible error de resposne de ajax
     */
    $.fn.validate_error = function(ObjErr) {
        // show mensaje error //
        alert('Ocurrio un problema en el proceso, se realizaron las siguientes acciones\n\n' +
                '*Se han bloqueado los botones por seguridad, para realizar de nuevo el proceso debera actualizar la pagina\n' +
                '*Se ha enviado un email al administrador del sistema con la informacion del error\n' +
                '*Por favor verificar si realizo los cambios que deseaba hacer o si por el contrario no realizo ningun cambio'
                );
        $('#divPreload-ui').remove();
        $("#divDialogPreloadFullScreen").dialog('destroy');
        $("#divDialogPreloadFullScreen").remove();
    }
    /***/
    $.fn.get_mes_spanish = function(month) {
        switch (parseInt(month)) {
            case 1:
                return 'Enero';
            case 2:
                return 'Febrero';
            case 3:
                return 'Marzo';
            case 4:
                return 'Abril';
            case 5:
                return 'Mayo';
            case 6:
                return 'Junio';
            case 7:
                return 'Julio';
            case 8:
                return 'Agosto';
            case 9:
                return 'Septiembre';
            case 10:
                return 'Octubre';
            case 11:
                return 'Noviembre';
            case 12:
                return 'Diciembre';
            default:
                return '';
        }
    }

    /**
     valida campo solo numerico con decimales
     */
    $.fn.validate_numero = function(campo, set_campo, campo_vacio) {
        if (set_campo == undefined) {
            set_campo = '';
        }
        if (!document.getElementById(campo)) {
            alert('El campo ---> ' + campo + ', No existe');
            return false;
        }
        if (isNaN($('#' + campo).val())) {
            alert('El campo debe ser numerico\n\nEj:1307.20 o 20.2');
            $('#' + campo).val(set_campo);
            return false;
        }
        // validamos caja 1 //
        if ($('#' + campo).val() != '') {
            var ch = $('#' + campo).val().substring(0, 1);
            if (ch == '.') {
                alert('Debe ingresar un valor numerico valido\n\nEj:0.1 o 10.33');
                $('#' + campo).val(set_campo);
                return false;
            }
            ch = $('#' + campo).val().substring(($('#' + campo).val().length - 1), $('#' + campo).val().length);
            if (ch == '.') {
                alert('Debe ingresar un valor numerico valido\n\nEj:0.2 o 11.34');
                $('#' + campo).val(set_campo);
                return false;
            }
        } else {
            if ($('#' + campo).val().trim() == '') {
                if (campo_vacio != undefined) {
                    $('#' + campo).val(set_campo);
                }
            }
        }
        return true;
    }


    /***/
    $.show_confirm_dialog = function(args) {
        // delete obj //
        $('#divDialogUtilidades').remove();

        var html = "<div id='divDialogUtilidades' title='Confirmar...' class=''>";
        html += "		<div id='divPreloadReferenciaOtros' style='font-family:verdana;color:red;' align='center'></div>";
        html += "		<div style='color:blue;font-size:small;font-style:verdana;' class='ui-corner-all'>";
        if (args.msj != undefined && args.msj != null) {
            html += "		<p><table style='width:100%;'><tr><th><img src='images/dialog-help.png'/></th><td>" + args.msj + "</td></tr></table></p>";
        } else {
            html += "		<p>Realmente desea confirmar los datos?</p>";
        }
        html += "		</div>";

        html += "	</div>";
        // append body //
        $('body').append(html);

        $('#divDialogUtilidades').dialog({
            autoOpen: true,
            modal: true,
            buttons: {
                "Si": function() {
                    if (args.si != undefined && args.si != null) {
                        eval(args.si());
                    }
                    $(this).dialog('close');
                    $('#divDialogUtilidades').remove();
                    return true;
                },
                "No": function() {
                    $(this).dialog('close');
                    if (args.no != undefined && args.no != null) {
                        eval(args.no());
                    }
                    return false;
                }
            },
            show: {effect: 'size', duration: 500}
        });
    }

    /**
     * 
     * @param {type} fecha
     * @param {type} days
     * @returns {String}
     */
    $.fn.sumar_dias_fecha = function(fecha, days) {
        milisegundos = parseInt(35 * 24 * 60 * 60 * 1000);
        /***/
        if (fecha == undefined || fecha == null) {
            fecha = new Date();
        } else {
            fecha = new Date(fecha);
        }
        day = fecha.getDate();
        // el mes es devuelto entre 0 y 11
        month = fecha.getMonth() + 1;
        year = fecha.getFullYear();

        /*document.write("Fecha actual: "+day+"/"+month+"/"+year);*/

        //Obtenemos los milisegundos desde media noche del 1/1/1970
        tiempo = fecha.getTime();
        //Calculamos los milisegundos sobre la fecha que hay que sumar o restar...
        milisegundos = parseInt(days * 24 * 60 * 60 * 1000);
        //Modificamos la fecha actual
        total = fecha.setTime(tiempo + milisegundos);
        day = fecha.getDate();
        month = fecha.getMonth() + 1;
        year = fecha.getFullYear();

        if (month.toString().length < 2) {
            month = "0".concat(month);
        }

        if (day.toString().length < 2) {
            day = "0".concat(day);
        }

        //document.write("Fecha modificada: "+day+"/"+month+"/"+year);
        return year + '-' + month + '-' + day;
    }
    /***/
    $.fn.str_pad = function(input, pad_length, pad_string, pad_type) {
        var half = '',
                pad_to_go;

        var str_pad_repeater = function(s, len) {
            var collect = '',
                    i;

            while (collect.length < len) {
                collect += s;
            }
            collect = collect.substr(0, len);

            return collect;
        };

        input += '';
        pad_string = pad_string !== undefined ? pad_string : ' ';

        if (pad_type !== 'STR_PAD_LEFT' && pad_type !== 'STR_PAD_RIGHT' && pad_type !== 'STR_PAD_BOTH') {
            pad_type = 'STR_PAD_RIGHT';
        }
        if ((pad_to_go = pad_length - input.length) > 0) {
            if (pad_type === 'STR_PAD_LEFT') {
                input = str_pad_repeater(pad_string, pad_to_go) + input;
            } else if (pad_type === 'STR_PAD_RIGHT') {
                input = input + str_pad_repeater(pad_string, pad_to_go);
            } else if (pad_type === 'STR_PAD_BOTH') {
                half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
                input = half + input + half;
                input = input.substr(0, pad_length);
            }
        }

        return input;
    }

    /***/
    $.fn.add_dialog = function(arr_parametros) {
        /***/
        arr_parametros = jQuery.extend({
            'title': 'Ventana Modal',
            'id_dialog': 'divDialog_add_dialog',
            'autoOpen':true
        }, arr_parametros);

        /***/

        /***/
        $('#' + arr_parametros.id_dialog).remove();
        /**creamos el html del div en el body*/
        var html = "<div id='" + arr_parametros.id_dialog + "' title='" + arr_parametros.title + "'>";
        html += "<div id='divPreload_" + arr_parametros.id_dialog + "'></div>";
        html += "<div id='divBody_" + arr_parametros.id_dialog + "'><form id='frmDialog_" + arr_parametros.id_dialog + "'></form></div>";
        html += "</div>";
        /***/
        $('body').append(html);
        /***/
        if (arr_parametros.fields != null) {
            /**creamos los campos*/
            var html_c = "<table id='tbl_"+arr_parametros.id_dialog+"' style='font-size:small;font-family:verdana;'>";
            $.each(arr_parametros.fields, function(indice, nombre) {
                html_c += "<tr>";
                /**/
                if (arr_parametros.fields[indice].type == "input") {
                    html_c += "<td style='" + (arr_parametros.fields[indice].visible == false ? 'display:none;' : '') + "'>";
                    html_c += "	<label id='lbl_" + nombre.id + "'><b>" + (nombre.label != undefined ? nombre.label : 'Campo ' + indice) + ":</b></label>&nbsp;";
                    html_c += "</td>";
                    html_c += "<td style='" + (arr_parametros.fields[indice].visible == false ? 'display:none;' : '') + "'>";
                    html_c += "	<input type='text'  id='" + nombre.id + "' name='" + nombre.id + "' class='"+(nombre.quit_class==true?"":"bttn_class")+" " + (nombre.class_css!=null?nombre.class_css:"") + " "+(nombre.validate==true?"validate":"")+" ' style='"+(nombre.style!=null?nombre.style:"")+"'/>";
                    html_c += "</td>";
                }
                /**/
                if (arr_parametros.fields[indice].type == "textarea") {
                    html_c += "<td style='" + (arr_parametros.fields[indice].visible == false ? 'display:none;' : '') + "'>";
                    html_c += "	<label id='lbl_" + nombre.id + "'><b>" + (nombre.label != undefined ? nombre.label : 'Campo ' + indice) + ":</b></label>&nbsp;";
                    html_c += "</td>";
                    html_c += "<td style='" + (arr_parametros.fields[indice].visible == false ? 'display:none;' : '') + "'>";
                    html_c += "	<textarea id='" + nombre.id + "' name='" + nombre.id + "' class='"+(nombre.quit_class==true?"":"bttn_class")+" " + (nombre.class_css!=null?nombre.class_css:"") + " "+(nombre.validate==true?"validate":"")+" '></textarea>";
                    html_c += "</td>";
                }
                /**/
                html_c += "</tr>";
            });
            html_c += "</table>";
        }
        /**/
        $('#frmDialog_' + arr_parametros.id_dialog).html(html_c);
        /***/
        $('.bttn_class').button();
        /***/
        $("#" + arr_parametros.id_dialog).dialog({
            autoOpen: arr_parametros.autoOpen,
            modal: true,
            draggable: true,
            resizable: true,
            width: arr_parametros.width,
            buttons: arr_parametros.buttons
        }
        );
    }

    /***/
    $.fn.clean_field = function(dato, v_return) {
        v_return = (v_return == undefined || v_return == null ? '' : v_return);
        return dato == undefined || dato == null ? v_return : dato;
    }

    /***/
    /***/
    $.fn.create_grilla_tmp = function(arr_parametros) {
        /***/
        arr_parametros = jQuery.extend({
            'id': 'grid_tmp',
            'page_id': 'page_grid_tmp',
            'page_use': true,
            'caption': 'Datos',
            'div_destino': 'body',
            'width': '1000',
            'height': '300',
            'data': null,
            'dinamic': false,
            'create_dialog':false,
            "footerrow":false,
            "userDataOnFooter":true,
            "arr_totalizar":[],
            'viewrecords': false,  
            'rownumbers': false,
            'arr_hidden_col':[],
            'buttons':null
        }, arr_parametros);
        
        jQuery('#' + arr_parametros.id).GridUnload();
        jQuery('#' + arr_parametros.id).jqGrid('GridDestroy');
        jQuery('#' + arr_parametros.id).GridDestroy();
        $('#'+arr_parametros.id).remove();
        $('#page_'+arr_parametros.id).remove();

        /**verificamos si hay q crear el dialog automaicamente*/
        if(arr_parametros.create_dialog == true){
            arr_parametros.div_destino = "dialog_"+arr_parametros.id;
            /**cremos el dialog*/
            $.fn.add_dialog(
                {
                    "id_dialog":'dialog_'+arr_parametros.id,
                    "title":arr_parametros.caption,
                    "width":(parseFloat(arr_parametros.width)+20),
                    "autoOpen":true,
                    "buttons":{
                        "Cerrar":function(){
                            $(this).dialog('close');
                        }
                    }
                }
            );
            /**abrimos el dialog**/
            $('#dialog_'+arr_parametros.create_dialog).dialog('open');
        }

        /**creamos el div de la grilla*/
        var html = "";
        html += "<table id='" + arr_parametros.id + "'>";
        html += "</table>";
        html += "<div id='" + arr_parametros.page_id + "'>";
        html += "</div>";
        /**set html*/
        $('#' + arr_parametros.div_destino).append(html);

        /**datos*/
        var col_names = [];
        var col_model = [];
        var col_body = [];
        var bol_p = false;
        /***/
        if (arr_parametros.data != null) {
            $.each(arr_parametros.data, function(indice, obj_row) {
                if (bol_p == false) {
                    $.each(obj_row, function(indice_i, nombre) {
                        col_names.push(indice_i.toUpperCase());
                        /**/
                        col_model.push({
                            name: indice_i,
                            index: indice_i,
                            align: 'center'
                        });
                    });
                    bol_p = true;
                }
            });
        }
		
        /**verifica si hay q usar footer d columnas*/
        if(arr_parametros.arr_totalizar.length>0){
                arr_parametros.footerrow=true
        }
		
        /***/
        jQuery('#' + arr_parametros.id).jqGrid({
            caption: (arr_parametros.create_dialog==true?"":arr_parametros.caption),
            pager: (arr_parametros.page_use == true ? '#' + arr_parametros.page_id : null),
            rowNum: 250,
            rowList: [50, 100, 200, 300, 500],
            width: arr_parametros.width,
            height: arr_parametros.height,
            datatype: "local",
            colNames: col_names,
            colModel: col_model,
            footerrow:arr_parametros.footerrow,
            userDataOnFooter:arr_parametros.userDataOnFooter,
            viewrecords: arr_parametros.viewrecords,  
            rownumbers: arr_parametros.rownumbers,
            gridComplete:function(){
            }
        }).trigger("reloadGrid");
        /***/
        var arr_totalizar_na = [];
        var arr_totalizar_to = [];
        var indice_sum = 0;
        if (arr_parametros.data != null) {
            for (var i = 0; i < arr_parametros.data.length; i++) {
                /**body*/
                $.each(arr_parametros.data[i], function(indice, obj_row) {
					
                    if(arr_parametros.arr_totalizar.length>0){
                            /**buscamos si la columna hay q totalizarla**/
                            if(jQuery.inArray(indice,arr_parametros.arr_totalizar)!=-1){
                                    /**buscamos si esta creado el indice*/
                                    var indice_sum = jQuery.inArray(indice,arr_totalizar_na)
                                    if(indice_sum==-1){
                                            arr_totalizar_to[arr_totalizar_na.length] = 0;
                                            indice_sum = arr_totalizar_na.length;
                                            arr_totalizar_na.push(indice);

                                    }
                                    arr_totalizar_to[indice_sum] += parseFloat(obj_row.toString().indexOf(',')?obj_row.toString().replace(/,/gi,""):obj_row);
                            }
                    }
                    
                    /**verificamos si viene array de buttons*/
                    if(arr_parametros.buttons!=null){
                        if(arr_parametros.buttons.right!=null){
                            obj_row = {
                                type:'button',
                                icon:arr_parametros.buttons.right[0].icon,
                                fn:arr_parametros.buttons.right[0].fn
                            }
                        }
                    }
					
                    if (typeof (obj_row) == 'object') {
                        if (obj_row!=null && obj_row.type != null) {
                            switch (obj_row.type) {
                                case 'img':
                                    arr_parametros.data[i][indice] = '<a href="' + _PATH_WEB_ + '/' + obj_row.ruta + '/' + obj_row.data + '" target="_blank">';
                                    /**img**/
                                    var type_arc = obj_row.ruta.substring(obj_row.ruta.lastIndexOf('/') + 1);
                                    type_arc = type_arc.split('_');
                                    arr_parametros.data[i][indice] += "<img src='" +
                                            _PATH_WEB_ + "/" +
                                            "images/" +
                                            "document_" + type_arc[0] + ".png" +
                                            "' title=''/>";
                                    /***/
                                    arr_parametros.data[i][indice] += "</a>";
                                    break;
                                case 'button':
                                    /***/
                                    arr_parametros.data[i][indice] = "<a href='javascript:void(0)' onclick='" + obj_row.fn + "'>";
                                    /***/
                                    arr_parametros.data[i][indice] += "<img src='images/" + obj_row.icon + "' title='Click'/>";
                                    /***/
                                    arr_parametros.data[i][indice] += "</a>";
                                    break;
                                case "input":
                                        arr_parametros.data[i][indice] = "<input id='"+obj_row.id+"' "+
                                                                            " name='"+obj_row.id+"' "+
                                                                            " type='text' "+
                                                                            " class='"+(obj_row.class_bttn!=null&&obj_row.class_bttn!=undefined?obj_row.class_bttn:"")+"' "+
                                                                            " size='"+(obj_row.size!=null&&obj_row.size!=undefined?obj_row.size:"")+"' "+
                                                                            ""+(obj_row.blur!=null&&obj_row.blur!=undefined?"onblur='"+obj_row.blur+"'":"")+
                                                                        "/>";
                                        break;
                                case "hidden":
                                        arr_parametros.data[i][indice] = "<input id='"+obj_row.id+"' "+
                                                                            " name='"+obj_row.id+"' "+
                                                                            " type='hidden' "+
                                                                            " value='"+obj_row.value+"' "+
                                                                        "/>";
                                        arr_parametros.arr_hidden_col.push(indice);
                                        break;
                            }
                        }
                    }
                
                });

                jQuery('#' + arr_parametros.id).jqGrid('addRowData', i + 1, arr_parametros.data[i]);
            } /**fin for add datos**/
            /**hidden column vr**/
            if(arr_parametros.arr_hidden_col.length>0){
                for(var k=0;k<arr_parametros.arr_hidden_col.length;k++){
                    //jQuery('#' + arr_parametros.id).hideCol(arr_hidden_col[k]);
                    jQuery('#' + arr_parametros.id).jqGrid({ 
                            width: arr_parametros.width,
                    })
                    .hideCol(arr_parametros.arr_hidden_col[k])
                    .setGridWidth(arr_parametros.width);
                }
            }
			
            if(arr_parametros.arr_totalizar.length>0){
                for(var i=0;i<arr_totalizar_to.length;i++){
                    var n_col = arr_totalizar_na[i];
                    var t_col = arr_totalizar_to[i];
                    var j_son = '{"'+n_col+'":"'+number_format(t_col)+'"}';
                    j_son = JSON.parse(j_son);
                    jQuery('#' + arr_parametros.id).jqGrid('footerData', 'set',j_son);
                }
            }
            /****/
            $('.bttn_class').button();
        }
    }

    $.fn.create_esqueleto_tabla = function(iddiv) {
        strhtml = "<table id='div" + iddiv + "'>";
        strhtml += "   <thead></thead>";
        strhtml += "   <tbody id='bodygestioninventarios'></tbody>";
        strhtml += "   <tfoot>";
        strhtml += "   </tfoot>";
        strhtml += "</table>";

        $("#" + iddiv).html(strhtml);
    }

    $.fn.create_table = function(idtbody, arrayprincipal, autocomplete) {

        $.fn.create_esqueleto_tabla(idtbody);

        //strhtml="<td id='tabla"+idtbody+"'>";
        strhtml += "<table class='ui-jqgrid ui-widget ui-widget-content ui-corner-all' width='100%'>";
        strhtml += "  <thead>";
        strhtml += "    <tr class='ui-state-default ui-th-column ui-th-ltr'>";
        strhtml += "        <td></td>";
        for (var i = 0; i < arrayprincipal.titulos.length; i++) {
            strhtml += "<td>&nbsp;" + arrayprincipal.titulos[i].toUpperCase() + "&nbsp;</td>";
        }
        strhtml += "<td></td>";
        strhtml += "<td></td>";
        strhtml += "  </thead>";
        strhtml += "  <tbody id='tbody" + idtbody + "'>";
        strhtml += "  </tbody>";
        strhtml += "  <tfoot>";
        strhtml += "    <input type='hidden' value='' id='hdnglobal" + idtbody + "' name='hdnglobalfilas'>";
        strhtml += "  </tfoot>";
        strhtml += "</table>";
        // strhtml+="</td>";

        $("#" + idtbody).html(strhtml);

        $("#hdnglobal" + idtbody).val(0);


        $.fn.create_row(idtbody, arrayprincipal, autocomplete);
    }


    $.fn.create_row = function(idtbody, arrayprincipal, autocomplete) {

        arridselectcombo = [];
        boolselect = false;
        idselectcombo = null;
        indice = $("#hdnglobal" + idtbody).val();
        $("#hdnglobal" + idtbody).val(indice * 1 + 1);
        html = "<tr id='fila" + indice + "'>";
        html += "<td>" + (indice * 1 + 1) + "</td>";
        for (var i = 0; i < arrayprincipal.campos.length; i++) {

            if (arrayprincipal.campos[i].autocomplete == true)
            { //si se activa el autocomplete
                if (arrayprincipal.campos[i].funcion != '' && arrayprincipal.campos[i].funcion!=undefined)
                { //si viene una funcion

                    var mifunction = arrayprincipal.campos[i].funcion;

                    switch (arrayprincipal.campos[i].type)
                    { //identificamos el tipo de campo
                        case "text":

                            if (arrayprincipal.campos[i].validate == true) //si se debe validar el campo
                            {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\",\"" + mifunction + "\"," + indice + ")' class='validate'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value='' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }
                            else
                            {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\",\"" + mifunction + "\"," + indice + ")'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value='' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }

                            break;

                        case "select":

                            if (arrayprincipal.campos[i].validate == true) //si se debe validar el campo
                            {
                                html += "<td style='text-align:center;'> <select id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\",\"" + mifunction + "\"," + indice + ")' class='validate'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }
                            else
                            {
                                html += "<td style='text-align:center;'> <select id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\",\"" + mifunction + "\"," + indice + ")'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value='' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }

                            break;

                        case "number":

                            if (arrayprincipal.campos[i].validate == true) //si se debe validar el campo
                            {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\",\"" + mifunction + "\"," + indice + ")' class='validate'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value='' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }
                            else
                            {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\",\"" + mifunction + "\"," + indice + ")'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value='' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }



                            break;

                    }


                }
                else
                { // si NO viene una funcion

                    switch (arrayprincipal.campos[i].type)
                    {
                        case "text":

                            if (arrayprincipal.campos[i].validate == true) //si se debe validar el campo
                            {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\")' class='validate'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value='' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }
                            else
                            {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\")'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value='' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }

                            break;

                        case "select":

                            if (arrayprincipal.campos[i].validate == true) //si se debe validar el campo
                            {
                                html += "<td style='text-align:center;'> <select id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\")' class='validate'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value='' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }
                            else
                            {
                                html += "<td style='text-align:center;'> <select id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\")'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value=''>";
                            }

                            break;


                        case "number":


                            if (arrayprincipal.campos[i].validate == true) //si se debe validar el campo
                            {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\")' class='validate'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value='' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }
                            else
                            {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' onclick='$.fn.autocompletemanual(" + arrayprincipal.campos[i].type + idtbody + indice + i + ",\"" + arrayprincipal.campos[i].ruta + "\",\"#hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "\")'></td>";
                                html += "<input type='hidden' id='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "' value='' name='hdn" + arrayprincipal.campos[i].type + idtbody + indice + i + "'>";
                            }
                            break;


                    }
                }
            } //si el campo no tiene autocomplete

            else
            {
                switch (arrayprincipal.campos[i].type)
                {
                    case "text":

                        if (arrayprincipal.campos[i].validate == true) //si se debe validar el campo
                        {
                            if (typeof (arrayprincipal.campos[i].class) != 'undefined') {

                                if (typeof (arrayprincipal.campos[i].evento) != 'undefined') {
                                    html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' class='validate " + arrayprincipal.campos[i].class + "'  " + arrayprincipal.campos[i].evento + "='" + arrayprincipal.campos[i].funcion_evento + "(" + arrayprincipal.campos[i].type + idtbody + indice + i + "," + indice + "," + i + ")'></td>"; //
                                }
                                else {
                                    html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' class='validate " + arrayprincipal.campos[i].class + "'></td>";
                                }

                            }
                            else {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' class='validate'></td>";
                            }

                        }
                        else
                        {
                            if (typeof (arrayprincipal.campos[i].class) != 'undefined') {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' class='" + arrayprincipal.campos[i].class + "'></td>";
                            }
                            else {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' ></td>";
                            }

                        }


                        break;

                    case "select":

                        boolselect = true;
                        arridselectcombo[i] = arrayprincipal.campos[i].type + idtbody + indice + i;

                        if (arrayprincipal.campos[i].validate == true) //si se debe validar el campo
                        {
                            html += "<td style='text-align:center;'> <select id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "'  name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' class='validate' ><option value=''>SELECCIONE...</option></select></td>";            
                            break;
                        }
                        else
                        {
                            html += "<td style='text-align:center;'> <select id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "'  name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "'><option value=''>SELECCIONE...</option></select></td>";
                            break;
                        }


                    case "number":

                        if (arrayprincipal.campos[i].validate == true) //si se debe validar el campo
                        {
                            if (typeof (arrayprincipal.campos[i].class) != 'undefined') {

                                if (typeof (arrayprincipal.campos[i].evento) != 'undefined') {
                                    html += "<td> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' class='validate " + arrayprincipal.campos[i].class + "'  " + arrayprincipal.campos[i].evento + "='" + arrayprincipal.campos[i].funcion_evento + "(\"" + arrayprincipal.campos[i].type + idtbody + indice + i + "\"," + indice + "," + i + ")'></td>"; //
                                }
                                else {
                                    html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' class='validate " + arrayprincipal.campos[i].class + "' " + arrayprincipal.campos[i].evento + "='" + arrayprincipal.campos[i].funcion_evento + "(\"" + arrayprincipal.campos[i].type + idtbody + indice + i + "\"," + indice + "," + i + ")'></td>";
                                }

                            }
                            else {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' class='validate' " + arrayprincipal.campos[i].evento + "='" + arrayprincipal.campos[i].funcion_evento + "(\"" + arrayprincipal.campos[i].type + idtbody + indice + i + "\"," + indice + "," + i + ")'></td>";
                            }

                        }
                        else
                        {
                            if (typeof (arrayprincipal.campos[i].class) != 'undefined') {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' class='" + arrayprincipal.campos[i].class + "' " + arrayprincipal.campos[i].evento + "='" + arrayprincipal.campos[i].funcion_evento + "(\"" + arrayprincipal.campos[i].type + idtbody + indice + i + "\"," + indice + "," + i + ")'></td>";
                            }
                            else {
                                html += "<td style='text-align:center;'> <input type='" + arrayprincipal.campos[i].type + "' id='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' name='" + arrayprincipal.campos[i].type + idtbody + indice + i + "' " + arrayprincipal.campos[i].evento + "='" + arrayprincipal.campos[i].funcion_evento + "(\"" + arrayprincipal.campos[i].type + idtbody + indice + i + "\"," + indice + "," + i + ")'></td>";
                            }
                        }
                        break;
                }

            }
        }
        html += "  <td style='text-align:center;'>";
        html += "    &nbsp;<button id='agregarfila" + idtbody + "" + indice + "' style='width:18px; height:18px;' onclick='$.fn.create_row(\"" + idtbody + "\",arrayprincipal)'>Agregar fila</button>";
        html += "  </td>";
        html += "  <td style='text-align:center;'>";
        if(arrayprincipal.funcion_remove!='undefined'){
            html += "    &nbsp;<button id='removerfila" + idtbody + "" + indice + "' style='width:18px; height:18px;' onclick='$.fn.delete_row(" + indice + ",arrayprincipal.funcion_remove)'>Remover fila</button>";
        }
        else{
            html += "    &nbsp;<button id='removerfila" + idtbody + "" + indice + "' style='width:18px; height:18px;' onclick='$.fn.delete_row(" + indice + ")'>Remover fila</button>";
        }
        
        html += "  </td>";
        html += "</tr>";

        $(html).appendTo("#tbody" + idtbody);
        
        if (typeof (arrayprincipal.funcion_add) != 'undefined') {
            eval(arrayprincipal.funcion_add + "(" + indice + ")");
        }

        if (boolselect == true) {
            for (i = 0; i <= arridselectcombo.length; i++) {
                if (arridselectcombo[i] != 'undefined') {
                    $.fn.create_combo_box(arridselectcombo[i]);
                }
            }
        }

    $("#agregarfila"+idtbody+""+indice).button({
                    text: false,
            icons: {
                primary: "ui-icon-plusthick"
            }
        }).click(function() {
            return false;
        });


        $("#removerfila" + idtbody + "" + indice).button({
            text: false,
            icons: {
                primary: "ui-icon-closethick"
            }
        }).click(function() {
            return false;
        });

    }

    $.fn.autocompletemanual = function(idcampo, ruta, idcampooculto, mifunction, indice) {
        $(idcampo).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "GET",
                    url: $.fn.encoded_url_controller(ruta),
                    dataType: "json",
                    data: {term: request.term},
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            dataType: 'json',
            select: function(event, ui) { // lo que pasa cuando seleccionamos
				$(idcampooculto).val(ui.item.id);
				if(mifunction!=undefined){
					eval(mifunction + "(" + ui.item.id + "," + indice + ",ui.item);");
				}
            },
            search: function(event, ui) { // cuando buscamos
				$(idcampooculto).val("");
            }//realiza el autocomplete en el momento de las descripciones

        });
    }

    $.fn.delete_row = function(indice,funcion) {
        $("#fila" + indice).remove();
		if(funcion!=undefined){
			eval(funcion+"("+indice+")");
		}
        
    }
    $.fn.create_combo_box = function(idselect) {
        $("#" + idselect).after("<button id='toggle_" + idselect + "'></button>");
        $("#" + idselect).combobox();
        $("#toggle_" + idselect).click(function() {
            $("#" + idselect).toggle();
        });
    }

    $.fn.alert_dialog = function(arrDialog) {
		/***/
        arrDialog = jQuery.extend({
            'id': 'dialog_info',
            'id_dialog': 'divDialog_add_dialog',
            'titulo':'Informacion...',
            'idicono':'ui-icon-info',
            'mensaje':'&nbsp;',
            'buttons': {
                "Ok":function(){
                   $(this).dialog("close");
                }
            },
            'width':400,
            'autoopen':true
        }, arrDialog);
	
        $('#' + arrDialog.id).remove();
        strhtml = "<div id='div_" + arrDialog.id + "' title='"+arrDialog.titulo+"'>";
        strhtml += "<table>";
        strhtml += "    <tr>";
        strhtml += "        <th>";
        strhtml += "            <img src='images/dialog-information.png'/>";
        strhtml += "        </th>";
        strhtml += "        <td style='font-family:verdana;'>";
        strhtml += "            <span class='ui-icon "+arrDialog.idicono+"' style='float: left; margin-right: .3em;'>hola</span>";
        strhtml +=              arrDialog.mensaje;
        strhtml += "        </td>";
        strhtml += "    </tr>";
        strhtml += "</table>";
        strhtml += "</div>";
        //$("body").html(strhtml);
        $(strhtml).appendTo("#divBody");
        $("#div_" + arrDialog.id).dialog({
            autoOpen: (arrDialog.autoopen!=undefined?arrDialog.autoopen:false),
            modal: true,
            width: arrDialog.width,
            heigth: arrDialog.heigth,
            buttons: arrDialog.buttons,
            show: {effect: 'bounce', duration: 1250},
            hide: {effect: 'scale', duration: 500}
        }).parent('.ui-dialog').find('.ui-dialog-titlebar-close').remove();
    }

	// implement JSON.stringify serialization
	JSON.stringify = JSON.stringify || function (obj) {
		var t = typeof (obj);
		if (t != "object" || obj === null) {
			// simple data type
			if (t == "string") obj = '"'+obj+'"';
			return String(obj);
		}
		else {
			// recurse array or object
			var n, v, json = [], arr = (obj && obj.constructor == Array);
			for (n in obj) {
				v = obj[n]; t = typeof(v);
				if (t == "string") v = '"'+v+'"';
				else if (t == "object" && v !== null) v = JSON.stringify(v);
				json.push((arr ? "" : '"' + n + '":') + String(v));
			}
			return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
		}
	};
	
	/****/
	$.fn.funct_a_link_formatter = function(cellvalue, options, rowObject){
		var strCol = '';
		var index_c = jQuery.inArray(options.colModel.index,gbl_array_used_col);
		if(index_c==-1){
			gbl_array_used_col[gbl_array_used_col.length] = options.colModel.index;
			gbl_array_used_col_names[gbl_array_used_col_names.length] = gbl_array_col[gbl_cont_array];
			strCol = gbl_array_col[gbl_cont_array];
			
			gbl_cont_array ++;
		}else{
                    strCol = gbl_array_used_col_names[index_c];
		}
		
		/****/
		if(isNaN(cellvalue)==false){
                    if(options.colModel.number!=undefined && options.colModel.number!=false){
                        cellvalue = number_format(cellvalue);
                    }
		}
		
		var html = "<a href='javascript:void(0)' onclick='myProyecto.fn_"+options.colModel.index+"(\""+cellvalue+"\","+JSON.stringify(options)+","+JSON.stringify(rowObject)+");' style='color:"+strCol+"'>"+cellvalue+"</a>";
		return html;
	}
        
        
        /**
         * crea una fila de html
         * 
         * @param {type} args
         */
        $.fn.create_row_html = function(args){
            args = jQuery.extend({
                index:false,
                prefix:"tr-",
                porc:true,
                icon_delete:false,
                icon_add:false,
                icon_update:false,
                fields:{}
            }, args);
            
            /***/
            var row = document.createElement("tr");
                row.setAttribute("id",args.prefix+""+(args.index!=false?args.index:"0"));
            if(args.porc!=false){
                var porc_fields = (100/args.fields.length);
            }
            /***/
            for(var i=0;i<args.fields.length;i++){
                var cell = document.createElement("th");
                    cell.setAttribute("id",'td-'+(args.index!=false?args.index:"0")+'-'+i);
                    cell.setAttribute("style",(args.porc!=false?"width:"+porc_fields+"%;":""));
                
                switch(args.fields[i].type){
                    case null:
                        var cellContent = document.createTextNode("&nbsp;");
                            cell.appendChild(cellContent);
                        break;
                    case 'label':
                        var cellLabel = document.createElement("label");
                            cellLabel.setAttribute("id",args.fields[i].id+"-"+(args.index!=false?args.index:"0"));
                            var cellLabelContent = document.createTextNode((args.fields[i].value!=undefined?(args.fields[i].value==true?(parseInt(args.index)+1):args.fields[i].value):""));
                            /**add content label a label**/
                            cellLabel.appendChild(cellLabelContent);
                            /**add content a  cellcontent**/
                            cell.appendChild(cellLabel);
                        break;
                    case "input":
                        var cellInput = document.createElement("input");
                            cellInput.setAttribute('type','input');
                            cellInput.setAttribute("id",args.fields[i].id+"-"+(args.index!=false?args.index:"0"));
                            cellInput.setAttribute("name",args.fields[i].id+"-"+(args.index!=false?args.index:"0"));
                            cellInput.setAttribute('size',(args.fields[i].size!=undefined?args.fields[i].size:10));
                            cellInput.setAttribute('class',"ucwords ui-button ui-widget ui-corner-all"+(args.fields[i].class!=undefined?args.fields[i].class:""));
                            cellInput.setAttribute('maxlength',(args.fields[i].maxlength!=undefined?args.fields[i].maxlength:255));
                            cellInput.setAttribute('value',(args.fields[i].value!=undefined?number_format(args.fields[i].value):''));
                            /**add content a  cellcontent**/
                            cell.appendChild(cellInput);
                            /**verificamos autocomplete*/
                            if(args.fields[i].autocomplete==true){
                                var cellInput_hdn = document.createElement("input");
                                    cellInput_hdn.setAttribute('type','hidden');
                                    cellInput_hdn.setAttribute("id",'hdn_'+args.fields[i].id+"-"+(args.index!=false?args.index:"0"));
                                    cellInput_hdn.setAttribute("name",'hdn_'+args.fields[i].id+"-"+(args.index!=false?args.index:"0"));
                                    cellInput_hdn.setAttribute('class',(args.fields[i].class!=undefined?args.fields[i].class:""));
                                    /***/
                                    cell.appendChild(cellInput_hdn);
                                    /**init autocomplete**/
                                    var url_t = args.fields[i].autocomplete_url;
                                    var field_t = args.fields[i].autocomplete_field;
                                    var function_select = args.fields[i].autocomplete_onselect;
                                    var function_search = args.fields[i].autocomplete_onsearch;
                                    var arg_index = args.index;
                                    $(cellInput).autocomplete({
                                        /*source: "control/cue_hist_pedidos/cue_inventarios_ept.php?bttnAction=buscacuerosAction&scolor=true&categoria",*/
                                        source: function( request, response ) {
                                            $.ajax({
                                                type: "GET",
                                                url: url_t,
                                                dataType: "json",
                                                data: {term: request.term,field:field_t},
                                                success: function( data ) {response(data);}
                                            });
                                        },
                                        minLength: 2,
                                        dataType:'json',
                                        select: function( event, ui ){
                                            function_select(arg_index,cellInput,ui);
                                        },
                                        search:function(event,ui){
                                            function_search(arg_index,cellInput,ui);
                                        }
                                    }).blur(function(){
                                        if($(this).val()==''){$(cellInput_hdn).val('')}
                                    });
                            }
                        break;
                    case "number":
                        var cellInput = document.createElement("input");
                            cellInput.setAttribute('type','text');
                            //cellInput.setAttribute('pattern','[0-9]*');
                            cellInput.setAttribute('onkeyup','this.value = number_format(this.value)');
                            cellInput.setAttribute("id",args.fields[i].id+"-"+(args.index!=false?args.index:"0"));
                            cellInput.setAttribute("name",args.fields[i].id+"-"+(args.index!=false?args.index:"0"));
                            cellInput.setAttribute('size',(args.fields[i].size!=undefined?args.fields[i].size:10));
                            cellInput.setAttribute('class',"ucwords ui-button ui-widget ui-corner-all"+(args.fields[i].class!=undefined?args.fields[i].class:""));
                            cellInput.setAttribute('maxlength',(args.fields[i].maxlength!=undefined?args.fields[i].maxlength:255));
                            cellInput.setAttribute('value',(args.fields[i].value!=undefined?number_format(args.fields[i].value):''));
                            /**add content a  cellcontent**/
                            cell.appendChild(cellInput);
                        break;
                }
                /*add eventos**/
                if(args.fields[i].events!=undefined){
                    $.fn.set_event_field(args.fields[i].events,cellInput,args.index);
                }
                /*add icons**/
                if(args.fields[i].icons!=undefined){
                    cell = $.fn.set_icons_field(args.fields[i],cell,args.index);
                }
                
                /**add th a tr */
                row.appendChild(cell);
            }
            
            /**verificamos si hy q incluir el icono de eliminar fila*/
            if(args.icon_delete==true){
                var cell = document.createElement("th");
                /**add image**/
                var button = $.fn.get_imagen_icon("bttnDelete",args.index,"Quitar Registro");
                $(button).button({
                    text: false,
                    icons: {primary: 'ui-icon-closethick'}
                }).click(function(){
                    $(row).remove();
                    /***/
                    args.afterDelete();
                    return false;
                });
                /***/
                cell.appendChild(button);
                /**add th a tr */
                row.appendChild(cell);
            }
            
            return row;
        }
        
        /**
         * 
         * @param {type} id
         * @param {type} index
         * @param {type} title
         * @returns {Element}
         */
        $.fn.get_imagen_icon = function(id,index,title){
            var button = document.createElement("button");
                button.setAttribute("id",id+"-"+(index!=false?index:"0")+"-"+i);
                button.setAttribute("style","width:18px; height:18px;");
                button.setAttribute("title",title);
            return button;
        }
        
        /**
         * 
         * @param {type} obj
         * @param {type} __event
         * @param {type} fn
         */
        $.fn.set_event_field = function(__events,obj,index_row){
            $.each(__events, function (index, value) {
                $.fn.set_event_one(obj,index,value,index_row);
            });
            
        }
        
        /**
         * 
         * @param {type} obj
         * @param {type} __event
         * @param {type} fn
         */
        $.fn.set_event_one = function(obj,__event,fn,index){
            if (obj.addEventListener) {
                obj.addEventListener(__event,function(obj){fn(obj.target,index)}, false);
            } else {
                obj.attachEvent("on" + __event,function(obj){fn(obj.target,index)});
            }
        }
        
        /**
         * 
         * @param {type} parameters
         * @param {type} cell
         * @param {type} index
         * @returns {cell}
         */
        $.fn.set_icons_field = function(parameters,cell,index){
            if(parameters.icons.after!=undefined){
                for(var i=0;i<parameters.icons.after.length;i++){
                    if(parameters.icons.after[i]!=undefined){
                        var fn_click = parameters.icons.after[i].click;
                        /**add image**/
                        var button = $.fn.get_imagen_icon("bttnAfter-"+index,index,parameters.icons.after[i].title);
                        $(button).button({
                            text: false,
                            icons: {primary:parameters.icons.after[i].icon}
                        }).click(function(){
                            fn_click();
                            return false;
                        });
                        /***/
                        cell.appendChild(button);
                    }
                }
            }
            return cell;
        }
        
        /****/
        jQuery.extend({
            ajax_frm : function(args){
                args = jQuery.extend({
                    type : 'POST',
                    dataType : 'json',
                    beforeSend:function(){$().preload('Guardando Cambios...',true);},
                    error:function(jqXHR){$.fn.validate_error(jqXHR)},
                    success:function(){},
                    params:{}
                }, args);
                /***/
                $.ajax({
                    url: args.url,
                    type:args.type,
                    data: args.data,
                    dataType:args.dataType,
                    beforeSend:args.beforeSend,
                    error:args.error,
                    success: function(msg){
                        if($.fn.validateResponse(msg,null)){
                            var as_f = eval('('+args.success+')');
                            as_f(args.params,msg);
                        }else{
                            
                        }
                    }
                });
            }
        });
        
        /**
         * 
         */
	$.fn.ucwordsAll = function(){
            $('.ucwords').keyup(function(){
                this.value = $.fn.ucwords(this.value.toLowerCase());
            });
        }
        
        /**
         * 
         * @param {type} element
         * @param {type} eventName
         * @param {type} namespace
         * @returns {Boolean}
         */
        $.fn.hasEventListener = function (element, eventName, namespace) {
            var returnValue = false;
            var events = $(element).data("events");
            if (events) {
                $.each(events, function (index, value) {
                    if (index == eventName) {
                        if (namespace) {
                            $.each(value, function (index, value) {
                                if (value.namespace == namespace) {
                                    returnValue = true;
                                    return false;
                                }
                            });
                        }
                        else {
                            returnValue = true;
                            return false;
                        }
                    }
                });
            }
            return returnValue;
        }
        
        $.fn.load_view = function(args){
            args = jQuery.extend({
            }, args);
            
            /**creamos el dialog*/
            $.fn.add_dialog({
                title:args.title,
                id_dialog:args.id,
                width:args.width,
                height:args.height
            });
            
            var arrUrl = args.view.split('/');
            
            /**
             * creamos el iframe
             */
            var iframe = $('<iframe id="iframe_'+args.id+'" frameborder="0" marginwidth="0" marginheight="0" allowfullscreen></iframe>');
            /**
             * agregamos el iframe a dialog
             */
            $('#divBody_'+args.id).html(iframe);
            //$('#divBody_'+args.id).load(_PATH_WEB_+'/index.php?module='+arrUrl[0]+'&action='+arrUrl[1]+' #divAddEdit');
            /**
             * cargamos la vista
             */
            iframe.attr({
                width:(args.width-20),
                height:(args.height),
                src:_PATH_WEB_+'/index.php?module='+arrUrl[0]+'&action='+arrUrl[1]
            });
            
            /**calback load iframe**/
            iframe.load(function() {
                console.log('callback-->');
                if(args.load!=undefined){
                    eval('document.getElementById(\'iframe_\'+args.id).contentWindow.myProyecto.'+args.load+'()');
                }
                /***/
                if(args.hideCallback!=undefined){
                    var framebody = $('#iframe_'+args.id).contents().find('body');
                        framebody.find(args.hideCallback).hide();
                }
                /**callback*/
                if(args.callback!=undefined){
                    args.callback();
                }
            });
            
            //var framebody = iframe.contents().find('<body>');
            //    framebody.find('.ui-icon-plus').trigger('click');
            /*iframe.load(function(){
                    console.log('aca-->');
                }
            );*/
        }
        
})(jQuery);
