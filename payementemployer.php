<?php require 'headerv3.php';?>

<div class="container-fluid" style="overflow: auto;">
    <div class="row"><?php  

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

        if (isset($_GET['mois'])) {
            $_SESSION['moisp']=$_GET['mois'];
        }else{
            $_SESSION['moisp']=0;
        }

        $_SESSION['mois']=$_SESSION['moisp'];

        if (isset($_GET['cursus'])) {
            $_SESSION['cursusp']=$_GET['cursus'];
        }else{
            $_SESSION['cursusp']="";
        }

        if(isset($_POST['payen']) && !empty($_POST['saln']) && !empty($_POST['typep'])){

            if ($_POST['saln']>$panier->montantCompteT($_POST['compte'])) {?>

                <div class="alert alert-warning">Echec montant decaissé est > au montant disponible</div><?php

            }else{

                $maxid = $DB->querys('SELECT max(id) as id FROM payenseignant');
                                
                $numdec=$maxid['id']+1;

                if (!empty($_POST['heuret'])) {

                    $heure= $_POST['heuret'];

                }else{
                    $heure=0;

                }

                $numeen=$panier->h($_POST['mat']);

                $datep=$panier->h($_POST['datep']);

                
                $mois=$panier->h($_GET['mois']);

                $promo=$panier->h($_POST['promo']);

                $typep=$panier->h($_POST['typep']);

                $numcheque=$panier->h($_POST['numcheque']);

                $saln=$panier->h($_POST['saln']);

                $compte=$panier->h($_POST['compte']);

                $prodrep = $DB->querys('SELECT montant, heurep FROM payenseignant WHERE matricule = :mat and mois=:mois and anneescolaire=:promo', array('mat'=> $numeen, 'mois'=>$mois, 'promo'=>$_POST['promo']));

                $cumulmontant=$prodrep['montant']+$_POST['saln'];
                $cumulheure=$prodrep['heurep']+$heure;

                if (empty($prodrep['montant'])) {

                    if (empty($_POST['datep'])) {

                        if (empty($prodrep)) {

                            $DB->insert('INSERT INTO payenseignant(caisse, numdec, matricule, montant, mois, heurep, motif, typepaye, numcheque, anneescolaire, datepaye) VALUES(?,?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($_POST['compte'], $numdec, $numeen, $saln, $mois, $heure, 'payements des enseignants', $typep, $numcheque, $promo));

                        }else{

                            $DB->insert('UPDATE payenseignant SET montant=?, heurep=?, datepaye=now() where matricule=? and mois=?' ,array($cumulmontant, $cumulheure, $numeen, $mois));


                        }

                        $DB->insert('INSERT INTO histopayenseignant(numdec, matricule, montant, mois, heurep, typepaye, numcheque, anneescolaire, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())',array($numdec, $numeen, $saln, $mois, $heure, $typep, $numcheque, $promo));

                        $DB->insert('INSERT INTO banque (id_banque, montant, libelles, typep, numeropaie, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($_POST['compte'], -$_POST['saln'], 'paiement enseignant', $typep, $numcheque, 'paiens'.$numdec, $numeen, $_POST['promo']));
                    }else{

                        if (empty($prodrep)) {

                            $DB->insert('INSERT INTO payenseignant(caisse, numdec, matricule, montant, mois, heurep, motif, typepaye, numcheque, anneescolaire, datepaye) VALUES(?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',array($_POST['compte'], $numdec, $numeen, $saln, $mois, $heure, 'payements des enseignants', $typep, $numcheque, $promo, $datep));

                        }else{

                            $DB->insert('UPDATE payenseignant SET montant=?, heurep=?, datepaye=? where matricule=? and mois=?' ,array($cumulmontant, $cumulheure, $datep, $numeen, $mois));


                        }

                        $DB->insert('INSERT INTO histopayenseignant(numdec, matricule, montant, mois, heurep, typepaye, numcheque, anneescolaire, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)',array($numdec, $numeen, $saln, $mois, $heure, $typep, $numcheque, $promo, $datep));

                        $DB->insert('INSERT INTO banque (id_banque, montant, libelles, typep, numeropaie, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)', array($_POST['compte'], -$_POST['saln'], 'paiement enseignant', $typep, $numcheque, 'paiens'.$numdec, $numeen, $promo, $datep));

                    }?>

                    <div class="alert alert-success">Paiement effectué avec succèe!!</div><?php
                }
            }

        }

        if (isset($_GET['mois']) and empty($_SESSION['moisp'])){

            $_SESSION['moisp']=$_GET['mois'];

            $_SESSION['legende']='Paiements des Enseignants pour le mois de '.$panier->moisbul();   

        }else{
            $_SESSION['legende']='Paiements des Enseignants';
        }?>

        <table class="table table-hover table-bordered table-striped table-responsive text-center">
            <thead class="sticky-top">
        
                <tr>
                    <form class="form" method="GET" action="payementemployer.php" id="suitec" name="termc">
                        <th colspan="5">
                            
                            <select class="form-select" name="mois" required="" onchange="this.form.submit()"><?php

                                if (isset($_GET['mois'])) {?>
                                    
                                    <option value="<?=$_SESSION['moisp'];?>" ><?=$panier->moisbul();?></option><?php

                                }else{?>

                                    <option>Selectionnez le mois</option><?php
                                }

                                foreach ($month as $key => $mois) {?>

                                    <option value="<?=$key;?>"><?=$mois;?></option><?php

                                }?>
                            </select>
                        </th>
                    </form><?php 
                    if (!empty($_SESSION['moisp'])) {?>
                        <th colspan="9">
                            <a class="btn btn-primary" href="?cursus=<?="";?>&mois=<?=$_SESSION['moisp'];?>">Complexe</a>
                            <a class="btn btn-primary" href="?cursus=<?="maternelle";?>&mois=<?=$_SESSION['moisp'];?>">Maternelle</a>
                            <a class="btn btn-primary" href="?cursus=<?="primaire";?>&mois=<?=$_SESSION['moisp'];?>">Primaire</a>
                            <a class="btn btn-primary" href="?cursus=<?="secondaire";?>&mois=<?=$_SESSION['moisp'];?>">Secondaire</a>
                        </th><?php 
                    }?>
                </tr>
                <tr>
                    <th colspan="13">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <?=$_SESSION['legende'];?>
                                </div>

                                <div class="col-sm-4 col-md-4">
                                    <form method="POST" action="payementemployer.php" id="suitec" name="termc">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-8 col-md-8">
                                                    <input type="hidden" name="mois" value="<?=$_SESSION['moisp'];?>">

                                                    <input class="form-control" type = "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">
                                                    <input   type = "hidden" name = "effnav" value = "search">
                                                </div>

                                                <div class="col-md-4 col-md-4">

                                                    <button class="btn btn-primary" type = "submit" name = "s">Rechercher</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>

                                <div class="col-sm-2 col-md-2">
                                    <a href="exportpdfpayens.php?mois=<?=$_SESSION['moisp'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                                    <a href="exportpaiensexcel.php?mois=<?=$_SESSION['moisp'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
                                </div>
                            </div>
                        </div>
                    </th>
                        
                </tr>
                <tr>
                    <th></th>
                    <th height="30">Matricule</th>
                    <th>Prénom & Nom</th>
                    <th>H</th>
                    <th>Salaire Brut</th>
                    <th>Prime</th>
                    <th>A/salaire</th>
                    <th>Cotisa.</th>
                    <th class="bg-success">Salaire Net</th>
                    <th colspan="5" class="bg-danger">Paiement</th>
                </tr>

            </thead><?php

            if (isset($_POST['termec'])) {
              $_POST['termec'] = htmlspecialchars($_POST['termec']); //pour sécuriser le formulaire contre les failles html
              $terme = $_POST['termec'];
              $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
              $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
              $terme = strtolower($terme);

              $prodm=$DB->query('SELECT  enseignant.matricule as matricule, prenomen, nomen from enseignant inner join salaireens on salaireens.numpers=enseignant.matricule where salaireens.promo LIKE ? and enseignant.matricule not in(SELECT matricule FROM payenseignant WHERE anneescolaire LIKE? and mois LIKE?) and promo LIKE? and (enseignant.matricule LIKE? or nomen LIKE ? or prenomen LIKE ?) order by(prenomen)',array($_SESSION['promo'], $_SESSION['promo'], $_SESSION['moisp'], $_SESSION['promo'], "%".$terme."%", "%".$terme."%", "%".$terme."%"));
              
            }elseif (isset($_GET['mois'])) {
                if (!empty($_SESSION['cursusp'])) {
                    $prodm=$DB->query('SELECT enseignant.matricule as matricule, prenomen, nomen from enseignant inner join enseignantencours on enseignant.matricule=matriculens inner join salaireens on salaireens.numpers=enseignant.matricule where salaireens.promo=:promop and enseignantencours.promo=:promoc and cursus=:cursus  and enseignant.matricule not in(SELECT matricule FROM payenseignant WHERE anneescolaire=:annee and mois=:mois) order by(prenomen)', array('promop'=>$_SESSION['promo'],'promoc'=>$_SESSION['promo'],'cursus'=>$_SESSION['cursusp'], 'annee'=>$_SESSION['promo'], 'mois'=>$_SESSION['moisp']));                    
                }else{
                    $prodm=$DB->query('SELECT enseignant.matricule as matricule, prenomen, nomen from enseignant inner join salaireens on salaireens.numpers=enseignant.matricule where salaireens.promo=:promop and enseignant.matricule not in(SELECT matricule FROM payenseignant WHERE anneescolaire=:annee and mois=:mois) and promo=:promo order by(prenomen)', array('promop'=>$_SESSION['promo'], 'annee'=>$_SESSION['promo'], 'mois'=>$_SESSION['moisp'], 'promo'=>$_SESSION['promo']));
                }
                
            }else{
                $prodm=array();
            }

            if (isset($_GET['mois'])) {

                if ($_GET['mois']<10) {
                    
                    $cmois='0'.$_SESSION['moisp'];

                }else{

                    $cmois=$_SESSION['moisp'];
                }
                

                
                $totb=0;
                $totp=0;
                $totac=0;
                $totcot=0;
                $totn=0;
                foreach ($prodm as $key => $value) {

                    $_SESSION['numeen']=$value->matricule;
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

                    $prodprime=$DB->querys('SELECT montant as montantp from primesplanifie where matricule=:mat and mois=:mois and anneescolaire=:promo', array('mat'=>$numeen, 'mois'=>$_SESSION['moisp'], 'promo'=>$_SESSION['promo']));

                    if (empty($prodprime)) {
                        $prime=0;
                    }else{
                        $prime=$prodprime['montantp'];
                    }

                    $prodautres=$DB->querys('SELECT id, matricule from liaisonenseigpers where matricule=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));

                    //var_dump($prodautres['matricule']);

                    if (empty($prodautres['id'])) {
                        $salairesautres=0;
                    }else{
                        $prodautres=$DB->querys('SELECT salaire as montantp from salairepers where numpers=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));

                        $salairesautres=$prodautres['montantp'];

                    }


                    $prodh=$DB->querys('SELECT sum(heuret) as heuret from horairet where numens=:mat and date_format(datet,\'%m\')=:datet and annees=:promo', array('mat'=>$numeen, 'datet'=>$cmois, 'promo'=>$_SESSION['promo']));

                    $prodac=$DB->querys('SELECT montant from accompte where matricule=:mat and mois=:datet and anneescolaire=:promo', array('mat'=>$numeen, 'datet'=>$_SESSION['moisp'], 'promo'=>$_SESSION['promo']));

                    if (empty($prodac)) {
                        $accompte=0;
                    }else{
                        $accompte=$prodac['montant'];
                    }
                    if ($_SESSION['salaireact']=='not') {

                        $salaireb=$_SESSION['salaire']*$prodh['heuret']+$salairesautres;
                        
                        $salairep=$_SESSION['salaire']*$prodh['heuret']+$salairesautres+$prime-$accompte-$_SESSION['prodsocial'];

                    }else{
                        $salaireb=$_SESSION['salaire']+($prodh['heuret']*$rapport->infoEtablissement()['thoraire'])+$salairesautres;

                        $salairep=$salaireb+$prime-$accompte-$_SESSION['prodsocial'];
                    }

                    $totb+=$salaireb;
                    $totp+=$prime;
                    $totac+=$accompte;
                    $totcot+=$_SESSION['prodsocial'];
                    $totn+=$salairep;?>

                    <form class="form" method="POST" action="payementemployer.php?mois=<?=$_SESSION['moisp'];?>&cursus=<?=$_SESSION['cursusp'];?>" id="suitec" name="termc">

                        <tbody>
                            <tr>
                                <td style="text-align: center;"><?=$key+1;?></td>

                                <td><?=$value->matricule;?><input type="hidden" name="mat" value="<?=$value->matricule;?>"><input type="hidden" name="mois" value="<?=$_GET['mois'];?>"></td>
                                <td><?=ucwords(strtolower($value->prenomen)).' '.strtoupper($value->nomen);?></td>
                                <td style="text-align: center;"><input type="hidden" name="heuret" value="<?=$prodh['heuret'];?>"><?=number_format($prodh['heuret'],1,',',' ');?></td>
                                <td style="text-align: right; padding-right: 5px;"><input type="hidden" name="salb" value="<?=$salaireb;?>"><?=number_format($salaireb,0,',',' ');?></td>
                                <td style="text-align: right; padding-right: 5px;"><input type="hidden" name="prime" value="<?=$prime;?>"><?=number_format($prime,0,',',' ');?></td>
                                <td style="text-align: right; padding-right: 5px;"><input type="hidden" name="acc" value="<?=$accompte;?>"><?=number_format($accompte,0,',',' ');?></td>
                                <td style="text-align: right; padding-right: 5px;"><input type="hidden" name="cot" value="<?=$_SESSION['prodsocial'];?>"><?=number_format($_SESSION['prodsocial'],0,',',' ');?></td>
                                <td class="bg-success fw-bold fs-6 text-white" style="text-align: right;">
                                    <input type="hidden" name="saln" value="<?=$salairep;?>"><?=number_format($salairep,0,',',' ');?>

                                    <input type="hidden" name="promo" value="<?=$_SESSION['promo'];?>" required=""/>
                                </td>

                                <td><select class="form-select" name="typep" required="" >
                                <option value=""></option><?php 
                                foreach ($panier->modep as $value) {?>
                                    <option value="<?=$value;?>"><?=$value;?></option><?php 
                                }?></select></td>

                                <td><input class="form-control" type="text" name="numcheque" placeholder="numéro chèque/bordereau"></td>

                                <td><select class="form-select" name="compte" required="">
                                    <option></option><?php
                                    $type='Banque';

                                    foreach($panier->nomBanque() as $product){?>

                                        <option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
                                    }?>
                                    </select>
                                </td>

                                <td><input class="form-control" type="date" name="datep"></td>

                                <td><?php 

                                    if ($products['type']=='admin' or $products['type']=='comptable' ) {?>
                                        <button class="btn  btn-primary" type="submit" name="payen" onclick="return alerteV();">Valider</button><?php 
                                    }?>
                                </td>
                            </tr>
                        </tbody>

                    </form><?php
                }?>
                <tfoot>
                    <tr>
                        <th colspan="4">Total</th>
                        <th style="text-align: right; padding-right: 5px;"><?=number_format($totb,0,',',' ');?></th>
                        <th style="text-align: right; padding-right: 5px;"><?=number_format($totp,0,',',' ');?></th>
                        <th style="text-align: right; padding-right: 5px;"><?=number_format($totac,0,',',' ');?></th>
                        <th style="text-align: right; padding-right: 5px;"><?=number_format($totcot,0,',',' ');?></th>
                        <th class="bg-success fw-bold fs-6 text-white" style="text-align: right;"><?=number_format($totn,0,',',' ');?></th>
                    </tr>
                </tfoot><?php
            }?>
        </table>
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


    
