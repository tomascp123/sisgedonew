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
		alert("El formulario ya está siendo enviado, por favor espere un instante.");
		return false;
	}
}

// -->
</script>

<table width="100%"  height="100%"  border="0" cellpadding="0" cellspacing="10"  class="backform" >
<tr><td valign="top" >
<table class="frmline" width="720" align="center"  border="0" cellpadding="0" cellspacing="0">
<form name="email" method="post" action="envia_esol_grabar.php" onSubmit="return (ObligaCampos(this) && confirmar('Su Solicitud va a ser Procesada ¿ sus datos están correctos ?') && enviado())">
	<tr>
		<td colspan="7" >
			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%" colspan="3">
				<? 				
				 topform("Enviar",0," Solicitud Electr&oacute;nica ") ;			
				?>				
				
				</td>
			</tr>
			</table>
	<?	

		/* Recibo parametro id de la dependencia */ 
		$depe_idSiga=base64_decode($_GET[pass]);

		/* Cambió el depe_id enviado por el SIGA al código correspondiente del SISGEDO */
		switch($depe_idSiga){
			case 2: // Sede Regional en SIGA 
				$depe_id=1; // Sede en SISGEDO
				break;		
			case 4: // Dirección Regional de Salud
				$depe_id=614; 
				break;		
			case 15: // UGEL Chiclayo
				$depe_id=498; 
				break;		
			case 16: // UGEL Lambayeque
				$depe_id=539; 
				break;		
			case 18: // UGEL Ferreñafe
				$depe_id=543; 
				break;		
			case 1303: // Dirección Regional de Transportes
				$depe_id=471; 
				break;		

		}

		/* Reordsource de Entidades que cuentan con un Responsable de Transparencia */
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
			labelcombo(1,1,'Entidad','tr_entidad',$row,$rsEntidad);
		}
					
	    labelcajatxt(1,1, "Nombres y Apellidos","Sr_nombre",$row,60);				
	    labelcajatxt(1,1, "E-mail de contacto","cr_email",$row,60);						
  		labelareatxt(1,1, "Mensaje","Sr_mensaje",$row,12,60);			
	?>

	<tr>
		<td colspan="7" height="30">
		<? 	
		 bottform("Enviar",1,0,"center") ;
		?>		
		</td>
	</tr>


		<tr height="40" bgcolor="#EFEFEF">
		  <td colspan="4"><div align="center" class="style11"><span class="style12">TENGA EN CUENTA LO SIGUIENTE </span></div></td>
	  </tr >
		<tr bgcolor="#EFEFEF" >
		  <td colspan="4"><ol>
		    <li>
		      <div align="justify"><span class="style9"> La informaci&oacute;n que tiene <span class="style13">M&Aacute;S DE DOS (2) A&Ntilde;OS DE ANTIGUEDAD</span> requiere por lo general m&aacute;s tiempo de b&uacute;squeda.</span></div>
		    </li>
	        <li class="style9">
	          <div align="justify">Los documentos que usted desea revisar, ser&aacute;n puestos a su disposici&oacute;n con las debidas medidas de seguridad tendientes a mantener su conservaci&oacute;n en el lugar que le indique el funcionario responsable del acceso a la informaci&oacute;n.</div>
	        </li>
	        <li class="style9">
	          <div align="justify">De acuerdo con el artículo 13 de la ley 27806 de transparencia y acceso a la información pública, a través de una solicitud de información no se obliga al gobierno regional a: 
				  <ul> 
			        <li class="style9">
					Crear o producir información con la que no cuente o no tenga la obligación de contar.
			        </li>					
			        <li class="style9">
					Hacer evaluaciones o análisis de la información que posee.
			        </li>					
				  </ul>
			  </div>
	        </li>
		    <li class="style9">
		      <div align="justify">El costo de reproducci&oacute;n (copias fotost&aacute;ticas) es de S/. 0.10 por hoja. Pero usted puede entregar las hojas que requiera para la reproducci&oacute;n y en ese caso el servicio es<span class="style13"> GRATUITO.</span></div>
		    </li>
		    <li class="style9">
		      <div align="justify">Usted puede indicar que la informaci&oacute;n solicitada (Los documentos) le sean remitidos por correo electr&oacute;nico, en ese caso <span class="style13">ASEG&Uacute;RESE QUE SU CUENTA DE CORREO ELECTR&Oacute;NICO TIENE SUFICIENTE CAPACIDAD.</span> </div>
		    </li>
		  </ol></td>
	  </tr>
	
</td>
</tr>

</form>
</table>
</td></tr>
</table>
