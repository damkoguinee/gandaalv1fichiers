<?php

require 'headerv2.php';

$bdd='relevegeneralebul'; 
$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `matricule` varchar(50) NOT NULL,
    `moyenne` float NOT NULL,
    `trimestre` int(11) NOT NULL,
    `codef` varchar(50) NOT NULL,
    `pseudo` varchar(50) NOT NULL,
    `promo` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");

$bdd='rangel'; 
$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `matricule` varchar(50) NOT NULL,
  `rang` int(10) NOT NULL,
  `moyenne` float NOT NULL,
  `pseudo` varchar(50) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");

if (isset($_GET['printnote'])){?>

    <style type="text/css">
        table.tablistebul{
          background-color: white;
          width: 100%;
          color: #717375;
          font-family: helvetica;
          border-collapse: collapse; 
          margin-top: 30px;     
        }
        .tablistebul th {
          line-height: 5mm;
          border: 2px solid grey;
          background-color: white;
          color: grey;
          font-size: 14px;
          font-weight: bold;
          text-align: center;
        }
        .tablistebul td {
          border: 2px solid grey;
          line-height: 5mm;
          text-align: right;
          padding-right: 10px; 
          font-size: 14px;
        }

        label {
            float: right;
            font-size: 14px;
            font-weight: bold;
            width: 200px;
          }

          ol{
            list-style: none;
          }
    </style><?php

    //require 'enteteprint.php';
    
}

