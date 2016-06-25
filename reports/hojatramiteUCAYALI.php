<?
session_name("SISGEDO");
session_start();
require_once('../app/config.php'); 
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Hoja de Tr&aacute;mite</title><STYLE>
P.breakhere { page-break-before:always; border:0px; margin:0px; background:#FFFF00; }
body {font-size: 9pt;
font-family:Verdana, Arial, Helvetica, sans-serif;}
</STYLE><script>
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

        $_SESSION['$_stringsql'];        
	$sql=$_SESSION['$_stringsql']; 
	$result=$db->sql_query($sql);	

	$numrows=$db->sql_numrows($result);
	
	// Datos de la tabla
	for ($i = 0; $i < $numrows; $i++) {
		$row = $db->sql_fetchrow($result);
?>
<table width="620" border="0">
  <tr>
    <td colspan="6"><? echo _EMPRESA_ ?></td>
  </tr>
  <tr>
    <td colspan="6"><? echo $row[entidad];?></td>
  </tr>
  <tr>
    <td colspan="6"><div align="center"><strong>TRAMITE INTERNO</strong></div>
    </td>
  </tr>
  <tr>
    <td height="22" width="90"><div align="right"><strong>Remitente</strong></div></td>
    <td width="230"><? echo $row[origen];  ?></td>
    <td height="22" width="100">
        <div align="right"><strong>N&deg; Registro</strong></div></td>
        <td width="180"><? echo $row[expe_id]; ?>
    </td>
  </tr>
  <tr>
    <td height="22" width="100" colspan="3" >
        <div align="right"><strong>Expdiente</strong></div></td>
        <td width="180"><? echo $row[exmaid]; ?>
    </td>
  </tr>
  <tr>
    <td><div align="right"><strong>Documento</strong></div></td>
    <td><? echo $row[expediente]; ?></td>
    <td><div align="right"><strong>Fecha</strong></div></td>
    <td ><? echo $row[expe_fecha]; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>Folios</strong></div></td>
    <td><? echo $row[expe_folios]; ?></td>
    <td><div align="right"><strong>Area destino</strong></div></td>
    <td><? echo $row[destino];?></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Asunto</strong></div></td>
    <td colspan="3"><? echo $row[expe_asunto]; ?></td>
  </tr>
  <tr>
    <td ></td>
    <td colspan="3"><? echo rtrim($row[expe_firma]).'<br>'.rtrim($row[expe_cargo]); ?></td>
  </tr>
</table>
<table width="620" height="160" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="120"><div align="center">
            Org. Destino
          </div></td>
    <td width="140"><div align="center">
            Acciones
          </div></td>
    <td width="60"><div align="center">
            Fecha
          </div></td>
    <td width="40"><div align="center">
            V&deg; B&deg;
          </div></td>
    <td width="140"><div align="center">
            Observaciones
          </div></td>
    <td width="120"><div align="center">
            Sello y firma Responsable
          </div></td>
  </tr>
  <tr>
    <td height="75"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="75"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="75"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="75"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="75"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="75"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
<TABLE width="620" border="0">
  <TR>
    <TD align="center" colspan="3"><b>ACCION A TOMAR</b></TD>
  </TR>
  <TR>
    <TD>1) Conocimiento y fines</TD>
    <TD>6) Coordinaci&oacute;n </TD>
    <TD>11) Evaluaci&oacute;n y Calificaci&oacute;n</TD>
  </TR>
  <TR>
    <TD>
          2) Tr&aacute;mite
        </TD>
    <TD>
          7) Atenci&oacute;n
        </TD>
    <TD>
          12) Elaborar Contestaci&oacute;n 
        </TD>
  </TR>
  <TR>
    <TD>
          3) Informe
        </TD>
    <TD>
          8) Opini&oacute;n
        </TD>
    <TD>
          13) Archivo
        </TD>
  </TR>
  <TR>
    <TD>
          4) Ayuda Memoria
        </TD>
    <TD>
          9) Conocimiento
        </TD>
    <TD>
          14) Otros
        </TD>
  </TR>
  <TR>
    <TD>
          5) Devolver
        </TD>
    <TD>
          10) V&deg;B&deg;
        </TD>
  </TR>
</TABLE>
<HR align="left" width="620">
<div align="left"><?php echo date('d/m/Y h:i:s') ?></div>
<BR CLEAR="ALL"><BR><P CLASS="breakhere"></P>
<?
	}
?>
</body>
</html>
