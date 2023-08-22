    <?php
    require 'header.php';

    if (!empty($_SESSION['pseudo'])) {
        
        if ($products['niveau']<4) {?>

            <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

        }else{

            if (isset($_GET['note'])) {

                require 'navcompta.php';
            }

            if (isset($_POST['numel'])) {

                $_SESSION['numel']=$_POST['numel'];
                $numel=$_SESSION['numel'];

            }elseif (isset($_POST['payel'])) {

                $numel=$_SESSION['numel'];

            }else{

            }

            if (isset($_GET['delepaye'])) {

                $numel=$_SESSION['numel'];
            }

            if (isset($_GET['eleve']) ) {

                $_SESSION['numel']=$_GET['eleve'];
                $numel=$_SESSION['numel'];
            }


            if (isset($_POST['numel']) or isset($_GET['eleve']) or !empty($numel) or isset($_GET['delepaye'])) {

                $products=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email , annee, nomf, formation.codef as codef, classe, nomgr from inscription inner join contact on inscription.matricule=contact.matricule inner join eleve on inscription.matricule=eleve.matricule inner join formation on inscription.codef=formation.codef where inscription.matricule=:mat and annee=:promo', array('mat'=>$numel, 'promo'=>$_SESSION['promo']));

                $prodscol=$DB->querys('SELECT sum(montant) as montant from scolarite where codef=:code and promo=:promo', array('code'=>$products['codef'], 'promo'=>$_SESSION['promo']));

                $mensualite=$prodscol['montant'];
                 
            }else{
                $products = array();
            }?>

            <div style="display: flex;">


            <div><?php

                $month = array(
                    1   => '1ere tranche',
                    2   => '2eme tranche',
                    3   => '3eme tranche'
                    
                );

                

                if (isset($_GET['payeem']) or isset($_POST['numeen']) or isset($_GET['enseignant']) or isset($_GET['personnel']) or isset($_POST['payen']) or isset($_GET['delepayeens']) or isset($_GET['delehoraire']) or isset($_GET['payecherc'])) {

                    require 'payementemployer0.php';

                }elseif (isset($_GET['compta']) or isset($_POST['jour']) or isset($_POST['mensuelle']) or isset($_POST['annee'])) {

                    require 'synthesecompta.php';

                }elseif (isset($_GET['horaire']) or isset($_POST['horairet']) or isset($_POST['matriens']) or isset($_GET['horairecherc'])) {

                    require 'horaire.php';

                }else{

                    if (isset($_POST['numel']) or isset($_GET['eleve']) or isset($_GET['delepaye'])){

                        $prodrem = $DB->querys('SELECT remise FROM inscription WHERE matricule = :mat and annee=:annee', array('mat'=> $numel, 'annee'=>$_SESSION['promo']));

                        if ($prodrem['remise']>0) {
                            
                            $remise='Droit à une Remise de: '.$prodrem['remise'].'%';
                        }else{
                            $remise=' ';
                        }
                    }?>

                    <form action="comptabilite.php" method="post" id="formulaire" style="background-color: grey;"><?php

                        if (isset($_POST['numel']) or isset($_GET['eleve'])){?>

                            <fieldset><legend>Payement des frais de scolarité de<?php

                                if (isset($_POST['numel']) or isset($_GET['eleve']) or isset($_POST['payel'])) {

                                    require 'ficheeleve.php';

                                }?><?=$remise;?></legend> <?php

                        }else{?>

                            <fieldset><legend>Payement des frais de scolarité </legend> <?php

                        }?>
                            <ol style="margin-top: -10px;"><?php

                                if (isset($_POST['numel']) AND empty($products)) {?>

                                    <div class="alertes">Numéro incorrect, <a style="color: red;" href="comptabilite.php?paye">réessayer ici</a></div><?php
                                }else{
                                        
                                    if (isset($_POST['numel']) or isset($_GET['eleve']) or isset($_POST['payel'])) {?>

                                        <li><label>N°Matricule</label><input  type="text" name="numel" placeholder="N° matricule" onchange="document.getElementById('formulaire').submit()" value="<?= $numel; ?>" /><a href="ajout_eleve.php?listeeleve&cherceleve" style=" color: white; font-weight: bold;">Rechercher un matricule </a></li><?php

                                    }else{?>
                                        <li><label>N°Matricule</label><input  type="text" name="numel" placeholder="N° matricule" onchange="document.getElementById('formulaire').submit()" /><a href="ajout_eleve.php?listeeleve&cherceleve" style=" color: white; font-weight: bold;">Rechercher un matricule </a></li><?php                                
                                    }?>

                                    <li><label>Type de Réçu</label><select name="famille" required="" >
                                        <option></option>
                                        <option value="simple">Simple</option>
                                        <option value="multiple">Multiple</option>
                                    </select></li>

                                    <li><label>Solder toutes les tranches</label><input type="checkbox" name="tott" style="width: 100px; height:20px;"></li>

                                    <li><label style="padding-top: 30px;">Selectionnez la tranche</label>

                                        <select type="text" name="tranche[]" multiple >
                                            <option>Selectionnez!!</option><?php
                                            foreach ($panier->tranche() as $value) {?>
                                                <option value="<?=$value->nom;?>"><?=ucwords($value->nom);?></option><?php
                                            }?>
                                            <option value="inscript">Frais ins/Reins</option>
                                        </select>

                                    </li>

                                    <li><label>Montant à Payer </label><input  type="number" name="mp"/>
                                    </li>

                                    <li><label>Type de Payement</label><select name="typep" required="" >
                                    <option value=""></option>
                                    <option value="espèces">Espèces</option>
                                    <option value="chèque">Chèque</option>
                                    <option value="virement">Virement</option></select></li>

                                    <li><label>Année-scolaire</label>

                                        <select type="text" name="promo" required="">
                                            <option></option><?php
                                          
                                            $annee=date("Y")+1;

                                            for($i=2020;$i<=$annee ;$i++){
                                                $j=$i+1;?>

                                                <option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

                                            }?>
                                        </select>
                            
                                    </li><?php
                                }?> 

                            </ol>

                        </fieldset>

                        <fieldset><input type="reset" value="Annuler" id="form" style="cursor: pointer;" /><input type="submit" value="Valider" name="payel" id="form" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>

                    </form><?php
                }

                if (isset($_GET['delepaye'])) {

                    $DB->delete('DELETE FROM payementfraiscol WHERE id = ?', array($_GET['delepaye']));

                    $DB->delete('DELETE FROM histopayefrais WHERE tranche= ? and matricule=?', array($_GET['tranche'], $_GET['matr']));?>

                    <div class="alerteV">Payement supprimé avec succèe</div><?php
                }

                if (isset($_GET['delins'])) {

                    $DB->delete('DELETE FROM payement WHERE matricule = ? and promo=?', array($_GET['delins'], $_SESSION['promo']));?>

                    <div class="alerteV">Payement supprimé avec succèe</div><?php
                }

                if (isset($_POST['tott']) and !empty($_POST['tott'])) {

                    require 'famillemultiple.php';

                    $verifpaie = $DB->querys("SELECT matricule FROM payementfraiscol where promo='{$_POST['promo']}' and matricule='{$numel}' ");

                    if (!empty($verifpaie)) {?>
                        <div class="alertes">Un paiement est déjà éffectué, proceder aux paiements en plusieurs fois</div><?php 
                    }else{                        

                        foreach($panier->tranche as $mois){

                            $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE codef=:code and tranche=:mois and promo=:promo', array('code'=>$products['codef'], 'mois'=>$mois, 'promo'=>$_POST['promo']));

                                        $montant=$prodrep['montant']+$_POST['mp'];

                                        $maxid = $DB->querys('SELECT max(numpaye) as id FROM histopayefrais');
                                        
                                        $numpaye=$maxid['id']+1;

                                        if ($_POST['famille']=='simple') {
                                            $famille=$numpaye;
                                        }

                                        if (empty($prodrep)) {

                                            $DB->insert('INSERT INTO payementfraiscol(numpaye, matricule, montant, tranche, typepaye, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $_POST['mp'], $mois, $_POST['typep'], $_POST['promo']));

                                            if ($_POST['famille']!='simple') {

                                                $cumul = $DB->querys("SELECT matricule, famille, montant FROM histopayefrais where famille='{$famille}' and matricule='{$numel}' ");

                                                if (empty($cumul)) {
                                                    $DB->insert('INSERT INTO histopayefrais(numpaye, matricule, montant, tranche, typepaye, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $_POST['mp'], $mois, $_POST['typep'], $_POST['promo'], $famille));
                                                }else{

                                                    $montantc=$cumul['montant']+$_POST['mp'];

                                                    $DB->insert('UPDATE histopayefrais SET montant=? where matricule=? and famille=?' ,array($montantc, $numel, $famille));
                                                }

                                            }else{
                                                $DB->insert('INSERT INTO histopayefrais(numpaye, matricule, montant, tranche, typepaye, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $_POST['mp'], $mois, $_POST['typep'], $_POST['promo'], $famille));
                                            }?>

                                            <div class="alerteV">Payement effectué avec succèe!!</div><?php

                                        }elseif(($prodrep['montant']+$prodscol['montant']*($prodrem['remise']/100))<$prodscol['montant']){

                                            if (($montant+$prodscol['montant']*($prodrem['remise']/100))<=$prodscol['montant']) {
                                                
                                                $DB->insert('UPDATE payementfraiscol SET montant=?, datepaye=now() where matricule=? and tranche=? and promo=?' ,array($montant, $numel, $mois, $_POST['promo']));


                                                if ($_POST['famille']!='simple') {

                                                $cumul = $DB->querys("SELECT matricule, famille, montant FROM histopayefrais where famille='{$famille}' and matricule='{$numel}' ");

                                                if (empty($cumul)) {
                                                    $DB->insert('INSERT INTO histopayefrais(numpaye, matricule, montant, tranche, typepaye, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $_POST['mp'], $mois, $_POST['typep'], $_POST['promo'], $famille));
                                                }else{

                                                    $montantc=$cumul['montant']+$_POST['mp'];

                                                    $DB->insert('UPDATE histopayefrais SET montant=? where matricule=? and famille=?' ,array($montantc, $numel, $famille));
                                                }

                                            }else{
                                                $DB->insert('INSERT INTO histopayefrais(numpaye, matricule, montant, tranche, typepaye, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $_POST['mp'], $mois, $_POST['typep'], $_POST['promo'], $famille));
                                            }?>

                                                <div class="alerteV">Payement de la tranche completée avec succèe!!</div><?php

                                            }else{

                                                $reste=$montant-$prodscol['montant'];?>

                                                <div class="alertes">Le montant saisi est + la remise est > à la tranche définie</div><?php
                                            }

                                        }else{?>

                                            <div class="alertes">le(s) tranches choisies sont déjà régularisées</div><?php 

                                        }
                                    }
                                
                            }
                        }


                }else{

                    if(isset($_POST['payel']) && !empty($_POST['tranche']) && !empty($_POST['mp']) && !empty($_POST['typep'])){

                        
                        require 'famillemultiple.php';
                        $tabmois = $_POST['tranche'];

                        

                        foreach($tabmois as $mois){

                            if ($mois=='inscript') {

                                $DB->insert('UPDATE payement SET montant=?, typepaye=? where matricule=? and promo=?', array($_POST['mp'], $_POST['typep'], $numel, $_POST['promo']));?>

                                <div class="alerteV">Paiement des frais enregistrés avec succèe!!!</div><?php 
                            }else{

                                //mois=tranche

                                $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE codef=:code and tranche=:mois and promo=:promo', array('code'=>$products['codef'], 'mois'=>$mois, 'promo'=>$_POST['promo']));

                                if ($_POST['mp']>$prodscol['montant']) {

                                    $reste=$_POST['mp']-$prodscol['montant'];?>

                                    <div class="alertes">Le montant saisi est > de <?=number_format($reste,0,',',' ');?> GNF à la tranche définie</div><?php

                                }elseif($_POST['mp']>($prodscol['montant']*(1-($prodrem['remise']/100)))){?>

                                    <div class="alertes">Le montant saisi + la remise est > à la tranche définie</div><?php


                                }else{

                                    $prodrep = $DB->querys('SELECT tranche, montant FROM payementfraiscol WHERE matricule = :mat and tranche=:mois and promo=:promo', array('mat'=> $numel, 'mois'=>$mois, 'promo'=>$_POST['promo']));

                                    $montant=$prodrep['montant']+$_POST['mp'];

                                    $maxid = $DB->querys('SELECT max(numpaye) as id FROM histopayefrais');
                                    
                                    $numpaye=$maxid['id']+1;

                                    if ($_POST['famille']=='simple') {
                                        $famille=$numpaye;
                                    }

                                    if (empty($prodrep)) {

                                        $DB->insert('INSERT INTO payementfraiscol(numpaye, matricule, montant, tranche, typepaye, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $_POST['mp'], $mois, $_POST['typep'], $_POST['promo']));

                                        if ($_POST['famille']!='simple') {

                                            $cumul = $DB->querys("SELECT matricule, famille, montant FROM histopayefrais where famille='{$famille}' and matricule='{$numel}' ");

                                            if (empty($cumul)) {
                                                $DB->insert('INSERT INTO histopayefrais(numpaye, matricule, montant, tranche, typepaye, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $_POST['mp'], $mois, $_POST['typep'], $_POST['promo'], $famille));
                                            }else{

                                                $montantc=$cumul['montant']+$_POST['mp'];

                                                $DB->insert('UPDATE histopayefrais SET montant=? where matricule=? and famille=?' ,array($montantc, $numel, $famille));
                                            }

                                        }else{
                                            $DB->insert('INSERT INTO histopayefrais(numpaye, matricule, montant, tranche, typepaye, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $_POST['mp'], $mois, $_POST['typep'], $_POST['promo'], $famille));
                                        }?>

                                        <div class="alerteV">Payement effectué avec succèe!!</div><?php

                                    }elseif(($prodrep['montant']+$prodscol['montant']*($prodrem['remise']/100))<$prodscol['montant']){

                                        if (($montant+$prodscol['montant']*($prodrem['remise']/100))<=$prodscol['montant']) {
                                            
                                            $DB->insert('UPDATE payementfraiscol SET montant=?, datepaye=now() where matricule=? and tranche=? and promo=?' ,array($montant, $numel, $mois, $_POST['promo']));


                                            if ($_POST['famille']!='simple') {

                                            $cumul = $DB->querys("SELECT matricule, famille, montant FROM histopayefrais where famille='{$famille}' and matricule='{$numel}' ");

                                            if (empty($cumul)) {
                                                $DB->insert('INSERT INTO histopayefrais(numpaye, matricule, montant, tranche, typepaye, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $_POST['mp'], $mois, $_POST['typep'], $_POST['promo'], $famille));
                                            }else{

                                                $montantc=$cumul['montant']+$_POST['mp'];

                                                $DB->insert('UPDATE histopayefrais SET montant=? where matricule=? and famille=?' ,array($montantc, $numel, $famille));
                                            }

                                        }else{
                                            $DB->insert('INSERT INTO histopayefrais(numpaye, matricule, montant, tranche, typepaye, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $_POST['mp'], $mois, $_POST['typep'], $_POST['promo'], $famille));
                                        }?>

                                            <div class="alerteV">Payement de la tranche completée avec succèe!!</div><?php

                                        }else{

                                            $reste=$montant-$prodscol['montant'];?>

                                            <div class="alertes">Le montant saisi est + la remise est > à la tranche définie</div><?php
                                        }

                                    }else{?>

                                        <div class="alertes">le(s) tranches choisies sont déjà régularisées</div><?php 

                                    }
                                }
                            }
                        }

                    }
                }?>
            </div>

            <div style="margin-left: 30px;">

                <ol><?php

                    if ((isset($_POST['numel']) or isset($_GET['eleve']) or isset($_POST['payel']) or isset($_GET['delepaye'])) AND !empty($products)) {?>

                        <div style="display: flex;"><?php

                            if ($products['classe']==1) {

                                $inscrit=' '.$products['classe'].'ère année '.$products['nomf'].' Année: '.($products['annee']-1).'-'.$products['annee'];
                            }else{

                                 $inscrit=' '.$products['classe'].'ème année '.$products['nomf'].' Année: '.($products['annee']-1).'-'.$products['annee'];

                            }?><?php

                            $nom=ucwords($products['prenomel'].' '.strtoupper($products['nomel'])); //pour recuperer le nom dans le pdf?>

                            <div>
                                <table class="payement" style="margin-top: 10px;">
                                    <thead>
                                        <tr>
                                            <th colspan="4">Historique des frais de scolarité payés<a style="margin-left: 10px;"href="fiche_inscription.php?ficheins=<?=$numel;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                                            <a style="margin-left: 10px;"href="printdoc.php?histscol=<?=$numel;?>&mens=<?=$mensualite;?>&nomel=<?=$nom;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
                                        </tr>
                            
                                        <tr>
                                            <th>Tranche</th>
                                            <th>Montant</th>
                                            <th>Date de paye</th>
                                            <th>Réçu</th>
                                        </tr>
                                    </thead>

                                    <tbody><?php

                                        $montant=0;

                                        $prodpaye = $DB->query('SELECT id, numpaye, matricule, montant, tranche, famille, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, typepaye FROM histopayefrais WHERE matricule = :mat and promo=:promo ORDER BY(datepaye) DESC', array('mat'=> $numel, 'promo'=>$_SESSION['promo']));


                                        if (!empty($prodpaye)) {

                                                                                          
                                            foreach ($prodpaye as $paye) {

                                                $montant+=$paye->montant;?>

                                                <tr>

                                                <td><?=ucfirst($paye->tranche);?></td>

                                                <td style="text-align: right;"><?=number_format($paye->montant,0,',',' ');?></td>

                                                <td><?='Payé le '.$paye->datepaye;?></td>

                                                <td style="text-align: center;"><a href="printdoc.php?numfac=<?=$paye->famille; ?>&tranche=<?=$paye->tranche; ?>&codef=<?=$products['codef'];?>&date=<?=$paye->datepaye; ?>&numel=<?=$numel;?>&type=<?=$paye->typepaye; ?>&nomel=<?=$nom;?>&daten=<?=$products['naissance'];?>&phone=<?=$products['phone'];?>&inscrit=<?=$inscrit;?>&groupel=<?=$products['nomgr'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></td>
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
                        </div>

                            <div style="margin-right: 30px;">

                                <table class="payement">
                                    <thead>
                                        <tr>
                                            <th style="color: orange;"><?='Remise: '.$prodrem['remise'];?>%</th>
                                            <th colspan="3">Frais de scolarité payés <a style="margin-left: 10px;"href="printdoc.php?scoltot=<?=$numel;?>&mens=<?=$mensualite;?>&nomel=<?=$nom;?>&daten=<?=$products['naissance'];?>&phone=<?=$products['phone'];?>&inscrit=<?=$inscrit;?>&groupel=<?=$products['nomgr'];?>&promo=<?=$_SESSION['promo'];?>&codef=<?=$products['codef'];?>&remise=<?=$prodrem['remise'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
                                        </tr>
                            
                                        <tr>
                                            <th>Désignation</th>
                                            <th>Montant</th>
                                            <th>Payé le</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <tr>

                                            <td><?='Inscrip/Reinscrip';?></td>

                                            <td style="text-align: right;"><?=number_format($panier->fraisIns($numel, $_SESSION['promo'])[0],0,',',' ');?></td>

                                            <td><?=(new dateTime($panier->fraisIns($numel, $_SESSION['promo'])[1]))->format('d/m/Y');?></td>

                                            <td style="text-align: center;">
                                                <a href="comptabilite.php?delins=<?=$numel;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 100%; font-size: 16px; background-color: red; color: white; cursor: pointer"></a>
                                            </td>
                                        </tr><?php

                                        $montant=0;
                                        foreach ($month as $key=> $mois) {

                                            $prodpaye = $DB->query('SELECT id, numpaye, matricule, montant, tranche, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, typepaye FROM payementfraiscol WHERE matricule = :mat and tranche=:mois  and promo=:promo ORDER BY(datepaye) DESC', array('mat'=> $numel, 'mois'=>$mois,  'promo'=>$_SESSION['promo']));?>
                                            <tr>

                                                <td><?=ucfirst($mois);?></td><?php

                                                if (!empty($prodpaye)) {

                                                                                          
                                                    foreach ($prodpaye as $paye) {

                                                        $montant+=$paye->montant;?>

                                                        <td style="text-align: right;"><?=number_format($paye->montant,0,',',' ');?></td>

                                                        <td><?=$paye->datepaye;?></td>

                                                        <td>
                                                            

                                                            <a href="comptabilite.php?delepaye=<?=$paye->id;?>&matr=<?=$numel;?>&tranche=<?=$paye->tranche;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 100%; font-size: 16px; background-color: red; color: white; cursor: pointer"></a>
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
                                            <th style="padding: 10px;">Total :</th>
                                            <th style="text-align: right;"><?=number_format($montant+$panier->fraisIns($numel, $_SESSION['promo'])[0],0,',',' ');?></th>
                                            <th colspan="2" style="color: orange;">Reste: <?=number_format($mensualite*(1-($prodrem['remise']/100))-($montant),0,',',' ');?></th>
                                        </tr>

                                    </tbody>
                                </table>
                            </div><?php

                    }?>

                </ol>
            </div><?php
        }
    }else{
        header("Location: form_connexion.php");
    }?>

</div>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer cette facture ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>


    
