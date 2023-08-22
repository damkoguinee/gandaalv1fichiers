<?php require 'headerv2.php';?>

<div class="container-fluid">
    <div class="row" style="overflow:auto;"><?php

    if (isset($_POST['paye'])) {

        $DB->insert('UPDATE payepersonnel SET etat=? where id=?' ,array('ok', $_POST['id']));
    }

    if (isset($_POST['annul'])) {

        $DB->insert('UPDATE payepersonnel SET etat=? where id=?' ,array('', $_POST['id']));
    }

    if (isset($_POST['niveau'])){

        $_SESSION['niveau']=$_POST['niveau'];

        $_SESSION['legende']='Etat des Salaires du Personnels Niveau '.ucfirst($_SESSION['niveau']);

    }elseif (isset($_POST['mois'])){

        $_SESSION['mois']=$_POST['mois'];

        $_SESSION['legende']='Etat des Salaires du Personnels '. ' pour le mois de '.$panier->moisbul();

    }else{
        $_SESSION['legende']='Etat des Salaires du Personnels';
        if (empty($_SESSION['mois'])) {
            $_SESSION['mois']='';
        } else {
            $_SESSION['mois']=$_SESSION['mois'];
            
        }
        
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

    //$DB->insert('UPDATE niveauc SET nom=? where nom=?' ,array('secondaire', 'college'));?> 

    <form class="form" action="etatsalairepers.php" method="POST">
        <ol >
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

                </select>
            </li>
        </ol>
    </form>

    <table class="table table-hover table-bordered table-striped table-responsive text-center">

    <thead>
        <form method="GET" action="etatsalairepers.php" class="form">
            <tr>
                <th colspan="8"><?=$_SESSION['legende'];?>

                    <a class="btn btn-info" href="etatsalairepdf.php?etatsalairepers&mois=<?=$_SESSION['mois'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a><?php 

                    if (isset($_POST['mois'])){?>

                        <a class="btn btn-info" href="fichedepaiepers.php?payehemp&mois=<?=$panier->moisbul();?>&moisnum=<?=$_POST['mois'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg">Fiche de Paie</a><?php 
                    }?>
                </th>  
                <th colspan="2"><input class="form-control" type= "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="this.form.submit()"></th>              
            </tr>
            <tr></tr>
        </form>

        <tr>
            <th></th>
            <th height="30">N°M</th>
            <th>Prénom & Nom</th>
            <th>Phone</th>
            <th>Salaire Brut</th>
            <th style="background-color: green;">Salaire Net</th>
            <th>N° Compte</th>
            <th colspan="3">Etat</th>
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

          $prodm =$DB->query('SELECT payepersonnel.id as id, personnel.numpers as matricule, prenom as prenomen, nom as nomen, phone, salaire, payepersonnel.montant as montant, typepaye, etat, mois, numbanq from personnel left join payepersonnel on payepersonnel.matricule=personnel.numpers inner join contact on personnel.numpers=contact.matricule inner join salairepers on salairepers.numpers=personnel.numpers WHERE typepaye LIKE? and (personnel.numpers LIKE? or nom LIKE ? or prenom LIKE ? or phone LIKE ?)',array($value, "%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
          
        }elseif(isset($_POST['mois']) or isset($_POST['paye']) or isset($_POST['annul'])) {

            $prodm=$DB->query('SELECT payepersonnel.id as id, personnel.numpers as matricule, prenom as prenomen, nom as nomen, phone, salaire, payepersonnel.montant as montant, typepaye, etat, mois, numbanq from personnel left join payepersonnel on payepersonnel.matricule=personnel.numpers inner join contact on personnel.numpers=contact.matricule inner join salairepers on salairepers.numpers=personnel.numpers where typepaye=:type and payepersonnel.promo=:promo and salairepers.promo=:promo1 and mois=:mois order by(etat)', array('type'=>$value, 'promo'=>$_SESSION['promo'], 'promo1'=>$_SESSION['promo'],  'mois'=>$_SESSION['mois']));

        }else{

            $prodm=$DB->query('SELECT payepersonnel.id as id, personnel.numpers as matricule, prenom as prenomen, nom as nomen, phone, salaire, payepersonnel.montant as montant, typepaye, etat, mois, numbanq from personnel left join payepersonnel on payepersonnel.matricule=personnel.numpers inner join contact on personnel.numpers=contact.matricule inner join salairepers on salairepers.numpers=personnel.numpers where typepaye=:type and payepersonnel.promo=:promo and salairepers.promo=:promo1 order by(etat)', array('type'=>$value, 'promo'=>$_SESSION['promo'], 'promo1'=>$_SESSION['promo']));
        }?>

        <tbody><?php

            
            if (empty($prodm)) {
            # code...
            }else{?>

                <tr>
                    <th colspan="12" style="text-align: center; background-color:orange; ">Mode de Paiement <?=ucwords($value);?></th>
                </tr><?php
                
                foreach ($prodm as $key=> $formation) {

                    $tots1+=$formation->montant;

                    $totsb1+=$formation->salaire;?>

                    <form action="etatsalairepers.php" method="POST">

                        <tr>
                            <td style="text-align: center;"><?=$key+1;?></td>

                            <td style="text-align: center; font-size: 14px;">

                                <a class="btn btn-info" target="_blank" href="printdoc.php?paytotpers=<?=$formation->matricule;?>&mens=<?=100;?>&nomel=<?=$panier->nomPersonnel($formation->matricule);?>&motif=<?="Payements du Personnel";?>"><?=$formation->matricule;?></a>
                            </td>
                            

                            <td><?=ucwords(strtolower($formation->prenomen)).' '.strtoupper($formation->nomen);?></td>

                            <td><?=$formation->phone;?></td>

                            <td style="text-align: right;"><?=number_format($formation->salaire,0,',',' ');?></td>

                            <td style="text-align: right; background-color: green; color: white; font-size: 22px; font-weight: bold;"><?=number_format($formation->montant,0,',',' ');?></td>

                            <td style="text-align: center; font-size: 14px;"><?=$formation->numbanq;?></td>

                            <td><?=$formation->typepaye;?></td><?php

                            if (!empty($formation->etat)) {?>

                                <td><?=$formation->etat;?></td>

                                <td><input type="hidden" name="id" value="<?=$formation->id;?>"><button calss="btn btn-primary" type="submit" name="annul">Annulé</button></td><?php
                            }else{?>

                                <td><?php 

                                    if (isset($_POST['mois'])){?>

                                        <a style="margin-left: 10px;"href="fichedepaiepers.php?payehemp&matensind=<?=$formation->matricule;?>&moisnum=<?=$formation->mois;?>&mois=<?=$panier->moisbul();?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg">Fiche de Paie</a><?php 
                                    }?>
                                </td>

                                <td><input type="hidden" name="id" value="<?=$formation->id;?>">
                                <button calss="btn btn-primary " type="submit" name="paye">Payé</button></td><?php
                            }?>

                        </tr>
                    </form><?php
                }
            }?>

        
        </tbody><?php
    }
        $totsalaire=$tots1;
        $totsalaireb=$totsb1;?>
    <tfoot>
        <tr>
            <th height="30" colspan="4"></th>
            <th style="text-align: right;"><?=number_format($totsalaireb,0,',',' ');?></th>
            <th style="text-align: right; background-color: green; color: white; font-size: 22px; font-weight: bold;"><?=number_format($totsalaire,0,',',' ');?></th>
        </tr>
    </tfoot>
</table>