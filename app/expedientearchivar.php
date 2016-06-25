<?
// Make sure people don't try and access it directly
include('checksession.php');

// Archivadores de toda la dependencia y del usuario
$query   = "select archi_id, archi_periodo || ' / ' || archi_nombre   
			from archivador where depe_id=$_SESSION[depe_id] and (archi_idusua is null or archi_idusua=$_SESSION[id]) order by archi_periodo desc,archi_nombre";
$rsarchi=$db->sql_query($query);	
if(!$rsarchi) {die($db->sql_error().' Error en consulta de archivadores '); }

?>
<table width="100%"  height="80%"  border="0" cellpadding="0" cellspacing="10"  class="backform" >
<tr><td valign="top" >
<table class="frmline" width="750" align="center"  border="0" cellpadding="0" cellspacing="0">
<form name="formregistro" method="post" action="<? echo $_url?>?_tipoedicion=<? echo $_tipoedicion?>&_op=<? echo $_op?>&_type=<? echo 'G&_nametype=expedientearchivar_grabar.php' ?>&_tabactivo=<? echo $_tabactivo ?>&_where=<? echo $_where ?>" onSubmit="return ObligaCamposyDisabled(this)">
	<tr>
		<td colspan="7" >
			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%" colspan="3">
				<? 
				topform($_btncaption,$_tipoedicion,$_titulo,1) ;
				?>
				</td>
			</tr>
			</table>

	<?	
	seccion("REGISTROS QUE SERAN ARCHIVADOS",3,3);
	$_array=explode(",",$_mydato);
	for($i=0;$i<count($_array);$i++) {
		$id_reg=str_pad(substr($_array[$i],0,strpos($_array[$i],";")),8,'0',STR_PAD_LEFT);
		labelcajatxt(3,3,"","registro=".$id_reg,$row,8);
	}

	seccion('',3,3);	
	seccion("ARCHIVADOR",2,$_tipoedicion);
								
	//	Campos ocultos
	?>
	<input type="hidden"  name="___mydato" 	value="<? echo $_mydato; ?>">	
	<input type="hidden"  name="___oper_idtope" value="3">
	<input type="hidden"  name="___oper_fecha" value="<? echo date("d/m/Y"); ?>">	
	<input type="hidden"  name="___id_usu"  value="<? echo $_SESSION["id"] ?>">
	<input type="hidden"  name="___depe_id"  value="<? echo $_SESSION["depe_id"] ?>">	
	<?

	// Campos visibles
//    labelcheck(2,1, "Forma","Copia","nx_oper_forma",$row); // $_tipoedicion le envio 1, por el problema del check que no pasa como POST.  Para cualquier control editable, $_tipoedicion puede ser 1 o 2
	labelcombo(2,$_tipoedicion,"Archivador","tr_archi_id",$row,$rsarchi,0,88);
	labelcajatxt(2,$_tipoedicion,"Acciones","Sr_oper_acciones",$row,100);

	?>
	<tr>
		<td colspan="7" height="30">
			<? 	
			bottform($_btncaption,$_tipoedicion,1) ;	 
			?>				
		</td>
	</tr>
</td>
</tr>
</form>
</table>
</td></tr>
</table>
<? 
$db->sql_close();
?>

