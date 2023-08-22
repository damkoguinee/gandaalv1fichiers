<?php
require_once "lib/html2pdf.php";

ob_start(); ?>

<?php require '_header.php';?>

<style type="text/css">

body{
  margin: 0px;
  width: 100%;
  height:68%;
  padding:0px;
}
  .entete{
    width: 100%;

  }

  .pied{
    text-align: center;
    margin-top: 40px;
    margin-right: 80px;
    font-size: 30px;
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
    font-size: 18px;
  }

  table.tablistebul{
    width: 100%;
    margin-left: 30px;
    border-collapse: collapse;
  }

  .tablistebul th {
    line-height: 7mm;
    border: 2px solid black;
    font-size: 25px;
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
    font-size: 25px;
  }

  .info{
    text-align: left;
    border-bottom: 0px solid black;
    font-size: 20px;

  }


  table.border {
    width: 100%;
    color: #717375;
    font-family: helvetica;
    line-height: 10mm;
    border-collapse: collapse;
  }


  .border th {
    border: 2px solid black;
    border-style: dotted;
    padding: 0px;
    font-weight: bold;
    font-size: 25px;
    color: black;
    background: white;
    text-align: right; }
  .border td {
    line-height: 15mm;
    border: 2px solid black;
    border-style: dotted;    
    font-size: 25px;
    color: black;
    background: white;
    text-align: center;
  }

  label {
    float: right;
    font-size: 25px;
    font-weight: bold;
    width: 200px;
  }

  ol{
    list-style: none;
  }
