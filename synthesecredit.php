<?php
require 'headerv3.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<3) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>
        <div class="container-fluid">

            <div class="row"><?php

                require 'navcredit.php';

                $prodgroup=$DB->query('SELECT nomgr from groupe where promo=:promo', array('promo'=>$_SESSION['promo']));?>

                <div class="col-sm-12 col-md-10" style="overflow: auto;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <form class="form" method="POST" action="synthesecredit.php" name="termc">
                                    <select class="form-select" name="annee" required="" onchange="this.form.submit();"><?php

                                        if (isset($_POST['annee']) or isset($_POST['groupe']) or isset($_POST['mensuellec'])) {?>

                                                <option value="<?=$_SESSION['annee'];?>"><?="année-scolaire ".$_SESSION['annee'];?></option><?php

                                        }else{?>

                                            <option value="">Choisir l'année-scolaire</option><?php
                                        }
                                      
                                        $annee=date("Y")+1;

                                        for($i=2020;$i<=$annee ;$i++){
                                            $j=$i+1;?>

                                            <option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

                                        }?>
                                    </select>
                                </form>
                            </div>

                            <div class="col-sm-12 col-md-3"><?php
                                if (isset($_POST['annee']) or isset($_POST['groupe'])) {?>

                                    <form class="form" method="POST" action="synthesecredit.php" name="termc">

                                        <select class="form-select" name="groupe" required="" onchange="this.form.submit()"><?php

                                            if (isset($_POST['groupe'])) {?>

                                                <option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php
                                            }else{?>

                                                <option>Choisissez la Classe</option><?php
                                            }

                                            foreach ($prodgroup as $form) {?>

                                                <option><?=$form->nomgr;?></option><?php

                                            }?>
                                        </select>
                                    </form><?php
                                }?>
                            </div>
                        </div>
                    </div><?php

                    if (isset($_POST['annee'])) {

                        $_SESSION['annee']=$_POST['annee'];

                        $legende='promo '.($_SESSION['annee']-1).'-'. $_SESSION['annee'];
                        
                    }

                    if (isset($_POST['groupe']) or isset($_GET['termec'])) {

                        

                        $prodgroup=$DB->query('SELECT nomgr from groupe where promo=:promo', array('promo'=>$_SESSION['promo']));

                        if (isset($_POST['groupe'])) {
                            
                            $_SESSION['groupe']=$_POST['groupe'];

                            $prodcodef=$DB->querys('SELECT codef from groupe  where promo=:promo and nomgr=:nom', array('promo'=>$_SESSION['promo'], 'nom'=>$_POST['groupe']));
                        }else{

                            $prodcodef=$DB->querys('SELECT codef from groupe  where promo=:promo and nomgr=:nom', array('promo'=>$_SESSION['promo'], 'nom'=>$_SESSION['groupe']));

                        }

                        $legende=' de '.$_SESSION['groupe'].' année-scolaire '.($_SESSION['annee']-1).'-'. $_SESSION['annee'];
                        
                    }

                    if (isset($_POST['annee']) or isset($_POST['groupe']) or isset($_GET['termec'])) {

                        if (isset($_POST['groupe']) or isset($_GET['termec'])) {

                            $prodscol = $DB->query('SELECT nom FROM tranche WHERE promo=:promo order by(id)', array('promo'=>$_SESSION['annee']));



                            if (isset($_POST['groupe']) or isset($_GET['termec'])) {

                                $prodscolt1 =$DB->querys('SELECT montant, DATE_FORMAT(limite, \'%Y%m\') as limite, DATE_FORMAT(limite, \'%d/%m/%Y\') as dlimite FROM scolarite WHERE tranche=:tranche and codef=:code and promo=:promo', array('tranche'=>'1ere tranche', 'code'=>$prodcodef['codef'], 'promo' => $_SESSION['promo']));

                                $prodscolt2 =$DB->querys('SELECT montant, DATE_FORMAT(limite, \'%Y%m\') as limite, DATE_FORMAT(limite, \'%d/%m/%Y\') as dlimite FROM scolarite WHERE tranche=:tranche and codef=:code and promo=:promo', array('tranche'=>'2eme tranche', 'code'=>$prodcodef['codef'], 'promo' => $_SESSION['promo']));

                                $prodscolt3 =$DB->querys('SELECT montant, DATE_FORMAT(limite, \'%Y%m\') as limite, DATE_FORMAT(limite, \'%d/%m/%Y\') as dlimite FROM scolarite WHERE tranche=:tranche and codef=:code and promo=:promo', array('tranche'=>'3eme tranche', 'code'=>$prodcodef['codef'], 'promo' => $_SESSION['promo']));


                                $now = date('Y-m-d');
                                $now = new DateTime( $now );
                                $now = $now->format('Ym');

                                if (empty($prodscolt1)) {

                                    $montant1=0;
                                    $limite1=0;
                                    $date1=0;

                                }else{
                                    $montant1=$prodscolt1['montant'];
                                    $limite1=$prodscolt1['limite']-$now;
                                    $date1=$prodscolt1['dlimite'];

                                }


                                if (empty($prodscolt2)) {

                                    $montant2=0;
                                    $limite2=0;
                                    $date2=0;

                                }else{
                                    $montant2=$prodscolt2['montant'];
                                    $limite2=$prodscolt2['limite']-$now;
                                    $date2=$prodscolt2['dlimite'];

                                }

                                if (empty($prodscolt3)) {

                                    $montant3=0;
                                    $limite3=0;
                                    $date3=0;

                                }else{
                                    $montant3=$prodscolt3['montant'];
                                    $limite3=$prodscolt3['limite']-$now;
                                    $date3=$prodscolt3['dlimite'];

                                }
                               
                            }



                            $i=0;?>

                            <div style="display: flex;"><?php 

                            foreach ($prodscol as $tranche) {?>
                                <div>

                                <table class="table table-hover table-bordered table-striped table-responsive text-center">


                                    <thead><?php

                                        if ($i==0) {?>

                                            <tr>

                                                <form class="form" method="GET" action="synthesecredit.php" id="suitec" name="termc">

                                                    <tr>
                                                        <th colspan="3" class="info" style="text-align: center">Liste des <?=$_SESSION['typeel'];?></th>

                                                        <th colspan="2"></th>
                                                        
                                                        <th></th>

                                                        
                                                    </tr>

                                                </form>

                                                <th>N°</th>
                                                <th height="30" width="10">Matricule</th>
                                                <th>Nom & Prénom</th>
                                                <th>Téléphone</th>
                                                <th>Filière</th>
                                                <th height="30"><?=$tranche->nom;?></th>
                                            </tr><?php

                                        }else{?>
                                            <tr>
                                                <th height="35" colspan="2" style="height: 40px;"></th>
                                            </tr>
                                            <tr>

                                                <th height="35"><?=$tranche->nom;?></th>
                                            </tr><?php
                                        }?>
                                    </thead>

                                        <tbody><?php


                                            if (isset($_POST['groupe'])) {

                                                $prodelevenote=$DB->query('SELECT  inscription.matricule as matricule, nomel, prenomel, adresse, DATE_FORMAT(naissance, \'%Y\')AS naissance, phone, classe, formation.codef as codef, nomf, remise from inscription inner join eleve on eleve.matricule=inscription.matricule inner join contact on inscription.matricule=contact.matricule inner join formation on inscription.codef=formation.codef where  nomgr=:nom and annee=:promo order by (matricule)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

                                            }elseif (isset($_GET['termec'])) {

                                              $_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
                                              $terme = $_GET['termec'];
                                              $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
                                              $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
                                              $terme = strtolower($terme);

                                              $prodelevenote =$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel, adresse, DATE_FORMAT(naissance, \'%Y\')AS naissance, phone, classe, formation.codef as codef, nomf, remise from inscription inner join eleve on eleve.matricule=inscription.matricule inner join contact on inscription.matricule=contact.matricule inner join formation on inscription.codef=formation.codef WHERE eleve.matricule LIKE ? or nomel LIKE ? or prenomel LIKE ? or phone LIKE ?',array("%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
                                              
                                            }else{

                                                $prodelevenote=$DB->query('SELECT  inscription.matricule as matricule, nomel, prenomel, adresse, DATE_FORMAT(naissance, \'%Y\')AS naissance, phone, classe, formation.codef as codef, nomf, remise from inscription inner join eleve on eleve.matricule=inscription.matricule inner join contact on inscription.matricule=contact.matricule inner join formation on inscription.codef=formation.codef where annee=:promo order by (matricule)', array('promo'=>$_SESSION['promo']));

                                            }

                                            foreach ($prodelevenote as $key => $eleve) {

                                                if (isset($_POST['groupe'])) {

                                                    $prodtranche=$DB->query('SELECT  sum(montant) as montant, tranche, remise from payementfraiscol inner join eleve on payementfraiscol.matricule=eleve.matricule inner join inscription on inscription.matricule=eleve.matricule where payementfraiscol.matricule=:mat and tranche=:tranche and payementfraiscol.promo=:promo and annee=:annee and nomgr=:nom order by (eleve.matricule)', array('mat'=>$eleve->matricule, 'tranche'=>$tranche->nom, 'promo'=>$_SESSION['promo'], 'annee'=>$_SESSION['promo'], 'nom'=>$_SESSION['groupe']));

                                                }else{

                                                    $prodtranche=$DB->query('SELECT  sum(montant) as montant, tranche, remise from payementfraiscol inner join eleve on payementfraiscol.matricule=eleve.matricule inner join inscription on inscription.matricule=payementfraiscol.matricule where payementfraiscol.matricule=:mat and tranche=:tranche and payementfraiscol.promo=:promo order by (eleve.matricule)', array('mat'=>$eleve->matricule, 'tranche'=>$tranche->nom, 'promo'=>$_SESSION['promo']));

                                                }

                                                $nom=ucfirst($eleve->prenomel).' '.strtoupper($eleve->nomel);

                                                if ($eleve->classe==1) {
                                                    $filiere=$eleve->classe.'ère '.$eleve->nomf;
                                                }else{

                                                    $filiere=$eleve->classe.'ère '.$eleve->nomf;               
                                                }



                                                if (empty($prodtranche)) {?>

                                                    <tr><?php

                                                        if ($i==0) {?>

                                                            <td style="text-align: center;"><?=$key+1;?></td>

                                                            <td style="text-align: center;"><a href="comptabilite.php?eleve=<?=$eleve->matricule;?>"><?=$eleve->matricule;?></a></td>

                                                            <td><?=ucfirst($eleve->prenomel).' '.strtoupper($eleve->nomel);?></td>

                                                            <td style="text-align: center;"><?=ucwords($eleve->phone);?></td><?php

                                                            if ($eleve->classe==1) {?>

                                                                <td><?=$eleve->classe.'ère '.$eleve->codef;?></td><?php
                                                            }else{?>

                                                                <td><?=$eleve->classe.'ème '.$eleve->codef;?></td><?php                
                                                            }?>

                                                            <td style="text-align: right; color: red; font-size: 25px;">0</td><?php

                                                        }else{

                                                            if (isset($_POST['groupe']) and $tranche->nom=='2eme tranche' and $limite2<=0) {?>
                                                                    
                                                                <td style="text-align: right; color: red; font-size: 25px;">0</td><?php

                                                                }elseif (isset($_POST['groupe']) and $tranche->nom=='2eme tranche' and $limite2==1) {?>
                                                                    
                                                                    <td style="text-align: right; color: orange; font-size: 25px;">0</td><?php

                                                                }elseif (isset($_POST['groupe']) and $tranche->nom=='3eme tranche' and $limite3<=0) {?>
                                                                    
                                                                    <td style="text-align: right; color: red; font-size: 25px;">0</td><?php

                                                                }elseif (isset($_POST['groupe']) and $tranche->nom=='3eme tranche'  and $limite3==1) {?>
                                                                    
                                                                    <td style="text-align: right; color: orange; font-size: 25px;">0</td><?php
                                                                }else{?>

                                                                    <td style="text-align: right; color: green; font-size: 25px;">0</td><?php
                                                                }

                                                            
                                                        }?>
                                                        
                                                    </tr><?php

                                                }else{

                                                    foreach ($prodtranche as $montant) {

                                                        $montantrem1=$montant->montant+(($montant->remise/100)*$montant1);

                                                        $montantrem2=$montant->montant+(($montant->remise/100)*$montant2);

                                                        $montantrem3=$montant->montant+(($montant->remise/100)*$montant3);

                                                        if ($montant->remise==0) {

                                                             $remise='';

                                                        }else{

                                                            $remise='('.$montant->remise.'%)';
                                                        } ?>

                                                        <tr><?php
                                                            $nom=ucfirst($eleve->prenomel).' '.strtoupper($eleve->nomel);

                                                            if ($i==0) {?>

                                                                <td style="text-align: center;"><?=$key+1;?></td>

                                                                <td style="text-align: center;"><a href="comptabilite.php?eleve=<?=$eleve->matricule;?>"><?=$eleve->matricule;?></a></td>

                                                                <td><?=ucfirst($eleve->prenomel).' '.strtoupper($eleve->nomel);?></td>

                                                                <td style="text-align: center;"><?=ucwords($eleve->phone);?></td><?php

                                                                if ($eleve->classe==1) {
                                                                    $filiere=$eleve->classe.'ère '.$eleve->nomf;?>

                                                                    <td><?=$eleve->classe.'ère '.$eleve->codef;?></td><?php
                                                                }else{

                                                                    $filiere=$eleve->classe.'ère '.$eleve->nomf;?>

                                                                    <td><?=$eleve->classe.'ème '.$eleve->codef;?></td><?php                
                                                                }

                                                                if (isset($_POST['groupe']) and $montant->tranche=='1ere tranche' and $montantrem1<$montant1 and $limite1<=0) {?>
                                                                    
                                                                    <td style="text-align: right; color: red; font-size: 20px;"><?=number_format($montant->montant,0,',',' ');?><label style="font-size: 10px;color: red; width: 1%;"><?=$remise;?></label></td><?php

                                                                }elseif (isset($_POST['groupe']) and $montant->tranche=='1ere tranche' and $montantrem1<$montant1 and $limite1==1) {?>
                                                                    
                                                                    <td style="text-align: right; color: orange; font-size: 20px;"><?=number_format($montant->montant,0,',',' ');?>
                                                                    <label style="font-size: 10px;color: red; width: 1%;"><?=$remise;?></label></td><?php

                                                                }else{?>

                                                                    <td style="text-align: right; color: green; font-size: 20px;"><?=number_format($montant->montant,0,',',' ');?>
                                                                    <label style="font-size: 10px;color: red; width: 1%;"><?=$remise;?></label></td><?php
                                                                }

                                                            }else{

                                                                if (isset($_POST['groupe']) and $montant->tranche=='2eme tranche' and $montantrem2<$montant2 and $limite2<=0) {?>
                                                                    
                                                                    <td style="text-align: right; color: red; font-size: 20px;"><?=number_format($montant->montant,0,',',' ');?><label style="font-size: 10px;color: red; width: 1%;"><?=$remise;?></label></td><?php

                                                                }elseif (isset($_POST['groupe']) and $montant->tranche=='2eme tranche' and $montantrem2<$montant2 and $limite2==1) {?>
                                                                    
                                                                    <td style="text-align: right; color: orange; font-size: 20px;"><?=number_format($montant->montant,0,',',' ');?><label style="font-size: 10px;color: red; width: 1%;"><?=$remise;?></label></td><?php

                                                                }elseif (isset($_POST['groupe']) and $montant->tranche=='3eme tranche' and $montantrem3<$montant3 and $limite3<=0) {?>
                                                                    
                                                                    <td style="text-align: right; color: red; font-size: 20px;"><?=number_format($montant->montant,0,',',' ');?><label style="font-size: 10px;color: red; width: 1%;"><?=$remise;?></label></td><?php

                                                                }elseif (isset($_POST['groupe']) and $montant->tranche=='3eme tranche' and $montantrem3<$montant3 and $limite3==1) {?>
                                                                    
                                                                    <td style="text-align: right; color: orange; font-size: 20px;"><?=number_format($montant->montant,0,',',' ');?><label style="font-size: 10px;color: red; width: 1%;"><?=$remise;?></label></td><?php

                                                                }else{?>

                                                                    <td style="text-align: right; color: green; font-size: 20px;"><?=number_format($montant->montant,0,',',' ');?><label style="font-size: 10px;color: red; width: 1%;"><?=$remise;?></label></td><?php
                                                                }
                                                            }?>

                                                        </tr><?php  

                                                    }
                                                }
                                            }?>
                                        </tbody>
                                    </table>

                                </div><?php

                                $i++;
                            }?>

                        </div>

                    </div><?php
                }else{?>

                    <div class="alerteV">Selectionnez un Groupe!!!!</div><?php
                }
            }
        }
    }?>
</div>


