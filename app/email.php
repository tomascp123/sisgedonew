<?
include('config.php');
ini_mod(_VERSION_,_SISTEMA_);	

if($_POST['btn_Enviar']) include('email_envia.php');
else {
?>
<table width="100%"  height="80%"  border="0" cellpadding="0" cellspacing="10"  class="backform" >
<tr><td valign="top" >
<table class="frmline" width="720" align="center"  border="0" cellpadding="0" cellspacing="0">
<form name="email" method="post" action="email.php" onSubmit="return ObligaCampos(this)">
	<tr>
		<td colspan="7" >
			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%" colspan="3">
				<? 				
				 topform("Enviar",0,"Mensaje Nuevo") ;			
				?>				
				
				</td>
			</tr>
			</table>

	<?	
	    labelcajatxt(1,1, "Nombre","sr_nombre",$row,60);				
	    labelcajatxt(1,1, "E-mail de contacto","cr_email",$row,60);						
		labelcajatxt(1,1, "Asunto","sr_asunto",$row,60); 				
  		labelareatxt(1,1, "Mensaje","er_mensaje",$row,12,60);			
	?>

	<tr>
		<td colspan="7" height="30">
		<? 	
		 bottform("Enviar",1,0,"center") ;
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
}
?>
