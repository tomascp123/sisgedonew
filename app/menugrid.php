<!-- menu mx !-->
<table border=0 cellpadding=0 cellspacing=0 width=100% class="N" id="HMTB" align="left">
<tr><td colspan=2><img src="<? echo $pathlib ?>imagenes/spacer.gif" height=1 width=100%></td></tr>
<tr><td>
	<table border=0 cellpadding=0 cellspacing=0 width=100% class="O">
		<tr><td height="21" style="width:8px"><img src="<? echo $pathlib ?>imagenes/spacer.gif" height=1 width=8></td>
		<?
		  if($_op==32) { ?>
			<td class="LL">|</td>
			<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,10,2,'M','<? echo $_url ?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>')"><img src="../imagenes/sello.gif" border=0 align=absmiddle hspace=1 alt="Recepcionar"> Recepcionar</td>
		<? }	

		  if($_op==33) { ?>
			<td class="LL">|</td>
			<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,4,2,'G','<? echo $_url?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>','¿ Está seguro de DEVOLVER este(os) registro(s) marcados al estado EN PROCESO ?')"><img src="../imagenes/devolver.gif" border=0 align=absmiddle hspace=1 alt="Devolver a EN PROCESO"> Devolver a EN PROCESO</td>
		<? }	

		  if($_op==10) { ?>
				<td class="LL">|</td>
				<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,10,3,'L','<? echo $_url?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>');"><img src="../imagenes/tramite.gif" border=0 align=absmiddle hspace=1 alt="Ver Trámite"> Ver Trámite </td>
		<? }	
		
			if($_op!=32 && $_op!=33 && $_op!=10){ // Expdtes x recibir y Expdtes Archivados y Buscar Expdtes
				if(ereg("[TW]",$_permiso) && $_op!=31 && $_op!=53 && $_op!=54) {//PERMISO DE ADICION ?>
						<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,1,1,'M','<? echo $_url?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>');"><img src="<? echo $pathlib?>imagenes/nuevo.gif" border=0 align=absmiddle hspace=1 alt="Nuevo Registro"> Nuevo </td>
				<? } //FIN PERMISO DE ADICION ?>								
				<? if(ereg("[TE]",$_permiso)) {//PERMISO DE EDICION ?>								
					<td class="LL">|</td>
					<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,2,3,'M','<? echo $_url?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>');"><img src="<? echo $pathlib?>imagenes/editar.gif" border=0 align=absmiddle hspace=1 alt="Editar Registro"> Editar </td>
				<? } //FIN PERMISO DE EDICION 

				if($_op!=54){
					?>	
					<td class="LL">|</td>
					<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,3,3,'M','<? echo $_url?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>');"><img src="<? echo $pathlib?>imagenes/detalle.gif" border=0 align=absmiddle hspace=1 alt="Ver Detalle de Registro"> Detalle</td>			
					<? 
				}									
				if($_op==31){ // Para Eliminar un expdte debe asignarsele al ADMIN o al SUPERVISOR la dependencia que tiene en proceso el expdte a eliminar, para que este lo pueda eliminar
//					 if($_SESSION["id"]==1 or $_SESSION["tipo_user"]=='5')
					 if($_SESSION["id"]==1) {//PERMISO DE ELIMINACION ?>
						<td class="LL">|</td>
						<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,4,2,'G','<? echo $_url?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>','¿ Está seguro de eliminar el(los) registro(s) marcados ?')"><img src="<? echo $pathlib?>imagenes/delete.gif" border=0 align=absmiddle hspace=1 alt="Eliminar"> Eliminar</td>
				  <? }   
				}else{
					 if(ereg("[TD]",$_permiso) and $_op!=54) {//PERMISO DE ELIMINACION?>								
						<td class="LL">|</td>
						<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,4,2,'G','<? echo $_url?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>','¿ Está seguro de eliminar el(los) registro(s) marcados ?')"><img src="<? echo $pathlib?>imagenes/delete.gif" border=0 align=absmiddle hspace=1 alt="Eliminar"> Eliminar</td>
				  <? }   
				}
			}
				?>
					<td class="LL">|</td>
					<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,5,1,'M','<? echo $_url?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>')"><img src="<? echo $pathlib?>imagenes/search.gif" border=0 align=absmiddle hspace=1 alt="Nueva Busqueda"> Buscar</td>
				<?  
			
		  if($_op==31) { ?>
			<td class="LL">|</td>
			<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,10,2,'M','<? echo $_url ?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>')"><img src="../imagenes/derivar.gif" border=0 align=absmiddle hspace=1 alt="Derivar"> Derivar</td>
			<td class="LL">|</td>
			<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,11,2,'M','<? echo $_url ?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>')"><img src="../imagenes/archiva.gif" height="18" border=0 align=absmiddle hspace=1 alt="Archivar"> Archivar</td>
			<td class="LL">|</td>
			<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,12,2,'G','<? echo $_url?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>','¿ Está seguro de eliminar la derivaci&oacute;n de el(los) registro(s) marcado(s) ?')"><img src="<? echo $pathlib?>imagenes/rechazar.gif" border=0 align=absmiddle hspace=1 alt="Eliminar Derivaci&oacute;n"> Eliminar Derivaci&oacute;n</td>
			<td class="LL">|</td>
			<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,13,2,'M','<? echo $_url ?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>')"><img src="../imagenes/adjuntar.gif" border=0 align=absmiddle hspace=1 alt="Adjuntar"> Adjuntar</td>
		<? }	
		
		  if($_op==53) { ?>
			<td class="LL">|</td>
			<td class="P" nowrap onmouseover="MO(event,'TD')" onmouseout="MU(event,'TD')" onclick="actionbtngrid(frmgriprin,10,3,'M','<? echo $_url ?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo $_npop?>','<? echo str_replace("'","\'",$_where) ?>','¿ Está seguro de activar el registro marcado ?')"><img src="<? echo $pathlib?>imagenes/cuenta.gif" border=0 align=absmiddle hspace=1 alt="Permisos"> Permisos</td>
		<?  }	?>

			<td class="LL">|</td>
			<td width=100%>&nbsp;</td>
		</tr>
	</table>
	</td>
	<td style="CURSOR:auto">
	<table border=0 cellpadding=0 cellspacing=0 width=100% class="O">
		<tr><td width=100%>&nbsp;</td>
		</tr>
	</table>
</td>
 </tr>
 <tr>
 <td colspan=2><img src="<? echo $pathlib ?>imagenes/spacer.gif" height=1 width=100%></td>
 </tr>
</table>
<!-- fin menu mx !-->
