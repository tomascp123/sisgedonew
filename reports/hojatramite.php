<?
session_name("SISGEDO");
session_start();
require_once('../app/config.php') ; 
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Hoja de Tr&aacute;mite</title>
<STYLE>
P.breakhere { page-break-before:always; border:0px; margin:0px; background:#FFFF00; }
body {font-size: 9pt;
font-family:Verdana, Arial, Helvetica, sans-serif;}
</STYLE>
<script>
function ShowDiv(ObjName)
	{
	dObj=document.getElementById(ObjName);
	dObj.style.top = document.body.scrollTop;
	dObj.style.left= (document.body.scrollWidth - 85);					
	setTimeout("ShowDiv('"+ObjName+"')",1);
}

function ocultarObj(idObj,timeOutSecs){
	 	// luego de timeOutSecs segundos, el bot�n se habilitar� de nuevo, 
		// para el caso de que el servidor deje de responder y el usuario 
		// necesite volver a submitir. 
	myID = document.getElementById(idObj);
	myID.style.display = 'none';
	document.body.style.cursor = 'wait'; // relojito
	setTimeout(function(){myID.style.display = 'inline';document.body.style.cursor = 'default';},timeOutSecs*1000)
}

</script>
</head>

<body onLoad="ShowDiv('cuadro')">
<DIV align="center" id="cuadro" class="oculto" style="position:absolute;top:50;width:80;height:60">
<BR><IMG SRC="../imagenes/printer.gif" id="printer" width="40" height="40" alt="Imprimir" onClick="ocultarObj('printer',50);javascript:print();" style="cursor:pointer">
</DIV>
<? 
	require_once("../app/config.php");

	$sql=$_SESSION['$_stringsql']; 
	$result=$db->sql_query($sql);	
//echo $sql;
	$numrows=$db->sql_numrows($result);
	
	// Datos de la tabla
	for ($i = 0; $i < $numrows; $i++) {
		$row = $db->sql_fetchrow($result);
?>
<table width="585" border="0">
  <tr>
    <td colspan="6"><? echo _EMPRESA_ ?></td>
  </tr>
  <tr>
    <td colspan="6"><? echo $row[entidad];?></td>
  </tr>
  <tr>
    <td colspan="6"><div align="center"><strong>HOJA DE TRAMITE </strong></div></td>
  </tr>
  <tr>
    <td width="101"><div align="right"><strong>N&ordm; de registro</strong></div></td>
    <td width="96"><? echo $row[expe_id]; ?></td>
    <td width="59"><div align="right"><strong>Fecha</strong></div></td>
    <td width="117"><? echo $row[expe_fecha]; ?></td>
    <td width="51"><div align="right"><strong>Folios</strong></div></td>
    <td width="121"><? echo $row[expe_folios]; ?></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><strong>Expediente</strong></div></td>
    <td colspan="5"><? echo $row[exmaid]; ?></td>
  </tr>  
  <tr>
    <td height="22"><div align="right"><strong>Remitente</strong></div></td>
    <td colspan="5"><? echo rtrim($row[expe_firma]).' '.rtrim($row[expe_cargo]); ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>Documento</strong></div></td>
    <td colspan="5"><? echo $row[expediente]; ?></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Asunto</strong></div></td>
    <td colspan="5"><? echo $row[expe_asunto]; ?></td>
  </tr>
</table>
<table width="586" height="160" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="5"><div align="center">DEL REMITENTE </div></td>
  </tr>
  <tr>
    <td width="63"><div align="center">De</div></td>
    <td width="83"><div align="center">Pase a</div></td>
    <td width="72"><div align="center">Folios</div></td>
    <td width="243"><div align="center">Proveido</div></td>
    <td width="91"><div align="center">Firma</div></td>
  </tr>
  <tr>
    <td height="20"><? echo $row[origen];?></td>
    <td><? echo $row[destino];?></td>
    <td>&nbsp;</td>
    <td><? echo $row[acciones];?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="21">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="21">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="21">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="21">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="21">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="21">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<BR CLEAR="ALL"><BR><P CLASS="breakhere"></P>
<?
	}
?>
</body>
</html>
