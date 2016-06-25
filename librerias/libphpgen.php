<?
//define (PAGE,$HTTP_SERVER_VARS['PHP_SELF']);
define (PAGE,$_SERVER['REQUEST_URI']); //Este es el que funciona en Zend Server


if($_GET[random_num]) {gfx($_GET[random_num]);}

function DateDMA($DateYMD)
{
if($DateYMD=='0000-00-00' or $DateYMD=='') return('');
else return (substr($DateYMD,8,2).'/'.substr($DateYMD,5,2).'/'.substr($DateYMD,0,4));

}

function DateAMD($DateDMY)
{
if (strlen($DateDMY)==10)
	return (substr($DateDMY,6,4).'-'.substr($DateDMY,3,2).'-'.substr($DateDMY,0,2));
else
	return ('');
}


function saca_char($string,$char)
{
$nvostring='';
	for($x=0;$x<=strlen($string);$x++)
	{if(strcmp(substr($string,$x,1),$char)!=0){$nvostring=$nvostring.substr($string,$x,1);}	}

return $nvostring;
}

function coloca_coma($string)
{
$nvostring='';
	for($x=0;$x<=strlen($string);$x++)
	{if(strcmp(substr($string,$x,1),$char)!=0){$nvostring=$nvostring.substr($string,$x,1);}	}

return $nvostring;
}

function seccion($Label,$formavisual,$tipoedicion)
{
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
global $pathlib;
	if($Label){
		  echo "<tr>";
		  echo "<td colspan=\"5\">";	  
		  echo "<table cellspacing=\"0\" border=\"0\" cellpadding=\"0\">";
		  echo "<tr>";	  
		  echo "<td width=\"10\" background=\"".$pathlib."imagenes/titulo1.jpg\" height=\"10\">&nbsp;</td>";	  
		  echo "<td width=\"90%\" align=\"left\" class=\"marco ".iif($Label,"!=","","seccion","seccionblank") ."\" >&nbsp;$Label</td>";
		  echo "<td background=\"".$pathlib."imagenes/titulo3.jpg\" height=\"20\" align=\"right\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";	  	  
		  echo "</tr>";
		  echo "</table>";	  
		  echo "</td>";	  	  
		  echo "</tr>";
		  }
	else {
		  echo "<tr>";	  	
		  echo "<td colspan=\"5\" class=\"marco seccionblank\" >&nbsp</td>";
		  echo "</tr>";	  			  
		}
	
}
} 

function seccion2($Label,$formavisual,$tipoedicion,$align="left",$class="marco seccionblank")
{
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
?>
      <tr>
        <td colspan="4" align="<? echo $align ?>" class="<? echo $class ?>" >&nbsp;
		<? echo $Label; ?>
		
		</td>		
        <td colspan="1" class="objeto">&nbsp;</td>		
      </tr>
<?
}
} 

function tabseccion($Label,$formavisual,$tipoedicion,$width,$tableabre='',$tablecierra='')
{
global $pathlib;
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) 
	{
	if ($tableabre=='' or $tableabre=='[') {	?>	
		<TR><TD colspan="5" class="marco seccionblank" > 	<? } ?>
		<img src="<? echo $pathlib ?>imagenes/spacer.gif" width="<? echo  $width ?>%" border=0 height="15"><?php echo $Label ?>
		<? 
			if ($tablecierra=='' or $tablecierra==']') {	?>	
				</TD></TR> 	<? } 
	}
} 



function error($Label)
{
?>
      <tr>
        <td colspan="5" align="left"  class="marco error"><?php echo $Label ?></td>		
      </tr>
<?
} 

function labelcajatxt($formavisual,$tipoedicion,$Label,$cajatxtname,$row,$size,$tableabre='',$tablecierra='',$err_objeto='',$random_num=0)
{
global $pathlib;
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
 // busco valores iniciales
 $pos_fin=strpos($cajatxtname,"=");
 $value_ini="";
 if($pos_fin)
	{ $namefield=substr($cajatxtname,3,$pos_fin-3); 
	  $value_ini=substr($cajatxtname,$pos_fin+1);
	  $cajatxtname=substr($cajatxtname,0,$pos_fin);
	}
 else
   $namefield=substr($cajatxtname,3); 

 $namecbsearch='_busq'.$namefield;  
 $namescurity='_secu'.$namefield;   


	if ($tableabre=='' or $tableabre=='[') { 
		if(substr($cajatxtname,0,1)!="H") // Si no es campo oculto que se usa para las tablas dinàmicas
			html_iniobj($err_objeto,$cajatxtname,$Label);
		
			if($tipoedicion==5) {//busqueda	?>
				<input type="hidden"  name="<? echo $namecbsearch ?>" value="LIKE" >									
			<? }	
		
		} 
		
	if(substr($cajatxtname,0,1)!="H"){ ?>
		<input name="<? echo $cajatxtname ?>" type="text"    value="<? if($row[$namefield] && !isset($_POST[$cajatxtname])) {echo trim($row[$namefield]);} else if($_POST[$cajatxtname]) {echo trim(stripslashes($_POST[$cajatxtname]));} else if($value_ini) {echo trim($value_ini);} ?>"    size="<? if($tipoedicion==5) echo $size-4 ; else echo $size+4 ;?>" class="cajatexto"  id="<? echo $Label?>" maxlength="<? echo $size ?>"  onKeyPress="return formato(event,form,this,<? echo $size ?>)" <? if($tipoedicion==3) {echo iif(substr($cajatxtname,1,1),'==','s',"READONLY","DISABLED"); } if($tipoedicion==2 && substr($cajatxtname,1,1)=='s') echo "READONLY" ?>> 
	<? }
	else{ ?>
	<input name="<? echo $cajatxtname ?>" type="hidden" value="<? if($row[$namefield] && !isset($_POST[$cajatxtname])) {echo trim($row[$namefield]);} else if($_POST[$cajatxtname]) {echo trim(stripslashes($_POST[$cajatxtname]));} else if($value_ini) {echo trim($value_ini);} ?>" >	
	<? 
	}
	if($random_num && ($tipoedicion==1 or $tipoedicion==2)) { ?>
		&nbsp;<img src='<? echo $pathlib ?>libphpgen.php?random_num=<? echo $random_num ?>' border='1' alt='Codigo de Seguridad' title='Codigo de Seguridad'>
		<input name="<? echo $namescurity  ?>"  type="hidden" value=<? echo $random_num ?> >
	<? }
	//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
	if(strpos($cajatxtname,"XX")){ ?>
		<input name="<? echo substr($cajatxtname,2)  ?>"   type="hidden" >	
	<? }
	if ($tablecierra=='' or $tablecierra==']') { 
		if(substr($cajatxtname,0,1)!="H")
			html_endobj(); 		
			}  
}
} 

