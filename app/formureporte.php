<?
// Make sure people don't try and access it directly
include('checksession.php');
require_once($pathlib.'/clases/myformreporte.php') ;
require_once('../mislibs/clsreportes.php') ;
?>
<script language='JavaScript'>
	function resetform(){
		document.frmreporte.target = "";
		document.frmreporte.action = "";
	}
	
	function imprimir(sURL) {
		document.frmreporte.target = "controle";
		document.frmreporte.action = sURL;
		document.frmreporte.submit();
		setTimeout("resetform()", 500)
	}
</script>

<table width="100%" height="81%" border="0" cellpadding="
	cellspacing="10" class="<? echo iif($_npop,'!=','','','backform')?>">
	<tr>
		<td valign="top">
		<form name="<? echo $_nameform ?>" method="post">
		<table id="luis" class="frmline" width="720" align="center" border="0"
			cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="7">
				<table width="100%" align="center" border="0" cellpadding="0"
					cellspacing="0">
					<tr>
						<td width="100%" colspan="3"><? topform($_btncaption,0,$_titulo) ?>
						</td>
					</tr>
				</table>
				</td>
			</tr>
			<tr bgcolor="#EFEFEF">
				<td width="100%"><? $MyFrmRepo = new clsformrepo(); ?></td>
			</tr>
			<tr>
				<td bgcolor="#EFEFEF" height="10" align="left">
                                <span class="acctitle">
                                <a href="main.php?_op=11&_type=L&_nameop=Otros Reportes" >Otros Reportes</a>
                                </span
                                </td>
			</tr>
			<tr bgcolor="#EFEFEF">
				<td width="100%"><? $MyFrmRepo->creo_capaobjetos(); ?></td>
				<?
				if($_POST[btn_imprime]){	// Si he presionado el botón imprimir
					$MyFrmRepo->creo_sql();
				}
				 ?>
				</td>
			</tr>
			<tr>
				<td bgcolor="#EFEFEF" height="10" align="center">	

                                </td>
			</tr>
			<tr>
                            <td bgcolor="#EFEFEF" colspan="7" height="30" align="center">


                                    <input type="hidden"
					name="_setfocus" value="<? echo $_POST['_setfocus']?>"> 
						<?if($MyFrmRepo->_idRepo==1 or $MyFrmRepo->_idRepo==7 or $MyFrmRepo->_idRepo==8 or $MyFrmRepo->_idRepo==9 or $MyFrmRepo->_idRepo==10 or $MyFrmRepo->_idRepo==6){
							$rptFile=saca_valor("select * from reporte where repo_id=".$MyFrmRepo->_idRepo,'repo_archivo');?>
							<input type="submit" onClick="imprimir('<?=$rptFile?>')" class="boton" name="btn_imprime" value=".:: Imprimir ::."> 
						<? }else{ ?>
							<input type="submit" class="boton" name="btn_imprime" value=".:: Imprimir ::."> 
						<?}?>
				</td>
			</tr>
		</table>
		</form>
		</td>
	</tr>
</table>
<IFRAME name="controle" frameborder=0 scrolling="auto" width="800"
	height="0"> </IFRAME>
