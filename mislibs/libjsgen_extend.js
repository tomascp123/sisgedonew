/* JavaScript Document */
/*========================================================================================================================*/
function mivalidacion(objform) { 
   // VALIDO CAMPOS
   switch(objform.name) {
	   case "frmusuario":
			{
			if(objform.sr_usua_password.value!=objform.srxreusua_password.value) {
				alert("vuelva a retipear password");
				objform.srxreusua_password.focus();	   
				return false;
				}
			else
				return true;	
			break;
			}

	default: {return true; break};
	
   }
}

function valida_actionbtngrid(op,tipoedicion,value) { 
   // VALIDO CAMPOS 
	switch(op) {
		case "31": // Expedientes en Proceso
			{
				if(tipoedicion==12) { // Eliminar Derivación
					var myArray = value.split(','); 
					marcados=''
					for(i=0;i<myArray.length;i++){
						//Obtengo el oper_id
						cCadena=myArray[i]
						var myArrayAux = cCadena.split(';') 
						nOPerId=myArrayAux[1]
						TipOpe=myArrayAux[3]
						//Vuelvo a unir los oper_id con comas					
						if(TipOpe==2){ // Solo si està derivao
							marcados= marcados+nOPerId+",";
						}
					}
					if(marcados){
						return true	
					}else{
						alert("No existen Documentos derivados marcados");
						return false
					}
				}

				if(tipoedicion==13 || tipoedicion==11) { // Adjuntar Expedientes o Archivar expedientes
					var myArray = value.split(','); 
					hay_derivado=''
					for(i=0;i<myArray.length;i++){
						//Obtengo el oper_id
						cCadena=myArray[i]
						var myArrayAux = cCadena.split(';') 
						TipOpe=myArrayAux[3]
						//Vuelvo a unir los oper_id con comas					
						if(TipOpe==2){ // si està derivao
							hay_derivado=1
						}
					}
					if(hay_derivado){
						if(tipoedicion==13)
							alert("No es posible Adjuntar Documento(s) que se encuentra(n) derivado(s)");
						else
							alert("No es posible Archivar Documento(s) que se encuentra(n) derivado(s)");
							
						return false
					}else{
						return true	
					}
				}

				return true	
				break;						
			}	   

		case "32": // Expedientes por Recepcionar
			{
				if(tipoedicion==10) { // Recepcionar
					var myArray = value.split(','); 
					hay_expnoperte='' // variable que me indicará si en los elementos seleccionados existe un expediente que no le pertenece al usuario activo
					for(i=0;i<myArray.length;i++){
						//Obtengo el oper_id
						cCadena=myArray[i]
						var myArrayAux = cCadena.split(';')
						usuid_destino=myArrayAux[3]
						usuid_actual=myArrayAux[4]						
						//Vuelvo a unir los oper_id con comas					
						if(usuid_destino!=0 && usuid_destino!=usuid_actual ){ // Si usuario destino es diferente de 0 (se ha derivado el expediente a la dependencia desde otra dependencia) y el usuario destino no es el mismo con el usuario destino
							hay_expnoperte=1
						}
					}
					if(hay_expnoperte){
						alert("No es posible Recibir Documento(s) que no son para usted");
						return false
					}else{
						return true	
					}
				}

				return true	
				break;						
			}	   

		default: {return true; break};
	}
}

function pideusuario(val,depe_id){
	if(val==depe_id){ // Si se está derivando dentro de la misma dependencia
		document.getElementById('Usuario').style.visibility = "visible"		
		document.getElementById('Usuario').name='arXXT01operacionZZoper_usuaid_d'; // me aseguro que el dato sea obligatorio
		document.getElementById('Usuario').disabled=""; // Habilito el objeto

		document.getElementById('___usuario').name='___usuario'; // Hago que el dato no sea obligatorio
		document.getElementById('___usuario').disabled="disabled"; // Deshabilito el objeto oculto para que no pase en el POST
	}else{
		document.getElementById('Usuario').style.visibility = "hidden"		
		document.getElementById('Usuario').name='xxxusuario'; // Hago que el dato no sea obligatorio
		document.getElementById('Usuario').disabled="disabled"; // Deshabilito el objeto para que no pase en el POST

		document.getElementById('___usuario').name='HxXXT01operacionZZoper_usuaid_d'; // Hago que el dato no sea obligatorio
		document.getElementById('___usuario').disabled=""; // Habilito el objeto oculto para que sea este el que pase en el POST
	}
}