function labelcajadate($formavisual,$tipoedicion,$Label,$cajatxtname,$row,$form, $tableabre='',$tablecierra='',$err_objeto='')
{
global $pathlib;
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
 // busco valores iniciales
 $pos_fin=strpos($cajatxtname,"=");
 $value_ini="";
 if($pos_fin)
	{ $namefield=substr($cajatxtname,3,$pos_fin-3); 
	  $value_ini=substr($cajatxtname,$pos_fin+1);
	  $cajatxtname=substr($cajatxtname,0,$pos_fin);
	}
 else
   $namefield=substr($cajatxtname,3); 

  $namecbsearch='_busq'.$namefield;  

	if ($tableabre=='' or $tableabre=='[') { 
		html_iniobj($err_objeto,$cajatxtname,$Label);

			if($tipoedicion==5) {//busqueda
				if(strpos(strtoupper($Label),"DESDE")) {  ?>
					<input type="hidden"  name="<? echo $namecbsearch ?>" value=">=" >													
				<? } else { 	
						if(strpos(strtoupper($Label),"HASTA")) {	  $namecbsearch='_bus2'.$namefield;   ;?>
							<input type="hidden"  name="<? echo $namecbsearch ?>" value="<=" >													
					<? } else { ?>
							<input type="hidden"  name="<? echo $namecbsearch ?>" value="=" >													
					<? } } 
			  }	
		} ?>
	
		<input name="<? echo $cajatxtname ?>" type='text' class='cajatexto' id='<? echo $Label?>'  onKeyPress='return formato(event,form,this,10)' value='<? if($row[$namefield] && !isset($_POST[$cajatxtname])) {echo DateDMA($row[$namefield]);} else if($_POST[$cajatxtname]) {echo trim($_POST[$cajatxtname]);} else if($value_ini) {echo trim($value_ini);} ?>' size="10"   maxlength='10' <? if($tipoedicion==3  || ($tipoedicion==2 && substr($cajatxtname,2,1)=='s')) echo 'READONLY' ?>>
		&nbsp;
		<?
		if($tipoedicion!=3) {//en la opcion del grid ver detalle no se muestra este icono
			?>
			<img src="<? echo $pathlib ?>imagenes/calendaricon.gif" height="15" width="15" border=0 onClick="popUpCalendar(this, <? echo $form ?>.<? echo $cajatxtname  ?>, 'dd/mm/yyyy')">
			<?
		}
	//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
	if(strpos($cajatxtname,"XX")){ ?>
		<input name="<? echo substr($cajatxtname,2) ?>"   type="hidden" >	
	<? }

		if ($tablecierra=='' or $tablecierra==']') { 
			html_endobj(); 		
			}  
}
} 

function labelcajapass($formavisual,$tipoedicion,$Label,$cajatxtname,$row,$size,$tableabre='',$tablecierra='',$err_objeto='')
{
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
 $namefield=substr($cajatxtname,3);
 $namecbsearch='_busq'.$namefield;  

	if ($tableabre=='' or $tableabre=='[') { 
		html_iniobj($err_objeto,$cajatxtname,$Label);
		}  ?>
		<input name="<? echo $cajatxtname  ?>" type="password" value="<? if(($tipoedicion==2 && !$_POST[$cajatxtname]) or $tipoedicion==3) echo '******'; else echo trim($_POST[$cajatxtname]) ; ?>"    size="<? if($tipoedicion==5) echo $size-4 ; else echo $size+4 ;?>" class="cajatexto"  id="<? echo $Label?>" maxlength="<? echo $size ?>"  onKeyPress="return formato(event,form,this,<? echo $size ?>)" <? if($tipoedicion==3  || ($tipoedicion==2 && substr($cajatxtname,2,1)=='s')) echo "DISABLED" ?>>
		<? 
	//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
	if(strpos($cajatxtname,"XX")){ ?>
		<input name="<? echo substr($cajatxtname,2) ?>"   type="hidden" >	
	<? }

	if ($tablecierra=='' or $tablecierra==']') { 
		html_endobj(); 	
		}  
}
} 

function labelcajanum($formavisual,$tipoedicion,$Label,$cajanumname,$row,$coma,$size=0,$decimal=0,$search='',$tableabre='',$tablecierra='',$err_objeto='') 
{
global $pathlib;
//$formavisual: 1->aparece en todo,
//              2-> aparece en todo exepto en la busqueda 
//              3->no aparece solo en el ingreso, 
//              5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
 // busco valores iniciales
 $pos_fin=strpos($cajanumname,"=");
 $value_ini="";
 if($pos_fin)
	{ $namefield=substr($cajanumname,3,$pos_fin-3); 
	  $value_ini=substr($cajanumname,$pos_fin+1);
	  $cajanumname=substr($cajanumname,0,$pos_fin);
	}
 else
	$namefield=substr($cajanumname,3); 
	$namecbsearch='_busq'.$namefield;   

	if ($tableabre=='' or $tableabre=='[') { 		
			html_iniobj($err_objeto,$cajanumname,$Label);

			if($tipoedicion==5) {//busqueda
			?>
			<input type="hidden"  name="<? echo $namecbsearch ?>" value="=" >									
			<? } 
	} ?>

	<input name="<? echo $cajanumname  ?>" type="text" class="cajatexto"  id="<? echo $Label?>" STYLE="text-align:right"   onFocus="replaceChars(this,',','')"  onBlur="commaSplit(this,<? echo $coma?>,<? echo $size ?>,<? echo $decimal ?>)" onKeyPress="return formato(event,form,this,<? echo $size ?>,<? echo $decimal ?>)"  value="<? 
	
	if($decimal>0) $sizetotal=$size+$decimal+1; else $sizetotal=$size;

	if($row[$namefield] && !isset($_POST[$cajanumname])) 
		{
		if($coma) echo iif($row[$namefield],'>',0,number_format(substr($row[$namefield],0,$sizetotal),$decimal),'') ; else echo iif($row[$namefield],'>',0,substr($row[$namefield],0,$sizetotal),'') ;
		} 
		else 
		{
		 if($_POST[$cajanumname]) 
		 	{if($coma) echo number_format(substr($_POST[$cajanumname],0,$sizetotal),$decimal);	else echo substr($_POST[$cajanumname],0,$sizetotal);	} 
		else 
			{if($coma && $value_ini) echo number_format(substr($value_ini,0,$sizetotal),$decimal); 	else echo substr($value_ini,0,$sizetotal);}
		} 
	
	?>"  size="<? echo $size+iif($size,'>',10,8,4)?>" maxlength="<? if(!$coma) echo $size+$decimal; else $size+$decimal+iif($size,'>',3,($size/3),0);?>" <? if($tipoedicion==3) {echo iif(substr($cajanumname,1,1),'==','s',"READONLY","DISABLED"); } if($tipoedicion==2 && substr($cajanumname,1,1)=='s') echo "READONLY" ?>>	
	<? if($search && $tipoedicion==1) { ?>
		&nbsp;<img src="<? echo $pathlib ?>imagenes/search.gif" alt="Buscar" height="14" width="16" border=0 onClick="eventaccion(<? echo $search ?>,9,'<? echo PAGE ?>')" style="cursor:pointer">
	<?	}

	//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
	if(strpos($cajanumname,"XX")){ ?>
		<input name="<? echo substr($cajanumname,2) ?>"   type="hidden" >	
	<? }

	if ($tablecierra=='' or $tablecierra==']') { 
		html_endobj(); 
			}  
}
} 

function labelareatxt($formavisual,$tipoedicion,$Label,$cajatxtname,$row,$rows,$size,$tableabre='',$tablecierra='',$err_objeto='')
{
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {


 $namefield=substr($cajatxtname,3);
$namecbsearch='_busq'.$namefield;   

	if ($tableabre=='' or $tableabre=='[') { 
		html_iniobj($err_objeto,$cajatxtname,$Label);
			if($tipoedicion==5) {//busqueda		
				?>
				<input type="hidden"  name="<? echo $namecbsearch ?>" value="LIKE" >									
				<? }	
			} 
		
		?>

		<textarea name=<? echo $cajatxtname  ?> rows=<? echo iif($tipoedicion,'==',5,1,$rows) ?>  cols=<? echo $size ?> class="cajatexto"  id="<? echo $Label?>"  onKeyPress="return formato(event,form,this)" <? if($tipoedicion==3) echo "DISABLED" ?>><? if($row[$namefield] && !isset($_POST[$cajatxtname])) echo trim($row[$namefield]); else echo stripslashes($_POST[$cajatxtname]); ?></textarea>	
		<? 

	//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
	if(strpos($cajatxtname,"XX")){ ?>
		<input name="<? echo substr($cajatxtname,2) ?>"   type="hidden" >	
	<? }

	if ($tablecierra=='' or $tablecierra==']') { 
			html_endobj(); 	
			}  
	}

} 

