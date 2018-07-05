 $(document).ready(function(){     
    $('#bttnLogin').click(function(){        
        fnLoginUsuario();    
    });		
    /* evento para buscar los datos del cliente por usuario */	
    $('#txtUser').blur(function(){		
        fnVerificarOficinas();	
    });        
    /* consultamos oficinas y acceso a datos */    
    fnVerificarOficinaAccesoBD();
});
/* * validamos y guardamos el pedido **/
function fnLoginUsuario(){    
	$("#divAlert").html('');    
	if($('#txtUser').val()==''){        
		$("#divAlert").html($.fn.errorStyle2("Debe ingresar el nombre de usuario",$('#txtUser')));        
		return;    
	}    
	if($('#txtPass').val()==''){        
		$("#divAlert").html($.fn.errorStyle2("Debe ingresar el password",$('#txtPass')));        
		return;    
	}    
	if($('#trOficina').is(':visible') && $('#selOficina').val()==''){        
		$("#divAlert").html($.fn.errorStyle2("Debe seleccionar la sucursal",$('#selOficina')));        
		return;    
	}    
	if($('#selBd').is(':visible') && $('#selBd').val()==''){        
		$("#divAlert").html($.fn.errorStyle2("Debe seleccionar el origen de datos",$('#selBd')));        
		return;    
	}    
	/* enviamos el pedidos al servidor */    
	$.ajax({        
		url: "aplication/control/Usuarios/conLogin.php",        
		type:'POST',        
		data: 'bttnAction=login_user&actionajax=true&'+$('#frmMisDatos').serialize(),        
		dataType:'json',        
		beforeSend:function(){            
			$('#divAlert').preload('Validando Usuario...');        
		},        
		success: function(msg){            
			if(msg!==null){                
				if(msg.error===true){                    
					alert(msg.msj);                
				}else{                    
					$("#divAlert").html($.fn.errorStyle2(msg.msj));                    
					if(msg.valido!==null && msg.valido===true){                        
						location.href = msg.url;                    
					}                
				}            
			}else{                
				$("#divAlert").html($.fn.errorStyle2("Error: Ocurrio un error al validar los datos"));            
			}        
		}    
	});
}

/***/
function fnVerificarOficinaAccesoBD(){    
	$.ajax({        
		url: "aplication/control/Usuarios/conLogin.php",        
		type:'POST',        
		data: 'bttnAction=verificar_oficinasacc&actionajax=true&',        
		dataType:'json',        
		success: function(msg){            
			if(msg!==null){                
				if(msg.error===true){                    
					alert(msg.msj);                
				}else{                    
					/* llenamos el combo de oficinas */                    
					$.fn.set_combo(msg[0].rows,'selOficina');                    
					if(msg[0].rows.length>1){                        
						$('#trOficina').show();                    
					}                    
					/* llenamos el combo de bd */                    
					$.fn.set_combo(msg[1].rows,'selBd');                    
					if(msg[1].rows.length>1){                        
						$('#trBd').show();                    
					}                
				}            
			}else{                
				$("#divAlert").html($.fn.errorStyle2("Error: Ocurrio un error al validar los datos -1"));            
			}        
		}    
	});
}

/***/
function fnVerificarOficinas(){	
	if($('#txtUser').val().trim()==''){		
		return;	
	}    
	$.ajax({        
		url: "aplication/control/Usuarios/conLogin.php",
		type:'POST',        
		data: 'bttnAction=verificar_oficinasacc_usuario&actionajax=true&user='+$('#txtUser').val(),        
		dataType:'json',        
		success: function(msg){            
			if(msg!==null){                
				if(msg.error===true){                    
					alert(msg.msj);                
				}else{					
					if(msg.rows!=null && msg.valido===true){						
						/* set oficinas */						
						$('#selOficina').val(msg.rows[0].oficinas_id);					
					}                
				}            
			}else{                
				$("#divAlert").html($.fn.errorStyle2("Error: Ocurrio un error al validar los datos -5"));            
			}        
		}    
	});
}