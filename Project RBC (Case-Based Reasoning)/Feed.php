<?php

class Feed{

	const CODIGO = 'CODIGO';
	const NOME = 'NOME';
	const TEXTO = 'TEXTO';
	const VALOR = 'VALOR';
	const FOTO = 'FOTO';


	protected $codigo;
	protected $nome;
	protected $texto;
	protected $valor;
	protected $foto;


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

	public function getTexto() {
		return $this->texto;
	}

	public function setTexto($texto) {
		$this->texto = $texto;
	}

	public function getValor() {
		return $this->valor;
	}

	public function setValor($valor) {
		$this->valor = $valor;
	}

	public function getFoto() {
		return $this->foto;
	}

	public function setFoto($foto) {
		$this->foto = $foto;
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

	public static function retrieveByNome($nome) {

			$sqlParam = array();
			$sqlParam[':NOME'] = $nome;

		$db = Database::getInstance();
		$sql = "SELECT * FROM feed WHERE nome = :NOME" ;

		try{
			$result = $db->queryOne($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {
				$obj = new Feed();
				$obj->hydrate($result);

				return $obj;
			}

		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
	}

	public static function retrieveByPk($codigo) {

			$sqlParam = array();
			$sqlParam[':codigo'] = $codigo;

		$db = Database::getInstance();
		$sql = "SELECT * FROM feed WHERE codigo = :codigo" ;

		try{
			$result = $db->queryOne($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {
				$obj = new Feed();
				$obj->hydrate($result);

				return $obj;
			}

		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
	}


	public static function retrieveByValor() {

		$sqlParam = array();

		$db = Database::getInstance();
		$sql = "SELECT * FROM feed ORDER BY VALOR LIMIT 1" ;

		try{
			$result = $db->queryOne($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {
				$obj = new Feed();
				$obj->hydrate($result);

				return $obj;
			}

		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
	}

	public static function retrieveByValorDesc() {

		$sqlParam = array();

		$db = Database::getInstance();
		$sql = "SELECT * FROM feed ORDER BY VALOR DESC LIMIT 1" ;

		try{
			$result = $db->queryOne($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {
				$obj = new Feed();
				$obj->hydrate($result);

				return $obj;
			}

		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
	}
	public static function retrieveFeed() {

			$sqlParam = array();


		$db = Database::getInstance();
		$sql = "SELECT * FROM feed " ;

		try{
			$result = $db->query($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {

				$retorno = array();
				foreach($result as $r){
					$obj = new Feed();
					$obj->hydrate($r);

					$retorno[] = $obj;
				}


				return $retorno;
			}

		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
	}

	public static function retrieveByCategoria($idCategoria) {

		$sqlParam = array();
		$sqlParam[':CODIGO'] = $idCategoria;

		$db = Database::getInstance();
		$sql = "SELECT * FROM feed f  INNER JOIN categoria_feed cf
				ON cf.idfeed = f.codigo
				WHERE cf.idcategoria = :CODIGO ORDER BY VALOR" ;

		try{
			$result = $db->query($sql, $sqlParam);

			if (is_null($result)) {
				return null;
			} else {

				$retorno = array();
				foreach($result as $r){
					$obj = new Feed();
					$obj->hydrate($r);

					$retorno[] = $obj;
				}


				return $retorno;
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
					feed
				SET
					codigo = :codigo
					, nome = :nome
					, texto = :texto
					, valor = :valor
					, foto = :foto
				WHERE
					codigo = :codigo
			";

		} else {

			$sql = "
				INSERT INTO
					feed
				(
					codigo
					, nome
					, texto
					, valor
					, foto
				) VALUES (
					:CODIGO
					, :nome
					, :texto
					, :valor
					, :foto
				)
			";

		}

		$sqlParam = array();
		$sqlParam[':codigo'] = $this->codigo;
		$sqlParam[':nome'] = $this->nome;
		$sqlParam[':texto'] = $this->texto;
		$sqlParam[':valor'] = $this->valor;
		$sqlParam[':foto'] = $this->foto;

		try{
			$db->beginTransaction();
			$db->execute($sql, $sqlParam);

			$db->commit();
			return true;
		} catch (PDOException $e){
			echo "<pre>";var_dump($e);die("</pre>");
			$db->rollBack();
			throw new PDOException($e->getMessage());
		}
	}

}