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
		$this->CampoGrupo1='diasxvencer'; // Voy a tener el Grupo 1 agrupado por el campo repe_descripcion

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
		$this->Cell(15,$this->lineHeight+1,'Clasif.',1,0,'C',1);		
		$this->Cell(40,$this->lineHeight+1,'Documento',1,0,'C',1);
		$this->Cell(40,$this->lineHeight+1,'Unidad Org.',1,0,'C',1);
		$this->Cell(40,$this->lineHeight+1,'Firma',1,0,'C',1);						
		$this->Cell(60,$this->lineHeight+1,'Asunto',1,0,'C',1);		
		$this->Cell(35,$this->lineHeight+1,'Ubicación Actual',1,0,'C',1);		
	}

	function SeteoCampos(){
		/* Defino los campos que voy a usar en el cuerpo del reporte */
		// Campos que van en en detalle, deben empezar su nombre con 'C'
		$this->addField('C1',  99999,	0,  15);
		$this->addField('C2',  99999,	0,  15);
		$this->addField('C3',  99999,	0,  15);		
		$this->addField('C4',  99999,	0,  40);
		$this->addField('C6',  99999,	0,  40);		
		$this->addField('C7',  99999,	0,  40);		
		$this->addField('C8',  99999,	0,  60);
		$this->addField('C9',  99999,	0,  35);
		
		$this->addField('HG1',   0,	0,	160);

	}

	function TituloGrupo1(){
		global $rs;	
		$this->beginBlock();									
		$this->printField('Vencen en '.$rs->field("diasxvencer").' días', 'HG1','bold',0,'L');
		
	}
	
	function Detalle(){
		global $rs;		
		
		/* Imprimo los campos */			
		$this->printField($rs->field("registro"), 'C1','MyFont','','L');
		$this->printField(dtos($rs->field("expe_fecha")), 'C2','MyFont','','L');
		$this->printField($rs->field("clasif"), 'C3','MyFont','','L');		
		$this->printField($rs->field("documento"), 'C4','MyFont','','L');
		$this->printField($rs->field("expe_folios"), 'C5','MyFont','','L');
		$this->printField($rs->field("dependencia"), 'C6','MyFont','','L');		
		$this->printField($rs->field("expe_firma"), 'C7','MyFont','','L');				
		$this->printField($rs->field("expe_asunto"), 'C8','MyFont','','L');
//		$this->printField($rs->field("expe_asunto"), 'C8','MyFont',0,'J',true);						
		$this->printField($rs->field("depe_actual"), 'C9','MyFont','0','L',true);		
	}

}

/*	recibo los parámetros */
$_titulo = "DOCUMENTOS SEGUN PERIODO DE VENCIMIENTO" ; // Título del reporte
$entidad_id = getParam("tr_entidad");
if ($entidad_id != "") $filtro .= " and e.depe_depende = $entidad_id" ;

/*	establecer conexión con la BD */
$conn = new db();
$conn->open();

$sql="SELECT b.expe_diasatencion,
       b.expe_diasatencion - (now()::date - b.expe_fecha) AS diasxvencer,
       lpad(a.expe_id::TEXT,8,'0') as registro,
       b.expe_fecha,
       CASE 
           WHEN b.expe_clastupa=1 THEN 'Sil.Positivo'
           WHEN b.expe_clastupa=2 THEN 'Sil.Negativo'
           WHEN b.expe_clastupa=3 THEN 'Autommático'       
           WHEN b.expe_clastupa=9 THEN 'Ninguna'           
       END AS clasif,
 	   c.texp_abreviado || ' ' || 
	   lpad(b.expe_numero_doc::TEXT,6,'0') ||
	   b.expe_siglas_doc ||
	   CASE 
	       WHEN b.expe_proyectado!='' then '-' || b.expe_proyectado 
	       WHEN b.expe_proyectado='' then b.expe_proyectado 
	   END as documento,
       b.expe_folios,
	   d.depe_nombre as dependencia,
       b.expe_firma,
       b.expe_asunto,
       e.depe_abreviado || ' / ' || f.depe_abreviado AS depe_actual 
	FROM operacion a
	LEFT JOIN expediente b ON a.expe_id=b.expe_id 
	LEFT JOIN tipo_expediente c on b.texp_id=c.texp_id
	LEFT JOIN dependencia d on b.depe_id=d.depe_id
	LEFT JOIN dependencia e ON a.depe_id=e.depe_id
	LEFT JOIN dependencia f ON e.depe_depende=f.depe_id 	 
	WHERE b.expe_diasatencion>0 AND a.oper_procesado=FALSE AND a.oper_idtope NOT IN (3,4) $filtro
	ORDER BY diasxvencer";

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
$Subtitle=getDbValue("select depe_nombre from dependencia where depe_id=$entidad_id");
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