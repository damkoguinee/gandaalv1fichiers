<?php
require '_header.php';

if (isset($_GET['user'])) {
	$user=(string) trim($_GET['user']);

	$req=$DB->query('SELECT *FROM eleve left join contact on eleve.matricule=contact.matricule where prenomel LIKE ? or eleve.matricule LIKE ? or phone LIKE ? LIMIT 10',array("%".$user."%", "%".$user."%", "%".$user."%"));

	

	foreach ($req as $key => $value) {?>

		<a style="font-weight: bold; color: white;" href="versement.php?searchclientvers=<?=$value->matricule;?>&client&ajout"><div><?=$value->prenomel.' '.$value->nomel.' '.$value->matricule;?></div></a><?php
	}
	
}

//echo "string";