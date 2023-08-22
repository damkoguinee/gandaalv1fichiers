 <?php
require 'header.php';

if (isset($_SESSION['pseudo'])) {
        
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

        <div style="display: flex;">
            <div style="width: 25%;"><?php

                require 'navcompta.php';?>

            </div>

            <div class="col" style="margin-bottom: 30px;"><?php

                if (!isset($_POST['j1'])) {
                  $_SESSION['date01']=date("Y0101");  
                  $_SESSION['date02']=date("Y0101"); 
                  $_SESSION['date']=date("Y0101");  
                  $dates = $_SESSION['date'];
                  $dates = new DateTime( $dates );
                  $dates = $dates->format('Y0101'); 
                  $_SESSION['date']=$dates;
                  $_SESSION['date1']=$dates;
                  $_SESSION['date2']=date('Y1231'); ;
                  $_SESSION['dates1']=$dates; 

                }else{

                  $_SESSION['date01']=$_POST['j1'];
                  $_SESSION['date1'] = new DateTime($_SESSION['date01']);
                  $_SESSION['date1'] = $_SESSION['date1']->format('Ymd');
                  
                  $_SESSION['date02']=$_POST['j2'];
                  $_SESSION['date2'] = new DateTime($_SESSION['date02']);
                  $_SESSION['date2'] = $_SESSION['date2']->format('Ymd');

                  $_SESSION['dates1']=(new DateTime($_SESSION['date01']))->format('d/m/Y');
                  $_SESSION['dates2']=(new DateTime($_SESSION['date02']))->format('d/m/Y');  
                }


                if (isset($_POST['j2'])) {

                  $datenormale='entre le '.$_SESSION['dates1'].' et le '.$_SESSION['dates2'];

                }else{

                  $datenormale='entre le '.(new DateTime($_GET['date1']))->format('d/m/Y').' et le '.(new DateTime($_GET['date2']))->format('d/m/Y');
                }?>
            <?php

        //require 'pagination.php';

            if ((isset($_GET['general']) or isset($_GET['sscol']) or isset($_POST['j1']) or isset($_POST['j2']) or isset($_GET['spers']))) {


                if (isset($_POST['j1']) and isset($_POST['j2'])) {

                    $proddec =$DB->query('SELECT accompte.matricule as matricule, montant, DATE_FORMAT(accompte.datepaye, \'%d/%m/%Y\')AS datepaye, mois, moischaine, nomen as nomen, prenomen as prenomen FROM accompte inner join enseignant  on enseignant.matricule=accompte.matricule inner join personnel on numpers=accompte.matricule WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));

                    $proddecp =$DB->query('SELECT accompte.matricule as matricule, montant, DATE_FORMAT(accompte.datepaye, \'%d/%m/%Y\')AS datepaye, mois, moischaine, nom as nomen, prenom as prenomen FROM accompte inner join personnel on numpers=accompte.matricule WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));

                }else{

                    $proddec =$DB->query('SELECT accompte.matricule as matricule, montant, DATE_FORMAT(accompte.datepaye, \'%d/%m/%Y\')AS datepaye, mois, moischaine, nomen as nomen, prenomen as prenomen, nom, prenom FROM accompte left join enseignant  on enseignant.matricule=accompte.matricule left join personnel on numpers=accompte.matricule WHERE anneescolaire=?', array($_SESSION['promo']));

                    $proddecp =$DB->query('SELECT accompte.matricule as matricule, montant, DATE_FORMAT(accompte.datepaye, \'%d/%m/%Y\')AS datepaye, mois, moischaine, nom as nomen, prenom as prenomen FROM accompte inner join personnel on numpers=accompte.matricule WHERE anneescolaire=?', array($_SESSION['promo']));
                }?>

                <table class="payement">

                    <thead>

                        <tr>

                            <form id='formulaire' method="POST" action="syntheseacompte.php" name="termc" style="height: 30px;">

                                <th colspan="6"><?php

                                    if (isset($_POST['j1']) or isset($_GET['date1'])) {?>

                                      <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"><?php

                                    }else{?>

                                      <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" onchange="this.form.submit()"><?php

                                    }

                                    if (isset($_POST['j2']) or isset($_GET['date2'])) {?>

                                      <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"><?php

                                    }else{?>

                                      <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" onchange="this.form.submit()"><?php

                                    }?><?='Avance sur Salaire '.$datenormale;?><a style="margin-left: 10px;"href="csv.php?accompte" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
                                </th>
                            </form>
                        </tr>

                        <tr>
                          <th height="30"></th>
                          <th>Matricule</th>
                          <th>Bénéficiaires</th>
                          <th>Mois</th>
                          <th>Montant</th>
                          <th>Date</th>
                        </tr>

                    </thead>

                    <tbody><?php

                        $totpaye=0;
                        $toth=0;
                        foreach( $proddec as $key => $product ){

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

                            $nom=ucwords($product->prenomen).' '.strtoupper($product->nomen);

                            if (!empty($product->prenom)) {
                                $nom=ucwords($product->prenom).' '.strtoupper($product->nom);
                            }

                            $totpaye+=$product->montant;?>

                            <tr>
                                    
                                <td style="text-align: center;"><?=$key+1;?> </td>

                                <td height="25"><?=$product->matricule;?></td>

                                <td height="25"><?=$nom;?></td>

                                <td style="text-align: left;"><?= $mois;?></td>

                                <td style="text-align: right;"><?= number_format($product->montant,0,',',' ');?></td>

                                <td><?=$product->datepaye;?></td><?php
                            } ?>

                    </tbody>
                    <thead>

                        <tr>
                          <th colspan="4" height="30">Total: </th>
                          <th style="text-align: right;"><?=number_format($totpaye,0,',',' ');?></th>
                        </tr>
                    </thead>
            </table><?php
        }

    }
}?>


