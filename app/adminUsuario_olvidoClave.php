<?
/* formulario de ingreso y modificación */
include("../mislibs/common.php"); 

/* establecer conexión con la BD */
$conn = new db();
$conn->open();

/* Seteo para utilizar librerías de Zend */
set_include_path(get_include_path().
            PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT']."/library");

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);
$loader->suppressNotFoundWarnings(false);

// Para Ajax
include("../xajax/xajax.inc.php");
$xajax = new xajax();
$xajax->registerFunction("solicitar");
$xajax->setCharEncoding("iso-8859-1");

function solicitar($usuario,$NameDiv)
{
    global $conn;

    $objResponse = new xajaxResponse();
    
    /* Obtengo el Id del usuario */
    $IdUsuario = getDbValue("SELECT id_usu
		                    FROM usuario  
		                    WHERE upper(usua_login) = upper('$usuario')");

    
    if (!$IdUsuario) { /* NO existe el usuario */
        $objResponse->addScript("$(\".botao\").show();"); /* Muestro los botones */
        $objResponse->addAlert('NO existe el Usuario.  Por favor corrija');
        return $objResponse;
    }		                    

	/* Obtengo el email de la persona */
    $emailUsuario = trim(getDbValue("SELECT usua_email
		                    FROM usuario  
		                    WHERE upper(usua_login) = upper('$usuario')"));


    if (!$emailUsuario or strlen($emailUsuario) == 0) { /* Si no tiene asignado ninguna persona */
        $objResponse->addScript("$(\".botao\").show();"); /* Muestro los botones */
        $objResponse->addAlert('Su usuario no tiene ningún email asignado.');
        return $objResponse;
    } else {

        /* Guardo código de seguridad */
        $cs = mt_rand(1000, 9000);
        
        $sSql="UPDATE usuario SET usua_codigoseguridad = $cs  
        		WHERE id_usu = $IdUsuario";

        $conn->execute($sSql);

        /* Envio mail */
        /* Para usarlo con gmail */
        $config = array('auth' => 'login',
                  'username' => SIS_EMAIL_GMAIL,
                  // in case of Gmail username also acts as mandatory value of FROM header
                  'password' => SIS_PASS_EMAIL_GMAIL,'ssl' => 'tls','port' => 587);

                  $mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com',$config);
                  Zend_Mail::setDefaultTransport($mailTransport);
        
        $mail = new Zend_Mail();
        $IdUsuario = base64_encode($IdUsuario);
        $cs = base64_encode($cs);

        $url_sistema = SIS_URL_SISTEMA . "app/adminUsuario_generaClave.php?pass=$IdUsuario&cs=$cs";
        $empresa = SIS_EMPRESA;
        $mail->setBodyHtml("<b>SISGEDO:</b> Sistema De Gestión Documentaria <BR><BR>
        					<b>Confirmación para restablecer su contraseña.</b><BR><BR>
							Para confirmar el cambio de su contraseña, haga click aquí 
							<a href='$url_sistema'>Regenerar Contraseña</a>
							<BR><BR>
							<b>NOTA.</b><BR>
							Si usted ha recibido este correo sin haberlo solicitado.  ¡Tenga cuidado!.  Esto indica que alguien está solicitando 
							obtener la contraseña del usuario del SISGEDO que le pertenece.  Le recomendamos cambiar su contraseña como precaución 
							y eliminar este correo.<BR><BR>
							<b>IMPORTANTE</b><BR>
							Por favor NO RESPONDA este correo, Ha sido generado de manera automática; su respuesta no será recibida<BR><BR><BR><BR>
							Atte.<BR>
							Soporte SISGEDO<BR>
							$empresa<BR>		
							")
            ->setFrom("luis.a.guevara@gmail.com",'Administrador del SISGEDO')
            ->addTo("$emailUsuario", 'Usuario del SISGEDO')
            ->setSubject('SISGEDO: Instrucciones para restablecer su contraseña');
        
        try {
            $mail->send();
            $mensaje = "Se le ha enviado a su email: <b>$emailUsuario</b>, las instrucciones para restablecer su contraseña.
        				Por favor, revise su correo y siga estas instrucciones.";		    
            
        } catch (Exception $e) {
            $mensaje = 'Error al enviar el Correo...Por favor, Comuníquese con el área de Informática.  <br>
            			Su mensaje de error es: <br>
            			'.$e->getMessage();
        	
            $objResponse->addScript("$(\".botao\").show();"); /* Muestro los botones */            
        }         
    }    

    $contenido_respuesta = $mensaje;

    $objResponse->addAssign($NameDiv,'innerHTML', $contenido_respuesta);

    // Se devuelve el objeto, que este dara todas las instruccione JS para realizar la tarea
    return $objResponse;
}


$xajax->processRequests();
// fin para Ajax
?>
<html>
<head>
	<title>Olvido de Contraseña</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
	<script type="text/javascript" src="<?=PATH_INC?>jquerypack.js"></script>	
	<script language="JavaScript" src="<?=PATH_INC?>focus.js"></script>
	<script language="JavaScript" src="<?=PATH_INC?>libjsgen.js"></script>
    <?php $xajax->printJavascript(PATH_INC.'../xajax/');?>	
	<script language='JavaScript'>

	/*
		se invoca desde la funcion obligacampos (libjsgen.js)
		en esta función se puede personalizar la validación del formulario
		y se ejecuta al momento de gurdar los datos
	*/	
	function mivalidacion(frm) {  
		return true			
	}
	
	/*
		función que define el foco inicial en el formulario
	*/
	function inicializa() {
		document.frm.Sr_usua_login.focus();
	}

		function muestra_cargando(){
		      xajax.$('MensajeCarga').style.display='block';
		   }

		   function oculta_cargando(){
		      xajax.$('MensajeCarga').style.display='none';
		   }
		   
		   xajax.loadingFunction = muestra_cargando;
		   xajax.doneLoadingFunction = oculta_cargando;
		   		
	</script>

</head>
<body class="contentBODY" onload="inicializa()" >
<? 
pageTitle("Olvido de Contraseña","");
?>
Para recuperar su contraseña debe ingresar su Usuario:<BR><BR>

<table border='0' cellpadding='1' cellspacing='0' align='center' width='100%'>
    <tr><td><form name='frm' id='frm'  action='' method='POST' target='content' onKeyUp='highlight(event)' onClick='highlight(event)'>
                <table width='100%' class='FormTABLE' cellspacing=0>
                    <tr><td width='20%' class='LabelTD' nowrap><font class=LabelFONT>Usuario: </font></td><td width='80%' class='DataTD BackTD' title=""><font class='DataFONT'><input type='text' name='Sr_usua_login' id='Usuario' value='' size='20' maxlength='20' onKeyPress='return formato(event,form,this,20)' ></font></td></tr>
                </table>
            </form>
        </td></tr></table>

<input value="Restablecer mi Contraseña" class='botao' type='button' onClick="javascript:$('.botao').hide();xajax_solicitar(document.frm.Sr_usua_login.value,'divOlvido')"> </input>

<div id='divOlvido'>
</div>



<div id="MensajeCarga" style="display: none;">
Enviando Email!.... Por favor espere
</div> 
</body>
</html>
<?php
$conn->close(); 
?>		
