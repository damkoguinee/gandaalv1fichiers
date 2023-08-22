<?php require 'headerenseignant.php'?><?php

$month = array(
    10  => 'Octobre',
    11  => 'Novembre',
    12  => 'Décembre',
    1   => 'Janvier',
    2   => 'Février',
    3   => 'Mars',
    4   => 'Avril',
    5   => 'Mai',
    6   => 'Juin',
    7   => 'Juillet',
    8   => 'Août',
    9   => 'Septembre'
    
);

$nom=$panier->nomPersonnel($_GET['enseignantsalaires']);
$numeen=$_GET['enseignantsalaires']; //pour recuperer le nom dans le pdf?>

<div style="display: flex; margin: auto;">
    <div style="margin-right: 30px;">

        <table class="payement" >
            <thead>
                <tr>
                    <th></th>
                    <th colspan="4">Mes Paiements <a style="margin-left: 10px;"href="printdoc.php?paytotemp=<?=$numeen;?>&mens=<?=100;?>&nomel=<?=$nom;?>&motif=<?="Payements des enseignants";?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
                </tr>

                <tr>
                    <th>Mois</th>
                    <th>Heure(s)</th>
                    <th>Montant Payé</th>
                    <th>Date de Paie</th>
                    <th>Fiche de Paie</th>
                </tr>
            </thead>

            <tbody><?php

                $montant=0;
                $heuret=0;

                foreach ($month as $key=> $mois) {

                    $prodpaye = $DB->query('SELECT id, numdec, matricule, montant, mois, heurep, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payenseignant WHERE matricule = :mat and mois=:mois and anneescolaire=:promo ORDER BY(datepaye) DESC', array('mat'=> $numeen, 'mois'=>$key, 'promo'=>$_SESSION['promo']));?>

                    <tr>

                        <td><?=ucfirst($mois);?></td><?php

                        if (!empty($prodpaye)) {
                                                              
                            foreach ($prodpaye as $paye) {

                                $montant+=$paye->montant;
                                $heuret+=$paye->heurep; ?>

                                <td style="text-align: center;"><?=$paye->heurep;?> h</td>

                                <td style="text-align: right; padding-right: 5px;"><?=number_format($paye->montant,0,',',' ');?></td>

                                <td><?='Payé le '.$paye->datepaye;?></td>

                                <td style="text-align: center;">
                                    <a href="fichedepaieens.php?payehemp&matensind=<?=$numeen;?>&moisens=<?=$mois;?>&moisnum=<?=$paye->mois;?>&niveau=<?='secondaire';?>&enseignant" target="_blank"><img  style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a>
                                </td><?php
                            }
                        }else{

                            if ($key<=date('m')) {?>

                                <td style="text-align: center; color: red;"><?='00:00';?></td>

                                <td></td>

                                <td style="text-align: right;color: red;"><?='--';?></td>

                                
                                <td></td><?php
                            }else{?>

                                <td style="text-align: center;"><?='00:00';?></td>

                                <td style="text-align: right;"><?='--';?></td>

                                <td></td>
                                <td></td><?php
                            }

                        }?>
                    </tr><?php
                }?>

                <tr>
                    <th></th>
                    <th><?=$heuret;?> h</th>
                    <th style="text-align: right;"><?=number_format($montant,0,',',' ');?></th>
                    <th></th>

                    <th></th>
                </tr>

            </tbody>
        </table>
    </div>

    <div>


        
    </div>
</div>