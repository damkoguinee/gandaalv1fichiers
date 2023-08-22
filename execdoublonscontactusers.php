<?php
require 'header.php';

//$DB->insert("DELETE table FROM table LEFT OUTER JOIN (SELECT MIN(id) as id, champ1, champ2, champ3 FROM table GROUP BY champ1, champ2, champ3 ) as ON table.id = t1.id WHERE t1.id IS NULL")

$doublons=$DB->query("SELECT   COUNT(phoneuser) AS doublons, mat_user, id, typeuser FROM contactusers GROUP BY mat_user HAVING   COUNT(*) > 1");

foreach ($doublons as $key => $value) {?>
    <pre><?=$value->id." ".$value->mat_user;?></pre><?php
    $DB->delete("DELETE FROM contactusers where id='{$value->id}'");
}

$doublons=$DB->query("SELECT   COUNT(mailuser) AS doublons, mat_user, id, typeuser FROM contactusers GROUP BY mat_user HAVING   COUNT(*) > 1");

foreach ($doublons as $key => $value) {?>
    <pre><?=$value->id." ".$value->mat_user;?></pre><?php
    $DB->delete("DELETE FROM contactusers where id='{$value->id}'");
}

