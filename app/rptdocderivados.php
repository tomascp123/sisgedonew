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
		$this->CampoGrupo1='destino'; // Voy a tener el Grupo 1 agrupado por el campo dependencia
	 	
		/* Establezco mi área de impresión */
		/* Para A4 */ 
		$this->setMaxWidth(297); // Por lo que ancho de A4 son 21cm=210mm
		$this->setMaxHeight(180);  // Por lo que alto de A4 son 29.70=297mm .    (La diferencia entre la altura real del papel y la altura de mi área de impresión, debe ser mínimo 30. Por ejm. 297-265=32)
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

		$this->Cell(15,$this->lineHeight+1,'','LTR',0,'C',1);
                $this->Cell(15,$this->lineHeight+1,'','LTR',0,'C',1);
                $this->Cell(40,$this->lineHeight+1,'','LTR',0,'C',1);
                $this->Cell(65,$this->lineHeight+1,'','LTR',0,'C',1);
                $this->Cell(40,$this->lineHeight+1,'','LTR',0,'C',1);                
		$this->Cell(90,$this->lineHeight+1,'Estado Final','LTR',1,'C',1);
                
		$this->Cell(15,$this->lineHeight+1,'Registro','LBR',0,'C',1);
		$this->Cell(15,$this->lineHeight+1,'fecha','LBR',0,'C',1);
                $this->Cell(40,$this->lineHeight+1,'Unidad','LBR',0,'C',1);
                $this->Cell(65,$this->lineHeight+1,'Asunto','LBR',0,'C',1);
		$this->Cell(40,$this->lineHeight+1,'Primer Proveido','LBR',0,'C',1);                
                $this->Cell(25,$this->lineHeight+1,'Usuario',1,0,'C',1);
                $this->Cell(65,$this->lineHeight+1,'Acciones',1,0,'C',1);
	}

	function SeteoCampos(){
		/* Defino los campos que voy a usar en el cuerpo del reporte */
		// Campos que van en en detalle, deben empezar su nombre con 'C'
		$this->addField('C1',  99999,	0,  15);
                $this->addField('C2',  99999,	0,  15);
                $this->addField('C3',  99999,	0,  40);		
                $this->addField('C4',  99999,	0,  65);		
                $this->addField('C5',  99999,	0,  40);
                $this->addField('C6',  99999,	0,  25);		
                $this->addField('C7',  99999,	0,  65);		
		
		$this->addField('HG1',   0,	0,	160);
				
	}

	function TituloGrupo1(){
		global $rs;	
                $this->beginBlock();									                
		$this->printField('Oficina destino: '.$rs->field("destino"), 'HG1','bold',0,'L');
	}
	
	function Detalle(){
		global $rs;		
		
		/* Imprimo los campos */			
		$this->printField($rs->field("expe_id"), 'C1','MyFont','','L');
		$this->printField($rs->field("expe_fecha_doc"), 'C2','MyFont','','R');
                $this->printField($rs->field("dependencia"), 'C3','MyFont','','L');
                $this->printField(' '.$rs->field("expe_asunto"), 'C4','MyFont','','L');
                $this->printField($rs->field("primer_proveido"), 'C5','MyFont','','L');		                
                $this->printField(' '.$rs->field("ultimo_usuario"), 'C6','MyFont','','L');
                $this->printField($rs->field("ultimas_acciones"), 'C7','MyFont','','L');
                
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
$_titulo = "DOCUMENTOS DERIVADOS" ; // Título del reporte
$depe_id = getParam("sr_depe_id");
$periodo = getParam("nr_archi_periodo");

$dFechadesde = $_POST['Dd_expe_fecha'];
$dFechahasta = $_POST['Dh_expe_fecha'];
$filtro .= $_POST['Sx_hora_desde']==''?'':" and d.oper_hora >= '".$_POST['Sx_hora_desde']."'";
$filtro .= $_POST['Sx_hora_hasta']==''?'':" and d.oper_hora <= '".$_POST['Sx_hora_hasta']."'";
$filtro .= $_POST['sr_depe_id']==''?'':" and d.depe_id=".$_POST['sr_depe_id'];
$filtro .= $_POST['tr_id_usu']==''?'':" and a.id_usu=".$_POST['tr_id_usu'];
$filtro .= $_POST['tr_id_usu']==''?'':" and d.id_usu=".$_POST['tr_id_usu'];  /* id_usu de operacion */
$filtro .= $_POST['Sx_expe_firma']==''?'':" and a.expe_firma LIKE '%".$_POST['Sx_expe_firma']."%'";
$filtro .= $_POST['tr_texp_id']==''?'':" and a.texp_id=".$_POST['tr_texp_id'];
$filtro .= $_POST['sr_depeid_destino']==''?'':" and d.oper_depeid_d=".$_POST['sr_depeid_destino'];
$filtro .= $_POST['Sx_expe_depe_detalle']==''?'':" and a.expe_depe_detalle LIKE '%".$_POST['Sx_expe_depe_detalle']."%'";


/*	establecer conexión con la BD */
$conn = new db();
$conn->open();

/*
 * OJO: Para el correcto funcionamiento de este reporte siempre debe elegirse la oficina origen.
 */
$sql="select distinct lpad(a.expe_id::TEXT, 8, '0') as expe_id,
       b.texp_descripcion || ' ' || lpad(a.expe_numero_doc::TEXT, 6, '0') || '-'
        || a.expe_siglas_doc as expediente,
       to_char(a.expe_fecha_doc, 'dd/mm/yyyy') AS expe_fecha_doc,
       c.depe_nombre as dependencia,
       a.expe_asunto,
       e.depe_nombre as destino,
       g.usua_login AS ultimo_usuario,
       f.oper_acciones AS ultimas_acciones, 
       h.oper_acciones AS primer_proveido 
from expediente a
     left join tipo_expediente b on a.texp_id = b.texp_id
     left join dependencia c on a.depe_id = c.depe_id
     left join operacion d on a.expe_id = d.expe_id
     left join dependencia e on e.depe_id = d.oper_depeid_d
     LEFT JOIN (
      SELECT DISTINCT ON (expe_id) expe_id,
      		id_usu,
            oper_acciones
      FROM operacion
     ) f ON f.expe_id = a.expe_id 
     LEFT JOIN usuario g ON g.id_usu = f.id_usu 
     LEFT JOIN (
          SELECT DISTINCT ON (expe_id) expe_id,oper_acciones
          FROM operacion a
          where oper_fecha BETWEEN '$dFechadesde' and
          '$dFechahasta' and
          oper_idtope = 2 and depe_id = $depe_id
     ) h ON h.expe_id = a.expe_id 
where d.oper_fecha BETWEEN '$dFechadesde' and
      '$dFechahasta' and
      d.oper_idtope = 2 $filtro 
order by destino,
         1
 ";


/* creo el recordset */
$rs = new query($conn, $sql);

if ($rs->numrows()==0){
	wait('');
	alert("No existen datos con los parámetros seleccionados");
}

/* Creo el objeto PDF a partir del REPORTE */
$pdf = new Reporte("L"); // Por defecto crea en hoja A4

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