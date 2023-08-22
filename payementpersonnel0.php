 <?php
require 'header.php';

if (isset($_SESSION['pseudo'])) {
        
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

        <div class="col" style="display: flex;"><?php

            if (isset($_POST['numeen'])) {

                $_SESSION['numeen']=$_POST['numeen'];
                $numeen=$_SESSION['numeen'];

                $prodsocial=$DB->querys('SELECT montant from ssocialpers where numpers=:mat', array('mat'=>$numeen));

                $_SESSION['prodsocial']=$prodsocial['montant'];

                $prodsalaire=$DB->querys('SELECT salaire from salairepers where numpers=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));

                $_SESSION['salaire']=$prodsalaire['salaire'];
                $_SESSION['salaireact']='ok';

            }elseif (isset($_POST['payen'])) {

                $numeen=$_SESSION['numeen'];

            }else{

            }

            if (isset($_POST['mois'])) {

                
                $prodac=$DB->querys('SELECT montant from accompte where matricule=:mat and mois=:datet and anneescolaire=:promo', array('mat'=>$numeen, 'datet'=>$_POST['mois'], 'promo'=>$_SESSION['promo']));

                if (empty($prodac['montant'])) {
                    $montantac=0;
                }else{
                    $montantac=$product['montant'];
                }

                $salairep=$_SESSION['salaire']-$montantac-$_SESSION['prodsocial'];
            }

            if (isset($_GET['payecherc'])) {
      
              $_SESSION['numeen']=$_GET['payecherc'];
              $numeen=$_SESSION['numeen'];

            }

            if (isset($_GET['enseignant']) ) {

                $_SESSION['numeen']=$_GET['enseignant'];
                $numeen=$_SESSION['numeen'];
            }

            if (isset($_GET['personnel']) ) {

                $_SESSION['numeen']=$_GET['personnel'];
                $numeen=$_SESSION['numeen'];
            }

            if (isset($_GET['delepayeens'])) {

                $numeen=$_SESSION['numeen'];
            }

            if (isset($_POST['numeen']) or !empty($numeen) or isset($_GET['delepayeens']) or isset($_GET['enseignant'])) {

                $products=$DB->querys('SELECT numpers as mat, nom as nomen, prenom as prenomen, date_format(datenaissance,\'%d/%m/%Y \') as naissance, phone, email from personnel inner join contact on numpers=matricule where numpers=:mat', array('mat'=>$numeen));
         
            }else{
                $products = array();
            }?>


        <div><?php

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
            
        );?>

        <form action="payementpersonnel0.php" method="post" id="formulaire" style="background-color: grey;">                
            <fieldset><legend>Paiement du Personnels<?php

                if (isset($_POST['numeen']) or isset($_POST['payen']) or isset($_GET['enseignant']) or isset($_GET['personnel']) or isset($_GET['payecherc'])) {

                    require 'fichepersonnel.php';

                }?></legend> 
                <ol><?php

                    if (isset($_POST['numeen']) AND empty($products)) {?>

                        <div class="alertes">Numéro incorrect, <a style="color: red;" href="comptabilite.php?paye">réessayer ici</a></div><?php
                    }else{
                            
                        if (isset($_POST['numeen']) or isset($_POST['payen']) or isset($_GET['enseignant']) or isset($_GET['personnel']) or isset($_GET['payecherc'])) {?>

                            <li><label>N°Matricule</label><input  type="text" name="numeen" placeholder="N° du personnel" onchange="document.getElementById('formulaire').submit()" value="<?= $numeen; ?>" /><a href="enseignant.php?personnel&payempcherc=<?='payemp';?>&effnav" style=" color: white; font-weight: bold;">Rechercher </a></li><?php

                        }else{?>
                            <li><label>N°Matricule</label><input  type="text" name="numeen" placeholder="N° du personnel" onchange="document.getElementById('formulaire').submit()" /><a href="enseignant.php?personnel&payempcherc=<?='payemp';?>&effnav" style=" color: white; font-weight: bold;">Rechercher </a></li><?php                                
                        }?>

                        <li><label>Selectionnez le mois</label>

                            <select name="mois" required="" onchange="this.form.submit()"><?php

                            if (isset($_POST['mois'])) {?>
                                
                                <option value="<?=$_POST['mois'];?>" ><?=$panier->moisbul();?></option><?php

                            }else{?>

                                <option></option><?php
                            }

                                foreach ($month as $key => $mois) {?>

                                    <option value="<?=$key;?>"><?=$mois;?></option><?php

                                }?>
                            </select>
                        </li><?php

                        if (isset($_POST['mois'])) {?>

                            <li><label>Avance sur Salaire</label>

                                <input style="width: 20%;" type="text" name="acc" value="<?=$montantac;?>"/>

                                Cotisations <input style="width: 15%;" type="text" name="acc" value="<?=$_SESSION['prodsocial'];?>"/>
                            </li><?php
                        }?>

                        <li><label>Montant Net Payer </label><input  type="number" name="mp" value="<?=$salairep;?>" required="" min="0"  /></li>

                        <li><label>Type de Paiement</label><select name="typep" required="" >
                        <option value=""></option><?php 
                        foreach ($panier->modep as $value) {?>
                            <option value="<?=$value;?>"><?=$value;?></option><?php 
                        }?></select></li>

                        <li><label>Compte à debiter</label><select  name="compte" required="">
                            <option></option><?php
                            $type='Banque';

                            foreach($panier->nomBanque() as $product){?>

                                <option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
                            }?>
                            </select>
                        </li>

                        <li><label>Promotion</label>

                            <select type="text" name="promo" required=""><?php
                              
                                $annee=date("Y")+1;

                                for($i=($_SESSION['promo']-1);$i<=($_SESSION['promo']) ;$i++){
                                    $j=$i+1;?>

                                    <option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

                                }?>
                            </select>
                
                        </li><?php
                    }?> 

                </ol>

            </fieldset>

            <fieldset><input type="reset" value="Annuler" name="recnaiss" id="form" style="cursor: pointer;" /><input type="submit" value="Valider" name="payen" id="form" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>

        </form><?php

        if (isset($_GET['delepayeens'])) {

            $DB->delete('DELETE FROM payepersonnel WHERE numdec = ?', array($_GET['delepayeens']));

            $DB->delete('DELETE FROM banque WHERE numero=? and promob=?', array('paiepers'.$_GET['delepayeens'], $_SESSION['promo']));?>

            <div class="alerteV">Payement supprimé avec succèe</div><?php 
        }

        if(isset($_POST['payen']) && !empty($_POST['mois']) && !empty($_POST['mp']) && !empty($_POST['typep'])){

            if ($_POST['mp']>$panier->montantCompte($_POST['compte'])) {?>

                <div class="alertes">Echec montant decaissé est > au montant disponible</div><?php

            }else{

                $maxid = $DB->querys('SELECT max(id) as id FROM payepersonnel');
                                
                $numdec=$maxid['id']+1;

                $mois = $_POST['mois'];

                $prodrep = $DB->querys('SELECT mois FROM payepersonnel WHERE matricule = :mat and mois=:mois and promo=:promo', array('mat'=> $numeen, 'mois'=>$mois, 'promo'=>$_POST['promo']));

                if (empty($prodrep)) {

                    $DB->insert('INSERT INTO payepersonnel(numdec, matricule, montant, mois, motif, typepaye, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numdec, $numeen, $_POST['mp'], $mois, 'payement salaire', $_POST['typep'], $_POST['promo']));

                    $DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, now())', array($_POST['compte'], -$_POST['mp'], 'paiement personnel', 'paiepers'.$numdec, $numeen, $_POST['promo']));?>

                    <div class="alerteV">Payement effectué avec succèe!!</div><?php

                }else{?>

                    <div class="alertes">le mois selectionnez est déjà régularisé</div><?php 

                }
            }

        }?>
    </div>

    <div style="margin-left: 30px;">

        <ol><?php

            if ((isset($_POST['numeen']) or isset($_POST['payen'])) AND !empty($products) or isset($_GET['delepayeens']) or isset($_GET['enseignant']) or isset($_GET['personnel']) or isset($_GET['delehoraire']) or isset($_GET['payecherc'])) {?>

                <?php

                $nom=strtoupper($products['nomen']).' '.ucwords($products['prenomen']); //pour recuperer le nom dans le pdf?>

                <table class="payement" >
                    <thead>
                        <tr>
                            <th></th>
                            <th colspan="3">Salaires payés <a style="margin-left: 10px;"href="printdoc.php?paytotpers=<?=$numeen;?>&mens=<?=100;?>&nomel=<?=$nom;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
                        </tr>
            
                        <tr>
                            <th>Mois</th>
                            <th>Montant</th>
                            <th>Date paye</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody><?php

                        $montant=0;
                        $mensualite=0;
                        foreach ($month as $key=> $mois) {

                            $prodpaye = $DB->query('SELECT id, numdec, matricule, montant, mois, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payepersonnel WHERE matricule = :mat and mois=:mois and promo=:promo ORDER BY(datepaye) DESC', array('mat'=> $numeen, 'mois'=>$key, 'promo'=>$_SESSION['promo']));?>
                            <tr>

                                <td><?=ucfirst($mois);?></td><?php

                                if (!empty($prodpaye)) {

                                                                          
                                    foreach ($prodpaye as $paye) {

                                        $montant+=$paye->montant;
                                        $mensualite=$paye->montant; ?>

                                        <td style="text-align: right;"><?=number_format($paye->montant,0,',',' ');?></td>

                                        <td><?='Payé le '.$paye->datepaye;?></td>

                                        <td>
                                            <a href="printdoc.php?payepersfact=<?=$paye->numdec; ?>&date=<?=$paye->datepaye; ?>&numel=<?=$numeen;?>&type=<?=$paye->typepaye; ?>&nomel=<?=$nom;?>&mois=<?=$mois;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                                            <a href="payementpersonnel0.php?delepayeens=<?=$paye->numdec;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 77%; font-size: 16px; background-color: red; color: white; cursor: pointer"></a>
                                        </td><?php
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
                            <th style="padding: 10px;">Total payé :</th>
                            <th style="text-align: right;"><?=number_format($montant,0,',',' ');?></th>
                            <th style="color: red;">Reste: <?=number_format($mensualite*12-$montant,0,',',' ');?></th>
                        </tr>

                    </tbody>
                </table><?php

            }?>

        </ol>
    </div>

</div><?php
}
}?>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer cette facture ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>


    




    
