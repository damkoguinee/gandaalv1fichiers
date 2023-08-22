<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

    	<div style="display: flex;"><?php

    		require 'navrapport.php';?>

    		<div style="display: flex; flex-wrap: wrap; overflow: auto;">

    			<div>

			    	<table class="synthesecompta" style="margin-left: 10px;">
			    		<thead>
			    			<tr>
					            <th colspan="2" class="info" style="text-align: center">
				                    <form method="POST" action="rapport.php" id="suitec" name="termc" style="display: flex;">
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
				            
			    				<th colspan="4" class="info" style="text-align: center">Statistique Générale des <?=$_SESSION['typeel'];?></th>
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

							foreach ($panier->cursus() as $valuec) {

								if (isset($_POST['anneeff'])) {
										$promo=$_POST['anneeff'];
									}else{
										$promo=$_SESSION['promo'];
									}

									$etatscol='actif';?>

								<tr>
									<td><?=ucfirst($valuec->nom);?></td>

									<td style="text-align:center;"><?=$panier->effectifTotCursus($valuec->nom,$promo);?></td><?php

									foreach ($panier->sexe as $value) {

										if (isset($_POST['anneeff'])) {
											$promo=$_POST['anneeff'];
										}else{
											$promo=$_SESSION['promo'];
										}

										$prodcursus=$DB->query("SELECT  count(inscription.id) as nbre from inscription inner join eleve on eleve.matricule=inscription.matricule where etatscol='{$etatscol}' and inscription.niveau='{$valuec->nom}' and sexe='{$value}' and annee='{$promo}'");

								

										foreach ($prodcursus as $cursus) {?>

											<td style="text-align:center"><?=ucfirst($cursus->nbre);?></td><?php
										}
									}?>

									<td style="text-align:center;"><?=number_format($panier->percentEffCursus($valuec->nom,$promo)[0],2,',',' ');?></td>

									<td style="text-align:center;"><?=number_format($panier->percentEffCursus($valuec->nom,$promo)[1],2,',',' ');?></td>
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

				<div><?php 

					if ($products['type']=='admin' or $products['type']=='fondation' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='Directeur Général' or $products['type']=='comptable') {

						$etatscol='actif';
						$abandon=$DB->query("SELECT inscription.matricule as matricule, nomel, prenomel, nomgr from inscription inner join eleve on eleve.matricule=inscription.matricule where etatscol!='{$etatscol}' and annee='{$promo}'");

						if (!empty($abandon)) {?>

							<table class="payement" style="margin-left: 10px;">
								<thead>
									<tr>
										<th colspan="7">Elèves ayant abandonné l'année-scolaire</th>
									</tr>

									<tr>
										<th>N°</th>
										<th>Matricule</th>
										<th>Prénom & Nom</th>
										<th>Classe</th>
										<th>Montant Ins</th>
										<th>Montant Scol</th>
										<th></th>
									</tr>
								</thead>

								<tbody><?php 
									$cumulins=0;
									$cumulscol=0;
									foreach ($abandon as $key => $valueab) {

										$abandonins=$DB->querys("SELECT montant from payement  where matricule='{$valueab->matricule}' and promo='{$promo}'");

										$abandonscol=$DB->querys("SELECT sum(montant) as montant from payementfraiscol where matricule='{$valueab->matricule}' and promo='{$promo}'");

										$montantins=$abandonins['montant'];
										$montantscol=$abandonscol['montant'];

										$cumulins+=$montantins;
										$cumulscol+=$montantscol; ?>

										<tr>
											<td style="text-align: center;"><?=$key+1;?></td>
											<td style="text-align: center;"><?=$valueab->matricule;?></td>
											<td><?=ucwords($valueab->prenomel).' '.strtoupper($valueab->nomel);?></td>
											<td style="text-align: center;"><?=$valueab->nomgr;?></td>
											<td style="text-align: right; padding-right: 5px;"><?=number_format($montantins,0,',',' ');?></td>
											<td style="text-align: right; padding-right: 5px;"><?=number_format($montantscol,0,',',' ');?></td>
											<td><a href="ajout_eleve.php?fiche_eleve=<?=$valueab->matricule;?>&promo=<?=$_SESSION['promo'];?>" class="btn btn-info"><input type="button" value="+infos" style="font-size: 16px;  cursor: pointer"></a></td>
											
										</tr><?php 
									}?>
								</tbody>
								<tfoot>
									<tr>
										<th colspan="4">Totaux</th>
										<th style="text-align: right; padding-right: 5px;"><?=number_format($cumulins,0,',',' ');?></th>
										<th style="text-align: right; padding-right: 5px;"><?=number_format($cumulscol,0,',',' ');?></th>

									</tr>
								</tfoot>
							</table><?php 
						}
					}?>
				</div>
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