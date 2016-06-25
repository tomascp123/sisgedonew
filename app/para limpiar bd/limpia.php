<?php
include_once("conexionlimpia.php");
list($dbhost, $sqlport) = split(":", $dbhost);
$conn = pg_connect("user=$dbUsuario password=$dbpassword host=$dbhost port=$sqlport dbname=$dbName");
if (pg_ErrorMessage($conn)) { echo "<p><b>Ocurrio un error conectando a la base de datos: .</b></p>"; exit; }

/* Limpiando expediente */
$resultado=pg_exec("DELETE FROM expediente");
if (!$resultado) { echo "<b>Error al Limpiar Expediente y Operacion</b>"; exit; }
else {echo "<b>Operación 1 correcto </b><br>"; }
pg_FreeResult($resultado);

/* Tipo_expediente_correl*/
$resultado=pg_exec("DELETE FROM tipo_expediente_correl");
if (!$resultado) { echo "<b>Error al Limpiar tipo_expediente_correl</b>"; exit; }
else {echo "<b>Operación 2 correcto </b><br>"; }
pg_FreeResult($resultado);

/* Archivador */
$resultado=pg_exec("DELETE FROM archivador");
if (!$resultado) { echo "<b>Error al Limpiar archivador</b>"; exit; }
else {echo "<b>Operación 3 correcto </b><br>"; }
pg_FreeResult($resultado);

/* bloqueo */
$resultado=pg_exec("DELETE FROM bloqueo");
if (!$resultado) { echo "<b>Error al Limpiar bloqueo</b>"; exit; }
else {echo "<b>Operación 4 correcto </b><br>"; }
pg_FreeResult($resultado);

/* mensaje */
$resultado=pg_exec("DELETE FROM mensaje");
if (!$resultado) { echo "<b>Error al Limpiar mensaje</b>"; exit; }
else {echo "<b>Operación 5 correcto </b><br>"; }
pg_FreeResult($resultado);

/* Secuencia de expedientes */
pg_exec("ALTER SEQUENCE expediente_expe_id_seq
				    INCREMENT 1  MINVALUE 1
				    MAXVALUE 9223372036854775807  RESTART 1
				    CACHE 1  NO CYCLE");

/* Secuencia de operacion */
pg_exec("ALTER SEQUENCE operacion_oper_id_seq
				    INCREMENT 1  MINVALUE 1
				    MAXVALUE 9223372036854775807  RESTART 1
				    CACHE 1  NO CYCLE");

/* Secuencia de Bloqueos */
pg_exec("ALTER SEQUENCE bloqueo_bloq_id_seq
				    INCREMENT 1  MINVALUE 1
				    MAXVALUE 9223372036854775807  RESTART 1
				    CACHE 1  NO CYCLE");

pg_close($conn);

?>
