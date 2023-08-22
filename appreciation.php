<?php require 'header.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<3) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

        <div style="width:99%;">
            <div><?php

                require 'formappreciation.php'; ?>

            <div>

            <div><?php

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


                    <table class="payement">

                        <thead>
                            <tr>
                                <th colspan="6" class="info" style="text-align: center">Appréciations des élèves de la <?=$_SESSION['groupe'];?>.  Période: <?=$_SESSION['periodeap'];?>
                                <a style="margin-left: 10px;"href="printdocapp.php?voir_eleveap=<?=$_SESSION['eleveap'];?>&codef=<?=$_SESSION['codefmat'];?>&periode=<?=$_SESSION['periodesaisie'];?>&periodeap=<?=$_SESSION['periodeap'];?>&promo=<?=$_SESSION['promo'];?>&indi" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                                <a style="margin-left: 10px;"href="printdocapp.php?voir_eleveap=<?=$_SESSION['eleveap'];?>&codef=<?=$_SESSION['codefmat'];?>&periode=<?=$_SESSION['periodesaisie'];?>&periodeap=<?=$_SESSION['periodeap'];?>&promo=<?=$_SESSION['promo'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
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

                                <form method="POST" action="appreciation.php"> 

                                    <tr>
                                        <td style="text-align: center;"><?=$key+1;?></td>                                   

                                        <td><?=ucfirst(strtolower($formation->nommat));?>
                                            <input type="hidden" name="codefmat" value="<?=$_SESSION['codefmat'];?>">
                                            <input type="hidden" name="codem" value="<?=$formation->codem;?>">
                                            <input type="hidden" name="eleveap" value="<?=$_SESSION['eleveap'];?>">
                                        </td>

                                        <td style="text-align:center;"><?=$formation->coef;?></td>

                                        <td style="text-align:center;">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td style="border: 0px;">
                                                            <select name="app" required="" style="width: 90%;">
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

                                        <td><textarea style="width:95%; height: 30px;" type="text" name="com" maxlength="200"></textarea></td>

                                        <td><input type="submit" value="Valider" name="validap" style="width: 100%; font-size: 16px;  cursor: pointer">
                                        </td>

                                    </tr>
                                </form><?php
                            }
                        }?>

                            
                        </tbody>

                        <tfoot>
                            <form method="POST" action="appreciation.php">
                                <tr>
                                    <th colspan="3">Appréciation Générale</th>
                                    <th style="text-align:center;">
                                        <input type="hidden" name="codefmat" value="<?=$_SESSION['codefmat'];?>"><input type="hidden" name="eleveap" value="<?=$_SESSION['eleveap'];?>">
                                        <select name="app" required="" style="width: 90%;">
                                            <option></option>
                                            <option value="Très-bien">Très-bien</option>
                                            <option value="Bien">Bien</option>
                                            <option value="Assez-bien">Asses-bien</option>
                                            <option value="Passable">Passable</option>
                                        
                                        </select>
                                    </th>
                                    <th><textarea style="width:95%; height: 30px;" type="text" name="com" maxlength="200"></textarea></th>
                                    <th><input type="submit" value="Valider" name="validapg" style="width: 100%; font-size: 16px;  cursor: pointer"></th>
                                </tr>
                            </form>
                        </tfoot>
                    </table><?php
                }?>
                
            </div>
        </div><?php
    }
}