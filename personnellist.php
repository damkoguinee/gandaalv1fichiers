<?php
require 'headerv3.php';
require_once "phpqrcode/qrlib.php";
require_once "phpqrcode/qrconfig.php";

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alert alert-danger">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

    	<div class="container-fluid">

    		<div class="row"><?php 
		    	require 'navformation.php';
		    	?>

				<div class="col-sm-12 col-md-10 col-lg-10 " style="overflow: auto;"><?php 
					if (isset($_GET['fichepers']) or isset($_GET['fichepers'])  or isset($_GET['supimg'])  or isset($_POST["ajoutimg"])) {

						require 'fiche_pers.php';

					}

					if (isset($_GET['ajout_en'])) {

						$form=$DB->query('SELECT *from matiere ');?>
						
						<form class="form bg-light p-2" method="POST" action="personnellist.php" enctype="multipart/form-data">

					    	<fieldset><legend>Ajouter un personnel</legend>

					    		<div class="container-fluid">

					    			<div class="row">

					    				<div class="col-sm-12 col-md-6">

								    		<div class="mb-1">

												<label class="form-label">Fonction du personnel*</label><select class="form-select" type="text" name="perso" required="">
													<option></option>
													<option value="fondation">Fondation</option>
													<option value="fondateur">Fondateur</option>
													<option value="Administrateur Général">Administrateur Général</option>
													<option value="rh">Ressources humaines</option>
													<option value="Directeur Général">Directeur Général</option>
													<option value="secrétaire">Secrétaire</option>
													<option value="Directeur du primaire">Directeur du primaire</option>
													<option value="coordonateur bloc B">coordonateur bloc B</option>									
													<option value="coordinatrice maternelle">Coordinatrice Maternelle</option>
													<option value="monitrice">Monitrice</option>
													<option value="proviseur">Proviseur</option>
													<option value="DE/Censeur">Directeur des etudes</option>
													<option value="Conseille a l'éducation">Conseiller à l'éducation</option>

													<option value="bibliothecaire">Bibliothécaire</option>
													<option value="comptable">Comptable</option>
													
													<option value="surveillant Général">Surveillant Général</option>

													<option value="électricien">Electricien</option>
													<option value="technicien de surface">Technicien de Surface</option>

													<option value="vigile">Vigile</option>

													<option value="conseiller pédogogique">Conseiller Pédagogique</option>

													<option value="informaticien">Informaticien</option>

													<option value="cuisinier">Cuisinier</option>

													<option value="aide maitresse">Aide Maitresse</option>

													<option value="gardien">Gardien</option>

													<option value="chauffeur">Chauffeur</option>

													<option value="hygieniste">Hygièniste</option>
												</select>

											</div>

											<div class="container-fluid">
												<div class="row">

													<div class="col-sm-12 col-md-6 mb-1">

											  			<label class="form-label">Justificatifs</label>
									                	<input class="form-control" type="file" name="just[]"multiple id="photo" />
									                	<input class="form-control" type="hidden" value="b" name="env"/>
									              	</div>

									              	<div class="col-sm-12 col-md-6 mb-1">
								              			<label class="form-label">Photo</label>
									                	<input class="form-control" type="file" name="photo" id="photo" />
									                	<input class="form-control" type="hidden" value="b" name="env"/>
								              		</div>
								              	</div>
								            </div>

								            <div class="container-fluid">

								            	<div class="row">
								            		<div class="col-sm-12 col-md-6 mb-1">
														<label class="form-label">N°Matricule</label>
														<input class="form-control" type="text" name="matr">
													</div>

													<div class="col-sm-12 col-md-6 mb-1">

														<label class="form-label">Nom*</label>
														<input class="form-control" type="text" name="nom" required="">
													

													</div>
												</div>
											</div>

											<div class="mb-1">

												<label class="form-label">Prénom*</label>
												<input class="form-control" type="text" name="prenom" required=""> 
											</div>

											<div class="mb-1">

												<label class="form-label">Date de Naissance</label>
												<input class="form-control" type="date" name="datenaiss"> 
											</div>

											<div class="mb-1">

												<label class="form-label">Lieu de Naissance</label>
												<input class="form-control" type="text" name="lieunaiss"> 
											</div>
											<div class="mb-1">

												<label class="form-label">Sexe*</label>
												<select class="form-select" type="text" name="sexe" required="">
													<option></option>
													<option value="m">Masculin</option>
													<option value="f">feminin</option>
												</select> 
											</div>

											<div class="container-fluid">

								            	<div class="row">
								            		<div class="col-sm-12 col-md-6 mb-1">

								            			<label class="form-label">Téléphone</label>
												    	<input class="form-control" type="text" name="tel" >
													</div>

													<div class="col-sm-12 col-md-6 mb-1">
														<label class="form-label">Mail</label>
														<input class="form-control" type="email" name="email">  
													</div>
												</div>
											</div>
										</div>

										<div class="col-sm-12 col-md-6">

											<div class="container-fluid">

								            	<div class="row">
								            		<div class="col-sm-12 col-md-6 mb-1">

														<label class="form-label">N° Bancaire</label>
														<input class="form-control" type="text" name="numb"> 
											  		</div>

											  		<div class="col-sm-12 col-md-6 mb-1">
														<label class="form-label">Agence Bancaire</label>
														<input class="form-control" type="text" name="agenceb"> 
												  	</div>
												</div>
											</div>

											<div class="container-fluid">

								            	<div class="row">
								            		<div class="col-sm-12 col-md-6 mb-1">

													    <label class="form-label">Salaire</label>
													    <input class="form-control" type="text" name="salaire"> 
													</div>

													<div class="col-sm-12 col-md-6 mb-1">
														<label class="form-label">Taux Hoaraire</label>
														<input class="form-control" type="text" name="thoraire" >
													</div>
												</div>
											</div>

											<div class="container-fluid">

								            	<div class="row">
								            		<div class="col-sm-12 col-md-6 mb-1">
													    <label class="form-label">Sécurité Sociale</label>
													    <input class="form-control" type="text" name="ss" >  
													</div>

													<div class="col-sm-12 col-md-6 mb-1">
													    <label class="form-label">Prime</label>
													    <input class="form-control" type="text" name="prime" value="0">  
													</div>
												</div>
											</div>

											<div class="container-fluid">

								            	<div class="row">
								            		<div class="col-sm-12 col-md-6 mb-1">

														<label class="form-label">Niveau*</label>
														<select class="form-select" type="number" name="niv[]"multiple required="">
															<option></option><?php

														$prodf=$DB->query('SELECT *from cursus order by(id)');

						                                foreach ($prodf as $value) {?>

													    	<option value="<?=$value->nom;?>"><?=ucwords($value->nom);?></option><?php
														}?></select>

													</div>

													<div class="col-sm-12 col-md-6 mb-1">

														<label class="form-label">Autorisation*</label>
														<select class="form-select" type="number" name="auto" required="">
															<option value="1">Niveau 1</option>
															<option value="2">Niveau 2</option>
															<option value="3">Niveau 3</option>
															<option value="4">Niveau 4</option>
															<option value="5">Niveau 5</option>
														</select>  
													</div>
												</div>

                                                <div class="mb-1">
													<label class="form-label">Autres Fonction</label>
													<select class="form-select" name="autresf"> 
                                                        <option value=""></option>
                                                        <option value="enseignant">Enseignant</option>
                                                    </select>
												</div>

												<div class="mb-1">
													<label class="form-label">Date d'Embauche</label>
													<input class="form-control" type="date" name="embauche"> 
												</div>

												<div class="mb-1">
													<label class="form-label">Adresse</label>
													<input class="form-control" type="text" name="adresse"> 
												</div>
											</div>
										</div>
									</div>
								</div>

								<button class="btn btn-primary" type="submit" value="Valider" name="ajouteen">Valider</button>

							</fieldset>
						</form><?php
					}

					if(isset($_POST['ajouteen'])){

						if($_POST['nom']!="" and $_POST['prenom']!=""  and $_POST['perso']!=""){
							
							$nom=$panier->h(($_POST['nom']));
							$prenom=$panier->h(($_POST['prenom']));
							$phone=$panier->h(($_POST['tel']));
							$email=$panier->h((($_POST['email'])));
							$sexe=$panier->h((($_POST['sexe'])));
							$type=$panier->h((($_POST['perso'])));
							$niveau=$_POST['niv'];
							$auto=$panier->h((($_POST['auto'])));
							$salaire=$panier->h((($_POST['salaire'])));
							$thoraire=$panier->h((($_POST['thoraire'])));
							$ss=$panier->h((($_POST['ss'])));
							$prime=$panier->h((($_POST['prime'])));
							$agencebanq=$panier->h((($_POST['agenceb'])));
							$numbanq=$panier->h((($_POST['numb'])));
							$datenaiss=$panier->h((($_POST['datenaiss'])));
							$lieunaiss=$panier->h((($_POST['lieunaiss'])));
							$adresse=$panier->h((($_POST['adresse'])));
							$embauche=$panier->h((($_POST['embauche'])));
							$autresf=$panier->h((($_POST['autresf'])));

							if (empty($_POST['salaire'])) {
								$salaire=0;
							}

							if (empty($_POST['thoraire'])) {
								$thoraire=0;
							}

                            $nb=$DB->querys('SELECT nom from personnel inner join contact on personnel.numpers=matricule where nom=:nom and prenom=:prenom and phone=:phone', array(
                            'nom'=>$nom,
                            'prenom'=>$prenom,
                            'phone'=>$phone
                            ));

                            if(!empty($nb)){?>
                                <div class="alert alert-warning">Ce personnel est déjà enregistré</div><?php
                            }else{

                                $nb=$DB->querys('SELECT max(id) as id from personnel');
                                
                                if (!empty($_POST['matr'])) {

                                    $matricule=$_POST['matr'];
                                    $matricule=$matricule;
                                }else{

                                    $matricule=$nb['id']+1;
                                    $matricule="csp".$matricule;
                                }
                                $pseudo=$prenom[0].$nom.$matricule[4];
                                $mdp=$matricule;
                                $mdp=password_hash($mdp, PASSWORD_DEFAULT);

                                $DB->insert('INSERT INTO personnel(numpers, nom, prenom, sexe, numbanq, agencebanq, lieunaiss, datenaiss, embauche, adresse, dateenreg) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($matricule, $nom, $prenom, $sexe, $numbanq, $agencebanq, $lieunaiss, $datenaiss, $embauche, $adresse));

                                /*		Ajouter le num dans le login    */

                                $DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array($matricule, strtolower($pseudo) , strtolower($mdp), $type, $auto));

                                $prodcontact=$DB->querys("SELECT id from contact where matricule='{$matricule}' ");
                                
                                if (empty($prodcontact)) {
                                    $DB->insert('INSERT INTO contact(matricule, phone, email) values(?, ?, ?)', array($matricule, strtolower($phone) , strtolower($email)));
                                }

                                if (!empty($autresf)) {
                                    $DB->insert('INSERT INTO liaisonenseigpers(matricule, promo) values(?, ?)', array($matricule, $_SESSION['promo']));
                                }

                                $DB->insert('INSERT INTO salairepers(numpers, salaire, promo) values(?, ?, ?)', array($matricule, strtolower($salaire) , strtolower($_SESSION['promo'])));

                                if (!empty($_POST['ss'])) {

                                    $DB->insert('INSERT INTO ssocialpers(numpers, montant) values(?, ?)', array($matricule, $ss));
                                }else{

                                    $DB->insert('INSERT INTO ssocialpers(numpers, montant) values(?, ?)', array($matricule, 0));

                                }

                                $DB->insert('INSERT INTO primepers(numpersp, montantp, promop) values(?, ?, ?)', array($matricule, $prime, $_SESSION['promo']));

                                foreach ($niveau as $value) {							

                                    $DB->insert('INSERT INTO niveau(matricule, nom) values(?, ?)', array($matricule, $value));								
                                }

                                $logo=$_FILES['photo']['name'];

                                if($logo!=""){

                                    require "uploadImagepers.php";

                                    require "uploadpers.php";
                                    
                                }?>	

                                <div class="alert alert-success">Personnel ajouté avec succée!!!</div><?php
                            }
							

						}else{?>	

							<div class="alert alert-warning">Remplissez les champs vides</div><?php
						}
					}


					//Modifier un enseignant

					if (isset($_GET['modif_en'])) {
						if(isset($_POST['modifsalaire'])){
							$mat=$panier->h($_POST['mat']);
							$salaire=$panier->h($panier->espace($_POST['salaire']));
							$verif=$DB->querys("SELECT id from salairepers where numpers='{$mat}' and promo='{$_SESSION['promo']}' ");

							if (empty($verif['id'])) {
								$DB->insert("INSERT INTO salairepers (numpers,salaire,promo)VALUES(?,?,?)",array($mat,$salaire,$_SESSION['promo']));
							}else{

								$DB->insert('UPDATE salairepers SET salaire=? WHERE numpers = ?', array($salaire, $mat));
							}?>
							<div class="alert alert-success">Opération éffectuée avec succèe!!!</div><?php
						}?>

						<div class="container-fluid">

							<div class="row">

								<div class="col-sm-12 col-md-8">
						
						    		<form class="form" method="POST" action="personnellist.php" enctype="multipart/form-data">
						    			

								    	<fieldset><legend>Modifier un personnel</legend><?php
											$prodm=$DB->querys('SELECT personnel.numpers as matricule, type, nom as nomen, prenom as prenomen, type, sexe, phone, email, niveau, pseudo, mdp, montant, numbanq, agencebanq, datenaiss, lieunaiss, embauche, adresse from personnel left join contact on numpers=contact.matricule left join login on login.matricule=contact.matricule left join ssocialpers on ssocialpers.numpers=personnel.numpers where personnel.numpers=:mat', array('mat'=>$_GET['modif_en']));
											
											$prodsalaire=$DB->querys('SELECT  salaire from salairepers where numpers=:mat and promo=:promo', array('mat'=>$_GET['modif_en'], 'promo'=>$_SESSION['promo']));?>



                                            <div class="mb-1">
                                                <label class="form-label">Fonction du personnel</label>
                                                <select class="form-select" type="text" name="perso" required="">
                                                    <option value="<?=$prodm['type'];?>"><?=$prodm['type'];?></option>
                                                    <option value="fondation">Fondation</option>
                                                    <option value="fondateur">Fondateur</option>
                                                    <option value="Administrateur Général">Administrateur Général</option>
                                                    <option value="Directeur Général">Directeur Général</option>
                                                    <option value="secrétaire">Secrétaire</option>
                                                    <option value="Directeur du primaire">Directeur du primaire</option>
                                                    <option value="coordonateur bloc B">coordonateur bloc B</option>									
                                                    <option value="coordinatrice maternelle">Coordinatrice Maternelle</option>
                                                    <option value="monitrice">Monitrice</option>
                                                    <option value="proviseur">Proviseur</option>
                                                    <option value="DE/Censeur">Directeur des etudes</option>
                                                    <option value="Conseille a l'éducation">Conseiller à l'éducation</option>

                                                    <option value="bibliothécaire">Bibliothécaire</option>
                                                    <option value="comptable">Comptable</option>
                                                    
                                                    <option value="surveillant Général">Surveillant Général</option>

                                                    <option value="électricien">Electricien</option>
                                                    <option value="technicien de surface">Technicien de Surface</option>

                                                    <option value="vigile">Vigile</option>

                                                    <option value="conseiller pédogogique">Conseiller Pédagogique</option>

                                                    <option value="informaticien">Informaticien</option>

                                                    <option value="cuisinier">Cuisinier</option>

                                                    <option value="hygieniste">Hygièniste</option>
                                                </select> 
                                            </div>

											<div class="container-fluid">
												<div class="row">

													<div class="col-sm-12 col-md-6 mb-1"> 

														<label class="form-label">Justificatifs</label>
									                	<input class="form-control" type="file" name="just[]"multiple id="photo" />
									                	<input class="form-control" type="hidden" value="b" name="env"/>
									                </div>

									                <div class="col-sm-12 col-md-6 mb-1">
									              	

										              	<label class="form-label">Photo</label>
									                	<input class="form-control" type="file" name="photo" id="photo" />
									                	<input class="form-control" type="hidden" value="b" name="env"/>
									                </div>
									            </div>
									        </div>

									        <div class="container-fluid">
									        	<div class="row">

									        		<div class="col-sm-12 col-md-6 mb-1">

														<label class="form-label">Matricule</label>
														<input class="form-control"type="text" name="matc" value="<?=$prodm['matricule'];?>"/>
													</div>

									        		<div class="col-sm-12 col-md-6 mb-1">

														<label class="form-label">Nom</label>
														<input class="form-control"type="text" name="nom" value="<?=$prodm['nomen'];?>"/>

														<input class="form-control" type="hidden" name="mat" value="<?=$prodm['matricule'];?>"/>
													</div>

													
												</div>
											</div>

											<div class="mb-1">

												<label class="form-label">Prénom</label>
												<input class="form-control" type="text" name="prenom" value="<?=$prodm['prenomen'];?>"/> 
											</div>

											<div class="mb-1">

												<label class="form-label">Date de Naissance</label>
												<input class="form-control" type="date" name="datenaiss" value="<?=$prodm['datenaiss'];?>"/> 
											</div>

											<div class="mb-1">

												<label class="form-label">lieu de Naissance</label>
												<input class="form-control" type="text" name="lieunaiss" value="<?=$prodm['lieunaiss'];?>"/> 
											</div>

											<div class="mb-1">
											  	<label class="form-label">Sexe</label><select class="form-select" type="text" name="sexe">
													<option><?=$prodm['sexe'];?></option>
													<option value="m">Masculin</option>
													<option value="f">feminin</option>
												</select>
											</div> 

											<div class="container-fluid">
												<div class="row">


													<div class="col-sm-12 col-md-6 mb-1">

														<label class="form-label">Téléphone</label>
														<input class="form-control" type="text" name="tel"  value="<?=$prodm['phone'];?>"/>
													</div>

													<div class="col-sm-12 col-md-6 mb-1">
														
														<label class="form-label">Mail</label>
														<input class="form-control" type="text" name="email" value="<?=$prodm['email'];?>"/>
													</div>
												</div>
											</div>

											<div class="container-fluid"> 
												<div class="row">

													<div class="col-sm-12 col-md-6 mb-1"> 
														
														<label class="form-label">N° Bancaire</label>
														<input class="form-control" type="text" name="numb" value="<?=$prodm['numbanq'];?>">
													</div>

													<div class="col-sm-12 col-md-6 mb-1">

													  	<label class="form-label">Agence Bancaire</label>
														<input class="form-control" type="text" name="agenceb" value="<?=$prodm['agencebanq'];?>">
													</div>
												</div>
											</div>

											
											<input class="form-control"type="hidden" name="salaire" value="<?=$prodsalaire['salaire'];?>">
											<input class="form-control"type="hidden" name="thoraire" value="<?php if(!empty($prodsalaire['thoraire'])){echo $prodm['thoraire'];}?>" >

											<div class="container-fluid">

												<div class="row">


													<div class="col-sm-12 col-md-6 mb-1 mb-1">
														
														<label class="form-label">Sécurité Sociale</label>
														<input class="form-control" type="text" name="ss" value="<?=$prodm['montant'];?>">
													</div>
													<input class="form-control" type="hidden" name="prime" value="0">
													
												</div>
											</div>

											<div class="container-fluid">

												<div class="row">

													<div class="col-sm-12 col-md-6 mb-1">

														<label class="form-label">Niveau</label>
														<select class="form-select" type="number" name="niv[]"multiple required="">
															<option></option><?php

															$prodf=$DB->query('SELECT *from cursus order by(id)');

							                                foreach ($prodf as $value) {?>

														    	<option value="<?=$value->nom;?>"><?=ucwords($value->nom);?></option><?php
															}?>
														</select>

													</div>
													<input  type="hidden" value="1" class="form-select" type="number" name="auto">

													
												</div>

												<div class="mb-1">

													<label class="form-label">Date d'Embauche</label>
													<input class="form-control" type="date" name="embauche" value="<?=$prodm['embauche'];?>"/> 
												</div>

												<div class="mb-1">

													<label class="form-label">Adresse</label>
													<input class="form-control" type="text" name="adresse" value="<?=$prodm['adresse'];?>"/> 
												</div>
											</div>

											<button type="submit" name="modifen" class="btn btn-primary">Modifier</button>

										</fieldset>
									</form>
								</div>

								<div class="col-sm-12 col-md-4">
						
						    		<form class="form" method="POST" action="personnellist.php?modif_en=<?=$prodm['matricule'];?>">

								    	<fieldset><legend></legend>
								    		<div class="mb-1">

												<input class="form-control" type="hidden" name="mat" value="<?=$prodm['matricule'];?>"/>	
											    <label class="form-label">Pseudo</label>
											    <input class="form-control" type="text" name="pseudo"  value="<?=$prodm['pseudo'];?>"/>
																		  	
												<label class="form-label">Mot de passe</label>
												<input class="form-control" type="text" name="mdp"/>  

										  	</div>

										  	<button type="submit" name="modifenmdp" class="btn btn-primary">Modifier</button>

										</fieldset>
									</form><?php

									if ($products['type']=='admin' or $products['type']=='rh' or $products['type']=='bibliothecaire') {?>

										<form class="form my-4" method="POST" action="personnellist.php?modif_en=<?=$prodm['matricule'];?>">

											<fieldset><legend></legend>
												<div class="row">
													<label class="form-label">Salaire</label>

													<div class="col-sm-12 col-md-8">

														<input class="form-control" type="hidden" name="mat" value="<?=$prodm['matricule'];?>"/>	
														<input class="form-control"type="text" name="salaire" value="<?=$prodsalaire['salaire'];?>">
													</div>
													<div class="col-sm-12 col-md-4">
														<button type="submit" name="modifsalaire" class="btn btn-warning">Modifier</button>
													</div>
												</div>


											</fieldset>
										</form><?php
									}

                                    if(isset($_POST['modifonction'])){
                                        $matricule=addslashes(Nl2br(Htmlspecialchars($_POST['autresf'])));
									    $DB->insert('INSERT INTO liaisonenseigpers(matricule, promo) values(?, ?)', array($matricule, $_SESSION['promo']));?>

                                        <div class="alert alert-success">Fonction ajoutée avec succée!!!</div><?php
                                        
                                    }
                                    if (isset($_GET['deletefonction'])) {
                                        $matricule=$_GET['deletefonction'];
                                        $DB->delete("DELETE FROM liaisonenseigpers WHERE matricule='{$matricule}' and promo='{$_SESSION['promo']}'");
                                    } ?>

                                    <form class="form" method="POST" action="personnellist.php?modif_en=<?=$_GET['modif_en'];?>">

								    	<fieldset><legend></legend>
								    		<div class="row">
												
											    <label class="form-label">Autre Fonction</label>
												<div class="col-sm-12 col-md-8">

													<select class="form-select" name="autresf">
														<option value=""></option>
														<option value="<?=$prodm['matricule'];?>">Enseignant</option>
													</select>
												</div>
												<div class="col-sm-12 col-md-4">
													<button type="submit" name="modifonction" class="btn btn-primary">Ajouter</button>
												</div>
										  	</div>


										</fieldset>
									</form><?php 
									$prodl=$DB->querys("SELECT *from liaisonenseigpers where  matricule='{$prodm['matricule']}' and promo='{$_SESSION['promo']}'");

                                    if (!empty($prodl['id'])) {?>
                                    
                                        <table class="table table-hover table-bordered table-striped table-responsive text-center my-4">
                                            <tbody>
                                                <tr>
                                                    <td>Enseignant</td>
                                                    <td><a class="btn btn-danger" href="personnellist.php?deletefonction=<?=$prodm['matricule'];?>&modif_en=<?=$prodm['matricule'];?>">Annuler</a></td>
                                                </tr>
                                            </tbody>
                                        </table><?php 
                                    }?>

								</div>
							</div>
						<div><?php
					}

					if(isset($_POST['modifen'])){
							
						$nom=$panier->h(($_POST['nom']));
						$prenom=$panier->h(($_POST['prenom']));
						$phone=$panier->h(($_POST['tel']));
						$email=$panier->h((($_POST['email'])));
						$sexe=$panier->h((($_POST['sexe'])));
						$type=$panier->h((($_POST['perso'])));
						
						$auto=$panier->h((($_POST['auto'])));

						//$pseudo=$panier->h((($_POST['pseudo'])));
						//$mdp=$panier->h((($_POST['mdp'])));
						//$mdp=password_hash($mdp, PASSWORD_DEFAULT);
						$salaire=$panier->h($panier->espace($_POST['salaire']));
						$thoraire=$panier->h($panier->espace($_POST['thoraire']));

						$ss=$panier->h((($_POST['ss'])));

						$prime=0;

						$agencebanq=$panier->h((($_POST['agenceb'])));
						$numbanq=$panier->h((($_POST['numb'])));

						$datenaiss=$panier->h((($_POST['datenaiss'])));
						$lieunaiss=$panier->h((($_POST['lieunaiss'])));
						$adresse=$panier->h((($_POST['adresse'])));
						$embauche=$panier->h((($_POST['embauche'])));

						$matc=$panier->h($panier->espace($_POST['matc']));

						if (empty($_POST['salaire'])) {
							$salaire=0;
						}

						if (empty($_POST['thoraire'])) {
							$thoraire=0;
						}

						$DB->insert('UPDATE personnel SET numpers=?, nom = ?, prenom=?, sexe=?, numbanq=?, agencebanq=?, datenaiss=?, lieunaiss=?, embauche=?, adresse=? WHERE numpers = ?', array($matc, $nom, $prenom, $sexe, $numbanq, $agencebanq, $datenaiss, $lieunaiss, $embauche, $adresse, $_POST['mat']));

						$DB->insert('UPDATE salairepers SET numpers=? WHERE numpers = ?', array($matc, $_POST['mat']));

						$DB->insert('UPDATE ssocialpers SET numpers=?, montant=? WHERE numpers = ?', array($matc, $ss, $_POST['mat']));

						//$DB->insert('UPDATE primepers SET numpersp=?, montantp=? WHERE numpersp = ?', array($matc, $prime, $_POST['mat']));

						$matricule=$_POST['matc'];

						$logo=$_FILES['photo']['name'];

						if($logo!=""){

							require "uploadImagepers.php";

							require "uploadpers.php";
							
						}
							/*		Modifier le num dans le contact    */

						$DB->insert('UPDATE contact SET matricule=?, phone = ?, email=? WHERE matricule = ?', array($matc, $phone, strtolower($email), $_POST['mat']));
						$DB->insert('UPDATE login SET matricule=?, type = ?, niveau=? WHERE matricule = ?', array($matc, $type, $auto, $_POST['mat']));
						$DB->insert('UPDATE payepersonnel SET matricule=? WHERE matricule=?', array($matc, $_POST['mat']));
						$DB->insert('UPDATE events SET codensp=? WHERE codensp=?', array($matc, $_POST['mat']));
						//$DB->insert('UPDATE horairet SET numens=? WHERE numens=?', array($matc, $_POST['mat']));
						$DB->insert('UPDATE liaisonenseigpers SET matricule=? WHERE matricule=?', array($matc, $_POST['mat']));
						//$DB->insert('UPDATE note SET matricule=? WHERE matricule=?', array($matc, $_POST['mat']));
						//$DB->insert('UPDATE note SET matricule=? WHERE matricule=?', array($matc, $_POST['mat']));

						if (!empty($_POST['niv'])) {
							
							$niveau=$_POST['niv'];

							$DB->delete('DELETE FROM niveau WHERE matricule = ?', array($_POST['matc']));

							foreach ($niveau as $value) {

								$DB->insert('INSERT INTO niveau(matricule, nom) values(?, ?)', array($_POST['matc'], $value));
							}
						}?>

						<div class="alert alert-success"> Modification effectuée avec succée!!!</div><?php
						
					}

					if(isset($_POST['modifenmdp'])){

						$pseudo=addslashes(Nl2br(Htmlspecialchars($_POST['pseudo'])));
						$mdp=addslashes(Nl2br(Htmlspecialchars($_POST['mdp'])));
						$mdp=password_hash($mdp, PASSWORD_DEFAULT);

						

						$DB->insert('UPDATE login SET pseudo=?, mdp=? WHERE matricule = ?', array($pseudo, $mdp, $_POST['mat']));?>

						<div class="alert alert-success"> Modification effectuée avec succée!!!</div><?php
						
					}

							// fin modification

					if (isset($_GET['enseig']) or isset($_POST['ajouteen']) or isset($_GET['termec'])  or isset($_GET['termep']) or isset($_GET['del_en']) or isset($_GET['del_pers']) or isset($_POST['modifen']) or isset($_GET['matiereen']) or isset($_GET['personnel']) or isset($_GET['payempcherc']) or isset($_GET['livrens']) or isset($_GET['page'])) {

						if (isset($_GET['del_pers'])) {

							$DB->delete('DELETE FROM personnel WHERE numpers = ?', array($_GET['del_pers']));
							$DB->delete('DELETE FROM contact WHERE matricule = ?', array($_GET['del_pers']));
							$DB->delete('DELETE FROM login WHERE matricule = ?', array($_GET['del_pers']));

							$DB->delete('DELETE FROM salairepers WHERE numpers = ?', array($_GET['del_pers']));

							$DB->delete('DELETE FROM ssocialens WHERE numpers = ?', array($_GET['del_pers']));

							$DB->delete('DELETE FROM primepers WHERE numpersp = ?', array($_GET['del_pers']));

							$DB->delete('DELETE FROM niveau WHERE matricule = ?', array($_GET['del_pers']));?>

							<div class="alert alert-success">Suppression reussie!!!</div><?php 
						}

						if (!isset($_POST['modifen'])) {
							//require 'paginationpers.php';

							if (isset($_GET['termep'])) {
								$_GET["termep"] = htmlspecialchars($_GET["termep"]); //pour sécuriser le formulaire contre les failles html
								$terme = $_GET['termep'];
								$terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
								$terme = strip_tags($terme); //pour supprimer les balises html dans la requête
								$terme = strtolower($terme);

								$prodm =$DB->query('SELECT personnel.numpers as matricule, personnel.nom as nom, prenom, phone from personnel left join contact on personnel.numpers=contact.matricule WHERE (personnel.nom LIKE ? or prenom LIKE ? or phone LIKE ?) ',array("%".$terme."%", "%".$terme."%", "%".$terme."%"));
								
							}elseif (!empty($_SESSION['niveauf'])) {

								$prodm=$DB->query("SELECT personnel.numpers as matricule, personnel.nom as nom, prenom, phone from personnel left join contact on personnel.numpers=contact.matricule left join niveau on personnel.numpers=niveau.matricule where niveau.nom='{$_SESSION['niveauf']}' ");

							}else{

								$prodm=$DB->query("SELECT  personnel.numpers as matricule, personnel.nom as nom, prenom, phone from personnel left join contact on personnel.numpers=contact.matricule order by(prenom) ");
							}?>
							<div class="row" style="height: 90vh;">
		
								<table class="table table-hover table-bordered table-striped table-responsive text-center">
								<thead class="sticky-top bg-light">
									<form class="form" method="GET" action="personnellist.php" id="suitec" name="termp">
										<tr>
											<th colspan="8">Liste des personnels
												<a class="btn btn-info" href="printdoc.php?perso" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>
												<a class="btn btn-info" href="csv.php?persodirec" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
											</th>
										</tr>
										<tr>
											<th colspan="4">

												<input class="form-control" type = "search" name = "termep" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">

												<input class="form-control"   type = "hidden" name = "effnav" value = "search">
												<input  class="form-control"  type = "hidden" name = "personnel" value = "search">

											</th>

											<th colspan="4"><?php 

											if ($products['type']=='admin' or $products['type']=='comptable' or $products['type']=='rh' or $products['type']=='informaticien' or $products['type']=='bibliothecaire') {?><a href="personnellist.php?ajout_en" class="btn btn-info">Ajouter un personnel</a><?php }?></th>
										
										</tr>
									</form>

									<tr>
										<th>N°</th>
										<th>Matricule</th>
										<th>Prénom & Nom</th>
										<th>Fonction</th>
										<th>Téléphone</th>
										<th colspan="3"></th>
									</tr>

								</thead>

								<tbody><?php

									if (empty($prodm)) {
										# code...
									}else{
										foreach ($prodm as $key=> $formation) {
											$type=$panier->login($formation->matricule)[0];?>

											<tr>
												<td style="text-align: center;"><?=$key+1;?></td>
												<td style="font-size: 16px;"><?php
													if (isset($_GET['payempcherc'])) {?>
														
														<a class="btn btn-info" href="payementpersonnel0.php?payecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
													}elseif (isset($_GET['livrepers'])) {?>
														
														<a class="btn btn-info" href="emprunterlivre.php?personnel&payecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
													}else{?>

														<?=$formation->matricule;?><?php
													}?>
												</td>

												<td><?=ucwords(strtolower($formation->prenom)).' '.strtoupper($formation->nom);?></td>

												<td><?=ucfirst($type);?></td>

												<td><?=$formation->phone;?></td>

												<td>
													<a class="btn btn-info" href="personnellist.php?fichepers=<?=$formation->matricule;?>">+Infos</a>
												</td>

												<td><?php 

													if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='bibliothecaire' or $products['type']=='comptable') {?>
														<a onclick="return alerteM();" class="btn btn-warning" href="personnellist.php?modif_en=<?=$formation->matricule;?>">Modifier</a><?php 
													}?>

												</td>

												<td><?php

													if ($products['type']=='admin' or $products['type']=='informaticien') {?>

														<a onclick="return alerteS();" class="btn btn-danger" href="enseignant.php?del_pers=<?=$formation->matricule;?>" onclick="return alerteS();">Supprimer</a><?php 
													}?>
												</td>

											</tr><?php
										}
									}?>

								
								</tbody>
								</table>
							</div><?php
						
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