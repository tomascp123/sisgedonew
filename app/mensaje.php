<?
session_name("SISGEDO");
session_start(); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo2 {font-size: 14px; font-family: Arial, Helvetica, sans-serif;}
.Estilo1 {
	font-size: 18px;
	font-family: Arial, Helvetica, sans-serif;
	color: #FF0000;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
</head>

<body>
<table align="center" width="100%"  height="100%" border="0">
  <tr>
    <td align="center" valign="top"><span class="Estilo1">MENSAJE</span></td>
  </tr>
  <tr>
    <td align="center" valign="bottom"><span class="Estilo2"><? echo $_SESSION[bloq_mensaje]; ?></span>	</td>
  </tr>
</table>
</body>
</html>
