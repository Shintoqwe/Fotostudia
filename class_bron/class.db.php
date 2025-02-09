<?php
/**
 * @author col.shrapnel@gmail.com
 * @link http://phpfaq.ru/safemysql
 * 
 * Безопасный и удобный способ справиться с SQL-запросы, использующие заполнители типа-намекнул.
 * 
 * Ключевые особенности
 * - Набор вспомогательных функций, чтобы получить желаемый результат прямо из запроса, как в PEAR :: DB
 * - Метод условной построения запросов с использованием разбора (), чтобы построить запросы любой comlexity,
 * При сохранении дополнительную безопасность заполнителей
 * - Тип-намекнул заполнители
 * 
 *  Тип-намекнул заполнителей здорово, потому что
  * - Безопасно, так как любой другой [должным образом реализованы] заполнителей
  * - Нет необходимости в ручной побега или обязательными, не делает код очень сухой
  * - Позволяет поддерживать нестандартных типов, таких как идентификатор или массив, который экономит много боли в спине.
  *
  * Поддерживаемые заполнители на данный момент являются:
  *
 * ?s ("string")  - strings (also DATE, FLOAT and DECIMAL) строки /(также ДАТА, плавать и десятичной системе)
 * ?i ("integer") - the name says it all 
 * ?n ("name")    - identifiers (table and field names) 
 * ?a ("array")   - complex placeholder for IN() operator  (substituted with string of 'a','b','c' format, without parentesis)
 * ?u ("update")  - complex placeholder for SET operator (substituted with string of `field`='value',`field`='value' format)
 * and
 * ?p ("parsed") - special type placeholder, for inserting already parsed statements without any processing, to avoid double parsing.
 * 
 * Некоторые примеры:
 *
 * $db = new SafeMySQL(); // with default settings
 * 
 * $opts = array(
 *		'user'    => 'user',
 *		'pass'    => 'pass',
 *		'db'      => 'db',
 *		'charset' => 'latin1'
 * );
 * $db = new SafeMySQL($opts); // with some of the default settings overwritten
 * 
 * 
 * $name = $db->getOne('SELECT name FROM table WHERE id = ?i',$_GET['id']);
 * $data = $db->getInd('id','SELECT * FROM ?n WHERE id IN ?a','table', array(1,2));
 * $data = $db->getAll("SELECT * FROM ?n WHERE mod=?s LIMIT ?i",$table,$mod,$limit);
 *
 * $ids  = $db->getCol("SELECT id FROM tags WHERE tagname = ?s",$tag);
 * $data = $db->getAll("SELECT * FROM table WHERE category IN (?a)",$ids);
 * 
 * $data = array('offers_in' => $in, 'offers_out' => $out);
 * $sql  = "INSERT INTO stats SET pid=?i,dt=CURDATE(),?u ON DUPLICATE KEY UPDATE ?u";
 * $db->query($sql,$pid,$data,$data);
 * 
 * if ($var === NULL) {
 *     $sqlpart = "field is NULL";
 * } else {
 *     $sqlpart = $db->parse("field = ?s", $var);
 * }
 * $data = $db->getAll("SELECT * FROM table WHERE ?p", $bar, $sqlpart);
 * 
 */
