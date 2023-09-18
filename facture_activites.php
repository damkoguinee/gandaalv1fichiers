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

  if (isset($_GET['matricule'])) {
    $products=$DB->query("SELECT activitespaiehistorique.id as id, numeropaie, matp, montantp, moisp, idact, nomgr, dateop FROM activitespaiehistorique left join inscription on matricule=matp where matp='{$_GET['matricule']}' and anneep='{$_SESSION['promo']}' and annee='{$_SESSION['promo']}'  order by(activitespaiehistorique.id) ");

    $eleve=$panier->nomElevex($_GET['matricule']);
    if (empty($eleve)) {
        $eleve=$panier->nomEleve($_GET['matricule']);
    }
    $classe=$panier->infoEleve($_GET['matricule'])[3];
    if (empty($classe)) {
        $classe="externe";
    }?>

    <table class="border" style=" margin: auto; margin-top: 30px;">
      <thead>
        <tr>
          <th colspan="5" style="font-size: 12px; text-align: left;">NB: Aucun remboursement ou transfert n'est possible après paiement. Le paiement de toute activité entamée est entièrement dû.</th>
        </tr>

        <tr>
          <th colspan="5" style="border-bottom: 0px; text-align: center;">Réçu de Payement des activités / Année-Scolaire: <?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></th>
        </tr>

        <tr>
          <th colspan="5" height="10" style=" text-align: left;">Matricule: <?=$_GET['matricule'];?> / Nom & Prénom: <?=$eleve;?> / Classe: <?=$classe;?> / Date: <?=date("d/m/Y");?></th>
        </tr>

        <tr>
            <th>N°</th>
            <th>Date</th>
            <th>Nature de Paiement</th>
            <th>Période</th>
            <th>Montant Payé</th> 
        </tr>
      </thead>

      <tbody><?php
          $totnow=0;
          $totp=0;
          $restp=0;
          $resta=0;

          foreach ($products as $key=> $product){

            $totp+=$product->montantp;
            $dated=(new DateTime($product->dateop))->format("d/m/Y");
            $now = date("d/m/Y"); 
            if ($now==$dated) {
                $totnow +=$product->montantp;
            } ?>
            <tr>
              <td><?=$key+1;?></td>
              <td><?=$dated;?></td>
              <td><?=$panier->nomActivites($product->idact)[0];?></td>
              <td><?=$product->moisp;?></td>
              <td style="text-align: right;"><?=number_format($product->montantp,0,',',' '); ?></td>
            </tr><?php
          } ?>
        

      </tbody><?php 
      if (sizeof($products)>1) {?>

        <tfoot>
          <tr>
            <th colspan="3">Dernier(s) Paiement(s): <?=number_format($totnow,0,',',' ');?></th>
            <th colspan="2">Total: <?=number_format($totp,0,',',' ');?></th>
          </tr>
        </tfoot><?php
      }?>

    </table><?php
  }

  $pers1=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where pseudo=:pseudo', array('pseudo'=>$_SESSION['pseudo']));

  $pers2=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'Proviseur'));?>
  

  <div  style="margin-top: 20px; color: #717375;"><label style="margin-left: 320px; font-size: 13px; font-style: italic;"><?=ucwords($pers1['type']);?></label></div>

  <div class="pied" style="margin-top: 85px; color: #717375;"><label style="margin-left: 30px; font-size: 13px; font-style: italic;"><?=strtoupper($pers1['nom']).' '.ucwords($pers1['prenom']);?></label></div>
</page><?php
  //require 'signature.php';


  
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


