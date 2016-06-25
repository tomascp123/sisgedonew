<?php
require('fpdf.php');

//$pdf=new FPDF('P','mm',array(20,20));  
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(100,10,'¡Hola, Mundo!','R');
$pdf->Cell(60,10,'Hecho con FPDF.',0,1,'C');
$pdf->Cell(180,10,'AAAA',1);
$pdf->Output();

?> 