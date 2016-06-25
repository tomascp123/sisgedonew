<?
// Make sure people don't try and access it directly
include('checksession.php');

$query="select * from ".$_table." where ".$_campoclave.'='.$_mydato;
$rsusers=$db->sql_query($query);	
$row     = $db->sql_fetchrow($rsusers);

$query  = "select tabl_codigo,tabl_descripcion from tabla where tabl_tipo='IDPERM' order by tabl_codigo";
$rsestado=$db->sql_query($query);	
if(!$rsestado) {die($db->sql_error().' Error en consulta de estados '); }

?>
<table width="100%"  height="80%"  border="0" cellpadding="0" cellspacing="10"  class="backform" >
<tr><td valign="top" >
<table class="frmline" width="750" align="center"  border="0" cellpadding="0" cellspacing="0">
<form name="formregistro" method="post" action="<? echo $_url?>?_tipoedicion=<? echo $_tipoedicion?>&_op=<? echo $_op?>&_type=<? echo 'G&_nametype=usuariopermisosgrabar.php' ?>&_tabactivo=<? echo $_tabactivo ?>&_where=<? echo $_where ?>" >
	<tr>
		<td colspan="7" >
			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%" colspan="3">
				<? 
					if($row['id_usu']!=1) //si no es supervisor	
						topform($_btncaption,$_tipoedicion,$_titulo,1) ;
					else
						topform($_btncaption,3,$_titulo,0) 
				?>
				</td>
			</tr>
			</table>

	<?	
	seccion("DATOS DE LA CUENTA",3,3);
    labelcajanum(3,3, "Código de Usuario", "zsxid_usu",$row,0,5,0);	
	labelcajatxt(3,3, "Usuario","Srxusua_login",$row,20); 									
	labelcajatxt(3,3, "Nombres","Srxusua_nombres",$row,60); 
	labelcajatxt(3,3, "Apellidos","Srxusua_apellidos",$row,60); 	
	seccion('',3,3);	
	if($row['id_usu']!=1){ //si no es el Administrador
		seccion("MENU PRINCIPAL/SUB MENUS Y PERMISOS",3,3);
		$i=1;
		$rsquery=$db->sql_query("select * from menu where groupmenu>0 order by groupmenu asc") ;	
		while ($rowm= $db->sql_fetchrow($rsquery)) 
		{
//			if($i!=5){
				seccion2($rowm['name'],1,$_tipoedicion);
				$rsquerymc=$db->sql_query("select * from menu_categoria where groupmenu=".$rowm['groupmenu']." order by op") ;	
				while ($rowmc= $db->sql_fetchrow($rsquerymc)) 	{
					if(empty($rowmc['nivel'])  or  $row["usua_tipo"] == $rowmc['nivel'] or $row["usua_tipo"]>$rowmc['nivel'] ){				
						//$tipopermiso=saca_valor("select * from usuario_permisos where id_usu=".$row['id_usu'].' and op='.$rowmc['op'],'tipopermiso');
						$tipopermiso=saca_valor("select * from usuario_permisos where id_usu=".$row['id_usu']." and op='".$rowmc['op']."'",'tipopermiso');						
						labelcombo(1,$_tipoedicion,$rowmc['module'],'t'.$rowmc['op'].'_op'.iif($tipopermiso,'!=','','='.$tipopermiso,''),$row,$rsestado,0);				
						}					
					}
				$db->sql_freeresult($rsquerymc);	
//			}
			$i++;		
		}
	}//fin de Administrador
	?>

	<tr>
		<td colspan="7" height="30">
			<? 	
			if($row['id_usu']!=1) //si no es supervisor	
				bottform($_btncaption,$_tipoedicion,1) ;	 
			else
				bottform($_btncaption,3,0) ;	 
			?>				
		</td>
	</tr>
</td>
</tr>
<?
$html="<"."script".">\n";
$html.="setfocus('formregistro'".iif($_POST['_setfocus'],'!=','',",'".$_POST['_setfocus']."'","").")\n";
$html.="<"."/script".">\n";
echo $html;
?>

</form>
</table>
</td></tr>
</table>
<? 
$db->sql_close();
?>

