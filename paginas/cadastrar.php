<?php
	include('../includes/conexao.php');
	include('../classes.php');
	include('../url.php');
	
	$requerido=0;
	
	if(empty($_SESSION['login']))
		$_SESSION['login'] = null;
	else
		$requerido=1;
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<title>Cadastro</title>
		<meta charset="utf-8" />
		
		<link rel="stylesheet" href="../css/cadastro.css" />
		
		<script src="../js/jquery-2.1.1.min.js" ></script>
		<script src="../js/funcoes.js" ></script>
	</head>
	
	<body>
		<section>
			<?php
				if(!empty($_SESSION['inserido'])){?>
					<div class="mensagem">"<?=$_SESSION['inserido']?>"</div>
			<?php	
					unset($_SESSION['inserido']);
				}?>
				<form method="post" action="../controller/cadastrar.php" enctype="multipart/form-data" class="form_cadastro">
					<input type="text" name="nomeCompleto" id="nome" placeholder="Nome" value="<?=$_SESSION['login']['nomeCompleto']?>" />
					<input type="text" name="email" id="email" placeholder="Email" value="<?=$_SESSION['login']['email']?>" />
					
					<select name="idEstado" id="idEstado" onchange="mostrar_cidades('cidade',this.value,'mostrar_cidades.php');">
						<option value="">Estado</option>
						<?php
							$estado_obj = new Estado('estados');
							$estado_rec = $estado_obj->getListar('order by nomeEstado asc');
							foreach($estado_rec as $estado){
								$selected='';
								if(!empty($_SESSION['login'])){
									if($estado['idEstado']==$estado_rec_busca['idEstado'])
										$selected = 'selected="selected"';
								}
								?>
								<option value="<?=$estado['idEstado']?>" <?=$selected?>><?=$estado['nomeEstado']?></option>
						<?php
							}
						?>
					</select>
					
					<select name="cidade" id="cidade">
						<option value="">Cidade</option>
						<?php
							if(!empty($_SESSION['login'])){
								$cidade_obj_aux = new Cidade('cidades');
								$cidade_obj_aux->setDados(array($estado_rec_busca['idEstado']));
								$cidade_aux_busca = $cidade_obj_aux->getListar('and idEstado=?');
								foreach($cidade_aux_busca as $cidade_aux){
									$selected='';
									
									if($_SESSION['login']['cidade']==$cidade_aux['idCidade'])
										$selected = 'selected="selected"';
									?>
									<option value="<?=$cidade_aux['idCidade']?>" <?=$selected?>><?=$cidade_aux['nomeCidade']?></option>
						<?php
								}
							}
						?>
					</select>
					
					<input type="text" name="login" id="login" placeholder="Usuário" value="<?=$_SESSION['login']['login']?>" />
					<input type="password" name="senha" id="senha" placeholder="Senha" />
					<input type="file" name="arquivoFoto" id="arquivoFoto" /> <label>*.jpg, *.png e *.gif.</label>
					<?	
						if(!empty($_SESSION['login'])){?>
							<figure>
								<img src="<?=$url?>/fotos/<?=$_SESSION['login']['arquivoFoto']?>" alt="Imagem não carregada" title="<?=$_SESSION['login']['nomeCompleto']?>" />
							</figure>
					<?	}?>
					<textarea name="descricao" id="descricao" placeholder="Faça uma breve descrição sua"><?=$_SESSION['login']['descricao']?></textarea>
					
					<button type="button" onclick="validar_formulario('form_cadastro','<?=$requerido?>');">Salvar</button>
					
					<a href="../index.php">Voltar</a>
				</form>
		</section>
		<?php
			include('rodape.php');
		?>
	</body>
</html>