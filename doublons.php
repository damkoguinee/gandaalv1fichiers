<?php
require 'header.php';

$prod=$DB->query("SELECT matricule from inscription where annee='{$_SESSION['promo']}'");



foreach ($prod as $key => $value) {

    $prodc=$DB->querys("SELECT count(matricule) as nbre, matricule from login where matricule='{$value->matricule}'");

    if ($prodc['nbre']>1) {?>

        <pre><?='table contact '.$prodc['matricule'];?></pre><?php
    }

    $prodins=$DB->querys("SELECT count(matricule) as nbre, matricule from inscription where matricule='{$value->matricule}' and annee='{$_SESSION['promo']}'");

    if ($prodins['nbre']>1) {?>

        <pre><?='table insc '.$prodins['matricule'];?></pre><?php
    }


    $prodl=$DB->querys("SELECT count(matricule) as nbre, matricule from login where matricule='{$value->matricule}'");

    if ($prodl['nbre']>1) {?>

        <pre><?='table login '.$prodl['matricule'];?></pre><?php
    }


    $prodp=$DB->querys("SELECT count(matricule) as nbre, matricule from payement where matricule='{$value->matricule}'");

    if ($prodp['nbre']>1) {?>

        <pre><?='table paye '.$prodp['matricule'];?></pre><?php
    }

    if (empty($prodp['nbre'])) {?>

        <pre><?='ce numero ne figure pas insc'.$value->matricule;?></pre><?php
    }


    $prodt=$DB->querys("SELECT count(matricule) as nbre, matricule from tuteur where matricule='{$value->matricule}'");

    if ($prodt['nbre']>1) {?>

        <pre><?='table tuteur '.$prodt['matricule'];?></pre><?php
    }



}

$rech=$DB->query("SELECT matricule from contact where matricule not in (SELECT matricule from eleve)");

foreach ($rech as $key => $value) {
    echo $value->matricule.' ';
}

$depart=21001;

$initiale='csod';

while ($depart <=21500) {

    $numero=$initiale.$depart;

    $prodsup=$DB->querys("SELECT matricule from eleve where matricule='{$numero}'");

    if (empty($prodsup)) {?>

        <pre><?=' Le numero '.$depart.' n\'est pas attribué';?></pre><br/><?php
    }

    $depart++;
}
    
