
<div class="col-sm-12 col-md-2 pb-3 " style="background-color: #253553;">

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="sms.php?sms">Messagerie<img style="width:15%; height: 40px;" src="css/img/sms.jpg"></a></div></div>

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="cursus.php?cursus">Cursus Scolaire</a></div></div>

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="formation.php?form&note">Formations</a></div></div><?php 
     
    if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='Directeur Général' or  $products['type']=='bibliothecaire' or  $products['type']=='comptable') {?>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="tranche.php?scol">Tranches</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="fraisinscription.php?scol">Frais d'inscript/Reins</a></div></div>
        
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="scolarite.php?scol">Frais de scolarité</a></div></div><?php 
    }
		    	

    if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='informaticien' or $products['type']=='Directeur Général' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='surveillant Général' or $products['type']=='comptable' or $products['type']=='coordonateur bloc B' or $products['type']=='secrétaire' or $products['type']=='bibliothecaire') {?> 


        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="matiere.php?matiere">Matières</a></div></div>


        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="enseignant.php?enseig&effnav">Enseignants</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="personnellist.php?personnel&effnav">Personnels</a></div></div> 

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="groupe.php?group">Classes</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="enseignement.php?enseign">Cours</a></div></div><?php 
	} 

    if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='bibliothecaire') {?>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="etablissement.php?etab">Etablissement</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="etablissement.php?cloturer">Clôturer</a></div></div><?php

        if ($products['niveau']>5) {?>

            <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="historiquesup.php">Historique Sup</a></div></div><?php
        }
    }?>
</div>

<?php require 'footer.php';?>