<?php
require 'header.php';

//$DB->insert("DELETE table FROM table LEFT OUTER JOIN (SELECT MIN(id) as id, champ1, champ2, champ3 FROM table GROUP BY champ1, champ2, champ3 ) as ON table.id = t1.id WHERE t1.id IS NULL")

$doublons=$DB->query("SELECT   COUNT(*) AS doublons, nomgrp FROM events GROUP BY nomgrp HAVING   COUNT(*) > 1");

foreach ($doublons as $key => $value) {
    var_dump($value->nomgrp);
}

//$DB->delete('DELETE FROM eleve WHERE matricule = ?', array($_GET['del_eleve']));
