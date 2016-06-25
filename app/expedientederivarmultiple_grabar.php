<?
$_regADerivar=$_POST[_regADerivar];
//echo $_regADerivar.'<BR>';
$_ArrayregADerivar=explode(",",$_regADerivar);
//echo print_r($_ArrayregADerivar).'<BR>';
//exit;
/*for($i=0;$i<count($_ArrayregADerivar);$i++){
	$_cadena=$_ArrayregADerivar[$i];
	$_arrayauxi=explode(";",$_cadena);	
	$expe_id=$_arrayauxi[0];
	$_oper_idprocesado=$_arrayauxi[1];
	
	echo $_oper_idprocesado.'  -  '.$expe_id.'<BR>';
}
exit;
*/

foreach($_POST as $variable=>$valor){ 
	if (substr($variable,2,2)=='XX') {//OBTENGO LOS NOMBRE DE LOS CAMPOS PARA EXPLOTAR LOS ARRAYS
		$obj_a_explotar[]=substr($variable,2);
		$obj_a_explotar_typedato[]=substr($variable,0,1);	
		$obj_grupo[]=substr($variable,2,5);
	}
}	

//obtengo los valores unicos
$obj_grupo=array_unique ($obj_grupo);
		
//recorro el array con los grupos 
if(is_array($obj_grupo)){
	for($i=0;$i<count($_ArrayregADerivar);$i++){
		/* Del Array obtengo el Expe_id y oper_idprocesado que grabaré en el nuevo registro */
		$_cadena = $_ArrayregADerivar[$i];
		$_arrayauxi = explode(";",$_cadena);	
		$expe_id = $_arrayauxi[0];
		$_oper_forma = $_arrayauxi[2];		
		$_oper_idprocesado = $_arrayauxi[1];
		$_oper_idtope = $_arrayauxi[3];

		if($_oper_idtope==1) // Si estoy derivando un expediente que solo està registrado
			$_oper_idprocesado=$_arrayauxi[1]; // doy por procesado el id del expediente.
		else // Si estoy derivando sobre un expediente ya derivado
			$_oper_idprocesado=$_arrayauxi[4]; // Doy por procesado el idprocesado del registro que estoy proceando, es decir siempre guardaria en idprocesado el id del expediente original
		
		if($_oper_idprocesado==0) // Si estoy derivando una copia a partir de otra copia
			$_oper_idprocesado='NULL';

		/*  */
		
		foreach($obj_grupo as $grupo){ 
				//busco los campos a explotar segun grupo
				$fieldgroup='';
				foreach($obj_a_explotar as $explota){
					if (substr($explota,0,5)==$grupo){
						//proceso de explotacion 
						$array=$explota;
						$$array=explode('®',$_POST[$explota]);
						if(is_array($$array)){
							$elementos=count($$array)-1;
						}

						$tablegroup=substr($explota,5,strpos($explota,"ZZ")-5) ;
						$fieldgroup=$fieldgroup.iif($fieldgroup,'!=','',',','').substr($explota,strpos($explota,"ZZ")+2);
					}//if
				}//foreach
					
					if($_tipoedicion==2){
						// SI ES EDICION SE DECIDE POR BORRAR
						// $_campoclave viene de modulo.php
						// $variableid viene de php_grabar.php
						$sqlstring="delete from ".$tablegroup." where ".$_campoclave.'='.$_POST[$variableid];								
						$query=$db->sql_query($sqlstring);	
						if(!$query) {die($db->sql_error().' Error al eliminar registro ');}
						}

					if($elementos>0){
						for ($x=0;$x<$elementos;$x++) {
							$campos="insert into ".$tablegroup." (".$fieldgroup;
							$valores='';
							foreach($obj_a_explotar as $explota){
								$typeDato=each($obj_a_explotar_typedato);										
								if (substr($explota,0,5)==$grupo){
										$array=$explota;
										if(is_array($$array)){
											$valor=each($$array);
											switch($typeDato[1]){
												case 'N':
														$valores=$valores.iif($valores,'!=','',',','').$valor[1];
														break;
												case 'D':
														$valores=$valores.iif($valores,'!=','',',','')."'".DateAMD($valor[1])."'";
														break;
												case 'P':
														$valores=$valores.iif($valores,'!=','',',','')."'".md5($valor[1])."'";
														break;
												default :
														if($explota == 'XXT01operacionZZoper_idprocesado'){
															$valores=$valores.iif($valores,'!=','',',','')."'".$_oper_idprocesado."'";
														} else if ($explota == 'XXT01operacionZZoper_forma' and $_oper_forma == 1) { /* Si estoy derivando una copia, me aseguro de grabar la derivación como copia, aún haya desactivado el check de copia o estén derivando varios registros a la vez */
															$valores=$valores.iif($valores,'!=','',',','')."'".$_oper_forma."'";														
														} else {
															$valores=$valores.iif($valores,'!=','',',','')."'".strtoupper($valor[1])."'";
														}
														break;
												}
											}
									}//if
								}//foreach
								$sql.=$campos.')';
								
								if($_tipoedicion==2){
										$idvalor=$_POST[$variableid];
									}
								else{
//										$idvalor=$idinsert;
										$idvalor=$expe_id;
									}
									
								$sqlstring=$campos.','.$_campoclave.') values ('.$valores.','.$idvalor.')';
	
								// ** //
								// Si voy a ejecutar una función para insertar registro en Postgres, cambio el $sqlstring
								if($myfpgins_extend){
									// Busco y reemplazo los datos NULL
									$sqlstring=ereg_replace( "'NULL'","NULL", $sqlstring );	
									
									$sqlstring=stristr($sqlstring,"values (");
									$sqlstring=ereg_replace( "values", $myfpgins_extend, $sqlstring );
								}		
								// ** //
								$query=$db->sql_query($sqlstring);
//								if(!$query) {die($db->sql_error().' ERROR EN INGRESO DE LAS DERIVACIONES '); }
								if(!$query) {$_MensError = muestra_error($db->sql_error()); }		
								
							}//for
						}
			}//foreach grupo
	}//foreach grupo
}//if

?>