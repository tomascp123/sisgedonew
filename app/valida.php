<?
// solo para edicion o nuevo
$ok=1;
if($_tipoedicion==1 or $_tipoedicion==2)
	switch($_op)
	  {
	  case '1C'://datos de mi cuenta
	  case '52'://suscripciones
			if(strcmp($_POST[nr_codesecurity],encripcode($_POST[_secucodesecurity]))!=0) 
			{
				$_type='L';
				$_nametype='';// es necesario inicializar esta variable para q el sistema regrese al formulario de edicion
				$err_objeto="nr_codesecurity"; 
				$err_mesaje="ERROR en ingreso de código de Seguridad";
				$ok=0;
			} 		
			break; 

	  }
	  
if (!$ok)	include('menutabs.php') ;	
else	include($pathlib.'php_grabar.php');

?>