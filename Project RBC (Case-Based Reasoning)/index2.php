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
$c = $_GET['c'];
$c++;

header("Location: noticia.php?c=".$c);
die();

