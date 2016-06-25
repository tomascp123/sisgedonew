<?
// Conexión a la base de datos
require_once('../app/conexion.php');

define("CSS_CONTENT","/"._CARPETAAPP_."/mislibs/content_yahoo.css");
define("PATH_INC","/"._CARPETAAPP_."/mislibs/"); // Ruta de las librerías 


define("SIS_PIELEFT_REPORTE","Sistema de Gestión Documentaria");
define("SIS_VERSION","SISGEDO 2.0");

//carpeta publica del servidor donde se almacenaran los archivos cargados por los usuarios
define("PUBLICUPLOAD","../../docs/");
// FileField
define("FILEFIELD_ARQUIVOATUAL","");
define("FILEFIELD_REMOVER","remover");


// TextAreaField
define("TEXTAREA_RESTANTES","caracteres restantes");

include("../mislibs/db/pg.class.php");

/*****************************************************************************************************
	Classe para montagem de expressões SQL de atualização
	O método getValue deve ser adaptado conforme o banco de dados utilizado.
	No futuro esta classe será mais generalizada
*/
class UpdateSQL {
	var $action;
	var $table;

	var $keyField;
	var $keyValue;
	var $keyType;

	var $updateFields;
	var $updateValues;
	var $updateTypes;

	/*
		Construtor
		theAction : INSERT, UPDATE, DELETE
		theTable : nome da tabela
	*/
	function UpdateSQL($theAction="", $theTable="") {
		$this->action = strtoupper($theAction);
		$this->table = $theTable;
	}

	/*
		Define a chave
		theField : nome do campo
		theValue : valor do campo
		theType : tipo do campo (Number, String, Date)
	*/
	function setKey($theField, $theValue, $theType) {
		$this->keyField = $theField;
		$this->keyValue = $theValue;
		$this->keyType = $theType;
	}

	/*
		Adiciona um campo na expressão SQL
		theField : nome do campo
		theValue : valor do campo
		theType : tipo do campo (Number, String, Date)
	*/
	function addField($theField, $theValue, $theType) {
		$this->updateFields[] = $theField;
		$this->updateValues[] = $theValue;
		$this->updateTypes[] = $theType;
	}

	/*
		Define a ação da expressão SQL
		theAction : INSERT, UPDATE, DELETE
	*/
	function setAction($theAction) {
		$this->action = strtoupper($theAction);
	}

	/*
		Define a tabela que vai sofrer atualização
		theTable : nome da tabela
	*/
	function setTable($theTable) {
		$this->table = $theTable;
	}

	/*
		Monta a expressão SQL e retorna como string
	*/
	function getSQL() {
		$sql = "";
		// adicion
		if ($this->action=="INSERT") {
			$sql .= "INSERT INTO " . $this->table . " (";
			$fieldlist = "";
			$valuelist = "";
			for ($i=0; $i<sizeof($this->updateFields); $i++) {
				$fieldlist .= $this->updateFields[$i] . ", ";
				$valuelist .= $this->getValue($this->updateValues[$i], $this->updateTypes[$i]) . ", ";
			}
			$fieldlist = substr($fieldlist,0,-2);
			$valuelist = substr($valuelist,0,-2);
			$sql .= $fieldlist . ") VALUES (" . $valuelist . ")";
		}

		// modificacion
		if ($this->action=="UPDATE") {
			$sql .= "UPDATE " . $this->table . " SET ";
			$updatelist = "";
			for ($i=0; $i<sizeof($this->updateFields); $i++) {
				$updatelist .= $this->updateFields[$i] . "=" .
				               $this->getValue($this->updateValues[$i], $this->updateTypes[$i]) . ", ";
			}
			$updatelist = substr($updatelist,0,-2);
			$sql .= $updatelist . " WHERE " . $this->keyField . "=" . $this->getValue($this->keyValue, $this->keyType);
		}

		// eliminacion
		if ($this->action=="DELETE") {
			$sql .= "DELETE FROM " . $this->table . " WHERE " . $this->keyField . "=" . $this->getValue($this->keyValue, $this->keyType);
		}

		return $sql;
	}

	/*
		Formata o valor conforme o tipo
		value : valor do campo
		type : tipo do campo (Number, String, Date)
	*/
	function getValue($value, $type) {
		if (!strlen($value)) {
			return "NULL";
		} else {
			if ($type == "Number") {
				//return str_replace (",", ".", doubleval($value));
				return str_replace (",", "", doubleval($value));
			} else {
				if (get_magic_quotes_gpc() == 0) {
					$value = str_replace("'","''",$value);
					$value = str_replace("\\","\\\\",$value);
				} else {
					$value = str_replace("\\'","''",$value);
					$value = str_replace("\\\"","\"",$value);
				}
				return "'" . $value . "'";
			}
		}
	}
}

/*****************************************************************************************************
	Clase para creación de formularios
*/
class Form {
	var $name;
	var $action;
	var $method;
	var $target;
	var $width;
	var $blockFields;
	var $blockHidden;
	var $focus;
	var $upload;
	var $labelWidth;
	var $dataWidth;
	var $tableMargin;
	var $classlabel;
	var $LabelFONT;
	var $classdata;

	// construtor
	// $name : identificador do formulário
	// $action : action do formulário
	// $method : método a ser utilizado POST ou GET
	// $target : frame em que o action será executado
	// $width : largura do formulário
	// $focus : mecanismo de foco destacado, true ou false
	function Form($name="frm", $action="", $method="POST", $target="controle", $width="100%", $focus=false) {
		$this->name = $name;
		$this->action = $action;
		$this->method = $method;
		$this->target = $target;
		$this->width = $width;
		$this->blockFields = "";
		$this->blockHidden = "";
		$this->focus = $focus;
		$this->enctype=$enctype;
		$this->labelWidth = "30%";
		$this->dataWidth = "70%";
		$this->tableMargin= true;
		$this->classlabel = "LabelTD";
		$this->LabelFONT = "LabelFONT";
		$this->classdata = "DataTD BackTD";
	}


	// define o tipo de documento
	function setUpload($fazUpload=false) {
		$this->upload = $fazUpload;
	}

	// define a largura da coluna label
	function setLabelWidth($valor) {
		$this->labelWidth = $valor;
	}

	// define a largura da coluna data
	function setDataWidth($valor) {
		$this->dataWidth = $valor;
	}

	// define o nome do formulário
	function setName($umNome) {
		$this->name = $umNome;
	}

	// define a ação do formulário
	function setAction($umaAcao) {
		$this->action = $umaAcao;
	}

	// define o método do formulário
	function setMethod($umMetodo) {
		$this->method = $umMetodo;
	}

	// define o target do formulário
	function setTarget($umTarget) {
		$this->target = $umTarget;
	}

	// define se campos terão highligth
	function setFocus($focus) {
		$this->focus = $focus;
	}

	// define a largura do formulário
	function setWidth($largura) {
		$this->width = $largura;
	}

	function setTabMargin($setmargin) {
		$this->tableMargin = $setmargin;
	}

	function setClassLabel($class) {
		$this->classlabel = $class;
	}

	function setClassLabelFont($class) {
		$this->LabelFONT = $class;
	}

	function setClassData($class) {
		$this->classdata = $class;
	}

	// adiciona campo hidden ao formulário
	// $varName : nome do campo
	// $varValue : valor do campo
	function addHidden($varName, $varValue, $msjvalid='') {
		$this->blockHidden .= "<input type='hidden' name='".$varName."' value='".$varValue."' id='$msjvalid'>\n";
	}

	// adiciona campo al formulário
	// $label : título del campo
	// $field : expresión html que define al campo
	function addField($label="", $field, $title="") {
		$this->blockFields .= "<tr>";
		$this->blockFields .= "<td width='".$this->labelWidth."' class='".$this->classlabel."' nowrap><font class=".$this->LabelFONT.">".$label."</font></td>";
		$this->blockFields .= "<td width='".$this->dataWidth."' class='".$this->classdata."' title=\"$title\"><font class='".iif(strpos($field,"name="),">",0,"DataFONT","ValueFONT")."'>".$field."</font></td>";
		$this->blockFields .= "</tr>\n";
	}

	// adiciona una imagen dentro de un div
	// $label : título do campo
	// $field : expressão html que define o campo
	function addDivImage($DivImg) {
		$this->blockFields .= $DivImg;
	}

	// adiciona Còdigo html en el form
	// $label : título do campo
	// $field : expressão html que define o campo
	function addHtml($CodHtml) {
		$this->blockFields .= $CodHtml;
	}

	function addLine($colspan='2'){
		$this->blockFields .= "<tr>";
		$this->blockFields .= "<td colspan='$colspan'>
						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
						<div style=\"BACKGROUND:url(../img/hlhx.gif)\"><img src=\"../img/1.gif\" width=\"12\" height=\"1\"></div></table>
					 	</td>";						
		$this->blockFields .= "</tr>\n";
	}
	
	// adiciona divisória ao formulário
	// $text : expressão que será mostrada dentro da quebra
	// $style : usar estilo predefinido? true ou false
	function addBreak($text="", $style=true, $colspan='2', $align='') {
		$this->blockFields .= "<tr>";
		if ($style) {
			$this->blockFields .= "<td class='RecordSeparatorTD' colspan='$colspan' align=\"$align\"><font class='RecordSeparatorFONT'>".$text."</font></td>";
		} else {
			$this->blockFields .= "<td colspan='$colspan' align=\"$align\">".$text."</td>";
		}
		$this->blockFields .= "</tr>\n";
	}

	// retorna bloco HTML com o formulário montado
	function writeHTML() {
		$out = "";
		if($this->tableMargin){
			$out .= "<table border='0' cellpadding='1' cellspacing='0' align='center' width='".$this->width."'>\n";
			$out .= "<tr><td>";
			}

		$enctype = "";
		if ($this->upload) $enctype = "enctype='multipart/form-data'";

		if ($this->focus) {
			$out .= "<form name='".$this->name."' id='".$this->name."' ".$enctype." action='".$this->action."' method='".$this->method."' target='".$this->target."' onKeyUp='highlight(event)' onClick='highlight(event)'>\n";
		} else {
			$out .= "<form name='".$this->name."' id='".$this->name."' ".$enctype." action='".$this->action."' method='".$this->method."' target='".$this->target."'>\n";
		}
		$out .= $this->blockHidden;
		$out .= "<table width='".$this->width."' class='FormTABLE' cellspacing=0>\n";
		$out .= $this->blockFields;
		$out .= "</table>\n";
		$out .= "</form>\n";

		if($this->tableMargin)
			$out .= "</td></tr></table>\n";
		return $out;
	}
}

/***********************************************************************************************************
 Clase para Añadir una tabla dentro de un formulario de Edición.  Esta tabla se crea con la misma estructura
 del formulario permitiendo entonces poder agregar más campos al formulario de manera transparente.
***********************************************************************************************************/
class AddTableForm {
	var $labelWidth;
	var $dataWidth;
	var $styRecordSeparatorTD;
	var $styRecordSeparatorFONT;
	var $styLabelTD;
	var $styLabelFONT;
	var $styDataTD;
	var $styBackTD;
	var $styDataFONT;
	var $styValueFONT;
	var $classlabel;
	var $LabelFONT;
	var $classdata;

	// construtor
	function AddTableForm($width="100%")
	{
		$this->TableWidth = $width;
		$this->tableAlign = "L";
		$this->labelWidth = "30%";
		$this->dataWidth = "70%";
		$this->styRecordSeparatorTD='RecordSeparatorTD';
		$this->styRecordSeparatorFONT='RecordSeparatorFONT';
		$this->styLabelTD='LabelTD';
		$this->styLabelFONT='LabelFONT';
		$this->styDataTD='DataTD';
		$this->styBackTD='BackTD';
		$this->styDataFONT='DataFONT';
		$this->styDataTD='DataTD';
		$this->styValueFONT='ValueFONT';
		$this->classlabel = "LabelTD";
		$this->LabelFONT = "LabelFONT";
		$this->classdata = "DataTD BackTD";

	}

	//setea el stylo para el fondo de los separadores de seccion 'metodo BREAK'
	function setRecordSeparatorTD($style) {
		$this->styRecordSeparatorTD=$style;
		}
	//setea el stylo para las letras de los separadores de seccion 'metodo BREAK'
	function setRecordSeparatorFONT($style) {
		$this->styRecordSeparatorFONT=$style;
		}
	//setea el stylo para el fondo de las etiquetas de los forms
	function setLabelTD($style) {
		$this->styLabelTD=$style;
		}
	//setea el stylo para el texto de las etiquetas de los forms
	function setLabelFONT($style) {
		$this->styLabelFONT=$style;
		}
	//setea el stylo para las olumnas de los datos de los forms
	function setDataTD($style) {
		$this->styDataTD=$style;
		}
	//setea el stylo para el color de fondo de las columnas de los forms
	function setBackTD($style) {
		$this->styBackTD=$style;
		}
	//setea el stylo para el color de fondo de los datos de los forms
	function setDataFONT($style) {
		$this->styDataFONT=$style;
		}
	//setea el stylo para el color de valores de los datos (cuando no hay objetos)
	function setValueFONT($style) {
		$this->styValueFONT=$style;
		}
	// define a largura da coluna label
	function setLabelWidth($valor) {
		$this->labelWidth = $valor;
	}
	// define a largura da coluna data
	function setDataWidth($valor) {
		$this->dataWidth = $valor;
	}

