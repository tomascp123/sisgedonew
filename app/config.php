<?
//DATOS DE LA BASE DE DATOS
require_once('conexion.php') ;

// Variable para indicar si se auditará las tablas desde el cliente
// Cada tabla deberá tener los campos: ultfecha,fecha,hora,id_usuario,nick
$audita=0;
define(_DESARROLLO_,0); /* Constante que indica si estoy en desarrollo, se mostrarán los errores  */

//PATH de las librerias
$pathlib="../librerias/";

// Para la función que se llama desde gridpaginado
require_once('../mislibs/libphpgen_extend.php') ;

require_once($pathlib.'libphpgen.php');

/* Seteo mi función que controlará los errores */
set_error_handler('my_error_handler');

require_once($pathlib.'clases/db.php');	
require_once($pathlib.'/clases/gridpaginado.php') ;

// constantes
define(_EMPRESA_,'GOBIERNO REGIONAL JUNIN');
define(_VERSION_,'SisGeDo | 2.0');
define(_SISTEMA_,'Sistema de Gestión Documentaria');
define(_WEBADMIN_,'webmaster@regionjunin.gob.pe');
define(_PUBLICUPLOAD_,'../../docs/');

// ARRAY CON EL NOMBRE LOS MODULOS PARA EL MENU PRINCIPAL 
if($_SESSION['id']) // Si se ha ingresado al sistema con un usuario y contraseña
	$rsquery=$db->sql_query("select * from menu where groupmenu>0 order by groupmenu asc") ;	
else // Visitante
	$rsquery=$db->sql_query("select * from menu where groupmenu=1 order by groupmenu asc") ;

$i=1;

while ($row= $db->sql_fetchrow($rsquery)) 
{
	if(!$_nameop) if($i==$_op) {$_nameop=$row['name'] ;}
		$submenu='';
		$opsubmenu='';
		$rsquerymc=$db->sql_query("select * from menu_categoria where groupmenu=".$row['groupmenu']." order by op") ;
		while ($rowmc= $db->sql_fetchrow($rsquerymc)) 
		{
			if(empty($rowmc['nivel'])  or  $_SESSION["tipo_user"] == $rowmc['nivel'] or $_SESSION["tipo_user"]>$rowmc['nivel'] or $_SESSION['id'] == 1){  // Si la opciòn no tiene nivel o tiene un nivel igual al usuario que ha ingresado o el nivel del usuario es mayor al nivel de la opciòn o es el Administrador
				$submenu=$submenu.iif($submenu,'!=','',';','').$rowmc['module'];
				$opsubmenu=$opsubmenu.iif($opsubmenu,'!=','',';','').$rowmc['op'];		
				// OBTENGO EL NOMBRE DE LA OPCION SELECCIONADA		
				if(!$_nameop){		
				  if($rowmc['op']==$_op) {$_nameop=$rowmc['module'] ;}
				}//END IF
		}//END WHILE
  	}//END IF
	$modulo[$row['name']]=$submenu;
	$opmodulo[$row['name']]=$opsubmenu;	
	$db->sql_freeresult($rsquerymc);	
	$i++;
}
$db->sql_freeresult($rsquery);

?>