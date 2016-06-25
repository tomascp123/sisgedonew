<?
include("../mislibs/common.php");
// Para Ajax
include("../xajax/xajax.inc.php");
$xajax = new xajax();
$xajax->registerFunction("grabar");

function grabar($formData){
	global $conn;
	
	$objResponse = new xajaxResponse();

	/* Me conecto */
	$conn = new db();
	$conn->open();

	/* Verifico usuario y password */
	$usua_login=$formData['Sr_usua_login']; 	
	$usua_password=$formData['pr_usua_password']; 		
	$sSql="SELECT * FROM usuario WHERE usua_login=upper('$usua_login') and usua_password ='".md5($usua_password)."'"; 
	$rs = new query($conn, $sSql);
	if($rs->numrows()){ /* Si existe el usuario */
		/* Obtengo datos necesarios del usuario */
		$rs->getrow();
		$depe_id=$rs->field("depe_id");
		$id_usu=$rs->field("id_usu");		
		$rs->free();
		
		/* Obtengo datos de la dependencia */
		$sSql="SELECT * FROM dependencia WHERE depe_id=$depe_id"; 
		$rs = new query($conn, $sSql);
		$rs->getrow();
		$firma=$rs->field("depe_representante");
		$cargo=$rs->field("depe_cargo");		
		$siglas_doc=$rs->field("depe_siglasexp");				
		$rs->free();

		/* Otros datos */
		$texp_id=6; /* Solicitud */
		$frec_id=1; /* Directa */
		$asunto=$formData['Er_expe_asunto']; 
		$numero_doc=$formData['zr_expe_numero_doc']; 
		$folios=$formData['zr_expe_folios']; 
		$periodo=
		
		/* Verifico si el registro ya fue grabado */
		$periodo=date("Y");
   	    $sSql="SELECT expe_id 
			   FROM expediente
			   WHERE date_part('year',expe_fecha_doc)=$periodo
               AND texp_id=6 
               AND expe_numero_doc=$numero_doc 
               AND expe_siglas_doc='$siglas_doc'";

		$expe_idRegistrado=getDbValue($sSql);
		if($expe_idRegistrado){
			$objResponse->addAlert("Este expediente ya fue grabado con el registro $expe_idRegistrado. Corrija sus datos");
			$objResponse->addScript("$('#Guardar').show()");			
			return $objResponse;			
		}

		/* Inicio Transacción */
		$conn->begin();

		/* Grabo registro padre.  Registro Expediente */
		$sSql="insert into expediente(expe_origen,depe_id,expe_firma,expe_cargo,texp_id,expe_numero_doc,expe_siglas_doc,
										frec_id,expe_folios,expe_asunto,id_usu,idusu_depe) 
								values(0,$depe_id,'$firma','$cargo',$texp_id,$numero_doc,'$siglas_doc',
										$frec_id,$folios,'$asunto',$id_usu,$depe_id) RETURNING expe_id";
		$expe_id=$conn->execute($sSql); //obtengo el expe_id del registro ingresado
		$error=$conn->error();
		if($error){
			$conn-> rollback();	
			$objResponse->addAlert($error);
			return $objResponse;			
		}

		/* Registro Derivación de Expediente.  Registro Hijo */
		/* Obtengo el oper_id del registro del expdte */
		$oper_depeid_d = 928;    /* Oficina Trámite Documentario  - Dirección Reg. de Trabajo */
		$oper_idprocesado=getDbValue("select oper_id from operacion where expe_id=$expe_id");

		$sSql="insert into operacion(expe_id,depe_id,id_usu,oper_idtope,oper_depeid_d,oper_acciones,oper_idprocesado) 
							values($expe_id,$depe_id,$id_usu,2,$oper_depeid_d,'CONOCIMIENTO',$oper_idprocesado)";

		$conn->execute($sSql); 
		$error=$conn->error();
		if($error){
			$conn-> rollback();	
			$objResponse->addAlert($error);
			return $objResponse;			
		}

		$conn->commit(); /* termino transacción */		
		$ok=true;		
		
	}else{
		$objResponse->addAlert(utf8_encode('Error en el Usuario y Contraseña.  Corrija sus datos'));
		$objResponse->addScript("$('#Guardar').show()");					
	}
	
	/* Muestro Mensaje de éxito */
	if($ok){
		$button = "<input  type=\"button\" id=\"Nuevo Expediente\" value=\"Nuevo Expediente\" onClick=\"nuevo()\">";
		$contenido_respuesta="<B>El Proceso se ha efectuado con éxito.  Su número de registro SISGEDO es: $expe_id</B> &nbsp;&nbsp;&nbsp;".$button;
		$objResponse->addAssign('DivResultado','innerHTML', utf8_encode($contenido_respuesta));
	}
	
	return $objResponse;
}

$xajax->processRequests();

?>
<html>
<head>
	<title>Registro de Expediente</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="../mislibs/content_yahoo.css">
	<script language="JavaScript" src="../mislibs/focus.js"></script>
	<script language="JavaScript" src="../mislibs/libjsgen.js"></script>		
	<script language="JavaScript" src="../mislibs/textcounter.js"></script>    
	<script type="text/javascript" src="../../mysiga/inc/jquery/jquerypack.js"></script>
	<script language='JavaScript'>
	function mivalidacion(frm) {  
      var sError="Mensajes del sistema: "+"\n\n"; 	
      var nErrTot=0; 	 
		if (nErrTot>0){ 		
			alert(sError)
			return false
		}else
			return true			
	}
		
	function salvar(idObj) {
		if (ObligaCampos(frm)){
			$('#Guardar').hide(); /* Oculto botón */
			xajax_grabar(xajax.getFormValues('frm'))			
		}
	}

	function inicializa() {
		document.frm.zr_expe_numero_doc.focus();
	}
	
	function nuevo(){
		document.frm.zr_expe_numero_doc.value='';	
		document.frm.zr_expe_folios.value='';	
		document.frm.Er_expe_asunto.value='';					
		inicializa()
		$('#DivResultado').hide();	
		$('#Guardar').show();
	}
	
	</script>
    <!-- Este es la impresion de las rutinas JS que necesita Xajax para funcionar -->
    <?php $xajax->printJavascript('../xajax/'); ?>
</head>
<body class="contentBODY" onLoad="inicializa()">
<div id='DivResultado' align="center" style="background-color:#FFF1A8;font-size:16px;"></div>
<?
pageTitle("Registro de Expediente","");

/* Formulario */
$form = new Form("frm", "", "POST", "controle", "100%",true);
$form->setLabelWidth("20%");
$form->setDataWidth("80%");

$form->addField("N&uacute;mero de Doc.: ",numField("Número de Doc.","zr_expe_numero_doc",'',6,6,0));
$form->addField("Folios: ",numField("Folios","zr_expe_folios",'',6,6,0));
$form->addField("Asunto: ",textAreaField("Asunto","Er_expe_asunto",'',2,100,200));
$form->addBreak("<b>Datos de Acceso</b>");
$form->addField("Usuario: ",textField("Usuario","Sr_usua_login",'',20,20));
$form->addField("Contrase&ntilde;a: ",passwordField("Contraseña","pr_usua_password",$bd_usua_password,20,20));

echo $form->writeHTML();
/* botones */
$button = new Button;
$button->addItem(" Guardar ","javascript:salvar('Guardar')","");
$button->addItem(" Cerrar ","javascript:top.close()","");
echo $button->writeHTML();
?>
</body>
</html>
