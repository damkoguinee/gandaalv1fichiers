<?php

if (isset($_GET['enseignant'])) {
	require 'headerenseignant.php';
}else{
	require 'headerv2.php';
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
					require "navpreinscris.php";?>

					<div class="col-sm-12 col-md-10"><?php

						$nb=$DB->querys('SELECT count(id) as id from matricule where etat=? and annee=?', array('inscription', $_SESSION['promo']));
						$anneeins=substr($_SESSION['promo'],2,4);
						$matnew=($anneeins . '000')+($nb['id']+1);
						$matnew=$rapport->infoEtablissement()["initial"].$matnew;
				    	$matnew=$matnew;
						if (isset($_POST['group'])) {
							$codef=$panier->classeInfos($_POST['group'], $_SESSION['promo'])[0];						
							$niveau=$panier->classeInfos($_POST['group'], $_SESSION['promo'])[2];						
						}

						$prodgroupe=$DB->query("SELECT nomgr from groupe where promo='{$_SESSION['promo']}'");
						if (isset($_GET['ajoute']) or isset($_POST['ajoutel']) or isset($_GET['niveau']) or isset($_POST['group'])) {
							// Ajouter un eleve à la base de données

						    if(isset($_POST['ajoutel'])){

								if($_POST['nom']!="" and $_POST['prenom']!="" and $_POST['daten']!="" and $_POST['group']!="" and $_POST['annee']!=""){
							
									$nome=$panier->h($_POST['nom']);
									$prenom=$panier->h($_POST['prenom']);
									$daten=$panier->h($_POST['daten']);
									$nomp=$panier->h($_POST['nomp']);
									$nomm=$panier->h($_POST['nomm']);
									$phone=$panier->h($_POST['tel']);
									$adresse=$panier->h($_POST['adr']);
									$email=$panier->h($_POST['email']);
									$pays=$panier->h($_POST['pays']);
									$nation=$panier->h($_POST['nation']);
									$sexe=$panier->h($_POST['sexe']);
									$telp=$panier->h($_POST['telp']);
									$telm=$panier->h($_POST['telm']);
									$tuteur=$panier->h($_POST['tut']);
									$telt=$panier->h($_POST['telt']);
									$lieutp=$panier->h($_POST['lieutp']);
									$lieutm=$panier->h($_POST['lieutm']);
									$adressep=$panier->h($_POST['adressep']);
									
									$annee=$panier->h($_POST['annee']);
									$groupe=$panier->h($_POST['group']);
									$statut=$panier->h($_POST['statut']);

									$codef=$panier->classeInfos($groupe, $annee)[0];
									$niveau=$panier->classeInfos($groupe, $annee)[2];

									if (isset($_POST['remise'])) {

										$remise=$panier->h($_POST['remise']);
										$remisescol=$panier->h($_POST['remisescol']);
										$typep=$panier->h($_POST['typep']);
										$bordereau=$panier->h($_POST['bord']);
										$banque=$panier->h($_POST['banque']);
										$compte=$panier->h($_POST['compte']);
										$devise=$panier->h($_POST['devise']);
										$taux=$panier->h($_POST['taux']);
									}

									$origine=$panier->h($_POST['origine']);
									$profm=$panier->h($_POST['profm']);
									$profp=$panier->h($_POST['profp']);
									$proft=$panier->h($_POST['proft']);

									$nb=$DB->querys('SELECT count(id) as id from matricule where etat=? and annee=?', array('inscription', $_SESSION['promo']));

									if (!empty($_POST['mat'])) {

										$matricule=$_POST['mat'];
										$initiale='';

									}else{
										$anneeins=substr($_SESSION['promo'],2,4);
										$matricule=($anneeins . '000')+($nb['id']+1);
										$initiale=$rapport->infoEtablissement()["initial"];
									}
										
									
									$pseudo=$prenom[0].$nome.$matricule;
									$mdp=$initiale.$matricule;
									$mdp=password_hash($mdp, PASSWORD_DEFAULT);

									$verifel=$DB->querys('SELECT nomel from eleve where nomel=:nom and prenomel=:prenom and naissance=:naiss and pere=:pere and mere=:mere ', array(
										'nom'=>$nome,
										'prenom'=>$prenom,
										'naiss'=>$daten,
										'pere'=>$nomp,
										'mere'=>$nomm
									));

									$verifmat=$DB->querys('SELECT nomel from eleve where matricule=:nom ', array(
										'nom'=>$initiale.$matricule
									));
									$etat="non inscrit";

									if(!empty($verifel)){?>

										<div class="alert alert-danger">Cet élève est déjà enregistré</div><?php

									}elseif(!empty($verifmat)){?>

										<div class="alert alert-danger">Ce matricule existe</div><?php

									}else{
										//Upload photo

										if(isset($_POST["env"])){

								            $logo=$_FILES['photo']['name'];

								            if($logo!=""){

								              require "uploadImage.php";

								              require "uploadpdf.php";
								             
								            }
								        }

										$DB->insert('INSERT INTO eleve(matricule, nomel, prenomel, sexe, naissance, pere, mere, telpere, telmere, profp, profm, origine, pays, nationnalite, adresse, lieutp, lieutm, adressep, dateenreg) values( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array(($initiale.$matricule), $nome, $prenom, $sexe, $daten, $nomp, $nomm, $telp, $telm, $profp, $profm, $origine, $pays, $nation, $adresse, $lieutp, $lieutm, $adressep));


										/*		Ajouter le num dans le login    */

										$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array(($initiale.$matricule), strtolower($pseudo) , strtolower($mdp), 'eleve', '1'));

										$DB->insert('INSERT INTO contact(matricule, phone, email) values(?, ?, ?)', array(($initiale.$matricule), strtolower($phone) , strtolower($email)));


										$nb=$DB->querys('SELECT max(id_tut) as id from tuteur');

										$matuteur=$matricule;

										$matuteur='tut'.$matuteur;

										$pseudo=$matuteur;
										$mdp=$matuteur;

										if (empty($_POST['tut'])) {

											$DB->insert('INSERT INTO tuteur(matuteur, matricule, nomtut, teltut, proft) values(?, ?, ?, ?, ?)', array($matuteur, ($initiale.$matricule), $nomp, $telp, $proft));

										}else{

											$DB->insert('INSERT INTO tuteur(matuteur, matricule, nomtut, teltut, proft) values(?, ?, ?, ?, ?)', array($matuteur, ($initiale.$matricule), $tuteur, $telt, $proft));
										}

										/*		Ajouter le num dans le login    */

										$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array($matuteur, strtolower($pseudo) , strtolower($mdp), 'tuteur', 1));
									
										$verifins=$DB->querys('SELECT codef from inscription where codef=:code and annee=:annee and matricule=:mat', array(
											'code'=>$codef,
											'annee'=>$annee,
											'mat'=>$initiale.$matricule
										));

										if(!empty($verifins)){?>
											<div class="alert alert-danger">Il est déjà inscrit à cette formation</div><?php
										}else{

											$DB->insert('INSERT INTO inscription(matricule, codef, niveau, nomgr, etat, remise, statut, annee) values( ?, ?, ?, ?, ?, ?, ?, ?)', array(($initiale.$matricule), $codef, $niveau, $groupe, 'inscription', $remisescol, $statut, $annee));

											$DB->insert('INSERT INTO matricule(matricule, etat, annee) values( ?, ?, ?)', array(($initiale.$matricule),'inscription', $annee));



											$maxid = $DB->querys('SELECT max(id) as id FROM payement');
			                        
			                    			$numpaye=$maxid['id']+1;

			                    			$montant=$_POST['mp']*(1-($remise/100));

											$DB->insert('INSERT INTO payement(caisse, numpaye, matricule, montant, devise, taux, remise, motif, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $numpaye, ($initiale.$matricule), $montant, $devise, $taux, $remise, 'inscription', $typep, $bordereau, $banque, $annee));

											if ($_POST['mp']!=0) {

												$DB->insert('INSERT INTO banque (id_banque, montant, devise, taux, libelles, numero, matriculeb, personnel, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $montant, $devise, $taux, 'paiement frais inscription', 'depins'.$numpaye, ($initiale.$matricule), $_SESSION['idpseudo'], $annee));
											}
										}

										if (!empty($_POST['activites'])) {
											$activites=$_POST['activites'];

											foreach ($activites as $valueact) {

												$mens=$DB->querys("SELECT mensualite FROM activites WHERE id='{$valueact}' and promoact='{$annee}' ");
												$mensualite=$mens['mensualite'];
												$idact=$panier->h($valueact);
												
												$DB->insert('INSERT INTO inscriptactivites (idact, matinscrit, mensualite, promoact,  dateop) VALUES(?, ?, ?, ?, now())', array($valueact, $initiale.$matricule, $mensualite, $annee));
											}
										}

										$DB->insert('UPDATE elevepreinscription SET etat=? WHERE matricule = ?', array('traite', $initiale.$matricule));?>	

										<div class="alert alert-success">Elève inscrit avec succèe!!!</div><?php

										$_SESSION['matricule']=($matricule);
										$_SESSION['etat']="reussi";
										$etat=$_SESSION['etat'];

									}

								}else{?>	

									<div class="alert alert-danger">Remplissez les champs vides</div><?php
								}
							}

							// fin inscription

							if ($_SESSION['type']=='admin' or $_SESSION['type']=='fondateur' or $_SESSION['type']=='comptable' or $_SESSION['type']=='informaticien' or $_SESSION['type']=='secrétaire' or $_SESSION['type']=='bibliothecaire') {

								if (isset($_POST['remise'])) {
					                $_SESSION['bordereau']=$_POST['bord'];
					                $_SESSION['banque']=$_POST['banque'];
					                $_SESSION['mpaiement']=$_POST['typep'];
					                $_SESSION['typer']='';
					            }

					            if (isset($_POST['group'])) {

					            	$seuil=$rapport->seuilClasse($codef, $_SESSION['promo']);

					            	if ($seuil<10) {?>
					            		<div class="alert alert-warning">Il reste <?=$seuil ;?> élève(s) à inscrire pour ce niveau</div><?php
					            	}else{?>
					            		<div class="alert alert-warning">Il reste <?=$seuil ;?> élève(s) à inscrire pour ce niveau</div><?php

					            	}


					            	if ($rapport->codefSuivant($codef)[4]=='creche') {
										$fraisreins=1;
									}elseif ($rapport->codefSuivant($codef)[4]=='maternelle') {
										$fraisreins=2;
									}elseif ($rapport->codefSuivant($codef)[4]=='primaire') {
										$fraisreins=3;
									}elseif ($rapport->codefSuivant($codef)[4]=='college') {
										$fraisreins=4;
									}elseif ($rapport->codefSuivant($codef)[4]=='lycee') {
										$fraisreins=5;
									}else{
										$fraisreins=0;
									}		            	

					            }else{
					            	$fraisreins=0;
					            }
								
								if (isset($_GET['preinscris'])) {
									$_SESSION['matpreinscrit']=$_GET['preinscris'];									
									$preinscrit=$DB->querys("SELECT *from elevepreinscription where matricule='{$_SESSION['matpreinscrit']}' ");
									?>

									<form id="formulaire" method="POST" action="ajout_eleve.php" enctype="multipart/form-data" style="display: flex; flex-wrap: wrap;">

										<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Infos Administratives</legend>
										<ol>
											<li>
												<label>Classe</label>
												<select type="text" name="group" required="" onchange="this.form.submit()" >
													<option value="<?=$preinscrit['nomgr'];?>"><?=$preinscrit['nomgr'];?></option><?php
													foreach ($prodgroupe as $form) {?>

														<option value="<?=$form->nomgr;?>"><?=$form->nomgr;?></option><?php

													}?>
												</select><?php

												if (isset($_POST['group'])) {?>
														
													<input type="hidden" name="niveau" value="<?=$niveau;?>"/>
													<input type="hidden" name="codef" value="<?=$codef;?>"/><?php

												}?>
											</li>

											<li><label>Justificatifs</label>
												<input type="file" name="just[]"multiple id="photo" />
												<input type="hidden" value="b" name="env"/>
											</li>

										</ol>

										<legend class="text-center bg-success bg-opacity-50">Filiation</legend>
										<ol>

											<li>
												<label>Nom du Père*</label>
												<input type="text" name="nomp" value="<?=$preinscrit['pere'];?>" required="">
												
											</li>

											<li>
												<label>Téléphone du Père</label>
												<input type="text" name="telp" value="<?=$preinscrit['telpere'];?>">
											</li>

											<li>
												<label>Profession du Père</label>
												<input type="text" name="profp" value="<?=$preinscrit['profp'];?>">
											</li>
											

											<li>
												<label>Nom de la mère*</label>
												<input type="text" name="nomm" value="<?=$preinscrit['mere'];?>" required="">
													
											</li>

											<li>
												<label>Téléphone de la mère</label>
												<input type="text" name="telm" value="<?=$preinscrit['telmere'];?>">
											</li>

											<li>
												<label>Profession de la Mère</label>
												<input type="text" name="profm" value="<?=$preinscrit['profm'];?>">
											</li>

											<li>
												<label>Lieu de travail du Père</label>
												<input type="text" name="lieutp" value="<?=$preinscrit['lieutp'];?>" maxlength="100">
											</li>

											<li>
												<label>Lieu de travail Mère</label>
												<input type="text" name="lieutm" value="<?=$preinscrit['lieutm'];?>" maxlength="100">
											</li>
											<li>
												<label>Adresse des Parents</label>
												<input type="text" name="adressep" value="<?=$preinscrit['adressep'];?>" maxlength="100">
											</li>
											<li>
												<label>Tuteur </label>
												<input type="text" name="tut" value="<?=$preinscrit['nomtut'];?>">
													
											</li>

											<li>
												<label>Téléphone du Tuteur</label>
												<input type="text" name="telt" value="<?=$preinscrit['teltut'];?>">
											</li>

											<li>
												<label>Profession du tuteur</label>
												<input type="text" name="proft" value="<?=$preinscrit['proft'];?>">
											</li>
										</ol>
									</fieldset>

										<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Informations de l'élève</legend>
											<ol>
												<input type="hidden" name="mat" value="<?=$preinscrit['matricule'];?>" /></li>

												<li>
													<label>Statut*</label><select type="text" name="statut" required="">
														<option value=""></option>
														<option value="admis">Admis</option>
														<option value="redoublant">Redoublant</option>
													</select> 
												</li>

												<li>
													<label>Nom*</label>
													<input type="text" name="nom" value="<?=$preinscrit['nomel'];?>" required="">
												</li>

												<li>
													<label>Prénom*</label>
													<input type="text" name="prenom" value="<?=$preinscrit['prenomel'];?>" required="">
													
												</li>
												
												<li>
													<label>Sexe*</label><select type="text" name="sexe" required="">
														<option value="<?=$preinscrit['sexe'];?>"><?=$preinscrit['sexe'];?></option>
														<option value="m">Masculin</option>
														<option value="f">feminin</option>
													</select> 
												</li>

												<li>
													<label>Né le*</label>
													<input type="date" name="daten" value="<?=$preinscrit['naissance'];?>"  required="">
														
												</li>

												<li>
													<label>Lieu de naissance</label>
													<input type="adde" name="adr" value="<?=$preinscrit['adresse'];?>">
														
												</li>

												<li>
													<label>Téléphone*</label>
													<input type="text" name="tel" value="<?=$preinscrit['phone'];?>" required="">
												</li>

												<li>
													<label>Pays*</label>
													<input type="text" name="pays" value="<?=$preinscrit['pays'];?>" required="">
														
												</li>

												<li>

													<label>Nationnalite*</label>
													<input type="text" name="nation" value="<?=$preinscrit['nationnalite'];?>" required="">
														
												</li>

												<li>
													<label>Ecole d'Origine</label>
													<input type="text" name="origine" value="<?=$preinscrit['origine'];?>" >
														
												</li>										

												<li>   
													<label>Mail</label>
													<input type="email" name="email" value="<?=$preinscrit['email'];?>">
												</li>

												<li><label>Photo élève</label>
													<input type="file" name="photo" id="photo" />
													<input type="hidden" value="b" name="env"/>
												</li>
											</ol>
										</fieldset>
										<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Frais à Payer</legend>

											<ol>

												<li>
													<label>Frais d'inscription</label>
													<input style="font-size:25px;" type="text" name="mp" value="<?=$rapport->fraisins($fraisreins,"inscription")['montant'];?>"  required=""/>
												</li>

												<li>
													<label>Remise Inscription</label><input type="text" name="remise" value="0" required="">
												</li>

												<li><label>Dévise</label>
													<select name="devise" required="">
														<option value="gnf">GNF</option>
														<option value="us">$</option>
														<option value="eu">€</option>
														<option value="cfa">CFA</option>
													</select>
												</li>

												<li><label>Taux</label><input type="text" name="taux" value="1"></li>					                            

												<li>
													<label>Type de payement</label><select name="typep" required="" ><?php 

													if (empty($_SESSION['mpaiement'])) {?>

														<option></option><?php

													}else{?>
														<option value="<?=$_SESSION['mpaiement'];?>"><?=$_SESSION['mpaiement'];?></option><?php
													} 
													foreach ($panier->modep as $value) {?>
														<option value="<?=$value;?>"><?=$value;?></option><?php 
													}?></select>
												</li> 

												<li><label>N°Chèque/Bordereau</label><?php 

													if (empty($_SESSION['mpaiement'])) {?>

														<input style="font-size: 20px;" type="text" name="bord"><?php

													}else{?>
														<input style="font-size: 20px;" type="text" name="bord" value="<?=$_SESSION['bordereau'];?>"><?php
													}?>
												</li>

												<li><label>Banque</label><?php 

													if (empty($_SESSION['mpaiement'])) {?>

														<input style="font-size: 20px;" type="text" name="banque"><?php

													}else{?>
														<input style="font-size: 20px;" type="text" name="banque" value="<?=$_SESSION['banque'];?>"><?php
													}?>
												</li>

												<li><label>Compte depôt</label>
													<select  name="compte" required="">
														<option></option><?php
														$type='Banque';

														foreach($panier->nomBanque() as $product){?>

															<option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
														}?>
													</select>
												</li>

												<li>
													<label>Remise Scolarité</label><input type="text" name="remisescol" value="0" required="">
												</li>

												<li><label>Année-Scolaire</label>

													<select type="text" name="annee" required="">
														<option></option><?php
														$annee=date("Y")+1;
														$anneei=date("Y")-1;
														for($i=$anneei;$i<=$annee ;$i++){
															$j=$i+1;?>

															<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

														}?>
													</select>
													
												</li>
											</ol><?php

											if ($products['niveau']>=4) {

												if ($panier->licence()!="expiree" and $panier->cloture()!='cloturer') {?>

													<input type="reset" value="Annuler" name="annuldec" />
													<input  type="submit" value="Valider" name="ajoutel" onclick="return alerteV();" /><?php

												}else{?>

													<div class="alert alert-warning">Les inscriptions sont fermées contacter le chef d'établissement </div>

													<div class="alert alert-warning">OU</div>

													<div class="alert alert-warning">la licence est expirée contacter DAMKO </div><?php

												}
											}?>
										</fieldset>

										<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Activités Extra-Scolaire / Autres</legend>

											<ol>

												<li><label>Activites</label>
													<select name="activites[]"multiple><?php 

														foreach ($panier->activites($_SESSION['promo']) as $value) {?>
															<option value="<?=$value->id;?>"><?=ucfirst($value->nomact);?> Mensualité: <?=number_format($value->mensualite,0,',',' ');?></option><?php 
														}?>
														
													</select>
												</li>

											</ol>

										</fieldset>

									</form><?php

								}else{?>
								
									<form id="formulaire" method="POST" action="ajout_eleve.php" enctype="multipart/form-data" style="display: flex; flex-wrap: wrap;">

										<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Infos Administratives</legend>
											<ol>
												<li>
													<label>Classe</label>
													<select type="text" name="group" onchange="this.form.submit()"><?php 
														if(isset($_POST['group'])){?>
															<option value="<?=$_POST['group'];?>"><?=$_POST['group'];?></option><?php
														}else{?>
															<option></option><?php 
														}
														foreach ($prodgroupe as $form) {?>

															<option value="<?=$form->nomgr;?>"><?=$form->nomgr;?></option><?php

														}?>
													</select><?php

													if (isset($_POST['group'])) {?>															
														<input type="hidden" name="niveau" value="<?=$niveau;?>"/>
														<input type="hidden" name="codef" value="<?=$codef;?>"/><?php
													}?>
												</li>

												<li><label>Justificatifs</label>
													<input type="file" name="just[]"multiple id="photo" />
													<input type="hidden" value="b" name="env"/>
												</li>

											</ol>

											<legend class="text-center bg-success bg-opacity-50">Filiation</legend>
											<ol>

												<li>
													<label>Nom du Père*</label>
													<input type="text" name="nomp" required="">
													
												</li>

												<li>
													<label>Téléphone du Père</label>
													<input type="text" name="telp">
												</li>

												<li>
													<label>Profession du Père</label>
													<input type="text" name="profp">
												</li>
												

												<li>
													<label>Nom de la mère*</label>
													<input type="text" name="nomm" required="">
														
												</li>

												<li>
													<label>Téléphone de la mère</label>
													<input type="text" name="telm">
												</li>

												<li>
													<label>Profession de la Mère</label>
													<input type="text" name="profm">
												</li>

												<li>
													<label>Lieu de travail du Père</label>
													<input type="text" name="lieutp"  maxlength="100">
												</li>

												<li>
													<label>Lieu de travail Mère</label>
													<input type="text" name="lieutm"  maxlength="100">
												</li>

												<li>
													<label>Adresse des Parents</label>
													<input type="text" name="adressep"  maxlength="100">
												</li>

												<li>
													<label>Tuteur </label>
													<input type="text" name="tut">
														
												</li>

												<li>
													<label>Téléphone du Tuteur</label>
													<input type="text" name="telt">
												</li>

												<li>
													<label>Profession du tuteur</label>
													<input type="text" name="proft">
												</li>

												
											</ol>
										</fieldset>

										<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Informations de l'élève</legend>
											<ol>
												<li><label>Matricule</label><input type="text" name="mat" placeholder="<?=$matnew;?>" /></li>

												<li>
													<label>Statut*</label><select type="text" name="statut" required="">
														<option value="admis">Admis</option>
														<option value="redoublant">Redoublant</option>
													</select> 
												</li>

												<li>
													<label>Nom*</label>
													<input type="text" name="nom" required="">
												</li>

												<li>
													<label>Prénom*</label>
													<input type="text" name="prenom" required="">
													
												</li>

												

												<li>
													<label>Sexe*</label><select type="text" name="sexe" required="">
														<option></option>
														<option value="m">Masculin</option>
														<option value="f">feminin</option>
													</select> 
												</li>

												<li>
													<label>Né le*</label>
													<input type="date" name="daten" max="<?=$panier->datemin(2)[0];?>"  required="">
														
												</li>

												<li>
													<label>Lieu de naissance</label>
													<input type="adde" name="adr">
														
												</li>

												<li>
													<label>Téléphone*</label>
													<input type="text" name="tel"  required="">
												</li>

												<li>
													<label>Pays*</label>
													<input type="text" name="pays" value="guinee" required="">
														
												</li>

												<li>

													<label>Nationnalite*</label>
													<input type="text" name="nation" value="guineenne" required="">
														
												</li>

												<li>
													<label>Ecole d'Origine</label>
													<input type="text" name="origine" >
														
												</li>										

												<li>   
													<label>Mail</label>
													<input type="email" name="email">
												</li>

												<li><label>Photo élève</label>
													<input type="file" name="photo" id="photo" />
													<input type="hidden" value="b" name="env"/>
												</li>
											</ol>
										</fieldset>



										<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Frais à Payer</legend>

											<ol>

												<li>
													<label>Frais d'inscription</label>
													<input style="font-size:25px;" type="text" name="mp" value="<?=$rapport->fraisins($fraisreins,"inscription")['montant'];?>"  required=""/>
												</li>

												<li>
													<label>Remise Inscription</label><input type="text" name="remise" value="0" required="">
												</li>

												<li><label>Dévise</label>
													<select name="devise" required="">
														<option value="gnf">GNF</option>
														<option value="us">$</option>
														<option value="eu">€</option>
														<option value="cfa">CFA</option>
													</select>
												</li>

												<li><label>Taux</label><input type="text" name="taux" value="1"></li>					                            

												<li>
													<label>Type de payement</label><select name="typep" required="" ><?php 

													if (empty($_SESSION['mpaiement'])) {?>

														<option value="espèces">espèces</option><?php

													}else{?>
														<option value="<?=$_SESSION['mpaiement'];?>"><?=$_SESSION['mpaiement'];?></option><?php
													} 
													foreach ($panier->modep as $value) {?>
														<option value="<?=$value;?>"><?=$value;?></option><?php 
													}?></select>
												</li> 

												<li><label>N°Chèque/Bordereau</label><?php 

													if (empty($_SESSION['mpaiement'])) {?>

														<input style="font-size: 20px;" type="text" name="bord"><?php

													}else{?>
														<input style="font-size: 20px;" type="text" name="bord" value="<?=$_SESSION['bordereau'];?>"><?php
													}?>
												</li>

												<li><label>Banque</label><?php 

													if (empty($_SESSION['mpaiement'])) {?>

														<input style="font-size: 20px;" type="text" name="banque"><?php

													}else{?>
														<input style="font-size: 20px;" type="text" name="banque" value="<?=$_SESSION['banque'];?>"><?php
													}?>
												</li>

												<li><label>Compte depôt</label>
													<select  name="compte" required=""><?php
														$type='Banque';

														foreach($panier->nomBanque() as $product){?>

															<option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
														}?>
													</select>
												</li>

												<li>
													<label>Remise Scolarité</label><input type="text" name="remisescol" value="0" required="">
												</li>


												<li><label>Année-Scolaire</label>

													<select type="text" name="annee" required="">
														<option value="<?=$_SESSION['promo'];?>"><?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></option><?php
													
														$annee=date("Y")+1;
														$anneei=date("Y")-1;

														for($i=$anneei;$i<=$annee ;$i++){
															$j=$i+1;?>

															<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

														}?>
													</select>
													
												</li>
											</ol><?php

											if ($products['niveau']>=4) {

												if ($panier->licence()!="expiree" and $panier->cloture()!='cloturer') {?>

													<input type="reset" value="Annuler" name="annuldec" />
													<input  type="submit" value="Valider" name="ajoutel" onclick="return alerteV();" /><?php

												}else{?>

													<div class="alert alert-warning">Les inscriptions sont fermées contacter le chef d'établissement </div>

													<div class="alert alert-warning">OU</div>

													<div class="alert alert-warning">la licence est expirée contacter DAMKO </div><?php

												}
											}?>
										</fieldset>

										<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Activités Extra-Scolaire / Autres</legend>

											<ol>

												<li><label>Activites</label>
													<select name="activites[]"multiple><?php 

														foreach ($panier->activites($_SESSION['promo']) as $value) {?>
															<option value="<?=$value->id;?>"><?=ucfirst($value->nomact);?> Mensualité: <?=number_format($value->mensualite,0,',',' ');?></option><?php 
														}?>
														
													</select>
												</li>

											</ol>

										</fieldset>

									</form><?php 
								}
							}
						}

						if (isset($_GET['listeeleve']) or isset($_GET['termec']) or isset($_GET['fiche_eleve']) or isset($_GET['supimg'])  or isset($_POST["ajoutimg"]) or isset($_GET['del_eleve']) or isset($_GET['modif_eleve']) or isset($_POST['modifel']) or isset($_GET['listelsear'])  or isset($_GET['page'])) {

							require 'afficher_eleve.php';
						};?>
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
