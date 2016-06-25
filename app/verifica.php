<?
session_name("SISGEDO");
session_start();
if($_POST[Srnick] == '' or $_POST[srpass] == '' or $_POST[sr_codesecurity] == '') 
{
header("Location: main.php?error=1&_op=1I&_type=L&_nameop=Login de Acceso"); //enviamos al form de registro que esta en reg.php
}
else if(strcmp($_POST[sr_codesecurity],encripcodex($_POST[_secucodesecurity]))!=0) 
	{
		header("Location: main.php?error=5&_op=1I&_type=L&_nameop=Login de Acceso"); 
	}
	else{
		require_once('config.php') ; 
		$query="SELECT * FROM usuario WHERE usua_login =upper('$_POST[Srnick]') and usua_password ='".md5($_POST[srpass])."'";
		$rsquery=$db->sql_query($query);	
		if(!$rsquery) {die(print_r($db->sql_error()).' ERROR EN CONSULTA DE INGRESO '); }
		if($user_ok = $db->sql_fetchrow($rsquery)) //si existe comenzamos con la sesion, si no, al index
		{
			if (($user_ok["usua_estado"])=="1"){ // Si cuenta está activa
				$Fecha_Vige=dateFormat($user_ok["usua_vigencia"],"Y-m-d","d/m/Y");
				$difFechas = compara_fechas($Fecha_Vige,date("d/m/Y"));
				if ($difFechas >= 0 or $user_ok["id_usu"]==1){ // Si cuenta está vigente o es el ADMIN
					// Cargo en memoria mis funciones PHP
					require_once('../mislibs/libphpgen_extend.php') ;					
					
					//damos valores a las variables de la sesión
					$_SESSION["nickusu"] = $user_ok["usua_login"]; 
					$_SESSION["usuinicial"] = $user_ok["usua_iniciales"]; 
					$_SESSION["nomusu"] = trim($user_ok["usua_nombres"]); 
					$_SESSION["apeusu"] = trim($user_ok["usua_apellidos"]); 
					$_SESSION["cargo"] = trim($user_ok["usua_cargo"]);
					$_SESSION["id"] = $user_ok["id_usu"];
					$_SESSION["tipo_user"] = $user_ok["usua_tipo"];
					$_SESSION["depe_id"] = $user_ok["depe_id"];
					$_SESSION["usua_caseta"] = $user_ok["usua_caseta"]; 
					$_SESSION["depe_depende"]=saca_valor("select * from dependencia where depe_id=$_SESSION[depe_id]",'depe_depende');
					if($_SESSION["depe_depende"])
						$_SESSION["entidad"] = saca_valor("select * from depenti_v where depe_id=$_SESSION[depe_depende]",'depe_nombre');
					
					$rsquery=$db->sql_query("UPDATE usuario SET usua_hora_ingreso = now(),usua_hora_actual = now() WHERE id_usu = $_SESSION[id]") ;	
					$db->sql_freeresult($rsquery);

//					$cSql="select * from depint_v where depe_id=$_SESSION[depe_id]";
					$cSql="select * from dependencia where depe_id=$_SESSION[depe_id]"; // Debe usarse esta línea por los sectores que aún no trabajan con el Sisgedo de manera interna
					$rsquery=$db->sql_query($cSql) ;	
					if(!$rsquery) {die($db->sql_error().'Error en consulta '); }
					$row     = $db->sql_fetchrow($rsquery);
					$_SESSION["depe_nombre"] = $row['depe_nombre'];
					$_SESSION["depe_tipo"] = $row['depe_tipo'];
					$_SESSION["depe_siglasexp"] = $row['depe_siglasexp'];
					$_SESSION["depe_proyectado"] = $row['depe_proyectado'];
					$_SESSION["depe_representante"] = $row['depe_representante'];
					$_SESSION["depe_cargo"] = $row['depe_cargo'];
					$_SESSION["depe_maxenproceso"] = $row['depe_maxenproceso'];	// Obtengo el número máximo de expdtes en proceso que puede tener la dependencia
					$_SESSION["depe_diasmaxenproceso"] = $row['depe_diasmaxenproceso'];	// Obtengo el número de días como máximo que puede estar un expdte en proceso
					
					// Para saber tipo de bloqueo a nivel de dependencia y si el Administrador le ha dejado algún mensaje
					$cSql="select * from bloqueo where depe_id=$_SESSION[depe_id] and id_usu is null";
					$rsquery=$db->sql_query($cSql) ;	
					if(!$rsquery) {die($db->sql_error().' Error en consulta de bloqueo '); }
					$row     = $db->sql_fetchrow($rsquery);
					if($row){
						$_SESSION["bloq_bloqueo"] = $row['bloq_bloqueo'];
						$_SESSION["bloq_mensaje"] = $row['bloq_mensaje'];
					}
					$db->sql_freeresult($rsquery);

					// Para saber tipo de bloqueo el usuario y si el Administrador le ha dejado algún mensaje
					$cSql="select * from bloqueo where id_usu=$_SESSION[id]";
					$rsquery=$db->sql_query($cSql) ;	
					if(!$rsquery) {die($db->sql_error().' Error en consulta de tipo de bloqueo '); }
					$row     = $db->sql_fetchrow($rsquery);
					if($row){
						$_SESSION["bloq_bloqueo"] = $row['bloq_bloqueo'];
						$_SESSION["bloq_mensaje"] = $row['bloq_mensaje'];
					}
					$db->sql_freeresult($rsquery);

					if($_SESSION["bloq_bloqueo"]!=3){ // Si es un usuario o Dependencia que puede bloquearlo el sistema por expedientes por recibir o demasiados expdtes en proceso
						// Obtengo la configuración de días por recibir como máximo que debe tener un expdte.
						$cSql="select tabl_numero from tabla where tabl_tipo='IDCONF' and tabl_codigo='2'";
						$rsquery=$db->sql_query($cSql) ;	
						if(!$rsquery) {die($db->sql_error().' Error al obtener configuración '); }
						$row     = $db->sql_fetchrow($rsquery);
						if($row){
							$_SESSION["dias_xrecibir"] = $row['tabl_numero'];
						}
						$db->sql_freeresult($rsquery);

						// Obtengo la cantidad de Expdtes por recibir que tiene la Dependencia
						$_SESSION["Arr_ExpXrecibir"] = MyExpXrecibir($_SESSION[depe_id],$_SESSION["dias_xrecibir"]);
                                                $_SESSION["ExpXrecibir"] = count($_SESSION["Arr_ExpXrecibir"]);
                                                
						// Obtengo la cantidad de Expdtes en proceso que tiene la Dependencia
						$_SESSION["ExpEnProceso"] = MyExpEnProceso($_SESSION[depe_id]);
						
						// Obtengo la cantidad de Expdtes Derivados por la dependencia y que están a la espera de ser recibidos
						$_SESSION["Arr_ExpDerivadosEnEspera"] = MyExpDerivadosEnEspera($_SESSION[depe_id],$_SESSION["dias_xrecibir"]);
                                                $_SESSION["ExpDerivadosEnEspera"] = count($_SESSION["Arr_ExpDerivadosEnEspera"]);
						
						// Obtengo la cantidad de expedientes en proceso con muchos días sin atender.
						IF($_SESSION[depe_diasmaxenproceso]) /* Si la Dependencia tiene configurado este dato*/
							$_SESSION["ExpEnProcesoDias"]=MyExpEnProcesoDias($_SESSION[depe_id],$_SESSION[depe_diasmaxenproceso]);						
						
					}

					// Cierro Conexion
					$db->sql_close();

					header("Location: main.php"); //ingresamos a la intranet donde nos saldrá nuestro menú de usuario
				}else{
					header("Location: main.php?error=6&_op=1I&_type=L&_nameop=Login de Acceso"); //enviamos al form de registro que esta en reg.php			
				}
			}
			else{
				header("Location: main.php?error=4&_op=1I&_type=L&_nameop=Login de Acceso"); //enviamos al form de registro que esta en reg.php			
			}
		}else{
			header("Location: main.php?error=2&_op=1I&_type=L&_nameop=Login de Acceso"); //enviamos al form de registro que esta en reg.php				
		}
} 

function encripcodex($random_num){
	$sitekey = "SdFk*fa28367-dm56w69.3a2fDS+e9";
    $datekey = date("F j");
    $rcode = hexdec(md5($_SERVER[HTTP_USER_AGENT] . $sitekey . $random_num . $datekey));
    $code = substr($rcode, 2, 6);
	return($code);
}

?>