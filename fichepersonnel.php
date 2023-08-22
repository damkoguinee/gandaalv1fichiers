<div class="mb-1"><label class="form-label">N° Mat</label> <?=$numeen;?></div>
<div class="mb-1"><label class="form-label">Nom</label><?=strtoupper($products['nomen']);?><input type="hidden" name="nom" value="<?= strtoupper($products['nomen']).' '.ucwords($products['nomen']);?>" /></div>
<div class="mb-1"><label class="form-label">Prénom</label><?= ucwords($products['prenomen']);?><input type="hidden" name="prenom" value="<?= strtoupper($products['prenomen']).' '.ucwords($products['prenomen']);?>" /></div>
<div class="mb-1"><label class="form-label">Téléphone</label> <?=' '.$products['phone'];?></div>
            
        