function labelcheck($formavisual,$tipoedicion,$Label,$Label2,$checkname,$row,$jump=0,$tableabre='',$tablecierra='',$err_objeto='')
{
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
 
  // busco valores iniciales
 $pos_fin=strpos($checkname,"=");
 $value_ini=0;
 if($pos_fin)
	{ 
	  $namefield=substr($checkname,3,$pos_fin-3); 
	  $value_ini=strval(substr($checkname,$pos_fin+1));
	  $checkname=substr($checkname,0,$pos_fin);
	}
 else
    $namefield=substr($checkname,3); 
	
	if ($tableabre=='' or $tableabre=='[') { 
		html_iniobj($err_objeto,$checkname,$Label);

			if($tipoedicion==5) {//busqueda
			?>
				<input type="hidden"  name="<? echo $namecbsearch ?>" value="=" > <?			
			}	

		} 

		if($tipoedicion==2 or $tipoedicion==1) { 	
			?>
	         <input type="hidden" name="<? echo $checkname  ?>"   value=0>		
			 <? } ?>

	    <span class="etiqueta objeto" ><?php echo $Label2?></span>

		<? if($row[$namefield]) $valcheck=$row[$namefield];
		   else	if(isset($checkname)) {$valcheck=$_POST[$checkname];}
		   else if($value_ini) $valcheck=$value_ini;
		   else $valcheck=0; 
		?>
		<input type="checkbox" name="<? echo $checkname  ?>" class="cajatexto"  id="<? echo $Label?>" onKeyPress="return formato(event,form,this)"  value=1  <? if($valcheck) echo "CHECKED" ?> <? if($tipoedicion==3) echo "DISABLED" ?>
		<? if($jump) {?>		
		onClick="MM_jumpMenu(form,this,'<? echo PAGE ?>')"
		<? }?>				
		>	

	<? 
	//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
	if(strpos($checkname,"XX")){ ?>
		<input name="<? echo substr($checkname,2) ?>"   type="hidden" >	
	<? }

	if ($tablecierra=='' or $tablecierra==']') { 
		html_endobj(); 
		}  
	}

} 


function labelarchivo($formavisual,$tipoedicion,$Label,$cajatxtname,$row,$size,$form, $tableabre='',$tablecierra='',$err_objeto='')
{
global $pathlib;
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
 // busco valores iniciales
 $pos_fin=strpos($cajatxtname,"=");
 $value_ini="";
 if($pos_fin)
	{ $namefield=substr($cajatxtname,3,$pos_fin-3); 
	  $value_ini=substr($cajatxtname,$pos_fin+1);
	  $cajatxtname=substr($cajatxtname,0,$pos_fin);
	}
 else
   $namefield=substr($cajatxtname,3); 

 $namecbsearch='_busq'.$namefield;  
 $namescurity='_secu'.$namefield;   
 
	if ($tableabre=='' or $tableabre=='[') { 
		html_iniobj($err_objeto,$cajatxtname,$Label);

			if($tipoedicion==5) {//busqueda
			?>
			<select name="<? echo $namecbsearch ?>" class="cajatexto" onKeyPress="return formato(event,form,this)" >
			  <option value="LIKE" <? if(strcmp($_POST[$namecbsearch],"LIKE")==0) {echo "SELECTED";} ?>>INc</option>
			  <option value="=" <? if(strcmp($_POST[$namecbsearch],"=")==0) {echo "SELECTED";} ?>>=</option>
			</select>
			<? }	
		} 
	if($tipoedicion!=3){
	?>
	<input name="<? echo "file_".$cajatxtname  ?>" type="file"    value="<? if($row[$namefield] && !isset($_POST[$cajatxtname])) {echo trim($row[$namefield]);} else if($_POST[$cajatxtname]) {echo trim(stripslashes($_POST[$cajatxtname]));} else if($value_ini) {echo trim($value_ini);} ?>"    size="<? if($tipoedicion==5) echo $size-4 ; else echo $size+4 ;?>" class="cajatexto"  id="<? echo $Label?>" onKeyPress="return formato(event,form,this)" <? if($tipoedicion==3) echo "DISABLED" ?> onChange="refresh_field(<? echo $form ?>.<? echo $cajatxtname ?>,this.value)">
	<br>
	<?
	}
	?>
	<input name="<? echo $cajatxtname  ?>" type=<? echo iif($tipoedicion,"==",1,"hidden","text") ?>    value="<? if($row[$namefield] && !isset($_POST[$cajatxtname])) {echo trim($row[$namefield]);} else if($_POST[$cajatxtname]) {echo trim(stripslashes($_POST[$cajatxtname]));} else if($value_ini) {echo trim($value_ini);} ?>"    size="<? if($tipoedicion==5) echo $size-4 ; else echo $size+4 ;?>" class="cajatexto"  id="<? echo $Label?>" onKeyPress="return formato(event,form,this)" <? if($tipoedicion==3) echo "DISABLED"; else echo "READONLY"; ?>>
	<?
	//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
	if(strpos($cajatxtname,"XX")){ ?>
		<input name="<? echo substr($cajatxtname,2) ?>"   type="hidden" >	
	<? }

	if ($tablecierra=='' or $tablecierra==']') { 
			html_endobj(); 		
			}  
}
} 


function labeloption($formavisual,$tipoedicion,$Label,$Labe2,$value,$optname,$row,$jump,$tableabre='',$tablecierra='',$err_objeto='')
{
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
 
  // busco valores iniciales
 $pos_fin=strpos($optname,"=");
 $value_ini=0;
 if($pos_fin)
	{ 
	  $namefield=substr($optname,3,$pos_fin-3); 
	  $value_ini=strval(substr($optname,$pos_fin+1));
	  $optname=substr($optname,0,$pos_fin);
	}
 else
    $namefield=substr($optname,3); 
	$namecbsearch='_busq'.$namefield;  
	
	if ($tableabre=='' or $tableabre=='[') { 
		html_iniobj($err_objeto,$optname,$Label);

			if($tipoedicion==5) {//busqueda
			?>
			<select name="<? echo $namecbsearch ?>" class="cajatexto" onKeyPress="return formato(event,form,this)" >
		      <option value="=" <? if(strcmp($_POST[$namecbsearch],"=")==0) {echo "SELECTED";} ?>>=&nbsp;&nbsp;&nbsp;</option>	  			
		      <option value="!=" <? if(strcmp($_POST[$namecbsearch],"!=")==0) {echo "SELECTED";} ?>>EXc</option>	  						  
			</select>
			<? }	

		} 
		
		?>

	    <span class="etiqueta objeto"><?php echo $Labe2?></span>

		<? if($_POST[$optname]) $valopt=$_POST[$optname];
		   else	if($row[$namefield]) $valopt=$row[$namefield];
		   else if($value_ini) $valopt=$value_ini;
		   else $valopt=''; 	
		?>
		<input  type="radio" name="<? echo $optname  ?>" class="cajatexto"  id="<? echo $Label?>" value="<? echo $value?>"  <? if(strcmp($valopt,$value)==0) {echo "CHECKED";} ?> <? if($tipoedicion==3) echo "DISABLED" ?>	
		<? if($jump) {?>		
		onClick="MM_jumpMenu(form,this,'<? echo PAGE ?>')"
		<? }?>				
		 >	
		<? 
			//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
			if(strpos($optname,"XX")){ ?>
				<input name="<? echo substr($optname,2) ?>"   type="hidden" >	
			<? }

	if ($tablecierra=='' or $tablecierra==']') { 
		html_endobj(); 
		}  
	}
} 

