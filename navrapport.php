<div class="col-sm-12 col-md-2 pb-3 " style="background-color: #253553;">

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapport.php?credit">Statistique Générale</a></div></div>

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportcursus.php?credit">Stat par Niveau</a></div></div>

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportclasse.php?credit">Stat par Classe</a></div></div><?php 
    /*

    <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="centralisation.php">Centralisation Ministère</a></div></div><?php */

    if (($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_COMPTABLE")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") ) {?> 


        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportinscription.php?credit">Stat Inscription</a></div></div>

        <!-- <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportscolarite.php?credit">Stat Frais de Scolarité</a></div></div> -->

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportinscription.php?remiseins">Remise Inscription</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="rapportscolarite.php?remisescol">Remise Scolarité</a></div></div><?php 
    }
    if ( $panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_COMPTABLE")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {?> 
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="bilancomptable.php?bilanc">Bilan Comptable</a></div></div>
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="bilancomptableV2.php?bilanc">Bilan Comptable/abandons</a></div></div><?php 
    }?>
</div> 