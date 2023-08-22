<?php
require_once "lib/html2pdf.php";

ob_start(); ?>

<?php require '_header.php';?>

<style type="text/css">

body{
  margin: 0px;
  width: 100%;
  height:100%;
  padding:0px;
}
  .entete{
    width: 100%;
    margin-bottom: 20px;

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
    color: #717375;
  }

  table.tablistebul{
    width: 100%;
    margin:auto;
    margin-top: 20px;
    color: #717375;
    border-collapse: collapse;
  }

  .tablistebul th {
    line-height: 7mm;
    border: 1px solid black;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    margin-top: 30px;
    padding-right: 5px;
    padding-left: 5px;
  }
  .tablistebul td {
    border: 1px solid black;
    line-height: 7mm;
    text-align: left;
    padding-right: 5px;
    padding-left: 5px;
    font-size: 16px;
  }

  table.tablistel{
    width: 99%;
    margin-left: 2px;
    margin-top: 30px;
    color: #717375;
    border-collapse: collapse;
  }

  .tablistel th {
    line-height: 7mm;
    border: 1px solid black;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    margin-top: 30px;
    padding-right: 2px;
    padding-left: 2px;
  }

  .tablistel td {
    border: 1px solid black;
    line-height: 7mm;
    text-align: left;
    padding-right: 5px;
    padding-left: 5px;
    font-size: 10.88px;
  }


  table.border {
    width: 100%;
    color: #717375;
    font-family: helvetica;
    line-height: 10mm;
    border-collapse: collapse;
  }


  .border th {
    border: 1px solid black;
    padding: 5px;
    font-size: 14px;
    background: white;
    text-align: center; }
  .border td {
    padding: 5px;
    border: 1px solid black;    
    font-size: 16px;
    background: white;
    text-align: center;
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
</style>
<page backtop="10mm" backleft="3mm" backright="1mm" backbottom="10mm"><?php

  require 'entete.php';

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

  if (isset($_GET['enseig'])) {

    $niveau='Complexe Scolaire';

    $prodm=$DB->query("SELECT  *from enseignant inner join enseignantencours on enseignant.matricule=matriculens left join contact on enseignant.matricule=contact.matricule where promo='{$_SESSION['promo']}' order by(prenomen) ");

    ?>
      
    <table class="tablistebul" style="width: 100%;">
      <thead>
        <tr><th colspan="6">Liste des Enseignants </th></tr>

        <tr>
          <th>N°</th>
          <th>Matricule</th>
          <th height="25">Prénom & Nom </th>
          <th>Sexe</th>
          <th>Téléphone</th>
          <th>Matière</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
          # code...
        }else{
          $keye=1;
          foreach ($prodm as $key=>$formation) {

            $value=$DB->querys('SELECT  *from enseignement inner join matiere on matiere.codem=enseignement.codem where codens=:code and promo=:promo order by (nomgr)', array('code'=>$formation->matricule, 'promo'=>$_SESSION['promo']));?>

              <tr>
                <td><?=$keye;?></td>
                <td><?=$formation->matricule;?></td>

                <td height="20"><?=ucwords(strtolower($formation->prenomen)).' '.strtoupper($formation->nomen);?></td>

                <td style="text-align: center;"><?=strtoupper($formation->sexe);?></td>

                <td><?=$formation->phone;?></td>

                <td><?=ucwords($value['nommat']);?></td>

              </tr><?php
              $keye++;
          }
        }?>
      </tbody>
    </table><?php
  }

  if (isset($_GET['perso'])) {

    $prodm=$DB->query('SELECT personnel.numpers as matricule, personnel.nom as nom, prenom, sexe, type, phone, pseudo, mdp from personnel left join contact on numpers=contact.matricule left join login on login.matricule=numpers order by(prenom)');?>
      
    <table class="tablistebul" style="width: 90%;">
      <thead>
        <tr><th colspan="6">Liste du Personnels</th></tr>

        <tr>
          <th>N°</th>
          <th>Matricule</th>
          <th height="25">Prénom & Nom </th>
          <th>Sexe</th>
          <th>Fonction</th>
          <th>Téléphone</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
          # code...
        }else{

          foreach ($prodm as $key=>$formation) {?>

            <tr>
              <td><?=$key+1;?></td>
              <td><?=$formation->matricule;?></td>

              <td height="20"><?=ucwords(strtolower($formation->prenom)).' '.strtoupper($formation->nom);?></td>
              <td style="text-align: center;"><?=strtoupper($formation->sexe);?></td>
              <td><?=ucwords($formation->type);?></td>

              <td><?=$formation->phone;?></td>

            </tr><?php
          }
        }?>
      </tbody>
    </table><?php
  }


  if (isset($_GET['groupe'])) {?>
    <table class="tablistel" style="margin: auto; margin-top:30px;">
      <thead>
        <tr>
          <th colspan="13" class="info" style="text-align: center">Liste des Classes</th>									
        </tr>

        <tr>
          <th colspan="2">N°</th>
          <th>Classes</th>
          <th class="px-1">Fille</th>
          <th class="px-1">Garçon</th>
          <th class="px-1">Total</th>
          <th class="px-1">Anciens</th>
          <th class="px-1">Nouveaux</th>
          <th class="px-1">Total</th>
          <th class="bg-warning bg-opacity-50">Redoublants</th>
          <th>% Nouveaux</th>         							
        </tr>

      </thead><?php

      $prodm=$DB->query('SELECT id, nom from cursus  order by(cursus.id)');

      
      $count=1;
      $cumulgenseconfille=0;
      $cumulgensecongarcon=0;
      $cumulgenseconeffectif=0;
      $cumulgenseconanciens=0;
      $cumulgenseconnouveaux=0;
      $cumulgensecontotalins=0;
      $cumulgenseconredoublants=0;
      $cumulgenseconpourcentage=0;

      $cumulgenfille=0;
      $cumulgengarcon=0;
      $cumulgeneffectif=0;
      $cumulgenanciens=0;
      $cumulgennouveaux=0;
      $cumulgentotalins=0;
      $cumulgenredoublants=0;
      $cumulgenpourcentage=0;
      foreach ($prodm as $values) {
        
        $prodf=$DB->query('SELECT groupe.id as id, nomgr, groupe.niveau as niveau, groupe.codef as codef, nomf, classe from groupe inner join formation on formation.codef=groupe.codef where promo=:promo and groupe.niveau=:niv', array('promo'=>$_SESSION['promo'], 'niv'=>$values->nom));        

        if(!empty($prodf)){?>

          <tbody>
            <tr>
              <th colspan="13">Niveau <?=ucwords($values->nom);?></th>
            </tr><?php

            if (empty($prodf)) {

            }else{
              $cumulfille=0;
              $cumulgarcon=0;
              $cumuleffectif=0;
              $cumulanciens=0;
              $cumulnouveaux=0;
              $cumultotalins=0;
              $cumulredoublants=0;
              $cumulpourcentage=0;
              foreach ($prodf as $key=> $formation) {
                $fille=$panier->effectifSexeClasse("f",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0];
                $garcon=$panier->effectifSexeClasse("m",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0];
                $totaleff=$fille+$garcon;

                $anciens=$panier->effectifInscritClasse("reinscription",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0];
                $nouveaux=$panier->effectifInscritClasse("inscription",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0];
                $totalins=$anciens+$nouveaux;
                
                $redoublants=$panier->effectifStatutClasse("admis",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0];
                
                $cumulfille+=$fille;
                $cumulgarcon+=$garcon;
                $cumuleffectif+=$totaleff;
                $cumulanciens+=$anciens;
                $cumulnouveaux+=$nouveaux;
                $cumultotalins+=$totalins;
                $cumulredoublants+=$redoublants;

                if ($values->nom=="college" or $values->nom=="lycee") {
                  $cumulgenseconfille+=$fille;
                  $cumulgensecongarcon+=$garcon;
                  $cumulgenseconeffectif+=$totaleff;
                  $cumulgenseconanciens+=$anciens;
                  $cumulgenseconnouveaux+=$nouveaux;
                  $cumulgensecontotalins+=$totalins;
                  $cumulgenseconredoublants+=$redoublants;
                }else{
                  $cumulgenseconfille+=0;
                  $cumulgensecongarcon+=0;
                  $cumulgenseconeffectif+=0;
                  $cumulgenseconanciens+=0;
                  $cumulgenseconnouveaux+=0;
                  $cumulgensecontotalins+=0;
                  $cumulgenseconredoublants+=0;
                  $cumulgenseconpourcentage+=0;
                }

                $cumulgenfille+=$fille;
                $cumulgengarcon+=$garcon;
                $cumulgeneffectif+=$totaleff;
                $cumulgenanciens+=$anciens;
                $cumulgennouveaux+=$nouveaux;
                $cumulgentotalins+=$totalins;
                $cumulgenredoublants+=$redoublants;

                if (!empty($totalins)) {
                  $percentNouveau=(($panier->effectifInscritClasse("inscription",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0])/$totalins)*100;?><?php 
                }else{
                  $percentNouveau=0;
                }
                $cumulpourcentage+=$percentNouveau;?>

                <tr>												
                  <td><?=$count;?></td>
                  <td><?=$key+1;?></td>
                  <td><?=$formation->nomgr;?> <?=' '.ucfirst($formation->niveau);?></td>
                  <td style="text-align:center;"><?=$fille;?></td>
                  <td style="text-align:center;"><?=$garcon;?></td>
                  <td style="text-align:center;" class="bg-success bg-opacity-50"><?=$totaleff;?></td>

                  <td style="text-align:center;"><?=$anciens;?></td>
                  <td style="text-align:center;"><?=$nouveaux;?></td>
                  <td style="text-align:center;" class="bg-info bg-opacity-50"><?=$totalins;?></td>

                  <td style="text-align:center;"><?=$redoublants;?></td>
                  <td style="text-align:center;"><?=number_format($percentNouveau,2,',',' ');?></td>
                </tr><?php
                $count++;

              }?>

              <tr>
                <th colspan="3">Total <?=ucwords($values->nom);?></th>
                <th><?=$cumulfille;?></th>
                <th><?=$cumulgarcon;?></th>
                <th><?=$cumulfille+$cumulgarcon;?></th>
                <th><?=$cumulanciens;?></th>
                <th><?=$cumulnouveaux;?></th>
                <th><?=$cumulanciens+$cumulnouveaux;?></th>
                <th><?=$cumulredoublants;?></th>
                <th><?=number_format(($cumulnouveaux/($cumulanciens+$cumulnouveaux))*100,2,',',' ');?></th>
              </tr><?php
            }?>
          </tbody><?php
        }
      }?>
      <tfoot class="fs-8">
        <tr class="bg-warning bg-opacity-50">
          <th colspan="3">Total Secondaire (Collège + Lycée)</th>
          <th><?=$cumulgenseconfille;?></th>
          <th><?=$cumulgensecongarcon;?></th>
          <th><?=$cumulgenseconfille+$cumulgensecongarcon;?></th>
          <th><?=$cumulgenseconanciens;?></th>
          <th><?=$cumulgenseconnouveaux;?></th>
          <th><?=$cumulgenseconanciens+$cumulgenseconnouveaux;?></th>
          <th><?=$cumulgenseconredoublants;?></th>
          <th><?=number_format(($cumulgenseconnouveaux/($cumulgenseconanciens+$cumulgenseconnouveaux))*100,2,',',' ');?></th>
        </tr>

        <tr class="bg-success bg-opacity-50">
          <th colspan="3">Total Général</th>
          <th><?=$cumulgenfille;?></th>
          <th><?=$cumulgengarcon;?></th>
          <th><?=$cumulgenfille+$cumulgengarcon;?></th>
          <th><?=$cumulgenanciens;?></th>
          <th><?=$cumulgennouveaux;?></th>
          <th><?=$cumulgenanciens+$cumulgennouveaux;?></th>
          <th><?=$cumulgenredoublants;?></th>
          <th><?=number_format(($cumulgennouveaux/($cumulgenanciens+$cumulgennouveaux))*100,2,',',' ');?></th>
        </tr>
      </tfoot>
      </table><?php
  }

  // Afficher la liste des élèves

  if (isset($_GET['listel'])) {

    $prodeleve=$DB->query('SELECT nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%d/%m/%Y\') AS naissance, inscription.matricule as matricule, nomgr, classe, nomf from eleve inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef where annee=:promo order by (prenomel)', array('promo'=>$_SESSION['promo']));?>

        
    <table class="tablistel" style="margin: auto; margin-top:30px;">

      <thead>

        <tr><th colspan="7" height="30">Liste des <?=$_SESSION['typeel'];?> Inscrits en <?=$_SESSION['promo'];?></th></tr>

        <tr>
          <td></td>
          <th height="25">N°</th>
          <th>Prénom & Nom</th>
          <th>Né(e)</th>
          <th>S</th>
          <th>Filiation</th>
          <th>Filière</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodeleve)) {

        }else{

          foreach ($prodeleve as $key=> $eleve) {?>

            <tr>
              <td style="text-align: center;"><?=$key+1;?></td>

              <td height="20"><?=$eleve->matricule;?></td>

              <td><?=ucwords(strtolower($eleve->prenomel)).' '.strtoupper($eleve->nomel);?></td>

              <td><?=$eleve->naissance;?></td>

              <td style="text-align: center;"><?=strtoupper($eleve->sexe);?></td>

              <td style="width: 180px;"><?=ucwords($eleve->pere).' et '.ucwords(strtolower($eleve->mere));?></td><?php

              if ($eleve->classe==1) {?>

                <td height="20"><?=$eleve->classe.'ère '.$eleve->nomf;?></td><?php

              }elseif($eleve->classe=='petite section' or $eleve->classe=='moyenne section' or $eleve->classe=='grande section' or $eleve->classe=='terminale'){?>

                <td><?=' '.ucwords($eleve->classe);?></td><?php

              }elseif($eleve->classe=='terminale'){?>

                <td><?=' '.ucwords($eleve->classe.' '.$eleve->nomf);?></td><?php

              }else{?>

                <td height="20"><?=$eleve->nomgr;?></td><?php                
              }?>

            </tr><?php
          }

        }?>
      </tbody>
    </table><?php
  }

  // Afficher la liste des élèves d'une formation

  if (isset($_GET['voir_e'])) {

    $prodm=$DB->query('SELECT  nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%d/%m/%Y\') AS naissance, inscription.matricule as matricule, nomgr, classe, nomf from eleve inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef where inscription.codef=:code and annee=:promo order by(eleve.prenomel)', array('code'=>$_GET['voir_e'], 'promo'=>$_SESSION['promo']));

    $prodf=$DB->querys('SELECT classe, nomf from formation  where codef=:code', array('code'=>$_GET['voir_e']));

    if ($prodf['classe']=='1') {

      $classe=ucwords($prodf['classe'].'ere '.ucwords($prodf['nomf']));

    }elseif($prodf['classe']=='petite section' or $prodf['classe']=='moyenne section' or $prodf['classe']=='grande section' or $prodf['classe']=='terminale'){

      $classe=ucwords($prodf['classe'].' '.$prodf['nomf']);

    }else{

      $classe=ucwords($prodf['classe'].'ème '.ucwords($prodf['nomf']));
    }?>

        
    <table class="tablistel" style="margin: auto;">

      <thead>

        <tr><th colspan="8" height="30">Liste des <?=$_SESSION['typeel'];?> <?=$classe;?></th></tr>

        <tr>
          <th height="25"></th>          
          <th>N°M</th>
          <th>Prénom & Nom</th>          
          <th>Né(e)</th>
          <th>Sexe</th>
          <th>Lieu N</th>
          <th>Filiation</th>
          <th>Classe</th>         
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
      # code...
        }else{

          foreach ($prodm as $key=> $formation) {?>

            <tr>
              <td style="text-align: center;"><?=$key+1;?></td>
              <td height="20"><?=$formation->matricule;?></td>
              <td><?=ucwords(strtolower($formation->prenomel)).' '.strtoupper($formation->nomel);?></td>              
              <td height="20"><?=$formation->naissance;?></td>
              <td style="text-align: center;"><?=strtoupper($formation->sexe);?></td>
              <td height="20"><?=$formation->adresse;?></td>
              <td style="width: 180px;"><?=strtoupper($formation->pere).' et de '.ucfirst(strtolower($formation->mere));?></td>
              <td height="20"><?=$formation->nomgr;?></td>
            </tr><?php
          }

        }?>
      </tbody>
    </table><?php
  }

