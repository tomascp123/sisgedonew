<?
// Make sure people don't try and access it directly
include('checksession.php');

if($_SERVER["HTTP_REFERER"]){
	$_pag_volver = $_SERVER["HTTP_REFERER"];
	$_btn_caption="Volver";	
}else{
	$_pag_volver="main.php";
	$_btn_caption="Buscar otro registro";
}		

?>
<table width="100%"  height="80%"  border="0" cellpadding="0" cellspacing="10"  class="backform" >
	<tr>
		<td valign="top" > 
			<table class="frmline" width="855" align="center"  border="0" cellpadding="0" cellspacing="0">
				<form name="formregistro" method="post" action="<? echo $_pag_volver; ?>">
				<tr>
					<td colspan="7" >
						<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="100%" colspan="3">
								<? topform($_btncaption,0,$_titulo) ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?	
                                    include('camposregistro.php');
				?>
				<tr>
					<td colspan="7" bgcolor="#CDD5DB" height="30" align="right">
					 <input  type="button" class="boton" name="btn_imprime" onClick="AbreVentana('../reports/tramitereporte.php?_expe_id=<? echo $_POST[txtexpeid] ?>','Tramite')" value=".:: Imprimir ::." >&nbsp;&nbsp;
					 <input type="submit" class="boton" name="btn_volver" value=".:: <? echo $_btn_caption ?> ::." >&nbsp;&nbsp;
					</td>
				</tr>
				</form>
			</table>
		</td>
	</tr>
</table>
<? 
$db->sql_close();
?>

