<div class="col-sm-12 col-md-2 pb-3" style="background-color: #253553;"> <?php

	if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire' or $products['type']=='bibliothecaire') {?>

		<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="">Gestion des Bâtiments</a></div></div>
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="piecesGestion.php?ajoutpiece">Gestion des Pièces/Salles</a></div></div>
		<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="bienGestion.php">Gestion des Biens</a></div></div>

		<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="accesitevisiteur.php?visiteur">Liste des biens</a></div></div><?php
	}?>
</div>