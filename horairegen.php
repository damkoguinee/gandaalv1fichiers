<?php require 'headerv2.php';?>

<div class="container-fluid">
    <div class="row">
        <div class="col" style="overflow: auto;"><?php 



            if (isset($_POST['mois'])) {
                $_SESSION['moisp']=$_POST['mois'];
            }else{
                $_SESSION['moisp']=date('Y-m-d');
            }

            if(isset($_POST['confirm'])){

                $prodverif=$DB->querys('SELECT id FROM horairet where numens=:num and heured=:heuret and datet=:datet and annees=:promo', array('num'=>$_POST['mat'], 'heuret'=>$_POST['hdebut'], 'datet'=>$_POST['dated'], 'promo'=>$_POST['promo']));
               
                if (empty($prodverif)) {

                    $id=$panier->h($_POST['id']);
                    $mat=$panier->h($_POST['mat']);
                    $hdebut=$panier->h($_POST['hdebut']);
                    $htot=$panier->h($_POST['htot']);
                    $dated=$panier->h($_POST['dated']);
                    $nomgr=$panier->h($_POST['nomgr']);
                    $nommat=$panier->h($_POST['nommat']);
                    $promo=$panier->h($_POST['promo']);
                    $hreal=$panier->h($_POST['hreal']);


                    if ($hreal==0) {
                        
                        $DB->insert('INSERT INTO horairet(idevent, numens, heured, heuret, datet, groupe, matiere, annees, datesaisie) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())',array($id, $mat, $hdebut, -$htot, $dated, $nomgr, $nommat, $promo));
                    }else{

                        $DB->insert('INSERT INTO horairet(idevent, numens, heured, heuret, datet, groupe, matiere, annees, datesaisie) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())',array($id, $mat, $hdebut, $hreal, $dated, $nomgr, $nommat, $promo));

                    }?>

                    <div class="alert alert-success">Heure(s) enregistrées avec succèe!!</div><?php
                }else{?>

                    <div class="alert alert-warning">Heure(s) déjà enregistrées</div><?php

                }

            }?>

            <table class="table table-hover table-bordered table-striped table-responsive text-center">
                <thead>
                    
                    <tr>
                        
                        <th colspan="4">

                            <form class="form" method="POST" action="horairegen.php" id="suitec" name="termc">
                                
                                <input class="form-control" type="date" name="mois" value="<?=$_SESSION['moisp'];?>" onchange="this.form.submit()">
                            </form>
                        </th>

                        <th colspan="7">
                            <a class="btn btn-info" href="exportplanning.php?horairep" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6">Horaires planifiées</th>

                        <th colspan="5">

                            <form class="form" method="POST" action="horairegen.php" id="suitec" name="termc">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                            
                                            <input class="form-control" type="hidden" name="mois" value="<?=$_SESSION['moisp'];?>">

                                            <input class="form-control" type = "search" name = "termec" placeholder="rechercher !!!!">
                                            <input class="form-control"  type = "hidden" name = "effnav" value = "search">
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <button class="btn btn-primary" type = "submit" name = "s">Rechercher</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </th>
                            
                    </tr>

                    <tr>
                        <th></th>
                        <th height="30">N°M</th>
                        <th>Prénom & Nom</th>
                        <th>Téléphone</th>
                        <th>Classe</th>
                        <th>Matière</th>
                        <th>Plage Horaire</th>
                        <th>Heures Prev</th>
                        <th>Heures Real</th>
                        <th>Année</th>
                        <th></th>
                    </tr>

                </thead><?php

                if (isset($_POST['termec'])) {
                  $_POST['termec'] = htmlspecialchars($_POST['termec']); //pour sécuriser le formulaire contre les failles html
                  $terme = $_POST['termec'];
                  $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
                  $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
                  $terme = strtolower($terme);

                  $prodm=$DB->query('SELECT  events.id as id, codemp, nommat, nomgrp, nomen, prenomen, codensp, name, debut, fin, lieu, phone FROM events inner join matiere on codemp=codem inner join enseignant on matricule=codensp left join contact on enseignant.matricule=contact.matricule  WHERE events.id not in(SELECT idevent FROM horairet where date_format(datet,\'%Y-%m-%d \')=?) and date_format(debut,\'%Y-%m-%d\') LIKE? and(enseignant.matricule LIKE? or nomen LIKE ? or prenomen LIKE ? or phone LIKE ?) order by(prenomen)',array($_SESSION['moisp'], $_SESSION['moisp'],"%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
                  
                }elseif (isset($_POST['mois'])) {

                    $prodm=$DB->query('SELECT  events.id as id, codemp, nommat, nomgrp, nomen, prenomen, codensp, name, debut, fin, lieu, phone FROM events inner join matiere on codemp=codem inner join enseignant on matricule=codensp left join contact on enseignant.matricule=contact.matricule  WHERE events.id not in(SELECT idevent FROM horairet where date_format(datet,\'%Y-%m-%d \')=:debuth) and date_format(debut,\'%Y-%m-%d \')=:debut and events.promo=:promo order by(prenomen)', array('debuth'=>$_POST['mois'], 'debut'=>$_POST['mois'], 'promo'=>$_SESSION['promo']));
                    

                }else{

                    if (!empty($_SESSION['search'])) {

                        $prodm=$DB->query('SELECT  events.id as id,  codemp, nommat, nomgrp, nomen, prenomen, codensp, name, debut, fin, lieu, phone FROM events inner join matiere on codemp=codem inner join enseignant on matricule=codensp left join contact on enseignant.matricule=contact.matricule  WHERE events.id not in(SELECT idevent FROM horairet where date_format(datet,\'%Y-%m-%d \')=:debuth) and date_format(debut,\'%Y-%m-%d \')=:debut and codensp=:code and events.promo=:promo order by(prenomen)', array('debuth'=>date('Y-m-d'), 'debut'=>date('Y-m-d'), 'code'=>$_SESSION['search'], 'promo'=>$_SESSION['promo']));
                    }else{

                        $prodm=$DB->query('SELECT  events.id as id,  codemp, nommat, nomgrp, nomen, prenomen, codensp, name, debut, fin, lieu, phone FROM events inner join matiere on codemp=codem inner join enseignant on matricule=codensp left join contact on enseignant.matricule=contact.matricule  WHERE events.id not in(SELECT idevent FROM horairet where date_format(datet,\'%Y-%m-%d \')=:debuth) and date_format(debut,\'%Y-%m-%d \')=:debut and  events.promo=:promo order by(prenomen)', array('debuth'=>date('Y-m-d'), 'debut'=>date('Y-m-d'), 'promo'=>$_SESSION['promo']));

                    }
                }

                $toth=0;
                foreach ($prodm as $key => $value) {

                    $totf=intval((new DateTime($value->fin))->format('H:i'));
                    $totd=intval((new DateTime($value->debut))->format('H:i'));
                    $dated=(new DateTime($value->debut))->format('Y-m-d');
                    $heured=(new DateTime($value->debut))->format('H:i');

                    if (!empty($value->salaire)) {
                        $color='success';
                    }else{
                        $color='';
                    }

                    $tot=$totf-$totd;

                    $toth+=$tot;?>

                    <form method="POST" action="horairegen.php" id="suitec" name="termc">

                        <tbody>
                            <tr>
                                <td class="text-<?=$color;?>"><?=$key+1;?></td>

                                <td class="text-<?=$color;?>"><?=$value->codensp;?><input type="hidden" name="mat" value="<?=$value->codensp;?>"><input type="hidden" name="id" value="<?=$value->id;?>"></td>

                                <td class="text-<?=$color;?>"><?=ucwords($value->prenomen).' '.strtoupper($value->nomen);?></td>

                                <td class="text-<?=$color;?>"><?=$value->phone;?></td>

                                <td class="text-<?=$color;?>"><?=$value->nomgrp;?><input type="hidden" name="nomgr" value="<?=$value->nomgrp;?>"></td>

                                <td class="text-<?=$color;?>"><?=ucwords($value->nommat);?><input type="hidden" name="nommat" value="<?=$value->codemp;?>"></td>

                                <td class="text-<?=$color;?>"><?=(new DateTime($value->debut))->format('H:i').' - '.(new DateTime($value->fin))->format('H:i') ;?></td>

                                <input type="hidden" name="hdebut" value="<?=$heured;?>"><input type="hidden" name="dated" value="<?=$dated;?>">

                                <td style="text-align: center;"><?=$tot ;?><input type="hidden" name="htot" value="<?=$tot;?>"></td>
                                <td class="text-<?=$color;?>"><input class="form-control text-center fw-bold" type="text" name="hreal" min="0" value="<?=$tot ;?>" required ></td>


                                <td class="text-<?=$color;?>"><select class="form-select" type="text" name="promo" required=""><?php
                                      
                                        $annee=date("Y")+1;

                                        for($i=($_SESSION['promo']-1);$i<=$annee ;$i++){
                                            $j=$i+1;?>

                                            <option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

                                        }?>
                                    </select>
                                </td>

                                <td><?php 

                                    if ($products['type']=='admin' or $products['type']=='bibliothecaire' or $products['type']=='informaticien' or $products['type']=='Directeur Général' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='coordonateur bloc B' or $products['type']=='Directeur du primaire'  or $products['type']=='secrétaire') {?>

                                        <button class="btn btn-success" type="submit" name="confirm" onclick="return alerteV();">Confirmer</button></td><?php 
                                    }?>
                            </tr>
                        </tbody>

                    </form><?php
                }?>
                <tfoot>
                    <tr>
                        <th colspan="7">Total</th>
                        <th><?=number_format($toth,2,',',' ');?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer cette facture ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>


    
