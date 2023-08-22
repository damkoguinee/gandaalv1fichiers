<?php
require 'headerv3.php';?>

<div class="container-fluid">
  <div class="row"><?php

    if (isset($_SESSION['pseudo'])) {
        
      if ($products['niveau']<4) {?>

        <div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

      }else{

        if (!isset($_POST['j1'])) {
          $_SESSION['date01']=date("Y-m-d");
          $_SESSION['date02']=date("Y-m-d");

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
        }

        require 'navcompta.php';?><?php

        if ((isset($_GET['general']) or isset($_GET['sscol']) or isset($_POST['j1']) or isset($_POST['j2']) or isset($_GET['spers']))) {?>

          <div class="col-sm-12 col-md-10">

            <table class="table table-bordered table-striped table-hover table-responsive text-center">

              <thead>
                <form class="form" method="POST" action="?sscol" name="termc">

                  <th colspan="5">
                    <div class="row">
                      <div class="col-sm-6 col-md-3"><?php
                        if (isset($_POST['j1']) or isset($_GET['date1'])) {?>

                          <input class="form-control" id="reccode" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"><?php

                        }else{?>

                          <input class="form-control" id="reccode" type = "date" name = "j1" onchange="this.form.submit()"><?php

                        }?>
                      </div>
                      <div class="col-sm-6 col-md-3"><?php
                        if (isset($_POST['j2']) or isset($_GET['date2'])) {?>

                          <input class="form-control" id="reccode" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"><?php

                        }else{?>

                          <input class="form-control" id="reccode" type = "date" name = "j2" onchange="this.form.submit()"><?php

                        }?>
                      </div>
                      <div class="col-sm-6 col-md-6">
                        <?='Paiements des personnels '.$datenormale;?><a class="btn btn-warning" href="csv.php?perso" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
                      </div>
                    </div>
                  </th>
                </form>
                <tr>
                  <th height="30">Motif</th>
                  <th>Nom</th>
                  <th>Mois</th>
                  <th>Montant</th>
                  <th>Date</th>
                </tr>
              </thead>

              <tbody><?php

                if (isset($_POST['j1']) and isset($_POST['j2'])) {

                  $proddec =$DB->query('SELECT numpers as matricule, montant, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, motif, mois, nom as nomen, prenom as prenomen FROM payepersonnel inner join personnel on numpers=matricule WHERE promo=:promo and DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2', array('promo' => $_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));

                }else{
                  $proddec =$DB->query('SELECT  numpers as matricule, montant, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, motif, mois, nom as nomen, prenom as prenomen FROM payepersonnel inner join personnel on numpers=matricule WHERE promo=:promo', array('promo' => $_SESSION['promo'])); 
                }
                $totpaye=0;
                $toth=0;
                foreach( $proddec as $product ){
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

                  $nom=strtoupper($product->nomen).' '.ucwords($product->prenomen);

                  $totpaye+=$product->montant;?>

                  <tr><?php
                  if ($product->motif!='payementpers') {?>
                      
                      <td class="text-start">Payement enseignant</td><?php

                  }else{?>
                      
                      <td class="text-start">Payement personnel </td><?php

                  }?>

                  <td class="text-start"><?=$nom;?></td>

                  <td><?= $mois;?></td>

                  <td class="text-end"><?= number_format($product->montant,0,',',' ');?></td>

                  <td><?=$product->datepaye;?></td><?php
                } ?>

              </tbody>
              <tfoot>
                <tr>
                  <th colspan="2" height="30">Total: </th>
                  <th><?=$toth;?></th>
                  <th class="text-end"><?=number_format($totpaye,0,',',' ');?></th>
                </tr>
              </tfoot>
            </table>
          </div><?php
        }
      }
    }?>
  </div>
</div>


