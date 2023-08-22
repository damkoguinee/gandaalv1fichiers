<?php 
require_once "phpqrcode/qrlib.php";
require_once "phpqrcode/qrconfig.php";
require 'headerv2.php';?>

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
        $codeContent=$mat;
        $fileName=$mat.".png";
        $cheminQrcode='qrcode/'.$fileName;
        if (!file_exists($cheminQrcode)) {
            QRcode::png($codeContent, $cheminQrcode);
        }

        $inscrit=$DB->querys('SELECT id from inscription where matricule=:mat and annee=:promo', array('mat'=>$mat, 'promo'=>$promoins));


        if (empty($inscrit['id'])) {

           $fiche=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, pere, telpere, mere, telmere, date_format(naissance,\'%d/%m/%Y \') as naiss, adresse, nationnalite, profm, profp, lieutp, lieutm, adressep, phone, email, nomtut, teltut from eleve left join contact on eleve.matricule=contact.matricule left join tuteur on eleve.matricule=tuteur.matricule where eleve.matricule=:mat', array('mat'=>$mat));
           $color="";
        }else{

            $fiche=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, pere, telpere, mere, telmere, date_format(naissance,\'%d/%m/%Y \') as naiss, adresse, nationnalite, profp, profm, lieutp, lieutm, adressep, phone, email , annee, nomf, classe, nomgr, nomtut, teltut, formation.codef as codef, etatscol, etat, statut, dateinscription from eleve left join contact on eleve.matricule=contact.matricule left join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef left join tuteur on eleve.matricule=tuteur.matricule where eleve.matricule=:mat and annee=:promo', array('mat'=>$mat, 'promo'=>$_SESSION['promo']));

            if ($fiche['etatscol']=='actif') {
                $color="success";
                $etatscol='actif';
            }else{
                $color='danger';
                $etatscol='inactif';
            }

            if ($fiche['etat']=='inscription') {
                $etat="Inscrit ";
                $etatp="FRAIS D'INSCRIPTION";
            
            }else{
            $etat="Reinscrit";
            $etatp="FRAIS DE REINSCRIPTION";
        
            }

            if (empty($fiche['dateinscription'])) {
                $dateinscrit=(new dateTime($fiche['dateinscription']))->format("d/m/Y");
            }else{
                $dateinscrit=(new dateTime($fiche['dateinscription']))->format("d/m/Y");
            }
        }?>

        <div class="row">

            <div class="col-sm-12 col-md-12 ">

                <div class="col rounded-end text-dark text-opacity-75" style="box-shadow: 10px 2px 20px; margin-bottom: 10px; "><?php

                    if (isset($_GET['fiche_eleve']) or isset($_POST["ajoutimg"])) {?>

                        
                        <div class="fichel bg-<?=$color;?> mb-3" style="font-size: 20px; font-weight: bold; text-align: center; margin-top: 10px;" >Fiche de renseignements de l'élève</div>
                        <?php
                    }

                    $nomel=ucfirst(strtolower($fiche['prenomel'])).' '.strtoupper($fiche['nomel']);?>

                    <div class="row">       

                        <div class="col-sm-12 col-md-5">
                            <ol style="font-size: 15px;">
                                <li class="fw-bold"><label class="label">Matricule</label><?=strtoupper($mat);?></li>

                                <li class="fw-bold"><label class="label">Nom</label> <?=strtoupper($fiche['nomel']);?></li>

                                <li class="fw-bold"><label class="label">Prénom</label> <?=ucwords(strtolower($fiche['prenomel']));?></li>

                                <li class="fw-bold"><label class="label">Né(e) le</label> <?=$fiche['naiss'];?> à <?=$fiche['adresse'];?></li>

                                <li class="fw-bold"><label class="label">Nationnalite</label> <?=$fiche['nationnalite'];?></li>

                                <li class="fw-bold"><label class="labell"><img style="width: 20px;" class="card-img-left" src="css/img/phone.jpg"/></label> <?=$fiche['phone'];?></li>

                                <li class="fw-bold"><label class="labell"><img style="width: 20px;" class="card-img-left" src="css/img/email.jpg"/></label><?=$fiche['email'];?></li><?php

                                if (!empty($inscrit)) {

                                    if ($fiche['classe']=='terminale' or $fiche['classe']=='toute petite section' or $fiche['classe']=='petite section' or $fiche['classe']=='moyenne section' or $fiche['classe']=='grande section') {?>

                                        <li class="fw-bold pt-3"><label class="label"><?=$etat;?> en </label><?=ucwords($fiche['classe'].' '.$fiche['nomf']);?></li><?php

                                    }elseif ($fiche['classe']=='1') {?>

                                        <li class="fw-bold"><label class="label"><?=$etat;?> en </label><?=$fiche['classe'].' ère année '.$fiche['nomf'];?></li><?php

                                    }else{?>

                                        <li class="fw-bold"><label class="label"><?=$etat;?> en </label><?=$fiche['classe'].' ème année '.$fiche['nomf'];?></li><?php
                                    }?>

                                    <li class="fw-bold"><label class="label"><?=$etat;?> le  </label> <?=strtoupper($dateinscrit);?></li>

                                    <li class="fw-bold"><label class="label">Statut  </label> <?=ucwords($fiche['statut']);?></li>

                                    <li class="fw-bold"><label class="label">Classe </label> <?=strtoupper($fiche['nomgr']);?></li><?php
                                    
                                }else{?>

                                    <li class="fw-bold"><label>Non Inscrit</label>Année scolaire <?=$_SESSION['promo']-1;?> - <?=$_SESSION['promo'];?></li><?php

                                }?>

                                <li class="fw-bold"><label class="label">Année</label><?=($fiche['annee']-1).'-'.$fiche['annee'];?></li>

                    
                            </ol>
                
                        </div><?php
            
                        require 'image.php';?> 
                        

                        <div class="col-sm-12 col-md-5">
                            <ol style="font-size: 15px;">

                                <li class="fw-bold"><label>Filiation</label> <?=ucwords($fiche['pere']).' et de '.ucwords(strtolower($fiche['mere']));?></li>

                                <li class="fw-bold">Profession du Père <?=ucwords($fiche['profp']);?></li>
                                <li class="fw-bold">Lieu de travail <?=ucwords($fiche['lieutp']);?></li>
                                <li class="fw-bold">Profession du Père <?=ucwords($fiche['profp']);?></li>
                                <li class="fw-bold">Lieu de travail de la Mère <?=ucwords($fiche['lieutm']);?></li>
                                <li class="fw-bold">Adresse/Parents <?=ucwords($fiche['adressep']);?></li>
                                <li class="fw-bold"><label class="label">Tuteur</label> <?=ucwords($fiche['nomtut']);?></li>

                                <li class="fw-bold"><label class="label">Tél du Père</label> <?=$fiche['telpere'];?></li>

                                <li class="fw-bold"><label class="label">Tél de la Mère</label> <?=$fiche['telmere'];?></li>

                                <li class="fw-bold"><label class="label">Tél du Tuteur</label> <?=$fiche['teltut'];?></li>

                                <li class="fw-bold mt-5"><label class="label">Etat</label><a onclick="return alerteV();" class="btn btn-<?=$color;?>" href="fiche_elevegen.php?etatscol=<?=$etatscol;?>&fiche_eleve=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>"><?=strtoupper($etatscol);?></button></a></li>   
                                
                                <div class="modal" tabindex="-1">
  
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

                                    <a class="btn btn-danger" href="ajout_eleve.php?del_eleve=<?=$mat;?>" onclick="return alerteS();" >Supprimer</a><?php 
                                }?>

                            </div><?php
                        }
                    }?>
                </div>                
            </div>
        </div><?php 

        $prod=$DB->query("SELECT *From inscriptactivites where matinscrit='{$mat}' and promoact='{$_SESSION['promo']}' ");

        if (empty($prod)) {
             # code...
        }else{?>

            <div class="container-fluid">

                <div class="row">

                    <div class="col col-sm-12 col-md-6">

                        <table class="table table-hover table-bordered table-striped table-responsive text-center">
                            <thead>
                                <tr><th colspan="7">Liste de mes activités</th></tr>

                                <tr>
                                  <th scope="col" class="text-center">N°</th>                                     
                                  <th scope="col">Désignation</th>
                                  <th scope="col">Tarif Mensuel</th>
                                  <th scope="col">Remise</th>
                                  <th scope="col">Montant Payé</th>
                                  <th scope="col">Date de Début</th>
                                  <th>Action</th>
                                </tr>
                            </thead>

                            <tbody><?php 
                                foreach ($prod as $key => $value) {

                                    $prodmontant=$DB->querys("SELECT sum(montantp) as montant FROM activitespaiehistorique where idact='{$value->idact}' and matp='{$mat}' and promoact='{$_SESSION['promo']}'");

                                    $montantp=$prodmontant['montant'];

                                    $dated=(new DateTime($value->dateop))->format("d/m/Y")?>

                                    <tr>
                                        <td><?=$key+1;?></td>
                                        <td style="text-align: left;"><?=$panier->nomActivites($value->idact)[0];?></td>
                                        <td><?=number_format($value->mensualite,0,',',' ');?></td>
                                        <td><?=number_format($value->remiseact,2,',',' ');?>%</td>
                                        <td><?=number_format($montantp,2,',',' ');?></td>
                                        <td><?=$dated;?></td>
                                        <td><a class="btn btn-info" href="fiche_elevegen.php?fiche_eleve=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>&idactvoir=<?=$value->idact;?>">+Infos</a></td>
                                    </tr><?php 
                                }?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col col-sm-12 col-md-6"><?php 
                        if (isset($_GET['idactvoir'])) {

                            $prod=$DB->query("SELECT montantp as montant, idact, moisp, devise, taux, dateop FROM activitespaiehistorique where idact='{$_GET['idactvoir']}' and matp='{$mat}' and promoact='{$_SESSION['promo']}'");?>

                            <table class="table table-hover table-bordered table-striped table-responsive text-center">
                                <thead>
                                    <tr><th colspan="5">Historique des Paiements <?=$panier->nomActivites($_GET['idactvoir'])[0];?></th></tr>

                                    <tr>
                                      <th scope="col" class="text-center">N°</th>                                     
                                      <th scope="col">Mois</th>
                                      <th scope="col">Montant Payé</th>
                                      <th scope="col">Date</th>
                                      <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody><?php 
                                    $montantcumul=0;
                                    foreach ($prod as $key => $value) {

                                        $montantcumul+=$value->montant;

                                        $dated=(new DateTime($value->dateop))->format("d/m/Y")?>

                                        <tr>
                                            <td><?=$key+1;?></td>
                                            <td><?=$value->moisp;?></td>
                                            <td><?=number_format($value->montant,0,',',' ');?></td>
                                            <td><?=$dated;?></td>
                                            <td><a class="btn btn-danger" href="fiche_elevegen.php?fiche_eleve=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>&idactvoir=<?=$value->idact;?>" onclick="return alerteV();">Annuler</a></td>
                                        </tr><?php 
                                    }?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="2">Totaux</th>
                                        <th><?=number_format($montantcumul,0,',',' ');?></th>
                                    </tr>
                                </tfoot>
                            </table><?php 
                        }?>
                    </div>
                </div>
            </div><?php 
        }
    }?>

</div>

<?php require 'footer.php';?>

<script type="text/javascript">

    function alerteI(){
        return(confirm('Etes-vous sûr de vouloir initialisé? Le nouveau réçu ne sera pas lié au réçu précédent'));
    }

    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir annuler ce paiement ? il se peut que ce réçu soit lié'));
    }

    function alerteSains(){
        return(confirm('Etes-vous sûr de vouloir annuler cette inscription ?'));
    }

    function alerteSins(){
        return(confirm('Etes-vous sûr de vouloir annuler ce paiement ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>
