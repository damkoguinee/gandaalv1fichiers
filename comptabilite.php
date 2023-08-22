    <?php
    require 'headerv2.php';

    $prodlogin = $DB->querys('SELECT type, matricule, niveau FROM login WHERE pseudo= :PSEUDO',array('PSEUDO'=>$_SESSION['pseudo']));

    if (!empty($_SESSION['pseudo'])) {
        
        if ($products['niveau']<4) {?>

            <div class="alert alert-danger">Des autorisations sont requises pour consulter cette page</div><?php

        }else{?>

            <div class="container-fluid m-0 px-0">

                <div class="row px-0 mx-0"><?php

                    if (isset($_GET['note'])) {

                        require 'navcompta.php';
                    }

                    if (isset($_POST['bord'])) {
                        $_SESSION['bordereau']=$_POST['bord'];
                        $_SESSION['banque']=$_POST['banque'];
                        $_SESSION['mpaiement']=$_POST['typep'];
                        $_SESSION['typer']=$_POST['famille'];
                    }

                    if (isset($_GET['init'])) {
                        unset($_SESSION['famille']);
                        unset($_SESSION['bordereau']);
                        unset($_SESSION['banque']);
                        unset($_SESSION['typer']);
                        unset($_SESSION['mpaiement']);
                    }

                    $maxid = $DB->querys('SELECT max(numpaye) as id FROM histopayefrais');
                                        
                    $recuencours=$maxid['id']+1;
                    $recuencours='Reçu Numéro '.$recuencours. ' en-cours';

                    if (isset($_POST['numel'])) {

                        $_SESSION['numel']=$_POST['numel'];
                        $numel=$_SESSION['numel'];

                    }elseif (isset($_POST['payel'])) {

                        $numel=$_SESSION['numel'];

                    }else{

                    }

                    if (isset($_GET['delepaye']) or isset($_GET['annulins']) or isset($_GET['delins'])) {

                        $numel=$_SESSION['numel'];
                    }

                    if (isset($_GET['eleve']) ) {

                        $_SESSION['numel']=$_GET['eleve'];
                        $numel=$_SESSION['numel'];
                    }


                    if (isset($_POST['numel']) or isset($_GET['eleve']) or !empty($numel) or isset($_GET['delepaye']) or isset($_GET['annulins']) or isset($_GET['delins'])) {

                        $products=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email , annee, nomf, formation.codef as codef, classe, nomgr from inscription inner join contact on inscription.matricule=contact.matricule inner join eleve on inscription.matricule=eleve.matricule inner join formation on inscription.codef=formation.codef where inscription.matricule=:mat and annee=:promo', array('mat'=>$numel, 'promo'=>$_SESSION['promo']));

                        $prodscol=$DB->querys('SELECT sum(montant) as montant from scolarite where codef=:code and promo=:promo', array('code'=>$products['codef'], 'promo'=>$_SESSION['promo']));

                        $prodtranche=$DB->query('SELECT tranche, montant from scolarite where codef=:code and promo=:promo', array('code'=>$products['codef'], 'promo'=>$_SESSION['promo']));

                        $mensualite=$prodscol['montant'];
                         
                    }else{
                        $products = array();
                        $prodtranche=array();
                    }

                    if (isset($_GET['eleve']) or isset($_GET['elevef'])) {?>

                        <div class="col px-0"><?php 
                    }else{?>

                        <div class="col-sm-12 col-md-10 px-0 "  ><?php 
                    }?>

                        <div class="container-fluid px-0">
                            <div class="row px-0 mx-0"><?php

                                if (isset($_GET['eleve']) or isset($_GET['elevef'])) {?>

                                    <div class="col-sm-12 col-md-7 px-0"><?php 

                                }else{?>

                                    <div class="col-sm-12 col-md-12" style="overflow:auto;"><?php 
                                }

                                    $month = array(
                                        1   => '1ere tranche',
                                        2   => '2eme tranche',
                                        3   => '3eme tranche'
                                        
                                    );

                        

                                    if (isset($_GET['payeem']) or isset($_POST['numeen']) or isset($_GET['enseignant']) or isset($_GET['personnel']) or isset($_POST['payen']) or isset($_GET['delepayeens']) or isset($_GET['delehoraire']) or isset($_GET['payecherc'])) {

                                        require 'payementemployer0.php';

                                    }elseif (isset($_GET['compta']) or isset($_POST['j1']) or isset($_POST['j2'])) {

                                        require 'synthesecompta.php';

                                    }elseif (isset($_GET['horaire']) or isset($_POST['horairet']) or isset($_POST['matriens']) or isset($_GET['horairecherc'])) {

                                        require 'horaire.php';

                                    }else{

                                        if (isset($_POST['numel']) or isset($_GET['eleve']) or isset($_GET['delepaye']) or isset($_GET['annulins']) or isset($_GET['delins'])){

                                            $prodrem = $DB->querys('SELECT remise FROM inscription WHERE matricule = :mat and annee=:annee', array('mat'=> $numel, 'annee'=>$_SESSION['promo']));

                                            if ($prodrem['remise']>0) {
                                                
                                                $remise='Droit à une Remise de: '.$prodrem['remise'].'%';
                                            }else{
                                                $remise=' ';
                                            }
                                        }
                                        
                                        if ($_SESSION["type"]=='comptable' or $_SESSION["type"]=='admin' or $_SESSION["type"]=='bibliothecaire') {?>

                                            <form action="comptabilite.php?elevef" method="post" id="formulaire" style="background-color: grey; margin: 0px; margin-top: 10px;"><?php

                                                if (isset($_POST['numel']) or isset($_GET['eleve']) or isset($_GET['delepaye']) or isset($_GET['annulins']) or isset($_GET['delins'])){?>

                                                    <fieldset><legend>Paiement des frais de scolarité / <strong><?=$recuencours;?> <a style="color:white; background-color:red; margin-left: 50px; font-size: 20px; font-weight: 2em;" href="comptabilite.php?init" onclick="return alerteI();" >Initialisé</a></strong>

                                                    <div class="container-fluid">
                                                        <div class="row"><?php 
                                                            foreach ($prodtranche as $montranche) {

                                                                $prodtranchepaye = $DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE matricule = :mat and tranche=:mois  and promo=:promo ORDER BY(datepaye) DESC', array('mat'=> $numel, 'mois'=>$montranche->tranche,  'promo'=>$_SESSION['promo']));

                                                                $payetranche=$prodtranchepaye['montant'];    ?>

                                                                <div class="col" style="color:white; font-size:16px; font-weight:bold;">Reste <?=$montranche->tranche;?> = <?=number_format(($montranche->montant*(1-($prodrem['remise']/100))-$payetranche),0,',',' ').'-';?></div><?php
                                                            }?>
                                                        </div>
                                                        
                                                    </div><?php

                                                    if (isset($_POST['numel']) or isset($_GET['eleve']) or isset($_POST['payel']) or isset($_GET['delepaye']) or isset($_GET['annulins']) or isset($_GET['delins'])) {

                                                        require 'ficheeleve.php';

                                                    }?><?=$remise;?></legend> <?php

                                                }else{?>

                                                    <fieldset><legend>Paiement des frais de scolarité  / <strong><?=$recuencours;?></strong> <a style="color:white; background-color:red; margin-left: 50px; font-size: 20px; font-weight: 2em;" href="comptabilite.php?init" onclick="return alerteI();" >Initialisé</a></legend> <?php

                                                }?> 
                                                <ol class="px-0 ml-0" style="margin-top: -10px;"><?php

                                                    if (isset($_POST['numel']) AND empty($products)) {?>

                                                        <div class="alert alert-warning">Numéro incorrect, <a style="color: red;" href="comptabilite.php?paye">réessayer ici</a></div><?php
                                                    }else{
                                                            
                                                        if (isset($_POST['numel']) or isset($_GET['eleve']) or isset($_POST['payel']) or isset($_GET['delepaye']) or isset($_GET['annulins']) or isset($_GET['delins'])) {?>

                                                            <li><label>N° Matricule</label><input  type="text" name="numel" placeholder="N° matricule" onchange="document.getElementById('formulaire').submit()" value="<?= $numel; ?>" /><a href="ajout_eleve.php?listeeleve&cherceleve" style=" color: white; font-weight: bold;">Rechercher un matricule </a></li><?php

                                                        }else{?>
                                                            <li><label>N° Matricule</label><input  type="text" name="numel" placeholder="N° matricule" onchange="document.getElementById('formulaire').submit()" /><a href="ajout_eleve.php?listeeleve&cherceleve" style=" color: white; font-weight: bold;">Rechercher un matricule </a></li><?php                                
                                                        }?>

                                                        <li><label>Type de Réçu</label>
                                                            <select name="famille" required="" ><?php 

                                                                if (empty($_SESSION['mpaiement'])) {?>

                                                                    <option value=""></option><?php

                                                                }else{?>

                                                                    <option value="<?=$_SESSION['typer'];?>"><?=$_SESSION['typer'];?></option><?php
                                                                }?>
                                                                <option value="simple">Simple</option>
                                                                <option value="multiple">Multiple</option>
                                                            </select>
                                                        </li>

                                                        <li><label>Selectionnez la tranche</label>

                                                            <select type="text" name="tranche" required="">
                                                                <option>Selectionnez!!</option><?php
                                                                foreach ($panier->tranche() as $value) {?>
                                                                    <option value="<?=$value->nom;?>"><?=ucwords($value->nom);?></option><?php
                                                                }
                                                                if (sizeof($panier->tranche())>6) {?>

                                                                    <option value="t1">Trimestre 1</option>
                                                                    <option value="t2">Trimestre 2</option>
                                                                    <option value="t3">Trimestre 3</option><?php
                                                                    
                                                                }?>
                                                                <option value="annuel">Annuel</option>
                                                                <option value="inscript">Frais ins/Reins</option>
                                                            </select>

                                                        </li>

                                                        <div style="display: flex;">
                                                            <div style="width: 100%;">

                                                                <li><label>Montant Payer*</label><input id="numberconvert" type="text"   name="mp" min="0" style="font-size: 25px; width: 40%;"></li>
                                                            </div>

                                                            <li style="width:50%;"><label style="width:60%;"><div style="color:white; background-color: grey; font-size: 25px; color: orange; width:100%;" id="convertnumber"></div></li></label>
                                                        </div>

                                                        <li><label>Dévise</label>
                                                            <select name="devise" required="">
                                                                <option value="gnf">GNF</option>
                                                                <option value="us">$</option>
                                                                <option value="eu">€</option>
                                                                <option value="cfa">CFA</option>
                                                            </select>
                                                        </li>

                                                        <li><label>Taux</label><input type="text" name="taux" value="1"></li>

                                                        <li><label>Type de Paiement</label><select name="typep" required="" ><?php 

                                                            if (empty($_SESSION['mpaiement'])) {?>

                                                                <option></option><?php

                                                            }else{?>

                                                                <option value="<?=$_SESSION['mpaiement'];?>"><?=$_SESSION['mpaiement'];?></option><?php
                                                            }
                                                            foreach ($panier->modep as $value) {?>
                                                                <option value="<?=$value;?>"><?=$value;?></option><?php 
                                                            }?></select>
                                                        </li>

                                                        <li><label>N°Chèque/Bordereau</label><?php 

                                                            if (empty($_SESSION['bordereau'])) {?>
                                                                <input style="font-size: 20px;" type="text" name="bord"><?php
                                                            }else{?>

                                                                <input style="font-size: 20px;" type="text" name="bord" value="<?=$_SESSION['bordereau'];?>"><?php
                                                            }?>
                                                            
                                                        </li>

                                                        <li><label>Banque</label><?php

                                                            if (empty($_SESSION['bordereau'])) {?>

                                                                <input style="font-size: 20px;" type="text" name="banque"><?php
                                                            }else{?>

                                                                <input style="font-size: 20px;" type="text" name="banque" value="<?=$_SESSION['banque'];?>"><?php
                                                            }?>
                                                        </li>


                                                        <li><label>Compte depôt</label>
                                                            <select  name="compte" required="">
                                                                <option></option><?php
                                                                $type='Banque';

                                                                foreach($panier->nomBanque() as $product){?>

                                                                    <option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
                                                                }?>
                                                            </select>
                                                        </li>

                                                        <li><label>Année-scolaire</label>

                                                            <select type="text" name="promo" required=""><?php
                                                            
                                                                $annee=date("Y")+1;

                                                                for($i=($_SESSION['promo']-1);$i<=$annee ;$i++){
                                                                    $j=$i+1;?>

                                                                    <option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

                                                                }?>
                                                            </select>
                                                
                                                        </li><?php
                                            
                                                    }?> 

                                                </ol>

                                                </fieldset><?php 

                                                if ($_SESSION['etab']=='Complexe Scolaire la Plume') {

                                                    if ($prodlogin['type']=='secrétaire' or $prodlogin['type']=='comptable' or $prodlogin['type']=='admin')  {?>

                                                        <fieldset><input type="reset" value="Annuler" id="form" style="cursor: pointer;" /><input type="submit" value="Valider" name="payel" id="form" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset><?php 
                                                    }
                                                }else{

                                                    if ($prodlogin['type']=='comptable' or $prodlogin['type']=='informaticien' or $prodlogin['type']=='admin')  {?>

                                                        <fieldset><input type="reset" value="Annuler" id="form" style="cursor: pointer;" /><input type="submit" value="Valider" name="payel" id="form" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset><?php 
                                                    }
                                                }?>

                                            </form><?php
                                        }
                                    }

                                    if (isset($_GET['delepaye'])) {

                                        $prodpaieinit = $DB->querys('SELECT montant FROM payementfraiscol WHERE matricule=? or famille = ? and promo=?', array($_GET['matr'], $_GET['delepaye'], $_SESSION['promo']));

                                        $montantinit=$prodpaieinit['montant'];
                                        

                                        $montantnew=$montantinit-$_GET['montant'];

                                        //$DB->insert('UPDATE payementfraiscol SET montant=? where matricule = ? and tranche=? and promo=?', array($montantnew, $_GET['matr'], $_GET['tranche'], $_SESSION['promo']));

                                        $DB->delete('DELETE FROM payementfraiscol WHERE famille = ? and promo=?', array($_GET['delepaye'], $_SESSION['promo']));

                                        $DB->delete('DELETE FROM histopayefrais WHERE famille=? and promo=?', array($_GET['delepaye'], $_SESSION['promo']));

                                        $DB->delete('DELETE FROM banque WHERE numero=? and promob=?', array(('depfs'.$_GET['delepaye']), $_SESSION['promo']));

                                        $DB->insert('INSERT INTO historiquesup(type, mateleve, executeur, promo, datesup) values( ?, ?, ?, ?, now())', array('Suppression des frais de scolarité de ', $_GET['matr'], $personnelsup, $_SESSION['promo']));?>

                                        <div class="alert alert-success">Payement supprimé avec succèe</div><?php
                                    }

                                    if (isset($_GET['delins'])) {

                                        $DB->insert('UPDATE payement SET montant=? where matricule=? and promo=?', array(0, $_GET['delins'], $_SESSION['promo']));

                                        $DB->delete('DELETE FROM banque WHERE matriculeb=? and libelles=? and promob=?', array($_GET['delins'], 'paiement frais inscription', $_SESSION['promo']));

                                        $DB->delete('DELETE FROM banque WHERE matriculeb=? and libelles=? and promob=?', array($_GET['delins'], 'paiement frais reinscription', $_SESSION['promo']));

                                        $DB->insert('INSERT INTO historiquesup(type, mateleve, executeur, promo, datesup) values( ?, ?, ?, ?, now())', array('Suppression des frais inscript/reins ', $_GET['delins'], $personnelsup, $_SESSION['promo']));?>

                                        <div class="alert alert-success">Payement supprimé avec succèe</div><?php
                                    }

                                    if (isset($_GET['annulins'])) {

                                        $DB->delete('DELETE FROM payement WHERE matricule = ? and promo=?', array($_GET['annulins'], $_SESSION['promo']));

                                        $DB->delete('DELETE FROM inscription WHERE matricule = ? and annee=?', array($_GET['annulins'], $_SESSION['promo']));

                                        $DB->delete('DELETE FROM payementfraiscol WHERE matricule = ? and promo=?', array($_GET['annulins'], $_SESSION['promo']));

                                        $DB->delete('DELETE FROM histopayefrais WHERE matricule=? and promo=?', array($_GET['annulins'], $_SESSION['promo']));

                                        $DB->delete('DELETE FROM banque WHERE matriculeb=? and promob=?', array($_GET['annulins'], $_SESSION['promo']));

                                        $DB->insert('INSERT INTO historiquesup(type, mateleve, executeur, promo, datesup) values( ?, ?, ?, ?, now())', array('Annulation de linscription de ', $_GET['annulins'], $personnelsup, $_SESSION['promo']));?>

                                        <div class="alert alert-success">Payement supprimé avec succèe</div><?php
                                    }

                                    if(isset($_POST['payel']) && !empty($_POST['tranche'])  && !empty($_POST['typep'])){

                                        $bordereau=addslashes(Nl2br(Htmlspecialchars($_POST['bord'])));
                                        $banque=addslashes(Nl2br(Htmlspecialchars($_POST['banque'])));

                                        
                                        if ($_POST['famille']=='simple') {
                                            $_SESSION['famille']=array();
                                        }

                                        //var_dump($_SESSION['famille']);

                                        if (empty($_SESSION['famille'])) {
                                           
                                            $maxid = $DB->querys('SELECT max(numpaye) as id FROM histopayefrais');
                                                
                                            $numpaye=$maxid['id']+1;

                                            $_SESSION['numpaye']=$numpaye;
                                        }

                                        //var_dump($_SESSION['numpaye']);

                                        if ($_POST['famille']=='multiple') {
                                            
                                            $_SESSION['famille']=$_SESSION['numpaye'];
                                            $famille=$_SESSION['famille'];
                                        }else{
                                            $famille=$_SESSION['numpaye'];
                                        }

                                        $mois=$panier->h($_POST['tranche']);
                                        $mp=$panier->h($_POST['mp']);
                                        $taux=$panier->h($_POST['taux']);
                                        $devise=$panier->h($_POST['devise']);
                                        $typep=$panier->h($_POST['typep']);
                                        $promo=$panier->h($_POST['promo']);
                                        if (empty($mp)) {
                                            $mp=0;
                                        }
                                        $mp=$mp*$taux;
                                        $compte=$panier->h($_POST['compte']);
                                        $famillep=$panier->h($_POST['famille']);

                                        if ($mois=='inscript') {

                                            $DB->insert('UPDATE payement SET montant=?, typepaye=?, taux=?, devise=? where matricule=? and promo=?', array($mp, $typep, $taux, $devise, $numel, $promo));

                                            $DB->insert('INSERT INTO banque (id_banque, montant, taux, devise, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $mp, $taux, $devise, 'paiement frais inscription', 'depins'.$famille, $numel, $promo));?>

                                            <div class="alert alert-success">Paiement des frais enregistrés avec succèe!!!</div><?php 
                                        }else{

                                            //mois=tranche

                                            $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE codef=:code and tranche=:mois and promo=:promo', array('code'=>$products['codef'], 'mois'=>$mois, 'promo'=>$promo));

                                            $valremise=($prodscol['montant']-$prodscol['montant']*($prodrem['remise']/100));

                                            if ($mp>$prodscol['montant']) {

                                                $reste=$mp-$prodscol['montant'];?>

                                                <div class="alert alert-danger">Le montant saisi est > de <?=number_format($reste,0,',',' ');?> GNF à la tranche définie</div><?php


                                            }elseif($mp>$valremise){?>

                                                <div class="alert alert-danger">Le montant saisi + la remise est > à la tranche définie</div><?php


                                            }else{                            

                                                $maxid = $DB->querys('SELECT max(numpaye) as id FROM histopayefrais');
                                                
                                                $numpaye=$maxid['id']+1;

                                                if ($famillep=='simple') {
                                                    $famille=$numpaye;
                                                }

                                                if ($mois=='annuel') {

                                                    foreach ($panier->tranche() as $valuet) {

                                                        $mois=$valuet->nom;

                                                        $prodrep = $DB->querys('SELECT tranche, montant FROM payementfraiscol WHERE matricule = :mat and tranche=:mois and promo=:promo', array('mat'=> $numel, 'mois'=>$mois, 'promo'=>$promo));

                                                        $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE codef=:code and tranche=:mois and promo=:promo', array('code'=>$products['codef'], 'mois'=>$mois, 'promo'=>$promo));

                                                        if (empty($prodrep)) {

                                                            $montantannuel=($prodscol['montant']-$prodscol['montant']*($prodrem['remise']/100));;

                                                            if ($_POST['famille']!='simple') {

                                                                $DB->insert('INSERT INTO payementfraiscol(numpaye, matricule, montant, tranche, famille, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($famille, $numel, $montantannuel, $mois, $famille, $typep, $bordereau, $banque, $promo));

                                                                $cumul = $DB->querys("SELECT matricule, famille, montant FROM histopayefrais where famille='{$famille}' and matricule='{$numel}' ");

                                                                if (empty($cumul)) {
                                                                    $DB->insert('INSERT INTO histopayefrais(caisse, numpaye, matricule, montant, devise, taux, tranche, typepaye, numpaie, banque, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $famille, $numel, $montantannuel, $devise, $taux, $mois, $typep, $bordereau, $banque, $promo, $famille));
                                                                }else{

                                                                    $montantc=$cumul['montant']+$montantannuel;

                                                                    $DB->insert('UPDATE histopayefrais SET montant=? where matricule=? and famille=?' ,array($montantc, $numel, $famille));
                                                                }

                                                            
                                                                

                                                            }else{

                                                                $DB->insert('INSERT INTO payementfraiscol(numpaye, matricule, montant, tranche, famille, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $montantannuel, $mois, $famille, $typep, $bordereau, $banque, $promo));

                                                                $DB->insert('INSERT INTO histopayefrais(caisse, numpaye, matricule, montant, devise, taux, tranche, typepaye, numpaie, banque, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $numpaye, $numel, $montantannuel, $devise, $taux, $mois, $typep, $bordereau, $banque, $promo, $famille));

                                                            }

                                                            $DB->insert('INSERT INTO banque (id_banque, montant, taux, devise, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $montantannuel, $taux, $devise, 'paiement frais de scolarite', 'depfs'.$famille, $numel, $promo));

                                                        }else{?>

                                                            <div class="alert alert-danger">un paiement existe pour cet élève</div><?php 
                                                        }
                                                        // code...
                                                    }
                                                    
                                                }elseif ($mois=='t1') {

                                                    $t=1;

                                                    while ($t<4) {

                                                        if ($t==1) {
                                                            $mois=$t.'ere tranche';
                                                        }else{
                                                            $mois=$t.'eme tranche';
                                                        }

                                                        $prodrep = $DB->querys('SELECT tranche, montant FROM payementfraiscol WHERE matricule = :mat and tranche=:mois and promo=:promo', array('mat'=> $numel, 'mois'=>$mois, 'promo'=>$promo));

                                                        $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE codef=:code and tranche=:mois and promo=:promo', array('code'=>$products['codef'], 'mois'=>$mois, 'promo'=>$promo));

                                                        if (empty($prodrep)) {

                                                            $montantannuel=($prodscol['montant']-$prodscol['montant']*($prodrem['remise']/100));;

                                                            

                                                            $DB->insert('INSERT INTO payementfraiscol(numpaye, matricule, montant, tranche, famille, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($famille, $numel, $montantannuel, $mois, $famille, $typep, $bordereau, $banque, $promo));

                                                            $cumul = $DB->querys("SELECT matricule, famille, montant FROM histopayefrais where famille='{$famille}' and matricule='{$numel}' ");

                                                            if (empty($cumul)) {
                                                                $DB->insert('INSERT INTO histopayefrais(caisse, numpaye, matricule, montant, taux, devise, tranche, typepaye, numpaie, banque, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $famille, $numel, $montantannuel, $mois, $typep, $bordereau, $banque, $promo, $famille));
                                                            }else{

                                                                $montantc=$cumul['montant']+$montantannuel;

                                                                $DB->insert('UPDATE histopayefrais SET montant=? where matricule=? and famille=?' ,array($montantc, $numel, $famille));
                                                            }

                                                            $DB->insert('INSERT INTO banque (id_banque, montant, devise, taux, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $montantannuel, $devise, $taux, 'paiement frais de scolarite', 'depfs'.$famille, $numel, $promo));

                                                        }else{?>

                                                            <div class="alert alert-danger">un paiement existe pour cet élève</div><?php 
                                                        }
                                                        $t++;
                                                    }
                                                    
                                                }elseif ($mois=='t2') {

                                                    $t=4;

                                                    while ($t<7) {

                                                        if ($t==1) {
                                                            $mois=$t.'ere tranche';
                                                        }else{
                                                            $mois=$t.'eme tranche';
                                                        }

                                                        $prodrep = $DB->querys('SELECT tranche, montant FROM payementfraiscol WHERE matricule = :mat and tranche=:mois and promo=:promo', array('mat'=> $numel, 'mois'=>$mois, 'promo'=>$promo));

                                                        $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE codef=:code and tranche=:mois and promo=:promo', array('code'=>$products['codef'], 'mois'=>$mois, 'promo'=>$promo));

                                                        if (empty($prodrep)) {

                                                            $montantannuel=($prodscol['montant']-$prodscol['montant']*($prodrem['remise']/100));

                                                            $DB->insert('INSERT INTO payementfraiscol(numpaye, matricule, montant, tranche, famille, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($famille, $numel, $montantannuel, $mois, $famille, $typep, $bordereau, $banque, $promo));

                                                            $cumul = $DB->querys("SELECT matricule, famille, montant FROM histopayefrais where famille='{$famille}' and matricule='{$numel}' ");

                                                            if (empty($cumul)) {
                                                                $DB->insert('INSERT INTO histopayefrais(caisse, numpaye, matricule, montant, devise, taux, tranche, typepaye, numpaie, banque, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $famille, $numel, $montantannuel, $devise, $taux, $mois, $typep, $bordereau, $banque, $promo, $famille));
                                                            }else{

                                                                $montantc=$cumul['montant']+$montantannuel;

                                                                $DB->insert('UPDATE histopayefrais SET montant=? where matricule=? and famille=?' ,array($montantc, $numel, $famille));
                                                            }

                                                            $DB->insert('INSERT INTO banque (id_banque, montant, taux, devise, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $montantannuel, $taux, $devise, 'paiement frais de scolarite', 'depfs'.$famille, $numel, $promo));

                                                        }else{?>

                                                            <div class="alert alert-danger">un paiement existe pour cet élève</div><?php 
                                                        }
                                                        $t++;
                                                    }
                                                    
                                                }elseif ($mois=='t3') {

                                                    $t=7;

                                                    while ($t<10) {

                                                        if ($t==1) {
                                                            $mois=$t.'ere tranche';
                                                        }else{
                                                            $mois=$t.'eme tranche';
                                                        }

                                                        $prodrep = $DB->querys('SELECT tranche, montant FROM payementfraiscol WHERE matricule = :mat and tranche=:mois and promo=:promo', array('mat'=> $numel, 'mois'=>$mois, 'promo'=>$promo));

                                                        $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE codef=:code and tranche=:mois and promo=:promo', array('code'=>$products['codef'], 'mois'=>$mois, 'promo'=>$promo));

                                                        if (empty($prodrep)) {

                                                            $montantannuel=($prodscol['montant']-$prodscol['montant']*($prodrem['remise']/100));

                                                            $DB->insert('INSERT INTO payementfraiscol(numpaye, matricule, montant, tranche, famille, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($famille, $numel, $montantannuel, $mois, $famille, $typep, $bordereau, $banque, $promo));

                                                            $cumul = $DB->querys("SELECT matricule, famille, montant FROM histopayefrais where famille='{$famille}' and matricule='{$numel}' ");

                                                            if (empty($cumul)) {
                                                                $DB->insert('INSERT INTO histopayefrais(caisse, numpaye, matricule, montant, taux, devise, tranche, typepaye, numpaie, banque, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $famille, $numel, $montantannuel, $taux, $devise, $mois, $typep, $bordereau, $banque, $_POST['promo'], $famille));
                                                            }else{

                                                                $montantc=$cumul['montant']+$montantannuel;

                                                                $DB->insert('UPDATE histopayefrais SET montant=? where matricule=? and famille=?' ,array($montantc, $numel, $famille));
                                                            }

                                                            $DB->insert('INSERT INTO banque (id_banque, montant, taux, devise, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $montantannuel, $taux, $devise, 'paiement frais de scolarite', 'depfs'.$famille, $numel, $promo));

                                                        }else{?>

                                                            <div class="alert alert-danger">un paiement existe pour cet élève</div><?php 
                                                        }
                                                        $t++;
                                                    }
                                                    
                                                }else{

                                                    $prodrep = $DB->querys('SELECT tranche, montant FROM payementfraiscol WHERE matricule = :mat and tranche=:mois and promo=:promo', array('mat'=> $numel, 'mois'=>$mois, 'promo'=>$promo));

                                                    $montant=$prodrep['montant']+$mp;

                                                    if (empty($prodrep)) {

                                                        if ($_POST['famille']!='simple') {

                                                            $DB->insert('INSERT INTO payementfraiscol(numpaye, matricule, montant, tranche, famille, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($famille, $numel, $mp, $mois, $famille, $typep, $bordereau, $banque, $promo));

                                                            $cumul = $DB->querys("SELECT matricule, famille, montant FROM histopayefrais where famille='{$famille}' and matricule='{$numel}' ");

                                                            if (empty($cumul)) {
                                                                $DB->insert('INSERT INTO histopayefrais(caisse, numpaye, matricule, montant, devise, taux, tranche, typepaye, numpaie, banque, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $famille, $numel, $mp, $devise, $taux, $mois, $typep, $bordereau, $banque, $promo, $famille));
                                                            }else{

                                                                $montantc=$cumul['montant']+$mp;

                                                                $DB->insert('UPDATE histopayefrais SET montant=? where matricule=? and famille=?' ,array($montantc, $numel, $famille));
                                                            }

                                                        }else{
                                                            $DB->insert('INSERT INTO payementfraiscol(numpaye, matricule, montant, tranche, famille, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $mp, $mois, $famille, $typep, $bordereau, $banque, $_POST['promo']));

                                                            $DB->insert('INSERT INTO histopayefrais(caisse, numpaye, matricule, montant, devise, taux, tranche, typepaye, numpaie, banque, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $numpaye, $numel, $mp, $devise, $taux, $mois, $typep, $bordereau, $banque, $promo, $famille));
                                                        }

                                                        $DB->insert('INSERT INTO banque (id_banque, montant, devise, taux, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $mp, $devise, $taux, 'paiement frais de scolarite', 'depfs'.$famille, $numel, $promo));?>

                                                        <div class="alert laert-success">Payement effectué avec succèe!!</div><?php

                                                    }elseif(($prodrep['montant']+$prodscol['montant']*($prodrem['remise']/100))<$prodscol['montant']){

                                                        if (($montant+$prodscol['montant']*($prodrem['remise']/100))<=$prodscol['montant']) {
                                                            
                                                            //$DB->insert('UPDATE payementfraiscol SET montant=?, datepaye=now() where matricule=? and tranche=? and promo=?' ,array($montant, $numel, $mois, $_POST['promo']));

                                                            $DB->insert('INSERT INTO payementfraiscol(numpaye, matricule, montant, tranche, famille, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, $numel, $mp, $mois, $famille, $typep, $bordereau, $banque, $promo));


                                                            if ($_POST['famille']!='simple') {

                                                                $cumul = $DB->querys("SELECT matricule, famille, montant FROM histopayefrais where famille='{$famille}' and matricule='{$numel}' ");

                                                                if (empty($cumul)) {
                                                                    $DB->insert('INSERT INTO histopayefrais(caisse, numpaye, matricule, montant, devise, taux, tranche, typepaye, numpaie, banque, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $numpaye, $numel, $mp, $devise, $taux, $mois, $typep, $bordereau, $banque, $promo, $famille));
                                                                }else{

                                                                    $montantc=$cumul['montant']+$mp;

                                                                    $DB->insert('UPDATE histopayefrais SET montant=? where matricule=? and famille=?' ,array($montantc, $numel, $famille));
                                                                }

                                                            }else{
                                                                $DB->insert('INSERT INTO histopayefrais(caisse, numpaye, matricule, montant, devise, taux, tranche, typepaye, numpaie, banque, promo, famille, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $numpaye, $numel, $mp, $devise, $taux, $mois, $typep, $bordereau, $banque, $promo, $famille));
                                                            }

                                                            $DB->insert('INSERT INTO banque (id_banque, montant, devise, taux, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $mp, $devise, $taux, 'paiement frais de scolarite', 'depfs'.$famille, $numel, $promo));?>

                                                            <div class="alert alert-success">Payement de la tranche completée avec succèe!!</div><?php

                                                        }else{

                                                            $reste=$montant-$prodscol['montant'];?>

                                                            <div class="alert alert-success">Le montant saisi est + la remise est > à la tranche définie</div><?php
                                                        }

                                                    }else{?>

                                                        <div class="alert alert-success">le(s) tranches choisies sont déjà régularisées</div><?php 

                                                    }
                                                }
                                            }
                                        }
                                        

                                    }?>
                                </div>

                                <div class="col-sm-12 col-md-5 my-3" ><?php

                                    if ((isset($_POST['numel']) or isset($_GET['eleve']) or isset($_POST['payel']) or isset($_GET['delepaye']) or isset($_GET['annulins']) or isset($_GET['delins'])) AND !empty($products)) {?>

                                        <div class="container-fluid"><?php

                                            if ($products['classe']==1) {

                                                $inscrit=' '.$products['classe'].'ère année '.$products['nomf'].' Année: '.($products['annee']-1).'-'.$products['annee'];
                                            }else{

                                                 $inscrit=' '.$products['classe'].'ème année '.$products['nomf'].' Année: '.($products['annee']-1).'-'.$products['annee'];

                                            }

                                            $nom=ucwords($products['prenomel'].' '.strtoupper($products['nomel'])); //pour recuperer le nom dans le pdf?>

                                            <div class="row" style="overflow:auto; ">
                                                <table class="table table-hover table-bordered table-striped table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="5">Historique des frais de scolarité payés<a style="margin-left: 10px;"href="fiche_inscription.php?ficheins=<?=$numel;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                                                            <a style="margin-left: 10px;"href="printdoc.php?histscol=<?=$numel;?>&mens=<?=$mensualite;?>&nomel=<?=$nom;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
                                                        </tr>
                                            
                                                        <tr>
                                                            <th>Tranche</th>
                                                            <th>Montant</th>
                                                            <th>Date de paye</th>
                                                            <th>Réçu</th>
                                                            <th></th>
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

                                                                    <td style="text-align: center;"><a href="facture.php?numfac=<?=$paye->famille; ?>&tranche=<?=$paye->tranche; ?>&codef=<?=$products['codef'];?>&date=<?=$paye->datepaye; ?>&numel=<?=$numel;?>&type=<?=$paye->typepaye; ?>&nomel=<?=$nom;?>&daten=<?=$products['naissance'];?>&phone=<?=$products['phone'];?>&inscrit=<?=$inscrit;?>&groupel=<?=$products['nomgr'];?>&numpaie=<?=$paye->numpaie;?>&banque=<?=$paye->banque;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></td>

                                                                    <td><?php 

                                                                        if ($_SESSION['etab']=='Complexe Scolaire la Plume') {

                                                                            if ($prodlogin['type']=='secrétaire' or $prodlogin['type']=='comptable' or $prodlogin['type']=='admin')  {?>

                                                                                <a class="btn btn-danger" href="comptabilite.php?delepaye=<?=$paye->famille;?>&matr=<?=$numel;?>&tranche=<?=$paye->tranche;?>&montant=<?=$paye->montant;?>" onclick="return alerteS();"><input type="button" value="Annuler paiement" ></a><?php 
                                                                            }
                                                                        }else{

                                                                            if ($prodlogin['type']=='comptable' or $prodlogin['type']=='informaticien' or $prodlogin['type']=='admin')  {?>

                                                                                <a class="btn btn-danger" href="comptabilite.php?delepaye=<?=$paye->famille;?>&matr=<?=$numel;?>&tranche=<?=$paye->tranche;?>&montant=<?=$paye->montant;?>" onclick="return alerteS();">Annuler Paiement</a><?php 
                                                                            }
                                                                        }?>
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

                                            <table class="table table-hover table-bordered table-striped table-responsive">
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

                                                        <td style="text-align: center;"><?php 

                                                            if ($_SESSION['etab']=='Complexe Scolaire la Plume') {

                                                                if ($prodlogin['type']=='secrétaire' or $prodlogin['type']=='comptable' or $prodlogin['type']=='admin')  {?>

                                                                    <a class="btn btn-danger" href="comptabilite.php?delins=<?=$numel;?>" onclick="return alerteSins();">Annuler paie</a><?php 
                                                                }
                                                            }else{

                                                                if ($prodlogin['type']=='comptable' or $prodlogin['type']=='informaticien' or $prodlogin['type']=='admin')  {?>

                                                                    <a class="btn btn-danger" href="comptabilite.php?delins=<?=$numel;?>" onclick="return alerteSins();">Annuler paie</a><?php 
                                                                }
                                                            }?> 
                                                            
                                                        </td>
                                                    </tr><?php

                                                    $montant=0;
                                                    foreach ($panier->tranche() as $key=> $valuem) {
                                                        $mois=$valuem->nom;

                                                        $prodpaye = $DB->query('SELECT id, numpaye, matricule, sum(montant) as montant, tranche, famille, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, typepaye FROM payementfraiscol WHERE matricule = :mat and tranche=:mois  and promo=:promo ORDER BY(datepaye) DESC', array('mat'=> $numel, 'mois'=>$mois,  'promo'=>$_SESSION['promo']));?>
                                                        <tr>

                                                            <td><?=ucfirst($mois);?></td><?php

                                                            if (!empty($prodpaye)) {

                                                                                                      
                                                                foreach ($prodpaye as $paye) {

                                                                    $montant+=$paye->montant;?>

                                                                    <td style="text-align: right;"><?=number_format($paye->montant,0,',',' ');?></td>

                                                                    <td><?=$paye->datepaye;?></td>

                                                                    <td>
                                                                        

                                                                        


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
                                        </div>
                                    </div>
                                </div>
                            </div><?php

                        }?>
                    </div><?php
                }
            }else{
                header("Location: form_connexion.php");
            }?>

        </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $('#numberconvert').keyup(function(){
            $('#convertnumber').html("");

            var utilisateur = $(this).val();

            if (utilisateur!='') {
                $.ajax({
                    type: 'GET',
                    url: 'convertnumber.php?convertvers',
                    data: 'user=' + encodeURIComponent(utilisateur),
                    success: function(data){
                        if(data != ""){
                          $('#convertnumber').append(data);
                        }else{
                          document.getElementById('convertnumber').innerHTML = "<div style='font-size: 20px; text-align: center; margin-top: 10px'>Aucun utilisateur</div>"
                        }
                    }
                })
            }
      
        });
    });
  </script>

<script type="text/javascript">

    function alerteI(){
        return(confirm('Etes-vous sûr de vouloir initialisé? Le nouveau réçu ne sera pas lié au réçu précédent'));
    }

    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir annuler ce paiement ? il se peut que ce réçu soit lié'));
    }

    function alerteSains(){
        return(confirm('Etes-vous sûr de vouloir annuler cette inscription ?'));
    }

    function alerteSins(){
        return(confirm('Etes-vous sûr de vouloir annuler ce paiement ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>


    
