<?php
require 'header.php';
$prodenseig=$DB->query("SELECT * from inscription where annee='{$_SESSION['promo']}'");

foreach ($prodenseig as $value) {

    $verif=$DB->querys("SELECT id,nomel, prenomel from eleve where matricule='{$value->matricule}' ");
    //$verif=$DB->querys("SELECT id from payementfraiscol where matricule='{$value->matricule}' ");

    if (empty($verif['id'])) {?>
        <br><?=$value->matricule;?></br><?php
    }
}