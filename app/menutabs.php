<? 
/* AJAX */
//if($_op==31){ // Expedientes en Proceso
	require_once("../xajax/xajax.inc.php");
	$xajax = new xajax();
	$xajax->registerFunction("callScript");
	$xajax->registerFunction("buscaexpdte");
	$xajax->processRequests();
	
	function callScript($formData)
	{
		$response = new xajaxResponse();
		/* Obtengo el correlativo correspondiente */
		$periodo=substr($formData[Dr_expe_fecha_doc],6);
		$result=pg_query("select my_correl(0,$formData[nx_expe_tipo],$formData[tr_texp_id],$_SESSION[depe_id],$_SESSION[id],$periodo,0)");
		$arr = pg_fetch_array ($result, 0);
		$num_exp=str_pad($arr[0],5,'0',STR_PAD_LEFT);
	
		/* Llamo a la función javascri*/	
		$response->addScriptCall("myJSFunction",$num_exp);
		return $response;
	}

	function buscaexpdte($formData)
	{
		$objResponse = new xajaxResponse();

                $exma_id = $formData['zn_exma_id'];
                $depe_id = $formData['___depe_id'];

                $existe_documento = false;
                $adjuntar_documento = "";

                /* Consulto si el último doc. del expdte está en mi oficina */
                $sql = "
                        SELECT  CASE 
                                    WHEN b.expe_origen = 1 THEN d.depe_nombre 
                                    WHEN b.expe_origen = 2 THEN d.depe_nombre || '/' || b.expe_depe_detalle 
                                END AS unidad,
                                a.expe_id,
                                c.texp_descripcion || ' ' ||
                                lpad(b.expe_numero_doc::TEXT,6,'0') || ' ' || b.expe_siglas_doc AS documento,
                                b.expe_fecha_doc AS fecha_doc,
                                b.expe_asunto AS asunto,
                                b.expe_firma AS firma,
                                case when a.oper_idtope=1 then 'EN PROCESO'
                                  when a.oper_idtope=2 then 'DERIVADO'
                                  when a.oper_idtope=3 then 'ARCHIVADO '
                                  when a.oper_idtope=4 then 'ADJUNTADO AL ' || lpad(a.oper_expeid_adj::TEXT,8,'0')
                                end as estado
                        FROM operacion a
                        LEFT JOIN expediente b ON b.expe_id = a.expe_id
                        LEFT JOIN tipo_expediente c ON b.texp_id = c.texp_id
                        LEFT JOIN dependencia d ON d.depe_id = b.depe_id
                        WHERE b.exma_id = $exma_id
                            AND a.oper_idtope = 1
                            AND a.depe_id = $depe_id
                            AND a.oper_procesado = FALSE
                        ORDER BY oper_id DESC
                        LIMIT 1;
                    ";

                /* Busco si existe un documento EN PROCESO del expediente en la Unidad Orgánica */
		$result = pg_query($sql);
                
                if(pg_num_rows($result) > 0){

                    $existe_documento = true;

                    global $muestra_adjuntar;
                    
                    if($muestra_adjuntar){
                        $adjuntar_documento = "<input type='checkbox' id='nx_expe_exmaadjunta' name='nx_expe_exmaadjunta' value='1' >
                                            <span class='etiqueta objeto'>Adjuntar al nuevo registro</span>";
                    } else {
                        $adjuntar_documento = "";
                    }
                    
                } else {
                
                    /* Busco el último documento del expdte, sin importar el estado y en cualquier Unidad Orgánica de todo el Sistema */

                    $sql = "
                            SELECT  CASE 
                                    WHEN b.expe_origen = 1 THEN d.depe_nombre 
                                    WHEN b.expe_origen = 2 THEN d.depe_nombre || '/' || b.expe_depe_detalle 
                                    END AS unidad,
                                    a.expe_id,
                                    c.texp_descripcion || ' ' ||
                                    lpad(b.expe_numero_doc::TEXT,6,'0') || ' ' || b.expe_siglas_doc AS documento,
                                    b.expe_fecha_doc AS fecha_doc,
                                    b.expe_asunto AS asunto,
                                    b.expe_firma AS firma,
                                    case when a.oper_idtope=1 then 'EN PROCESO'
                                      when a.oper_idtope=2 then 'DERIVADO'
                                      when a.oper_idtope=3 then 'ARCHIVADO '
                                      when a.oper_idtope=4 then 'ADJUNTADO AL ' || lpad(a.oper_expeid_adj::TEXT,8,'0')
                                    end as estado
                            FROM operacion a
                            LEFT JOIN expediente b ON b.expe_id = a.expe_id
                            LEFT JOIN tipo_expediente c ON b.texp_id = c.texp_id
                            LEFT JOIN dependencia d ON d.depe_id = b.depe_id
                            WHERE b.exma_id = $exma_id
                            ORDER BY oper_id DESC
                            LIMIT 1;
                        ";

                    $result = pg_query($sql);

                    if(pg_num_rows($result) > 0){
                        $existe_documento = true;
                    }
                    
                }

                if($existe_documento){
                    $arr = pg_fetch_array ($result, 0);

                    $unidad = $arr['unidad'];
                    $expe_id = $arr['expe_id'];
                    $documento = $arr['documento'];
                    $fecha_doc = $arr['fecha_doc'];
                    $asunto = $arr['asunto'];
                    $firma = $arr['firma'];
                    $estado = $arr['estado'];

                    $contenido_respuesta = "<p> Ultimo documento del expediente.</p>
                    <dl>
                        <dt>Unidad Orgánica:</dt>
                        <dd>$unidad</dd>
                        <dt>Documento:</dt>
                        <dd>$documento [$expe_id]$adjuntar_documento</dd>
                        <dt>Fecha:</dt>
                        <dd>$fecha_doc</dd>
                        <dt>Asunto:</dt>
                        <dd>$asunto</dd>
                        <dt>Firma:</dt>
                        <dd>$firma</dd>
                        <dt>Estado:</dt>
                        <dd>$estado</dd>
                    </dl>
                    ";

                } else {
                    $contenido_respuesta =  "<p class='error' > ¡¡¡ No existe expediente !!! </p>";
                }

                /* Cambio de nombre a nx_expe_exmaadjunta para que no existan dos objetos con el mismo nombre */

                /* Asigno los datos al div*/
		$objResponse->addAssign('divExpdte','innerHTML', utf8_encode($contenido_respuesta));

		return $objResponse;
	}

