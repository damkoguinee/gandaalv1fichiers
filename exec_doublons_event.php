<?php
require 'header.php';

//$DB->insert("DELETE table FROM table LEFT OUTER JOIN (SELECT MIN(id) as id, champ1, champ2, champ3 FROM table GROUP BY champ1, champ2, champ3 ) as ON table.id = t1.id WHERE t1.id IS NULL")

$doublons=$DB->query("SELECT   COUNT(codensp) AS doublons, codensp, id FROM events GROUP BY codensp HAVING   COUNT(*) > 1");

foreach ($doublons as $key => $value) {?>
    <pre><?=$value->id." ".$value->codensp;?></pre><?php
    //$DB->delete("DELETE FROM contact where id='{$value->id}'");
}

