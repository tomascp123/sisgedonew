<? 
session_name("SISGEDO");
session_start();
session_unset();  // Desregistra todas las variables de session 
session_destroy(); //destruimos la session y sus variables
header("Location: main.php?_op=1I&_type=L");

?>
