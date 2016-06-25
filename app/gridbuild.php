<?
	$_nameform="frmgriprin";
	echo "<table width=\"100%\" height=\"$_porcengrid%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"8\"  class=\"".iif($_npop,'!=','','','backform')."\">\n";
	echo "<tr><td valign=\"top\">\n";
	echo "<table class=\"frmline\" width=\"100%\" height=\"100%\" align=\"center\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "<form name=\"".$_nameform."\" method=\"post\">\n";
	echo "<tr height=\"21\">\n";
	echo "<td width=\"100%\">\n";
	topform($_btncaption,0,strtoupper($_titulo).iif($_where,'!=','',' :: [Resultado de la Búsqueda]','')) ;
	echo "</td>\n";
	echo "</tr>\n";
    gridbotonera("top");	 
  	echo "<tr><td valign=\"top\">\n";
	$gridrowcolor=array_envia($gridrowcolor); 
	$gridcolconfig=array_envia($gridcolconfig); 	
	echo "<iframe width=\"100%\" height=\"100%\" id=\"igrid\" name=\"igrid\" src=\"gridsimple.php?_stringsql=$_stringsql&_where=$_where&_where2=$_where2&_op=$_op&_npop=$_npop&_tabselected=$_tabselected&pagina=$_pagina&orden=$_orden&_url=$_url&_gridrowcolor=$gridrowcolor&_gridcolconfig=$gridcolconfig&_fullregisgrid=$fullregisgrid&_pathlib=$pathlib&_stringsqlwhere=$_stringsqlwhere&_stringsqlorder=$_stringsqlorder&_classgrid=$_classgrid\" frameborder=\"0\" scrolling=\"yes\" ></iframe>\n";
	echo "</td></tr>\n";
	gridbotonera("bottom");	 	 
	echo "</form>\n";	 
	echo "</table>\n";	 
	echo "</td></tr>\n";	 
	echo "</table>\n";	 

	function gridbotonera($alineacion){ 
		global $_buttmenumx,$_url,$_where,$_op,$pathlib,$_pagina,$_orden,$_permiso,$_npop;
		echo "<tr height=\"21\" valign=\"$alineacion\">\n";
		echo "<td>\n";
		    if (file_exists("$_buttmenumx")) 
				include("$_buttmenumx");
		echo "</td>\n";
		echo "</tr>\n";
	}

?>
