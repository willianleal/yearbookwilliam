<?php
	include('includes/conexao.php');
	include('classes.php');
	include('url.php');
	
	$checar_login = "";
	$cookie_usuario = "";
	
	if(!empty($_COOKIE['lembrar_usuario'])){
		$checar_login = 'checked="checked"';
		$cookie_usuario = $_COOKIE['lembrar_usuario'];
	}	
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Atividade Aberta 05</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" href="css/index.css" />
</head>
<body>
	<header>
		<h1>Alunos do curso de Pós-Graduação em Desenvolvimento de Aplicações WEB da PUC-Minas</h1>
	</header>
	
	<?php
		if(empty($_SESSION['login'])){?>
			<section id="sessao_login">
				<ul id="bloco_fotos_login">
					<?php
						$participante_obj = new Participante('participantes');
						$participante_rec = $participante_obj->getListar('order by nomeCompleto limit 10');
						$participante_obj->fechar_conexao();
						foreach($participante_rec as $participante){
							
							$pagina = 'javascript:void(0);';
							if(!empty($_SESSION['login']))
								$pagina = "paginas/perfil.php";
					?>
					<li>
						<a href="<?=$pagina?>">
							<figure>
								<img src="<?=$url?>/Fotos/<?=$participante['arquivoFoto']?>" title="<?=$participante['nomeCompleto']?>" alt="Fotos do aluno <?=$participante['nomeCompleto']?>" />
								<figcaption></figcaption>
							</figure>
						</a>
					</li>
					<?php }?>
				</ul>
	
				<form method="post" action="controller/login.php">			
					<input type="text" name="login" id="login" placeholder="Usuário" value="<?=$cookie_usuario?>" />
					<input type="password" name="senha" id="senha" placeholder="Senha" />
					<button type="submit">Fazer Login</button>			
					<div>
						<input type="checkbox" name="lembrar_usuario" id="lembrar_usuario" <?=$checar_login?> />
						<label for="lembrar_usuario">Lembrar usuário</label>
					</div>
						
					<a class="botao_voltar" href="<?=$url?>/paginas/cadastrar.php">Criar uma conta</a>
				</form>
			</section>
			
			<footer>
				<p>Desenvolvido por William Leal - Todos os direitos reservados.</p>
			</footer>
	<?php
		}
		else
		{
			header('Location:paginas/home.php');
		}
	?>
</body>
</html>


