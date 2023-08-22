<?php
require '_header.php';
if (isset($_GET['user'])) {
	$user=(string) trim($_GET['user']);

	$req=$DB->query('SELECT *FROM eleve inner join contact on eleve.matricule=contact.matricule where eleve.matricule LIKE ? or nomel LIKE ? or prenomel LIKE ? or phone LIKE ? LIMIT 10',array("%".$user."%", "%".$user."%", "%".$user."%", "%".$user."%"));

	if (isset($_GET['elevesearch'])) {
		foreach ($req as $key => $value) {?>

			<a style="font-weight: bold; color: white;" href="activitespaie.php?ideleve=<?=$value->matricule;?>"><div><?=$panier->infoseleve($value->matricule)[0];?></div></a><?php
		}
	}


	
	
}

//echo "string";