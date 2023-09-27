<div class="col-sm-12 col-md-2 pb-3 "style="background-color: #253553;"> <?php

if ($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_COMPTABLE")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {?>

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="preinscription.php">Pré-inscription</a></div></div>

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="preinscriptiontraite.php">Liste des Pré-inscris</a></div></div>

    <?php 
    if ($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_COMPTABLE")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {?>
    
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="ajout_eleve.php?ajoute&note">Inscription</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="inscription.php?inscript&note">Ré-inscription</a></div></div><?php 
    }
}?>

<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="ajout_eleve.php?listeeleve">Liste des élèves</a></div></div>
</div>