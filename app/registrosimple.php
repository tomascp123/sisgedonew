<?
// Make sure people don't try and access it directly
include('checksession.php');
	
// verifica si esta pagina ha sido llamada  para modificacion o visualizacion
if($_tipoedicion==2 || $_tipoedicion==3) {
	$query="SELECT * FROM ".$_table." WHERE ".$_campoclave.'='.$_mydato;
	$rsquery=$db->sql_query($query);	
	if(!$rsquery) {die($db->sql_error().' Error al consultar registro '); }
	$row     = $db->sql_fetchrow($rsquery);
}

?>
<table width="100%"  height="81%"  border="0" cellpadding="0" cellspacing="10"  class="<? echo iif($_npop,'!=','','','backform')?>" >
<tr><td valign="top" > 
<table class="frmline" width="720" align="center"  border="0" cellpadding="0" cellspacing="0">
<form name="<? echo $_nameform ?>" method="post" action="<? echo $_url?>?_tipoedicion=<? echo $_tipoedicion?>&_op=<? echo $_op?>&_type=<? if ($_tipoedicion==1 or $_tipoedicion==2 or $_tipoedicion==5) echo 'G&_nametype='.$pathlib.'php_grabar.php'; else echo 'M'; ?>&_tabactivo=<? echo $_tabactivo ?>&pagina=<? echo $_pagina ?>&orden=<? echo $_orden ?>&_where=<? echo $_where ?>&_npop=<? echo $_npop ?>&_flag=<? echo $_flag ?>" onSubmit="return <? echo iif($_tipoedicion,'<=',2,'ObligaCamposyDisabled(this)','true') ?> ">
	<tr>
		<td colspan="7" >
			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%" colspan="3">
				<? topform($_btncaption,$_tipoedicion,$_titulo) ?>
				</td>
			</tr>
			</table>
			<?	
			include('camposregistro.php');
			?>

	<tr>
		<td colspan="7" height="30">
		<? 	bottform($_btncaption,$_tipoedicion) ;	 ?>
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

