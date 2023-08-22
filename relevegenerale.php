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
  background-color: white;
  width: 100%;
  color: #717375;
  font-family: helvetica;
  border-collapse: collapse; 
  margin: 30px;     
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

            $prodm2t=$DB->querys('SELECT sum(moyenne)/3 as moyenne from relevegenerale  where matricule=:matr and codef=:codef and pseudo=:pseudo and promo=:promo ', array('matr'=>$matricule->matricule, 'codef'=>$matricule->codef, 'pseudo'=>$_SESSION['pseudo'], 'promo'=>$_SESSION['promo']));

            $moyenmat=$prodm2t['moyenne'];

            $DB->insert('INSERT INTO relevegenerale(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 4, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));
        }
    }
            

}

//require 'entetebul.php';
$fiche=$DB->querys('SELECT niveau as nomf from groupe where nomgr=:mat', array('mat'=>$_SESSION['groupe']));

if (!isset($_GET['printnote'])){

    //require 'navnote.php';
}?>

 <div class="entete" style="font-size: 18px; display: flex; margin-left: 30px;">
    <div style="margin-right: 20px;"><?=$panier->etablissement();?></div>
    <div>Relevé Général des élèves de la <?= $_SESSION['groupe']. '.  Année scolaire '. ($_SESSION['promo']-1).' - '.$_SESSION['promo'];?></div>
</div>

<div style="display: flex;"><?php 

    $i=1;

    $moyenneannuel=0;

    $moyennetrimestre1=0;
    $moyennetrimestre2=0;
    $moyennetrimestre3=0;

    while ($i<=4) {?>

        <div>

            <table class="tablistebul" style="margin-left: 10px; margin-top: 0px;">

              <thead>
                <tr><?php 
                    if ($i==1) {?>
                        <th style="height: 5px; padding-top: 5px; text-align: center;">N°</th>
                        <th style="height: 5px; padding-top: 5px;">Prénom & Nom</th>
                        <th style="height: 5px; padding-top: 5px;">Matricule</th>
                        <th style="height: 5px; padding-top: 5px;">1er Trimestre</th><?php 
                    }elseif ($i>1 and $i <=3) {?>
                        <th style="height: 5px; padding-top: 5px; width: 70px;"><?=$i;?> ème Trimestre</th><?php
                    }elseif ($i==4) {?>
                        <th style="height: 5px; padding-top: 5px;">Annuel</th><?php
                    }?>
                </tr>
              </thead>


              <tbody><?php

                $codef=$prodgr['codef'];
                $trimestre=$i; 

                $prodmoy=$DB->query("SELECT * from relevegenerale inner join eleve on eleve.matricule=relevegenerale.matricule where codef='{$codef}' and trimestre='{$trimestre}' and pseudo='{$_SESSION['pseudo']}' and promo='{$_SESSION['promo']}' order by(prenomel)");

                $j=sizeof($prodmoy);

                foreach ($prodmoy as $key => $value) {

                    if ($i==1) {
                        $moyennetrimestre1+=$value->moyenne;
                    }elseif ($i==2) {
                        $moyennetrimestre2+=$value->moyenne;
                    }elseif ($i==3) {
                        $moyennetrimestre3+=$value->moyenne;
                    }elseif ($i==4) {
                        $moyenneannuel+=$value->moyenne;
                    }

                    ?>

                    <tr><?php 
                        if ($i==1) {?>

                            <td style="text-align:center;"><?=$key+1;?></td>
                            <td style="text-align:left;"><?=$panier->nomEleve($value->matricule);?></td>
                            <td style="text-align:left;"><?=strtolower($value->matricule);?></td><?php 
                        }?>
                        <td><?=number_format($value->moyenne,2,',',' ');?></td>
                    </tr><?php
                }?>
                  
              </tbody>
              <thead>
                <tr><?php 
                    if ($i==1) {?>
                        <th colspan="3" style="text-align: center;">Moyenne Générale de la Classe</th>
                        <th style="text-align: right; padding-right: 10px;"><?=number_format(($moyennetrimestre1/($j)),2,',',' ');?></th><?php 
                    }elseif ($i==2) {?>
                        <th style="text-align: right; padding-right: 10px;"><?=number_format($moyennetrimestre2/($j),2,',',' ');?></th><?php
                    }elseif ($i==3) {?>
                        <th style="text-align: right; padding-right: 10px;"><?=number_format($moyennetrimestre3/($j),2,',',' ');?></th><?php
                    }elseif ($i==4) {?>
                        <th style="text-align: right; padding-right: 10px;"><?=number_format($moyenneannuel/($j),2,',',' ');?></th><?php
                    }?>
                </tr>
              </thead>
            </table>
        </div><?php 

        $i++;
    }?>

    
</div><?php

$DB->delete("DELETE FROM relevegenerale WHERE pseudo='{$_SESSION['pseudo']}' and promo='{$_SESSION['promo']}'");


