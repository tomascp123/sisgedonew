<?php
//--------------------------------------------------------------------------
// GenPDF example
//
// Copyright Jeff Redding, 2004, All Rights Reserved.
//
// This is a simple example of how to use GenPDF.  The code below defines
// the layout of a block, and then displays items into their named fields.
//
// GenPDF keeps blocks from splitting across pages, while making it much
// easier to create a printable output.
//
// GenPDF requires FreePDF which can be found at: http://fpdf.org
//
//--------------------------------------------------------------------------
include "include/GenPDF.inc";

// Create a new PDF object
$pdf = new PDF('L', 'mm', 'Letter');

// Add a couple of fonts
$pdf->addFont('bold', 'Arial', 'B', 10);
$pdf->addFont('bigbold', 'Arial', 'B', 12);

// Begin defining our fields
$pdf->addField('A',	0,	0,	100);
$pdf->addField('F',	0,	5,	100);
$pdf->addField('E',	0,	10,	100);

$pdf->addField('B',	100,	0,	50);
$pdf->addField('C',	100,	5,	50);
$pdf->addField('D',	100,	10,	50);

$pdf->addField('G',	150,	0,	50);
$pdf->addField('I',	150,	5,	50);
$pdf->addField('J',	150,	10,	50);

$pdf->addField('H',	0,	15,	200);

// Define a title and subtitle
$pdf->setTitle("Here is My Main Title");
$pdf->setSubTitle("This is the sub-title");

// Create our first page
$pdf->Open();
$pdf->AddPage();

for($i=0; $i<10; $i++)
{
	// Begin a new block
//	$pdf->beginBlock("Block #$i", 'bigbold');
	$pdf->beginBlock();	
	

	// Display the fields
	
	$pdf->printField('Field1: datadatadatadatadatadatadatadata', 'A');
	$pdf->printField('Field2', 'B', 'bold');
	$pdf->printField('Field3', 'C');
	$pdf->printField('Field4', 'D');
	$pdf->printField('Field5: 1234567890', 'E');
	$pdf->printField('Field6: 12345678901234567890', 'F');
	$pdf->printField('Field7', 'G');
	$pdf->printField('Field8: Here is some more random text.  This is intentionally quite long'.
			' in order to demonstrate how text gets truncated based on field length', 'H');
	$pdf->printField('Field9', 'I');
	$pdf->printField('Field10', 'J', 'bigbold');
}

// Output the finished product
$pdf->Output();

?>