function labelcombo($formavisual,$tipoedicion,$Label,$comboname,$row,$rs_source,$jump=0,$len=0,$tableabre='',$tablecierra='',$err_objeto='',$titucombo='------- Seleccione Opci&oacute;n -------')
{ 
global $db,$myOpcion;
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
 // busco valores iniciales
 $pos_fin=strpos($comboname,"=");
 $value_ini="";
 if($pos_fin)
	{ $namefield=substr($comboname,3,$pos_fin-3); 
	  $value_ini=substr($comboname,$pos_fin+1);
	  $comboname=trim(substr($comboname,0,$pos_fin));
	}
 else
   {$namefield=substr($comboname,3); 
   }
   $namecbsearch='_busq'.$namefield;    

	if ($tableabre=='' or $tableabre=='[') { 
			html_iniobj($err_objeto,$comboname,$Label);

		if($tipoedicion==5) {//busqueda
			if($len>0) {$len=$len-10;}	?>
			<input type="hidden"  name="<? echo $namecbsearch ?>" value="=" >	<? 
		} 
	} 		

	//CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
		?>
		<select name="<? echo $comboname ?>" class="cajatexto"    id="<? echo $Label?>" 
		<? if($jump) {?>
			onChange="MM_jumpMenu(form,this,'<? echo PAGE ?>')" <?
		   }else{ 
			echo $myOpcion;
		   }						
			?>
	
		onKeyPress="return formato(event,form,this)" <? if($tipoedicion==3 || ($tipoedicion==2 && substr($cajatxtname,2,1)=='s')) echo "DISABLED" ?>>
		   <option value="" ><? echo $titucombo ?></option>	
		  <? 
			while ($row_combo=$db->sql_fetchrow($rs_source)) 
			 {
			 $seleccionado="";
			 if($row[$namefield] && !isset($_POST[$comboname]) && strcmp(trim($row[$namefield]),trim($row_combo[0]))==0)
				$seleccionado="SELECTED"; 
			 else if(strcmp(trim($_POST[$comboname]),trim($row_combo[0]))==0) {
				$seleccionado="SELECTED";} 
			 else if(!isset($_POST[$comboname]) && strcmp(trim($value_ini),trim($row_combo[0]))==0) {
				$seleccionado="SELECTED";} 	  
		   ?>
			<option value="<? echo $row_combo[0]?>" <? echo $seleccionado ?>  ><? if($len==0) {echo $row_combo[1];} else {echo substr($row_combo[1],0,$len);}?></option>
		 <?  }?>	  
		</select>
	<? 
	
	//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
	if(strpos($comboname,"XX")){ ?>
		<input name="<? echo substr($comboname,2) ?>"   type="hidden" >	
		
	<? }

		if($db->sql_numrows($rs_source)>0) $db->sql_rowseek(0,$rs_source);

 	if ($tablecierra=='' or $tablecierra==']') 	{ 
			html_endobj(); 
		}  
}
} 

function TableDynamicButton($formavisual,$tipoedicion,$form,$tablehtml,$array,$HeaderTableDynamic,$FieldTableDynamic,$result)
{
global $db;
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
	html_iniobj(0,1,$Label); // 0 y 1 porq tienen q ser diferentes si no coloca los colores para error
	
	$fnum=$db->sql_numfields($result);

	//creo mi array  en java con una fila la cual contiene los nombres de las variables ocultas					
    $html="<"."script".">\n";
	$html.="var ".$array." = new Array () \n";	
	$html.=$array."[0]=Array(";	
	foreach($FieldTableDynamic as $valor) 
			$html.='"'.$valor.'",';	
		$html.='"0"';				
		$html.=")\n";					

//si existen registros, estos se envian al array del dynamictable
$varobjtxt=substr($FieldTableDynamic[0],2);
if($_POST[substr($FieldTableDynamic[0],2)]){
 if($_POST[substr($FieldTableDynamic[0],2)]!="*"){
	foreach($FieldTableDynamic as $explota) {
			$arrayx=substr($explota,2);
			$$arrayx=explode('®',$_POST[substr($explota,2)]);
			if(is_array($$arrayx))
				{
				$elementos=count($$arrayx)-1;
				}
		}
		if($elementos>0){
				for ($x=0;$x<$elementos;$x++) {
					$html.=$array."[".$array.".length]=Array(";			
					foreach($FieldTableDynamic as $explota){
						$arrayx=substr($explota,2);
						if(is_array($$arrayx)){
							$valor=each($$arrayx);
							$html.='"'.$valor[1].'",';
							}//if
						}//foreach
					$html.='"1"';				
					$html.=")\n";
				}//for
	
			}//if
   }
 }//if
else{
	if($result){
		while ($row=$db->sql_fetchrow($result)) {	
			$html.=$array."[".$array.".length]=Array(";
			foreach($FieldTableDynamic as $valor) {
				for ($x = 0; $x < $fnum ; $x++) {
				//recorro el arrya de campos y me aseguro q pase solo los valores de los campos indicados en el array
						if(substr($valor,strpos($valor,"ZZ")+2)==$db->sql_fieldname($x,$result))
							$html.='"'.$row[$x].'",';
							}//foreach
					} //for
			$html.='"1"';				
			$html.=")\n";					
	
		}	
		if($db->sql_numrows($result)>0) $db->sql_rowseek(0,$result);		
	}
}

	$html.="<"."/script".">\n";	
	echo $html;
	if ($tipoedicion!=3) {
		echo "<input type=\"button\" class=\"boton\" style=\"visibility=visible\" name=\"".$tablehtml."btn_add\" id=\"".$tablehtml."btn_add\" value=\"Añadir\" onClick=\"addTableDynamic($form,$array,$tablehtml,'$form','$array')\">";
		echo "<input type=\"button\" id=\"".$tablehtml."btn_remove\" style=\"visibility=hidden\" class=\"boton\" name=\"".$tablehtml."btn_remove\" value=\"Eliminar\" onClick=\"removeTableDynamic($form,$array,$tablehtml,'$form','$array')\" disabled >";	
		echo "<input type=\"button\" class=\"boton\" style=\"visibility=visible\" name=\"".$tablehtml."btn_clear\" value=\"Limpiar\" onClick=\"clearFormTableDynamic($form,$tablehtml)\">";		
			}
	else {
		echo "<input type=\"button\" class=\"boton\"  style=\"visibility=hidden\" name=\"".$tablehtml."btn_add\" value=\"Añadir\" >";
		echo "<input type=\"button\" id=\"".$tablehtml."btn_remove\" style=\"visibility=hidden\" class=\"boton\" name=\"".$tablehtml."btn_remove\" value=\"Eliminar\" >";	
		echo "<input type=\"button\" class=\"boton\" style=\"visibility=hidden\" name=\"".$tablehtml."btn_clear\" value=\"Limpiar\" >";		
	}	
		echo "<input type=\"hidden\" class=\"boton\" name=\"".$tablehtml."rowIndex\">";
			
	html_endobj(); 		
	TableDynamicConstr($formavisual,$tipoedicion,$tablehtml,$HeaderTableDynamic);

	echo "<"."script".">\n";
	echo "regenerateTable(".$array.",".$tablehtml.",'".$form."','".$array."') \n";
	if ($tipoedicion==3) {	
		echo "borrafilas(".sizeof($HeaderTableDynamic).")\n";
		}
	echo "<"."/script".">\n";	

}
} 

