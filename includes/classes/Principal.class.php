<?php

	class Principal{
	
		private $link_conexao;
		private $tabela;
		private $dados = array();
		private $qtde;
		private $primary_key;
		private $valores = array(); 
		
		function __construct($tab){
			$this->link_conexao = conexao_mysql();
			$this->tabela = $tab;
		}
		
		function fechar_conexao(){
			$this->link_conexao=null;
		}
		
		public function getListar($condicao='',$campos=''){  				
			$sql = "select *from ".$this->tabela." where 1";
			
			if((strlen($campos)>0) && (strlen($condicao)>0))
				$sql = "select ".$campos." from ".$this->tabela." where 1 ".$condicao;
			else{
				if(strlen($condicao)>0)
					$sql = "select *from ".$this->tabela." where 1 ".$condicao;
				if(strlen($campos)>0)
					$sql = "select ".$campos." from ".$this->tabela." where 1";
			}
			
			$operacao = $this->link_conexao->prepare($sql);
			$pesquisar = $operacao->execute($this->dados);
			$resultado = $operacao->fetchAll();
			
			if(count($resultado)>0){
				foreach($resultado as $valores){
					$aux[] = $valores;
				}
			}
			else
				$aux[] = "";
				
			self::setQtde(count($resultado));
			
			return $aux;		
		}
		
		public function getBuscarPorId($id='',$campos=''){  				
			self::setPrimaryKey();
		
			$sql = "select *from ".$this->tabela." where 1 and ".$this->primary_key."=?";
			
			if((strlen($campos)>0))
				$sql = "select ".$campos." from ".$this->tabela." where 1 and ".$this->primary_key."=?";
			
			$operacao = $this->link_conexao->prepare($sql);
			$pesquisar = $operacao->execute(array($id));
			$resultado = $operacao->fetchAll();
			
			if(count($resultado)>0){
				foreach($resultado as $values){
					$aux[] = $values;
				}
			}
			else
				$aux[] = "";
				
			self::setQtde(count($resultado));
			
			return $aux;
		}
		
		public function salvar(){
			$sql = "insert into ".$this->tabela." (";
			$sql.=self::prepararCampos("campo");
			$sql.=") values (";
			$sql.=self::prepararCampos("valor");
			$sql.=");";
			
			$operacao = $this->link_conexao->prepare($sql);
			$inserir = $operacao->execute($this->valores);	
		}
		
		public function atualizar($campo,$chaves){
			$sql = "update ".$this->tabela." set ";
			$sql.=self::prepararCampos("atualizar");
			$aux="";
			if(is_array($chaves)){
				foreach($chaves as $chav){
					$aux.=",?";
					$this->valores[] = $chav;
				}
			}else{
				$aux.=",?";
				$this->valores[] = $chaves;
			}
			$aux = substr($aux,1,strlen($aux));
			$sql.=" where ".$campo." in(".$aux.")";
			
			$operacao = $this->link_conexao->prepare($sql);
			$atualizar = $operacao->execute($this->valores);
		}
		
		public function deletar($campo,$chaves){
			if(is_array($chaves))
				$chaves = implode(',',$chaves);   
			if(is_array($chaves)){
				foreach($chaves as $chav){
					$aux.=",?";
					$this->valores[] = $chav;
				}
			}else{
				$aux.=",?";
				$this->valores[] = $chaves;
			}
			$aux = substr($aux,1,strlen($aux));
			$sql = "delete from ".$this->tabela." where ".$campo." in(".$aux.")";
			
			$operacao = $this->link_conexao->prepare($sql);
			$deletar = $operacao->execute($this->valores);
		}
		
		public function prepararCampos($tipo){   
			$cont = 0;
			$var="";
			if($tipo=="campo"){		
				foreach($this->dados as $campo=>$valor){
					$var.=",".$campo;
				}
			}
			if($tipo=="valor"){
				foreach($this->dados as $campo=>$valor){
					$var.=",?";
					$this->valores[]=$valor;
				}
			}
			if($tipo=="atualizar"){	
				foreach($this->dados as $campo=>$valor){
					$var.=",".$campo."=?";
					$this->valores[]=$valor;
				}
			}
			$var = substr($var,1,strlen($var));
			return $var;
		}
		
		public function setDados($values){  
			$this->dados = $values;
		}
		
		public function getDados(){
			return $this->dados;
		}
		
		public function setQtde($quant){
			$this->qtde = $quant;
		}
		
		public function getQtde(){
			return $this->qtde;
		}
		
		public function setPrimaryKey(){
			$sql = "SHOW KEYS from ".$this->tabela." where Key_name='PRIMARY'";
			$operacao = $this->link_conexao->prepare($sql);
			$pesquisar = $operacao->execute();
			$resultado = $operacao->fetchAll();
			
			foreach($resultado as $values){
				$this->primary_key = $values['Column_name'];
			}
		}

	}	
?>