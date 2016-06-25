<?
//Datos de conexi�n hacia la Base de Datos
//$dbhost = 'localhost';	// hostsdasdasdasdasdasd
$dbhost = 'localhost';
$dbUsuario = 'postgres';	// usuario de conexi�n a la BD
$dbpassword = '123456';	// password de conexi�n a la BD
$dbName='sisgedonew'; // Nombre de la Base Datos
$dbtype = "postgres";  // Tipo de Bd

/* Servidor Smtp encargado de enviar correos. */
$ipSmtp = "172.16.0.4";

/* depe_id de la oficina tramite documentario de la Direcci�n Regional de Trabajo.  Esto es usado para el registro de expdtes por parte de Entidades privadas. */
$depeid_trabajo = 928;

/* Indicador para trabajar con tabla Tupa para expedientes externos */
$SG_Tupa = 0; // 1 -> Al seleccionar Expdte Externo pide el TUPA
              // 0 -> Ingreso de Expedte externo normal.

/* Indicador para que muestre el check de adjuntar documento, cuando se registra un doc. como parte de un exppediente */
$muestra_adjuntar = 0;

/* Indicador para que permita al supervisor crear usuarios  */
$supervisorCreaUsuarios = 0;

/* Para el uso de las librer�as del Siga */
define("DB_HOST",$dbhost);
define("DB_USER", $dbUsuario);
define("DB_PASSWORD",$dbpassword);
define("DB_DATABASE",$dbName);
define("DB_PERSISTENT",false);
define("IP_SMTP",$ipSmtp);

define(_CARPETAAPP_,'sisgedonew'); /* Nombre de la carpeta de la aplicaci�n  */
define(SIS_EMAIL_GMAIL,'abc123@gmail.com'); /* Nombre de la carpeta de la aplicaci�n  */
define(SIS_PASS_EMAIL_GMAIL,'abc123++'); /* Nombre de la carpeta de la aplicaci�n  */
define("SIS_URL_SISTEMA",'http://sytra.munitayacaja.gob.pe/');
define("SIS_EMPRESA","Municipalidad Provincial de Tayacaja");


/* Para los que usan Dependencia en lugar de Local */
define(_LOCAL_,'Local');
define(_LOCALES_,'Locales');

?>
