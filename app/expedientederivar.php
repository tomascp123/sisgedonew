<?
// Make sure people don't try and access it directly
include('checksession.php');

// Verifico si deseo derivar más de un expediente a la vez
$_regADerivar=$_mydato;
$_NumregADerivar=count(explode(",",$_mydato));
if($_NumregADerivar==1){  // Si Voy a derivar un solo expediente 
	// Exploto mydato para obtener expe_id,oper_id,oper_forma
	$_array=explode(";",$_mydato);
	$_mydato=$_array[0];
	$_oper_idprocesado=$_array[1]; // doy por procesado el id del expediente.
	$_oper_forma=$_array[2];
	$_oper_idtope=$_array[3];
	
	if($_oper_idtope==1) // Si estoy derivando un expediente que solo està registrado
		$_oper_idprocesado=$_array[1]; // doy por procesado el id del expediente.
	else // Si estoy derivando sobre un expediente ya derivado
		$_oper_idprocesado=$_array[4]; // Doy por procesado el idprocesado del registro que estoy proceando, es decir siempre guardaria en idprocesado el id del expediente original
	
	if($_oper_idprocesado==0) // Si estoy derivando una copia a partir de otra copia
		$_oper_idprocesado='NULL';
	
	// Efectúo consulta para mostrar los datos del registro a derivar
	$query="select a.expe_id,
					a.texp_id,
					lpad(a.expe_numero_doc::TEXT,6,'0') as expe_numero_doc,
					a.expe_siglas_doc || 
					case 
					when a.expe_proyectado!='' then '-' || a.expe_proyectado 
					when a.expe_proyectado='' then a.expe_proyectado 
					end as expe_siglas_doc,
					a.expe_fecha_doc,a.expe_folios,a.expe_asunto 
					from $_table a 
					where a.expe_id=$_mydato";
	$rsexpe=$db->sql_query($query);	
	$row    = $db->sql_fetchrow($rsexpe);
	
	// Tipos de expedientes
	$query="select texp_id,texp_descripcion from tipo_expediente order by texp_descripcion";
	$rstipexpe=$db->sql_query($query);	
	if(!$rstipexpe) {die($db->sql_error().' ERROR EN CONSULTA DE TIPO DE EXPEDIENTE '); }
}	

// Usuarios destino
$query   = "select id_usu,
				   usua_login 
		    from usuario 
			where depe_id=$_SESSION[depe_id] and usua_estado='1' 
			order by usua_nombres";

//			and id_usu<>".$_SESSION['id'].
		
$rsusua=$db->sql_query($query);	
if(!$rsusua) {die($db->sql_error().' ERROR EN CONSULTA DE USUARIOS DESTINO '); }

// Consulta de todos los usuarios para bùsqueda del combo dependiente de usuarios destino
//$query   = "select id_usu, usua_login from usuario order by usua_nombres";
//$rsusuatodos=$db->sql_query($query);	
//if(!$rsusuatodos) {die($db->sql_error().' ERROR EN CONSULTA DE TODOS LOS USUARIOS '); }

?>
<table width="100%"  height="80%"  border="0" cellpadding="0" cellspacing="10"  class="backform" >
<tr><td valign="top" >
<table class="frmline" width="750" align="center"  border="0" cellpadding="0" cellspacing="0">
<form name="formregistro" onsubmit="disable(this)" method="post" action="<? echo $_url?>?_tipoedicion=<? echo $_tipoedicion?>&_op=<? echo $_op?>&_type=<? echo 'G&_nametype=expedientederivar_grabar.php' ?>&_tabactivo=<? echo $_tabactivo ?>&_where=<? echo $_where ?>&_oper_forma=<? echo $_oper_forma ?>" >
	<tr>
		<td colspan="7" >
			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%" colspan="3">
				<? 
				topform($_btncaption,$_tipoedicion,$_titulo,1) ;
				?>
				</td>
			</tr>
			</table>

			<input type="hidden"  name="_regADerivar"  value="<? echo $_regADerivar ?>">
			
	<?	
	if($_NumregADerivar>1){ // Si se han seleccionado más de un expediente para ser derivados
		seccion("REGISTROS QUE SERAN DERIVADOS",3,3);
		$_array=explode(",",$_mydato);
		for($i=0;$i<count($_array);$i++) {
			$id_reg=str_pad(substr($_array[$i],0,strpos($_array[$i],";")),8,'0',STR_PAD_LEFT);
			labelcajatxt(3,3,"","registro=".$id_reg,$row,8);
		}
	}else{ // Voy a derivar un solo expediente 
		seccion("DATOS DEL DOCUMENTO",3,3);
		labelcajanum(3,3, "Registro", "zsxexpe_id",$row,0,8,0);
		labelcombo(3,3,'Tipo de Documento','tr_texp_id',$row,$rstipexpe,0);
		labelcajatxt(3,3, "N&uacute;mero y Siglas","zr_expe_numero_doc",$row,06,'[','*'); 
		labelcajatxt(3,3, "N&uacute;mero y Siglas","Sr_expe_siglas_doc",$row,65,'*',']'); 
		labelcajadate(3,3, "Fecha de Expediente","Dr_expe_fecha_doc",$row,"formregistro");
		labelcajatxt(3,3, "Folios","zr_expe_folios",$row,05); 
		labelareatxt(3,3, "Asunto","ex_expe_asunto",$row,4,80);
	}
	
	seccion('',3,3);	
	seccion("DESTINO(S)",2,$_tipoedicion);
	$idTableDynamic="XXT01";
	$idArrayDynamic="XXA01";
	$TableDB="operacion";

		$HeaderTableDynamic=array("_oper_idtope",
								"_oper_fecha",
								"_id_usu",
								"_depe_id",
								"_oper_idprocesado",
								"_archi_id",
								"_oper_expeid_adj",
								"Forma",
								"Unidad_Organica",
								"Detalle",
								"Usuario",
								"Proveido de atención");
