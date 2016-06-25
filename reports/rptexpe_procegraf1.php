<?
        session_name("SISGEDO");
	session_start(); 
	require_once("../app/config.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documentos en Proceso</title>
<STYLE>
body {font-size: 7pt;
font-family:Verdana, Arial, Helvetica, sans-serif;}
.Estilo1 {color: #FF0000;
font-size:10px}

#parentX {
	cursor:pointer;
	font-weight:800;

}

</STYLE>
<script>
function ShowDiv(ObjName)
	{
	dObj=document.getElementById(ObjName);
	dObj.style.top = document.body.scrollTop;
	dObj.style.left= (document.body.scrollWidth - 85);					
	setTimeout("ShowDiv('"+ObjName+"')",1);
}

function toggleDisplay(rowIndex) {
  var obj = document.getElementById(rowIndex);
  obj.style.display = obj.style.display!="none"?"none":"";
}

</script>
</head>

<body onLoad="ShowDiv('cuadro')">
<DIV align="center" id="cuadro" class="oculto" style="position:absolute;top:50;width:80;height:60">
<BR><IMG SRC="../imagenes/printer.gif" width="40" height="40" alt="Imprimir" onClick="javascript:print();" style="cursor:pointer">
</DIV>
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="80%">
<TR>
<TD CLASS="TITULO" WIDTH="70%" style="background:#CCCCCC;color:#505050;"><b>&nbsp;Documentos En Proceso </b><br>
  <b>&nbsp;Modulo: Reportes</b><br><br><br>
  <b>&nbsp; <? echo date("d/m/Y").' | '.date(" H:i:s",time()); ?></b><br>   
</TD>
<TD CLASS="TITULO" style="background:#A0A0A0;color:#FFFFFF;" ALIGN="CENTER"><b><? echo _EMPRESA_; ?></b>
</TD>
<TD CLASS="HEADER" style="background:#CCCCCC;color:#FFFFFF;"><IMG SRC="../imagenes/logo2.gif" width="90" height="60">
</TD>
</TR>
</TABLE>

<? 
	/* Obtengo el Total de expedientes en proceso */
	$sql=$_SESSION['$_stringsql2'];
	$result=$db->sql_query($sql);
	$row=$db->sql_fetchrow($result);
	$totalenproceso=$row[total];
	$db->sql_freeresult($rsquery);	

	/* Obtengo el total de expedientes */
	$sql="select count(*) as total from expediente ";
	$result=$db->sql_query($sql);
	$row=$db->sql_fetchrow($result);
	$total=$row[total];
	$db->sql_freeresult($rsquery);	

	/* Consulta de expedientes en proceso por oficina y usuario */ 
	$sql=$_SESSION['$_stringsql'];
	$result=$db->sql_query($sql);	
	$numrows=$db->sql_numrows($result);

	$porctotal = round($totalenproceso*100/$total,2);

	/* Variable que indica si deseo obtener solo datos de un dependencia, para evitar que salga la barra de la Dependencia en rojo  */ 
	$solodep=stripos($sql, 'b.depe_id=34');

	?>
	<table width="80%" border="0">
		<tr>
			<td width="100%"><div align="right" class="Estilo1"><? echo "TOTAL GENERAL DE DOCUMENTOS: ".$total; ?></div>
			</td>
		</tr>
		<tr>
			<td width="100%"><div align="right" class="Estilo1"><? echo 'TOTAL DE DOCUMENTOS EN PROCESO:  [ '.$totalenproceso.' ] &nbsp;&nbsp;'.$porctotal.'%' ; ?></div>
			</td>
		</tr>
	</table>
	<BR>
	<table width="80%" border="1">
	<tr>
		<td width="40%" >UNIDAD ORGANICA</td>
		<td width="40%" >BARRA GRAFICA</td>		
		<td width="10%" >%</td>				
		<td width="10%" >TOTAL </td>		
	</tr>
	</table>
	<div id="tbl_fondo"  style="width:80%;">
	<?
	// Datos de la tabla
		while ($row=$db->sql_fetchrow($result)) {
			$porc = round($row["total"]*100/$totalenproceso,2);
			switch($porc){
			   case $porc>70: // rojo
			   		$Imagen='../imagenes/b_rojo.gif';
			   		break;
			   case $porc<10: // azul
			   		$Imagen='../imagenes/b_azul.gif';
			   		break;
			   case $porc>10 && $porc<50 : // verde
			   		$Imagen='../imagenes/b_verde.gif';
			   		break;
			   case $porc>50 && $porc<70 : // naranja
			   		$Imagen='../imagenes/b_naranja.gif';
			   		break;
			}

			
			if($row[id_usu]==0){ // Es padre
				if($abrio_div){
					$abrio_div=0;
					?></div><?			
				}
				?>
				<table width="100%" border="0">
				    <tr onClick="toggleDisplay('<? echo $row[depe_id]; ?>')">
						<td id="parentX" width="40%"><? echo $row[oficina]; ?></td>
						<td width="40%">
							<? if(!$solodep){ // Solo si muestro todas las dependencias ?> 
							<IMG HEIGHT="10" WIDTH="<? echo $porc ?>%" SRC="<? echo $Imagen ?>">
							<? } ?>
						</td>
						<td width="10%">
							<? echo $porc.'%'; ?>
						</td>
						<td width="10%">
							<? echo '[ '.$row[total].' ]'; ?>
						</td>
				    </tr>
				</table>
				<?
			}else{ // Es hijo
				$porc = round($row["total"]*100/$totalenproceso,2);
				if(!$abrio_div){
					$abrio_div=1;
					?><div id="<? echo $row[depe_id]; ?>" style="display:" ><?			
				}
				?>
				<table width="100%" border="0">
				  <tr>
					<td width="5%"></td>
					<td width="35%"><? echo $row[usuario]; ?></td>
					<td width="40%">
					<IMG HEIGHT="10" WIDTH="<? echo $porc ?>%" SRC="<? echo $Imagen ?>">
					</td>
					<td width="10%">
						<? echo $porc.'%'; ?>
					</td>
					<td width="10%">
						<? echo '[ '.$row[total].' ]'; ?>
					</td>
				  </tr>
				</table>
				<?
			}
			
	}
?>
</div>
</body>
</html>