// Afficher les eleves d'un niveau

  if (isset($_GET['voir_cursus'])) {

    $prodm=$DB->query('SELECT  nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%d/%m/%Y\') AS naissance, inscription.matricule as matricule, nomgr, classe, nomf from eleve inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef where inscription.niveau=:code and annee=:promo order by(prenomel)', array('code'=>$_GET['voir_cursus'], 'promo'=>$_SESSION['promo']));?>

        
    <table class="tablistel" style="margin: auto; margin-top:30px;">

      <thead>

        <tr><th colspan="7" height="30">Liste des <?=$_SESSION['typeel'];?> niveau <?=$_GET['voir_cursus'];?></th></tr>

        <tr>
          <th height="25"></th>          
          <th>N°M</th>
          <th>Prénom & Nom</th>          
          <th>Né(e)</th>
          <th>Sexe</th>
          <th>Filiation</th>
          <th>Classe</th>         
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
      # code...
        }else{

          foreach ($prodm as $key=> $formation) {

              if ($formation->classe=='1') {

                $classe=ucwords($formation->classe.'ère ');

              }elseif($formation->classe=='petite section' or $formation->classe=='moyenne section' or $formation->classe=='grande section' or $formation->classe=='terminale'){

                $classe=ucwords($formation->classe);

              }else{

                $classe=ucwords($formation->classe.'ème');?><?php
              }?>

            <tr>
              <td style="text-align: center;"><?=$key+1;?></td>
              <td height="20"><?=$formation->matricule;?></td>
              <td><?=ucwords(strtolower($formation->prenomel)).' '.strtoupper($formation->nomel);?></td>              
              <td height="20"><?=$formation->naissance;?></td>
              <td style="text-align: center;"><?=strtoupper($formation->sexe);?></td>
              <td style="width: 180px;"><?=ucwords($formation->pere).' et '.ucwords(strtolower($formation->mere));?></td>
              <td height="20"><?=$formation->nomgr;?></td>
            </tr><?php
          }

        }?>
      </tbody>
    </table><?php
  }

  //Afficher les élèves d'un groupe


  if (isset($_GET['voir_elg'])) {

    $prodm=$DB->query('SELECT  nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%d/%m/%Y\') AS naissance, inscription.matricule as matricule, nomgr, classe, nomf from inscription inner join eleve on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef where inscription.nomgr=:code and annee=:promo order by(prenomel)', array('code'=>$_GET['voir_elg'], 'promo'=>$_SESSION['promo']));

    $prodf=$DB->querys('SELECT nomgr from groupe  where nomgr=:code', array('code'=>$_GET['voir_elg']));?>

        
    <table class="tablistel">

      <thead>

        <tr><th colspan="6" height="15"><?=$_SESSION['typeel'];?> de <?=$prodf['nomgr'];?> Année-Scolaire <?=($_SESSION['promo']-1).'-'. $_SESSION['promo'];?></th></tr>

        <tr>
          <th height="25"></th>
          <th>N°M</th>
          <th>Prénom & Nom</th>
          <th>Né(e)</th>
          <th>Sexe</th>
          <th>Filation</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
      # code...
        }else{


          foreach($prodm as $key=>$payeloc ){?>

            <tr>

              <td height="15" style="text-align: center;"><?=$key+1;?></td>

              <td style="text-align: center;"><?=$payeloc->matricule;?></td>

              <td><?=ucfirst($payeloc->prenomel).' '.strtoupper($payeloc->nomel);?></td>              

              <td style="text-align: center;"><?=$payeloc->naissance;?></td>

              <td style="text-align: center;"><?=strtoupper($payeloc->sexe);?></td>

              <td><?=ucwords($payeloc->pere).' et '.ucwords(strtolower($payeloc->mere));?></td>

            </tr><?php
          }

        }?>
      </tbody>
    </table><?php
  }

  //Afficher les élèves dune salle par annee


  if (isset($_GET['classenouv'])) {?>

    <table class="tablistel" style="margin:auto;">

      <thead>

        <tr><th colspan="8" height="15"><?=$_SESSION['typeel'];?> de <?=$_GET['classenouv'];?> Année-Scolaire <?=($_SESSION['promo']-1).'-'. $_SESSION['promo'];?></th></tr>

        <tr>
          <th height="25"></th>
          <th>N°M</th>
          <th>Prénom & Nom</th>
          <th>Né(e)</th>
          <th>Sexe</th>
          <th>Lieu de N</th>
          <th>Filation</th>
          <th>Etat scol</th>
        </tr>

      </thead><?php

      $neweleve = array(
        'inscription'   => 'Inscrits',
        'reinscription'   => 'Reinscrits'
        
      );

      foreach ($neweleve as $keynew => $valuenew) {

        $prodm=$DB->query('SELECT  nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%d/%m/%Y\') AS naissance, inscription.matricule as matricule, nomgr, classe, nomf, etatscol from inscription inner join eleve on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef where inscription.nomgr=:code and etat=:etat and annee=:promo order by(prenomel)', array('code'=>$_GET['classenouv'], 'etat'=>$keynew, 'promo'=>$_SESSION['promo']));?>

        <tbody>

          <tr><th colspan="8" height="15"><?=$_SESSION['typeel'].' '.$valuenew;?></th></tr><?php

          if (empty($prodm)) {
        # code...
          }else{


            foreach($prodm as $key=>$payeloc ){?>

              <tr>

                <td height="15" style="text-align: center;"><?=$key+1;?></td>

                <td style="text-align: center;"><?=$payeloc->matricule;?></td>

                <td><?=ucfirst($payeloc->prenomel).' '.strtoupper($payeloc->nomel);?></td>              

                <td style="text-align: center;"><?=$payeloc->naissance;?></td>

                <td style="text-align: center;"><?=strtoupper($payeloc->sexe);?></td>

                <td style="text-align: center;"><?=$payeloc->adresse;?></td>

                <td><?=ucwords($payeloc->pere).' et '.ucwords(strtolower($payeloc->mere));?></td>
                <td><?=$payeloc->etatscol;?></td>

              </tr><?php
            }

          }?>
        </tbody><?php
      }?>
      </table><?php
  }


  // Afficher la liste des enseignements


  if (isset($_GET['enseigne'])) {?>
        
    <table class="tablistebul">

      <thead>

        <tr><th colspan="4" height="30">Liste des Cours</th></tr>

        <tr>
          <th>N°</th>          
          <th height="25">Enseignants</th>
          <th>Matières</th>
          <th>Classe</th>
        </tr>

      </thead><?php

      foreach ($panier->cursus() as $values) {
       

        $prodm=$DB->query('SELECT  enseignement.id as id, groupe.nomgr as nomgr, nomf, nommat, nomen, prenomen, codens, matiere.codem as codem, groupe.nomgr as nomgr from enseignement inner join groupe on enseignement.nomgr=groupe.nomgr inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule inner join formation on enseignement.codef=formation.codef where enseignement.promo=:promo and formation.niveau=:niv order by(prenomen)',array('promo'=>$_SESSION['promo'], 'niv'=>$values->nom));

        if(!empty($values)){?>
              
          <tbody>
            <tr>
              <th colspan="4" height="25" style="text-align: center; ">Niveau <?=ucwords($values->nom);?></th>
            </tr><?php

            if (empty($prodm)) {
       
            }else{

              foreach ($prodm as $key=> $formation) {?>

                <tr>
                  <td><?=$key+1;?></td>

                  <td height="20"><?=ucfirst(strtolower($formation->prenomen)).' '.strtoupper($formation->nomen);?></td>

                  <td><?=ucwords($formation->nommat);?></td>

                  <td><?=$formation->nomgr;?></td>

                </tr><?php
              }

            }?>

          </tbody><?php
        }
      }?>
    </table><?php
  }

  //Pour imprimer les frais de scolarités


  if (isset($_GET['scoltot'])) {
    $montant=0;
    $prodpaye = $DB->query('SELECT montant, tranche, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payementfraiscol WHERE matricule = :mat and promo=:promo ORDER BY(tranche)', array('mat'=> $_GET['scoltot'], 'promo'=>$_SESSION['promo']));?>

        
    <table class="tablistebul">
      <thead>

        <tr><th colspan="3" height="25">Frais de scolarité de <?=$_GET['nomel'].' N° matricule '.$_GET['scoltot'];?></th></tr>

        <tr>  
          <th height="25">Désignation</th>
          <th>Montant</th>
          <th>Date de paye</th>
        </tr>

      </thead>

      <tbody>
        <tr>

          <td height="25"><?='Inscrip/Reinscrip';?></td>

          <td style="text-align: right;"><?=number_format($panier->fraisIns($_GET['scoltot'], $_GET['promo'])[0],0,',',' ');?></td>

          <td><?='Payé le '.(new dateTime($panier->fraisIns($_GET['scoltot'], $_GET['promo'])[1]))->format('d/m/Y');?></td>
        </tr><?php

        if (empty($prodpaye)) {

        }else{

          foreach ($prodpaye as $product) {

            $montant+=$product->montant;?>

            <tr>

              <td height="25" style="text-align: center;"><?=ucfirst($product->tranche);?></td>

              <td style="text-align: right; padding: 10px;"><?=number_format($product->montant,0,',',' ');?></td>

              <td><?='Payé le '.$product->datepaye;?></td>

            </tr><?php
          }?>

            <tr>

              <th style="padding: 10px; text-align: right;" colspan="2">Total payé : <?=number_format($montant+$panier->fraisIns($_GET['scoltot'], $_GET['promo'])[0],0,',',' ');?></th>

              <th style="color: red;">Reste Annuel: <?=number_format($panier->resteAnnuel($_GET['scoltot'], $_GET['promo'], $_GET['codef']),0,',',' ');?></th>

            </tr><?php

        }?>

      </tbody>
    </table><?php
  }


  if (isset($_GET['histscol'])) {
    $montant=0;
    $prodpaye = $DB->query('SELECT montant, tranche, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM histopayefrais WHERE matricule = :mat and promo=:promo ORDER BY(tranche)', array('mat'=> $_GET['histscol'], 'promo'=>$_SESSION['promo']));?>

        
    <table class="tablistebul">
      <thead>

        <tr><th colspan="3" height="25">Historique des frais de scolarité de <?=$_GET['nomel'].' N° matricule '.$_GET['histscol'];?></th></tr>

        <tr>  
          <th height="25">Tranche</th>
          <th>Montant</th>
          <th>Date de paye</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodpaye)) {

        }else{

          foreach ($prodpaye as $product) {

            $montant+=$product->montant;?>

            <tr>

              <td height="25" style="text-align: center;"><?=ucfirst($product->tranche);?></td>

              <td style="text-align: right; padding: 10px;"><?=number_format($product->montant,0,',',' ');?></td>

              <td><?='Payé le '.$product->datepaye;?></td>

            </tr><?php
          }?>

            <tr>

              <th style="padding: 10px; text-align: right;" colspan="2">Total payé : <?=number_format($montant,0,',',' ');?></th>

              <th style="color: red;"></th>

            </tr><?php

        }?>

      </tbody>
    </table><?php
  }

  // Pour imprimer une facture de payement de frais de scolarité

  if (isset($_GET['numfac'])) {

    $products=$DB->query('SELECT histopayefrais.matricule as matricule, montant, numpaie, tranche, nomel, prenomel, nomgr, codef FROM histopayefrais inner join eleve on eleve.matricule=histopayefrais.matricule inner join inscription on inscription.matricule=histopayefrais.matricule WHERE histopayefrais.famille= ? and annee=? order by(nomgr)', array($_GET['numfac'], $_SESSION['promo']));

    if ($_GET['tranche']=='1ere tranche') {
      $fraisinscript=' + inscription ou reinscription';
    }else{
      $fraisinscript='';
    }?>

    <table class="border" style=" margin: auto; margin-top: 30px;">

      <thead>
        <tr>
          <th colspan="3" style="border: 0px; border-right: 1px; text-align: left; font-size:12px;">NB: Aucun remboursement ou transfert n'est possible après paiement.</th><?php 
          foreach($panier->nomBanqueTicket() as $product){
            if (sizeof($panier->nomBanqueTicket())>1) {
              $cols=1;
            }else{
              $cols=3;
            }?>
            <th colspan="<?=$cols;?>" style="font-size: 11px;"><?=ucwords($product->nomb);?> N°</th><?php
          }?>
        </tr>

        <tr>
          <th colspan="3" style="border: 0px; border-bottom: 1px; border-right: 1px; text-align: left; font-size:12px;">Le paiement de toute scolarité entamée est entièrement dû.</th>
          <?php 
          foreach($panier->nomBanqueTicket() as $product){
            if (sizeof($panier->nomBanqueTicket())>1) {
              $cols=1;
            }else{
              $cols=3;
            } ?>
            <th colspan="<?=$cols;?>" style="font-size: 11px;"><?=ucwords($product->numero);?></th><?php
          }?>
        </tr>

        <tr>
          <th colspan="6" style="border-bottom: 0px; text-align: center;">Réçu de Payement des frais de scolarité / Année-Scolaire: <?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></th>
        </tr>

        <tr>
          <th colspan="6" height="10" style=" text-align: left;">Date: <?=$_GET['date'];?> / N° Réçu: <?=$_GET['numfac'];?> / TP: <?=$_GET['type'];?> / Banque: <?=ucwords($_GET['banque']);?> / N° Bordereau/Chèque: <?=$_GET['numpaie'];?></th>
        </tr>

        <tr>
          <th>Matricule</th>
          <th>Prénom & Nom</th>
          <th>Classe</th>
          <th>Montant Payé</th> 
          <th>Total Payé</th>
          <th>Reste Annuel</th>
        </tr>
      </thead>

      <tbody><?php
          $montp=0;
          $totp=0;
          $restp=0;
          $resta=0;

          foreach ($products as $key=> $product){

            $prodmat=$DB->querys('SELECT matricule FROM histopayefrais WHERE matricule= ? and promo=?', array($product->matricule, $_SESSION['promo']));

            $prodtot=$DB->querys('SELECT sum(montant) as montant FROM histopayefrais WHERE matricule= ? and promo=?', array($product->matricule, $_SESSION['promo']));

            $prodtott=$DB->querys('SELECT sum(montant) as montant FROM histopayefrais WHERE matricule= ? and promo=? and tranche=?', array($product->matricule, $_SESSION['promo'], $product->tranche));

            $prodscolt=$DB->querys('SELECT sum(montant) as montant from scolarite where codef=:code and promo=:promo', array('code'=>$product->codef, 'promo'=>$_SESSION['promo']));

            $prodscol=$DB->querys('SELECT sum(montant) as montant from scolarite where codef=:code and tranche=:tranche and promo=:promo', array('code'=>$product->codef, 'tranche'=>$product->tranche, 'promo'=>$_SESSION['promo']));

            $prodscola=$DB->querys('SELECT sum(montant) as montant from scolarite where codef=:code and promo=:promo', array('code'=>$product->codef, 'promo'=>$_SESSION['promo']));

            $prodins=$DB->querys('SELECT sum(montant) as montant from payement where matricule=:mat and promo=:promo', array('mat'=>$product->matricule, 'promo'=>$_SESSION['promo']));

            if ($product->tranche=='1ere tranche') {

              $montpaye=$product->montant+$prodins['montant'];
            }else{
              $montpaye=$product->montant;
            }

            $totpaye=$prodtot['montant']+$prodins['montant'];

            $restetranche=$prodscol['montant']-$prodtott['montant'];

            $resteannuel=$prodscola['montant']-$prodtot['montant'];

            //$resteannuel=$prodscola['montant']-$prodtot['montant'];

            $montp+=$montpaye;

            $totp+=$totpaye;
            $restp+=$restetranche;
            $resta+=$resteannuel;?>

            <tr>

              <td><?=$product->matricule;?></td>

              <td><?=ucwords($product->prenomel.' '.strtoupper($product->nomel));?></td>

              <td><?=$product->nomgr; ?></td>

              <td style="text-align: right;"><?=number_format($montpaye,0,',',' '); ?></td>

              <td style="text-align: right;"><?=number_format($totpaye,0,',',' '); ?></td>

              <td style="text-align: right; color: red;"><?=number_format($resteannuel,0,',',' '); ?></td>

            </tr><?php
          } ?>
        

      </tbody><?php 
      if (sizeof($products)>1) {?>

        <tfoot>
          <tr>
            <th colspan="3">Total</th>
            <th style="text-align: right;"><?=number_format($montp,0,',',' ');?></th>

            <th style="text-align: right;"></th>

            <th style="text-align: right; color: red;"><?=number_format($resta,0,',',' ');?></th>
          </tr>
        </tfoot><?php
      }?>

    </table><?php
  }


  //Pour imprimer les salaires payés en tot


  if (isset($_GET['paytotemp'])) {
    $montant=0;
    $heuret=0;?>

        
    <table class="tablistebul">
      <thead>
          <tr><th colspan="4" height="30">Salaires payés de <?=$_GET['nomel'];?></th></tr>

          <tr>
              <th height="25">Mois</th>
              <th>Heure(s)</th>
              <th>Montant</th>
              <th>Date paye</th>
          </tr>
      </thead>

      <tbody><?php

        $montant=0;
        $heuret=0;

        foreach ($month as $key=> $mois) {

          $prodpaye = $DB->query('SELECT id, numdec, matricule, montant, mois, heurep, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payenseignant WHERE matricule = :mat and mois=:mois and anneescolaire=:promo ORDER BY(datepaye) DESC', array('mat'=> $_GET['paytotemp'], 'mois'=>$key, 'promo'=>$_SESSION['promo']));?>

          <tr>

            <td><?=ucfirst($mois);?></td><?php

            if (!empty($prodpaye)) {
                                                  
              foreach ($prodpaye as $paye) {

                $montant+=$paye->montant;
                $heuret+=$paye->heurep; ?>

                <td style="text-align: center;"><?=$paye->heurep;?> h</td>

                <td style="text-align: right;"><?=number_format($paye->montant,0,',',' ');?></td>

                <td><?='Payé le '.$paye->datepaye;?></td><?php
              }
            }else{?>

              <td style="text-align: center;"><?='00:00';?></td>

              <td style="text-align: right;"><?='--';?></td>
              <td></td><?php

            }?>
          </tr><?php
        }?>

        <tr>
            <th></th>
            <th><?=$heuret;?> h</th>
            <th style="text-align: right;"><?=number_format($montant,0,',',' ');?></th>
            <th></th>
        </tr>

    </tbody>
  </table><?php
}

