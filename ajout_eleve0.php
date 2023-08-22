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

	    		<div class="row">

					<div class="col-sm-12 col-md-2 pb-3 bg-danger bg-opacity-50"> <?php

						if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire' or $products['type']=='bibliothecaire') {?>

							<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="preinscription.php">Formulaire Pré-inscription</a></div></div>

							<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="preinscriptiontraite.php">Pré-inscription</a></div></div>

							<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="ajout_eleve.php?ajoute&note">Inscription</a></div></div><?php 
							if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire') {?>

								<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="ajout_eleve.php?inscript&note">Réinscription</a></div></div><?php 
							}
						}?>

						<div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="ajout_eleve.php?listeeleve">Liste des élèves</a></div></div>
					</div>

					<div class="col-sm-12 col-md-10"><?php

						$nb=$DB->querys('SELECT count(id) as id from matricule where etat=? and annee=?', array('inscription', $_SESSION['promo']));

				    	$matnew=date('y') . '000'+($nb['id']+1);
						$matnew=$rapport->init['0'].$matnew;
				    	$matnew=$matnew;

						if (isset($_GET['ajoute']) or isset($_POST['ajoutel']) or isset($_GET['niveau']) or isset($_POST['codef'])) {

							if (isset($_POST['codef'])) {

								$_SESSION['codef']=$_POST['codef'];

								$prodgroupe=$DB->query('SELECT nomgr from groupe where codef=:code and promo=:promo', array('code'=>$_SESSION['codef'], 'promo'=>$_SESSION['promo']));

								$prodniv=$DB->querys('SELECT niveau from formation where codef=:code', array('code'=>$_SESSION['codef']));
							}else{

								$prodgroupe=$DB->query('SELECT nomgr from groupe where promo=:promo', array('promo'=>$_SESSION['promo']));
							}

							if (!empty($_SESSION['niveauf'])) {

					    		$prodf=$DB->query('SELECT codef, nomf, classe from formation where niveau=:niv', array('niv'=>$_SESSION['niveauf']));

					    	}else{

					    		$prodf=$DB->query('SELECT niveau, codef, nomf, classe from formation');
					    	}
							// Ajouter un eleve à la base de données

						    if(isset($_POST['ajoutel'])){

								if($_POST['nom']!="" and $_POST['prenom']!="" and $_POST['daten']!="" and $_POST['codef']!="" and $_POST['annee']!=""){
							
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
									$remise=$panier->h($_POST['remise']);
									$niveau=$panier->h($_POST['niveau']);
									$remisescol=$panier->h($_POST['remisescol']);
									//$bordereau=$panier->h($_POST['bord']);
									//$banque=$panier->h($_POST['banque']);
									$origine=$panier->h($_POST['origine']);
									$profm=$panier->h($_POST['profm']);
									$profp=$panier->h($_POST['profp']);
									$proft=$panier->h($_POST['proft']);

									$codef=$panier->h($_POST['codef']);
									$annee=$panier->h($_POST['annee']);
									$groupe=$panier->h($_POST['group']);
									//$typep=$panier->h($_POST['typep']);
									//$compte=$panier->h($_POST['compte']);
									//$devise=$panier->h($_POST['devise']);
									//$taux=$panier->h($_POST['taux']);
									$activites=$_POST['activites'];


									$nb=$DB->querys('SELECT count(id) as id from matricule where etat=? and annee=?', array('inscription', $_SESSION['promo']));

									if (!empty($_POST['mat'])) {

										$matricule=$_POST['mat'];
										$initiale='';

									}else{

										$anneeins=(new dateTime($_SESSION['promo']))->format("y");

										$matricule=$anneeins . '000'+($nb['id']+1);
										$initiale=$rapport->init['0'];
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



										$DB->insert('INSERT INTO eleve(matricule, nomel, prenomel, sexe, naissance, pere, mere, telpere, telmere, profp, profm, origine, pays, nationnalite, adresse, dateenreg) values( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array(($initiale.$matricule), $nome, $prenom, $sexe, $daten, $nomp, $nomm, $telp, $telm, $profp, $profm, $origine, $pays, $nation, $adresse));


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

											$DB->insert('INSERT INTO inscription(matricule, codef, niveau, nomgr, etat, remise, annee) values( ?, ?, ?, ?, ?, ?, ?)', array(($initiale.$matricule), $codef, $niveau, $groupe, 'inscription', $remisescol, $annee));

											$DB->insert('INSERT INTO matricule(matricule, etat, annee) values( ?, ?, ?)', array(($initiale.$matricule),'inscription', $annee));


											/*
											$maxid = $DB->querys('SELECT max(id) as id FROM payement');
			                        
			                    			$numpaye=$maxid['id']+1;

			                    			$montant=$_POST['mp']*(1-($remise/100));

											$DB->insert('INSERT INTO payement(numpaye, matricule, montant, devise, taux, remise, motif, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, ($initiale.$matricule), $montant, $devise, $taux, $remise, 'inscription', $typep, $bordereau, $banque, $annee));

											if ($_POST['mp']!=0) {

												$DB->insert('INSERT INTO banque (id_banque, montant, devise, taux, libelles, numero, matriculeb, personnel, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $montant, $devise, $taux, 'paiement frais inscription', 'depins'.$numpaye, ($initiale.$matricule), $_SESSION['idpseudo'], $annee));
											}
											*/
										}

										foreach ($activites as $valueact) {

											$mens=$DB->querys("SELECT mensualite FROM activites WHERE id='{$valueact}' and promoact='{$annee}' ");
											$mensualite=$mens['mensualite'];
											$idact=$panier->h($valueact);
											
											$DB->insert('INSERT INTO inscriptactivites (idact, matinscrit, mensualite, promoact,  dateop) VALUES(?, ?, ?, ?, now())', array($valueact, $initiale.$matricule, $mensualite, $annee));
										}?>	

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

							if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire' or $products['type']=='bibliothecaire') {

								if (isset($_POST['ajoutel'])) {
									if (isset($_POST['bord'])) {
						                $_SESSION['bordereau']=$_POST['bord'];
						                $_SESSION['banque']=$_POST['banque'];
						                $_SESSION['mpaiement']=$_POST['typep'];
						                $_SESSION['typer']='';
						            }
					            }

					            if (isset($_POST['codef'])) {

					            	$seuil=$rapport->seuilClasse($_POST['codef'], $_SESSION['promo']);

					            	if ($seuil<10) {?>
					            		<div class="alert alert-warning">Il reste <?=$seuil ;?> élève(s) à inscrire pour ce niveau</div><?php
					            	}else{?>
					            		<div class="alert alert-warning">Il reste <?=$seuil ;?> élève(s) à inscrire pour ce niveau</div><?php

					            	}


					            	if ($rapport->codefSuivant($_POST['codef'])[4]=='creche') {
										$fraisreins='inscreche';
									}elseif ($rapport->codefSuivant($_POST['codef'])[4]=='maternelle') {
										$fraisreins='insmaternelle';
									}elseif ($rapport->codefSuivant($_POST['codef'])[4]=='primaire') {
										$fraisreins='insprimaire';
									}elseif ($rapport->codefSuivant($_POST['codef'])[4]=='college') {
										$fraisreins='inscollege';
									}elseif ($rapport->codefSuivant($_POST['codef'])[4]=='lycee') {
										$fraisreins='inslycee';
									}else{
										$fraisreins='ins';
									}		            	

					            }else{
					            	$fraisreins='ins';
					            }?>
								
						    	<form id="formulaire" method="POST" action="ajout_eleve.php" enctype="multipart/form-data" style="display: flex; flex-wrap: wrap;">

									<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Infos Administratives</legend>
							    		<ol>

							    						    				
								            <li>

											    <label>Niveau</label><?php

											    if (isset($_POST['codef'])) {?>
												    	
											    	<input type="hidden" name="niveau" value="<?=$prodniv['niveau'];?>"/><?php

											    }?>

											    <select type="text" name="codef" required="" onchange="this.form.submit()"><?php

												    if (isset($_POST['codef'])) {?>
												    	
												    	<option value="<?=$_POST['codef'];?>"><?=$_POST['codef'];?></option><?php

												    }else{?>
												    	
												    	<option></option><?php

												    }
											    	foreach ($prodf as $form) {

											    		if ($form->classe=='1') {?>

															<option value="<?=$form->codef;?>"><?=$form->classe.' ère année '.$form->nomf;?></option><?php

														}elseif ($form->classe=='petite section' or $form->classe=='moyenne section' or $form->classe=='grande section' or $form->classe=='terminale') {?>

								                            <option value="<?=$form->codef;?>"><?=' '.$form->classe.' '.$form->nomf;?></option><?php
								                        }else{?>
																<option value="<?=$form->codef;?>"><?=$form->classe.'ème année '.$form->nomf;?></option><?php
														}

											    	}?>
												</select>
											</li>

											<li>
												<label>Classe</label>
												<select type="text" name="group">
											    	<option></option><?php
											    	foreach ($prodgroupe as $form) {?>

														<option value="<?=$form->nomgr;?>"><?=$form->nomgr;?></option><?php

											    	}?>
											    </select>
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
												<input type="date" name="daten" required="">
												    
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
									</fieldset><?php 

									if ($rapport->init['0']!='gsb') {?>



										<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Frais à Payer</legend>

									    	<ol>

									    		<li>
												    <label>Frais d'inscription</label>
												    <input style="font-size:25px;" type="text" name="mp" value="<?=$rapport->fraisins[$fraisreins];?>"  required=""/>
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
											</ol>
										</fieldset><?php 
									}?>

									<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Activités Extra-Scolaire / Autres</legend>

										<ol>

											<li><label>Activites</label>
												<select name="activites[]"multiple><?php 

													foreach ($panier->activites($_SESSION['promo']) as $value) {?>
														<option value="<?=$value->id;?>"><?=ucfirst($value->nomact);?> Mensualité: <?=number_format($value->mensualite,0,',',' ');?></option><?php 
													}?>
													
												</select>
											</li>

											<li>
												<label>Remise Inscription</label><input type="text" name="remise" value="0" required="">
				                            </li>

											<li>
												<label>Remise Scolarité</label><input type="text" name="remisescol" value="0" required="">
				                            </li>

				                            

											<li><label>Année-Scolaire</label>

									            <select type="text" name="annee" required="">
									            	<option value="<?=$_SESSION['promo'];?>"><?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></option><?php
									              
										            $annee=date("Y")+1;

										            for($i=2020;$i<=$annee ;$i++){
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

								</form><?php 
							}
						}

						if (isset($_GET['inscript']) or isset($_GET['searchel']) or isset($_GET['inscriptfic']) or isset($_GET['niveaur']) ) {

							require 'inscription.php';
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
