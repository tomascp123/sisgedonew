<?
//DATOS DE LA BASE DE DATOS
require_once('conexion.php') ;

//PATH de las librerias
$pathlib="../librerias/";

require_once($pathlib.'libphpgen.php');
require_once($pathlib.'clases/db.php');	

// Grabo el registro como expediente

$entidad=strtoupper($_POST[tr_entidad]);
$firma=strtoupper($_POST[Sr_nombre]);
$domicilio=strtoupper($_POST[Sr_domicilio]);
$dni=strtoupper($_POST[Sr_dni]);
$telefono=strtoupper($_POST[Sr_telefono]);
$asunto=strtoupper($_POST[Sr_mensaje]);
$emailorigen=strtoupper($_POST[cr_email]);

/*
$depe_id=26; // C�digo de la dependencia donde debe aparecer EN PROCESO
$id_usu=257; // C�digo del usuario en el cual aparecer� como EN PROCESO
$texp_id=30; // Solicitud Electr�nica
*/
$id_usu=saca_valor("select * from depenti_v where depe_id=$entidad",'id_usu_transp');  // Obtengo el responsable de Transparencia de la Sede Regional
$depe_id=saca_valor("select * from usuario where id_usu=$id_usu",'depe_id');

$texp_id=111; // Reclamo
$frec_id=3;  // Via Web

$cSql="insert into
expediente(expe_origen,depe_id,expe_firma,expe_cargo,texp_id,frec_id,expe_asunto,id_usu,idusu_depe,expe_emailorigen,expe_domic,expe_dni,expe_telef) 
values(1,$depe_id,'$firma','VISITANTE',$texp_id,$frec_id,'$asunto',$id_usu,$depe_id,'$emailorigen','$domicilio','$dni','$telefono')";
$rsquery=$db->sql_query($cSql) ;	
if(!$rsquery) {
echo $cSql;	
	die($db->sql_error().' Error al Insertar El Reclamo.....'); 
}
$db->sql_freeresult($rsquery);

// Obtengo el expe_id
$_table='expediente';
$_campoclave='expe_id';
$idinsert=$db->sql_nextid();

// Envio mail
//$address='transparencia@regionlambayeque.gob.pe';
$address=saca_valor("select * from usuario where id_usu=$id_usu",'usua_email');

//$address='lguevara@regionlambayeque.gob.pe';
$from=$_POST['cr_email'];
$subject='Registro en Libro de Reclamaciones N� de expdte '.$idinsert;
$mensaje=$_POST['Sr_mensaje'];
$mensaje.='<BR>';
$mensaje.='<BR>';
$mensaje.=$_POST['Sr_nombre'];
$mensaje.=$_POST['Sr_domicilio'];
$mensaje.=$_POST['Sr_dni'];
$mensaje.=$_POST['Sr_telefono'];
$AddImage="sin imagen";
define(_VERSION_,'SisGeDo - '.$_POST['Sr_nombre']);
				
include("email_sendmail.php");

if(!$ok_send) {
   echo "El mensaje de correo no pudo ser enviado....<br>";
   echo "Mailer Error: " . $mail->ErrorInfo;
}

header("Location: envia_reclamo_confirma.php?idinsert=$idinsert"); 

?>
