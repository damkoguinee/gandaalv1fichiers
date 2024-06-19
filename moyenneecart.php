<?php
$moyengenerale=0;

if (isset($_GET['annuel']) and ($_SESSION['groupe'] == '6A/A' or $_SESSION['groupe'] == '6A/B' or $_SESSION['groupe'] == '6A/C') ) {

    foreach ($prodmat as $keye=> $matricule) {
        $trimes_an = 4;
        $prodmoyA=$DB->querys("SELECT ROUND(AVG(moyenne),2) as moyenne from relevegeneralebul  where moyenne!=0 and matricule='{$matricule->matricule}' and pseudo='{$_SESSION['pseudo']}' and promo='{$_SESSION['promo']}' and trimestre='{$trimes_an}' ");
        
    
       
        $DB->insert('INSERT INTO rangel(matricule, moyenne, rang, pseudo) values( ?, ?, ?, ?)', array($matricule->matricule, $prodmoyA['moyenne'], 1, $_SESSION['pseudo']));
        
    
    
    }
}else{

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
    
            $DB->insert('INSERT INTO rangel(matricule, moyenne, rang, pseudo) values( ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 1, $_SESSION['pseudo']));
    
        }else{
            $moyengenerale=0;
    
        }
    }
}




$prodmin=$DB->querys("SELECT  min(moyenne) as mpetite from rangel where pseudo='{$_SESSION['pseudo']}'");
$prodmax=$DB->querys("SELECT  max(moyenne) as mgrande from rangel where pseudo='{$_SESSION['pseudo']}' ");

// $DB->delete("DELETE FROM rangel where pseudo='{$_SESSION['pseudo']}'"); // Pour supprimer imediatement la liste des admis

$mpetite=$prodmin['mpetite'];
$mgrande=$prodmax['mgrande'];

if ($mgrande == 8.574999809265137) {
    $mgrande = 8.58;
}

if (empty($nbrele)) {
    $moyenneecart=$moyengenerale/1;
}else{
    $moyenneecart=$moyengenerale/$nbrele;
}

