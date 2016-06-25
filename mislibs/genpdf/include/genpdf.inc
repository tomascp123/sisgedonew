<?php
//--------------------------------------------------------------------
// PHP GenPDF Class
//
// Copyright Jeff Redding, 2004, All Rights Reserved.
//
// This class creates a block/field entry model for creating PDF reports from various 
// data.
//
// Version: $Id: GenPDF.inc,v 1.2 2004/06/03 18:42:40 jwr Exp $
//
//--------------------------------------------------------------------
define('FPDF_FONTPATH', 'font/');
require('fpdf.php');

class PDF extends FPDF
{
	var $field_defs = array();
	var $font_defs = array();
	var $maxYoff = 0;
	var $blockPosX = 0;
	var $blockPosY = 0;
	var $blockHeight = 0;
	var $lineHeight = 4;
	
	var $currFont = 'default';
	
	var $title = "Default Title for GenPrint";
	var $subTitle = "";
	
	var $maxWidth = 275;	// Landscape: 11in * 25mm 
	//var $maxWidth = 212;	// Portrait: 8.5in * 25mm
	var $maxHeight = 185;   // Kind of a kludgy number...

	function __construct($orientation='P',$unit='mm',$format='A4') // Función agregada por Luis Guevara, para poder configurar papeles en tamaños personalizados
	{
		parent::__construct($orientation,$unit,$format);	// Llamo a la función constructora de la clase Padre.
	}
	
	function setMaxWidth($width)
	{
		$this->maxWidth = $width;
	}
	function setMaxHeight($height)
	{
		$this->maxHeight = $height;
	}
	function setTitle($title)
	{
		$this->title = $title;
	}
	function setSubTitle($subtitle)
	{
		$this->subTitle = $subtitle;
	}
	
	function addField($name, $xoff, $yoff, $width)
	{
		$this->field_defs[$name] = array($xoff, $yoff, $width);
		if($yoff > $this->blockHeight)
			$this->blockHeight = $yoff;
	}
	
	function addFont($name, $font_type, $font_weight, $font_size)
	{
		$this->font_defs[$name] = array($font_type, $font_weight, $font_size);
	}
	
	function printField($text, $field_name="", $font_name="")
	{
		// Set offsets and width based on first field entry, or
		// given field entry.
		if($field_name == "") {
			$field_xoff = $this->field_defs[0][0];
			$field_yoff = $this->field_defs[0][1];
			$field_width = $this->field_defs[0][2];
		} else {
			$field_xoff = $this->field_defs[$field_name][0];
			$field_yoff = $this->field_defs[$field_name][1];
			$field_width = $this->field_defs[$field_name][2];
		}
		
		// Set font information based on first font entry, or given
		// font entry.
		$this->_useFontDef($font_name);

		// Set the field position.			
		$this->SetXY($this->blockPosX + $field_xoff, $this->blockPosY + $field_yoff);

		// Shorten the field however much it needs
		$outText = $this->_cutField($text, $field_width);

		// Output the data
		$this->Cell($field_width, $this->lineHeight, $outText,0);
		
		// Make sure to save the maximum y offset for this page.  This tells us 
		// how long the block is.  We use this to determine where to start the
		// next block.
		
		$t_yoff = $this->GetY();
		if($this->maxYoff < $t_yoff)
			$this->maxYoff = $t_yoff;
			
	}
	
	function beginBlock($title="", $font_name="")
	{
		if(($this->maxYoff + $this->blockHeight) > $this->maxHeight)
		{	
			$this->AddPage();
			$this->maxYoff = $this->GetY();
		}
		$this->blockPosY = $this->maxYoff;

		$this->SetXY($this->blockPosX, $this->blockPosY);
		$this->Ln();
		
		if($title != "") {
			$this->_useFontDef($font_name);
			$this->SetFillColor(240,240,240);
			$this->Cell(0,$this->lineHeight,$title,0,0,'L',1);
			$this->Ln();
		}			
		$this->blockPosY = $this->GetY();
		$this->maxYoff = $this->blockPosY;
	}
	
