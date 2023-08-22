<?php
require 'headerv2.php';
if (isset($_SESSION['pseudo'])) {
				
	if ($products['niveau']<1) {?>

		<div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

	}else{?>

		<div class="container-fluid p-0">
			<div class="row m-0"><?php 
				require 'navformation.php'; ?>
				<div class="col-sm-12 col-md-10 p-0" style="overflow:auto;"><?php
					if (isset($_GET['ajout_eta'])) {

						$form=$DB->query('SELECT *from matiere ');?>
					
						<form id="formulaire" method="POST" action="etablissement.php">

							<fieldset><legend>Ajouter un établissement</legend>
								<ol>
									<li>
										<label>INITIAL*</label>
										<input type="text" name="init" required="">
									</li>
									<li>
										<label>TAUX HORAIRE*</label>
										<input type="number" name="thoraire" required="">
									</li>

									<li>
										<label>IRE*</label>
										<input type="text" name="ire" required="">
									</li>

									<li>
										<label>DPE/DCE*</label>
										<input type="text" name="dpe" required="">
									</li>

									<li>
										<label>Code école*</label>
										<input type="text" name="nume" required="">
									</li>

									<li>
										<label>Nom de l'établissement*</label>
										<input type="text" name="nom" required="">
									</li>

									<li>
										<label>Téléphone*</label>
										<input type="text" name="tel"  required="">
									</li>

									<li>
										<label>Mail*</label>
										<input type="email" name="email">  
									</li>

									<li>
										<label>Adresse*</label>
										<input type="adde" name="adr" required="">
									</li>

									<li><label>Pays*</label>
										<input type="text" name="pays" required="">
									</li>

									<li><label>Région</label>
										<input type="text" name="region">
									</li>

									<li><label>Secteur</label>
										<input type="text" name="sect">
									</li>

									<li><label>Dévise</label>
										<input type="text" name="dev">
									</li>

									<li><label>Compte Banque</label>
										<input type="text" name="banque">
									</li>

								</ol>

							</fieldset>

							<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajouteet" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
						</form><?php
					}

					if(isset($_POST['ajouteet'])){

						if($_POST['nom']!="" and $_POST['tel']!="" and $_POST['adr']!="" And $_POST['pays']!=""){

							$init=$panier->h($_POST['init']);
							$thoraire=$panier->h($_POST['thoraire']);
							$ire=$panier->h($_POST['ire']);
							$dpe=$panier->h($_POST['dpe']);							
							$nom=addslashes(Htmlspecialchars($_POST['nom']));
							$nume=addslashes(Htmlspecialchars($_POST['nume']));
							$phone=addslashes(Htmlspecialchars($_POST['tel']));
							$adresse=addslashes(Nl2br(Htmlspecialchars($_POST['adr'])));
							$email=addslashes(Nl2br(Htmlspecialchars($_POST['email'])));
							$pays=addslashes(Nl2br(Htmlspecialchars($_POST['pays'])));
							$region=addslashes(Nl2br(Htmlspecialchars($_POST['region'])));
							$sect=addslashes(Nl2br(Htmlspecialchars($_POST['sect'])));
							$dev=addslashes(Nl2br(Htmlspecialchars($_POST['dev'])));
							$banque=addslashes(Nl2br(Htmlspecialchars($_POST['banque'])));

										

							$nb=$DB->querys('SELECT id from etablissement');

							if(!empty($nb)){

								$DB->insert('UPDATE etablissement SET initial=?, thoraire=?, ire=?, dpe=?, nom = ?, numero=?, phone=?, email=?, adresse=?, pays=?, region=?, secteur=?, devise=?, cbanque=?', array($init, $thoraire, $ire, $dpe, $nom, $nume, $phone, $email, $adresse, $pays, $region, $sect, $dev, $banque));

							}else{

								$DB->insert('INSERT INTO etablissement(initial, thoraire, ire, dpe, nom, numero, phone, email, adresse, pays, region, secteur, devise, cbanque) values(?, ?, ?, ?, ?, ?, ?, ?,?,? ?, ?, ?, ?)', array($init, $thoraire, $ire, $dpe, $nom, $nume, $phone, $email, $adresse, $pays, $region, $sect, $dev, $banque));
							}?>	

							<div class="alert alert-success">Etablissement ajouté avec succée!!!</div><?php

						}else{?>	

							<div class="alert alert-warning">Remplissez les champs vides</div><?php
						}
					}


					//Modifier un enseignant

					if (isset($_GET['modif_et'])) {?>
			
						<form id="formulaire" method="POST" action="etablissement.php"><?php

							$prodm=$DB->querys('SELECT  *from etablissement');?>

							<fieldset><legend>Modifier l'établissement</legend>
								<ol>
									<li>
										<label>TAUX HORAIRE</label>
										<input type="text" name="thoraire" value="<?=$prodm['thoraire'];?>">
									</li>
									<li>
										<label>IRE</label>
										<input type="text" name="ire" value="<?=$prodm['ire'];?>">
									</li>

									<li>
										<label>DPE/DCE</label>
										<input type="text" name="dpe" value="<?=$prodm['dpe'];?>">
									</li>

									<li>
										<label>Nom de l'établissement</label>
										<input type="text" name="nom" value="<?=$prodm['nom'];?>">
									</li>

									<li><label>Code école</label>
										<input type="text" name="nume" value="<?=$prodm['numero'];?>">
									</li>

									<li>
										<label>Téléphone</label>
										<input type="text" name="tel"  value="<?=$prodm['phone'];?>">
									</li>

									<li>
										<label>Mail</label>
										<input type="email" name="email" value="<?=$prodm['email'];?>">  
									</li>

									<li>
										<label>Adresse</label>
										<input type="adde" name="adr" value="<?=$prodm['adresse'];?>">
									</li>

									<li><label>Pays</label>
										<input type="text" name="pays" value="<?=$prodm['pays'];?>">
									</li>

									<li><label>Région</label>
										<input type="text" name="region" value="<?=$prodm['region'];?>">
									</li>

									<li><label>Secteur</label>
										<input type="text" name="sect" value="<?=$prodm['secteur'];?>">
									</li>

									<li><label>Dévise</label>
										<input type="text" name="dev" value="<?=$prodm['devise'];?>">
									</li>

									<li><label>Compte Banque</label>
										<input type="text" name="banque" value="<?=$prodm['cbanque'];?>">
									</li>

								</ol>

							</fieldset>

							<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Modifier" name="modifet" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
						</form><?php
					}

					if(isset($_POST['modifet'])){
						$thoraire=$panier->h($_POST['thoraire']);
						$ire=$panier->h($_POST['ire']);
						$dpe=$panier->h($_POST['dpe']);				
						$nom=addslashes(Htmlspecialchars($_POST['nom']));
						$nume=addslashes(Htmlspecialchars($_POST['nume']));
						$phone=addslashes(Htmlspecialchars($_POST['tel']));
						$adresse=addslashes(Nl2br(Htmlspecialchars($_POST['adr'])));
						$email=addslashes(Nl2br(Htmlspecialchars($_POST['email'])));
						$pays=addslashes(Nl2br(Htmlspecialchars($_POST['pays'])));
						$region=addslashes(Nl2br(Htmlspecialchars($_POST['region'])));
						$sect=addslashes(Nl2br(Htmlspecialchars($_POST['sect'])));
						$dev=addslashes(Nl2br(Htmlspecialchars($_POST['dev'])));
						$banque=addslashes(Nl2br(Htmlspecialchars($_POST['banque'])));

						$DB->insert('UPDATE etablissement SET thoraire=?, ire=?, dpe=?, nom = ?, numero=?, phone=?, email=?, adresse=?, pays=?, region=?, secteur=?, devise=?, cbanque=?', array($thoraire,$ire, $dpe, $nom, $nume, $phone, $email, $adresse, $pays, $region, $sect, $dev, $banque));?>	

						<div class="alert alert-success"> Modification effectuée avec succée!!!</div><?php
						
					}

					// fin modification

	    			if (isset($_GET['etab']) or isset($_POST['ajouteet'])  or isset($_POST['modifet'])) {

	    				$prodm=$DB->query('SELECT  *from etablissement');?>
						<table class="payement">
							<thead>
								<tr><?php
								
									if ($products['niveau']>3) {?>

										<th colspan="5"><a href="etablissement.php?ajout_eta" style="color: white;">Ajouter un établissement</a></th><?php

									}?>			                    	
								</tr>

								<tr>
									<th height="30">Nom de l'établissement</th>
									<th>Adresse</th>
									<th>Phone</th>
									<th>Email</th><?php

									if ($products['niveau']>3) {?>

										<th></th><?php

									}?>
								</tr>

							</thead>

							<tbody><?php
								if (empty($prodm)) {
									# code...
								}else{

									foreach ($prodm as $formation) {?>

										<tr>

											<td><?=ucfirst($formation->nom);?></td>

											<td><?=$formation->adresse;?></td>

											<td><?=$formation->phone;?></td>

											<td><?=$formation->email;?></td><?php

											if ($products['niveau']>3) {?>

												<td>
													<a href="etablissement.php?modif_et=<?=$formation->id;?>"><input type="button" value="Modifier" style="width: 95%; font-size: 16px; background-color: orange; color: white; cursor: pointer"></a>
													
												</td><?php

											}?>

										</tr><?php
									}
								}?>
							</tbody>
						</table><?php
					}


					if (isset($_GET['cloturer']) or isset($_GET['supcloture']) or isset($_POST['vcloture'])) {?>
					
						<form id="formulaire" method="POST" action="etablissement.php">

							<fieldset><legend>Bloquer la saisie</legend>
								<ol>

									<li>
										<label>Choississez</label>
										<select type="text" name="nom" required="">
											<option></option><?php

											if ($prodtype['type']=='semestre') {?>

												<option value="1">Clôturer le 1er Semestre</option>
												<option value="2">Clôturer le 2ème Semestre</option><?php

											}else{?>
												<option value="1">Clôturer le 1er Trimestre</option>
												<option value="2">Clôturer le 2ème Trimestre</option>
												<option value="3">Clôturer le 3ème Trimestre</option><?php

											}?>
											<option value="inscript">Clôturer les inscriptions</option>
											
										</select>
									</li>

								</ol>

							</fieldset>

							<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Clôturer" name="vcloture" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
						</form>

						<fieldset style="height: 215px; margin-top: 10px; background: #384313; "><legend style="color: white; font-size: 25px; font-weight: bold; padding-top: 15px; ">Autoriser la saisie pour le(s) </legend>

							<div class="row" style="margin-top: 50px;"><?php

								if ($prodtype=='semestre') {?>

									<a href="etablissement.php?supcloture=<?=1;?>" onclick="return alerteV();"><input type="button" value="1er Semestre" style="width: 98%; height: 50px; font-size: 16px; font-weight: bold; cursor: pointer"></a>

									<a href="etablissement.php?supcloture=<?=2;?>" onclick="return alerteV();"><input type="button" value="2ème Semestre" style="width: 98%; height: 50px; font-size: 16px; font-weight: bold; cursor: pointer"></a><?php

								}else{?>

									<a href="etablissement.php?supcloture=<?=1;?>" onclick="return alerteV();"><input type="button" value="1er Trimestre" style="width: 98%; height: 50px; font-size: 16px; font-weight: bold; cursor: pointer"></a>

									<a href="etablissement.php?supcloture=<?=2;?>" onclick="return alerteV();"><input type="button" value="2ème Trimestre" style="width: 98%; height: 50px; font-size: 16px; font-weight: bold; cursor: pointer"></a>

									<a href="etablissement.php?supcloture=<?=3;?>" onclick="return alerteV();"><input type="button" value="3ème Trimestre" style="width: 98%; height: 50px; font-size: 16px; font-weight: bold; cursor: pointer"></a><?php

								}?>

								

								<a href="etablissement.php?supcloture=<?='inscript';?>" onclick="return alerteV();"><input type="button" value="Inscription" style="width: 98%; height: 50px; font-size: 16px; font-weight: bold; cursor: pointer"></a></a>

							</div>

						</fieldset><?php
					}


					if(isset($_POST['vcloture'])){

						if($_POST['nom']!=""){
							
							$nom=addslashes(Htmlspecialchars($_POST['nom']));

							$nb=$DB->querys('SELECT id from cloture where nomcloture=:nom', array('nom'=>$nom));

							if(!empty($nb)){?>	

								<div class="alert alert-success">Déjà clotuer!!!</div><?php

							}else{

								$DB->insert('INSERT INTO cloture(nomcloture,  promo, date_cloture) values(?, ?, now())', array($nom, $_SESSION['promo']));?>	

								<div class="alert alert-success">Clôture effectuée avec succèe!!!</div><?php
							}

						}
					}

					if(isset($_GET['supcloture'])){

						$DB->delete('DELETE FROM cloture WHERE nomcloture = ? and promo=?', array($_GET['supcloture'], $_SESSION['promo']));?>	

						<div class="alert alert-success">Autorisation effectuée avec succèe!!!</div><?php

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