</style>
<page backtop="5mm" backleft="5mm" backright="5mm" backbottom="10mm" footer="page;"><?php

  require 'entete_mini.php';

  if (isset($_GET['enseig'])) {

    $prodm=$DB->query('SELECT  *from enseignant inner join contact on enseignant.matricule=contact.matricule');?>
      
    <table class="tablistebul" style="width: 90%;">
      <thead>
        <tr><th colspan="3">Liste des enseignants</th></tr>

        <tr>
          <th height="25">Nom & Prénom</th>
          <th>Matricule</th>
          <th>Téléphone</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
          # code...
        }else{

          foreach ($prodm as $formation) {?>

            <tr>

              <td height="20"><?=strtoupper($formation->nomen).' '.ucwords(strtolower($formation->prenomen));?></td>

              <td><?=$formation->matricule;?></td>

              <td><?=$formation->phone;?></td>

            </tr><?php
          }
        }?>
      </tbody>
    </table><?php
  }


  if (isset($_GET['perso'])) {

    $prodm=$DB->query('SELECT  *from personnel inner join contact on numpers=contact.matricule inner join login on login.matricule=numpers');?>
      
    <table class="tablistebul" style="width: 90%;">
      <thead>
        <tr><th colspan="4">Liste des enseignants</th></tr>

        <tr>
          <th height="30">Nom & Prénom</th>
          <th>Fonction</th>
          <th>Adresse</th>
          <th>Phone</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
          # code...
        }else{

          foreach ($prodm as $formation) {?>

            <tr>

              <td height="20"><?=strtoupper($formation->nom).' '.ucwords(strtolower($formation->prenom));?></td>

              <td><?=ucfirst($formation->type);?></td>

              <td><?=$formation->adresse;?></td>

              <td><?=$formation->phone;?></td>

            </tr><?php
          }
        }?>
      </tbody>
    </table><?php
  }


  if (isset($_GET['groupe'])) {

    $prodm=$DB->query('SELECT groupe.id as id, nomgr, nomf, classe, nomen, prenomen, profcoor  from groupe inner join formation on formation.codef=groupe.codef inner join enseignant on groupe.profcoor=enseignant.matricule where promo=:promo', array('promo'=>$_SESSION['promo']));?>

        
    <table class="tablistebul" style="width: 90%;">

      <thead>

        <tr><th colspan="3">Liste des Groupes</th></tr>

        <tr>
          <th height="25">Groupe</th>
          <th>Formation</th>
          <th>Coordinateur</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
      # code...
        }else{

          foreach ($prodm as $formation) {?>

            <tr>
              <td height="20"><?=$formation->nomgr;?></td><?php

              if ($formation->classe=='terminale') {?>

                <td><?=' '.$formation->classe.' '.$formation->nomf;?></td><?php

              }else{?>

                <td><?=' '.$formation->classe.'ème Année '.$formation->nomf;?></td><?php
              }?>

              <td><?=ucwords($formation->nomen.' '.$formation->prenomen);?></td>
            </tr><?php
          }

        }?>
      </tbody>
    </table><?php
  }

  // Afficher la liste des élèves

  if (isset($_GET['listel'])) {

    $prodeleve=$DB->query('SELECT nomel, prenomel, adresse, DATE_FORMAT(naissance, \'%Y\')AS naissance, inscription.matricule as matricule, nomgr, classe, nomf from eleve inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef where annee=:promo order by (nomel)', array('promo'=>$_SESSION['promo']));?>

        
    <table class="tablistebul">

      <thead>

        <tr><th colspan="6">Liste des étudiants inscrits en <?=$_SESSION['promo'];?></th></tr>

        <tr>
          <th height="25">N°</th>
          <th>Nom & Prénom</th>
          <th>Né(e)</th>
          <th>Lieu N</th>
          <th>Groupe</th>
          <th>Filière</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodeleve)) {

        }else{

          foreach ($prodeleve as $eleve) {?>

            <tr>
              <td height="20"><?=$eleve->matricule;?></td>

              <td><?=strtoupper($eleve->nomel).' '.ucfirst(strtolower($eleve->prenomel));?></td>

              <td><?=$eleve->naissance;?></td>

              <td height="20"><?=$eleve->adresse;?></td>

              <td height="20"><?=$eleve->nomgr;?></td>

              <td height="20"><?=$eleve->classe.' '.$eleve->nomf;?></td>

            </tr><?php
          }

        }?>
      </tbody>
    </table><?php
  }

  // Afficher la liste des élèves d'une formation

  if (isset($_GET['voir_e'])) {

    $prodm=$DB->query('SELECT  nomel, prenomel, adresse, DATE_FORMAT(naissance, \'%Y\')AS naissance, inscription.matricule as matricule, nomgr, classe, nomf from eleve inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef where inscription.codef=:code and annee=:promo order by(eleve.nomel)', array('code'=>$_GET['voir_e'], 'promo'=>$_SESSION['promo']));

    $prodf=$DB->querys('SELECT nomf from formation  where codef=:code', array('code'=>$_GET['voir_e']));?>

        
    <table class="tablistebul">

      <thead>

        <tr><th colspan="6">Liste des étudiants d(e) <?=$prodf['nomf'];?></th></tr>

        <tr>
          <th height="25">Groupe</th>
          <th>Nom</th>
          <th>Matricule</th>
          <th>Né(e)</th>
          <th>Lieu N</th>
          <th>Filière</th>         
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
      # code...
        }else{

          foreach ($prodm as $formation) {?>

            <tr>
              <td height="20"><?=$formation->nomgr;?></td>              

              <td><?=strtoupper($formation->nomel).' '.ucfirst(strtolower($formation->prenomel));?></td>
              <td height="20"><?=$formation->matricule;?></td>
              <td height="20"><?=$formation->naissance;?></td>
              <td height="20"><?=$formation->adresse;?></td>
              <td height="20"><?=$formation->classe.' '.$formation->nomf;?></td>
            </tr><?php
          }

        }?>
      </tbody>
    </table><?php
  }

  //Afficher les élèves d'un groupe


  if (isset($_GET['voir_elg'])) {

    $prodm=$DB->query('SELECT  eleve.matricule as matricule, nomel, prenomel, nomgr from inscription inner join eleve on eleve.matricule=inscription.matricule where inscription.nomgr=:code and annee=:promo', array('code'=>$_GET['voir_elg'], 'promo'=>$_SESSION['promo']));

    $prodf=$DB->querys('SELECT nomgr from groupe  where nomgr=:code', array('code'=>$_GET['voir_elg']));?>

        
    <table class="tablistebul">

      <thead>

        <tr><th colspan="3">Liste des élèves du <?=$prodf['nomgr'];?></th></tr>

        <tr>
          <th height="25">Groupe</th>
          <th>N° élève</th>
          <th>Elèves</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
      # code...
        }else{

          foreach ($prodm as $formation) {?>

            <tr>
              <td height="20"><?=$formation->nomgr;?></td>

              <td height="20"><?=$formation->matricule;?></td>

              <td><?=strtoupper($formation->nomel).' '.ucfirst(strtolower($formation->prenomel));?></td>
            </tr><?php
          }

        }?>
      </tbody>
    </table><?php
  }


  // Afficher la liste des enseignements


  if (isset($_GET['enseigne'])) {

    $prodm=$DB->query('SELECT  enseignement.id as id, groupe.nomgr as nomgr, nomf, nommat, nomen, prenomen, codens, matiere.codem as codem, groupe.nomgr as nomgr from enseignement inner join groupe on enseignement.nomgr=groupe.nomgr inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule inner join formation on enseignement.codef=formation.codef where enseignement.promo=:promo order by(prenomen)',array('promo'=>$_SESSION['promo']));?>

        
    <table class="tablistebul">

      <thead>

        <tr><th colspan="4">Liste des enseignements</th></tr>

        <tr>          
          <th height="25">Professeur</th>
          <th>Matière</th>
          <th>Groupe</th>
          <th>Formation</th>
        </tr>

      </thead>

      <tbody><?php

        if (empty($prodm)) {
      # code...
        }else{

          foreach ($prodm as $formation) {?>

            <tr>

              <td height="20"><?=strtoupper($formation->nomen).' '.ucfirst(strtolower($formation->prenomen));?></td>

              <td><?=ucwords($formation->nommat);?></td>

              <td><?=$formation->nomgr;?></td>

              <td><?=ucwords($formation->nomf);?></td>

            </tr><?php
          }

        }?>

      </tbody>
    </table><?php
  }

  //Pour imprimer les frais de scolarités


  if (isset($_GET['scoltot'])) {
    $montant=0;
    $prodpaye = $DB->query('SELECT montant, tranche, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payementfraiscol WHERE matricule = :mat and promo=:promo ORDER BY(tranche)', array('mat'=> $_GET['scoltot'], 'promo'=>$_SESSION['promo']));?>


    

        
    <table class="tablistebul">
      <thead>

        <tr><th colspan="3">Frais de scolarité de <?=$_GET['nomel'];?></th></tr>

        <tr><th colspan="3" class="info">Né(e) en <?=$_GET['daten'];?></th></tr>
        <tr><th colspan="3" class="info">Tel: <?=$_GET['phone'];?></th></tr>
        <tr><th colspan="3" class="info">Inscrit(e) en <?=$_GET['inscrit'];?></th></tr>
        <tr><th colspan="3" class="info" style="text-align: left; border-bottom: 2px;">Groupe <?=$_GET['groupel'];?></th></tr>

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


  if (isset($_GET['histscol'])) {
    $montant=0;
    $prodpaye = $DB->query('SELECT montant, tranche, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM histopayefrais WHERE matricule = :mat and promo=:promo ORDER BY(tranche)', array('mat'=> $_GET['histscol'], 'promo'=>$_SESSION['promo']));?>

        
    <table class="tablistebul">
      <thead>

        <tr><th colspan="3">Historique des frais de scolarité de <?=$_GET['nomel'];?></th></tr>

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

              <td style="text-align: right; padding: 10px;"><?=number_format($product->montant,2,',',' ');?></td>

              <td><?='Payé le '.$product->datepaye;?></td>

            </tr><?php
          }?>

            <tr>

              <th style="padding: 10px; text-align: right;" colspan="2">Total payé : <?=number_format($montant,2,',',' ');?></th>

              <th style="color: red;"></th>

            </tr><?php

        }?>

      </tbody>
    </table><?php
  }

  // Pour imprimer une facture de payement de frais de scolarité

  if (isset($_GET['numfac'])) {

    $products=$DB->query('SELECT *FROM payementfraiscol WHERE payementfraiscol.numpaye= ?', array($_GET['numfac']));?>


    <table style="margin:0px; font-size: 25px;color: black; background: white;" >

      <tr>
        <td><strong><?php echo "Facture N°: " .$_GET['numfac']; ?></strong></td>
      </tr>      

      <tr>
        <td><?php echo "Payement:  " .$_GET['type']; ?></td>
      </tr>

      <tr>
        <td><?php echo "Date de payement:  " .$_GET['date']; ?></td>
      </tr>

       <tr>
        <td style="padding-top: 15px;"><?php echo "Nom de l'élève:  " .$_GET['nomel']; ?></td>
      </tr>

      <tr><td>Né(e) en <?=$_GET['daten'];?></td></tr>
      <tr><td>Tel: <?=$_GET['phone'];?></td></tr>
      <tr><td>Inscrit(e) en <?=$_GET['inscrit'];?></td></tr>
      <tr><td>Groupe <?=$_GET['groupel'];?></td></tr>

    </table>

    <table style="margin-top: 30px; margin-left:0px; border-bottom: 0px;" class="border">

      <tbody>

        <tr>
          <th style="width: 10%; border-bottom: 0px;" height="30">Qtite</th>
          <th style="width: 62%; text-align: left; border-bottom: 0px;">Désignation</th>
          <th style="width: 28%; border-bottom: 0px;">Montant</th>
        </tr>

      </tbody>
    </table>


    <table style="margin-top: 0px; margin-left:0px;" class="border">

        <tbody><?php
          $total=0;

          foreach ($products as $product){?>

            <tr>

              <td style="width: 10%;border:2px ; border-bottom: 0px;">1</td>

              <td style="width: 62%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Frais de scolarité pour la'.' '.$product->tranche); ?></td>

              <td style="width: 28%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($product->montant,2,',',' '); ?></td><?php

              $price=($product->montant*1);

              $total += $price;?>

            </tr><?php
          }

          //$montantverse=$payement->montant;

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


  //Pour imprimer les salaires payés en tot


  if (isset($_GET['paytotemp'])) {
    $montant=0;
    $heuret=0;

    $prodpaye = $DB->query('SELECT id, numdec, matricule, montant, heurep, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payenseignant WHERE matricule = :mat and anneescolaire=:promo ORDER BY(datepaye)', array('mat'=> $_GET['paytotemp'], 'promo'=>$_SESSION['promo']));?>

        
    <table class="border">
      <thead>

        <tr><th colspan="3">Salaires payés de <?=$_GET['nomel'];?></th></tr>

        <tr>
          <th height="25">Heure(s)</th>
          <th>Montant</th>
          <th style="text-align: center;">Date paye</th>
      </tr>

      </thead>

      <tbody><?php

        foreach ($prodpaye as $paye) {

          $montant+=$paye->montant;
          $heuret+=$paye->heurep; ?>

          <tr>

            <td style="text-align: center;" height="25"><?=$paye->heurep;?> h</td>

            <td style="text-align: right;"><?=number_format($paye->montant,0,',',' ');?></td>

            <td><?='Payé le '.$paye->datepaye;?></td>
          </tr><?php
        }?>

        <tr>
          <th><?=$heuret;?> h</th>
          <th style="text-align: right;"><?=number_format($montant,0,',',' ');?></th>
          <th></th>
      </tr>

    </tbody>
  </table><?php
}



  // Pour imprimer une facture de payement des salaires des employés

  if (isset($_GET['payepersfact'])) {

    $products=$DB->query('SELECT id, numdec, montant, mois, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payepersonnel WHERE numdec= ?', array($_GET['payepersfact']));?>


    <table style="margin:0px; font-size: 25px;color: black; background: white;" >

      <tr>
        <td><strong><?php echo "Facture N°: " .$_GET['payepersfact']; ?></strong></td>
      </tr>      

      <tr>
        <td><?php echo "Payement:  " .$_GET['type']; ?></td>
      </tr>

      <tr>
        <td><?php echo "Date de payement:  " .$_GET['date']; ?></td>
      </tr>

       <tr>
        <td style="padding-top: 15px; font-size: 25px;"><?php echo "Nom du personnel:  " .$_GET['nomel']; ?></td>
      </tr>

    </table>

    <table style="margin-top: 30px; margin-left:0px; border-bottom: 0px;" class="border">

      <tbody>

        <tr>
          <th style="width: 20%; border-bottom: 0px; text-align: center;" height="30">Mois</th>
          <th style="width: 38%; text-align: left; border-bottom: 0px;">Désignation</th>
          <th style="width: 14%; border-bottom: 0px;">Montant</th>
        </tr>

      </tbody>
    </table>


    <table style="margin-top: 0px; margin-left:0px;" class="border">

      <tbody><?php
        $total=0;

        foreach ($products as $product){

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
            }?>

          <tr>

            <td style="width: 20%;border:2px ; border-bottom: 0px; text-align: left;"><?=$mois;?></td>

            <td style="width: 38%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Payement de salaire'.' '); ?></td>

            <td style="width: 14%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($product->montant,0,',',' '); ?></td><?php

            $price=($product->montant*1);

            $total += $price;?>

          </tr><?php
        }

        //$montantverse=$payement->montant;

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


if (isset($_GET['paytotpers'])) {
    $montant=0;
    $prodpaye = $DB->query('SELECT montant, mois, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payepersonnel WHERE matricule = :mat and promo=:promo ORDER BY(mois)', array('mat'=> $_GET['paytotpers'],  'promo'=>$_SESSION['promo']));?>

        
    <table class="border">
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

  if (isset($_GET['payehemp'])) {

    $products=$DB->query('SELECT id, numdec, matricule, montant, heurep, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM payenseignant WHERE numdec= ?', array($_GET['payehemp']));?>


    <table style="margin:0px; font-size: 25px;color: black; background: white;" >

      <tr>
        <td><strong><?php echo "Facture N°: " .$_GET['payehemp']; ?></strong></td>
      </tr>      

      <tr>
        <td><?php echo "Payement:  " .$_GET['type']; ?></td>
      </tr>

      <tr>
        <td><?php echo "Date de payement:  " .$_GET['date']; ?></td>
      </tr>

       <tr>
        <td style="padding-top: 15px; font-size: 25px;"><?php echo "Nom du personnel:  " .$_GET['nomel']; ?></td>
      </tr>

    </table>

    <table style="margin-top: 30px; margin-left:0px; border-bottom: 0px;" class="border">

      <tbody>

        <tr>
          <th style="width: 10%; border-bottom: 0px;" height="30">Heure(s)</th>
          <th style="width: 48%; text-align: left; border-bottom: 0px;">Désignation</th>
          <th style="width: 14%; border-bottom: 0px;">Montant</th>
        </tr>

      </tbody>
    </table>


    <table style="margin-top: 0px; margin-left:0px;" class="border">

      <tbody><?php
        $total=0;

        foreach ($products as $product){?>

          <tr>

            <td style="width: 10%;border:2px ; border-bottom: 0px;"><?=$product->heurep;?></td>

            <td style="width: 48%;border:2px;text-align:left; border-bottom: 0px;"><?=ucfirst('Payement de salaire'.' '); ?></td>

            <td style="width: 14%;border:2px; text-align:right; border-bottom: 0px;"><?=number_format($product->montant,0,',',' '); ?></td><?php

            $price=($product->montant*1);

            $total += $price;?>

          </tr><?php
        }

        //$montantverse=$payement->montant;

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


  <table style="margin:0px; font-size: 25px;color: black; background: white;" >


     <tr>
      <td style="padding-top: 15px; font-size: 25px;"><?php echo "Nom du personnel:  " .$_GET['nomel']; ?></td>
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

if (isset($_GET['synthesea']) or isset($_GET['synthesem']) or isset($_GET['synthesej'])) {?>

  <table class="tablistebul">
    <thead>
      <tr><th colspan="4" height="25"><?='Comptabilité générale du '.$_GET['annee'];?></th></tr>

      <tr>
        <th height="25">Prestation</th>
        <th>Nbre</th>
        <th>Entrer</th>
        <th>Sorties</th>
      </tr>

    </thead>

    <tbody><?php

      $typedoc = array(
        "Frais de scolarite" => "mensualite",
        "Frais d'inscription" => "inscription"       
      );

      $tot=0;
      $nbre=0;
      foreach ($typedoc as $keymen=> $document){

        if (isset($_GET['synthesea'])) {

          $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE motif=:type AND DATE_FORMAT(datepaye, \'%Y\')=:annee', array('type'=>$document,'annee' => $_GET['synthesea']));

        }elseif (isset($_GET['synthesem'])) {
          
          $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE motif=:type AND  DATE_FORMAT(datepaye, \'%m/%Y\')=:annee', array('type'=>$document,'annee' => $_GET['synthesem']));

        }elseif (isset($_GET['synthesej'])) {
          
          $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(motif) AS nbre, motif FROM payement WHERE motif=:type AND DATE_FORMAT(datepaye, \'%Y-%m-%d\')=:annee', array('type'=>$document,'annee' => $_GET['synthesej']));
        }

        foreach( $products as $product ) {

          $tot+= $product->montant;
          $nbre+=$product->nbre;
          ?>

          <tr><?php
            if (empty($product->motif)) {
                # code...
            }else{?>
                <td height="20"><?= ucfirst(strtolower($keymen)); ?></td>

                <td style="text-align: center;"><?= $product->nbre; ?></td>

                <td style="text-align: right;"><?= number_format($product->montant,0,',',' '); ?></td>

                <td style="text-align: right;">-</td><?php
            }?>
          
          </tr><?php

        }
      }

      $typedoc = array(

        "payements employer" => "payementem",
        "payements personnel" => "payementpers",
        "depenses" => "depense",
        "Banque" => "versement banque"     
      );
  

      $sortie=0;
      $nbresortie=0;
      foreach ($typedoc as $keydec => $document){

        if (isset($_GET['synthesea'])) {

          $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(id) AS nbre, motif FROM decaissement WHERE motif=:type AND DATE_FORMAT(datepaye, \'%Y\')=:annee', array('type'=>$document, 'annee' => $_GET['synthesea']));

        }elseif (isset($_GET['synthesem'])) {

          $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(id) AS nbre, motif FROM decaissement WHERE motif=:type AND DATE_FORMAT(datepaye, \'%m/%Y\')=:annee', array('type'=>$document, 'annee' => $_GET['synthesem']));

        }elseif (isset($_GET['synthesej'])) {

          $products =$DB->query('SELECT SUM(montant) AS montant, COUNT(id) AS nbre, motif FROM decaissement WHERE motif=:type AND DATE_FORMAT(datepaye, \'%Y-%m-%d\')=:annee', array('type'=>$document, 'annee' => $_GET['synthesej']));

        }


        foreach( $products as $product ) {

          $sortie+= $product->montant;
          $nbresortie+=$product->nbre;?>

          <tr><?php

            if (empty($product->motif)) {
                # code...
            }else{?>
                <td height="20"><?= ucfirst(strtolower($keydec)); ?></td>
                <td style="text-align: center;"><?= $product->nbre;?></td>
                <td style="text-align: right;">-</td>
                <td style="text-align: right;"><?= number_format($product->montant,0,',',' '); ?></td><?php
            }?>
          
          </tr><?php

        }

      }?>

    </tbody><?php
    $nbretotal=$nbre+$nbresortie;
    $totalcredit=$tot;
    $totaldebiter=$sortie;
    $solde=$totalcredit-$totaldebiter; ?>
    <tfoot>

      <tr>
        <th class="legende" height="30">Total: </th>
        <th><?=$nbretotal;?></th>
        <th><?=number_format($totalcredit,0,',',' ');?></th>
        <th><?=number_format($totaldebiter,0,',',' ');?></th>
      </tr>

      <tr>
        <th height="30">Solde: </th>
        <th></th><?php
        if ($solde>=0) {?>
            <th colspan="2" style="background-color: green;"><?=number_format($solde,0,',',' ');?></th><?php
        }else{?>
            <th colspan="2" style="background-color: red;"><?=number_format($solde,0,',',' ');?></th><?php
        }?>
      </tr>

    </tfoot>

  </table><?php

}




//Pour la liste des decouvert

if (isset($_GET['deca']) or isset($_GET['decm']) or isset($_GET['decg'])) {?>

  <table class="tablistebul">
    <thead>
      <tr><th colspan="2" height="25"><?='Liste des élèves en découvert '.$_GET['annee'];?></th></tr>

      <tr>
        <th height="30">Matricule</th>
        <th>Nom & Prénom</th>
      </tr>

    </thead>

    <tbody><?php

        if (isset($_GET['deca'])) {

         $prodpaye =$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel FROM eleve inner join inscription on inscription.matricule=eleve.matricule WHERE inscription.annee=:promoins and inscription.matricule not in(SELECT matricule FROM payement WHERE promo=:annee) order by(matricule)', array('promoins'=>$_GET['deca'], 'annee' => $_GET['deca']));

        }elseif (isset($_GET['decm'])) {
          
          $prodpaye =$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel FROM eleve inner join inscription on inscription.matricule=eleve.matricule WHERE inscription.annee=:promoins and inscription.matricule not in(SELECT matricule FROM payement WHERE promo=:annee and mois=:mois) order by(matricule)', array('promoins'=>$_SESSION['annee'], 'annee' => $_SESSION['annee'], 'mois'=>$_GET['decm']));

        }elseif (isset($_GET['decg'])) {
          
          $prodpaye =$DB->query('SELECT eleve.matricule as matricule, nomel, prenomel FROM eleve inner join inscription on eleve.matricule=inscription.matricule WHERE nomgr=:nom and inscription.annee=:promoins and eleve.matricule not in(SELECT matricule FROM payement WHERE promo=:annee and mois=:mois) order by(matricule)', array('promoins'=>$_SESSION['annee'], 'annee' => $_SESSION['annee'], 'mois'=>$_SESSION['mensuelle'], 'nom'=>$_GET['decg']));
        }


        foreach($prodpaye as $payeloc ){?>

          <tr>
            <td style="text-align: center;"><?=$payeloc->matricule;?></td>

            <td><?=strtoupper($payeloc->nomel).' '.ucfirst($payeloc->prenomel);?></td>

          </tr><?php

      }?>

        

    </tbody>

  </table><?php

}

//Frais de scolarité


if (isset($_GET['scolarite'])) {

  $prodm=$DB->query('SELECT classe, nomf, montant, tranche, scolarite.id as id from scolarite inner join formation on scolarite.codef=formation.codef where scolarite.codef=:code and scolarite.promo=:promo',array('code'=>$_GET['codef'], 'promo'=>$_GET['scolarite']));?>

  <table class="tablistebul" style="margin-top: 60px; width: 80%;">
    <thead>
      <tr><th colspan="3" height="25"><?='Frais de scolarité'.' en '.$_GET['nomf'].' Année Scolaire'.($_GET['scolarite']-1).'-'.$_GET['scolarite'];?></th></tr>

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

                <?=ucwords($formation->classe.'er '.strtolower($formation->nomf));?><?php

              }else{?>

                <?=ucwords($formation->classe.' ème '.strtolower($formation->nomf));?><?php
              }?>
            </td>

            <td style="text-align: center"><?=$formation->tranche;?></td>

            <td style="text-align: right"><?=number_format($formation->montant,2,',',' ');?></td>
          </tr><?php
        }
      }?>       

    </tbody>
    <tfoot>
      <tr>
        <th colspan="2" height="30">Total</th>
        <th style="text-align: right"><?=number_format($cumultranche,2,',',' ');?></th>
      </tr>
    </tfoot>

  </table><?php

}


