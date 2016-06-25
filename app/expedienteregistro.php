<?
// Make sure people don't try and access it directly
include('checksession.php');
$v_esdecaseta = $_SESSION["usua_caseta"]; // Si es usuario que atiende en la caseta de trámite

// verifica si esta pagina ha sido llamada  para modificacion o visualizacion
if($_tipoedicion==2 || $_tipoedicion==3) {
	$query="SELECT * FROM ".$_table." WHERE ".$_campoclave.'='.$_mydato;
    $rsquery=$db->sql_query($query);	
	if(!$rsquery) {die($db->sql_error().' Error al consultar expediente '); }
	$row     = $db->sql_fetchrow($rsquery);
	
	$op_expe_origen=$row[expe_origen];	
}else{
	if($_GET[_regcopia]){
		$query="SELECT * FROM $_table WHERE $_campoclave=$_GET[_regcopia]";
    	$rsquery=$db->sql_query($query);	
		if(!$rsquery) {die($db->sql_error().' Error al consultar expediente a copiar '); }
		$row     = $db->sql_fetchrow($rsquery);

		$op_expe_origen=$row[expe_origen];	
	}
}

?>
<script languaje="JavaScript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function myJSFunction(numberArg){
	document.getElementById('Número y Siglas').value=numberArg; 
	if(numberArg=='00001'){ 
		document.getElementById('Número y Siglas').readOnly=false; // Hago el objeto de lectura y escritura
		document.getElementById('Número y Siglas').focus(); 
	}else{
		document.getElementById('Número y Siglas').readOnly=true; // Hago el objeto de Solo lectura
		document.getElementById('Folios').focus(); 
	}
}

</script>
<script language="JavaScript" src="../mislibs/jquery-1.4.2.min.js"></script>
<?php $xajax->printJavascript("../xajax/") ?>

<?
    // Para que se active el grabar extendido solo cuando se registra un nuevo expediente y no cuando se modifica.
$grabar_ext=($_tipoedicion==1)?1:0;
?>

<form name="<? echo $_nameform ?>" id="<? echo $_nameform ?>" method="post" action="<? echo $_url?>?_tipoedicion=<? echo $_tipoedicion?>&_op=<? echo $_op?>&_type=<? if ($_tipoedicion==1 or $_tipoedicion==2 or $_tipoedicion==5) echo 'G&_nametype='.$pathlib.'php_grabar.php&_extend_grabar='.$grabar_ext; else echo 'M'; ?>&_tabactivo=<? echo $_tabactivo ?>&pagina=<? echo $_pagina ?>&orden=<? echo $_orden ?>&_where=<? echo $_where ?>&_npop=<? echo $_npop ?>&_flag=<? echo $_flag ?>" onSubmit="return <? echo iif($_tipoedicion,'<=',2,'ObligaCamposyDisabled(this)','true') ?> " enctype='multipart/form-data'>
    <table width="100%"  height="81%"  border="0" cellpadding="0" cellspacing="10"  class="backform" >
    <tr><td valign="top" >
    <table class="frmline" width="720" align="center"  border="0" cellpadding="0" cellspacing="0">

	<tr>
		<td colspan="7" >
			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%" colspan="3">
				<? topform($_btncaption,$_tipoedicion,$_titulo) ?>
				</td>
			</tr>
			<? if($v_esdecaseta && $_tipoedicion==1){?>
				<tr>
					<td width="100%" colspan="3">
						<input type="text" name="txtregcopia">
						<input name="btncopiar" type="button" value="Copiar Registro" onClick="MM_goToURL('parent','/<? echo _CARPETAAPP_; ?>/app/main.php?_op=31&_type=M&_tabactivo=2&_regcopia='+txtregcopia.value);return document.MM_returnValue">
					</td>
				</tr>
			<? } ?>
			</table>

			<? if($_tipoedicion==1){?>

			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
                            <tr>
				<td width="100%" colspan="6">
                                    <?php

                                    seccion("EXPEDIENTE",2,$_tipoedicion);
                                    seccion('',$_tipoedicion,3);

                                    labelcajanum(2,$_tipoedicion, "Expediente", "zn_exma_id",$row,0,8,0);

                                    if($_tipoedicion==1) {
                                        echo "<tr>";
                                        echo "<td colspan=\"2\" class=\"marco seccionblank\" /> </td>";
                                        echo "<td colspan=\"5\" class=\"marco seccionblank\" >&nbsp;<input type=\"button\" value=\"Buscar\" id=\"btnBuscaExpdte\" /></div></td>";
                                        echo "</tr>";
                                        echo "<tr>";
                                        echo "<td colspan=\"5\" class=\"marco seccionblank\" >
                                            <input type='hidden' id='nx_expe_exmaadjunta' name='nx_expe_exmaadjunta' value='0'>
                                            <div id='divExpdte'></div>
                                        </td>";
                                        echo "</tr>";

                                    }

                                    ?>

				</td>
                            </tr>

                            <?php if(!$_POST[op_expe_origen]): ?>
                             <tr id="trBtnContinuar" >
                                 <td align="right" width="100%" colspan="6">
                                     <br>
                                     <input type="button" value=".:: Continuar ::." id="btn_Continuar" class="boton"> &emsp;
                                     <br>
                                     <br>
                                 </td>
                             </tr>
                            <?php endif ?>

			</table>
			<? } ?>

                        <div id="divRegExpdte">
                                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="100%" colspan="3">
                                            <?
                                            include('camposregistro.php');
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                            <td colspan="7" height="30">
                                            <? 	bottform($_btncaption,$_tipoedicion) ;	 ?>
                                            </td>
                                    </tr>
                                </table>
                        </div>
                </td>
        </tr>
    </table>
    </td></tr>
    </table>




</form>
<script>
<? if($_tipoedicion==1){?>

$(function(){

//    $("#Expediente").bind("blur", function(e) {
    $("#btnBuscaExpdte").bind("click", function(e) {
        xajax_buscaexpdte(xajax.getFormValues('formregistro'))
    });

    <?php if(!$_POST[op_expe_origen]):  // Si no se ha hecho un submit al formulario ?>
        $("#divRegExpdte").hide();
    <?php else: ?>
        <?php if($_POST[zn_exma_id]): ?>
            xajax_buscaexpdte(xajax.getFormValues('formregistro'))
        <?php endif ?>
    <?php endif ?>


    $("#btn_Continuar").bind("click", function(e) {
        $("#divRegExpdte").show();
        $("#trBtnContinuar").hide();
    });

});
<? } ?>

</script>
<? 
$db->sql_close();
?>