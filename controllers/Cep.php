<?php
namespace controllers{
	/*
	Classe produto
	*/
	class Cep{
		//Atributo para banco de dados
		private $PDO;

		/*
		__construct
		Conectando ao banco de dados
		*/
		function __construct(){
			$this->PDO = new \PDO('mysql:host=localhost;dbname=cepbd', 'root', ''); //Conexão
			$this->PDO->setAttribute( \PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION ); //habilitando erros do PDO
		}
		/*
		lista
		*/
		public function lista(){
			global $app;
			$sth = $this->PDO->prepare("SELECT * FROM endereco");
			$sth->execute();
			$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
			$app->render('default.php',["data"=>$result],200); 
		}
		/*
		get
		param $codigo
		Pega CEP pelo codigo
		*/
		public function get($codigo){
			global $app;
			$sth = $this->PDO->prepare("SELECT * FROM endereco WHERE id = :codigo");
			$sth ->bindValue(':codigo',$codigo);
			$sth->execute();
			$result = $sth->fetch(\PDO::FETCH_ASSOC);
			$app->render('default.php',["data"=>$result],200); 
		}

		/*
		nova
		Cadastra CEP
		*/
		public function inserir(){

			global $app;
			$dados = json_decode($app->request->getBody(), true);
			$dados = (is_array($dados)==0)? $_POST : $dados;
			$keys = array_keys($dados); //pega as chaves do array
		
			//usar prepare e bindvalue é bom para evitar sql injection
			$sth = $this->PDO->prepare("INSERT INTO endereco (".implode(',', $keys).") VALUES (:".implode(",:", $keys).")");
			foreach ($dados as $key => $value) {
				$sth ->	bindValue(':'.$key,$value);
			}
			$sth->execute();
			//Retorna o codigo inserir
			//$app->render('default.php',["data"=>['id'=>$this->PDO->lastInsertcodigo()]],200); 
			$app->render('default.php',["data"=>$sth]); 
		}

		/*
		editar
		param $codigo
		Editando CEP
		*/
		public function editar($codigo){
			global $app;
			$dados = json_decode($app->request->getBody(), true);
			$dados = (sizeof($dados)==0)? $_POST : $dados;
			$sets = [];
			foreach ($dados as $key => $VALUES) {
				$sets[] = $key." = :".$key;
			}

			$sth = $this->PDO->prepare("UPDATE endereco SET ".implode(',', $sets)." WHERE id = :codigo");
			$sth ->bindValue(':codigo',$codigo);
			foreach ($dados as $key => $value) {
				$sth ->bindValue(':'.$key,$value);
			}
			//Retorna status da edição
			$app->render('default.php',["data"=>['status'=>$sth->execute()==1]],200); 
		}

		/*
		excluir
		param $codigo
		Excluindo CEP
		*/
		public function excluir($codigo){
			global $app;
			$sth = $this->PDO->prepare("DELETE FROM endereco WHERE id = :codigo");
			$sth ->bindValue(':codigo',$codigo);
			$app->render('default.php',["data"=>['status'=>$sth->execute()==1]],200); 
		}
	}
}
