<?php
require_once "lib/html2pdf.php";

ob_start(); ?>

<?php require '_header.php';
?>

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
    margin-top: 20px;
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
    margin-top: 30px;
    margin-left: 10px;
    font-weight: bold;
    font-size: 12px;
    color: #717375;
  }

  table.tablistebul{
    width: 100%;
    margin-left: 2px;
    border-collapse: collapse;
  }

  .tablistebul th {
    line-height: 3mm;
    border: 1px solid black;
    color: grey;
    font-size: 15px;
    text-align: center;
    padding: 10px;
  }
  .tablistebul td {
    height: 8px;
    border: 1px solid black;
    text-align: right;
    padding-top: 10px;
    font-size: 15px;
    padding-right: 5px;
  }

  label {
    float: right;
    font-size: 16px;    
    width: 200px;
  }

  ol{
    list-style: none;
    font-size: 16px;
  }
</style><?php


if (isset($_GET['voir_eleveap'])) {

  if (isset($_GET['indi'])) {

    $matriculeindi=$_GET['voir_eleveap'];

    $prodmat=$DB->query('SELECT  inscription.matricule as matricule from inscription inner join eleve on inscription.matricule=eleve.matricule where eleve.matricule=:matr and annee=:promo', array('matr'=>$matriculeindi, 'promo'=>$_SESSION['promo']));

  }else{

    $prodmat=$DB->query('SELECT  inscription.matricule as matricule from inscription inner join eleve on inscription.matricule=eleve.matricule where nomgr=:nom and annee=:promo order by (prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
  }

  foreach ($prodmat as $eleve) {?>

    <page backtop="5mm" backleft="5mm" backright="5mm" backbottom="5mm">

      <div class="body"><?php

        require 'entetebul.php';

        $fiche=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, pere, telpere, mere, telmere, date_format(naissance,\'%d/%m/%Y \') as naiss, phone, email , annee, nomf, classe, nomgr from eleve inner join contact on eleve.matricule=contact.matricule inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef where eleve.matricule=:mat and annee=:promo', array('mat'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));?>

        <div style="width: 80%; background-color: white; color: #717375; border: 0.5px solid grey; border-style: dotted; margin-top:25px;">

          <div style="width: 100%; text-align: center; font-size: 16px; font-weight: bold; background-color: white;">FICHE D'APPRECIATION PERIODE: <?=strtoupper($_GET['periodeap']);?></div>

          <div style="width: 100%; text-align: center; font-size: 16px; font-weight: bold; background-color: white;">Année-Scolaire <?=$fiche['annee']-1;?> - <?=$fiche['annee'];?></div><?php

          $mat=$eleve->matricule;
          $filename1="img/".$mat.'.jpg';

        require 'ficheapp.php';?>

        <div style="margin-top: 30px; "><?php

          $prodapp=$DB->query("SELECT *from appreciation inner join matiere on codem=codematap where codefap='{$_GET['codef']}' and periodeap='{$_GET['periode']}' and promoap='{$_GET['promo']}'");?>


          <table class="tablistebul">

            <thead>

              <tr>
                <th>N°</th>
                <th>Activités</th>
                <th>Appréciations</th>
                <th>Observation</th>
              </tr>
            </thead>

            <tbody><?php
              if (empty($prodapp)) {
              # code...
              }else{

                foreach ($prodapp as $key=> $formation) {?>

                  <tr>
                    <td style="text-align: center;"><?=$key+1;?></td>                                   

                    <td style="text-align:left;"><?=ucfirst(strtolower($formation->nommat));?></td>

                    <td style="text-align:center;"><?=ucfirst($formation->appreciation);?></td>

                    <td style="text-align:left; width: 300px;"><?=ucfirst($formation->commentaires);?></td>

                  </tr><?php
                }

              }?>

                            
            </tbody>

            <tfoot><?php 
              $prodappgen=$DB->querys("SELECT *from appreciationgen where codefap='{$_GET['codef']}' and periodeap='{$_GET['periode']}' and promoap='{$_GET['promo']}'");

              if (empty($prodappgen['appreciation'])) {
                $appreciation='';
                $com='';
              }else{
                $appreciation=$prodappgen['appreciation'];
                $com=$prodappgen['commentaires'];

              }?>
              <tr>
                  <th colspan="2" height="20" style="text-align:center; padding-top: 40px; font-size: 15px;">Appréciation Générale</th>

                  <th style="text-align:center; padding-top: 40px; font-size: 15px;"><?=ucfirst($appreciation);?></th>

                  <th  style="text-align:left; width: 300px; font-size: 14px; padding-top: 20px;"><?=ucfirst($com);?></th>
              </tr>

              <tr><th colspan="4" style="text-align: right; font-style: italic; font-size:12px; border: 0px; border-top: 1px;">Editer le <?=date("d/m/Y");?></th></tr>
            </tfoot>
          </table>
        </div>
      </div><?php
    require 'piedapp.php';
  }

    
}





$content = ob_get_clean();
try {
  $pdf = new HTML2PDF("p","A4","fr", true, "UTF-8" , 0);
  $pdf->pdf->SetAuthor('Amadou');
  $pdf->pdf->SetTitle(date("d/m/y"));
  $pdf->pdf->SetSubject('Création d\'un Portfolio');
  $pdf->pdf->SetKeywords('HTML2PDF, Synthese, PHP');
  //$pdf->pdf->IncludeJS("print(true);");
  $pdf->writeHTML($content);
  $pdf->Output('appreciation'.'.pdf');
  // $pdf->Output('Devis.pdf', 'D');    
} catch (HTML2PDF_exception $e) {
  die($e);
}
//header("Refresh: 10; URL=index.php");
?>
