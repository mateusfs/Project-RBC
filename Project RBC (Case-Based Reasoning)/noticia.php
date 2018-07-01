<?php
session_start();
require_once 'Feed.php';
require_once 'Database.php';
require_once 'Categoria.php';

if(!isset($_SESSION['FEED'])){
	$_SESSION['FEED'] = array();
}
if(!isset($_SESSION['CATEGORIA'])){
	$_SESSION['CATEGORIA'] = array();
}

$sessao = $_SESSION['FEED'];
$feed = Feed::retrieveByPk($_GET['c']);
$sessao[] = $feed->getCodigo();
$_SESSION['FEED'] = $sessao;

$sessao = $_SESSION['CATEGORIA'];
$categoria = Categoria::retrieveByFeed($feed->getCodigo());
$sessao[] = $categoria->getCodigo();
$_SESSION['CATEGORIA'] = $sessao;


if(isset($_SESSION['INDEX'])){
$index = $_SESSION['INDEX'];

	if($index >= 5){

		$sessaoCategoria = $_SESSION['CATEGORIA'];
		foreach ($sessaoCategoria as $categoria) {
			$categoriaSave = Categoria::retrieveByPk($categoria);
			$categoriaSave->setValor($categoriaSave->getValor()+1);
			$categoriaSave->save();
		}

		$sessaoFeed = $_SESSION['FEED'];
		foreach ($sessaoFeed as $feed) {
			$feedSave = Feed::retrieveByPk($feed);
			$feedSave->setValor($feedSave->getValor()+1);
			$feedSave->save();
		}
		session_destroy();
	}
}



?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name='robots' content='noindex,nofollow'/>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<meta http-equiv='X-UA-Compatible' content='IE=edge' />
	<meta http-equiv=Content-Type content='text/html; charset=utf-8'>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link href="css/metro-bootstrap.css" rel="stylesheet">
	<link href="css/iconFont.css" rel="stylesheet">
	<script src="js/jquery/jquery.min.js"></script>
	<script src="js/jquery/jquery.widget.min.js"></script>
	<script src="js/load-metro.js"></script>
	<script src="js/github.info.js"></script>
	<title>RBC</title>
	<style>
		.container {
			width: 1040px;
		}
	</style>
</head>
<body class="metro">
	<div class="container">
		<header class="margin20 nrm nlm">
			<div class="clearfix">
				<div class="place-right">
					<form>
						<div class="input-control text size6 margin20 nrm">
							<input type="text" name="q" placeholder="Search...">
							<button class="btn-search"></button>
						</div>
					</form>
				</div>
				<a class="place-left" href="index.php" title="">
					<h1>RBC</h1>
				</a>
			</div>
			<div class="main-menu-wrapper">
				<ul class="horizontal-menu nlm">
<?php
$categorias = Categoria::retrieveCategorias();
foreach ($categorias as $categoria) {
?>
					<li><a href="#"><?= $categoria->getNome() ?></a></li>
<?php }?>
				</ul>
			</div>
	</header>
		<div class="main-content clearfix">
			<div class="tile-area no-padding clearfix">
				<div class="tile-group no-margin no-padding clearfix" style="width: 100%;">
					<div class="tile double quadro-vertical ol-transparent" style="float: right;  height: 250px;">
						<div class="tile-content">
							<div class="brand" >
								<?php if($_GET['c'] > 25){?>
									<img src="images/banner2.gif" style="height: 250px;"/>
								<?php }else{?>
									<img src="images/banner3.gif" style="height: 250px;"/>
								<?php }?>
							</div>
						</div>
					</div>

					<div class="tile quadro double-vertical ol-transparent">
						<div class="tile-content">
							<div class="carousel" data-role="carousel" data-height="100%" data-width="100%" data-controls="false">
								<div class="slide">
									<img src="images/3.jpg" />
								</div>
								<div class="slide">
									<img src="images/2.jpg" />
								</div>
								<div class="slide">
									<img src="images/1.jpg" />
								</div>
							</div>
						</div>
					</div>
					<div class="tile bg-lightBlue ol-transparent">
						<div class="tile-content icon">
							<span class="icon-windows"></span>
						</div>
					</div>
					<div class="tile bg-orange ol-transparent">
						<div class="tile-content icon">
							<span class="icon-music"></span>
						</div>
					</div>
					<div class="tile ol-transparent bg-teal">
						<div class="tile-content icon">
							<span class="icon-facebook"></span>
						</div>
					</div>
					<div class="tile ol-transparent bg-green">
						<div class="tile-content icon">
							<span class="icon-twitter"></span>
						</div>
					</div>
			<div class="tile ol-transparent" style="width: 99%; height: 100%;">
				<div class="tile-content" >
					<div class="panel no-border">
				<?php
				$feed = Feed::retrieveByPk($_GET['c']);
				$categoria = Categoria::retrieveByFeed($feed->getCodigo());

				?>
						<div class="panel-header bg-darkRed fg-white"><?= $categoria->getNome() ?></div>
					</div>
				</div>

			<div class="tertiary-text text-justify" >
				<img src="images/<?= $feed->getFoto() ?>" class="place-left span6" style="float: left; margin-top: 50px;">
					<div style="float: right;">
						<h2><?= $feed->getNome() ?></h2><br>
							<p class="tertiary-text"><?= $feed->getTexto()?> <?= $feed->getTexto()?> <?= $feed->getTexto()?> <?= $feed->getTexto()?></p>
					</div>
			</div>
		</div>
		<div style="width: 100%; clear: both; height: 150px;">
			<a href="index.php">
				<button class="command-button primary" style="float: left; margin-top: 50px;">
					<i class="icon-arrow-left-2 on-left"></i>
					Voltar
					<small>Voltar ao inicio</small>
				</button>
			</a>
			<a href="index2.php?c=<?= $feed->getCodigo() ?>">
				<button class="command-button primary" style="float: right; margin-top: 50px;">
					<i class="icon-arrow-right-2 on-right"></i>
					Avançar
					<small>Próxima noticia</small>
				</button>
			</a>
		</div>
		<footer style="clear: both; width: 99%; bottom: 0px;">
			<div class="bottom-menu-wrapper">
				<ul class="horizontal-menu compact">
					<li>&copy; 2014 Trabalho RBC</li>
				</ul>
			</div>
		</footer>
	</div>
	<script src="js/hitua.js"></script>
</body>
</html>