//Historique des payements des enseignants

if (isset($_GET['histopaytotemp'])) {?>

        
  <table class="tablistebul">
    <thead>
      <tr>
          <th></th>
          <th colspan="4">Historique des payements pour le mois de <?=$_GET['periode'];?></th>
      </tr>

      <tr>
          <th height="25">Heure(s)</th>
          <th>Montant</th>
          <th>Date paye</th>
      </tr>
    </thead>

    <tbody><?php

      $montant=0;
      $heuret=0;

      $prodpaye = $DB->query('SELECT id, matricule, montant, mois, heurep, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM histopayenseignant WHERE matricule = :mat and mois=:mois and anneescolaire=:promo ORDER BY(datepaye) DESC', array('mat'=> $_GET['mat'], 'mois'=>$_GET['mois'], 'promo'=>$_SESSION['promo']));

      if (!empty($prodpaye)) {
                                                  
        foreach ($prodpaye as $paye) {

            $montant+=$paye->montant;
            $heuret+=$paye->heurep; ?>

            <tr>

              <td style="text-align: center;"><?=$paye->heurep;?> h</td>

              <td style="text-align: right;"><?=number_format($paye->montant,2,',',' ');?></td>

              <td><?='Payé le '.$paye->datepaye;?></td>

              
            </tr><?php
          }
        }?>
      <tr>
        <th height="25"><?=$heuret;?> h</th>
        <th style="text-align: right;"><?=number_format($montant,2,',',' ');?></th>
      </tr>

    </tbody>
  </table><?php
}



