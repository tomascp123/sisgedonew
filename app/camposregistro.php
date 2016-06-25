<?
$_porcenleft="22%";  // Porcentaje de ancho de la parte izquierda de los formularios
$_porcenright="78%"; // Porcentaje de ancho de la parte derecha de los formularios

switch($_op) {
    case '1C': // Consulta de tràmite de expedientes
        require_once('../mislibs/misclases.php') ;
        if($_POST[txtexpeid]){
            $registro_id = $_POST[txtexpeid];
        }else{
            $registro_id = $_GET[txtexpeid];
        }

        $MyDocum = new clsMyDocumento($registro_id);
        $MyDocum->consulta_tramite();

        if($MyDocum->_TotFilTramite) { // Si tiene trámite
            $MyDocum->consulta_expdte();
            seccion("DATOS DEL DOCUMENTO",3,3);
            $MyDocum->muestra_expdte();
            $MyDocum->muestra_tramite();

            // Buscar si tiene expedientes relacionados y/o adjuntados
            // Mostrar trámite de expedientes relacionados
            $MyDocum->consulta_expdte_rel();
            if($MyDocum->_TotFilExpRel) { // Si tiene trámite
                seccion('',3,3);
                seccion2("DOCUMENTOS RELACIONADOS",3,3,"center","titrelacionados");
                seccion('',3,3);

                for ($x = 0; $x < $MyDocum->_TotFilExpRel; $x++) {
                    $row = $db->sql_fetchrow($MyDocum->_rsExpdteRel);
                    $MyDocum->_idExpdte=$row[0]; // Asigno el id del expediente
                    $MyDocum->consulta_expdte();
                    seccion2("DOCUMENTO [ ".str_pad($MyDocum->_idExpdte,8,'0',STR_PAD_LEFT)." ]",3,3,"left","titrela");
                    $MyDocum->muestra_expdte();
                    $MyDocum->consulta_tramite();
                    $MyDocum->muestra_tramite();
                    seccion('',3,3);
                }
            }

        }else {
            ?>
<tr>
    <td  align="center" colspan='5' class='marco seccionblank' >
        <p>&nbsp; </p>
                    <? echo "No existe el documento solicitado... Por favor intente nuevamente !!!";?>
        <p>&nbsp; </p>
    </td>
</tr>
        <?
        }

        break;
    case '1P': // Cambio de clave
    // Campos
        labelcajanum(3,3, "Código de Usuario", "zsxid_usu",$row,0,5,0);
        seccion("DATOS PERSONALES",1,$_tipoedicion);
        labelcajatxt(3,3, "Nombres","Sr_usua_nombres",$row,60);
        labelcajatxt(3,3, "Apellidos","Sr_usua_apellidos",$row,60);
        labelcajatxt(3,3, "Cargo","Sr_usua_cargo",$row,80);
        labelcajatxt(3,3, "Iniciales","Sr_usua_iniciales",$row,40);
        seccion("DATOS DE LA CUENTA",1,$_tipoedicion);
        labelcajatxt(3,3, "Usuario","Sr_usua_login",$row,20);
        labelcajapass(2,$_tipoedicion, "Password","sr_usua_password",$row,20);
        labelcajapass(2,$_tipoedicion, "Retipee Password","srxreusua_password",$row,20);

        break;

    case 10: // Buscar expedientes
    // *********************************** //
    // ***** Consultas para combos ******* //
    // *********************************** //
    // Usuarios destino
        if($_tipoedicion==5) {
        // Tipos de expedientes
            $query="select texp_id,texp_descripcion from tipo_expediente order by texp_descripcion";
            $rstipexpe=$db->sql_query($query);
            if(!$rstipexpe) {die($db->sql_error().' Error en Consulta de tipos de expedientes '); }
        }

        seccion("REGISTRO",5,$_tipoedicion);
        labelcajadate(5,$_tipoedicion, "Fecha Desde","Dd_expe_fecha",$row,"formregistro");
        labelcajadate(5,$_tipoedicion, "Fecha Hasta","Dh_expe_fecha",$row,"formregistro");

        seccion('',$_tipoedicion,5);
        seccion("ORIGEN",5,$_tipoedicion);

        if($_SESSION["id"]) {	// Si he ingresado al sistema permite buscar expedientes internos y externos
            if($_tipoedicion==5 && !$_POST[op_expe_origen]) $op_expe_origen=1;
            labeloption(5,$_tipoedicion, "Orígen","Interno",1,"op_expe_origen=1",$row,1,'[','*');
            labeloption(5,$_tipoedicion, "Orígen","Externo",2,"op_expe_origen",$row,1,'*',']');

            if($op_expe_origen==1) { // Si es de Orìgen interno
                labelpopup(5,$_tipoedicion,"Unidad Org.","sr_depe_id=",$row,'formregistro',6,'P59',0,0,'P','','','','----------------- Todas -----------------');
            }else {
                labelpopup(5,$_tipoedicion,"Unidad Org.","sr_depe_id",$row,"formregistro",6,"P51",0,0,'P','','','','----------------- Todas -----------------');

                if ($SG_Tupa) { // si está configurado para ingresar docs según tupa
                    labelcajatxt(5,$_tipoedicion, "Número de Doc.","Sx_expe_numtdoc",$row,15);
                } else {
                    labelcajatxt(5,$_tipoedicion, "Detalle","Sx_expe_depe_detalle",$row,80);
                }

            }
        }else {
            labelpopup(5,$_tipoedicion,"Dependencia","sr_depe_id",$row,"formregistro",6,"P51",0,0,'P','','','','----------------- Todas -----------------');
            if ($SG_Tupa) { // si está configurado para ingresar docs según tupa
                labelcajatxt(5,$_tipoedicion, "Número de Doc.","Sx_expe_numtdoc",$row,15);
            } else {
                labelcajatxt(5,$_tipoedicion, "Detalle","Sx_expe_depe_detalle",$row,80);
            }
        }

        labelcajatxt(5,$_tipoedicion, "Firma","Sr_expe_firma",$row,80);

        seccion('',$_tipoedicion,5);
        seccion("DATOS",5,$_tipoedicion);
        labelcombo(5,$_tipoedicion,'Tipo de Documento','tr_texp_id',$row,$rstipexpe,0,0,'','','','------- Todos -------');

        labelcajanum(5,$_tipoedicion, "N&uacute;mero", "zr_expe_numero_doc",$row,0,5,0);
        labelcajatxt(5,$_tipoedicion, "Siglas","Ss_expe_siglas_doc",$row,65);
        labelareatxt(5,$_tipoedicion, "Asunto","Sx_expe_asunto",$row,4,80);

        break;

    case 31: // Expedientes en Proceso
    // *********************************** //
    // ***** Consultas para combos ******* //
    // *********************************** //
    // Usuarios destino
        if($_tipoedicion==5) { // Si es búsqueda
            $query   = "select id_usu, usua_login from usuario where depe_id=".$_SESSION[depe_id]." and usua_estado='1' order by usua_nombres";
            $rsusua=$db->sql_query($query);
            if(!$rsusua) {die($db->sql_error().' ERROR EN CONSULTA DE USUARIOS '); }
        }else {
            // Tipos de prioridades
            $query="select tpri_id,tpri_descripcion from tipo_prioridad order by tpri_descripcion";
            $rspriori=$db->sql_query($query);
            if(!$rspriori) {die($db->sql_error().' ERROR EN CONSULTA DE TIPOS DE PRIORIDADES '); }
            // Tipos de expedientes
            $query="select texp_id,texp_descripcion from tipo_expediente order by texp_descripcion";
            $rstipexpe=$db->sql_query($query);
            if(!$rstipexpe) {die($db->sql_error().' ERROR EN CONSULTA DE TIPOS DE EXPEDIENTES '); }
            // Formas de recepción
            $query="select frec_id,frec_descripcion from forma_recepcion order by frec_descripcion";
            $rsforrec=$db->sql_query($query);
            if(!$rsforrec) {die($db->sql_error().' ERROR EN CONSULTA DE FORMAS DE RECEPCION '); }

            if ($SG_Tupa) { // si está configurado para ingresar docs según tupa
                // Tipos de docs para tupa
                $query="select tabl_codigo,tabl_descripcion from tabla where tabl_tipo='IDTDOC' order by tabl_codigo";
                $rsidtdoc=$db->sql_query($query);
                if(!$rsidtdoc) {die($db->sql_error().' Error en Consulta Tipos de Docs Tupa'); }
            }

        }

        // *********************************** //
        // ***** Campos Ocultos ************** //
        // *********************************** //
        ?>
<input type="hidden"  name="___id_usu"  value="<? echo $_SESSION["id"] ?>">
<input type="hidden"  name="___idusu_depe"  value="<? echo $_SESSION[depe_id] ?>">			
        <?

        // *********************************** //
        // ***** Campos Generales ************ //
        // *********************************** //

        // Obtengo el tipo de dependencia a la cual pertenece el usuario activo, para controlar si se activa o no el campo número de expediente y orígen y tipo
        $_depe_tipo=$_SESSION["depe_tipo"];
        if($_tipoedicion==2){ // Si se está modificando un registro  
            labelcajanum(3,$_tipoedicion, "Expediente ", "zr_exma_id",$row,0,8,0);        
        }
        
        labelcajanum(3,$_tipoedicion, "Registro ", "zsxexpe_id",$row,0,8,0);
        labelcombo(5,$_tipoedicion,'Documentos del Usuario','tr_id_usu='.$_SESSION["id"],$row,$rsusua,0,100,'','','','------- Todos -------');
        labelcheck(5,$_tipoedicion, "","Ver Adjuntados","nx_ver_adjuntados",$row);

        if($_tipoedicion!=5) { // Si es búsqueda
            seccion("DATOS DEL REGISTRO",2,$_tipoedicion);
            if($_tipoedicion!=5)
                labelcajadate(2,3, "Fecha de Registro","Dr_expe_fecha=".date("d/m/Y"),$row,"formregistro");

            labelcombo(2,$_tipoedicion,'Prioridad','tr_tpri_id=1',$row,$rspriori,0);
            seccion('',$_tipoedicion,3);

            seccion("ORIGEN",2,$_tipoedicion);
            if($_tipoedicion==1 && !$_POST[op_expe_origen]) {
                if($v_esdecaseta) { // Si es usuario de la caseta de Tràmite
                    $op_expe_origen=2; // ingresa expediente externo
                }else {
                    $op_expe_origen=1; // ingresa expedte. interno
                }
            }

            if($_tipoedicion==2) {
                labeloption(2,3, "Orígen","Interno",1,"op_expe_origen=1",$row,1,'[','*');
                labeloption(2,3, "Orígen","Externo",2,"op_expe_origen",$row,1,'*',']');
            }else {
                if($v_esdecaseta) { // Si es usuario de caseta de trámite
                    labeloption(2,$_tipoedicion, "Orígen","Interno",1,"op_expe_origen",$row,1,'[','*');
                    labeloption(2,$_tipoedicion, "Orígen","Externo",2,"op_expe_origen=2",$row,1,'*',']');
                }else {
                    labeloption(2,$_tipoedicion, "Orígen","Interno",1,"op_expe_origen=1",$row,1,'[','*');
                    labeloption(2,$_tipoedicion, "Orígen","Externo",2,"op_expe_origen",$row,1,'*',']');
                }
            }

            if($op_expe_origen==1) { // Si es de Orìgen interno
            // Obtengo las siglas de la dependencia
                if($_tipoedicion==1 or $_tipoedicion==2) { // Solo si es nuevo registro o estoy editando el registro
					/*
					$_siglas=rtrim(saca_valor("select * from depint_v where depe_id=$_SESSION[depe_id]",'depe_siglasexp'));
					$_proyectado=rtrim(saca_valor("select * from depint_v where depe_id=$_SESSION[depe_id]",'depe_proyectado'));
					*/
                    $_siglas=$_SESSION["depe_siglasexp"];
                    $_proyectado=$_SESSION["depe_proyectado"];
                }

                if($_depe_tipo==0) { // Si es un sector, Ya no solicito "Tipo del expdte.", ya que siempre debe ser expdte. de la dependencia.
                    ?><input type="hidden"  name="nx_expe_tipo"  value="0"><?
                }else {
                    if($_tipoedicion==2)	// Si estoy editando el registro
                        labelcheck(2,3, "Tipo","Documento Personal","nx_expe_tipo",$row,1); // No permito la modificación
                    else
                        labelcheck(2,$_tipoedicion, "Tipo","Documento Personal","nx_expe_tipo",$row,1);
                }

                //			labelpopup(2,3,"Dependencia","ss_depe_id=".$_SESSION["depe_id"],$row,$_nameform,6,'P52'); // Màs abajo tengo el mismo labelpoup, por eso no puedo usarlo aquí.
                //			labelcombo(2,3,'Dependencia','ss_depe_id='.$_SESSION["depe_id"],$row,$rsdepe,0);
                labelcajatxt(1,3, "Unidad Org.","xxxdepe=".$_SESSION["depe_nombre"],$row,60);

                ?><input type="hidden"  name="___depe_id"  value="<? echo $_SESSION["depe_id"] ?>">
                <input type="hidden"  name="___idtdoc"  value="">
                <input type="hidden"  name="___expe_numtdoc"  value="">
                <input type="hidden"  name="___expe_depe_detalle"  value=""><?

                if($_POST[nx_expe_tipo] or $row[expe_tipo]) { // Expediente personal
                    $_POST[Ss_expe_firma]=$_SESSION["nomusu"].' '.$_SESSION["apeusu"];
                    $_POST[Ss_expe_cargo]=$_SESSION["cargo"];
                    $_POST[Ss_expe_siglas_doc]=$_siglas.'-'.$_SESSION["usuinicial"];
                }else { // Expediente de la dependencia
					/*
					$_representante=saca_valor("select * from depint_v where depe_id=$_SESSION[depe_id]",'depe_representante');	
					$_cargo=saca_valor("select * from depint_v where depe_id=$_SESSION[depe_id]",'depe_cargo');					
					*/
                    $_representante=$_SESSION["depe_representante"];
                    $_cargo=$_SESSION["depe_cargo"];
                    $_POST[Ss_expe_firma]=$_representante;
                    $_POST[Ss_expe_cargo]=$_cargo;
                    $_POST[Ss_expe_siglas_doc]=$_siglas;
                }
                labelcajatxt(1,3, "Firma","Ss_expe_firma=".$_representante,$row,60);
                labelcajatxt(1,3, "Cargo","Ss_expe_cargo=".$_cargo,$row,60);
                ?><input type="hidden"  name="___tupa_id"  value=0><?
            }

            if($op_expe_origen==2) { // Si es de orìgen externo
                ?><input type="hidden"  name="nx_expe_tipo"  value="0"><?
                labelpopup(1,$_tipoedicion,"Entidad","sr_depe_id",$row,"formregistro",6,"P51");

                if ($SG_Tupa) { // si está configurado para ingresar docs según tupa
                    labelcombo(1,$_tipoedicion,'Documento','tr_idtdoc=1',$row,$rsidtdoc,0) ;
                    labelcajatxt(1,$_tipoedicion, "Número","Sr_expe_numtdoc",$row,15);
                    ?><input type="hidden"  name="___expe_depe_detalle"  value=""><?
                    labelcajatxt(1,$_tipoedicion, "Nombre o Raz.Social","Sr_expe_firma",$row,60);
                    ?><input type="hidden"  name="___expe_cargo"  value=""><?
                    labelpopup(1,$_tipoedicion,"Nº Tupa","sr_tupa_id",$row,"formregistro",6,"P45");

                } else {
                    ?><input type="hidden"  name="___idtdoc"  value=""><?
                    ?><input type="hidden"  name="___expe_numtdoc"  value=""><?
                    labelcajatxt(1,$_tipoedicion, "Detalle","Sx_expe_depe_detalle",$row,60);
                    labelcajatxt(1,$_tipoedicion, "Firma","Sr_expe_firma",$row,60);
                    labelcajatxt(1,$_tipoedicion, "Cargo","Sr_expe_cargo",$row,60);
                    ?><input type="hidden"  name="___tupa_id"  value=0><?
                }
            }

            seccion('',$_tipoedicion,3);
            seccion("DATOS DEL DOCUMENTO",2,$_tipoedicion);
            //	    labelcheck(2,$_tipoedicion, "Forma","Copia","nx_expe_forma",$row); Todo expediente ingresado será Original
            ?>
<input type="hidden"  name="___expe_forma"  value="0">
            <?

            labelcajadate(2,$_tipoedicion, "Fecha de Documento","Dr_expe_fecha_doc=".date("d/m/Y"),$row,"formregistro");
            if($op_expe_origen==1 && $_depe_tipo!=0) { // Si es de Orìgen interno y no es un sector
                if($_tipoedicion==2) { // si estoy editando
                    labelcombo(2,3,'Tipo de Documento','tr_texp_id',$row,$rstipexpe,1);
                }else {
                //					labelcombo(2,$_tipoedicion,'Tipo de Expediente','tr_texp_id',$row,$rstipexpe,1);
                    $myOpcion="onChange=xajax_callScript(xajax.getFormValues('formregistro'))";
                    labelcombo(2,$_tipoedicion,'Tipo de Documento','tr_texp_id',$row,$rstipexpe);
                    $myOpcion="";
                }

                if($_depe_tipo!=0) { // Sólo Si es una dependencia interna diferente a un Sector, entonces consulto el correlativo
                // Consulto el número correlativo que corresponde según el tipo de expediente si estoy ingresando un nuevo registro
                    if($_tipoedicion==1) {
                        if($_POST[tr_texp_id]) {
                            $periodo=substr($_POST[Dr_expe_fecha_doc],6);
                            $result=pg_query("select my_correl(0,$_POST[nx_expe_tipo],$_POST[tr_texp_id],$_SESSION[depe_id],$_SESSION[id],$periodo,0)");
                            $arr = pg_fetch_array ($result, 0);
                            $num_exp=str_pad($arr[0],5,'0',STR_PAD_LEFT);
                        }
                    }
                }
            }else { // Si es de orígen externo
                labelcombo(2,$_tipoedicion,'Tipo de Documento','tr_texp_id',$row,$rstipexpe,0);
            }

            if($op_expe_origen==1) { // Si es de Orìgen interno
                if($_tipoedicion==1 && $_depe_tipo!=0) { // Si es un nuevo registro y no es un Sector o Dirección Regional
                    $_POST[zr_expe_numero_doc]=$num_exp;
                    $_POST[zs_expe_numero_doc]=$num_exp;
                }

                if($_depe_tipo==0) { // Si es un sector, se permite editar el campo número del expediente
                    labelcajanum(2,$_tipoedicion, "N&uacute;mero y Siglas","zr_expe_numero_doc=".$num_exp,$row,0,6,0,'','[','*');
                }else {
                    if($_tipoedicion==2) { // Si estoy Editando el registro
                        labelcajanum(2,3, "N&uacute;mero y Siglas",iif($num_exp,">",1,"zs_expe_numero_doc=","zr_expe_numero_doc=").$num_exp,$row,0,6,0,'','[','*');
                    }else {
                        labelcajanum(2,iif($num_exp,">",1,3,$_tipoedicion), "N&uacute;mero y Siglas",iif($num_exp,">",1,"zs_expe_numero_doc=","zr_expe_numero_doc=").$num_exp,$row,0,6,0,'','[','*');
                    }
                }

                labelcajatxt(2,3, "N&uacute;mero y Siglas","Ss_expe_siglas_doc=".$_siglas,$row,65,'*',']');
                if($_proyectado) { // Si debe pedir el campo "proyectado por"
                    labelcajatxt(1,$_tipoedicion, "Proyectado por","St_expe_proyectado",$row,10);
                }else {
                    ?><input type="hidden"  name="St_expe_proyectado"  value=""><?
                }

            }else { // Si es de orígen externo
                if($_tipoedicion==1) // Si se está agregando un nuevo registro
                    $_POST[Ss_expe_siglas_doc]="";

                labelcajanum(2,$_tipoedicion, "N&uacute;mero y Siglas", "zn_expe_numero_doc",$row,0,6,0,'','[','*');
                labelcajatxt(2,$_tipoedicion, "N&uacute;mero y Siglas","Sx_expe_siglas_doc",$row,65,'*',']');
                ?><input type="hidden"  name="St_expe_proyectado"  value=""><?

            }

            labelcombo(2,$_tipoedicion,'Forma de recepci&oacute;n','tr_frec_id=1',$row,$rsforrec,0);
            labelarchivo(2,$_tipoedicion, "Archivo","Fx_ar_expearchivo",$row,60,$_nameform);
            labelcajatxt(2,$_tipoedicion, "Folios","zr_expe_folios",$row,05);
            labelareatxt(2,$_tipoedicion, "Asunto","Sr_expe_asunto",$row,4,80);
            
            // Al usar la nueva versión con expedientes ya no pedimos este campo
            //labelcajanum(2,$_tipoedicion, "Relacionado con Docum.", "zn_expe_relacionado",$row,0,8,0);
            ?><input type="hidden"  name="zn_expe_relacionado"  value=""><?
            
            

            //			if($op_expe_origen==2){ // Si es de orìgen externo
            seccion("CLASIFICACION TUPA",2,$_tipoedicion);
            labeloption(2,$_tipoedicion, "Clasificación","Silencio positivo",1,"op_expe_clastupa",$row,0,'[','*');
            labeloption(2,$_tipoedicion, "Clasificación","Silencio negativo",2,"op_expe_clastupa",$row,0,'*','*');
            labeloption(2,$_tipoedicion, "Clasificación","Automático",3,"op_expe_clastupa",$row,0,'*','*');
            labeloption(2,$_tipoedicion, "Clasificación","Ninguna",9,"op_expe_clastupa=9",$row,0,'*',']');
            labelcajanum(2,$_tipoedicion, "# de Días de atención", "zn_expe_diasatencion",$row,0,2,0);

            if($row[expe_emailorigen])
                labelcajatxt(3,3, "Email","Sx_expe_emailorigen",$row,60);

            seccion('',$_tipoedicion,3);

            // ************************************* //
            // Destinos o derivacion del expediente  //
            // ************************************* //
            if($_tipoedicion==1) {
            //  Consultas //
            // Usuarios destino
                $query   = "select id_usu,
								       usua_login 
								from usuario 
								where depe_id=$_SESSION[depe_id] and usua_estado='1' 
								order by usua_nombres";

                $rsusua=$db->sql_query($query);
                if(!$rsusua) {die($db->sql_error().' Error en consulta de usuarios'); }

                ////////////////

                seccion("DESTINO(S) - DERIVACION DEL DOCUMENTO",2,$_tipoedicion);
                $idTableDynamic="XXT01";
                $idArrayDynamic="XXA01";
                $TableDB="operacion";

                $HeaderTableDynamic=array("_oper_idtope",
                    "_oper_fecha",
                    "_id_usu",
                    "_depe_id",
                    "_oper_idprocesado",
                    "_archi_id",
                    "_oper_expeid_adj",
                    "Forma",
                    "Unidad Orgánica",
                    "Detalle",
                    "Usuario",
                    "Proveido de atención");

                //	Los elementos que empiezan con:
                //	'H' --> Ocultos, Elementos no se mostrarán, pero si se guardan sus datos
                //	't' --> Combos normales
                //	'a' --> Elementos que efectúan bùsquedas en arrys javacript para mostrar los datos que deseamos. Ejm. Combos dependientes, check.

                $FieldTableDynamic=array("Hx".$idTableDynamic.$TableDB."ZZ"."oper_idtope",
                    "Hx".$idTableDynamic.$TableDB."ZZ"."oper_fecha",
                    "Hx".$idTableDynamic.$TableDB."ZZ"."id_usu",
                    "Hx".$idTableDynamic.$TableDB."ZZ"."depe_id",
                    "Hx".$idTableDynamic.$TableDB."ZZ"."oper_idprocesado",
                    "Hx".$idTableDynamic.$TableDB."ZZ"."archi_id",
                    "Hx".$idTableDynamic.$TableDB."ZZ"."oper_expeid_adj",
                    "ak".$idTableDynamic.$TableDB."ZZ"."oper_forma",
                    "tr".$idTableDynamic.$TableDB."ZZ"."oper_depeid_d",
                    "St".$idTableDynamic.$TableDB."ZZ"."oper_detalle_destino",
                    "ar".$idTableDynamic.$TableDB."ZZ"."oper_usuaid_d",
                    "Sr".$idTableDynamic.$TableDB."ZZ"."oper_acciones");

                //	Campos ocultos
                labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[0],$FieldTableDynamic[0]."=2",$row,2);
                labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[1],$FieldTableDynamic[1]."=".date("d/m/Y"),$row,2);
                labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[2],$FieldTableDynamic[2]."=".$_SESSION["id"],$row,2);
                labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[3],$FieldTableDynamic[3]."=".$_SESSION["depe_id"],$row,2);
                labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[4],$FieldTableDynamic[4]."=XXXXX",$row,2); // Coloco este valor para que luego en gra_ext_nvoexpdte.php lo cambie por oper_id
                labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[5],$FieldTableDynamic[5]."=null",$row,2);
                labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[6],$FieldTableDynamic[6]."=null",$row,2);

                // Campos visibles
                labelcheck(2,1, "Forma","Copia",$FieldTableDynamic[7],$row); // $_tipoedicion le envio 1, por el problema del check que no pasa como POST.  Para cualquier control editable, $_tipoedicion puede ser 1 o 2
                // Array necesario para búsqueda del valor del check.
                echo "<"."script".">\n";
                echo $FieldTableDynamic[7]."= new Array()\n";
                echo $FieldTableDynamic[7]."[".$FieldTableDynamic[7].".length] = new Array('0','ORIGINAL')\n";
                echo $FieldTableDynamic[7]."[".$FieldTableDynamic[7].".length] = new Array('1','COPIA')\n";
                echo "<"."/script".">\n";

                $myOpcion="pideusuario(this.value,$_SESSION[depe_id])";
                labelpopup(2,$_tipoedicion,$HeaderTableDynamic[8],$FieldTableDynamic[8],$row,$_nameform,6,'P59',0,70);
                $myOpcion="";
                labelcajatxt(2,$_tipoedicion, $HeaderTableDynamic[9],$FieldTableDynamic[9],$row,80);
                labelcombo(2,$_tipoedicion,$HeaderTableDynamic[10],$FieldTableDynamic[10],$row,$rsusua,0,88);
                crea_array_js($rsusua,$FieldTableDynamic[10]); // Crea array porque el combo anterior es dependiente

                ?><input type="hidden" id="___usuario"  name="___usuario"  value="null"><? // Creo campo oculto para cuando no se pida usuario
                $html = "<"."script".">\n";
                $html .= "pideusuario(0,$_SESSION[depe_id])";
                $html .= "</"."script".">\n";
                echo $html;

                labelareatxt(2,$_tipoedicion, $HeaderTableDynamic[11],$FieldTableDynamic[11],$row,2,80);
                TableDynamicButton(2,$_tipoedicion,"formregistro",$idTableDynamic,$idArrayDynamic,$HeaderTableDynamic,$FieldTableDynamic,$rsderivados);
                seccion('',$_tipoedicion,3);



            }
        }
        break;

    case 32: // Expedientes Por recepcionar
    // *********************************** //
    // ***** Consultas para combos ******* //
    // *********************************** //
    // Usuarios destino
        if($_tipoedicion==5) {
        // Ususarios
            $query   = "select id_usu, usua_nombres from usuario where depe_id=".$_SESSION[depe_id]." and usua_estado='1' order by usua_nombres";
            $rsusua=$db->sql_query($query);
            if(!$rsusua) {die($db->sql_error().' ERROR EN CONSULTA DE USUARIOS '); }

			/* Entidades */
            $v_esdecaseta = $_SESSION["usua_caseta"]; // Si es usuario que atiende en la caseta de trámite
            if($v_esdecaseta) {
                $query="select depe_id,depe_nombre from depenti_v order by depe_nombre";
                $rsEntidad=$db->sql_query($query);
                if(!$rsEntidad) {die($db->sql_error().' Error en Consulta Entidades'); }
            }
        }

        labelcajanum(5,$_tipoedicion, "Registro ", "zsxexpe_id",$row,0,8,0);
        labelcombo(5,$_tipoedicion,'Documentos del Usuario','tr_oper_usuaid_d='.$_SESSION["id"],$row,$rsusua,0,60,'','','','------- Todos -------');

        if($v_esdecaseta)
            labelcombo(5,$_tipoedicion,'Entidad','tr_depe_depende',$row,$rsEntidad,0);

        break;

    case 33: // Expedientes Archivados / Procesados
    // *********************************** //
    // ***** Consultas para combos ******* //
    // *********************************** //
    // Usuarios destino
        if($_tipoedicion==5) {
            $query   = "select id_usu, usua_nombres from usuario where depe_id=".$_SESSION[depe_id]." and usua_estado='1' order by usua_nombres";
            $rsusua=$db->sql_query($query);
            if(!$rsusua) {die($db->sql_error().' Error en consulta de usuarios '); }

            $query   = "select archi_id,
							archi_periodo || ' / ' || archi_nombre   
							from archivador 
							where depe_id=$_SESSION[depe_id] and (archi_idusua is null or archi_idusua=$_SESSION[id]) 
							order by archi_periodo desc,archi_nombre";

            $rsarchi=$db->sql_query($query);
            if(!$rsarchi) {die($db->sql_error().' Error en consulta de Archivadores '); }
        }

        labelcombo(5,$_tipoedicion,'Documentos del Usuario','tr_id_usu='.$_SESSION["id"],$row,$rsusua,0,60,'','','','------- Todos -------');
        labelcombo(5,$_tipoedicion,"Archivador","tr_archi_id",$row,$rsarchi,0,88,'','','','------- Todos -------');
        labelcajanum(5,$_tipoedicion, "Registro ", "zsxexpe_id",$row,0,8,0);

        break;

    case 41: // Archivadores
    // *********************************** //
    // ***** Consultas para combos ******* //
    // *********************************** //
        if($_POST['ss_depe_id'])
            $query   = "select id_usu, usua_nombres from usuario where depe_id=".$_POST['ss_depe_id']." order by usua_nombres";
        else
            $query   = "select id_usu, usua_nombres from usuario order by usua_nombres";
        $rsusua=$db->sql_query($query);
        if(!$rsusua) {die($db->sql_error().' Error en consulta de archivadores '); }

        // *********************************** //
        // ***** Campos Generales ************ //
        // *********************************** //
        seccion("DATOS DEL ARCHIVADOR",1,$_tipoedicion);

        if($_tipoedicion==2) // Si es EDICION
            labelcajanum(3,$_tipoedicion, "Código ", "zsxarchi_id",$row,0,6,0);

        if($_tipoedicion==1)
            labelcheck(2,$_tipoedicion, "Ambito","Archivador Personal","archiper",$row,1);

        labelpopup(2,3,"Unidad Org.","ss_depe_id=".$_SESSION["depe_id"],$row,'formregistro',6,'P52');
        ?><input type="hidden"  name="___depe_id"  value="<? echo $_SESSION["depe_id"] ?>"><?
        ?><input type="hidden"  name="___id_usu"  value="<? echo $_SESSION["id"] ?>"><?


        if($_POST[archiper]) { // Si registro un archivador personal, muestro el usuario
            labelcombo(2,3,"Usuario","tr_id_usu=".$_SESSION["id"],$row,$rsusua,0,88);
            ?><input type="hidden"  name="___archi_idusua"  value="<? echo $_SESSION["id"] ?>"><?
        }

        labelcajatxt(1,$_tipoedicion, "Descripción","Sr_archi_nombre",$row,60);
        labelcajanum(1,$_tipoedicion, "Periodo", "nr_archi_periodo",$row,0,4,0);

        break;

    case 42: // Tipo de expedientes
        seccion("DATOS DEL TIPO DE DOCUMENTO",1,$_tipoedicion);

        if($_tipoedicion==2) // Si es EDICION
            labelcajanum(3,$_tipoedicion, "Código ", "zsxtexp_id",$row,0,3,0);

        labelcajatxt(1,$_tipoedicion, "Descripción","Sr_texp_descripcion",$row,40);
        labelcajatxt(2,$_tipoedicion, "Descripción abreviado","Sr_texp_abreviado",$row,20);
        break;

    case 43: // Formas de recepción
        seccion("DATOS DE LA FORMA DE RECEPCION",1,$_tipoedicion);
        if($_tipoedicion==2) // Si es EDICION
            labelcajanum(3,$_tipoedicion, "Código ", "zsxfreec_id",$row,0,3,0);

        labelcajatxt(1,$_tipoedicion, "Descripción","Sr_frec_descripcion",$row,20);
        labelcajatxt(2,$_tipoedicion, "Descripción abreviado","Sr_frec_abreviado",$row,10);
        break;

    case 44: // Tipos de prioridades
        seccion("DATOS DEL TIPO DE PRIORIDAD",1,$_tipoedicion);
        if($_tipoedicion==2) // Si es EDICION
            labelcajanum(3,$_tipoedicion, "Código ", "zsxtpri_id",$row,0,3,0);

        labelcajatxt(1,$_tipoedicion, "Descripción","Sr_tpri_descripcion",$row,20);
        labelcajatxt(2,$_tipoedicion, "Descripción abreviado","Sr_tpri_abreviado",$row,10);
        break;

    case 45: // Tupa
        seccion("DATOS DEL TUPA",1,$_tipoedicion);
        if($_tipoedicion==2) // Si es EDICION
            labelcajanum(3,$_tipoedicion, "Código ", "zsxtupa_id",$row,0,3,0);

        labelcajatxt(1,$_tipoedicion, "Descripción","Sr_tupa_descripcion",$row,100);
        labelcajatxt(2,$_tipoedicion, "Descripción abreviado","Sr_tupa_descricorta",$row,50);
        labelcajanum(1,$_tipoedicion, "Periodo", "nr_tupa_periodo",$row,0,4,0);
        break;

    case $_op==51 || $_op==52: //Entidades o Unidades Orgánicas

        if($_op==51){
            $descri = 'Entidad';
        } else {
            $descri = 'Unidad Orgánica';
        }

    // Consultas para combos de Entidades
        ?><input type="hidden"  name="___id_usu"  value="<? echo $_SESSION["id"] ?>"><?

        if($_SESSION["id"]==1) { //  Si es el Administrador
            $query="select depe_id,depe_nombre from depenti_v where depe_agente=0 order by depe_nombre";
            $rsEntidad=$db->sql_query($query);
            if(!$rsEntidad) {die($db->sql_error().' Error en Consulta Entidades'); }
        }

        // Consultas para combos de Estados
        $query="select tabl_codigo,tabl_descripcion from tabla where tabl_tipo='IDESTA' order by tabl_codigo";
        $rsestado=$db->sql_query($query);
        if(!$rsestado) {die($db->sql_error().' Error en Consulta Estado'); }

        if($_op==51){ // Entidad
            seccion("DATOS DE LA ENTIDAD",1,$_tipoedicion);
        } else { // Unidad orgánica
            seccion("DATOS DE LA UNIDAD ORGANICA",1,$_tipoedicion);
        }

        labelcajanum(3,$_tipoedicion, "Código de $descri", "zsxdepe_id",$row,0,6,0);
        if($_op==51) { // Dependencias externas
            labelcajatxt(1,$_tipoedicion, "Nombre","Sr_depe_nombre",$row,80);
            labelcajatxt(2,$_tipoedicion, "Nombre abreviado","Sr_depe_abreviado",$row,30);
            labelcajatxt(2,$_tipoedicion, "Siglas de documentos","St_depe_siglasexp",$row,40);
            labelcajatxt(1,$_tipoedicion, "Representante","St_depe_representante",$row,60);
            labelcajatxt(2,$_tipoedicion, "Cargo","St_depe_cargo",$row,40);
            seccion("DATOS DE LA CUENTA",2,$_tipoedicion);
            labelcajatxt(2,$_tipoedicion, "Usuario","Sx_usua_login",$row,20);
            labelcajapass(2,$_tipoedicion, "Password","sx_usua_password",$row,20);
        }else { // Dependencias Internas requiere llenar todos los campos
            $popup = strpos ($_SERVER['PHP_SELF'], "seekpopup");
            if($_SESSION["tipo_user"]=='5' and $popup===false) { // Si es un supervisor y no es un popup
                ?><input type="hidden"  name="___depe_depende"  value="<? echo $_SESSION["depe_depende"] ?>"><?
                labelcajatxt(2,3, "Dependencia","__Depe=".$_SESSION["entidad"],$row,60);
            }elseif($_SESSION["id"]==1)  // Sólo si es el Administrador
                labelcombo(1,$_tipoedicion,'Dependencia','tr_depe_depende',$row,$rsEntidad,0);

            labelcajatxt(1,$_tipoedicion, "Nombre","Sr_depe_nombre",$row,80);
            labelcajatxt(2,$_tipoedicion, "Nombre abreviado","Sr_depe_abreviado",$row,30);
            labelcajatxt(2,$_tipoedicion, "Siglas de documentos","Sr_depe_siglasexp",$row,40);
            labelcajatxt(1,$_tipoedicion, "Representante","Sr_depe_representante",$row,60);
            labelcajatxt(2,$_tipoedicion, "Cargo","Sr_depe_cargo",$row,40);
            labelcheck(2,$_tipoedicion, "Al registrar documento","Solicitar quien proyectó documento","nx_depe_proyectado",$row);
            labelcheck(2,$_tipoedicion, "Sobre Tr&aacute;mite","Recibe tr&aacute;mite desde otras Dependencias","nx_depe_recibetramite",$row);
            labelcajatxt(2,$_tipoedicion, "Núm.Máx.Exp.en Proceso","nr_depe_maxenproceso",$row,05);
            labelcajatxt(2,$_tipoedicion, "# Días.Máx.atención Exp","nx_depe_diasmaxenproceso",$row,05);
            seccion("ESTADO ACTUAL",2,$_tipoedicion);
            labelcombo(2,$_tipoedicion,'Estado','tr_depe_estado=1',$row,$rsestado,0) ;
            labelcajatxt(2,$_tipoedicion, "Observaciones","Sx_depe_observaciones",$row,100);
        }

        break;

    case 53: //Usuarios

    // Consultas para combos
		/* Estados */ 
        $query="select tabl_codigo,tabl_descripcion from tabla where tabl_tipo='IDESTA' order by tabl_codigo";
        $rsestado=$db->sql_query($query);
        if(!$rsestado) {die($db->sql_error().' Error en Consulta Estado'); }

		/* Tipos de Usuarios */
        if($row["id_usu"]<>1) { // Si es registro a editar no es el del ADMINISTRADOR
            $query="select tabl_codigo,tabl_descripcion from tabla where tabl_tipo='IDTUSU' order by tabl_codigo";
            $rstipouser=$db->sql_query($query);
            if(!$rstipouser) {die($db->sql_error().' Error en Consulta Tipo de Usuarios'); }
        }

        if($_SESSION["tipo_user"]=='5' and $_SESSION["id"]<>1) { // Si es un supervisor pero no es el Administrador
			/* Dependencias en Combo (Solo en el caso que sea un supervisor ) */ 
            $query="select depe_id,depe_nombre from depint_v where depe_depende=$_SESSION[depe_depende] order by depe_nombre";
            $rsDependencia=$db->sql_query($query);
            if(!$rsDependencia) {die($db->sql_error().' Error en Consulta Dependencias'); }
        }

        //		$err_mesaje='Prueba';
        //		if($err_mesaje) {error($err_mesaje);exit;}

        // Campos
        labelcajanum(3,$_tipoedicion, "Código de Usuario", "zsxid_usu",$row,0,5,0);
        seccion("DATOS PERSONALES",1,$_tipoedicion);
        labelcajatxt(1,$_tipoedicion, "Nombres","Sr_usua_nombres",$row,60);
        labelcajatxt(1,$_tipoedicion, "Apellidos","Sr_usua_apellidos",$row,60);
        labelcajatxt(2,$_tipoedicion, "Cargo","Sr_usua_cargo",$row,80);
        labelcajatxt(2,$_tipoedicion, "Email","cx_usua_email",$row,80);
        labelcajatxt(2,$_tipoedicion, "Iniciales","Sr_usua_iniciales",$row,8);
        if($_SESSION["tipo_user"]=='5' and $_SESSION["id"]<>1) { // Si es un supervisor pero no es el Administrador
            labelcombo(1,$_tipoedicion,'Unidad Org.','sr_depe_id',$row,$rsDependencia,0) ;
        }else { // Para el Administrador
            labelpopup(1,$_tipoedicion,"Unidad Org.","sr_depe_id",$row,$_nameform,6,'P59',0,75);
        }
        seccion("DATOS DE LA CUENTA",1,$_tipoedicion);
        labelcajatxt(2,iif($_op,'==','1C',3,$_tipoedicion), "Usuario","Sr_usua_login",$row,20);
        labelcajapass(2,$_tipoedicion, "Password","sr_usua_password",$row,20);
        labelcajapass(2,$_tipoedicion, "Retipee Password","srxreusua_password",$row,20);
        if($row["id_usu"]<>1) { // Si el registro a editar no es el del ADMINISTRADOR
            labelcombo(1,$_tipoedicion,'Tipo de usuario','tr_usua_tipo',$row,$rstipouser,0) ;
        }
        labelcheck(2,$_tipoedicion, "","Atiende a públio externo","nx_usua_caseta",$row);
        labelcajadate(2,$_tipoedicion, "Vigente hasta","Dr_usua_vigencia=".date("d/m/Y"),$row,$_nameform);
        labelcombo(1,$_tipoedicion,'Estado','tr_usua_estado',$row,$rsestado,0) ;
        labelcajatxt(2,$_tipoedicion, "Observaciones","Sx_usua_observaciones",$row,100);

        break;

    case 54: // Correlativos
    // *********************************** //
    // ***** Consultas para combos ******* //
    // *********************************** //
    // Tipos de expedientes
        $query="select texp_id,texp_descripcion from tipo_expediente order by texp_descripcion";
        $rstipexpe=$db->sql_query($query);
        if(!$rstipexpe) {die($db->sql_error().' ERROR EN CONSULTA DE TIPOS DE EXPEDIENTES '); }

        // Periodos
        $query="select distinct teco_periodo,teco_periodo from tipo_expediente_correl order by teco_periodo";
        $rsPeriodo=$db->sql_query($query);
        if(!$rsPeriodo) {die($db->sql_error().' ERROR EN CONSULTA DE PERIODOS '); }

		/* Dependencias en Combo (Solo en el caso que sea un supervisor ) */ 
        if($_SESSION["tipo_user"]=='5') { // Si es un supervisor
            $query="select depe_id,depe_nombre from depint_v where depe_depende=$_SESSION[depe_depende] order by depe_nombre";
            $rsDependencia=$db->sql_query($query);
            if(!$rsDependencia) {die($db->sql_error().' Error en Consulta Dependencias'); }
        }

        if($_tipoedicion==2)
            $query   = "select id_usu, usua_login from usuario where depe_id=".$row[depe_id]." order by usua_nombres";
        else {
            if($_POST[ss_depe_id])
                $query   = "select id_usu, usua_login from usuario where depe_id=$_POST[ss_depe_id] order by usua_nombres";
            else
                $query   = "select id_usu, usua_login from usuario where depe_id=9999 order by usua_nombres";
        }

        $rsusua=$db->sql_query($query);
        if(!$rsusua) {die($db->sql_error().' ERROR EN CONSULTA DE USUARIOS '); }

        // *********************************** //
        // ***** Campos Generales ************ //
        // *********************************** //
        seccion("DATOS DEL CORRELATIVO",1,$_tipoedicion);
        labelcajanum(2,$_tipoedicion, "Código ", "zsxteco_id",$row,0,6,0);
        $peri=date('Y');
        labelcombo(3,($_tipoedicion==2)?3:$_tipoedicion,'Periodo','tr_teco_periodo='.$peri,$row,$rsPeriodo,0);

        if($_SESSION["tipo_user"]=='5') { // Si es un supervisor
            labelcombo(1,($_tipoedicion==2)?3:$_tipoedicion,'Unidad Org.','ss_depe_id',$row,$rsDependencia,1);
        }else { // Para el Administrador
            labelpopup(3,($_tipoedicion==2)?3:$_tipoedicion,"Unidad Org.","ss_depe_id",$row,'formregistro',6,'P52',1);
        }

        if($_tipoedicion==2) {
            if($row[id_usu])
                labelcombo(3,3,'Usuario','tr_id_usu',$row,$rsusua,0,100,'','','','------- Todos -------');
        }else {
            labelcombo(3,$_tipoedicion,'Usuario','tr_id_usu',$row,$rsusua,0,100,'','','','------- Todos -------');
        }

        labelcombo(3,($_tipoedicion==2)?3:$_tipoedicion,'Tipo de Documento','tr_texp_id',$row,$rstipexpe,0);

        labelcajanum(2,$_tipoedicion, "Número siguiente", "nr_teco_numero",$row,0,4,0);


        break;

    case 55: // Bloqueos
    // Consultas para combos
    // Tipos de bloqueo
        $query="select tabl_codigo,tabl_descripcion from tabla where tabl_tipo='IDBLOQ' order by tabl_codigo";
        $rstipbloq=$db->sql_query($query);
        if(!$rstipbloq) {die($db->sql_error().' Error en consulta de tipos de bloqueos '); }
        if($err_mesaje) {error($err_mesaje);}

        // Usuarios por Dependencia
        if($_POST[nr_depe_id] || $depe_id) {
            $depe_id=($_POST[nr_depe_id])?$_POST[nr_depe_id]:$depe_id;
            $query   = "select id_usu, usua_nombres from usuario where depe_id=".$depe_id." order by usua_nombres";
        }

        $rsusua=$db->sql_query($query);
        if(!$rsusua) {die($db->sql_error().' Error en consulta de usuarios '); }

        // Campos
        seccion("DATOS DEL BLOQUEO",1,$_tipoedicion);
        labelcajanum(3,$_tipoedicion, "Código de Bloqueo", "zsxbloq_id",$row,0,5,0);
        labelcombo(1,$_tipoedicion,'Tipo','nr_bloq_bloqueo',$row,$rstipbloq,1) ;
        labelpopup(1,$_tipoedicion,"Unidad Org.","nr_depe_id",$row,$_nameform,6,'P59',1,75);
        if($_POST[nr_depe_id] || $depe_id)
            labelcombo(2,$_tipoedicion,"Usuario","tx_id_usu",$row,$rsusua,0,88);

        if($_POST[nr_bloq_bloqueo]!=3 && $bloq_bloqueo!=3)
            labelareatxt(2,$_tipoedicion, "Mensaje que mostrar&aacute;","Sr_bloq_mensaje",$row,4,80);

        break;

    case $_op==57: // Dependencia

    // Consultas para combos de Estados
        $query="select tabl_codigo,tabl_descripcion from tabla where tabl_tipo='IDESTA' order by tabl_codigo";
        $rsestado=$db->sql_query($query);
        if(!$rsestado) {die($db->sql_error().' Error en Consulta Estado'); }

        seccion("DATOS DE ".strtoupper(_LOCAL_),1,$_tipoedicion);
        labelcajanum(3,$_tipoedicion, "Código de ". _LOCAL_, "zsxdepe_id",$row,0,6,0);
        labelcajatxt(1,$_tipoedicion, "Nombre","Sr_depe_nombre",$row,80);
        labelcajatxt(2,$_tipoedicion, "Nombre abreviado","Sr_depe_abreviado",$row,30);
        labelcheck(2,$_tipoedicion, "Tipo de "._LOCAL_,"Es Agente","nx_depe_agente",$row,1);
        labelcombo(2,$_tipoedicion,'Estado','tr_depe_estado=1',$row,$rsestado,0) ;

        if($_POST[nx_depe_agente] || $row[depe_agente]) {  // Si se ingresa un Agente
            labelcajatxt(2,$_tipoedicion, "Siglas de documentos","Sr_depe_siglasexp",$row,60);
            labelcajatxt(1,$_tipoedicion, "Representante","Sr_depe_representante",$row,60);
            labelcajatxt(2,$_tipoedicion, "Cargo","Sr_depe_cargo",$row,60);
            labelcheck(2,$_tipoedicion, "Al registrar documento","Solicitar quien proyectó documento","nx_depe_proyectado",$row);
            labelcajatxt(2,$_tipoedicion, "Núm.Máx.Exp.en Proceso","zr_depe_maxenproceso",$row,05);
        }else { // Se ingresa una Entidad
            ?><input type="hidden"  name="___depe_proyectado"  value="0"><?
            labelpopup(2,$_tipoedicion,"Responsable Transparencia","nn_id_usu_transp",$row,$_nameform,6,'P60',0,75);  //Lo he comentado temporalmente porque es muy lento
        }

        break;

    case $_op==56: // Dependencia Orígen.  Se usa para reporte Expedientes recibidos, para permitir buscar dependencias
        labelcajatxt(1,$_tipoedicion, "Nombre","Sr_depe_nombre",$row,80);
        break;

    case $_op==59: //Dependencias Intenas (Se usa para las derivaciones, para poder mostrar las dependencias internas de la entidad y los sectores que son agentes)
      //
    // Consultas para combos de Entidades
        ?><input type="hidden"  name="___id_usu"  value="<? echo $_SESSION["id"] ?>"><?

        if($_SESSION["id"]==1) { //  Si es el Administrador
            //$query="select depe_id,depe_nombre from depenti_v where depe_agente=0 order by depe_nombre";
            $query="select depe_id,depe_nombre from dependencia order by depe_nombre";
            $rsEntidad=$db->sql_query($query);
            if(!$rsEntidad) {die($db->sql_error().' Error en Consulta Entidades'); }
        }

        // Consultas para combos de Estados
        $query="select tabl_codigo,tabl_descripcion from tabla where tabl_tipo='IDESTA' order by tabl_codigo";
        $rsestado=$db->sql_query($query);
        if(!$rsestado) {die($db->sql_error().' Error en Consulta Estado'); }

        seccion("DATOS DE LA UNIDAD ORGANICA",1,$_tipoedicion);
        labelcajanum(3,$_tipoedicion, "Código de Unidad Orgánica", "zsxdepe_id",$row,0,6,0);
        $popup = strpos ($_SERVER['PHP_SELF'], "seekpopup");
        if($_SESSION["tipo_user"]=='5' and $popup===false) { // Si es un supervisor y no es un popup
            ?><input type="hidden"  name="___depe_depende"  value="<? echo $_SESSION["depe_depende"] ?>"><?
            labelcajatxt(2,3, "Entidad","Entidad=".$_SESSION["entidad"],$row,60);
        }elseif($_SESSION["id"]==1)  // Sólo si es el Administrador
            labelcombo(1,$_tipoedicion,'Entidad','tr_depe_depende',$row,$rsEntidad,0);

        labelcajatxt(1,$_tipoedicion, "Nombre","Sr_depe_nombre",$row,80);
        labelcajatxt(2,$_tipoedicion, "Nombre abreviado","Sr_depe_abreviado",$row,30);
        labelcajatxt(2,$_tipoedicion, "Siglas de documentos","Sr_depe_siglasexp",$row,40);
        labelcajatxt(1,$_tipoedicion, "Representante","Sr_depe_representante",$row,60);
        labelcajatxt(2,$_tipoedicion, "Cargo","Sr_depe_cargo",$row,40);
        labelcheck(2,$_tipoedicion, "Al registrar documento","Solicitar quien proyectó documento","nx_depe_proyectado",$row);
        labelcheck(2,$_tipoedicion, "Sobre Tr&aacute;mite","Recibe tr&aacute;mite desde otras Entidades","nx_depe_recibetramite",$row);
        labelcajatxt(2,$_tipoedicion, "Núm.Máx.Exp.en Proceso","zr_depe_maxenproceso",$row,05);
        seccion("ESTADO ACTUAL",2,$_tipoedicion);
        labelcombo(2,$_tipoedicion,'Estado','tr_depe_estado=1',$row,$rsestado,0) ;
        labelcajatxt(2,$_tipoedicion, "Observaciones","Sx_depe_observaciones",$row,100);
        break;

    default:
        break;
} // Fin del switch

?>

