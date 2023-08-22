<?php require 'headerv3.php';

$month = array(
    10  => 'Octobre',
    11  => 'Novembre',
    12  => 'Décembre',
    1   => 'Janvier',
    2   => 'Février',
    3   => 'Mars',
    4   => 'Avril',
    5   => 'Mai',
    6   => 'Juin',
    7   => 'Juillet',
    8   => 'Août',
    9   => 'Septembre'
    
);

if (isset($_POST['mois'])) {
    $_SESSION['mois']=$_POST['mois'];
}else{
    $_SESSION['mois']=0;
}?>

<div class="container-fluid">

    <div class="row" style="overflow: auto;"><?php 

        if(isset($_POST['payen']) && !empty($_POST['saln']) && !empty($_POST['typep'])){

            if ($_POST['saln']>$panier->montantCompteT($_POST['compte'])) {?>

                <div class="alert alert-warning">Echec montant decaissé est > au montant disponible</div><?php

            }else{

                $maxid = $DB->querys('SELECT max(id) as id FROM payepersonnel');
                                
                $numdec=$maxid['id']+1;

                if (!empty($_POST['heuret'])) {

                    $heure= $_POST['heuret'];

                }else{
                    $heure=0;

                }

                $numeen=$panier->h($_POST['mat']);

                $datep=$panier->h($_POST['datep']);

                
                $mois=$panier->h($_POST['mois']);

                $promo=$panier->h($_POST['promo']);

                $typep=$panier->h($_POST['typep']);

                $numcheque=$panier->h($_POST['numcheque']);

                $saln=$panier->h($_POST['saln']);

                $compte=$panier->h($_POST['compte']);

                $prodrep = $DB->querys('SELECT montant FROM payepersonnel WHERE matricule = :mat and mois=:mois and promo=:promo', array('mat'=> $numeen, 'mois'=>$mois, 'promo'=>$promo));

                $cumulmontant=$prodrep['montant']+$_POST['saln'];

                if (empty($prodrep['montant'])) {

                    if (empty($_POST['datep'])) {

                        if (empty($prodrep)) {

                            $DB->insert('INSERT INTO payepersonnel(caisse, numdec, matricule, montant, mois, motif, typepaye, numcheque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $numdec, $numeen, $saln, $mois, 'payements du personnels', $typep, $numcheque,  $promo));

                        }else{

                            $DB->insert('UPDATE payepersonnel SET montant=?, datepaye=now() where matricule=? and mois=?' ,array($cumulmontant, $numeen, $mois));


                        }

                        $DB->insert('INSERT INTO banque (id_banque, montant, typep, numeropaie, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, -$saln, $typep, $numcheque, 'paiement personnel', 'paiepers'.$numdec, $numeen, $promo));
                    }else{

                        if (empty($prodrep)) {

                            $DB->insert('INSERT INTO payepersonnel(caisse, numdec, matricule, montant, mois, motif, typepaye, numcheque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',array($compte, $numdec, $numeen, $saln, $mois, 'payements du personnels', $typep, $numcheque, $promo, $datep));

                        }else{

                            $DB->insert('UPDATE payepersonnel SET montant=?, datepaye=? where matricule=? and mois=?' ,array($cumulmontant, $datep, $numeen, $mois));


                        }

                        $DB->insert('INSERT INTO banque (id_banque, montant, typep, numeropaie, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)', array($compte, -$saln, $typep, $numcheque, 'paiement personnel', 'paiepers'.$numdec, $numeen, $promo, $datep));

                    }?>

                    <div class="alert alert-success">Paiement effectué avec succèe!!</div><?php
                }
            }

        }?>

        <table class="table table-hover table-bordered table-striped table-responsive text-center">
            <thead>
                
                <tr>
                    <th colspan="6" class="info" style="text-align: center">
                        <form method="POST" action="payementpersonnel.php" class="form">
                            <select class="form-select" name="mois" required="" onchange="this.form.submit()"><?php

                                if (isset($_POST['mois'])) {?>
                                    
                                    <option value="<?=$_POST['mois'];?>" ><?=$panier->moisbul();?></option><?php

                                }else{?>

                                    <option>Selectionnez le mois</option><?php
                                }

                                foreach ($month as $key => $mois) {?>

                                    <option value="<?=$key;?>"><?=$mois;?></option><?php

                                }?>
                            </select>
                        </form>
                    </th>

                    <th colspan="6">

                        <form class="form" method="POST" action="payementpersonnel.php" id="suitec" name="termc">
                            <input type="hidden" name="mois" value="<?=$_SESSION['mois'];?>">

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-6">

                                        <input class="form-control" type = "search" name = "termec" placeholder="rechercher !!!!"  onchange="this.form.submit()">
                                    </div>

                                    <div class="col-2">

                                        <input   type = "submit" name = "s" value="valider" >
                                    </div>

                                    <div class="col-4">

                                    <a href="exportpdfpaypers.php?mois=<?=$_SESSION['mois'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                                    <a href="exportpaiepersexcel.php?mois=<?=$_SESSION['mois'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
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
                    <th>Salaire Brut</th>
                    <th>Prime</th>
                    <th>A/salaire</th>
                    <th>Cotisation</th>
                    <th class="bg-success">Salaire Net</th>
                    <th colspan="5" class="bg-danger">Paiement</th>
                </tr>

            </thead><?php

            if (isset($_POST['termec'])) {
              $_POST['termec'] = htmlspecialchars($_POST['termec']); //pour sécuriser le formulaire contre les failles html
              $terme = $_POST['termec'];
              $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
              $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
              $terme = strtolower($terme);

              $prodm=$DB->query('SELECT  *from personnel inner join salairepers on salairepers.numpers=personnel.numpers where salairepers.promo LIKE ? and personnel.numpers not in(SELECT matricule FROM payepersonnel WHERE promo LIKE? and mois LIKE?) and personnel.numpers not in(SELECT matricule FROM liaisonenseigpers WHERE promo LIKE?) and (personnel.numpers LIKE? or nom LIKE ? or prenom LIKE ?) order by(prenom)',array($_SESSION['promo'], $_SESSION['promo'], $_SESSION['mois'], $_SESSION['promo'], "%".$terme."%", "%".$terme."%", "%".$terme."%"));
              
            }else{

                $prodm=$DB->query("SELECT  *from personnel inner join salairepers on salairepers.numpers=personnel.numpers where salairepers.promo='{$_SESSION['promo']}' and personnel.numpers not in(SELECT matricule FROM payepersonnel WHERE promo='{$_SESSION['promo']}' and mois='{$_SESSION['mois']}') and personnel.numpers not in(SELECT matricule FROM liaisonenseigpers WHERE promo='{$_SESSION['promo']}') order by(prenom)");
            }

            if (isset($_POST['mois'])) {

                if ($_POST['mois']<10) {
                    
                    $cmois='0'.$_SESSION['mois'];

                }else{

                    $cmois=$_SESSION['mois'];
                }

                
                $totb=0;
                $totp=0;
                $totac=0;
                $totcot=0;
                $totn=0;
                foreach ($prodm as $key => $value) {

                    $_SESSION['numeen']=$value->numpers;
                    $numeen=$_SESSION['numeen'];

                    $prodsocial=$DB->querys('SELECT montant from ssocialpers where numpers=:mat', array('mat'=>$numeen));

                    $_SESSION['prodsocial']=$prodsocial['montant'];

                    $prodsalaire=$DB->querys('SELECT salaire from salairepers where numpers=:mat', array('mat'=>$numeen));

                    $_SESSION['salaire']=$prodsalaire['salaire'];
                    $_SESSION['salaireact']='ok';


                    $prodprime=$DB->querys('SELECT montant as montantp from primesplanifie where matricule=:mat and mois=:mois and anneescolaire=:promo', array('mat'=>$numeen, 'mois'=>$_SESSION['mois'], 'promo'=>$_SESSION['promo']));

                    if (empty($prodprime)) {
                        $prime=0;
                    }else{
                        $prime=$prodprime['montantp'];
                    }
    

                    $prodac=$DB->querys('SELECT montant from accompte where matricule=:mat and mois=:datet and anneescolaire=:promo', array('mat'=>$numeen, 'datet'=>$_SESSION['mois'], 'promo'=>$_SESSION['promo']));

                    if (empty($prodac)) {
                        $accompte=0;
                    }else{
                        $accompte=$prodac['montant'];
                    }

                    
                    $salaireb=$_SESSION['salaire'];

                    $salairep=$_SESSION['salaire']+$prime-$accompte-$_SESSION['prodsocial'];

                    $totb+=$salaireb;
                    $totp+=$prime;
                    $totac+=$accompte;
                    $totcot+=$_SESSION['prodsocial'];
                    $totn+=$salairep;
                    ?>

                    <form class="form" method="POST" action="payementpersonnel.php" id="suitec" name="termc">

                        <tbody>
                            <tr>
                                <td><?=$key+1;?></td>

                                <td><?=$value->numpers;?><input class="form-control" type="hidden" name="mat" value="<?=$value->numpers;?>"><input class="form-control" type="hidden" name="mois" value="<?=$_POST['mois'];?>"></td>

                                <td><?=ucwords($value->prenom).' '.strtoupper($value->nom);?></td>

                                <td style="text-align: right; padding-right: 5px;"><input class="form-control" type="hidden" name="salb" value="<?=$salaireb;?>"><?=number_format($salaireb,0,',',' ');?></td>

                                <td style="text-align: right; padding-right: 5px;"><input class="form-control" type="hidden" name="prime" value="<?=$prime;?>"><?=number_format($prime,0,',',' ');?></td>

                                <td style="text-align: right; padding-right: 5px;"><input class="form-control" type="hidden" name="acc" value="<?=$accompte;?>"><?=number_format($accompte,0,',',' ');?></td>

                                <td style="text-align: right; padding-right: 5px;"><input class="form-control" type="hidden" name="cot" value="<?=$_SESSION['prodsocial'];?>"><?=number_format($_SESSION['prodsocial'],0,',',' ');?></td>

                                <td class="bg-success fw-bold fs-6 text-white" style="text-align: right; padding-right: 5px;">
                                    <input class="form-control" type="hidden" name="saln" value="<?=$salairep;?>"><?=number_format($salairep,0,',',' ');?>
                                    <input class="form-control" type="hidden" name="promo" value="<?=$_SESSION['promo'];?>" required=""/>

                                </td>

                                <td><select class="form-select" name="typep" required="" >
                                    <option value=""></option><?php 
                                    foreach ($panier->modep as $value) {?>
                                        <option value="<?=$value;?>"><?=$value;?></option><?php 
                                    }?></select>
                                </td>

                                <td><input class="form-control" type="text" name="numcheque" placeholder="numéro chèque/bordereau" ></td>

                                <td><select class="form-select"  name="compte" required="">
                                    <option></option><?php
                                    $type='Banque';

                                    foreach($panier->nomBanque() as $product){?>

                                        <option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
                                    }?>
                                    </select>
                                </td>

                                <td><input class="form-control" type="date" name="datep" style="width: 90%;" ></td>

                                <td><?php 

                                    if ($products['type']=='admin' or $products['type']=='comptable' ) {?>
                                        <button class="btn  btn-primary" type="submit" name="payen" onclick="return alerteV();">Valider</button><?php 
                                    }?>
                                </td>
                            </tr>
                        </tbody>
                    </form><?php
                }?>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th style="text-align: right; padding-right: 5px;"><?=number_format($totb,0,',',' ');?></th>
                        <th style="text-align: right; padding-right: 5px;"><?=number_format($totp,0,',',' ');?></th>
                        <th style="text-align: right; padding-right: 5px;"><?=number_format($totac,0,',',' ');?></th>
                        <th style="text-align: right; padding-right: 5px;"><?=number_format($totcot,0,',',' ');?></th>
                        <th class="bg-success fw-bold fs-6 text-white"><?=number_format($totn,0,',',' ');?></th>
                    </tr>
                </tfoot><?php

            }?>
        </table>
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


    
