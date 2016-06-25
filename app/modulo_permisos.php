<?
	if($_SESSION['id'] == 1 or ($_SESSION["tipo_user"]=='5' and ($_op==52 or $_op==53 or $_op==54))) {$_permiso='T'; }// Si es el ADMINISTRADOR WEB o Un Supervisor y está trabajando en el mòdulo UNIDADES ORGANICAS o USUARIOS o CORRELATIVOS
	else  if($_SESSION['id'] == '') 	{
				switch($_tipoedicion) {
				//adicion
					case 1: 
						$_permiso='R'; // SOLO LECTURA POR DEFECTO
						$_btnconfirma="";
						$mensajeusuario='LO SENTIMOS, USTED NO ESTA AUTORIZADO PARA INGRESAR A ESTA OPCION...!!';
						$_modfile="confirma.php";
						break;
					}
			}
		else  {
				$_permiso=saca_valor("select * from usuario_permisos where id_usu=".$_SESSION['id']." and op='".$_op."'",'permiso');
				$_permiso=$_permiso?$_permiso:'R';
				switch($_tipoedicion) {
				//adicion
					case 1: 
						if(!ereg("[TW]",$_permiso))
							{
							$_btnconfirma="";
							$mensajeusuario='LO SENTIMOS, USTED NO TIENE PERMISO PARA ADICIONAR REGISTROS...!!';						
							$_modfile="confirma.php";							
							}
						break;
				//edicion
					case 2: 
						if($_mydato){
							$query="select * from ".$_table." where ".$_campoclave.'='.$_mydato;					
							$rsquery=$db->sql_query($query);	
							if(!$rsquery) {die($db->sql_error().' Error en consulta de permisos '); }
							$row     = $db->sql_fetchrow($rsquery);
							if($row['id_usu']!=$_SESSION['id'])
								{
								$_btnconfirma="Regresar";
								$mensajeusuario='LO SENTIMOS, USTED NO ESTA AUTORIZADO PARA MODIFICAR ESTE REGISTRO...!!';
								$_modfile="confirma.php";							
								}
							$db->sql_freeresult($rsquery);								
						}
						break;
						
				//eliminacion
					case 4: 
						if($_mydato){
							// Exploto mydato para formar un nuevo mydato que contenga solo los registros de los cuales es dueño el usuario activo $_SESSION['id']
							$_array=explode(",",$_mydato);
							$_mydato='';
							for($i=0;$i<count($_array);$i++) {
								$_idelimina=$_campoclave.'='.$_array[$i];				  
								$query="select id_usu from ".$_table." where ".$_campoclave.'='.$_array[$i];
								$rsquery=$db->sql_query($query);	
								if(!$rsquery) {die($db->sql_error().' Error al eliminar permisos '); }
								$row     = $db->sql_fetchrow($rsquery);
								if($row['id_usu']==$_SESSION['id']){
									$_mydato.=$_array[$i].",";
								}
							}
							
							// Saco la última coma ','
							$_mydato=substr($_mydato,0,strlen($_mydato)-1);

							// Si el usuario no es dueño de ningún registro
							if(strlen($_mydato)==0){
								$_modfile="";															
							 	header("Location: $_url?_op=$_op&_type=M&pagina=$_pagina&orden=$_orden&_where=$_where&_tabactivo=$_tabactivo&_tipoedicion=5&_flag=2");		
							}					
							$db->sql_freeresult($rsquery);								
						}
						break;
					}//end switch
		}//end else
if ($_modfile) include("$_modfile");
?>