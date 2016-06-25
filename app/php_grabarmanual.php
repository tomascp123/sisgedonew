<?
		$bloq_id=$_POST[zsxbloq_id];
		$depe_id=$_POST[nr_depe_id];
		$id_usu=$_POST[tx_id_usu];
		$bloq_mensaje=$_POST[Sr_bloq_mensaje];
		$bloq_bloqueo=$_POST[nr_bloq_bloqueo];

		if(!$id_usu) //  para los bloqueos donde no se registra usuario
			$id_usu='NULL';

		if(!$bloq_mensaje) //  para los bloqueos donde no se registra mensaje
			$bloq_mensaje='';

switch($_tipoedicion)
  {
  case 1: // NUEVO
		$sqlstring = "insert into bloqueo (depe_id,id_usu,bloq_mensaje,bloq_bloqueo) values ($depe_id,$id_usu,'$bloq_mensaje',$bloq_bloqueo)";

		$query=$db->sql_query($sqlstring);
		if(!$query) {$idinsert=muestra_error($db->sql_error()); }
		else $idinsert=$db->sql_nextid();

		$type=($_type=='GL')?'L':'M';
		header("Location: $_url?_op=$_op&_type=$type&_nametype=confirma.php&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&_update=1&_idinsert=$idinsert&_npop=$_npop");
  	    break; 

  case 2: //MODIFICACION
		$variableid='zsx'.$_campoclave;
		$idedit=$_POST[$variableid];

		$sqlstring = "update bloqueo set depe_id=$depe_id,id_usu=$id_usu,bloq_mensaje='$bloq_mensaje',bloq_bloqueo=$bloq_bloqueo where bloq_id=$bloq_id";

		$query=$db->sql_query($sqlstring);	
		if(!$query) {$idinsert=muestra_error($db->sql_error()); }

		$type=($_type=='GL')?'L':'M';
		header("Location: $_url?_op=$_op&_type=$type&_nametype=confirma.php&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&pagina=$_pagina&orden=$_orden&_where=$_where&_update=1&_idinsert=$idedit&_npop=$_npop");
  	    break; 

  case 4: //ELIMINACION
		 $_array=explode(",",$_mydato);
		 for($i=0;$i<count($_array);$i++) {
			$_idelimina=$_campoclave.'='.$_array[$i];				  
		    $sqlstringdel=" delete from ".$_table." where ".$_idelimina;
			$query=$db->sql_query($sqlstringdel);	
			if(!$query) {die($db->sql_error().' Error al eliminar ');}
			}
	 	header("Location: $_url?_op=$_op&_type=".iif($_npop,'!=','','P','M')."&pagina=$_pagina&orden=$_orden&_where=$_where&_tabactivo=$_tabactivo&_flag=2&_npop=$_npop&_altertable=1");		
	    break; 

  case 5: // BUSCAR
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
										{$sqlstring="(".'a.'.substr($variable,3)." like '®".$_POST[$variable]."®')";}							
									else
										{$sqlstring='a.'.substr($variable,3).$_POST['_busq'.substr($variable,3)]."'".$_POST[$variable]."'";}}
								else {
		
									if($_POST['_busq'.substr($variable,3)]=="LIKE")
										 {$sqlstring=$sqlstring." and (".'a.'.substr($variable,3)." like '®".$_POST[$variable]."®')";}
									else
										 {$sqlstring=$sqlstring." and ".'a.'.substr($variable,3).$_POST['_busq'.substr($variable,3)]."'".$_POST[$variable]."'";}}
						}
					}

		    }}

	$type=$_npop?'P':'M';		
 	header("Location: $_url?_op=$_op&_type=$type&_where=$sqlstring&_tabactivo=$_tabactivo&_tipoedicion=$_tipoedicion&_flag=2&_npop=$_npop");		
    break; 

  }

?>