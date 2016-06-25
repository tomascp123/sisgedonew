<?php
//--------------------------------------------------------------------
// PHP GenReporte Class
//--------------------------------------------------------------------
require('genpdf.inc');

class GenReporte extends PDF
{
	var $NameFile = "";	// Nombre del archivo PDF que se generará.  Este nombre puede incluir la ruta del archivo.  Ejm.  "../../docs/reporte.pdf"

	var $WidthTotalCampos = 0;	// Aquí se guardará el ancho total de los campos acumulados del cuerpo del reporte
	var $nyIniciaDetalle=0;	 // Para guardar la posición "Y" donde se inicia la impresión del detalle del reporte
	
	var $HeadLeft = "";	// Aquí se guardará lo que deseamos que se imprima en la parte izquierda de la cabecera
	var $footLeft = "";	// Aquí se guardará lo que deseamos que se imprima en la parte izquierda del pie de página
	var $footRight = ""; // Aquí se guardará lo que deseamos que se imprima en la parte derecha del pie de página	
	var $nCuentaRegistro; // Contador general de registros de todo el reporte

	var $CampoGrupo1; // Campo que determina el Grupo1.  
	var $DatoGrupo1; // Variable donde se guardará el Dato del campo del Grupo 1 
	var $nCtaRegistroGrupo1; // Contador de registros del grupo 1.  
	var $Grupo1NewPage; // Variable que determina si cada grupo se imprime en una nueva página.  Para activarlo asignar el valor 1  	

	var $CampoGrupo2; // Campo que determina el Grupo2.  
	var $DatoGrupo2; // Variable donde se guardará el Dato del campo del Grupo 2 
	var $nCtaRegistroGrupo2; // Contador de registros del grupo 2.  
	var $Grupo2NewPage; // Variable que determina si cada grupo se imprime en una nueva página.  Para activarlo asignar el valor 1  	

	var $CampoGrupo3; // Campo que determina el Grupo3.  
	var $DatoGrupo3; // Variable donde se guardará el Dato del campo del Grupo 3 
	var $nCtaRegistroGrupo3; // Contador de registros del grupo 3.  
	var $Grupo3NewPage; // Variable que determina si cada grupo se imprime en una nueva página.  Para activarlo asignar el valor 1  	

	var $CampoGrupo4; // Campo que determina el Grupo4.  
	var $DatoGrupo4; // Variable donde se guardará el Dato del campo del Grupo 4 
	var $nCtaRegistroGrupo4; // Contador de registros del grupo 4.  

	var $CampoGrupo5; // Campo que determina el Grupo5.  
	var $DatoGrupo5; // Variable donde se guardará el Dato del campo del Grupo 5 
	var $nCtaRegistroGrupo5; // Contador de registros del grupo 5.  

	var $nlnCabecera=3; //numero de lineas en blanco q imprimirá despues de escribir el sub titulo
	var $PosYIniciaTitulo=15; // Posición de Y para que inicie la impresión del título 
	
	var $funcion=array(''); //inicializa el array de operaciones arimeticas
	
	function __construct($orientation='P',$unit='mm',$format='A4')
	{
		parent::__construct($orientation,$unit,$format);	// Llamo a la función constructora de la clase Padre.
		$this->_FuncJavascript(); // Cargo en memoria las funciones javascript que voy a necesitar.
		
		// Configuro datos de Cabecera y pie de página
		$this->footLeft=SIS_PIELEFT_REPORTE;	
		$this->HeadLeft=SIS_EMPRESA;
		$this->footRight=SIS_VERSION;	
	}
	
	function addField($name, $xoff, $yoff, $width)
	{
		$xoff=($xoff==99999)?$this->WidthTotalCampos:$xoff;
		parent::addField($name, $xoff, $yoff, $width);
		if(substr($name,0,1)=='C' || substr($name,0,1)=='N'){ // Solo considero WidthTotalCampos a los campos que van en el Detalle (empiezan con C)
			$this->WidthTotalCampos=$this->WidthTotalCampos + $width;
	

			//inicia el array de calculo
			$this->functions['CONT_GRUPO1'][$name]=0;	
			$this->functions['CONT_GRUPO2'][$name]=0;				
			$this->functions['CONT_GRUPO3'][$name]=0;				
			$this->functions['CONT_GRUPO4'][$name]=0;				
			$this->functions['CONT_GRUPO5'][$name]=0;
			$this->functions['CONT_TOTAL'][$name]=0;	
			
			if(substr($name,0,1)=='N'){
				$this->functions['SUMA_GRUPO1'][$name]=0;	
				$this->functions['SUMA_GRUPO2'][$name]=0;	
				$this->functions['SUMA_GRUPO3'][$name]=0;					
				$this->functions['SUMA_GRUPO4'][$name]=0;
				$this->functions['SUMA_GRUPO5'][$name]=0;
				$this->functions['SUMA_TOTAL'][$name]=0;	
			}
						
		}

	}

