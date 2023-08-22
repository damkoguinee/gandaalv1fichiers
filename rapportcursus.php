<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

    	<div style="display: flex;"><?php

    		require 'navrapport.php';?>

    		<div style="overflow: auto;">

		    	<table class="payement" style="margin-left: 10px;">
		    		<thead>
		    			<tr>
				            <th colspan="2" class="info" style="text-align: center">
			                    <form method="POST" action="rapportcursus.php" id="suitec" name="termc" style="display: flex;">
				                    <select style="width: 250px; height: 30px; font-size: 19px;" name="anneeff" required="" onchange="this.form.submit()"><?php

				                        if (isset($_POST['anneeff'])) {?>
				                            
				                            <option value="<?=$_POST['anneeff'];?>" ><?=$_POST['anneeff'];?></option><?php

				                        }else{?>

				                            <option><?=$_SESSION['promo'];?></option><?php
				                        }

				                        $anneef=$_SESSION['promo']+1;

				                        for($i=2021;$i<=$anneef ;$i++){?>

				                          <option value="<?=$i;?>"><?=$i;?></option><?php

				                        }?>
				                    </select>
				                </form>
			            	</th>
			            
		    				<th colspan="4" class="info" style="text-align: center">Statistique par cursus des <?=$_SESSION['typeel'];?></th>
					   </tr>
								    			
						<tr>
							<th></th>
							<th>Total</th>
							<th>Garçons</th>
							<th>Filles</th>
							<th>% G</th>
							<th>% F</th>
						</tr>
					</thead>

					<tbody><?php

						foreach ($panier->formation() as $valuec) {

							if (isset($_POST['anneeff'])) {
									$promo=$_POST['anneeff'];
								}else{
									$promo=$_SESSION['promo'];
								}?>

							<tr><?php 

								if ($valuec->classe=='1') {

									$classe=ucwords($valuec->classe.'ere ');

								}elseif($valuec->classe=='toute petite section' or $valuec->classe=='petite section' or $valuec->classe=='moyenne section' or $valuec->classe=='grande section' or $valuec->classe=='terminale'){

									$classe=ucwords($valuec->classe);

								}else{

									$classe=ucwords($valuec->classe.'ème ');?><?php
								}

								if ($valuec->niveau=='lycee') {
									
									$nomf=$valuec->nomf;

								}elseif($valuec->niveau=='maternelle'){

									$nomf='';

								}else{
									$nomf=' Année';
								}
								$etatscol='actif';?>

								<td><?=$classe.' '.ucfirst($nomf);?></td>

								<td style="text-align:center;"><?=$panier->effectifTotForm($valuec->codef,$promo);?></td><?php

								foreach ($panier->sexe as $value) {

									if (isset($_POST['anneeff'])) {
										$promo=$_POST['anneeff'];
									}else{
										$promo=$_SESSION['promo'];
									}

									$prodcursus=$DB->query("SELECT  count(inscription.id) as nbre from inscription inner join eleve on eleve.matricule=inscription.matricule where etatscol='{$etatscol}' and inscription.codef='{$valuec->codef}' and sexe='{$value}' and annee='{$promo}'");

							

									foreach ($prodcursus as $cursus) {?>

										<td style="text-align:center"><?=ucfirst($cursus->nbre);?></td><?php
									}
								}?>

								<td style="text-align:center;"><?=number_format($panier->percentEffForm($valuec->codef,$promo)[0],2,',',' ');?></td>

								<td style="text-align:center;"><?=number_format($panier->percentEffForm($valuec->codef,$promo)[1],2,',',' ');?></td>
							</tr><?php 
						}?>

						<tr>
							<td>Complexe</td>
							<td style="text-align:center;"><?=$panier->effectifTotal($promo);?></td>
							<?php

							$efft=0;

							foreach ($panier->sexe as $value) {

								if (isset($_POST['anneeff'])) {
									$promo=$_POST['anneeff'];
								}else{
									$promo=$_SESSION['promo'];
								}

								$prodeff=$DB->query("SELECT count(inscription.id) as nbre from inscription inner join eleve on eleve.matricule=inscription.matricule where etatscol='{$etatscol}' and sexe='{$value}' and annee='{$promo}'");

								foreach ($prodeff as $effectif) {

									$efft+=$effectif->nbre;?>

									<td style="text-align:center;"><?=$effectif->nbre;?></td><?php
								}


							}?>
							<td style="text-align:center;"><?=number_format($panier->percentEff($promo)[0],2,',',' ');?></td>
							<td style="text-align:center;"><?=number_format($panier->percentEff($promo)[1],2,',',' ');?></td>
						</tr>
			    	
					
					</tbody>
				</table>
			</div>
		</div><?php
	}
}?>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Valider la suppression'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation'));
    }

    function focus(){
        document.getElementById('pointeur').focus();
    }

</script>