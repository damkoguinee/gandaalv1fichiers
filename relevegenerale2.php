<?php
require_once "lib/html2pdf.php";

ob_start(); ?>

<?php require 'header.php';?>

<style type="text/css">

body{
  margin: 0px;
  width: 100%;
  height:100%;
  padding:0px;
}
  .entete{
    width: 100%;

  }

  .pied{
    text-align: center;
    margin-top: 40px;
    margin-right: 80px;
    font-size: 20px;
    font-style: italic;
  }

  .symbole{
    margin: 30px;
    margin-top: 500px;
    margin-left: 0px;
    margin-right: 100px;

  }

  .etat{
    margin-top: 20px;
    margin-left: 10px;
    font-weight: bold;
    font-size: 12px;
  }

  table.tablistebul{
    width: 100%;
    border-collapse: collapse;
  }

  .tablistebul th {
    line-height: 3mm;
    border: 1px solid black;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    margin-top: 30px;
    color: grey;
  }
  .tablistebul td {
    height: <?=$height;?>;
    padding-top: <?=$padding;?>;
    border: 1px solid black;
    text-align: right;
    padding-left: 10px; 
    font-size: 12px;
    color: grey;
  }

  label {
    float: right;
    font-size: 12px;
    font-weight: bold;
    width: 200px;
  }

  ol{
    list-style: none;
  }
</style><?php

$etab=$DB->querys('SELECT *from etablissement');

if (isset($_SESSION['pseudo'])) {
    
    

    if ((isset($_GET['listad']) or isset($_GET['printnote']) or isset($_POST['semestre']) or isset($_POST['mois'])) and $_SESSION['semestre']!='choisissez le semestre') {

        $prodgr=$DB->querys('SELECT codef from inscription where nomgr=:nom and annee=:promo order by (matricule)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

        $prodmat=$DB->query('SELECT  inscription.matricule as matricule, codef, nomel, prenomel, DATE_FORMAT(naissance, \'%d/%m/%Y\')AS naissance from inscription inner join eleve on inscription.matricule=eleve.matricule where nomgr=:nom and annee=:promo order by (prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

        $prodmatiere=$DB->query('SELECT nommat, codem, coef from  matiere where codef=:nom order by(cat)', array('nom'=>$prodgr['codef']));

                
                
        $moyengen=0;
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
            }

            if (!empty($coefm1t)) {

                $moyenmat=($totm1t/$coefm1t);
                $moyengenerale+=$moyenmat;

                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 1, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

            }else{

                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, 0, 1, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

            }
        }

        //**********************************2eme trimestre************************************************

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
            }

            if (!empty($coefm2t)) {

                $moyenmat=($totm2t/$coefm2t);
                $moyengenerale+=$moyenmat;

                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 2, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

            }else{

                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, 0, 2, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

            }
        }

        //***************************************************3ème trimestre**********************************
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
            }

            if (!empty($coefm2t)) {

                $moyenmat=($totm2t/$coefm2t);
                $moyengenerale+=$moyenmat;

                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 3, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

            }else{

                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, 0, 3, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

            }
        }


        //**********************************Annuel************************************************

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
            }

            if (!empty($coefm2t)) {

                $moyenmat=($totm2t/$coefm2t);
                $moyengenerale+=$moyenmat;

                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 4, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

            }else{

                $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, 0, 4, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

            }
        }
    }
            

}

//require 'entetebul.php';
$fiche=$DB->querys('SELECT niveau as nomf from groupe where nomgr=:mat', array('mat'=>$_SESSION['groupe']));?>

<div>Relevé général des élèves de la <?= $_SESSION['groupe']. ' Année scolaire '. ($_SESSION['promo']-1).' - '.$_SESSION['promo'];?></div>

<div style="display: flex;"><?php 

    $i=1;

    $totannuel=0;

    while ($i<=4) {?>

        <div>

            <table class="tablistebul">

              <thead>
                <tr><?php 
                    if ($i==1) {?>
                        <th style="height: 5px; padding-top: 5px; text-align: center;">N°</th>
                        <th style="height: 5px; padding-top: 5px;">Prénom & Nom</th>
                        <th style="height: 5px; padding-top: 5px;">1er Trimestre</th><?php 
                    }elseif ($i>1 and $i <=3) {?>
                        <th style="height: 5px; padding-top: 5px;"><?=$i;?> ème Trimestre</th><?php
                    }elseif ($i==4) {?>
                        <th style="height: 5px; padding-top: 5px;">Annuel</th><?php
                    }?>
                </tr>
              </thead>


              <tbody><?php

                $codef=$prodgr['codef'];
                $trimestre=$i; 

                $prodmoy=$DB->query("SELECT * from relevegenerale where codef='{$codef}' and trimestre='{$trimestre}' and pseudo='{$_SESSION['pseudo']}' and promo='{$_SESSION['promo']}' order by(matricule)");

                foreach ($prodmoy as $key => $value) {

                    $totannuel+=$value->moyenne;?>

                    <tr><?php 
                        if ($i==1) {?>

                            <td style="text-align:center;"><?=$key+1;?></td>
                            <td style="text-align:left;"><?=$panier->nomEleve($value->matricule);?></td><?php 
                        }?>
                        <td><?=number_format($value->moyenne,2,',',' ');?></td>
                    </tr><?php
                }?>
                  
              </tbody>
            </table>
        </div><?php 

        $i++;
    }?>

    
</div><?php


//require 'piedbuladmis.php';

$DB->delete("DELETE FROM relevegenerale WHERE pseudo='{$_SESSION['pseudo']}' and promo='{$_SESSION['promo']}'");


