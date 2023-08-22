 <?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
        
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

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

            if ((isset($_GET['general']) or isset($_POST['annee']) or isset($_POST['mensuelle']) or isset($_POST['jour']) or isset($_GET['sdep']))) {?>
                <table class="payement">

                    <thead>

                        <tr>
                            

                            <form id='formulaire' method="POST" action="synthesedepense.php?sdep" name="termc" style="height: 30px;">

                                <th colspan="3"><?php

                                if (isset($_POST['j1']) or isset($_GET['date1'])) {?>

                                    <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"><?php

                                }else{?>

                                    <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" onchange="this.form.submit()"><?php

                                }

                                if (isset($_POST['j2']) or isset($_GET['date2'])) {?>

                                    <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"><?php

                                }else{?>

                                    <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" onchange="this.form.submit()"><?php

                                }?><?='Liste des depenses de '.$datenormale;?><a style="margin-left: 10px;"href="csv.php?dec" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
                            </th>
                            </form>
                            
                        </tr>

                        <tr>
                            <th>N°</th>
                            <th>Prestation</th>
                            <th>Montant</th>
                        </tr>

                    </thead>

                    <tbody><?php

                        if (isset($_POST['j1']) and isset($_POST['j2'])) {


                            $proddec =$DB->query('SELECT montant, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, matricule, motif, coment FROM decaissement WHERE promo=:promo and DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2', array('promo'=>$_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));

                        }else{

                            $proddec =$DB->query('SELECT montant, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, matricule, motif, coment FROM decaissement WHERE promo=?', array($_SESSION['promo']));
                        }
                        $totpaye=0;
                        foreach( $proddec as $key=> $dec ):

                            $totpaye+=$dec->montant;?>

                            <tr>
                                <td style="text-align:center;"><?=$key+1;?></td><?php
                                if ($dec->motif!='depense') {?>
                                    
                                    <td>Depense du <?=$dec->datepaye;?> pour payement employer N° <a href="comptabilite.php?enseignant=<?=$dec->matricule;?>"><?=$dec->matricule;?></a> </td><?php

                                }else{?>
                                    
                                    <td>Depense du <?=$dec->datepaye;?> pour <?=ucfirst(strtolower($dec->coment));?></td><?php

                                }?>

                                <td style="text-align: right;"><?= number_format($dec->montant,0,',',' ');?></td>
                            </tr>
                        <?php endforeach ?>

                    </tbody>
                    <thead>

                        <tr>
                            <th colspan="2">Total: </th>
                            <th style="text-align: right;"><?=number_format($totpaye,0,',',' ');?></th>
                        </tr>
                    </thead>
                </table><?php
            }

        }
    }?>


