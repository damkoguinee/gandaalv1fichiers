<?php
$moyengenerale=0;

foreach ($prodmat as $matricule) {
    $totm1=0;
    $coefm1=0;

    
    foreach ($prodmatiere as $matiere) {

        require 'requetebul.php';
                
        foreach ($prodm1 as $moyenne) {
            $totm1+=($moyenne->mgen*$moyenne->coef);

            $coefm1+=$moyenne->coef;
            
        }
    }

    if (!empty($coefm1)) {

        $moyenmat=($totm1/$coefm1);
        $moyengenerale+=$moyenmat;

        $DB->insert('INSERT INTO rangel(matricule, moyenne, rang) values( ?, ?, ?)', array($matricule->matricule, $moyenmat, 1));

    }else{
        $moyengenerale=0;

    }
}

$prodmin=$DB->querys('SELECT  min(moyenne) as mpetite from rangel ');
$prodmax=$DB->querys('SELECT  max(moyenne) as mgrande from rangel ');

$DB->delete('DELETE FROM rangel'); // Pour supprimer imediatement la liste des admis

$mpetite=$prodmin['mpetite'];
$mgrande=$prodmax['mgrande'];

$moyenneecart=$moyengenerale/$nbrele;