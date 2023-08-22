<?php
require 'header.php';
$prod=$DB->query("SELECT *from events ");
//var_dump((new dateTime("2023-06-08"))->format("w"));
foreach ($prod as $key => $value) {
    $jours=(new dateTime($value->debut))->format("w");
    $mois=(new dateTime($value->debut))->format("m");
    $semaine=(new dateTime($value->debut))->format("W");    
    $DB->insert("UPDATE events SET moisEvent='{$mois}', semaineEvent='{$semaine}', joursEvent='{$jours}' where id='{$value->id}'  ");    
}
$mois=866;
$mois1=8;
$jours=7;
$DB->delete("DELETE FROM events WHERE moisEvent='{$mois}' or moisEvent='{$mois1}' or joursEvent='{$jours}' ");


