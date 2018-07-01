<?php
class Database{

	private static $database;
	private $pdo;

	private function __construct(){

		$db_user = "root";
		$db_pass = "";
		$db_name = 'mysql:host=localhost;dbname=rbc';

		try {

			$this->pdo = new PDO($db_name, $db_user, $db_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(PDO::ATTR_PERSISTENT, TRUE);
			$this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
			$this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
		} catch (Exception $e) {
			throw new PDOException($e->getMessage(), $e->getCode());
		}


	}

	public static function getInstance(){

		if(Database::$database == null){
			Database::$database = new Database();
		}

		return Database::$database;
	}



	public function setSearchPathTo( $schema, $public = true ) {

		$pathsTo = '';
		$sep = '';

		if ($public) {
			$pathsTo = 'public';
			$sep = ', ';
		}

		if (count($schema)) {
			$pathsTo.= $sep.implode(', ', $schema);
		}

		self::exec('SET search_path TO '.$pathsTo.';');
	}


	public function query( $sql, $sqlParam = array()) {

		$statement = $this->execute($sql, $sqlParam);


		return $statement->fetchAll(PDO::FETCH_ASSOC);

	}


	public function execute( $sql, $sqlParam = array()) {

		$parameters = array();
		$executarOci = false;

		$sqlParamExecute = array();
		foreach($sqlParam as $index => $value){
			$sqlParamExecute[$index] = urldecode($value);
		}

		$statement = $this->pdo->prepare($sql);

		try{
			$statement->execute($sqlParamExecute);
		}catch(PDOException $e){
			if($e->errorInfo[1] == '12899'){
				$column = $e->errorInfo[2];
				$columnError = explode("\"", $column);
				$coluna = $columnError[sizeof($columnError)-2];
				$parameters[":".$coluna] = substr($parameters[":".$coluna],0,250);

				$statement = $this->pdo->prepare($sql);
				$statement->execute($parameters);
			}else{
				throw $e;
			}
		}
		return $statement;
	}

	public function redirect ($controller = '', $action = '', $app = ''){

		$protocol 	= $this->getEnv('PROTOCOL');
		$host  		= $this->getEnv('SERVER_NAME');
		$uri   		= $this->getEnv('SCRIPT_NAME');

		$sessao = $_SESSION['FEED'];
		$feed = Feed::retrieveByNome($controller);
		$sessao[] = $feed->getId();
		$_SESSION['FEED'] = $sessao;

		if ($app) {
			$uri.= '/'.$app;
		} else if ($this->getApp() != $this->getConfig(self::KEY_APP_DEFAULT)) {
			$uri.= '/'.$this->getApp();
		}


		$extra = "";
		if ($controller) {
			$extra.= '/' . $controller;
		}

		if ($controller && $action) {
			$extra.= '/' . $action;
		}

		header("Location: ".$protocol."://".$host . $uri . $extra);

	}

	public function redirectSair(){

		$sessao = $_SESSION['FEED'];
		foreach ($sessao as $set) {
			$categoria = Categoria::retrieveByPk($set);
			$categoria->setValor($categoria->getValor()+1);
			$categoria->save();
		}

		session_destroy();

		header("Location: index.php");
	}

	public function beginTransaction(){
		$this->pdo->beginTransaction();
	}

	public function commit(){
		$this->pdo->commit();
	}

	public function rollBack(){
		$this->pdo->rollBack();
	}

	public static function retrieveClob($clob){
		return stream_get_contents($clob);
	}

	public function queryOne( $sql, $sqlParam = array()) {

		$exec = $this->query($sql, $sqlParam);

		if ($exec) {
			return current($exec);
		} else {
			return null;
		}

	}
}
