<? 
session_name("SISGEDO");
session_start();
if ($_GET['_ini']=='ini' &&  $_SESSION["_session"]) {
		$_SESSION["_session"] = 0;		
		header("Location: main.php");
		die();
		}

if (!ini_get("register_globals")) {
    import_request_variables('GPC');}

// Elimino variable de session que se usa en el seekpopup para identificar cuando se ha efectuado una modificacion en la tabla del popup
unset($_SESSION["_altertable"]);
unset($_SESSION["_frm"]);

$_nameop=$_GET['_nameop'];
$_op=$_GET['_op'];
$_type=$_GET['_type'];
$_nametype=$_GET['_nametype'];
$_tabactivo=$_GET['_tabactivo'];		
$_tipoedicion=$_GET['_tipoedicion'];
$_pagina=$_GET["pagina"]; //Le indicamos la página en que estamos.  1 por defecto
$_orden=$_GET["orden"]; //Le indicamos la página en que estamos.  1 por defecto
$_where=str_replace("\'","'",$_GET['_where']); 
$_where2=str_replace("\'","'",$_GET['_where2']); 
$_flag=$_GET["_flag"]; //su valor cambia en el php_grabar solo cuando es busqueda
$err_objeto=$_GET['err_objeto'];
$err_mesaje=$_GET['err_mesaje'];
$_mydato=$_GET['_mydato']; // es enviado desde el grid
$_update=$_GET['_update']; //devuelve verdadero si la grabacion fue exitosa
$_idinsert=$_GET['_idinsert']; //devuelve el id despues de una insercion
$_idexpedi=$_GET['_idexpedi']; //devuelve el id despues de una insercion de expediente
$_extend_grabar=$_GET['_extend_grabar']; // si es verdadero extiende la grabacion cuando existen tablas dinamicas
$_url=$_SERVER['PHP_SELF'];
$_setfocus=$_POST['_setfocus'];
$_MensError=$_GET['_MensError']; 

require_once('config.php') ; 

$_type=$_type?$_type:L;
if($_type=='M' or $_type=='L'){
	require_once('menutabs.php') ; 
	}
else if($_type=='G' or $_type=='GL'){
	require_once('modulo.php') ; 
	}
?>