if (isset($_GET['paytotpers'])) {
    $montant=0;
    $prodpaye = $DB->query('SELECT montant, mois, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payepersonnel WHERE matricule = :mat and promo=:promo ORDER BY(mois)', array('mat'=> $_GET['paytotpers'],  'promo'=>$_SESSION['promo']));?>

        
    <table class="tablistebul">
      <thead>

        <tr><th height="30" colspan="3">Salaires payés de <?=$_GET['nomel'];?></th></tr>

        <tr>  
          <th height="25">Mois</th>
          <th>Montant</th>
          <th>Date de paye</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodpaye)) {

        }else{

          foreach ($prodpaye as $product) {

            if ($product->mois==1) {
              $mois='Janvier';
            }elseif ($product->mois==2) {
              $mois='Février';
            }elseif ($product->mois==3) {
              $mois='Mars';
            }elseif ($product->mois==4) {
              $mois='Avril';
            }elseif ($product->mois==5) {
              $mois='Mai';
            }elseif ($product->mois==6) {
              $mois='Juin';
            }elseif ($product->mois==7) {
              $mois='Juillet';
            }elseif ($product->mois==8) {
              $mois='Août';
            }elseif ($product->mois==9) {
              $mois='Septembre';
            }elseif ($product->mois==10) {
              $mois='Octobre';
            }elseif ($product->mois==11) {
              $mois='Novembre';
            }elseif ($product->mois==12) {
              $mois='Décembre';
            }

            $montant+=$product->montant;?>

            <tr>

              <td height="25"><?=ucfirst($mois);?></td>

              <td style="text-align: right; padding: 10px;"><?=number_format($product->montant,0,',',' ');?></td>

              <td><?='Payé le '.$product->datepaye;?></td>

            </tr><?php
          }?>

            <tr>

              <th style="padding: 10px; text-align: left;" colspan="3">Total payé : <?=number_format($montant,0,',',' ');?></th>

            </tr><?php

        }?>

      </tbody>
    </table><?php
  }


  if (isset($_GET['payepersfact'])) {

    $product=$DB->querys('SELECT id, numdec, montant, mois, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payepersonnel WHERE numdec= ?', array($_GET['payepersfact']));

    
    if (empty($product['montant'])) {
      $montantpaye=0;
    }else{
      $montantpaye=$product['montant'];
    }

    $prodac=$DB->querys('SELECT *FROM accompte WHERE matricule= ? and mois=? and anneescolaire=?', array($_GET['numel'], $_GET['mois'], $_SESSION['promo']));

    if (empty($prodac['montant'])) {
      $accompte=0;
    }else{
      $accompte=$product['montant'];
    }


    $prodsoc=$DB->querys('SELECT *FROM ssocialpers WHERE numpers= ?', array($_GET['numel']));

    if (empty($prodsoc['montant'])) {
      $cotisation=0;
    }else{
      $cotisation=$prodsoc['montant'];
    }

    $prodpri=$DB->querys('SELECT *FROM primepers WHERE numpersp= ? and promop=?', array($_GET['numel'], $_SESSION['promo']));

    if (empty($prodpri['montantp'])) {
      $prime=0;
    }else{
      $prime=$prodpri['montantp'];
    }?>


    <table style="margin-top: 30px; margin-left:30px; border-bottom: 0px;" class="border" >
      <thead>

        <tr>
          <th colspan="4">BULLETIN DE PAIE <?=strtoupper($_GET['mois']);?> </th>
        </tr>

        <tr>
          <th><?="Matricule:  " .$_GET['numel']; ?></th>
          <th colspan="3" style="font-size: 18px;"><?=$panier->nomPersonnel($_GET['numel']); ?></th>
        </tr>

        <tr>
          <th><?php echo "Paiement N°: " .$_GET['payepersfact']; ?></th>
          <th><?php echo "Type de Paiement:  " .$_GET['type']; ?></th>
          <th colspan="2"><?php echo "Date de paiement:  " .$_GET['date']; ?></th>
        </tr>

        <tr>
          <th colspan="4"></th>
        </tr>

        <tr>
          <th style="width: 10%;" height="30">Mois</th>
          <th style="width: 48%; text-align: center;">Désignation</th>
          <th style="width: 14%;">Montant</th>
        </tr>

      </thead>

      <tbody><?php
        $total=0;

        $salaire=$montantpaye-$prime+$accompte+$cotisation;

        if ($product['mois']==1) {
            $mois='Janvier';
          }elseif ($product['mois']==2) {
            $mois='Février';
          }elseif ($product['mois']==3) {
            $mois='Mars';
          }elseif ($product['mois']==4) {
            $mois='Avril';
          }elseif ($product['mois']==5) {
            $mois='Mai';
          }elseif ($product['mois']==6) {
            $mois='Juin';
          }elseif ($product['mois']==7) {
            $mois='Juillet';
          }elseif ($product['mois']==8) {
            $mois='Août';
          }elseif ($product['mois']==9) {
            $mois='Septembre';
          }elseif ($product['mois']==10) {
            $mois='Octobre';
          }elseif ($product['mois']==11) {
            $mois='Novembre';
          }elseif ($product['mois']==12) {
            $mois='Décembre';
          }?>

        <tr>

          <td style="width: 20%;border:2px ; border-bottom: 0px; text-align: left;"><?=$mois;?></td>

          <td style="width: 38%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Paiement de salaire'.' '); ?></td>

          <td style="width: 14%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($montantpaye,0,',',' '); ?></td>
        </tr>

        <tr>

          <td style="width: 10%;border:2px ; border-bottom: 0px;">--</td>

          <td style="width: 48%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Prime'.' '); ?></td>

          <td style="width: 14%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($prime,0,',',' '); ?></td>
        </tr>

        <tr>

          <td style="width: 10%;border:2px ; border-bottom: 0px;">--</td>

          <td style="width: 48%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Avance sur Salaire'.' '); ?></td>

          <td style="width: 14%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($accompte,0,',',' '); ?></td>
        </tr>

        <tr>

          <td style="width: 10%;border:2px ; border-bottom: 0px;">--</td>

          <td style="width: 48%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Cotisation Sociale'.' '); ?></td>

          <td style="width: 14%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($cotisation,0,',',' '); ?></td>
        </tr>

        <?php

        $total= $product['montant'];
        $Remise=0;

        $ttc = $total-$Remise;

        $tot_Rest = 0; ?>

      <tr>
        <td style="border:2px; padding-top: 50px;" class="space"></td>
        <td style="border:2px; padding-top: 50px;" class="space"></td>
        <td style="border:2px; padding-top: 50px;" class="space"></td>
      </tr>

        

        
        

      <tr>
        <td colspan="1" rowspan="5" style="padding: 1px; text-align: left; font-size:25px; border-right:0px;"></td>
      </tr>

      <tr>
        <td style="text-align: right;" class="no-border">HT </td>
        <td style="text-align:right; padding-right: 5px;"><?php echo number_format((($total))-0%(($total)),0,',',' ') ?></td>
      </tr>

      <tr>
        <td style="text-align: right;" class="no-border">TVA </td>
        <td style="text-align:right; padding-right: 5px;"><?php echo number_format(0%($total),0,',',' ') ?></td>
      </tr>

      <tr>
        <td style="text-align: right; margin-bottom: 5px" class="no-border">TTC </td>
        <td style="text-align:right; padding-right: 5px;"><?php echo number_format($total,0,',',' ') ?></td>
      </tr>

    </tbody>

  </table><?php
}



  // Pour imprimer une facture de payement des salaires des employés

  if (isset($_GET['payehemp'])) {

    $product=$DB->querys('SELECT id, numdec, matricule, mois, montant, heurep, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payenseignant WHERE numdec= ?', array($_GET['payehemp']));

    if (empty($product['montant'])) {
      $montantpaye=0;
    }else{
      $montantpaye=$product['montant'];
    }

    $prodac=$DB->querys('SELECT *FROM accompte WHERE matricule= ? and moischaine=? and anneescolaire=?', array($_GET['mat'], $_GET['mois'], $_SESSION['promo']));

    if (empty($prodac['montant'])) {
      $accompte=0;
    }else{
      $accompte=$prodac['montant'];
    }


    $prodsoc=$DB->querys('SELECT *FROM ssocialens WHERE numpers= ?', array($_GET['mat']));

    if (empty($prodsoc['montant'])) {
      $cotisation=0;
    }else{
      $cotisation=$prodsoc['montant'];
    }

    $prodpri=$DB->querys('SELECT *FROM prime WHERE numpersp= ? and promop=?', array($_GET['mat'], $_SESSION['promo']));

    if (empty($prodpri['montantp'])) {
      $prime=0;
    }else{
      $prime=$prodpri['montantp'];
    }?>


    <table style="margin-top: 30px; margin-left:30px; border-bottom: 0px;" class="border" >
      <thead>

        <tr>
          <th colspan="4">BULLETIN DE PAIE <?=strtoupper($_GET['mois']);?> </th>
        </tr>

        <tr>
          <th><?="Matricule:  " .$_GET['mat']; ?></th>
          <th colspan="3" style="font-size: 18px;"><?=$panier->nomEnseignant($_GET['mat']); ?></th>
        </tr>

        <tr>
          <th><?php echo "Paiement N°: " .$_GET['payehemp']; ?></th>
          <th><?php echo "Type de Paiement:  " .$_GET['type']; ?></th>
          <th colspan="2"><?php echo "Date de paiement:  " .$_GET['date']; ?></th>
        </tr>

        <tr>
          <th colspan="4"></th>
        </tr>

        <tr>
          <th style="width: 10%;" height="30">Heure(s)</th>
          <th style="width: 48%; text-align: center;">Désignation</th>
          <th style="width: 14%;">Montant</th>
        </tr>

      </thead>

      <tbody><?php
        $total=0;

        $salaire=$montantpaye-$prime+$accompte+$cotisation;?>

        <tr>

          <td style="width: 10%;border:2px ; border-bottom: 0px;"><?=$product['heurep'];?></td>

          <td style="width: 48%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Paiement Salaire'.' '); ?></td>

          <td style="width: 14%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($salaire,0,',',' '); ?></td>
        </tr>

        <tr>

          <td style="width: 10%;border:2px ; border-bottom: 0px;">--</td>

          <td style="width: 48%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Prime'.' '); ?></td>

          <td style="width: 14%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($prime,0,',',' '); ?></td>
        </tr>

        <tr>

          <td style="width: 10%;border:2px ; border-bottom: 0px;">--</td>

          <td style="width: 48%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Avance sur Salaire'.' '); ?></td>

          <td style="width: 14%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($accompte,0,',',' '); ?></td>
        </tr>

        <tr>

          <td style="width: 10%;border:2px ; border-bottom: 0px;">--</td>

          <td style="width: 48%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Cotisation Sociale'.' '); ?></td>

          <td style="width: 14%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($cotisation,0,',',' '); ?></td>
        </tr>

        <?php

        $total= $product['montant'];
        $Remise=0;

        $ttc = $total-$Remise;

        $tot_Rest = 0; ?>

      <tr>
        <td style="border:2px; padding-top: 50px;" class="space"></td>
        <td style="border:2px; padding-top: 50px;" class="space"></td>
        <td style="border:2px; padding-top: 50px;" class="space"></td>
      </tr>

        
        

      <tr>
        <td colspan="1" rowspan="5" style="padding: 1px; text-align: left; font-size:25px; border-right:0px;"></td>
      </tr>

      <tr>
        <td style="text-align: right;" class="no-border">HT </td>
        <td style="text-align:right; padding-right: 5px;"><?php echo number_format((($total))-0%(($total)),0,',',' ') ?></td>
      </tr>

      <tr>
        <td style="text-align: right;" class="no-border">TVA </td>
        <td style="text-align:right; padding-right: 5px;"><?php echo number_format(0%($total),0,',',' ') ?></td>
      </tr>

      <tr>
        <td style="text-align: right; margin-bottom: 5px" class="no-border">TTC </td>
        <td style="text-align:right; padding-right: 5px;"><?php echo number_format($total,0,',',' ') ?></td>
      </tr>

    </tbody>

  </table><?php
}


