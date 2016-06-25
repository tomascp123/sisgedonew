<script languaje="JavaScript">

function AbreVentanamess(sURL, Handle){
  var w=550, h=510;
  var ventana=window.open(sURL, Handle, "status=yes,resizable=yes,toolbar=no,scrollbars=yes,top=0,left=0,width=" + w + ",height=" + h, 1 );
  ventana.focus();
}

</script>

<style type="text/css">
<!--

a:link {
	color:#003366
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color:#003366
}
a:hover {
	text-decoration: none;
	color:#003366
}
a:active {
	text-decoration: none;
	color:#003366
}
.Estilo4 {
	color: #0000FF;
	font-size: 16px;
	font-family: Arial, Helvetica, sans-serif;
	font-style: italic;
}
.Estilo6 {
	color: #990000;
	font-weight: bold;
}
.Estilo7 {color: #000066}

-->
</style>

<table width="100%" height="80%" border="0" cellpadding="2" cellspacing="2">
	<?
		if(!$_SESSION["id"]){	
		?>
		<DIV align="center" id="cuadro" style="position:absolute;left:1;top:400;width:80;height:60">
		</DIV>

		  <tr valign="top">
    		<td >
				  <form name="form1" method="post" action="main.php?_op=1C&_type=L&_nameop=Trámite del Expediente">
					<table width="690" border="0">
						<tr>
						  <td width="310"><img src="../imagenes/gedo.jpg" width="310" height="184"></td>
						  <td width="97"><input name="txtexpeid" type="text" size="15"></td>
						  <td width="299"><input class="submit2" type="submit" name="ver_tramite" value="Buscar" /></td>
						</tr>
						<tr>
						  <td align="right"><BR><P><BR><P><BR><P><a href="main.php?_op=10&_type=M"><img src="../imagenes/buscar.gif" width="32" height="32" border="0" ></a></td>
						  <td colspan="2"><img src="../imagenes/noricordo.jpg" width="400" height="146"></td>
					  </tr>
						<tr>
						  <td height="23" colspan="3" align="right"><span class="Estilo4"> <span class="Estilo6">&iquest;No has podido ubicar tu expediente?</span> <span class="Estilo7">Comun&iacute;cate con nosotros por el <a href="javascript:AbreVentanamess('../../messenger/app/main.php?_op=1I&_type=L&_idsector=1')"><u>MessengerRL</u></a></span></span></td>
					  </tr>
						<tr>
						  <td height="23" colspan="3" align="right"><? 
							  include("contador.php"); 
						  ?></td>
					  </tr>
					</table>
				  </form>
			  </td>
  		</tr>
		<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
		</script>
		<script type="text/javascript">
		_uacct = "UA-2602606-3";
		urchinTracker();
		</script>
	  <? } else{ ?>
	<tr height="100%">
		<td  valign="top">
						<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
							  <td>&nbsp;</td>
							  <td >&nbsp;</td>
							  <td >&nbsp;</td>
							  <td >&nbsp;</td>
						  </tr>
							<tr>
							<td width="20%">
							  <a href="main.php?_op=31&_type=M"><img border="0" src="../imagenes/enproceso52.gif" ></a>
							  </td>
							<td width="20%" ><a href="main.php?_op=32&_type=M"><img border="0" src="../imagenes/porrecepcionar5.gif" ></a>
							  </td>
							<td width="20%" ><a href="main.php?_op=33&_type=M"><img border="0" src="../imagenes/cajon.gif"></a>
							  </td>
							<td width="20%" ><a href="../manual/manual_sisgedo.zip"><img border="0" src="../imagenes/help.gif"></a></td>
							</tr>
							<tr>
							  <td nowrap><a href="main.php?_op=31&_type=M">Documentos</a></td>
							  <td nowrap><a href="main.php?_op=32&_type=M">Documentos</a></td>
							  <td nowrap><a href="main.php?_op=33&_type=M">Documentos</a></td>
							  <td nowrap><a href="../manual/manual_sisgedo.zip">Manual de </a></td>
						  </tr>
							<tr>
							  <td nowrap><a href="main.php?_op=31&_type=M">en Proceso </a></td>
							  <td nowrap><a href="main.php?_op=32&_type=M"> por Recibir</a></td>
							  <td nowrap><a href="main.php?_op=33&_type=M">Archivados/Procesados</a></td>
							  <td nowrap><a href="../manual/manual_sisgedo.zip">Ayuda</a></td>
						  </tr>
							<tr>
							  <td colspan="4" valign="middle" nowrap><p>&nbsp;</p></td>
						  </tr>
							<tr>
							  <td valign="middle" nowrap><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>		      </td>
							  <td valign="middle" nowrap><a href="main.php?_op=10&_type=M"><img src="../imagenes/buscar.gif" width="32" height="32" border="0" ></a></td>
							  <td valign="middle" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="main.php?_op=1P&_type=M&_nameop=Actualizar datos de mi Cuenta"><img border="0" src="../imagenes/clave5.gif" ></a>
						      </td>
							  <td valign="middle" ><a href="logout.php?_url=/<? echo _CARPETAAPP_; ?>/app/main.php"><img src="../imagenes/salir1.gif" width="25" height="28" border="0"></a>
						      </td>
							</tr>
							<tr>
							  <td valign="middle" nowrap>&nbsp;</td>
							  <td valign="middle" nowrap><a href="main.php?_op=10&_type=M">Buscar</a></td>
							  <td valign="middle" nowrap><a href="main.php?_op=1P&_type=M&_nameop=Actualizar datos de mi Cuenta">Cambiar Contrase&ntilde;a</a></td>
							  <td valign="middle" nowrap><a href="logout.php?_url=/<? echo _CARPETAAPP_; ?>/app/main.php">Finalizar Sesi&oacute;n </a></td>
						  </tr>
						</table>


		</td>	  		
	</tr>
            
    <? } ?>
</table>
</html>

