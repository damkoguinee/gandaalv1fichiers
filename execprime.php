<?php
require 'header.php';


$prodenseig=$DB->query('SELECT * from enseignant');



foreach ($prodenseig as $value) {
	$login=$DB->querys("SELECT * from login where matricule='{$value->matricule}'");
	$prime=$DB->querys("SELECT * from prime where numpersp='{$value->matricule}' and promop='{$_SESSION['promo']}'");
	$contact=$DB->querys("SELECT * from contact where matricule='{$value->matricule}'");
	$salaire=$DB->querys("SELECT * from salaireens where numpers='{$value->matricule}' and promo='{$_SESSION['promo']}'");
	$social=$DB->querys("SELECT * from ssocialens where numpers='{$value->matricule}'");

	if (empty($login)) {
		
		$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array($value->matricule, $value->matricule, $value->matricule, 'enseignant', 1));
	}

	if (empty($prime)) {
		
		$DB->insert('INSERT INTO prime(numpersp, montantp, promop) values(?, ?, ?)', array($value->matricule, 0, $_SESSION['promo']));
	}

	if (empty($contact)) {
		
		$DB->insert('INSERT INTO contact(matricule) values(?)', array($value->matricule));
	}

	if (empty($salaire)) {
		
		$DB->insert('INSERT INTO salaireens(numpers, salaire, thoraire, promo) values(?, ?, ?, ?)', array($value->matricule, 0, 0, $_SESSION['promo']));
	}

	if (empty($social)) {
		
		$DB->insert('INSERT INTO ssocialens(numpers, montant) values(?, ?)', array($value->matricule, 0));
	}

	//
}

$prodpers=$DB->query('SELECT * from personnel');


foreach ($prodpers as $valuep) {

	$login=$DB->querys("SELECT * from login where matricule='{$value->matricule}'");
	$prime=$DB->querys("SELECT * from primepers where numpersp='{$value->matricule}' and promop='{$_SESSION['promo']}'");
	$contact=$DB->querys("SELECT * from contact where matricule='{$value->matricule}'");
	$salaire=$DB->querys("SELECT * from salairepers where numpers='{$value->matricule}' and promo='{$_SESSION['promo']}'");
	$social=$DB->querys("SELECT * from ssocialpers where numpers='{$value->matricule}'");

	if (empty($login)) {
		
		$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array($value->matricule, $value->matricule, $value->matricule, 'enseignant', 1));
	}

	if (empty($prime)) {
		
		$DB->insert('INSERT INTO primepers(numpersp, montantp, promop) values(?, ?, ?)', array($valuep->numpers, 0, $_SESSION['promo']));
	}

	if (empty($contact)) {
		
		$DB->insert('INSERT INTO contact(matricule) values(?)', array($value->matricule));
	}

	if (empty($salaire)) {
		
		$DB->insert('INSERT INTO salairepers(numpers, salaire, promo) values(?, ?, ?)', array($value->matricule, 0, $_SESSION['promo']));
	}

	if (empty($social)) {
		
		$DB->insert('INSERT INTO ssocialpers(numpers, montant) values(?, ?)', array($value->matricule, 0));
	}

	
}