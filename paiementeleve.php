<?php require 'headereleve.php';

    if (!empty($_SESSION['pseudo'])) {
        $nom='';
        $inscrit='';
        
        if ($products['niveau']<1) {?>

            <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

        }else{

            $numel=$_SESSION['matricule'];

            $products=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email , annee, nomf, formation.codef as codef, formation.niveau as classe, nomgr from inscription inner join contact on inscription.matricule=contact.matricule inner join eleve on inscription.matricule=eleve.matricule inner join formation on inscription.codef=formation.codef where inscription.matricule=:mat and annee=:promo', array('mat'=>$numel, 'promo'=>$_SESSION['promo']));

            $prodscol=$DB->querys('SELECT sum(montant) as montant from scolarite where codef=:code and promo=:promo', array('code'=>$products['codef'], 'promo'=>$_SESSION['promo']));

             $mensualite=$prodscol['montant'];

            $prodrem = $DB->querys('SELECT remise FROM inscription WHERE matricule = :mat and annee=:annee', array('mat'=> $numel, 'annee'=>$_SESSION['promo']));

            if ($prodrem['remise']>0) {
                
                $remise='Droit à une Remise de: '.$prodrem['remise'].'%';
            }else{
                $remise=' ';
            }

            $month = array(
                1   => '1ere tranche',
                2   => '2eme tranche',
                3   => '3eme tranche'
                
            );?>

            <div style="display: flex;">
                <div>
                    <table class="payement">
                        <thead>
                            <tr>
                                <th colspan="5">Historique des frais de scolarité payés <?php 
                                    /*
                                    <a style="margin-left: 10px;"href="fiche_inscription.php?ficheins=<?=$numel;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>
                                    <a style="margin-left: 10px;"href="printdoc.php?histscol=<?=$numel;?>&mens=<?=$mensualite;?>&nomel=<?=$nom;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>*/ ;?>
                                </th>
                            </tr>
                
                            <tr>
                                <th>Tranche</th>
                                <th>Montant</th>
                                <th>Date de paiement</th>
                                <th colspan="2">Réçu</th>
                            </tr>
                        </thead>

                        <tbody><?php

                            $montant=0;

                            $prodpaye = $DB->query('SELECT id, numpaye, matricule, montant, tranche, famille, typepaye, numpaie, banque, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, typepaye FROM histopayefrais WHERE matricule = :mat and promo=:promo ORDER BY(datepaye) DESC', array('mat'=> $numel, 'promo'=>$_SESSION['promo']));


                            if (!empty($prodpaye)) {

                                                                              
                                foreach ($prodpaye as $paye) {

                                    $montant+=$paye->montant;?>

                                    <tr>

                                        <td><?=ucfirst($paye->tranche);?></td>

                                        <td style="text-align: right;"><?=number_format($paye->montant,0,',',' ');?></td>

                                        <td><?='Payé le '.$paye->datepaye;?></td>

                                        <td style="text-align: center;" colspan="2"><?php 
                                            /*

                                            <a href="facture.php?numfac=<?=$paye->famille; ?>&tranche=<?=$paye->tranche; ?>&codef=<?=$products['codef'];?>&date=<?=$paye->datepaye; ?>&numel=<?=$numel;?>&type=<?=$paye->typepaye; ?>&nomel=<?=$nom;?>&daten=<?=$products['naissance'];?>&phone=<?=$products['phone'];?>&inscrit=<?=$inscrit;?>&groupel=<?=$products['nomgr'];?>&numpaie=<?=$paye->numpaie;?>&banque=<?=$paye->banque;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a> */ ;?>
                                        </td>

                                    
                                    </tr><?php
                                }

                            }?>

                        <tr>
                            <th style="padding: 10px;">Total payé :</th>
                            <th style="text-align: right;"><?=number_format($montant,0,',',' ');?></th>
                            <th style="color: red;" colspan="2"></th>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div style="margin-left: 30px;">

                <table class="payement">
                    <thead>
                        <tr>
                            <th style="color: orange;"><?='Remise: '.$prodrem['remise'];?>%</th>
                            <th colspan="3">Frais de scolarité payés <?php 
                                /*

                                <a style="margin-left: 10px;"href="printdoc.php?scoltot=<?=$numel;?>&mens=<?=$mensualite;?>&nomel=<?=$nom;?>&daten=<?=$products['naissance'];?>&phone=<?=$products['phone'];?>&inscrit=<?=$inscrit;?>&groupel=<?=$products['nomgr'];?>&promo=<?=$_SESSION['promo'];?>&codef=<?=$products['codef'];?>&remise=<?=$prodrem['remise'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                                */ ;?>
                            </th>
                        </tr>
            
                        <tr>
                            <th>Désignation</th>
                            <th>Montant</th>
                            <th colspan="2">Payé le</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>

                            <td><?='Inscrip/Reinscrip';?></td>

                            <td style="text-align: right;"><?=number_format($panier->fraisIns($numel, $_SESSION['promo'])[0],0,',',' ');?></td>

                            <td colspan="2"><?=(new dateTime($panier->fraisIns($numel, $_SESSION['promo'])[1]))->format('d/m/Y');?></td>

                            
                        </tr><?php

                        $montant=0;
                        foreach ($month as $key=> $mois) {

                            $prodpaye = $DB->query('SELECT id, numpaye, matricule, sum(montant) as montant, tranche, famille, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, typepaye FROM payementfraiscol WHERE matricule = :mat and tranche=:mois  and promo=:promo ORDER BY(datepaye) DESC', array('mat'=> $numel, 'mois'=>$mois,  'promo'=>$_SESSION['promo']));?>
                            <tr>

                                <td><?=ucfirst($mois);?></td><?php

                                if (!empty($prodpaye)) {

                                                                                                  
                                    foreach ($prodpaye as $paye) {

                                        $montant+=$paye->montant;?>

                                        <td style="text-align: right;"><?=number_format($paye->montant,0,',',' ');?></td>

                                        <td colspan="2"><?=$paye->datepaye;?></td><?php
                                    }

                                }else{

                                    if ($key<=date('m')) {?>

                                        <td style="text-align: right; color: red;"><?=number_format(0,0,',',' ');?></td>

                                        <td style="text-align: center;color: red;"><?='--';?></td>

                                        <td></td><?php
                                    }else{?>

                                        <td style="text-align: right;"><?=number_format(0,0,',',' ');?></td>

                                        <td style="text-align: center;"><?='--';?></td>
                                        <td></td><?php
                                    }

                                }?>
                            </tr><?php
                        }?>

                        <tr>
                            <th style="padding: 10px;">Total :</th>
                            <th style="text-align: right;"><?=number_format($montant+$panier->fraisIns($numel, $_SESSION['promo'])[0],0,',',' ');?></th>
                            <th colspan="2" style="color: orange;">Reste: <?=number_format($mensualite*(1-($prodrem['remise']/100))-($montant),0,',',' ');?></th>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div><?php 
    }
}