	// define o alinhamento da tabela
	function setTableAlign($tableAlign) {
		$this->tableAlign = strtoupper($tableAlign);
	}

	function setClassLabel($class) {
		$this->classlabel = $class;
	}

	function setClassLabelFont($class) {
		$this->LabelFONT = $class;
	}

	function setClassData($class) {
		$this->classdata = $class;
	}

	// adiciona Còdigo html en el form
	// $label : título do campo
	// $field : expressão html que define o campo
	function addHtml($CodHtml) {
		$this->blockFields .= $CodHtml;
	}

	function addLine(){
		$this->blockFields .= "<tr>";
		$this->blockFields .= "<td colspan='2'>
						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
						<div style=\"BACKGROUND:url(../img/hlhx.gif)\"><img src=\"../img/1.gif\" width=\"12\" height=\"1\"></div></table>
					 	</td>";						
		$this->blockFields .= "</tr>\n";
	}

	// adiciona divisória ao formulário
	// $text : expressão que será mostrada dentro da quebra
	// $style : usar estilo predefinido? true ou false
	function addBreak($text="", $style=true, $colspan='2', $align='') {
		$this->blockFields .= "<tr>";
		if ($style) {
			$this->blockFields .= "<td class='$this->styRecordSeparatorTD' colspan='$colspan' align=\"$align\"><font class='$this->styRecordSeparatorFONT'>".$text."</font></td>";
		} else {
			$this->blockFields .= "<td colspan='$colspan' align=\"$align\">".$text."</td>";
		}
		$this->blockFields .= "</tr>\n";
	}

	// adiciona una imagen dentro de un div
	// $label : título do campo
	// $field : expressão html que define o campo
	function addDivImage($DivImg) {
		$this->blockFields .= $DivImg;
	}

	// adiciona campo ao formulário
	// $label : título do campo
	// $field : expressão html que define o campo
	function addField($label="", $field) {
		$this->blockFields .= "<tr>";
		$this->blockFields .= "<td width='".$this->labelWidth."' class='$this->styLabelTD' nowrap><font class='$this->styLabelFONT'>".$label."</font></td>";
		$this->blockFields .= "<td width='".$this->dataWidth."' class='$this->styDataTD $this->styBackTD'><font class='".iif(strpos($field,"name="),">",0,"$this->styDataFONT","$this->styValueFONT")."'>".$field."</font></td>";
		$this->blockFields .= "</tr>\n";
	}

	// adiciona campo hidden ao formulário
	// $varName : nome do campo
	// $varValue : valor do campo
	function addHidden($varName, $varValue, $msjvalid='') {
		$this->blockHidden .= "<input type='hidden' name='".$varName."' value='".$varValue."' id='$msjvalid'>\n";
	}

	// retorna bloco HTML com o formulário montado
	function writeHTML() {
		if ($this->tableAlign=="L") $ta = "<div align='left'>";
		if ($this->tableAlign=="C") $ta = "<div align='center'>";
		if ($this->tableAlign=="R") $ta = "<div align='right'>";
		$out = "";
		$out .= $this->blockHidden;
		$out .= "$ta<table width='".$this->TableWidth."' class='FormTABLE' border='0' cellpadding='0' cellspacing='0' >\n";
		$out .= $this->blockFields;
		$out .= "</table></div>\n";
		return $out;
	}
}

/*****************************************************************************************************
 Clase para generar tablas
*/
class Table {
	var $block;
	var $blockHead;	
	var $title;
	var $width;
	var $row;
	var $columns;
	var $currcol;
	var $style;
	var $alternate = false;
	var $tableAlign;
	var $styAlternateBackTD;
	var $styBackTD;
	var $styAlternateDataTD;
	var $styDataTD;
	var $styDataFONT;
	var $FormTotalTD;
	var $styFormTotalFONT;
	var $styColumnTD;
	var $styColumnFontLink;
	var $styColumnFont;
	var $styRecordSeparatorTD;
	var $styRecordSeparatorFONT;
	var $styFormTABLE;
	var $styFormHeaderTD;
	var $styFormHeaderFONT;
	var $id;	

	// Construtor
	// $title : título da tabela
	// $width : largura da tabela
	// $columns : quantidade de colunas na tabela
	// $style : usar estilo predefinido? true ou false
	// $id : id de la tabla 
	function Table($title="", $width="100%", $columns, $style=true, $id='') {
		$this->title = $title;
		$this->width = $width;
		$this->columns = $columns;
		$this->currcol = 1;
		$this->style = $style;
		$this->tableAlign = "L";
		$this->styAlternateBackTD='AlternateBackTD';
		$this->styBackTD='BackTD';
		$this->styAlternateDataTD='AlternateDataTD';
		$this->styDataTD='DataTD';
		$this->styDataFONT='DataFONT';
		$this->styFormTotalTD='FormTotalTD';
		$this->styFormTotalFONT='FormTotalFONT';
		$this->styColumnTD='ColumnTD';
		$this->styColumnFontLink='ColumnFontLink';
		$this->styColumnFont='ColumnFont';
		$this->styRecordSeparatorTD='RecordSeparatorTD';
		$this->styFormTABLE='FormTABLE';
		$this->styFormHeaderTD='FormHeaderTD';
		$this->styFormHeaderFONT='FormHeaderFONT';
		$this->styRecordSeparatorFONT='RecordSeparatorFONT';
		$this->id = $id;		
	}
	//setea el segundo color de fondo para las filas de datos de las tablas
	function setAlternateBackTD($style) {
		$this->styAlternateBackTD=$style;
		}
	//setea el primer color de fondo para las filas de datos de las tablas
	function setBackTD($style) {
		$this->styBackTD=$style;
		}
	//setea el segundo stylo para las filas de datos de las tablas
	function setAlternateDataTD($style) {
		$this->styAlternateDataTD=$style;
		}
	//setea el primer stylo para las filas de datos de las tablas
	function setDataTD($style) {
		$this->styDataTD=$style;
		}
	//setea el tipo y color de letras para las filas de datos de las tablas
	function setDataFONT($style) {
		$this->styDataFONT=$style;
		}
	//setea el color de fondo para las filas de totales de las tablas
	function setFormTotalTD($style) {
		$this->styFormTotalTD=$style;
		}
	//setea el stylo de letra para las filas de totales de las tablas
	function setFormTotalFONT($style) {
		$this->styFormTotalFONT=$style;
		}
	//setea el stylo para el texto de ordenacion en las cebaceras de las tablas
	function setColumnFontLink($style) {
		$this->styColumnFontLink=$style;
		}
	//setea el stylo para el fondo de las cabeceras de las tablas
	function setColumnTD($style) {
		$this->styColumnTD=$style;
		}
	//setea el stylo para las letras de las cabeceras de las tablas
	function setColumnFont($style) {
		$this->styColumnFont=$style;
		}
	//setea el stylo para el fondo del titulo de las tablas
	function setFormHeaderTD($style) {
		$this->styFormHeaderTD=$style;
		}
	//setea el stylo para las letras del titulo de las tablas
	function setFormHeaderFONT($style) {
		$this->styFormHeaderFONT=$style;
		}
	//setea el stylo general de la tabla, por lo general no se modifica
	function setFormTABLE($style) {
		$this->styFormTABLE=$style;
		}
	//setea el stylo para el fondo de los separadores de seccion 'metodo BREAK'
	function setRecordSeparatorTD($style) {
		$this->styRecordSeparatorTD=$style;
		}
	//setea el stylo para las letras de los separadores de seccion 'metodo BREAK'
	function setRecordSeparatorFONT($style) {
		$this->styRecordSeparatorFONT=$style;
		}
	// agrupa células e adiciona na linha
	// recibe un estylo, para controlar el color solo de una fila, ejemplo: ANULADOS
	function addRow($style="",$selector=true) {
		$st = $this->alternate?$this->styAlternateBackTD:$this->styBackTD;
		$style=$style?$style:$st;

		if($selector==false)
			$this->block .= "<tr class='$style' id='$style' >".$this->row."</tr>\n";		
		else
			$this->block .= "<tr class='$style' id='$style' onmouseover=\"MO(event,'TR')\" onmouseout=\"MU(event,'TR')\">".$this->row."</tr>\n";

		$this->row = "";
		$this->currcol = 1;
		$this->alternate = !$this->alternate;
	}

	// Creo la fila que contendrá las celdas de cabecera de la tabla
	function addRowHead($style="") {
		$style=$style?$style:$st;
		$this->blockHead .= "<tr class='$style' id='$style' >".$this->row."</tr>\n";		
		$this->row = "";
		$this->currcol = 1;
	}
	
	function addHtml($CodHtml) {
		$this->block .= $CodHtml;
	}

	// crea una celda
	// $data : conteúdo dentro da célula
	// $align : alinhamento (L, C, R)
	function addData($data="&nbsp", $align="L", $id="", $js="", $title="") {
		$cs = $this->currcol;
		$align = strtoupper($align);
		if ($align=="L") $al = "align=left";
		if ($align=="C") $al = "align=center";
		if ($align=="R") $al = "align=right";
		if ($this->style) {
			$st = $this->alternate?$this->styAlternateDataTD:$this->styDataTD;
			$this->row .= "<td class='$st' $al title=\"$title\" id=\"$id\" ".str_replace('NCOL',$cs,$js)." ><font class='".$this->styDataFONT."'>".$data."</font></td>";
		} else {
			$this->row .= "<td $al>".$data."</td>";
		}
		$this->currcol++;
	}

	// crea una celda total
	// $data : contenido dentro da célula
	// $align : alinhamento (L, C, R)
	function addTotal($data="&nbsp", $align="R") {
		$align = strtoupper($align);
		if ($align=="L") $al = "align=left";
		if ($align=="C") $al = "align=center";
		if ($align=="R") $al = "align=right";
		$this->row .= "<td class='".$this->styFormTotalTD."' $al><font class='".$this->styFormTotalFONT."'>".$data."</font></td>";
		}

	// cria título da coluna
	// $title : título da coluna
	// $ord : ordenar? true ou false
	// $width : largura da coluna
	// $align : alinhamento (L, C, R)
	function addColumnHeader($title="&nbsp;", $ord=false, $width="1", $align="L", $js="", $alt="", $nowrap="") {
		global $form_sorting;
		$cs = $this->currcol;

		$align = strtoupper($align);
		if ($align=="L") $al = "align=left";
		if ($align=="C") $al = "align=center";
		if ($align=="R") $al = "align=right";

		$this->row .= "<td class='".$this->styColumnTD."' width='".$width."' $al $nowrap title=\"$alt\">";
		if ($ord) {
			if($js)
				$this->row .= "<a title='Ordenar por $title' class='".$this->styColumnFontLink."' href='#' onClick=\"".str_replace('NCOL',$cs,$js)."\">".$title."</a>";
			else
				$this->row .= "<a title='Ordenar por $title' class='".$this->styColumnFontLink."' href='".$_SERVER['PHP_SELF']."?Sorting=$cs&Sorted=$form_sorting'>".$title."</a>";
		} else {
			$this->row .= "<font class='".$this->styColumnFont."'>".$title."</font>";
		}
		$this->row .= "</td>";
		$this->alternate = true;
		$this->currcol++;
	}

	function addLine(){
		$this->blockFields .= "<tr>";
		$this->blockFields .= "<td colspan='$this->columns'>
						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
						<div style=\"BACKGROUND:url(../img/hlhx.gif)\"><img src=\"../img/1.gif\" width=\"12\" height=\"1\"></div></table>
					 	</td>";						
		$this->blockFields .= "</tr>\n";
	}
	// adiciona linha divisória na tabela
	// $title : expressão html que será exibida na quebra
	function addBreak($title="&nbsp", $style=true, $align='') {
		if (!$style) {
			$this->row .= "<td colspan='".$this->columns."' align=\"$align\">".$title."</td>";
		} else {
			$this->row .= "<td class='".$this->styRecordSeparatorTD."' colspan='".$this->columns."' align=\"$align\"><font class='".$this->styRecordSeparatorFONT."'>".$title."</font></td>";
		}
		$this->addRow();
		$this->alternate = false;
	}

	// define o alinhamento da tabela
	function setTableAlign($tableAlign) {
		$this->tableAlign = strtoupper($tableAlign);
	}

