<?
// Clase Expediente
class clsMyDocumento
{
	var $_idExpdte; // Id del expediente
	var $_rsTramite; // Recordsource de trámite del $_idExpdte
	var $_TotFilTramite; // Total de filas de trámite
	var $_sqlTramite; // Sentencia Sql para obtener el trámite del expediente.
	var $_rsExpdte; // Recordsource del expdte.
	var $_rowExpdte; // Fila array con los datos del expediente.
	var $_rsExpdteRel;	// Recordsource de expedientes relacionados. 
	var $_TotFilExpRel;  // Total de filas obtenidas de la consulta de expedientes relacionados.

	function __construct($nidExpdte)
	{
		$this->_idExpdte=$nidExpdte; // Asigno el id del expediente
	} 

	function consulta_expdte()
	{
		global $db;
		// consulta para mostrar los datos del expediente del que solicito su trámite
		$query="SELECT lpad(a.expe_id::TEXT,8,'0') as registro,
                                lpad(a.exma_id::TEXT,8,'0') as expediente,
                                a.texp_id,
				b.texp_descripcion||' '||COALESCE(lpad(a.expe_numero_doc::TEXT,6,'0'),'')|| 
				COALESCE(a.expe_siglas_doc,'') || 
				case 
                                    when a.expe_proyectado!='' then '-' || expe_proyectado 
                                    when a.expe_proyectado='' then expe_proyectado 
				end as documento,
                                a.expe_origen,
				a.expe_fecha_doc,
				a.expe_folios,
				a.expe_asunto,
                                c.depe_nombre AS unidad_entidad,
                                a.expe_depe_detalle,
                                d.depe_nombre AS Dependencia,
				a.expe_firma,
                                a.expe_cargo,
				a.expe_estado,
				a.expe_clastupa,
				a.expe_diasatencion,
				a.expe_emailorigen 
			FROM expediente a
			LEFT JOIN tipo_expediente b ON a.texp_id = b.texp_id 
                        LEFT JOIN dependencia c ON c.depe_id = a.depe_id 
                        LEFT JOIN dependencia d ON d.depe_id = c.depe_depende 
			WHERE a.expe_id = $this->_idExpdte";
				
		$this->_rsExpdte=$db->sql_query($query);	
		$this->_rowExpdte = $db->sql_fetchrow($this->_rsExpediente);
	} // function

        function get_expediente(){
            return $this->_rowExpdte['expediente'];
        }
        
	function muestra_expdte()
	{
		global $db;
	
			// *********************************** //
			// ***** Campos Generales ************ //
			// *********************************** //
			labelcajatxt(3,3, "Expediente","tr_texp_id=".$this->_rowExpdte['documento'],'',80); 
			labelcajadate(3,3, "Fecha de Expediente","Dr_expe_fecha_doc",$this->_rowExpdte,"formregistro");
			if($this->_rowExpdte['texp_id']<>30)			
				labelcajatxt(3,3, "Folios","zr_expe_folios",$this->_rowExpdte,05); 
			
			labelareatxt(3,3, "Asunto","ex_expe_asunto",$this->_rowExpdte,4,80);

                        if($this->_rowExpdte[expe_origen] == 1){ /* Si es expdte interno */
                            labelcajatxt(3,3, "Unidad","Sr_unidad_entidad",$this->_rowExpdte,70);
                            labelcajatxt(3,3, "Dependencia","Sr_dependencia",$this->_rowExpdte,70);
                        } else {
                            labelcajatxt(3,3, "Entidad","Sr_unidad_entidad",$this->_rowExpdte,70);
                            labelcajatxt(3,3, "Dependencia","Sr_expe_depe_detalle",$this->_rowExpdte,70);
                        }
                        
			labelcajatxt(3,3, "Firma","Sr_expe_firma",$this->_rowExpdte,70);
                        labelcajatxt(3,3, "Cargo","Sr_expe_cargo",$this->_rowExpdte,70);

            if($this->_rowExpdte[expe_clastupa] <> 9){			
        		labeloption(3,3, "Clasificación","Silencio positivo",1,"op_expe_clastupa",$this->_rowExpdte,0,'[','*');
    	    	labeloption(3,3, "Clasificación","Silencio negativo",2,"op_expe_clastupa",$this->_rowExpdte,0,'*','*');
    		    labeloption(3,3, "Clasificación","Automático",3,"op_expe_clastupa",$this->_rowExpdte,0,'*','*');
    		    labeloption(3,3, "Clasificación","Ninguna",9,"op_expe_clastupa=9",$this->_rowExpdte,0,'*',']');
    		    labelcajanum(3,3, "# de Días de atención", "zn_expe_diasatencion",$this->_rowExpdte,0,2,0);
            }
            
    		if($this->_rowExpdte[expe_emailorigen])
	    		labelcajatxt(3,3, "Email","Sx_expe_emailorigen",$this->_rowExpdte,60); 
	    
			seccion('',3,3);	
			if($this->_rowExpdte['expe_estado']==9)
				seccion2('ANULADO',3,3,$align="center",$class="Anulado");

	} // function

