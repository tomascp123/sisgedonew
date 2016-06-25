<?
switch($_tipoedicion)
  {
  case 1: // NUEVO
		$sqlcampos="";
		foreach($_POST as $variable=>$valor){ 
			if (substr($variable,2,1)=='_' ) //OBTENGO LOS NOMBRE DE LOS CAMPOS SOLO DE LOS OBJETOS Q TIENEN DATOS 
				if($sqlcampos=='') $sqlcampos="insert into ".$_table." (".substr($variable,3);
				else
				 $sqlcampos=$sqlcampos.','.substr($variable,3);
		    }
		$sqlvalores="";
		foreach($_POST as $variable=>$valor){ 
			if (substr($variable,2,1)=='_' )
				if (strtoupper(substr($variable,0,1))=='N' or strtoupper(substr($variable,0,2))=='ZN'){ //CAMPO TIPO NUMERICO o NUMERICO QUE SE RELLENA DE CEROS
					if(strtoupper(substr($variable,0,2))=='NN') // Si es un numérico que puede guardarse como NULL
						$dato=iif($_POST[$variable],'>',0,saca_char($_POST[$variable],','),'NULL');
					else
						$dato=iif($_POST[$variable],'>',0,saca_char($_POST[$variable],','),0);
				
					if($sqlvalores=='') $sqlvalores=" values (".$dato;
					else{
						$sqlvalores=$sqlvalores.",".$dato;
					}
			    }else
					if (strtoupper(substr($variable,0,1))=='D') //CAMPO TIPO FECHA
						{
						if($sqlvalores=='') $sqlvalores=" values ('".DateAMD($_POST[$variable])."'";
							else  $sqlvalores=$sqlvalores.",'".DateAMD($_POST[$variable])."'";
						}
					else
						if (strtoupper(substr($variable,0,1))=='F') //CAMPO TIPO ARCHIVO
							{
								//Subimos los Archivos al Server
								//_PUBLICUPLOAD_ -> se define en modulo.php
								$name_upload=$_FILES['file_'.$variable]['tmp_name'];
								$name_file=$_FILES['file_'.$variable]['name'];
								move_uploaded_file($name_upload,_PUBLICUPLOAD_.$name_file);
							
							if($name_file)
								$name_file = _PUBLICUPLOAD_.$name_file;	
							
							if($sqlvalores=='') $sqlvalores=" values ('".$name_file."'";
								else  $sqlvalores=$sqlvalores.",'".$name_file."'";
							}
						else
							if ((strtoupper(substr($variable,3,4))=='PASS') or (strlen(stristr($variable,'PASSW')))>0) // CAMPO TIPO PASSWORD
								if($sqlvalores=='') $sqlvalores=" values ('".md5($_POST
								[$variable]."'");
								else
								 $sqlvalores=$sqlvalores.",'".md5($_POST[$variable])."'";
							else{
								// RESTO DE CAMPOS
                        					//if($sqlvalores=='') $sqlvalores=" values ('".iif(substr($variable,0,1),'==','c',$_POST[$variable],strtoupper($_POST[$variable]))."'";
                                                                // Se añadió addslashes()
								if($sqlvalores=='')
                                                                    $sqlvalores=" values ($$".iif(substr($variable,0,1),'==','c',$_POST[$variable],strtoupper($_POST[$variable]))."$$";
								else
                                                                    $sqlvalores=$sqlvalores.",$$".iif(substr($variable,0,1),'==','c',$_POST[$variable],strtoupper($_POST[$variable]))."$$";
							}

		    }
		$sqlstring = $sqlcampos.")".$sqlvalores.")";

		if($myfunpginsert){ // Si voy a ejecutar una función para insertar registro en Postgres, cambio el $sqlstring
			$sqlstring = $sqlvalores.")";
			$sqlstring = stristr($sqlstring,"values (");
			$sqlstring = str_replace( "values", $myfunpginsert, $sqlstring );
		}		

                $query = $db->sql_query($sqlstring);

		if(!$query) {
                    $idinsert = muestra_error($db->sql_error());
                }else{
                    $idinsert = $db->sql_nextid();
                }

		// si es verdadero extiende la grabacion especial para tablas dinámicas
		if(substr($idinsert,0,5)!='ERROR'){
			if($_extend_grabar) include($file_grabar_extend);
                } else {
                    /* Se muestra el error */
                    //echo $idinsert;
                }

		$type=($_type=='GL')?'L':'M';
		header("Location: $_url?_op=$_op&_type=$type&_nametype=confirma.php&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&_update=1&_idinsert=$idinsert&_idexpedi=$_idexpedi&_npop=$_npop");
  	    break; 

  case 2: //MODIFICACION
		$sqlstring="";
		foreach($_POST as $variable=>$valor){ 
			if (substr($variable,2,1)=='_' )
				if (strtoupper(substr($variable,0,1))=='N' or strtoupper(substr($variable,0,2))=='ZN'){ //CAMPO TIPO NUMERICO o NUMERICO QUE SE RELLENA DE CEROS
					if(strtoupper(substr($variable,0,2))=='NN') // Si es un numérico que puede guardarse como NULL
						$dato=iif($_POST[$variable],'>',0,saca_char($_POST[$variable],','),'NULL');
					else
						$dato=iif($_POST[$variable],'>',0,saca_char($_POST[$variable],','),0);
					
					if($sqlstring=='') $sqlstring=" update ".$_table." set ".substr($variable,3)."=".$dato;
					else
					 $sqlstring=$sqlstring.",".substr($variable,3)."=".$dato;
			    }else
					if (strtoupper(substr($variable,0,1))=='F') //CAMPO TIPO ARCHIVO
						{
							//Subimos los Archivos al Server
							//_PUBLICUPLOAD_ -> se define en modulo.php
							$name_upload=$_FILES['file_'.$variable]['tmp_name'];
							$name_file=$_FILES['file_'.$variable]['name'];
							move_uploaded_file($name_upload,_PUBLICUPLOAD_.$name_file);

							if($sqlstring=='') $sqlstring=" update ".$_table." set ".substr($variable,3)."='".$name_file."'";
							else 	 $sqlstring=$sqlstring.",".substr($variable,3)."='".$name_file."'";
							
							}
					 else				
						if ((strtoupper(substr($variable,3,4))=='PASS') or (strlen(stristr($variable,'PASSW')))>0) {// CAMPO TIPO PASSWORD (strtoupper(substr($variable,3,4))=='PASS' )
							if (strcmp( $_POST[$variable],"******")){
								if($sqlstring=='') {$sqlstring=" update ".$_table." set ".substr($variable,3)."='".md5($_POST[$variable])."'";}
								else
								 {$sqlstring=$sqlstring.",".substr($variable,3)."='".md5($_POST[$variable])."'";}}}
						else {
							if (strtoupper(substr($variable,0,1))=='D') //CAMPO TIPO FECHA
								{if($sqlstring=='') $sqlstring=" update ".$_table." set ".substr($variable,3)."='".DateAMD($_POST[$variable])."'";
								else 	 $sqlstring=$sqlstring.",".substr($variable,3)."='".DateAMD($_POST[$variable])."'";}
							else
								// RESTO DE CAMPOS
								if($sqlstring=='') {
                                                                    $sqlstring=" update ".$_table." set ".substr($variable,3)."=$$".iif(substr($variable,0,1),'==','c',$_POST[$variable],strtoupper($_POST[$variable]))."$$";
                                                                } else {
                                                                    $sqlstring=$sqlstring.",".substr($variable,3)."=$$".iif(substr($variable,0,1),'==','c',$_POST[$variable],strtoupper($_POST[$variable]))."$$";}
                                                                }
                                                                 
		    }
		$variableid='zsx'.$_campoclave;
		$idedit=$_POST[$variableid];
		$sqlstring=$sqlstring." where ".$_campoclave.'='.$idedit;
                
		$query=$db->sql_query($sqlstring);
//		if(!$query) {die($db->sql_error().' ERROR DE PROCESO.  No es posible efectuar la operación'); }
		if(!$query) {$_MensError=muestra_error($db->sql_error()); }		

		// si es verdadero extiende la grabacion especial para tablas dinamicas
		if(!$_MensError) /* Si no ha habido ningún error */		
			if($_extend_grabar) include($file_grabar_extend);
				
		$type=($_type=='GL')?'L':'M';
		header("Location: $_url?_op=$_op&_type=$type&_nametype=confirma.php&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&pagina=$_pagina&orden=$_orden&_where=$_where&_update=1&_idinsert=$idedit&_npop=$_npop&_MensError=$_MensError");
  	    break; 

  case 4: //ELIMINACION
		 $_array=explode(",",$_mydato);
		 for($i=0;$i<count($_array);$i++) {
			$_idelimina=$_campoclave.'='.$_array[$i];				  
		    $sqlstringdel=" delete from ".$_table." where ".$_idelimina;
			$query=$db->sql_query($sqlstringdel);
//			if(!$query) {die($db->sql_error().$sqlstringdel);}
			if(!$query) {die($db->sql_error().' ERROR DE PROCESO.  No es posible efectuar la operación'); }
			}
	 	header("Location: $_url?_op=$_op&_type=".iif($_npop,'!=','','P','M')."&pagina=$_pagina&orden=$_orden&_where=$_where&_tabactivo=$_tabactivo&_flag=2&_npop=$_npop&_altertable=1");
	    break; 

  case 5: // BUSCAR
  		if($_op==31){ // Expedientes en Proceso
			$v_expe_id=$_POST[zsxexpe_id];
			$v_id_usu=$_POST[tr_id_usu];
			$v_ver_adjuntados=$_POST[nx_ver_adjuntados];			

			if($v_expe_id)
				$filtro=" and a.expe_id=$v_expe_id ";
	
			if($v_id_usu)
				$filtro.=" and a.id_usu=$v_id_usu ";

			if($v_expe_id and $v_ver_adjuntados){
				$sqlstring="where ((a.oper_idtope=1 or a.oper_idtope=2) and a.depe_id=".$_SESSION[depe_id]." and a.oper_procesado=FALSE "; 
				$sqlstring.="$filtro) or oper_expeid_adj=$v_expe_id ";				

//				$sqlstring="where ((a.oper_idtope=1 or a.oper_idtope=2) and a.depe_id=".$_SESSION[depe_id]." and a.oper_id not in "; 
//				$sqlstring.=" (select oper_idprocesado from operacion where oper_idprocesado is not null) $filtro) or oper_expeid_adj=$v_expe_id ";				

			}else{
				$sqlstring="where ((a.oper_idtope=1 or a.oper_idtope=2) and a.depe_id=".$_SESSION[depe_id]." and a.oper_procesado=FALSE ";
				$sqlstring.="$filtro) ";

//				$sqlstring="where ((a.oper_idtope=1 or a.oper_idtope=2) and a.depe_id=".$_SESSION[depe_id]." and a.oper_id not in ";
//				$sqlstring.="(select oper_idprocesado from operacion where oper_idprocesado is not null) $filtro) ";
			}
	
		}else{
			$sqlstring="";
			foreach($_POST as $variable=>$valor){ 
				if ((substr($variable,2,1)=='_' or substr($variable,0,3)=='zsx') && substr($variable,0,1)!='_' && $valor!=''){
					if (strtoupper(substr($variable,0,1))=='N') //CAMPO TIPO NUMERICO
						if($sqlstring=='') $sqlstring='a.'.substr($variable,3).$_POST['_busq'.substr($variable,3)].saca_char($_POST[$variable],',');
						else
						 $sqlstring=$sqlstring." and ". 'a.'.substr($variable,3).$_POST['_busq'.substr($variable,3)].saca_char($_POST[$variable],',');
					else {
						if (strtoupper(substr($variable,0,1))=='D') //CAMPO TIPO FECHA
							if($sqlstring=='') $sqlstring='a.'.substr($variable,3).$_POST[iif(strtoupper(substr($variable,0,2)),'==','DH','_bus2','_busq').substr($variable,3)]."'".DateAMD(saca_char($_POST[$variable],','))."'";
							else
							 $sqlstring=$sqlstring." and ".'a.'.substr($variable,3).$_POST[iif(strtoupper(substr($variable,0,2)),'==','DH','_bus2','_busq').substr($variable,3)]."'".DateAMD(saca_char($_POST[$variable],','))."'";
	
						else {
							// RESTO DE CAMPOS
							if($sqlstring=='') 
								{if($_POST['_busq'.substr($variable,3)]=="LIKE")
									{$sqlstring="(".'a.'.substr($variable,3)." ilike '®".$_POST[$variable]."®')";}							
								else
									{$sqlstring='a.'.substr($variable,3).$_POST['_busq'.substr($variable,3)]."'".$_POST[$variable]."'";}}
							else {
	
								if($_POST['_busq'.substr($variable,3)]=="LIKE")
									 {$sqlstring=$sqlstring." and (".'a.'.substr($variable,3)." ilike '®".$_POST[$variable]."®')";}
								else
									 {$sqlstring=$sqlstring." and ".'a.'.substr($variable,3).$_POST['_busq'.substr($variable,3)]."'".$_POST[$variable]."'";}}
						}
					}
				}
			}
		}

	$type=$_npop?'P':'M';		
 	header("Location: $_url?_op=$_op&_type=$type&_where=$sqlstring&_where2=$sqlstring2&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&_flag=2&_npop=$_npop");
// 	header("Location: $_url?_op=$_op&_type=$type&_where=$sqlstring&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&_flag=2&_npop=$_npop");
    break; 

  }

?>