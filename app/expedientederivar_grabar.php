<?
	$variableid='zsx'.$_campoclave;	
	$idinsert=$_POST[$variableid];	
//	$_tipoedicion=2; // Para que primero elimine
//	include($pathlib."php_grabar_extend.php");
	include("expedientederivarmultiple_grabar.php");
	header("Location: $_url?_op=$_op&_nametype=confirma.php&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&pagina=$_pagina&orden=$_orden&_where=$_where&_MensError=$_MensError");
?>