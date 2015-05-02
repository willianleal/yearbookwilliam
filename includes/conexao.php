<?php
	//Conexão com o BD
	function conexao_mysql(){
		try{
			//$host = 'localhost';
			//$usuario = 'daw';
			//$senha='daw2014';
			//$banco = 'daw_yearbook';
			
			$host = 'us-cdbr-azure-east-b.cloudapp.net';
			$usuario = 'b7ed2834f93bed';
			$senha= '925cd357';
			$banco = 'as_078c509120eae02';
			
			$porta = 3306;
			$string_conexao = "mysql:host=$host;port=$porta;dbname=$banco";
			
			$con = new PDO($string_conexao,$usuario,$senha);
			
			return $con;
		}
		
		//Retorna a mensagem em caso de erro
		catch(PDOException $e){
			echo 'Erro: '.$e->getMessage().'<br />';
			die();
		}
	}
?>