	function consulta_tramite()
	{
		global $db;

                $local = _LOCAL_;
                
		// Sentencia para obtener el trámite u operaciones efectuadas en el expdte.	
		$this->_sqlTramite="select SUBSTRING(g.depe_abreviado,0,15) as $local,
					to_char(a.oper_fecha,'dd-mm-yyyy') || ' ' || to_char(a.oper_hora, 'HH24:MI:SS') as Fecha,
					CASE 
                                            WHEN a.oper_idtope=1 then 'REGISTRADO' 
                                            WHEN a.oper_idtope=2 then 'DERIVADO' 
                                            WHEN a.oper_idtope=3 then 'ARCHIVADO EN FILE: ' || f.archi_periodo || ' / '|| f.archi_nombre
                                            WHEN a.oper_idtope=4 then 'ADJUNTADO AL ' || lpad(a.oper_expeid_adj::TEXT,8,'0') 
                                        END AS Operacion,
					CASE 
                                            when a.oper_forma=0 then 'ORIGINAL' 
                                            when a.oper_forma=1 then 'COPIA' 
                                        END as Forma,
					b.depe_nombre as Unidad_Organica,
					c.usua_nombres || ' ' || c.usua_apellidos as Usuario,
					d.depe_nombre as Unidad_Destino,
					e.usua_nombres || ' ' || e.usua_apellidos as Usuario_destino,
					a.oper_acciones as Proveido,
					CASE
                                            WHEN a.oper_procesado OR a.oper_idtope IN (3,4)THEN 1
					ELSE 0
					END AS ___oper_procesado,									
					a.oper_id as _mydato
                                    FROM operacion a
                                    LEFT join dependencia b on a.depe_id=b.depe_id
                                    left join depenti_v g on g.depe_id=b.depe_depende							
                                    left join usuario c on a.id_usu=c.id_usu
                                    left join dependencia d on a.oper_depeid_d=d.depe_id
                                    left join usuario e on a.oper_usuaid_d=e.id_usu
                                    left join archivador f on a.archi_id=f.archi_id 
                                    where expe_id=".$this->_idExpdte." order by oper_id";

		// Creo el recordsource de trámite
		$this->_rsTramite=$db->sql_query($this->_sqlTramite); 
		// Guardo la cantidad total de filas obtenidas del Trámite
		$this->_TotFilTramite=$db->sql_numrows($this->_rsTramite);
	} // function

	function muestra_tramite()
	{
		global $gridcolconfig,$gridrowcolor;
	
		$_pagina=1;
		$_orden=10; // Para ordenar el array del gridpaginado por oper_id, el último campo de la consulta
		$nRegxPag=1000;
		
		$gridrowcolor = array (
			   "0"  => array("campo"=>"_oper_procesado", "dato"=>"0", "color"=>"#748AB6","cuenta"=>0),
				);

		// Creo el array para dar ancho a determinados campos
		$gridcolconfig = array (
			   "0"  => array("campo"=>"fecha"	,           "obj"=>"", "width"=>"10%", "color"=>""),
			   "1"  => array("campo"=>"operacion"	,           "obj"=>"", "width"=>"15%", "color"=>""),
			   "2"  => array("campo"=>"unidad_organica"	,   "obj"=>"", "width"=>"35%", "color"=>""),
			   "3"  => array("campo"=>"unidad_destino"	,   "obj"=>"", "width"=>"35%", "color"=>""),
			   "4"  => array("campo"=>"proveido"	,           "obj"=>"", "width"=>"35%", "color"=>""),
			   "5"  => array("campo"=>"___oper_procesado"	,   "obj"=>"", "width"=>"0%", "color"=>""),
                           "6"  => array("campo"=>"usuario"	,           "obj"=>"", "width"=>"55%", "color"=>""),
                           "7"  => array("campo"=>"usuario_destino"	,   "obj"=>"", "width"=>"55%", "color"=>"")
			   );		

		$grid = new paginado($_pagina,$_orden,$nRegxPag,$this->_sqlTramite); 
		$grid->_CheckSelec=0; // Para indicar si se mostrará un check de selección por fila
		$grid->_TituloOrdenar=0; // Para indicar si se mostrará los titulos de los campos como links 
		$grid->_BarraNavegar=0; // Para indicar si se muestra la barra de navegar entre páginas
		$grid->_ClassTitulo="titutramite"; // Para indicar la clase que dara forma y color a los titulos de los campos
		$grid->_ClassTupla="tuplagrid"; // Para indicar la clase que dara forma y color a los titulos de los campos		
		$grid->_ColFila1="#DAE6EA"; // Primer color que se mostrará en las filas
		$grid->_ColFila2="#FFFFFF"; // Segundo color que alternará
	
		?>		
		<tr>
		<td colspan='5' class='marco seccionblank' >
		<? 
		$grid->table_create($grid->rs());			
		?>
		</td>
		</tr>
		<?
	} 
	
	function consulta_expdte_rel() // Para consultar los expedientes relacionados y/o adjuntados que tiene un determinado expediente
	{
		global $db;
		// consulta para mostrar los datos del expediente del que solicito su trámite
		$query="select expe_id from expediente where expe_relacionado=".$this->_idExpdte.
		" union select expe_id from operacion where oper_expeid_adj=".$this->_idExpdte;
		$this->_rsExpdteRel=$db->sql_query($query);	

		// Guardo la cantidad total de filas obtenidas de los expedientees relacionados
		$this->_TotFilExpRel=$db->sql_numrows($this->_rsExpdteRel);

	} // function


} // end of class

?>