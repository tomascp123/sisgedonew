<?
$_tabactivo=$_tabactivo?$_tabactivo:1;
if(!$_tipoedicion) $_tipoedicion=($_tabactivo==1)?5:1;
$tab1_caption="Mantenimiento";
$tab2_caption="Nuevo registro";

$_porcengrid="80"; //variable que contendra el % height del grid
$_porcengridwidth="100"; //variable que contendra el % width del grid
$_nameform="formregistro";
$_classgrid="class=griddatos cellspacing=0 border=1 rules=cols" ;
$_stringsqlwhere="";
$_stringsqlorder="";

// Archivo para procesar grabado de tablas dinámicas
$file_grabar_extend="php_grabar_extend.php";

switch ($_op) {
    case '1I': //login
        $_tab1_caption = "Login para ingresar al SisGeDo"; //caption del tab
        $_tab2_caption = ""; //caption del tab
        $_modfile = 'login.php';
        $_page1 = $modfile;  // página que mostrará el grid
        $_page2 = ''; // Página para editar los datos
        break;

    case '1P': // Cambiar contraseña
        $_tab1_caption = "Datos de mi Cuenta"; //caption del tab
        $_tab2_caption = ""; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "usuario"; // Tabla con la que se va a trabajar
        $_titulo = "datos de mi cuenta"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'id_usu'; // Campo clave para actualización
        $_page1 = $_modfile;  // página que mostrará el grid
        $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
        $_btncaption = "Actualizar";
        $_tipoedicion = 2;
        $_nameform = "frmusuario"; // Coloco el nombre de formulario para validar en base a este en mi archivo libjsgen_Extend.js
        $_mydato = $_SESSION[id];
        break;

    case '2': // Reportes
        $_tabactivo = 0; //tip para que no se active el setfocus de MENUTABS.PHP, ya que de lo contrario no se enfoca la nueva pàgina del reporte al presionar en EMPRIMIR
        $_tab1_caption = "Reportes"; //caption del tab
        $_tab2_caption = ""; //caption del tab
        $_modfile = $_nametype ? $_nametype : "formureporte.php"; // Página para editar los datos desde el grid;
        $_titulo = "Reportes"; // Titulo que aparece en la parte superior del grid
        $_btncaption = "Imprimir";
        $_tipoedicion = 1;
        $_nameform = "frmreporte"; // Coloco el nombre de formulario para validar en base a este en mi archivo libjsgen_Extend.js

        break;

    case '1C': // Buscar trámite de expediente
        $_tab1_caption = "Trámite"; //caption del tab
        if ($_POST[txtexpeid]) {
            $registro_id = $_POST[txtexpeid];
        } else {
            $registro_id = $_GET[txtexpeid];
        }

        if ($_POST['ver_tramite'] == 'Reg.Expediente' or $_POST['ver_tramite'] == 'Buscar') {
            $_modfile = "expedientetramite.php"; // Página para editar los datos desde el grid;
            $_titulo = "Trámite del Expediente" . " [ Registro ::> " . str_pad($registro_id, 8, '0', STR_PAD_LEFT) . " ]"; // Titulo que aparece en la parte superior del grid
        } else {
            $exma_id = saca_valor("select exma_id from expediente where expe_id = $registro_id", 'exma_id');
            $_modfile = "documentotramite.php"; // Página para editar los datos desde el grid;
            $_titulo = "Expediente ::> [" . str_pad($exma_id, 8, '0', STR_PAD_LEFT) . " ] <br> Trámite del Documento" . " [ Registro ::> " . str_pad($registro_id, 8, '0', STR_PAD_LEFT) . " ]"; // Titulo que aparece en la parte superior del grid
        }

        $_btncaption = "Volver";

        // Este código no se modifica
        break;

    case 10: // Buscar Expedientes
        $_tab1_caption = "Explorar Documentos"; //caption del tab
        $_tab2_caption = ""; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "expediente"; // Tabla con la que se va a trabajar
        $_titulo = "Buscar Documentos"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'expe_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        //estas líneas coloca todos los registros de la consulta en una sola pagina es decir evita el anterior/siguiente en el grid
        //			$fullregisgrid=1; //y ya no sería necesaria la segunda línea línea despúes del case, si solo deseo mostrar el grid sin búsqueda

        if ($_tipoedicion == 3 or $_tipoedicion == 5) {
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
        }

        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }

        switch ($_tipoedicion) {
            case 10: // Mostrar trámite de expediente
                $_POST[txtexpeid] = $_mydato;
                $_op = '1C';
                $_type = 'L';

                $_tab1_caption = "Trámite"; //caption del tab
                $_modfile = "documentotramite.php"; // Página para editar los datos desde el grid;
                $_titulo = "Trámite del Documento" . " [ Registro ::> " . str_pad($_POST[txtexpeid], 8, '0', STR_PAD_LEFT) . " ]"; // Titulo que aparece en la parte superior del grid

                break;
        }

        $_stringsql = "select lpad(a.expe_id::TEXT,8,'0') as Registro,lpad(a.exma_id::TEXT,8,'0') as Expediente,to_char(a.expe_fecha,'dd-mm-yyyy') as Fec_Registro,to_char(a.expe_fecha_doc,'dd-mm-yyyy') as Fec_Doc,d.texp_abreviado as tipo,lpad(a.expe_numero_doc::TEXT,6,'0') as Numero,
			a.expe_siglas_doc || case when a.expe_proyectado!='' then '-' || a.expe_proyectado when a.expe_proyectado='' then a.expe_proyectado end as Siglas,
			c.depe_nombre as Dependencia,a.expe_depe_detalle as Detalle,a.expe_firma as Firma,a.expe_cargo as Cargo,a.expe_asunto as Asunto,
			a.expe_hora as Hora,e.usua_login as Usuario,a.expe_id as _mydato 
			from expediente as a 
			left join dependencia c on a.depe_id=c.depe_id 
			left join tipo_expediente d on a.texp_id=d.texp_id 
			left join usuario e on a.id_usu=e.id_usu";

        break;

    case 31: // Expedientes en Proceso

        $_tab1_caption = "Explorar Documentos en Proceso"; //caption del tab
        $_tab2_caption = "Nuevo Documento"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "expedienteregistro.php"; // Página para editar los datos desde el grid;
        $_table = "expediente"; // Tabla con la que se va a trabajar
        $_titulo = "Documentos en Proceso"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'expe_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        $_porcengridwidth = "200"; //variable que contendra el % width del grid
        $fullregisgrid = 0; //y ya no sería necesaria la segunda línea línea despúes del case, si solo deseo mostrar el grid sin búsqueda

        if ($_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            // En mydato solo escojo el expe_id
            $_mydato = substr($_mydato, 0, strpos($_mydato, ";")); // $_mydato, siempre tiene el campo ID de la tabla

            $file_grabar_extend = "gra_ext_nvoexpdte.php";  //

            $_idinsert = str_pad($_idinsert, 8, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = "Actualizaci&oacute;n exitosa del Documento: " . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = "Terminar  Actualización";
            $_tedconfirma = 5;
        }

        if ($_tipoedicion == 4) {
            // Falta ordenar la variable $_mydato para que contenga los expe_id entre comas para que se pueda eliminar varios expedientes a la vez
            // En mydato solo escojo el expe_id
            $_mydato = str_pad(substr($_mydato, 0, strpos($_mydato, ";")), 8, '0', STR_PAD_LEFT);
            $_modfile = $pathlib . 'php_grabar.php';
        }

        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }

        switch ($_tipoedicion) {
            case 1: // Nuevo Expediente

                if ($_SESSION[ExpEnProceso] > $_SESSION[depe_maxenproceso]) {
                    $mensajeusuario = " Usted o en su oficina tienen demasiados documentos en proceso, Por favor ¡ Procese o Archive sus documentos ! ";
                    $bloqueado = 1;
                }

                if ($_SESSION["ExpEnProcesoDias"]) { /* Si existen expdtes en proceso más días de lo permitido */
                    $mensajeusuario = " Usted o en su oficina tienen documentos en proceso más de " . $_SESSION[depe_diasmaxenproceso] . " días, Por favor ¡ Procese o Archive sus documentos ! ";
                    $bloqueado = 1;
                }

                if ($_SESSION[ExpDerivadosEnEspera]) {
                    $mensajeusuario = " Usted o su oficina tienen documentos derivados desde hace $_SESSION[dias_xrecibir] día(s) que aún no han sido recibidos, Por favor ¡ Asegúrese que reciban sus documentos ! ";

                    $mensajeusuario .= '<br> Registros: ';
                    $mensajeusuario .= '<hr style="width:100px; " >';
                    foreach ($_SESSION["Arr_ExpDerivadosEnEspera"] as $indice => $valor) {
                        $mensajeusuario .= '<br>' . $valor[expe_id];
                    }

                    $bloqueado = 1;
                }

                //					if($_SESSION[bloq_bloqueo]==3) // Si el Uauario o la Dependencia estàn registrados como "No bloquear"
                //						$_SESSION["ExpXrecibir"] = 0;

                if ($_SESSION[ExpXrecibir]) { // Si está bloqueado el usuario o su dependencia
                    $mensajeusuario = " Usted o en su oficina tiene(n) $_SESSION[ExpXrecibir] Documento(s) Por Recibir desde hace $_SESSION[dias_xrecibir] día(s)..., Por favor ¡ Recepcione sus documentos !. <br> ";

                    $mensajeusuario .= '<br> Registros: ';
                    $mensajeusuario .= '<hr style="width:100px; " >';
                    foreach ($_SESSION["Arr_ExpXrecibir"] as $indice => $valor) {
                        $mensajeusuario .= '<br>' . $valor[expe_id];
                    }

                    $bloqueado = 1;
                }

                if ($_SESSION[bloq_bloqueo] == 2) { // Si el Usuario o la Dependencia han sido bloqueados por el Administrador
                    $mensajeusuario = $_SESSION["bloq_mensaje"];
                    $bloqueado = 1;
                }

                if ($bloqueado && $_SESSION[bloq_bloqueo] != 3) { // Si está bloqueado y el usuario puede ser bloqueado, es decir el Usuario o la Dependencia estàn registrados como "No bloquear"
                    $_tab1_caption = "Explorar Documentos en Proceso"; //caption del tab
                    $_modfile = $_nametype ? $_nametype : "confirma.php"; // Página para editar los datos desde el grid;
                } else {
                    // En mydato solo escojo el expe_id
                    $_mydato = substr($_mydato, 0, strpos($_mydato, ";")); // $_mydato, siempre tiene el campo ID de la tabla
                    $file_grabar_extend = "gra_ext_nvoexpdte.php";  //  Para poder grabar los registros de las tablas dinámicas
                    $_idinsert = str_pad($_idinsert, 8, "0", STR_PAD_LEFT); // Variable que guarda el id del registro que se acaba de grabar
                    $_idexpedi = str_pad($_idexpedi, 8, "0", STR_PAD_LEFT);

                    if (substr($_idinsert, 0, 5) != 'ERROR') {
                        if ($_update)
                            $_msjconfirma = "Nuevo Reg. Documento: " . $_idinsert . "<br><br><b>Nuevo Reg. Expediente: $_idexpedi</b>"; // Mensaje que se mostrará en la ventana confirma.php
                    }else {
                        if ($_update)
                            $_msjconfirma = $_idinsert; // Mensaje de Error a mostrar
                        $mensajeusuario = ' ¡¡ E R R O R !! ';
                    }

                    $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
                    $_page1 = $_modfile;  // página que mostrará el grid
                    $_page2 = $_modfile; // Página para editar los datos
                    $_btncaption = "Grabar"; // Captión del botón que aparece cuando se ingresa un nuevo registro
                    $_btnconfirma = $_tab2_caption; // Captión que aparece en el botón que aparece en la ventana de confirma.php	(En este caso Nuevo registro)
                    $_tedconfirma = 1;

                    // Para llamar función de Postgres
                    // Para registro de expediente
                    $myfunpginsert = "select my_addexpediente";
                    // Para registro de operaciones de derivacion
                    $myfpgins_extend = "select my_addoperacion";
                }
                break;

            case 10: // Derivar Expedientes
                //despues de activar la suscripcion en $_nametype llega 'confirma.php'
                $_titulo = "DERIVAR DOCUMENTO"; // Titulo que aparece en la parte superior del grid
                $_btncaption = "Grabar Derivaciones";
                $_modfile = $_nametype ? $_nametype : "expedientederivar.php";
                $_btnconfirma = "Terminar Derivación de Documento ";
                $_tedconfirma = 5;

                // Para llamar función de Postgres
                $myfpgins_extend = "select my_addoperacion";
                break;

            case 11: // Archivar expedientes
                $_titulo = "ARCHIVAR DOCUMENTO(S)"; // Titulo que aparece en la parte superior del grid
                $_btncaption = "Grabar";
                $_modfile = $_nametype ? $_nametype : "expedientearchivar.php";
                $_btnconfirma = "Terminar Archivamiento de Documento(s)";
                $_tedconfirma = 5;

                // Para llamar función de Postgres
                $myfpgins_extend = "select my_addoperacion";
                break;

            case 12: // Eliminar Derivación
                $_table = "operacion"; // Tabla con la que se va a trabajar
                $_campoclave = 'oper_id'; // Campo clave para actualización
                $_modfile = $pathlib . 'php_grabar.php';
                $_tipoedicion = 4; // Para que entre a la opción eliminar en el phpgrabar.php

                break;

            case 13: // Adjuntar Expedientes
                $_titulo = "ADJUNTAR DOCUMENTO(S)"; // Titulo que aparece en la parte superior del grid
                $_btncaption = "Grabar";
                $_modfile = $_nametype ? $_nametype : "expedienteadjuntar.php";
                $_btnconfirma = "Terminar el Adjuntado de Documento(s)";
                $_tedconfirma = 5;

                // Para llamar función de Postgres
                $myfpgins_extend = "select my_addoperacion";
                break;

            default:
            /*
              // Para llamar función de Postgres
              // Para registro de expediente
              $myfunpginsert="select my_addexpediente";
              // Para registro de operaciones de derivacion
              $myfpgins_extend="select my_addoperacion";
              break;
             */
        }

        // Este código no se modifica
        break;

    case 32: // Expedientes Por recibir
        $_tab1_caption = "Explorar Documentos por Recibir"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "expdtexrecibirregistro.php"; // Página para editar los datos desde el grid;
        $_table = "expediente"; // Tabla con la que se va a trabajar
        $_titulo = "Documentos por Recibir"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'expe_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        //estas líneas coloca todos los registros de la consulta en una sola pagina es decir evita el anterior/siguiente en el grid
        //			$_flag=!$_flag?2:$_flag;  // Esta lìnea la descomento si deseo que al ingresar me muestre directamente el grid
        $fullregisgrid = 0; //y ya no sería necesaria la segunda línea línea despúes del case, si solo deseo mostrar el grid sin búsqueda

        if ($_tipoedicion == 3 or $_tipoedicion == 5) {
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
        }

        if ($_tipoedicion == 10) { // Recepcionar expedientes
            $_titulo = "RECIBIR DOCUMENTO"; // Titulo que aparece en la parte superior del grid
            $_btncaption = "Grabar";
            $_modfile = $_nametype ? $_nametype : "expedienterecibir.php";
            $_btnconfirma = "Terminar Recepción de Documento(s) ";
            $_tedconfirma = 5;

            // Para llamar función de Postgres
            $myfunpginsert = "select my_addoperacion";
            break;
        }

        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }

        $_stringsql = "select lpad(a.expe_id::TEXT,8,'0') as Registro,
                                                lpad(b.exma_id::TEXT,8,'0') as Expediente,
						CASE 
						WHEN a.oper_forma=0 THEN 'ORIGINAL' 
						WHEN a.oper_forma=1 THEN 'COPIA' 
						END as Forma,
						CASE 
						WHEN a.oper_forma=0 THEN b.expe_folios 
						WHEN a.oper_forma=1 THEN 1 
						END as Folios,
						d.texp_abreviado as tipo,
						lpad(b.expe_numero_doc::TEXT,6,'0') as Numero,
						b.expe_siglas_doc ||
						CASE 
						WHEN b.expe_proyectado!='' then '-' || b.expe_proyectado 
						WHEN b.expe_proyectado='' then b.expe_proyectado 
						END as Siglas, 
						c.depe_nombre as Dependencia,
						b.expe_depe_detalle as Detalle,
						g.depe_nombre as Entidad,
						b.expe_firma as Firma,
						b.expe_cargo as Cargo,
						b.expe_asunto as Asunto,
						f.usua_login as Para,
						to_char(a.oper_fecha,'dd-mm-yyyy') as Fecha,
						a.oper_hora as Hora,
						e.usua_login as Usuario,
						b.ar_expearchivo as archivo,
						a.expe_id || ';' || a.oper_id || ';' || a.oper_forma || ';' || COALESCE(a.oper_usuaid_d, 0) || ';' || $_SESSION[id] as _mydato 
						from operacion as a 
						left join expediente b on a.expe_id=b.expe_id 
						left join dependencia c on b.depe_id=c.depe_id 
						left join depenti_v g on g.depe_id=c.depe_depende 
						left join tipo_expediente d on b.texp_id=d.texp_id 
						left join usuario e on a.id_usu=e.id_usu 
						left join usuario f on a.oper_usuaid_d=f.id_usu ";

        $_stringsqlwhere = "where b.expe_estado=1 and (a.oper_idtope=2)  and a.oper_depeid_d=" . $_SESSION[depe_id] . " and a.oper_procesado=FALSE ";

        $_stringsqlorder = "";

        $gridcolconfig = array(
            "0" => array("campo" => "archivo", "obj" => "<a href=\"MyValue\" ><img src=\"../imagenes/download.gif\" width=\"35\" height=\"35\" border=\"0\" alt=\"Bajar archivo\"></a>", "width" => "", "color" => ""),
        );
        break;

    case 33: // Expedientes Archivados
        $_tab1_caption = "Explorar Documentos Archivados"; //caption del tab
        $_tab2_caption = ""; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "operacion"; // Tabla con la que se va a trabajar
        $_titulo = "Expdtes Archivados"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'oper_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        //estas líneas coloca todos los registros de la consulta en una sola pagina es decir evita el anterior/siguiente en el grid
        //			$fullregisgrid=1; //y ya no sería necesaria la segunda línea línea despúes del case, si solo deseo mostrar el grid sin búsqueda

        if ($_tipoedicion == 3 or $_tipoedicion == 5) {
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
        }

        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }

        $_stringsql = "select lpad(a.expe_id::TEXT,8,'0') as Registro,
								lpad(a.oper_expeid_adj::TEXT,8,'0') as Adjuntado_al,
								f.archi_periodo || ' / ' || f.archi_nombre as Archivador,
								d.texp_abreviado as tipo,
								lpad(b.expe_numero_doc::TEXT,6,'0') as Numero,
								b.expe_siglas_doc || 
								case 
									when b.expe_proyectado!='' then '-' || b.expe_proyectado 
									when b.expe_proyectado='' then b.expe_proyectado 
								end as Siglas,
								c.depe_nombre as Dependencia,
								b.expe_depe_detalle as Detalle,
								b.expe_firma as Firma,
								b.expe_cargo as Cargo,
								b.expe_asunto as Asunto,
								b.expe_fecha as Fecha,
								b.expe_hora as Hora,
								e.usua_login as Usuario,
								a.oper_id as _mydato 
						from operacion as a 
						left join expediente b on a.expe_id=b.expe_id 
						left join dependencia c on b.depe_id=c.depe_id 
						left join tipo_expediente d on b.texp_id=d.texp_id 
						left join usuario e on a.id_usu=e.id_usu 
						left join archivador f on a.archi_id=f.archi_id ";

        $_stringsqlwhere = "where (a.oper_idtope=3 or a.oper_idtope=4) and a.depe_id=" . $_SESSION[depe_id];
        $_stringsqlorder = "";


        // Este código no se modifica
        break;

    case 41: // Archivadores
        $_tab1_caption = "Explorar Archivadores"; //caption del tab
        $_tab2_caption = "Nuevo Archivador"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "archivador"; // Tabla con la que se va a trabajar
        $_titulo = "Archivadores"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'archi_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo Archivador: ", "Actualizaci&oacute;n exitosa del Archivador: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }
        if ($_type == 'P') {
            $popcolmues = 2; // Columna que se mostrará en el campo detalle del control
            $popalto = 900; // Alto del popup
            $popancho = 800; // Ancho del popup
            $_porcengrid = 100;
        }
        $_stringsql = "select a.archi_nombre as Descripcion,COALESCE(b.usua_login,'') as Archivador_de,a.archi_periodo as Periodo,";
        $_stringsql.="a.archi_id as _mydato from archivador a ";
        $_stringsql.="left join usuario b on a.archi_idusua=b.id_usu ";
        $_stringsqlwhere = "where a.depe_id=" . $_SESSION[depe_id];

        // Este código no se modifica
        break;


    case 42: // Tipo de expedientes
        $_tab1_caption = "Explorar Tipos de Documentos"; //caption del tab
        $_tab2_caption = "Nuevo Tipo"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "tipo_expediente"; // Tabla con la que se va a trabajar
        $_titulo = "Tipos de Documentos"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'texp_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo Tipo de Documento: ", "Actualizaci&oacute;n exitosa del Tipo de Documento: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }
        if ($_type == 'P') {
            $popcolmues = 2; // Columna que se mostrará en el campo detalle del control
            $popalto = 900; // Alto del popup
            $popancho = 800; // Ancho del popup
            $_porcengrid = 100;
        }
        $_stringsql = "select a.texp_id as Cod,a.texp_descripcion as Descripcion,a.texp_abreviado as Abreviado,";
        $_stringsql.="a.texp_id as _mydato from tipo_expediente as a";

        break;

    case 43: // Formas de recepción
        $_tab1_caption = "Explorar Formas de recepción"; //caption del tab
        $_tab2_caption = "Nueva Forma"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "forma_recepcion"; // Tabla con la que se va a trabajar
        $_titulo = "Formas de Recepción"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'frec_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo Forma de Recepci&oacute;n: ", "Actualizaci&oacute;n exitosa de la Forma de recepci&oacute;n: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }
        if ($_type == 'P') {
            $popcolmues = 2; // Columna que se mostrará en el campo detalle del control
            $popalto = 900; // Alto del popup
            $popancho = 800; // Ancho del popup
            $_porcengrid = 100;
        }
        $_stringsql = "select a.frec_id as Cod,a.frec_descripcion as Descripcion,a.frec_abreviado as Abreviado,";
        $_stringsql.="a.frec_id as _mydato from forma_recepcion as a";

        break;

    case 44: // Tipos de prioridades
        $_tab1_caption = "Explorar Tipos de Prioridades"; //caption del tab
        $_tab2_caption = "Nuevo Tipo"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "tipo_prioridad"; // Tabla con la que se va a trabajar
        $_titulo = "Tipos de Prioridades"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'tpri_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo Tipo de prioridad: ", "Actualizaci&oacute;n exitosa del Tipo de prioridad: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }
        if ($_type == 'P') {
            $popcolmues = 2; // Columna que se mostrará en el campo detalle del control
            $popalto = 900; // Alto del popup
            $popancho = 800; // Ancho del popup
            $_porcengrid = 100;
        }
        $_stringsql = "select a.tpri_id as Cod,a.tpri_descripcion as Descripcion,a.tpri_abreviado as Abreviado,";
        $_stringsql.="a.tpri_id as _mydato from tipo_prioridad as a";

        // Este código no se modifica
        break;

    case 45: // Tupa
        $_tab1_caption = "Explorar Tupa"; //caption del tab
        $_tab2_caption = "Nuevo Registro"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "tupa"; // Tabla con la que se va a trabajar
        $_titulo = "Tupa"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'tupa_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo Registro: ", "Actualizaci&oacute;n exitosa del Registro: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }
        if ($_type == 'P') {
            $popcolmues = 1; // Columna que se mostrará en el campo detalle del control
            $popalto = 900; // Alto del popup
            $popancho = 800; // Ancho del popup
            $_porcengrid = 100;
            $_modfile = 'gridbuild.php'; // SI DESEAMOS QUE LA PRIMERA VEZ SE MUESTRE EL GRID DIRECTAMENTE EN UNA VENTANA POPUP
//							$_stringsqlorder="order by 2";	// Para el orden en que se mostrarán los datos al abrir el combo
        }

        $_stringsql = "select a.tupa_id as Cod,a.tupa_descripcion as Descripcion,a.tupa_descricorta AS Abreviado,a.tupa_periodo as Periodo,";
        $_stringsql.="a.tupa_id as _mydato from tupa as a";

        // Este código no se modifica
        break;

    case 51: // Entidades
        $_tab1_caption = "Explorar Entidades"; //caption del tab
        $_tab2_caption = "Nueva Entidad"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "depext_v"; // Tabla con la que se va a trabajar
        $_titulo = "Entidades"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'depe_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo C&oacute;digo de Entidad: ", "Actualizaci&oacute;n exitosa de la Entidad: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }
        if ($_type == 'P') {
            $_orden = empty($_orden) ? 1 : $_orden; // Para ordenar por el campo Nombre cuando se abre el popup (Esta variable se maneja en el array de la clase gridpaginado)
            $popcolmues = 1; // Columna que se mostrará en el campo detalle del control
            $_porcengrid = 100;
//							$fullregisgrid=1; // Para mostrar todos los registros y no paginación
            $_modfile = 'gridbuild.php'; // SI DESEAMOS QUE LA PRIMERA VEZ SE MUESTRE EL GRID DIRECTAMENTE EN UNA VENTANA POPUP
            $_stringsqlorder = "order by 2"; // Para el orden en que se mostrarán los datos al abrir el combo
        }

        $_stringsql = "select a.depe_id as Cod,a.depe_nombre as Nombre,a.depe_abreviado as Abreviado,a.depe_siglasexp as Siglas,";
        $_stringsql.="a.depe_representante as Representante,a.depe_id as _mydato from depext_v as a";

        break;

    case 52: //Unidades Orgánicas
        $_tab1_caption = "Explorar Unidades Orgánicas"; //caption del tab
        $_tab2_caption = "Nueva Unidad Orgánica"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "depint_v"; // Tabla con la que se va a trabajar
        $_titulo = "Unidades Organicas"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'depe_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo C&oacute;digo de Unidad Org.: ", "Actualizaci&oacute;n exitosa de la Unidad Org.: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }

        $_stringsql = "select a.depe_id as Cod,
								a.depe_nombre as Nombre,
								a.depe_abreviado as Abreviado,
								a.depe_siglasexp as Siglas,
								a.depe_representante as Representante,
								b.depe_nombre as Dependencia,
								a.depe_id as _mydato 
						from depint_v a 
						left join depenti_v b on b.depe_id=a.depe_depende ";

        if ($_SESSION["id"] == 1) //  Si es el Administrador
            $_stringsqlwhere = "";
        else { // Para cualquier otro usuario
            $_stringsqlwhere = "where a.depe_depende=$_SESSION[depe_depende]";
        }

        if ($_type == 'P') { // Si es un Popup
            $_orden = empty($_orden) ? 1 : $_orden; // Para ordenar por el campo Nombre cuando se abre el popup (Esta variable se maneja en el array de la clase gridpaginado)
            $popcolmues = 1; // Columna que se mostrará en el campo detalle del control
            $_porcengrid = 100;
            $fullregisgrid = 0;
            $_modfile = 'gridbuild.php'; // SI DESEAMOS QUE LA PRIMERA VEZ SE MUESTRE EL GRID DIRECTAMENTE EN UNA VENTANA POPUP
            $_stringsqlorder = "order by 2"; // Para el orden en que se mostrarán los datos al abrir el combo
        }

        break;

    case 53: //mantenimiento de usuarios
        $_tab1_caption = "Explorar Usuarios y Asignar Permisos"; //caption del tab
        
        if($supervisorCreaUsuarios == 1){
            $_tab2_caption = "Nuevo Usuario"; //caption del tab
        } else {
            if($_SESSION['id'] == 1){ // Si es el ADMINISTRADOR
                $_tab2_caption = "Nuevo Usuario"; //caption del tab
            }
        }
        
        
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "usuario"; // Tabla con la que se va a trabajar
        $_titulo = "usuarios"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'id_usu'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        $_nameform = "frmusuario"; // Coloco el nombre de formulario para validar en base a este en mi archivo libjsgen_Extend.js

        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 5, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo C&oacute;digo de Usuario: ", "Actualizaci&oacute;n exitosa del Usuario: ") . $_idinsert;
            $_modfile = $_modfile;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar Actualizaci&oacute;n";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }
        if ($_tipoedicion == 10) {
            //despues de activar la suscripcion en $_nametype llega 'confirma.php'
            $_titulo = "PERMISOS"; // Titulo que aparece en la parte superior del grid
            $_btncaption = "Grabar Permisos";
            $_modfile = $_nametype ? $_nametype : "usuariopermisos.php";
            $_btnconfirma = "Terminar Asignaci&oacute;n de permisos ";
            $_tedconfirma = 5;
        }

        $local = _LOCAL_;

        if ($_type == 'P') {
            $_orden = empty($_orden) ? 1 : $_orden; // Para ordenar por el campo Nombre cuando se abre el popup (Esta variable se maneja en el array de la clase gridpaginado)
            $popcolmues = 1; // Columna que se mostrará en el campo detalle del control
            $_porcengrid = 100;
            $fullregisgrid = 1;
            $_modfile = 'gridbuild.php'; // SI DESEAMOS QUE LA PRIMERA VEZ SE MUESTRE EL GRID DIRECTAMENTE EN UNA VENTANA POPUP
            $_stringsqlorder = "order by 2"; // Para el orden en que se mostrarán los datos al abrir el combo

            $_stringsql = "select a.id_usu as Codigo,
								a.usua_nombres||' '|| a.usua_apellidos as Nombres,
								a.usua_cargo as Cargo,
								a.usua_login as Nick,
								a.usua_email as eMail,
								b.depe_nombre as Unidad,
								c.depe_nombre as $local,
								a.id_usu as _mydato 
						  from usuario as a 
						  left join dependencia b on a.depe_id=b.depe_id
						  left join depenti_v c on c.depe_id=b.depe_depende";
        } else {

            $_stringsql = "select a.id_usu as Codigo,
								a.usua_nombres as Nombres,
								a.usua_apellidos as Apellidos,
								a.usua_cargo as Cargo,
								a.usua_login as Nick,
								a.usua_email as eMail,
								b.depe_nombre as Unidad,
								c.depe_nombre as $local,
								a.id_usu as _mydato 
						  from usuario as a 
						  left join dependencia b on a.depe_id=b.depe_id
						  left join depenti_v c on c.depe_id=b.depe_depende";
        }

        if ($_SESSION["tipo_user"] == '5' and $_SESSION["id"] <> 1) { // Si es un supervisor pero no es el Administrador
            $_stringsqlwhere = "where b.depe_depende=$_SESSION[depe_depende] ";
        }

        break;

    case 54: // Correlativos
        $_tab1_caption = "Explorar Correlativos"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "tipo_expediente_correl"; // Tabla con la que se va a trabajar
        $_titulo = "Correlativos"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'teco_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo Archivador: ", "Actualizaci&oacute;n exitosa del Archivador: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }

        $_stringsql = "select a.teco_periodo as Periodo,c.depe_nombre as Dependencia,b.texp_descripcion As Tipo,";
        $_stringsql.="COALESCE(d.usua_login,'') as Usuario,a.teco_numero as siguiente_número,a.teco_id as _mydato ";
        $_stringsql.="from tipo_expediente_correl a ";
        $_stringsql.="left join tipo_expediente b on b.texp_id=a.texp_id ";
        $_stringsql.="left join depint_v c on c.depe_id=a.depe_id ";
        $_stringsql.="left join usuario d on d.id_usu=a.id_usu ";
        $_stringsqlorder = "order by dependencia, tipo";

        break;

    case 55: // mantenimiento de bloqueos
        $_tab1_caption = "Explorar Bloqueos"; //caption del tab
        $_tab2_caption = "Nuevo Bloqueo"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimplebloqueo.php"; // Página para editar los datos desde el grid;
        $_table = "bloqueo"; // Tabla con la que se va a trabajar
        $_titulo = "Bloqueos"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'bloq_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        $_nameform = "frmbloqueo"; // Coloco el nombre de formulario para validar en base a este en mi archivo libjsgen_Extend.js

        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 5, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo Bloqueo: ", "Actualizaci&oacute;n exitosa del Bloqueo: ") . $_idinsert;
            $_modfile = $_modfile;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Grabar";
            if ($_tipoedicion == 2)
                $_btncaption = "Grabar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar Actualizaci&oacute;n";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }

        $_stringsql = "select b.depe_nombre as Dependencia,c.usua_nombres || ' ' || c.usua_apellidos as Usuario,a.bloq_mensaje as Mensaje,d.tabl_descripcion as Tipo,";
        $_stringsql.="a.bloq_id as _mydato FROM bloqueo as a ";
        $_stringsql.="left join dependencia b on a.depe_id=b.depe_id ";
        $_stringsql.="left join usuario c on c.id_usu=a.id_usu ";
        $_stringsql.="left join tabla d on d.tabl_tipo='IDBLOQ' and d.tabl_codigo=a.bloq_bloqueo::VARCHAR ";
        break;

    case 56: // Todas las Dependencias, se usa solo en Reporte de expedientes recibidos.
        $_tab1_caption = "Explorar Entidades"; //caption del tab
        $_tab2_caption = "Nueva Entidad"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "depext_v"; // Tabla con la que se va a trabajar
        $_titulo = "Entidades"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'depe_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo C&oacute;digo de Entidad: ", "Actualizaci&oacute;n exitosa de la Entidad: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }
        if ($_type == 'P') {
            $_orden = empty($_orden) ? 1 : $_orden; // Para ordenar por el campo Nombre cuando se abre el popup (Esta variable se maneja en el array de la clase gridpaginado)
            $popcolmues = 1; // Columna que se mostrará en el campo detalle del control
            $_porcengrid = 100;
//							$fullregisgrid=1;
            $_modfile = 'gridbuild.php'; // SI DESEAMOS QUE LA PRIMERA VEZ SE MUESTRE EL GRID DIRECTAMENTE EN UNA VENTANA POPUP
            $_stringsqlorder = "order by 2"; // Para el orden en que se mostrarán los datos al abrir el combo
        }

        $_stringsql = "select a.depe_id as Cod,a.depe_nombre as Nombre,a.depe_abreviado as Abreviado,a.depe_siglasexp as Siglas,";
        $_stringsql.="a.depe_representante as Representante,a.depe_id as _mydato from dependencia as a";

        // Este código no se modifica
        break;

    case 57: // Dependencias
        $_tab1_caption = "Explorar " . _LOCALES_; //caption del tab
        $_tab2_caption = "Nuevo " . _LOCAL_; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "depenti_v"; // Tabla con la que se va a trabajar
        $_titulo = _LOCALES_; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'depe_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo C&oacute;digo de " . _LOCAL_, "Actualizaci&oacute;n exitosa del " . _LOCAL_) . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2) {
                $_table = "dependencia";
                $_btncaption = "Actualizar";
            }
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }
        if ($_type == 'P') {
            $_orden = empty($_orden) ? 1 : $_orden; // Para ordenar por el campo Nombre cuando se abre el popup (Esta variable se maneja en el array de la clase gridpaginado)
            $popcolmues = 1; // Columna que se mostrará en el campo detalle del control
            $_porcengrid = 100;
            $fullregisgrid = 1;
            $_modfile = 'gridbuild.php'; // SI DESEAMOS QUE LA PRIMERA VEZ SE MUESTRE EL GRID DIRECTAMENTE EN UNA VENTANA POPUP
            $_stringsqlorder = "order by 2"; // Para el orden en que se mostrarán los datos al abrir el combo
        }

        $_stringsql = "select a.depe_id as Cod,
								a.depe_nombre as Nombre,
								a.depe_abreviado as Abreviado,
								case 
									when a.depe_agente=1 then 'AGENTE' 
									else ''
								end as Tipo, 
								b.usua_nombres||' '||b.usua_apellidos as Resp_Transparencia,
								a.depe_id as _mydato 
						 from depenti_v as a
						 left join usuario b on b.id_usu=a.id_usu_transp";

        break;

    case 58: //Dependencias Internas (Se usa en reportes)
        $_tab1_caption = "Explorar Unidades Orgánicas"; //caption del tab
        $_tab2_caption = "Nueva Unidad Org."; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "depint_v"; // Tabla con la que se va a trabajar
        $_titulo = "Unidades Orgánicas"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'depe_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo C&oacute;digo de Unidad Org.: ", "Actualizaci&oacute;n exitosa de la Unidad Org.: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }

        $v_depe_depende = ($_POST[tr_entidad]) ? "and a.depe_depende=$_POST[tr_entidad]" : "";
        $_stringsql = "select a.depe_id as Cod,
						a.depe_nombre as Nombre,
						a.depe_abreviado as Abreviado,
						a.depe_siglasexp as Siglas,
						a.depe_representante as Representante,
						b.depe_nombre as Entidad,
						a.depe_id as _mydato 
						from dependencia a 
						left join depenti_v b on b.depe_id=a.depe_depende 
						where a.depe_tipo IN (0,1) and a.depe_estado='1' $v_depe_depende";

        if ($_type == 'P') { // Si es un Popup
            $_orden = empty($_orden) ? 1 : $_orden; // Para ordenar por el campo Nombre cuando se abre el popup (Esta variable se maneja en el array de la clase gridpaginado)
            $popcolmues = 1; // Columna que se mostrará en el campo detalle del control
            $_porcengrid = 100;
//							$fullregisgrid=1;
            $_modfile = 'gridbuild.php'; // SI DESEAMOS QUE LA PRIMERA VEZ SE MUESTRE EL GRID DIRECTAMENTE EN UNA VENTANA POPUP
            $_stringsqlorder = "order by 2"; // Para el orden en que se mostrarán los datos al abrir el combo
        }
        break;

    case 59: //Dependencias Internas (Se usa para las derivaciones, para poder mostrar las dependencias internas de la entidad y los sectores que son agentes)
        $_tab1_caption = "Explorar Unidades Orgánicas"; //caption del tab
        $_tab2_caption = "Nueva Unidad Org."; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "depint_v"; // Tabla con la que se va a trabajar
        $_titulo = "Unidades Orgánicas"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'depe_id'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 3, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo C&oacute;digo de Unidad Org.: ", "Actualizaci&oacute;n exitosa de la Unidad Org.: ") . $_idinsert;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar  Actualización";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }

        $_stringsql = "select a.depe_id as Cod,
								a.depe_nombre as Nombre,
								a.depe_abreviado as Abreviado,
								a.depe_siglasexp as Siglas,
								a.depe_representante as Representante,
								b.depe_nombre as Entidad,
								a.depe_id as _mydato 
						from dependencia a 
						left join depenti_v b on b.depe_id=a.depe_depende ";

        if ($_SESSION["id"] == 1) //  Si es el Administrador
        //$_stringsqlwhere="where a.depe_tipo IN (0,1) and a.depe_estado='1'";
            $_stringsqlwhere = "where (a.depe_tipo = 1 OR a.depe_agente = 1) AND a.depe_estado='1'";
        else { // Para cualquier otro usuario
            $_stringsqlwhere = "where a.depe_estado='1' and (a.depe_depende=$_SESSION[depe_depende] or depe_recibetramite=1)";
        }

        if ($_type == 'P') { // Si es un Popup
            $_orden = empty($_orden) ? 1 : $_orden; // Para ordenar por el campo Nombre cuando se abre el popup (Esta variable se maneja en el array de la clase gridpaginado)
            $popcolmues = 1; // Columna que se mostrará en el campo detalle del control
            $_porcengrid = 100;
            $fullregisgrid = 0;
            $_modfile = 'gridbuild.php'; // SI DESEAMOS QUE LA PRIMERA VEZ SE MUESTRE EL GRID DIRECTAMENTE EN UNA VENTANA POPUP
            $_stringsqlorder = "order by 2"; // Para el orden en que se mostrarán los datos al abrir el combo
        }

        break;


    case 60: //mantenimiento de usuarios
        $_tab1_caption = "Explorar Usuarios y Asignar Permisos"; //caption del tab
        $_tab2_caption = "Nuevo Usuario"; //caption del tab
        $_modfile = $_nametype ? $_nametype : "registrosimple.php"; // Página para editar los datos desde el grid;
        $_table = "usuario"; // Tabla con la que se va a trabajar
        $_titulo = "usuarios"; // Titulo que aparece en la parte superior del grid
        $_campoclave = 'id_usu'; // Campo clave para actualización
        $_buttmenumx = "menugrid.php"; //archivo que contendra los botones de edicion del grid
        $_nameform = "frmusuario"; // Coloco el nombre de formulario para validar en base a este en mi archivo libjsgen_Extend.js

        if ($_tipoedicion == 1 or $_tipoedicion == 2 or $_tipoedicion == 3 or $_tipoedicion == 5) {
            $_idinsert = str_pad($_idinsert, 5, "0", STR_PAD_LEFT);
            if ($_update)
                $_msjconfirma = iif($_tipoedicion, "==", "1", "Nuevo C&oacute;digo de Usuario: ", "Actualizaci&oacute;n exitosa del Usuario: ") . $_idinsert;
            $_modfile = $_modfile;
            $_pagereturn = $_modfile; // Página a la que se retornará al terminar el registro de datos
            $_page1 = $_modfile;  // página que mostrará el grid
            $_page2 = $_modfile; // Página para editar los datos
            if ($_tipoedicion == 1)
                $_btncaption = "Registrar";
            if ($_tipoedicion == 2)
                $_btncaption = "Actualizar";
            if ($_tipoedicion == 5)
                $_btncaption = "Buscar";
            $_btnconfirma = ($_tipoedicion == 1) ? $_tab2_caption : "Terminar Actualizaci&oacute;n";
            $_tedconfirma = ($_tipoedicion == 1) ? 1 : 5;
        }
        if ($_tipoedicion == 4)
            $_modfile = $pathlib . 'php_grabar.php';
        if ($_tipoedicion == 5 && $_flag == 2) {
            $_modfile = 'gridbuild.php';
            $_btncaption = "Buscar";
        }
        if ($_tipoedicion == 10) {
            //despues de activar la suscripcion en $_nametype llega 'confirma.php'
            $_titulo = "PERMISOS"; // Titulo que aparece en la parte superior del grid
            $_btncaption = "Grabar Permisos";
            $_modfile = $_nametype ? $_nametype : "usuariopermisos.php";
            $_btnconfirma = "Terminar Asignaci&oacute;n de permisos ";
            $_tedconfirma = 5;
        }

        if ($_type == 'P') {
            $_orden = empty($_orden) ? 1 : $_orden; // Para ordenar por el campo Nombre cuando se abre el popup (Esta variable se maneja en el array de la clase gridpaginado)
            $popcolmues = 1; // Columna que se mostrará en el campo detalle del control
            $_porcengrid = 100;
            $fullregisgrid = 1;
            $_modfile = 'gridbuild.php'; // SI DESEAMOS QUE LA PRIMERA VEZ SE MUESTRE EL GRID DIRECTAMENTE EN UNA VENTANA POPUP
            $_stringsqlorder = "order by 2"; // Para el orden en que se mostrarán los datos al abrir el combo

            $_stringsql = "select a.id_usu as Codigo,
								a.usua_nombres||' '|| a.usua_apellidos as Nombres,
								a.usua_cargo as Cargo,
								a.usua_login as Nick,
								a.usua_email as eMail,
								b.depe_nombre as Dependencia,
								c.depe_nombre as Entidad,
								a.id_usu as _mydato 
						  from usuario as a 
						  left join dependencia b on a.depe_id=b.depe_id
						  left join depenti_v c on c.depe_id=b.depe_depende";
        }

        $_stringsqlwhere = "where a.usua_email IS NOT NULL ";


        break;



    default:
        $_tabactivo = '';
        $_modfile = 'background.php';
        break;

    case '11': // Otros reportes
        $_tab1_caption = "Otros Reportes";
        $_modfile = "../reports/otrosReportes.php"; // Página para editar los datos desde el grid;
        $_titulo = $_tab1_caption; // Titulo que aparece en la parte superior del grid
        $_btncaption = "Volver";



        // Este código no se modifica
        break;
}


/*
 $_tab1_caption="Mensaje del Administrador";//caption del tab
 $_modfile=$_nametype?$_nametype:"confirma.php"; // Página para editar los datos desde el grid;
 $mensajeusuario=" En este momento... El Sistema se encuentra en mantenimiento con el fin de poder brindarle un mejor servicio.  Por favor intente en unos minutos  ! ";
*/

if($_type!='P'){
	$file = @file($_modfile);
	if (!$file) {echo "<br>Archivo $_modfile no reconocido...";	}
	else {
		//M <- MODULO CON VERIFICACION DE PERMISOS
		//L <- MODULO LIBRE SIN VERIFICACION DE PERMISOS
		//G <- TIPO GRABAR O FUNCION CON VERIFICACION DE PERMISOS
		//GL <- TIPO GRABAR O FUNCION SIN VERIFICACION DE PERMISOS
		if(($_type=='M' or $_type=='L') && !$_npop) {include($pathlib.'tabs.php') ;}
		if($_type=='L' or $_type=='GL')  include("$_modfile");
		else { if (!$_update) include("modulo_permisos.php");
		else
		include("$_modfile");
		}
	}
	include("pie.php");
}
?>