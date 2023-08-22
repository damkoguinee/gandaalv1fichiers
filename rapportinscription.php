<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

    	<div class="container-fluid">

    		<div class="row"><?php

    			require 'navrapport.php';?>

    			<div class="col-sm-12  col-md-10" style="overflow: auto;">

    				<div class="row"><?php 

		    			if (isset($_POST['anneeff'])) {
							$promo=$_POST['anneeff'];
						}else{
							$promo=$_SESSION['promo'];
						}

		    			if (!isset($_GET['remiseins'])) {?>

					    	<table class="synthesecompta" style="margin-left: 10px;">
					    		<thead>
					    			<tr>
							            <th colspan="1" class="info" style="text-align: center">
						                    <form method="POST" action="rapportinscription.php" id="suitec" name="termc" style="display: flex;">
							                    <select style="width: 250px; height: 30px; font-size: 19px;" name="choix" required="" onchange="this.form.submit()"><?php

							                        if (isset($_POST['choix'])) {?>
							                            
							                            <option value="<?=$_POST['choix'];?>" ><?=$_POST['choix'];?></option><?php

							                        }else{?>

							                            <option>Choisir....</option><?php
							                        }?>

							                        <option>Par cursus</option>
							                        <option>Par niveau</option>
							                        <option>Par classe</option>
							                    </select>
							                </form>
							            </th>
							            <th>

							                <form method="POST" action="rapportinscription.php" id="suitec" name="termc" style="display: flex;">
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
						            
					    				<th colspan="2" class="info" style="text-align: center">Frais d'ins/reins des <?=$_SESSION['typeel'];?> sans les remises</th>
								   </tr>
											    			
									<tr>
										<th></th>
										<th>Nombre</th>
										<th>Montant Payé</th>
										<th>Reste à Payer</th>
									</tr>
								</thead>

								<tbody><?php
									$reste=0;

									foreach ($panier->cursus() as $valuec) {

										if (isset($_POST['anneeff'])) {
											$promo=$_POST['anneeff'];
										}else{
											$promo=$_SESSION['promo'];
										}

										$reste+=$rapport->resteIns($valuec->nom,$promo)[0];?>

										<tr>
											<td><?=ucfirst($valuec->nom);?></td>
											<td style="text-align:center;"><?=$panier->effectifTotCursus($valuec->nom,$promo);?></td>
											<td style="text-align:right; padding-right: 5px;"><?=number_format($rapport->inscriptionTotCursus($valuec->nom,$promo)[0],0,',',' ');?></td>
											<td style="text-align:right; padding-right: 5px;"><?=number_format($rapport->resteIns($valuec->nom,$promo)[0],0,',',' ');?></td>
										</tr><?php 
									}?>

									<tr>
										<td>Complexe</td>
										<td style="text-align:center;"><?=$panier->effectifTotal($promo);?></td>
										<td style="text-align:right; padding-right: 5px;"><?=number_format($rapport->inscriptionTotal($promo),0,',',' ');?></td>
										<td style="text-align:right; padding-right: 5px;"><?=number_format($reste,0,',',' ');?></td>
									</tr>
						    	
								
								</tbody>
							</table><?php 
						}?>
					</div>


					<div class="row"><?php

						if (isset($_GET['remiseins'])) {

							$prodremise = $DB->query('SELECT payement.matricule as matricule, montant, payement.remise as remise, nomgr, nomel, prenomel FROM payement inner join inscription on inscription.matricule=payement.matricule inner join eleve on eleve.matricule=inscription.matricule WHERE payement.remise!= :mat and promo=:promo ORDER BY(prenomel) DESC', array('mat'=> 0, 'promo'=>$promo));

							if (!empty($prodremise)) {?>

						    	<table class="payement" style="margin-left: 30px;">
						    		<thead>
						    			<tr>			            
						    				<th colspan="6" class="info" style="text-align: center">Liste des <?=$_SESSION['typeel'];?> ayant obtenus une remise pour les frais d'inscriptions/reins <a style="margin-left: 10px;"href="printdoc.php?remiseins&promo=<?=$promo;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
									   </tr>
												    			
										<tr>
											<th></th>
											<th>Matricule</th>
											<th>Prénom & Nom</th>
											<th>Classe</th>
											<th>Remise</th>
											<th>Montant Payé</th>
										</tr>
									</thead>

									<tbody><?php

										$totremise=0; 

										foreach ($prodremise as $key => $valuer) {

											$totremise+=$valuer->montant;?>

											<tr>
												<td style="text-align:center;"><?=$key+1;?></td>
												<td style="text-align:center;"><?=$valuer->matricule;?></td>
												<td><?=ucfirst($valuer->prenomel).' '.ucwords($valuer->nomel);?></td>
												<td style="text-align:center;"><?=$valuer->nomgr;?></td>
												<td style="text-align:center;"><?=$valuer->remise;?>%</td>
												<td style="text-align:right; padding-right: 5px;"><?=number_format($valuer->montant,0,',',' ');?></td>
											</tr><?php
										}?>

									</tbody>

									<tfoot>
										<tr>
											<th colspan="5">Total</th>
											<th style="text-align:right; padding-right: 5px;"><?=number_format($totremise,0,',',' ');?></th>
										</tr>
									</tfoot>
								</table><?php 
							}
						}?>
					</div>
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