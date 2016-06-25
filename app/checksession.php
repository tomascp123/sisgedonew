<?php
if (!$_url or !eregi($_url,$_SERVER['PHP_SELF']) ) {
    header("Location: ../index.htm");
    die();
}
?>