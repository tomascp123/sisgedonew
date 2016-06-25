<?php

/** 
 * Clase Paginado
 *  
 * Clase que permite la consulta a bases de datos
 * mientras que ofrece un sistema de paginado y 
 * navegación de resultados de manera automática.
 *
 **/
class paginado
{
    /**
     * Identificador de recurso de conexion a la Base de Datos.
     *
     * Este atributo es pasado al objeto en el momento de instanciarlo.
	 * Debe ser un recurso válido
     * @access private
     * @since 25/02/2002 05:29:43 p.m.
     **/
//    var $_conn;
	
	/**
	 * Información interna de Error
	 *
	 * Contiene información sobre el último error generado en la ejecución
	 * del objeto.
	 * @access private
	 * @since 25/02/2002 05:30:27 p.m.
	 **/
	var $_error;
	
	/**
	 * Página actual de Resultados.
	 *
	 * Indica que página actual de resultados es la que se quiere pedir de
	 * la base.
	 * @access private
	 * @since 25/02/2002 05:56:59 p.m.
	 **/
	var $_pagina;
	var $_orden;	
	/**
	 * Resultados por cada página.
	 *
	 * Indica la cantidad de resultados que poseerá cada página de resultados.
	 * @access private
	 * @since 25/02/2002 05:31:22 p.m.
	 **/
	var $_porPagina = 20;
	
	/**
	 * Query SQL provisto por el usuario.
	 *
	 * Este Query debe ser un SELECT, sin la sentencia LIMIT (es agregada
	 * automáticamente por el Objeto).
	 * De no ser una sentencia SQL válida o si contiene algún tipo de 
	 * error, el objeto cancelará su ejecución devolviendo FALSE y seteando
	 * internamente un mensaje de error.
	 * @access private
	 * @since 25/02/2002 05:31:51 p.m.
	 **/
	var $_query;
	
	/**
	 * Identificador de Recurso de ResultSet.
	 *
	 * Contiene el identificador de resurso de las consultas realizadas
	 * en la base de datos.
	 * @access private
	 * @since 25/02/2002 05:54:45 p.m.
	 **/
	var $_rs;
	
	/**
	 * Total de Resultados.
	 *
	 * Indica la cantidad total de resultados que devuelve la consulta
	 * contenida en _query.
	 * @access private
	 * @since 26/02/2002 11:12:57 a.m.
	 **/
	var $_total;

	/**
	 * Total de Páginas.
	 *
	 * Indica la cantidad total de páginas que devuelve la consulta
	 * contenida en _query.
	 * @access private
	 * @since 26/02/2002 12:23:20 p.m.
	 **/
	var $_totalPaginas;
	
	/**
	 * Total de Registros.
	 *
	 * Indica la cantidad de registros leidos en la última consulta
	 * desde la base de datos.
	 * @access private
	 * @since 26/02/2002 12:17:22 p.m.
	 **/
	var $_registros;
	
	/**
	 * Código de Siguiente.
	 *
	 * Este atributo contiene el código HTML que representará al link
	 * para avanzar a la siguiente página de resultados.
	 * Puede ser cualquier código HTML permitido dentro dentro de un 
	 * tag <A>.
	 * @access private
	 * @since 26/02/2002 01:53:58 p.m.
	 **/
	var $_siguiente = "Siguiente >";
	
	/**
	 * Código de Anterior.
	 *
	 * Este atributo contiene el código HTML que representará al link
	 * para retroceder a la página anterior de resultados.
	 * Puede ser cualquier código HTML permitido dentro dentro de un 
	 * tag <A>.
	 * @access private
	 * @since 26/02/2002 01:54:04 p.m.
	 **/
	var $_anterior = "< Anterior";
	
	/**
	 * Color del contorno.
	 **/
	var $_ColCont;

	/**
	 * Color del titulo de los campos.
	 **/
	var $_ColTitCam;

	/**
	 * Color del titulo de los campos.
	 **/
	var $_ColBarra;