//Pour la gestion horaire

if (isset($_GET['horairemp'])) {
    $heuret=0;

    $prodpaye = $DB->query('SELECT id, numens, heuret, heured, DATE_FORMAT(datet, \'%d/%m/%Y\')AS datet FROM horairet WHERE numens = :mat and annees=:promo ORDER BY(datet)', array('mat'=> $_GET['horairemp'], 'promo'=>$_SESSION['promo']));?>

        
    <table class="tablistebul">
      <thead>

        <tr><th colspan="3" height="25">Heure(s) éffectuées de <?=$_GET['nomel'];?></th></tr>

        <tr>
          <th height="25">Jour</th>
          <th>H. de debut</th>
          <th>Nbre Heure(s)</th>
        </tr>

      </thead>

      <tbody><?php

        foreach ($prodpaye as $paye) {

          $heuret+=$paye->heuret; ?>

          <tr>

              <td style="text-align: center;"><?=$paye->datet;?> </td>

              <td style="text-align: right;"><?=$paye->heured;?></td>

              <td style="text-align: center;"><?=$paye->heuret;?> h</td>

              
          </tr><?php
      }?>

      <tr>
          <th colspan="2"></th>
          <th><?=$heuret;?> h</th>
      </tr>

    </tbody>
  </table><?php
}



  // Pour imprimer une facture de payement des salaires des employés

