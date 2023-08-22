<?php

if (isset($_GET['enseignant'])) {
	require 'headerenseignant.php';
}else{
	require 'headerv3.php';
}

if (isset($_SESSION['pseudo'])) {
	if (isset($_GET['enseignant'])) {
		require 'fiche_eleve.php';
	}else{
    
	    if ($products['niveau']<4) {?>

	        <div class="alert alert-danger">Des autorisations sont requises pour consulter cette page</div><?php

	    }else{?>

	    	<div class="container-fluid">

	    		<div class="row"><?php 
	    			require 'navactivites.php';?>

					<div class="col-sm-12 col-md-10"><?php 

						if (isset($_POST['valid'])) {
							 	
						 	if (!empty($_POST['ideleve']) and !empty($_POST['activites']) and !empty($_POST['typep']) and !empty($_POST['compte'])) {

						 		$montantd=$panier->h($panier->espace($_POST['montant']));
						 		$montantf=$panier->h($panier->espace($_POST['montantf']));
						 		$remise=$panier->h($_POST['remise']);
						 		$devise=$panier->h($_POST['devise']);
						 		$taux=$panier->h($_POST['taux']);
						 		$montant=$montantd*$taux;			 		
						 		$ideleve=$panier->h($_POST['ideleve']);
						 		$elevetype=$panier->h($_POST['elevetype']);
						 		$idact=$panier->h($_POST['activites']);
						 		$mois=$_POST['mois'];
						 		$typep=$panier->h($_POST['typep']);
						 		$numcheque=$panier->h($_POST['bord']);
						 		$banquecheque=$panier->h($_POST['banque']);
						 		$compte=$panier->h($_POST['compte']);
						 		$annee=$panier->h($_POST['annee']);

						 		$nb=$DB->querys("SELECT max(id) as id FROM activitespaiement ");

						 		$numeropaieact=$nb['id']+1;

						 		foreach ($mois as $valuem) {

						 			if ($valuem=='annuel') {

						 				$prodpaie=$DB->querys("SELECT id, montantp FROM activitespaiement WHERE matp='{$ideleve}' and idact='{$idact}' and anneep='{$annee}' ");

										$payer=$prodpaie['montantp']+$montant;

										if (!empty($prodpaie['id'])) {?>

									 		<div class="alert alert-warning" role="alert">Un paiement est déjà enregistré</div><?php
									 			
									 	}else{

									 		foreach ($panier->moisenlettre as $valuea) {


							 					$DB->insert('INSERT INTO activitespaiement (numeropaie, matp, elevetype, idact, moisp, anneep, montantf, montantp, dateop) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($numeropaieact, $ideleve, $elevetype, $idact, $valuea, $annee, $montantf, $montant));
							 				

								 				$prodid=$DB->querys("SELECT max(id) as id FROM activitespaiehistorique ");

							 					$numeropaie="act".($prodid['id']+1);
								 		

								 				$DB->insert('INSERT INTO activitespaiehistorique (numeropaie, matp, elevetype, idact, moisp, anneep, montantf, montantp, remise, devise, taux, personnel, modep, caisse, numcheque, banquecheque, dateop) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($numeropaie, $ideleve, $elevetype, $idact, $valuea, $annee, $montantf, $montant, $remise, $devise, $taux, $_SESSION['idpseudo'], $typep, $compte, $numcheque, $banquecheque));

						 					

								 				$DB->insert('INSERT INTO banque (id_banque, montant, devise, taux, personnel, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $montant, $devise, $taux, $_SESSION['idpseudo'], 'paiement activites', $numeropaie, $ideleve, $annee));

								 				$prodverif=$DB->querys("SELECT id FROM inscriptactivites where idact='{$idact}' and matinscrit='{$ideleve}' and promoact='{$annee}'");

								 				if (empty($prodverif['id'])) {

								 					$DB->insert('INSERT INTO inscriptactivites (idact, matinscrit, mensualite, promoact,  dateop) VALUES(?, ?, ?, ?, now())', array($idact, $ideleve, $montantf, $annee));
								 				}

								 			}

								 			if (!empty($remise)) {

									 		 	$DB->insert("UPDATE inscriptactivites SET remiseact='{$remise}' where matinscrit='{$ideleve}' and idact='{$idact}' and promoact='{$annee}'");
									 		} ?>

							 				<div class="alert alert-success" role="alert">Paiement effectué avec succèe!!!</div><?php

								 			unset($_SESSION['typel']);
								 			unset($_SESSION['searchmat']);
							 			}
						 				
						 			}else{

							 			$prodpaie=$DB->querys("SELECT id, montantp FROM activitespaiement WHERE matp='{$ideleve}' and idact='{$idact}' and moisp='{$valuem}' and anneep='{$annee}' ");

										$payer=$prodpaie['montantp']+$montant;

										if ($payer>$montantf) {?>

									 		<div class="alert alert-warning" role="alert">Montant payé est supérieur au montant restant du mois</div><?php
									 			
									 	}else{

									 		if ($payer==$montantf) {
								 				$etat='clos';
								 			}else{
								 				$etat='encours';
								 			}

								 			if (empty($prodpaie['id'])){

							 					$DB->insert('INSERT INTO activitespaiement (numeropaie, matp, elevetype, idact, moisp, anneep, montantf, montantp, dateop) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($numeropaieact, $ideleve, $elevetype, $idact, $valuem, $annee, $montantf, $montant));
							 				}else{

							 					$DB->insert("UPDATE activitespaiement SET montantp='{$payer}' where matp='{$ideleve}' and moisp='{$valuem}' and anneep='{$annee}'");
							 				}

							 				$prodid=$DB->querys("SELECT max(id) as id FROM activitespaiehistorique ");

						 					$numeropaie="act".($prodid['id']+1);
							 		

							 				$DB->insert('INSERT INTO activitespaiehistorique (numeropaie, matp, elevetype, idact, moisp, anneep, montantf, montantp, remise, devise, taux, personnel, modep, caisse, numcheque, banquecheque, dateop) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($numeropaie, $ideleve, $elevetype, $idact, $valuem, $annee, $montantf, $montant, $remise, $devise, $taux, $_SESSION['idpseudo'], $typep, $compte, $numcheque, $banquecheque));

						 					

							 				$DB->insert('INSERT INTO banque (id_banque, montant, devise, taux, personnel, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $montant, $devise, $taux, $_SESSION['idpseudo'], 'paiement activites', $numeropaie, $ideleve, $annee));

							 				$prodverif=$DB->querys("SELECT id FROM inscriptactivites where idact='{$idact}' and matinscrit='{$ideleve}' and promoact='{$annee}'");

							 				if (empty($prodverif['id'])) {

							 					$DB->insert('INSERT INTO inscriptactivites (idact, matinscrit, mensualite, promoact,  dateop) VALUES(?, ?, ?, ?, now())', array($idact, $ideleve, $montantf, $annee));
							 				}

							 				if (!empty($remise)) {

									 		 	$DB->insert("UPDATE inscriptactivites SET remiseact='{$remise}' where matinscrit='{$ideleve}' and idact='{$idact}' and promoact='{$annee}'");
									 		} ?>

							 				<div class="alert alert-success" role="alert">Paiement effectué avec succèe!!!</div><?php

							 				unset($_SESSION['typel']);
							 				unset($_SESSION['searchmat']);
							 			}
							 		}
						 		}		

						 	}
						}
						if (isset($_GET['ideleve'])) {
							$_SESSION['searchmat']=$_GET['ideleve'];
						}

						if (isset($_POST['activites'])) {
							$prodins=$DB->querys("SELECT * FROM inscriptactivites where idact='{$_POST['activites']}' and matinscrit='{$_SESSION['searchmat']}' and promoact='{$_SESSION['promo']}'");
							$remise=$prodins['remiseact'];
						}

						if (isset($_POST['valid'])) {
				            $_SESSION['bordereau']=$_POST['bord'];
				            $_SESSION['banque']=$_POST['banque'];
				            $_SESSION['mpaiement']=$_POST['typep'];
				            $_SESSION['typer']='';
				        }

				        if (isset($_POST['typel'])){

							$_SESSION['typel']=$_POST['typel'];

						}else{
							if (empty($_SESSION['typel'])) {
								$_SESSION['typel']='interne';
							}

						}

						if (isset($_POST['validact'])) {
							$nomAct=$panier->h($_POST['nomAct']);
							$mensualite=$panier->h($panier->espace($_POST['montantact']));
							$prodverif=$DB->querys("SELECT id FROM activites where nomact='{$nomAct}' ");
							if (empty($prodverif['id'])) {								
								$DB->insert("INSERT INTO activites (nomact, mensualite, promoact) VALUES (?, ?, ?)", array($nomAct, $mensualite, $_SESSION['promo']));?>
								<div class="alert alert-success">Activité ajoutée avec succèes!!</div><?php
							}else{?>
								<div class="alert alert-warning">cette activité est déja disponible!!!</div><?php
							}
						}
						if (isset($_GET['newAct'])) {?>
							<form method="POST" action="activitesgestion.php?ideleve=<?=$_SESSION['searchmat'];?>">

								<fieldset><legend>Ajouter une nouvelle activité</legend>

									<div class="mb-1">
										<label class="form-label">Nom de l'activité*</label>
										<input class="form-control" type="text" name="nomAct" required="">													
									</div>

									<div class="mb-1">
										<label class="form-label">Mensualité*</label>
										<input class="form-control" type="text" name="montantact" min="0" required="">													
									</div>

									<button type="submit" name="validact" class="btn btn-primary">Valider</button>
								</fieldset>
							</form><?php
							
						} else {?>

							<form method="POST" action="activitesgestion.php?ideleve=<?=$_SESSION['searchmat'];?>">

								<fieldset><legend>Paiement activités</legend>
									<div class="container">

										<div class="row">

											<div class="col-sm-12 col-md-6">

												<div class="mb-1">
													<label class="form-label">Type élève</label>
													<select class="form-select" name="typel" onchange="this.form.submit()" ><?php 
														if (isset($_POST['typel']) or isset($_POST['activites']) or isset($_GET['ideleve'])) {

															if (isset($_POST['typel'])){

																$_SESSION['typel']=$_POST['typel'];?>

																<option value="<?=$_POST['typel'];?>"><?=$_POST['typel'];?></option><?php
															}elseif (isset($_GET['ideleve'])){?>

																<option value="<?=$_SESSION['typel'];?>"><?=$_SESSION['typel'];?></option><?php
															}
														}else{?>
															<option></option><?php
															$_SESSION['typel']=1; 
														}?>

														<option value="interne">Interne</option>
														<option value="externe">Externe</option>
														
													</select>
												</div>

												<div class="mb-1">
													<label class="form-label">Selectionnez l'élève*</label><?php 

													if (!empty($_SESSION['searchmat'])) {?>
														<input class="form-control fs-5" id="search-user" type="text" placeholder="<?=$panier->infosEleve($_SESSION['searchmat'])[0].' matricule '.$_SESSION['searchmat'];?>" /><?php
													}else{?>

														<input class="form-control" id="search-user" type="text" placeholder="rechercher un éleve" /><?php 
													}?>	                          					

													<div class="bg-info" id="result-search"></div>

													<input type="hidden" name="ideleve" value="<?=$_SESSION['searchmat'];?>">

													<input type="hidden" name="elevetype" value="<?=$_SESSION['typel'];?>">
															
												</div>

												<div class="row mb-1">

													<label class="form-label">Activites</label>
													<div class="col-sm-12 col-md-8">
														<select class="form-select" name="activites" onchange="this.form.submit()" ><?php 
															if (isset($_POST['activites'])) {
																$_SESSION['activites']=$_POST['activites'];?>
																<option value="<?=$_POST['activites'];?>"><?=$panier->nomActivites($_POST['activites'])[0].' Mensualité: '.number_format($panier->nomActivites($_POST['activites'])[1],0,',',' ').' remise '.$remise.'%';?></option><?php
															}else{?>
																<option></option><?php
																$_SESSION['activites']=1; 
															}

															foreach ($panier->activites($_SESSION['promo']) as $value) {?>
																<option value="<?=$value->id;?>"><?=ucfirst($value->nomact);?> Mensualité: <?=number_format($value->mensualite,0,',',' ');?></option><?php 
															}?>
															
														</select>
													</div>
													<div class="col-sm-12 col-md-4">
														<a href="activitesgestion.php?newAct" class="btn btn-warning">Ajouter activité</a>
													</div>

												</div>

												<div class="col">
												<label class="form-label">Tarif Mensuel à Payer*</label><?php 
													if (isset($_POST['activites'])) {?>
														<input class="form-control" type="text" name="montant" value="<?=number_format($panier->nomActivites($_POST['activites'])[1]*(1-($remise/100)),0,',',' ');?>" required="">

														<input type="hidden" name="montantf" value="<?=$panier->nomActivites($_POST['activites'])[1];?>"><?php 
													}else{?>
														<input class="form-control" type="text" name="montant" required=""><?php 

													}?>	
												</div>

												<div class="mb-1">
													<label class="form-label">Selectionnez la Période*</label>
													<select class="form-select" name="mois[]"multiple required="">
														<option class="text-danger fw-bold" value="annuel">Annuel</option><?php 
													
														foreach ($panier->moisenlettre as $value) {

															$prodmois=$DB->querys("SELECT id, sum(montantf-montantp) as reste, moisp FROM activitespaiement where idact='{$_SESSION['activites']}' and matp='{$_SESSION['searchmat']}' and moisp='{$value}' and anneep='{$_SESSION['promo']}' ");

															if ($prodmois['reste']==0 and $prodmois['moisp']==$value) {

															}else{?>

																<option value="<?=$value;?>"><?=ucfirst($value);?></option><?php 
															}
														}?>
													</select>
												</div>

												<div class="mb-1"><label class="form-label">Dévise</label>
													<select class="form-select" name="devise" required="">
														<option value="gnf">GNF</option>
														<option value="us">$</option>
														<option value="eu">€</option>
														<option value="cfa">CFA</option>
													</select>
												</div>

												
											</div>

											<div class="col-sm-12 col-md-6">
												<div class="mb-1"><label class="form-label">Taux</label><input class="form-control" type="text" name="taux" value="1"></div><?php 

												if (!empty($prodins['id'])) {?>

													<input class="form-control" type="hidden" name="remise" value="<?=$remise;?>"  required=""/><?php 

												}else{?>

													<div class="mb-1"><label class="form-label">Remise Annuelle</label><input class="form-control" type="text" name="remise" value="0"  required=""/></div><?php 

												}?>

												<div class="mb-1"><label class="form-label">Type de Paiement</label>
													<select class="form-select" name="typep" required="" ><?php 

														if (empty($_SESSION['mpaiement'])) {?>

															<option></option><?php

														}else{?>
															<option value="<?=$_SESSION['mpaiement'];?>"><?=$_SESSION['mpaiement'];?></option><?php
														} 
														foreach ($panier->modep as $value) {?>
															<option value="<?=$value;?>"><?=$value;?></option><?php 
														}?>
													</select>
												</div>

												<div class="mb-1"><label class="form-label">N°Chèque/Bordereau</label><?php 

													if (empty($_SESSION['mpaiement'])) {?>

														<input class="form-control" type="text" name="bord"><?php

													}else{?>
														<input class="form-control" type="text" name="bord" value="<?=$_SESSION['bordereau'];?>"><?php
													}?>
												</div>

												<div class="mb-1"><label class="form-label">Banque</label><?php 

													if (empty($_SESSION['mpaiement'])) {?>

														<input class="form-control" type="text" name="banque"><?php

													}else{?>
														<input class="form-control" type="text" name="banque" value="<?=$_SESSION['banque'];?>"><?php
													}?>
												</div>

												<div class="mb-1"><label class="form-label">Compte depôt*</label>
													<select class="form-control"  name="compte" required=""><?php
														$type='Banque';

														foreach($panier->nomBanque() as $product){?>

															<option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
														}?>
													</select>
												</div>

												<div class="mb-1"><label class="form-label">Année-Scolaire</label>

													<select class="form-select" type="text" name="annee" required="">
														<option value="<?=$_SESSION['promo'];?>"><?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></option><?php
													
														$annee=date("Y")+1;

														for($i=2020;$i<=$annee ;$i++){
															$j=$i+1;?>

															<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

														}?>
													</select>
												</div>
											</div>
										</div>
									</div>

									<button type="submit" name="valid" class="btn btn-primary">Valider</button>
								</fieldset>
							</form><?php 
						}?>

					</div>
				</div>
			</div> <?php
		}
	}
}?>

<?php require 'footer.php';?>

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



<script>
    $(document).ready(function(){
        $('#search-user').keyup(function(){
            $('#result-search').html("");

            var utilisateur = $(this).val();

            if (utilisateur!='') {
                $.ajax({
                    type: 'GET',
                    url: 'searcheleve.php?elevesearch',
                    data: 'user=' + encodeURIComponent(utilisateur),
                    success: function(data){
                        if(data != ""){
                          $('#result-search').append(data);
                        }else{
                          document.getElementById('result-search').innerHTML = "<div style='font-size: 20px; text-align: center; margin-top: 10px'>Aucun utilisateur</div>"
                        }
                    }
                })
            }
      
        });
    });
  </script>