	/**
	 * Color de fondo de las filas de loas registros
	 **/
	var $_ColFilas;

	/**
	 * Para indicar si debe mostrar un check para seleccionar una fila 
	 **/
	var $_CheckSelec;

	/**
	 * Para indicar si los titulos de los campos se muestran como links para ordenar 
	 **/
	var $_TituloOrdenar;

	/**
	 * Para indicar si se muestran las barras de navegación con la cantidad de registros y pàginas 
	 **/
	var $_BarraNavegar;

	/**
	 * Para indicar la clase que dara forma y color a los titutlos de los campos
	 **/
	var $_ClassTitulo;

	/**
	 * Para indicar los colores que alternaran entre filas 
	 **/
	var $_ColFila1;
	var $_ColFila2;	
	/**
	 * Para indicar si el grid se mostrará solo en una página
	 **/
	var $_fullregisgrid;
	/**
	 * Para indicar como se va ha crear la tabla del grid
	 **/
	var $_conftablegrid;

	/**
	 * Para indicar la clase que dará formato a las tuplas del grid
	 **/
	var $_ClassTupla;

	/**
	 * Constructor de la clase
	 * 
	 * Recibe como parámetro un link hacia la base de datos y lo guarda.
	 * @since 26/02/2002 10:29:09 a.m.
	 * @return 
	 **/

	function __construct($nPagina,$norden,$nRegxPag,$cSql)
	// $nPagina  -->> Número de pàgina a mostrar o en la que estoy.
	// $nRegxPag -->> Número de registros a mostrar por página.
	{
		$this->pagina($nPagina); // Le indicamos en que página estamos 1 por defecto
		$this->porPagina($nRegxPag); // Le decimos cuantos registros por página queremos - 20 por defecto
		
		// Ejecutamos nuestra consulta.
		if(!$this->query($cSql));  // Si Query devolvió falso, hubo un error y lo mostramos.

		$this->_orden=empty($norden)?0:$norden; // Orden del array

		$this->_CheckSelec=1; // Para indicar si se mostrará un check de selecciòn por fila

		$this->_TituloOrdenar=1; // Para indicar si se mostrará los titulos de los campos como links posibilitando ordenar por columna

		$this->_BarraNavegar=1; // Para indicar si se mostrará la barra para navegar con las pàginas y con los registros

		$this->_ClassTitulo="gridhead"; // Para indicar la clase que dará forma y color a los titulos

		$this->_ColFila1="#FFFFFF"; // Primer color que se mostrará
		$this->_ColFila2="#FFFFFF"; // Segundo color que alternará
		$this->_fullregisgrid=0; //por defecto muesto paginado
		$this->_conftablegrid="class=griddatos cellspacing=0  border=1 rules=cols" ;

	} 
	
    /**
     * Método para acceder a $_conn
     *
     * @access public
     * @since 25/02/2002 05:29:59 p.m.
     **/
    function conn()
    {
    	switch (func_num_args())
    	{
    		case 1:
    			$this->_conn = func_get_arg(0);
    		break;
    		default:
    			return $this->_conn;
    		break;
    	}
    } // function
	
	/**
	 * Método para acceder a $_error
	 *
	 * @access public
	 * @since 25/02/2002 05:30:39 p.m.
	 **/
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
	
	/**
	 * Método para acceder a $_pagina
	 *
	 * @access public
	 * @since 25/02/2002 05:57:18 p.m.
	 **/
	function pagina()
	{
		switch (func_num_args())
		{
			case 1:
				$this->_pagina = func_get_arg(0);
				$this->_pagina = empty($this->_pagina)?1:$this->_pagina;
			break;
			default:
				return $this->_pagina;
			break;
		}
	} // function

	/**
	 * Método para acceder a $_porPagina
	 *
	 * @access public
	 * @since 25/02/2002 05:31:31 p.m.
	 **/
	function porPagina()
	{
		switch (func_num_args())
		{
			case 1:
				$this->_porPagina = func_get_arg(0);
			break;
			default:
				return $this->_porPagina;
			break;
		}
	} // function
	
