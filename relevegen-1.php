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
    
    if ($products['niveau']<3) {?>

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

                if ((isset($_GET['listad']) or isset($_GET['printnote']) or isset($_POST['semestre']) or isset($_POST['mois'])) and $_SESSION['semestre']!='choisissez le semestre') {


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

                    $prodmat=$DB->query('SELECT  inscription.matricule as matricule, codef, nomel, prenomel, DATE_FORMAT(naissance, \'%d/%m/%Y\')AS naissance from inscription inner join eleve on inscription.matricule=eleve.matricule where nomgr=:nom and annee=:promo order by (prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

                    $prodmatiere=$DB->query('SELECT nommat, codem, coef from  matiere where codef=:nom order by(cat)', array('nom'=>$prodgr['codef']));

                    
                    $moyengenerale=0;
                    $moyengen=0;?>

                    <div style="display: flex; margin-top: 2px; margin-top: -20px;">

                        <div class="col">

                            <table class="tablistebul" style="width: 400px;">
                         
                                <thead>
                                
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
                                                if (isset($_POST['mois'])) {?>

                                                    <a href="releve_note.php?mois=<?=$_POST['mois'];?>&mensuel&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php

                                                }elseif (isset($_POST['semestre'])) {?>

                                                    <a href="releve_notet.php?semestre=<?=$_POST['semestre'];?>&trimestre=<?=$typerepart;?>&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php
                                                }else{?>

                                                    <a href="releve_notea.php?annuel&trimestre=<?=$typerepart;?>&indi=<?=$matricule->matricule;?>" target="_blank" style="text-decoration: none;" ><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></a><?php
                                                }?>
                                            </td>

                                            <td style="text-align: center;"><?=$matricule->naissance;?></td>

                                            <td style="text-align: left"><?=strtoupper($matricule->matricule);?></td>
                                        </tr><?php
                                    }?>
                                    

                                </tbody>
                            </table>

                        </div>



                        <div class="col">

                            <table class="tablistebul">
                                                 
                                <thead>
                                
                                    <tr>
                                        <th style="height: 20px;">1er Trimestre</th>
                                    </tr>
                                </thead>

                                <tbody><?php
                                    $moyengenerale=0;

                                    foreach ($prodmat as $matricule) {
                                        $totm1t=0;
                                        $coefm1t=0;
                                        
                                        foreach ($prodmatiere as $matiere) {

                                            if ($_SESSION['niveauclasse']!='primaire') {

                                                

                                                $prodm1t=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>1, 'promo'=>$_SESSION['promo']));

                                                
                                            }else{

                                                

                                                $prodm1t=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>1, 'promo'=>$_SESSION['promo']));

                                                
                                            }
                                                    
                                            foreach ($prodm1t as $moyenne) {
                                                $totm1t+=($moyenne->mgen*$moyenne->coef);

                                                $coefm1t+=$moyenne->coef;
                                                
                                            }
                                        }?>

                                        <tr><?php

                                            if (!empty($coefm1t)) {

                                                $moyenmat=($totm1t/$coefm1t);
                                                $moyengenerale+=$moyenmat;

                                                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 1, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));?>
                                                
                                                <td height="45"><?=number_format($totm1t/$coefm1t,2,',',' ');?></td><?php

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
                                        <th style="height: 20px;">2ème Trimestre</th>
                                    </tr>
                                </thead>

                                <tbody><?php
                                    $moyengenerale=0;

                                    foreach ($prodmat as $matricule) {
                                        $totm2t=0;
                                        $coefm2t=0;
                                        
                                        foreach ($prodmatiere as $matiere) {

                                            if ($_SESSION['niveauclasse']!='primaire') {

                                                

                                                $prodm2t=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>2, 'promo'=>$_SESSION['promo']));

                                                
                                            }else{

                                                

                                                $prodm2t=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>2, 'promo'=>$_SESSION['promo']));

                                                
                                            }
                                                    
                                            foreach ($prodm2t as $moyenne) {
                                                $totm2t+=($moyenne->mgen*$moyenne->coef);

                                                $coefm2t+=$moyenne->coef;
                                                
                                            }
                                        }?>

                                        <tr><?php

                                            if (!empty($coefm2t)) {

                                                $moyenmat=($totm2t/$coefm2t);
                                                $moyengenerale+=$moyenmat;

                                                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 2, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));?>
                                                
                                                <td height="45"><?=number_format($totm2t/$coefm2t,2,',',' ');?></td><?php

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
                                        <th style="height: 20px;">3ème Trimestre</th>
                                    </tr>
                                </thead>

                                <tbody><?php
                                    $moyengenerale=0;

                                    foreach ($prodmat as $matricule) {
                                        $totm2t=0;
                                        $coefm2t=0;
                                        
                                        foreach ($prodmatiere as $matiere) {

                                            if ($_SESSION['niveauclasse']!='primaire') {

                                                

                                                $prodm2t=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>3, 'promo'=>$_SESSION['promo']));

                                                
                                            }else{

                                                

                                                $prodm2t=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>3, 'promo'=>$_SESSION['promo']));

                                                
                                            }
                                                    
                                            foreach ($prodm2t as $moyenne) {
                                                $totm2t+=($moyenne->mgen*$moyenne->coef);

                                                $coefm2t+=$moyenne->coef;
                                                
                                            }
                                        }?>

                                        <tr><?php

                                            if (!empty($coefm2t)) {

                                                $moyenmat=($totm2t/$coefm2t);
                                                $moyengenerale+=$moyenmat;

                                                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 3, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));?>
                                                
                                                <td height="45"><?=number_format($totm2t/$coefm2t,2,',',' ');?></td><?php

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
                                        <th style="height: 20px;">Annuel</th>
                                    </tr>
                                </thead>

                                <tbody><?php
                                    $moyengenerale=0;

                                    foreach ($prodmat as $matricule) {
                                        $totm2t=0;
                                        $coefm2t=0;
                                        
                                        foreach ($prodmatiere as $matiere) {

                                            if ($_SESSION['niveauclasse']!='primaire') {

                                                

                                                $prodm2t=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'promo'=>$_SESSION['promo']));

                                                
                                            }else{

                                                

                                                $prodm2t=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'promo'=>$_SESSION['promo']));

                                                
                                            }
                                                    
                                            foreach ($prodm2t as $moyenne) {
                                                $totm2t+=($moyenne->mgen*$moyenne->coef);

                                                $coefm2t+=$moyenne->coef;
                                                
                                            }
                                        }?>

                                        <tr><?php

                                            if (!empty($coefm2t)) {

                                                $moyenmat=($totm2t/$coefm2t);
                                                $moyengenerale+=$moyenmat;?>
                                                
                                                <td height="45"><?=number_format($totm2t/$coefm2t,2,',',' ');?></td><?php

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
                        </div><?php
                    }
                
                }
            }?>
        </div><?php 

                            
    if (isset($_GET['printnote'])){

        if ($_SESSION['niveauclasse']=='primaire') {

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