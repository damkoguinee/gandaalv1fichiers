<?php
	require 'db.class.php';
	require 'panier.class.php';
	require 'rapportClass.php';
	require 'immobillierClass.php';
	$DB = new DB();
	$panier = new panier($DB);
	$rapport= new Rapport($DB);
	$immobillier= new Immobillier($DB);
?>