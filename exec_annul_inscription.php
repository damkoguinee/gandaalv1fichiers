<?php
require 'headerv2.php';


$prodenseig=$DB->query("SELECT *from eleve ");


foreach ($prodenseig as $value) {
    echo $value->matricule;

    $prodpaie=$DB->querys("SELECT *from payement where promo='{$_SESSION['promo']}' and matricule='{$value->matricule}' ");
    
    if ($prodpaie['montant']==0) {

        $DB->delete('DELETE FROM payement WHERE matricule = ? and promo = ?', array($value->matricule, $_SESSION['promo']));

        $DB->delete('DELETE FROM inscription WHERE matricule = ? and annee = ?', array($value->matricule, $_SESSION['promo']));
    }
}