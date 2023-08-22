<?php
require 'headerv3.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>
        <div class="container-fluid">

            <div class="row"><?php

                require 'navcredit.php';

                $prodgroup=$DB->query('SELECT nomgr from groupe where promo=:promo order by(nomgr)', array('promo'=>$_SESSION['promo']));

                if (isset($_POST['annee'])) {

                    $_SESSION['annee']=$_POST['annee'];

                    $legende='promo '.($_SESSION['annee']-1).'-'. $_SESSION['annee'];

                    $_SESSION['mensuellec']='';
                    
                }



                if (isset($_POST['mensuellec'])) {

                    $_SESSION['mensuellec']=$_POST['mensuellec'];

                    $legende=' sur la '.$_POST['mensuellec'].' année-scolaire '.($_SESSION['annee']-1).'-'. $_SESSION['annee'];
                    
                }

                if (isset($_POST['groupe'])) {

                    $prodgroup=$DB->query('SELECT nomgr from groupe where promo=:promo order by(nomgr)', array('promo'=>$_SESSION['promo']));

                    if (isset($_POST['groupe'])) {
                        
                        $_SESSION['groupe']=$_POST['groupe'];

                        $prodcodef=$DB->querys('SELECT codef from groupe  where promo=:promo and nomgr=:nom', array('promo'=>$_SESSION['promo'], 'nom'=>$_POST['groupe']));
                    }else{

                        $prodcodef=$DB->querys('SELECT codef from groupe  where promo=:promo and nomgr=:nom', array('promo'=>$_SESSION['promo'], 'nom'=>$_SESSION['groupe']));

                    }

                    $legende=' de '.$_SESSION['groupe'].' sur la '.$_SESSION['mensuellec'].' année-scolaire '.($_SESSION['annee']-1).'-'. $_SESSION['annee'];
                    
                }

                if (isset($_GET['credit'])) {
                    $legende='';
                }

                if ((isset($_GET['credit']) or isset($_POST['annee']) or isset($_POST['groupe']) or isset($_POST['mensuellec']))) {

                    $etat='actif';

                    if (isset($_POST['annee'])) {

                        $prodpaye =$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel, adresse, DATE_FORMAT(naissance, \'%Y\')AS naissance, phone, codef, remise FROM eleve inner join inscription on eleve.matricule=inscription.matricule inner join contact on contact.matricule=inscription.matricule WHERE inscription.annee=:promoins and etatscol=:etat and inscription.matricule not in(SELECT matricule FROM payementfraiscol WHERE promo=:annee) order by(prenomel)', array('promoins'=>$_SESSION['annee'], 'etat'=>$etat, 'annee' => $_SESSION['annee']));

                    }elseif (isset($_POST['mensuellec'])) {

                        $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE tranche=:mois and promo=:promo', array('mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['annee']));

                        $montantscol=$prodscol['montant'];

                        $_SESSION['montantscol']=$montantscol;

                        
                        $prodpaye =$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel, adresse, DATE_FORMAT(naissance, \'%Y\')AS naissance, phone, codef, remise FROM eleve inner join inscription on eleve.matricule=inscription.matricule inner join contact on contact.matricule=inscription.matricule WHERE inscription.annee=:promoins and etatscol=:etat and  inscription.matricule not in(SELECT matricule FROM payementfraiscol WHERE (montant>=:montant and promo=:annee and tranche=:mois)) order by(prenomel)', array('promoins'=>$_SESSION['annee'], 'etat'=>$etat, 'montant'=>$montantscol, 'annee' => $_SESSION['annee'], 'mois'=>$_SESSION['mensuellec']));
                        
                    }elseif (isset($_POST['groupe'])){

                        $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['annee'], 'code'=>$prodcodef['codef']));

                        $montantscol=$prodscol['montant'];
                        $_SESSION['montantscol']=$montantscol;

                        $prodpaye =$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel, adresse, DATE_FORMAT(naissance, \'%Y\')AS naissance, phone, codef, remise FROM eleve inner join inscription on eleve.matricule=inscription.matricule inner join contact on contact.matricule=inscription.matricule WHERE nomgr=:nom and inscription.annee=:promoins and etatscol=:etat and eleve.matricule not in(SELECT matricule FROM payementfraiscol WHERE (montant>=:montant and promo=:annee and tranche=:mois)) order by(prenomel)', array('promoins'=>$_SESSION['annee'], 'etat'=>$etat, 'montant'=>$montantscol, 'annee' => $_SESSION['annee'], 'mois'=>$_SESSION['mensuellec'], 'nom'=>$_SESSION['groupe']));
                        
                    }?>

                    <div class="col-sm-12 col-md-10" style="overflow: auto;">

                        <table class="table table-hover table-bordered table-striped table-responsive text-center">

                            <thead>
                                <tr>
                                    <th colspan="11">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-3">
                                                    <form class="form" method="POST" action="credit.php" name="termc">

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
                                                </div><?php
                                                if (isset($_POST['annee']) or isset($_POST['mensuellec']) or isset($_POST['groupe'])) {?>

                                                    <div class="col-sm-12 col-md-2">
                                                        <form class="form" method="POST" action="credit.php" name="termc">

                                                            <select class="form-select" name="mensuellec" onchange="this.form.submit()"><?php

                                                                if (isset($_POST['mensuellec']) or isset($_POST['groupe'])) {?>

                                                                  <option value="<?=$_SESSION['mensuellec'];?>"><?=$_SESSION['mensuellec'];?></option><?php

                                                                }else{?>

                                                                  <option value="">Selectionnez la tranche !!</option><?php

                                                                }
                                                                foreach ($panier->tranche() as $value) {?>
                                                                    <option value="<?=$value->nom;?>"><?=ucwords($value->nom);?></option><?php
                                                                }?>
                                                            </select>
                                                        </form>
                                                        
                                                    </div>

                                                    <div class="col-sm-12 col-md-2">
                                                        <form class="form" method="POST" action="credit.php" name="termc">

                                                            <select class="form-select" name="groupe" onchange="this.form.submit()"><?php

                                                                if (isset($_POST['groupe'])) {?>

                                                                    <option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php
                                                                }else{?>

                                                                    <option>Choisissez la Classe</option><?php
                                                                }

                                                                foreach ($prodgroup as $form) {?>

                                                                    <option><?=$form->nomgr;?></option><?php

                                                                }?>
                                                            </select>
                                                        </form>
                                                        
                                                    </div><?php 
                                                }?>

                                                <div class="col-sm-12 col-md-5">
                                                    <form method="GET" action="synthesecredit.php" id="suitec" name="termc">
                                                        <div class="container-fluid">
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <input class="form-control" type = "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <input class="form-control"  type = "submit" name = "s" value = "search">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form> 
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                </tr>

                                <tr>
                                    <th colspan="11" height="30"><?='Liste des élèves n étant pas à jour '.$legende;?><?php

                                    if (isset($_POST['annee']) and !empty($_POST['annee'])) {

                                    }elseif (isset($_POST['mensuellec']) and !empty($_POST['mensuellec'])) {

                                    }elseif (isset($_POST['groupe']) and !empty($_POST['groupe'])){?>

                                        <a style="margin-left: 10px;"href="printdoc.php?decg=<?=$_SESSION['groupe'];?>&annee=<?=$legende;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                                        <a style="margin-left: 10px;"href="relance.php?courriert=<?=$_SESSION['groupe'];?>&montantscol=<?=$montantscol;?>&tranche=<?=$_SESSION['mensuellec'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/courrier.jpg"></a>

                                        <a style="margin-left: 10px;"href="relanceglobale.php?courriert=<?=$_SESSION['groupe'];?>&montantscol=<?=$montantscol;?>&tranche=<?=$_SESSION['mensuellec'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/courrier.jpg"></a><?php

                                    }?></th>
                                </tr>

                                <tr>
                                    <th></th>
                                    <th height="30" width="10">Matricule</th>
                                    <th>Nom & Prénom</th>
                                    <th>Né(e)</th>
                                    <th>Lieu de N</th>
                                    <th>Téléphone</th>
                                    <th>Payé</th>
                                    <th>Remise</th>
                                    <th>Reste</th>
                                    <th colspan="2"></th>
                                </tr>

                            </thead><?php

                            if (!empty($prodpaye)) {?>

                                <tbody><?php
                                    $reste1=0;
                                    $reste2=0;

                                    foreach($prodpaye as $key => $payeloc ){

                                        $prodscol = $DB->querys('SELECT montant FROM scolarite WHERE tranche=:mois and promo=:promo and codef=:code', array('mois'=>$_SESSION['mensuellec'], 'promo'=>$_SESSION['promo'], 'code'=>$payeloc->codef));

                                        if (empty($prodscol)) {
                                           $montantscol=0;
                                        }else{

                                            $montantscol=$prodscol['montant'];
                                        }

                                        $prodcredit =$DB->query('SELECT sum(montant) as montant, remise FROM payementfraiscol inner join inscription on inscription.matricule=payementfraiscol.matricule WHERE promo=:promo and annee=:promoins and payementfraiscol.matricule=:mat and tranche=:mois', array('promo'=>$_SESSION['annee'], 'promoins'=>$_SESSION['promo'], 'mat' => $payeloc->matricule, 'mois'=>$_SESSION['mensuellec']));

                                        $prodrem =$DB->querys('SELECT remise FROM inscription WHERE annee=:promoins and matricule=:mat', array('promoins'=>$_SESSION['promo'], 'mat' => $payeloc->matricule));

                                        

                                        if (empty($prodcredit)) {

                                        }elseif($payeloc->remise==100){

                                        }else{

                                            foreach ($prodcredit as $montant) {

                                                $resterem=$montantscol-(($montant->montant+(($prodrem['remise']/100)*$montantscol)));

                                                $reste2+=$resterem;

                                                if ($resterem!=0) {?>

                                                    <tr>
                                                        <td style="text-align: center;"><?=$key+1;?></td>
                                                        <td style="text-align: center;"><a href="comptabilite.php?eleve=<?=$payeloc->matricule;?>"><?=$payeloc->matricule;?></a></td>

                                                        <td><?=ucfirst($payeloc->prenomel).' '.strtoupper($payeloc->nomel);?></td>

                                                        <td style="text-align: center;"><?=$payeloc->naissance;?></td>

                                                        <td style="text-align: center;"><?=ucwords($payeloc->adresse);?></td>

                                                        <td style="text-align: center;"><?=ucwords($payeloc->phone);?></td>

                                                        <td style="text-align: right;"><?=number_format($montant->montant,0,',',' ');?></td>

                                                        <td style="text-align: center;"><?=$prodrem['remise'];?>%</td>

                                                        <td style="text-align: right; color: red;"><?=number_format($resterem,0,',',' ');?></td>

                                                        <td>
                                                            <a class="btn btn-success" href="comptabilite.php?eleve=<?=$payeloc->matricule;?>">Payer</a>
                                                        </td>

                                                        <td>

                                                            <a class="btn btn-info" href="ajout_eleve.php?fiche_eleve=<?=$payeloc->matricule;?>&promo=<?=$_SESSION['promo'];?>">+Infos</a>
                                                        </td>
                                                    </tr><?php
                                                }
                                            }
                                        }

                                    }?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="8">Reste à Payer</th>
                                        <th style="text-align:right"><?=number_format(($reste1+$reste2),0,',',' ');?></th>
                                    </tr>
                                </tfoot><?php 
                            }?>
                        </table>

                    </div><?php
                }?>
            </div>
        </div><?php
    }
}?>