	function Header()
	{
		$this->blockPosX = $this->GetX();

		$this->SetFontHeadFooter(); // Seteo el font para head y footer

		// Imprimo la parte izquierda de la cabecera
		$this->SetXY(10,5);
		$WidthHead=($this->maxWidth-20)/2; // Determino el ancho que deben tener las líneas que van en el Head y en el footer, el 20 es porque dejo un espacio de 10 a cada lado de la línea
		$this->Cell($WidthHead,3,$this->HeadLeft,'B',0,'L');
		// Imprimo el número de página de cuantas páginas tenga el reporte
		$this->AliasNbPages(); // Para poder obtener el número de páginas
		$this->Cell($WidthHead,3,'Página '.$this->PageNo().' de {nb}','B',1,'R');		

		// Imprimo la fecha
		$this->SetFont('Arial', '', 7);
		$this->Ln(1);	
		$this->SetX(($this->maxWidth-45));
		$this->Cell(20,3,'Fecha:',0,0,'R');
		$this->Cell(15,3,date("d/m/Y"),0,1,'L');		

		// Imprimo la hora
		$this->SetX(($this->maxWidth-45));
		$this->Cell(20,3,'Hora:',0,0,'R');
		$this->Cell(15,3,date("H:i:s"),0,1,'L');		

		// Ancho de la línea borde de cualquier celda
		$this->SetLineWidth(0.3);
		//Colors of frame, background and text
		$this->SetFillColor(230,230,230);
		$this->SetTextColor(0,0,0);

		$this->title(); // Imprimo el título

		$this->SetFont('Arial', 'B', 8);
		$this->Cabecera(); // Imprimo la cabecera (los títulos de los campos)

		$this->nyIniciaDetalle = $this->GetY()+$this->lasth; // Guardo la posición "Y" donde empiezo a imprimir el detalle
		
		// Save the Y offset.  This is where the first block following the header will appear.
		$this->maxYoff = $this->GetY();
		$this->_resetFontDef();
	}

	function title(){

		if($this->font_defs['header'][0] == "") {
			$this->_setFontDefs();
		}
		$font_type = $this->font_defs['header'][0];
		$font_weight = $this->font_defs['header'][1];
		$font_size = $this->font_defs['header'][2];
	
		$extra_width = 20;
		
		//Calculate width of title and position
		$this->SetFont($font_type, $font_weight, $font_size);
		$w = $this->GetStringWidth($this->title)+ $extra_width;

		$this->SetFont($font_type, $font_weight, $font_size-3);
		if(($this->GetStringWidth($this->subTitle)+ $extra_width) > $w)
			$w = $this->GetStringWidth($this->subTitle)+ $extra_width;

		//Title
		if($w>$this->maxWidth)		
			$w=$this->maxWidth;
		
		$this->SetY($this->PosYIniciaTitulo);
		$this->SetX(($this->maxWidth-$w)/2);
		$this->SetFont($font_type, $font_weight, $font_size-2);
		$this->Cell($w,$this->lineHeight,$this->title,0,1,'C');

		if($this->subTitle){
			// Subtítulo	
			$this->SetX(($this->maxWidth-$w)/2);
			$this->SetFont($font_type, $font_weight, $font_size-4);;
			$this->Cell($w,$this->lineHeight-2,$this->subTitle,0,1,'C');
		}			

		$this->Ln($this->nlnCabecera);	
	}

