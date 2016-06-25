<?
include("../mislibs/common.php");
/* establecer conexión con la BD */
$conn = new db();
$conn->open();

// Para Ajax
include("../xajax/xajax.inc.php");
$xajax = new xajax();
$xajax->registerFunction("cargar");
$xajax->registerFunction("cargarFile");
$xajax->registerFunction("buscar");

function cargar($expe_id,$usua_login,$op){
	global $conn;
    $objResponse = new xajaxResponse();

	$otable = new AddTableForm();
	$otable->setLabelWidth("20%");
	$otable->setDataWidth("80%");
	$otable->addBreak("<b>Datos del Expediente</b>");

	if($expe_id==0){ /* Si voy a ingresar un nuevo expdte */
		$bd_texp_id=76; /* Para que cargue por defecto Solicitud Contratos */
//		$bd_ar_expearchivo='';
	}else{ /* Voy a modificar o anular un expdte ya registrado */
		$bd_texp_id=76; /* Para que cargue por defecto Solicitud Contratos SI NO ENCUENTRA EL REGISTRO */	
		/* Obtengo el id del usuario */
		$id_usu=getDbValue("SELECT id_usu FROM usuario WHERE usua_login='$usua_login'");		
		
		/* Obtengo los datos del expdte registrado, me aseguro que solo pueda obtener el registro que haya sido ingresado por el usuario */
		$sSql="SELECT * FROM expediente WHERE expe_id=$expe_id AND id_usu=$id_usu"; 

		$rs = new query($conn, $sSql);
		if($rs->numrows()){ /* Si se encontró el registro */
			$rs->getrow();
			$rs->skiprow(0); // Retorno al registro 0
			if($rs->field("expe_estado")==1){
				/* Verifico que aún no se reciba */
				$sSql="SELECT * FROM operacion WHERE expe_id=$expe_id"; 
				$rsOpe = new query($conn, $sSql);
				$operaciones=$rsOpe->numrows();
				$rsOpe->free();			
				/**/
				if($operaciones<3){ /* Si aún no se ha recibido el expedte */
					/* Obtengo los datos del expdte */
					$rs->getrow();
					$bd_texp_id=$rs->field("texp_id");				
					$bd_expe_numero_doc=$rs->field("expe_numero_doc");
					$bd_expe_siglas_doc=$rs->field("expe_siglas_doc");
					$bd_expe_folios=$rs->field("expe_folios");
					$bd_expe_asunto=$rs->field("expe_asunto");
					$bd_expe_firma=$rs->field("expe_firma");				
					$bd_expe_cargo=$rs->field("expe_cargo");			
					$bd_ar_expearchivo=$rs->field("ar_expearchivo");
					$rs->free();
					$otable->addHidden("f_id",$expe_id); // Campo oculto con la clave primaria para permitir el update
					
					/* Agrego botón Elimininar */
					$button = new Button;
					$button->addItem(" Anular ","javascript:anular('Anular')","");
					$button->addItem(" Nuevo Registro ","javascript:nuevo()","");				
					$contenido_respuesta=$button->writeHTML();
				}else{
					$objResponse->addAlert(utf8_encode('Registro ya Recibido.  No es posible editarlo'));
				}
			}else{
				$objResponse->addAlert(utf8_encode('Registro Anulado.  No es posible editarlo'));
			}
		}else{
			$objResponse->addAlert(utf8_encode('Registro no encontrado.  Asegúrese de haber registrado correctamente el USUARIO y el REGISTRO'));
		}
	}
	
	$sqlTipExpdte = "SELECT texp_id as id, texp_descripcion as Descripcion FROM tipo_expediente WHERE texp_id IN (6,76) ORDER BY texp_descripcion ";
	$otable->addField("Tipo: ",listboxField("Tipo",$sqlTipExpdte, "tr_texp_id",$bd_texp_id,"","onChange=xajax_cargarFile(this.value,'',1);document.getElementById('divCargaFile').innerHTML = 'Procesando....';"));
	$otable->addField("N&uacute;mero de Doc.: ",numField("Número de Doc.","zr_expe_numero_doc",$bd_expe_numero_doc,6,6,0).'&nbsp;&nbsp;'.textField("Siglas","Sr_expe_siglas_doc",$bd_expe_siglas_doc,40,40));
	$otable->addField("Folios: ",numField("Folios","zr_expe_folios",$bd_expe_folios,6,6,0));
	$otable->addField("Asunto: ",textAreaField("Asunto","Er_expe_asunto",$bd_expe_asunto,2,100,200));
	$otable->addHtml("<tr><td colspan=2><div id='divCargaFile'>\n");
	$otable->addHtml(cargarFile($bd_texp_id,$bd_ar_expearchivo,2));
	$otable->addHtml("</div></td></tr>\n");
	$otable->addField("Firma: ",textField("Firma","Sr_expe_firma",$bd_expe_firma,60,60));
	$otable->addField("Cargo: ",textField("Cargo","Sr_expe_cargo",$bd_expe_cargo,60,60));

	$contenido_respuesta.=$otable->writeHTML();
	
	$objResponse->addAssign('divCarga','innerHTML', utf8_encode($contenido_respuesta));

	// Se devuelve el objeto, que este dara todas las instruccione JS para realizar la tarea
	$objResponse->addScript("$('#DivBus').hide()");	
	if($op==1){
		return $objResponse;
	}else{
		return $contenido_respuesta	;
	}		
}