	// retorna o bloco HTML com a tabela montada
	function writeHTML() {
		if ($this->tableAlign=="L") $ta = "<div align='left'>";
		if ($this->tableAlign=="C") $ta = "<div align='center'>";
		if ($this->tableAlign=="R") $ta = "<div align='right'>";
		$out .= "$ta<table border=0 cellspacing=0 cellpadding=1 width='".$this->width."'><tr><td vAlign='top' align='center'>";
		if ($this->style) {
			$out .= "<table id='$this->id' width='100%' class='".$this->styFormTABLE."' cellspacing=0>";

		} else {
			$out .= "<table id='$this->id' border='0'>";
		}
		$out .= "<thead>";		
		$out .= $this->blockHead;				
		$out .= "</thead>";				
		if ($this->title != "") {
			$out .= "<tr>";
			$out .= "<td class='".$this->styFormHeaderTD."' colspan='".$this->columns."'>";
			$out .= "<font class='".$this->styFormHeaderFONT."'>".$this->title."</font>";
			$out .= "</td>";
			$out .= "</tr>";
		}
		$out .= $this->block;
		$out .= "</table>";
		$out .= "</td></tr></table></div>";
		return $out;
	}
}

/*****************************************************************************************************
 Clase para generar tablas
*/
class TableSimple {
	var $block;
	var $blockHead;	
	var $title;
	var $width;
	var $row;
	var $columns;
	var $id;	

	// Construtor
	// $title : título da tabela
	// $width : largura da tabela
	// $columns : quantidade de colunas na tabela
	// $style : usar estilo predefinido? true ou false
	// $id : id de la tabla 
	function TableSimple($title="", $width="100%", $columns, $id='') {
		$this->title = $title;
		$this->width = $width;
		$this->columns = $columns;
		$this->currcol = 1;
		$this->id = $id;		
	}

	// agrupa células e adiciona na linha
	// recibe un estylo, para controlar el color solo de una fila, ejemplo: ANULADOS
	function addRow($id='') {
		$this->block .= "<tr id='$id' >".$this->row."</tr>\n";		
		$this->row = "";
		$this->currcol = 1;
	}

	// Creo la fila que contendrá las celdas de cabecera de la tabla
	function addRowHead($id='') {
		$this->blockHead .= "<tr id='$id' >".$this->row."</tr>\n";		
		$this->row = "";
		$this->currcol = 1;
	}
	
	function addHtml($CodHtml) {
		$this->block .= $CodHtml;
	}

	// crea una celda
	// $data : conteúdo dentro da célula
	// $align : alinhamento (L, C, R)
	function addData($data="&nbsp", $align="L", $id="", $title="" ) {
		$align = strtoupper($align);
		if ($align=="L") $al = "align=left";
		if ($align=="C") $al = "align=center";
		if ($align=="R") $al = "align=right";
		$this->row .= "<td $al id=\"$id\" title=\"$title\">$data</td>";
	}

	// cria título da coluna
	// $title : título da coluna
	// $ord : ordenar? true ou false
	// $width : largura da coluna
	// $align : alinhamento (L, C, R)
	function addColumnHeader($title="&nbsp;", $width="1", $align="L", $nowrap="") {
		$align = strtoupper($align);
		if ($align=="L") $al = "align=left";
		if ($align=="C") $al = "align=center";
		if ($align=="R") $al = "align=right";
		$this->row .= "<th width='$width' $al $nowrap>$title";
		$this->row .= "</th>";
	}

	function addLine(){
		$this->blockFields .= "<tr>";
		$this->blockFields .= "<td colspan='$this->columns'>
						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
						<div style=\"BACKGROUND:url(../img/hlhx.gif)\"><img src=\"../img/1.gif\" width=\"12\" height=\"1\"></div></table>
					 	</td>";						
		$this->blockFields .= "</tr>\n";
	}
	// adiciona linha divisória na tabela
	// $title : expressão html que será exibida na quebra
	function addBreak($align='',$id) {
		$this->row .= "<td class='break' id='$id' colspan='".$this->columns."' align=\"$align\">".$title."</td>";
		$this->addRow();
	}

	// retorna o bloco HTML com a tabela montada
	function writeHTML() {
		if ($this->title != "") {
			$out .="<div class='Bordeatabla' style='width:100%' align=center >$this->title</div>";			
		}
		$out .= "<table id='$this->id' class='tablesorter' width='$this->width' border=0 cellspacing=0 cellpadding=1>";
		$out .= "<thead>";		
		$out .= $this->blockHead;				
		$out .= "</thead>";				
		$out .= $this->block;
		$out .= "</table>";
		return $out;
	}
}

/*****************************************************************************************************
	Classe pra gerar caixas de conteúdo
*/
class Box {
	var $title;
	var $width;
	var $content;

	// Construtor
	// $title : título do box
	// $width : largura do box
	function Box($title="", $width="100%") {
		$this->title = $title;
		$this->width = $width;
	}

	// adiciona conteúdo ao box
	// $texto : expressão html que será adicionada ao box
	function addContent($texto="") {
		$this->content .= $texto;
	}

	// retorna bloco HTML com o box montado
	function writeHTML() {
		$out = "";
		$out .= "<table border=0 cellspacing=0 cellpadding=0 width='".$this->width."'><tr><td vAlign='top'>";
		$out .= "<table class='FormTABLE'>";
		if ($this->title!="") {
			$out .= "<tr>";
			$out .= "<td class='FormHeaderTD'>";
			$out .= "<font class='FormHeaderFONT'>".$this->title."</font>";
			$out .= "</td>";
			$out .= "</tr>";
		}
		$out .= "<tr>";
		$out .= "<td class='DataTD BackTD'>";
		$out .= "<font class='DataFONT'>";
		$out .= $this->content;
		$out .= "</font>";
		$out .= "</td>";
		$out .= "</tr>";
		$out .= "</table>";
		$out .= "</td></tr></table>";
		return $out;
	}
}

/*****************************************************************************************************
 Classe que gera um menu vertical
*/
class Menu {
	var $title;
	var $item;
	var $url;
	var $frame;
	var $width;

	// Construtor
	// $aTitle : título do menu
	// $width : largura do menu
	function Menu($aTitle="",$width="100%") {
		$this->title = $aTitle;
		$this->width = $width;
	}

	// adiciona item ao menu
	// $item : nome do item de menu
	// $url : link que será chamado
	// $frame : frame de destino
	function addItem($item, $url="#", $frame="content") {
		$this->item[] = $item;
		$this->url[] = $url;
		$this->frame[] = $frame;
	}

	// retorna bloco HTML que monta o menu
	function writeHTML() {
		$out = "";
		$out .= "<table border=0 cellspacing=0 cellpadding=0 width='".$this->width."'><tr><td vAlign='top'>";
		$out .= "<table class='FormTABLE'>";
		$out .= "<tr>";
		$out .= "<td class='FormHeaderTD'>";
		$out .= "<font class='FormHeaderFONT'>".$this->title."</font>";
		$out .= "</td>";
		$out .= "</tr>";
		for ($i = 0; $i < sizeof($this->item); $i++) {
			$out .= "<tr>";
			$out .= "<td class='DataTD BackTD'>";
			$out .= "<font class='DataFONT'>";
			$out .= "<a href='".$this->url[$i]."' target='".$this->frame[$i]."' class='link'>";
			$out .= $this->item[$i];
			$out .= "</a>";
			$out .= "</font>";
			$out .= "</td>";
			$out .= "</tr>";
		}
		$out .= "</table>";
		$out .= "</td></tr></table>";
		return $out;
	}
}

/*****************************************************************************************************
 Classe para gerar campo lookup
*/
class Lookup {
	var $title;
	var $nomeCampoForm;
	var $captionCampoForm;
	var $valorCampoForm;
	var $nomeTabela;
	var $nomeCampoChave;
	var $nomeCampoExibicao;
	var $nomeCampoAuxiliar;
	var $valorCampoFormDummy;
	var $upCase;
	var $size;
	var $WinWidth;
	var $WinHeight;
	var $ListaInicial;
	var $sql;
	var $stringBusqueda;  // Se usa para indicar un string de búsqueda en especial al editar el registro.  Solo se usa cuando el lookup es el complejo está basado en un string y no en una simple tabla
	var $readonly;
	var $NumForm; // Número del formulario donde se encuentra el objeto.  Por Default es 0	
	
	function Lookup() {
		// Establezco las medidas por defecto de ventana popup
		$this->WinWidth=500;
		$this->WinHeight=520;
		
		$this->upCase=true;
		$this->ListaInicial=1;		
		$this->size=LOOKUP_FIELDSIZE;
		$this->readonly=false;
		$this->NumForm=0; // por defecto el objeto va en el formulario 0
	}

	// define el número de formulario donde se encuetra el objeto que llamará al lookup
	function setNumForm($NumForm) {
		$this->NumForm=$NumForm; 
	}

	// define o nome do campo do formulário
	function setNomeCampoForm($caption,$umNome) {
		$this->captionCampoForm = $caption;
		$this->nomeCampoForm = $umNome;
	}

	// define o nome do campo auxiliar que será exibido no lookup
	function setNomeCampoAuxiliar($umNome) {
		$this->nomeCampoAuxiliar = $umNome;
	}

	// define o título que aparecerá na janela de lookup
	function setTitle($umTitulo) {
		$this->title = $umTitulo;
	}

	// define si el objeto es de solo lectura
	function readonly($readonly) {
		$this->readonly = $readonly;
	}

	// define un string especial de búsqueda si fuera necesario.  Esto es para cuando el string que genera la consulta
	// no es el mismo que se puede usar para efectuar la búsqueda al editar el registro.
	function setStringBusqueda($StringSql) {
		$this->stringBusqueda = $StringSql;
	}

	// define o valor inicial do campo do formulário
	function setValorCampoForm($umValor) {
		$this->valorCampoForm = $umValor;
//		$sql = "select ".$this->nomeCampoExibicao.", ".$this->nomeCampoChave." from ".$this->nomeTabela
//		     . " where ".$this->nomeCampoChave."=".$this->valorCampoForm;

		// He colocado comillas simples al dato '$this->valorCampoForm' para que funcione cuando el campo clave es varchar o text,
		// esto no afecta si el campo es integer o serial ya que Postgres lo interpreta como tal.

	    $numCampo=0;

		//verifico si la tabla es un string sql
		if(getSession($this->nomeTabela)){
			if($this->stringBusqueda) // Si exsite un string de búsqueda especial para efectuar al editar el registro
				$sql = $this->stringBusqueda;
			else
				$sql = getSession($this->nomeTabela);

			if(strpos(strtoupper($sql),"WHERE"))
				$sql .= " and $this->nomeCampoChave='$this->valorCampoForm'";
			else
				$sql .= " where $this->nomeCampoChave='$this->valorCampoForm'";

			$numCampo=1;
			}
		else
			//estructura la consulta para obtener el campo de exibicion
			$sql = "select $this->nomeCampoExibicao,$this->nomeCampoChave from
				$this->nomeTabela where $this->nomeCampoChave='$this->valorCampoForm'";

		$this->sql = $sql;
		$this->valorCampoFormDummy = getDbValue($sql,$numCampo);
	}

	// define o nome da tabela que será exibida no lookup
	function setNomeTabela($umNome) {
		$this->nomeTabela = $umNome;
	}

	// define o nome do campo chave que será devolvido ao campo do formulário
	function setNomeCampoChave($umNome) {
		$this->nomeCampoChave = $umNome;
	}

	// define o nome do campo que será exibido no lookup
	function setNomeCampoExibicao($umNome) {
		$this->nomeCampoExibicao = $umNome;
	}
	// define si la caja de texto donde se busca solicita letras mayusculas
	function setUpCase($upCase) {
		$this->upCase=$upCase;
	}
	// define el tamaño de la caja de texto
	function setSize($size) {
		$this->size=$size;
	}

	// define el ancho de la ventana
	function setWidth($width) {
		$this->WinWidth=$width;
	}

	// define la altura de la ventana
	function setHeight($height) {
		$this->WinHeight=$height;
	}

	// define si muestra una lista inicial al cargar el popup,  Por defecto muestra la lista.  Si el valor es false solo se mostrará la lista cuando se efectúe una búsqueda.
	function setListaInicial($ListaInicial) {
		$this->ListaInicial=$ListaInicial;
	}

	// retorna o bloco HTML que monta o campo lookup
	function writeHTML() {
		$out = "";
		$out .= "<input type='hidden' name='__Change_".$this->nomeCampoForm."' id='__Change_".$this->captionCampoForm."' value=0>"; // Para poder controlar un evnto Change en este objeto, ya que no es posible escribir nada en el.
		$out .= "<input type='hidden' name='".$this->nomeCampoForm."' id='".$this->captionCampoForm."' value='".$this->valorCampoForm."'>";
		$out .= "<input type='text' name='_Dummy".$this->nomeCampoForm."' id='".$this->captionCampoForm."' value='".$this->valorCampoFormDummy."' size='".$this->size."' readonly>";
		if($this->readonly){}
		else{
			$out .= "<img title=\"Clique aqui para abrir la lista de registros\" align='middle' style='cursor: pointer' src='". LOOKUP_IMAGEM ."' onClick=\"lookup(";
			$out .= "'".$this->nomeCampoForm."', '".$this->nomeTabela."', '".$this->nomeCampoChave."', '".$this->nomeCampoExibicao."', '".$this->nomeCampoAuxiliar."', '".$this->upCase."', '".$this->title."', ".$this->WinWidth.",".$this->WinHeight.",".$this->ListaInicial.",".$this->NumForm;
			$out .= ")\">";
		}
		return $out;
	}
}