	/**
	 * Método para acceder a $_total
	 *
	 * @access public
	 * @since 26/02/2002 11:13:19 a.m.
	 **/
	function total()
	{
		switch (func_num_args())
		{
			case 1:
				$this->_total = func_get_arg(0);
			break;
			default:
				return $this->_total;
			break;
		}
	} // function
	
	/**
	 * Método para acceder a $_totalPaginas
	 *
	 * @access public
	 * @since 26/02/2002 12:22:59 p.m.
	 **/
	function totalPaginas()
	{
		switch (func_num_args())
		{
			case 1:
				$this->_totalPaginas = func_get_arg(0);
			break;
			default:
				return $this->_totalPaginas;
			break;
		}
	} // function
	
	/**
	 * Método para acceder a $_rs
	 *
	 * En caso de ser un link inválido, el método retorna FALSE.
	 * @access public
	 * @since 25/02/2002 05:55:15 p.m.
	 **/
	function rs()
	{
		switch (func_num_args())
		{
			case 1:
				$this->_rs = func_get_arg(0);
				if(!$this->_rs)
				{
					return false;
				}// Fin If
				return true;
			break;
			default:
				return $this->_rs;
			break;
		}
	} // function
	
	/**
	 * Método para acceder a $_registros
	 *
	 * @access public
	 * @since 26/02/2002 12:17:44 p.m.
	 **/
	function registros()
	{
		switch (func_num_args())
		{
			case 1:
				$this->_registros = func_get_arg(0);
			break;
			default:
				return $this->_registros;
			break;
		}
	} // function

	/**
	 * Retorna el indice dentro del Result Set del primer
	 * elemento de la página actual.
	 * 
	 * @since 26/02/2002 12:00:12 p.m.
	 * @return 
	 **/
	function desde()
	{
		return (($this->pagina()-1)*$this->porPagina())+1;
	} // function
	
	/**
	 * Retorna el índice dentro del Result Set del último
	 * elemento de la página actual.
	 *
	 * @since 26/02/2002 12:18:08 p.m.
	 * @return 
	 **/
	function hasta()
	{
		return ($this->desde()-1)+$this->registros();
	} // function
	
	/**
	 * Ejecuta el Query el base, averiguando previamente la cantidad total de 
	 * registros que devuelve la consulta
	 *
	 * @access public
	 * @since 25/02/2002 05:31:59 p.m.
	 **/
	function query($query)
	{
  	    global $db,$fullregisgrid;
		//esta linea es porque ya se tienen sistemas con esta variable para controlar el paginado
		if($fullregisgrid) $this->_fullregisgrid=1;
		// Primero modificamos el query para averiguar la cantidad total
		// de registros que devuelve el query.
	    $rsquery=$db->sql_query($query);	
		if(!$rsquery) {die($db->sql_error().' ERROR EN CONSULTA '); }			
		
		$this->rs($rsquery);
		$this->total($db->sql_numrows($result));
		if($this->_total>6000){
			echo "<div align='center'><b>Demasiados registros...<br>
								Por favor sea más específico en su consulta</div>";					
			exit;
			return false;
		}
		
		$this->totalPaginas(iif($this->_fullregisgrid,'==',1,1,ceil($this->total() / $this->porPagina())));

		//numero de registros por mostrar
		$porMostrar=$this->total()-(($this->pagina()-1)*$this->porPagina());
		
		if($porMostrar>$this->porPagina())
			$numrowActul=$this->porPagina(); //numero de registros que se debe mostrar en la pagina actual
		else 
			$numrowActul=abs($porMostrar); //numero de registros que se debe mostrar en la pagina actual

		$this->registros(iif($fullregisgrid,'==',1,$db->sql_numrows($rsquery), $numrowActul ));
		
		// Comprobamos que no se intenta acceder a una página que no existe.
		if( $this->pagina() > $this->totalPaginas() )
		{
			$this->error("No exite la página ".$this->pagina()." de resutados. Hay solo un total de ".$this->totalPaginas());
			return true;
		}// Fin If


		return true;

	} // function

