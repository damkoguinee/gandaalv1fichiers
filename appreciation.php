<?php require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<3) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

        <div class="container-fluid"><?php

            require 'formappreciation.php'; ?>

            <div class="row"><?php

                if (isset($_POST['validap'])) {
                    $codef=$_POST['codefmat'];
                    $codem=$_POST['codem'];
                    $matricule=$_POST['eleveap'];
                    $app=$_POST['app'];
                    $com=$_POST['com'];
                    $periode=$_SESSION['periodesaisie'];
                    $saisie=$_SESSION['matricule'];

                    $prodverif=$DB->querys("SELECT id from appreciation where codefap='{$codef}' and codematap='{$codem}' and periodeap='{$periode}' and promoap='{$_SESSION['promo']}'");

                    if (empty($prodverif)) {

                        $DB->insert("INSERT INTO appreciation(codefap, codematap, matriculeap, periodeap, appreciation, commentaires, saisie, promoap, dateap) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())", array($codef, $codem, $matricule, $periode, $app, $com, $saisie, $_SESSION['promo']));
                    }else{
                        $DB->insert("UPDATE appreciation SET appreciation='{$app}', commentaires='{$com}' where codefap='{$codef}' and codematap='{$codem}' and periodeap='{$periode}' and promoap='{$_SESSION['promo']}'");
                    }
                    
                    
                }

                if (isset($_POST['validapg'])) {
                    $codef=$_POST['codefmat'];
                    $matricule=$_POST['eleveap'];
                    $app=$_POST['app'];
                    $com=$_POST['com'];
                    $periode=$_SESSION['periodesaisie'];
                    $saisie=$_SESSION['matricule'];

                    $prodverif=$DB->querys("SELECT id from appreciationgen where codefap='{$codef}' and periodeap='{$periode}' and promoap='{$_SESSION['promo']}'");

                    if (empty($prodverif)) {

                        $DB->insert("INSERT INTO appreciationgen(codefap, matriculeap, periodeap, appreciation, commentaires, saisie, promoap, dateap) VALUES(?, ?, ?, ?, ?, ?, ?, now())", array($codef, $matricule, $periode, $app, $com, $saisie, $_SESSION['promo']));
                    }else{
                        $DB->insert("UPDATE appreciationgen SET appreciation='{$app}', commentaires='{$com}' where codefap='{$codef}' and periodeap='{$periode}' and promoap='{$_SESSION['promo']}'");
                    }
                    
                    
                }

                if (isset($_POST['eleveap']) or isset($_POST['eleveap'])) {
                   
                    $prodmatiere=$DB->query("SELECT *from matiere where codef='{$_SESSION['codefmat']}'");?>


                    <table class="table table-bordered table-striped table-hover align-middle">

                        <thead class="sticky-top text-center bg-light">
                            <tr>
                                <th colspan="6">
                                    Appréciations des élèves de la <?=$_SESSION['groupe'];?>.  Période: <?=$_SESSION['periodeap'];?>
                                    <a class="btn btn-info" href="printdocapp.php?voir_eleveap=<?=$_SESSION['eleveap'];?>&codef=<?=$_SESSION['codefmat'];?>&periode=<?=$_SESSION['periodesaisie'];?>&periodeap=<?=$_SESSION['periodeap'];?>&promo=<?=$_SESSION['promo'];?>&indi" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                                    <a class="btn btn-info" href="printdocapp.php?voir_eleveap=<?=$_SESSION['eleveap'];?>&codef=<?=$_SESSION['codefmat'];?>&periode=<?=$_SESSION['periodesaisie'];?>&periodeap=<?=$_SESSION['periodeap'];?>&promo=<?=$_SESSION['promo'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>
                                </th>
                            </tr>

                            <tr>
                                <th height="30">N°</th>
                                <th>Matières</th>
                                <th>Coef</th>
                                <th>Appréciations</th>
                                <th>Commenataires</th>
                                <th></th>
                            </tr>

                        </thead>

                        <tbody><?php
                        if (empty($prodmatiere)) {
                            # code...
                        }else{

                            foreach ($prodmatiere as $key=> $formation) {?>

                                <form class="form " method="POST" action="appreciation.php"> 

                                    <tr>
                                        <td class="text-center"><?=$key+1;?></td>                                   

                                        <td><?=ucfirst(strtolower($formation->nommat));?>
                                            <input class="form-control" type="hidden" name="codefmat" value="<?=$_SESSION['codefmat'];?>">
                                            <input class="form-control" type="hidden" name="codem" value="<?=$formation->codem;?>">
                                            <input class="form-control" type="hidden" name="eleveap" value="<?=$_SESSION['eleveap'];?>">
                                        </td>

                                        <td style="text-align:center;"><?=$formation->coef;?></td>

                                        <td style="text-align:center;">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td style="border: 0px;">
                                                            <select class="form-select" name="app" required="">
                                                                <option></option>
                                                                <option value="Très-bien">Très-bien</option>
                                                                <option value="Bien">Bien</option>
                                                                <option value="Assez-bien">Asses-bien</option>
                                                                <option value="Passable">Passable</option>
                                                            
                                                            </select>
                                                        </td><?php 

                                                        $prodappgen=$DB->querys("SELECT appreciation from appreciation where codematap='{$formation->codem}' and codefap='{$_SESSION['codefmat']}' and periodeap='{$_SESSION['periodesaisie']}' and promoap='{$_SESSION['promo']}'");

                                                        if (empty($prodappgen['appreciation'])) {?>

                                                            <td style="border: 0px;"></td><?php

                                                        }else{?>

                                                            <td style="border: 0px;"><img  style="margin-top: 10px; height: 15px; width: 15px;" src="css/img/checkbox.jpg"></td><?php 
                                                        }?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </td>

                                        <td><textarea class="form-control" type="text" name="com" maxlength="200"></textarea></td>

                                        <td><button class="btn btn-primary" type="submit" name="validap">Valider</button>
                                        </td>

                                    </tr>
                                </form><?php
                            }
                        }?>

                            
                        </tbody>

                        <tfoot>
                            <form class="form bg-success" method="POST" action="appreciation.php">
                                <tr class="bg-success">
                                    <th colspan="3">Appréciation Générale</th>
                                    <th style="text-align:center;">
                                        <input class="form-control" type="hidden" name="codefmat" value="<?=$_SESSION['codefmat'];?>"><input class="form-control" type="hidden" name="eleveap" value="<?=$_SESSION['eleveap'];?>">
                                        <select class="form-select" name="app" required="">
                                            <option></option>
                                            <option value="Très-bien">Très-bien</option>
                                            <option value="Bien">Bien</option>
                                            <option value="Assez-bien">Asses-bien</option>
                                            <option value="Passable">Passable</option>
                                        
                                        </select>
                                    </th>
                                    <th><textarea class="form-control" type="text" name="com" maxlength="200"></textarea></th>
                                    <th><button class="btn btn-primary" type="submit" name="validapg">Valider</button></th>
                                </tr>
                            </form>
                        </tfoot>
                    </table><?php
                }?>
                
            </div>
        </div><?php
    }
}