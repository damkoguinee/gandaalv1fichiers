 <?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
        
  if ($products['niveau']<4) {?>

      <div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

  }else{?>
    <div class="container-fluid">
      <div class="row"><?php

        require 'navcompta.php';?>

        <div class="col-sm-12 col-md-10"><?php

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
          }
          //require 'pagination.php';

          if ((isset($_GET['general']) or isset($_POST['j1']) or isset($_POST['j2']) or isset($_GET['sscol']) or isset($_GET['ssins']))) {?><?php 

            if ((isset($_GET['sscol']))) {?>

              <table class="table table-hover table-bordered table-striped table-responsive text-center">
                <thead>
                  <tr>
                    <form class="form" method="POST" action="synthesescolarite.php?sscol" name="termc">
                      <th colspan="6">
                        <div class="row">
                          <div class="col-sm-6 col-md-3"><?php

                            if (isset($_POST['j1']) or isset($_GET['date1'])) {?>

                              <input class="form-control" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"><?php

                            }else{?>

                              <input class="form-control" type = "date" name = "j1" onchange="this.form.submit()"><?php

                            }?>
                          </div>
                          <div class="col-sm-6 col-md-3"><?php

                            if (isset($_POST['j2']) or isset($_GET['date2'])) {?>

                              <input class="form-control" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"><?php

                            }else{?>

                              <input class="form-control" type = "date" name = "j2" onchange="this.form.submit()"><?php

                            }?>
                          </div>
                          <div class="col-sm-6 col-md-6">

                            <?='Paiements des Frais de scolarité '.$datenormale;?><a class="btn btn-warning" href="csv.php?fraiscol" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
                          </div>
                        </div>
                      </th>
                    </form>
                  </tr>

                  <tr>
                    <th>N°</th>
                    <th>Motif</th>
                    <th>Nom & Prénom</th>
                    <th>Montant</th>
                    <th>Remise</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody><?php

                  if (isset($_POST['j1']) and isset($_POST['j2'])) {


                      $location =$DB->query('SELECT montant, remise, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, payementfraiscol.matricule as matricule, nomel, prenomel FROM payementfraiscol inner join eleve on eleve.matricule=payementfraiscol.matricule inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and annee=:promo and promo=:promo1', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo'], 'promo1'=>$_SESSION['promo']));

                  }else{

                      $location =$DB->query('SELECT montant, remise, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, payementfraiscol.matricule as matricule, nomel, prenomel FROM payementfraiscol inner join eleve on eleve.matricule=payementfraiscol.matricule inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE annee=:promo and promo=:promo1', array('promo'=>$_SESSION['promo'], 'promo1'=>$_SESSION['promo']));
                  }
                  $totpaye=0;
                  foreach( $location as $keyf=> $payeloc ){

                    $montantrem=$payeloc->montant;

                    $nom=strtoupper($payeloc->nomel).' '.ucwords($payeloc->prenomel);
                    $totpaye+=$montantrem;?>

                    <tr>
                        <td><?=$keyf+1;?></td>

                        <td class="text-start"><?='Paiement scolarité '; ?> de <a href="comptabilite.php?eleve=<?=$payeloc->matricule;?>"><?=$payeloc->matricule;?></a></td>

                        <td class="text-start"><?=$nom; ?></td>

                        <td class="text-end"><?= number_format($payeloc->montant,2,',',' ');?></td>

                        <td><?= number_format($payeloc->remise,2,',',' ');?>%</td>

                        <td><?=$payeloc->datepaye;?></td>

                    </tr><?php
                  } ?>
                </tbody>
                <tfoot>

                  <tr>
                    <th colspan="3">Total </th>
                    <th class="text-end"><?=number_format($totpaye,2,',',' ');?></th>
                  </tr>
                </tfoot>
              </table><?php 
            }

            if ((isset($_GET['ssins']))) {?>

              <table class="table table-hover table-bordered table-striped table-responsive text-center">
                <thead>
                    <tr>
                      <form class="form" method="POST" action="synthesescolarite.php?ssins" name="termc">
                        <th colspan="6">
                          <div class="row">
                            <div class="col-sm-6 col-md-3"><?php

                              if (isset($_POST['j1']) or isset($_GET['date1'])) {?>

                                <input class="form-control" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"><?php

                              }else{?>

                                <input class="form-control" type = "date" name = "j1" onchange="this.form.submit()"><?php

                              }?>
                            </div>
                            <div class="col-sm-6 col-md-3"><?php

                              if (isset($_POST['j2']) or isset($_GET['date2'])) {?>

                                <input class="form-control" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"><?php

                              }else{?>

                                <input class="form-control" type = "date" name = "j2" onchange="this.form.submit()"><?php

                              }?>
                            </div>
                            <div class="col-sm-6 col-md-6">

                              <?='Paiements des frais inscription/reinscription '.$datenormale;?><a class="btn btn-warning" href="csv.php?inscrip" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
                            </div>
                          </div>
                        </th>
                      </form>
                    </tr>

                    <tr>
                      <th>N°</th>
                      <th>Motif</th>
                      <th>Nom & Prénom</th>
                      <th>Montant</th>
                      <th>Remise</th>
                      <th>Date</th>
                    </tr>
                  </thead>

                    <tbody><?php

                        if (isset($_POST['j1']) and isset($_POST['j2'])) {


                            $location =$DB->query('SELECT montant, remise, motif, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, payement.matricule as matricule, nomel, prenomel FROM payement inner join eleve on eleve.matricule=payement.matricule WHERE DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 and promo=:promo', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo']));

                        }else{

                            $location =$DB->query('SELECT montant, remise, motif, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, payement.matricule as matricule, nomel, prenomel FROM payement inner join eleve on eleve.matricule=payement.matricule WHERE promo=?', array($_SESSION['promo']));
                        }
                        $totpaye=0;

                        foreach( $location as $keyp=> $payeloc ){
                            $montantrem=$payeloc->montant;
                            $nom=strtoupper($payeloc->nomel).' '.ucwords($payeloc->prenomel);
                            $totpaye+=$montantrem;?>
                            <tr>
                                <td><?=$keyp+1;?></td>

                                <td class="text-start"><?='Payement des frais d\''.$payeloc->motif; ?> de <a href="comptabilite.php?eleve=<?=$payeloc->matricule;?>"><?=$payeloc->matricule;?></a></td>

                                <td class="text-start"><?=$nom; ?></td>

                                <td class="text-end"><?= number_format($montantrem,2,',',' ');?></td>

                                <td><?= number_format($payeloc->remise,2,',',' ');?>%</td>

                                <td><?=$payeloc->datepaye;?></td>
                            </tr><?php
                        } ?>

                    </tbody>
                    <thead>

                        <tr>
                          <th colspan="3" height="30">Total </th>
                          <th class="text-end"><?=number_format($totpaye,2,',',' ');?></th>
                        </tr>
                    </thead>
                </table><?php 
            }
          }?>
        </div>
      </div>
    </div><?php
  }
}?>


