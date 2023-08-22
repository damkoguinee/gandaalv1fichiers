<?php
require_once "lib/html2pdf.php";

ob_start(); ?>

<?php require '_header.php';

$prodmat=$DB->query('SELECT  codef from inscription  where nomgr=:nom and annee=:promo ', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

$nbremat=sizeof($prodmat);

if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {
  if ($nbremat<=15) {
    $height='0px';
    $padding='5px';
  }else{
    $height='0px';
    $padding='3px';
  }  

}else{

  if ($nbremat<=15) {
    $height='0px';
    $padding='5px';
  }else{
    $height='0px';
    $padding='3px';
  }

  
}?>

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
    margin-left: 100px;
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

if (isset($_GET['listad'])) {

  $prodmoyeg=$DB->querys('SELECT count(DISTINCT(matricule)) as coef from effectifn where nomgr=:nom and promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));


            
  if ($prodmoyeg['coef']!=0) {
      $nbrele=$prodmoyeg['coef'];// nbre élève
  }else{
      $nbrele=1;
  }

  $prodmat=$DB->query('SELECT  inscription.matricule as matricule, nomel, prenomel from inscription inner join eleve on inscription.matricule=eleve.matricule where nomgr=:nom and annee=:promo order by (prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

  $prodcount=$DB->querys('SELECT count(matricule) as countel, codef from inscription where nomgr=:nom and annee=:promo order by (matricule)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

  $prodmatiere=$DB->query('SELECT nommat, codem, coef from  matiere where codef=:nom', array('nom'=>$prodcount['codef']));

      

  foreach ($prodmat as $matricule) {
    $totm1=0;
    $coefm1=0;
    
    foreach ($prodmatiere as $matiere) {

      if (isset($_GET['annuel'])) {

        if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {

          $prodm1=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by (eleve.prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'promo'=>$_SESSION['promo']));

          
        }else{

          $prodm1=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by (eleve.prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'promo'=>$_SESSION['promo']));

          

        }

      }elseif (isset($_GET['mensuel'])) {

        $prodverifdev=$DB->querys('SELECT type from devoir where DATE_FORMAT(datedev, \'%m\')=:sem and nomgroupe=:nom and codem=:code and promo=:promo', array('sem'=>$_SESSION['mois'], 'nom'=>$_SESSION['groupe'], 'code'=>$matiere->codem, 'promo'=>$_SESSION['promo']));

        //var_dump($prodverifdev);

        if (!empty($prodverifdev)) {

          if ($prodverifdev['type']=='note de cours') {
            
            $prodm1=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and DATE_FORMAT(datedev, \'%m\')=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>$_SESSION['mois'], 'promo'=>$_SESSION['promo']));
          }else{
            $prodm1=$DB->query('SELECT ((sum(compo*devoir.coefcom)/sum(devoir.coefcom))) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and DATE_FORMAT(datedev, \'%m\')=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>$_SESSION['mois'], 'promo'=>$_SESSION['promo']));
          }
        }else{
          $prodm1=array();
        };

      }else{

        if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {

          $prodm1=$DB->query('SELECT ((sum(compo*devoir.coefcom)/sum(devoir.coefcom))) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>$_GET['semestre'], 'promo'=>$_SESSION['promo']));

          
        }else{

          $prodm1=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>$_GET['semestre'], 'promo'=>$_SESSION['promo']));
          

        }

      }
                  
      foreach ($prodm1 as $moyenne) {
        $totm1+=($moyenne->mgen*$moyenne->coef);

        $coefm1+=$moyenne->coef;
          
      }

    }

    $mat=$matricule->matricule;
    $nom=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);


    if (!empty($coefm1)) {
      $moyenne=($totm1/$coefm1);
    }else{
      $moyenne=0;
    }
    $groupe=$_SESSION['groupe'];
    $semestre=1;
    $promo=$_SESSION['promo'];

    $DB->insert('INSERT INTO admis(matricule, nomel, moyenne, nomgr, semestre, promo) values( ?, ?, ?, ?, ?, ?)', array($mat, $nom, $moyenne, $groupe, $semestre, $promo));// Pour pouvoir faire la trie par ordre de classement
  }

  $prodmoy=$DB->query('SELECT *from admis where nomgr=:nom and semestre=:sem and promo=:promo order by (moyenne) desc', array('nom'=>$_SESSION['groupe'], 'sem'=>$semestre, 'promo'=>$_SESSION['promo']));

  $DB->delete('DELETE FROM admis WHERE nomgr = ? and semestre=? and promo=?', array($_SESSION['groupe'], $semestre, $_SESSION['promo'])); // Pour supprimer imediatement la liste des admis 
  $eff=0;
  foreach ($prodmoy as $moyenne) {
      $eff++;
  }
  $admis=0;
  foreach ($prodmoy as $moyenne) {
    if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {

      if ($moyenne->moyenne>=5) {
        $admis++;
      }
      
    }else{

      if ($moyenne->moyenne>=10) {
        $admis++;
      }     

    }
  }
  $echec=0;
  foreach ($prodmoy as $moyenne) {

    if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {
      if ($moyenne->moyenne<5) {
        $echec++;
      }      
    }else{
      if ($moyenne->moyenne<10) {
        $echec++;
      }     
    }
  }
  if (isset($_GET['annuel'])) {
    $periode=($promo-1).' - '.$promo;
  }elseif (isset($_GET['semestre'])) {
    if ($_GET['semestre']==1) {
        $periode=$_GET['semestre'].'er '.$_SESSION['prodtype'];
    }else{
        $periode=$_GET['semestre'].'ème '.$_SESSION['prodtype'];
    }
  }else{
    $periode=$panier->moisbul();

  }?>

  <page backtop="5mm" backleft="5mm" backright="5mm" backbottom="5mm"><?php

    require 'entetebul.php';

    $fiche=$DB->querys('SELECT niveau as nomf from groupe where nomgr=:mat', array('mat'=>$_SESSION['groupe']));?>
      <ol style="list-style: none; color: grey;">
        <li><label>Effectif </label>.........................<?=$eff;?></li>
        <li><label>Admis</label>............................<?=$admis;?></li>
        <li><label>Non admis</label>....................<?=$echec;?></li>
      </ol>

    <table class="tablistebul">

      <thead>
        <tr>
          <th colspan="3" height="20" style="padding-right: 5px; padding-top: 10px;">Classement général des élèves de la <?=$groupe.' Année scolaire ';?><?=$promo-1;?> - <?=$promo;?>. Période: <?=$periode;?></th>
        </tr>
        <tr>
          <th style="height: 5px; padding-top: 5px;">Rang</th>
          <th style="height: 5px; padding-top: 5px;">Nom</th>
          <th style="height: 5px; padding-top: 5px;">Moyenne</th>
        </tr>
      </thead>

      <tbody><?php

        $moyengen=0;

        foreach ($prodmoy as $key => $moyenne) {

          $moyengen+=$moyenne->moyenne;?><?php

          if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {

            if ($moyenne->moyenne>=5) {?>
              

              <tr>
                <td style="text-align: center;" height="10"><?=$key+1;?></td>


                <td style="text-align: left;"><?=$moyenne->nomel;?></td>
                      
                <td style=""><?=number_format($moyenne->moyenne,2,',',' ');?></td>

              </tr><?php
            }

            
          }else{
            if ($moyenne->moyenne>=10) {?>
              

              <tr>
                <td style="text-align: center; " height="10"><?=$key+1;?></td>


                <td style="text-align: left;"><?=$moyenne->nomel;?></td>
                      
                <td style=""><?=number_format($moyenne->moyenne,2,',',' ');?></td>

              </tr><?php
            }

          }
        }?>

        <tr>
          <th colspan="3">Non admis</th>
        </tr><?php

        foreach ($prodmoy as $key => $moyenne) {

          if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {

            if ($moyenne->moyenne<5) {?>
              

              <tr>
                <td style="text-align: center;" height="10"><?=$key+1;?></td>


                <td style="text-align: left;"><?=$moyenne->nomel;?></td>
                      
                <td style=""><?=number_format($moyenne->moyenne,2,',',' ');?></td>

              </tr><?php
            }

            
          }else{
            if ($moyenne->moyenne<10) {?>
              

              <tr>
                <td style="text-align: center;" height="10"><?=$key+1;?></td>


                <td style="text-align: left;"><?=$moyenne->nomel;?></td>
                      
                <td style=""><?=number_format($moyenne->moyenne,2,',',' ');?></td>

              </tr><?php
            }

          }

        }?>

        <tr>

          <th colspan="2" height="10" style="padding-top: 10px;">Moyenne générale</th>

          <th style="text-align: right; padding-right: 10px; padding-top: 10px;"><?='  '.number_format($moyengen/$nbrele,2,',',' ');?></th>
        </tr>

      </tbody>

    </table><?php

  }

  require 'piedbuladmis.php';

  
  $content = ob_get_clean();
  try {
    $pdf = new HTML2PDF("p","A4","fr", true, "UTF-8" , 0);
    $pdf->pdf->SetAuthor('Amadou');
    $pdf->pdf->SetTitle(date("d/m/y"));
    $pdf->pdf->SetSubject('Création d\'un Portfolio');
    $pdf->pdf->SetKeywords('HTML2PDF, Synthese, PHP');
    //$pdf->pdf->IncludeJS("print(true);");
    $pdf->writeHTML($content);
    $pdf->Output('classement'.'.pdf');
    // $pdf->Output('Devis.pdf', 'D');    
  } catch (HTML2PDF_exception $e) {
    die($e);
  }
//header("Refresh: 10; URL=index.php");
?>