//}

/* FIN AJAX */

/* Estas líneas aseguran que nada quede en el cache del navegador, y se vuelvan a cargar todas las librerias externas
   Por lo tanto al hacer una modificación en una librería externa javascript no es necesario presionar F5 para el caso del Firefox
 */
//Header('Cache-Control: no-cache');
//Header('Pragma: no-cache');
/**/

// Chequeo la session
include('checksession.php');

ini_mod(_VERSION_,_SISTEMA_);

$_tabselected=substr($_op,0,1);
$_tabselected=($_tabselected>0 && $_tabselected<6)?$_tabselected:1;
// ARRAY CON EL NOMBRE DE LAS CLASES PARA EL CSS EN BODY
$classbody[1]="start";
$classbody[2]="developers";
$classbody[3]="solutions";
$classbody[4]="oshop";
$classbody[5]="os2hop";
// ARRAY CON EL NOMBRE LOS MODULOS PARA EL MENU PRINCIPAL 

?>
<? 
	if(!$_op and $_SESSION[bloq_mensaje]){ 
	$func_winlike=' onLoad=WinLIKE.init() onResize=WinLIKE.resizewindows()';
?>
<!-- WinLIKE (c) 1998-2005 by CEITON technologies GmbH - www.winlike.net -->
<!-- Change this source for older browsers! --><SCRIPT>WinLIKEerrorpage='winlike/winman/hlp-error.html';</SCRIPT>
<SCRIPT SRC="winlike/winman/wininit.js"></SCRIPT><SCRIPT SRC="winlike/winman/winman.js"></SCRIPT>
	<SCRIPT>
	WinLIKE.definewindows=mydefs;
	function mydefs()
	{
		var j=new WinLIKE.window('Mensaje',120,150,550,180,5);
		j.Fro=true;
		j.Ski='light';
		j.HD=false;
		j.SD=false;		
		j.LD=false;				
		j.Min=false;		
		j.Nam='winmensaje';
		j.Adr='mensaje.php';
		WinLIKE.addwindow(j);
	}
	</SCRIPT>
<?
}
?>

<BODY class="<? echo $classbody[$_tabselected] ?>" <? echo $func_winlike; ?> >