class SafeMySQL
{
	private $conn;
	private $stats;
	private $emode;
	private $exname;
	private $defaults = array(
		'host'      => 'localhost',
		'user'      => 'root',
		'pass'      => '',
		'db'        => 'foto_gal',
		'port'      => NULL,
		'socket'    => NULL,
		'pconnect'  => FALSE,
		'charset'   => 'utf8',
		'errmode'   => 'error', //or exception
		'exception' => 'Exception', //Exception class name
	);
	const RESULT_ASSOC = MYSQLI_ASSOC;
	const RESULT_NUM   = MYSQLI_NUM;
	function __construct($opt = array())
	{
		$opt = array_merge($this->defaults,$opt);
		$this->emode  = $opt['errmode'];
		$this->exname = $opt['exception'];
		if ($opt['pconnect'])
		{
			$opt['host'] = "p:".$opt['host'];
		}
		@$this->conn = mysqli_connect($opt['host'], $opt['user'], $opt['pass'], $opt['db'], $opt['port'], $opt['socket']);
		if ( !$this->conn )
		{
			$this->error(mysqli_connect_errno()." ".mysqli_connect_error());
		}
		mysqli_set_charset($this->conn, $opt['charset']) or $this->error(mysqli_error($this->conn));
		unset($opt); // I am paranoid
	}
	/**
	 * Обычная функция выполнить запрос с заполнителями.Mysqli_query оболочка с поддержкой заполнителей
*
* Примеры:
	 * $db->query("DELETE FROM table WHERE id=?i", $id);
	 *
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return resource|FALSE whatever mysqli_query returns
	 */
	public function query()
	{	
		return $this->rawQuery($this->prepareQuery(func_get_args()));
	}
	/**
	 * Обычные функции для извлечения одной строки. 
	 * 
	 * @param resource $result - myqli result
	 * @param int $mode - optional fetch mode, RESULT_ASSOC|RESULT_NUM, default RESULT_ASSOC
	 * @return array|FALSE whatever mysqli_fetch_array returns
	 */
	public function fetch($result,$mode=self::RESULT_ASSOC)
	{
		return mysqli_fetch_array($result, $mode);
	}
	/**
	 * Обычные функции получить число пострадавших строк.
	 * 
	 * @return int whatever mysqli_affected_rows returns
	 */
	public function affectedRows()
	{
		return mysqli_affected_rows ($this->conn);
	}
	/**
	 * Обычные функцию, чтобы получить последнюю вставки ID. 
	 * 
	 * @return int whatever mysqli_insert_id returns
	 */
	public function insertId()
	{
		return mysqli_insert_id($this->conn);
	}
	/**
	 * Обычные функцию, чтобы получить количество строк в наборе результатов.
	 * 
	 * @param resource $result - myqli result
	 * @return int whatever mysqli_num_rows returns
	 */
	public function numRows($result)
	{
		return mysqli_num_rows($result);
	}
	/**
	 * Обычные функции, чтобы освободить результирующего. 
	 */
	public function free($result)
	{
		mysqli_free_result($result);
	}
	/**
	 * Помощник функцию, чтобы получить скалярное значение прямо из запросов и необязательных аргументов
*
* Примеры:
	 * $name = $db->getOne("SELECT name FROM table WHERE id=1");
	 * $name = $db->getOne("SELECT name FROM table WHERE id=?i", $id);
	 *
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return string|FALSE either first column of the first row of resultset or FALSE if none found
	 */
	public function getOne()
	{
		$query = $this->prepareQuery(func_get_args());
		if ($res = $this->rawQuery($query))
		{
			$row = $this->fetch($res);
			if (is_array($row)) {
				return reset($row);
			}
			$this->free($res);
		}
		return FALSE;
	}
	/**
	 * Helper function to get single row right out of query and optional arguments
	 * 
	 * Examples:
	 * $data = $db->getRow("SELECT * FROM table WHERE id=1");
	 * $data = $db->getOne("SELECT * FROM table WHERE id=?i", $id);
	 *
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return array|FALSE either associative array contains first row of resultset or FALSE if none found
	 */
	public function getRow()
	{
		$query = $this->prepareQuery(func_get_args());
		if ($res = $this->rawQuery($query)) {
			$ret = $this->fetch($res);
			$this->free($res);
			return $ret;
		}
		return FALSE;
	}
	/**
	 * Helper function to get single column right out of query and optional arguments
	 * 
	 * Examples:
	 * $ids = $db->getCol("SELECT id FROM table WHERE cat=1");
	 * $ids = $db->getCol("SELECT id FROM tags WHERE tagname = ?s", $tag);
	 *
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return array|FALSE either enumerated array of first fields of all rows of resultset or FALSE if none found
	 */
	public function getCol()
	{
		$ret   = array();
		$query = $this->prepareQuery(func_get_args());
		if ( $res = $this->rawQuery($query) )
		{
			while($row = $this->fetch($res))
			{
				$ret[] = reset($row);
			}
			$this->free($res);
		}
		return $ret;
	}
	/**
	 * Helper function to get all the rows of resultset right out of query and optional arguments
	 * 
	 * Examples:
	 * $data = $db->getAll("SELECT * FROM table");
	 * $data = $db->getAll("SELECT * FROM table LIMIT ?i,?i", $start, $rows);
	 *
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return array enumerated 2d array contains the resultset. Empty if no rows found. 
	 */
	public function getAll()
	{
		$ret   = array();
		$query = $this->prepareQuery(func_get_args());
		if ( $res = $this->rawQuery($query) )
		{
			while($row = $this->fetch($res))
			{
				$ret[] = $row;
			}
			$this->free($res);
		}
		return $ret;
	}
	/**
	 * Helper function to get all the rows of resultset into indexed array right out of query and optional arguments
	 * 
	 * Examples:
	 * $data = $db->getInd("id", "SELECT * FROM table");
	 * $data = $db->getInd("id", "SELECT * FROM table LIMIT ?i,?i", $start, $rows);
	 *
	 * @param string $index - name of the field which value is used to index resulting array
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return array - associative 2d array contains the resultset. Empty if no rows found. 
	 */
	public function getInd()
	{
		$args  = func_get_args();
		$index = array_shift($args);
		$query = $this->prepareQuery($args);
		$ret = array();
		if ( $res = $this->rawQuery($query) )
		{
			while($row = $this->fetch($res))
			{
				$ret[$row[$index]] = $row;
			}
			$this->free($res);
		}
		return $ret;
	}
	/**
	 * Helper function to get a dictionary-style array right out of query and optional arguments
	 * 
	 * Examples:
	 * $data = $db->getIndCol("name", "SELECT name, id FROM cities");
	 *
	 * @param string $index - name of the field which value is used to index resulting array
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return array - associative array contains key=value pairs out of resultset. Empty if no rows found. 
	 */
	public function getIndCol()
	{
		$args  = func_get_args();
		$index = array_shift($args);
		$query = $this->prepareQuery($args);
		$ret = array();
		if ( $res = $this->rawQuery($query) )
		{
			while($row = $this->fetch($res))
			{
				$key = $row[$index];
				unset($row[$index]);
				$ret[$key] = reset($row);
			}
			$this->free($res);
		}
		return $ret;
	}
	/**
	 * Функция для разбора заполнители либо в полном запроса или части запроса
* В отличие от отечественных подготовленных заявлений, позволяет любой части запроса, который будет обработан
*
* Полезно для отладки
* И чрезвычайно полезна для условного построения запросов
* Как добавление различных частей запроса, используя циклы, условия и т.д.
* Уже разобранные детали должны быть добавлены с помощью его стороны? Заполнителя
*
* Примеры:
	 * $query = $db->parse("SELECT * FROM table WHERE foo=?s AND bar=?s", $foo, $bar);
	 * echo $query;
	 * 
	 * if ($foo) {
	 *     $qpart = $db->parse(" AND foo=?s", $foo);
	 * }
	 * $data = $db->getAll("SELECT * FROM table WHERE bar=?s ?p", $bar, $qpart);
	 *
	 * @param string $query - whatever expression contains placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the expression
	 * @return string - initial expression with placeholders substituted with data. 
	 */
	public function parse()
	{
		return $this->prepareQuery(func_get_args());
	}
	/**
	 * function to implement whitelisting feature
	 * sometimes we can't allow a non-validated user-supplied data to the query even through placeholder
	 * especially if it comes down to SQL OPERATORS
	 * 
	 * Example:
	 *
	 * $order = $db->whiteList($_GET['order'], array('name','price'));
	 * $dir   = $db->whiteList($_GET['dir'],   array('ASC','DESC'));
	 * if (!$order || !dir) {
	 *     throw new http404(); //non-expected values should cause 404 or similar response
	 * }
	 * $sql  = "SELECT * FROM table ORDER BY ?p ?p LIMIT ?i,?i"
	 * $data = $db->getArr($sql, $order, $dir, $start, $per_page);
	 * 
	 * @param string $iinput   - field name to test
	 * @param  array  $allowed - an array with allowed variants
	 * @param  string $default - optional variable to set if no match found. Default to false.
	 * @return string|FALSE    - either sanitized value or FALSE
	 */
	public function whiteList($input,$allowed,$default=FALSE)
	{
		$found = array_search($input,$allowed);
		return ($found === FALSE) ? $default : $allowed[$found];
	}
	/**
	 * function to filter out arrays, for the whitelisting purposes
	 * useful to pass entire superglobal to the INSERT or UPDATE query
	 * OUGHT to be used for this purpose, 
	 * as there could be fields to which user should have no access to.
	 * 
	 * Example:
	 * $allowed = array('title','url','body','rating','term','type');
	 * $data    = $db->filterArray($_POST,$allowed);
	 * $sql     = "INSERT INTO ?n SET ?u";
	 * $db->query($sql,$table,$data);
	 * 
	 * @param  array $input   - source array
	 * @param  array $allowed - an array with allowed field names
	 * @return array filtered out source array
	 */
	public function filterArray($input,$allowed)
	{
		foreach(array_keys($input) as $key )
		{
			if ( !in_array($key,$allowed) )
			{
				unset($input[$key]);
			}
		}
		return $input;
	}
	/**
	 * Function to get last executed query. 
	 * 
	 * @return string|NULL either last executed query or NULL if were none
	 */
	public function lastQuery()
	{
		$last = end($this->stats);
		return $last['query'];
	}
	/**
	 * Function to get all query statistics. 
	 * 
	 * @return array contains all executed queries with timings and errors
	 */
	public function getStats()
	{
		return $this->stats;
	}
	/**
	 * private function which actually runs a query against Mysql server.
	 * also logs some stats like profiling info and error message
	 * 
	 * @param string $query - a regular SQL query
	 * @return mysqli result resource or FALSE on error
	 */
	private function rawQuery($query)
	{
		$start = microtime(TRUE);
		$res   = mysqli_query($this->conn, $query);
		$timer = microtime(TRUE) - $start;
		$this->stats[] = array(
			'query' => $query,
			'start' => $start,
			'timer' => $timer,
		);
		if (!$res)
		{
			$error = mysqli_error($this->conn);
			
			end($this->stats);
			$key = key($this->stats);
			$this->stats[$key]['error'] = $error;
			$this->cutStats();
			
			$this->error("$error. Full query: [$query]");
		}
		$this->cutStats();
		return $res;
	}
	private function prepareQuery($args)
	{
		$query = '';
		$raw   = array_shift($args);
		$array = preg_split('~(\?[nsiuap])~u',$raw,null,PREG_SPLIT_DELIM_CAPTURE);
		$anum  = count($args);
		$pnum  = floor(count($array) / 2);
		if ( $pnum != $anum )
		{
			$this->error("Количество аргументов ($anum) не соответствует числу placeholders ($pnum) в [$raw]");
		}
		foreach ($array as $i => $part)
		{
			if ( ($i % 2) == 0 )
			{
				$query .= $part;
				continue;
			}
			$value = array_shift($args);
			switch ($part)
			{
				case '?n':
					$part = $this->escapeIdent($value);
					break;
				case '?s':
					$part = $this->escapeString($value);
					break;
				case '?i':
					$part = $this->escapeInt($value);
					break;
				case '?a':
					$part = $this->createIN($value);
					break;
				case '?u':
					$part = $this->createSET($value);
					break;
				case '?p':
					$part = $value;
					break;
			}
			$query .= $part;
		}
		return $query;
	}
	private function escapeInt($value)
	{
		if ($value === NULL)
		{
			return 'NULL';
		}
		if(!is_numeric($value))
		{
			$this->error("Integer (?i) placeholder ожидает числовое значение, ".gettype($value)." given");
			return FALSE;
		}
		if (is_float($value))
		{
			$value = number_format($value, 0, '.', ''); // may lose precision on big numbers
		} 
		return $value;
	}
	private function escapeString($value)
	{
		if ($value === NULL)
		{
			return 'NULL';
		}
		return	"'".mysqli_real_escape_string($this->conn,$value)."'";
	}
	private function escapeIdent($value)
	{
		if ($value)
		{
			return "`".str_replace("`","``",$value)."`";
		} else {
			$this->error("Пустое значение для идентификатора (?n) placeholder");
		}
	}
	private function createIN($data)
	{
		if (!is_array($data))
		{
			$this->error("Значение для IN (?a) placeholder должен быть массивом");
			return;
		}
		if (!$data)
		{
			return 'NULL';
		}
		$query = $comma = '';
		foreach ($data as $value)
		{
			$query .= $comma.$this->escapeString($value);
			$comma  = ",";
		}
		return $query;
	}
	private function createSET($data)
	{
		if (!is_array($data))
		{
			$this->error("SET (?u) placeholder ожидается array, ".gettype($data)." given");
			return;
		}
		if (!$data)
		{
			$this->error("Пустой массив для SET (?u) placeholder");
			return;
		}
		$query = $comma = '';
		foreach ($data as $key => $value)
		{
			$query .= $comma.$this->escapeIdent($key).'='.$this->escapeString($value);
			$comma  = ",";
		}
		return $query;
	}
	private function error($err)
	{
		$err  = __CLASS__.": ".$err;
		if ( $this->emode == 'error' )
		{
			$err .= ". Ошибка начата в ".$this->caller().", бросил";
			trigger_error($err,E_USER_ERROR);
		} else {
			throw new $this->exname($err);
		}
	}
	private function caller()
	{
		$trace  = debug_backtrace();
		$caller = '';
		foreach ($trace as $t)
		{
			if ( isset($t['class']) && $t['class'] == __CLASS__ )
			{
				$caller = $t['file']." on line ".$t['line'];
			} else {
				break;
			}
		}
		return $caller;
	}
	/**
	 * On a long run we can eat up too much memory with mere statsistics
	 * Let's keep it at reasonable size, leaving only last 100 entries.
	 */
	private function cutStats()
	{
		if ( count($this->stats) > 100 )
		{
			reset($this->stats);
			$first = key($this->stats);
			unset($this->stats[$first]);
		}
	}
}

