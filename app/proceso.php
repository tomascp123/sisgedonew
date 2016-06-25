<?
/*	archivo comun/modificación de registros */
include("../mislibs/common.php");

/*	verificación a nivel de usuario */
verificaUsuario(0);

/* variable que se recibe de la opcion para datos de edicion */
$op = getParam("_op");

$erro = new Erro();

if(!$op) $erro->addErro('No envio parámetro de proceso.');   

//	conexión a la BD 
$conn = new db();
$conn->open();
if (!$erro->hasErro()) { // verifico si pasa la validación

	switch($op){
	   case 'ExpExt':  // Registro de Expedte Externo 
			/* Verifico usuario y password */
			$usua_login=getParam("Sr_usua_login"); 	
			$usua_password=getParam("pr_usua_password"); 		

			/* Obtengo datos del usuario */
			$sSql="SELECT id_usu,depe_id FROM usuario WHERE usua_login=upper('$usua_login') and usua_password ='".md5($usua_password)."'"; 
			$rs = new query($conn, $sSql);
			$rs->getrow();
			$id_usu=$rs->field("id_usu");				
			$depe_id=$rs->field("depe_id");		
			$rs->free();
			
			if($id_usu){ /* Si existe el usuario */
				/* Recibo datos desde el formulario */
				$pk_id=getParam("f_id"); /* Campo control que identifica si estoy insertando o modifinado un registro */
				$firma=getParam("Sr_expe_firma");
				$cargo=getParam("Sr_expe_cargo");		
				$siglas_doc=getParam("Sr_expe_siglas_doc");				
				$texp_id=getParam("tr_texp_id"); /* Solicitud */
				$frec_id=1; /* Directa */
				$asunto=getParam("Er_expe_asunto"); 
				$numero_doc=getParam("zr_expe_numero_doc"); 
				$folios=getParam("zr_expe_folios"); 
				
				if(!$pk_id){ /* Solo si estoy ingresando un nuevo registro, verifico si ya fué ingresado */
					/* Verifico si el registro ya fue grabado */
					$periodo=date("Y");
					$sSql="SELECT expe_id 
						   FROM expediente
						   WHERE date_part('year',expe_fecha_doc)=$periodo
						   AND texp_id=6 
						   AND expe_numero_doc=$numero_doc 
						   AND expe_siglas_doc='$siglas_doc'";
			
					$expe_idRegistrado=getDbValue($sSql);
					if($expe_idRegistrado){
						$error="Este expediente ya fue grabado con el registro $expe_idRegistrado. Corrija sus datos";
						break;					
					}
				}
								
				/* Verifico el campo archivo */
				if(!$pk_id and $texp_id==76){ /* Si estoy ingresando un NUEVO REGISTRO y es SOLICITUD CONTRATOS para la D.R. Trabajo */
					$variable='ar_expearchivo';
					$name_upload=$_FILES[$variable]['tmp_name'];
					$name_file=$_FILES[$variable]['name'];			
								
					//si se requiere grabar el archivco en una carpeta especifica, deberá existir una vairbla oculta 
					//en el formulario de nombre 'postPath'
					if(getParam('postPath'))
						$nvoPath_file=PUBLICUPLOAD.getParam('postPath').'/';
					else
						$nvoPath_file=PUBLICUPLOAD;
								
					//si se requiere renombrar el archivo, deberá existir una variable oculta 
					//en el formulario de nombre 'prefFile'
					if(getParam('prefFile')){
						//refrescamos fecha+hora
						$nvoName_file=date("dmY").date("His").'_'.$name_file;
					}
					else
						$nvoName_file=$name_file;
									
					//si ha elejido una nueva imagen, esta se sube
					if($name_file){
						move_uploaded_file($name_upload,$nvoPath_file.$nvoName_file);
						$ar_expearchivo=$nvoPath_file.$nvoName_file;
						//$sql->addField(substr($variable,3), $nvoName_file, "String");
					}
				}
		
				/* Inicio Transacción */
				$conn->begin();
				
				/* Actualizo datos de la Dependencia si han sido cambiados por el usuario */
				$sSql="SELECT depe_siglasexp,depe_representante,depe_cargo FROM dependencia WHERE depe_id=$depe_id"; 
				$rs = new query($conn, $sSql);
				$rs->getrow();
				$siglas_ori=$rs->field("depe_siglasexp");				
				$repre_ori=$rs->field("depe_representante");		
				$cargo_ori=$rs->field("depe_cargo");		
				$rs->free();
				if($siglas_ori!=$siglas_doc or $repre_ori!=$firma or $cargo_ori!=$cargo){
					$sql="UPDATE dependencia SET depe_siglasexp='$siglas_doc',
												depe_representante='$firma',
												depe_cargo='$cargo' 
						  WHERE depe_id=$depe_id";
					$sql= strtoupper($sql);
					$conn->execute($sql); 
					$error=$conn->error();
					if($error){
						break;					
					}
				}

				// objeto para instanciar la clase sql
				$setTable='expediente';
				$setKey='expe_id';
				$typeKey='Number';
				$sql = new UpdateSQL();
				$sql->setTable($setTable);
				$sql->setKey($setKey,$pk_id,$typeKey);
			
				if($pk_id)
					$sql->setAction("UPDATE"); /* Operación */	
				else
					$sql->setAction("INSERT"); /* Operación */
					
				/* Campos */
				$sql->addField('expe_origen', 0, "Number");	
				$sql->addField('depe_id', $depe_id, "Number");							
				$sql->addField('expe_firma', strtoupper($firma), "String");	
				$sql->addField('expe_cargo', strtoupper($cargo), "String");								
				$sql->addField('texp_id', $texp_id, "Number");							
				$sql->addField('expe_numero_doc', $numero_doc, "Number");
				$sql->addField('expe_siglas_doc', strtoupper($siglas_doc), "String");
				$sql->addField('frec_id', $frec_id, "Number");							
				$sql->addField('expe_folios', $folios, "Number");
				$sql->addField('expe_asunto', strtoupper($asunto), "String");
				$sql->addField('id_usu', $id_usu, "Number");								
				$sql->addField('idusu_depe', $depe_id, "Number");							
				if(!$pk_id and $texp_id==76) /* Si estoy ingresando un NUEVO REGISTRO y es SOLICITUD CONTRATOS para la D.R. Trabajo */
					$sql->addField('ar_expearchivo', $ar_expearchivo, "String");																														
					
				$sql=$sql->getSQL()." RETURNING expe_id";
			
				$expe_id=$conn->execute($sql); //obtengo el expe_id del registro ingresado
				$error=$conn->error();
				if($error){
					break;					
				}
		
				/* Registro Derivación de Expediente.  Registro Hijo */
				/* Obtengo el oper_id del registro del expdte */
				if(!$pk_id){ /* Solo si estoy ingresando un nuevo registro, entonces ingreso su derivación */
                                        $oper_depeid_d = $depeid_trabajo;    /* Oficina Trámite Documentario  - Dirección Reg. de Trabajo */
                                   //     $oper_depeid_d = 928;    /* Oficina Trámite Documentario  - Dirección Reg. de Trabajo */
					$oper_idprocesado=getDbValue("select oper_id from operacion where expe_id=$expe_id");
			
					$sSql="insert into operacion(expe_id,depe_id,id_usu,oper_idtope,oper_depeid_d,oper_acciones,oper_idprocesado) 
										values($expe_id,$depe_id,$id_usu,2,$oper_depeid_d,'CONOCIMIENTO',$oper_idprocesado)";
			
					$conn->execute($sSql); 
					$error=$conn->error();
					if($error){
						break;
					}
				}
				
				$conn->commit(); /* termino transacción */		
				$ok=true;		
			}else{
				$error='Error en el Usuario y Contraseña.  Corrija sus datos';
			}
			
			if($pk_id)
				$update='1';
				
		    $destino = "regexpdteexternoconfirma.php?expe_id=$expe_id&update=$update"; 
	   		break;

	   case 'ExpExtElimi':  // Eliminar Expedte Externo 
			/* Verifico usuario y password */
			$usua_login=getParam("Sr_usua_login"); 	
			$usua_password=getParam("pr_usua_password"); 		

			/* Obtengo datos del usuario */
			$id_usu=getDbValue("SELECT id_usu FROM usuario WHERE usua_login=upper('$usua_login') and usua_password ='".md5($usua_password)."'");
			if($id_usu){ 
				/* Recibo datos */
				$expe_id=getParam("zxBusRegistro"); /* Registro buscado */
	
				/* Obtengo ruta del archivo para el caso de los Tipos de expdte 76 */
				$ar_expearchivo=getDbValue("SELECT ar_expearchivo FROM expediente WHERE expe_id=$expe_id");

				/* Elimino expdte */
				$sql="DELETE FROM expediente WHERE expe_id=$expe_id";
				$conn->execute($sql); 
				$error=$conn->error();
				if($error){
					break;					
				}
	
				/* Elimino archivo */
				if($ar_expearchivo)
					unlink ($ar_expearchivo);
	
				$destino = "regexpdteexternoconfirma.php?expe_id=$expe_id&update=2"; 
			}else{
				$error='Error en el Usuario y Contraseña.  Corrija sus datos';			
			}
	   		break;

	   case 'ExpExtAnula':  // Anular Expedte Externo 
			/* Verifico usuario y password */
			$usua_login=getParam("Sr_usua_login"); 	
			$usua_password=getParam("pr_usua_password"); 		

			/* Obtengo datos del usuario */
			$id_usu=getDbValue("SELECT id_usu FROM usuario WHERE usua_login=upper('$usua_login') and usua_password ='".md5($usua_password)."'");
			if($id_usu){ 
				/* Recibo datos */
				$expe_id=getParam("zxBusRegistro"); /* Registro buscado */

				/* Anulo expdte */
				$sql="UPDATE expediente SET expe_estado=9 WHERE expe_id=$expe_id";
				$conn->execute($sql); 
				$error=$conn->error();
				if($error){
					break;					
				}
	
				$destino = "regexpdteexternoconfirma.php?expe_id=$expe_id&update=3"; 
			}else{
				$error='Error en el Usuario y Contraseña.  Corrija sus datos';			
			}
	   		break;


	} // Fin del switch
	
	if($error) 
		alert($error);				
	else {
		// muestra mensaje noticia del la base de datos, pero no detiene la ejecucion						
		$notice=$conn->notice();
		if($notice) 
			alert($notice,0);				
	
		// si es una ventana emergente desde donde se llama al guardar				
		if(strcmp("close",$destino)==0){
			echo "<"."script".">\n";
			echo "javascript:top.close()\n";
			echo "</"."script".">\n";
		}
		else {
			// redirecciona segun la variable $destino
			if($destino) // Si existeun destino al que deseo ir después del proceso
				miRedirect($destino,"content");
		}
	} // Fin del else

} else { // si no pasa la validación
	alert('Mensajes de errores!\n\n'.$erro->toString());
}
//	cierra la conexión con la BD
$conn->close();

function miRedirect($url, $target="content") {
	echo "<script language='JavaScript'>";
	echo "parent.document.location='$url';";
	echo "</script>";
}

?>