	function Header()
	{
		$fTime = date("Y-m-d G:i:s", time());
		$subtitle2 ="Generated on $fTime";
		
		$this->blockPosX = $this->GetX();

		if($this->font_defs['header'][0] == "") {
			$this->_setFontDefs();
		}
		$font_type = $this->font_defs['header'][0];
		$font_weight = $this->font_defs['header'][1];
		$font_size = $this->font_defs['header'][2];
	
		$extra_width = 30;
		
		//Calculate width of title and position
		$this->SetFont($font_type, $font_weight, $font_size);
		$w = $this->GetStringWidth($this->title)+ $extra_width;

		$this->SetFont($font_type, $font_weight, $font_size-3);
		if(($this->GetStringWidth($this->subTitle)+ $extra_width) > $w)
			$w = $this->GetStringWidth($this->subTitle)+ $extra_width;

		$this->SetFont($font_type, $font_weight, $font_size-6);
		if(($this->GetStringWidth($subtitle2)+ $extra_width) > $w)
			$w = $this->GetStringWidth($subtitle2)+ $extra_width;

		//Colors of frame, background and text
		$this->SetDrawColor(0,80,180);
		$this->SetFillColor(230,230,230);
		$this->SetTextColor(0,0,0);

		//Thickness of frame (1 mm)
		$this->SetLineWidth(1);

		//Title
		$this->SetX(($this->maxWidth-$w)/2);
		$this->SetFont($font_type, $font_weight, $font_size);
		$this->Cell($w,$this->lineHeight+5,$this->title,"TLR",1,'C',1);
			
		$this->SetX(($this->maxWidth-$w)/2);
		$this->SetFont($font_type, $font_weight, $font_size-3);;
		$this->Cell($w,$this->lineHeight+1,$this->subTitle,"LR",1,'C',1);
			
		$this->SetX(($this->maxWidth-$w)/2);
		$this->SetFont($font_type, $font_weight, $font_size-6);
		$this->Cell($w,$this->lineHeight+4,$subtitle2,"BLR",1,'C',1);

		$this->Ln(10);	
		
		// Save the Y offset.  This is where the first block following the header will appear.
		
		$this->maxYoff = $this->GetY();
		$this->_resetFontDef();
	}
	
	function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-15);

		if($this->font_defs['footer'][0] == "") {
			$this->_setFontDefs();
		}
		$font_type = $this->font_defs['footer'][0];
		$font_weight = $this->font_defs['footer'][1];
		$font_size = $this->font_defs['footer'][2];
		
		$this->SetFont($font_type, $font_weight, $font_size);
		$this->SetTextColor(128);
		
		//Page number
		$this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
		$this->_resetFontDef();
	}
	
	function _setFontDefs()
	{
		if($this->font_defs['default'][0] == "")
			$this->font_defs['default'] = array('Arial', '', 10);
			
		if($this->font_defs['header'][0] == "")
			$this->font_defs['header'] = array('Arial', 'B', 15);
			
		if($this->font_defs['footer'][0] == "")
			$this->font_defs['footer'] = array('Arial', 'I', 8);
	}
	
	function _resetFontDef()
	{
		$this->_useFontDef($curr_font);
	}
	
	function _useFontDef($font_name)
	{
		// Set font information based on first font entry, or given
		// font entry.
		if($font_name == "") {
			$this->curr_font = 'default';
			if($this->font_defs['default'][0] == "") {
				$this->_setFontDefs();
			}
		} else {
			$this->curr_font = $font_name;
		}
		$font_type = $this->font_defs[$this->curr_font][0];
		$font_weight = $this->font_defs[$this->curr_font][1];
		$font_size = $this->font_defs[$this->curr_font][2];
		$this->SetFont($font_type, $font_weight, $font_size);
	}
			 
	function _cutField($text, $max_width, $desde=0)
	{
		$tText = $text;
		$twidth = $this->GetStringWidth($tText);

		while($twidth > $max_width) {
			$tText = substr($tText, $desde, strlen($tText)-1);
			$twidth = $this->GetStringWidth($tText);
			$desde=0;
		}
		return $tText;
	}

} // End class

?>