if (isset($_GET['heuretr'])) {

  $products=$DB->query('SELECT id, numens, heuret, heured, DATE_FORMAT(datet, \'%d/%m/%Y\')AS datet FROM horairet WHERE id= ?', array($_GET['heuretr']));?>


  <table style="margin:0px; font-size: 18px;color: black; background: white;" >


     <tr>
      <td style="padding-top: 15px; font-size: 18px;"><?php echo "Nom du personnel:  " .$_GET['nomel']; ?></td>
    </tr>

  </table>

  <table style="margin-top: 30px; margin-left:0px; border-bottom: 0px;" class="border">

    <tbody>

      <tr>
        <th style="width: 40%; text-align: center; border-bottom: 0px;" height="30">Jour(s) travaillé(s)</th>
        <th style="width: 30%; text-align: center; border-bottom: 0px;">Heure de debut</th>
        <th style="width: 30%; border-bottom: 0px; text-align: center;">Nbre d'heure(s)</th>
      </tr>

    </tbody>
  </table>


  <table style="margin-top: 0px; margin-left:0px;" class="border">

    <tbody><?php
      $heuret=0;

      foreach ($products as $product){

        $heuret+=$product->heuret; ?>

        <tr>

          <td style="width: 40%;border:2px;"><?=$product->datet;?></td>

          <td style="width: 30%;border:2px;text-align:center;"><?=$product->heured;?></td>

          <td style="text-align: center; width: 30%;border:2px; text-align:center; "><?=$product->heuret;?> h</td>

        </tr><?php
      }?>

      <tr>
        <th colspan="2">Total horaire</th>
        <th style="text-align: center;"><?=$heuret;?> h</th>
      </tr>       

    </tbody>

  </table><?php
}


//Pour la comptabilité générale

if (isset($_GET['synthesea']) or isset($_GET['synthesem']) or isset($_GET['synthesej']) or isset($_GET['syntheseg'])) {?>

  <table class="tablistebul">
    <thead>
      <tr><th colspan="4" height="25"><?='Situation générale '.$_GET['datenormale'];?></th></tr>

      <tr>
        <th height="25">Prestation</th>
        <th>Nbre</th>
        <th>Les Entrées</th>
        <th>Les Sorties</th>
      </tr>

    </thead>

    <tbody><?php

      $totins=0;
      $nbreins=0;

      $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE promo=?', array($_SESSION['promo']));

      foreach( $products as $product ) {

        $totins+= $product->montant;
        $nbreins+=$product->nbre;
        ?>

        <tr><?php
          if (empty($product->motif)) {
              # code...
          }else{?>
              <td height="20">Frais d'inscription/reinscription</td>

              <td style="text-align: center;"><?= $product->nbre; ?></td>

              <td style="text-align: right;"><?= number_format($product->montant,0,',',' '); ?></td>

              <td style="text-align: right;">-</td><?php
          }?>
        
        </tr><?php

      }


      $totfrais=0;
      $nbrefrais=0;

      $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(tranche) AS nbre, tranche FROM payementfraiscol WHERE promo=?', array($_SESSION['promo']));

      foreach( $products as $product ) {

        $totfrais+= $product->montant;
        $nbrefrais+=$product->nbre;
        ?>

        <tr><?php
          if (empty($product->tranche)) {
              # code...
          }else{?>
              <td height="20">Frais de scolarité</td>

              <td style="text-align: center;"><?= $product->nbre; ?></td>

              <td style="text-align: right;"><?= number_format($product->montant,0,',',' '); ?></td>

              <td style="text-align: right;">-</td><?php
          }?>
        
        </tr><?php

      }

      $totactivites=0;
      $nbreactivites=0;

      $prodactivites=$DB->querys("SELECT count(id) as nbre, sum(montantp) as montant FROM activitespaiehistorique  where anneep='{$_SESSION['promo']}'  ");

      $totactivites= $prodactivites['montant'];
      $nbreactivites=$prodactivites['nbre'];
      ?>

      <tr><?php
        if (empty($prodactivites['nbre'])) {
            # code...
        }else{?>
            <td height="20">Situation des activités</td>

            <td style="text-align: center;"><?= $prodactivites['nbre']; ?></td>

            <td style="text-align: right;"><?= number_format($prodactivites['montant'],0,',',' '); ?></td>

            <td style="text-align: right;">-</td><?php
        }?>
      
      </tr><?php

      $totversement=0;
      $nbreversement=0;

      $prodversement=$DB->querys("SELECT count(id) as nbre, sum(montant*taux) as montant FROM versement  where promo='{$_SESSION['promo']}'  ");

      $totversement= $prodversement['montant'];
      $nbreversement=$prodversement['nbre'];
      ?>

      <tr><?php
        if (empty($prodversement['nbre'])) {
            # code...
        }else{?>
            <td height="20">Recettes</td>

            <td style="text-align: center;"><?= $prodversement['nbre']; ?></td>

            <td style="text-align: right;"><?= number_format($prodversement['montant'],0,',',' '); ?></td>

            <td style="text-align: right;">-</td><?php
        }?>

      </tr><?php

      

      $totbib=0;
      $nbrebib=0;

      $prodlivre=$DB->query('SELECT SUM(totalp) AS montant, SUM(totalc) AS montantc, COUNT(id) AS nbre FROM payelivre WHERE DATE_FORMAT(datecmd, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datecmd, \'%Y%m%d\') <= :date2', array('date1' => $_GET['date1'], 'date2' => $_GET['date2']));

      foreach($prodlivre as $product ) {

        $totbib+= $product->montantc;
        $nbrebib+=$product->nbre;
        ?>

        <tr><?php
          if (empty($product->montantc)) {
              # code...
          }else{?>
              <td height="20">Bibliothèque</td>

              <td style="text-align: center;"><?= $product->nbre; ?></td>

              <td style="text-align: right;"><?= number_format($product->montantc,0,',',' '); ?></td>

              <td style="text-align: right;">-</td><?php
          }?>
        
        </tr><?php

      }

      $typedoc = array(
        "depenses" => "depense",
        "decaissement externe" => "decaissement externe"    
      );
  

      $sortie=0;
      $nbresortie=0;
      foreach ($panier->listeCategorie() as $keydec => $document){

        $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(id) AS nbre, motif FROM decaissement WHERE promo=? and motif=?', array($_SESSION['promo'], $document->id));


        foreach( $products as $product ) {

          $sortie+= $product->montant;
          $nbresortie+=$product->nbre;?>

          <tr><?php

            if (empty($product->motif)) {
                # code...
            }else{?>
                <td height="20"><?= ucfirst(strtolower($document->nom)); ?></td>
                <td style="text-align: center;"><?= $product->nbre;?></td>
                <td style="text-align: right;">-</td>
                <td style="text-align: right;"><?= number_format($product->montant,0,',',' '); ?></td><?php
            }?>
          
          </tr><?php

        }

      }

      $sortieac=0;
      $nbresortieac=0;

      $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM accompte WHERE anneescolaire=?', array($_SESSION['promo']));

              

      foreach( $products as $product ) {

          $sortieac+= $product->montant;
          $nbresortieac+=$product->nbre;
          $_SESSION['sortieac']=$sortieac;
          $_SESSION['nbresortieac']=$nbresortieac;?>

        <tr><?php
          if (empty($product->nbre)) {
              # code...
          }else{?>
              <td>Avance sur Salaire</td>
              <td style="text-align: center;"><?= $product->nbre;?></td>
              <td style="text-align: right;">-</td>
              <td style="text-align: right;"><?= number_format($product->montant,0,',',' '); ?></td><?php
          }?>
          
        </tr><?php

      }

      $sortiep=0;
      $nbresortiep=0;


      $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM payepersonnel WHERE promo=?', array($_SESSION['promo']));

              

      foreach( $products as $product ) {

          $sortiep+= $product->montant;
          $nbresortiep+=$product->nbre;
          $_SESSION['sortiep']=$sortiep;
          $_SESSION['nbresortiep']=$nbresortiep;?>

        <tr><?php
          if (empty($product->nbre)) {
              # code...
          }else{?>
              <td>Paiements des Personnels</td>
              <td style="text-align: center;"><?= $product->nbre;?></td>
              <td style="text-align: right;">-</td>
              <td style="text-align: right;"><?= number_format($product->montant,0,',',' '); ?></td><?php
          }?>
          
        </tr><?php

      }


      $sortiens=0;
      $nbresortiens=0;


      $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(id) AS nbre FROM payenseignant WHERE anneescolaire=?', array($_SESSION['promo']));

              

      foreach( $products as $product ) {

          $sortiens+= $product->montant;
          $nbresortiens+=$product->nbre;
          $_SESSION['sortiep']=$sortiens;
          $_SESSION['nbresortiep']=$nbresortiens;?>

        <tr><?php
          if (empty($product->nbre)) {
              # code...
          }else{?>
              <td>Payement des Enseignants</td>
              <td style="text-align: center;"><?= $product->nbre;?></td>
              <td style="text-align: right;">-</td>
              <td style="text-align: right;"><?= number_format($product->montant,0,',',' '); ?></td><?php
          }?>
          
        </tr><?php

      }?>

      
      </tbody><?php
      $nbretotal=$nbreins+$nbrefrais+$nbreactivites+$nbreversement+$nbrebib+$nbresortie+$nbresortieac+$nbresortiep+$nbresortiens;
      $totalcredit=$totins+$totfrais+$totactivites+$totversement+$totbib;
      $totaldebiter=$sortie+$sortieac+$sortiep+$sortiens;
      $solde=$totalcredit-$totaldebiter;?>
      <tfoot>

      <tr>
        <th class="legende" height="30">Total: </th>
        <th><?=$nbretotal;?></th>
        <th><?=number_format($totalcredit,0,',',' ');?></th>
        <th><?=number_format($totaldebiter,0,',',' ');?></th>
      </tr>

      <tr>
        <th height="30" colspan="2">Solde: </th><?php
        if ($solde>=0) {?>
            <th colspan="2" style="background-color: green; color: white"><?=number_format($solde,0,',',' ');?></th><?php
        }else{?>
            <th colspan="2" style="background-color: red; color: white"><?=number_format($solde,0,',',' ');?></th><?php
        }?>
      </tr>

    </tfoot>

  </table><?php

}