function TableDynamicConstr($formavisual,$tipoedicion,$tablehtml,$HeaderTableDynamic)
{
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
		echo "<tr><td colspan=\"7\" width=\"100%\"  class=\"marco\">";
		echo "<table id=\"$tablehtml\" width=\"100%\" border=\"1\" cellpadding=\"1\" cellspacing=\"1\">";
		echo "<tr>";
		foreach($HeaderTableDynamic as $Valor){
			if(substr($Valor,0,1)!='_')
				echo "<td align=\"center\" class=\"objeto\">".$Valor."</td>";}
		if($tipoedicion!=3)				
			echo "<td align=\"center\" class=\"objeto\">Op</td>";			
		echo "</tr>";
		echo "</table>";
		echo "</td></tr>";		
}
} 


function html_iniobj($err_objeto,$cajatxtname,$Label) { 
	global $_porcenleft,$_porcenright;

	?>
	<tr valign="middle">
	<td width="1%"  class="marco">&nbsp;</td>	
    <td width="<? echo $_porcenleft ?>" class="<? if(strcmp(trim($err_objeto),trim($cajatxtname))==0) echo "erroretiqueta"; else echo "etiqueta" ?>" align="right" ><?php echo $Label ?>&nbsp;&nbsp;</td>
    <td width="1%"  class="objeto">&nbsp;</td>
    <td width="<? echo $_porcenright ?>" class="objeto">	
	<?
}

function html_endobj() { 
	echo "</td>
		<td width='1%' class='objeto'>&nbsp;</td>
		</tr>";
}



function topform($btncaption,$tipoedicion,$title,$btngrid=0)
{
?>
			<table width="100%" cellspacing="0" cellpadding="3" border="0">
			<tr>
                            <th  bgcolor="#6600FF" width="20%" align="left">
					<? //botonera($tipoedicion,$btngrid,$btncaption,1) ?>
			    </th>
				<th width="70%" height="26">
				<? switch ($tipoedicion) {
				   case 1:
					   echo strtoupper($title)." :: [Nuevo Registro]";
					   break;
				   case 2:
					   echo strtoupper($title)." :: [Actualización]";
					   break;
				   case 3:
					   echo strtoupper($title)." :: [Visualización]";			
					   break;
				   case 5:
					   echo strtoupper($title)." :: [B&uacute;squeda de Datos]";			
					   break;
				   default:
					   echo $title;
					   break;				    
					}
				  ?>
				</th>	
		        <th width="25%" align="right">
					<? botonera($tipoedicion,$btngrid,$btncaption,2) ?>			  
    	        </th>				
			  </tr>
				</table>
<?
}

function botonera($tipoedicion,$btngrid,$btncaption,$tipo)
{
global $pathlib,$_url,$_where,$_op,$_pagina,$_orden,$_npop,$type,$_PreviewCaption,$_PreviewPage;
$type=($_type=='GL')?'L':'M';
$type=$_npop?'P':$type;		
				 

switch ($tipo) {
	   case 1:
			 if ($tipoedicion==1 || $tipoedicion==2 || $tipoedicion==5 || $btngrid) {?>			  
				 <input type="submit"  class="boton" id="btn_<? echo saca_char($btncaption,' ') ?>" name="btn_<? echo saca_char($btncaption,' ') ?>" value=".:: <? echo $btncaption ?> ::." >
				<? }

			if ($tipoedicion==3 && !$_npop && $_PreviewCaption) { 
				 echo "<input type=\"button\"  class=\"boton\" name=\"btn_VistaPrevia\" onClick=\"AbreVentana('".$_PreviewPage."', 'VistaPrevia')\" value=\".:: Vista Previa ::.\" >";			
					}
//				echo "<img src=\"".$pathlib."imagenes/next.gif\" alt=\"Buscar\" height=\"15\" width=\"15\" border=0 style=\"cursor:pointer\">";

			break; 
	   case 2:	   
			 if (($type=='P' && $tipoedicion) or $tipoedicion==2 or $tipoedicion==3 or ($tipoedicion==5 && $_where) or $btngrid) {?>
				 <input type="button" class="boton" name="btn_volver" value=".:: Volver ::." onclick="editaccion(5,'<? echo $type?>','<? echo $_url?>','<? echo $_op?>','<? echo $_pagina?>','<? echo $_orden?>','<? echo str_replace("'","\'",$_where) ?>',2,'<? echo $_npop?>')">
				 <!--input type="button" class="boton" name="btn_volver" value=".:: Volver ::." onclick="history.back()"-->				 
				<? }				 
			break; 	   
		}
		
}

function bottform($btncaption,$tipoedicion,$btngrid=0,$align="left")
{
global $_url,$_where,$_op,$_pagina,$_orden,$audita;
?>
		<table width="100%" cellspacing="1" cellpadding="3" border="0">			
				<tr>
					<td class="spaceRow" colspan="7" height="1"></td>
				</tr>
				<tr align="center">
				<td class="catBottom" colspan="7" height="28">
				
          <table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td width="100%" align="<?=$align?>">
			  
				<input type="hidden"  name="_action" 		value="<? echo $_POST['_action'] ?>" >						
				<input type="hidden"  name="_setfocus"      value="<? echo $_POST['_setfocus']?>">						
				<? 
				 if($audita==1){ ?>
					<!-- Campos para auditoría de la tabla desde el cliente !-->				
					<!-- fecha de grabacion para mi tabla !-->
					<input type="hidden"  name="___ultfecha"    value="<? echo date("Y/m/d") ?>">	
					<? if($tipoedicion==1) {?>
					<input type="hidden"  name="___fecha" 	    value="<? echo date("Y/m/d") ?>">
					<? }?>				
					<input type="hidden"  name="___hora" 	    value="<? echo date(" H:i:s",time()); ?>">
					<input type="hidden"  name="___id_usuario"  value="<? echo $_SESSION["id"] ?>">
					<input type="hidden"  name="___nick" 	    value="<? echo $_SESSION["nickusu"] ?>">				
				<?  }
				botonera($tipoedicion,$btngrid,$btncaption,1) ?>
			  </td>
              <td width="50%">
			  	
              </td>
              <td width="25%" align="right">
				<? botonera($tipoedicion,$btngrid,$btncaption,2) ?>			  
              </td>
            </tr>
        </table>
			</td>
			</tr>
      </table>
<?
}

function random()
{
mt_srand ((double)microtime()*1000000);
$maxran = 1000000;
$random_num = mt_rand(0, $maxran);
return ($random_num);
}

function gfx($random_num) {
    $code = encripcode($random_num);
    $image = ImageCreateFromJPEG("imagenes/code_bg.jpg");	
    $text_color = ImageColorAllocate($image, 80, 80, 80);
    Header("Content-type: image/jpeg");
    ImageString ($image, 5, 12, 2, $code, $text_color);
    ImageJPEG($image,'', 75);
    ImageDestroy($image);
    die();
}

function encripcode($random_num){
	$sitekey = "SdFk*fa28367-dm56w69.3a2fDS+e9";
    $datekey = date("F j");
    $rcode = hexdec(md5($_SERVER[HTTP_USER_AGENT] . $sitekey . $random_num . $datekey));
    $code = substr($rcode, 2, 6);
	return($code);
}

