<?
$pathlib="../librerias/";
require_once('config.php') ;
require_once($pathlib.'libphpgen.php');
$idinsert=$_GET['idinsert'];
?>
<html><head>
<title>Enviar Correo Electr&oacute;nico</title>
<script languaje="JavaScript">
function AbreVentana(sURL){
  var w=640, h=480;

  if (window.screen && window.screen.availHeight) {
    h = window.screen.availHeight - 58; // 58
    w = window.screen.availWidth - 4;
  }

  var ventana=window.open(sURL, "Sistema", "status=yes,resizable=yes,toolbar=no,scrollbars=yes,top=0,left=0,width=" + w + ",height=" + h, 1 );
  ventana.focus();
}
</script>


<style type="text/css">
<!--
.style5 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.style7 {
	font-size: 9px;
	color: #000000;
}
.style9 {font-family: Arial, Helvetica, sans-serif}
.style11 {font-size: 12px}
.style12 {font-weight: bold; font-family: Arial, Helvetica, sans-serif;}
.style13 {
	color: #CC0000;
	font-weight: bold;
}
body {
	background-color: #D1D7DC;
}
.Estilo1 {
	font-size: 18px;
	font-style: italic;
	font-weight: bold;
}
.Estilo2 {
	color: #FF0000;
	font-size: 16px;
	font-weight: bold;	
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body>
<center>
<form id="frmEnvia" method="post" action="envia.php?mail=<? echo($mail) ?>">
	<table width="636" bgcolor="#006699">
		<tr bgcolor="#DDDD88">
		  <td width="1584" colspan="2" bgcolor="#EFEFEF"><div align="center" class="style11"><span class="style12">OPERACION EFECTUADA SATISFACTORIAMENTE </span></div></td>
	  </tr>
		<tr bgcolor="#DDDD88">
		  <td colspan="2" bgcolor="#EFEFEF"><ol class="style11">
		    <li>
		      <div align="justify"><span class="style9"> Su reclamo ha sido enviado satisfactoriamente al funcionario responsable de atender las reclamaciones del <? echo _EMPRESA_ ?> el d&iacute;a <? echo fecha_completa(); ?>.</span></div>
		    </li>
	        <li class="style9">
	          <div align="justify">El n&uacute;mero de expediente de su Reclamo es el <span class="Estilo2"><? echo $idinsert ?></span>.  Haga el seguimiento del tr&aacute;mite a trav&eacute;s del Sistema de Gesti&oacute;n Documentaria <a href="javascript:AbreVentana('main.php')">SisGeDo</a>.</div>
	        </li>
		    <!-- li class="style9">
		      <div align="justify">El plazo m&aacute;ximo para recibir la informaci&oacute;n es de siete d&iacute;as h&aacute;biles contados a partir del d&iacute;a siguiente de su envio. Si la entidad requiere m&aacute;s tiempo para satisfacer su pedido (hasta 5 d&iacute;as h&aacute;biles adicionales). El funcionario responsable, se lo comunicar&aacute; por esta v&iacute;a antes del vancimiento de los siete d&iacute;as h&aacute;biles.</div>
		    </li -->
		    <li class="style9">
		      <div align="justify">Si desea revisar documentos, comun&iacute;quese al d&iacute;a h&aacute;bil siguiente del envio de esta comunicaci&oacute;n, con el funcionario responsable de atender los Reclamos al tel&eacute;fono 074-606060 anexo 1104, para que le indique en que oficina puede acceder al documento.</div>
		    </li>
		    </ol>
		    <p align="center" class="Estilo1">Este documento es el cargo de libro de reclamaciones </p>
	      <p>&nbsp;	    </p></td>
	  </tr>
		<tr bgcolor="#006699">
		  <td  align="center" colspan="2"><input type="button" name="Submit"  onClick="javascript:print()" value="Imprimir"></td>
	  </tr>
	</table>	
</form>
</center>
</body>
</html>