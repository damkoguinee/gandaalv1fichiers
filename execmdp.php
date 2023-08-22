<?php
require 'header.php';


$prodenseig=$DB->query('SELECT * from login');

foreach ($prodenseig as $value) {

	$mdp=password_hash($value->mdp, PASSWORD_DEFAULT);

	$DB->insert("UPDATE login SET mdp='{$mdp}' where matricule='{$value->matricule}'")
	;
}