/*
	Los elementos que empiezan con:
	'H' --> Ocultos, Elementos no se mostrarán, pero si se guardan sus datos
	't' --> Combos normales
	'a' --> Elementos que efectúan bùsquedas en arrys javacript para mostrar los datos que deseamos. Ejm. Combos dependientes, check.
*/
		$FieldTableDynamic=array("Hx".$idTableDynamic.$TableDB."ZZ"."oper_idtope",
							"Hx".$idTableDynamic.$TableDB."ZZ"."oper_fecha",
							"Hx".$idTableDynamic.$TableDB."ZZ"."id_usu",
							"Hx".$idTableDynamic.$TableDB."ZZ"."depe_id",
							"Hx".$idTableDynamic.$TableDB."ZZ"."oper_idprocesado",
							"Hx".$idTableDynamic.$TableDB."ZZ"."archi_id",
							"Hx".$idTableDynamic.$TableDB."ZZ"."oper_expeid_adj",
							"ak".$idTableDynamic.$TableDB."ZZ"."oper_forma",
							"tr".$idTableDynamic.$TableDB."ZZ"."oper_depeid_d",
							"St".$idTableDynamic.$TableDB."ZZ"."oper_detalle_destino",
							"ar".$idTableDynamic.$TableDB."ZZ"."oper_usuaid_d",
							"Sr".$idTableDynamic.$TableDB."ZZ"."oper_acciones");

	//	Campos ocultos
	labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[0],$FieldTableDynamic[0]."=2",$row,2);
	labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[1],$FieldTableDynamic[1]."=".date("d/m/Y"),$row,2);
	labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[2],$FieldTableDynamic[2]."=".$_SESSION["id"],$row,2);
	labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[3],$FieldTableDynamic[3]."=".$_SESSION["depe_id"],$row,2);
	labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[4],$FieldTableDynamic[4]."=".$_oper_idprocesado,$row,2);
	labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[5],$FieldTableDynamic[5]."=null",$row,2);
	labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[6],$FieldTableDynamic[6]."=null",$row,2);

	// Campos visibles
	if($_oper_forma){ // Si es copia 
		$_POST[$FieldTableDynamic[7]]=1;
	}
	// he copiado y modificado aquì la función labelcheck_1 el metodo click, para controlar que cuando se derive una copia se siga derivando la copia
    labelcheck(2,1, "Forma","Copia",$FieldTableDynamic[7],$row); // $_tipoedicion le envio 1, por el problema del check que no pasa como POST.  Para cualquier control editable, $_tipoedicion puede ser 1 o 2	

	// Array necesario para búsqueda del valor del check.
	echo "<"."script".">\n";
	echo $FieldTableDynamic[7]."= new Array()\n";	
	echo $FieldTableDynamic[7]."[".$FieldTableDynamic[7].".length] = new Array('0','ORIGINAL')\n";	
	echo $FieldTableDynamic[7]."[".$FieldTableDynamic[7].".length] = new Array('1','COPIA')\n";		
	echo "<"."/script".">\n";	

	$myOpcion="pideusuario(this.value,$_SESSION[depe_id])";
	labelpopup(2,$_tipoedicion,$HeaderTableDynamic[8],$FieldTableDynamic[8],$row,'formregistro',6,'P59',0,70);
	$myOpcion="";	

	labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[9],$FieldTableDynamic[9],$row,100);

	// Estas lìneas son para combos dependientes, para que al modificar el registro de la tabla dinàmica 
	// se muestre el valor correspondiente
	labelcombo(2,$_tipoedicion,$HeaderTableDynamic[10],$FieldTableDynamic[10],$row,$rsusua,0,88);
	crea_array_js($rsusua,$FieldTableDynamic[10]); // Crea array porque el combo anterior es dependiente

	?><input type="hidden" id="___usuario"  name="___usuario"  value="null"><? // Creo campo oculto para cuando no se pida usuario

	$html = "<"."script".">\n";
	$html .= "pideusuario(0,$_SESSION[depe_id])";
	$html .= "</"."script".">\n";
	echo $html;
	
	labelareatxt(2,$_tipoedicion, $HeaderTableDynamic[11],$FieldTableDynamic[11],$row,2,100);
	TableDynamicButton(2,$_tipoedicion,"formregistro",$idTableDynamic,$idArrayDynamic,$HeaderTableDynamic,$FieldTableDynamic,$rsderivados);

	?>
	<tr>
		<td colspan="7" height="30">
			<? 	
			bottform($_btncaption,$_tipoedicion,1) ;	 
			?>				
		</td>
	</tr>
</td>
</tr>
</form>
</table>
</td></tr>
</table>
<? 
$db->sql_close();
?>

