<?php require 'headerv2.php';

$prodlogin = $DB->querys('SELECT type, matricule, niveau FROM login WHERE pseudo= :PSEUDO',array('PSEUDO'=>$_SESSION['pseudo']));?>

<div class="container-fluid">
    <div class="row"><?php


        if (isset($_SESSION['pseudo'])) {
            
            if ($_SESSION['niveaupers']<4) {?>

                <div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

            }else{
                if (isset($_GET['payeem'])) {
                    $_SESSION['numeen']=array();
                }

                if (isset($_POST['numeen'])) {

                    $_SESSION['numeen']=$_POST['numeen'];
                    $numeen=$_SESSION['numeen'];

                }elseif (isset($_POST['validac'])) {

                    $numeen=$_SESSION['numeen'];

                }elseif (isset($_POST['montantac'])) {

                    $numeen=$_SESSION['numeen'];

                }else{

                    $numeen=0;

                }

                
                if (isset($_GET['delepayeens'])) {

                    $numeen=$_SESSION['numeen'];
                }

                if (isset($_GET['deleteac'])) {

                    $numeen=$_SESSION['numeen'];
                }

                if (isset($_POST['numeen']) or !empty($numeen)  or isset($_POST['validac']) or isset($_POST['montantac']) or isset($_GET['deleteac'])) {

                    if (isset($_POST['numeen']) or isset($_POST['validac']) or isset($_POST['montantac']) or isset($_GET['deleteac'])) {

                        if (isset($_POST['numeen'])) {
                           
                            if ($_POST['type']=='personnel') {
                                
                                $products=$DB->querys('SELECT numpers as mat, nom as nomen, prenom as prenomen, date_format(datenaiss,\'%d/%m/%Y \') as naissance, phone, email from personnel inner join contact on numpers=matricule where numpers=:matp', array('matp'=>$numeen));
                            }else{

                                $products=$DB->querys('SELECT enseignant.matricule as mat, nomen, prenomen, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email from enseignant inner join contact on enseignant.matricule=contact.matricule where enseignant.matricule=:mat', array('mat'=>$numeen));

                            }
                        }else{
                            $products=$DB->querys('SELECT enseignant.matricule as mat, nomen, prenomen, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email from enseignant inner join contact on enseignant.matricule=contact.matricule where enseignant.matricule=:mat', array('mat'=>$numeen));

                        }

                        
                    }
                     
                }else{
                    $products = array();
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
                    
                );?>

                <div class="col-sm-12 col-md-4" style="overflow: auto;">

                    <form action="prime.php" method="post" class="form mt-2">                
                        <fieldset><legend>Prime

                            <div class="mb-1"><?php
                                if (isset($_POST['numeen']) or isset($_POST['validac']) or isset($_POST['montantac']) or isset($_GET['deleteac'])) {

                                    require 'fichepersonnel.php';
                                }?>
                            </div></legend>

                            <div class="mb-1">
                                <label class="form-label">Type Personnel</label>
                                <select class="form-select" name="typel" onchange="this.form.submit()" ><?php 
                                    if (isset($_POST['typel'])) {

                                        $_SESSION['typel']=$_POST['typel'];?>

                                        <option value="<?=$_POST['typel'];?>"><?=$_POST['typel'];?></option><?php

                                    }else{?>
                                        <option></option><?php
                                    }?>

                                    <option value="enseignant">Enseignant</option>
                                    <option value="personnel">Personnel</option>
                                    
                                </select>
                            </div>
                        </fieldset>
                    </form><?php 

                    if (!empty($_SESSION['typel'])) {?>

                        <form action="prime.php" method="post" class="form mt-2">

                            <input class="form-control"  type="hidden" name="type" value="<?=$_SESSION['typel'];?>" /> <?php

                            if (isset($_POST['numeen']) AND empty($products)) {?>

                                <div class="alertes">Numéro incorrect, <a style="color: red;" href="prime.php?paye">réessayer ici</a></div><?php

                            }else{
                                    
                                if (isset($_POST['numeen']) or isset($_POST['payen']) or isset($_POST['validac']) or isset($_POST['montantac']) or isset($_GET['deleteac'])) {?>

                                    <div class="mb-1"><label class="form-label">N°Matricule</label><input class="form-control"  type="text" name="numeen" placeholder="N° matricule" onchange="this.form.submit()" value="<?= $numeen; ?>" /></div><?php

                                }else{?>
                                    <div class="mb-1"><label class="form-label">N°Matricule</label><input class="form-control"  type="text" name="numeen" placeholder="N° matricule" onchange="this.form.submit()" /></div><?php                                
                                }
                            }?>
                        </form><?php 
                    }?>
                </div>

                <div class="col-sm-12 col-md-8" style="overflow: auto;"><?php

                    if (isset($_GET['deleteac'])) {
                        
                        $DB->delete('DELETE FROM primesplanifie WHERE id = ?', array($_GET['deleteac']));
                    }

                    if (isset($_POST['validac']) or isset($_POST['montantac'])) {

                        if ($_POST['montantac']<0){?>

                            <div class="alert alert-warning">Format incorrect</div><?php

                        }else{

                            $montantac=$panier->h($_POST['montantac']);
                            $moisac=$panier->h($_POST['moisac']);
                            $moischaine=$panier->h($_POST['moischaine']);

                            $maxid = $DB->querys('SELECT max(id) as id FROM primesplanifie');
                                    
                            $numdec=$maxid['id']+1;

                            $prodacver = $DB->querys('SELECT id FROM primesplanifie WHERE matricule=:mat and mois = :mois and anneescolaire=:promo', array('mat'=>$numeen, 'mois'=> $moisac, 'promo'=>$_SESSION['promo']));

                            if (empty($prodacver)) {

                                $DB->insert('INSERT INTO primesplanifie(matricule, montant, mois, moischaine, anneescolaire, datepaye) VALUES(?, ?, ?, ?, ?, now())',array($numeen, $montantac, $moisac, $moischaine, $_SESSION['promo']));

                            }else{

                                $DB->insert('UPDATE primesplanifie SET montant=?, datepaye=now() where matricule=? and mois=? and anneescolaire=?' ,array($montantac, $numeen, $moisac, $_SESSION['promo']));
                            }
                        }

                    }

                    $prodac = $DB->query('SELECT id, matricule, montant, mois, moischaine, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM primesplanifie WHERE matricule = :mat and anneescolaire=:promo ORDER BY(datepaye) DESC', array('mat'=> $numeen, 'promo'=>$_SESSION['promo']));

                    if (!empty($_SESSION['numeen'])) {

                        if (!empty($prodac)) {?>

                            <table class="table table-hover table-bordered table-striped table-responsive text-center mt-2">
                                <thead>
                                    <tr><th colspan="3">Liste des Primes octroyées</th></tr>

                                    <tr>
                                        <th>Mois de</th>
                                        <th>Montant</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody><?php
                                    $totmontant=0;
                                    foreach ($prodac as $key => $paye) {

                                        $totmontant+=$paye->montant;?>

                                        <tr>
                                            <td><?=$paye->moischaine;?></td>

                                            <td><?=number_format($paye->montant,0,',',' ');?></td>

                                            <td><a class="btn btn-danger" href="prime.php?deleteac=<?=$paye->id;?>" onclick="return alerteS();">Supprimer</td>
                                        </tr><?php
                                    }?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th><?=number_format($totmontant,0,',',' ');?></th>
                                    </tr>
                                </tfoot>
                            </table><?php
                        }?>

                        <table class="table table-hover table-bordered table-striped table-responsive text-center">
                            <thead>
                                <tr><th colspan="3">Planifiez une Prime</th></tr>

                                <tr>
                                    <th>Mois</th>
                                    <th>Montant de la Prime</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody><?php

                                foreach ($month as $key => $value) {?>

                                    <form action="prime.php" method="post" id="formulaire" class="form">

                                        <tr>
                                            <td><?=$value;?><input class="form-control" type="hidden" name="moisac" value="<?=$key;?>"><input class="form-control" type="hidden" name="moischaine" value="<?=$value;?>"></td>

                                            <td><input class="form-control" type="text" name="montantac" required="" />
                                                <input class="form-control" type="hidden" name="mat" value="<?=$numeen;?>"></td>

                                            <td><?php if ($prodlogin['type']=='comptable' or $prodlogin['type']=='admin')  {?><button class="btn btn-primary" type="submit" name="validac">Valider</button><?php }?></td>
                                        </tr>

                                    </form><?php
                                }?>
                            </tbody>
                        </table><?php 
                    }?>

                </div><?php
            }
        }?>
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


    