//Pour la liste des decouvert

if (isset($_GET['deca']) or isset($_GET['decm']) or isset($_GET['decg'])) {?>

  <table class="tablistel">
    <thead>
      <tr><th colspan="9" height="25"><?='Liste des créances '.$_GET['annee'];?></th></tr>

      <tr>
        <th>N°</th>
        <th height="20">N° mat</th>
        <th>Prénom & Nom</th>
        <th>S</th>
        <th>Né(e)</th>
        <th>Filiataion</th>
        <th>Payé</th>
        <th>Rem</th>
        <th>Reste</th>
      </tr>

    </thead>

    <tbody><?php
      $etat='actif';

        if (isset($_GET['deca'])) {

         $prodpaye =$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%d/%m/%Y\') AS naissance, codef, remise FROM eleve inner join inscription on inscription.matricule=eleve.matricule WHERE inscription.annee=:promoins and etatscol=:etat and inscription.matricule not in(SELECT matricule FROM payementfraiscol WHERE promo=:annee) order by(prenomel)', array('promoins'=>$_GET['deca'], 'etat'=>$etat, 'annee' => $_GET['deca']));

        }elseif (isset($_GET['decm'])) {
          
          $prodpaye =$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%d/%m/%Y\') AS naissance, codef, remise FROM eleve inner join inscription on inscription.matricule=eleve.matricule WHERE inscription.annee=:promoins and etatscol=:etat and inscription.matricule not in(SELECT matricule FROM payementfraiscol WHERE (montant>=:montant and promo=:annee and tranche=:mois)) order by(prenomel)', array('promoins'=>$_SESSION['annee'], 'etat'=>$etat, 'montant'=>$_SESSION['montantscol'], 'annee' => $_SESSION['annee'], 'mois'=>$_GET['decm']));

        }elseif (isset($_GET['decg'])) {
          
          $prodpaye =$DB->query('SELECT eleve.matricule as matricule, nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%d/%m/%Y\') AS naissance, codef, remise FROM eleve inner join inscription on eleve.matricule=inscription.matricule WHERE nomgr=:nom and inscription.annee=:promoins and etatscol=:etat and eleve.matricule not in(SELECT matricule FROM payementfraiscol WHERE(montant>=:montant and promo=:annee and tranche=:mois)) order by(prenomel)', array('promoins'=>$_SESSION['annee'], 'etat'=>$etat, 'montant'=>$_SESSION['montantscol'], 'annee' => $_SESSION['annee'], 'mois'=>$_SESSION['mensuellec'], 'nom'=>$_GET['decg']));
        }

        $reste1=0;
        $reste2=0;
        foreach($prodpaye as $key=>$payeloc ){

          $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['promo'], 'code'=>$payeloc->codef));

          //$prodscol = $DB->querys('SELECT montant FROM scolarite WHERE tranche=:mois and promo=:promo', array('mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['annee']));

          $montantscol=$prodscol['montant'];

          $prodcredit =$DB->query('SELECT sum(montant) as montant, remise FROM payementfraiscol inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE promo=:promo and annee=:promoins and payementfraiscol.matricule=:mat and tranche=:mois', array('promo'=>$_SESSION['annee'], 'promoins'=>$_SESSION['promo'], 'mat' => $payeloc->matricule, 'mois'=>$_SESSION['mensuellec']));

          $prodrem =$DB->querys('SELECT remise FROM inscription WHERE annee=:promoins and matricule=:mat', array('promoins'=>$_SESSION['promo'], 'mat' => $payeloc->matricule));

          if($payeloc->remise==100){

          }else{

            if (empty($prodcredit)) {

              $reste1+=$montantscol;?>

              <tr>

                <td style="text-align: center;" height="15"><?=$key+1;?></td>

                <td style="text-align: center;"><?=$payeloc->matricule;?></td>

                <td><?=ucfirst($payeloc->prenomel).' '.strtoupper($payeloc->nomel);?></td>

                <td style="text-align: center;"><?=strtoupper($payeloc->sexe);?></td>

                <td style="text-align: center;"><?=$payeloc->naissance;?></td>

                <td><?=ucwords($payeloc->pere).' et '.ucwords(strtolower($payeloc->mere));?></td>

                <td style="text-align: right;">0</td>

                <td style="text-align: center;">0%</td>

                <td style="text-align: right; color: red;"><?=number_format($montantscol,0,',',' ');?></td>

              </tr><?php
            }else{

              foreach ($prodcredit as $montant) {

                $resterem=$montantscol-(($montant->montant+(($prodrem['remise']/100)*$montantscol)));
                $reste2+=$resterem;

                if (!empty($resterem)) {?>

                  <tr>

                    <td style="text-align: center;" height="15"><?=$key+1;?></td>

                    <td style="text-align: center;"><?=$payeloc->matricule;?></td>

                    <td><?=ucfirst($payeloc->prenomel).' '.strtoupper($payeloc->nomel);?></td>

                    <td style="text-align: center;"><?=strtoupper($payeloc->sexe);?></td>

                    <td style="text-align: center;"><?=$payeloc->naissance;?></td>

                    <td><?=ucwords($payeloc->pere).' et '.ucwords(strtolower($payeloc->mere));?></td>

                    <td style="text-align: right;"><?=number_format($montant->montant,0,',',' ');?></td>

                    <td style="text-align: center;"><?=$prodrem['remise'];?>%</td>

                    <td style="text-align: right; color: red;"><?=number_format($resterem,0,',',' ');?></td>

                  </tr><?php 
                }
              }
            }
          }

      }?>

        

    </tbody>
    <tfoot>
        <tr>
            <th colspan="8">Reste à Payer</th>
            <th><?=number_format(($reste1+$reste2),0,',',' ');?></th>
        </tr>
    </tfoot>

  </table><?php

}

//Frais de scolarité


if (isset($_GET['scolarite'])) {

  $prodm=$DB->query('SELECT classe, nomf, montant, tranche, scolarite.id as id from scolarite inner join formation on scolarite.codef=formation.codef where scolarite.codef=:code and scolarite.promo=:promo',array('code'=>$_GET['codef'], 'promo'=>$_GET['scolarite']));?>

  <table class="tablistebul" style="margin-top: 60px; width: 80%;">
    <thead>
      <tr><th colspan="3" height="25"><?='Frais de scolarité'.' en '.$_GET['classe'].' '.$_GET['nomf'].' Année Scolaire '.($_GET['scolarite']-1).'-'.$_GET['scolarite'];?></th></tr>

      <tr>
        <th height="30">Classe</th>
        <th>Désignation</th>
        <th>Mensuel</th>
      </tr>

    </thead>

    <tbody><?php

      if (empty($prodm)) {
        $cumultranche=0;
      }else{

        $cumultranche=0;
        foreach ($prodm as $formation) {

          $cumultranche+=$formation->montant;?>

          <tr>
            <td height="30"><?php

              if ($formation->classe=='1') {?>

                <?=ucwords($formation->classe.'ère '.strtolower($formation->nomf));?><?php

              }elseif($formation->classe=='petite section' or $formation->classe=='moyenne section' or $formation->classe=='grande section' or $formation->classe=='terminale'){?>

                <?=ucwords($formation->classe.' '.strtolower($formation->nomf));

              }else{?>

                <?=ucwords($formation->classe.' ème '.strtolower($formation->nomf));?><?php
              }?>
            </td>

            <td style="text-align: center"><?=$formation->tranche;?></td>

            <td style="text-align: right"><?=number_format($formation->montant,0,',',' ');?></td>
          </tr><?php
        }
      }?>       

    </tbody>
    <tfoot>
      <tr>
        <th colspan="2" height="30">Total</th>
        <th style="text-align: right"><?=number_format($cumultranche,0,',',' ');?></th>
      </tr>
    </tfoot>

  </table><?php

}


//impression matière