function iif($var1,$cond,$var2,$res1,$res2)
{
$_eval="if(\"$var1\"". $cond ." \"$var2\") { \$solution = \$res1  ;} else { \$solution = \$res2 ;}"; 
eval($_eval);
return($solution);
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

function themesidebox($title, $content) {
global $pathlib ;
    echo "<table border=\"0\" align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">"
	."<tr><td background=\"".$pathlib."imagenes/table-title.gif\" width=\"100%\" height=\"20\">"
	."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FFFFFF\"><b>$title</b></font>"
	."</td></tr><tr><td><img src=\"".$pathlib."imagenes/pixel.gif\" width=\"100%\" height=\"1\"></td></tr></table>\n"
	."<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
	."<tr><td width=\"100%\" bgcolor=\"#000000\">\n"
	."<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">\n"
	."<tr><td width=\"100%\" bgcolor=\"#ffffff\">\n"
	."$content"
	."</td></tr></table></td></tr></table>";
}

function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
        global $db, $template, $board_config, $theme, $lang, $phpEx, $phpbb_root_path, $nav_links, $gen_simple_header, $images;
        global $userdata, $user_ip, $session_length;
        global $starttime;

        if(defined('HAS_DIED'))
        {
                die("message_die() was called multiple times. This isn't supposed to happen. Was message_die() used in page_tail.php?");
        }

        define(HAS_DIED, 1);


        $sql_store = $sql;

        //
        // Get SQL error if we are debugging. Do this as soon as possible to prevent
        // subsequent queries from overwriting the status of sql_error()
        //
        if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
        {
                $sql_error = $db->sql_error();

                $debug_text = '';

                if ( $sql_error['message'] != '' )
                {
                        $debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
                }

                if ( $sql_store != '' )
                {
                        $debug_text .= "<br /><br />$sql_store";
                }

                if ( $err_line != '' && $err_file != '' )
                {
                        $debug_text .= '</br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
                }
        }

        if( empty($userdata) && ( $msg_code == GENERAL_MESSAGE || $msg_code == GENERAL_ERROR ) )
        {
                $userdata = session_pagestart($user_ip, PAGE_INDEX, $nukeuser);
                init_userprefs($userdata);
        }

        //
        // If the header hasn't been output then do it
        //
        if ( !defined('HEADER_INC') && $msg_code != CRITICAL_ERROR )
        {
                if ( empty($lang) )
                {
                        if ( !empty($board_config['default_lang']) )
                        {
                                include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.'.$phpEx);
                        }
                        else
                        {
                                include($phpbb_root_path . 'language/lang_english/lang_main.'.$phpEx);
                        }
                }

                if ( empty($template) )
                {
                        $ThemeSel = get_theme();
                        if (file_exists("themes/$ThemeSel/forums/".$board_config['board_template']."/index_body.tpl")) {
                            $template = new Template("themes/$ThemeSel/forums/".$board_config['board_template']."");
                        } else {
                            $template = new Template($phpbb_root_path . 'templates/' . $board_config['board_template']);
                        }
                }
                if ( empty($theme) )
                {
                        $theme = setup_style($board_config['default_style']);
                }

                //
                // Load the Page Header
                //
                if ( !defined('IN_ADMIN') )
                {
                        include("includes/page_header.php");
                }
                else
                {
                        include($phpbb_root_path . 'admin/page_header_admin.'.$phpEx);
                }
        }

        switch($msg_code)
        {
                case GENERAL_MESSAGE:
                        if ( $msg_title == '' )
                        {
                                $msg_title = $lang['Information'];
                        }
                        break;

                case CRITICAL_MESSAGE:
                        if ( $msg_title == '' )
                        {
                                $msg_title = $lang['Critical_Information'];
                        }
                        break;

                case GENERAL_ERROR:
                        if ( $msg_text == '' )
                        {
                                $msg_text = $lang['An_error_occured'];
                        }

                        if ( $msg_title == '' )
                        {
                                $msg_title = $lang['General_Error'];
                        }
                        break;

                case CRITICAL_ERROR:
                        //
                        // Critical errors mean we cannot rely on _ANY_ DB information being
                        // available so we're going to dump out a simple echo'd statement
                        //
                        include('ms-analysis/lang-spanish.php');

                        if ( $msg_text == '' )
                        {
                                $msg_text = $lang['A_critical_error'];
                        }

                        if ( $msg_title == '' )
                        {
                                $msg_title = 'Error : <b>' . $lang['Critical_Error'] . '</b>';
                        }
                        break;
        }

        //
        // Add on DEBUG info if we've enabled debug mode and this is an error. This
        // prevents debug info being output for general messages should DEBUG be
        // set TRUE by accident (preventing confusion for the end user!)
        //
        if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
        {
                if ( $debug_text != '' )
                {
                        $msg_text = $msg_text . '<br /><br /><b><u>DEBUG MODE</u></b>' . $debug_text;
                }
        }

        if ( $msg_code != CRITICAL_ERROR )
        {
                if ( !empty($lang[$msg_text]) )
                {
                        $msg_text = $lang[$msg_text];
                }

                if ( !defined('IN_ADMIN') )
                {
                        $template->set_filenames(array(
                                'message_body' => 'message_body.tpl')
                        );
                }
                else
                {
                        $template->set_filenames(array(
                                'message_body' => 'admin/admin_message_body.tpl')
                        );
                }

                $template->assign_vars(array(
                        'MESSAGE_TITLE' => $msg_title,
                        'MESSAGE_TEXT' => $msg_text)
                );
                $template->pparse('message_body');

                if ( !defined('IN_ADMIN') )
                {
                        include("includes/page_tail.php");
                }
                else
                {
                        include($phpbb_root_path . 'admin/page_footer_admin.'.$phpEx);
                }
        }
        else
        {
                echo "<html>\n<body>\n" . $msg_title . "\n<br /><br />\n" . $msg_text . "</body>\n</html>";
        }

        exit;
}

function ini_mod($version,$sistema){
global $_nameop,$pathlib;
echo "<html>\n";
echo "<title>$version $sistema | $_nameop</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"../mislibs/tabscreen.css\" />\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"".$pathlib."styles/menumx.css\"></link>\n";		
echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"print\" href=\"../mislibs/tabprint.css\" />\n"; 
echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"../mislibs/estilos.css\" />\n"; 
echo "<script type=\"text/javascript\" src=\"".$pathlib."libjsgen.js\"> </script>\n"; 
echo "<script type=\"text/javascript\" src=\"../mislibs/libjsgen_extend.js\"> </script>\n"; 
echo "<script type=\"text/javascript\" src=\"".$pathlib."popcalendar.js\"> </script>\n"; 
echo "</html>\n";
}

// Crea un array javascript a partir de de un recordset 
function crea_array_js($result,$nomArray)
{
global $db;

	$numrows=$db->sql_numrows($result);
	$fnum=$db->sql_numfields($result);
		
	$html = "<"."script".">\n";
	$html .= $nomArray." = new Array();\n";
	for ($i = 0; $i < $numrows; $i++) {
       $row     = $db->sql_fetchrow($result);
	   $cArray = $nomArray."[".$nomArray.".length] = new Array(";
       for ($x = 0; $x < $fnum; $x++) {
		   $fieldname = $db->sql_fieldname($x,$result);	   
		   $dato_campo = $row[$fieldname];
		   $cArray .= "'".$dato_campo."'".",";
       }	
	   $cArray = substr($cArray,0,$cArray.length-1).");\n";
	   
	$html .= $cArray;
	}
	$html .= "</"."script".">\n";
	echo $html;
} 

