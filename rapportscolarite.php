<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{

    	if (isset($_POST['anneeff'])) {
			$promo=$_POST['anneeff'];
		}else{
			$promo=$_SESSION['promo'];
		}?>

    	<div style="display: flex; flex-wrap: wrap; overflow: auto;"><?php

    		//require 'navrapport.php';?>

    		<div><?php 

    			if (!isset($_GET['remisescol'])) {?>

			    	<table class="payement" style="margin-left: 10px;">
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
					            <th colspan="2">

					                <form method="POST" action="rapportscolarite.php" id="suitec" name="termc" style="display: flex;">
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
				            	</th><?php 
				            	if (array_sum(($panier->trancheRapport($promo)))>2) {
				            		$colspan=7;
				            	}else{
				            		$colspan=6;
				            	}?>
				            
			    				<th colspan="<?=$colspan-1;?>" class="info" style="text-align: center">Frais Scolarité des <?=$_SESSION['typeel'];?> sans les remises</th>
						   </tr>
									    			
							<tr>
								<th></th>
								<th>Nbre</th><?php
								foreach ($panier->trancheRapport($promo) as $tranche) {?>
									<th>
										<table class="payement">
											<tr>
												<th colspan="2"><?=$tranche->nom;?></th>
											</tr>

											<tr>
												<th width="150" style="background-color: green;">Payé</th>
												<th width="150" style="background-color: red;">Reste</th>
											</tr>
										
										</table></th><?php 
								}?>
								<th>Reste Annuel</th>
							</tr>
						</thead>

						<tbody><?php

							$restet=0;
							$restetranche1=0;
							$restetranche2=0;
							$restetranche3=0;

							foreach ($panier->formation() as $valuec) {

								$tranche1='1ere tranche';
								$tranche2='2eme tranche';
								$tranche3='3eme tranche';

								$restetranche1+=$rapport->totRestScol($valuec->codef, $tranche1, $promo);
								$restetranche2+=$rapport->totRestScol($valuec->codef, $tranche2, $promo);
								$restetranche3+=$rapport->totRestScol($valuec->codef, $tranche3, $promo);

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
								}?>

								<tr>
									<td><?=$classe.' '.ucfirst($nomf);?></td>

									<td style="text-align:center;"><?=$panier->effectifTotForm($valuec->codef,$promo);?></td><?php

									$reste=0;
									foreach ($panier->trancheRapport($promo) as $tranche) {

										$reste+=$rapport->totRestTranche($valuec->codef, $tranche->nom, $promo);

										$restet+=$rapport->totRestTranche($valuec->codef, $tranche->nom, $promo);

										if ($rapport->totRestTranche($valuec->codef, $tranche->nom, $promo)==0) {
											$color='green';
										}elseif($rapport->totRestTranche($valuec->codef, $tranche->nom, $promo)<0.45*($rapport->totFraiscolPayeTranche($valuec->codef, $tranche->nom, $promo))){

											$color='orange';

										}else{

											$color='red';
										}?>

										<td>
											<table class="payement" style="margin: auto;">
												<tr>
													<td width="130" style="text-align:center; background-color:green; color: white; font-size: 20px; font-weight: bold;"><?=number_format($rapport->totFraiscolPayeTranche($valuec->codef, $tranche->nom, $promo),0,',',' ');?></td>

													<td width="130" style="text-align:center; background-color:<?=$color;?>; color: white; font-size: 20px; font-weight: bold;"><?=number_format($rapport->totRestTranche($valuec->codef, $tranche->nom, $promo),0,',',' ');?></td>
												</tr>
											</table>
										</td>

										<?php 
									}?>
									<td style="text-align:right; color:red; font-size: 22px; font-weight: bold;"><?=number_format($reste,0,',',' ');?></td>
								</tr><?php 
							}?>

							<tr>
								<td>Complexe</td>
								<td style="text-align:center;"><?=$panier->effectifTotal($promo);?></td><?php
								$prevt=0;
								foreach ($panier->trancheRapport($promo) as $tranche) {
										if ($tranche->nom=='1ere tranche'){
									  		$restetranche=$restetranche1;
									  	}elseif($tranche->nom=='2eme tranche'){
									  		$restetranche=$restetranche2;
									  	}else{
									  		$restetranche=$restetranche3;
									  	}

									  	$prev=$rapport->totFraiscolPaye($tranche->nom, $promo)+$restetranche;

									  	$prevt+=$prev; ?>

									<td>
										<table>
											<tr>
												<td width="130" style="text-align:center; background-color:green; color: white; font-size: 20px; font-weight: bold;"><?=number_format($rapport->totFraiscolPaye($tranche->nom, $promo),0,',',' ');?></td>

												<td width="130" style="text-align:center; background-color:red; color: white; font-size: 20px; font-weight: bold;"><?=number_format($restetranche,0,',',' ');?></td>
											</tr>

											<tr>
												<td colspan="2" style="text-align:center; background-color:orange; color: white; font-size: 20px; font-weight: bold;">Total Prev:<?=number_format($prev,0,',',' ');?></td>
											</tr>
										</table>
									</td><?php 
								}?>

								<td>
									<table>
										<tr>
											<td style="text-align:right; color:red; font-size: 22px; font-weight: bold;"><?=number_format($restet,0,',',' ');?></td>										
										</tr>

										<tr>
											<td colspan="2" style="text-align:center; background-color:orange; color: white; font-size: 20px; font-weight: bold;">Budget:<?=number_format($prevt,0,',',' ');?></td>
										</tr>
									</table>
							</tr>
				    	
						
						</tbody>
					</table><?php 
				}?>
			</div>


			<div>

				<div style="display:flex;">

					<div><?php 

						$prodremise = $DB->query('SELECT inscription.matricule as matricule, remise as remise, nomgr, nomel, prenomel FROM inscription  inner join eleve on eleve.matricule=inscription.matricule WHERE remise> :mat and annee=:promo ORDER BY(nomgr) DESC', array('mat'=> 99, 'promo'=>$promo));

						if (!empty($prodremise)) {?>

					    	<table class="payement" style="margin-left: 5px;">
					    		<thead>
					    			<tr>			            
					    				<th colspan="5" class="info" style="text-align: center">Liste des <?=$_SESSION['typeel'];?> ayant obtenus une remise pour les frais de scolarité <a style="margin-left: 10px;"href="printdoc.php?remisescol&promo=<?=$promo;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
								   </tr>
											    			
									<tr>
										<th></th>
										<th>Matricule</th>
										<th>Prénom & Nom</th>
										<th>Classe</th>
										<th>Remise</th>
									</tr>
								</thead>

								<tbody><?php

									$totremise=0; 

									foreach ($prodremise as $key => $valuer) {

										//$totremise+=$valuer->montant;?>

										<tr>
											<td style="text-align:center;"><?=$key+1;?></td>
											<td style="text-align:center;"><?=$valuer->matricule;?></td>
											<td><?=ucfirst($valuer->prenomel).' '.ucwords($valuer->nomel);?></td>
											<td style="text-align:center;"><?=$valuer->nomgr;?></td>
											<td style="text-align:center;"><?=$valuer->remise;?>%</td>
										</tr><?php
									}?>

								</tbody>
							</table><?php 
						}?>

					</div>

					<div><?php

						$prodremise = $DB->query('SELECT inscription.matricule as matricule, remise as remise, nomgr, nomel, prenomel FROM inscription  inner join eleve on eleve.matricule=inscription.matricule WHERE remise<= :mat and remise!=:mat1 and annee=:promo ORDER BY(prenomel) DESC', array('mat'=> 99, 'mat1'=>0, 'promo'=>$promo));

						if (!empty($prodremise)) {?>

					    	<table class="payement" style="margin-left: 20px;">
					    		<thead>
					    			<tr>			            
					    				<th colspan="5" class="info" style="text-align: center">Liste des <?=$_SESSION['typeel'];?> ayant obtenus une remise pour les frais de scolarité <a style="margin-left: 10px;"href="printdoc.php?remisescol&promo=<?=$promo;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
								   </tr>
											    			
									<tr>
										<th></th>
										<th>Matricule</th>
										<th>Prénom & Nom</th>
										<th>Classe</th>
										<th>Remise</th>
									</tr>
								</thead>

								<tbody><?php

									$totremise=0; 

									foreach ($prodremise as $key => $valuer) {

										//$totremise+=$valuer->montant;?>

										<tr>
											<td style="text-align:center;"><?=$key+1;?></td>
											<td style="text-align:center;"><?=$valuer->matricule;?></td>
											<td><?=ucfirst($valuer->prenomel).' '.ucwords($valuer->nomel);?></td>
											<td style="text-align:center;"><?=$valuer->nomgr;?></td>
											<td style="text-align:center;"><?=$valuer->remise;?>%</td>
										</tr><?php
									}?>

								</tbody>
							</table><?php 
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