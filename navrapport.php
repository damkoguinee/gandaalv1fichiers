<div class="col-sm-12 col-md-2 pb-3 " style="background-color: #253553;">

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapport.php?credit">Statistique Générale</a></div></div>

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportcursus.php?credit">Stat par Niveau</a></div></div>

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportclasse.php?credit">Stat par Classe</a></div></div><?php 
    /*

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="centralisation.php">Centralisation Ministère</a></div></div><?php */

    if ($products['type']=='admin' or $products['type']=='fondation' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='Directeur Général' or $products['type']=='comptable' or $products['type']=='bibliothecaire' or $products['type']=='informaticien') {?> 


        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportinscription.php?credit">Stat Inscription</a></div></div>

        <!-- <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportscolarite.php?credit">Stat Frais de Scolarité</a></div></div> -->

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportinscription.php?remiseins">Remise Inscription</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportscolarite.php?remisescol">Remise Scolarité</a></div></div><?php 
    }
    if ($products['type']=='admin' or $products['type']=='fondation' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='Directeur Général' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='bibliothecaire') {?> 
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="bilancomptable.php?bilanc">Bilan Comptable</a></div></div><?php 
    }?>
</div> 