function labelpopup($formavisual,$tipoedicion,$Label,$comboname,$row,$form,$size,$_npop,$jump=0,$len=0,$type='P',$tableabre='',$tablecierra='',$err_objeto='',$titucombo='------- Seleccione Opci&oacute;n -------')
{ 
global $pathlib,$nameform,$db,$myOpcion;
//$formavisual: 1->aparece en todo, 2-> aparece en todo exepto en la busqueda 3->no aparece solo en el ingreso, 5->aparece solo en la busqueda,  
if($formavisual==1 or ($formavisual==5 && $tipoedicion==5) or ($formavisual==3 && $tipoedicion!=1) or ($formavisual==2 && $tipoedicion!=5)) {
 // busco valores iniciales
 $pos_fin=strpos($comboname,"=");
 $value_ini="";
 if($pos_fin)
	{ $namefield=substr($comboname,3,$pos_fin-3); 
	  $value_ini=substr($comboname,$pos_fin+1);
	  $comboname=trim(substr($comboname,0,$pos_fin));
	}
 else
   {$namefield=substr($comboname,3); 
   }
   $namecbsearch='_busq'.$namefield;    

	if ($tableabre=='' or $tableabre=='[') { 
			html_iniobj($err_objeto,$comboname,$Label);
		} 
	if($tipoedicion==5) {//busqueda
		if($len>0) {$len=$len-10;}	?>
		<input type="hidden"  name="<? echo $namecbsearch ?>" value="=" > <?
	} 

	//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
//	if(strpos($comboname,"XX")){ 
//		$cajatxtname= 'seek'.substr($comboname,5).substr($comboname,2,6);	}
//	else
		$cajatxtname= 'seek'.$comboname;	

	$_sbpop=1;
	$_typetemp=$_type;
	$_optemp=$_op;
	$_type='P';
	$_op=substr($_npop,1);
	include('modulo.php');
	$_type=$_typetemp;
	$_op=$_optemp;

	$rs_source=$db->sql_query($_stringsql." ".$_stringsqlwhere." ".$_stringsqlorder);
	if(!$rs_source) {die($db->sql_error().'Error en consulta para generar objeto'); }
	
	// Para el Onblur de la caja de texto, con el fin de que haga un submit si lo deseamos 	
	if($jump)
		$_onblur_text="form._setfocus.value='".$comboname."'; this.form.action=''; this.form.submit()";
	else
		$_onblur_text=$myOpcion;	

	?>
    <input class="cajatexto" name="<? echo $cajatxtname ?>" type="text" size="<? echo $size?>" value="<? if($row[$namefield] && !isset($_POST[$cajatxtname])) {echo iif(strtoupper(substr($comboname,0,1)),'==','Z',str_pad(trim($row[$namefield]),$size,'0', STR_PAD_LEFT),trim($row[$namefield]));} else if($_POST[$cajatxtname]) {echo iif(strtoupper(substr($comboname,0,1)),'==','Z',str_pad(trim($_POST[$cajatxtname]),$size,'0', STR_PAD_LEFT),trim($_POST[$cajatxtname]));} else if($value_ini) {echo trim($value_ini);} ?>"  maxlength="<? echo $size ?>"  onKeyPress="return formato(event,form,this,<? echo $size ?>)" <? if($tipoedicion==3  || ($tipoedicion==2 && substr($cajatxtname,2,1)=='s')) echo "DISABLED" ?>
	onBlur="cambiacampo('<? echo $form ?>','<? echo $comboname ?>',this.value,<? echo $size ?>); <? echo $_onblur_text ?>" >
	<?
	if($tipoedicion!=3) {//en la opcion del grid ver detalle no se muestra este icono
	?>
		<img src="<? echo $pathlib ?>imagenes/search.gif" alt="Buscar" height="14" width="16" border=0 onclick="Open('<? echo $form ?>','<? echo $_npop ?>','<? echo $cajatxtname ?>','<? echo $type ?>');" style="cursor:pointer" >
	<?
	}
	?>
    <select name='<? echo $comboname ?>' class="cajatexto"    id="<? echo $Label?>" 
	onChange="jumplabelpopup('<? echo $form ?>',form,this,'<? echo PAGE ?>','<? echo $cajatxtname ?>',this.value,<? echo $jump ?>); <? echo $myOpcion ?>"  
	onKeyPress="return formato(event,form,this)" <? if($tipoedicion==3 || ($tipoedicion==2 && substr($cajatxtname,2,1)=='s')) echo "DISABLED" ?>>
       <option value="" ><? echo $titucombo ?></option>	
      <? 
		while ($row_combo=$db->sql_fetchrow($rs_source)) 
	     {
		 $seleccionado="";
		 if($row[$namefield] && !isset($_POST[$comboname]) && strcmp(trim($row[$namefield]),trim($row_combo[0]))==0) 
		 	$seleccionado="SELECTED"; 
		 else if(strcmp(trim($_POST[$comboname]),trim($row_combo[0]))==0) {
			$seleccionado="SELECTED";} 
		 else if(!isset($_POST[$comboname]) && strcmp(trim($value_ini),trim($row_combo[0]))==0) {
			$seleccionado="SELECTED";} 	  
	   ?>
        <option value="<? echo $row_combo[0]?>" <? echo $seleccionado ?>  ><? if($len==0) {echo $row_combo[$popcolmues];} else {echo substr($row_combo[$popcolmues],0,$len);}?></option>
	 <?  }?>	  
	</select>
	<input type="hidden" name="<? echo $_npop ?>" value="<? echo $cajatxtname ?>">	
	<? 
	//CREO EL CAMPO OCULTO CUANDO SE ESTA TRABAJANDO CON TABLAS DINAMICAS
	if(strpos($comboname,"XX")){ ?>
		<input name="<? echo substr($comboname,2) ?>"   type="hidden" >	
	<? }

		if($db->sql_numrows($rs_source)>0) $db->sql_rowseek(0,$rs_source);

 	if ($tablecierra=='' or $tablecierra==']') 	{ 
		html_endobj(); 
		}  
	}
	
	// para que el combo tenga el mismo valor que la caja de texto, cuando se hace un submit al formulario
	// valido especialmente cuando se ha agregado un nuevo registro en el popup.
	if($_POST[$cajatxtname] or $row[$namefield])	{
		$html = "<"."script".">\n";
		$html .= "document.".$form.".".$comboname.".value=document.".$form.".".$cajatxtname.".value";
		$html .= "</"."script".">\n";
		echo $html;
	}
} 


function saca_valor($sql,$valueretorno)
{
global $db;
$rssacavalor=$db->sql_query($sql);	
if(!$rssacavalor) {die($db->sql_error().' '.$sql);}
$row     = $db->sql_fetchrow($rssacavalor);
$retorno=$row[$valueretorno];
$db->sql_freeresult($rssacavalor);	
return($retorno);
}