	function Footer()
	{
		//Position at 1.5 cm from bottom
//		$this->SetY(-15);
//		$this->SetX($this->blockPosX);
//		$this->SetXY(10,-15);
		$this->SetXY(10,-5);
		$this->SetFontHeadFooter();

		$WidthHead=($this->maxWidth-20)/2; // Determino el ancho que deben tener las líneas que van en el Head y en el footer

		$this->Cell($WidthHead,4,$this->footLeft,'T',0,'L');
		$this->Cell($WidthHead,4,$this->footRight,'T',1,'R');		
		
		$this->_resetFontDef();
	}

	function SetFontHeadFooter()
	{
		if($this->font_defs['footer'][0] == "") {
			$this->_setFontDefs();
		}
		$font_type = $this->font_defs['footer'][0];
		$font_weight = $this->font_defs['footer'][1];
		$font_size = $this->font_defs['footer'][2];
		
		$this->SetFont($font_type, $font_weight, $font_size);
		$this->SetTextColor(128);
	}

	function _setFontDefs()
	{
		if($this->font_defs['default'][0] == "")
			$this->font_defs['default'] = array('Arial', '', 8);
			
		if($this->font_defs['header'][0] == "")
			$this->font_defs['header'] = array('Arial', 'B', 12);
			
		if($this->font_defs['footer'][0] == "")
			$this->font_defs['footer'] = array('Arial', 'I', 7);
	}

	function _FuncJavascript()
	{
		?>
		<script language="JavaScript">
		//	funcion para abrir la ventana popup para mostrar el reporte	
		function AbreVentana(sURL) {
			var w=800, h=600;
			venrepo=window.open(sURL,'rptSalida', "status=yes,resizable=yes,toolbar=no,scrollbars=yes,top=0,left=0,width=" + w + ",height=" + h, 1 );
			venrepo.focus();
		}
		</script><? 
	}
	
	function Cabecera(){
	// Aquí se imprimirá todos los campos que van como cabecera, esto se configura en cada reporte
	}

	function printField($valor, $field_name="", $font_name="", $border="", $align="", $flotante=false)
	{
		/*
 		$valor       --> valor a ser mostrado
		$field_name --> Nombre del campo donde se mostrará $valor
		$font_name  --> Fuente con la que se mostrará $valor 
		$border     --> Borde del campo, usar los mismos valores de $pdf->cell()
		$align      --> Alineación del texto en el campo, usar los mismos valores de $pdf->cell()
		*/

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
		if($flotante){
			$outText = $valor;
			$twidth = $this->GetStringWidth($outText);			
			if($twidth>$field_width){
				$this->MultiCell($field_width, $this->lineHeight, $outText, $border, $align);
				$this->SetY($this->GetY()-$this->lineHeight); /* Regreso una fila, para que no se quede una fila en blanco al terminar de imprimir la celda, ya que el método Multicell deja esta fila en blanco */
			}else
				$this->Cell($field_width, $this->lineHeight, $outText, $border, 0, $align);
		}else{
			$outText = $this->_cutField($valor, $field_width);
			$this->Cell($field_width, $this->lineHeight, $outText, $border, 0, $align);
		}

		//realiza operaciones de cálculo
		if(substr($field_name,0,1)=='C' || substr($field_name,0,1)=='N'){ // Solo considero WidthTotalCampos a los campos que van en el Detalle (empiezan con C)
			$this->functions['CONT_GRUPO1'][$field_name]++;	
			$this->functions['CONT_GRUPO2'][$field_name]++;
			$this->functions['CONT_GRUPO3'][$field_name]++;
			$this->functions['CONT_GRUPO4'][$field_name]++;			
			$this->functions['CONT_GRUPO5'][$field_name]++;			
			$this->functions['CONT_TOTAL'][$field_name]++;	
			
			if(substr($field_name,0,1)=='N'){
				$this->functions['SUMA_GRUPO1'][$field_name]+=str_replace(',','',$valor);
				$this->functions['SUMA_GRUPO2'][$field_name]+=str_replace(',','',$valor);
				$this->functions['SUMA_GRUPO3'][$field_name]+=str_replace(',','',$valor);
				$this->functions['SUMA_GRUPO4'][$field_name]+=str_replace(',','',$valor);				
				$this->functions['SUMA_GRUPO5'][$field_name]+=str_replace(',','',$valor);								
				$this->functions['SUMA_TOTAL'][$field_name]+=str_replace(',','',$valor);	
			}
						
		}

		// Make sure to save the maximum y offset for this page.  This tells us 
		// how long the block is.  We use this to determine where to start the
		// next block.
		$t_yoff = $this->GetY();
		if($this->maxYoff < $t_yoff)
			$this->maxYoff = $t_yoff;
			
	}

