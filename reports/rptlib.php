<?

function cabecera($titulo,$modulo){
	$sReturn = "<XHTML>";
	$sReturn .= "<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"90%\">";
	$sReturn .= "<TR>";
	$sReturn .= "<TD CLASS=\"TITULO\" WIDTH=\"70%\">";
	$sReturn .= "<b>".$titulo."</b><br/>";
	$sReturn .= "<b>Módulo: ".$modulo."</b>";	
	$sReturn .= "</TD>";
	$sReturn .= "<TD CLASS=\"TITULO\" style=\"background:#A0A0A0;color:#FFFFFF;\" ALIGN=\"CENTER\">";
	$sReturn .= "<b>"._EMPRESA_."</b>";
	$sReturn .= "</TD>";
	$sReturn .= 	"<TD CLASS=\"HEADER\" style=\"background:#CCCCCC;color:#FFFFFF;\" >";
	$sReturn .= 		"<IMG SRC=\"../imagenes/logo2.gif\" width=\"90\" height=\"60\"></IMG>";								
	$sReturn .= 	"</TD>";
	$sReturn .= "</TR>";
	$sReturn .= "</TABLE>";
	$sReturn .= "</XHTML>";
	return($sReturn);
}


function rptlink($valor,$title){
	$sReturn = "<XHTML>";
	$sReturn.= "<a href=\"rptproyecto.php?_id=".$valor."\" title=\"".$title."\" target=\"_blank\">".$valor."</a>";
	$sReturn.= "</XHTML>";	
	return($sReturn);
}


function autor(){
	return('SGRI/lga');
}


?>