<? if(!$_op and $_SESSION[bloq_mensaje]){ ?>
<!-- Don't remove this line!--><IMG ID=ih_ SRC="skins/trans.gif" style="z-Index:4000;position:absolute;left:0;top:0;width:100%;height:100%">
<!-- You can change this loading picture! --><IMG ID=ig_ SRC="winlike/winman/load.gif" STYLE="position:absolute;left:35%;top:40%;z-Index:4001">
<? } ?>
	<div id="header"></div>
	<div id="logo">
	<?
	$convenio = @file('../imagenes/logoconvenio.gif');
	if($convenio)
		$milogo = 'logoconvenio.gif';
	else
		$milogo = 'logo.gif';	 
	?>
	<img src="../imagenes/<?echo $milogo ?>" width="100" height="65">
	</div>
	<div id="tag">
	<? echo _EMPRESA_?>
	<br><? echo $_SESSION["entidad"] ?> 
	<br><? echo $_SESSION["depe_nombre"] ?>
	</div>
	<div id="tag3"><p> <? 
        $carpetaApp = _CARPETAAPP_;
	if (($_SESSION[nickusu] == '') or ($_SESSION[nomusu] == '') or ($_SESSION[apeusu] == '') )  {
		echo "<a href=\"$_url?_op=1I&_type=L&_nameop=Login de Acceso\" target=\"_parent\" >Ingresar al Sistema <img src='/$carpetaApp/imagenes/login.gif' border='0' height='20'></a>\n";
	} else { 
		echo "<a href=\"logout.php?_url=$_url\" target=\"_parent\" >Salir del Sistema <img src='/$carpetaApp/imagenes/logout.gif' border='0' height='20'></a>\n";
	}	?>
	</p></div>	
	<div id="head">
	<div id="search">
	  <?
	  if($_SESSION["id"]){?>
	   <form name="frmmainsearch" action="main.php?_op=1C&_type=L&_nameop=Trámite del Expediente" method="post">
               <table border="0" cellspacing="2" cellpadding="0">
                   <tr>
                       <td width="60%">
                           N&uacute;mero<input class="frm" type="text" name="txtexpeid" size="12" maxlength="255" />
                       </td>
                       <td width="20%">
                           <input class="submit2" name="ver_tramite" type="submit" value="Reg.Documento" />
                       </td>
                       <td width="20%">
                           <input class="submit2" name="ver_tramite" type="submit" value="Reg.Expediente" />
                       </td>
                   </tr>
               </table>
		<input type="hidden"   name="_tabselected" value="" />
	   </form>
	   <? } ?>
	</div>
		
	<? if($convenio){ ?>
		<div id="divlogoRight">
			<img border = 0 src="../imagenes/logo.gif" width="70" height="35" >
		</div>		
	<? } ?>
	</div>
	<div id="nav">
	<div id="tabs">
	<ul>
<? 
//MENU PRINCIPAL
$i=1;
$namemodulo='';
foreach($modulo as $key => $valor) {  
//	if(($_SESSION['id'] == 1 && $i==5) or $i<5){ // Si es el Administrador muestra el módulo Administración
	  echo "<li class=\"".substr($classbody[$i],0,3)."\">\n";
	  echo "<a href=\"$_url?_op=$i\">$key</a>\n";
	  echo "</li>\n";
//	  }
	  if($i==$_tabselected)	$namemodulo=$key;
	  $i++;
 }

?>
	</ul>
	</div>

	<div style="clear: both"></div>
	 <div id="menu">
  <ul>
<?
//SUB MENU 
	$_submenu=explode(";",$modulo[$namemodulo]);
	$_opsubmenu=explode(";",$opmodulo[$namemodulo]);	
	 echo  "<li>&nbsp;</li>\n";	 	 
	 for($i=0;$i<count($_submenu);$i++) {
		 echo  "<li><a href=\"$_url?_op=$_opsubmenu[$i]&_type=M\">".iif($_opsubmenu[$i],'==',$_op,'<strong>','').$_submenu[$i].iif($_opsubmenu[$i],'==',$_op,'</strong>','')."</a></li>\n";	 
		 }
?>

  </ul>

</div>
</div>

	<table width="100%" height="93">
	<tr><td>&nbsp;</td></tr>
	</table>			
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="33%" align="left" valign="top">
			<span class="acctitle">&nbsp;<? echo _VERSION_."&nbsp;&nbsp;"._SISTEMA_ ?> </span>
			</td>			
			<td width="33%" align="center" valign="top">
			<span class="acctitle">
				<img src="<? echo $pathlib."imagenes/icon_home.gif" ?>" border="0" height="12">
				<a href="<? echo $_url ?>">WEB-Inicio</a>
			</span>				
			</td>			
			
			<td width="33%" align="right">			
			<span class="acctitle">
			<? if (($_SESSION[nickusu] == '') or ($_SESSION[nomusu] == '') or ($_SESSION[apeusu] == '') )
					echo "An&oacute;nimo";
				else
					echo ucwords(strtolower($_SESSION["nomusu"]))." ".ucwords(strtolower($_SESSION["apeusu"])." [ ".$_SESSION["nickusu"]."]") ?>
			</span>
			</td>						
		</tr>
	</table>
<?
include('modulo.php') ; 

if($_tabactivo){
	$html = "<"."script".">\n";
	if($_setfocus) 
		$html .= "setfocus(".$_nameform.",'".$_setfocus."')\n";
	else 
		$html .= "setfocus(".$_nameform.")\n";	
	
	// Para que el scroll lo coloque al final cuando estoy derivando un expediente al registrarlo
	if($_setfocus=='trXXT01operacionZZoper_depeid_d') 
		$html .= "self.scroll(1,1000)";
		
	$html .= "</"."script".">\n";
	echo $html;
}

?>
</body>