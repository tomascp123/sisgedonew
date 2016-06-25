
<style>
.tabsonline,.tabsoffline,.tabsoffline a {font-size: 10px; color:#FFFFFF; text-decoration: none; }.tabsoffline a:active,.tabsoffline a:link,.tabsoffline a:visited,.tabsonline a:link,.tabsonline a:visited {text-decoration: none; color:#FFFFFF;}.tabsline {background-image: url(/libsmx/imagenes/tabbackline2.gif); background-position: bottom; background-repeat: repeat-x;}.tabsoff {color: #ffffff;}.tabsoffline {background-image: url(/libsmx/imagenes/tabbglineoff2.gif);  background-repeat: repeat-x;}.tabson {color: #ffffff;}.tabsonline {background-image: url(/libsmx/imagenes/tabbglineon2.gif); background-repeat: repeat-x;}.maintable .tabstext {color: #ffffff;}.maintable .tabs {vertical-align:bottom;}
</style>
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center"  bgcolor="#5C5C5C">
 <tr><td >
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
			<tr><td  class="tabs" >
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<? if($_tabactivo==1) {?>
						<td width="20" class="tabsline"><img  src="<? echo $pathlib ?>imagenes/tabinion2.gif" width="20" height="16" border="0" ></td>	
						<td width="100" align="center" class="tabsonline"  valign="middle"><nobr><a href='<? echo $_url ?>?_op=<? echo $_op ?>&_type=<? echo $_type?>&_tabactivo=1'><b><font color="#000000"><? echo $_tab1_caption ?></font></b></a></nobr></td>	
						<? if($_tab2_caption) {?>
							<td width="28" class="tabson"><img  src="<? echo $pathlib ?>imagenes/tabmidon3.gif" width="28" height="16" border="0" alt="" ></td>	
							<td width="100" align="center" class="tabsoffline"><nobr><a href='<? echo $_url ?>?_op=<? echo $_op ?>&_type=<? echo $_type?>&_tabactivo=2'><? echo $_tab2_caption ?></a></nobr></td>	
							<td width="11" class="tabsoff"><img  src="<? echo $pathlib ?>imagenes/tabendoff2.gif" width="11" height="16" border="0" alt="" ></td>	
						<? ;} else {?>
							<td width="28" class="tabson"><img  src="<? echo $pathlib ?>imagenes/tabendon.gif" width="28" height="16" border="0" alt="" ></td>							
						<? }?>						

						<td class="tabsline" width="100%">
					    </td></tr>	
					<? ; } else if($_tabactivo==2 ) {?>
						<td width="20" class="tabsline"><img  src="<? echo $pathlib ?>imagenes/tabinioff2.gif" width="20" height="16" border="0" ></td>	
						<td width="100" align="center" class="tabsoffline"><nobr><a href='<? echo $_url ?>?_op=<? echo $_op ?>&_type=<? echo $_type ?>&_tabactivo=1'><? echo $_tab1_caption ?></a></nobr></td>	
						<td width="28" class="tabson"><img  src="<? echo $pathlib ?>imagenes/tabmidon4.gif" width="28" height="16" border="0" alt="" ></td>	
						<td width="100" align="center" class="tabsonline"><nobr><a href='<? echo $_url ?>?_op=<? echo $_op ?>&_type=<? echo $_type?>&_tabactivo=2'><b><font color="#000000"><? echo $_tab2_caption ?></font></b></a></nobr></td>							
						<td width="11" class="tabsoff"><img  src="<? echo $pathlib ?>imagenes/tabendon2.gif" width="11" height="16" border="0" alt="" ></td>	
						<td class="tabsline" width="100%"></td></tr>	
					<? ; } else {?> <td width="100%"  class="tabsoff" height="25">&nbsp;</td></tr><? } ?>	
        		</table>
			</td></tr>
    </table>
 </td></tr>
</table>

