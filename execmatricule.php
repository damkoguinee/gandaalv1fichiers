<?php
require 'header.php';


$prodenseig=$DB->query('SELECT * from inscription');

foreach ($prodenseig as $value) {

	$DB->insert('INSERT INTO matricule(matricule, etat, annee) values( ?, ?, ?)', array($value->matricule, $value->etat, $value->annee));
}