<?
// Make sure people don't try and access it directly
include('checksession.php');
?>

<script>

function olvido() {
	// la extensión, o separador "&" debe ser substituido por coma ","
        AbreMyVentana('../app/adminUsuario_olvidoClave.php','olvido',400,300)
} 
</script>

<table width="100%" height="81%" border="0" align="center" cellpadding="0" cellspacing="0" class="backform">
  <tr>
    <td>
	<table width="600"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#336699" background="../imagenes/login.jpg">
	<tr bordercolor="#FFFFFF"> 
	<td>
	<table width="600" height="360" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#336699">	
		<tr bordercolor="#FFFFFF"> 
          <td width="54" height="19">&nbsp;</td>
          <td width="153" height="19">&nbsp;</td>
          <td width="126" height="19">&nbsp;</td>
          <td width="305">&nbsp;</td>
        </tr>
        <form name="formregistro" action="verifica.php" method="post">
          <tr bordercolor="#FFFFFF"> 
            <td height="30" colspan="3"></td>
            <td>
              <? If ($error =='1') { ;?>
              <div align="center"><strong><font color="#FF0000">Datos Incorrectos<br>
                Ingrese Correctamente los Datos </font></strong> </div>
              <? } ELSEIf ($error =='2') { ;?>
              <div align="center"><strong><font color="#FF0000">Los Datos Ingresados 
                son Inválidos</font></strong> </div>
              <? } ELSEIf ($error =='3') { ;?>
              <div align="center"><strong><font color="#FF0000">Operaci&oacute;n 
                no Autorizada !!!<br>
                Ingrese su Nickname y Password por favor</font></strong></div>
              <? } ELSEIf ($error =='4') { ;?>
              <div align="center"><strong><font color="#FF0000">Lo siento, Su 
                Cuenta Esta Desactivada !!!<br>
                Contacte al administrador de Cuentas</font></strong></div>
              <? } ELSEIf ($error =='5') { ;?>
              <div align="center"><strong><font color="#FF0000">El Código de seguridad 
                Ingresado es Inválido</font></strong></div>
              <? } ELSEIf ($error =='6') { ;?>
              <div align="center"><strong><font color="#FF0000">Su cuenta de acceso ha caducado !!!<br>
                Contacte al administrador del Sistema</font></strong></div>
              <? } ELSE { ?>
              <div align="center"><strong><font color="#FF0000"> Bienvenido Ingrese 
                Correctamente los Datos </font></strong> </div>
              <? } ?>
            </td>
          </tr>
          <tr bordercolor="#000099"> 
            <td height="30" colspan="3"><div align="right"> </td>
            <td valign="top"> 
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr> 
                  <td width="52%"><font color="#000099"><strong>Nombre de Usuario</strong> 
                    </font></td>
                  <td width="47%"> 
                    <input name="Srnick" type="text" tabindex="1" size="20" maxlength="20" onKeyPress="return formato(event,form,this,20)" value=""></td>
                </tr>
                <tr> 
                  <td><font color="#000099"><strong>Contrase&ntilde;a</strong> 
                    </font></td>
                  <td>
				  <input name="srpass" type="password" tabindex="2" size="20" maxlength="20" onKeyPress="return formato(event,form,this,20)" value="">
				  </td>
                </tr>
                <tr> 
                  <td><font color="#000099"><strong>C&oacute;digo de Seguridad</strong> 
                    </font></td>
                  <td>
				  	<? $random_num=random();?>
					<input name="sr_codesecurity"  type="text"   tabindex="3"  size="6"  maxlength="6"  onKeyPress="return formato(event,form,this,6)" >
					&nbsp;<img src='<? echo $pathlib ?>libphpgen.php?random_num=<? echo $random_num ?>' border='1' alt='Código de Seguridad' title='Código de Seguridad'>
					<input name=_secucodesecurity type="hidden" value=<? echo $random_num ?> >					
				  </td>
                </tr>

                <tr> 
                  <td colspan="3">&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td><div align="center"> 
                      <input type="submit" name="Submit" value=".:: Ingresar ::.">
                    </div></td>
		           <td width="1%">&nbsp;</td>		  
                </tr>
                <tr> 
                    <td colspan="2" style="text-align: right">
                        <br>
                        <a href='#' style='font-size: 10pt' onClick=olvido()>&nbsp;¿Olvidó su Contraseña?</a>
                  </td>
                </tr>
                
              </table></td>
          </tr>
          <tr bordercolor="#000099"> 
            <td height="22" colspan="4"></td>
          </tr>
          <tr bordercolor="#000099"> 
            <td height="25" colspan="4"></td>
          </tr>
        </form>
      </table></td>
  </tr>
</table>
</table>
<?
if(!$convenio){ /* Si no es un convenio */
?>
	<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
	</script>
	<script type="text/javascript">
	_uacct = "UA-2602606-3";
	urchinTracker();
	</script>
<?} ?>