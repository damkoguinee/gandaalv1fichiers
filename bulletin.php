<?php

require 'header.php';

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

        <div class="container"><?php

            if (!isset($_GET['printnote'])){
                //require 'navnote.php';
            }?>            

            <div><?php
                if (!isset($_GET['printnote'])){

                    require 'formbulletin.php';
                }

                if ((isset($_POST['groupe']) or isset($_GET['printnote']) or isset($_POST['semestre']) or isset($_POST['mois'])) and $_SESSION['semestre']!='choisissez le semestre') {


                    $prodcount=$DB->querys('SELECT count(matricule) as countel, codef, niveau from inscription where nomgr=:nom and annee=:promo order by (matricule)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

                    $niveauclasse=$prodcount['niveau'];
                    $_SESSION['niveauclasse']=$niveauclasse;

                    $prodmoyeg=$DB->querys('SELECT count(DISTINCT(matricule)) as coef from effectifn where nomgr=:nom and promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
                
                    if ($prodmoyeg['coef']!=0) {
                        $nbrele=$prodmoyeg['coef'];// nbre élève
                    }else{
                        $nbrele=1;
                    }

                    $prodgr=$DB->querys('SELECT codef from inscription where nomgr=:nom and annee=:promo order by (matricule)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

                    //$nbrele=$prodcount['countel'];//Pour avoir le nombre d'élève

                    $prodmat=$DB->query('SELECT  inscription.matricule as matricule, nomel, prenomel, DATE_FORMAT(naissance, \'%d/%m/%Y\')AS naissance from inscription inner join eleve on inscription.matricule=eleve.matricule where nomgr=:nom and annee=:promo order by (prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

                    $prodmatiere=$DB->query('SELECT nommat, codem, coef from  matiere where codef=:nom order by(cat)', array('nom'=>$prodgr['codef']));

                    
                    $moyengenerale=0;
                    $moyengen=0;

                    if (isset($_POST['semestre'])) {

                        if ($_POST['semestre']==1) {

                            $periode=$_POST['semestre'].'er '.$typerepart;
                        }else{
                            $periode=$_POST['semestre'].'ème '.$typerepart;
                        }

                        $prodabs=$DB->querys('SELECT count(nbreheure) as nbreh from absence where promo=:promo and semestre=:annee and nomgr=:nom and absence.id not in(SELECT id_absence FROM justabsence)', array('promo'=>$_SESSION['promo'], 'annee' => $_POST['semestre'], 'nom'=>$_SESSION['groupe']));

                        $prodret=$DB->querys('SELECT count(timeretard) as nbrer from retard where promo=:promo and semestre=:annee and nomgr=:nom and retard.id not in(SELECT id_absence FROM justretard)', array('promo'=>$_SESSION['promo'], 'annee' => $_POST['semestre'], 'nom'=>$_SESSION['groupe']));


                    }elseif (isset($_GET['semestre'])) {
                        if ($_GET['semestre']==1) {

                            $periode=$_GET['semestre'].'er '.$typerepart;
                        }else{
                            $periode=$_GET['semestre'].'ème '.$typerepart;
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

                    <div class="entete" style="font-size: 18px; display: flex;">

                        <div style="margin-right: 20px;"><?=$etab['nom'];?></div>

                        <div style="margin-right: 20px;">Période: <?=$periode;?></div>

                        <div style="margin-right: 20px;">Classe: <?=strtoupper($_SESSION['groupe']);?></div>

                        <div style="margin-right: 20px;">Effectif: <?=$nbrele;?></div>

                        <div style="margin-right: 20px;">Année-Scolaire: <?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></div>

                        <div style="margin-right: 20px;"><?=$prodabs['nbreh'].' Absence(s)';?> / <?=$prodret['nbrer'].' Rétard(s)';?></div><?php

                        if (!isset($_GET['printnote'])){?>

                            <div style="margin-right: 20px;"> Synthèse <?php
                                if (isset($_POST['mois'])) {?>

                                    <a href="bulletin.php?printnote&mois=<?=$_POST['mois'];?>&mensuel" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php

                                }elseif (isset($_POST['semestre'])) {?>

                                    <a href="bulletin.php?printnote&semestre=<?=$_POST['semestre'];?>&trimestre=<?=$typerepart;?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                }else{?>

                                    <a href="bulletin.php?printnote&annuel&trimestre=<?=$typerepart;?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                }?>
                            </div>

                            <div style="margin-right: 20px;"> Bulletin <?php
                                if ($_SESSION['niveauclasse']=='maternelle') {
                                    if (isset($_POST['mois'])) {?>

                                        <a href="releve_notemat.php?mois=<?=$_POST['mois'];?>&mensuel" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php

                                    }elseif (isset($_POST['semestre'])) {?>

                                        <a href="releve_notetmat.php?semestre=<?=$_POST['semestre'];?>&trimestre=<?=$typerepart;?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                    }else{?>

                                        <a href="releve_noteamat.php?annuel&trimestre=<?=$typerepart;?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                    }
                                }else{


                                    if (isset($_POST['mois'])) {?>

                                        <a href="releve_note.php?mois=<?=$_POST['mois'];?>&mensuel" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php

                                    }elseif (isset($_POST['semestre'])) {?>

                                        <a href="releve_notet.php?semestre=<?=$_POST['semestre'];?>&trimestre=<?=$typerepart;?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                    }else{?>

                                        <a href="releve_notea.php?annuel&trimestre=<?=$typerepart;?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                    }

                                }?>
                            </div>

                            <div> Classement <?php
                                if (isset($_POST['mois'])) {?>

                                    <a href="admis.php?listad&mois=<?=$_POST['mois'];?>&mensuel" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php

                                }elseif (isset($_POST['semestre'])) {?>

                                    <a href="admis.php?listad&semestre=<?=$_POST['semestre'];?>&trimestre=<?=$typerepart;?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                }else{?>

                                    <a href="admis.php?listad&annuel&trimestre=<?=$typerepart;?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a><?php
                                }?>
                            </div>

                            <div> Général 

                                <a href="relevegenerale.php?printnote" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a>
                            </div><?php
                            
                        }?>

                    </div>

                    <div style="display: flex; margin-top: 2px; margin-top: -20px;">

                        <div class="col">

                            <table class="tablistebul" style="width: 400px;">
                         
                                <thead>
                                    <tr>
                                        <th style="height: 80px; text-align: right; padding-right: 20px;" colspan="3">Matières</th>
                                    </tr>

                                    <tr>
                                        <th style="height: 10px; text-align: right; padding-right: 20px;" colspan="3">Coefficients</th>
                                    </tr>
                                
                                    <tr>
                                        <th style="height: 20px;">Prénom & Nom</th>
                                        <th>Né(e) le</th>
                                        <th>Matricule</th>
                                    </tr>
                                </thead>

                                <tbody><?php

                                    require 'moyenneecart.php';

                                    $variance=0;

                                    $moyengenerale=0;

                                    foreach ($prodmat as $matricule) {

                                        $totm1=0;
                                        $coefm1=0;
                                            
                                        foreach ($prodmatiere as $matiere) {

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
                                            <td height="45" style="text-align: left"><?php

                                                if ($_SESSION['niveauclasse']=='maternelle') {
                                                    if (isset($_POST['mois'])) {?>

                                                        <a href="releve_notemat.php?mois=<?=$_POST['mois'];?>&mensuel&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php

                                                    }elseif (isset($_POST['semestre'])) {?>

                                                        <a href="releve_notetmat.php?semestre=<?=$_POST['semestre'];?>&trimestre=<?=$typerepart;?>&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php
                                                    }else{?>

                                                        <a href="releve_noteamat.php?annuel&trimestre=<?=$typerepart;?>&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;" ><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php
                                                    }
                                                }else{

                                                    if (isset($_POST['mois'])) {?>

                                                        <a href="releve_note.php?mois=<?=$_POST['mois'];?>&mensuel&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php

                                                    }elseif (isset($_POST['semestre'])) {?>

                                                        <a href="releve_notet.php?semestre=<?=$_POST['semestre'];?>&trimestre=<?=$typerepart;?>&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php
                                                    }else{?>

                                                        <a href="releve_notea.php?annuel&trimestre=<?=$typerepart;?>&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;" ><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php
                                                    }

                                                }?>
                                            </td>

                                            <td style="text-align: center;"><?=$matricule->naissance;?></td>

                                            <td style="text-align: left"><?=strtoupper($matricule->matricule);?></td>
                                        </tr><?php
                                    }?>
                                    <tr>
                                        <th style="padding-bottom: 6.5px; padding-top: 5px; text-align:right;" colspan="3">Moyenne Classe par Matière</th>
                                    </tr>

                                    <tr>
                                        <th style="padding-bottom: 6.5px; padding-top: 5px; text-align: right;" colspan="2">Moyenne Générale de la Classe</th><?php 
                                        $_SESSION['moyennegenbul']=$moyengenerale/$nbrele;?>

                                        <th><?='  '.number_format($moyengenerale/$nbrele,2,',',' ');?></th>
                                    </tr>

                                    <tr>
                                        <th style="padding-bottom: 6.5px; padding-top: 5px; text-align: right;" colspan="2">Ecart-Type</th>

                                        <th><?='  '.number_format(sqrt($variance/$nbrele),2,',',' ');?></th>
                                    </tr>

                                    <tr>
                                        <th style="padding-bottom: 6.5px; padding-top: 5px; text-align: right;" colspan="2">Moyenne la plus élevée</th><?php 
                                        $_SESSION['moyennegenbulgrande']=$mgrande;?>

                                        <th><?='  '.number_format($mgrande,2,',',' ');?></th>
                                    </tr>

                                    <tr>
                                        <th style="padding-bottom: 6.5px; padding-top: 5px; text-align: right;" colspan="2">Moyenne la plus faible</th>

                                        <th><?='  '.number_format($mpetite,2,',',' ');?></th>
                                    </tr>

                                </tbody>
                            </table>

                        </div><?php                      


                        $prodgr=$DB->querys('SELECT codef from  groupe where nomgr=:nom and promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

                        $prodevoir=$DB->query('SELECT nommat, matiere.codem as codem, coef from  matiere inner join enseignement on enseignement.codem=matiere.codem where matiere.codef=:nom and nomgr=:nomgr and promo=:promo order by(cat)', array('nom'=>$prodgr['codef'], 'nomgr'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

                        foreach ($prodevoir as $devoir) {?>

                            <div class="col">

                                <table class="tablistebul" style="width: 20px;">
                                    <thead>
                                            
                                        <tr>
                                            <th height="80" ><?php if (strlen($devoir->nommat)>=1000) {
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

                                        foreach ($prodmat as $mat) {//prod viens en haut dans le calcul de la moyenne générale

                                            if (isset($_POST['mois']) or isset($_GET['mois'])) {
                                                                                            
                                                $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and DATE_FORMAT(datedev, \'%m\')=:sem and annee=:promo and devoir.promo=:promo1', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'sem'=>$_SESSION['mois'], 'promo1'=>$_SESSION['promo']));

                                            }elseif (isset($_POST['semestre']) or isset($_GET['semestre'])) {
                                                                                            
                                                $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and trimes=:sem and annee=:promo and devoir.promo=:promo1', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'sem'=>$_SESSION['semestre'], 'promo1'=>$_SESSION['promo']));

                                            }else{

                                                $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and annee=:promo and devoir.promo=:promo1', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'promo1'=>$_SESSION['promo']));
                                            }

                                            foreach ($prodnote as $note) {?>


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

                                                        <td height="45"><?=number_format(($generale),2,',',' ');?></td><?php

                                                    }else{?>

                                                        <td height="45" style="color: white;">neval</td><?php

                                                    }?>

                                                </tr><?php // Recupération du nbre des élèves ayant été evalués

                                                $prodmoymat=$DB->querys('SELECT count(matricule) as coef from effectifn where codev=:code and nomgr=:nom and promo=:promo', array('code'=>$note->id, 'nom'=>$_SESSION['groupe'],'promo'=>$_SESSION['promo']));

                                            }
                                        }

                                        if ($prodmoymat['coef']!=0) {?>
                                            
                                            <tr>
                                                <th id="moyenneg" style="padding-bottom: 6.5px; padding-top: 5px; padding-right: 10px; text-align: right;"><?='  '.number_format($moyenne/($prodmoymat['coef']),2,',',' ');?></th>
                                            </tr><?php
                                        }?> 
                                        

                                    </tbody>

                                </table> 
                                
                            </div><?php
                            
                        }?>

                        <div class="col">

                                <table class="tablistebul">
                                                     
                                    <thead>
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
                                        $moyengenerale=0;

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
                                                    $moyengenerale+=$moyenmat;?>
                                                    
                                                    <td height="45"><?=number_format($totm1/$coefm1,2,',',' ');?></td><?php

                                                }else{?>

                                                    <td height="45" style="color:white;">neval</td><?php

                                                }?>
                                            </tr><?php
                                        }?>
                                        <tr><?php

                                            if ($moyengenerale!=0) {?>
                                                <th id="moyenneg" style="padding-bottom: 6.5px; padding-top: 5px; padding-right: 10px; text-align: right;"><?='  '.number_format($moyengenerale/$nbrele,2,',',' ');?></th><?php

                                            }else{?>
                                                <th id="moyenneg" style="padding-bottom: 6.5px; padding-top: 5px; padding-right: 10px; text-align: right;">0.00</th><?php
                                            }?>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                            <div class="col">

                                <table class="tablistebul">
                             
                                    <thead>
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

                            
    if (isset($_GET['printnote'])){

        if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {

            if ($_SESSION['niveauclasse']=='primaire') {

                $pers1=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=? or type=?', array('Directeur du primaire', 'Directrice du Préscolaire/Primaire'));
            }else{

                $pers1=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=? or type=? or type=?', array('Directeur du primaire', 'Directrice du Préscolaire/Primaire', 'coordinatrice maternelle'));

            }

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