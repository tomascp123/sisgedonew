<?
// Make sure people don't try and access it directly
include('checksession.php');

?>
<table width="100%"  height="80%"  border="0" cellpadding="0" cellspacing="10"  class="backform" >
<tr><td valign="top" >
<table class="frmline" width="750" align="center"  border="0" cellpadding="0" cellspacing="0">
<form name="formregistro" onsubmit="disable(this)" method="post" action="<? echo $_url?>?_tipoedicion=<? echo $_tipoedicion?>&_op=<? echo $_op?>&_type=<? echo 'G&_nametype=expedienterecibir_grabar.php' ?>&_tabactivo=<? echo $_tabactivo ?>&_where=<? echo $_where ?>" >
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
	seccion("REGISTROS QUE SERAN RECEPCIONADOS",3,3);
	seccion('',3,3);	
	$_array=explode(",",$_mydato);
	for($i=0;$i<count($_array);$i++) {
		$id_reg=str_pad(substr($_array[$i],0,strpos($_array[$i],";")),8,'0',STR_PAD_LEFT);
		labelcajatxt(3,3,"","registro=".$id_reg,$row,8);
	}
	seccion('',3,3);
								
	//	Campos ocultos
	?>
	<input type="hidden"  name="___mydato" 	value="<? echo $_mydato; ?>">	
	<input type="hidden"  name="___oper_idtope" value="1">
	<input type="hidden"  name="___oper_fecha" value="<? echo date("d/m/Y"); ?>">	
	<input type="hidden"  name="___id_usu"  value="<? echo $_SESSION["id"] ?>">
	<input type="hidden"  name="___depe_id"  value="<? echo $_SESSION["depe_id"] ?>">	
	<tr>
		<td colspan="7" height="30">
			<? 	
			bottform($_btncaption,$_tipoedicion,1) ;	 
			?>				
		</td>
	</tr>
</td>
</tr>
<?
$html="<"."script".">\n";
$html.="setfocus('formregistro'".iif($_POST['_setfocus'],'!=','',",'".$_POST['_setfocus']."'","").")\n";
$html.="<"."/script".">\n";
echo $html;
?>

</form>
</table>
</td></tr>
</table>
<? 
$db->sql_close();
?>

