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
    color: #717375;
    border-collapse: collapse;
  }

  .tablistel th {
    line-height: 7mm;
    border: 2px solid black;
    font-size: 14px;
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

  $prodpaye =$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel, adresse, DATE_FORMAT(naissance, \'%d%/%m%/%Y\')AS naissance, phone, codef, nomgr, remise FROM eleve inner join inscription on eleve.matricule=inscription.matricule inner join contact on contact.matricule=inscription.matricule WHERE nomgr=:nom and inscription.annee=:promoins and etatscol=:etat and eleve.matricule not in(SELECT matricule FROM payementfraiscol WHERE (montant>=:montant and promo=:annee and tranche=:mois)) order by(prenomel)', array('promoins'=>$_SESSION['annee'], 'etat'=>$etat, 'montant'=>$_GET['montantscol'], 'annee' => $_SESSION['annee'], 'mois'=>$_SESSION['mensuellec'], 'nom'=>$_SESSION['groupe']));

  foreach ($prodpaye as $key => $value) {      

    $prodscol = $DB->querys('SELECT montant, DATE_FORMAT(limite, \'%d%/%m%/%Y\')AS limite FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['promo'], 'code'=>$value->codef));

    $prodscollimite = $DB->querys('SELECT limite FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['promo'], 'code'=>$value->codef));

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
          Sauf erreur de notre part, votre fils/fille <strong><?=strtoupper($value->nomel).' '.ucwords($value->prenomel);?></strong> matricule N°<strong><?=$value->matricule;?></strong>, né(e) le <strong><?=$value->naissance;?></strong> inscrit(e) en <strong><?=$value->nomgr;?></strong> n'est pas à jour dans ses frais de scolarité concernant la <strong><?=$_GET['tranche'];?></strong>. La date limite de payement de la <strong><?=$_GET['tranche'];?></strong> <?=$verbe;?> le <strong><?=$prodscol['limite'];?></strong><br/><br/>

          <strong>Montant de la Tranche..............<?=number_format($prodscol['montant'],0,',',' ');?></strong><br/>

          
          <strong>Montant Payé.............................. <?=number_format($prodcredit['montant'],0,',',' ');?></strong><br/><?php

          if ($prodcredit['remise']>0) {?>
            <strong>Remise...........................................<?=$prodcredit['remise'];?>%</strong><br/><?php
          }?>    

          <strong>Reste à Payer............................. <?=number_format($resteapayer,0,',',' ');?></strong><br/>

          <p>Merci de faire le nécessaire pour régulariser sa situation.</p>

          <p>Cordialement,</p>
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

