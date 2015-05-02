<?php
	
	session_start();
	
	include('../includes/conexao.php');
	include('../classes.php');

	$dados = array();
	
	foreach($_POST as $chave=>$valor){
		if($chave!='idEstado'){
			if($chave=='senha')
				$dados[$chave] = md5(htmlspecialchars($valor));
			else
				$dados[$chave] = htmlspecialchars($valor);
		}
		//$teste .= $chave."|".$valor."|";
	}
	
	if(strlen($_FILES['arquivoFoto']['name'])>0){
		$arquivo = explode('.',$_FILES['arquivoFoto']['name']);
		$nome_arquivo="";
		
		for($i=0;$i<count($arquivo);$i++){
			if($i<count($arquivo)-1)
				$nome_arquivo.=$arquivo[$i];
			else
				$extensao = $arquivo[$i];
		}
		
		$nome_arquivo = md5($nome_arquivo).date('dm').date('His'); 
		$nome_arquivo.='.'.$extensao;
		$_FILES['arquivoFoto']['name'] = $nome_arquivo;
		
		if(!move_uploaded_file($_FILES['arquivoFoto']['tmp_name'],'../fotos/'.$nome_arquivo))
			echo "Falha ao anexar a foto!";
		
		if(file_exists("../fotos/".$_SESSION['login']['arquivoFoto']))
			unlink("../fotos/".$_SESSION['login']['arquivoFoto']);
			
		$dados['arquivoFoto'] = $nome_arquivo;
		
		if(!empty($_SESSION['login']))
			$_SESSION['login']['arquivoFoto'] = $dados['arquivoFoto'];
	}
	
	$participante_obj = new Participante('participantes');
	$participante_obj->setDados($dados);
	
	if(empty($_SESSION['login']))
	{
		$participante_obj->salvar();
	}		
	else
	{
		$participante_obj->atualizar('email',$_SESSION['login']['email']);	
		
		foreach($_POST as $chave=>$valor){
			if($chave!='idEstado'){
				if($chave=='senha')
					$_SESSION['login'][$chave] = md5(htmlspecialchars($valor));
				else
					$_SESSION['login'][$chave] = htmlspecialchars($valor);
			}
		}
	}
		
	$participante_obj->fechar_conexao();

	$pag = 'cadastrar.php';
	
	$_SESSION['inserido'] = 'Perfil cadastrado com sucesso!';
	
	header('Location:../paginas/'.$pag);
?>