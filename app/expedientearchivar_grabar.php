<?
	$_mydato=$_POST[___mydato];	
	$oper_idtope=$_POST[___oper_idtope];	
	$oper_fecha=$_POST[___oper_fecha];	
	$id_usu=$_POST[___id_usu];	
	$depe_id=$_POST[___depe_id];	
	$oper_acciones=strtoupper($_POST[Sr_oper_acciones]);
	$tr_archi_id=$_POST[tr_archi_id];	

	$_array=explode(",",$_mydato);
	for($i=0;$i<count($_array);$i++){
		$_cadena=$_array[$i];
		$_arrayauxi=explode(";",$_cadena);	
		$expe_id=$_arrayauxi[0];
		$oper_id=$_arrayauxi[1];
		$oper_forma=$_arrayauxi[2];
		
		$sqlstring="select my_addoperacion('".$oper_idtope."','".$oper_fecha."','".$id_usu."','".$depe_id."','".$oper_id."','".$tr_archi_id."',null,'".$oper_forma."',null,null,null,'".$oper_acciones."','".$expe_id."')";

		$query=$db->sql_query($sqlstring);	
		if(!$query) {die($db->sql_error().' Error al archivar expediente '); }

	}

	header("Location: $_url?_op=$_op&_nametype=confirma.php&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&pagina=$_pagina&orden=$_orden&_where=$_where");
  
?>