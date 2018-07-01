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

if(!isset($_SESSION['INDEX'])){
	$_SESSION['INDEX'] = 0;
}else{
	$_SESSION['INDEX'] ++;
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
				<div class="tile-group no-margin no-padding clearfix" style="width: 100%">
					<div class="tile double quadro-vertical bg-gray ol-transparent" style="float: right; ">
						<div class="tile-content">
							<div class="brand">
								<img src="images/banner1.gif" style="height: 510px;"/>
							</div>
						</div>
					</div>

					<div class="tile quadro double-vertical ol-transparent">
						<div class="tile-content">
							<div class="carousel" data-role="carousel" data-height="100%" data-width="100%" data-controls="false">
								<div class="slide">
									<img src="images/1.jpg" />
								</div>
								<div class="slide">
									<img src="images/2.jpg" />
								</div>
								<div class="slide">
									<img src="images/3.jpg" />
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

					<div class="tile triple double-vertical ol-transparent bg-white">
						<div class="tile-content">

							<div class="panel no-border">
							<?php
								$feed = Feed::retrieveByValor();
								$categoria = Categoria::retrieveByFeed($feed->getCodigo());
							?>
								<div class="panel-header bg-darkRed fg-white"><?= $categoria->getNome() ?></div>
								<div class="panel-content fg-dark nlp nrp">
									<a href="noticia.php?c=<?= $feed->getCodigo()?>">
									<img src="images/<?= $feed->getFoto() ?>" class="place-left margin10 nlm ntm size2">
									</a>
									<strong><?= $feed->getNome() ?></strong> <?= $feed->getTexto() ?>
								</div>
							</div>
						</div>
					</div>
					<div class="tile triple double-vertical ol-transparent bg-white">
						<div class="tile-content">
							<div class="panel no-border">
							<?php
								$feed = Feed::retrieveByValorDesc();
								$categoria = Categoria::retrieveByFeed($feed->getCodigo());
							?>
								<div class="panel-header bg-pink fg-white"><?= $categoria->getNome() ?></div>
								<div class="panel-content fg-dark nlp nrp">
									<a href="noticia.php?c=<?= $feed->getCodigo()?>">
										<img src="images/<?= $feed->getFoto() ?>" class="place-left margin10 nlm ntm size2">
									</a>
									<strong><?= $feed->getNome() ?></strong><?= $feed->getTexto() ?>
								</div>
							</div>
						</div>
					</div>
				</div>
<?php
				$categorias = Categoria::retrieveCategorias();
				foreach ($categorias as $categoria) {
				$feeds = Feed::retrieveByCategoria($categoria->getCodigo());
?>
				<div class="tile-group no-margin no-padding1 clearfix" style="width: 100%;">
					<a href="#"><span class="tile-group-title fg-orange"> <?= $categoria->getNome() ?> <span class="icon-arrow-right-5"></span></span></a>
<?php
				foreach ($feeds as $key => $feed) {
						if($key == 0){
?>
					<div class="tile quadro double-vertical">
					<a href="noticia.php?c=<?= $feed->getCodigo() ?>">
						<div class="image-container" style="height: 100%; width: 100%;">
							<img src="images/<?= $feed->getFoto() ?>" class="place-left  shadow" />
								<div class="overlay">
									<?= $feed->getNome() ?>
								</div>
						</div>
					</a>
					</div>
<?php
						}
					if($key != 0 && $key < 9){
?>

					<div class="tile">
						<a href="noticia.php?c=<?= $feed->getCodigo() ?>">
							<div class="image-container" style="height: 100%; width: 100%;">
									<img src="images/<?= $feed->getFoto() ?>" class="place-left span2 shadow" style="height: 100%; width: 100%;"/>
									<div class="overlay">
										<?= $feed->getNome() ?>
									</div>
							</div>
						</a>
					</div>
<?php }}}?>
			</div>
		</div>
		</div>

		<footer>
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
