<?php
require 'headerv3.php';
require_once "phpqrcode/qrlib.php";
require_once "phpqrcode/qrconfig.php";

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alert alert-danger">Des autorisations sont requises pour consulter cette page</div><?php

    }else{
		$bdd='enseignantencours'; 
		$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`matriculens` VARCHAR(50) NULL,
		`promo` VARCHAR(50) DEFAULT '2024',
		`cursus` VARCHAR(50) DEFAULT 'secondaire',
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");?>

    	<div class="container-fluid">

    		<div class="row"><?php 
		    	require 'navformation.php';
		    	?>

				<div class="col-sm-12 col-md-10 col-lg-10" style="overflow: auto;"><?php 

					if (isset($_GET['ajout_en'])) {

						$form=$DB->query('SELECT *from matiere ');?>
						
						<form class="form bg-light p-2" method="POST" action="enseignant.php" enctype="multipart/form-data" >

					    	<fieldset><legend>Ajouter un enseignant</legend>

					    		<div class="container-fluid">

					    			<div class="row">

					    				<div class="col-sm-12 col-md-6">

								    		<div class="mb-1">

												<label class="form-label">Fonction du personnel*</label><select class="form-select" type="text" name="perso" required="">
													<option></option>
													<option value="enseignant">Enseignant</option>
													
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

							if (empty($_POST['salaire'])) {
								$salaire=0;
							}

							if (empty($_POST['thoraire'])) {
								$thoraire=0;
							}

							if ($_POST['perso']=='enseignant') {			

								$nb=$DB->querys('SELECT *from enseignant inner join contact on enseignant.matricule=contact.matricule where nomen=:nom and prenomen=:prenom and phone=:phone', array(
								'nom'=>$nom,
								'prenom'=>$prenom,
								'phone'=>$phone
								));

								if(!empty($nb)){?>
									<div class="alert alert-warning">Cet enseignant est déjà enregistré</div><?php
								}else{

									$nb=$DB->querys('SELECT max(id) as id from enseignant');

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

									$DB->insert('INSERT INTO enseignant(matricule, nomen, prenomen, sexe, numbanq, agencebanq, datenaiss, lieunaiss, embauche, adresse, dateenreg) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($matricule, $nom, $prenom, $sexe, $numbanq, $agencebanq, $datenaiss, $lieunaiss, $embauche, $adresse));

									/*		Ajouter le num dans le login    */

									$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array($matricule, strtolower($pseudo) , strtolower($mdp), $type, $auto));
									
									$prodcontact=$DB->querys("SELECT id from contact where matricule='{$matricule}' ");
									
									if (empty($prodcontact)) {
										$DB->insert('INSERT INTO contact(matricule, phone, email) values(?, ?, ?)', array($matricule, strtolower($phone) , strtolower($email)));
									}

									$DB->insert('INSERT INTO salaireens(numpers, salaire, thoraire, promo) values(?, ?, ?, ?)', array($matricule, $salaire, $thoraire, strtolower($_SESSION['promo'])));

									if (!empty($_POST['ss'])) {

										$DB->insert('INSERT INTO ssocialens(numpers, montant) values(?, ?)', array($matricule, $ss));
									}else{

										$DB->insert('INSERT INTO ssocialens(numpers, montant) values(?, ?)', array($matricule, 0));

									}

									$DB->insert('INSERT INTO prime(numpersp, montantp, promop) values(?, ?, ?)', array($matricule, $prime, $_SESSION['promo']));


									foreach ($niveau as $value) {							

										$DB->insert('INSERT INTO niveau(matricule, nom) values(?, ?)', array($matricule, $value));
									

										$prodniv=$DB->querys("SELECT id from niveauc where matricule='{$matricule}' ");

										if (empty($prodniv['id'])){ 

											if ($value=='college' or $value=='lycee') {
												
												$DB->insert('INSERT INTO niveauc(matricule, nom) values(?, ?)', array($matricule, 'secondaire'));
											}else {
												
												$DB->insert('INSERT INTO niveauc(matricule, nom) values(?, ?)', array($matricule, $value));
											}
										}

									}

									$logo=$_FILES['photo']['name'];

						            if($logo!=""){

						              require "uploadImageens.php";

						              require "uploadens.php";
						             
						            }?>	

									<div class="alert alert-success">Enseignant ajouté avec succée!!!</div><?php
								}

							}

						}else{?>	

							<div class="alert alert-warning">Remplissez les champs vides</div><?php
						}
					}


					//Modifier un enseignant

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
						$salaire=$panier->h(($panier->espace($_POST['salaire'])));
						$thoraire=$panier->h((($_POST['thoraire'])));

						$prime=0;
						$ss=0;

						$agencebanq=$panier->h((($_POST['agenceb'])));
						$numbanq=$panier->h((($_POST['numb'])));

						$datenaiss=$panier->h((($_POST['datenaiss'])));
						$lieunaiss=$panier->h((($_POST['lieunaiss'])));
						$adresse=$panier->h((($_POST['adresse'])));
						$embauche=$panier->h((($_POST['embauche'])));

						$matc=$panier->h(($panier->espace($_POST['matc'])));

						if (empty($_POST['salaire'])) {
							$salaire=0;
						}

						if (empty($_POST['thoraire'])) {
							$thoraire=0;
						}
						if (empty($datenaiss)) {
							$datenaiss=date("Y-m-d");
						}
						if (empty($embauche)) {
							$embauche=date("Y-m-d");
						}
						$DB->insert('UPDATE enseignant SET matricule=?, nomen = ?, prenomen=?, sexe=?, numbanq=?, agencebanq=?, datenaiss=?, lieunaiss=?, embauche=?, adresse=? WHERE matricule = ?', array($matc, $nom, $prenom, $sexe, $numbanq, $agencebanq, $datenaiss, $lieunaiss, $embauche, $adresse, $_POST['mat']));
						$DB->insert('UPDATE salaireens SET numpers=? WHERE numpers = ?', array($matc, $_POST['mat']));
						$DB->insert('UPDATE ssocialens SET numpers=?, montant=? WHERE numpers = ?', array($matc, $ss, $_POST['mat']));
						$DB->insert('UPDATE prime SET numpersp=?, montantp=? WHERE numpersp = ?', array($matc, $prime, $_POST['mat']));
						$DB->insert('UPDATE events SET codensp=? WHERE codensp = ?', array($matc, $_POST['mat']));						
						$DB->insert('UPDATE enseignement SET codens=? WHERE codens = ?', array($matc, $_POST['mat']));
						$DB->insert('UPDATE payenseignant SET matricule=? WHERE matricule=?', array($matc, $_POST['mat']));
						$DB->insert('UPDATE histopayenseignant SET matricule=? WHERE matricule=?', array($matc, $_POST['mat']));
						$DB->insert('UPDATE horairet SET numens=? WHERE numens=?', array($matc, $_POST['mat']));
						$DB->insert('UPDATE liaisonenseigpers SET matricule=? WHERE matricule=?', array($matc, $_POST['mat']));
						$DB->insert('UPDATE enseignantencours SET matriculens=? WHERE matriculens=?', array($matc, $_POST['mat']));

						$matricule=$_POST['matc'];

						$logo=$_FILES['photo']['name'];

						if($logo!=""){

							require "uploadImageens.php";

							require "uploadens.php";
							
						}

							/*		Modifier le num dans le contact    */

						$DB->insert('UPDATE contact SET matricule=?, phone = ?, email=? WHERE matricule = ?', array($matc, $phone, strtolower($email), $_POST['mat']));

						$DB->insert('UPDATE login SET matricule=?, type = ?, niveau=? WHERE matricule = ?', array($matc, $type, $auto, $_POST['mat']));

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


					if (isset($_GET['modif_en'])) {?>

						<div class="container-fluid">

							<div class="row">

								<div class="col-sm-12 col-md-8">
						
						    		<form class="form" method="POST" action="enseignant.php?modif_en=<?=$_GET['modif_en'];?>"  enctype="multipart/form-data">
						    			

								    	<fieldset><legend>Modifier un enseignant</legend><?php
											
											$prodm=$DB->querys('SELECT enseignant.matricule as matricule, type,  nomen, prenomen, type, sexe, phone, email, niveau, pseudo, mdp, montant, numbanq, agencebanq, datenaiss, lieunaiss, embauche, adresse from enseignant left join contact on enseignant.matricule=contact.matricule left join login on login.matricule=contact.matricule left join ssocialpers on ssocialpers.numpers=enseignant.matricule where enseignant.matricule=:mat', array('mat'=>$_GET['modif_en']));
											
											$prodsalaire=$DB->querys('SELECT  salaire, thoraire from salaireens where numpers=:mat and promo=:promo', array('mat'=>$_GET['modif_en'], 'promo'=>$_SESSION['promo']));?>

							    			<input type="hidden" name="perso" value="enseignant"/>

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
											<input class="form-control"type="hidden" name="thoraire" value="<?php if(!empty($prodsalaire['thoraire'])){echo $prodsalaire['thoraire'];}?>" >

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
						
						    		<form class="form" method="POST" action="enseignant.php?modif_en=<?=$prodm['matricule'];?>">

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

									if(isset($_POST['modifsalaire'])){
										$mat=$panier->h($_POST['mat']);
										$salaire=$panier->h($panier->espace($_POST['salaire']));
										$thoraire=$panier->h($panier->espace($_POST['thoraire']));
										$verif=$DB->querys('SELECT *from salaireens where numpers=:mat and promo=:promo', array('mat'=>$mat, 'promo'=>$_SESSION['promo']));
										if (!empty($verif['id'])) {
											if(empty($thoraire)){
												$DB->insert('UPDATE salaireens SET salaire=? WHERE numpers = ?', array($salaire, $mat));
											}else{
												$DB->insert('UPDATE salaireens SET salaire=?, thoraire=? WHERE numpers = ?', array($salaire, $thoraire, $mat));

											}
										}else{
											if(empty($thoraire)){
												$DB->insert('INSERT INTO salaireens(numpers, salaire, promo)VALUES(?,?,?)', array($mat,$salaire,$_SESSION['promo']));
											}else{
												$DB->insert('INSERT INTO salaireens(numpers, thoraire, promo)VALUES(?,?,?)', array($mat,$thoraire,$_SESSION['promo']));
											}
										}?>
										<div class="alert alert-success">Opération éffectuée avec succée!!!</div><?php
									}

									if ($products['type']=='admin' or $products['type']=='rh' or $products['type']=='bibliothecaire') {
										$prodsalaire=$DB->querys('SELECT  salaire, thoraire from salaireens where numpers=:mat and promo=:promo', array('mat'=>$_GET['modif_en'], 'promo'=>$_SESSION['promo']));?>

										<form class="form my-4" method="POST" action="enseignant.php?modif_en=<?=$prodm['matricule'];?>">

											<fieldset><legend></legend>
												<div class="row my-2">

													<div class="col-sm-12 col-md-6">
														<input class="form-control" type="hidden" name="mat" value="<?=$prodm['matricule'];?>"/>

														<label class="form-label">Salaire</label>
														<input class="form-control"type="text" name="salaire" value="<?=$prodsalaire['salaire'];?>">
													</div>
													<div class="col-sm-12 col-md-6">

														<label class="form-label">Taux Horaire</label><input class="form-control" type="text" name="thoraire" value="<?php if(!empty($prodsalaire['thoraire'])){echo $prodsalaire['thoraire'];}?>" >
													</div>
												</div>
												<button type="submit" name="modifsalaire" class="btn btn-warning">Modifier</button>


											</fieldset>
										</form><?php
									}?>
								</div>
							</div>
						</div><?php
							
					}

					

					if (isset($_GET['enseig']) or isset($_POST['ajouteen']) or isset($_GET['termec'])  or isset($_GET['termep']) or isset($_GET['del_en']) or isset($_GET['del_pers']) or isset($_POST['modifen']) or isset($_GET['matiereen']) or isset($_GET['personnel']) or isset($_GET['payempcherc']) or isset($_GET['livrens']) or isset($_GET['page'])) {

						if (isset($_GET['del_en'])) {
							$DB->delete('DELETE FROM enseignant WHERE matricule = ?', array($_GET['del_en']));
							$DB->delete('DELETE FROM contact WHERE matricule = ?', array($_GET['del_en']));
							$DB->delete('DELETE FROM login WHERE matricule = ?', array($_GET['del_en']));
							$DB->delete('DELETE FROM salaireens WHERE numpers = ?', array($_GET['del_en']));
							$DB->delete('DELETE FROM ssocialens WHERE numpers = ?', array($_GET['del_en']));
							$DB->delete('DELETE FROM prime WHERE numpersp = ?', array($_GET['del_en']));							
							$DB->delete('DELETE FROM niveauc WHERE matricule = ?', array($_GET['del_en']));
							$DB->delete('DELETE FROM niveau WHERE matricule = ?', array($_GET['del_en']));
							$DB->delete("DELETE  FROM enseignantencours where matriculens='{$_GET['del_en']}' ");
							?>

							<div class="alert alert-success">Suppression reussie!!!</div><?php 
						}

						if (!isset($_POST['modifen'])) {

							//require 'paginationens.php';

							if (isset($_GET['termec'])) {
								$_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
								$terme = $_GET['termec'];
								$terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
								$terme = strip_tags($terme); //pour supprimer les balises html dans la requête
								$terme = strtolower($terme);

								$prodm =$DB->query('SELECT *from enseignant inner join enseignantencours on enseignant.matricule=matriculens left join contact on enseignant.matricule=contact.matricule  WHERE (enseignant.matricule LIKE? or nomen LIKE ? or prenomen LIKE ? or phone LIKE ?) and promo LIKE ? order by(prenomen)',array("%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%",$_SESSION['promo']));

								
								
							}elseif(!empty($_SESSION['niveauf'])) {

								$prodm=$DB->query("SELECT enseignant.matricule as matricule, nomen, prenomen, phone from enseignant inner join enseignantencours on enseignant.matricule=matriculens left join contact on enseignant.matricule=contact.matricule  inner join niveau on enseignant.matricule=niveau.matricule where nom='{$_SESSION['niveauf']}' and promo='{$_SESSION['promo']}'  order by(prenomen)");

							}else{

								$prodm=$DB->query("SELECT  *from enseignant inner join enseignantencours on enseignant.matricule=matriculens left join contact on enseignant.matricule=contact.matricule where promo='{$_SESSION['promo']}' order by(prenomen)");
							}?>

							<div class="row" style="height: 90vh;">
		
								<table class="table table-hover table-bordered table-striped table-responsive text-center">
									<thead class="sticky-top bg-light">
										<form class="form" method="GET" action="enseignant.php" id="suitec" name="termc">
											<tr>
												<th colspan="8" class="info" style="text-align: center">Liste des Enseignants

													<a style="margin-left: 10px;"href="printdoc.php?enseig&niveau=<?=$_SESSION['niveaufl'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

													<a style="margin-left: 10px;"href="csv.php?enseignant" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
												</th>
											</tr>

											<tr>
												<th colspan="5">

													<input class="form-control" type = "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">

													<input class="form-control"  type = "hidden" name = "effnav" value = "search">

												</th>

												<th colspan="3"><?php 

													if ($products['type']=='admin' or $products['type']=='comptable' or $products['type']=='rh' or $products['type']=='informaticien' or $products['type']=='Proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='bibliothecaire') {?>
														<a href="enseignant.php?ajout_en" class="btn btn-info">Ajouter un enseignant</a>
														<a href="enseignantconfig.php?ajout_en" class="btn btn-info">Configuration</a><?php
													}?>
												</th>
												
											</tr>
										</form>

										<tr>
											<th>N°</th>
											<th>Matricule</th>
											<th>Prénom & Nom</th>
											<th>Téléphone</th>
											<th>Matière</th>
											<th colspan="3"></th>
										</tr>

									</thead>

									<tbody><?php
										if (empty($prodm)) {
											# code...
										}else{
											$keye=1;
											foreach ($prodm as $key=> $formation) {?>

												<tr>
													<td style="text-align: center;"><?=$key+1;?></td>
													<td style="text-align: center; font-size: 14px;"><?php
														if (isset($_GET['payempcherc'])) {?>
															
															<a href="comptabilite.php?payecherc=<?=$formation->matriculens;?>"><?=$formation->matriculens;?></a><?php
														}elseif (isset($_GET['livrens'])) {?>
															
															<a href="emprunterlivre.php?enseig&payecherc=<?=$formation->matriculens;?>"><?=$formation->matriculens;?></a><?php
														}else{?>

															<a href="comptabilite.php?horairecherc=<?=$formation->matriculens;?>"><?=$formation->matriculens;?></a><?php
														}?>
													</td>

													<td><?=ucwords(strtolower($formation->prenomen)).' '.strtoupper($formation->nomen);?></td>

													<td><?=$formation->phone;?></td>

													<td><?=$formation->cursus;?></td>

													<td>
														<a class="btn btn-info" href="enseignant.php?ficheens=<?=$formation->matricule;?>">+infos</a>
													</td>

													<td><?php 

														if ($products['type']=='admin' or $products['type']=='bibliothecaire' or $products['type']=='comptable' or $products['type']=='Proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle') {?>
															<a class="btn btn-warning" href="enseignant.php?modif_en=<?=$formation->matricule;?>&type=<?="enseignant";?>">Modifier</a><?php 
														}?>
													</td>

													<td><?php

														if ($products['type']=='admin') {?>

															<a class="btn btn-danger" href="enseignant.php?del_en=<?=$formation->matricule;?>" onclick="return alerteS();">Supprimer</a><?php
														}?>
													</td>

												</tr><?php
											}
										}?>

											
									</tbody>
								</table>
							</div><?php
						}
					}

					// affichage des eleves de l'enseignants


					if (isset($_GET['voir_elens'])) {?>

						<table class="table table-hover table-bordered table-striped table-responsive text-center">
							<thead>
								<tr>
									<th colspan="8" class="info" style="text-align: center">Liste des élèves de <?=$_GET['voir_elens'];?><a style="margin-left: 10px;"href="#?listel=<?=$_GET['voir_elens'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
								</tr>

								<tr>
									<th height="30"></th>
									<th>N°M</th>
									<th colspan="3">Nom & Prénom</th>
									<th>Né(e)</th>
									<th colspan="3"></th>
								</tr>
							</thead>

							<tbody><?php

								$prodenseig=$DB->query('SELECT nomgr from enseignement where codens=:code and promo=:promo', array('code'=>$_GET['voir_elens'], 'promo'=>$_SESSION['promo']));

								$nbreeleve=0;

								foreach ($prodenseig as $value) {

									$prodeleve=$DB->query('SELECT inscription.matricule as matricule, nomel, prenomel, date_format(naissance,\'%Y \') as naissance from eleve inner join inscription on inscription.matricule=eleve.matricule where annee=:promo and nomgr=:nom order by(matricule)', array('nom'=>$value->nomgr, 'promo'=>$_SESSION['promo']));


									foreach ($prodeleve as $key => $eleve) {?>

										<tr>
											<td><?=$key+1;?></td>

											<td style="text-align: center;"><?=$eleve->matricule;?></td>

											<td colspan="3"><?=strtoupper($eleve->nomel).' '.ucfirst(strtolower($eleve->prenomel));?></td>

											<td style="text-align: center;"><?=$eleve->naissance;?></td>

											<td colspan="3">
												<a href="ajout_eleve.php?fiche_eleve=<?=$eleve->matricule;?>&promo=<?=$_SESSION['promo'];?>" class="btn btn-info">+Infos</a>
											</td>

										</tr><?php
									}
								}?>
							</tbody>
						</table><?php
					}

					if (isset($_GET['ficheens']) or isset($_GET['fichepers'])  or isset($_GET['supimg'])  or isset($_POST["ajoutimg"])) {

						require 'fiche_ens.php';

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