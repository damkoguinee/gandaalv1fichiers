<?php 
require_once "phpqrcode/qrlib.php";
require_once "phpqrcode/qrconfig.php";?>

<div class="container-fluid mt-1"><?php

    if (isset($_GET['fiche_eleve']) or isset($_POST['eleve']) or isset($_GET['disci']) or isset($_GET['supimg']) or isset($_GET['ajoutimg']) or isset($_POST["ajoutimg"]) or isset($_GET['inscript']) or isset($_GET['eleve']) or isset($_POST['j1'])) {

        if (isset($_GET['fiche_eleve'])) {
            $_SESSION['fiche']=$_GET['fiche_eleve'];
            $promoins=$_GET['promo'];
        }

        if (isset($_GET['disci'])) {
            $_SESSION['fiche']=$_GET['disci'];
            $promoins=$_GET['promo'];
        }

        if (isset($_GET['inscript'])) {
            $_SESSION['fiche']=$matricule;
            $promoins=$_SESSION['promo'];
        }

        if (isset($_POST['eleve'])) {
            $_SESSION['fiche']=$_POST['eleve'];
            $promoins=$_SESSION['promo'];
        }

        if (isset($_GET['eleve'])) {
            $_SESSION['fiche']=$_SESSION['matricule'];
            $promoins=$_SESSION['promo'];
        }

        if (isset($_POST["ajoutimg"])) {
            $_SESSION['fiche']=$_POST["env"];
            $promoins=$_SESSION['promo'];
        }

        if (isset($_POST["j1"])) {
            $_SESSION['fiche']=$_SESSION['fiche'];
            $promoins=$_SESSION['promo'];
        }

        if (isset($_GET['etatscol'])) {
            if ($_GET['etatscol']=='actif') {
                $etatup='inactif';
            }else{
                $etatup='actif';
            }

            $DB->insert('UPDATE inscription SET etatscol=? WHERE matricule=? and annee=?', array($etatup, $_GET['fiche_eleve'], $_GET['promo']));
        }

       
        $mat=$_SESSION['fiche'];

        $inscrit=$DB->querys('SELECT id from inscription where matricule=:mat and annee=:promo', array('mat'=>$mat, 'promo'=>$promoins));


        if (empty($inscrit['id'])) {

           $fiche=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, pere, telpere, mere, telmere, date_format(naissance,\'%d/%m/%Y \') as naiss, phone, email, nomtut, teltut from eleve inner join contact on eleve.matricule=contact.matricule inner join tuteur on eleve.matricule=tuteur.matricule where eleve.matricule=:mat', array('mat'=>$mat));
           $color="";
        }else{

            $fiche=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, pere, telpere, mere, telmere, date_format(naissance,\'%d/%m/%Y \') as naiss, phone, email , annee, nomf, classe, nomgr, nomtut, teltut, formation.codef as codef, etatscol from eleve inner join contact on eleve.matricule=contact.matricule inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join tuteur on eleve.matricule=tuteur.matricule where eleve.matricule=:mat and annee=:promo', array('mat'=>$mat, 'promo'=>$_SESSION['promo']));

            if ($fiche['etatscol']=='actif') {
                $color='rgba(1, 175, 80, 0.3)';
                $etatscol='actif';
            }else{
                $color='danger';
                $etatscol='inactif';
            }
        }?>

        <div class="row">

            <div class="col-sm-12 col-md-12 ">

                <div class="col rounded-end text-dark text-opacity-75" style="box-shadow: 10px 2px 20px; margin-bottom: 10px; "><?php

                    if (isset($_GET['fiche_eleve']) or isset($_POST["ajoutimg"])) {?>

                        
                        <div class="fichel bg-<?=$color;?>" style="font-size: 20px; font-weight: bold; text-align: center; margin-top: 10px;" >Fiche de renseignements de l'élève</div>
                        <?php
                    }

                    $nomel=ucfirst(strtolower($fiche['prenomel'])).' '.strtoupper($fiche['nomel']);?>

                    <div class="row">       

                        <div class="col-sm-12 col-md-4">
                            <ol style="font-size: 15px;">
                                <li class="fw-bold"><label class="label">Matricule</label><?=strtoupper($mat);?></li>

                                <li class="fw-bold"><label class="label">Nom</label> <?=strtoupper($fiche['nomel']);?></li>

                                <li class="fw-bold"><label class="label">Prénom</label> <?=ucwords(strtolower($fiche['prenomel']));?></li>

                                <li class="fw-bold"><label class="label">Né(e) le</label> <?=$fiche['naiss'];?></li>

                                <li class="fw-bold"><label class="labell"><img style="width: 20px;" class="card-img-left" src="css/img/phone.jpg"/></label> <?=$fiche['phone'];?></li>

                                <li class="fw-bold"><label class="labell"><img style="width: 20px;" class="card-img-left" src="css/img/email.jpg"/></label><?=$fiche['email'];?></li><?php

                                if (!empty($inscrit)) {

                                    if ($fiche['classe']=='terminale' or $fiche['classe']=='toute petite section' or $fiche['classe']=='petite section' or $fiche['classe']=='moyenne section' or $fiche['classe']=='grande section') {?>

                                        <li class="fw-bold"><label class="label">Inscrit en </label><?=ucwords($fiche['classe'].' '.$fiche['nomf']);?></li><?php

                                    }elseif ($fiche['classe']=='1') {?>

                                        <li class="fw-bold"><label class="label">Inscrit en </label><?=$fiche['classe'].' ère année '.$fiche['nomf'].' Année: '.($fiche['annee']-1).'-'.$fiche['annee'];?></li><?php

                                    }else{?>

                                        <li class="fw-bold"><label class="label">Inscrit en </label><?=$fiche['classe'].' ème année '.$fiche['nomf'].' Année: '.($fiche['annee']-1).'-'.$fiche['annee'];?></li><?php
                                    }?>

                                    <li class="fw-bold"><label class="label">Classe </label> <?=strtoupper($fiche['nomgr']);?></li><?php
                                    
                                }else{?>

                                    <li class="fw-bold"><label>Non Inscrit</label>Année scolaire <?=$_SESSION['promo']-1;?> - <?=$_SESSION['promo'];?></li><?php

                                }?>

                    
                            </ol>
                
                        </div><?php
            
                        require 'image.php';?> 

                        <div class="col-sm-12 col-md-5">
                            <ol style="font-size: 15px;">

                                <li class="fw-bold"><label>Filiation</label> <?=ucwords($fiche['pere']).' et de '.ucwords(strtolower($fiche['mere']));?></li>

                                <li class="fw-bold"><label class="label">Tuteur</label> <?=ucwords($fiche['nomtut']);?></li>

                                <li class="fw-bold"><label class="label">Tél du Père</label> <?=$fiche['telpere'];?></li>

                                <li class="fw-bold"><label class="label">Tél de la Mère</label> <?=$fiche['telmere'];?></li>

                                <li class="fw-bold"><label class="label">Tél du Tuteur</label> <?=$fiche['teltut'];?></li>

                                <li class="fw-bold"><label class="label">Etat</label><a class="btn" href="fiche_eleve.php?etatscol=<?=$etatscol;?>&fiche_eleve=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>"><input type="button" value="<?=strtoupper($etatscol);?>" ></a></li>                   
                            </ol>
                
                        </div>           

                    </div><?php

                    if (isset($_GET['mateleve']) or isset($_GET['enseignant'])) {

                    }else{

                        if (isset($_GET['fiche_eleve']) or isset($_GET['inscript'])) {?>
                            <div class="alert alert-success text-center fw-bold fs-5 ">

                                <a class="btn btn-primary" href="ajout_eleve.php?inscriptfic=<?=$mat;?>">Réinscription</a>

                                <a class="btn btn-primary" href="matiere.php?matiereel=<?=$mat;?>&codef=<?=$fiche['codef'];?>">Matières </a>  

                                <a class="btn btn-primary" href="enseignement.php?matiereel=<?=$mat;?>">Enseignants</a><?php 

                                if ($products['type']=='admin' or $products['type']=='fondation' or $products['type']=='fondateur' or $products['type']=='Admistrateur Général' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire') {?>

                                    <a class="btn btn-danger" href="comptabilite.php?eleve=<?=$mat;?>">Mensualités</a><?php 
                                }?>

                                <a class="btn btn-primary" href="fiche_inscription.php?ficheins=<?=$mat;?>" target="_blank">Inscription</a>

                                <a class="btn btn-primary" href="carte_scolaire1.php?voircartel=<?=$mat;?>" target="_blank">Carte Scolaire</a>

                                <a class="btn btn-primary" href="carte_scolaire.php?voircartel=<?=$mat;?>" target="_blank">Carte de Retrait</a>

                                <a class="btn btn-primary" href="discipline.php?disci=<?=$mat;?>&nomel=<?=$nomel;?>&promo=<?=$_SESSION['promo'];?>">Assiduité</a>

                                <a class="btn btn-primary" href="document.php?docel=<?=$mat;?>&fiche_eleve=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>" >Mes Documents</a><?php

                                if ($products['type']=='admin' or $products['type']=='informaticien') {?>

                                    <a class="btn btn-primary" href="ajout_eleve.php?del_eleve=<?=$mat;?>" onclick="return alerteS();" >Supprimer</a><?php 
                                }?>

                            </div><?php
                        }
                    }?>
                </div>
            </div>
        </div><?php 
    }?>

</div>