<?
// Este archivo se ejecuta cuando registro un nuevo expediente y a la vez registro su derivación
// Obtengo el oper_id del nuevo registro ingresado
$_operid=saca_valor("select oper_id from operacion where expe_id=$idinsert",'oper_id');
$_idexpedi=saca_valor("select exma_id from expediente where expe_id=$idinsert",'exma_id');

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
	foreach($obj_grupo as $grupo){ 
			//busco los campos a explotar segun grupo
			$fieldgroup='';
			foreach($obj_a_explotar as $explota){
				if (substr($explota,0,5)==$grupo){
					//proceso de explotacion 
					$array=$explota;
					$$array=explode('®',$_POST[$explota]);
					if(is_array($$array))
						{
						$elementos=count($$array)-1;
						}
					$tablegroup=substr($explota,5,strpos($explota,"ZZ")-5) ;
					$fieldgroup=$fieldgroup.iif($fieldgroup,'!=','',',','').substr($explota,strpos($explota,"ZZ")+2);
					}//if
				}//foreach
														
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
													$valores=$valores.iif($valores,'!=','',',','')."'".strtoupper($valor[1])."'";
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
									$idvalor=$idinsert;
								}
								
							$sqlstring=$campos.','.$_campoclave.') values ('.$valores.','.$idvalor.')';

							// ** //
							// Si voy a ejecutar una función para insertar registro en Postgres, cambio el $sqlstring
							if($myfpgins_extend){
								// Buso y reeplazo los datos NULL
								$sqlstring=ereg_replace( "'NULL'","NULL", $sqlstring );	
								
								$sqlstring=stristr($sqlstring,"values (");
								$sqlstring=ereg_replace( "values", $myfpgins_extend, $sqlstring );
								$sqlstring=ereg_replace( "XXXXX", $_operid, $sqlstring );
							}		
							// ** //

							$query=$db->sql_query($sqlstring);	
							if(!$query) {die($db->sql_error().' Error al ingresar expediente '); }

						}//for
					}
			}//foreach grupo
	}//if

?>