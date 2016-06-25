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
		$this->CampoGrupo1='local'; // Voy a tener el Grupo 1 agrupado por el campo dependencia
	 	
		/* Establezco mi área de impresión */
		/* Para A4 */ 
		$this->setMaxWidth(210); // Por lo que ancho de A4 son 21cm=210mm
		$this->setMaxHeight(265);  // Por lo que alto de A4 son 29.70=297mm .    (La diferencia entre la altura real del papel y la altura de mi área de impresión, debe ser mínimo 30. Por ejm. 297-265=32)
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
		$this->Cell(60,$this->lineHeight+1,'Empleado',1,0,'C',1);
		$this->Cell(20,$this->lineHeight+1,'Usuario',1,0,'C',1);
		$this->Cell(50,$this->lineHeight+1,'Cargo',1,0,'C',1);
                $this->Cell(50,$this->lineHeight+1,'Unidad',1,0,'C',1);
	}

	function SeteoCampos(){
		/* Defino los campos que voy a usar en el cuerpo del reporte */
		// Campos que van en en detalle, deben empezar su nombre con 'C'
		$this->addField('C1',  99999,	0,  60);
		$this->addField('C2',  99999,	0,  20);
		$this->addField('C3',  99999,	0,  50);		
                $this->addField('C4',  99999,	0,  50);		
		
		$this->addField('HG1',   0,	0,	160);
				
	}

	function TituloGrupo1(){
		global $rs;	
                $this->beginBlock();									                
		$this->printField(_LOCAL_.': '.$rs->field("local"), 'HG1','bold',0,'L');
	}
	
	function Detalle(){
		global $rs;		
		
		/* Imprimo los campos */			
		$this->printField($rs->field("empleado"), 'C1','MyFont','','L');
		$this->printField($rs->field("usua_login"), 'C2','MyFont','','L');		
		$this->printField($rs->field("usua_cargo"), 'C3','MyFont','','L');
                $this->printField($rs->field("unidad"), 'C4','MyFont','','L');
	}
        
	function PieGrupo1(){
		global $rs, $contador;
		$this->beginBlock();
		$this->Line($this->blockPosX, $this->blockPosY,$this->blockPosX+$this->WidthTotalCampos, $this->blockPosY); // Imprimo Línea al final de cada grupo
                $total = number_format($this->functions[CONT_GRUPO1][C2],0,'.',',');
                $this->printField('Total : ' . $total, 'HG1','bold',0,'L');
		$this->beginBlock();
                
	}
}

/*	recibo los parámetros */
$_titulo = "USUARIOS SISGEDO" ; // Título del reporte
$depe_id = getParam("sr_depe_id");
$periodo = getParam("nr_archi_periodo");
if ($depe_id != "") $filtro .= " and a.depe_id = $depe_id" ;
if ($periodo != "") $filtro .= " and a.archi_periodo = $periodo" ;

/*	establecer conexión con la BD */
$conn = new db();
$conn->open();

$sql="SELECT c.depe_nombre AS local,
	a.usua_apellidos || ' '|| a.usua_nombres AS empleado,
        a.usua_login,
        a.usua_cargo,
        b.depe_nombre AS unidad
FROM usuario a 
LEFT JOIN dependencia b ON a.depe_id = b.depe_id 
LEFT JOIN dependencia c ON c.depe_id = b.depe_depende 
ORDER BY local, empleado, unidad ";

/* creo el recordset */ 
$rs = new query($conn, $sql);

if ($rs->numrows()==0){
	wait('');
	alert("No existen datos con los parámetros seleccionados");
}

/* Creo el objeto PDF a partir del REPORTE */
$pdf = new Reporte(); // Por defecto crea en hoja A4

/* Define el título y subtítulo que tendrá el reporte  */ 
$pdf->setTitle($_titulo);
//$Subtitle=getDbValue("select depe_nombre from dependencia where depe_id=$entidad_id");
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