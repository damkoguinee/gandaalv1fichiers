<?php
require '_header.php';
$_SESSION['pseudo'] = $_POST['pseudo'];
$_SESSION['promo'] = $_POST['promo'];
$_etat = 'connecté';
unset($_SESSION['type']);
	
$connexion = $DB->querys('SELECT * FROM login WHERE pseudo =:Pseudo', 
	array('Pseudo'=>$_POST['pseudo']));

$password=password_verify($_POST['mdp'], $connexion['mdp']);

$etab=$DB->querys('SELECT *from etablissement');


$_SESSION['etab']=$etab['nom'];
$_SESSION['type']=$connexion['type'];
$_SESSION['idpseudo']=$connexion['id'];
$_SESSION['niveaupers']=$connexion['niveau'];
$_SESSION['level']=$_SESSION['niveaupers'];

if ($_SESSION['type']=='tuteur') {

	$prodtut = $DB->querys('SELECT * FROM tuteur WHERE matuteur =:Pseudo', 
	array('Pseudo'=>$connexion['matricule']));

	$_SESSION['matricule']=$prodtut['matricule'];

}else{
	$_SESSION['matricule']=$connexion['matricule'];
}

if ($_SESSION['type']=='eleve') {

	$_SESSION['promo']=2023;

}

$prodcodef=$DB->querys("SELECT codef from inscription where matricule='{$_SESSION['matricule']}' and annee='{$_SESSION['promo']}' ");

$_SESSION['codefcon']=$prodcodef['codef'];




if (empty($connexion)){
	header('Location:form_connexion.php');
}else{

	if (!$password){
		header('Location:form_connexion.php');

	}else{
		$bdd='contactusers'; 
		$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`mat_user` VARCHAR(20) NULL,
		`phoneuser` VARCHAR(20) NULL,
		`mailuser` VARCHAR(50) NULL,
		`typeuser` VARCHAR(20) NULL,
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");

		// $usersEleves=$DB->query("SELECT phone, contact.matricule as mat_user, email FROM contact inner join inscription on inscription.matricule=contact.matricule");
		// foreach ($usersEleves as $key => $value) {
		// 	$verif=$DB->querys("SELECT mat_user FROM contactusers where mat_user='{$value->mat_user}' ");
		// 	if (empty($verif['mat_user'])) {
		// 		$DB->insert("INSERT INTO contactusers (mat_user,phoneuser,mailuser,typeuser)VALUES(?,?,?,?)",array($value->mat_user,$value->phone,$value->email,'eleve'));
		// 	}
		// }

		// $usersPere=$DB->query("SELECT matricule as mat_user, telpere as phone FROM eleve");
		// foreach ($usersPere as $key => $value) {
		// 	if (!empty($value->phone)) {
		// 		$verif=$DB->querys("SELECT mat_user FROM contactusers where phoneuser='{$value->phone}' ");
		// 		if (empty($verif['mat_user'])) {
		// 			$DB->insert("INSERT INTO contactusers (mat_user,phoneuser,mailuser,typeuser)VALUES(?,?,?,?)",array($value->mat_user,$value->phone,"",'pere'));
		// 		}
		// 	}
		// }

		// $usersMere=$DB->query("SELECT matricule as mat_user, telmere as phone FROM eleve");
		// foreach ($usersMere as $key => $value) {
		// 	if (!empty($value->phone)) {
		// 		$verif=$DB->querys("SELECT mat_user FROM contactusers where phoneuser='{$value->phone}' ");
		// 		if (empty($verif['mat_user'])) {
		// 			$DB->insert("INSERT INTO contactusers (mat_user,phoneuser,mailuser,typeuser)VALUES(?,?,?,?)",array($value->mat_user,$value->phone,"",'mere'));
		// 		}
		// 	}
		// }

		// $userstuteur=$DB->query("SELECT matricule as mat_user, teltut as phone FROM tuteur");
		// foreach ($userstuteur as $key => $value) {
		// 	if (!empty($value->phone)) {
		// 		$verif=$DB->querys("SELECT mat_user FROM contactusers where phoneuser='{$value->phone}' ");
		// 		if (empty($verif['mat_user'])) {
		// 			$DB->insert("INSERT INTO contactusers (mat_user,phoneuser,mailuser,typeuser)VALUES(?,?,?,?)",array($value->mat_user,$value->phone,"",'tuteur'));
		// 		}
		// 	}
		// }

		// $usersEnseignant=$DB->query("SELECT phone, contact.matricule as mat_user, email FROM contact inner join enseignant on enseignant.matricule=contact.matricule");
		// foreach ($usersEnseignant as $key => $value) {
		// 	if (!empty($value->phone)) {
		// 		$verif=$DB->querys("SELECT mat_user FROM contactusers where mat_user='{$value->mat_user}' ");
		// 		if (empty($verif['mat_user'])) {
		// 			$DB->insert("INSERT INTO contactusers (mat_user,phoneuser,mailuser,typeuser)VALUES(?,?,?,?)",array($value->mat_user,$value->phone,$value->email,'enseignant'));
		// 		}
		// 	}
		// }

		// $usersPersonnel=$DB->query("SELECT phone, contact.matricule as mat_user, email FROM contact inner join personnel on personnel.numpers=contact.matricule");
		// foreach ($usersPersonnel as $key => $value) {
		// 	if (!empty($value->phone)) {
		// 		$verif=$DB->querys("SELECT mat_user FROM contactusers where mat_user='{$value->mat_user}' ");
		// 		if (empty($verif['mat_user'])) {
		// 			$DB->insert("INSERT INTO contactusers (mat_user,phoneuser,mailuser,typeuser)VALUES(?,?,?,?)",array($value->mat_user,$value->phone,$value->email,'personnel'));
		// 		}
		// 	}
		// }

		if ($_SESSION['type']=='eleve' or $_SESSION['type']=='tuteur') {

			header('Location: accueileleve.php?eleve');

		}elseif ($_SESSION['type']=='enseignant') {

			header('Location: accueilenseignant.php?enseignant');

		}else{

			header('Location: index.php?form&note');

		}

		
	}
}?>