<?
//funcion que se invoca desde gridbuild
function myFunctionGrid($_op,$valor)
{
$linephp='';
switch($_op)
	  {
      default:
 		break;
	}
return($linephp);
}


function MyExpXrecibir($depe_id,$dias)
{
	global $db;
	/* Si los que han derivado son agentes y no Direcciones Sectoriales (es decir son solo colegios), la cantidad de días que se espere que llegue el doc. físico es 15 días */
	$_stringsql="select a.expe_id
				from operacion as a
				left join dependencia b on b.depe_id=a.depe_id
				left join expediente c on c.expe_id=a.expe_id
				where c.expe_estado=1 and
					  a.oper_idtope = 2 and
					  a.oper_depeid_d = $depe_id  and
					  a.oper_procesado=FALSE and
					  case
					  when b.depe_agente=1 and a.depe_id not in (418,420,426,431,432,433,434,435,436,437,438,441) then
						   current_date - a.oper_fecha > 15
					  else
						   current_date - a.oper_fecha > $dias
					  end";
	$rsquery = $db->sql_query($_stringsql);
	if(!$rsquery) {die($db->sql_error().' ERROR EN CONSULTA DE EXPEDIENTES POR RECIBIR '); }
	$rowx_reci = $db->sql_fetchrowset($rsquery);
	return($rowx_reci);
}


function MyExpEnProceso($depe_id)
{
	global $db;
	$_stringsql="SELECT func_cuentaexpdtes($depe_id,0,0) AS total";
	$rsquery=$db->sql_query($_stringsql);	
	if(!$rsquery) {die($db->sql_error().' ERROR EN CONSULTA DE EXPEDIENTES EN PROCESO '); }
	$rowx_reci = $db->sql_fetchrow($rsquery);
	$ExpXrecibir = $rowx_reci["total"];
	$db->sql_freeresult($rsquery);
	return($ExpXrecibir);
}

function MyExpEnProcesoDias($depe_id,$DiasMax)
{
	global $db;
	if(!$DiasMax)
		return 0;	
	
	$_stringsql="SELECT func_cuentaexpdtes($depe_id,0,$DiasMax) AS total";
	$rsquery=$db->sql_query($_stringsql);	
	if(!$rsquery) {die($db->sql_error().' ERROR EN CONSULTA DE EXPEDIENTES CON TIEMPO MAXIMO EN PROCESO '); }
	$rowx_reci = $db->sql_fetchrow($rsquery);
	$ExpXrecibir = $rowx_reci["total"];
	$db->sql_freeresult($rsquery);
	return($ExpXrecibir);
}

function MyExpDerivadosEnEspera($depe_id,$dias)
{
	global $db;
	$_stringsql="select a.expe_id
				from operacion a
				where (a.oper_idtope = 2 and
					  a.depe_id = $depe_id  and
					  a.oper_procesado=FALSE)
					  and current_date - a.oper_fecha > $dias
				order by 1";
	$rsquery=$db->sql_query($_stringsql);	
	if(!$rsquery) {die($db->sql_error().' ERROR EN CONSULTA DE EXPEDIENTES DERIVADOS EN ESPERA '); }
	$rowx_reci = $db->sql_fetchrowset($rsquery);
        return($rowx_reci);
}

?>
