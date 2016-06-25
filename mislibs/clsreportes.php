<?php
$_porcenleft='25%';
class clsformrepo extends myformreporte {
	function creo_objetos() {
		parent::creo_objetos();
		switch ($this->_idRepo)
		{
			case 6: // Expedientes Derivados
			case 1: // Expedientes Generados por Unidad Orgï¿½nica
				global $db;	
				// Consultas
					/* Entidades */
					$query="select depe_id,depe_nombre from depenti_v where depe_agente=0 order by depe_nombre";
					$rsEntidad=$db->sql_query($query);	
					if(!$rsEntidad) {die($db->sql_error().' Error en Consulta Entidades'); }
				
					// Tipos de expedientes	
					$query="select texp_id,texp_descripcion from tipo_expediente order by texp_descripcion";
					$rstipexpe=$db->sql_query($query);	
					if(!$rstipexpe) {die($db->sql_error().' Error al consultar tipo de expedientes '); }

					// Usuarios
					$query   = "select id_usu, usua_login from usuario where depe_id=".iif($_POST[sr_depe_id],">",0,$_POST[sr_depe_id],0)." order by usua_nombres";
					$rsusua=$db->sql_query($query);	
					if(!$rsusua) {die($db->sql_error().' Error en consulta de usuarios '); }

				
				// Objetos
				labelcajadate(1,1, "Fecha Desde","Dd_expe_fecha=".date("d/m/Y"),$row,"frmreporte"); 
				labelcajadate(1,1, "Fecha Hasta","Dh_expe_fecha=".date("d/m/Y"),$row,"frmreporte");

                                if($this->_idRepo == 6) { /* Expdtes Derivados */
                                    labelcajatxt(1,1, "Hora desde","Sx_hora_desde",$row,5);
                                    labelcajatxt(1,1, "Hora hasta","Sx_hora_hasta",$row,5);
                                }
                                labeloption(1,1, "Orígen","Todos",9,"op_expe_origen=9",$row,0,'[','*');
                                labeloption(1,1, "Orígen","Interno",1,"op_expe_origen",$row,0,'*','*');
                                labeloption(1,1, "Orígen","Externo",2,"op_expe_origen",$row,0,'*',']');
				labelcombo(1,1,_LOCAL_,'tr_entidad',$row,$rsEntidad,1,200,'','','','------- Todos -------');
				labelpopup(1,1,"Unidad Org.","sr_depe_id",$row,"frmreporte",6,'P58',1,50,'P','','','','----------------- Todas -----------------');
//				labelpopup(1,1,"Unidad Org.","sr_depe_id=",$row,"frmreporte",6,'P59',1,50,'P','','','','----------------- Todas -----------------');
				labelcombo(1,1,'Usuario','tr_id_usu='.$_SESSION["id"],$row,$rsusua,0,200,'','','','------- Todos -------');
				labelcajatxt(1,1, "Firma","Sx_expe_firma",$row,60); 
				labelcombo(1,1,'Tipo de Documento','tr_texp_id',$row,$rstipexpe,0,0,'','','','------- Todos -------');
				labelpopup(1,1,"Unidad Org. Destino","sr_depeid_destino",$row,"frmreporte",6,"P59",0,50,'P','','','','----------------- Todas -----------------');
				labelcajatxt(1,1, "Detalle","Sx_expe_depe_detalle",$row,60); 
			
				break;

			case 2: // Expedientes Recibidos
				global $db;	
				// Consultas
				// Tipos de expedientes	
				$query="select texp_id,texp_descripcion from tipo_expediente order by texp_descripcion";
				$rstipexpe=$db->sql_query($query);	
				if(!$rstipexpe) {die($db->sql_error().' Error en tipos de expedientes '); }
				// Usuarios
				$query   = "select id_usu, usua_login from usuario where depe_id=".iif($_POST[sr_depe_id],">",0,$_POST[sr_depe_id],0)." order by usua_nombres";
				$rsusua=$db->sql_query($query);	
				if(!$rsusua) {die($db->sql_error().' Error en consulta de usuarios '); }

				
				// Objetos
				labelcajadate(1,1, "Fecha Desde","Dd_expe_fecha=".date("d/m/Y"),$row,"frmreporte"); 
				labelcajadate(1,1, "Fecha Hasta","Dh_expe_fecha=".date("d/m/Y"),$row,"frmreporte"); 
				labelpopup(1,1,"Recibidos por Unidad Org.","sr_depe_id=",$row,"frmreporte",6,'P59',1,50,'P','','','','----------------- Todas -----------------');
				labelcombo(1,1,'Usuario','tr_id_usu='.$_SESSION["id"],$row,$rsusua,0,200,'','','','------- Todos -------');
				labelcombo(1,1,'Tipo de Documento','tr_texp_id',$row,$rstipexpe,0,0,'','','','------- Todos -------');
				labelpopup(1,1,"Unidad Org. Or&iacute;gen","sr_depeid_origen",$row,"frmreporte",6,"P56",0,50,'P','','','','----------------- Todas -----------------');
//				labelcajatxt(1,1, "Detalle","Sx_expe_depe_detalle",$row,60);
				labelcajatxt(1,1, "Asunto","Sr_expe_asunto",$row,60);
			
				break;

			case 3: // Expedientes Archivados/Procesados
				global $db;	
				// Tipos de expedientes	
				$query="select texp_id,texp_descripcion from tipo_expediente order by texp_descripcion";
				$rstipexpe=$db->sql_query($query);	
				if(!$rstipexpe) {die($db->sql_error().' Error en Consulta de Tipos de Expedientes'); }

				// Archivadores
				$sr_depe_id=iif($_POST[sr_depe_id],">",0,$_POST[sr_depe_id],0);
				$query   = "select archi_id, 
							archi_periodo || ' / ' || archi_nombre   
							from archivador 
							where depe_id=$sr_depe_id
							order by archi_periodo desc,archi_nombre";
				$rsarchi=$db->sql_query($query);	
				if(!$rsarchi) {die($db->sql_error().' Error en consulta de Archivadores '); }
				
				// Objetos
				labelcajadate(1,1, "Fecha Desde","Dd_expe_fecha=".date("d/m/Y"),$row,"frmreporte"); 
				labelcajadate(1,1, "Fecha Hasta","Dh_expe_fecha=".date("d/m/Y"),$row,"frmreporte"); 
				labelpopup(1,1,"Unidad Org.","sr_depe_id=",$row,"frmreporte",6,'P59',1,50,'P','','','','----------------- Todas -----------------');
				labelcombo(1,1,"Archivador","tr_archi_id",$row,$rsarchi,0,88,'','','','------- Todos -------');
				labelcombo(1,1,'Tipo de Documento','tr_texp_id',$row,$rstipexpe,0,0,'','','','------- Todos -------');
				labelcajatxt(1,1, "Asunto","Sr_expe_asunto",$row,60);				
							
				break;

			case 4 : // Expedientes en Proceso 
				global $db;	
				
				/* Consultas */
					/* Entidades */
					$query="select depe_id,depe_nombre from depenti_v where depe_agente=0 order by depe_nombre";
					$rsEntidad=$db->sql_query($query);	
					if(!$rsEntidad) {die($db->sql_error().' Error en Consulta Entidades'); }
	
					/* Usuarios */
					$query   = "select id_usu, usua_login from usuario where depe_id=".iif($_POST[sr_depe_id],">",0,$_POST[sr_depe_id],0)." order by usua_nombres";
					$rsusua=$db->sql_query($query);	
					if(!$rsusua) {die($db->sql_error().' Error en Consulta de Usuarios'); }
				
					// Tipos de expedientes	
					$query="select texp_id,texp_descripcion from tipo_expediente order by texp_descripcion";
					$rstipexpe=$db->sql_query($query);	
					if(!$rstipexpe) {die($db->sql_error().' Error en Consulta de Tipos de Expedientes'); }

				/* Fin de Consultas */
				
				// Objetos
				labelcombo(1,1,_LOCAL_,'tr_entidad',$row,$rsEntidad,1,200,'','','','------- Todos -------');
				labelpopup(1,1,"Unidad Org.","sr_depe_id",$row,"frmreporte",6,'P58',1,50,'P','','','','----------------- Todas -----------------');
				labelcombo(1,1,'Usuario','tr_id_usu='.$_SESSION["id"],$row,$rsusua,0,200,'','','','------- Todos -------');
				labelcombo(1,1,'Tipo de Documento','tr_texp_id',$row,$rstipexpe,0,0,'','','','------- Todos -------');
	  		    labelcheck(2,1, "Tipo de reporte","Gr&aacute;fico de barras","chk_TipReporte",$row);
			
				break;

			case 5: // Hoja de Trï¿½mite
			    labelcajanum(1,1, "Registro incial", "zsx_registroini",$row,0,8,0);
			    labelcajanum(1,1, "Registro final", "zsx_registrofin",$row,0,8,0);
	  		    labelcheck(2,1, "Selecci&oacute;n","S&oacute;lo mis registros","chk_SoloMisReg",$row);				
				break;
			
			case 7: // Expedtes por vencer  
                            
				global $db;	
				
				/* Consultas */
					/* Entidades */
					$query="select depe_id,depe_nombre from depenti_v where depe_agente=0 order by depe_nombre";
					$rsEntidad=$db->sql_query($query);	
					if(!$rsEntidad) {die($db->sql_error().' Error en Consulta Entidades'); }
				
				labelcombo(1,1,_LOCAL_,'tr_entidad',$row,$rsEntidad,0,200,'','','','------- Todos -------');
				
				break;

			case 8: // Archivadores  
				global $db;	
				
				/* Consultas */
					/* Entidades */
					$query="select depe_id,depe_nombre from depenti_v where depe_agente=0 order by depe_nombre";
					$rsEntidad=$db->sql_query($query);	
					if(!$rsEntidad) {die($db->sql_error().' Error en Consulta Entidades'); }
				
				labelcombo(1,1,_LOCAL_,'tr_entidad',$row,$rsEntidad,1,200,'','','','------- Todos -------');
				labelpopup(1,1,"Unidad Org.","sr_depe_id",$row,"frmreporte",6,'P58',1,50,'P','','','','----------------- Todas -----------------');
	    		labelcajanum(1,1, "Periodo", "nr_archi_periodo",$row,0,4,0);
	    											
				break;
				
			case 10: // Informe de Gestión
                            
				global $db;	
				
                                labelcajadate(1,1, "Fecha Desde","Dd_expe_fecha=".date("d/m/Y"),$row,"frmreporte"); 
                                labelcajadate(1,1, "Fecha Hasta","Dh_expe_fecha=".date("d/m/Y"),$row,"frmreporte"); 

                                /* Entidades */
                                $query="select depe_id,depe_nombre from depenti_v where depe_agente=0 order by depe_nombre";
                                $rsEntidad=$db->sql_query($query);	
                                if(!$rsEntidad) {die($db->sql_error().' Error en Consulta Entidades'); }
				
				labelcombo(1,1,_LOCAL_,'tr_entidad',$row,$rsEntidad,0,200,'','','','------- Todos -------');
				
				break;
                                
			default:
				return $this->_error;
				break;
		} // fin de switch
	} // fin de funciï¿½n

