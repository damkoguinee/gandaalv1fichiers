<?php

require 'headerv2.php';
$classe = "5A/A";

$liste_classe = $panier->listeClasse();

foreach ($liste_classe as $key => $classe) {

    $prod_effectif = $DB->query("SELECT distinct(matricule) as matricule from effectifn where promo = '{$_SESSION['promo']}' and nomgr = '{$classe->nomgr}' "); ?>
    <div class="alert alert-danger"><?=$classe->nomgr;?></div><?php

    foreach ($prod_effectif as $key => $value) {
        $prod_notes = $DB->querys("SELECT distinct(note.matricule) as matricule from note inner join devoir on codev = devoir.id inner join inscription on inscription.matricule = note.matricule where promo = '{$_SESSION['promo']}' and nomgroupe = '{$classe->nomgr}' and nomgr = '{$classe->nomgr}' and note.matricule = '{$value->matricule}' ");

        if (empty($prod_notes)) {?>
            <pre>matricule:<?=$value->matricule;?></pre><?php
            // $DB->delete("DELETE FROM effectifn WHERE promo = '{$_SESSION['promo']}' and nomgr = '{$classe->nomgr}' and matricule = '{$value->matricule}' ");
        }
    }
}