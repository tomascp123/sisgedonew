<?
/* Consideraciones
	1.- Asegurarse que el ancho total de la cabecera sea igual al ancho total de todos los campos detalle
	2.- Si cambio el tamaño de papel debo controlar el área de impresión con 
		$this->setMaxWidth(210); 
		$this->setMaxHeight(270);

*/

/*  Cargo librerias necesarias */
include("../mislibs/common.php"); 
include("../mislibs/genpdf/include/genreporte.php"); /* Librerías para generar el reporte en PDF */ 

class Reporte extends GenReporte
{
	function SeteoPdf(){
		/* Nombre del archivo a generar */
		$this->NameFile='../../docs/reportes/rpt'.rand(1000,1000000).'.pdf';
		
		/* Agrego las fuentes que voy a usar en el reporte */
		$this->addFont('bold', 'Arial', 'B', 8); // Esta fuente la uso para los títulos de los grupos
		$this->addFont('MyFont', 'Arial', '', 7); // Esta fuente la uso para los títulos de los grupos

		/* Seteo o configuro los campos que voy a usar en el reporte*/
	 	$this->SeteoCampos();

		/* Agrego los grupos que voy a tener */
		$this->CampoGrupo1='dependencia'; // Voy a tener el Grupo 1 agrupado por el campo repe_descripcion

		/* Establezco mi área de impresión */
		/* Para A4 */ 
		$this->setMaxWidth(297); // Por lo que ancho de A4 son 21cm=210mm
		$this->setMaxHeight(170);  // Por lo que alto de A4 son 29.70=297mm .    (La diferencia entre la altura real del papel y la altura de mi área de impresión, debe ser mínimo 30. Por ejm. 297-265=32)
								   // Uso solo 265 porque considero mi area de impresión solo para el cuerpo del reporte,  Sin considerar el Head y el footer

		// Establezco mi márgen izquierdo para que el cuerpo del reporte apareza centrado
		$this->SetLeftMargin((($this->maxWidth-$this->WidthTotalCampos)/2));

		// Modo de visualización. (real equivale a  100%)
		$this->SetDisplayMode('real');

		// Creo la primera página
		$this->Open(); 
		$this->AddPage();

	}
	
	function Cabecera(){
		// Aquí imprimo los campos como títulos para el cuerpo del reporte
		$this->SetX($this->blockPosX);
		$this->Cell(15,$this->lineHeight+1,'Registro',1,0,'C',1);
		$this->Cell(15,$this->lineHeight+1,'Fecha',1,0,'C',1);
		$this->Cell(70,$this->lineHeight+1,'Documento',1,0,'C',1);
		$this->Cell(10,$this->lineHeight+1,'Folios',1,0,'C',1);				
		$this->Cell(80,$this->lineHeight+1,'Asunto',1,0,'C',1);		
		$this->Cell(70,$this->lineHeight+1,'Destino',1,0,'C',1);		
	}

	function SeteoCampos(){
		/* Defino los campos que voy a usar en el cuerpo del reporte */
		// Campos que van en en detalle, deben empezar su nombre con 'C'
		$this->addField('C1',  99999,	0,  15);
		$this->addField('C2',  99999,	0,  15);
		$this->addField('C3',  99999,	0,  70);		
		$this->addField('C4',  99999,	0,  10);
		$this->addField('C5',  99999,	0,  80);		
		$this->addField('C6',  99999,	0,  70);		
		
		$this->addField('HG1',   0,	0,  100);

	}

	function TituloGrupo1(){
		global $rs;	
		$this->beginBlock();									
		$this->printField('UNIDAD ORGANICA: '.$rs->field("dependencia"), 'HG1','bold',0,'L');
		
	}
	
	function Detalle(){
		global $rs;		
		
		/* Imprimo los campos */			
		$this->printField($rs->field("expe_id"), 'C1','MyFont','','L');
		$this->printField(dtos($rs->field("expe_fecha_doc")), 'C2','MyFont','','L');
		$this->printField($rs->field("expediente"), 'C3','MyFont','','L');
		$this->printField($rs->field("expe_folios"), 'C4','MyFont','','L');
		$this->printField($rs->field("expe_asunto"), 'C5','MyFont','','L',true);
		$this->printField('  '.$rs->field("depe_destino"), 'C6','MyFont','','L');
	}