//impression matière


if (isset($_GET['printmat'])) {

  $prodm=$DB->query('SELECT  codem, nommat,  nbre_heure as heure from matiere where matiere.codef=:code order by(matiere.id)', array('code'=>$_GET['printmat']));

  $prodf=$DB->querys('SELECT  nomf from formation where codef=:code', array('code'=>$_GET['printmat']));?>

  <table class="tablistebul" style="margin-top: 60px; width: 80%;">
    <thead>
      <tr><th colspan="3" height="25"><?='Liste des matières en '.ucfirst($prodf['nomf']);?></th></tr>

      <tr class="active">
        <th height="25">Code mat</th>
        <th>Matière</th>
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

            <td style="text-align: center;"><?=$formation->heure;?>h</td>

          </tr><?php
        }
      }?>       

    </tbody>

  </table><?php

}


  require 'pied.php';

  
  $content = ob_get_clean();
  try {
    $pdf = new HTML2PDF("p","A4","fr", true, "UTF-8" , 0);
    $pdf->pdf->SetAuthor('Amadou');
    $pdf->pdf->SetTitle(date("d/m/y"));
    $pdf->pdf->SetSubject('Création d\'un Portfolio');
    $pdf->pdf->SetKeywords('HTML2PDF, Synthese, PHP');
    //$pdf->pdf->IncludeJS("print(true);");
    $pdf->writeHTML($content);
    $pdf->Output('ticket'.date("d/m/y").date("H:i:s").'.pdf');
    // $pdf->Output('Devis.pdf', 'D');    
  } catch (HTML2PDF_exception $e) {
    die($e);
  }
//header("Refresh: 10; URL=index.php");
?>


