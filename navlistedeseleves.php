<div class="col-sm-12 col-md-2 pb-3 "style="background-color: #253553;"> <?php

	if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire') {
	}

	foreach ($panier->activites($_SESSION['promo']) as $value) {?>

		<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="listedeseleves.php?listeeleve=<?=$value->id;?>"><?=ucwords(strtolower($value->nomact));?></a></div></div><?php 
	}?>
</div>