//$this->upCase."', '".$this->title."',500";


/*****************************************************************************************************
	Classe para criação de abas
*/
class Abas {
	var $item;
	var $status;
	var $url;
	var $level;
	var $js;

	// adiciona uma aba
	// $nome : nome da aba
	// $status : ativa? true ou false
	// $url : link que será chamado (usar somente se inativa)
	// $level : nível de acesso mínimo que o usuário deve ter para visualizar esta aba
	function addItem($nome="Geral", $status=false, $url="", $level=0, $js="") {
		$this->item[] = $nome;
		$this->status[] = $status;
		$this->url[] = $url;
		$this->level[] = $level;
		$this->js[] = $js;		
	}

	// retorna bloco HTML que monta as abas
	function writeHTML() {
		$y = 2;
		$out  = "";
		$out .= "<table cellpadding='2' cellspacing='0' width='100%' border='0'>";
		$out .= "<tr>";
		$out .= "<td class='FundoABA' width='10px'>&nbsp;</td>";
		for ($x = 0; $x < sizeof($this->item); $x++) {
			if (isValidUser($this->level[$x])) {
				if ($this->status[$x]) {
					$out .= "<td nowrap class='SelecionadaABA'><font class='SelecionadaFontABA'>&nbsp;" . $this->item[$x] . "&nbsp;</font></td>";
				} else {
					$out .= "<td nowrap class='NaoSelecionadaABA'>";
					$out .= "<font class='NaoSelecionadaFontABA'>&nbsp;";
					$out .= "<a href='".$this->url[$x]."' target='content' class='aba' ".$this->js[$x]." >";
					$out .= $this->item[$x];
					$out .= "</a>";
					$out .= "&nbsp;</font></td>";
				}
			}
			$out .= "<td class='FundoABA' width='1px'></td>";
			$y+=2;
		}
		$out .= "<td class='FundoABA' width='100%'>&nbsp;</td>";
		$out .= "</tr>";
		$out .= "<tr>";
		$out .= "<td colspan='$y' height='4px' class='SelecionadaABA'></td>";
		$out .= "</tr>";
		$out .= "</table>";
		return $out;
	}
}

/*****************************************************************************************************
	Classe para generar botones
*/
class Button {
	var $nome;
	var $url;
	var $target;
	var $level;
	var $iduser;
	var $align;
	var $style;
	var $type;
	var $styleAll;
	var $setDiv;

	function Button() {
		$this->styleAll='botao';
		$this->align="acoes";
		$this->setDiv=TRUE;
	}

	/*
		Adiciona un item al set de botones 
		$nome : Caption del Boton
		$url : url que se visitará.  Si contiene la expresión 'javascri' se activa el onclick() para llamar una función javascript
		$target="content" : Destino donde se cargará el url
		$level=0 : nível de acesso mínimo que el usuario debe tener para visualizar este botón
				0 --> Sin nivel
				1 --> Visitante
				2 --> Operador
				3 --> Supervisor
		$idUser=0 : Se envia el id del usuario para verifica que sea igual al id del usuario de registro, especial para el boton guardar.
		$style='' : Clase css que se aplicará al botón.
		$type='' : tipo ejm. 'button'
	*/
	function addItem($nome, $url, $target="content", $level=0, $idUser=0, $style='', $type='') {
		$this->nome[] = $nome;
		$this->url[] = $url;
		$this->target[] = $target;
		$this->level[] = $level;
		$this->iduser[] = $idUser;
		$this->style[]=$style?$style:$this->styleAll;
		$this->type[] = $type;
	}

	function align($align='') {
		if ($align=="C")
			$this->align="acoescenter";
		else
			if ($align=="L"){
				$this->align="acoesleft";
				}
			else
				$this->align="acoes";
		}

	function setStyle($style){
		$this->styleAll=$style;
	}
	/*
	funcion que setea el parametro div (el cual permite la alineación de los botones)
		TRUE: coloca div
		FALSE: no coloca div
	*/
	function setDiv($setDiv){
		$this->setDiv=$setDiv;
	}

	/*
		Retorna o código HTML com o deck de botões
	*/
	function writeHTML() {
		if($this->setDiv)
			$out = "<div class='$this->align'>";

		for ($x=0; $x<sizeof($this->nome); $x++) {
			// verifica el nivel de acceso
			if (isValidUser($this->level[$x]))
				//verifica si el id de usuario logeado=id de usuario de registro, especial para el boton gurdar

				if (($this->iduser[$x]==0)||(getSession("sis_userid")==$this->iduser[$x])){


					if ($this->type[$x]=='button')
						$out .= "<input type=\"button\" class=\"".trim($this->style[$x])."\" id=\"".$this->nome[$x]."\" name=\"".$this->nome[$x]."\" value=\"".iif(strpos($this->nome[$x],'-'),">",0,substr($this->nome[$x],0,strpos($this->nome[$x],'-')),$this->nome[$x])."\" onClick=\"".$this->url[$x]."\">";
					else{
						$out .= "&nbsp;";
						if(strpos($this->url[$x],"avascript"))
					      $out .= "<a class='".$this->style[$x]."' href='#' onClick=\"".$this->url[$x]."\"";
						else
					      $out .= "<a class='".$this->style[$x]."' href=\"".$this->url[$x]."\"";

					    $out .= " id=\"".
						trim($this->nome[$x]).
						"\" target='".
						$this->target[$x].
						"'>&nbsp;".
						$this->nome[$x].
						"&nbsp;</a>";
					}
			}

		}
		if($this->setDiv)
			return $out.="</div>";

		return $out;
	}
}

/*****************************************************************************************************
	Classe para controlar erros da página
*/
class Erro {
	var $strErro;
	function addErro($erro='') {
		$this->strErro .= $erro . '\n';
	}
	function hasErro() {
		return strlen($this->strErro)>0;
	}
	function toString() {
		return $this->strErro;
	}
}

/*****************************************************************************************************
	funion para recuperar as variáveis GET e POST
*/
function getParam($param_name) {
	$param_value = "";
	if (isset($_POST[$param_name])) {
		$param_value = $_POST[$param_name];
	} else if(isset($_GET[$param_name])) {
		$param_value = $_GET[$param_name];
	}
	return $param_value;
}

/*****************************************************************************************************
	función para recuperar variáveis de sessão
*/
function getSession($param_name) {
	return $_SESSION[$param_name];
}

/*****************************************************************************************************
	función para definir variáveis de sessão
*/
function setSession($param_name, $param_value) {
	$_SESSION[$param_name] = $param_value;
}

/*****************************************************************************************************
	formatação de texto para muestra, pode ser adaptado conforme necessidade do sistema
*/
function formataTexto($texto) {
	// quebra de linha
	$texto = str_replace(chr(13),"<br>",$texto);

	return $texto;
}

