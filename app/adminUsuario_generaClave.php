<?
/* formulario de ingreso y modificaci�n */
include("../mislibs/common.php"); 

/* Recibo par�metros */
$usua_id = base64_decode(getParam("pass")); 
$cs = base64_decode(getParam("cs")); 

/* establecer conexi�n con la BD */
$conn = new db();
$conn->open();

?>
<html>
<head>
	<title>Genera Contrase�a</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
	<script language='JavaScript'>

	</script>
</head>
<body class="contentBODY" >
<? 
pageTitle("SISGEDO: Sistema de Gesti�n Documentaria","Generaci�n de Contrase�a");

echo '<br>';

/* Verifico c�dgigo de seguridad */
$csBd = getDbValue("SELECT usua_codigoseguridad FROM usuario WHERE id_usu = '$usua_id'");

if($csBd == $cs) {
    /* Cambio la contrase�a del usuario recibido */
    $nvaClave = mt_rand(1000, 9000);
    $sSql="UPDATE usuario SET usua_password = md5('$nvaClave')  
    		WHERE id_usu = $usua_id";
    
    $conn->execute($sSql);
    $error=$conn->error();
    if ($error) {
    	echo 'Error en proceso de Generaci�n de contrase�a.';
    } else {
        echo "<b>Generaci�n de contrase�a exitosa.</b><BR>
        		Su nueva contrase�a es: <b><h3> $nvaClave </h3></b> ";
    }
} else {
    echo 'Error en c�digo de seguridad.  No ha sido posible generar su nueva contrase�a.<br>
    	Vuela a solicitar el proceso de Generaci�n de contrase�a';    
}
?>
<BR>
</body>
</html>
<?php
$conn->close(); 
?>		