	/**
	 * Despliega el link hacia la siguiente página
	 *
	 * Siempre que quede una página siguiente, se muestra un link
	 * hacia la siguiente página de resultados.
	 * El método acepta ser llamado con un parámetro que contenga el
	 * código HTML que representará al link y que pueda ser representado
	 * encerrado dentro de un tag <A>.
	 * @access public
	 * @since 26/02/2002 01:49:29 p.m.
	 **/
	function siguiente()
	{
	 global $_op,$_where,$_url,$_npop;
		switch (func_num_args())
		{
			case 1:
				$this->_siguiente = func_get_arg(0);
			default:
				if($this->hasta() < $this->total())
				{
					return "<a href=\"$_url?pagina=".($this->pagina()+1).'&orden='.$this->_orden.'&_op='.$_op.'&_type='.iif($_npop,'!=','','P','M').'&_flag=2&_where='.str_replace("\'", "'",$_where)."&_npop=".$_npop."\"  target=\"_parent\">".$this->_siguiente."</a>";
				}// Fin If
			break;
		}
	} // function	
	
	/**
	 * Despliega el link hacia la página anterior.
	 *
	 * Siempre que no estemos en la primer página, se muestra un link
	 * hacia la página anterior de resultados.
	 * El método acepta ser sllamado con un parámetro que contenga el
	 * código HTML que representará al link y que pueda ser representado
	 * encerrado dentro de un tag <A>.
	 * @access public
	 * @since 26/02/2002 01:49:29 p.m.
	 **/
	function anterior()
	{
	 global $_op,$_where,$_url,$_npop;
		switch (func_num_args())
		{
			case 1:
				$this->_anterior = func_get_arg(0);
			default:
				if($this->pagina() != 1)
				{
					return "<a href=\"$_url?pagina=".($this->pagina()-1).'&orden='.$this->_orden.'&_op='.$_op.'&_type='.iif($_npop,'!=','','P','M').'&_flag=2&_where='.str_replace("\'", "'",$_where)."&_npop=".$_npop."\" target=\"_parent\">".$this->_anterior."</a>";					
				}// Fin If
			break;
		}
	} // function
	
	/**
	 * Despliega los números de páginas posibles
     *
     * Este método muestra una lista de todas las páginas posibles como
	 * links, excepto la página actual, que se encuentra sin link y resaltada
	 * en negrita.
	 * @since 26/02/2002 02:15:36 p.m.
	 * @return 
	 **/
	function nroPaginas()
	{
		global $_op,$_where,$_url,$_npop;

		for($i = 1; $i <= $this->totalPaginas() ; $i++){
			$temp[$i] = "<a href=\"$_url?pagina=$i&orden=".$this->_orden.'&_op='.$_op.'&_type='.iif($_npop,'!=','','P','M').'&_flag=2&_where='.str_replace("\'", "'",$_where)."&_npop=".$_npop."\" target=\"_parent\">$i</a>";
		} 
		$temp[$this->pagina()] = "<b>".$this->pagina()."</b>";

		if($this->totalPaginas()>25){ // Si existen más de 25 páginas
			 if($this->pagina()>3)
			 	if($this->pagina()==$this->totalPaginas())
					$temp1=array_slice($temp, $this->pagina()-10, 3);				
				else
				 	if($this->pagina()==$this->totalPaginas()-1)
						$temp1=array_slice($temp, $this->pagina()-3, 3);
					else
						$temp1=array_slice($temp, $this->pagina()-2, 3);					
			else
				$temp1=array_slice($temp, 0, 3);				
				
			$temp2=array_slice($temp, -1, 1);
			return implode(" | ", $temp1).' | ......... | '.implode(" | ", $temp2);
		}

		return implode(" | ", $temp);
	} // function

