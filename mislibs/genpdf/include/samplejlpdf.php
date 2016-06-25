<? 
 /* definimos la ruta de las fuentes*/
 define('FPDF_FONTPATH','font/');
 /* Incorporamos la librería */ 
 require('jlpdf.php');
 
 /* Texto que se va a imprimir */ 
 $cadena=  "Un [blue]elefante[black] se columpiaba sobre la tela de una [BIG]araña[normal]. ";
 $cadena.= "Como veía que resistía, fue a llamar a [red]otro elefante[black]. ";
 $cadena.= "[b]Dos elefantes[/b] se columpiaban sobre la [i]tela de una araña[/i]. ";
 $cadena.= "[times]Como veían que resistía, fueron a llamar a otro elefante.";
  
 /* Generamos una instancia para comenzar a utilizarla */
 $pdf=new JLPDF();
 $pdf->AddPage();
 $pdf->SetFont('Arial','',12);

 /* Nos posicionamos en la posició 0,10 */
 $pdf->SetX(0);
 $pdf->SetY(10);
 /* Texto en una columna  de 100 puntos de ancho, justificada */
 $pdf->JLCell("$cadena",50,'j');

 $pdf->Output();
?>