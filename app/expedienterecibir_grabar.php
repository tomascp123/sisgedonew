<?
	$_mydato=$_POST[___mydato];	
	$oper_idtope=$_POST[___oper_idtope];	
	$oper_fecha=$_POST[___oper_fecha];	
	$id_usu=$_POST[___id_usu];	
	$depe_id=$_POST[___depe_id];	

	$_array=explode(",",$_mydato);
	for($i=0;$i<count($_array);$i++){
		$_cadena=$_array[$i];
		$_arrayauxi=explode(";",$_cadena);	
		$expe_id=$_arrayauxi[0];
		$oper_id=$_arrayauxi[1];
		$oper_forma=$_arrayauxi[2];
		
		$sqlstring="select my_addoperacion('".$oper_idtope."','".$oper_fecha."','".$id_usu."','".$depe_id."','".$oper_id."',null,null,'".$oper_forma."',null,null,null,null,'".$expe_id."')";

		$query=$db->sql_query($sqlstring);	
		if(!$query) {die($db->sql_error().' Error al recibir documentos '); }

	}

	$_SESSION["ExpXrecibir"]=MyExpXrecibir($_SESSION[depe_id],4); // Actualizo variable de los expdtes. x recibir
	
	header("Location: $_url?_op=$_op&_nametype=confirma.php&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&pagina=$_pagina&orden=$_orden&_where=$_where");
  
?>