$etab=$DB->querys('SELECT *from etablissement');

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{

        if (isset($_POST['groupe'])){

            $_SESSION['groupe']=$_POST['groupe'];
            $_SESSION['semestre']='choisissez le ';
        }

        if (isset($_POST['semestre'])){

            $_SESSION['semestre']=$_POST['semestre'];

        }

        

        if (isset($_GET['printnote'])){

            $_SESSION['semestre']=$_SESSION['semestre'];
            $_SESSION['groupe']=$_SESSION['groupe'];

        }

        if (isset($_POST['eleve'])){

            $_SESSION['eleve']=$_POST['eleve'];
        }?>

        <div class="container-fluid"><?php

            if (!isset($_GET['printnote'])){
                //require 'navnote.php';
            }
            if (!isset($_GET['printnote'])){

                require 'formbulletin.php';
            }
            $etat = 'actif';
                if ((isset($_POST['groupe']) or isset($_GET['printnote']) or isset($_POST['semestre']) or isset($_POST['mois'])) and $_SESSION['semestre']!='choisissez le semestre') {


                    $prodcount=$DB->querys('SELECT count(matricule) as countel, codef, niveau from inscription where  nomgr=:nom and annee=:promo and etatscol=:etat order by (matricule)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo'], 'etat' => $etat));

                    $niveauclasse=$prodcount['niveau'];
                    if ($niveauclasse=="maternelle") {
                        $niveauclasse="primaire";
                    }
                    $_SESSION['niveauclasse']=$niveauclasse;

                    $prodmoyeg=$DB->querys('SELECT count(DISTINCT(effectifn.matricule)) as coef from effectifn inner join inscription on inscription.matricule = effectifn.matricule where  effectifn.nomgr=:nom and inscription.nomgr=:nomIns and annee=:annee and promo=:promo and etatscol=:etat', array('nom'=>$_SESSION['groupe'], 'nomIns'=>$_SESSION['groupe'],'annee' => $_SESSION['promo'], 'promo'=>$_SESSION['promo'], 'etat' => $etat));
                
                    if ($prodmoyeg['coef']!=0) {
                        $nbrelegen=$prodmoyeg['coef'];// nbre élève
                    }else{
                        $nbrelegen=1;
                    }

                    $prodgr=$DB->querys('SELECT codef from inscription where nomgr=:nom and annee=:promo and etatscol=:etat order by (matricule)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo'], 'etat' => $etat));

                    //$nbrele=$prodcount['countel'];//Pour avoir le nombre d'élève

                    $prodmat=$DB->query('SELECT  inscription.matricule as matricule, codef, nomel, prenomel, DATE_FORMAT(naissance, \'%d/%m/%Y\')AS naissance from inscription inner join eleve on inscription.matricule=eleve.matricule where nomgr=:nom and annee=:promo and etatscol=:etat order by (prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo'], 'etat' => $etat));

                    $prodmatiere=$DB->query('SELECT nommat, codem, coef from  matiere where codef=:nom order by(cat)', array('nom'=>$prodgr['codef']));

                    require 'moyennegeneraleeleve.php';


                    ///*********************calcul de leffectif ayant compose*********************** */
                    $prodgr=$DB->querys('SELECT codef from  groupe where nomgr=:nom and promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

                    $prodevoir=$DB->query('SELECT nommat, matiere.codem as codem, coef from  matiere inner join enseignement on enseignement.codem=matiere.codem where matiere.codef=:nom and nomgr=:nomgr and promo=:promo order by(cat)', array('nom'=>$prodgr['codef'], 'nomgr'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
                    $tabcoef=array();
                    foreach ($prodevoir as $devoir) {

                        foreach ($prodmat as $mat) {//prod viens en haut dans le calcul de la moyenne générale

                            if (isset($_POST['mois']) or isset($_GET['mois'])) {
                                                                            
                                $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and DATE_FORMAT(datedev, \'%m\')=:sem and annee=:promo and devoir.promo=:promo1 and etatscol=:etat', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'sem'=>$_SESSION['mois'], 'promo1'=>$_SESSION['promo'], 'etat' => $etat));

                            }elseif (isset($_POST['semestre']) or isset($_GET['semestre'])) {
                                                                            
                                $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and trimes=:sem and annee=:promo and devoir.promo=:promo1 and etatscol=:etat', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'sem'=>$_SESSION['semestre'], 'promo1'=>$_SESSION['promo'], 'etat' => $etat));

                            }else{

                                $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and annee=:promo and devoir.promo=:promo1 and etatscol=:etat', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'promo1'=>$_SESSION['promo'], 'etat' => $etat));
                            }
                            
                            foreach ($prodnote as $note) {// Recupération du nbre des élèves ayant été evalués
                                if (!empty($note->id)) {
                                    $prodmoymat=$DB->querys('SELECT count(effectifn.matricule) as coef from effectifn inner join inscription on inscription.matricule = effectifn.matricule where effectifn.codev=:code  and effectifn.nomgr=:nom and inscription.nomgr=:nomIns and annee=:annee  and promo=:promo and etatscol=:etat', array('code'=>$note->id, 'nom'=>$_SESSION['groupe'], 'nomIns'=>$_SESSION['groupe'], 'annee'=> $_SESSION['promo'], 'promo'=>$_SESSION['promo'], 'etat' => $etat));

                                    array_push($tabcoef, $prodmoymat['coef']);
                                    
                                }
                            }
                        }
                    }
                    if (empty($tabcoef)) {
                        $maxcoef=0;
                    }else{
                        $maxcoef=max($tabcoef);
                    }
                    $nbrele=$maxcoef;

                    // *****************************************************************

                    
                    $moyengenerale=0;
                    $moyengen=0;

                    if (isset($_POST['semestre'])) {

                        if ($_POST['semestre']==1) {

                            $periode=$_POST['semestre'].'er '.$_SESSION['prodtype'];
                        }else{
                            $periode=$_POST['semestre'].'ème '.$_SESSION['prodtype'];
                        }

                        $prodabs=$DB->querys('SELECT count(nbreheure) as nbreh from absence where promo=:promo and semestre=:annee and nomgr=:nom and absence.id not in(SELECT id_absence FROM justabsence)', array('promo'=>$_SESSION['promo'], 'annee' => $_POST['semestre'], 'nom'=>$_SESSION['groupe']));

                        $prodret=$DB->querys('SELECT count(timeretard) as nbrer from retard where promo=:promo and semestre=:annee and nomgr=:nom and retard.id not in(SELECT id_absence FROM justretard)', array('promo'=>$_SESSION['promo'], 'annee' => $_POST['semestre'], 'nom'=>$_SESSION['groupe']));


                    }elseif (isset($_GET['semestre'])) {
                        if ($_GET['semestre']==1) {

                            $periode=$_GET['semestre'].'er '.$_SESSION['prodtype'];
                        }else{
                            $periode=$_GET['semestre'].'ème '.$_SESSION['prodtype'];
                        }

                        $prodabs=$DB->querys('SELECT count(nbreheure) as nbreh from absence where promo=:promo and semestre=:annee and nomgr=:nom and absence.id not in(SELECT id_absence FROM justabsence)', array('promo'=>$_SESSION['promo'], 'annee' => $_GET['semestre'], 'nom'=>$_SESSION['groupe']));

                        $prodret=$DB->querys('SELECT count(timeretard) as nbrer from retard where promo=:promo and semestre=:annee and nomgr=:nom and retard.id not in(SELECT id_absence FROM justretard)', array('promo'=>$_SESSION['promo'], 'annee' => $_GET['semestre'], 'nom'=>$_SESSION['groupe']));

                    }elseif (isset($_POST['mois'])) {
                        
                        $periode=$panier->moisbul();

                        $prodabs=$DB->querys('SELECT count(nbreheure) as nbreh from absence where promo=:promo and DATE_FORMAT(dateabs, \'%m\')=:annee and nomgr=:nom and absence.id not in(SELECT id_absence FROM justabsence)', array('promo'=>$_SESSION['promo'], 'annee' => $_POST['mois'], 'nom'=>$_SESSION['groupe']));

                        $prodret=$DB->querys('SELECT count(timeretard) as nbrer from retard where promo=:promo and DATE_FORMAT(dateabs, \'%m\')=:annee and nomgr=:nom and retard.id not in(SELECT id_absence FROM justretard)', array('promo'=>$_SESSION['promo'], 'annee' => $_POST['mois'], 'nom'=>$_SESSION['groupe']));

                    }elseif (isset($_GET['mois'])) {
                        
                        $periode=$panier->moisbul();

                        $prodabs=$DB->querys('SELECT count(nbreheure) as nbreh from absence where promo=:promo and DATE_FORMAT(dateabs, \'%m\')=:annee and nomgr=:nom and absence.id not in(SELECT id_absence FROM justabsence)', array('promo'=>$_SESSION['promo'], 'annee' => $_GET['mois'], 'nom'=>$_SESSION['groupe']));

                        $prodret=$DB->querys('SELECT count(timeretard) as nbrer from retard where promo=:promo  and DATE_FORMAT(dateabs, \'%m\')=:annee and nomgr=:nom and retard.id not in(SELECT id_absence FROM justretard)', array('promo'=>$_SESSION['promo'], 'annee' => $_GET['mois'], 'nom'=>$_SESSION['groupe']));

                    }else{                        
                        $periode='Année: '.($_SESSION['promo']-1).'-'.$_SESSION['promo'];
                        $prodabs=$DB->querys('SELECT count(nbreheure) as nbreh from absence where promo=:promo and nomgr=:nom  and absence.id not in(SELECT id_absence FROM justabsence)', array('promo'=>$_SESSION['promo'], 'nom'=>$_SESSION['groupe']));
                        $prodret=$DB->querys('SELECT count(timeretard) as nbrer from retard where promo=:promo and nomgr=:nom and retard.id not in(SELECT id_absence FROM justretard)', array('promo'=>$_SESSION['promo'], 'nom'=>$_SESSION['groupe']));
                    }?>

                    <div class="d-flex justify-content-between bg-info">

                    <div class="mx-1" ><?=$etab['nom'];?></div>

                    <div class="mx-1" >Période: <?=$periode;?></div>

                    <div class="mx-1" >Classe: <?=strtoupper($_SESSION['groupe']);?></div>

                    <div class="mx-1" >Effectif: <?=$nbrele;?></div>

                    <div class="mx-1" >Année-Scolaire: <?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></div>

                    <div class="mx-1" ><?=$prodabs['nbreh'].' Absence(s)';?> / <?=$prodret['nbrer'].' Rétard(s)';?></div><?php

                    if (!isset($_GET['printnote'])){?>

                        <div class="mx-1"> Synthèse <?php
                            if (isset($_POST['mois'])) {?>

                                <a href="bulletin.php?printnote&mois=<?=$_POST['mois'];?>&mensuel" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php

                            }elseif (isset($_POST['semestre'])) {?>

                                <a href="bulletin.php?printnote&semestre=<?=$_POST['semestre'];?>&trimestre=<?=$_SESSION['prodtype'];?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                            }else{?>

                                <a href="bulletin.php?printnote&annuel&trimestre=<?=$_SESSION['prodtype'];?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                            }?>
                        </div>

                        <div class="mx-1"> Bulletin <?php
                            if ($_SESSION['niveauclasse']=='maternelle') {
                                if (isset($_POST['mois'])) {?>

                                    <a href="releve_notemat.php?mois=<?=$_POST['mois'];?>&mensuel" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php

                                }elseif (isset($_POST['semestre'])) {?>

                                    <a href="releve_notetmat.php?semestre=<?=$_POST['semestre'];?>&trimestre=<?=$_SESSION['prodtype'];?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                }else{?>

                                    <a href="releve_noteamat.php?annuel&trimestre=<?=$_SESSION['prodtype'];?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                }
                            }else{


                                if (isset($_POST['mois'])) {?>

                                    <a href="releve_note.php?mois=<?=$_POST['mois'];?>&mensuel" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php

                                }elseif (isset($_POST['semestre'])) {?>

                                    <a href="releve_notet.php?semestre=<?=$_POST['semestre'];?>&trimestre=<?=$_SESSION['prodtype'];?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                }else{?>

                                    <a href="releve_notea.php?annuel&trimestre=<?=$_SESSION['prodtype'];?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                }

                            }?>
                        </div>

                        <div> Classement <?php
                            if (isset($_POST['mois'])) {?>

                                <a href="admis.php?listad&mois=<?=$_POST['mois'];?>&mensuel" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php

                            }elseif (isset($_POST['semestre'])) {?>

                                <a href="admis.php?listad&semestre=<?=$_POST['semestre'];?>&trimestre=<?=$_SESSION['prodtype'];?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                            }else{?>

                                <a href="admis.php?listad&annuel&trimestre=<?=$_SESSION['prodtype'];?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                            }?>
                        </div>

                        <div> Général 

                            <a href="relevegenerale.php?printnote" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a>
                        </div><?php
                            
                        }?>

                    </div>

                    <div style="display: flex; margin-top: 2px; ">

                    <div class="col">

                        <table class="table table-bordered table-hover align-middle" style="width: 530px;">

                            <thead class="sticky-top text-center ">
                                <tr>
                                    <th style="height: 80px; text-align: right; padding-right: 20px;" colspan="4">Matières</th>
                                </tr>

                                <tr>
                                    <th style="height: 10px; text-align: right; padding-right: 20px;" colspan="4">Coefficients</th>
                                </tr>
                            
                                <tr>
                                    <th>N°</th>
                                    <th style="height: 20px;">Prénom & Nom</th>
                                    <th>Né(e) le</th>
                                    <th>Matricule</th>
                                </tr>
                            </thead>

                            <tbody><?php

                                require 'moyenneecart.php';

                                $variance=0;

                                $moyengenerale=0;

                                foreach ($prodmat as $keye=> $matricule) {

                                    $totm1=0;
                                    $coefm1=0;
                                        
                                    foreach ($prodmatiere as  $matiere) {

                                        require 'requetebul.php';
                                                
                                        foreach ($prodm1 as $moyenne) {
                                            $totm1+=($moyenne->mgen*$moyenne->coef);

                                            $coefm1+=$moyenne->coef;
                                            
                                        }
                                    }

                                    if (!empty($coefm1)) {

                                        $moyenmat=($totm1/$coefm1);
                                        $moyengenerale+=$moyenmat;

                                        $variance+=pow(($moyenmat-$moyenneecart),2);

                                        ;
                                    }?>

                                    <tr>
                                        <td><?=$keye+1;?></td>
                                        <td height="45" style="text-align: left"><?php
                                            if (isset($_POST['mois'])) {?>

                                                <a href="releve_note.php?mois=<?=$_POST['mois'];?>&mensuel&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php

                                            }elseif (isset($_POST['semestre'])) {?>

                                                <a href="releve_notet.php?semestre=<?=$_POST['semestre'];?>&trimestre=<?=$_SESSION['prodtype'];?>&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php
                                            }else{?>

                                                <a href="releve_notea.php?annuel&trimestre=<?=$_SESSION['prodtype'];?>&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;" ><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php
                                            }?>
                                        </td>

                                        <td class="text-center"><?=$matricule->naissance;?></td>

                                        <td style="text-align: left"><?=strtoupper($matricule->matricule);?></td>
                                    </tr><?php
                                }?>
                                <tr>
                                    <th style="padding-bottom: 6.5px; padding-top: 5px; text-align:right;" colspan="4">Moyenne Classe par Matière</th>
                                </tr><?php 
                                require 'moyennegenerale.php';?>

                                    <tr>
                                        <th style="padding-bottom: 6.5px; padding-top: 5px; text-align: right;" colspan="3">Moyenne Générale de la Classe</th><?php 
                                        $_SESSION['moyennegenbul']=$moyenneGenerale;?>

                                        <th><?='  '.number_format($moyenneGenerale,2,',',' ');?></th>
                                    </tr>

                                    <tr>
                                        <th style="padding-bottom: 6.5px; padding-top: 5px; text-align: right;" colspan="3">Ecart-Type</th><?php 
                                        if (empty($nbrele)) {
                                            $nbrele=1;
                                        }?>
                                        <th><?='  '.number_format(sqrt($variance/$nbrele),2,',',' ');?></th>
                                    </tr>

                                    <tr>
                                        <th style="padding-bottom: 6.5px; padding-top: 5px; text-align: right;" colspan="3">Moyenne la plus élevée</th><?php 
                                        
                                        $_SESSION['moyennegenbulgrande']=$mgrande;?>

                                        <th><?='  '.number_format($mgrande,2,',',' ');?></th>
                                    </tr>

                                    <tr>
                                        <th style="padding-bottom: 6.5px; padding-top: 5px; text-align: right;" colspan="3">Moyenne la plus faible</th>

                                        <th><?='  '.number_format($mpetite,2,',',' ');?></th>
                                    </tr>

                                </tbody>
                            </table>

                        </div><?php                      
                        $prodgr=$DB->querys('SELECT codef from  groupe where nomgr=:nom and promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
                        
                        $prodevoir=$DB->query('SELECT nommat, matiere.codem as codem, coef from  matiere inner join enseignement on enseignement.codem=matiere.codem where matiere.codef=:nom and nomgr=:nomgr and promo=:promo order by(cat)', array('nom'=>$prodgr['codef'], 'nomgr'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
                        $nbreMat=sizeof($prodevoir);
                        $totalMoyenneGenerale=0;
                        foreach ($prodevoir as $devoir) {?>

                            <div class="col">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="sticky-top text-center ">
                                            
                                        <tr>
                                            <th height="80" style="font-size: 14px;"><?php if (strlen($devoir->nommat)>=1000) {
                                                echo ucwords($devoir->codem);
                                            }else{
                                                echo ucwords($devoir->nommat);
                                            }?></th>
                                        </tr>
                                            
                                        <tr>
                                            <th height="10"><?=ucwords($devoir->coef);?></th>
                                        </tr>

                                        <tr>
                                            <th height="20">Note</th>
                                        </tr>
                                    </thead>

                                    <tbody><?php
                                        $moyenne=0;
                                        $tabcoef1=array();
                                        $tab_eleve_eval = [];
                                        foreach ($prodmat as  $mat) {//prod viens en haut dans le calcul de la moyenne générale

                                            if (isset($_POST['mois']) or isset($_GET['mois'])) {
                                                                                            
                                                $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and DATE_FORMAT(datedev, \'%m\')=:sem and annee=:promo and devoir.promo=:promo1', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'sem'=>$_SESSION['mois'], 'promo1'=>$_SESSION['promo']));

                                            }elseif (isset($_POST['semestre']) or isset($_GET['semestre'])) {
                                                                                            
                                                $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and trimes=:sem and annee=:promo and devoir.promo=:promo1', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'sem'=>$_SESSION['semestre'], 'promo1'=>$_SESSION['promo']));

                                            }else{

                                                $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and annee=:promo and devoir.promo=:promo1', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'promo1'=>$_SESSION['promo']));
                                            }
                                            foreach ($prodnote as $note) {
                                                ?>

                                                <tr><?php

                                                    if (!empty($note->compo)) {

                                                        $compo=($note->compo/$note->coefc); //Moyenne composition
                                                    }else{
                                                        $compo=0;

                                                    }

                                                    if (!empty($note->note)) {

                                                        $cours=($note->note/$note->coef);//Moyenne note de cours
                                                    }else{
                                                        $cours=0;
                                                    }

                                                    if (!empty($compo) and !empty($cours)) {

                                                        if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {

                                                             $generale=($compo); //Moyenne eleve
                                                        }else{

                                                            $generale=($cours+2*$compo)/3; //Moyenne eleve

                                                        }

                                                    }elseif (!empty($note->compo)) {
                                                        
                                                        $generale=$compo;
                                                    }
                                                    else{
                                                        $generale=($cours); //Moyenne eleve

                                                    }

                                                    if (isset($_POST['mois'])) {
                                                        if (!empty($note->compo)) {
                                                            $generale=($compo); //Moyenne eleve
                                                        }else{
                                                            $generale=($cours); //Moyenne eleve
                                                        }
                                                    }

                                                    

                                                    $moyenne+=$generale;

                                                    if ($generale!=0) {?>

                                                        <td class="text-end" height="45"><?=number_format(($generale),2,',',' ');?></td><?php

                                                    }else{?>

                                                        <td class="text-end" height="45" style="color: white;">neval</td><?php

                                                    }?>

                                                </tr><?php // Recupération du nbre des élèves ayant été evalués
                                                // if (!empty($note->id)) {
                                                //     // $prodmoymat=$DB->querys('SELECT count(matricule) as coef from effectifn where codev=:code and nomgr=:nom and promo=:promo', array('code'=>$note->id, 'nom'=>$_SESSION['groupe'],'promo'=>$_SESSION['promo']));

                                                //     $prodmoymat=$DB->querys('SELECT count(DISTINCT(effectifn.matricule)) as coef from effectifn inner join inscription on inscription.matricule = effectifn.matricule where effectifn.nomgr=:nom and inscription.nomgr=:nomIns and annee=:annee and promo=:promo and etatscol=:etat', array('nom'=>$_SESSION['groupe'], 'nomIns'=>$_SESSION['groupe'], 'annee'=> $_SESSION['promo'], 'promo'=>$_SESSION['promo'], 'etat' => $etat));
                                                //     array_push($tabcoef1, $prodmoymat['coef']);

                                                // }
                                                if (!empty($generale)) {
                                                    $eleve_eval = 1;
                                                }else{
                                                    $eleve_eval = 0;
                                                }
                                                $tab_eleve_eval[]=$eleve_eval;
                                                $eleve_eval = $eleve_eval;

                                            }
                                        }


                                        //$maxcoef1=max($tabcoef1);
                                        // var_dump($prodmoymat['coef']);
                                        $nbre_elev_eval = array_sum($tab_eleve_eval);
                                        if ($nbre_elev_eval!=0) {

                                            $totalMoyenneGenerale+=$moyenne/($nbre_elev_eval);?>
                                            
                                            <tr>
                                                <th id="moyenneg" style="padding-bottom: 6.5px; padding-top: 5px; padding-right: 10px; text-align: right;"><?='  '.number_format($moyenne/($nbre_elev_eval),2,',',' ');?></th>
                                            </tr><?php

                                            
                                        }?> 
                                        

                                    </tbody>

                                </table> 
                                
                            </div><?php
                            
                        }?>

                        <div class="col">

                                <table class="table table-bordered table-hover align-middle">
                                                     
                                    <thead class="sticky-top text-center ">
                                        <tr>
                                            <th style="height: 80px;"></th>
                                        </tr>

                                        <tr>
                                            <th style="height: 10px;">M</th>
                                        </tr>
                                    
                                        <tr>
                                            <th style="height: 20px;">Moyenne</th>
                                        </tr>
                                    </thead>

                                    <tbody><?php
                                        if (isset($_POST['mois']) or isset($_GET['mois']) or isset($_POST['semestre']) or isset($_GET['semestre'])) {
                                            foreach ($prodmat as $matricule) {
                                                $totm1=0;
                                                $coefm1=0;
                                                
                                                foreach ($prodmatiere as $matiere) {

                                                    require 'requetebul.php';
                                                            
                                                    foreach ($prodm1 as $moyenne) {
                                                        $totm1+=($moyenne->mgen*$moyenne->coef);

                                                        $coefm1+=$moyenne->coef;
                                                        
                                                    }
                                                }?>

                                                <tr><?php

                                                    if (!empty($coefm1)) {

                                                        $moyenmat=($totm1/$coefm1);
                                                        $moyengenerale+=$moyenmat;
                                                        ?>
                                                        
                                                        <td height="45" class="text-end" ><?=number_format($totm1/$coefm1,2,',',' ');?></td><?php


                                                    }else{?>

                                                        <td height="45" class="text-end" style="color:white;">neval</td><?php

                                                    }?>
                                                </tr><?php
                                            }
                                        }else{

                                            foreach ($prodmat as $keye=> $matricule) {

                                                $prodmoyA=$DB->querys("SELECT ROUND(AVG(moyenne),2) as moyenne from relevegeneralebul  where moyenne!=0 and matricule='{$matricule->matricule}' and pseudo='{$_SESSION['pseudo']}' and promo='{$_SESSION['promo']}' ");
                                                
                                                ?>
                                                <tr>
                                                    <td class="text-end" height="45"><?=$prodmoyA['moyenne'];?></td>
                                                </tr><?php
                                            }
                                            $DB->delete("DELETE FROM relevegeneralebul WHERE pseudo='{$_SESSION['pseudo']}' and promo='{$_SESSION['promo']}'");
                                            
                                        }?>
                                        <tr><?php 

                                            if ($moyengenerale!=0) {?>
                                                <th id="moyenneg" style="padding-bottom: 6.5px; padding-top: 5px; padding-right: 10px; text-align: right;"><?='  '.number_format($moyenneGenerale,2,',',' ');?></th><?php

                                            }else{?>
                                                <th id="moyenneg" style="padding-bottom: 6.5px; padding-top: 5px; padding-right: 10px; text-align: right;">0.00</th><?php
                                            }?>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                            <div class="col">

                                <table class="table table-bordered table-hover align-middle">
                             
                                    <thead class="sticky-top text-center ">
                                        <tr>
                                            <th style="height: 80px;"></th>
                                        </tr>

                                        <tr>
                                            <th style="height: 10px;">Rang</th>
                                        </tr>

                                        <tr>
                                            <th style="height: 20px;">Rang</th>
                                        </tr>
                                    </thead>
                                    <tbody><?php

                                        require 'rangbul.php';?>                                    

                                    </tbody>
                                </table>
                            </div><?php //fin de calcul de la moyenne ?>
                        </div><?php
                }
                
            }
        }?>
    </div><?php 

    $DB->delete("DELETE FROM relevegeneralebul WHERE pseudo='{$_SESSION['pseudo']}' and promo='{$_SESSION['promo']}'");

                            
    if (isset($_GET['printnote'])){

        if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {

            $pers1=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=? or type=?', array('Directeur du primaire', 'Directrice du Préscolaire/Primaire'));

            $prodens=$DB->querys('SELECT codens from enseignement where nomgr=? and promo=? and codens!=? and codens!=? and codens!=?', array($_SESSION['groupe'], $_SESSION['promo'], 'cspe92', 'cspe132', 'cspe128'));

            $enseignantsig=$prodens['codens'];
            $_SESSION['enseignantsig']=$enseignantsig;

            $pers2=$DB->querys('SELECT nomen as nom, prenomen as prenom, type from enseignant inner join login on enseignant.matricule=login.matricule where login.matricule=:type', array('type'=>$_SESSION['enseignantsig']));?>

            <div>

                <div  style="margin-top: 10px; color: grey; display: flex;">

                  <div style="margin-left: 160px; width: 300px; text-align: center;">Le maître</div>
                  
                  <div style="margin-left: 230px; width: 300px; text-align:center;">Le Directeur</div>

                </div>

                <div  style="margin-top: 60px; color: grey; display: flex;">

                  <div style="margin-left: 160px; width: 300px; text-align:center; "><?=strtoupper($pers2['nom']).' '.ucwords($pers2['prenom']);?></div>
                  
                  <div style="margin-left: 230px; width: 300px; text-align:center;"><?=strtoupper($pers1['nom']).' '.ucwords($pers1['prenom']);?></div>
                </div>
            </div><?php
        }
    }?>

</div>