function getRooms($date,$db){
	$all=$db->getRow("SELECT * FROM bron_time WHERE date=?s",$date);
	if(!$all){$db->query("INSERT INTO bron_time(id_room,date,seans_1,seans_2,seans_3,seans_4,seans_5,seans_6,seans_7,seans_8,seans_9) VALUES('1','".$date."','0','0','0','0','0','0','0','0','0'),('2','".$date."','0','0','0','0','0','0','0','0','0'),('3','".$date."','0','0','0','0','0','0','0','0','0')");}
	$timtm=0;
	if(date("w",time()+$timtm)==0){$W='<span style="color:#AB4490;">вс</span>';}
	if(date("w",time()+$timtm)==1){$W='пн';}
	if(date("w",time()+$timtm)==2){$W='вт';}
	if(date("w",time()+$timtm)==3){$W='ср';}
	if(date("w",time()+$timtm)==4){$W='чт';}
	if(date("w",time()+$timtm)==5){$W='пт';}
	if(date("w",time()+$timtm)==6){$W='<span style="color:#AB4490;">сб</span>';}
	if(date("d.m.Y")==$date){$dop_cl='fiol';}else{$dop_cl='';}
	$result='<tr><td style="width: 35%;height:75px;"><div class="fiol" style="margin-top: -10px;">'.date("d.m.Y").'<br><span style="font-size:12px;">Сегодня</span></td>
	<td><span onClick="days(\''.date("d.m.Y").'\');" class="days_b '.$dop_cl.'">'.date("d").'</span><div class="top_w">'.$W.'</div></td>';
$timtm=86400;
	$i=1;
	for($i;$i<9;$i++){
	if(date("d.m.Y",time()+$timtm)==$date){$dop_cl='fiol';}else{$dop_cl='';}
	$result.='<td><span onClick="days(\''.date("d.m.Y",time()+$timtm).'\');" class="days_b '.$dop_cl.'">'.date("d",time()+$timtm).'</span>';
	if(date("w",time()+$timtm)==0){$W='<span style="color:#AB4490;">вс</span>';}
	if(date("w",time()+$timtm)==1){$W='пн';}
	if(date("w",time()+$timtm)==2){$W='вт';}
	if(date("w",time()+$timtm)==3){$W='ср';}
	if(date("w",time()+$timtm)==4){$W='чт';}
	if(date("w",time()+$timtm)==5){$W='пт';}
	if(date("w",time()+$timtm)==6){$W='<span style="color:#AB4490;">сб</span>';}
	$result.='<div class="top_w">'.$W.'</div></td>';
	$timtm=$timtm+86400;
	}
	$result.='</tr>';
	$rr=1;
	$rooms=$db->getAll("SELECT * FROM bron");
	foreach($rooms as $keys=>$vals){
$room=$db->getRow("SELECT * FROM bron_time WHERE date=?s AND id_room=?i",$date,$vals['id']);
$color4ik='rgba(81, 27, 66, 0.85);border:none;cursor:default';
if($room['seans_1']==1){$seans_1='background:'.$color4ik.';';}
if($room['seans_2']==1){$seans_2='background:'.$color4ik.';';}
if($room['seans_3']==1){$seans_3='background:'.$color4ik.';';}
if($room['seans_4']==1){$seans_4='background:'.$color4ik.';';}
if($room['seans_5']==1){$seans_5='background:'.$color4ik.';';}
if($room['seans_6']==1){$seans_6='background:'.$color4ik.';';}
if($room['seans_7']==1){$seans_7='background:'.$color4ik.';';}
if($room['seans_8']==1){$seans_8='background:'.$color4ik.';';}
if($room['seans_9']==1){$seans_9='background:'.$color4ik.';';}
if(date("d.m.Y")==$date){
	$yy=time()+1800;
if($vals['seans_1']<=date("H:i",$yy)){$seans_1='background:'.$color4ik.';';}
if($vals['seans_2']<=date("H:i",$yy)){$seans_2='background:'.$color4ik.';';}
if($vals['seans_3']<=date("H:i",$yy)){$seans_3='background:'.$color4ik.';';}
if($vals['seans_4']<=date("H:i",$yy)){$seans_4='background:'.$color4ik.';';}
if($vals['seans_5']<=date("H:i",$yy)){$seans_5='background:'.$color4ik.';';}
if($vals['seans_6']<=date("H:i",$yy)){$seans_6='background:'.$color4ik.';';}
if($vals['seans_7']<=date("H:i",$yy)){$seans_7='background:'.$color4ik.';';}
if($vals['seans_8']<=date("H:i",$yy)){$seans_8='background:'.$color4ik.';';}
if($vals['seans_9']<=date("H:i",$yy)){$seans_9='background:'.$color4ik.';';}
}
if($rr==1){$da='приют';}
if($rr==2){$da='во-все-тяжкие';}
if($rr==3){$da='релакс-бар';}$rr++;
$result.='<tr><td style="text-align:left;"><a href="/'.$da.'/" style="text-decoration:none;color:#fff;"><div class="box-1"><div class="btn_q btn-one"><span>'.$vals['name_room'].'</span></div></div></a></td>
<td onClick="bron_okno(\''.$date.'\',\'seans_1\',\''.$vals['id'].'\',\''.$vals['name_room'].'\',\''.$vals['seans_1'].'\');"><div class="bor_bron" style="'.$seans_1.'" >'.$vals['seans_1'].'</div></td>
<td onClick="bron_okno(\''.$date.'\',\'seans_2\',\''.$vals['id'].'\',\''.$vals['name_room'].'\',\''.$vals['seans_2'].'\');"><div class="bor_bron" style="'.$seans_2.'">'.$vals['seans_2'].'</div></td>
<td onClick="bron_okno(\''.$date.'\',\'seans_3\',\''.$vals['id'].'\',\''.$vals['name_room'].'\',\''.$vals['seans_3'].'\');"><div class="bor_bron" style="'.$seans_3.'">'.$vals['seans_3'].'</div></td>
<td onClick="bron_okno(\''.$date.'\',\'seans_4\',\''.$vals['id'].'\',\''.$vals['name_room'].'\',\''.$vals['seans_4'].'\');"><div class="bor_bron" style="'.$seans_4.'">'.$vals['seans_4'].'</div></td>
<td onClick="bron_okno(\''.$date.'\',\'seans_5\',\''.$vals['id'].'\',\''.$vals['name_room'].'\',\''.$vals['seans_5'].'\');"><div class="bor_bron" style="'.$seans_5.'" >'.$vals['seans_5'].'</div></td>
<td onClick="bron_okno(\''.$date.'\',\'seans_6\',\''.$vals['id'].'\',\''.$vals['name_room'].'\',\''.$vals['seans_6'].'\');"><div class="bor_bron" style="'.$seans_6.'">'.$vals['seans_6'].'</div></td>
<td onClick="bron_okno(\''.$date.'\',\'seans_7\',\''.$vals['id'].'\',\''.$vals['name_room'].'\',\''.$vals['seans_7'].'\');"><div class="bor_bron" style="'.$seans_7.'">'.$vals['seans_7'].'</div></td>
<td onClick="bron_okno(\''.$date.'\',\'seans_8\',\''.$vals['id'].'\',\''.$vals['name_room'].'\',\''.$vals['seans_8'].'\');"><div class="bor_bron" style="'.$seans_8.'">'.$vals['seans_8'].'</div></td>
<td onClick="bron_okno(\''.$date.'\',\'seans_9\',\''.$vals['id'].'\',\''.$vals['name_room'].'\',\''.$vals['seans_9'].'\');"><div class="bor_bron" style="'.$seans_9.'">'.$vals['seans_9'].'</div></td>
</tr>';
$seans_1=0;$seans_2=0;$seans_3=0;$seans_4=0;$seans_5=0;$seans_6=0;$seans_7=0;$seans_8=0;$seans_9=0;
}
return $result;
}

