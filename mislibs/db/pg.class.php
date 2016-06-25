<?
/*
	Classe para conexão em PostgreSQL, para utilizá-lo renomeie este arquivo
	para "db.class.php"
*/


/*********************************************
 * Classes para acesso à camada de dados
 * por Marcelo Rezende (malvre@gmail.com)
 * atualizado em 14/11/2002 -> suporte a navegacao de registros
 *
 *
 * Classe......: db
 * Métodos.....: db("tipodb") construtor, *experimental, usar sem parâmetros
 *               open(banco, host, user, password)
 *	              lock(tabela, modo)
 *               unlock()
 *               error()
 *               close()
 *               execute(sql)
 *               begin()
 *               commit()
 *               rollback()
 *
 * Classe......: query
 * Métodos.....: query(db, sql, numero_pagina, tamanho_pagina) -> construtor
 *               getrow()
 *               field(campo)
 *               fieldname([numerodocampo] ou [nomedocampo])
 *               firstrow()
 *               free()
 *               numrows()
 *               totalpages()
 *				  
 **************************/

class db {
	var $connect_id;
	var $type;
	
	function db($database_type="postgresql") {
		$this->type="postgresql";
		if (!function_exists("pg_pconnect")) {
			dl("pgsql.so");
		}
	}
	
	//----- executa uma expressão SQL
	function execute($strSQL) {
		$ret=@pg_query($this->connect_id, $strSQL);
		if($ret){
			$rowret = pg_fetch_row($ret);
			return $rowret[0];		
		}
	}

	//----- envia begin ao servidor de dados
	function begin() {
		pg_query($this->connect_id, "BEGIN");
	}

	//----- envia commit ao servidor de dados
	function commit() {
		pg_query($this->connect_id, "COMMIT");
	}

	//----- envia rollback ao servidor de dados
	function rollback() {
		pg_query($this->connect_id, "ROLLBACK");
	}
	
	function open($database=DB_DATABASE, $host=DB_HOST, $user=DB_USER, $password=DB_PASSWORD) {
		// $host::="hostname:port-number"
		
		$connstr="dbname=".$database;
		if ($host) {
			list($host,$port)=split(":", $host);
			$connstr=$connstr." host=$host port=$port";
		}
		if ($user) {
			$connstr=$connstr." user=".$user;
		}
		if ($password) {
			$connstr=$connstr." password=".$password;
		}
		$this->connect_id=@pg_connect($connstr);
		return $this->connect_id;
	}
	
	function lock($table, $mode="write") {
		if ($mode="write") {
			$query=new query($this, "lock table $table");
			$result=$query->result;
		} else {
			$result=1;
		}
		return $result;
	}
	
	function unlock() {
		$query=new query($this, "commit");
		$result=$query->result;
		return $result;
	}
	
	function nextid($sequence='') {
		global $setTable,$setKey;
		$sequence= $sequence?$sequence:$setTable. '_' . $setKey . "_seq";
		$esequence=ereg_replace("'","''",$sequence);
		$query=new query($this, "select nextval('$esequence') as nextid") ;
		if ($query->numrows()) {
			$query->getrow()	;	
			$nextid=$query->field("nextid");
		} else {
			$query->query($this, "create sequence $sequence")  ;
			if ($query->result) {
				$nextid=$this->nextid($sequence);
			} else {
				$nextid=0;
			}
		}
		return $nextid;
	}
	
	function lastid($sequence='') {
		global $setTable,$setKey;
		$sequence= $sequence?$sequence:$setTable. '_' . $setKey . "_seq";		
		$esequence=ereg_replace("'","''",$sequence);
		if (($query=new query($this, "select currval('$esequence') as last_value") )) {
			$query->getrow()	;	
			$nextid=$query->field("last_value");
		} else {
				$nextid=0;
			}
		return $nextid;
	}

	function setval($sequence='',$setValue=1) {

		$sql="ALTER SEQUENCE $sequence
		    INCREMENT 1  MINVALUE 1
		    MAXVALUE 9223372036854775807  RESTART $setValue
		    CACHE 1  NO CYCLE";
	
		if (($query=new query($this, $sql) )) {
				$setval=$setValue;
		} else {
				$setval=0;
			}
		return $setval;
	}


	function currval($sequence='') {
		$query=new query($this, "SELECT last_value FROM $sequence");
		if ($query->getrow()) {
			$currval=$query->field("last_value");
		} else {
				$currval=0;
			}
		return $currval;
	}

	function error() {
		return pg_last_error($this->connect_id);
	}

	function notice() {
		return pg_last_notice($this->connect_id);
	}
	
	function close() {
		$query=new query($this, "commit");
		if ($this->query_id && is_array($this->query_id)) {
			while (list($key,$val)=each($this->query_id)) {
				@pg_free_result($val);
			}
		}
		$result=@pg_close($this->connect_id);
		return $result;
	}
	
	function addquery($query_id) {
		$this->query_id[]=$query_id;
	}
};

/*********************************** QUERY *********************************/

class query {
	var $result;
	var $row;
	var $curr_row;
	var $numrows;
	var $numfields;	
	var $totalpages=0;
	
	function query(&$db, $query="", $pagina_inicial=0, $tamanho_pagina=0) {
		if ($this->result) {
			$this->free();
		}
		$this->result=@pg_query($db->connect_id, $query);
		$this->numrows = @pg_num_rows($this->result);
		$this->numfields = @pg_num_fields($this->result);
		
		if (($pagina_inicial+$tamanho_pagina) > 0) {
			$this->totalpages = ceil($this->numrows() / $tamanho_pagina);
			$query .= " limit $tamanho_pagina offset " . ($pagina_inicial-1)*$tamanho_pagina;
			$this->result=@pg_query($db->connect_id, $query);
		}
		$db->addquery($this->result);
		$this->curr_row=0;
	}
	
	function getrow() {
		$this->row=@pg_fetch_array($this->result, $this->curr_row);
		$this->curr_row++;
		return $this->row;
	}
	
	function field($field) {
		return $this->row[$field]	;
	}
	
	function fieldname($fieldnum) {
		return pg_field_name( $this->result, $fieldnum );
	}
	
	function firstrow() {
		$this->curr_row=0;
		return $this->getrow();
	}

	function skiprow($skiprow) {
		$this->curr_row=$skiprow;
	}
	
	function free() {
		return @pg_free_result($this->result);
	}
	
	//----- retorna a quantidade de registros
	function numrows() {
		return $this->numrows;
	}
	
	// ----- Retorna el número de campos
	function numfields()
	{
		return $this->numfields;
	}

	function totalpages() {
		return $this->totalpages;
	}
};
?>
