<div class="col" style="display: flex; margin-bottom: 30px; width: 105%; "><?php

    if (isset($_POST['numeen'])) {

        $_SESSION['numeen']=$_POST['numeen'];
        $numeen=$_SESSION['numeen'];

        $prodsocial=$DB->querys('SELECT montant from ssocialens where numpers=:mat', array('mat'=>$numeen));

        $_SESSION['prodsocial']=$prodsocial['montant'];

        $prodsalaire=$DB->querys('SELECT salaire, thoraire from salaireens where numpers=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));


        if ($prodsalaire['salaire']==0) {
            
            $_SESSION['salaire']=$prodsalaire['thoraire'];
            $_SESSION['salaireact']='not';

        }else{

            $_SESSION['salaire']=$prodsalaire['salaire'];
            $_SESSION['salaireact']='ok';
        }


    }elseif (isset($_POST['payen'])) {

        $numeen=$_SESSION['numeen'];

    }else{

    }

    if (isset($_POST['mois'])) {

        if ($_POST['mois']<10) {
            
            $cmois='0'.$_POST['mois'];

        }else{

            $cmois=$_POST['mois'];
        }

        $prodh=$DB->querys('SELECT sum(heuret) as heuret from horairet where numens=:mat and date_format(datet,\'%m\')=:datet and annees=:promo', array('mat'=>$numeen, 'datet'=>$cmois, 'promo'=>$_SESSION['promo']));

        if (empty($prodh['heuret'])) {
            $totheure=0;
        }else{
            $totheure=$prodh['heuret'];
        }


        $prodac=$DB->querys('SELECT montant from accompte where matricule=:mat and mois=:datet and anneescolaire=:promo', array('mat'=>$numeen, 'datet'=>$_POST['mois'], 'promo'=>$_SESSION['promo']));

        if (empty($prodac['montant'])) {
            $montantac=0;
        }else{
            $montantac=$product['montant'];
        }

        if ($_SESSION['salaireact']=='not') {
            
            $salairep=$_SESSION['salaire']*$totheure-$montantac-$_SESSION['prodsocial'];

        }else{

            $salairep=$_SESSION['salaire']-$montantac-$_SESSION['prodsocial'];
        }
    }

    if (isset($_GET['enseignant']) ) {

        $_SESSION['numeen']=$_GET['enseignant'];
        $numeen=$_SESSION['numeen'];
        
    }

    if (isset($_GET['payecherc'])) {
      
      $_SESSION['numeen']=$_GET['payecherc'];
      $numeen=$_SESSION['numeen'];
      
    }

    
    if (isset($_GET['delepayeens'])) {

        $numeen=$_SESSION['numeen'];
    }

    if (isset($_GET['delehoraire'])) {

        $numeen=$_SESSION['numeen'];
    }

    if (isset($_POST['numeen']) or !empty($numeen) or isset($_GET['delepayeens']) or isset($_GET['enseignant']) or isset($_GET['payecherc'])) {

        if (isset($_POST['numeen']) or isset($_GET['payecherc']) or isset($_GET['delepayeens']) or isset($_GET['delehoraire'])) {

            $products=$DB->querys('SELECT enseignant.matricule as mat, nomen, prenomen, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email from enseignant inner join contact on enseignant.matricule=contact.matricule where enseignant.matricule=:mat', array('mat'=>$numeen));
        }
         
    }else{
        $products = array();
    }

    if (!isset($_GET['payeem'])) {?>

        <div style="width:60%;"><?php

    }else{?>

        <div style="width:100%"><?php        
    }


    

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

        <form action="comptabilite.php" method="post" id="formulaire" style="background-color: grey; ">                
            <fieldset><legend>Payement des Enseignants 
                (Rechercher un <a href="enseignant.php?payempcherc=<?='payemp';?>&effnav" style=" color: white; font-weight: bold;">Enseignant </a>)<?php

                if (isset($_POST['numeen']) or isset($_POST['payen']) or isset($_GET['enseignant']) or isset($_GET['personnel']) or isset($_GET['payecherc'])) {

                    require 'fichepersonnel.php';

                }?></legend> 
                <ol style="margin-top: -10px;"><?php

                    if (isset($_POST['numeen']) AND empty($products)) {?>

                        <div class="alertes">Numéro incorrect, <a style="color: red;" href="comptabilite.php?paye">réessayer ici</a></div><?php
                    }else{
                            
                        if (isset($_POST['numeen']) or isset($_POST['payen']) or isset($_GET['enseignant']) or isset($_GET['personnel']) or isset($_GET['payecherc'])) {?>

                            <li><label>N°Matricule</label><input  type="text" name="numeen" placeholder="N° matricule" onchange="document.getElementById('formulaire').submit()" value="<?= $numeen; ?>" /></li><?php

                        }else{?>
                            <li><label>N°Matricule</label><input  type="text" name="numeen" placeholder="N° matricule" onchange="document.getElementById('formulaire').submit()" /></li><?php                                
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

                            <li><label>Heure(s) Payées</label>

                                <input type="text" name="heuret" value="<?=$totheure;?>"/>heure(s)
                            </li>

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

            if (!isset($_GET['deledefinitif'])) {

                $prodrep = $DB->querys('SELECT montant, heurep FROM payenseignant WHERE matricule = :mat and mois=:mois and anneescolaire=:promo', array('mat'=> $_GET['mat'], 'mois'=>$_GET['mois'], 'promo'=>$_SESSION['promo']));

                $restemontant=$prodrep['montant']-$_GET['montant'];
                $resteheurep=$prodrep['heurep']-$_GET['heurep'];

                $DB->insert('UPDATE payenseignant SET montant=?, heurep=?, datepaye=now() where matricule=? and mois=?' ,array($restemontant, $resteheurep, $_GET['mat'], $_GET['mois']));

                $DB->delete('DELETE FROM histopayenseignant WHERE id = ?', array($_GET['delepayeens']));

                $DB->delete('DELETE FROM banque WHERE numero=? and promob=?', array('paiens'.$_GET['numdec'], $_SESSION['promo']));


                $prodrep = $DB->querys('SELECT montant, heurep FROM payenseignant WHERE matricule = :mat and mois=:mois and anneescolaire=:promo', array('mat'=> $_GET['mat'], 'mois'=>$_GET['mois'], 'promo'=>$_SESSION['promo']));

                if ($prodrep['montant']==0) {
                   
                   $DB->delete('DELETE FROM payenseignant WHERE mois = ? and matricule=?', array($_GET['mois'], $_GET['mat']));
                }
            }else{

                $DB->delete('DELETE FROM payenseignant WHERE id = ?', array($_GET['delepayeens']));

                $DB->delete('DELETE FROM histopayenseignant WHERE mois = ? and matricule=?', array($_GET['deledefinitif'], $_GET['mat']));
                
            }?>

            <div class="alerteV">Payement supprimé avec succèe</div><?php 
        }


        if (isset($_GET['delehoraire'])) {

            $DB->delete('DELETE FROM horairet WHERE id = ?', array($_GET['delehoraire']));?>

            <div class="alerteV">Heure(s) supprimées avec succèe</div><?php 
        }


        if(isset($_POST['payen']) && !empty($_POST['mp']) && !empty($_POST['typep'])){

            if ($_POST['mp']>$panier->montantCompteT($_POST['compte'])) {?>

                <div class="alertes">Echec montant decaissé est > au montant disponible</div><?php

            }else{

                $maxid = $DB->querys('SELECT max(id) as id FROM payenseignant');
                                
                $numdec=$maxid['id']+1;

                if (!empty($_POST['heuret'])) {

                    $heure= $_POST['heuret'];

                }else{
                    $heure=0;

                }

                
                $mois=$_POST['mois'];

                $prodrep = $DB->querys('SELECT montant, heurep FROM payenseignant WHERE matricule = :mat and mois=:mois and anneescolaire=:promo', array('mat'=> $numeen, 'mois'=>$mois, 'promo'=>$_POST['promo']));

                $cumulmontant=$prodrep['montant']+$_POST['mp'];
                $cumulheure=$prodrep['heurep']+$heure;

                if (empty($prodrep)) {

                    $DB->insert('INSERT INTO payenseignant(numdec, matricule, montant, mois, heurep, motif, typepaye, anneescolaire, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())',array($numdec, $numeen, $_POST['mp'], $mois, $heure, 'payements des enseignants', $_POST['typep'], $_POST['promo']));

                }else{

                    $DB->insert('UPDATE payenseignant SET montant=?, heurep=?, datepaye=now() where matricule=? and mois=?' ,array($cumulmontant, $cumulheure, $numeen, $mois));


                }

                $DB->insert('INSERT INTO histopayenseignant(numdec, matricule, montant, mois, heurep, typepaye, anneescolaire, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numdec, $numeen, $_POST['mp'], $mois, $heure, $_POST['typep'], $_POST['promo']));

                $DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, now())', array($_POST['compte'], -$_POST['mp'], 'paiement enseignant', 'paiens'.$numdec, $numeen, $_POST['promo']));?>

                <div class="alerteV">Payement effectué avec succèe!!</div><?php
            }

        }?>
    </div><?php

    if (!isset($_GET['payeem'])) {?>

         <div style="margin-left: 60px; margin-right: 0px; width: 60%;"><?php

    }else{?>

         <div style="margin-left: 60px; margin-right: 0px; width:0%;"><?php        
    }

        if ((isset($_POST['numeen']) or isset($_POST['payen'])) AND !empty($products) or isset($_GET['delepayeens']) or isset($_GET['enseignant']) or isset($_GET['delehoraire']) or isset($_GET['payecherc'])) {

            $nom=strtoupper($products['nomen']).' '.ucwords($products['prenomen']); //pour recuperer le nom dans le pdf?>

            <table class="payement" >
                <thead>
                    <tr>
                        <th></th>
                        <th colspan="4">Salaires payés <a style="margin-left: 10px;"href="printdoc.php?paytotemp=<?=$numeen;?>&mens=<?=100;?>&nomel=<?=$nom;?>&motif=<?="Payements des enseignants";?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
                    </tr>
        
                    <tr>
                        <th>Mois</th>
                        <th>Heure(s)</th>
                        <th>Montant</th>
                        <th>Date paye</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody><?php

                    $montant=0;
                    $heuret=0;

                    foreach ($month as $key=> $mois) {

                        $prodpaye = $DB->query('SELECT id, numdec, matricule, montant, mois, heurep, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payenseignant WHERE matricule = :mat and mois=:mois and anneescolaire=:promo ORDER BY(datepaye) DESC', array('mat'=> $numeen, 'mois'=>$key, 'promo'=>$_SESSION['promo']));?>

                        <tr>

                            <td><a href="historiquepayenseignant.php?mois=<?=$key;?>&mat=<?=$numeen;?>&periode=<?=$mois;?>"><?=ucfirst($mois);?></a></td><?php

                            if (!empty($prodpaye)) {
                                                                  
                                foreach ($prodpaye as $paye) {

                                    $montant+=$paye->montant;
                                    $heuret+=$paye->heurep; ?>

                                    <td style="text-align: center;"><?=$paye->heurep;?> h</td>

                                    <td style="text-align: right;"><?=number_format($paye->montant,0,',',' ');?></td>

                                    <td><?='Payé le '.$paye->datepaye;?></td>

                                    <td>
                                        <a href="printdoc_mini.php?payehemp=<?=$paye->numdec; ?>&date=<?=$paye->datepaye; ?>&numel=<?=$numeen;?>&type=<?=$paye->typepaye; ?>&nomel=<?=$nom;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>
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


            <table class="payement">
                <thead>
                    <tr>
                        <th></th>
                        <th colspan="6">Heure(s) effectuées <a style="margin-left: 10px;"href="printdoc_mini.php?horairemp=<?=$numeen;?>&nomel=<?=$nom;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
                    </tr>

                    <tr>
                        <th height="25">Jour</th>
                        <th>Groupe</th>
                        <th>Matieres</th>
                        <th>H. debut</th>
                        <th>Nbre H.</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody><?php

                    $heuret=0;

                    $prodpaye = $DB->query('SELECT horairet.id as id, numens, heuret, heured, nommat, DATE_FORMAT(datet, \'%d/%m/%Y\')AS datet, groupe FROM horairet inner join matiere on matiere=codem WHERE numens = :mat and annees=:promo ORDER BY(datet) DESC', array('mat'=> $numeen, 'promo'=>$_SESSION['promo']));

                                                                  
                    foreach ($prodpaye as $paye) {

                        $heuret+=$paye->heuret; ?>

                        <tr>

                            <td style="text-align: center;"><?=$paye->datet;?> </td>

                            <td style="text-align: center;"><?=$paye->groupe;?> </td>

                            <td><?=ucwords($paye->nommat);?> </td>

                            <td style="text-align: right;"><?=$paye->heured;?></td>

                            <td style="text-align: center;"><?=$paye->heuret;?> h</td>

                            <td>
                                <a href="printdoc_mini.php?heuretr=<?=$paye->id; ?>&date=<?=$paye->datet; ?>&numens=<?=$numeen;?>&nomel=<?=$nom;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                                <a href="comptabilite.php?delehoraire=<?=$paye->id;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 77%; font-size: 16px; background-color: red; color: white; cursor: pointer"></a>
                            </td>
                        </tr><?php
                    }?>

                    <tr>
                        <th colspan="4"></th>
                        <th><?=$heuret;?> h</th>
                        <th></th>
                    </tr>

                </tbody>
            </table><?php

        }?>
    </div>

</div>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer cette facture ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>


    