	function nroPaginas1() /* original */
	{
		global $_op,$_where,$_url,$_npop;

		for($i = 1; $i <= $this->totalPaginas() ; $i++){
			$temp[$i] = "<a href=\"$_url?pagina=$i&orden=".$this->_orden.'&_op='.$_op.'&_type='.iif($_npop,'!=','','P','M').'&_flag=2&_where='.str_replace("\'", "'",$_where)."&_npop=".$_npop."\" target=\"_parent\">$i</a>";
		} 
		$temp[$this->pagina()] = "<b>".$this->pagina()."</b>";

		return implode(" | ", $temp);
	} // function

	
	/**
	 * Indica que variables se desean propagar en los links.
     *
     * Este metodo recibe una lista de nombres que son guarados internamente
	 * hasta que son creados los links para navegar los resultados. En ese 
	 * momento, son agregados los nombres de las variables con sus valores
	 * para que puedan ser propagados.
	 * @since 26/02/2002 02:15:36 p.m.
	 * @return 
	 **/
	function propagar()
	{
        switch(func_num_args()){
            case 0: 
                foreach($this->_variables as $var)
                    $ret.= "&$var=".$GLOBALS[$var];
                return $ret;
                break;
            default:
                for($i = 0; $i < func_num_args(); $i++)
                {
                    $this->_variables[] = func_get_arg($i);
                } // for
                break;
        } // switch
	} // function
	
