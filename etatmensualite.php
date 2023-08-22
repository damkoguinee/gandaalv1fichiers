<?php

$classe=$data['nomgr'];
$codef=$data['codef'];
$remise=$data['remise'];
$etat='actif';

foreach ($panier->tranche() as $tranche) {
    $_SESSION['mensuellec']=$tranche->nom;  

    $prodscol = $DB->querys('SELECT montant, limite FROM scolarite WHERE codef=:codef and tranche=:mois and promo=:promo', array('codef'=>$codef,'mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['promo']));

    $montantscol=$prodscol['montant'];
    $limite=(new dateTime($prodscol['limite']))->format("Ymd");
    $now=date("Ymd");

    $_SESSION['montantscol']=$montantscol;


    $reste1=0;
    $reste2=0;
    $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['promo'], 'code'=>$codef));

    if (empty($prodscol)) {
        $montantscol=0;
    }else{

        $montantscol=$prodscol['montant'];
    }

    $prodcredit =$DB->querys('SELECT payementfraiscol.id as id, sum(montant) as montant, remise FROM payementfraiscol inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE promo=:promo and annee=:promoins and payementfraiscol.matricule=:mat and tranche=:mois', array('promo'=>$_SESSION['promo'], 'promoins'=>$_SESSION['promo'], 'mat' => $mat, 'mois'=>$_SESSION['mensuellec']));
    $prodrem =$DB->querys('SELECT remise FROM inscription WHERE annee=:promoins and matricule=:mat', array('promoins'=>$_SESSION['promo'], 'mat' => $mat));
    if (empty($prodcredit['id']) and $now>=$limite) {?>
        <div class="alert alert-danger fw-bold fs-4 text-center">NOK<?=$_SESSION['mensuellec'];?></div><?php
    }else{    

        if (empty($prodcredit['id'])) {

        }elseif($remise==100){

        }else{

            $resterem=$montantscol-(($prodcredit['montant']+(($prodrem['remise']/100)*$montantscol)));
            if ($resterem!=0) {?>
                <div class="alert alert-danger fw-bold text-center">NOK <?=$_SESSION['mensuellec'];?></div><?php
            }
            
        } 
    }               
}?>


