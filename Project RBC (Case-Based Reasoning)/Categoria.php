<?php

class Categoria{

	const CODIGO = 'CODIGO';
	const NOME = 'NOME';
	const VALOR = 'VALOR';
	const DESCRICAO = 'DESCRICAO';

	protected $codigo;
	protected $nome;
	protected $valor;
	protected $descricao;


	public function getCodigo() {
		return $this->codigo;
	}

	public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	public function getNome() {
		return $this->nome;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	public function setValor($valor) {
		$this->valor = $valor;
	}

	public function getValor() {
		return $this->valor;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	public function getDescricao() {
		return $this->descricao;
	}


	public function hydrate($array) {
		if (is_array($array) && !empty($array)) {
			foreach ($array as $k=>$v) {
				$methodName = $this->arrToMethodName($k);

				if (method_exists($this, $methodName)) {
					$this->$methodName($v);
				}
			}
		}
	}



	public static function retrieveCategorias() {

		$sqlParam = array();

		$db = Database::getInstance();
		$sql = "SELECT * FROM categoria " ;

		try{
			$result = $db->query($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {

				$retorno = array();
				foreach($result as $r){
					$obj = new Categoria();
					$obj->hydrate($r);

					$retorno[] = $obj;
				}


				return $retorno;
			}

		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
	}


	public static function retrieveByValor() {

		$sqlParam = array();

		$db = Database::getInstance();
		$sql = "SELECT * FROM categoria  ORDER BY valor" ;

		try{
			$result = $db->queryOne($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {
				$obj = new Categoria();
				$obj->hydrate($result);

				return $obj;
			}

		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
	}

	public static function retrieveByPk($idCategoria) {

		$sqlParam = array();
		$sqlParam[':idCategoria'] = $idCategoria;

		$db = Database::getInstance();
		$sql = "SELECT * FROM categoria  WHERE codigo = :idCategoria" ;

		try{
			$result = $db->queryOne($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {
				$obj = new Categoria();
				$obj->hydrate($result);

				return $obj;
			}

		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
	}

	public static function retrieveByFeed($idFeed) {

		$sqlParam = array();
		$sqlParam[':idFeed'] = $idFeed;

		$db = Database::getInstance();
		$sql = "SELECT c.* FROM categoria c INNER JOIN categoria_feed cf ON cf.idcategoria = c.codigo  WHERE cf.idfeed = :idFeed" ;


		try{
			$result = $db->queryOne($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {
				$obj = new Categoria();
				$obj->hydrate($result);

				return $obj;
			}

		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
	}

	public static function retrieveByNome($id) {

		$sqlParam = array();
		$sqlParam[':ID'] = $id;

		$db = Database::getInstance();
		$sql = "SELECT * FROM categoria WHERE idcategoria = :ID" ;

		try{
			$result = $db->queryOne($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {
				$obj = new Categoria();
				$obj->hydrate($result);

				return $obj;
			}

		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
	}

	private function arrToMethodName($arr) {
		$tmp = explode('_', $arr);

		$string = 'set';
		foreach($tmp as $valor) {
			$string.= ucfirst($valor);
		}

		return $string;
	}

	public function save() {

		$db = Database::getInstance();

		if ($this->codigo) {

			$sql = "
				UPDATE
					CATEGORIA
				SET
					CODIGO = :CODIGO
					, NOME = :NOME
					, DESCRICAO = :DESCRICAO
					, VALOR = :VALOR
				WHERE
					CODIGO = :CODIGO
			";

		} else {

			$sql = "
				INSERT INTO
					CATEGORIA
				(
					CODIGO
					, NOME
					, DESCRICAO
					, VALOR
				) VALUES (
					:CODIGO
					, :NOME
					, :DESCRICAO
					, :VALOR
				)
			";

		}

		$sqlParam = array();
		$sqlParam[':CODIGO'] = $this->codigo;
		$sqlParam[':NOME'] = $this->nome;
		$sqlParam[':DESCRICAO'] = $this->descricao;
		$sqlParam[':VALOR'] = $this->valor;

		try{
			$db->beginTransaction();
			$db->execute($sql, $sqlParam);

			$db->commit();
			return true;
		} catch (PDOException $e) {
			$db->rollBack();
			throw new PDOException($e->getMessage());
		}
	}

}