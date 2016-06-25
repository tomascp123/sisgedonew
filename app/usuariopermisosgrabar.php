<?
$id_usu=$_POST['zsxid_usu'];
$query=" delete from usuario_permisos where ".$_campoclave.'='.$id_usu;
$rsquery=$db->sql_query($query);

foreach($_POST as $variable=>$valor)
if (ereg("_op", $variable)) {//OBTENGO LOS NOMBRE DE LOS CAMPOS SOLO DE LOS OBJETOS Q TIENEN DATOS
	$op=substr($variable,1,2);
	$fecha=date("Y/m/d");
	$hora=date(" H:i:s",time());
	$tipopermiso = trim($_POST[$variable]);
	if(!$tipopermiso)
		$permiso='R';//POR DEFECTO SOLO LECTURA
	else{
//		$permiso=saca_valor("select * from tabla where tabl_tipo='IDPERM' and rtrim(tabl_codigo)='".$_POST[$variable]."'",'tabl_abreviado');
		$permiso=saca_valor("select * from tabla where tabl_tipo='IDPERM' and rtrim(tabl_codigo)='$tipopermiso'",'tabl_abreviado');		
	}

	$sqlstring="insert into usuario_permisos (id_usu,op,tipopermiso,permiso,fecha,hora) ";
	$sqlstring.="values ($id_usu,'$op','$tipopermiso','$permiso','$fecha','$hora')";
	$query=$db->sql_query($sqlstring);
	if(!$query) {die(print_r($db->sql_error()).' Error al grabar permisos ');}
}
header("Location: $_url?_op=$_op&_nametype=confirma.php&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&pagina=$_pagina&orden=$_orden&_where=$_where");
?>