/*****************************************************************************************************
	monta select de data
*/
function formDate($nome_campo, $data="") {
	//----- monta select de dia
	if ($data!="") {
		$aData = explode("-",$data);
		$dia_hoje = $aData[2];
		$mes_hoje = $aData[1];
		$ano_hoje = $aData[0];
	}
	echo "<select name=\"" . $nome_campo . "_dia\">\n";
	echo "<option value=\"\">--</option>\n";
	for ($i=1; $i <= 31; $i++) {
		$xdia = $i < 10?"0".$i:$i;
		$selected = $dia_hoje==$xdia?" selected":"";
		echo "<option value=\"" . $xdia . "\" $selected>" . $xdia . "</option>\n";
	}
	echo "</select>\n";

	//----- monta select do mes
	$aMes = array("nulo","Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
	echo "&nbsp;<select name=\"" . $nome_campo . "_mes\">\n";
	echo "<option value=\"\">--</option>\n";
	for ($i=1; $i <= 12; $i++) {
		$xmes = $i < 10?"0".$i:$i;
		$selected = $mes_hoje==$xmes?" selected":"";
		echo "<option value=\"" . $xmes . "\" $selected>" . $aMes[$i] . "</option>\n";
	}
	echo "</select>\n";

	//----- monta select de ano
	echo "&nbsp;<select name=\"" . $nome_campo . "_ano\">\n";
	echo "<option value=\"\">--</option>\n";
	for ($i=date("Y"); $i <= date("Y")+1; $i++) {
		$selected = $ano_hoje==$i?" selected":"";
		echo "<option value=\"" . $i . "\" $selected>" . $i . "</option>\n";
	}
	echo "</select>\n";
}

/*****************************************************************************************************
	monta select de hora
*/
function formTime($nome_campo, $hora="") {
	//----- monta select de hora
	if ($hora!="") {
		$aHora = explode(":",$hora);
		$hora_hoje = $aHora[0];
		$min_hoje = $aHora[1];
	}

	echo "<select name=\"" . $nome_campo . "_hora\">\n";
	echo "<option value=\"\">--</option>\n";
	for ($i=0; $i <= 23; $i++) {
		$xhora = $i < 10?"0".$i:$i;
		$selected = $hora_hoje==$xhora?" selected":"";
		echo "<option value=\"" . $xhora . "\" $selected>" . $xhora . "</option>\n";
	}
	echo "</select>\n";

	//----- monta select do minuto
	echo "&nbsp;<select name=\"" . $nome_campo . "_minuto\">\n";
	echo "<option value=\"\">--</option>\n";
	for ($i=0; $i <= 55; $i+=5) {
		$xmin = $i < 10?"0".$i:$i;
		$selected = $min_hoje==$xmin?" selected":"";
		echo "<option value=\"" . $xmin . "\" $selected>" . $xmin . "</option>\n";
	}
	echo "</select>\n";
}

/*****************************************************************************************************
	gerador de listbox
	$sql : expressão sql que monta a lista (selecionar apenas 2 campos com os nomes "id" e "val"
	$name : nome do campo que será criado
	$default : valor inicial do campo
	$todos : texto indicativo, caso a lista permita valor null
	$js : expressão javascript
	$size : Número de elementos que se mostrarán
		1 --> Se mostrará como combo
		2 a más --> Se mostrará como una lista
	$css : Para indicar la clase de estilos o algún estilo especial.
		ejm: "class='miclase'"
			 "style='width:80%'"
*/
function listboxField($msjvalid, $sql, $name, $default=0, $todos="", $js="", $size=1, $css="") {
	global $conn;
	$rs = new query($conn,$sql);

	$result="<select name=\"$name\" id=\"$msjvalid\" $css size=$size onKeyPress='return formato(event,form,this)' $js>\n";
	if ($todos!="") {
		$result.= "<option value=\"\">$todos</option>\n";
	}
	if(is_array($sql)){ /* Si es una sentencia SQL */
		foreach($sql as $k => $v){
			$id = $k;
			$val = substr($v,0,100);
			if ($default == $id) {$selected="selected";} else {$selected="";} // Para que aparezca seleccionado un valor por defecto que se le envie o si se hace un submit al form
			$result.="<option value=\"$id\" $selected>$val</option>\n";
		}
	}else{ /* Si es array */
		while ($rs->getrow()) {
			$id = $rs->field($rs->fieldname(0));
			$val = substr($rs->field($rs->fieldname(1)),0,100);
			if ($default == $id) {$selected="selected";} else {$selected="";} // Para que aparezca seleccionado un valor por defecto que se le envie o si se hace un submit al form
			$result.="<option value=\"$id\" $selected>$val</option>\n";
		}
	}
	$result.="</select>\n";
	return $result;
}


/*****************************************************************************************************
	generador de listbox ajax
	$sql : expressão sql que monta a lista (selecionar apenas 2 campos com os nomes "id" e "val"
	$name : nome do campo que será criado
	$default : valor inicial do campo
	$todos : texto indicativo, caso a lista permita valor null
	$js : expressão javascript
*/
function listboxAjaxField($msjvalid, $sql, $name, $default=0, $todos="", $js="", $NameDiv="") {
	/* Obtengo el valor del control si se efectúa un submit al form */
	$idValSubmit=getParam($name);

	global $conn;
	$rs = new query($conn,$sql);

	$result="<span id=\"$NameDiv\" name=\"$NameDiv\" align=\"left\">";

	$result.="<select name=\"$name\" id=\"$msjvalid\" size=1 onKeyPress='return formato(event,form,this)' $js>\n";
	if ($todos!="") {
		$result.= "<option value=\"\">$todos</option>\n";
	}
	while ($rs->getrow()) {
		$id = $rs->field($rs->fieldname(0));
		$val = substr($rs->field($rs->fieldname(1)),0,60);
		if ($default == $id or $idValSubmit==$id) {$selected="selected";} else {$selected="";} // Para que aparezca seleccionado un valor por defecto que se le envie o si se hace un submit al form
		$result.="<option value=\"$id\" $selected>$val</option>\n";
	}
	$result.="</select></span>\n";
	return $result;
}

/*****************************************************************************************************
	verifica si el usuario puede acessar a la página
	$nivel : valor numerico que define el nível jerarquico de acesso
*/
function verificaUsuario($nivel=0) {
	if ($nivel > 0) {
		$loginFile="../content/login.php";
		if(!@file("$loginFile")) $loginFile="../../content/login.php";
		if (getSession("sis_apl")!=SIS_APL_NAME) {
			redirect("$loginFile?querystring=".urlencode(getenv("QUERY_STRING"))."&ret_page=".urlencode(getenv("REQUEST_URI")));
			die();
		} else if ((!isset($_SESSION["sis_level"]) || getSession("sis_level") < $nivel)) {
			redirect("$loginFile?querystring=".urlencode(getenv("QUERY_STRING"))."&ret_page=".urlencode(getenv("REQUEST_URI")));
			die();
		}
	}
}

/*****************************************************************************************************
	función que verifica se o usuario está dentro do nível
	retorna boolean
*/
function isValidUser($level=0) {
	return (($level==0)||(getSession("sis_level")>=$level));
}

/*****************************************************************************************************
	gera senha aleatória
*/
function geraSenha($tamanho=6) {
	$senha = "abcdefghjkmnpqrstuvxzwyABCDEFGHIJLKMNPQRSTUVXZYW23456789";
	srand ((double)microtime()*1000000);
	for ($i=0; $i<$tamanho; $i++) {
		$password .= $senha[rand()%strlen($senha)];
	}
	return $password;
}

/*****************************************************************************************************
	retorna o valor de um campo através de expressão sql
*/
function getDbValue($sql,$numCampo=0,$conectar=0) {
	global $conn;
	if($conectar){
		$connTemp = new db();
		$connTemp->open();
		$rs = new query($connTemp, $sql);
	}else{
		$rs = new query($conn, $sql);
	}
	if($rs->numrows()<1) {
		$valor = "";
	} else {
		$rs->getrow();
		if(ctype_digit($numCampo)) //si son digitos
			$nomecampo = $rs->fieldname($numCampo);
		else //si son caracteres
			$nomecampo = $numCampo;
			
		$valor = $rs->field($nomecampo);
	}
	$rs->free();
	if($conectar){
		$connTemp->close();
	}
	return $valor;
}

/*****************************************************************************************************
	Soma numero de dias a uma data
	Sintaxe: somadata( "01/12/2002",5 );
	Retorno: 06/12/2002
*/
function somadata($data, $nDias) {
	if (!isset( $nDias )) {
		$nDias = 1;
	}
	$aVet = Explode("/",$data);
	return date("d/m/Y",mktime(0,0,0,$aVet[1],$aVet[0]+$nDias,$aVet[2]));
}

/*****************************************************************************************************
	función para gerar campos radio
	$arr : array de valores, cada elemento deve ter a chave e o label separados por vírgula
	       exemplo: {"1,Solteiro","2,Casado","3,Separado"}
	$name : nome do campo
	$sel : valor inicial do campo
	$js : expressão javascript
	$posi: 'V'-> VERTICAL, 'H'->HORIZONTAL
*/
function radioField($msjvalid, $arr,$name,$sel = "", $js="", $posi='V') {
	$out = "";

	while (list($key, $val) = each($arr)) {
		$string = explode(",",$val);
		$label = $string[1];
		$valor = $string[0];
		$select_v = ($sel && $valor == $sel)?" checked":"";
		$out .= "<input type=radio name=\"$name\" id='$msjvalid' value=\"$valor\" $select_v $js > $label"
			.iif($posi,'==','V','<br>','&nbsp;')."\n";
	}
	return $out;
}

/*****************************************************************************************************
	función para gerar campo de data com calendário popup
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
*/
function dateField($msjvalid, $fieldname, $fieldvalue="", $js="") {
	$out = "";
	$out .= "<input type='text' id='$msjvalid' name='$fieldname' value='$fieldvalue' size='11' maxlength='10' $js>";
	$out .= "<a href=\"javascript:showCalendar('$fieldname')\">";
	$out .= "<img src='../inc/calendario/calendario.gif' border='0'>";
	$out .= "</a>";
	return $out;
}

/*****************************************************************************************************
	función para gerar campo de texto
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
	$lenght : tamanho do campo
	$maxlenght : capacidade do campo
	$js : expressão javascript
*/
function textField($msjvalid, $fieldname, $fieldvalue="", $length=40, $maxlength=40, $js="") {
	$out = "";
	$fieldvalue=htmlspecialchars($fieldvalue,ENT_QUOTES); // para el problema de las comillas
	$out .= "<input type='text' name='$fieldname' id='$msjvalid' value='$fieldvalue' size='$length' maxlength='$maxlength' onKeyPress='return formato(event,form,this,".$length.")' $js>";
	return $out;
}
/*****************************************************************************************************
	función para gerar campo de tipo numerico
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
	$lenght : tamanho do campo
	$maxlenght : capacidade do campo
	$numdecimal: número de decimales
	$coma: si coloca o no la coma
	$js : expressão javascript
*/
function numField($msjvalid, $fieldname, $fieldvalue="", $length=40, $maxlength=40, $numdecimal=0, $coma=false, $js="") {
	$out = "";
	$out .= "<input type='text' name='$fieldname' id='$msjvalid' STYLE='text-align:right' value='$fieldvalue' size='$length' maxlength='$maxlength'"
		 . " onFocus=\"replaceChars(this,',','')\"  onKeyPress='return formato(event,form,this,".$maxlength.",".$numdecimal.")' ";

	if($coma or strtoupper(substr($fieldname,0,1))=='Z')
		$out .= "onBlur=\"commaSplit(this,1,$maxlength,$numdecimal)\" ";

	 $out .=  " $js>";

	return $out;
}


/*****************************************************************************************************
	función para gerar campo de password
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
	$lenght : tamanho do campo
	$maxlenght : capacidade do campo
	$js : expressão javascript
*/
function passwordField($msjvalid, $fieldname, $fieldvalue="", $lenght=20, $maxlenght=20, $js="") {
	$out = "";
	$out .= "<input type='password' name='$fieldname' id='$msjvalid' value='$fieldvalue' size='$lenght' maxlenght='$maxlenght' $js>";
	return $out;
}

/*****************************************************************************************************
	función para gerar campo de checkbox
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
	$expr : expressão booleana que define se o checkbox está marcado ou não
	$js : expressão javascript
*/
function checkboxField($msjvalid, $fieldname, $fieldvalue="", $expr, $js="") {
	$out = "";
	$checked = $expr?" checked":"";
	$out .= "<input type='checkbox' name='$fieldname' id='$msjvalid' value='$fieldvalue' $checked $js>";
	return $out;
}

/*****************************************************************************************************
	función para gerar campo file
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
	$expr : expressão que retorna um boolean
	$js : expressão javascript
*/
function fileField($msjvalid, $fieldname, $fieldvalue="", $lenght=30, $js="") {
	$out = "";
	$out .= "<input type='hidden' name='Fi_".$fieldname."' value='$fieldvalue'>";
	$out .= "<input type='file' name='$fieldname' id='$msjvalid' size='$lenght' $js>";
	if (strlen(trim($fieldvalue))>0 && strpos($fieldvalue,"standar")==0) {
		$out .= "<br>".FILEFIELD_ARQUIVOATUAL." <b>" . str_replace(PUBLICUPLOAD,"",$fieldvalue). "</b>&nbsp;" . "<input type='checkbox' name='".$fieldname."_excluir' value='1'> ".FILEFIELD_REMOVER;
	}
	return $out;
}

/*****************************************************************************************************
	función para gerar lista de campos checkbox
	$formField : nome do campo no formulário
	$formFieldValue : valor do campo no formulário
	$table : nome da tabela que formará os checkboxes
	$keyField : campo chave da tabela
	$showField : campo que será exibido nos checkboxes
	$condition : condição de muestra dos registros (cláusula WHERE)
*/
function multipleCheckboxField ($formField, $formFieldValue, $table, $keyField, $showField, $condition="") {
	$connTemp = new db();
	$connTemp->open();
	if ($condition!="") $where = "WHERE $condition";
	$sql = "SELECT $keyField, $showField FROM $table $where ORDER BY $showField";
	$rs = new query($connTemp, $sql);
	$lista = explode(",",$formFieldValue);
	$out = "";
	while ($rs->getrow()) {
		$checked = "";
		if (in_array($rs->field($keyField),$lista)) $checked = " checked";
		$out .= "<input type='checkbox' name='".$formField."[]' id='$formField' value='".
		        $rs->field($keyField).
				  "' $checked> ".
				  $rs->field($showField).
				  "<br>";
	}
	$connTemp->close();
	return $out;
}

/*****************************************************************************************************
        función para gerar lista de campos checkbox
        $formField      : nome do campo no formulário
        $formFieldValue : valor do campo no formulário (valores separados por ,)
        $table          : nome da array que formará os checkboxes ("0,Teste")
*/
function multipleCheckboxArray ($formField, $formFieldValue, $elementos) {
	$lista     = explode(",",$formFieldValue);
	$out       = "";
	$qtd       = count($elementos);
	for ($i=0;$i<$qtd;$i++){
		$checked = "";
		$dado    = Explode(",",$elementos[$i]);
		if (in_array($dado[0],$lista)) $checked = " checked";
		$out .= "<input type='checkbox' name='".$formField."[]' id='$formField' value='".
		        $dado[0].
				"' $checked> ".
				$dado[1].
				"<br>";
	}
	return $out;
}

/*****************************************************************************************************
	función para gerar campo textarea com controle de caracteres via javascript
	$nome_campo : nome do campo que será criado
	$valor_inicial : valor inicial do campo
	$num_linhas : número de linhas do campo
	$num_colunas : número de colunas do campo
	$maximo : quantidade máxima de caracteres
*/
function textAreaField($msjvalid, $nome_campo, $valor_inicial="", $num_linhas=5, $num_colunas=40, $maximo=200, $readonly='') {
	$str = "<textarea ".
	       "name='$nome_campo' ".
		   "id='$msjvalid' ".
		   "rows='$num_linhas' ".
		   "cols='$num_colunas' ";

		   if($nome_campo){
//			  	$str.=  "onKeyPress='return formato(event,form,this)' and textCounter(this,this.form._counter".$nome_campo.",$maximo);' ";
			  	$str.=  "onKeyPress='textCounter(this,this.form._counter".$nome_campo.",$maximo);' ";
			  	$str.=  "onKeyUp='textCounter(this,this.form._counter".$nome_campo.",$maximo);' ";
		   }
		   $str.=  " $readonly >".
		   $valor_inicial.
		   "</textarea><br>";

		   if($nome_campo)
				$str.=
				   	"<input class='DataTD BackTD' ".
			   		"style='border: 0px; text-align: right' ".
				   	"type='text' ".
				   	"name='_counter".$nome_campo."' ".
				   	"maxlength='4' readonly size='4' value='".($maximo-strlen($valor_inicial))."'> ".TEXTAREA_RESTANTES;
	return $str;
}

/*****************************************************************************************************
	función para gerar link html
*/
function addLink($titulo, $url, $alt="", $target="content", $class="link") {
	return "<a title=\"$alt\" class=\"$class\" href=\"$url\" target=\"$target\">$titulo</a>";
}



/*****************************************************************************************************
 función para verificar campo duplicado
*/
function isDuplicated($tabela, $campo_valor, $campo_chave, $valor, $chave) {
	$retorno = false;
	if (strlen($valor)) {
		$iCount = 0;
		if ($chave=="") {
			$iCount = getDbValue("SELECT count(*) AS qtde FROM $tabela WHERE $campo_valor='$valor'");
		} else {
			$iCount = getDbValue("SELECT count(*) AS qtde FROM $tabela WHERE $campo_valor='$valor' AND NOT ($campo_chave=$chave)");
		}
		if ($iCount > 0) $retorno = true;
	}
	return $retorno;
}

/*****************************************************************************************************
 Tratamento da data para formatos apenas numéricos
 Recebe uma data no formato yyyymmdd, coloca as barras e ordena em dd/mm/yyyy
*/
function dtod($data) {
     $data_ano = substr($data,0,4);
     $data_mes = substr($data,4,2);
     $data_dia = substr($data,6,2);
     return $data_dia."/".$data_mes."/".$data_ano;
}

/*****************************************************************************************************
	Converte yyyy-mm-dd hh:mm:ss em dd/mm/yyyy hh:mm:ss
	función auxiliar, use stod()
*/
function _stodt($str) {
	$aStr = explode(" ",$str);

	$d = $aStr[0];
	$t = $aStr[1];
	$aD = explode("-",$d);

	$datetime = $aD[2] . "/" . $aD[1] . "/" . $aD[0] . " " . $t;
	return $datetime;
}

/*****************************************************************************************************
	Converte dd/mm/yyyy hh:mm:ss em yyyy-mm-dd hh:mm:ss
	función auxiliar, use dtos()
*/
function _dttos($datetime) {
	$aDT = explode(" ",$datetime);
	$s = $aDT[0];
	$t = $aDT[1];
	$aS = explode("/",$s);
	$str = $aS[2] . "-" . $aS[1] . "-" . $aS[0] . " " . $t;
	return $str;
}

/*****************************************************************************************************
	converte AAAA-MM-DD em DD/MM/AAAA
*/
function stod($texto) {
	if ($texto=="") return "";
	if (strlen($texto)>10) {
		return _stodt($texto);
	} else {
		$data = explode("/",$texto);
		return $data[2] . "/" . $data[1] . "/" . $data[0];
	}
}

/*****************************************************************************************************
	converte DD/MM/AAAA para AAAA-MM-DD
*/
function dtos($data) {
	if ($data=="") return "";
	if (strlen($data)>10) {
		return _dttos($data);
	} else {
		$texto = explode("-",$data); //en php las consultas con fechas, postgres las devuelve en yyyy-mm-dd (OJO CON EL GUION)
		return $texto[2] . "/" . $texto[1] . "/" . $texto[0];
	}
}

/*****************************************************************************************************
 función para formatar data
*/
function fdata($data,$formato="d/m/Y"){
	$months = array("january"=>"Janeiro","february"=>"Fevereiro","march"=>"Março","april"=>"Abril","may"=>"Maio","june"=>"Junho","july"=>"Julho","august"=>"Agosto","september"=>"Setembro","october"=>"Outubro","november"=>"Novembro","december"=>"Dezembro");
	$weeks = array("sunday"=>"Domingo","monday"=>"Segunda","tuesday"=>"Terça","wednesday"=>"Quarta","thursday"=>"Quinta","friday"=>"Sexta","saturday"=>"Sábado");
	$months3 = array("jan"=>"jan","feb"=>"fev","mar"=>"mar","apr"=>"abr","may"=>"mai","jun"=>"jun","jul"=>"jul","aug"=>"ago","sep"=>"set","oct"=>"out","nov"=>"nov","dec"=>"dez");
	$weeks3 = array("sun"=>"dom","mon"=>"seg","tue"=>"ter","wed"=>"qua","thu"=>"qui","fri"=>"sex","sat"=>"sab");

	$data = strtolower(date($formato,strtotime($data)));
	$data = strtr($data,$months);
	$data = strtr($data,$weeks);
	$data = strtr($data,$months3);
	$data = strtr($data, $weeks3);
	return $data;
}

/*****************************************************************************************************
	ayuda on-line
	Gera um ícone na página que quando clicado abre uma janela popup
	$titulo : título da ayuda
	$msg : texto da ayuda
*/
function help($titulo="",$msg="") {
	$out = "";
	$out .= "&nbsp;<img title=\"Clique aqui para obter ayuda\" style=\"cursor: pointer\" align=middle src=\"" . HELP_IMAGEM . "\" ".
           "onclick=\"hint=window.open('', 'hint', 'width=400, height=300, resizable=no, scrollbars=yes, top=80, left=450');".
           "hint.document.write('<HTML><HEAD><TITLE>AYUDA</TITLE></HEAD><BODY onClick=\'self.close();\' style=\'background-color: ".HELP_CORFUNDO."\'>');".
           "hint.document.write('<P style=\'font-size: ".HELP_TAMANHOTITULO."; font-weight: bold; color: ".HELP_CORTITULO."; font-family: ".HELP_FONTTITULO."\'>');".
           "hint.document.write( '$titulo' );".
           "hint.document.write('</P>');".
           "hint.document.write('<P style=\'font-size: ".HELP_TAMANHOTEXTO."; color: ".HELP_CORTEXTO."; font-family: ".HELP_FONTTEXTO."\'>');".
           "hint.document.write( '$msg' );".
           "hint.document.write('</P>');".
           "hint.document.write('</BODY></HTML>');".
           "\">&nbsp";
	return $out;
}

/*****************************************************************************************************
	Diseño de título da página
*/
function pageTitle($titulo,$subtitulo="",$img="",$classTitulo='titulo') {
	if ($titulo != "") {
		if($img)
			echo "<div class='$classTitulo'><img src=\"$img\">&nbsp;$titulo</div>";		
		else
			echo "<div class='$classTitulo'>$titulo</div>";
	}
	if ($subtitulo != "") {
		echo "<div class='subtitulo'>$subtitulo</div>";
	}
	echo "<hr noshade class='linha'>";
}

/*****************************************************************************************************
	muestra de alert en javascript (cuando el mensaje incluye comillas dobles)
*/
function alert($msg,$exit=1) {
	$msg=ereg_replace("\n","\\n",$msg); // Para controlar los retornos de carro que devuelve el postgres
	$msg=ereg_replace("\"","\'",$msg); // Para controlar los retornos de carro que devuelve el postgres
	echo "<script language='JavaScript'>";
	echo "alert(\"$msg\");";
	echo "</script>";
	if($exit) // recibe $exit=0 para el caso donde se llama al alert y deseamos que se sigan ejecutando las siguientes líneas. Ejm. AvanzLookup
		exit;
}

/*****************************************************************************************************
	Provoca redirect via javascript
*/
function redirect($url, $target="content") {
	echo "<script language='JavaScript'>";
	echo "if(top==self) top.location='../index.php';";
	echo "else parent.$target.document.location='$url';";
	echo "</script>";
}

/*****************************************************************************************************
	crea un scroll, con los datos enviado
*/

function scrollBlock($id="", $contenido="", $altura="300px", $anchura="100%", $class="") {
   $out  = "<div id=\"$id\" class=\"$class\" style='height: $altura; width: $anchura; ";
   $out .= "overflow: auto; border: 0px; padding: 1px;'>";
   $out .= $contenido;
   $out .= "</div>";
   return $out;
}

/*****************************************************************************************************
 Limita o tamanho de um texto colocando "..." no final da string
*/
function strLimit($str, $size, $showDots = false) {
	if (strlen($str) > $size) {
		$tmp = substr($str, 0, $size);
		$p = strrpos($tmp, ' ');
		if ($p) {
			$str = substr($tmp, 0, $p);
		} else {
			$str = $tmp;
		}
		return $str . ($showDots ? "..." : "");
	} else {
		return $str;
	}
}
/*****************************************************************************************************
if lineal
*/
function iif($var1,$cond,$var2,$res1,$res2)
{
$_eval="if(\"$var1\"". $cond ." \"$var2\") { \$solution = \$res1  ;} else { \$solution = \$res2 ;}";
eval($_eval);
return($solution);
}

/*****************************************************************************************************
*/
function dateFormat($input_date, $input_format, $output_format) {
	if(!$input_date)
		return '';

   preg_match("/^([\w]*)/i", $input_date, $regs);
   $sep = substr($input_date, strlen($regs[0]), 1);
   $label = explode($sep, $input_format);
   $value = explode($sep, $input_date);
   $array_date = array_combine($label, $value);
   if (in_array('Y', $label)) {
       $year = $array_date['Y'];
   } elseif (in_array('y', $label)) {
       $year = $year = $array_date['y'];
   } else {
       return false;
   }

   $output_date = date($output_format, mktime(0,0,0,$array_date['m'], $array_date['d'], $year));
   return $output_date;
}


/*****************************************************************************************************
 Classe para generar un arbol
*/
class Tree {
	var $title;
	var $nameCampoForm;
	var $captionCampoForm;
	var $valorCampoForm;
	var $nameTabla;
	var $nameCampoClave;
	var $nameCampoMuestra;
	var $nameCampoDepen;
	var $valorCampoFormDummy;
	var $ExcluNivelMax;
	var $iniStruct;
	var $setBuscar;
	var $size;
	var $width;
	var $height;
	var $sql;
	var $setTreeAvanz;
	var $page;
	
	function Tree() {
		$this->upCase=true;
		$this->setBuscar=true;
		$this->setTreeAvanz=false;
		$this->size=LOOKUP_FIELDSIZE;
		$this->width=500;
		$this->height=520;
		$this->page="'/mysiga/inc/tree.php?p=0',";
	}

	function setTreeAvanz($set) {
		$this->setTreeAvanz=$set;
		$this->page="'/mysiga/inc/treeAvanz.php?p=0',";
	}

	// define o nome do campo do formulário
	function setNameCampoForm($caption,$umNome) {
		$this->captionCampoForm = $caption;
		$this->nameCampoForm = $umNome;
	}

	// define o nome do campo auxiliar que será exibido no lookup
	function setNameCampoDepen($umNome) {
		$this->nameCampoDepen = $umNome;
	}

	// define o título que aparecerá na janela de lookup
	function setTitle($umTitulo) {
		$this->title = $umTitulo;
	}

	// define o valor inicial do campo do formulário
	function setValorCampoForm($umValor,$Valor='') {
		$this->valorCampoForm = $umValor;
		if ($this->setTreeAvanz) //si  utiliza el tree mejorado
//			$this->valorCampoFormDummy = $Valor;
			$this->valorCampoFormDummy = htmlspecialchars($Valor,ENT_QUOTES); // para el problema de las comillas
		else{
			// He colocado comillas simples al dato '$this->valorCampoForm' para que funcione cuando el campo clave es varchar o text,
			// esto no afecta si el campo es integer o serial ya que Postgres lo interpreta como tal.
			$sql = "select $this->nameCampoMuestra,$this->nameCampoClave from
					$this->nameTabla where $this->nameCampoClave='$this->valorCampoForm'";
			$this->sql = $sql;
			$this->valorCampoFormDummy = getDbValue($sql);
		}
	}

	// define o nome da tabela que será exibida no lookup
	function setNameTabla($umNome) {
		$this->nameTabla = $umNome;
	}

	// define o nome do campo chave que será devolvido ao campo do formulário
	function setNameCampoClave($umNome) {
		$this->nameCampoClave = $umNome;
	}

	// define o nome do campo que será exibido no lookup
	function setNameCampoMuestra($umNome) {
		$this->nameCampoMuestra = $umNome;
	}
	// define si en el arbol solo se elije el nivel más detallado.
	function setExcluNivelMax($nivelMax) {
		$this->ExcluNivelMax=$nivelMax;
	}
	// define desde donde se construye el arbol .
	function setIniStruct($Struct) {
		$this->iniStruct=$Struct;
	}
	// define si se muestra el icono de busqueda
	function setBuscar($Buscar) {
		$this->setBuscar=$Buscar;
	}
	// define el tamaño de la caja de texto
	function setSize($size) {
		$this->size=$size;
	}

	// define el ancho de la ventana
	function setWidth($width) {
		$this->width=$width;
	}


	// define la altura de la ventana
	function setHeight($height) {
		$this->height=$height;
	}

	// define el nombre de la página
	function setNamePage($namePage) {
		$this->page=$namePage;
	}

	// retorna o bloco HTML que monta o campo lookup
	function writeHTML() {
		$out = "";
		$out .= "<input type='hidden' name='__Change_".$this->nameCampoForm."' id='__Change_".$this->captionCampoForm."' value=0>";
		$out .= "<input type='hidden' name='".$this->nameCampoForm."' id='".$this->captionCampoForm."' value='".$this->valorCampoForm."'>";
		$out .= "<input type='text' name='_Dummy".$this->nameCampoForm."' id='".$this->captionCampoForm."' value='".$this->valorCampoFormDummy."' size='".$this->size."' readonly>";
		if($this->setBuscar){
			$out .= "<img title=\"Clique aqui para abrir el arbol de registros\" align='middle' style='cursor: pointer' src='". LOOKUP_IMAGEM ."' onClick=\"tree(";
			$out .= $this->page;
			$out .= "'".$this->nameCampoForm."', '".$this->nameTabla."', '".$this->nameCampoClave."', '".$this->nameCampoMuestra."', '".$this->nameCampoDepen."', '".$this->ExcluNivelMax."', '".$this->iniStruct."', '".$this->title."','".$this->width."', '".$this->height."'";
			$out .= ")\">";
		}
		return $out;
	}
}



/*****************************************************************************************************
	coloca una imagen en un elemento div, de tal manera q pueda ubicarse en cualquier parte de la hoja
	$fieldname: nombre del campo
	$image: archivo de imagen
	$ImgWidth: ancho de la imagen
	$ImgHeight: alto de la imagen
	$DivTop: alto en q se colocara el DIV en ralcion al borde superior de la hoja
	$DivWidth: ancho del div
	$DivHeight:	alto del div
	$js : expresión javascript
*/
function divImage($fieldname, $image, $ImgWidth, $ImgHeight, $DivTop, $DivWidth, $Divleft, $classFoto, $js='') {
	$result="<div align=\"center\" style=\"{position:absolute;  width:$DivWidth; left:$Divleft; margin-top:$DivTop}\">".
		"<div class=\"$classFoto\" >".
		"<img src=\"$image\" id=\"DivImage\" width=\"$ImgWidth\" height=\"$ImgHeight\"  style=\"border-color:#7F9DB9\">".
		"</div>".
		"<br>";
		if($fieldname)
			$result.=fileField('',$fieldname, $image, 30, $js);

		$result.="</div>";

	return $result;
}

function verif_framework(){
	//-- ASEGURA QUE LA PAGINA SE HAYA CARGADO DESDE EL INDEX.
	echo "<script language='JavaScript'>";
	echo "if(top==self) top.location='../index.php'";
	echo "</script>";
}


/*****************************************************************************************************
 Classe para gerar campo lookup avanzadp
*/
class AvanzLookup {
	var $title;
	var $nameCampoForm;
	var $nameHideCampoForm;
	var $captionCampoForm;
	var $valorCampoForm;
	var $valorCampoFormDummy;
	var $size;
	var $width;
	var $height;
	var $page; /* Página que se cargará en la ventana popup */
	var $fieldID;
	private $NewWin; 
	private	$classThickbox;
	/* Controla el tipo de ventana que se presentará 
	(default)false --> Ventana externa
			 true  --> Ventana interna (usa la librería http://jquery.com/demo/thickbox/)
	*/

	function AvanzLookup() {
		$this->width=500;
		$this->height=520;
		$this->size=LOOKUP_FIELDSIZE;
		$this->NewWin=false;
		$this->classThickbox='thickbox';
	}

	// define o nome do campo do formulário
	function setNameCampoForm($caption,$nameCampo) {
		$this->captionCampoForm = $caption;
		$this->nameCampoForm = $nameCampo;
	}

	// define un cmp
	function setHideNameCampoForm($nameHideCampo) {
		$this->nameHideCampoForm = $nameHideCampo;
	}

	// define o título que aparecerá na janela de lookup
	function setTitle($umTitulo) {
		$this->title = $umTitulo;
	}

	// define o valor inicial do campo do formulário
	function setValorCampoForm($umValor,$Valor) {
		$this->valorCampoForm = $umValor;
//			$this->valorCampoFormDummy = $Valor;
		$this->valorCampoFormDummy = htmlspecialchars($Valor,ENT_QUOTES); // para el problema de las comillas
	}

	// define el nombre de la página
	function setNamePage($namePage) {
		$this->page=$namePage;
	}

	// define el tamaño de la caja de texto
	function setSize($size) {
		$this->size=$size;
	}

	// define el ancho de la ventana
	function setWidth($width) {
		$this->width=$width;
	}

	// define la altura de la ventana
	function setHeight($height) {
		$this->height=$height;
	}
	//funciona q agrega un campo codigo al lookup
	function addFieldID($field) {
		$this->fieldID=$field;
	}

	// define tipo de ventana emergente
	function setNewWin($NewWin=false) {
		$this->NewWin = $NewWin;
	}
	// define el nombre de css a aplicarse en link q invoca al thickbox
	//este metodo se utiliza cuando se trabaja con xajax y hay necesidad de utilizarlo en varias funciones
	//ver ejemplo en la pagina sislogal/sislogalMovimientosOrdCompra_edicion.php
	function setClassThickbox($classThickbox='thickbox') {
		$this->classThickbox = $classThickbox;
	}

	// retorna o bloco HTML que monta o campo lookup
	function writeHTML() {
		$out = "";
		$out .= "<input type='hidden' name='__Change_".$this->nameCampoForm."' id='__Change_".$this->captionCampoForm."' value=0>"; // Para poder controlar un evnto Change en este objeto, ya que no es posible escribir nada en el.

		// si se adiciona un campo de busqueda, entonces se agrega un objeto DIV (le coloca el nombre del campo), para su funcionamiento con AJAX
		if(strlen($this->fieldID)){
			$out .= $this->fieldID."&nbsp;";
			}
		else{
			$out .= "<input type='hidden' name='".$this->nameCampoForm."' id='".$this->captionCampoForm."' value='".$this->valorCampoForm."'>"; //campo q contendra el valor a grabar
			}

		$out .= "<input type='text' name='_Dummy".$this->nameCampoForm."' id='_Dummy".$this->nameCampoForm."' value='".$this->valorCampoFormDummy."' size='".$this->size."' readonly>";

		if ($this->NewWin) /* para uso de la ventana thinckbox */
			$out .= "<a href=".PATH_INC."auxiliar.php?pag=$this->page,nomeCampoForm=$this->nameCampoForm&height=$this->height&width=$this->width class=\"$this->classThickbox\" >
					 <img title=\"Clique aqui para abrir la lista de registros\" align='middle' style='cursor: pointer; border:0px' src='".LOOKUP_IMAGEM."'/></a>";
		else{ /* Uso de ventana externa */		
			$out .= "<img title=\"Clique aqui para abrir la lista de registros\" align='middle' style='cursor: pointer' src='". LOOKUP_IMAGEM."'" ;
			$out .= "onClick=\"abreJanelaAuxiliar(";
			$out .= "'".$this->page.",nomeCampoForm=".$this->nameCampoForm.",titulo=".$this->title."','".$this->width."', '".$this->height."'";
			$out .= ")\">";
		}
		
		return $out;
	}

}

/* para detectar el ip del visitante */
function detectar_ip()
    {
        if(!empty($_SERVER['HTTP_X_FORWARDER_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDER_FOR'];

        elseif(!empty($_SERVER['HTTP_VIA']))
            $ip = $_SERVER['HTTP_VIA'];

        elseif(!empty($_SERVER['REMOTE_ADDR']))
            $ip = $_SERVER['REMOTE_ADDR'];

        else
            $ip = '1.1.1.1'; // Desconocido, no logró identificarse su ip
        return $ip;
}


/* Para generar números aleatorios */
function random(){
	mt_srand ((double)microtime()*1000000);
	$maxran = 1000000;
	$random_num = mt_rand(0, $maxran);
	return ($random_num);
}

/*  Para abrir un ventana popup desde PHP
	En la página desde donde se llama la función,
	debe crearse una función en javascript AbreVentana()
*/
function AbreVentana($sURL,$Handle){
	echo "<"."script".">\n";
	echo "AbreVentana(".'"'.$sURL.'","'.$Handle.'"'.")\n";
	echo "<"."/script".">\n";
}

//funcion q suma fechas segun el numero de dias enviado,
//la fecha se recibe en formato d/m/y   y se devuelve en d/m/y
function sumaFechas($Fecha,$nDias){
$data = explode("/",$Fecha);
$next = mktime(0,0,0,$data[1],$data[0]+$nDias,$data[2]);
return date("d/m/Y",$next);
}

//funcion q devuelve parte de una fecha
//la fecha se recibe en formato d/m/y   y se devuelve  d,m, y
function partFecha($Fecha,$parte){
$data = explode("/",$Fecha);

if($parte=='d')
	return $data[0];

if($parte=='m')	
	return $data[1];

if($parte=='Y')		
	return $data[2];
}


//funcion q calcaula el tiempo en años, meses y dias, especial para calculo de edad
//la fecha se recibe en y/m/d
function calcTiempo($Fecha,$style=1){
if ($Fecha!=date('d/m/Y'))
	$edad=getDbValue("SELECT age(current_date,'$Fecha')");
else
	$edad='';
	
if($style==1){
	$edad=str_replace('years','A&ntilde;os',str_replace('mons','Meses',str_replace('days','Dias',$edad)));
	$edad=str_replace('year','A&ntilde;o',str_replace('mon','Mes',str_replace('day','Dia',$edad)));
}
else{
	$edad=str_replace('years','.',str_replace('mons','.',str_replace('days','',$edad)));
	$edad=str_replace('year','.',str_replace('mon','.',str_replace('day','',$edad)));
	$edad=explode(".",$edad);
	$edad=str_pad(trim($edad[0]),2,'0',STR_PAD_LEFT).'.'.str_pad(trim($edad[1]),2,'0',STR_PAD_LEFT).'.'.str_pad(trim($edad[2]),2,'0',STR_PAD_LEFT);
}
return($edad);
}

/*****************************************************************************************************
	Classe para trabajar con un carrito de compras
*/

class carrito {
    //atributos de la clase
    var $num_productos;
    var $array_id_prod;
    var $array_nombre_prod;
    var $array_cantid_prod;
    var $array_precio_prod;

    //constructor. Realiza las tareas de inicializar los objetos cuando se instancian
    //inicializa el numero de productos a 0
    function carrito () {
       $this->num_productos=0;
    }

    //adiciona o modifica un producto en el carrito. Recibe los datos del producto
    //Si es nuevo Se encarga de introducir los datos en los arrays del objeto carrito
    //luego aumenta en 1 el numero de productos
    //Si se modifica Se altera los datos en el arrays del objeto carrito

    function AddModi_producto($linea, $id_prod,$nombre_prod,$cantid_prod,$precio_prod){
	// si $linea es vacio se agrega un producto ->N
	// si $linea no esta vacio modifica un producto->M
		$op=strlen($linea)?'M':'N';

		if($op=='N'){//si es nuevo, verifica q no exista el codigo
			if(is_array($this->array_id_prod))
				$clave=array_search($id_prod,$this->array_id_prod);

	 	        $linea=$this->num_productos;
			}

		if(!strlen($clave)){//si no encuentra el campo clave, lo agrega o se modifica;

			if($id_prod) //si recibe codigo producto
	    	   $this->array_id_prod[$linea]=$id_prod;

			if($nombre_prod) //si recibe nombre producto
		       $this->array_nombre_prod[$linea]=$nombre_prod;
			if($cantid_prod) //si recibe cantidad de producto
	    	   $this->array_cantid_prod[$linea]=$cantid_prod;
			if($precio_prod) //si recibe precio de producto
		       $this->array_precio_prod[$linea]=$precio_prod;

			////.... aqui pueden ir mas elementos del array

    	   $this->array_subtot_prod[$linea]=round($this->array_cantid_prod[$linea]*$this->array_precio_prod[$linea],2);

		   if($op=='N'){//si es nuevo incrementa el numero de productos
			   $this->num_productos++;
			   $return=count($this->array_id_prod);
			   }
		   else $return='exito';
		  }

		 return ($return);
    }

      //elimina un producto del carrito. recibe la linea del carrito que debe eliminar
    //no lo elimina realmente, simplemente pone a cero el id, para saber que esta en estado retirado
    function elimina_producto($linea){
	   $this->array_id_prod=array_merge(array_splice($this->array_id_prod, 0, $linea),array_splice($this->array_id_prod, 1));
       $this->array_nombre_prod=array_merge(array_splice($this->array_nombre_prod, 0, $linea),array_splice($this->array_nombre_prod, 1));
   	   $this->array_cantid_prod=array_merge(array_splice($this->array_cantid_prod, 0, $linea),array_splice($this->array_cantid_prod, 1));
       $this->array_precio_prod=array_merge(array_splice($this->array_precio_prod, 0, $linea),array_splice($this->array_precio_prod, 1));
   	   $this->array_subtot_prod=array_merge(array_splice($this->array_subtot_prod, 0, $linea),array_splice($this->array_subtot_prod, 1));
       ////.... aqui pueden ir mas elementos del array

	   $this->num_productos=count($this->array_id_prod);
    }
}

/*****************************************************************************************************
	Classe para trabajar con un carrito para equipos informaticos
*/
class carEquipo {
    //atributos de la clase
    var $num_equipo;
    var $array_equi_id;
    var $array_operador;
    var $array_gabinete_id;
    var $array_sw_id;
    var $array_codigo_ctd;
    var $array_equi_reemplaza;
    var $array_estado;
    var $array_motivo;
    var $array_name;
    var $array_ip;
    var $array_equi_destino; //cuando se tratan de componentes, se solicita el equipo destino y se guarda en esta variable

    //constructor. Realiza las tareas de inicializar los objetos cuando se instancian
    //inicializa el numero de productos a 0
    function carEquipo () {
       $this->num_equipo=0;
    }

    //adiciona o modifica un producto en el carrito. Recibe los datos del producto
    //Si es nuevo Se encarga de introducir los datos en los arrays del objeto carrito
    //luego aumenta en 1 el numero de productos
    //Si se modifica Se altera los datos en el arrays del objeto carrito

    function AddModi_equipo($linea,$equi_id,$operador,$gabinete_id,$sw_id,$codigo_ctd,$equi_reemplaza,$estado,$motivo,$name,$ip,$equi_destino){
	// si $linea es vacio se agrega un producto ->N
	// si $linea no esta vacio modifica un producto->M
		$op=strlen($linea)?'M':'N';

		if($op=='N'){//si es nuevo, verifica q no exista el codigo
			if(is_array($this->array_equi_id))
				$clave=array_search($equi_id,$this->array_equi_id);
	 	        $linea=$this->num_equipo;
			}

		if(!strlen($clave)){//si no encuentra el campo clave, lo agrega o se modifica;

			if($equi_id or $op=='N') //si recibe codigo de equipo
	    	   $this->array_equi_id[$linea]=$equi_id;

			if($operador  or $op=='N') //si recibe operador
	    	   $this->array_operador[$linea]=$operador;

			if($gabinete_id  or $op=='N') //si recibe gabinete
	    	   $this->array_gabinete_id[$linea]=$gabinete_id;

			if($sw_id  or $op=='N') //si recibe switch
	    	   $this->array_sw_id[$linea]=$sw_id;

			if($codigo_ctd  or $op=='N') //si recibe codigo de caja tomadatos
		       $this->array_codigo_ctd[$linea]=$codigo_ctd;

			if($equi_reemplaza  or $op=='N') //si hay equipo que es reemplazado
	    	   $this->array_equi_reemplaza[$linea]=$equi_reemplaza;

			if($estado or $op=='N') //estado del equipo
	    	   $this->array_estado[$linea]=$estado;

			if($motivo or $op=='N') //motivo de retiro del equipo
	    	   $this->array_motivo[$linea]=$motivo;

			if($name or $op=='N') //motivo de retiro del equipo
	    	   $this->array_name[$linea]=$name;

			if($ip or $op=='N') //motivo de retiro del equipo
	    	   $this->array_ip[$linea]=$ip;

			if($equi_destino or $op=='N') //motivo de retiro del equipo
	    	   $this->array_equi_destino[$linea]=$equi_destino;
			////.... aqui pueden ir mas elementos del array


		   if($op=='N'){//si es nuevo incrementa el numero de productos
			   $this->num_equipo++;
			   $return=count($this->array_equi_id);
			   }
		   else $return='exito';
		  }

		 return ($return);
    }


      //elimina un producto del carrito. recibe la linea del carrito que debe eliminar
    //no lo elimina realmente, simplemente pone a cero el id, para saber que esta en estado retirado
    function elimina_equipo($linea){
	   $this->array_equi_id=array_merge(array_splice($this->array_equi_id, 0, $linea),array_splice($this->array_equi_id, 1));
   	   $this->array_operador=array_merge(array_splice($this->array_operador, 0, $linea),array_splice($this->array_operador, 1));
   	   $this->array_gabinete_id=array_merge(array_splice($this->array_gabinete_id, 0, $linea),array_splice($this->array_gabinete_id, 1));
   	   $this->array_sw_id=array_merge(array_splice($this->array_sw_id, 0, $linea),array_splice($this->array_sw_id, 1));
   	   $this->array_codigo_ctd=array_merge(array_splice($this->array_codigo_ctd, 0, $linea),array_splice($this->array_codigo_ctd, 1));
   	   $this->array_equi_reemplaza=array_merge(array_splice($this->array_equi_reemplaza, 0, $linea),array_splice($this->array_equi_reemplaza, 1));
   	   $this->array_estado=array_merge(array_splice($this->array_estado, 0, $linea),array_splice($this->array_estado, 1));
   	   $this->array_motivo=array_merge(array_splice($this->array_motivo, 0, $linea),array_splice($this->array_motivo, 1));
   	   $this->array_name=array_merge(array_splice($this->array_name, 0, $linea),array_splice($this->array_name, 1));
   	   $this->array_ip=array_merge(array_splice($this->array_ip, 0, $linea),array_splice($this->array_ip, 1));
   	   $this->array_equi_destino=array_merge(array_splice($this->array_equi_destino, 0, $linea),array_splice($this->array_equi_destino, 1));
       ////.... aqui pueden ir mas elementos del array

	   $this->num_equipo=count($this->array_equi_id);
    }
}


/*funcion que soluciona el problema de las comillas */
function especialChar($texto) {
	/*reemplaza la comilla doble x comilla simple ->&#039:comilla doble */
	return(str_replace("&#039;","\'",htmlspecialchars($texto,ENT_QUOTES)));
	
}

function mu_sort ($array, $key_sort, $asc_desc=0) { // start function

   $key_sorta = explode(",", $key_sort);
   $keys = array_keys($array[0]);
     // sets the $key_sort vars to the first
    for($m=0; $m < count($key_sorta); $m++){ $nkeys[$m] = trim($key_sorta[$m]); }

   $n += count($key_sorta);    // counter used inside loop

     // this loop is used for gathering the rest of the
     // key's up and putting them into the $nkeys array
     for($i=0; $i < count($keys); $i++){ // start loop

         // quick check to see if key is already used.
         if(!in_array($keys[$i], $key_sorta)){

             // set the key into $nkeys array
             $nkeys[$n] = $keys[$i];

             // add 1 to the internal counter
             $n += "1";

           } // end if check

     } // end loop

     // this loop is used to group the first array [$array]
     // into it's usual clumps
     for($u=0;$u<count($array); $u++){ // start loop #1

         // set array into var, for easier access.
         $arr = $array[$u];

           // this loop is used for setting all the new keys
           // and values into the new order
           for($s=0; $s<count($nkeys); $s++){

               // set key from $nkeys into $k to be passed into multidimensional array
               $k = $nkeys[$s];

                 // sets up new multidimensional array with new key ordering
                 $output[$u][$k] = $array[$u][$k];

           } // end loop #2

     } // end loop #1

 switch($asc_desc) {
     case "1":
         rsort($output); break;
     default:
         sort($output);
   }


 // return sorted array
 return $output;
}
/*
funcion que devuelve el nombre del mes
ejemplo: list_mes(11), devuelve NOVIEMBRE
*/
function list_mes($mes){
switch($mes){
	case '1':
		$nameMes='ENERO';
		break;
	case '2':
		$nameMes='FEBRERO';
		break;
	case '3':
		$nameMes='MARZO';
		break;
	case '4':
		$nameMes='ABRIL';
		break;
	case '5':
		$nameMes='MAYO';
		break;
	case '6':
		$nameMes='JUNIO';
		break;
	case '7':
		$nameMes='JULIO';
		break;
	case '8':
		$nameMes='AGOSTO';
		break;
	case '9':
		$nameMes='SEPTIEMBRE';
		break;
	case '10':
		$nameMes='OCTUBRE';
		break;
	case '11':
		$nameMes='NOVIEMBRE';
		break;
	case '12':
		$nameMes='DICIEMBRE';
		break;
}
return($nameMes);
}
/*
funcion que devuelve el tiempo en formato HH:MM, se le envia la cantidad de minuitos
	ejemplo ->convHHMM(520), devuelve 08:40
*/
function convHHMM($mm=0){
return(str_pad(intval($mm/60),2,"0",STR_PAD_LEFT).':'.str_pad($mm % 60,2,"0",STR_PAD_LEFT));
}

/*****************************************************************************************************
	Para mostrar u ocultar un Wait al entrar en un proceso
	txtwait --> Aquí recibo el texto o código html que deseo se muestre en el DIV 'procesando'
	Formas de llamar:
	wait('<img src="../img/ajax-loader.gif" />')  Para mostrar la animación
	.........
	.........
	wait('')  Para eliminar la eliminación, luego que termina el proceso 
	
*/
function wait($txtwait="") {
	echo "<script language='JavaScript'>";
	echo "parent.menu.document.getElementById('procesando').innerHTML = '$txtwait'";
	echo "</script>";
}


/**********************************************************************************
funcion que devuelve una hora en formato de 00-23 a formato de 00-12, incluye AM/PM
el parametro $hora es recibido asi: '23:24', se devuelve '11:24 PM'
									'13:15', se devuelve '01:15 PM'
**********************************************************************************/
function getTurno($hora){
//$hora=getDbValue("SELECT to_char(to_timestamp('05 Dec 2000 $hora', 'DD Mon YYYY HH:MI'),'HH12:MI:AM')");
//return($hora);
return(date('h:i A',strtotime($hora)));
}

/*****************************************************************************************************
	Para escribir en un archivo texto
		$archivo --> Archivo donde se va a escribir
		$txt --> Texto a excribir	
	Ejmeplo: 
		EscribeTxt("../content/debug_ajax.txt",$sqlpc);
*/
function EscribeTxt($archivo,$txt="") {
	// Abrimos el archivo
	$abre = fopen($archivo, "w");
	// Y reemplazamos por la nueva cantidad de visitas
	$grabar = fwrite($abre, $txt);
	// Cerramos la conexión al archivo
	fclose($abre);
}

/*****************************************************************************************************
	Función que registra los ingresos a una web, datos que servirán como estadística.
	$cIdentificador --> Identifica el módulo del cual se desea registrar sus usos.
	$depe_id --> Para algunos módulos es necesario identificar la Dependencia.  Por. ejm. para el caso de PORTALES
*/
function Estadisticas($cIdentificador,$depe_id='NULL') {
	if(!$conn){ // Si no existe una conexión establecida anterior 
		$conn = new db();
		$conn->open();
	}
	$ip=detectar_ip();
	$sql = "INSERT INTO estadisticas (esta_identificador,esta_ip,esta_page,depe_id) VALUES ('$cIdentificador','$ip','".$_SERVER['PHP_SELF']."','$depe_id')" ;
	$rs = new query($conn, $sql);
	$conn->close();
}

/*tratamiento de variables pasadas por la url entre paginas*/
class manUrlv1 {
	var $url = array();
	
	function manUrlv1() {
		//$this->url = ($_GET) ; 
		$this->retrievCurrUrl();
	}
	
	function retrievCurrUrl() {
		$this->url = ($_GET) ; 
		$this->removePar('id');//OJO no recibe el indice 'id'
	}
	
	//agrega un elemento
	function addParComplete($kiave,$valore){
		$this->url[$kiave]=$valore;
	}

	//retorna el valor de una clave
	function getValuePar($par) {
		reset($this->url);
		
		$array=$this->getUrl();
		foreach($array as $key => $value)
			if ($key == $par) return $value;
	}
	
	
	//remueve un elemento
	function removePar($par) {
		$num = $this->getPosPar($par);
		
		if (($num >=0) && (isset($num) != false)) {
		  //print_r($this->url);
		  //echo "trovato e cancellato";
		  array_splice($this->url,$num,1);
		}
	
	//print_r($this->url);
	}

	//ritorna l'url costruito
	function getUrl() {
		return $this->url;
	}
	
	//prende un url esterno 
	function setUrlExternal($p_url){
		$this->url =  $p_url;
	}
	
	
	//cicla l'array
	function loopArray(&$kiave,&$valore) {
		list($kiave,$valore)=each($this->url);
		return $kiave;
	}
	
	//riporta il cursore al primo elemento 
	function resetPos() {
		reset($this->url);
	} 
	
	//ritorna ad uno a uno tutti gli 
	//elementi nel vettore
	function getNext() {
		 $this->loopArray($kiave,$valore);
		 $ret = array(); 
		 if ($kiave != "") {
			 $ret[] = $kiave;
			 $ret[] = $valore;
		 }else 
			 $ret = "";
		
		 return $ret;
	}
	
	//ritorna la kiave di una valore 
	function getKeyPar($par) {
		reset($this->url);
	
		$array=$this->getUrl();
		foreach($array as $key => $value)
			if ($value == $par) return (string)$key;
	}
			
	//ritorna la posizione di una chiave
	function getPosPar($par) {
		reset($this->url);
		$i=0;
		$array=$this->getUrl();		
		foreach($array as $key => $value){
			if ($key == $par) return $i;
			$i++;		
		}
	}

	//reemplaza un elemento
	function replaceParValue($par,$value) {
		$this->removePar($par); 
		$this->addParComplete($par,$value); 
	}
	
	
	
	//rimuove tutti gli elementi 
	function removeAllPar($start=1) {
		array_splice($this->url,$start,count($this->url));
	}
	
	
	function buildPars($withAsk = true,$char='&') {
		reset($this->url);
	
		if ($withAsk == true) $query ="?";
		else  $query = "";
		
		$array=$this->getUrl();
		foreach($array as $key => $value)
			$query .= $key."=".$value.$char;		

		$pos = strrpos($query,$char);
		if ($pos>0) $query=substr($query,0,$pos);
		return $query;
	}
	
};

function encodeArray($vector){
        $vector = serialize($vector);
        $vector = urlencode($vector);
        return $vector;
    }

function decodeArray($vector){
        $vector = stripslashes($vector);
        $vector = urldecode($vector);
        $vector = unserialize($vector); 
        return $vector;
 }
 
function inlist($valor,$lista){
	$lista = explode(",",$lista);
	if (in_array ($valor, $lista))
		return true;
	else
		return false;

}

function formateacuenta($mvalor,$mspace,$char="&nbsp;"){
/* Formatea el campo Cuenta
mvalor --> La cuenta que deseamos formatear
mspace --> true --> Coloca las sangrias correspondientes
		   false --> No coloca ninguna sangrìa, solo considera los puntos para el formateo
char   --> Caracter de espacio que se aplicará a la izquierda de las cuentas
			 "&nbsp;" --> Se aplica para una impresion en HTML
			 " "      --> Se aplica para una impresión en PDF 
*/
$x=strlen($mvalor);
$mvalor2=$mvalor;
$loNg=14;
$_Space=2;

if($x>5){
	$mvalor2 = substr($mvalor, 0, 4);  
	for ($y = 4; $y <= $x-2; $y+=2) {	
		$mvalor2 = $mvalor2.'.'.substr($mvalor, $y, 2);
		$_Space = $_Space+3;
	}
}elseif($x==2)
	$_Space=0;

if($mvalor and $mspace and $_Space){
	$mvalor2 = str_repeat($char, $_Space).$mvalor2;
}elseif(strlen($mvalor2)>$loNg)
	$mvalor2 = substr($mvalor2, 0, $loNg); 

return $mvalor2;
}

?>