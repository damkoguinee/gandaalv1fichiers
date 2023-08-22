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
</style><?php
  
if (isset($_GET['etatsalairens'])) {

    foreach ($panier->modep as $key => $valuet) {

        $prodm=$DB->query("SELECT  *from payenseignant inner join enseignant on payenseignant.matricule=enseignant.matricule inner join salaireens on salaireens.numpers=enseignant.matricule left join contact on enseignant.matricule=contact.matricule where typepaye='{$valuet}' and  mois='{$_GET['mois']}' and payenseignant.anneescolaire='{$_SESSION['promo']}' and salaireens.promo='{$_SESSION['promo']}' order by(payenseignant.typepaye)");
      
        if (!empty($prodm)) {?>

            <page backtop="10mm" backleft="3mm" backright="1mm" backbottom="10mm"><?php

                require 'entete.php';?>
        
                <table class="tablistebul" style="width: 100%;">
                <thead>
                    <tr><th colspan="9" height="30">Etat des Salaires des Enseignants. Période: <?=$panier->moisbul();?> Type de paiement: <?=$valuet;?></th></tr>

                    <tr>
                    <th>N°</th>
                    <th>Mat</th>
                    <th height="25">Prénom & Nom </th>
                    <th>Contact</th>
                    <th>S.Brut</th>
                    <th>Avance</th>
                    <th>Net à Payer</th>
                    <th>N° Compte</th>
                    <th>Agence</th>
                    </tr>

                </thead>

                <tbody><?php

                    $totetat=0;
                    $totbrute=0;
                    $totaccompte=0;

                    if (empty($prodm)) {
                    # code...
                    }else{

                    foreach ($prodm as $key=>$formation) {

                        $prodac=$DB->querys('SELECT montant from accompte where matricule=:mat and mois=:datet and anneescolaire=:promo', array('mat'=>$formation->matricule, 'datet'=>$_GET['mois'], 'promo'=>$_SESSION['promo']));

                        if (empty($prodac)) {
                        $accompte=0;
                        }else{
                        $accompte=$prodac['montant'];
                        }

                        $totetat+=$formation->montant;
                        $totbrute+=$formation->salaire;
                        $totaccompte+=$accompte;?>

                        <tr>
                        <td style="font-size: 12px; text-align:center;"><?=$key+1;?></td>
                        <td style="font-size: 12px;"><?=$formation->matricule;?></td>

                        <td height="20" style="width: 150px;font-size: 12px;"><?=ucwords(strtolower($formation->prenomen)).' '.strtoupper($formation->nomen);?></td>

                        <td style="font-size: 12px;"><?=$formation->phone;?></td>
                        <td style="text-align:right; font-size: 12px;"><?=number_format($formation->salaire,0,',',' ');?></td>
                        <td style="text-align:right; font-size: 12px;"><?=number_format($accompte,0,',',' ');?></td>

                        <td style="text-align:right; font-size: 15px; font-weight:bold;"><?=number_format($formation->montant,0,',',' ');?></td>

                        <td style="text-align: center; font-size: 15px; font-weight:bold;"><?=strtoupper($formation->numbanq);?></td>

                        <td style="text-align: center; font-size: 12px;"><?=strtoupper($formation->agencebanq);?></td>

                        </tr><?php
                    }
                    }?>
                </tbody>

                <tfoot>
                    <tr>
                    <th height='30' colspan="4">Totaux</th>
                    <th style="text-align:right; font-size: 12px;"><?=number_format($totbrute,0,',',' ');?></th>
                    <th style="text-align:right; font-size: 12px;"><?=number_format($totaccompte,0,',',' ');?></th>
                    <th style="text-align:right; font-size: 15px; font-weight:bold;"><?=number_format($totetat,0,',',' ');?></th>
                    </tr>
                </tfoot>
                </table>

            </page><?php
            
        }
    }
}



