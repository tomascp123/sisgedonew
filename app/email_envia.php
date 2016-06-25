<?
$address=_WEBADMIN_;
$from=$_POST['cr_email'];
$subject='Solicitud electronica';
$mensaje=$_POST['er_mensaje'];
$mensaje.='<BR>';
$mensaje.='<BR>';
$mensaje.=$_POST['sr_nombre'];
$AddImage="sin imagen";
				
include("email_sendmail.php");

if(!$ok_send) {
	   echo "Su mensaje no pudo ser enviado....<br>";
	   echo "Mailer Error: " . $mail->ErrorInfo;
	}
	else
	{
		echo "<h2>Mensaje enviado de ". $_POST['sr_nombre']." al Web-Admin</h2><br>";
	}
	
?>