	function creo_sql() {

		parent::creo_sql();
		// Declaro variables globales qu las uso en formureporte.php
		global $_pagerpt;
		
		// Obtengo el nombre del archivo XML del reporte
		$rptFileXml=saca_valor("select * from reporte where repo_id=$_POST[listarepo]",'repo_archivo');

		switch ($this->_idRepo)
		{
			case 1: // Expedientes Generados por Unidad Orgï¿½nica
				// Titulo del reporte
				$rptTitulo='EXPEDIENTES GENERADOS POR UNIDAD ORGANICA';
				
				// Variables
				$v_fecha_ini=$_POST['Dd_expe_fecha'];
				$v_fecha_fin=$_POST['Dh_expe_fecha'];
				$v_entidad=$_POST['tr_entidad']==''?'':"and e.depe_depende=".$_POST['tr_entidad'];				
				$v_depe_id=$_POST['sr_depe_id']==''?'':"and a.idusu_depe=".$_POST['sr_depe_id'];
				$v_depe_idop=$_POST['sr_depe_id']==''?'':"and d.depe_id=".$_POST['sr_depe_id'];
				$v_id_usu=$_POST['tr_id_usu']==''?'':"and a.id_usu=".$_POST['tr_id_usu'];
				$v_id_usuop=$_POST['tr_id_usu']==''?'':"and d.id_usu=".$_POST['tr_id_usu'];
				$v_expe_firma=$_POST['Sx_expe_firma'];
				$v_texp_id=$_POST['tr_texp_id']==''?'':"and a.texp_id=".$_POST['tr_texp_id'];
				$v_depeid_destino=$_POST['sr_depeid_destino']==''?'':"$v_depe_idop $v_id_usuop and d.oper_depeid_d=".$_POST['sr_depeid_destino'];
				$v_depe_detalle=$_POST['Sx_expe_depe_detalle'];

				// Consulta
				$_stringsql="select distinct lpad(a.expe_id::TEXT,8,'0') as expe_id,
									to_char(a.expe_fecha,'dd-mm-yyyy') as expe_fecha,
									b.texp_descripcion || ' ' || lpad(a.expe_numero_doc::TEXT,6,'0') || '-' || a.expe_siglas_doc as expediente,
									a.expe_fecha_doc,
									lpad(a.expe_folios::TEXT,3,'0') as expe_folios,
									c.depe_nombre as dependencia,
									a.expe_depe_detalle,
									a.expe_firma,
									a.expe_cargo,
									a.expe_asunto 
							 from expediente a 
								 left join tipo_expediente b on a.texp_id=b.texp_id 
								 left join dependencia c on a.depe_id=c.depe_id 
								 left join operacion d on a.expe_id=d.expe_id
								 left join dependencia e on a.idusu_depe=e.depe_id 								  
							 where a.expe_fecha BETWEEN '$v_fecha_ini' and '$v_fecha_fin' 
								 $v_entidad $v_depe_id $v_id_usu $v_texp_id $v_depeid_destino 
								 and a.expe_depe_detalle LIKE '%$v_depe_detalle%' 
								 and a.expe_firma LIKE '%$v_expe_firma%' 
							 order by 1";

				$_SESSION['$_stringsql'] = $_stringsql;
                                
				AbreVentana("../reports/rptreports.php?_titulo=$rptTitulo&_xml=$rptFileXml",'impresion');
				break;

			case 2: // Expedientes Recibidos
				// Titulo del reporte
				$rptTitulo='EXPEDIENTES RECIBIDOS';
				
				// Variables
				$v_fecha_ini=$_POST['Dd_expe_fecha'];
				$v_fecha_fin=$_POST['Dh_expe_fecha'];
				$v_depe_id1 = $_POST['sr_depe_id']==''?'':"and a.depe_id=".$_POST['sr_depe_id'];
                                $v_depe_id=$_POST['sr_depe_id']==''?'':"and d.depe_id=".$_POST['sr_depe_id'];
				$v_id_usu=$_POST['tr_id_usu']==''?'':"and d.id_usu=".$_POST['tr_id_usu'];
				$v_texp_id=$_POST['tr_texp_id']==''?'':"and a.texp_id=".$_POST['tr_texp_id'];
				$v_depeid_origen=$_POST['sr_depeid_origen']==''?'':"and a.depe_id=".$_POST['sr_depeid_origen'];
//				$v_depe_detalle=$_POST['Sx_expe_depe_detalle'];
//				$v_depe_detalle=$_POST['Sx_expe_depe_detalle']==''?'':"and a.expe_depe_detalle LIKE '%".$_POST['Sx_expe_depe_detalle']."%'";
				
				$v_expe_asunto = $_POST['Sr_expe_asunto'];
				$v_expe_asunto = $_POST['Sr_expe_asunto']==''?'':"and a.expe_asunto LIKE '%".$_POST['Sr_expe_asunto']."%'";
				
//                                $depe_id = $_POST['sr_depe_id'];
                                
				// Consulta
				$_stringsql="select distinct lpad(a.expe_id::TEXT,8,'0') as expe_id,
									to_char(d.oper_fecha,'dd-mm-yyyy') AS fecha_rec,
									b.texp_descripcion || ' ' || lpad(a.expe_numero_doc::TEXT,6,'0') || '-' || a.expe_siglas_doc as expediente,
									to_char(a.expe_fecha_doc,'dd-mm-yyyy') AS expe_fecha_doc,
									lpad(a.expe_folios::TEXT,3,'0') as expe_folios,
									c.depe_nombre as dependencia,
									a.expe_depe_detalle,
									a.expe_firma,
									a.expe_cargo,
									a.expe_asunto 
							 from expediente a 
								 left join tipo_expediente b on a.texp_id=b.texp_id 
								 left join dependencia c on a.depe_id=c.depe_id 
								 left join operacion d on a.expe_id=d.expe_id 
							 where d.oper_fecha BETWEEN '$v_fecha_ini' and '$v_fecha_fin' 
							 		and d.oper_idtope=1 
							 		and d.oper_id NOT IN (SELECT min(a.oper_id) AS oper_id  
										FROM operacion a 
										LEFT JOIN expediente b ON b.expe_id = a.expe_id 
										WHERE b.expe_origen = 1 AND b.expe_fecha < '$v_fecha_fin' $v_depe_id1 
										GROUP BY b.expe_id )
									$v_depe_id $v_id_usu $v_texp_id $v_depeid_origen $v_depe_detalle $v_expe_asunto 
							 order by 1";				

                                $_SESSION['$_stringsql'] = $_stringsql;

				AbreVentana("../reports/rptreports.php?_titulo=$rptTitulo&_xml=$rptFileXml",'impresion');
				break;

			case 3: // Expedientes Procesados/Archivados
				// Titulo del reporte
				$rptTitulo='DOCUMENTOS PROCESADOS/ARCHIVADOS';
				
				// Variables
				$v_fecha_ini=$_POST['Dd_expe_fecha'];
				$v_fecha_fin=$_POST['Dh_expe_fecha'];
				$v_depe_id=$_POST['sr_depe_id']==''?'':"and d.depe_id=".$_POST['sr_depe_id'];
				$v_archi_id=$_POST['tr_archi_id']==''?'':"and d.archi_id=".$_POST['tr_archi_id'];
				$v_texp_id=$_POST['tr_texp_id']==''?'':"and a.texp_id=".$_POST['tr_texp_id'];
				$v_expe_asunto = $_POST['Sr_expe_asunto'];
				$v_expe_asunto = $_POST['Sr_expe_asunto']==''?'':"and a.expe_asunto LIKE '%".$_POST['Sr_expe_asunto']."%'";
				
				
				// Consulta
				$_stringsql="select distinct e.depe_nombre as oficina,lpad(a.expe_id::TEXT,8,'0') as expe_id,to_char(a.expe_fecha,'dd-mm-yyyy') as expe_fecha,b.texp_descripcion || ' ' || lpad(a.expe_numero_doc::TEXT,6,'0') || a.expe_siglas_doc || case when a.expe_proyectado!='' then '-' || a.expe_proyectado when a.expe_proyectado='' then a.expe_proyectado end as expediente,";
				$_stringsql.="a.expe_fecha_doc,lpad(a.expe_folios::TEXT,3,'0') as expe_folios,c.depe_nombre as dependencia,a.expe_depe_detalle,a.expe_firma,a.expe_cargo,a.expe_asunto ";
				$_stringsql.="from expediente a ";
				$_stringsql.="left join tipo_expediente b on a.texp_id=b.texp_id ";
				$_stringsql.="left join dependencia c on a.depe_id=c.depe_id ";
				$_stringsql.="left join operacion d on a.expe_id=d.expe_id ";
				$_stringsql.="left join dependencia e on d.depe_id=e.depe_id ";
				$_stringsql.="where (d.oper_idtope=3 or d.oper_idtope=4) and d.oper_fecha BETWEEN '$v_fecha_ini' and '$v_fecha_fin' ";
				$_stringsql.="$v_depe_id $v_archi_id $v_texp_id $v_expe_asunto";
				$_stringsql.="order by 1";				

				$_SESSION['$_stringsql'] = $_stringsql; 				
				                                
				AbreVentana("../reports/rptreports.php?_titulo=$rptTitulo&_xml=$rptFileXml",'impresion');

				break;

			case 4: // Expedientes En Proceso
				// Titulo del reporte
				$rptTitulo='DOCUMENTOS EN PROCESO';
				
				// Variables
				$chk_TipReporte=$_POST['chk_TipReporte'];
				
				if($chk_TipReporte){ // Reporte de barras grï¿½ficas

					$v_entidad=$_POST['tr_entidad']==''?'':"and e.depe_depende=".$_POST['tr_entidad'];
					$v_depe_id=$_POST['sr_depe_id']==''?'':"and b.depe_id=".$_POST['sr_depe_id'];
					$v_usua_id=$_POST['tr_id_usu']==''?'':"and b.id_usu=".$_POST['tr_id_usu'];
					$v_texp_id=$_POST['tr_texp_id']==''?'':"and a.texp_id=".$_POST['tr_texp_id'];

					// Consulta
					$_stringsql="select a.depe_id,0 as id_usu,a.oficina,'aaaa' as usuario,a.total from ( ";
					$_stringsql.="select b.depe_id,e.depe_nombre as oficina,count(*) as total ";
					$_stringsql.="from expediente a ";
					$_stringsql.="left join operacion b on a.expe_id=b.expe_id ";
					$_stringsql.="left join dependencia e on b.depe_id=e.depe_id ";
					$_stringsql.="where (b.oper_idtope=1 or b.oper_idtope=2) ";
					$_stringsql.="$v_entidad $v_depe_id $v_texp_id and b.oper_procesado=FALSE ";
					$_stringsql.="group by b.depe_id,e.depe_nombre) as a ";
					$_stringsql.="union ";
					$_stringsql.="select b.depe_id,a.id_usu,c.depe_nombre as oficina,a.usuario,a.total ";
					$_stringsql.="from (";
					$_stringsql.="select b.id_usu,f.usua_login as usuario,count(*) as total ";
					$_stringsql.="from expediente a ";
					$_stringsql.="left join operacion b on a.expe_id=b.expe_id ";
					$_stringsql.="left join dependencia e on b.depe_id=e.depe_id ";
					$_stringsql.="left join usuario f on b.id_usu=f.id_usu ";
					$_stringsql.="where (b.oper_idtope=1 or b.oper_idtope=2) ";
					$_stringsql.="$v_entidad $v_depe_id $v_usua_id $v_texp_id and b.oper_procesado=FALSE ";
					$_stringsql.="group by b.id_usu,f.usua_login) as a ";
					$_stringsql.="left join usuario b on b.id_usu=a.id_usu ";
					$_stringsql.="left join dependencia c on c.depe_id=b.depe_id ";
					$_stringsql.="order by 1 ";

					/* Para obtener el nï¿½mero total de expedientes en proceso */ 
					$_stringsql2="select count(*) as total ";
					$_stringsql2.="from expediente a ";
					$_stringsql2.="left join operacion b on a.expe_id=b.expe_id ";
					$_stringsql2.="left join dependencia e on b.depe_id=e.depe_id ";
					$_stringsql2.="where (b.oper_idtope=1 or b.oper_idtope=2) ";
					$_stringsql2.="$v_entidad $v_depe_id $v_texp_id and b.oper_procesado=FALSE ";
					
					
					$_SESSION['$_stringsql'] = $_stringsql; 				
					$_SESSION['$_stringsql2'] = $_stringsql2;
					AbreVentana('../reports/rptexpe_procegraf1.php','impresion');
								
				}else{
					
					$v_entidad=$_POST['tr_entidad']==''?'':"and f.depe_depende=".$_POST['tr_entidad'];
					$v_depe_id=$_POST['sr_depe_id']==''?'':"and a.depe_id=".$_POST['sr_depe_id'];
					$v_usua_id=$_POST['tr_id_usu']==''?'':"and a.id_usu=".$_POST['tr_id_usu'];
					$v_texp_id=$_POST['tr_texp_id']==''?'':"and b.texp_id=".$_POST['tr_texp_id'];

					$_stringsql="select f.depe_nombre as oficina,g.usua_nombres as usuario,
                                                            lpad(a.expe_id::TEXT,8,'0') as expe_id,to_char(b.expe_fecha,'dd-mm-yyyy') as expe_fecha,
                                                            CASE
                                                                WHEN a.oper_forma=0 THEN 'ORIGINAL'
                                                                WHEN a.oper_forma=1 THEN 'COPIA'
                                                            END as expe_forma,
                                                            d.texp_abreviado || ' ' || lpad(b.expe_numero_doc::TEXT,6,'0') || ' ' || b.expe_siglas_doc ||
                                                            CASE
                                                                WHEN b.expe_proyectado!='' THEN '-' || b.expe_proyectado
                                                                WHEN b.expe_proyectado='' THEN b.expe_proyectado
                                                            END as expediente,
                                                            b.expe_fecha_doc,b.expe_folios,c.depe_abreviado as dependencia,
                                                            b.expe_depe_detalle,b.expe_firma,b.expe_cargo,b.expe_asunto,
                                                            CASE
                                                                WHEN b.expe_diasatencion>0 THEN b.expe_diasatencion - (now()::date - b.expe_fecha)
                                                                ELSE NULL END AS diasxvencer
                                                            FROM operacion a
                                                            LEFT JOIN expediente b on a.expe_id=b.expe_id
                                                            LEFT JOIN dependencia c on b.depe_id=c.depe_id
                                                            LEFT JOIN tipo_expediente d on b.texp_id=d.texp_id
                                                            LEFT JOIN dependencia f on f.depe_id=a.depe_id
                                                            LEFT JOIN usuario g on g.id_usu=a.id_usu
                                                            WHERE (a.oper_idtope=1 or a.oper_idtope=2) $v_entidad $v_depe_id $v_usua_id $v_texp_id and a.oper_procesado=FALSE
                                                            ORDER BY 1,2";

					$_SESSION['$_stringsql'] = $_stringsql;

					AbreVentana("../reports/rptreports.php?_titulo=$rptTitulo&_xml=$rptFileXml",'impresion');
				}
				break;

			case 5:
				// Titulo del reporte
				$rptTitulo='Hoja de Tramite';
				
				// Variables
				$reg_ini=$_POST['zsx_registroini'];
				$reg_fin=$_POST['zsx_registrofin'];
				$chk_SoloMisReg=$_POST['chk_SoloMisReg'];
				$v_id_usu=$chk_SoloMisReg?"and a.id_usu=".$_SESSION["id"]:'';

				// Consulta
				$_stringsql="select lpad(a.expe_id::TEXT,8,'0') as expe_id,
									to_char(a.expe_fecha,'dd-mm-yyyy') as expe_fecha,
									b.texp_descripcion || ' ' || lpad(a.expe_numero_doc::TEXT,6,'0') || a.expe_siglas_doc || 
									case 
									when a.expe_proyectado!='' then '-' || a.expe_proyectado 
									when a.expe_proyectado='' then a.expe_proyectado 
									end as expediente,
									a.expe_fecha_doc,
									lpad(a.expe_folios::TEXT,3,'0') as expe_folios,
									c.depe_nombre as dependencia,
									a.expe_depe_detalle,
									a.expe_firma,
									a.expe_cargo,
									a.expe_asunto,
									d.depe_nombre as entidad,
									f.depe_abreviado AS origen,
									g.depe_abreviado  AS destino,
									e.oper_acciones as acciones,
                                                                        lpad(a.exma_id::TEXT,8,'0') as exmaid
							 from expediente a 
							 left join tipo_expediente b on a.texp_id=b.texp_id 
							 left join dependencia c on a.depe_id=c.depe_id 
							 left join dependencia d on d.depe_id=c.depe_depende 
							 left join (select distinct on (expe_id) * 
										from operacion
										where expe_id BETWEEN $reg_ini AND $reg_fin
										and oper_idtope=2
										order by expe_id,oper_id) e on e.expe_id=a.expe_id 
							 left join dependencia f on f.depe_id=e.depe_id 
							 left join dependencia g on g.depe_id=e.oper_depeid_d 
							 where a.expe_id BETWEEN $reg_ini AND $reg_fin $v_id_usu 
							 order by a.expe_id "; 

				$_SESSION['$_stringsql'] = $_stringsql; 
				AbreVentana('../reports/hojatramite.php','impresion');
				
				break;

			case 6: // Expedientes Derivados /* Fue reemplazado por el reporte rptdocderivados.php */
				// Titulo del reporte
				$rptTitulo='Documentos Derivados';
				
				// Variables
				$v_fecha_ini = $_POST['Dd_expe_fecha'];
				$v_fecha_fin = $_POST['Dh_expe_fecha'];
				$v_hora_desde = $_POST['Sx_hora_desde']==''?'':"and d.oper_hora >= '".$_POST['Sx_hora_desde']."'";
                                $v_hora_hasta = $_POST['Sx_hora_hasta']==''?'':"and d.oper_hora <= '".$_POST['Sx_hora_hasta']."'";
				$v_depe_id = $_POST['sr_depe_id']==''?'':"and d.depe_id=".$_POST['sr_depe_id'];
				$v_depe_idop = $_POST['sr_depe_id']==''?'':"and d.depe_id=".$_POST['sr_depe_id'];
				$v_id_usu = $_POST['tr_id_usu']==''?'':"and a.id_usu=".$_POST['tr_id_usu'];
				$v_id_usuop = $_POST['tr_id_usu']==''?'':"and d.id_usu=".$_POST['tr_id_usu'];
				$v_expe_firma = $_POST['Sx_expe_firma'];
				$v_texp_id = $_POST['tr_texp_id']==''?'':"and a.texp_id=".$_POST['tr_texp_id'];
				$v_depeid_destino = $_POST['sr_depeid_destino']==''?'':"$v_depe_idop $v_id_usuop and d.oper_depeid_d=".$_POST['sr_depeid_destino'];
				$v_depe_detalle = $_POST['Sx_expe_depe_detalle'];

				// Consulta
				$_stringsql="select distinct lpad(a.expe_id::TEXT,8,'0') as expe_id,
									to_char(d.oper_fecha,'dd/mm/yyyy') || ' ' || to_char(d.oper_hora, 'HH24:MI:SS') AS oper_fecha,
									b.texp_descripcion || ' ' || lpad(a.expe_numero_doc::TEXT,6,'0') || '-' || a.expe_siglas_doc as expediente,
									to_char(a.expe_fecha_doc,'dd/mm/yyyy') AS expe_fecha_doc,
									lpad(a.expe_folios::TEXT,3,'0') as expe_folios,
									c.depe_nombre as dependencia,
									a.expe_depe_detalle,
									a.expe_firma,
									a.expe_cargo,
									a.expe_asunto,
									e.depe_nombre as destino 
							from expediente a 
								left join tipo_expediente b on a.texp_id=b.texp_id 
								left join dependencia c on a.depe_id=c.depe_id 
								left join operacion d on a.expe_id=d.expe_id 
								left join dependencia e on e.depe_id=d.oper_depeid_d 
							where d.oper_fecha BETWEEN '$v_fecha_ini' and '$v_fecha_fin' and d.oper_idtope=2 
		 						$v_hora_desde $v_hora_hasta $v_depe_id $v_id_usu $v_texp_id $v_depeid_destino
								and a.expe_depe_detalle LIKE '%$v_depe_detalle%' 
								and a.expe_firma LIKE '%$v_expe_firma%' 
							order by destino,1";				

				$_SESSION['$_stringsql'] = $_stringsql;
                                
				AbreVentana("../reports/rptreports.php?_titulo=$rptTitulo&_xml=$rptFileXml",'impresion');

				break;				
				
			default:
				return $this->_error;
				break;
		} // fin de switch
	} // fin de funciï¿½n
}
?>