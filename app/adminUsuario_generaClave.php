<?
/* formulario de ingreso y modificación */
include("../mislibs/common.php"); 

/* Recibo parámetros */
$usua_id = base64_decode(getParam("pass")); 
$cs = base64_decode(getParam("cs")); 

/* establecer conexión con la BD */
$conn = new db();
$conn->open();

?>
<html>
<head>
	<title>Genera Contraseña</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
	<script language='JavaScript'>

	</script>
</head>
<body class="contentBODY" >
<? 
pageTitle("SISGEDO: Sistema de Gestión Documentaria","Generación de Contraseña");

echo '<br>';

/* Verifico códgigo de seguridad */
$csBd = getDbValue("SELECT usua_codigoseguridad FROM usuario WHERE id_usu = '$usua_id'");

if($csBd == $cs) {
    /* Cambio la contraseña del usuario recibido */
    $nvaClave = mt_rand(1000, 9000);
    $sSql="UPDATE usuario SET usua_password = md5('$nvaClave')  
    		WHERE id_usu = $usua_id";
    
    $conn->execute($sSql);
    $error=$conn->error();
    if ($error) {
    	echo 'Error en proceso de Generación de contraseña.';
    } else {
        echo "<b>Generación de contraseña exitosa.</b><BR>
        		Su nueva contraseña es: <b><h3> $nvaClave </h3></b> ";
    }
} else {
    echo 'Error en código de seguridad.  No ha sido posible generar su nueva contraseña.<br>
    	Vuela a solicitar el proceso de Generación de contraseña';    
}
?>
<BR>
</body>
</html>
<?php
$conn->close(); 
?>		