function getRooms_admin($date,$db){
	$all=$db->getRow("SELECT * FROM bron_time WHERE date=?s",$date);
	if(!$all){$db->query("INSERT INTO bron_time(id_room,date,seans_1,seans_2,seans_3,seans_4,seans_5,seans_6,seans_7,seans_8,seans_9) VALUES('1','".$date."','0','0','0','0','0','0','0','0','0'),('2','".$date."','0','0','0','0','0','0','0','0','0'),('3','".$date."','0','0','0','0','0','0','0','0','0')");}
	$timtm=0;
	if(date("w",time()+$timtm)==0){$W='<span style="color:#AB4490;">вс</span>';}
	if(date("w",time()+$timtm)==1){$W='пн';}
	if(date("w",time()+$timtm)==2){$W='вт';}
	if(date("w",time()+$timtm)==3){$W='ср';}
	if(date("w",time()+$timtm)==4){$W='чт';}
	if(date("w",time()+$timtm)==5){$W='пт';}
	if(date("w",time()+$timtm)==6){$W='<span style="color:#AB4490;">сб</span>';}
	if(date("d.m.Y")==$date){$dop_cl='fiol';}else{$dop_cl='';}
	$result='<tr><td style="width: 35%;height:75px;"><div class="fiol" style="margin-top: -10px;">'.date("d.m.Y").'<br><span style="font-size:12px;">Сегодня</span></td>
	<td><span onClick="days(\''.date("d.m.Y").'\');" class="days_b '.$dop_cl.'">'.date("d").'</span><div class="top_w">'.$W.'</div></td>';
$timtm=86400;
	$i=1;
	for($i;$i<9;$i++){
	if(date("d.m.Y",time()+$timtm)==$date){$dop_cl='fiol';}else{$dop_cl='';}
	$result.='<td><span onClick="days(\''.date("d.m.Y",time()+$timtm).'\');" class="days_b '.$dop_cl.'">'.date("d",time()+$timtm).'</span>';
	if(date("w",time()+$timtm)==0){$W='<span style="color:#AB4490;">вс</span>';}
	if(date("w",time()+$timtm)==1){$W='пн';}
	if(date("w",time()+$timtm)==2){$W='вт';}
	if(date("w",time()+$timtm)==3){$W='ср';}
	if(date("w",time()+$timtm)==4){$W='чт';}
	if(date("w",time()+$timtm)==5){$W='пт';}
	if(date("w",time()+$timtm)==6){$W='<span style="color:#AB4490;">сб</span>';}
	$result.='<div class="top_w">'.$W.'</div></td>';
	$timtm=$timtm+86400;
	}
	$result.='</tr>';
	
	$rooms=$db->getAll("SELECT * FROM bron");
	foreach($rooms as $keys=>$vals){
$room=$db->getRow("SELECT * FROM bron_time WHERE date=?s AND id_room=?i",$date,$vals['id']);
$color4ik='rgba(81, 27, 66, 0.85);border:none';
if($room['seans_1']==1){$seans_1='background:'.$color4ik.';';}
if($room['seans_2']==1){$seans_2='background:'.$color4ik.';';}
if($room['seans_3']==1){$seans_3='background:'.$color4ik.';';}
if($room['seans_4']==1){$seans_4='background:'.$color4ik.';';}
if($room['seans_5']==1){$seans_5='background:'.$color4ik.';';}
if($room['seans_6']==1){$seans_6='background:'.$color4ik.';';}
if($room['seans_7']==1){$seans_7='background:'.$color4ik.';';}
if($room['seans_8']==1){$seans_8='background:'.$color4ik.';';}
if($room['seans_9']==1){$seans_9='background:'.$color4ik.';';}
$result.='<tr><td style="text-align:left;"><div class="fiol">'.$vals['name_room'].'<br><span style="font-size:13px;">2-4 человека, до 60 мин</span></div></td>
<td onClick="bron(\''.$date.'\',\'seans_1\',\''.$vals['id'].'\');"><div class="bor_bron" style="'.$seans_1.'" >'.$vals['seans_1'].'</div></td>
<td onClick="bron(\''.$date.'\',\'seans_2\',\''.$vals['id'].'\');"><div class="bor_bron" style="'.$seans_2.'">'.$vals['seans_2'].'</div></td>
<td onClick="bron(\''.$date.'\',\'seans_3\',\''.$vals['id'].'\');"><div class="bor_bron" style="'.$seans_3.'">'.$vals['seans_3'].'</div></td>
<td onClick="bron(\''.$date.'\',\'seans_4\',\''.$vals['id'].'\');"><div class="bor_bron" style="'.$seans_4.'">'.$vals['seans_4'].'</div></td>
<td onClick="bron(\''.$date.'\',\'seans_5\',\''.$vals['id'].'\');"><div class="bor_bron" style="'.$seans_5.'" >'.$vals['seans_5'].'</div></td>
<td onClick="bron(\''.$date.'\',\'seans_6\',\''.$vals['id'].'\');"><div class="bor_bron" style="'.$seans_6.'">'.$vals['seans_6'].'</div></td>
<td onClick="bron(\''.$date.'\',\'seans_7\',\''.$vals['id'].'\');"><div class="bor_bron" style="'.$seans_7.'">'.$vals['seans_7'].'</div></td>
<td onClick="bron(\''.$date.'\',\'seans_8\',\''.$vals['id'].'\');"><div class="bor_bron" style="'.$seans_8.'">'.$vals['seans_8'].'</div></td>
<td onClick="bron(\''.$date.'\',\'seans_9\',\''.$vals['id'].'\');"><div class="bor_bron" style="'.$seans_9.'">'.$vals['seans_9'].'</div></td>
</tr>';
$seans_1=0;$seans_2=0;$seans_3=0;$seans_4=0;$seans_5=0;$seans_6=0;$seans_7=0;$seans_8=0;$seans_9=0;
}
return $result;
}


function getOtziv(){
	$db=func_get_arg(1);
		$numar=func_num_args();
		$param=func_get_arg(0);
		if($numar>2){
		$ar1=func_get_arg(2);			
			$bade_menu = $db->getAll("SELECT * FROM $param ORDER by date DESC Limit ?i",$ar1);
			$param=func_get_arg(2);
		}else{
		$bade_menu = $db->getAll("SELECT * FROM $param ORDER by date DESC");
		}
		if(empty($bade_menu)){
			$file_menu='Запрос к базе данных не принес результата!';
		}else{
			$file_menu='';
				foreach($bade_menu as $key=>$val){
				$file_menu.=file_get_contents ('./old_otziv.php');
					foreach($val as $key_i=>$val_i){
					$file_menu=str_replace('{'.$key_i.'}',$val_i,$file_menu);
				}
			}
		}
	return $file_menu;
	}