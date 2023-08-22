<?php
require 'header.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

    	<div style="display: flex;"><?php

    		if (isset($_GET['note'])) {?>

				<div class="col">
					<fieldset style="margin-top: 30px;"><legend></legend>
					    <div class="choixg"><?php

					    	if ($products['type']=='admin' or $products['type']=='Admistrateur Général' or $products['type']=='comptable' or $products['type']=='informaticien') {?>


						        <div class="optiong">
						            <a href="ajout_eleve.php?ajoute&note">
						            <div class="descript_optiong">Inscription</div></a>
						        </div>

						        <div class="optiong">
						            <a href="ajout_eleve.php?inscript&note">
						            <div class="descript_optiong">Reinscription</div></a>
						        </div>

						        <div class="optiong">
						            <a href="preinscriptiontraite.php">
						            <div class="descript_optiong">Preinscription</div></a>
						        </div><?php 
						    }?>

					        <div class="optiong">
					            <a href="ajout_eleve.php?listeeleve">
					            <div class="descript_optiong">Liste des élèves</div></a>
					        </div>                
					           
					    </div>
					</fieldset>
				</div><?php
			}

			$nb=$DB->querys('SELECT count(id) as id from inscription where etat=? and annee=?', array('inscription', $_SESSION['promo']));

		    	$matnew=date('y') . '000'+($nb['id']+1);
				$matnew='csod'.$matnew;
		    	$matnew=$matnew;

			if (isset($_GET['ajoute']) or isset($_POST['idpreins']) or isset($_POST['codef']) or isset($_GET['niveau']) or isset($_POST['faculte'])) {

				if (isset($_POST['codef'])) {

					$_SESSION['codef']=$_POST['codef'];

					$prodgroupe=$DB->query('SELECT nomgr from groupe where codef=:code and promo=:promo', array('code'=>$_SESSION['codef'], 'promo'=>$_SESSION['promo']));

					$prodniv=$DB->querys('SELECT niveau from formation where codef=:code', array('code'=>$_SESSION['codef']));

				}else{

					$prodgroupe=$DB->query('SELECT nomgr from groupe where promo=:promo', array('promo'=>$_SESSION['promo']));
				}

				if (empty($_SESSION['idpreins'])) {
					$_SESSION['idpreins']=$_POST['idpreins'];
				}

				if (isset($_POST['idpreins'])) {
					$_SESSION['idpreins']=$_POST['idpreins'];
				}				

				$prodpreins=$DB->querys('SELECT *from preinscription where id=? and promopreins=?', array($_SESSION['idpreins'], $_SESSION['promo']));?>

				<div class="col"><?php

					if ($products['type']=='admin' or $products['type']=='Admistrateur Général' or $products['type']=='comptable' or $products['type']=='informaticien') {

						$prodf=$DB->query('SELECT niveau, codef, nomf, classe from formation');?>
							
					    <form id="formulaire" method="POST" action="ajout_elevepreins.php" enctype="multipart/form-data" style="display: flex; flex-wrap: wrap;">

								<fieldset><legend>Infos Administratives</legend>
						    		<ol>
						    			<li>
										    <label>Niveau d'études*</label><?php 
										    if (isset($_POST['codef'])) {?>
											    	
										    	<input type="hidden" name="niveau" value="<?=$prodniv['niveau'];?>"/><?php

										    }?>

										    <select type="text" name="codef" onchange="this.form.submit()" required=""><?php 
										    	if (isset($_POST['codef'])) {?>
											    	
											    	<option value="<?=$_POST['codef'];?>"><?=ucwords($_POST['codef']);?></option><?php

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
											<label>Salle de Classe*</label>
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

									<legend>Filiation</legend>
									<ol>

									  	<li>
											<label>Nom du père*</label>
											<input type="text" name="nomp" value="<?=$prodpreins['pere'];?>" required="">
											   
										</li>

										<li>
											<label>téléphone du père</label>
											<input type="text" name="profp" value="<?=$prodpreins['profpere'];?>">
											   
										</li>										

										<li>
											<label>Nom de la mère*</label>
											<input type="text" name="nomm" value="<?=$prodpreins['mere'];?>" required="">
											    
									  	</li>

									  	<li>
											<label>téléphone de la mère</label>
											<input type="text" name="profm" value="<?=$prodpreins['profmere'];?>">
											   
										</li>

										<li>
											<label>Tuteur* </label>
											<input type="text" name="tut" value="<?=$prodpreins['tuteur'];?>" required>
											    
									  	</li>

									  	<li>
											<label>Téléphone du Tuteur</label>
											<input type="text" name="telt" value="<?=$prodpreins['teltuteur'];?>">
										</li>

									  	
								  	</ol>
								</fieldset>

								<fieldset><legend>Informations de l'Etudiant(e)</legend>
									<ol>
										<li>
										    <label>Nom*</label>
										    <input type="text" name="nom" value="<?=$prodpreins['nomel'];?>" required="">
										</li>

										<li>
											<label>Prénom*</label>
											<input type="text" name="prenom" value="<?=$prodpreins['prenomel'];?>" required="">
											   
									  	</li>

									  	

										<li>
											<label>Sexe*</label><select type="text" name="sexe" required="">
												<option value="<?=$prodpreins['sexe'];?>"><?=$prodpreins['sexe'];?></option>
												<option value="m">Masculin</option>
												<option value="f">feminin</option>
											</select> 
									  	</li>

										<li>
											<label>Né le *</label>
											<input type="date" name="daten" value="<?=$prodpreins['naissance'];?>" required="">
											    
									  	</li>

									  	<li>
											<label>Lieu de naissance</label>
											<input type="adde" name="adr" value="<?=$prodpreins['adresse'];?>">
											    
										</li>

										<li>
											<label>Téléphone*</label>
											<input type="text" name="tel" value="<?=$prodpreins['phone'];?>" required="">
										</li>

										<li>
											<label>Pays*</label>
											<input type="text" name="pays" value="guinee" value="<?=$prodpreins['apys'];?>" required="">
											    
										</li>

									  	<li>

											<label>Nationnalite*</label>
											<input type="text" name="nation" value="guineenne" value="<?=$prodpreins['nationnalite'];?>" required="">
											    
									  	</li>

										<li>   
											<label>Mail</label>
											<input type="email" name="email" value="<?=$prodpreins['email'];?>">
										</li>

									  	<li><label>Photo élève</label>
						                	<input type="file" name="photo" id="photo" />
						                	<input type="hidden" value="b" name="env"/>
						              	</li>

										
									</ol>
								</fieldset>

								<fieldset><legend>Frais à Payer</legend>

							    	<ol><?php 

										if ($panier->etablissement()!='Csp') {?>

								    		<li>
											    <label>Frais d'inscription</label>
											    <input style="font-size:25px;" type="text" name="mp" value="0"  required=""/>
											</li>

											<li>
												<label>Remise Inscription</label><select type="number" name="remise"  required="">
												<option value="0">0%</option><?php
												$i=15;
												while ( $i<= 100) {?>

													<option value="<?=$i;?>"><?=$i;?>%</option><?php

													$i=$i+4;

													$i++;
												}?></select>
				                            </li>

				                            

											<li>
												<label>Type de payement</label><select name="typep" required="" ><?php 

												if (empty($_SESSION['mpaiement'])) {?>

													<option value="différé">différé</option><?php

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
												<label>Remise Scolarité</label><select type="number" name="remisescol"  required="">
												<option value="0">0%</option><?php
												$i=5;
												while ( $i<= 100) {?>

													<option value="<?=$i;?>"><?=$i;?>%</option><?php

													$i=$i+4;

													$i++;
												}?></select>
				                            </li><?php 
				                        }?>


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

											<input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajoutel" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/><?php

										}else{?>

				              				<div class="alertes">Les inscriptions sont fermées contacter le chef d'établissement </div>

				              				<div class="alertes">OU</div>

				              				<div class="alertes">la licence est expirée contacter DAMKO </div><?php

										}
									}?>
								</fieldset>

						</form><?php 
					}?>
				</div>
			</div>
			<div class="col"><?php

				// Ajouter un eleve à la base de données

			    if(isset($_POST['ajoutel'])){

					if($_POST['nom']!="" and $_POST['prenom']!="" and $_POST['daten']!="" and $_POST['codef']!="" and $_POST['annee']!=""){
						
						$nome=addslashes(Htmlspecialchars($_POST['nom']));
						$prenom=addslashes(Htmlspecialchars($_POST['prenom']));
						$daten=addslashes(Htmlspecialchars($_POST['daten']));
						$nomp=addslashes(Htmlspecialchars($_POST['nomp']));
						$nomm=addslashes(Htmlspecialchars($_POST['nomm']));
						$phone=addslashes(Htmlspecialchars($_POST['tel']));
						$adresse=addslashes(Nl2br(Htmlspecialchars($_POST['adr'])));
						$email=addslashes(Nl2br(Htmlspecialchars($_POST['email'])));
						$pays=addslashes(Nl2br(Htmlspecialchars($_POST['pays'])));
						$nation=addslashes(Nl2br(Htmlspecialchars($_POST['nation'])));
						$sexe=addslashes(Nl2br(Htmlspecialchars($_POST['sexe'])));
						$telp=addslashes(Nl2br(Htmlspecialchars($_POST['profp'])));
						$telm=addslashes(Nl2br(Htmlspecialchars($_POST['profm'])));
						$tuteur=addslashes(Nl2br(Htmlspecialchars($_POST['tut'])));
						$telt=addslashes(Nl2br(Htmlspecialchars($_POST['telt'])));
						$remise=addslashes(Nl2br(Htmlspecialchars($_POST['remise'])));
						$niveau=addslashes(Nl2br(Htmlspecialchars($_POST['niveau'])));
						$remisescol=addslashes(Nl2br(Htmlspecialchars($_POST['remisescol'])));
						$bordereau=addslashes(Nl2br(Htmlspecialchars($_POST['bord'])));
						$banque=addslashes(Nl2br(Htmlspecialchars($_POST['banque'])));

						$codef=$_POST['codef'];
						$annee=$_POST['annee'];
						$groupe=$_POST['group'];				
						


						$nb=$DB->querys('SELECT count(id) as id from inscription where etat=? and annee=?', array('inscription', $_SESSION['promo']));

						if (!empty($_POST['mat'])) {

							$matricule=$_POST['mat'];
							$initiale='';

						}else{

							$matricule=date('y') . '000'+($nb['id']+1);
							$initiale='csp';
						}
							
						
						$pseudo=$prenom[0].$nome.$matricule;
						$mdp=$prenom[0].$nome;
						
				

						$verifel=$DB->querys('SELECT nomel from eleve where nomel=:nom and prenomel=:prenom and naissance=:naiss and pere=:pere and mere=:mere ', array(
							'nom'=>$nome,
							'prenom'=>$prenom,
							'naiss'=>$daten,
							'pere'=>$nomp,
							'mere'=>$nomm
						));
						$etat="non inscrit";

						if(!empty($verifel)){?>

							<div class="alertes">C'est élève est déjà enregistré</div><?php

						}else{
							//Upload photo

							if(isset($_POST["env"])){

					            $logo=$_FILES['photo']['name'];

					            if($logo!=""){

					              require "uploadImage.php";

					              require "uploadpdf.php";
					             
					            }
					        }



							$DB->insert('INSERT INTO eleve(matricule, nomel, prenomel, sexe, naissance, pere, mere, telpere, telmere,  pays, nationnalite, adresse, dateenreg) values( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array(($initiale.$matricule), $nome, $prenom, $sexe, $daten, $nomp, $nomm, $telp, $telm, $pays, $nation, $adresse));

							/*		Ajouter le num dans le login    */

							$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array(($initiale.$matricule), strtolower($pseudo) , strtolower($mdp), 'eleve', '1'));

							$DB->insert('INSERT INTO contact(matricule, phone, email) values(?, ?, ?)', array(($initiale.$matricule), strtolower($phone) , strtolower($email)));


							$nb=$DB->querys('SELECT max(id_tut) as id from tuteur');

							$matuteur=$matricule;

							$matuteur='tut'.$initiale.$matuteur;

							$pseudo=$matuteur;
							$mdp=$matuteur;

							if (empty($_POST['tut'])) {

								$DB->insert('INSERT INTO tuteur(matuteur, matricule, nomtut, teltut) values(?, ?, ?, ?)', array($matuteur, ($initiale.$matricule), $nomp, $telp));

							}else{

								$DB->insert('INSERT INTO tuteur(matuteur, matricule, nomtut, teltut) values(?, ?, ?, ?)', array($matuteur, ($initiale.$matricule), $tuteur, $telt));
							}

							/*		Ajouter le num dans le login    */

							$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array($matuteur, strtolower($pseudo) , strtolower($mdp), 'tuteur', 1));
							



							$verifins=$DB->querys('SELECT codef from inscription where codef=:code and annee=:annee and matricule=:mat', array(
								'code'=>$codef,
								'annee'=>$annee,
								'mat'=>$matricule
							));

							if(!empty($verifins)){?>
								<div class="alertes">Il est déjà inscrit à cette formation</div><?php

							}else{

								$DB->insert('INSERT INTO inscription(matricule, codef, niveau, nomgr, etat, remise, annee) values( ?, ?, ?, ?, ?, ?, ?)', array(($initiale.$matricule), $codef, $niveau, $groupe, 'inscription', $remisescol, $annee));

								$maxid = $DB->querys('SELECT max(id) as id FROM payement');
                        
                    			$numpaye=$maxid['id']+1;

                    			$montant=$_POST['mp']*(1-($remise/100));

								$DB->insert('INSERT INTO payement(numpaye, matricule, montant, remise, motif, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($numpaye, ($initiale.$matricule), $montant, $remise, 'inscription', $_POST['typep'], $bordereau, $banque, $annee));

								if ($_POST['mp']!=0) {

									$DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, now())', array($_POST['compte'], $montant, 'paiement frais inscription', 'depins'.$numpaye, ($initiale.$matricule), $annee));
								}
							}
							 $DB->delete('DELETE FROM preinscription WHERE id = ?', array($_SESSION['idpreins']));
?>	
							<div class="alerteV">Elève inscrit avec succèe!!!</div>

							<div class="alerteV"><a href="ajout_eleve.php?fiche_eleve=<?=$matricule;?>&promo=<?=$_SESSION['promo'];?>">Voir la fiche élève</a></div><?php

							$_SESSION['matricule']=($initiale.$matricule);
							$_SESSION['etat']="reussi";
							$etat=$_SESSION['etat'];

						}

					}else{?>	

						<div class="alertes">Remplissez les champs vides</div><?php
					}
				}?>
			</div>
			<?php
		}?>

		</div><?php

		if (isset($_GET['inscript']) or isset($_GET['searchel']) or isset($_GET['inscriptfic']) or isset($_GET['niveaur']) ) {

			require 'inscription.php';
		}

		if (isset($_GET['listeeleve']) or isset($_GET['termec']) or isset($_GET['fiche_eleve']) or isset($_GET['supimg'])  or isset($_POST["ajoutimg"]) or isset($_GET['del_eleve']) or isset($_GET['modif_eleve']) or isset($_POST['modifel']) or isset($_GET['listelsear']) or (isset($_POST['ajoutel'])  and $etat=="reussi" or isset($_GET['page']))) {

			require 'afficher_eleve.php';
		}
	}
}?>
</div>		

							

		



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