	function Summary(){
            $this->Line($this->blockPosX, $this->blockPosY+$this->lasth,$this->blockPosX+$this->WidthTotalCampos, $this->blockPosY+$this->lasth); // Imprimo Línea al final de cada grupo
            $this->beginBlock();
            $this->printField('Total de documentos: '.number_format($this->functions[CONT_TOTAL][C1],0,'.',','), 'HG1','bold',0,'L');
	}
}



/*	recibo los parámetros */
$_titulo = "DOCUMENTOS SEGUN PERIODO DE VENCIMIENTO" ; // Título del reporte
$entidad_id = getParam("tr_entidad");
if ($entidad_id != "") $filtro .= " and e.depe_depende = $entidad_id" ;


$_titulo = 'DOCUMENTOS GENERADOS POR UNIDAD ORGANICA';
$fecha_ini = getParam("Dd_expe_fecha");
$fecha_fin = getParam("Dh_expe_fecha");
$origen = getParam("op_expe_origen");
$entidad_id = getParam("tr_entidad");
$depe_id = getParam("sr_depe_id");
$id_usu = getParam("tr_id_usu");
$expe_firma = getParam("Sx_expe_firma");
$texp_id = getParam("tr_texp_id");
$depeid_destino = getParam("sr_depeid_destino");

if ($origen != 9) $filtro = " and a.expe_origen = $origen" ;
if ($entidad_id != "") $filtro = " and e.depe_depende = $entidad_id" ;
if ($depe_id != "") $filtro .= " and a.idusu_depe = $depe_id " ;
if ($id_usu != "") $filtro .= " and a.id_usu = $id_usu " ;
if ($expe_firma != "") $filtro .= " and a.expe_firma LIKE '%$expe_firma%' " ;
if ($texp_id != "") $filtro .= " and a.texp_id = $texp_id " ;
if ($depeid_destino != "") $filtro .= "and d.oper_depeid_d = $depeid_destino " ;

if ($depe_id != "") $filtro2 = " and depe_id = $depe_id " ;

/*	establecer conexión con la BD */
$conn = new db();
$conn->open();

$sql = "SELECT DISTINCT ON (c.depe_nombre,a.expe_id) lpad(a.expe_id::TEXT,8,'0') as expe_id,
				a.expe_fecha_doc,
				b.texp_descripcion || ' ' || lpad(a.expe_numero_doc::TEXT,6,'0') || '-' || a.expe_siglas_doc as expediente,
				lpad(a.expe_folios::TEXT,3,'0') as expe_folios,
				c.depe_nombre AS dependencia,
				a.expe_asunto,
				f.depe_nombre AS depe_destino  
			 FROM expediente a 
				LEFT JOIN tipo_expediente b on a.texp_id=b.texp_id 
				LEFT JOIN dependencia c ON a.depe_id=c.depe_id 
				LEFT JOIN (SELECT expe_id,oper_depeid_d 
     						FROM operacion 
                			WHERE oper_idtope = 2 $filtro2
                			GROUP BY expe_id,oper_depeid_d) d ON a.expe_id=d.expe_id
				LEFT JOIN dependencia e ON a.idusu_depe=e.depe_id
				LEFT JOIN dependencia f ON f.depe_id = d.oper_depeid_d   								  
			 WHERE a.expe_fecha BETWEEN '$fecha_ini' and '$fecha_fin' $filtro  
			 ORDER BY c.depe_nombre, a.expe_id";

/* creo el recordset */
$rs = new query($conn, $sql);

if ($rs->numrows()==0){
	wait('');
	alert("No existen datos con los parámetros seleccionados");
}

/* Creo el objeto PDF a partir del REPORTE */
$pdf = new Reporte('L'); // Por defecto crea en hoja A4

/* Define el título y subtítulo que tendrá el reporte  */ 
$pdf->setTitle($_titulo);
$Subtitle="Desde: ".$fecha_ini.'  '."Hasta:".$fecha_fin;
$pdf->setSubTitle($Subtitle);

/* Genero el Pdf */
$pdf->GeneraPdf();

/* Cierrro la conexión */
$conn->close();
/* Visualizo el pdf generado*/ 
$pdf->VerPdf();
/* para eliminar la animaciòn WAIT */
wait('');

?>