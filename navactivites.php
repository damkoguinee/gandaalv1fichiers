<div class="col-sm-12 col-md-2 pb-3" style="background-color: #253553;"> <?php

	if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire' or $products['type']=='bibliothecaire') {?>

		<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="inscriptionext.php">Inscription des Externes</a></div></div><?php 

		if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire' or $products['type']=='bibliothecaire') {?>

			<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="activitespaie.php">Liste des Paiements des internes</a></div></div>

			<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="activitespaieexterne.php">Liste des Paiements des externes</a></div></div>

			<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="activitesgestion.php?ideleve&liste">Paiement activités</a></div></div><?php 
		}
	}?>

	<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="listedeseleves.php?listeeleve">Liste des élèves</a></div></div>
</div>