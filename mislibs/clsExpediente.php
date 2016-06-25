<?
// Clase Expediente
class clsExpediente
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
		$this->_sqlTramite="SELECT lpad(b.expe_id::TEXT,8,'0') AS registro,TO_CHAR(b.expe_fecha_doc,'dd/mm/YYYY') AS fecha,
					   c.texp_descripcion||' '||COALESCE(lpad(b.expe_numero_doc::TEXT,6,'0'),'')||
					   COALESCE(b.expe_siglas_doc,'') ||
					   case 
					   		when b.expe_proyectado!='' then '-' || expe_proyectado
							when b.expe_proyectado='' then expe_proyectado
					   end as documento,
					   b.expe_asunto AS asunto,
					   b.expe_firma AS firma,
                                           d.depe_abreviado AS un_organica,
                                           b.expe_emailorigen
				FROM expediente_main a
                                LEFT JOIN expediente b      ON a.exma_id=b.exma_id
				LEFT JOIN tipo_expediente c ON b.texp_id=c.texp_id
                                left join dependencia d     ON b.depe_id=d.depe_id
				WHERE a.exma_id=$this->_idExpdte";

		$this->_rsExpdte=$db->sql_query($this->_sqlTramite);
		$this->_rowExpdte = $db->sql_fetchrow($this->_rsExpediente);
	} // function


	function muestra_expdte()
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
			   "0"  => array("campo"=>"registro"	,           "obj"=>"<a href=\"#\" onClick=\"AbreMyVentana('../reports/tramitereporte.php?_expe_id=MyValue','Tramite',800,700)\">MyValue</a>", "width"=>"7%", "color"=>""),
			   "1"  => array("campo"=>"fecha"	,           "obj"=>"", "width"=>"7%", "color"=>""),
			   "2"  => array("campo"=>"documento"	,           "obj"=>"", "width"=>"30%", "color"=>""),
			   "3"  => array("campo"=>"asunto"	,           "obj"=>"", "width"=>"25%", "color"=>""),
			   "4"  => array("campo"=>"firma"	,           "obj"=>"", "width"=>"26%", "color"=>""),
			   "5"  => array("campo"=>"un_organica"	,           "obj"=>"", "width"=>"5%", "color"=>""),
			   "6"  => array("campo"=>"___oper_procesado"	,   "obj"=>"", "width"=>"0%", "color"=>""),
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
	
} // end of class

?>