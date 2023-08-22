<div class="col-sm-12 col-md-2 pb-3 "style="background-color: #253553;"> <?php

if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire' or $products['type']=='bibliothecaire') {?>

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="preinscription.php">Pré-inscription</a></div></div>

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="preinscriptiontraite.php">Liste des Pré-inscris</a></div></div>

    <?php 
    if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire') {?>
    
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="ajout_eleve.php?ajoute&note">Inscription</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="inscription.php?inscript&note">Ré-inscription</a></div></div><?php 
    }
}?>

<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="ajout_eleve.php?listeeleve">Liste des élèves</a></div></div>
</div>