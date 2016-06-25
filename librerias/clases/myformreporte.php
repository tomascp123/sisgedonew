<?php

class myformreporte
{
	var $_error;
	// id del reporte elegido
	var $_idRepo;	
	
	function __construct()
	{
		global $db;
		// Obtengo valor del reporte seleccionado
		$this->_idRepo=!isset($_POST['listarepo'])?1:$_POST['listarepo']; /* Si se ingresa por primera vez, se selecciona el reporte 1 */		
		// Consulto los reportes de mi tabla
		$rsquery=$db->sql_query("select * from reporte order by repo_id");	
		// Creo la Lista con los reportes
		?>	
		<SELECT	style="width:100%" NAME="listarepo" SIZE=8 onChange="Form_Submit(form)" >
			<?
			$seleccionado=''; // para controlar elemento seleccionado
			while ($row_lista=$db->sql_fetchrow($rsquery)) 
			 {	
			 	if($row_lista[0]==$this->_idRepo){
					$seleccionado='selected';
				}else{
					$seleccionado='';
				}
		   ?>
			<option value="<? echo $row_lista[0] ?>" <? echo $seleccionado ?>  ><? echo $row_lista[1] ?>
		 <?  }?>	  
		  </SELECT>
		<?	

		$db->sql_freeresult($rsquery);

		
	} 

	function creo_capaobjetos()
	{
		// Creo capa que contendrá los objetos
		?>
		<div id="capaobjetos" >
		<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
		<?
		$this->creo_objetos();
		?>
		</table>
		</div> 
		<?	
	
	} // function
	
	function creo_objetos()
	{
	} // function

	function creo_sql()
	{
	} // function

	function error()
	{
		switch (func_num_args())
		{
			case 1:
				$this->_error = func_get_arg(0);
			break;
			default:
				return $this->_error;
			break;
		}
	} // function
} // end of class
?>
