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
    $_SESSION['moisp']=$_GET['mois'];
    
    $prodm=$DB->query('SELECT enseignant.matricule as matricule, prenomen, nomen from enseignant inner join salaireens on salaireens.numpers=enseignant.matricule where salaireens.promo=:promop and enseignant.matricule not in(SELECT matricule FROM payenseignant WHERE anneescolaire=:annee and mois=:mois) and promo=:promo order by(prenomen)', array('promop'=>$_SESSION['promo'], 'annee'=>$_SESSION['promo'], 'mois'=>$_SESSION['moisp'], 'promo'=>$_SESSION['promo']));
    
    if ($_GET['mois']<10) {
                    
        $cmois='0'.$_SESSION['moisp'];

    }else{

        $cmois=$_SESSION['moisp'];
    }?>
  
    <table class="tablistel" style="margin:auto;">
        <thead>
            <tr><th colspan="9" height="30">Situation des salaires en-cours pour le mois de <?=$panier->moisbul();?> </th></tr>
            <tr>
                <th>N°</th>
                <th>Mat</th>
                <th>Prénom & Nom</th>
                <th>Heures</th>
                <th>Salaire Brut</th>
                <th>Prime</th>
                <th>A/salaire</th>
                <th>Cotisa.</th>
                <th class="bg-success">Salaire Net</th>
            </tr>
        </thead>

        <tbody><?php 
            $totb=0;
            $totp=0;
            $totac=0;
            $totcot=0;
            $totn=0;
            $toth=0;

            foreach ($prodm as $key => $value) {
                $_SESSION['numeen']=$value->matricule;
                    $numeen=$_SESSION['numeen'];

                $prodsocial=$DB->querys('SELECT montant from ssocialens where numpers=:mat', array('mat'=>$numeen));

                $_SESSION['prodsocial']=$prodsocial['montant'];

                $prodsalaire=$DB->querys('SELECT salaire, thoraire from salaireens where numpers=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));
                
                if ($prodsalaire['salaire']==0) {
                    
                    $_SESSION['salaire']=$prodsalaire['thoraire'];
                    $_SESSION['salaireact']='not';

                }else{

                    $_SESSION['salaire']=$prodsalaire['salaire'];
                    $_SESSION['salaireact']='ok';
                }

                $prodprime=$DB->querys('SELECT montant as montantp from primesplanifie where matricule=:mat and mois=:mois and anneescolaire=:promo', array('mat'=>$numeen, 'mois'=>$_SESSION['moisp'], 'promo'=>$_SESSION['promo']));
                
                if (empty($prodprime)) {
                    $prime=0;
                }else{
                    $prime=$prodprime['montantp'];
                }

                $prodautres=$DB->querys('SELECT id from liaisonenseigpers where matricule=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));

                //var_dump($numeen, $_SESSION['promo']);

                if (empty($prodautres['id'])) {
                    $salairesautres=0;
                }else{
                    $prodautres=$DB->querys('SELECT salaire as montantp from salairepers where numpers=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));

                    $salairesautres=$prodautres['montantp'];

                }
                
                $prodh=$DB->querys('SELECT sum(heuret) as heuret from horairet where numens=:mat and date_format(datet,\'%m\')=:datet and annees=:promo', array('mat'=>$numeen, 'datet'=>$cmois, 'promo'=>$_SESSION['promo'])); 
                
                $prodac=$DB->querys('SELECT montant from accompte where matricule=:mat and mois=:datet and anneescolaire=:promo', array('mat'=>$numeen, 'datet'=>$_SESSION['moisp'], 'promo'=>$_SESSION['promo']));

                if (empty($prodac)) {
                    $accompte=0;
                }else{
                    $accompte=$prodac['montant'];
                }

                if ($_SESSION['salaireact']=='not') {

                    $salaireb=$_SESSION['salaire']*$prodh['heuret']+$salairesautres;
                    
                    $salairep=$_SESSION['salaire']*$prodh['heuret']+$salairesautres+$prime-$accompte-$_SESSION['prodsocial'];

                }else{
                    $salaireb=$_SESSION['salaire']+($prodh['heuret']*$rapport->infoEtablissement()['thoraire'])+$salairesautres;

                    $salairep=$salaireb+$prime-$accompte-$_SESSION['prodsocial'];
                }

                $totb+=$salaireb;
                $totp+=$prime;
                $totac+=$accompte;
                $totcot+=$_SESSION['prodsocial'];
                $totn+=$salairep;
                $toth+=$prodh['heuret'];?>               
                <tr>
                    <td style="text-align: center;"><?=$key+1;?></td>
                    <td><?=$value->matricule;?></td>
                    <td><?=ucwords(strtolower($value->prenomen)).' '.strtoupper($value->nomen);?></td>
                    <td style="text-align: center; padding-right: 5px;"><?=number_format($prodh['heuret'],1,',',' ');?></td>
                    <td style="text-align: right; padding-right: 5px;"><?=number_format($salaireb,0,',',' ');?></td>
                    <td style="text-align: right; padding-right: 5px;"><?=number_format($prime,0,',',' ');?></td>
                    <td style="text-align: right; padding-right: 5px;"><?=number_format($accompte,0,',',' ');?></td>
                    <td style="text-align: right; padding-right: 5px;"><?=number_format($_SESSION['prodsocial'],0,',',' ');?></td>
                    <td style="text-align: right; padding-right: 5px;"><?=number_format($salairep,0,',',' ');?></td>
                </tr><?php 
            }?>
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3">Totaux</th>
                <th style="text-align: center; padding-right: 5px;"><?=number_format($toth,0,',',' ');?></th>
                <th style="text-align: right; padding-right: 5px;"><?=number_format($totb,0,',',' ');?></th>
                <th style="text-align: right; padding-right: 5px;"><?=number_format($totp,0,',',' ');?></th>
                <th style="text-align: right; padding-right: 5px;"><?=number_format($totac,0,',',' ');?></th>
                <th style="text-align: right; padding-right: 5px;"><?=number_format($totcot,0,',',' ');?></th>
                <th class="bg-success fw-bold fs-6 text-white" style="text-align: right;"><?=number_format($totn,0,',',' ');?></th>
            </tr>
        </tfoot>
        
    </table><?php 

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