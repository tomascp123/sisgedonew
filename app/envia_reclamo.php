<?
// Make sure people don't try and access it directly
include('config.php');
ini_mod(_VERSION_,_SISTEMA_);	
?>
<style type="text/css">
.style9 {font-family: Arial, Helvetica, sans-serif}
.style11 {font-size: 12px}
.style12 {font-weight: bold; font-family: Arial, Helvetica, sans-serif;}
.style13 {
	color: #CC0000;
	font-weight: bold;
}
</style>

<script LANGUAGE="JavaScript">
<!--
var cuenta=0;
function enviado() {
	if (cuenta == 0){
		cuenta++;
		return true;
	}else{
		alert("El Reclamo ya est� siendo enviado, por favor espere un instante.");
		return false;
	}
}

// -->
</script>

<table width="100%"  height="100%"  border="0" cellpadding="0" cellspacing="10"  class="backform" >
<tr><td valign="top" >
<table class="frmline" width="720" align="center"  border="0" cellpadding="0" cellspacing="0">
<form name="email" method="post" action="envia_reclamo_grabar.php" onSubmit="return (ObligaCampos(this) && confirmar('Su Reclamo va a ser Procesado, sus datos est&aacute;n correctos?') && enviado())">
	<tr>
		<td colspan="7" >
			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%" colspan="3">
				<? 				
				 topform("Enviar",0," Libro de Reclamaciones") ;			
				?>				
				<div align="center">Formato de Hoja de Reclamaci&oacute;n</div>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="3">
				<br>
				</td>
			</tr>

			</table>
	<?	

		/* Recibo parametro id de la dependencia */ 
		$depe_idSiga=base64_decode($_GET[pass]);

		/* Cambi� el depe_id enviado por el SIGA al c�digo correspondiente del SISGEDO */
		switch($depe_idSiga){
			case 2: // Sede Regional en SIGA 
				$depe_id = 3; // Sede Central en SISGEDO
				break;		
			case 4: // Direcci�n Regional de Salud
				$depe_id = 5; 
				break;		
			case 15: // UGEL Chiclayo
				$depe_id = 420; 
				break;		
			case 16: // UGEL Lambayeque
				$depe_id = 421; 
				break;		
			case 18: // UGEL Ferre�afe
				$depe_id = 422; 
				break;		
			case 1303: // Direcci�n Regional de Transportes
				$depe_id = 7; 
				break;		
		}

		/* Reordsource de Entidades que cuentan con un Responsable de Transparencia 
		$query="select depe_id,depe_nombre 
				from depenti_v 
				where id_usu_transp is not null
				order by depe_nombre";*/

		$query="select depe_id,depe_nombre 
				from depenti_v 
				where id_usu_transp is not null
				order by depe_nombre";
                
    	$rsEntidad=$db->sql_query($query);	
		if(!$rsEntidad) {die($db->sql_error().' Error en Consulta Entidades'); }

		if($depe_id){
			labelcombo(1,3,'Entidad','tr_entidad='.$depe_id,$row,$rsEntidad);		
			?>
			<input type="hidden"  name="tr_entidad"  value="<?=$depe_id ?>">
			<?
		}else{
			labelcombo(1,1,'Unidad Organica','tr_entidad',$row,$rsEntidad);
		}

	echo'	<tr valign="middle">
		<td width="1%" class"marco"></td>
		<td width class="objeto" align="center" colspan=3><br>(Nombre de la Persona Natural o Raz&oacute;n Social de la Persona Jur&iacute;dica)<br>(Domicilio de la Persona Natural o Entidad)<br>&nbsp;</td>
		<td width="1%" class="objeto"></td>
		</tr>';
	labelcajatxt(1,1, "Nombres y Apellidos","Sr_nombre",$row,60);
	labelcajatxt(1,1, "Domicilio","Sr_domicilio",$row,60);
	labelcajatxt(1,1, "Documento de Identidad","Sr_dni",$row,40);
	labelcajatxt(1,1, "Tel&eacute;fono","Sr_telefono",$row,40);
	labelcajatxt(1,1, "E-mail","cr_email",$row,60);
	echo'	<tr valign="middle">
		<td width="1%" class"marco"></td>
		<td width class="objeto" align="center" colspan=3><br>Identificaci&oacute;n de la Atenci&oacute;n brindada<br>&nbsp;</td>
		<td width="1%" class="objeto"></td>
		</tr>';
	labelareatxt(1,1, "Descripci&oacute;n del Reclamo","Sr_mensaje",$row,12,60);
	?>
		</td>
	<tr>
		<td colspan="7" height="30">
		<? 	
		 bottform("Enviar",1,0,"center") ;
		?>		
		</td>
	</tr>


		<!-- tr height="40" bgcolor="#EFEFEF">
		  <td colspan="4"><div align="center" class="style11"><span class="style12">TENGA EN CUENTA LO SIGUIENTE </span></div></td>
	  </tr -->
		<tr bgcolor="#EFEFEF" >
		  <td colspan="4"><ol>
		    <!-- li>
		      <div align="justify"><span class="style9"> La informaci&oacute;n que tiene <span class="style13">M&Aacute;S DE DOS (2) A&Ntilde;OS DE ANTIGUEDAD</span> requiere por lo general m&aacute;s tiempo de b&uacute;squeda.</span></div>
		    </li>
	        <li class="style9">
	          <div align="justify">Los documentos que usted desea revisar, ser&aacute;n puestos a su disposici&oacute;n con las debidas medidas de seguridad tendientes a mantener su conservaci&oacute;n en el lugar que le indique el funcionario responsable del acceso a la informaci&oacute;n.</div>
	        </li>
	        <li class="style9">
	          <div align="justify">De acuerdo con el art�culo 13 de la ley 27806 de transparencia y acceso a la informaci�n p�blica, a trav�s de una solicitud de informaci�n no se obliga al gobierno regional a: 
				  <ul> 
			        <li class="style9">
					Crear o producir informaci�n con la que no cuente o no tenga la obligaci�n de contar.
			        </li>					
			        <li class="style9">
					Hacer evaluaciones o an�lisis de la informaci�n que posee.
			        </li>					
				  </ul>
			  </div>
	        </li>
		    <li class="style9">
		      <div align="justify">El costo de reproducci&oacute;n (copias fotost&aacute;ticas) es de S/. 0.10 por hoja. Pero usted puede entregar las hojas que requiera para la reproducci&oacute;n y en ese caso el servicio es<span class="style13"> GRATUITO.</span></div>
		    </li>
		    <li class="style9">
		      <div align="justify">Usted puede indicar que la informaci&oacute;n solicitada (Los documentos) le sean remitidos por correo electr&oacute;nico, en ese caso <span class="style13">ASEG&Uacute;RESE QUE SU CUENTA DE CORREO ELECTR&Oacute;NICO TIENE SUFICIENTE CAPACIDAD.</span> </div>
		    </li-->
		  </ol></td>
	  </tr>
	
</td>
</tr>

</form>
</table>
</td></tr>
</table>
