<?php
        session_name("SISGEDO");
	session_start(); 
	require_once("../app/config.php");
	require_once("rptlib.php");
//	ini_set("include_path",ini_get("include_path").";".$pathlib."phpreports");
	ini_set("include_path",$pathlib."phpreports");
	require_once("PHPReportMaker.php");
	
	/********************************************************************************
	*																				*
	*	Use this file to see a sample of PHPReports.								*
	*	Please check the PDF manual for see how to use it.							*
	*	It need to be placed on a directory reached by the web server.				*
	*																				*
	*********************************************************************************/
	$sql=$_SESSION['$_stringsql']; 
	$titulo=$_GET['_titulo']; 	
	$xml=$_GET['_xml'];
	
	$aParms = Array();
	$aParms["titulo"]=$titulo;


	$oRpt = new PHPReportMaker();
	$oRpt->setUser($dbUsuario);                                                    
	$oRpt->setPassword($dbpassword);
	$oRpt->setConnection($dbhost);                                   
	$oRpt->setDatabaseInterface(strtolower($dbtype));
	$oRpt->setDatabase($dbName);
	$oRpt->setSQL($sql);	
	$oRpt->setParameters($aParms);
	$oRpt->setXML($xml);
	$oOut = $oRpt->createOutputPlugin("default");
	
	$oOut->setClean(false);
	$oRpt->setOutputPlugin($oOut);
	$oRpt->run();
	
?>
