<div style="display: flex;"><?php

	if (isset($_POST['numel']) or isset($_GET['eleve']) or isset($_GET['delepaye']) or isset($_GET['annulins']) or isset($_GET['delins'])) {

		$classe='Classe: '.$products['nomgr'];

	}else{

		$classe='';
	}?>

    <div class="fiche">

        <ol class="px-0">

            <li class="mx-0 px-0">
                <label>Nom </label><?=ucwords($products['prenomel']).' '.strtoupper($products['nomel']);?><?=' matricule N° '.$numel;?><br/><?=' Né(e) le '.$products['naissance'];?><?=' Tel '.$products['phone'].' ';?><?=$classe;?>
            </li>
        </ol>
    </div>
    <div style=" margin-left: 3px;"><img src="img/<?=$numel;?>.jpg" alt="" width="80" height="80"></div>
</div>