<div class="col-sm-12 col-md-2 pb-3 " style="background-color: #253553;"> <?php

    if ($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true" OR $panier->searchRole("ROLE_COMPTABLE")=="true") {?>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="comptabilite.php?paye">Paiements Scolarité</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="credit.php?note">Gestion des Impayés</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="accompte.php?payeem">Avance sur Salaire</a></div></div>
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="prime.php?payeem">Primes</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="payementemployer.php?payeem">Paiements Enseignants</a></div></div>

        <!-- <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="comptabilite.php?payeem">Paie Enseignants ind</a></div></div> -->

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="payementpersonnel.php?payepers">Paiements Personnels</a></div></div>

        <!-- <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="payementpersonnel0.php?payepers">Paie Personnels ind</a></div></div> -->

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="etatsalaire.php?etat">Etat des Salaires Enseignants</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="etatsalairepers.php?etat">Etat des Salaires Personnels</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="depense.php?dep">Dépenses</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="versement.php?dep">Recettes</a></div></div>
        
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="detteCreances.php?dep">Dettes/Créances</a></div></div><?php 
    }

    if ($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_COMPTABLE")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {?>
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="banque.php?compta">Transfert des fonds</a></div></div>
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="synthesecompta.php?compta">Synthèse Générale</a></div></div><?php 
    }?>
</div>