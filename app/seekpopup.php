<? 
session_name("SISGEDO");
session_start();
include('config.php');

$_url=$_SERVER['PHP_SELF'];
$_tipoedicion=5;
$_pagina=$_GET['pagina']; //Le indicamos la página en que estamos.  1 por defecto
$_orden=$_GET['orden']; //Le indicamos la página en que estamos.  1 por defecto
$_npop=$_GET["_npop"];
$_tipoedicion=$_GET['_tipoedicion'];
$_nametype=$_GET['_nametype'];
//$_nametype=!$_tipoedicion?'institucionregistro.php':$_nametype;
$_type=$_GET['_type'];
//$_type=$_type?$_type:'M';

$_where=str_replace("\'","'",$_GET['_where']); 
$_flag=$_GET["_flag"]; //su valor cambia en el php_grabar solo cuando es busqueda
$err_objeto=$_GET['err_objeto'];
$err_mesaje=$_GET['err_mesaje'];
$_mydato=$_GET['_mydato']; // es enviado desde el grid
$_update=$_GET['_update'];
$_idinsert=$_GET['_idinsert'];

// La variable $_SESSION["_altertable"] va a indicar si se ha hecho alguna modificacion a la tabla del popup
if($_GET['_altertable']){
	$_SESSION["_altertable"] =$_GET['_altertable']; 
}

if($_GET['_frm']){
	$_SESSION["_frm"] =$_GET['_frm']; 
}




$_sbpop=2;
$_typetemp=$_type;
$_optemp=$_op;
$_op=substr($_npop,1);

if($_type!='G') {
ini_mod(_VERSION_,_SISTEMA_);
?>
<script>
document.onkeypress = function hidecal1 () { 
var isNS4 = (navigator.appName=="Netscape")?1:0;
	if (isNS4) {
	    iKeyCode = event.which;	
		} 
	else {		
	    iKeyCode = event.keyCode;
		}

	if (iKeyCode==27) 
		{
			window.close()
		}
	}
</script>

<?
}


include('modulo.php');

$_type=$_typetemp;
$_op=$_optemp;

if($_modfile=='gridbuild.php'){
?>
<html>
	<body style="MARGIN:0px"  onUnload="popupregresa(0,'<? echo $_SESSION["_frm"] ?>','<? echo $_npop ?>','<? echo $_SESSION['_altertable']?>')" >
			<table height="100%" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffcc" >
				<tr height="15" width="100%" align="center">
					<td height="19">BUSQUEDA DE DATOS</td>
				</tr>
				<tr>
					<td colspan="2" valign="top">
				        <? 
				
						if($_type=='P') if (!$_update) 	include("modulo_permisos.php"); 
						else include("$_modfile");
					?>
				    </td>
				</tr>
				<tr>
					<td colspan="2" align="center" height="28">
						<button onClick="popupregresa(1,'<? echo $_SESSION["_frm"] ?>','<? echo $_npop ?>','<? echo $_SESSION["_altertable"] ?>')" id="btnOk" type="button" >Aceptar</button> &nbsp;&nbsp;&nbsp;&nbsp;
						<button onClick="popupregresa(2,'<? echo $_SESSION["_frm"] ?>','<? echo $_npop ?>','<? echo $_SESSION["_altertable"] ?>')" id="btnCancel" type="button" >Cancelar</button>
					</td>
				</tr>
			</table>

	</body>
	
</HTML>
<?  }

	$html = "<"."script".">\n";
	if($_setfocus) 
		$html .= "setfocus(".$_nameform.",'".$_setfocus."')\n";
	else 
		$html .= "setfocus(".$_nameform.")\n";	
		
	$html .= "</"."script".">\n";
	echo $html;

?>
