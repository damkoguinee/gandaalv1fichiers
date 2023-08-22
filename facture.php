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
<page backtop="5mm" backleft="3mm" backright="1mm" backbottom="5mm"><?php

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

  if (isset($_GET['numfac'])) {

    $products=$DB->query('SELECT histopayefrais.matricule as matricule, montant, numpaie, tranche, nomel, prenomel, nomgr, codef FROM histopayefrais inner join eleve on eleve.matricule=histopayefrais.matricule inner join inscription on inscription.matricule=histopayefrais.matricule WHERE histopayefrais.famille= ? and annee=? order by(nomgr)', array($_GET['numfac'], $_SESSION['promo']));

    $numerodep='depfs'.$_GET['numfac'];

    $prodpers=$DB->querys("SELECT personnel FROM banque WHERE numero='{$numerodep}' ");

    $idpers=$prodpers['personnel'];

    if ($_GET['tranche']=='1ere tranche') {
      $fraisinscript='';
    }else{
      $fraisinscript='';
    }?>

    <table class="border" style=" margin: auto; margin-top: 30px;">

      <thead>
        <tr>
          

          <th colspan="6">

            <table>
              <tbody>
                <tr><?php 

                  foreach($panier->nomBanqueTicket() as $product){?>
                    <th><?=ucwords($product->nomb);?> N°</th><?php
                  }?>
                </tr>
              </tbody>

              <tbody>
                <tr><?php 

                  foreach($panier->nomBanqueTicket() as $product){?>
                    <th><?=ucwords($product->numero);?></th><?php
                  }?>
                </tr>
              </tbody>
            </table>
          </th>
        </tr>

        <tr>
          <th colspan="6" style="font-size: 12px; text-align: left;">NB: Aucun remboursement ou transfert n'est possible après paiement. Le paiement de toute scolarité entamée est entièrement dû.</th>
        </tr>

        <tr>
          <th colspan="6" style="border-bottom: 0px; text-align: center;">Réçu de Payement des frais de scolarité / Année-Scolaire: <?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></th>
        </tr>

        <tr>
          <th colspan="6" height="10" style=" text-align: left;">Date: <?=$_GET['date'];?> / N° Réçu: <?=$_GET['numfac'];?> / TP: <?=$_GET['type'];?> / Banque: <?=ucwords($_GET['banque']);?> / N° Bordereau/Chèque: <?=$_GET['numpaie'];?> / traité par personnel: <?=$idpers;?></th>
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

            $prodrem = $DB->querys("SELECT remise FROM inscription WHERE matricule='{$product->matricule}' and annee='{$_SESSION['promo']}'");

            if ($product->tranche=='1ere tranche') {

              $montpaye=$product->montant+0;
            }else{
              $montpaye=$product->montant;
            }

            $totpaye=$prodtot['montant']+0;

            $restetranche=$prodscol['montant']-$prodtott['montant'];

            $resteannuel=$prodscola['montant']*(1-($prodrem['remise']/100))-$prodtot['montant'];


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
    $pdf->Output('reçu'.date("d/m/y").date("H:i:s").'.pdf');
    // $pdf->Output('Devis.pdf', 'D');    
  } catch (HTML2PDF_exception $e) {
    die($e);
  }
//header("Refresh: 10; URL=index.php");
?>


