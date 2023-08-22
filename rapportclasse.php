<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{

    	if (isset($_POST['nivcursus'])) {
    		$_SESSION['nivcursus']=$_POST['nivcursus'];
    	}else{
    		$_SESSION['nivcursus']='selectionnez une classe';
    	}?>

    	<div style="display: flex;"><?php

    		require 'navrapport.php';?>

    		<div style="overflow: auto;">
    			<table class="payement" style="margin-left: 10px;">
		    		<thead>
		    			<tr>
				            <th colspan="2" class="info" style="text-align: center">

				            	<div>Statistique par classe des <?=$_SESSION['typeel'];?></div>

				            	<div  style="display: flex;">

				            		<div>
					                    <form method="POST" action="rapportclasse.php" id="suitec" name="termc">
						                    <select style="width: 250px; height: 30px; font-size: 19px;" name="anneeff" required="" onchange="this.form.submit()"><?php

						                        if (isset($_POST['anneeff'])) {?>
						                            
						                            <option value="<?=$_POST['anneeff'];?>" ><?=$_POST['anneeff'];?></option><?php

						                        }else{?>

						                            <option value="<?=$_SESSION['promo'];?>"><?=$_SESSION['promo'];?></option><?php
						                        }

						                        $anneef=$_SESSION['promo']+1;

						                        for($i=2021;$i<=$anneef ;$i++){?>

						                          <option value="<?=$i;?>"><?=$i;?></option><?php

						                        }?>
						                    </select>
						                </form>
						            </div>

						            <div>

						                <form method="POST" action="rapportclasse.php" id="suitec" name="termc">
						                    <select style="width: 250px; height: 30px; font-size: 19px;" name="nivcursus" required="" onchange="this.form.submit()"><?php

						                        if (isset($_POST['classe'])) {?>
						                            
						                            <option value="<?=$_POST['nivcursus'];?>" ><?=$_POST['nivcursus'];?></option><?php

						                        }else{?>

						                            <option><?=$_SESSION['nivcursus'];?></option><?php
						                        }

						                        if (!empty($_SESSION['niveauf'])) {

										    		$prodf=$DB->query('SELECT *from formation where niveau=:niv', array('niv'=>$_SESSION['niveauf']));

										    	}else{

										    		$prodf=$DB->query('SELECT *from formation');
										    	}

						                        foreach ($prodf as $valuef) {?>

						                          <option value="<?=$valuef->codef;?>"><?=$valuef->classe.' '.$valuef->nomf;?></option><?php

						                        }?>
						                    </select>
						                </form>
						            </div>
						        </div>

				            </th>
					   </tr>
					</thead>

					<tbody>
						<tr>
							<td><?php

								foreach ($panier->formation() as $valuec) {

									if (isset($_POST['anneeff'])) {
										$promo=$_POST['anneeff'];
									}else{
										$promo=$_SESSION['promo'];
									}

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
									<table  class="payement">
										<thead>
											<tr>
												<th width="60%"><?=$classe.' '.ucfirst($nomf);?></th>
												<th>Total</th>
												<th>Garçons</th>
												<th>Filles</th>
												<th>% G</th>
												<th>% F</th>
											</tr>
										</thead>

										<tbody><?php
											$etatscol='actif';

											foreach ($panier->classeStat($valuec->codef, $promo) as $valuecl) {?>
												<tr>
													<td><a target="_blank" href="printdoc.php?classenouv=<?=$valuecl->nomgr;?>"><?=$valuecl->nomgr;?></a></td>

													<td style="text-align:center;"><?=$panier->effectifTotClass($valuecl->codef, $valuecl->nomgr,$promo);?></td><?php


													foreach ($panier->sexe as $value) {

														if (isset($_POST['anneeff'])) {
															$promo=$_POST['anneeff'];
														}else{
															$promo=$_SESSION['promo'];
														}

														$prodcursus=$DB->query("SELECT  count(inscription.id) as nbre from inscription inner join eleve on eleve.matricule=inscription.matricule where etatscol='{$etatscol}' and inscription.codef='{$valuecl->codef}' and nomgr='{$valuecl->nomgr}' and sexe='{$value}' and annee='{$promo}'");

							

														foreach ($prodcursus as $cursus) {?>

															<td style="text-align:center"><?=ucfirst($cursus->nbre);?></td><?php
														}
													}?>

													<td style="text-align:center;"><?=number_format($panier->percentEffClass($valuecl->codef,$valuecl->nomgr,$promo)[0],2,',',' ');?></td>

													<td style="text-align:center;"><?=number_format($panier->percentEffClass($valuecl->codef,$valuecl->nomgr,$promo)[1],2,',',' ');?></td>
												</tr><?php
											}?>
										</tbody>
										<tfoot>
											<tr>
												<th>Total</th>
												<th style="text-align:center;"><?=$panier->effectifTotForm($valuec->codef,$promo);?></th><?php

												$efft=0;

												foreach ($panier->sexe as $value) {

													if (isset($_POST['anneeff'])) {
														$promo=$_POST['anneeff'];
													}else{
														$promo=$_SESSION['promo'];
													}

													$prodeff=$DB->query("SELECT count(inscription.id) as nbre from inscription inner join eleve on eleve.matricule=inscription.matricule where etatscol='{$etatscol}' and codef='{$valuec->codef}' and sexe='{$value}' and annee='{$promo}'");

													foreach ($prodeff as $effectif) {

														$efft+=$effectif->nbre;?>

														<th style="text-align:center;"><?=$effectif->nbre;?></th><?php
													}


												}?>
												<th style="text-align:center;"><?=number_format($panier->percentEffForm($valuec->codef,$promo)[0],2,',',' ');?></th>
												<th style="text-align:center;"><?=number_format($panier->percentEffForm($valuec->codef,$promo)[1],2,',',' ');?></th>
											</tr>
										</tfoot>
									</table><?php 
								}?>
							</td>
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