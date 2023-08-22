<?php require 'headerv2.php';?>

<div class="container-fluid">
    <div class="row" style="overflow:auto;"><?php

        if (isset($_POST['paye'])) {

            $DB->insert('UPDATE payenseignant SET etat=? where id=?' ,array('ok', $_POST['id']));
        }

        if (isset($_POST['annul'])) {

            $DB->insert('UPDATE payenseignant SET etat=? where id=?' ,array('', $_POST['id']));
        }

        if (isset($_POST['mois'])){

            $_SESSION['mois']=$_POST['mois'];

            $_SESSION['legende']='Etat des Salaires des Enseignants pour le mois de '.$panier->moisbul();

        }else{
            $_SESSION['legende']='Etat des Salaires des Enseignants';
        }

        $month = array(
            1   => 'Janvier',
            2   => 'Février',
            3   => 'Mars',
            4   => 'Avril',
            5   => 'Mai',
            6   => 'Juin',
            7   => 'Juillet',
            8   => 'Août',
            9   => 'Septembre',
            10  => 'Octobre',
            11  => 'Novembre',
            12  => 'Décembre'
            
        );

        $modepaye = array(
            'Virement'   => 'Virement',
            'Chèque'   => 'cheque',
            'Espèces'   => 'especes'
            
        );

        //$DB->insert('UPDATE niveauc SET nom=? where nom=?' ,array('secondaire', 'college'));?>    

        <form class="form" action="etatsalaire.php" method="POST">
            <ol>
                <li><select class="form-select" type="text" name="mois" required="" onchange="this.form.submit()"><?php

                    if (isset($_POST['mois'])) {?>

                        <option value="<?=$_SESSION['mois'];?>"><?=$panier->moisbul();?></option><?php

                    }else{?>

                        <option>Choisissez le mois</option><?php
                    }

                    foreach ($month as $key => $mois) {

                        if ($key<10) {?>

                            <option value="<?=$key;?>"><?=$mois;?></option><?php
                            
                        }else{?>

                            <option value="<?=$key;?>"><?=$mois;?></option><?php
                        }

                    }?>

                    </select></li>
            </ol>
        </form>

        <table class="table table-hover table-bordered table-striped table-responsive text-center">

            <thead>
                <form class="form" method="GET" action="etatsalaire.php">
                    <tr>
                        <th colspan="13"><?=$_SESSION['legende'];?>

                            <a class="btn btn-info" style="margin-left: 10px;"href="etatsalairepdf.php?etatsalairens&mois=<?=$_SESSION['mois'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a><?php 

                            if (isset($_POST['mois'])){?>

                                <a class="btn btn-info" style="margin-left: 10px;"href="fichedepaieens.php?payehemp&mois=<?=$panier->moisbul();?>&moisnum=<?=$_POST['mois'];?>&niveau" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg">Fiche de Paie</a><?php 
                            }?>                               

                        </th>
                        <tr>

                            <th colspan="10">
                                <input class="form-control text-center" type ="search" name = "termec" placeholder="rechercher un enseignant !!!!"  onchange="this.form.submit()">
                            </th>
                            <th colspan="3">
                                <button class="btn btn-primary" type = "submit">Recherchez</button>
                            </th>
                        </tr>
                        
                    </tr>
                </form>

                    <tr>
                        <th></th>
                        <th height="30">N°M</th>
                        <th>Prénom & Nom</th>
                        <th>Phone</th>
                        <th>Matière</th>
                        <th>Salaire Brut</th>
                        <th style="background-color: green;">Salaire Net</th>
                        <th>Heures</th>
                        <th>T. Horaire</th>
                        <th>N° Compte</th>
                        <th>Mode</th>
                        <th colspan="2">Etat</th>
                    </tr>

                </thead><?php

                $tots1=0;
                $tots2=0;
                $totsb1=0;
                $totsb2=0;
                $cumh=0;

                foreach ($panier->modep as $key => $value) {
                

                    if (isset($_GET['termec'])) {
                    $_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
                    $terme = $_GET['termec'];
                    $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
                    $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
                    $terme = strtolower($terme);

                    $prodm =$DB->query('SELECT payenseignant.id as id, enseignant.matricule as matricule, prenomen, nomen, phone, salaire, payenseignant.montant as montant, mois, heurep, thoraire, typepaye, etat, numbanq from enseignant left join payenseignant on payenseignant.matricule=enseignant.matricule left join contact on enseignant.matricule=contact.matricule inner join salaireens on salaireens.numpers=enseignant.matricule WHERE typepaye LIKE? and (enseignant.matricule LIKE? or nomen LIKE ? or prenomen LIKE ? or phone LIKE ?)',array($value, "%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
                    
                    }elseif(isset($_POST['mois']) or isset($_POST['paye']) or isset($_POST['annul'])) {

                        $prodm=$DB->query('SELECT payenseignant.id as id, enseignant.matricule as matricule, prenomen, nomen, phone, salaire, payenseignant.montant as montant, mois, heurep, thoraire, typepaye, etat, numbanq from enseignant left join payenseignant on payenseignant.matricule=enseignant.matricule left join contact on enseignant.matricule=contact.matricule inner join salaireens on salaireens.numpers=enseignant.matricule where typepaye=:type and promo=:promo and anneescolaire=:promo1 and mois=:mois order by(etat)', array('type'=>$value, 'promo'=>$_SESSION['promo'], 'promo1'=>$_SESSION['promo'], 'mois'=>$_SESSION['mois']));

                    }else{

                        $prodm=$DB->query('SELECT payenseignant.id as id, enseignant.matricule as matricule, prenomen, nomen, phone, salaire, payenseignant.montant as montant, mois, heurep, thoraire, typepaye, etat, numbanq from enseignant left join payenseignant on payenseignant.matricule=enseignant.matricule left join contact on enseignant.matricule=contact.matricule inner join salaireens on salaireens.numpers=enseignant.matricule where typepaye=:type and promo=:promo and anneescolaire=:promo1 order by(etat)', array('type'=>$value, 'promo'=>$_SESSION['promo'], 'promo1'=>$_SESSION['promo']));
                    }?>

                    <tbody><?php

                
                        if (empty($prodm)) {
                        # code...
                        }else{?>

                            <tr>
                                <th colspan="13" style="text-align: center; background-color:orange; ">Mode de Paiement <?=ucwords($value);?></th>
                            </tr><?php
                            
                            foreach ($prodm as $key=> $formation) {

                                $cumh+=$formation->heurep;

                                if ($formation->salaire==0) {

                                    $tots1+=$formation->montant;

                                }else{

                                    $tots2+=$formation->montant;
                                }

                                if ($formation->salaire==0) {

                                    $totsb1+=$formation->heurep*$formation->thoraire;

                                }else{

                                    $totsb2+=$formation->salaire;
                                }?>

                                <form action="etatsalaire.php" method="POST">

                                    <tr>
                                        <td style="text-align: center;"><?=$key+1;?></td>
                                        <td style="text-align: center; font-size: 14px;">

                                            <a target="_blank" href="printdoc.php?paytotemp=<?=$formation->matricule;?>&mens=<?=100;?>&nomel=<?=$panier->nomEnseignant($formation->matricule);?>&motif=<?="Payements des enseignants";?>"><?=$formation->matricule;?></a>
                                        </td>

                                        <td><?=ucwords(strtolower($formation->prenomen)).' '.strtoupper($formation->nomen);?></td>

                                        <td><?=$formation->phone;?></td>
                                        <td></td><?php

                                        if ($formation->salaire==0) {?>

                                            <td style="text-align: right;"><?=number_format($formation->heurep*$formation->thoraire,0,',',' ');?></td>

                                            <td style="text-align: right; background-color: green; color: white; font-size: 22px; font-weight: bold;"><?=number_format($formation->montant,0,',',' ');?></td><?php
                                        }else{?>

                                            <td style="text-align: right; "><?=number_format($formation->salaire,0,',',' ');?></td>

                                            <td style="text-align: right; background-color: green; color: white; font-size: 22px; font-weight: bold;"><?=number_format($formation->montant,0,',',' ');?></td><?php
                                        }?>
                                        <td style="text-align: center;"><?=$formation->heurep;?>h</td>

                                        <td style="text-align: right;"><?=number_format($formation->thoraire,0,',',' ');?></td>

                                        <td style="text-align: center; font-size: 14px;"><?=$formation->numbanq;?></td>

                                        <td><?=$formation->typepaye;?></td><?php

                                        if (!empty($formation->etat)) {?>

                                            <td><?=$formation->etat;?></td>

                                            <td><input type="hidden" name="id" value="<?=$formation->id;?>"><input type="submit" name="annul" value="Annulé"></td><?php
                                        }else{?>

                                            <td><?php 

                                                if (isset($_POST['mois'])){?>

                                                    <a class="btn btn-info" style="margin-left: 10px;"href="fichedepaieens.php?payehemp&matensind=<?=$formation->matricule;?>&moisnum=<?=$formation->mois;?>&mois=<?=$panier->moisbul();?>&niveau" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg">Fiche de Paie</a><?php 
                                                }?>
                                            </td>

                                            <td><input type="hidden" name="id" value="<?=$formation->id;?>"><button class="btn btn-primary" type="submit" name="paye">Payé</button></td><?php
                                        }?>

                                    </tr>
                                </form><?php
                            }
                        }?>

            
                    </tbody><?php
                }
                $totsalaire=$tots1+$tots2;
                $totsalaireb=$totsb1+$totsb2;?>
                <tfoot>
                    <tr>
                        <th height="30" colspan="5"></th>
                        <th style="text-align: right;"><?=number_format($totsalaireb,0,',',' ');?></th>
                        <th style="text-align: right; background-color: green; color: white; font-size: 22px; font-weight: bold;"><?=number_format($totsalaire,0,',',' ');?></th>
                        <th style="text-align: center;"><?=$cumh;?></th>
                    </tr>
                </tfoot>
            </table>
    </div>
</div>