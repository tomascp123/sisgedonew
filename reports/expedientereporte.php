<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Hoja de Tr&aacute;mite</title>
<link rel="stylesheet" type="text/css" media="screen" href="../mislibs/estilos.css" />
<link rel="stylesheet" type="text/css" media="print" href="../mislibs/tabprint.css" />
<script>
function ShowDiv(ObjName)
	{
	dObj=document.getElementById(ObjName);
	dObj.style.top = document.body.scrollTop;
	dObj.style.left= (document.body.scrollWidth - 85);					
	setTimeout("ShowDiv('"+ObjName+"')",1);
}

</script>
</head>

<body onLoad="ShowDiv('cuadro')">
<DIV align="center" id="cuadro" class="oculto" style="position:absolute;top:50;width:80;height:60">
<BR><IMG SRC="../imagenes/printer.gif" width="40" height="40" alt="Imprimir" onClick="javascript:print();" style="cursor:pointer">
</DIV>
<?
require_once('../app/config.php') ;
$pathlib="../librerias/";
require_once($pathlib.'libphpgen.php');
$_porcenleft="20%";  // Porcentaje de ancho de la parte izquierda de los formularios
$_porcenright="80%"; // Porcentaje de ancho de la parte derecha de los formularios

?>
<table border=0 cellpadding=3 cellspacing=0 width="95%" align="center">
	<tr>
		<td width="9%" rowspan="3" style="border-bottom:1px solid #000099">
			<img src="../imagenes/logo2.gif" width=56 height=39 border=0>
		</td>
	    <td colspan="2"><? echo _EMPRESA_ ?></td>
	</tr>
	<tr>
	  <td >SisGeDo 1.0.0 </td>
      <td ><div align="right">Tramitereporte.php</div></td>
  </tr>
	<tr>
	  <td width="40%" style="border-bottom:1px solid #000099">Sistema De Gesti&oacute;n Documentaria </td>
      <td width="51%" style="border-bottom:1px solid #000099"><div align="right">Impreso el <? echo fecha_completa();?> </div></td>
  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="2" >&nbsp;</td>
  </tr>
</table>
<table class="frmline" width="95%" align="center"  border="0" cellpadding="0" cellspacing="0">
<?
	require_once("../app/config.php");
	require_once('../mislibs/misclases.php') ;
        require_once('../mislibs/clsExpediente.php') ;

	$_classgrid="class=griddatos cellspacing=0 border=1 width=100%" ;
        $MyExpdte = new clsExpediente($_GET[_expe_id]);
        $MyExpdte->consulta_expdte();

        if($MyExpdte->_rsExpdte) { // Si tiene trámite
            seccion2("TRAMITE DEL EXPEDIENTE ".str_pad($MyExpdte->_idExpdte,8,'0',STR_PAD_LEFT),3,3,"center","titrelacionados");
            $MyExpdte->muestra_expdte();
	}else{
		?>
		<tr>
		<td  align="center" colspan='5' class='marco seccionblank' >
		<p>&nbsp; </p>
		<? echo "No existe el documento solicitado... Por favor intente nuevamente !!!";?>
		<p>&nbsp; </p>
		</td>
		</tr>
		<?
	}
?>
</table>
</body>
</html>
