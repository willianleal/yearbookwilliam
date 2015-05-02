<?php
	include('../includes/conexao.php');
	include('../classes.php');
	include('../url.php');
	include('analisar_logado.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<title>Atividade Aberta 05</title>
		<meta charset="utf-8"/>
		
		<link rel="stylesheet" href="../css/home.css" />
	
		<script src="../js/jquery-2.1.1.min.js" ></script>
		<script src="../js/funcoes.js" ></script>
	</head>

	<body>
		<header>
			<h1>Alunos do curso de Pós-Graduação em Desenvolvimento de Aplicações WEB da PUC-Minas</h1>			
		</header>

		<section class="sessao_interna">
			
			<div class="blocoLeft">
				<div class="dados">
					<img src="<?=$url?>/fotos/<?=$_SESSION['login']['arquivoFoto']?>" title="<?=$_SESSION['login']['nomeCompleto']?>" alt="Foto não carregada" width="168px" />
					<br />
					<a href="<?=$url?>/paginas/cadastrar.php">Alterar perfil</a>
					<a href="javascript:void(0);" onclick="excluir_perfil('excluir_perfil.php');">Excluir perfil</a>
					
					<h1 class="titulo"><?=$_SESSION['login']['nomeCompleto']?></h1>
					<dl>
						<?php
							$cidade_obj = new Cidade('cidades');
							$cidade_obj->setDados(array($_SESSION['login']['cidade']));
							list($cidade) = $cidade_obj->getListar('and idCidade=?');
							$cidade_obj->fechar_conexao();
					
							$estado_obj = new Estado('estados');
							$estado_obj->setDados(array($cidade['idEstado']));
							list($estado) = $estado_obj->getListar('and idEstado=?');
							$estado_obj->fechar_conexao();	
						?>
						<dt>Cidade:</dt><dd><?=$cidade['nomeCidade']?></dd>
						<dt>Estado:</dt><dd><?=$estado['nomeEstado']?></dd>
						<dt>E-mail:</dt><dd><?=$_SESSION['login']['email']?></dd>
						<dt>Descrição:</dt><dd><?=$_SESSION['login']['descricao']?></dd>
					</dl>				
				</div>
			</div>
			
			<div class="blocoRight">
				
				<p class="bemVindo">Olá <?=$_SESSION['login']['nomeCompleto']?> <a href="<?=$url?>/controller/logoff.php">Sair</a></p>
				
				<div class="pesquisar">
					<form action="<?=$url?>/paginas/pesquisar.php" method="post" class="pesquisar">
						<p class="titulos_blocos">Pesquisar</p>
						<input class="campoFilrar" type="text" name="nome_busca" placeholder="Quem você está procurando?" />
						<button type="submit">Buscar</button>
					</form>
				
					<p class="titulos_blocos">Último perfil visitado</p>
					<?php
					$email_cookie = explode('@',$_SESSION['login']['email']);
			
					if(empty($_COOKIE['ultimoperfil_'.$email_cookie[0]])){?>
						<p id="nenhum_visitado">Nenhuma visita registrada</p>
					<?php
					}
					else
					{
						$participante_obj_perfil = new Participante('participantes');
						$participante_obj_perfil->setDados(array($_COOKIE['ultimoperfil_'.$email_cookie[0]]));
						list($participante_perfil) = $participante_obj_perfil->getListar('and email=?');
						$participante_obj_perfil->fechar_conexao();
					?>
						<a href="<?=$url?>/paginas/perfil.php?email_perfil=<?=$participante_perfil['email']?>">
							<figure>
								<img src="<?=$url?>/fotos/<?=$participante_perfil['arquivoFoto']?>" alt="Imagem não carregada" title="<?=$participante_perfil['nomeCompleto']?>" />
								<figcaption><?=$participante_perfil['nomeCompleto']." - ".$participante_perfil['email']?></figcaption>
							</figure>
						</a>
					<?php	
					}
					?>
				</div>
				
				<div class="participantes">
					<ul class="ul_participantes">
						<p class="titulos_blocos">Alunos</p>
						<?php
						$participante_obj_esq = new Participante('participantes');
						$participante_obj_esq->setDados(array($_SESSION['login']['email']));
						$participante_rec_esq = $participante_obj_esq->getListar('and email <> ? order by rand() limit 12');
						$participante_obj_esq->fechar_conexao();
						foreach($participante_rec_esq as $participante_esq){?>
						<li>
							<a href="<?=$url?>/paginas/perfil.php?email_perfil=<?=$participante_esq['email']?>">
								<figure>
									<img src="<?=$url?>/fotos/<?=$participante_esq['arquivoFoto']?>" title="<?=$participante_esq['nomeCompleto']?>" alt="Imagem não carregada" />
								</figure>
							</a>
						</li>
						<?php	}?>
					</ul>
				</div>
				
			</div>
		</section>
<?php
	include('rodape.php');
?>