function alert_inicial($valor) /* Ya no se usa */
	{
	echo "<"."script".">\n";
	echo "alert(".'"'.$valor.'"'.")\n";	
	echo "<"."/script".">\n";	
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

/*********************/
function alert2($valor)
	{
	echo "<"."script".">\n";
	echo "alert(".'"'.$valor.'"'.")\n";	
	echo "<"."/script".">\n";	
}


//FUNCION QUE DEVUELVE EL COLOR DE LA FILA SEGUN ARRAY
function fgridrowcolor($NameCampos,$ValorCampos,$NumCampos){
global $gridrowcolor;
$classreturn="";
if(is_array($gridrowcolor)){
	//recorro el arra de configuracion de color
	foreach ($gridrowcolor as $key => $value)
		//recorro los campos
		for ($x = 0; $x < $NumCampos-1; $x++) {
			if(strcmp(trim($NameCampos[$x]),trim($value['campo'])==0) && strcmp(trim($ValorCampos[$x]),trim($value['dato']))==0){
					$classreturn="bgcolor='".$value['color']."'";						
					$gridrowcolor[$key]['cuenta']=$value['cuenta']+1;
					break;
				}
			}
}
return($classreturn);
}

function fgridcolconfig($NameCampos,$Num,$valor,$tipo){
global $gridcolconfig ;
	switch($tipo){
		case 1:
			$classreturn="nowrap";
			break;
		case 2:
			$classreturn=muestra_html($valor)."&nbsp";
			break;
		}

if(is_array($gridcolconfig)){
	//recorro el arra de configuracion de color
	foreach ($gridcolconfig as $key => $value) {
			if($NameCampos[$Num]==$value['campo']){
				switch($tipo){
					case 1:
						$classreturn ="bgcolor='".$value['color']."' ";
						$classreturn.="width='".$value['width']."'";
						break;
					case 2:
						if($valor)
							if ($value['obj']) 
								$classreturn=str_replace("MyValue",muestra_html($valor),$value['obj']);
						break;
					}
					break;
			}
			}
}

return($classreturn);
}

function array_envia($array) { 
    $tmp = serialize($array); 
    $tmp = urlencode($tmp); 
    return $tmp; 
} 

function array_recibe($url_array) { 
    $tmp = stripslashes($url_array); 
    $tmp = urldecode($tmp); 
    $tmp = unserialize($tmp); 
   return $tmp; 
} 

function table_create($result)
{
	global $db;
	
	$numrows=$db->sql_numrows($result);
	$fnum=$db->sql_numfields($result);
	
	// Cabecera de la Tabla
	echo "<table width='100%'>";
	echo "<tr>";
	for ($x = 0; $x < $fnum; $x++) {
		echo "<td><b>";
		echo ucwords($db->sql_fieldname($x,$result));
		echo "</b></td>";
	}
	echo "</tr>";
	
	// Datos de la tabla
	for ($i = 0; $i < $numrows; $i++) {
		$row = $db->sql_fetchrow($result);
		echo "<tr>";
		for ($x = 0; $x < $fnum; $x++) {
			$fieldname = $db->sql_fieldname($x,$result);
			echo "<td>";
			$dato_campo = $row[$x];	      
			if ($dato_campo)
				echo $dato_campo;
			else
				echo "&nbsp;";

			echo "</td>";
		}
		echo"</tr>";
	}

	// Cierro la tabla
	echo "</table>";
} 

function AbreVentana($sURL, $Handle){
	echo "<"."script".">\n";
	echo "AbreVentana(".'"'.$sURL.'","'.$Handle.'"'.")\n";	
	echo "<"."/script".">\n";	
}

function muestra_html($msg){
	//$msg = addslashes($msg);
//	$msg = str_replace("\n", "\\n", $msg);
//	$msg = str_replace("\r", "\\r", $msg);
	$msg = htmlspecialchars($msg);
	return $msg;
}

function compara_fechas($fecha1,$fecha2)
/* Usos 
*------
$f1="30/01/1993";
$f2="30-01-1992";
if (compara_fechas($f1,$f2) <0)
      echo "$f1 es menor que $f2 <br>";

if (compara_fechas($f1,$f2) >0)
      echo "$f1 es mayor que $f2 <br>";

if (compara_fechas($f1,$f2) ==0)
      echo "$f1 es igual  que $f2 <br>";

*/
{
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
              list($dia1,$mes1,$año1)=split("/",$fecha1);

      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
              list($dia1,$mes1,$año1)=split("-",$fecha1);

        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
              list($dia2,$mes2,$año2)=split("/",$fecha2);

      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
              list($dia2,$mes2,$año2)=split("-",$fecha2);

	        $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
        return ($dif);                         
}

function dateFormat($input_date, $input_format, $output_format) {
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

function fecha_completa(){
/* Presentación de los resultados en una forma similar a la siguiente:
Miércoles, 23 de junio de 2004 | 17:20
*/

/* Definición de los meses del año en castellano */

$mes[0]="-";
$mes[1]="enero";
$mes[2]="febrero";
$mes[3]="marzo";
$mes[4]="abril";
$mes[5]="mayo";
$mes[6]="junio";
$mes[7]="julio";
$mes[8]="agosto";
$mes[9]="septiembre";
$mes[10]="octubre";
$mes[11]="noviembre";
$mes[12]="diciembre";

/* Definición de los días de la semana */

$dia[0]="Domingo";
$dia[1]="Lunes";
$dia[2]="Martes";
$dia[3]="Miércoles";
$dia[4]="Jueves";
$dia[5]="Viernes";
$dia[6]="Sábado";

/* Implementación de las variables que calculan la fecha */

$gisett=(int)date("w");
$mesnum=(int)date("m");

/* Variable que calcula la hora
*/
$hora = date(" H:i",time());
return $dia[$gisett].", ".date("d")." de ".$mes[$mesnum]." de ".date("Y")." | ".$hora;;
}

/* Para mostrar el mesaje de error que devuelve la BD */
function muestra_error($ArrMsjErro){
    $hasta = strpos($ArrMsjErro['message'],'DETAIL');
    if ($hasta == FALSE)
        return $ArrMsjErro['message'];
    else 
        return trim(substr($ArrMsjErro['message'],0,$hasta));
}

/*  Funciones para medir el tiempo de ejecución de un script 
Modo de uso:
Iniciar temporarizador
time_start();
// Contenido del script original
Mostrar el tiempo de ejecución
echo time_end(); 
*/

function time_start() {
	global $starttime;
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$starttime = $mtime;
}
 
function time_end() {
	global $starttime;
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	return ($mtime - $starttime);
}
/**/

/**
 * Funcion personal para control de errores
 *
 * @param unknown_type $errno
 * @param unknown_type $errstr
 * @param unknown_type $errfile
 * @param unknown_type $errline
 */
function my_error_handler($errno, $errstr, $errfile, $errline){
  $errno = $errno & error_reporting();
  if($errno == 0) return;
  if(_DESARROLLO_){
	  if(!defined('E_STRICT'))            define('E_STRICT', 2048);
	  if(!defined('E_RECOVERABLE_ERROR')) define('E_RECOVERABLE_ERROR', 4096);
	  print "<pre>\n<b>";
	  switch($errno){
	    case E_ERROR:               print "Error";                  break;
	    case E_WARNING:             print "Warning";                break;
	    case E_PARSE:               print "Parse Error";            break;
	    case E_NOTICE:              print "Notice";                 break;
	    case E_CORE_ERROR:          print "Core Error";             break;
	    case E_CORE_WARNING:        print "Core Warning";           break;
	    case E_COMPILE_ERROR:       print "Compile Error";          break;
	    case E_COMPILE_WARNING:     print "Compile Warning";        break;
	    case E_USER_ERROR:          print "User Error";             break;
	    case E_USER_WARNING:        print "User Warning";           break;
	    case E_USER_NOTICE:         print "User Notice";            break;
	    case E_STRICT:              print "Strict Notice";          break;
	    case E_RECOVERABLE_ERROR:   print "Recoverable Error";      break;
	    default:                    print "Unknown error ($errno)"; break;
	  }
	  print ":</b> <i>$errstr</i>\n";
	  if(function_exists('debug_backtrace')){
	    //print "backtrace:\n";
	    $backtrace = debug_backtrace();
	    array_shift($backtrace);
	    foreach($backtrace as $i=>$l){
	      print "[$i] in function <b>{$l['class']}{$l['type']}{$l['function']}</b>";
	      if($l['file']) print " in <b>{$l['file']}</b>";
	      if($l['line']) print " on line <b>{$l['line']}</b>";
	      print "\n";
	    }
	  }
	  print "\n</pre>";
	  if(isset($GLOBALS['error_fatal'])){
	    if($GLOBALS['error_fatal'] & $errno) die('fatal');
	  }
  }	
}

function error_fatal($mask = NULL){
  if(!is_null($mask)){
      $GLOBALS['error_fatal'] = $mask;
  }elseif(!isset($GLOBALS['die_on'])){
      $GLOBALS['error_fatal'] = 0;
  }
  return $GLOBALS['error_fatal'];
}

?>