if (isset($_GET['etatsalairepers'])) {

    foreach ($panier->modep as $key => $valuet) {

        $prodm=$DB->query("SELECT  *from payepersonnel inner join personnel on payepersonnel.matricule=numpers inner join salairepers on salairepers.numpers=personnel.numpers left join contact on personnel.numpers=contact.matricule where typepaye='{$valuet}' and mois='{$_GET['mois']}' and payepersonnel.promo='{$_SESSION['promo']}' and salairepers.promo='{$_SESSION['promo']}' order by(typepaye)");

        if (!empty($prodm)) {?>

            <page backtop="10mm" backleft="3mm" backright="1mm" backbottom="10mm"><?php

                require 'entete.php';?>
                <table class="tablistebul" style="width: 100%;">
                    <thead>
                        <tr><th colspan="9" height="30">Etat des Salaires des Personnels. Mois: <?=$panier->moisbul();?> Type de paiement: <?=$valuet;?></th></tr>

                        <tr>
                        <th>N°</th>
                        <th>Mat</th>
                        <th height="25">Prénom & Nom </th>
                        <th>Tél</th>
                        <th>S.Brut</th>
                        <th>Avance</th>
                        <th>Net à Payer</th>
                        <th>N° Compte</th>
                        <th>Agence</th>
                        </tr>

                    </thead>

                    <tbody><?php

                        $totetat=0;
                        $totbrute=0;
                        $totaccompte=0;

                        if (empty($prodm)) {
                        # code...
                        }else{

                            foreach ($prodm as $key=>$formation) {

                                $prodac=$DB->querys('SELECT montant from accompte where matricule=:mat and mois=:datet and anneescolaire=:promo', array('mat'=>$formation->matricule, 'datet'=>$_GET['mois'], 'promo'=>$_SESSION['promo']));

                                if (empty($prodac)) {
                                $accompte=0;
                                }else{
                                $accompte=$prodac['montant'];
                                }

                                $prodprime=$DB->querys('SELECT montantp from primepers where numpersp=:mat and promop=:promo', array('mat'=>$formation->matricule, 'promo'=>$_SESSION['promo']));

                                if (empty($prodprime)) {
                                    $prime=0;
                                }else{
                                    $prime=$prodprime['montantp'];
                                }

                                $prodsocial=$DB->querys('SELECT montant from ssocialpers where numpers=:mat', array('mat'=>$formation->matricule));

                                $_SESSION['prodsocial']=$prodsocial['montant'];

                                $totetat+=$formation->montant;
                                $totbrute+=$formation->salaire;
                                $totaccompte+=$accompte;?>

                                <tr>
                                <td style="font-size: 12px; text-align:center;"><?=$key+1;?></td>
                                <td style="font-size: 12px;"><?=$formation->matricule;?></td>

                                <td height="20" style="width: 150px;font-size: 12px;"><?=ucwords(strtolower($formation->prenom)).' '.strtoupper($formation->nom);?></td>

                                <td style="font-size: 12px;"><?=$formation->phone;?></td>
                                <td style="text-align:right; font-size: 12px;"><?=number_format($formation->salaire,0,',',' ');?></td>
                                <td style="text-align:right; font-size: 12px;"><?=number_format($accompte,0,',',' ');?></td>

                                <td style="text-align:right; font-size: 15px; font-weight:bold;"><?=number_format($formation->montant,0,',',' ');?></td>

                                <td style="text-align: center; font-size: 15px; font-weight:bold;"><?=strtoupper($formation->numbanq);?></td>

                                <td style="text-align: center; font-size: 12px;"><?=strtoupper($formation->agencebanq);?></td>

                                </tr><?php
                            }
                        }?>
                    </tbody>

                    <tfoot>
                        <tr>
                        <th height="30" colspan="4">Totaux</th>
                        <th style="text-align:right; font-size: 12px;"><?=number_format($totbrute,0,',',' ');?></th>
                        <th style="text-align:right; font-size: 12px;"><?=number_format($totaccompte,0,',',' ');?></th>
                        <th style="text-align:right; font-size: 15px; font-weight:bold;"><?=number_format($totetat,0,',',' ');?></th>
                        </tr>
                    </tfoot>
                </table>
            </page><?php
            
        }
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
$pdf->Output('document'.date("d/m/y").date("H:i:s").'.pdf');
// $pdf->Output('Devis.pdf', 'D');    
} catch (HTML2PDF_exception $e) {
die($e);
}