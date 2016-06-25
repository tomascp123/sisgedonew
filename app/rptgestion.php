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
//		$this->CampoGrupo1='destino'; // Voy a tener el Grupo 1 agrupado por el campo dependencia
	 	
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
                global $local;
            
		// Aquí imprimo los campos como títulos para el cuerpo del reporte
		$this->SetX($this->blockPosX);

                $this->Cell(30,$this->lineHeight,$local,0,1,'L',0);                
                $this->Cell(60,$this->lineHeight+1,'','LTR',0,'C',1);
		$this->Cell(60,$this->lineHeight+1,'Documentos atendidos - Tiempo de atención','LTR',0,'C',1);
                $this->Cell(30,$this->lineHeight+1,'','LTR',1,'C',1);
                
                $this->Cell(60,$this->lineHeight+1,'Unidad Orgánica','LBR',0,'C',1);
                $this->Cell(15,$this->lineHeight+1,'10 días',1,0,'C',1);
                $this->Cell(15,$this->lineHeight+1,'20 días',1,0,'C',1);
                $this->Cell(15,$this->lineHeight+1,'30 días',1,0,'C',1);
                $this->Cell(15,$this->lineHeight+1,'+ 30 días',1,0,'C',1);
                $this->Cell(30,$this->lineHeight+1,'Total','LBR',0,'C',1);
	}

	function SeteoCampos(){
		/* Defino los campos que voy a usar en el cuerpo del reporte */
		// Campos que van en en detalle, deben empezar su nombre con 'C'
		$this->addField('C1',  99999,	0,  60);
                $this->addField('N1',  99999,	0,  15);
                $this->addField('N2',  99999,	0,  15);		
                $this->addField('N3',  99999,	0,  15);		
                $this->addField('N4',  99999,	0,  15);
                $this->addField('N5',  99999,	0,  30);		
		
		$this->addField('HG1',   0,	0,	160);
		
                
                
	}

	function Detalle(){
		global $rs;		
		
		/* Imprimo los campos */	
		$this->printField($rs->field("oficina"), 'C1','MyFont','','L');
                $this->printField($rs->field("total_10"), 'N1','MyFont','','R');
                $this->printField($rs->field("total_20"), 'N2','MyFont','','R');
                $this->printField($rs->field("total_30"), 'N3','MyFont','','R');
                $this->printField($rs->field("total_masde30"), 'N4','MyFont','','R');
                $this->printField($rs->field("total_atendidos"), 'N5','MyFont','','R');
                
	}
        
	function Summary(){
		global $rs, $contador;
		$this->beginBlock();
		$this->Line($this->blockPosX, $this->blockPosY,$this->blockPosX+$this->WidthTotalCampos, $this->blockPosY); // Imprimo Línea al final de cada grupo
                $this->printField(number_format($this->functions[SUMA_TOTAL][N1],0,'.',','), 'N1','bold',0,'R');
                $this->printField(number_format($this->functions[SUMA_TOTAL][N2],0,'.',','), 'N2','bold',0,'R');
                $this->printField(number_format($this->functions[SUMA_TOTAL][N3],0,'.',','), 'N3','bold',0,'R');
                $this->printField(number_format($this->functions[SUMA_TOTAL][N4],0,'.',','), 'N4','bold',0,'R');
                $this->printField(number_format($this->functions[SUMA_TOTAL][N5],0,'.',','), 'N5','bold',0,'R');

		$this->beginBlock();
                
	}
}

/*	recibo los parámetros */
$_titulo = "INFORME DE GESTION DOCUMENTARIA" ; // Título del reporte

$dFechadesde = $_POST['Dd_expe_fecha'];
$dFechahasta = $_POST['Dh_expe_fecha'];
$entidad_id = getParam("tr_entidad");

/*	establecer conexión con la BD */
$conn = new db();
$conn->open();

$local = getDbValue("select depe_nombre from dependencia where depe_id = $entidad_id");

$sql="SELECT a.depe_id,
		a.oficina,
      SUM(total_10) AS total_10,
      SUM(total_20) AS total_20,
      SUM(total_30) AS total_30,
      SUM(total_masde30) AS total_masde30,
      SUM(total_atendidos) AS total_atendidos 
FROM (
    SELECT a.depe_id, 
    		a.oficina,
           CASE 
            WHEN a.dias_archivarse < 11 THEN count(a.dias_archivarse) 
            ELSE 0
           END AS total_10,
           CASE 
            WHEN a.dias_archivarse > 10 AND a.dias_archivarse < 21 THEN count(a.dias_archivarse) 
            ELSE 0
           END AS total_20,
           CASE 
            WHEN a.dias_archivarse > 20 AND a.dias_archivarse < 31 THEN count(a.dias_archivarse) 
            ELSE 0
           END AS total_30,
           CASE 
            WHEN a.dias_archivarse > 30 THEN count(a.dias_archivarse) 
            ELSE 0
           END AS total_masde30,
           count(a.dias_archivarse) AS total_atendidos 
    FROM (
        SELECT a.expe_id,
               d.oper_fecha - f.oper_fecha AS dias_archivarse,
               d.depe_id,
               e.depe_nombre as oficina
        FROM expediente a
             LEFT JOIN operacion d on a.expe_id = d.expe_id
             LEFT JOIN dependencia e on d.depe_id = e.depe_id
             LEFT JOIN operacion f ON f.oper_id = d.oper_idprocesado
        WHERE (d.oper_idtope = 3 or
              d.oper_idtope = 4) and
              e.depe_depende = $entidad_id and
              d.oper_fecha BETWEEN '$dFechadesde' and
              '$dFechahasta' 
    ) AS a 
    GROUP BY a.depe_id, 
    		 a.oficina,
    		 a.dias_archivarse
) AS a
GROUP BY a.depe_id,
         a.oficina
ORDER BY total_atendidos DESC";



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
$Subtitle="Desde: $dFechadesde Hasta: $dFechahasta ";
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