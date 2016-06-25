<?
/* Control para cuando ha existido un error */
if($_MensError){
	$_msjconfirma=$_MensError ; // Mensaje que se mostrará en la ventana confirma.php
	$mensajeusuario=' ¡¡ E R R O R !! ';	
}
?>

<table width="100%"  height="80%"  border="0" cellpadding="0" cellspacing="10" class="<? echo iif($_npop,'!=','','','backform')?>">

<tr><td valign="top" >

<table width="652" border="1" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC" align="center"  style="background-color:#99CCFF">
  <tr>
    <td width="644" bordercolor="#CCCCCC">
            <font color="#FF0000"><strong> </strong></font> <font color="#FF0000"><strong><br>
			</strong></font>
            <table width="100%" align="center" cellpadding="2" cellspacing="2">
              <tr bgcolor="#006CA0">
			  	<? if(!$mensajeusuario) $mensajeusuario="EL ALMACENAMIENTO DE DATOS SE HIZO CORRECTAMENTE"; ?>
                <td colspan="2" align="center"> <span class="tituConfirma"> <? echo $mensajeusuario?> </span></td>
              </tr>
              <tr>
                <td height="70%"  align="center"><p class="msjConfirma"><? if($_tipoedicion==1 or $_tipoedicion==2 or $_tipoedicion==10) echo $_msjconfirma ?>&nbsp;</p></td>
                <td width="30%" align="center" bgcolor="#D0DCE0">
				<? 
				if ($_btnconfirma) { 
							if ($_npop){
								echo "<a href=\"#\" onClick=\"popupregresa(1,'".$_SESSION["_frm"]."','".$_npop."','1','".$_idinsert."')\" class=\"linkConfirma\" >Seleccionar registro</a><br><br>";
							}
							echo "<form name=\"".$_nameform."\" action=\"".$_url."?_op=".$_op."&_type=M&_tabactivo=".iif($_tedconfirma,'==',1,2,1)."&_flag=".iif($_tedconfirma,'==',5,2,1)."&pagina=".$_pagina."&orden=".$_orden."&_where=".$_where."&_npop=".$_npop."&_altertable=1".iif($_npop,'!=','','&_tipoedicion=1','')."\" method=\"post\">";
							if($_MensError) /* Si existe un error */
								echo "<input type=\"button\" name=\"btn_termina\" class=\"boton\" OnClick='history.back(1)' value=\"Volver"."\">";
							else
								echo "<input type=\"submit\" name=\"btn_termina\" class=\"boton\"  value=\"".$_btnconfirma."\">";
								
							echo "</form>";
							echo "<br>";
							if($_flag!=2){
							echo "<form name=\"explorar\" action=\"".$_url."?_op=".$_op."&_type=".iif($_npop,'!=','','P','M')."&_tabactivo=1&_npop=".$_npop."&_altertable=1"."\" method=\"post\">";
							echo "<input type=\"submit\" name=\"btn_explorar\" class=\"boton\"  value=\"".$_tab1_caption."\">";
							echo "</form>";
							echo "<br>";
							}
							if (!$_npop && $_PreviewCaption){
								//vista previa de ficha
								echo "<a href=\"#\" onClick=\"AbreVentana('".$_PreviewPage."', 'VistaPrevia')\" class=\"linkConfirma\">$_PreviewCaption</a>";
							}
				} else {
							echo "<form name=\"".$_nameform."\" action=\"".$_url."\" method=\"post\">";
							echo "<input type=\"submit\" name=\"btntermina\" class=\"boton\"  value=\"WEB-Inicio\">";
							echo "</form>";
				}		
					?>		
				</td>
              </tr>
            </table>
			</td>
        </tr>
</table>
</td></tr>

</table>


