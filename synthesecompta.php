<?php
    require 'headerv2.php';?>

<div class="container-fluid"><?php

    //require 'headercompta.php';

    if (!isset($_POST['j1'])) {
        $_SESSION['date01']=date("Y0101");  
        $_SESSION['date02']=date("Y0101"); 
      $_SESSION['date']=date("Y0101");  
      $dates = $_SESSION['date'];
      $dates = new DateTime( $dates );
      $dates = $dates->format('Y0101'); 
      $_SESSION['date']=$dates;
      $_SESSION['date1']=$dates;
      $_SESSION['date2']=date('Y1231'); ;
      $_SESSION['dates1']=$dates; 

    }else{

      $_SESSION['date01']=$_POST['j1'];
      $_SESSION['date1'] = new DateTime($_SESSION['date01']);
      $_SESSION['date1'] = $_SESSION['date1']->format('Ymd');
      
      $_SESSION['date02']=$_POST['j2'];
      $_SESSION['date2'] = new DateTime($_SESSION['date02']);
      $_SESSION['date2'] = $_SESSION['date2']->format('Ymd');

      $_SESSION['dates1']=(new DateTime($_SESSION['date01']))->format('d/m/Y');
      $_SESSION['dates2']=(new DateTime($_SESSION['date02']))->format('d/m/Y');  
    }


    if (isset($_POST['j2'])) {

      $datenormale='entre le '.$_SESSION['dates1'].' et le '.$_SESSION['dates2'];

    }else{

      $datenormale=(new DateTime($dates))->format('Y');
    }

    
    //require 'pagination.php';

    if ((isset($_GET['general']) or isset($_POST['j1']) or isset($_POST['j2']) or isset($_GET['compta']))) {

        if ($_SESSION['etab']!='') {?>
            <div class="row mx-0 px-0" style="overflow:auto;">

                <table class="table table-hover table-bordered table-striped table-responsive text-center mt-2">

                    <tbody>
                        <tr>
                            <th>Nom</th><?php 
                            foreach ($panier->monnaie as $valuep) {?>
                                <th><?=strtoupper($valuep);?></th><?php 
                            }?>
                        </tr><?php 
                        $caisse=0;
                        foreach ($panier->nomBanque() as $banque) {?>
                            <tr>
                                <th><?=strtoupper($banque->nomb);?></th><?php
                                 
                                foreach ($panier->monnaie as $valuep) {
                                    $caisse+=$panier->caisse($banque->id, $valuep)[0];?>
                                    <th><?=number_format($panier->caisse($banque->id, $valuep)[0],0,',',' ');?></th>
                                    <?php
                                }?>
                            </tr>                            
                            <?php
                        }?>

                        <tr>
                            <th class="bg-info">Totaux</th><?php 
                            foreach ($panier->monnaie as $valuep) {?>
                                <th class="bg-info"><?=number_format($panier->cumulCaisse($valuep),0,',',' ');?></th>
                                <?php
                            }?>
                        </tr> 
                    </tbody>
                </table>
            </div><?php 
        }?>

        <div class="row mx-0 px-0" style="overflow:auto;">

            <table class="synthesecompta m-0">

                <thead>

                    <tr>
                        <form id='formulaire' method="POST" action="synthesecompta.php" name="termc" style="height: 30px;"><?php

                            if (isset($_POST['j1'])) {?>

                                <th style="border-right: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"></th><?php

                            }else{?>

                                <th style="border-right: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" onchange="this.form.submit()"></th><?php

                            }

                            if (isset($_POST['j2'])) {?>

                                <th style="border-left: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"></th><?php

                            }else{?>

                                <th style="border-left: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" onchange="this.form.submit()"></th><?php

                            }?>


                            <th colspan="2" height="30"><?='Comptabilité générale '.$datenormale;?><a style="margin-left: 10px;"href="printdoc.php?synthesem&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>&datenormale=<?=$datenormale;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
                        </form>
                    </tr>

                    <tr>
                        <th height="30">Prestation</th>
                        <th>Nbre</th>
                        <th>Entrées</th>
                        <th>Sorties</th>
                    </tr>

                </thead>

                <tbody><?php
                

                    $totins=0;
                    $nbreins=0;

                    if (!empty($_SESSION['niveauf'])) {

                        $prodins =$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement inner join inscription on inscription.matricule=payement.matricule WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and niveau=:niv and annee=:annee and promo=:promo and motif=:motif and etat=:etat', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'niv'=>$_SESSION['niveauf'], 'annee'=>$_SESSION['promo'], 'promo'=>$_SESSION['promo'], 'motif'=>'inscription', 'etat'=>'inscription'));

                    }else{

                        if (isset($_POST['j1'])) {

                            $prodins =$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and promo=:promo and motif=:motif', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo'], 'motif'=>'inscription'));
                        }else{

                            $prodins =$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE  promo=:promo and motif=:motif ', array('promo'=>$_SESSION['promo'], 'motif'=>'inscription'));
                        }
                    }

                    $totins+= $prodins['montant'];
                    $nbreins+=$prodins['nbre'];
                    $_SESSION['totins']=$totins;
                    $_SESSION['nbreins']=$nbreins;
                    ?>

                    <tr><?php
                    if (empty($prodins)) {
                        # code...
                    }else{?>
                        <td><a style="text-decoration: none; color: white;" href="synthesescolarite.php?ssins&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>"> Frais d'inscription</a></td>
                        <td style="text-align: center;"><?= $prodins['nbre']; ?></td>
                        <td>
                            <table class="synthesecomptatype">
                                <tbody><?php 
                                    foreach ($panier->modep as $value) {

                                        if (!empty($_SESSION['niveauf'])) {

                                            $prodinst=$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement inner join inscription on inscription.matricule=payement.matricule WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and niveau=:niv and typepaye=:typep and annee=:annee and promo=:promo and motif=:motif and etat=:etat', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'niv'=>$_SESSION['niveauf'], 'typep'=>$value, 'annee'=>$_SESSION['promo'], 'promo'=>$_SESSION['promo'], 'motif'=>'inscription', 'etat'=>'inscription'));

                                        }else{

                                            if (isset($_POST['j1'])) {

                                                $prodinst=$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and typepaye=:typep and promo=:promo and motif=:motif', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'typep'=>$value, 'promo'=>$_SESSION['promo'], 'motif'=>'inscription'));
                                            }else{

                                                $prodinst=$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE typepaye=:typep and promo=:promo and motif=:motif', array('typep'=>$value, 'promo'=>$_SESSION['promo'], 'motif'=>'inscription'));

                                            }
                                        }
                                        if (!empty($prodinst['montant'])) {?>

                                            <tr>
                                                <td><?=ucfirst($value);?>...</td>
                                                <td style="text-align: right;"><?=number_format($prodinst['montant'],0,',',' ');?></td>
                                            </tr><?php 
                                        }
                                    }?>

                                    <tr>
                                        <td>Total...</td>
                                        <th style="text-align: right;"><?= number_format($prodins['montant'],0,',',' '); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="text-align: right;">-</td><?php
                    }?>
                    
                    </tr>

                    <?php
                

                    $totreins=0;
                    $nbrereins=0;

                    if (!empty($_SESSION['niveauf'])) {

                        $prodins =$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement inner join inscription on inscription.matricule=payement.matricule WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and niveau=:niv and annee=:annee and promo=:promo and motif=:motif and etat=:etat', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'niv'=>$_SESSION['niveauf'], 'annee'=>$_SESSION['promo'], 'promo'=>$_SESSION['promo'], 'motif'=>'reinscription', 'etat'=>'reinscription'));

                    }else{

                        if (isset($_POST['j1'])) {

                            $prodins =$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and promo=:promo and motif=:motif', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo'], 'motif'=>'reinscription'));
                        }else{

                            $prodins =$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE  promo=:promo and motif=:motif ', array('promo'=>$_SESSION['promo'], 'motif'=>'reinscription'));
                        }
                    }

                    $totreins+= $prodins['montant'];
                    $nbrereins+=$prodins['nbre'];
                    $_SESSION['totreins']=$totreins;
                    $_SESSION['nbrereins']=$nbrereins;
                    ?>

                    <tr><?php
                    if (empty($prodins)) {
                        # code...
                    }else{?>
                        <td><a style="text-decoration: none; color: white;" href="synthesescolarite.php?ssins&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>"> Frais de Reinscription</a></td>
                        <td style="text-align: center;"><?= $prodins['nbre']; ?></td>
                        <td>
                            <table class="synthesecomptatype">
                                <tbody><?php 
                                    foreach ($panier->modep as $value) {

                                        if (!empty($_SESSION['niveauf'])) {

                                            $prodinst=$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement inner join inscription on inscription.matricule=payement.matricule WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and niveau=:niv and typepaye=:typep and annee=:annee and promo=:promo and motif=:motif and etat=:etat', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'niv'=>$_SESSION['niveauf'], 'typep'=>$value, 'annee'=>$_SESSION['promo'], 'promo'=>$_SESSION['promo'], 'motif'=>'reinscription', 'etat'=>'reinscription'));

                                        }else{

                                            if (isset($_POST['j1'])) {

                                                $prodinst=$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and typepaye=:typep and promo=:promo and motif=:motif', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'typep'=>$value, 'promo'=>$_SESSION['promo'], 'motif'=>'reinscription'));
                                            }else{

                                                $prodinst=$DB->querys('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE typepaye=:typep and promo=:promo and motif=:motif', array('typep'=>$value, 'promo'=>$_SESSION['promo'], 'motif'=>'reinscription'));

                                            }
                                        }
                                        if (!empty($prodinst['montant'])) {?>

                                            <tr>
                                                <td><?=ucfirst($value);?>...</td>
                                                <td style="text-align: right;"><?=number_format($prodinst['montant'],0,',',' ');?></td>
                                            </tr><?php
                                        } 
                                    }?>

                                    <tr>
                                        <td>Total...</td>
                                        <th style="text-align: right;"><?= number_format($prodins['montant'],0,',',' '); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="text-align: right;">-</td><?php
                    }?>
                    
                    </tr><?php


                    $totfrais=0;
                    $nbrefrais=0;

                    if (!empty($_SESSION['niveauf'])) {

                        $prodscol =$DB->querys('SELECT SUM(montant) AS montant, COUNT(tranche) AS nbre, tranche FROM payementfraiscol inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE niveau=:niv and DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and annee=:annee and promo=:promo', array('niv'=>$_SESSION['niveauf'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'annee'=>$_SESSION['promo'], 'promo'=>$_SESSION['promo']));

                    }else{

                        if (isset($_POST['j1'])) {

                            $prodscol=$DB->querys('SELECT SUM(montant) AS montant, COUNT(tranche) AS nbre, tranche FROM payementfraiscol WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and promo=:promo', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo']));
                        }else{

                            $prodscol=$DB->querys('SELECT SUM(montant) AS montant, COUNT(tranche) AS nbre, tranche FROM payementfraiscol WHERE promo=:promo', array('promo'=>$_SESSION['promo']));
                        }
                    }

                    $totfrais+= $prodscol['montant'];
                    $nbrefrais+=$prodscol['nbre'];
                    $_SESSION['totfrais']=$totfrais;
                    $_SESSION['nbrefrais']=$nbrefrais;
                    ?>

                    <tr><?php
                    if (empty($prodscol)) {
                        # code...
                    }else{?>
                        <td><a style="text-decoration: none; color: white;" href="synthesescolarite.php?sscol&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>">Frais de scolarité</a></td>
                        <td style="text-align: center;"><?= $prodscol['nbre']; ?></td>
                        <td>
                            <table class="synthesecomptatype">
                                <tbody><?php 
                                    foreach ($panier->modep as $value) {

                                        if (!empty($_SESSION['niveauf'])) {

                                            $prodscolt=$DB->querys('SELECT SUM(montant) AS montant, COUNT(tranche) AS nbre, tranche FROM payementfraiscol inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE niveau=:niv and DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and typepaye=:typep and annee=:annee and promo=:promo', array('niv'=>$_SESSION['niveauf'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'typep'=>$value, 'annee'=>$_SESSION['promo'], 'promo'=>$_SESSION['promo']));

                                        }else{

                                            if (isset($_POST['j1'])) {

                                                $prodscolt=$DB->querys('SELECT SUM(montant) AS montant, COUNT(tranche) AS nbre, tranche FROM payementfraiscol WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and typepaye=:typep and promo=:promo', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'typep'=>$value, 'promo'=>$_SESSION['promo']));
                                            }else{

                                                $prodscolt=$DB->querys('SELECT SUM(montant) AS montant, COUNT(tranche) AS nbre, tranche FROM payementfraiscol WHERE typepaye=:typep and promo=:promo', array('typep'=>$value, 'promo'=>$_SESSION['promo']));

                                            }
                                        }
                                        if (!empty($prodscolt['montant'])) {?>

                                            <tr>
                                                <td><?=ucfirst($value);?>...</td>
                                                <td style="text-align: right;"><?=number_format($prodscolt['montant'],0,',',' ');?></td>
                                            </tr><?php 
                                        }
                                    }?>

                                    <tr>
                                        <td>Total...</td>
                                        <th style="text-align: right;"><?= number_format($prodscol['montant'],0,',',' '); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="text-align: right;">-</td><?php
                    }?>
                    
                </tr><?php // activités


                $totactivites=0;
                $nbreactivites=0;

                if (!empty($_SESSION['niveauf'])) {

                    $prodactivites=$DB->querys("SELECT count(montantp) as nbre, sum(montantp) as montant FROM activitespaiehistorique left join inscription on matricule=matp left join elevexterne on matex=matp where niveau='{$_SESSION['niveauf']}' and DATE_FORMAT(dateop, \"%Y%m%d\") >='{$_SESSION['date1']}' and DATE_FORMAT(dateop, \"%Y%m%d\") <='{$_SESSION['date2']}' and anneep='{$_SESSION['promo']}' ");

                }else{

                    if (isset($_POST['j1'])) {

                        $prodactivites=$DB->querys("SELECT count(id) as nbre, sum(montantp) as montant FROM activitespaiehistorique  where DATE_FORMAT(dateop, \"%Y%m%d\") >='{$_SESSION['date1']}' and DATE_FORMAT(dateop, \"%Y%m%d\") <='{$_SESSION['date2']}' and anneep='{$_SESSION['promo']}'  ");                                

                    }else{

                        $prodactivites=$DB->querys("SELECT count(id) as nbre, sum(montantp) as montant FROM activitespaiehistorique  where anneep='{$_SESSION['promo']}'  ");

                        
                    }
                }

                $totactivites+= $prodactivites['montant'];
                $nbreactivites+=$prodactivites['nbre'];
                $_SESSION['totactivites']=$totfrais;
                $_SESSION['nbreactivites']=$nbrefrais;
                ?>

                <tr><?php
                    if (empty($prodscol)) {
                        # code...
                    }else{?>
                        <td><a style="text-decoration: none; color: white;" href="activitespaie.php?sscol&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>">Situation Activites</a></td>
                        <td style="text-align: center;"><?= $prodactivites['nbre']; ?></td>
                        <td>
                            <table class="synthesecomptatype">
                                <tbody><?php 
                                    foreach ($panier->modep as $value) {

                                        if (!empty($_SESSION['niveauf'])) {

                                            $prodactivitest=$DB->querys("SELECT count(montantp) as nbre, sum(montantp) as montant FROM activitespaiehistorique left join inscription on matricule=matp left join elevexterne on matex=matp where modep='{$value}' and niveau='{$_SESSION['niveauf']}' and DATE_FORMAT(dateop, \"%Y%m%d\") >='{$_SESSION['date1']}' and DATE_FORMAT(dateop, \"%Y%m%d\") <='{$_SESSION['date2']}' and anneep='{$_SESSION['promo']}' and annee='{$_SESSION['promo']}' ");

                                        }else{

                                            if (isset($_POST['j1'])) {

                                                $prodactivitest=$DB->querys("SELECT count(montantp) as nbre, sum(montantp) as montant FROM activitespaiehistorique left join inscription on matricule=matp left join elevexterne on matex=matp where modep='{$value}' and DATE_FORMAT(dateop, \"%Y%m%d\") >='{$_SESSION['date1']}' and DATE_FORMAT(dateop, \"%Y%m%d\") <='{$_SESSION['date2']}' and anneep='{$_SESSION['promo']}' and annee='{$_SESSION['promo']}' ");
                                            }else{

                                                $prodactivitest=$DB->querys("SELECT count(montantp) as nbre, sum(montantp) as montant FROM activitespaiehistorique left join inscription on matricule=matp left join elevexterne on matex=matp where modep='{$value}' and anneep='{$_SESSION['promo']}' and annee='{$_SESSION['promo']}' ");

                                            }
                                        }
                                        if (!empty($prodactivitest['montant'])) {?>

                                            <tr>
                                                <td><?=ucfirst($value);?>...</td>
                                                <td style="text-align: right;"><?=number_format($prodactivitest['montant'],0,',',' ');?></td>
                                            </tr><?php
                                        } 
                                    }?>

                                    <tr>
                                        <td>Total...</td>
                                        <th style="text-align: right;"><?= number_format($prodactivites['montant'],0,',',' '); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="text-align: right;">-</td><?php
                    }?>
                
                </tr><?php

                // versements

                $versement=0;
                $nbrevers=0;
                foreach ($panier->listeCategorieVers() as $keydec => $document){

                    if (isset($_POST['j1'])) {

                        $prodep =$DB->querys('SELECT SUM(montant*taux) AS montant, COUNT(id) AS nbre, categorie FROM versement WHERE categorie=:type AND DATE_FORMAT(date_versement, \'%Y%m%d\') >= :date1 and DATE_FORMAT(date_versement, \'%Y%m%d\') <= :date2 and promo=:promo', array('type'=>$document->id, 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo']));
                    }else{

                        $prodep =$DB->querys('SELECT SUM(montant*taux) AS montant, COUNT(id) AS nbre, categorie FROM versement WHERE categorie=:type and promo=:promo', array('type'=>$document->id, 'promo'=>$_SESSION['promo']));

                    }

                    $versement+= $prodep['montant'];
                    $nbrevers+=$prodep['nbre'];
                    $_SESSION['versement']=$versement;
                    $_SESSION['nbrevers']=$nbrevers;?>

                    <tr><?php
                    if (empty($prodep['montant'])) {
                        # code...
                    }else{?>
                        <td><a style="text-decoration: none; color: white;" href="versement.php?sdep&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>"><?= ucfirst(strtolower($document->nom)); ?></a></td>
                        <td style="text-align: center;"><?= $prodep['nbre'];?></td>
                        <td>
                            <table class="synthesecomptatype">
                                <tbody><?php 
                                    foreach ($panier->modep as $value) {

                                        if (isset($_POST['j1'])) {

                                            $prodept =$DB->querys('SELECT SUM(montant*taux) AS montant, COUNT(id) AS nbre, categorie FROM versement WHERE categorie=:type AND DATE_FORMAT(date_versement, \'%Y%m%d\') >= :date1 and DATE_FORMAT(date_versement, \'%Y%m%d\') <= :date2 and type_versement=:typep and promo=:promo', array('type'=>$document->id, 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'typep'=>$value, 'promo'=>$_SESSION['promo']));
                                        }else{

                                            $prodept =$DB->querys('SELECT SUM(montant*taux) AS montant, COUNT(id) AS nbre, categorie FROM versement WHERE categorie=:type and type_versement=:typep and promo=:promo', array('type'=>$document->id, 'typep'=>$value, 'promo'=>$_SESSION['promo']));

                                        }
                                        if (!empty($prodept['montant'])) {?>

                                            <tr>
                                                <td><?=ucfirst($value);?>...</td>
                                                <td style="text-align: right;"><?=number_format($prodept['montant'],0,',',' ');?></td>
                                            </tr><?php 
                                        }
                                    }?>

                                    <tr>
                                        <td>Total...</td>
                                        <th style="text-align: right;"><?= number_format($prodep['montant'],0,',',' '); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        
                        <td style="text-align: right;">-</td>
                        <?php
                    }?>
                    
                    </tr><?php
                }




                    $totb=0;
                    $totp=0;
                    $totc=0;
                    $nbreb=0;

                    if (isset($_POST['j1'])) {

                        $prodlivre=$DB->querys('SELECT SUM(totalp) AS montant, SUM(totalc) AS montantc, COUNT(id) AS nbre FROM payelivre WHERE DATE_FORMAT(datecmd, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datecmd, \'%Y%m%d\') <= :date2', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));
                    }else{

                        $prodlivre=$DB->querys('SELECT SUM(totalp) AS montant, SUM(totalc) AS montantc, COUNT(id) AS nbre FROM payelivre');

                    }

                    $totp+= $prodlivre['montant'];
                    $totc+= $prodlivre['montantc'];
                    $nbreb+=$prodlivre['nbre'];
                    $_SESSION['totp']=$totp;
                    $_SESSION['totc']=$totc;
                    $_SESSION['nbrefrais']=$nbreb;
                    ?>

                    <tr><?php
                    if (empty($prodlivre['montant'])) {
                        # code...
                    }else{?>
                        <td><a style="text-decoration: none; color: white;" href="#?sscol&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>">Bibliothèque</a></td>
                        <td style="text-align: center;"><?= $prodlivre['nbre']; ?></td>
                        <td>
                            <table class="synthesecomptatype">
                                <tbody><?php 
                                    foreach ($panier->modep as $value) {

                                        if (isset($_POST['j1'])) {

                                            $prodlivre =$DB->querys('SELECT SUM(totalp) AS montant, SUM(totalc) AS montantc, COUNT(id) AS nbre FROM payelivre WHERE DATE_FORMAT(datecmd, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datecmd, \'%Y%m%d\') <= :date2 and typep=:typep', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'typep'=>$value));
                                        }else{

                                            $prodlivre =$DB->querys('SELECT SUM(totalp) AS montant, SUM(totalc) AS montantc, COUNT(id) AS nbre FROM payelivre WHERE typep=:typep', array('typep'=>$value));

                                        }
                                        
                                        if (!empty($prodlivre['montant'])) {?>

                                            <tr>
                                                <td><?=ucfirst($value);?>...</td>
                                                <td style="text-align: right;"><?=number_format($prodlivre['montantc'],0,',',' ');?></td>
                                            </tr><?php 
                                        }
                                    }?>

                                    <tr>
                                        <td>Total...</td>
                                        <th style="text-align: right;"><?= number_format($totc,0,',',' '); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="text-align: right;">-</td><?php
                    }?>
                    
                    </tr><?php

                    $sortie=0;
                    $nbresortie=0;
                    foreach ($panier->listeCategorie() as $keydec => $document){

                        if (isset($_POST['j1'])) {

                            $prodep =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre, motif FROM decaissement WHERE motif=:type AND DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and promo=:promo', array('type'=>$document->id, 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo']));
                        }else{

                            $prodep =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre, motif FROM decaissement WHERE motif=:type and promo=:promo', array('type'=>$document->id, 'promo'=>$_SESSION['promo']));

                        }

                        $sortie+= $prodep['montant'];
                        $nbresortie+=$prodep['nbre'];
                        $_SESSION['sortie']=$sortie;
                        $_SESSION['nbresortie']=$nbresortie;?>

                        <tr><?php
                        if (empty($prodep['montant'])) {
                            # code...
                        }else{?>
                            <td><a style="text-decoration: none; color: white;" href="synthesedepense.php?sdep&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>"><?= ucfirst(strtolower($document->nom)); ?></a></td>
                            <td style="text-align: center;"><?= $prodep['nbre'];?></td>
                            <td style="text-align: right;">-</td>
                            <td>
                                <table class="synthesecomptatype">
                                    <tbody><?php 
                                        foreach ($panier->modep as $value) {

                                            if (isset($_POST['j1'])) {

                                                $prodept =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre, motif FROM decaissement WHERE motif=:type AND DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and typepaye=:typep and promo=:promo', array('type'=>$document->id, 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'typep'=>$value, 'promo'=>$_SESSION['promo']));
                                            }else{

                                                $prodept =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre, motif FROM decaissement WHERE motif=:type and typepaye=:typep and promo=:promo', array('type'=>$document->id, 'typep'=>$value, 'promo'=>$_SESSION['promo']));

                                            }
                                            if (!empty($prodept['montant'])) {?>

                                                <tr>
                                                    <td><?=ucfirst($value);?>...</td>
                                                    <td style="text-align: right;"><?=number_format($prodept['montant'],0,',',' ');?></td>
                                                </tr><?php 
                                            }
                                        }?>

                                        <tr>
                                            <td>Total...</td>
                                            <th style="text-align: right;"><?= number_format($prodep['montant'],0,',',' '); ?></th>
                                        </tr>
                                    </tbody>
                                </table>
                        </td><?php
                        }?>
                        
                        </tr><?php
                    }

                    //Accompte
                

                    $sortieac=0;
                    $nbresortieac=0;
                    if (isset($_POST['j1'])) {

                        $prodac=$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM accompte WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and anneescolaire=:promo', array('date1' =>$_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo']));
                    }else{

                        $prodac=$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM accompte WHERE anneescolaire=:promo', array('promo'=>$_SESSION['promo']));

                    }

                    $sortieac+= $prodac['montant'];
                    $nbresortieac+=$prodac['nbre'];
                    $_SESSION['sortieac']=$sortieac;
                    $_SESSION['nbresortieac']=$nbresortieac;?>

                    <tr><?php
                    if (empty($prodac['montant'])) {
                        # code...
                    }else{?>
                        <td><a style="text-decoration: none; color: white;" href="syntheseacompte.php?sscol&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>">Avance Sur Salaire</a></td>
                        <td style="text-align: center;"><?= $prodac['nbre'];?></td>
                        <td style="text-align: right;">-</td>
                        <td>
                            <table class="synthesecomptatype">
                                <tbody><?php 
                                    foreach ($panier->modep as $value) {

                                        if (isset($_POST['j1'])) {

                                            $prodact =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM accompte WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and typepaye=:typep and anneescolaire=:promo', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'typep'=>$value, 'promo'=>$_SESSION['promo']));
                                        }else{
                                            $prodact =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM accompte WHERE typepaye=:typep and anneescolaire=:promo', array('typep'=>$value, 'promo'=>$_SESSION['promo']));
                                        }
                                        if (!empty($prodact['montant'])) {?>

                                            <tr>
                                                <td><?=ucfirst($value);?>...</td>
                                                <td style="text-align: right;"><?=number_format($prodact['montant'],0,',',' ');?></td>
                                            </tr><?php 
                                        }
                                    }?>

                                    <tr>
                                        <td>Total...</td>
                                        <th style="text-align: right;"><?= number_format($prodac['montant'],0,',',' '); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </td><?php
                    }?>
                    
                    </tr><?php

                    //payement personnels
                

                    $sortiep=0;
                    $nbresortiep=0;

                    if (isset($_POST['j1'])) {

                        $prodpers =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM payepersonnel WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and promo=:promo', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo']));
                    }else{

                        $prodpers =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM payepersonnel WHERE promo=:promo', array('promo'=>$_SESSION['promo']));
                    }

                    $sortiep+= $prodpers['montant'];
                    $nbresortiep+=$prodpers['nbre'];
                    $_SESSION['sortiep']=$sortiep;
                    $_SESSION['nbresortiep']=$nbresortiep;?>

                    <tr><?php
                    if (empty($prodpers['montant'])) {
                        # code...
                    }else{?>
                        <td><a style="text-decoration: none; color: white;" href="synthesepersonnel.php?sscol&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>">Paiement des Personnels</a></td>
                        <td style="text-align: center;"><?= $prodpers['nbre'];?></td>
                        <td style="text-align: right;">-</td>
                        <td>
                            <table class="synthesecomptatype">
                                <tbody><?php 
                                    foreach ($panier->modep as $value) {
                                        if (isset($_POST['j1'])) {
                                            $prodperst =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM payepersonnel WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and typepaye=:typep and promo=:promo', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'typep'=>$value, 'promo'=>$_SESSION['promo']));
                                        }else{

                                            $prodperst =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM payepersonnel WHERE typepaye=:typep and promo=:promo', array('typep'=>$value, 'promo'=>$_SESSION['promo']));

                                        }
                                        if (!empty($prodperst['montant'])) {?>

                                            <tr>
                                                <td><?=ucfirst($value);?>...</td>
                                                <td style="text-align: right;"><?=number_format($prodperst['montant'],0,',',' ');?></td>
                                            </tr><?php 
                                        }
                                    }?>

                                    <tr>
                                        <td>Total...</td>
                                        <th style="text-align: right;"><?= number_format($prodpers['montant'],0,',',' '); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </td><?php
                    }?>
                    
                    </tr><?php

                    $sortiens=0;
                    $nbresortiens=0;

                    if (isset($_POST['j1'])) {

                        $prodens =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM payenseignant WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and anneescolaire=:promo', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo']));
                    }else{

                        $prodens =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM payenseignant WHERE anneescolaire=:promo', array('promo'=>$_SESSION['promo']));

                    }

                    $sortiens+= $prodens['montant'];
                    $nbresortiens+=$prodens['nbre'];
                    $_SESSION['sortiep']=$sortiens;
                    $_SESSION['nbresortiep']=$nbresortiens;?>

                    <tr><?php
                    if (empty($prodens['montant'])) {
                        # code...
                    }else{?>
                        <td><a style="text-decoration: none; color: white;" href="syntheseenseignant.php?sscol&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>">Paiement des Enseignants</a></td>
                        <td style="text-align: center;"><?=$prodens['nbre'];?></td>
                        <td style="text-align: right;">-</td>
                        <td>
                            <table class="synthesecomptatype">
                                <tbody><?php 
                                    foreach ($panier->modep as $value) {

                                        if (isset($_POST['j1'])) {

                                            $prodenst =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM payenseignant WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and typepaye=:typep and anneescolaire=:promo', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'typep'=>$value, 'promo'=>$_SESSION['promo']));
                                        }else{

                                            $prodenst =$DB->querys('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM payenseignant WHERE typepaye=:typep and anneescolaire=:promo', array('typep'=>$value, 'promo'=>$_SESSION['promo']));

                                        }
                                        if (!empty($prodenst['montant'])) {?>

                                            <tr>
                                                <td><?=ucfirst($value);?>...</td>
                                                <td style="text-align: right;"><?=number_format($prodenst['montant'],0,',',' ');?></td>
                                            </tr><?php 
                                        }
                                    }?>

                                    <tr>
                                        <td>Total...</td>
                                        <th style="text-align: right;"><?= number_format($prodens['montant'],0,',',' '); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </td><?php
                    }?>
                    
                    </tr>

                    
                </tbody><?php
                $nbretotal=$nbreins+$nbrereins+$nbrefrais+$nbreactivites+$nbrevers+$nbreb+$nbresortie+$nbresortiep+$nbresortiens+$nbresortieac;
                $totalcredit=$totins+$totreins+$totfrais+$totactivites+$versement+$totc;
                $totaldebiter=$sortie+$sortiep+$sortiens+$sortieac;
                $solde=$totalcredit-$totaldebiter;?>
                <thead>

                    <tr>
                        <th class="legende" height="30">Total: </th>
                        <th style="text-align: center;"><?=$nbretotal;?></th>
                        <th style="text-align: right;"><?=number_format($totalcredit,0,',',' ');?></th>
                        <th style="text-align: right;"><?=number_format($totaldebiter,0,',',' ');?></th>
                    </tr>

                    <tr>
                        <th height="30" colspan="2">Solde: </th><?php
                        if ($solde>=0) {?>
                            <th colspan="2" style="background-color: green; text-align: center;"><?=number_format($solde,0,',',' ');?></th><?php
                        }else{?>
                            <th colspan="2" style="background-color: red; text-align: center;"><?=number_format($solde,0,',',' ');?></th><?php
                        }?>
                    </tr>

                </thead>

            </table>
        </div>
        <?php
    }?>
</div>


