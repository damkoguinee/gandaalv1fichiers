<?php
require 'header.php';

$montantinscreche=2000000;
$montantinsmaternelle=2000000;
$montantinsprimaire=2000000;
$montantinscollege=2000000;
$montantinslycee=2000000;


$prodenseig=$DB->query("SELECT payement.matricule as matricule, niveau, payement.remise as remise, payement.montant as montant from payement inner join inscription on inscription.matricule=payement.matricule where promo='{$_SESSION['promo']}' and annee='{$_SESSION['promo']}' ");

foreach ($prodenseig as $value) {

	if ($value->niveau=='creche') {
		$montantins=$montantinscreche;
	}elseif ($value->niveau=='maternelle') {
		$montantins=$montantinsmaternelle;
	}elseif ($value->niveau=='primaire') {
		$montantins=$montantinsprimaire;
	}elseif ($value->niveau=='college') {
		$montantins=$montantinscollege;
	}elseif ($value->niveau=='lycee') {
		$montantins=$montantinslycee;
	}

	$montantremise=$montantins*(1-($value->remise/100));

	if ($montantremise==$value->montant) {
		$etat='inscription';
	}else{
		$etat='reinscription';
	}

	if (!$value->remise==100) {

		$DB->insert("UPDATE payement SET motif='{$etat}' where matricule='{$value->matricule}' and promo='{$_SESSION['promo']}'");

		$DB->insert("UPDATE inscription SET etat='{$etat}' where matricule='{$value->matricule}' and annee='{$_SESSION['promo']}'");
	}
}