function cargarFile($bd_texp_id,$bd_ar_expearchivo,$op)
{
    $objResponse = new xajaxResponse();

	if($bd_texp_id==76){ /* Solicitud Contratos */
		$otable = new AddTableForm();
		$otable->setLabelWidth("20%");
		$otable->setDataWidth("80%");
		if($bd_ar_expearchivo) /* Si estoy editando */
			$otable->addField("Archivo:",textField("Archivo","ar_expearchivo",$bd_ar_expearchivo,80,80,'readonly'));
		else{
			$otable->addField("Archivo:",fileField("Archivo","ar_expearchivo" ,$bd_ar_expearchivo,60)); 
			$otable->addHidden("postPath",'sisgedo'); /* Para subir el archivo dentro de la carpeta 'sisgedo' que debe estar creada dentro de 'DOCS' */
			$otable->addHidden("prefFile",1);  /* Para anteponer al nombre del archivo subido la fecha y hora para que no chanque a otros archivos con el mismo nombre que ya se encuentran subidos */
		}
	
		$contenido_respuesta=$otable->writeHTML();
	}else{
		$contenido_respuesta='';
	}
	
	$objResponse->addAssign('divCargaFile','innerHTML', utf8_encode($contenido_respuesta));

	// Se devuelve el objeto, que este dara todas las instruccione JS para realizar la tarea
	if($op==1){
		return $objResponse;
	}else{
		return $contenido_respuesta	;
	}		
}

function buscar($usuario){
	global $conn;
	
    $objResponse = new xajaxResponse();

	/* Obtengo datos de la dependencia */
	$sSql="SELECT * FROM depext_v WHERE usua_login='$usuario'"; 
	$rs = new query($conn, $sSql);
	$rs->getrow();
	$siglas_doc=utf8_encode($rs->field("depe_siglasexp"));
	$firma=utf8_encode($rs->field("depe_representante"));
	$cargo=utf8_encode($rs->field("depe_cargo"));		
	$rs->free();

	/* Relleno los campos */
	$objResponse->addScript("document.frm.Sr_expe_siglas_doc.value='".$siglas_doc."'");		
	$objResponse->addScript("document.frm.Sr_expe_firma.value='".$firma."'");
	$objResponse->addScript("document.frm.Sr_expe_cargo.value='".$cargo."'");
	
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
	<script type="text/javascript" src="../mislibs/jquerypack.js"></script>
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
			if (confirm('Ha verificado todos sus datos?')) {
				ocultarObj(idObj,10)
				document.frm.target = "controle";
				document.frm.action = "proceso.php?_op=ExpExt";
				document.frm.submit();
			}
		}
	}

	function anular(idObj) {
		if (confirm('Está seguro de ANULAR este registro?')) {
			ocultarObj(idObj,10)
			document.frm.target = "controle";
			document.frm.action = "proceso.php?_op=ExpExtAnula";
			document.frm.submit();
		}
	}

	function nuevo() {
		document.frm.zxBusRegistro.value = "";
		$('#Btnbusregistro').click();
	}


	function inicializa() {
		document.frm.Sr_usua_login.focus();
	}
	
	</script>
    <!-- Este es la impresion de las rutinas JS que necesita Xajax para funcionar -->
    <?php $xajax->printJavascript('../xajax/'); ?>
    
</head>
<body class="contentBODY" onLoad="inicializa()">
<div id='DivBus' align="right" style="width:100;background-color:#FFF1A8;font-size:16px; position:fixed; display:none " >Buscando....</div>
<?
pageTitle("Registro de Expediente","");

/* Formulario */
$form = new Form("frm", "", "POST", "controle", "100%",true);
$form->setLabelWidth("20%");
$form->setDataWidth("80%");
$form->setUpload(true);

$form->addField("Usuario: ",textField("Usuario","Sr_usua_login",'',20,20)."&nbsp;<input type=\"button\" onClick=\"xajax_buscar(document.frm.Sr_usua_login.value)\" value=\"Buscar\">");
$form->addField("Registro: ",textField("Registro","zxBusRegistro",'',10,10)."&nbsp;<input id=\"Btnbusregistro\" type=\"button\" onClick=\"$('#DivBus').show();xajax_cargar(document.frm.zxBusRegistro.value,document.frm.Sr_usua_login.value,1)\" value=\"Buscar\">");
$form->addHtml("<tr><td colspan=2><div id='divCarga'>\n");
$form->addHtml(cargar(0,0,2));
$form->addHtml("</div></td></tr>\n");
$form->addBreak("<b>Datos de Acceso</b>");
$form->addField("Contrase&ntilde;a: ",passwordField("Contraseña","pr_usua_password",$bd_usua_password,20,20));

echo $form->writeHTML();
/* botones */
$button = new Button;
$button->align('C');
$button->addItem(" Guardar ","javascript:salvar('Guardar')","");
$button->addItem(" Cerrar ","javascript:top.close()","");
echo $button->writeHTML();
?>
<iframe src=""  width="0" height="0" id='controle' frameborder="0" name="controle" scrolling="no"></iframe>
</body>
</html>
