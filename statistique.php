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

                if (isset($_GET['stat']) or isset($_POST['groupe'])) {
                    $_SESSION['annee']=' ';
                    $dateselect=' ';
                }

                if (isset($_POST['annee'])) {
                    $_SESSION['annee']=$_POST['annee'];
                    $_SESSION['mensuelle']="Selectionnez le mois !!";
                    $_SESSION['datesm']=$_POST['annee'];

                    $dateselect=$_POST['annee'];

                    $_SESSION['groupe']='Choix du groupe';
                }

                if (isset($_POST['mensuelle'])) {
                    $_SESSION['mensuelle']=$_POST['mensuelle'];
                    $dateselect=$_POST['mensuelle'];
                    $_SESSION['groupe']='Choix du groupe';
                }

                if (isset($_POST['jour'])) {
                    $_SESSION['jour']=$_POST['jour'];
                    $dateselect=$_POST['jour'];
                    $_SESSION['groupe']='Choix du groupe';
                }

                if (isset($_POST['groupe'])){

                    $_SESSION['groupe']=$_POST['groupe'];
                    $dateselect=$_POST['groupe'];
                }

                if (isset($_POST['collabo'])) {
                    $_SESSION['nomcollabo']=$_POST['collabo'];
                }

                if (isset($_POST['location'])) {
                    $_SESSION['nomloca']=$_POST['location'];
                }

                 $prodgroup=$DB->query('SELECT groupe.nomgr as nomgr, codef from groupe  where promo=:promo', array('promo'=>$_SESSION['promo']));

                 if (isset($_POST['groupe'])) {

                      $prodcodef=$DB->querys('SELECT codef from groupe  where promo=:promo and nomgr=:nom', array('promo'=>$_SESSION['promo'], 'nom'=>$_POST['groupe']));
                  }?>

                <div class="col" style="display: flex; width: 100%;">

                    <form id='formulaire' method="POST" action="statistique.php" name="termc" style="height: 30px;"> 

                        <ol style="margin-left: -50px; margin-top: -10px;">

                            <li>
                                <?='<select style=" font-size: 14px;"  type="number" name="annee" required="" onchange="this.form.submit();">',"n";

                                    if (isset($_POST['annee']) or isset($_POST['mensuelle']) or isset($_POST['jour'])) {?>

                                        <option value=""><?="Année ".$_SESSION['annee'];?></option><?php

                                      }else{

                                        echo "\t",'<option value="">Choisir une année...</option>',"\n";

                                      }

                                    $annee=date("Y");

                                    for($i=2019;$i<=$annee ;$i++){

                                      echo "\t",'<option value="', $i,'">', $i,'</option>',"\n";

                                    }?>
                                </select>
                            </li>
                        </ol>
                    </form>

                    <form id='formulaire' method="POST" action="statistique.php" name="termc" style="margin-left: -20px; height: 30px;"> 

                        <ol style="margin-left: -50px; margin-top: -10px;">

                            <li>

                                <select id="reccode" style=" font-size: 14px;" type = "number" name ="mensuelle" onchange="this.form.submit()"><?php

                                    if (isset($_POST['mensuelle']) or isset($_POST['jour'])) {?>

                                      <option value=""><?=$_SESSION['mensuelle'];?></option><?php

                                    }else{?>

                                      <option value="">Selectionnez le mois !!</option><?php

                                    }
                                    
                                    $mois=0;
                                    if ($_SESSION['datesm']==date('Y')) {
                                      
                                      while ( $mois<= date("m")-1) {
                                        $mois+=1;
                                        if ($mois<10) {?>
                                          <option value="<?='0'.$mois."/".$_SESSION['datesm']; ?>"><?='0'.$mois."/".$_SESSION['datesm']; ?></option><?php
                                        }else{?>
                                          <option value="<?=$mois."/".$_SESSION['datesm']; ?>"><?=$mois."/".$_SESSION['datesm']; ?></option><?php
                                        }
                                      }
                                    }else{
                                        while ( $mois<=11) {
                                            $mois+=1;
                                            if ($mois<10) {?>
                                              <option value="<?='0'.$mois."/".$_SESSION['datesm']; ?>"><?='0'.$mois."/".$_SESSION['datesm']; ?></option><?php
                                            }else{?>
                                              <option value="<?=$mois."/".$_SESSION['datesm']; ?>"><?=$mois."/".$_SESSION['datesm']; ?></option><?php
                                            }
                                        }

                                    }?>
                                </select>
                            </li>
                        </ol>
                    </form>

                    <form id='form' method="POST" action="statistique.php" name="termc" style="margin-left: -20px; height: 30px;"> 

                        <ol style="margin-left: -50px; margin-top: -10px;">

                            <li><?php

                                if (isset($_POST['jour'])) {?>

                                    <input style=" font-size: 14px;" type = "date"  name="jour" value="<?= $_SESSION['jour']; ?>" onchange="document.getElementById('form').submit()"/><?php

                                }else{?>

                                    <input style=" font-size: 14px;" type = "date"  name="jour" onchange="document.getElementById('form').submit()"/><?php
                                    
                                }?>

                                    
                            </li>

                        </ol>

                    </form>


                    <form id="formulaire" method="POST" action="statistique.php" name="termc" style="margin-left: -20px; height: 30px;">
                        <ol style="margin-left: -50px; margin-top: -10px;">
                            <li><select type="text" name="groupe" required="" onchange="this.form.submit()"><?php

                                if (isset($_POST['groupe']) or isset($_POST['annee']) or isset($_POST['mensuelle']) or isset($_POST['jour'])) {?>

                                    <option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php

                                }else{?>

                                    <option>Choix du groupe</option><?php
                                }

                                foreach ($prodgroup as $form) {?>

                                    <option><?=$form->nomgr;?></option><?php

                                }?></select>
                            </li>
                        </ol>
                    </form>


                    
                </div>
            <?php

        //require 'pagination.php';

            if ((isset($_GET['general']) or isset($_POST['annee']) or isset($_POST['mensuelle']) or isset($_POST['jour']) or isset($_POST['groupe']) or isset($_GET['stat']))) {?>

                <div class="col">

                    <div style="width: 110%;">

                        <table class="statistique">

                            <thead>

                                <tr>
                                  <th colspan="3" height="30"><?='Cumul Horaires des Enseignants '.$dateselect;?><a style="margin-left: 10px;"href="csv.php?perso" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a></th>
                                </tr>

                                <tr>
                                  <th height="30">Heures Travaillées</th>
                                  <th>Heures Payées</th>
                                  <th>Reste à Payer</th>
                                </tr>

                            </thead>

                            <tbody><?php

                            if (isset($_POST['annee']) and !empty($_POST['annee'])) {

                                $prodheuret =$DB->querys('SELECT sum(heuret) as heuret FROM horairet WHERE DATE_FORMAT(datesaisie, \'%Y\')=:annee', array('annee' => $_SESSION['annee']));

                                $prodheurep =$DB->querys('SELECT sum(heurep) as heurep FROM  payenseignant WHERE DATE_FORMAT(datepaye, \'%Y\')=:annee and motif=:motif', array('annee' => $_SESSION['annee'], 'motif'=>'payementem')); 

                            }elseif (isset($_POST['mensuelle']) and !empty($_POST['mensuelle'])) {

                                $prodheuret =$DB->querys('SELECT sum(heuret) as heuret FROM horairet WHERE DATE_FORMAT(datesaisie, \'%m/%Y\')=:annee ', array('annee' => $_SESSION['mensuelle']));

                                $prodheurep =$DB->querys('SELECT sum(heurep) as heurep FROM  payenseignant WHERE DATE_FORMAT(datepaye, \'%m/%Y\')=:annee and motif=:motif', array('annee' => $_SESSION['mensuelle'], 'motif'=>'payementem'));

                            }elseif (isset($_POST['jour']) and !empty($_POST['jour'])){

                                $prodheuret =$DB->querys('SELECT sum(heuret) as heuret FROM horairet WHERE DATE_FORMAT(datesaisie, \'%Y-%m-%d\')=:annee', array('annee' => $_SESSION['jour']));

                                $prodheurep =$DB->querys('SELECT sum(heurep) as heurep FROM  payenseignant WHERE DATE_FORMAT(datepaye, \'%Y-%m-%d\')=:annee and motif=:motif', array('annee' => $_SESSION['jour'], 'motif'=>'payementem'));



                            }else{
                                 $prodheuret =$DB->querys('SELECT sum(heuret) as heuret FROM horairet');

                                  $prodheurep =$DB->querys('SELECT sum(heurep) as heurep FROM  payenseignant WHERE motif=:motif', array('motif'=>'payementem'));
                            }

                            $difference=$prodheuret['heuret']-$prodheurep['heurep']?>

                            <tr>

                                <td style="text-align: center;"><?= $prodheuret['heuret'];?> H<br/><?= number_format(($prodheuret['heuret']*35000),0,',',' ');?> GNF</td>

                                <td style="text-align: center;"><?= $prodheurep['heurep'];?> H<br/><?= number_format(($prodheurep['heurep']*35000),0,',',' ');?> GNF</td>

                                <td style="text-align: center;"><?= $difference;?> H<br/><?= number_format(($difference*35000),0,',',' ');?> GNF</td>

                            </tr>

                        </tbody>
                            
                    </table>
                </div>


                <div style="width: 110%;"><?php

                    $prodscolt1 =$DB->querys('SELECT sum(montant) as montant FROM scolarite WHERE tranche=:tranche and codef=:code and promo=:promo', array('tranche'=>'1ere tranche', 'code'=>$prodcodef['codef'], 'promo' => $_SESSION['promo']));

                    $prodscolt2 =$DB->querys('SELECT sum(montant) as montant FROM scolarite WHERE tranche=:tranche and codef=:code and promo=:promo', array('tranche'=>'2eme tranche', 'code'=>$prodcodef['codef'], 'promo' => $_SESSION['promo']));

                    $prodscolt3 =$DB->querys('SELECT sum(montant) as montant FROM scolarite WHERE tranche=:tranche  and codef=:code and promo=:promo', array('tranche'=>'3eme tranche', 'code'=>$prodcodef['codef'], 'promo' => $_SESSION['promo']));

                    if (isset($_POST['groupe'])) {
                        
                        $prodins =$DB->querys('SELECT count(matricule) as nbre FROM inscription WHERE annee=:promo and nomgr=:nom', array('promo' => $_SESSION['promo'], 'nom'=>$_POST['groupe']));
                    }else{

                        $prodins =$DB->querys('SELECT count(matricule) as nbre FROM inscription WHERE annee=:promo', array('promo' => $_SESSION['promo']));
                    }

                        $tot1=$prodins['nbre']*$prodscolt1['montant'];
                        $tot2=$prodins['nbre']*$prodscolt2['montant'];
                        $tot3=$prodins['nbre']*$prodscolt3['montant'];

                    if (isset($_POST['annee']) and !empty($_POST['annee'])) {                        

                        $prodmt1payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche and DATE_FORMAT(datepaye, \'%Y\')=:annee', array('tranche'=>'1ere tranche', 'annee' => $_SESSION['annee']));

                        $prodmt2payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche and DATE_FORMAT(datepaye, \'%Y\')=:annee', array('tranche'=>'2eme tranche', 'annee' => $_SESSION['annee']));

                        $prodmt3payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche and DATE_FORMAT(datepaye, \'%Y\')=:annee', array('tranche'=>'3eme tranche', 'annee' => $_SESSION['annee'])); 

                    }elseif (isset($_POST['mensuelle']) and !empty($_POST['mensuelle'])) {

                        $prodmt1payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche and DATE_FORMAT(datepaye, \'%m/%Y\')=:annee', array('tranche'=>'1ere tranche', 'annee' => $_SESSION['mensuelle']));

                        $prodmt2payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche and DATE_FORMAT(datepaye, \'%m/%Y\')=:annee', array('tranche'=>'2eme tranche', 'annee' => $_SESSION['mensuelle']));

                        $prodmt3payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche and DATE_FORMAT(datepaye, \'%m/%Y\')=:annee', array('tranche'=>'3eme tranche', 'annee' => $_SESSION['mensuelle']));


                    }elseif (isset($_POST['jour']) and !empty($_POST['jour'])){

                        $prodmt1payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche and DATE_FORMAT(datepaye, \'%Y-%m-%d\')=:annee', array('tranche'=>'1ere tranche', 'annee' => $_SESSION['jour']));

                        $prodmt2payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche and DATE_FORMAT(datepaye, \'%Y-%m-%d\')=:annee', array('tranche'=>'2eme tranche', 'annee' => $_SESSION['jour']));

                        $prodmt3payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche and DATE_FORMAT(datepaye, \'%Y-%m-%d\')=:annee', array('tranche'=>'3eme tranche', 'annee' => $_SESSION['jour']));

                    }else{

                        $prodmt1payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche', array('tranche'=>'1ere tranche'));

                        $prodmt2payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche', array('tranche'=>'2eme tranche'));

                        $prodmt3payer =$DB->querys('SELECT sum(montant) as montant FROM payementfraiscol WHERE tranche=:tranche', array('tranche'=>'3eme tranche'));

                    }

                    $tot=$tot1+$tot2+$tot3;
                    $tott=$prodmt1payer['montant']+$prodmt2payer['montant']+$prodmt3payer['montant'];
                    $difference=$tot-$tott;

                    if (empty($tot)) {

                        $percent=0;
                    }else{
                        $percent=($tott/$tot)*100;
                    }?>

                    <table class="statistique">

                        <thead>

                            <tr>
                              <th colspan="4" height="30"><?='Situation sur les tranches de '.$dateselect;?><a style="margin-left: 10px;"href="csv.php?perso" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a></th>
                            </tr>

                            <tr>
                                <th></th>
                                <th height="30">Montant à Payer</th>
                                <th>Montant Payé</th>
                                <th>Reste à Payer</th>
                            </tr>

                        </thead>

                        <tbody>
                            <tr>
                                <td>1ère Tranche</td>

                                <td><?= number_format($tot1,0,',',' ');?></td>

                                <td><?= number_format($prodmt1payer['montant'],0,',',' ');?></td><?php

                                if (empty($tot1)) {?>

                                    <td><br/><?= number_format($tot1-$prodmt1payer['montant'],0,',',' ');?><br/> <label style="color: red; font-size: 28px; margin-left: 25%;">' '</label></td><?php
                                }else{?>

                                    <td><br/><?= number_format($tot1-$prodmt1payer['montant'],0,',',' ');?><br/> <label style="color: red; font-size: 28px; margin-left: 25%;"><?=number_format(($prodmt1payer['montant']/$tot1)*100,2,',',' ');?>%</label></td><?php
                                }?>

                            </tr>

                            <tr>
                                <td>2ème Tranche</td>

                                <td><?= number_format($tot2,0,',',' ');?></td>

                                <td><?= number_format($prodmt2payer['montant'],0,',',' ');?></td><?php

                                if (empty($tot2)) {?>

                                    <td><br/><?= number_format($tot2-$prodmt2payer['montant'],0,',',' ');?><br/> <label style="color: red; font-size: 28px; margin-left: 25%;">' '</label></td><?php
                                }else{?>

                                    <td><br/><?= number_format($tot2-$prodmt2payer['montant'],0,',',' ');?><br/> <label style="color: red; font-size: 28px; margin-left: 25%;"><?=number_format(($prodmt2payer['montant']/$tot2)*100,2,',',' ');?>%</label></td><?php
                                }?>
                            </tr>
                            <tr>
                                <td>3ème Tranche</td>

                                <td><?= number_format($tot3,0,',',' ');?></td>

                                <td><?= number_format($prodmt3payer['montant'],0,',',' ');?></td><?php

                                if (empty($tot3)) {?>

                                    <td><br/><?= number_format($tot3-$prodmt3payer['montant'],0,',',' ');?><br/> <label style="color: red; font-size: 28px; margin-left: 25%;">' '</label></td><?php
                                }else{?>

                                    <td><br/><?= number_format($tot3-$prodmt3payer['montant'],0,',',' ');?><br/> <label style="color: red; font-size: 28px; margin-left: 25%;"><?=number_format(($prodmt3payer['montant']/$tot3)*100,2,',',' ');?>%</label></td><?php
                                }?>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th><?= number_format($tot,0,',',' ');?></th>
                                <th><?= number_format($tott,0,',',' ');?></th>
                                <th><br/><?= number_format($difference,0,',',' ');?><br/> <label style="color: red; font-size: 28px; margin-left: 25%;"><?=number_format($percent,2,',',' ');?>%</label></th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div><?php
            }

        }
    }?>


