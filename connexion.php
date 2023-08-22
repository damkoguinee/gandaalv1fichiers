<?php
require '_header.php';
$_SESSION['pseudo'] = $_POST['pseudo'];
$_SESSION['promo'] = $_POST['promo'];
$_etat = 'connecté';
	
$connexion = $DB->querys('SELECT * FROM login WHERE pseudo =:Pseudo AND mdp=:Mdp ', 
	array('Pseudo'=>$_POST['pseudo'], 'Mdp'=>$_POST['mdp'] ));

$etab=$DB->querys('SELECT *from etablissement');

$_SESSION['etab']=$etab['nom'];

if (empty($connexion)){
		header('Location:form_connexion.php');
}else{
	
	header('Location: index.php?form&note');
}?>