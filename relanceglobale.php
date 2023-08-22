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
  }

  table.tablistebul{
    width: 100%;
    margin:auto;
    margin-top: 20px;
    color: #717375;
    border-collapse: collapse;
  }

  .tablistebul th {
    border: 1px solid black;
    font-size: 14px;
    text-align: center;
    padding-top: 2px;
    padding-right: 5px;
    padding-left: 5px;
  }
  .tablistebul td {
    border: 1px solid black;
    text-align: right;
    padding-right: 5px;
    padding-left: 5px;
    padding-top: 5px;
    font-size: 16px;
  }

  table.tablistel{
    width: 99%;
    margin-left: 2px;
    color: #717375;
    border-collapse: collapse;
  }

  .tablistel th {
    line-height: 7mm;
    border: 2px solid black;
    font-size: 14px;
    font-weight: bold;
    text-align: center;
    padding-right: 2px;
    padding-left: 2px;
  }

  .tablistel td {
    border: 1px solid black;
    line-height: 7mm;
    text-align: left;
    padding-right: 5px;
    padding-left: 5px;
    font-size: 14px;
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
    font-size: 16px;
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
<page backtop="5mm" backleft="3mm" backright="1mm" backbottom="5mm"><?php

  
if (isset($_GET['courriert'])) {
  $etat='actif';

  $prodpaye =$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel, adresse, DATE_FORMAT(naissance, \'%d%/%m%/%Y\')AS naissance, phone, codef, nomgr, remise FROM eleve inner join inscription on eleve.matricule=inscription.matricule inner join contact on contact.matricule=inscription.matricule WHERE nomgr=:nom and inscription.annee=:promoins and etatscol=:etat and eleve.matricule not in(SELECT matricule FROM payementfraiscol WHERE (montant>=:montant and promo=:annee and tranche=:mois)) order by(prenomel)', array('promoins'=>$_SESSION['annee'], 'etat'=>$etat,  'montant'=>$_GET['montantscol'], 'annee' => $_SESSION['annee'], 'mois'=>$_SESSION['mensuellec'], 'nom'=>$_SESSION['groupe']));

  foreach ($prodpaye as $key => $value) {

    if ($_SESSION['mensuellec']=='1ere tranche') {
      $prodpaye0=array();
    }elseif ($_SESSION['mensuellec']=='2eme tranche') {

      $prodscol0 = $DB->querys('SELECT montant, DATE_FORMAT(limite, \'%d%/%m%/%Y\')AS limite FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>'1ere tranche', 'promo'=>$_SESSION['promo'], 'code'=>$value->codef));

      $prodpaye0 =$DB->querys('SELECT sum(montant) as montant, remise FROM payementfraiscol inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE promo=:promo and annee=:promoins and payementfraiscol.matricule=:mat and tranche=:mois', array('promo'=>$_SESSION['annee'], 'promoins'=>$_SESSION['promo'], 'mat' => $value->matricule, 'mois'=>'1ere tranche'));
    }else{

      $prodscol0 = $DB->querys('SELECT montant, DATE_FORMAT(limite, \'%d%/%m%/%Y\')AS limite FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>'1ere tranche', 'promo'=>$_SESSION['promo'], 'code'=>$value->codef));

      $prodpaye0 =$DB->querys('SELECT sum(montant) as montant, remise FROM payementfraiscol inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE promo=:promo and annee=:promoins and payementfraiscol.matricule=:mat and tranche=:mois', array('promo'=>$_SESSION['annee'], 'promoins'=>$_SESSION['promo'], 'mat' => $value->matricule, 'mois'=>'1ere tranche'));


      $prodscol1 = $DB->querys('SELECT montant, DATE_FORMAT(limite, \'%d%/%m%/%Y\')AS limite FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>'2eme tranche', 'promo'=>$_SESSION['promo'], 'code'=>$value->codef));

      $prodpaye1 =$DB->querys('SELECT sum(montant) as montant, remise FROM payementfraiscol inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE promo=:promo and annee=:promoins and payementfraiscol.matricule=:mat and tranche=:mois', array('promo'=>$_SESSION['annee'], 'promoins'=>$_SESSION['promo'], 'mat' => $value->matricule, 'mois'=>'2eme tranche'));

    }

         

    $prodscol = $DB->querys('SELECT montant, DATE_FORMAT(limite, \'%d%/%m%/%Y\')AS limite FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['promo'], 'code'=>$value->codef));

    $prodscollimite = $DB->querys('SELECT limite FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['promo'], 'code'=>$value->codef));

    $montantscol=$prodscol['montant'];

    $montantscol=$prodscol['montant'];

    $prodcredit =$DB->querys('SELECT sum(montant) as montant, remise FROM payementfraiscol inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE promo=:promo and annee=:promoins and payementfraiscol.matricule=:mat and tranche=:mois', array('promo'=>$_SESSION['annee'], 'promoins'=>$_SESSION['promo'], 'mat' => $value->matricule, 'mois'=>$_SESSION['mensuellec']));

    $resteapayer=$prodscol['montant']*(1-($prodcredit['remise']/100))-$prodcredit['montant'];

    if($value->remise==100){

    }else{

      if (!empty($resteapayer)) {

        $limite=(new dateTime($prodscollimite['limite']))->format("Ymd");
        $now=date("Ymd");
        if ($limite>=$now) {
          $verbe="est";
        }else{
          $verbe="était";
        }

        require 'entete.php';?>
        

        <div style="width:700px; margin-left: 50px; font-size: 16px;">

          Chers Parents,<br/>
          Sauf erreur de notre part, votre fils/fille <strong><?=strtoupper($value->nomel).' '.ucwords($value->prenomel);?></strong> matricule N°<strong><?=$value->matricule;?></strong>, né(e) le <strong><?=$value->naissance;?></strong> inscrit(e) en <strong><?=$value->nomgr;?></strong> n'est pas à jour dans ses frais de scolarité concernant la <strong><?=$_GET['tranche'];?></strong>. La date limite de payement de la <strong><?=$_GET['tranche'];?></strong> <?=$verbe;?> le <strong><?=$prodscol['limite'];?></strong>

          <table class="tablistebul">
            <tbody>  
              <tr>
                <th height="18">Nom</th>
                <th>Montant de la Tranche</th>
                <th>Montant Payé</th>
                <th>Reste à Payer</th>
              </tr>
            </tbody>

            <tbody><?php
              $totmontantscol1=0;
              $totmontantpaye1=0;
              $totreste1=0;

              $totmontantscol2=0;
              $totmontantpaye2=0;
              $totreste2=0;

              $totmontantscol3=0;
              $totmontantpaye3=0;
              $totreste3=0;
              $totresteannuel=0; 
              foreach ($panier->tranche() as $value) {

                if ($_SESSION['mensuellec']=='1ere tranche') {

                  $tranche='actif';

                  $totmontantscol1+=$prodscol['montant'];
                  $totmontantpaye1+=$prodcredit['montant'];
                  $totreste1+=$resteapayer;

                  if ($value->nom=='1ere tranche') {?>
                    <tr>
                      <td height="15"><?=ucwords($value->nom);?></td>
                      <td><?=number_format($prodscol['montant'],0,',',' ');?></td>
                      <td><?=number_format($prodcredit['montant'],0,',',' ');?></td>
                      <td style="color: red;"><?=number_format($resteapayer,0,',',' ');?></td>
                    </tr><?php

                  }
                }elseif ($_SESSION['mensuellec']=='2eme tranche') {

                  $tranche='';

                  if ($value->nom!='3eme tranche') {

                    if ($value->nom=='1ere tranche') {

                      if ($prodpaye0['montant']<$prodscol0['montant']) {

                        $resteapayer1=$prodscol0['montant']*(1-($prodpaye0['remise']/100))-$prodpaye0['montant'];

                        $totmontantscol1+=$prodscol0['montant'];
                        $totmontantpaye1+=$prodpaye0['montant'];
                        $totreste1+=$resteapayer1;?>
                        <tr>
                          <td height="15"><?=ucwords($value->nom);?></td>
                          <td><?=number_format($prodscol0['montant'],0,',',' ');?></td>
                          <td><?=number_format($prodpaye0['montant'],0,',',' ');?></td>
                          <td style="color: red;"><?=number_format($resteapayer1,0,',',' ');?></td>
                        </tr><?php
                      }

                    }else{

                      if ($prodcredit['montant']<$prodscol['montant']) {                       

                        $totmontantscol2+=$prodscol['montant'];
                        $totmontantpaye2+=$prodcredit['montant'];
                        $totreste2+=$resteapayer;?>

                        <tr>
                          <td height="15"><?=ucwords($value->nom);?></td>
                          <td><?=number_format($prodscol['montant'],0,',',' ');?></td>
                          <td><?=number_format($prodcredit['montant'],0,',',' ');?></td>
                          <td style="color: red;"><?=number_format($resteapayer,0,',',' ');?></td>
                        </tr><?php
                      }
                    }

                  }

                }elseif ($_SESSION['mensuellec']=='3eme tranche') {
                  $tranche='';

                  if ($value->nom=='1ere tranche') {

                    if ($prodpaye0['montant']<$prodscol0['montant']) { 

                      $resteapayer1=$prodscol0['montant']*(1-($prodpaye0['remise']/100))-$prodpaye0['montant'];

                      $totmontantscol1+=$prodscol0['montant'];
                      $totmontantpaye1+=$prodpaye0['montant'];
                      $totreste1+=$resteapayer1;?>
                      <tr>
                        <td height="15"><?=ucwords($value->nom);?></td>
                        <td><?=number_format($prodscol0['montant'],0,',',' ');?></td>
                        <td><?=number_format($prodpaye0['montant'],0,',',' ');?></td>
                        <td style="color: red;"><?=number_format($resteapayer1,0,',',' ');?></td>
                      </tr><?php
                    }

                  }elseif ($value->nom=='2eme tranche') {

                    if ($prodpaye1['montant']<$prodscol1['montant']) {

                      $resteapayer2=$prodscol1['montant']*(1-($prodpaye1['remise']/100))-$prodpaye1['montant'];

                      $totmontantscol2+=$prodscol1['montant'];
                      $totmontantpaye2+=$prodpaye1['montant'];
                      $totreste2+=$resteapayer2;?>

                      <tr>
                        <td height="15"><?=ucwords($value->nom);?></td>
                        <td><?=number_format($prodscol1['montant'],0,',',' ');?></td>
                        <td><?=number_format($prodpaye1['montant'],0,',',' ');?></td>
                        <td style="color: red;"><?=number_format($resteapayer2,0,',',' ');?></td>
                      </tr><?php
                    }

                  }elseif ($value->nom=='3eme tranche') {

                    if ($prodcredit['montant']<$prodscol['montant']) {

                      $totmontantscol3+=$prodscol['montant'];
                      $totmontantpaye3+=$prodcredit['montant'];
                      $totreste3+=$resteapayer;?>

                      <tr>
                        <td height="15"><?=ucwords($value->nom);?></td>
                        <td><?=number_format($prodscol['montant'],0,',',' ');?></td>
                        <td><?=number_format($prodcredit['montant'],0,',',' ');?></td>
                        <td style="color: red;"><?=number_format($resteapayer,0,',',' ');?></td>
                      </tr><?php
                    }
                  }

                }
              }?>
            </tbody><?php 

            if ($_GET['tranche']!='1ere tranche') {?>

              <tfoot>
                <tr>
                  <th height="18">Totaux</th>
                  <th style="text-align:right; font-size: 16px;"><?=number_format($totmontantscol1+$totmontantscol2+$totmontantscol3,0,',',' ');?></th>
                  <th style="text-align:right; font-size: 16px;"><?=number_format($totmontantpaye1+$totmontantpaye2+$totmontantpaye3,0,',',' ');?></th>
                  <th style="text-align:right; font-size: 16px; color: red;"><?=number_format($totreste1+$totreste2+$totreste3,0,',',' ');?></th>
                </tr><?php 

                if ($_GET['tranche']!='2eme tranche') {?>

                  <tr>
                    <th colspan="3" height="18" style="text-align: right;">Reste Annuel</th>
                    <th style="text-align:right; font-size: 16px; color: red;"><?=number_format($totreste1+$totreste2+$totreste3,0,',',' ');?></th>
                  </tr><?php 
                }?>
              </tfoot><?php 
            }?>
          </table>

          Merci de faire le nécessaire pour régulariser sa situation.<br/>
          Cordialement,
        </div><?php

        require 'piedcomptable.php';
      }
    }
  }
}?>

</page><?php

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

