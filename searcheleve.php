<?php
require '_header.php';
if (isset($_GET['user'])) {
	$user=(string) trim($_GET['user']);

	if ($_SESSION['typel']=='interne') {
		$req=$DB->query('SELECT *FROM eleve inner join inscription on inscription.matricule=eleve.matricule left join contact on eleve.matricule=contact.matricule where (eleve.matricule LIKE ? or nomel LIKE ? or prenomel LIKE ? or phone LIKE ?) and annee LIKE ? LIMIT 50',array("%".$user."%", "%".$user."%", "%".$user."%", "%".$user."%", $_SESSION['promo']));
	}else{

		$req=$DB->query('SELECT matex as matricule, nom, prenom FROM elevexterne where matex LIKE ? or nom LIKE ? or prenom LIKE ? or tel LIKE ? LIMIT 10',array("%".$user."%", "%".$user."%", "%".$user."%", "%".$user."%"));

	}
	

	if (isset($_GET['elevesearch'])) {
		foreach ($req as $key => $value) {

			if ($_SESSION['typel']=='interne') {?>

				<a style="font-weight: bold; color: white;" href="activitesgestion.php?ideleve=<?=$value->matricule;?>"><div><?=$panier->infoseleve($value->matricule)[0];?></div></a><?php
			}else{?>

				<a style="font-weight: bold; color: white;" href="activitesgestion.php?ideleve=<?=$value->matricule;?>"><div><?=$value->nom.' '.$value->prenom;?></div></a><?php

			}
		}
	}


	
	
}

//echo "string";