if (isset($_GET['printmat'])) {

  $prodm=$DB->query('SELECT  codem, nommat, coef,  nbre_heure as heure from matiere where matiere.codef=:code order by(matiere.id)', array('code'=>$_GET['printmat']));

  $prodf=$DB->querys('SELECT  nomf from formation where codef=:code', array('code'=>$_GET['printmat']));?>

  <table class="tablistebul" style="margin-top: 60px; width: 80%;">
    <thead>
      <tr><th colspan="4" height="25"><?='Liste des matières en '.ucfirst($prodf['nomf']);?></th></tr>

      <tr class="active">
        <th height="25">Code mat</th>
        <th>Liste des Matières</th>
        <th>Coef</th>
        <th>Heure(s)</th>
      </tr>

    </thead>

    <tbody><?php

      if (empty($prodm)) {
        # code...
      }else{

        foreach ($prodm as $formation) {?>

          <tr>
            <td><?=$formation->codem;?></td>

            <td><?=ucwords($formation->nommat);?></td>

            <td style="text-align: center;"><?=$formation->coef;?></td>

            <td style="text-align: center;"><?=$formation->heure;?>h</td>

          </tr><?php
        }
      }?>       

    </tbody>

  </table><?php

}


// Courrier

if (isset($_GET['courrier'])) {?>

  <div style="width:700px; margin-left: 50px;">

    <p>Chers Parents,</p>

    <p>Sauf erreur de notre part, votre fils/fille <strong><?=$_GET['nom'];?></strong>, né(e) le <strong><?=$_GET['annee'];?></strong> inscrit(e) en <strong><?=$_GET['inscrit'];?></strong> n'est pas à jour dans ses frais de scolarité concernant la <strong><?=$_GET['tranche'];?></strong>. La date limite de payement de la <strong><?=$_GET['tranche'];?></strong> était le <strong><?=$_GET['date'];?></strong></p>

    <p><strong>Montant de la Tranche..............<?=number_format($_GET['montantap'],0,',',' ');?></strong></p>

    
    <p><strong>Montant Payé.............................. <?=number_format($_GET['montantp'],0,',',' ');?></strong></p><?php

    if ($_GET['remise']>0) {?>
      <p><strong>Remise...........................................<?=$_GET['remise'];?>%</strong></p><?php
    }?>    

    <p><strong>Reste à Payer............................. <?=number_format($_GET['montantap']*(1-($_GET['remise']/100))-$_GET['montantp'],0,',',' ');?></strong></p>

    <p>Merci de faire le nécessaire pour régulariser sa situation.</p>

    <p>Cordialement,</p>
  </div>


        
    <?php
  }

  if (isset($_GET['remiseins'])) {

    $prodremise = $DB->query('SELECT payement.matricule as matricule, montant, payement.remise as remise, nomgr, nomel, prenomel FROM payement inner join inscription on inscription.matricule=payement.matricule inner join eleve on eleve.matricule=inscription.matricule WHERE payement.remise!= :mat and promo=:promo ORDER BY(prenomel) DESC', array('mat'=> 0, 'promo'=>$_GET['promo']));

    if (!empty($prodremise)) {?>

      <table class="tablistel" style="margin-left: 30px; margin-top:30px;">
        <thead>
          <tr>                  
            <th colspan="6" class="info" style="text-align: center">Liste des <?=$_SESSION['typeel'];?> ayant obtenus une remise sur les frais d'inscriptions/reinscriptions</th>
         </tr>
                      
        <tr>
          <th></th>
          <th>Matricule</th>
          <th>Prénom & Nom</th>
          <th>Classe</th>
          <th>Remise</th>
          <th>Montant Payé</th>
        </tr>
      </thead>

      <tbody><?php

        $totremise=0; 

        foreach ($prodremise as $key => $valuer) {

          $totremise+=$valuer->montant;?>

          <tr>
            <td style="text-align:center;"><?=$key+1;?></td>
            <td style="text-align:center;"><?=$valuer->matricule;?></td>
            <td><?=ucfirst($valuer->prenomel).' '.ucwords($valuer->nomel);?></td>
            <td style="text-align:center;"><?=$valuer->nomgr;?></td>
            <td style="text-align:center;"><?=$valuer->remise;?>%</td>
            <td style="text-align:right; padding-right: 5px;"><?=number_format($valuer->montant,0,',',' ');?></td>
          </tr><?php
        }?>

      </tbody>

      <tfoot>
        <tr>
          <th colspan="5">Total</th>
          <th style="text-align:right; padding-right: 5px;"><?=number_format($totremise,0,',',' ');?></th>
        </tr>
      </tfoot>
    </table><?php 
  }
}

if (isset($_GET['remisescol'])) {

  $prodremise = $DB->query('SELECT inscription.matricule as matricule, remise as remise, nomgr, nomel, prenomel FROM inscription  inner join eleve on eleve.matricule=inscription.matricule WHERE remise> :mat and annee=:promo ORDER BY(prenomel) DESC', array('mat'=> 99, 'promo'=>$_GET['promo']));

  if (!empty($prodremise)) {?>

      <table class="tablistel" style="margin-left: 30px; margin-top:30px;">
        <thead>
          <tr>                  
            <th colspan="5" class="info" style="text-align: center">Liste des <?=$_SESSION['typeel'];?> ayant obtenus une remise pour les frais de scolarité</th>
         </tr>
                      
        <tr>
          <th></th>
          <th>Matricule</th>
          <th>Prénom & Nom</th>
          <th>Classe</th>
          <th>Remise</th>
        </tr>
      </thead>

      <tbody><?php

        $totremise=0; 

        foreach ($prodremise as $key => $valuer) {

          //$totremise+=$valuer->montant;?>

          <tr>
            <td style="text-align:center;"><?=$key+1;?></td>
            <td style="text-align:center;"><?=$valuer->matricule;?></td>
            <td><?=ucfirst($valuer->prenomel).' '.ucwords($valuer->nomel);?></td>
            <td style="text-align:center;"><?=$valuer->nomgr;?></td>
            <td style="text-align:center;"><?=$valuer->remise;?>%</td>
          </tr><?php
        }?>

      </tbody>
    </table><?php 
  }

    $prodremise = $DB->query('SELECT inscription.matricule as matricule, remise as remise, nomgr, nomel, prenomel FROM inscription  inner join eleve on eleve.matricule=inscription.matricule WHERE remise<= :mat and remise!=:mat1 and annee=:promo ORDER BY(prenomel) DESC', array('mat'=> 99, 'mat1'=>0, 'promo'=>$_GET['promo']));

    if (!empty($prodremise)) {?>

      <table class="tablistel" style="margin-left: 30px; margin-top:30px;">
              <thead>
                <tr>                  
                  <th colspan="5" class="info" style="text-align: center">Liste des <?=$_SESSION['typeel'];?> ayant obtenus une remise sur les frais de scolarité</th>
               </tr>
                            
              <tr>
                <th></th>
                <th>Matricule</th>
                <th>Prénom & Nom</th>
                <th>Classe</th>
                <th>Remise</th>
              </tr>
            </thead>

            <tbody><?php

              $totremise=0; 

              foreach ($prodremise as $key => $valuer) {

                //$totremise+=$valuer->montant;?>

                <tr>
                  <td style="text-align:center;"><?=$key+1;?></td>
                  <td style="text-align:center;"><?=$valuer->matricule;?></td>
                  <td><?=ucfirst($valuer->prenomel).' '.ucwords($valuer->nomel);?></td>
                  <td style="text-align:center;"><?=$valuer->nomgr;?></td>
                  <td style="text-align:center;"><?=$valuer->remise;?>%</td>
                </tr><?php
              }?>

            </tbody>
          </table><?php 
  }
}

if (isset($_GET['printdep'])) {

  $prodm=$DB->query('SELECT id, numdec, montant, coment, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye from decaissement where DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and motif=:motif and promo=:promo order by(id)desc', array('date1' => $_GET['date1'], 'date2' => $_GET['date2'], 'motif'=>$_GET['type'], 'promo'=>$_SESSION['promo']));?>

  <table class="tablistel" style="margin-left: 30px; margin-top:30px;">
    <thead>
      <tr>                  
        <th colspan="4" class="info" style="text-align: center">Liste des <?=ucwords($_GET['type']);?></th>
      </tr>

      <tr>
        <th>Motif</th>
        <th>Montant</th>
        <th>Paiement</th>
        <th>Date</th>
      </tr>

    </thead>

    <tbody><?php
      $totdep=0;
      if (empty($prodm)) {
        # code...
      }else{

        foreach ($prodm as $formation) {

          $totdep+=$formation->montant;?>

          <tr>                

            <td><?=ucfirst(strtolower($formation->coment));?></td>

            <td style="text-align: right"><?=number_format($formation->montant,0,',',' ');?></td>

            <td style="text-align: center"><?=$formation->typepaye;?></td>

            <td style="text-align: center"><?=$formation->datepaye;?></td>

          </tr><?php
        }
      }?>          
    </tbody>

    <tfoot>
      <tr>
        <th>Totaux</th>
        <th colspan="2"><?=number_format($totdep,0,',',' ');?></th>
      </tr>
    </tfoot>
  </table><?php

}




  require 'signature.php';

  
  $content = ob_get_clean();
  try {
    $pdf = new HTML2PDF("p","A4","fr", true, "UTF-8" , 0);
    $pdf->pdf->SetAuthor('Amadou');
    $pdf->pdf->SetTitle(date("d/m/y"));
    $pdf->pdf->SetSubject('Création d\'un Portfolio');
    $pdf->pdf->SetKeywords('HTML2PDF, Synthese, PHP');
    //$pdf->pdf->IncludeJS("print(true);");
    $pdf->writeHTML($content);
    $pdf->Output('document'.date("d/m/y").date("H:i:s").'.pdf');
    // $pdf->Output('Devis.pdf', 'D');    
  } catch (HTML2PDF_exception $e) {
    die($e);
  }
//header("Refresh: 10; URL=index.php");
?>


