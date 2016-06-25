<?
session_name("SISGEDO");
session_start(); 
$pathlib=$_GET['_pathlib'];

echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"../mislibs/estilos.css\" />\n"; 
echo "<script type=\"text/javascript\" src=\"".$pathlib."libjsgen.js\"> </script>\n"; 
echo "<script type=\"text/javascript\" src=\"../mislibs/jquerypack.js\"></script>";

?>
<script language="JavaScript">
	$(document).ready(function(e) {
		$("table .griddatos tr td:not(.gridhead)").not(":has(:checkbox)").click(function () {
				$(this).parent().find('input[@type=checkbox]').click();
		});
	});
</script>
<?
require_once('config.php') ;
$_pagina=$_GET["pagina"]; //Le indicamos la página en que estamos.  1 por defecto
$_orden=$_GET["orden"]; //Le indicamos la página en que estamos.  1 por defecto
$_stringsql=str_replace("\'", "'",$_GET['_stringsql']);
$_where=str_replace("\'", "'",$_GET['_where']);
$_where2=str_replace("\'", "'",$_GET['_where2']);
//$_where=str_replace("\'","'",str_replace("®","%",stripslashes($_GET['_where']))); 
$_url=$_GET["_url"];
$_op=$_GET['_op'];
$_npop=$_GET['_npop'];
$gridrowcolor=$_GET['_gridrowcolor'];
$gridcolconfig=$_GET['_gridcolconfig'];
$fullregisgrid=$_GET['_fullregisgrid'];
$_stringsqlwhere=str_replace("\'", "'",$_GET['_stringsqlwhere']);
$_stringsqlorder=str_replace("\'", "'",$_GET['_stringsqlorder']);
$_classgrid=$_GET['_classgrid'];
$gridrowcolor=array_recibe($gridrowcolor); 
$gridcolconfig=array_recibe($gridcolconfig); 

//// cadenas de Consultas según _op
if($_op==31){

		$_stringsql="select lpad(a.expe_id::TEXT,8,'0') as Registro,lpad(b.exma_id::TEXT,8,'0') as Expediente,to_char(a.oper_fecha,'dd-mm-yyyy') as Fec_Registro,CASE WHEN a.oper_forma=0 THEN 'ORIGINAL' WHEN a.oper_forma=1 THEN 'COPIA' END as Forma,";
		$_stringsql.="d.texp_abreviado as tipo,lpad(b.expe_numero_doc::TEXT,6,'0') as Numero,b.expe_siglas_doc || case when b.expe_proyectado!='' then '-' || b.expe_proyectado when b.expe_proyectado='' then b.expe_proyectado end as Siglas,to_char(b.expe_fecha_doc,'dd-mm-yyyy') as Fecha,c.depe_nombre as Dependencia,";
		$_stringsql.="b.expe_depe_detalle as Detalle,b.expe_firma as Firma,b.expe_cargo as Cargo,b.expe_asunto as Asunto,";
		$_stringsql.="CASE WHEN a.oper_idtope=1 THEN 'REGISTRADO' WHEN a.oper_idtope=2 THEN 'DERIVADO' END as Estado,f.depe_nombre as Destino,g.usua_login as Usuario_destino,b.ar_expearchivo as archivo,";
		$_stringsql.="a.expe_id || ';' || a.oper_id || ';' || a.oper_forma || ';' || a.oper_idtope || ';' || COALESCE(a.oper_idprocesado, 0) as _mydato ";
		$_stringsql.="from operacion a ";
		$_stringsql.="left join expediente b on a.expe_id=b.expe_id ";
		$_stringsql.="left join dependencia c on b.depe_id=c.depe_id ";
		$_stringsql.="left join tipo_expediente d on b.texp_id=d.texp_id ";
		$_stringsql.="left join usuario e on a.id_usu=e.id_usu ";
		$_stringsql.="left join dependencia f on a.oper_depeid_d=f.depe_id ";
		$_stringsql.="left join usuario g on a.oper_usuaid_d=g.id_usu ";			

/*		$_stringsqlwhere="where (a.oper_idtope=1 or a.oper_idtope=2) and a.depe_id=".$_SESSION[depe_id]." and a.oper_id not in ";
		$_stringsqlwhere.="(select oper_idprocesado from operacion where oper_idprocesado is not null)";
		ya viene desde PHP_GRABAR.PHP
	*/	
		
		$_where=$_where.$_where2;
		$_stringsqlwhere = $_stringsqlwhere.str_replace("®","%",$_where);


		$_stringsqlorder="order by 2";						

		//array de colores para las filas del gridpaginado
		//  Existe un problema si se activa estas lìneas, parece que por volverse muy grande la cadena que pasa por la url desde gridbuild.php a gridsimple.php
		$gridrowcolor = array (
			   "0"  => array("campo"=>"Estado", "dato"=>"REGISTRADO", "color"=>"#FFFFFF","cuenta"=>0),
			   "1"  => array("campo"=>"Estado", "dato"=>"DERIVADO", "color"=>"#E2FCF2","cuenta"=>0),
				);
		
		$gridcolconfig = array (
			   "0"  => array("campo"=>"archivo",    "obj"=>"<a href=\"MyValue\" ><img src=\"../imagenes/download.gif\" width=\"35\" height=\"35\" border=\"0\" alt=\"Bajar archivo\"></a>", "width"=>"",    "color"=>""),
						);
		
}else{
	if($_where){
	// Si dentro del $stringsql no està asignado el WHERE
		if ($_stringsqlwhere)
			{$_stringsqlwhere = $_stringsqlwhere." and ".str_replace("®","%",$_where);}
		else{
			$_stringsqlwhere= "where ".str_replace("®","%",$_where);
		}
	}
}

// Si dentro del $stringsql no està asignado el ORDER BY
if (!$_stringsqlorder)
	{ $_stringsqlorder= "order by 1";}

$nRegxPag=26; // Número de registros por página que queremos mostrar.  20 por defecto

$grid = new paginado($_pagina,$_orden,$nRegxPag,$_stringsql." ".$_stringsqlwhere." ".$_stringsqlorder); 

$pageedit=$_GET['_pageedit'];//pagina para editar los datos
$pagereturn=$_SERVER['PHP_SELF']; //pagina q linkeara al presionar 'volver' en la $pageedit
$datocampoclave=$_POST["_mydato"];
$tipoedicion=$_POST['_tipoedicion'];

?>
<table width="100%"  height="100%" align="center" border="0" cellpadding="0" cellspacing="0" >
<form name=frmgrid method="post" >
				<tr height="100%">
					<td colspan="7" valign="top"> 
					<? 
					$grid->table_create($grid->rs());
					?>
					</td>
				</tr>
</form>
</table>
<? 
$db->sql_close();
?>

