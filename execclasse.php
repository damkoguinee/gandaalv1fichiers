<?php
require 'header.php';


$prodenseig=$DB->query("SELECT * from groupe where promo='{$_SESSION['promo']}'");

foreach ($prodenseig as $value) {

	$idclasse=$value->id;

	$DB->insert("UPDATE enseignement SET idclasse='{$idclasse}' where nomgr='{$value->nomgr}' and promo='{$_SESSION['promo']}'")
	;
}