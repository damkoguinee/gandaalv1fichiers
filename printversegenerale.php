<?php
require_once "lib/html2pdf.php";

ob_start(); ?>
<?php require '_header.php';?>
<style type="text/css">

body{
  margin: 0px;
  width: 100%;
  height:68%;
  padding:0px;}
  .ticket{
    margin:0px;
    width: 100%;
  }
  table {
    width: 100%;
    color: #717375;
    font-family: helvetica;
    border-collapse: collapse;
    margin: auto;
  }
  
  .border th {
    border: 1px solid black;
    padding:5px;
    font-weight: bold;
    font-size: 11px;
    color: black;
    background: white;
    text-align: center; }
  .border td {
    padding-bottom: 5px;
    padding-top: 5px;
    border: 1px solid black;    
    font-size: 11px;
    color: black;
    background: white;
    text-align: left;
    padding-right: 10px;}
  .footer{
    font-size: 30px;
    font-style: italic;
  }

  .legende{
    font-size: 18px;
    text-align: center;
    padding-bottom: 5px;
    padding-top: 5px;
  }

</style>

<page backtop="10mm" backleft="5mm" backright="5mm" backbottom="10mm" footer="page;">

  <?php 

  require 'entete.php';

  $datenormale=date("d/m/Y à H:i"); ?>

  <table  class="border" style="margin:auto; margin-top: 20px;">

    <thead>

      <tr>
        <th colspan="7" ><?="Liste des Recettes à la date du " .$datenormale ?></th>
      </tr>

      <tr>
        <th>N°</th>
        <th>Date</th>
        <th style="width: 350px;">Désignation</th>              
        <th>Montant GNF</th>
        <th>Taux</th>
        <th>Montant</th>
        <th>Mode de Paie</th>
      </tr>

    </thead>

    <tbody><?php
      $montantgnf=0;
      $montantdev=0;
      $keyd=0;

      $categorie= $DB->query("SELECT *FROM categorievers order by(nom) ");

      foreach ($categorie as $key => $valuedep) {

        $montantgnfint=0;
        $montantdevint=0;

        $prodm=$DB->query("SELECT *FROM versement where categorie='{$valuedep->id}' and promo='{$_SESSION['promo']}' ");

        if (!empty($prodm)) {?>

          <tr><td colspan="7" style="text-align: center;font-weight: bold; "><?=strtoupper($valuedep->nom);?></td></tr><?php
        }

        

        foreach ($prodm as $keyv=> $product ){
          $keyd+=($keyv+1);?>

          <tr>
            <td style="text-align: center;"><?= $keyv+1; ?></td>
            <td style="text-align:center;"><?=(new dateTime($product->date_versement))->format("d/m/Y"); ?></td>
            <td style="width: 350px;"><?= ucwords(strtolower($product->motif)); ?></td><?php

            $montantgnfint+=$product->montant*$product->taux;
            $montantdevint+=$product->montant;
            $montantgnf+=$product->montant*$product->taux;
            $montantdev+=$product->montant;?>

            <td style="text-align: right; padding-right: 10px;"><?= number_format($product->montant*$product->taux,0,',',' '); ?></td>
            <td style="text-align: right; padding-right: 10px;"><?= number_format($product->taux,0,',',' '); ?></td>
            <td style="text-align: right; padding-right: 10px;"><?= number_format($product->montant,0,',',' '); ?></td>
            <td><?= $product->type_versement;?></td>            
          </tr><?php 
        }

        if (!empty($prodm)) {?>

          <tr>
            <td colspan="3" style="text-align: center;font-weight: bold; ">Totaux <?=ucwords($valuedep->nom);?></td>
            <td style="text-align: right; font-weight: bold;"><?= number_format($montantgnfint,0,',',' ');?></td>
            <td></td>
            <td style="text-align: right; font-weight: bold;"><?= number_format($montantdevint,0,',',' ');?></td>
            <td></td>
          </tr><?php
        }
      }?>

    </tbody>

    <tbody>
      <tr>
        <th colspan="3">Totaux montant des Recettes</th>
        <th style="text-align: right; padding-right: 10px;"><?= number_format($montantgnf,0,',',' ');?></th>
        <th></th>
        <th style="text-align: right; padding-right: 10px;"><?= number_format($montantdev,0,',',' ');?></th>
        <th></th>
      </tr>
    </tbody>

  </table>

</page>
<?php
  $content = ob_get_clean();
  try {
    $pdf = new HTML2PDF("p","A4","fr", true, "UTF-8" , 0);
    $pdf->pdf->SetAuthor('Amadou');
    $pdf->pdf->SetTitle(date("d/m/y"));
    $pdf->pdf->SetSubject('Création d\'un Portfolio');
    $pdf->pdf->SetKeywords('HTML2PDF, Synthese, PHP');
    $pdf->pdf->IncludeJS("print(true);");
    $pdf->writeHTML($content);
    $pdf->Output('recettes à la date '.date("d/m/y").date("H:i:s").'.pdf');
    // $pdf->Output('Devis.pdf', 'D');    
  } catch (HTML2PDF_exception $e) {
    die($e);
  }
?>