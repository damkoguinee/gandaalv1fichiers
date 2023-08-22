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


if (isset($_GET['payehemp'])) {

  $moisnum=$_GET['moisnum'];

    if (isset($_GET['matensind'])) {
        $products=$DB->query("SELECT *FROM personnel where numpers='{$_GET['matensind']}' ");
    }else{

        $products=$DB->query("SELECT *FROM personnel"); 
    }  

    foreach ($products as $key => $product) {

        $matricule=$product->numpers;

        $prodpaye=$DB->querys("SELECT id, numdec, matricule, mois, montant, typepaye, DATE_FORMAT(datepaye, \"%d/%m/%Y\")AS datepaye FROM payepersonnel where mois='{$moisnum}' and matricule='{$matricule}' and promo='{$_SESSION['promo']}' ");

        if (!empty($prodpaye['id'])) {

            $numdec=$prodpaye['numdec']; 
            $type=$prodpaye['typepaye'];
            $datep=$prodpaye['datepaye'];?>

            <page backtop="5mm" backleft="3mm" backright="1mm" backbottom="5mm"><?php

                require 'entete.php';
      

                if (empty($prodpaye['montant'])) {
                  $montantpaye=0;
                }else{
                  $montantpaye=$prodpaye['montant'];
                }

                $prodac=$DB->querys('SELECT *FROM accompte WHERE matricule= ? and moischaine=? and anneescolaire=?', array($matricule, $_GET['mois'], $_SESSION['promo']));

                if (empty($prodac['montant'])) {
                  $accompte=0;
                }else{
                  $accompte=$prodac['montant'];
                }


                $prodsoc=$DB->querys('SELECT *FROM ssocialpers WHERE numpers= ?', array($matricule));

                if (empty($prodsoc['montant'])) {
                  $cotisation=0;
                }else{
                  $cotisation=$prodsoc['montant'];
                }

                $prodpri=$DB->querys('SELECT *FROM primepers WHERE numpersp= ? and promop=?', array($matricule, $_SESSION['promo']));

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
                          <th><?="Matricule:  " .$matricule; ?></th>
                          <th colspan="3" style="font-size: 18px;"><?=$panier->nomPersonnel($matricule); ?></th>
                        </tr>

                        <tr>
                          <th><?php echo "Paiement N°: " .$numdec; ?></th>
                          <th><?php echo "Type de Paiement:  " .$type; ?></th>
                          <th colspan="2"><?php echo "Date de paiement:  " .$datep; ?></th>
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
                        if ($prodpaye['mois']==1) {
                          $mois='Janvier';
                        }elseif ($prodpaye['mois']==2) {
                          $mois='Février';
                        }elseif ($prodpaye['mois']==3) {
                          $mois='Mars';
                        }elseif ($prodpaye['mois']==4) {
                          $mois='Avril';
                        }elseif ($prodpaye['mois']==5) {
                          $mois='Mai';
                        }elseif ($prodpaye['mois']==6) {
                          $mois='Juin';
                        }elseif ($prodpaye['mois']==7) {
                          $mois='Juillet';
                        }elseif ($prodpaye['mois']==8) {
                          $mois='Août';
                        }elseif ($prodpaye['mois']==9) {
                          $mois='Septembre';
                        }elseif ($prodpaye['mois']==10) {
                          $mois='Octobre';
                        }elseif ($prodpaye['mois']==11) {
                          $mois='Novembre';
                        }elseif ($prodpaye['mois']==12) {
                          $mois='Décembre';
                        }?>

                        <tr>

                          <td style="width: 10%;border:2px ; border-bottom: 0px;"><?=$mois;?></td>

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

                        $total= $prodpaye['montant'];
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

            require 'signature.php';
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
        $pdf->Output('reçu'.date("d/m/y").date("H:i:s").'.pdf');
        // $pdf->Output('Devis.pdf', 'D');    
    } catch (HTML2PDF_exception $e) {
        die($e);
    }//header("Refresh: 10; URL=index.php");
}
    ?>