	/**
	 * Crea una tabla con los nombres de los campos y sus datos 
     *
     * Este metodo recibe el recordset resultante de la consulta y a partir de 
	 * allí genera la tabla.
	 * @since 26/01/2005 
	 * @return 
	 **/
	function table_create($result)
	{
  	    global $db,$_op,$_where,$_url,$_npop,$gridrowcolor,$gridcolconfig,$fullregisgrid,$_classgrid;
		
		if($this->_BarraNavegar)	// Si muestra la barra de navegar entre pàginas	
			// solo si es grid es paginado es decir no todo va en una sola pagina
			if(!$this->_fullregisgrid) {
			?>
			<table class="frmline2" width="100%" cellspacing="1" cellpadding="3" border="0">			
					<tr align="center">
					<td class="catBottom" colspan="7" >
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
					  <td width="70%" align="left" style='font-size:11px' >
						<? 
							echo $this->anterior()." - ".$this->nroPaginas()." - ".$this->siguiente(); ?>			  
					  </td>
					  <td width="30%" align="left">
					  <div class="msjConfirma">
						<? $this->cuenta(); ?>
					  </div> 
					  </td>
					</tr>
				</table>
					</td>
					</tr>
			  </table>
			<?
		}
		
			echo "<table class=\"gridfondo\" cellspacing=\"0\" cellpadding=\"0\" >\n";
			$numrows = $db->sql_numrows($result);
			$fnum = $db->sql_numfields($result);

			//creo mi array con el nombre de los campos
			for ($x = 0; $x < $fnum-1; $x++) {
					$arrayName[]=$db->sql_fieldname($x,$result);
			}
			
			while ($row=$db->sql_fetchrow($result)) {
					$this->arraydata[]=$row;
				}

	if (is_array($this->arraydata)) {		
		$this->arraydata=mu_sort($this->arraydata,$this->_orden) ;
		if($_classgrid) $this->_conftablegrid=$_classgrid;
		 ?>
			<tr>
				<td valign="top">	
					<table <? echo $this->_conftablegrid ?> bordercolor="#CCCCCC">
						<tr>
						<? 
						if($this->_CheckSelec){
						?>
						<td><input type='checkbox' name='ckboxall' onClick="checkform(frmgrid,this)" class="cajatexto" ></td>
						<? }
						
						// Cabecera con los nombres de los campos
						for ($x = 0; $x < $fnum-1; $x++) {
							if (substr($arrayName[$x],0,3) <> '___') { /* Para todos los campos que sean diferentes a ocultos */
								echo "<td align='center' class='".$this->_ClassTitulo."'><b>";	
								if($this->_TituloOrdenar)
									echo "<a class='".$this->_ClassTitulo."' href=\"$_url?pagina=".($this->pagina()).'&orden='.$x.'&_op='.$_op.'&_type='.iif($_npop,'!=','','P','M').'&_flag=2&_where='.str_replace("\'", "'",$_where)."&_npop=".$_npop."\"  target=\"_parent\">".ucfirst($arrayName[$x])."</a>";
								else
									echo ucfirst($arrayName[$x]);
	
								echo "</b></td>";
							}
						}
						echo "<td align='center' class='".$this->_ClassTitulo."'>&nbsp;</td>";
						echo "</tr>";

						// Datos de los campos
						$color=$this->_ColFila1;  // este es el primer color que queremos que aparezca por filas
						if($this->_ClassTupla)
							$stylo="class='".$this->_ClassTupla."' ";
						else
							$stylo="style='font-size:10px; font-family: Tahoma, Arial, sans-serif' ";
						
						$z=1;										
						while (list($clave, $valor ) = each($this->arraydata)){
							if($z>=$this->desde() && $z<=iif($fullregisgrid,'==',1,$this->total(), $this->desde()+$this->porPagina()-1)){
								echo "<tr ".fgridrowcolor($arrayName,$valor,$fnum)." bgcolor=\"$color\" height=\"18\" onmouseover=\"MO(event,'TR')\" onmouseout=\"MU(event,'TR')\">";
								if($this->_CheckSelec)
									echo "<td><input type='checkbox' name='ckbox'  onClick=\"checkform(frmgrid,this)\" value=\"".$valor[$fnum-1]."\"></td>";	
								// Datos de los campos
								for ($x = 0; $x < $fnum-1; $x++) {
									//funcion creada en mislibs/libphpgen_extend.php
									$linephp=myFunctionGrid($_op,$valor);
//									echo "<td style='font-size:10px; font-family: Tahoma, Arial, sans-serif' ".fgridcolconfig($arrayName,$x,'',1).">";
									if (substr($arrayName[$x],0,3) <> '___') { /* Para todos los campos que sean diferentes a ocultos */
										echo "<td $stylo".fgridcolconfig($arrayName,$x,'',1).">";
										if($linephp)
											echo "<a href=\"".$linephp."\" target=\"_blank\">".fgridcolconfig($arrayName,$x,$valor[$x],2)."</a>";
										else
											echo fgridcolconfig($arrayName,$x,$valor[$x],2);
										echo "</td>";
									}
								} //for
								echo "<td width=\"100%\" >&nbsp;</td>";
								echo "</tr>";
							    $color=($this->_ColFila2==$color)?$this->_ColFila1:$this->_ColFila2; 
							}
							$z++;
						} //end while
						

					?>
					</table>
				</td>
			</tr>
				<? 
			}else{
				echo "<div align='center'><b>No existen datos</div>";					
			}
			if($this->_BarraNavegar){	// Si muestra la barra de navegar entre pàginas					
				?>
				<table class="frmline2" width="100%" cellspacing="1" cellpadding="3" border="0">			
						<tr align="center">
						<td class="catBottom" colspan="7" >
					  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
						<tr>
						  <td width="70%" align="left" style='font-size:11px' >
							<? 
							echo $this->anterior()." - ".$this->nroPaginas()." - ".$this->siguiente(); 
							?>			  						
						  </td>
						  <td width="30%" align="left">
						  <div class="msjConfirma">
							<? $this->cuenta(); ?>
						  </div> 
						  </td>
						</tr>
					</table>
						</td>
						</tr>
			  </table> 
		<? }?>
		</table>
		<?
	} // function
	
	function cuenta()
	{
		echo $this->desde()." - ".$this->hasta()." / ".$this->total(); 
	}



} // end of class
?>
