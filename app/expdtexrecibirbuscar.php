<?
// Este archivo es una copia de php_grabar de libs
$v_expe_id=$_POST[zsxexpe_id];
$v_tr_depe_depende=$_POST[tr_depe_depende];
$v_tr_oper_usuaid_d=$_POST[tr_oper_usuaid_d];
$v_and=$sqlstring?' and ':'';

// ** // Campos a incluir en el where
if($v_expe_id)
	$sqlstring=$v_and."a.expe_id=$v_expe_id";  // Para mostrar solo el registro solicitado

$v_and=$sqlstring?' and ':'';
if($v_tr_oper_usuaid_d)
	$sqlstring.=$v_and."a.oper_usuaid_d=$v_tr_oper_usuaid_d";  // Para mostrar solo los expedientes que me han derivado desde los sectores

$v_and=$sqlstring?' and ':'';
if($v_tr_depe_depende)
	$sqlstring.=$v_and."c.depe_depende=$v_tr_depe_depende";  // Para mostrar solo los expedientes que me han derivado desde los sectores

// ** //

$type=$_npop?'P':'M';		
header("Location: $_url?_op=$_op&_type=$type&_where=$sqlstring&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&_flag=2&_npop=$_npop");		
	
?>