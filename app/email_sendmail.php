<?
	require_once($pathlib."clases/class.phpmailer.php");
	$AddImage=!$AddImage?"../imagenes/logo.gif":$AddImage;
	$from=!$from?_WEBADMIN_:$from;
	$mail=new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	//$mail->Host = "172.16.0.4"; // SMTP server
        $mail->Host = IP_SMTP; // SMTP server

	$mail->FromName=_VERSION_;
	$mail->From = $from;
	$mail->AddAddress($address); 

	$mail->Subject = $subject;

	$mail->WordWrap = 50;
	$mail->IsHTML(true);
	if (file_exists($AddImage)){
		$mail->AddEmbeddedImage($AddImage, "milogo", $AddImage);
		$mail->Body = "<img alt='Logo region' src='cid:milogo'><br>";  
	}
	$mail->Body .= $mensaje;

	$ok_send=$mail->Send();
	$mail->ClearAllRecipients();
	$mail->ClearAttachments()
?>
