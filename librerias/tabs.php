<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center"  bgcolor="#336699">
 <tr><td >
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
			<tr><td  class="tabs" >
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<? if($_tabactivo==1) {?>
						<td width="20" class="tabsline"><img  src="<? echo $pathlib ?>imagenes/tabinion.gif" width="20" height="29" border="0" ></td>	
						<td width="100" align="center" class="tabsonline"><nobr><b><a href='<? echo $_url ?>?_op=<? echo $_op ?>&_type=<? echo $_type?>&_tabactivo=1'><b><? echo $_tab1_caption ?></b></a></nobr></td>	
						<? if($_tab2_caption) {?>
							<td width="28" class="tabson"><img  src="<? echo $pathlib ?>imagenes/tabmidon.gif" width="28" height="29" border="0" alt="" ></td>	
							<td width="100" align="center" class="tabsoffline"><nobr><a href='<? echo $_url ?>?_op=<? echo $_op ?>&_type=<? echo $_type?>&_tabactivo=2'><b><? echo $_tab2_caption ?></b></a></nobr></td>	
							<td width="11" class="tabsoff"><img  src="<? echo $pathlib ?>imagenes/tabendoff.gif" width="11" height="29" border="0" alt="" ></td>	
						<? ;} else {?>
							<td width="28" class="tabson"><img  src="<? echo $pathlib ?>imagenes/tabendon.gif" width="28" height="29" border="0" alt="" ></td>							
						<? }?>						

						<td class="tabsline" width="100%">
					    </td></tr>	
					<? ; } else if($_tabactivo==2 ) {?>
						<td width="20" class="tabsline"><img  src="<? echo $pathlib ?>imagenes/tabinioff.gif" width="20" height="29" border="0" ></td>	
						<td width="100" align="center" class="tabsoffline"><nobr><a href='<? echo $_url ?>?_op=<? echo $_op ?>&_type=<? echo $_type ?>&_tabactivo=1'><b><? echo $_tab1_caption ?></b></a></nobr></td>	
						<td width="28" class="tabson"><img  src="<? echo $pathlib ?>imagenes/tabmidon2.gif" width="28" height="29" border="0" alt="" ></td>	
						<td width="100" align="center" class="tabsonline"><nobr><b><a href='<? echo $_url ?>?_op=<? echo $_op ?>&_type=<? echo $_type?>&_tabactivo=2'><b><? echo $_tab2_caption ?></b></a></nobr></td>							
						<td width="11" class="tabsoff"><img  src="<? echo $pathlib ?>imagenes/tabendon.gif" width="11" height="29" border="0" alt="" ></td>	
						<td class="tabsline" width="100%"></td></tr>	
					<? ; } else {?> 
 						<td width="100%"  class="tabsoff">&nbsp;</td></tr> 
					<? } ?>	
        		</table>
			</td></tr>
    </table>
 </td></tr>
</table>

