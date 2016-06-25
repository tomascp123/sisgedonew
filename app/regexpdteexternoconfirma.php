<?
include("../mislibs/common.php");
$expe_id=getParam("expe_id"); 	
$update=getParam("update"); 	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
.Estilo2 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 18px;
}
.Estilo3 {
	font-size: 24px;
	font-weight: bold;
}


-->
</style>
</head>
<body bgcolor="#D6DEEC">
<table width="100%" height="100%" border="0">
  <tr>
    <td><table width="50%" border="2" align="center" bordercolor="#0000FF" bgcolor="#E8EEFA">
      <tr>
        <td colspan="2" bgcolor="#0000CC"><div align="center" class="Estilo2">Confirmaci&oacute;n</div></td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" height="100%" border="0">
          <tr>
            <td><div align="center">
            	<? 
				if($update==1){
					echo "<p class=\"Estilo1\">La actualizaci&oacute;n del registro $expe_id se ha efectuado satisfactoriamente.</p>";
				}elseif($update==2){
					echo "<p class=\"Estilo1\">El registro $expe_id ha sido eliminado del Sistema.</p>";				
				}elseif($update==3)
					echo "<p class=\"Estilo1\">El registro $expe_id ha sido anulado en el Sistema.</p>";				
				else{ ?>
				  <p class="Estilo1">Su operaci&oacute;n ha sido registrada satisfactoriamente.</p>
				  <p class="Estilo1"> Su n&uacute;mero de registro SISGEDO es:</p>
				  <p class="Estilo3"><strong>
					<?=$expe_id?>
				  </strong></p>
				<? } ?>
            </div></td>
          </tr>
        </table>          </td>
      </tr>
      
      <tr>
        <td width="50%" align="center"><label>
          <input type="submit" name="button" id="button" value="Nuevo Expediente" onClick="location='regexpdteexterno.php'">
        </label></td>
        <td width="50%" align="center"><input type="submit" name="button2" id="button2" value="Cerrar" onClick="top.close()"></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