	function VerPdf()
	{
		/* Genero nombre aleatorio */
		if(!$this->NameFile) /* Si no existe nombre del archivo a generar */
			$this->NameFile='../../docs/reportes/rpt'.rand(1000,1000000).'.pdf';

		/* Muestro el PDF final */
		//$pdf->Output(); // Esto abre directamente el archivo generado pero tiene problemas a veces con el EXPLORER, eso sí, funciona bien en Firefox
		$this->Output($this->NameFile);
		//header("Location: usuario.pdf");
		AbreVentana($this->NameFile,'');
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
			$this->Cell(0,$this->lineHeight,$title,0,0,'L',0);
			$this->Ln();
		}			
		
		$this->blockPosY = $this->GetY();
		$this->maxYoff = $this->blockPosY;
	}

	function GeneraPdf(){
		/* Seteo el PDF */
		$this->SeteoPdf();

		/*** Detalle del Reporte ***/ 
		$this->ImprimeDetalle();

		/*** Summary del Reporte ***/ 
		$this->Summary();
	}

	function IniciaFila(){
		global $rs;
		if($this->DatoGrupo1!=$rs->field($this->CampoGrupo1)){ // Si no estoy en el mismo grupo
			/* Reinicio las variables de grupo */ 
			$this->ReiniciaVariables(1); // Llamo a función para reinicar variables al cambiar de grupo.  Esta función debe manejarse en cada reporte según lo que se necesite				
			/* Cambio de grupo */			
			$this->CambiaGrupo(1); // Imprimo los campos que necesito en el título del grupo 1
		}
		if($this->DatoGrupo2!=$rs->field($this->CampoGrupo2)){ // Si no estoy en el mismo grupo
			/* Reinicio las variables de grupo */ 
			$this->ReiniciaVariables(2); // Llamo a función para reinicar variables al cambiar de grupo.  Esta función debe manejarse en cada reporte según lo que se necesite				
			/* Cambio de grupo */			
			$this->CambiaGrupo(2); // Imprimo los campos que necesito en el título del grupo 1
		}
		if($this->DatoGrupo3!=$rs->field($this->CampoGrupo3)){ // Si no estoy en el mismo grupo
			/* Reinicio las variables de grupo */ 
			$this->ReiniciaVariables(3); // Llamo a función para reinicar variables al cambiar de grupo.  Esta función debe manejarse en cada reporte según lo que se necesite				
			/* Cambio de grupo */			
			$this->CambiaGrupo(3); // Imprimo los campos que necesito en el título del grupo 1
		}
		if($this->DatoGrupo4!=$rs->field($this->CampoGrupo4)){ // Si no estoy en el mismo grupo
			/* Reinicio las variables de grupo */ 
			$this->ReiniciaVariables(4); // Llamo a función para reinicar variables al cambiar de grupo.  Esta función debe manejarse en cada reporte según lo que se necesite				
			/* Cambio de grupo */			
			$this->CambiaGrupo(4); // Imprimo los campos que necesito en el título del grupo 1
		}
		if($this->DatoGrupo5!=$rs->field($this->CampoGrupo5)){ // Si no estoy en el mismo grupo
			/* Reinicio las variables de grupo */ 
			$this->ReiniciaVariables(5); // Llamo a función para reinicar variables al cambiar de grupo.  Esta función debe manejarse en cada reporte según lo que se necesite				
			/* Cambio de grupo */			
			$this->CambiaGrupo(5); // Imprimo los campos que necesito en el título del grupo 1
		}

	}


	function ReiniciaVariables($Grupo){
		// Aquí se reinician las variables por defecto creadas por la clase 
		switch ($Grupo)
		{
			case 1: // Grupo 1 
				$this->nCtaRegistroGrupo1=0; // Al cambiar el grupo inicializo el contador correspondiente del grupo

				$this->nCtaRegistroGrupo2=0; // También debo inicializar el contador del grupo 2 
				$this->nCtaRegistroGrupo3=0; // También debo inicializar el contador del grupo 3 
				$this->nCtaRegistroGrupo4=0; // También debo inicializar el contador del grupo 3 
				$this->ReiniciaVariables(2); // También Llamo a la función para reinicar variables del grupo 2.  Esta función debe manejarse en cada reporte según lo que se necesite				
				$this->ReiniciaVariables(3); // También Llamo a la función para reinicar variables del grupo 3.  Esta función debe manejarse en cada reporte según lo que se necesite				
				$this->ReiniciaVariables(4); // También Llamo a la función para reinicar variables del grupo 4.  Esta función debe manejarse en cada reporte según lo que se necesite
				$this->ReiniciaVariables(5); // También Llamo a la función para reinicar variables del grupo 5.  Esta función debe manejarse en cada reporte según lo que se necesite

				//reinicia el array arimetico de grupos
				foreach($this->functions['CONT_GRUPO1'] as $key => $value ) 	
						$this->functions['CONT_GRUPO1'][$key]=0;
		
				if(is_array($this->functions['SUMA_GRUPO1']))
					foreach($this->functions['SUMA_GRUPO1'] as $key => $value ) 	
							$this->functions['SUMA_GRUPO1'][$key]=0;

				break;
			case 2: // Grupo 2
				$this->nCtaRegistroGrupo2=0; // Al cambiar el grupo inicializo el contador correspondiente del grupo

				$this->nCtaRegistroGrupo3=0; // También debo inicializar el contador del grupo 3 
				$this->ReiniciaVariables(3); // También Llamo a la función para reinicar variables del grupo 3.  Esta función debe manejarse en cada reporte según lo que se necesite				

				$this->nCtaRegistroGrupo4=0; // También debo inicializar el contador del grupo 4 
				$this->ReiniciaVariables(4); // También Llamo a la función para reinicar variables del grupo 4.  Esta función debe manejarse en cada reporte según lo que se necesite

				$this->nCtaRegistroGrupo5=0; // También debo inicializar el contador del grupo 5 
				$this->ReiniciaVariables(5); // También Llamo a la función para reinicar variables del grupo 5.  Esta función debe manejarse en cada reporte según lo que se necesite

				//reinicia el array arimetico de grupos
				foreach($this->functions['CONT_GRUPO2'] as $key => $value ) 	
						$this->functions['CONT_GRUPO2'][$key]=0;
		
				if(is_array($this->functions['SUMA_GRUPO2']))
					foreach($this->functions['SUMA_GRUPO2'] as $key => $value ) 	
							$this->functions['SUMA_GRUPO2'][$key]=0;

				break;
			case 3: // Grupo 3
				$this->nCtaRegistroGrupo3=0; // Al cambiar el grupo inicializo el contador correspondiente del grupo
				
				$this->nCtaRegistroGrupo4=0; // También debo inicializar el contador del grupo 4 
				$this->ReiniciaVariables(4); // También Llamo a la función para reinicar variables del grupo 4.  Esta función debe manejarse en cada reporte según lo que se necesite

				$this->nCtaRegistroGrupo5=0; // También debo inicializar el contador del grupo 5 
				$this->ReiniciaVariables(5); // También Llamo a la función para reinicar variables del grupo 5.  Esta función debe manejarse en cada reporte según lo que se necesite

				//reinicia el array arimetico de grupos
				foreach($this->functions['CONT_GRUPO3'] as $key => $value ) 	
						$this->functions['CONT_GRUPO3'][$key]=0;
		
				if(is_array($this->functions['SUMA_GRUPO3']))
					foreach($this->functions['SUMA_GRUPO3'] as $key => $value )
							$this->functions['SUMA_GRUPO3'][$key]=0;

				break;
			case 4: // Grupo 4
				$this->nCtaRegistroGrupo4=0; // Al cambiar el grupo inicializo el contador correspondiente del grupo
				
				$this->nCtaRegistroGrupo5=0; // También debo inicializar el contador del grupo 5 
				$this->ReiniciaVariables(5); // También Llamo a la función para reinicar variables del grupo 5.  Esta función debe manejarse en cada reporte según lo que se necesite

				//reinicia el array arimetico de grupos
				foreach($this->functions['CONT_GRUPO4'] as $key => $value ) 	
						$this->functions['CONT_GRUPO4'][$key]=0;
		
				if(is_array($this->functions['SUMA_GRUPO4']))
					foreach($this->functions['SUMA_GRUPO4'] as $key => $value )
							$this->functions['SUMA_GRUPO4'][$key]=0;

				break;
			case 5: // Grupo 5
				$this->nCtaRegistroGrupo5=0; // Al cambiar el grupo inicializo el contador correspondiente del grupo
				
				//reinicia el array arimetico de grupos
				foreach($this->functions['CONT_GRUPO5'] as $key => $value ) 	
						$this->functions['CONT_GRUPO5'][$key]=0;
		
				if(is_array($this->functions['SUMA_GRUPO5']))
					foreach($this->functions['SUMA_GRUPO5'] as $key => $value )
							$this->functions['SUMA_GRUPO5'][$key]=0;

				break;

		}
	}

	function CambiaGrupo($Grupo){
		global $rs;
		if(($this->maxHeight-$this->blockPosY)<20 and $this->nCuentaRegistro>0){ // Para evitar que se imprima el título del grupo solo al final de la página y sus hijos o registros en la siguiente hoja
			$this->AddPage();
			$this->maxYoff = $this->GetY();
		}

		switch ($Grupo)
		{
			case 1: // Grupo 1
				if ($this->Grupo1NewPage){   // Verifico si cada grupo debe iniciar en una nueva página
					if($this->maxYoff>$this->nyIniciaDetalle){ // Solo añade una página nueva si no está al inicio de imprimir el detalle ya en una página nueva
							$this->AddPage();
							$this->maxYoff = $this->GetY();
					}
				}

				$this->TituloGrupo1(); // Imprimo los campos que necesito en el título del grupo 1
				$this->DatoGrupo1=$rs->field($this->CampoGrupo1); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente

				if($this->CampoGrupo2){ // Si tengo un Grupo 2 
					$this->TituloGrupo2(); // Imprimo los campos que necesito en el título del grupo 2
					$this->DatoGrupo2=$rs->field($this->CampoGrupo2); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	
				}

				if($this->CampoGrupo3){ // Si tengo un Grupo 3 
					$this->TituloGrupo3(); // Imprimo los campos que necesito en el título del grupo 3
					$this->DatoGrupo3=$rs->field($this->CampoGrupo3); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	
				}

				if($this->CampoGrupo4){ // Si tengo un Grupo 4 
					$this->TituloGrupo4(); // Imprimo los campos que necesito en el título del grupo 4
					$this->DatoGrupo4=$rs->field($this->CampoGrupo4); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	
				}

				if($this->CampoGrupo5){ // Si tengo un Grupo 5 
					$this->TituloGrupo5(); // Imprimo los campos que necesito en el título del grupo 5
					$this->DatoGrupo5=$rs->field($this->CampoGrupo5); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	
				}

				break;
			case 2: // Grupo 2
				if ($this->Grupo2NewPage){   // Verifico si cada grupo debe iniciar en una nueva página
					if($this->maxYoff>$this->nyIniciaDetalle){ // Solo añade una página nueva si no está al inicio de imprimir el detalle ya en una página nueva
						$this->AddPage();
						$this->maxYoff = $this->GetY();
					}
				}

				$this->TituloGrupo2(); // Imprimo los campos que necesito en el título del grupo 2
				$this->DatoGrupo2=$rs->field($this->CampoGrupo2); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente

				if($this->CampoGrupo3){ // Si tengo un Grupo 3 
					$this->TituloGrupo3(); // Imprimo los campos que necesito en el título del grupo 3
					$this->DatoGrupo3=$rs->field($this->CampoGrupo3); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	
				}

				if($this->CampoGrupo4){ // Si tengo un Grupo 4 
					$this->TituloGrupo4(); // Imprimo los campos que necesito en el título del grupo 4
					$this->DatoGrupo4=$rs->field($this->CampoGrupo4); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	
				}

				if($this->CampoGrupo5){ // Si tengo un Grupo 5 
					$this->TituloGrupo5(); // Imprimo los campos que necesito en el título del grupo 5
					$this->DatoGrupo5=$rs->field($this->CampoGrupo5); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	
				}

				break;
			case 3: // Grupo 3
				if ($this->Grupo3NewPage){   // Verifico si cada grupo debe iniciar en una nueva página
					if($this->maxYoff>$this->nyIniciaDetalle){ // Solo añade una página nueva si no está al inicio de imprimir el detalle ya en una página nueva
							$this->AddPage();
							$this->maxYoff = $this->GetY();
					}
				}

				$this->TituloGrupo3(); // Imprimo los campos que necesito en el título del grupo 3
				$this->DatoGrupo3=$rs->field($this->CampoGrupo3); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	

				if($this->CampoGrupo4){ // Si tengo un Grupo 4 
					$this->TituloGrupo4(); // Imprimo los campos que necesito en el título del grupo 4
					$this->DatoGrupo4=$rs->field($this->CampoGrupo4); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	
				}

				if($this->CampoGrupo5){ // Si tengo un Grupo 5 
					$this->TituloGrupo5(); // Imprimo los campos que necesito en el título del grupo 5
					$this->DatoGrupo5=$rs->field($this->CampoGrupo5); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	
				}

				break;
			case 4: // Grupo 4
				$this->TituloGrupo4(); // Imprimo los campos que necesito en el título del grupo 4
				$this->DatoGrupo4=$rs->field($this->CampoGrupo4); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	

				if($this->CampoGrupo5){ // Si tengo un Grupo 5 
					$this->TituloGrupo5(); // Imprimo los campos que necesito en el título del grupo 5
					$this->DatoGrupo5=$rs->field($this->CampoGrupo5); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	
				}

				break;
			case 5: // Grupo 5
				$this->TituloGrupo5(); // Imprimo los campos que necesito en el título del grupo 4
				$this->DatoGrupo5=$rs->field($this->CampoGrupo5); // Guardo en variable el dato del grupo para poder compararlo en el registro siguiente	

				break;
				
		}

	}

	function TituloGrupo1(){
	// Aquí se imprimirá todos los campos que van en el título del grupo 1, esto se configura en cada reporte
	}
	function TituloGrupo2(){
	// Aquí se imprimirá todos los campos que van en el título del grupo 2, esto se configura en cada reporte
	}
	function TituloGrupo3(){
	// Aquí se imprimirá todos los campos que van en el título del grupo 3, esto se configura en cada reporte
	}
	function TituloGrupo4(){
	// Aquí se imprimirá todos los campos que van en el título del grupo 4, esto se configura en cada reporte
	}
	function TituloGrupo5(){
	// Aquí se imprimirá todos los campos que van en el título del grupo 4, esto se configura en cada reporte
	}

	function CierraFila(){
		global $rs;
		if($this->CampoGrupo1){ // Si tengo grupos en el reporte
			$rs->getrow(); // obtengo los datos del registro siguiente

			if($this->DatoGrupo1!=$rs->field($this->CampoGrupo1)){ // Si cambia de grupo
				$this->CierraGrupo(1);
			}elseif($this->DatoGrupo2!=$rs->field($this->CampoGrupo2)){
				$this->CierraGrupo(2);
			}elseif($this->DatoGrupo3!=$rs->field($this->CampoGrupo3)){
				$this->CierraGrupo(3);
			}elseif($this->DatoGrupo4!=$rs->field($this->CampoGrupo4)){
				$this->CierraGrupo(4);
			}elseif($this->DatoGrupo5!=$rs->field($this->CampoGrupo5)){
				$this->CierraGrupo(5);
			}
			
			$rs->skiprow($rs->curr_row-1); // Regreso al registro donde estaba, donde dejó seteado el propio objeto $rs
		}
	}

	function CierraGrupo($Grupo){
		global $rs;

		switch ($Grupo)
		{
			case 1: // Grupo 1 
				if($this->CampoGrupo5){ // Si tengo un Grupo 5 
					$this->PieGrupo5(); // Imprimo los campos que necesito en el pie del grupo 5
				}

				if($this->CampoGrupo4){ // Si tengo un Grupo 4 
					$this->PieGrupo4(); // Imprimo los campos que necesito en el pie del grupo 4
				}

				if($this->CampoGrupo3){ // Si tengo un Grupo 3 
					$this->PieGrupo3(); // Imprimo los campos que necesito en el pie del grupo 3
				}

				if($this->CampoGrupo2){ // Si tengo un Grupo 2 
					$this->PieGrupo2(); // Imprimo los campos que necesito en el pie del grupo 2
				}

				$this->PieGrupo1(); // Imprimo los campos que necesito en el pie del grupo 1

				break;

			case 2: // Grupo 2
				if($this->CampoGrupo5){ // Si tengo un Grupo 5 
					$this->PieGrupo5(); // Imprimo los campos que necesito en el pie del grupo 5
				}

				if($this->CampoGrupo4){ // Si tengo un Grupo 4 
					$this->PieGrupo4(); // Imprimo los campos que necesito en el pie del grupo 4
				}

				if($this->CampoGrupo3){ // Si tengo un Grupo 3 
					$this->PieGrupo3(); // Imprimo los campos que necesito en el pie del grupo 3
				}

				$this->PieGrupo2(); // Imprimo los campos que necesito en el pie del grupo 2

				break;

			case 3: // Grupo 3
				if($this->CampoGrupo5){ // Si tengo un Grupo 5 
					$this->PieGrupo5(); // Imprimo los campos que necesito en el pie del grupo 5
				}

				if($this->CampoGrupo4){ // Si tengo un Grupo 4 
					$this->PieGrupo4(); // Imprimo los campos que necesito en el pie del grupo 4
				}

				$this->PieGrupo3(); // Imprimo los campos que necesito en el pie del grupo 3

				break;

			case 4: // Grupo 4
				if($this->CampoGrupo5){ // Si tengo un Grupo 5 
					$this->PieGrupo5(); // Imprimo los campos que necesito en el pie del grupo 5
				}

				$this->PieGrupo4(); // Imprimo los campos que necesito en el pie del grupo 4

				break;

			case 5: // Grupo 5

				$this->PieGrupo5(); // Imprimo los campos que necesito en el pie del grupo 5

				break;

		}
	}

	function PieGrupo1(){
		// Imprimo una línea al final del grupo
//		$this->Line($this->blockPosX, $this->blockPosY+$this->lasth,$this->blockPosX+$this->WidthTotalCampos, $this->blockPosY+$this->lasth); // Imprimo Línea al final de cada grupo
		// A partir de aquí se imprimirá todos los campos que van en el pie del grupo 1, esto se configura en cada reporte
	}

	function PieGrupo2(){
	// Aquí se imprimirá todos los campos que van en el pie del grupo 2, esto se configura en cada reporte
	}

	function PieGrupo3(){
	// Aquí se imprimirá todos los campos que van en el pie del grupo 3, esto se configura en cada reporte
	}

	function PieGrupo4(){
	// Aquí se imprimirá todos los campos que van en el pie del grupo 4, esto se configura en cada reporte
	}

	function PieGrupo5(){
	// Aquí se imprimirá todos los campos que van en el pie del grupo 5, esto se configura en cada reporte
	}

	function ImprimeDetalle(){
		// Aquí recorro todos los registros del recordset
		global $rs;
		while ($rs->getrow()){
			/* Inicio Fila */
			$this->IniciaFila();	

			/* Cálculo de variables */
			$this->nCuentaRegistro=$this->nCuentaRegistro+1; // Contador de registros general de todo el reporte
			$this->nCtaRegistroGrupo1=$this->nCtaRegistroGrupo1+1; // Contador de registros	del Grupo 1	
			$this->nCtaRegistroGrupo2=$this->nCtaRegistroGrupo2+1; // Contador de registros	del Grupo 2				
			$this->nCtaRegistroGrupo3=$this->nCtaRegistroGrupo3+1; // Contador de registros	del Grupo 3	
			$this->nCtaRegistroGrupo4=$this->nCtaRegistroGrupo4+1; // Contador de registros	del Grupo 4				
			$this->nCtaRegistroGrupo5=$this->nCtaRegistroGrupo5+1; // Contador de registros	del Grupo 5				

			/* Imprimo el detalle */ 
			$this->beginBlock(); // Creo un espacio en blanco para imprimir los campos del mismo grupo
			$this->Detalle(); // Imprimo los campos que van en la franja DETALLE
			
			/* Cierro la fila */
			$this->CierraFila();		
		}
	}

	function Detalle(){
	// Aquí se imprimirá todos los campos que van en la franja DETALLE del reporte, esto se configura en cada reporte
	}

	function Summary(){
		// A partir de aquí se imprimirá todos los campos que van en la franja SUMMARY del reporte, esto se configura en cada reporte
	}

} // End class
?>
