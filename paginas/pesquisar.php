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
		<?php
			$busca = htmlspecialchars($_POST['nome_busca']);
			$descricao_busca = '%'.$busca.'%';
	
			$participante_busca_obj = new Participante('participantes');
			$participante_busca_obj->setDados(array($descricao_busca));
			$participante_rec_busca = $participante_busca_obj->getListar('and nomeCompleto like ? order by nomeCompleto asc');
		?>

		<section class="sessao_interna">
			<div class="blocoLeft">
				<div class="dados">
					<ul id="fundo_informacoes_busca">
					<?php
					if($participante_busca_obj->getQtde()>0){
						foreach($participante_rec_busca as $participante_busca){?>
						<li>
							<a href="<?=$url?>/paginas/perfil.php?email_perfil=<?=$participante_busca['email']?>">
								<figure class="fotoperfil">
									<img class="imgperfil" src="<?=$url?>/fotos/<?=$participante_busca['arquivoFoto']?>" title="<?=$participante_busca['nomeCompleto']?>" alt="Imagem não carregada" />
									<figcaption><?=$participante_busca['nomeCompleto']?></figcaption>
								</figure>
							</a>
						</li>
					<?php
					}
					}else{ ?>
						<p>Nenhum resultado encontrado.</p>
					<?php
					}
					?>
					</ul>
				</div>
			</div>
			
			<div class="blocoRight">
			
				<p class="bemVindo">Olá <?=$_SESSION['login']['nomeCompleto']?> <a href="<?=$url?>/controller/logoff.php">Sair</a></p>
				
				<div class="pesquisar">
					<form action="<?=$url?>/paginas/pesquisar.php" method="post" class="pesquisar">
						<p class="titulos_blocos">Filtrar</p>
						<input class="campoFilrar" type="text" name="nome_busca" placeholder="Buscar participantes" />
						<button type="submit">Buscar</button>
					</form>
				
					<p class="titulos_blocos">Último perfil visitado</p>
					<?php
					$email_cookie = explode('@',$_SESSION['login']['email']);
			
					if(empty($_COOKIE['ultimoperfil_'.$email_cookie[0]])){?>
						<p id="nenhum_visitado">Nenhum perfil visitado</p>
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
						<?php	
						}?>
					</ul>
				</div>
			</div>
        </section>
<?php
	include('rodape.php');
?>