<?php
require '_header.php';

for ($i=1; $i <11 ; $i++) { 

	if (($i%2)==0) {
		$nom="DIALLO".$i;
		$prenom="Amadou".$i;
		$sexe="m";
	}else{

		$nom="BAH".$i;
		$prenom="Fatoumata Binta".$i;
		$sexe="f";

	}

	
	$daten="2003-04-01";
	
	$phone="0753542292";
	$nomp="DIALLO Madiou".$i;
	$nomm="BAH Kadiatou".$i;
	$telm="628196628";
	$telp="628309888";
	$nomt="BAH Kadiatou".$i;
	$telt="628196628";
	$adresse="Labé pellel";
	$email="d.amadoumouctar@yahoo.fr";
	$pays="Guinée";
	$nation="Guineenne";

	$frais=50000;
	$remiseins=0;
	$remisescol=0;
	$typepaye='especes';
	$codef="tsm";
	$annee="2021";
	$groupe="termA";
	$niveau='lycee';
	$numpaye=$i;


	$nb=$DB->querys('SELECT max(id) as id from eleve');
						
	$matricule=date('y') . '000'+$nb['id']+1;
	$pseudo=$prenom[0].$nom;
	$mdp=$prenom[0].$nom;
	$initiale='csp';

	$DB->insert('INSERT INTO eleve(matricule, nomel, prenomel, naissance, sexe, pere, mere, telpere, telmere, pays, nationnalite, adresse, dateenreg) values( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($initiale.$matricule, $nom, $prenom, $daten, $sexe, $nomp, $nomm, $telp, $telm, $pays, $nation, $adresse));


		/*		Ajouter le num dans le login    */

		$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array($initiale.$matricule, strtolower($pseudo) , strtolower($mdp), 'eleve', 1));

		$DB->insert('INSERT INTO contact(matricule, phone, email) values(?, ?, ?)', array($initiale.$matricule, strtolower($phone) , strtolower($email)));

		$DB->insert('INSERT INTO inscription(matricule, codef, niveau, nomgr, remise, annee) values( ?, ?, ?, ?, ?, ?)', array($initiale.$matricule, $codef, $niveau, $groupe, $remisescol, $annee));


		$DB->insert('INSERT INTO payement(numpaye, matricule, montant, remise, mois, motif, typepaye, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $initiale.$matricule, '150000', $remiseins, '01', 'inscription', 'especes', $annee));

		$nb=$DB->querys('SELECT max(id_tut) as id from tuteur');

		$matuteur=$matricule;

		$matuteur='tut'.$initiale.$matuteur;

		$pseudo=$matuteur;
		$mdp=$matuteur;

		$DB->insert('INSERT INTO tuteur(matuteur, matricule, nomtut, teltut) values(?, ?, ?, ?)', array($matuteur, $initiale.$matricule, $nomt, $telt));

		/*		Ajouter le num dans le login    */

		$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array($matuteur, strtolower($pseudo) , strtolower($mdp), 'tuteur', 1));

	}