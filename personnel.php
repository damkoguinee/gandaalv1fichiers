<?php
require 'header.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{

    	if (!isset($_GET['effnav'])) {

    		require 'navformation.php';
    	}

		if (isset($_GET['ajout_en'])) {

			$form=$DB->query('SELECT *from matiere ');?>

			<div class="col">
			
			    <form id="formulaire" method="POST" action="enseignant.php" style="width: 60%;">

			    	<fieldset><legend>Ajouter un enseignant</legend>
			    		<ol>
			    			<li>
								<label>Fonction du personnels</label><select type="text" name="perso" required="">
									<option></option>
									<option value="enseignant">Enseignant</option>
									<option value="Admistrateur Général">Administrateur Général</option>
									<option value="secrétaire">Secrétaire</option>
									<option value="Directeur du primaire">Directeur du primaire</option>									
									<option value="coordinatrice maternelle">Coordinatrice Maternelle</option>
									<option value="proviseur">Proviseur</option>
									<option value="DE/Censeur">Directeur des etudes/Censeur</option>
									<option value="CPE">CPE</option>
									<option value="admin reseau">Admin Réseau</option>
									<option value="admin bd">Admin BD</option>
									<option value="Conseille a l'éducation">Conseiller à l'éducation</option>

									<option value="bibliothecaire">Bibliothécaire</option>
									<option value="comptable">Comptable</option>
									
									<option value="surveillant Général">Surveillant Général</option>

									<option value="électricien">Electricien</option>

									<option value="vigile">Vigile</option>

									<option value="conseiller pédogogique">Conseiller Pédagogique</option>

									<option value="informaticien">Informaticien</option>

									<option value="cuisinier">Cuisinier</option>

									<option value="hygieniste">Hygièniste</option>
								</select> 
						  	</li>

						  	<li>
								<label>N°Matricule</label>
								<input type="text" name="matr">
							</li>

							<li>
								<label>Nom</label>
								<input type="text" name="nom" required="">
							</li>

							<li>
								<label>Prénom</label>
								<input type="text" name="prenom" required=""> 
						  	</li>

						  	<li>
								<label>Sexe</label><select type="text" name="sexe" required="">
									<option></option>
									<option value="m">Masculin</option>
									<option value="f">feminin</option>
								</select> 
						  	</li>

							<li>
							    <label>Téléphone</label>
							    <input type="text" name="tel" >
							</li>

							<li>
								<label>Mail</label>
								<input type="email" name="email">  
							</li>

							<li>
							    <label>Salaire</label>
							    <input type="text" name="salaire" style="width: 100px;"> ou Taux Horaire <input type="text" name="thoraire" style="width: 100px;">
							</li>

							<li>
							    <label>Sécurité Sociale</label>
							    <input type="text" name="ss" style="width: 100px;">
							</li>

							<li>
								<label>Niveau</label>
								<select type="number" name="niv[]"multiple required="">
									<option></option><?php

								$prodf=$DB->query('SELECT *from cursus order by(id)');

                                foreach ($prodf as $value) {?>

							    	<option value="<?=$value->nom;?>"><?=ucwords($value->nom);?></option><?php
								}?></select>
							</li>

							<li>
								<label>Autorisation</label>
								<select type="number" name="auto" required="">
									<option value="1">Niveau 1</option>
									<option value="2">Niveau 2</option>
									<option value="3">Niveau 3</option>
									<option value="4">Niveau 4</option>
									<option value="5">Niveau 5</option>
								</select>  
							</li>

					  	</ol>

					</fieldset>

					<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajouteen" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
				</form>
			</div><?php
		}

		if(isset($_POST['ajouteen'])){?>
				
			<div class="col"><?php

				if($_POST['nom']!="" and $_POST['prenom']!=""  and $_POST['perso']!=""){
					
					$nom=addslashes(Htmlspecialchars($_POST['nom']));
					$prenom=addslashes(Htmlspecialchars($_POST['prenom']));
					$phone=addslashes(Htmlspecialchars($_POST['tel']));
					$email=addslashes(Nl2br(Htmlspecialchars($_POST['email'])));
					$sexe=addslashes(Nl2br(Htmlspecialchars($_POST['sexe'])));
					$type=addslashes(Nl2br(Htmlspecialchars($_POST['perso'])));
					$niveau=$_POST['niv'];
					$auto=addslashes(Nl2br(Htmlspecialchars($_POST['auto'])));
					$salaire=addslashes(Nl2br(Htmlspecialchars($_POST['salaire'])));
					$thoraire=addslashes(Nl2br(Htmlspecialchars($_POST['thoraire'])));
					$ss=addslashes(Nl2br(Htmlspecialchars($_POST['ss'])));

					if (empty($_POST['salaire'])) {
						$salaire=0;
					}

					if (empty($_POST['thoraire'])) {
						$thoraire=0;
					}

					if ($_POST['perso']=='enseignant') {			

						$nb=$DB->querys('SELECT id from enseignant where nomen=:nom and prenomen=:prenom', array(
						'nom'=>$nom,
						'prenom'=>$prenom
						));

						if(!empty($nb)){?>
							<div class="alertes">C'est enseignant est déjà enregistré</div><?php
						}else{

							$nb=$DB->querys('SELECT max(id) as id from enseignant');

							if (!empty($_POST['matr'])) {

								$matricule=$_POST['matr'];
								$matricule=$matricule;
							}else{

								$matricule=$nb['id']+1;
								$matricule='cspe'.$matricule;
							}
							$pseudo=$prenom[0].$nom.$matricule[4];
							$mdp=$matricule;

							$DB->insert('INSERT INTO enseignant(matricule, nomen, prenomen, sexe, dateenreg) values(?, ?, ?, ?, now())', array($matricule, $nom, $prenom, $sexe));

							/*		Ajouter le num dans le login    */

							$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array($matricule, strtolower($pseudo) , strtolower($mdp), $type, $auto));

							$DB->insert('INSERT INTO contact(matricule, phone, email) values(?, ?, ?)', array($matricule, strtolower($phone) , strtolower($email)));

							$DB->insert('INSERT INTO salaireens(numpers, salaire, thoraire, promo) values(?, ?, ?, ?)', array($matricule, $salaire, $thoraire, strtolower($_SESSION['promo'])));

							if (!empty($_POST['ss'])) {

								$DB->insert('INSERT INTO ssocialens(numpers, montant) values(?, ?)', array($matricule, $ss));
							}else{

								$DB->insert('INSERT INTO ssocialens(numpers, montant) values(?, ?)', array($matricule, 0));

							}


							foreach ($niveau as $value) {								

								$DB->insert('INSERT INTO niveau(matricule, nom) values(?, ?)', array($matricule, $value));
							}



							if ($niveau=='college' or $niveau='lycee') {
								
								$DB->insert('INSERT INTO niveauc(matricule, nom) values(?, ?)', array($matricule, 'secondaire'));
							}else {
								
								$DB->insert('INSERT INTO niveauc(matricule, nom) values(?, ?)', array($matricule, $niveau));
							}?>	

							<div class="alerteV">Enseignant ajouté avec succée!!!</div><?php
						}

					}else{			

						$nb=$DB->querys('SELECT nom from personnel where nom=:nom and prenom=:prenom', array(
						'nom'=>$nom,
						'prenom'=>$prenom
						));

						if(!empty($nb)){?>
							<div class="alertes">C'est enseignant est déjà enregistré</div><?php
						}else{

							$nb=$DB->querys('SELECT max(id) as id from personnel');
							
							if (!empty($_POST['matr'])) {

								$matricule=$_POST['matr'];
								$matricule=$matricule;
							}else{

								$matricule=$nb['id']+1;
								$matricule='cspp'.$matricule;
							}
							$pseudo=$prenom[0].$nom.$matricule[4];
							$mdp=$matricule;

							$DB->insert('INSERT INTO personnel(numpers, nom, prenom, sexe, dateenreg) values(?, ?, ?, ?, now())', array($matricule, $nom, $prenom, $sexe));

							/*		Ajouter le num dans le login    */

							$DB->insert('INSERT INTO login(matricule, pseudo, mdp, type, niveau) values(?, ?, ?, ?, ?)', array($matricule, strtolower($pseudo) , strtolower($mdp), $type, $auto));

							$DB->insert('INSERT INTO contact(matricule, phone, email) values(?, ?, ?)', array($matricule, strtolower($phone) , strtolower($email)));

							$DB->insert('INSERT INTO salairepers(numpers, salaire, promo) values(?, ?, ?)', array($matricule, strtolower($salaire) , strtolower($_SESSION['promo'])));

							if (!empty($_POST['ss'])) {

								$DB->insert('INSERT INTO ssocialpers(numpers, montant) values(?, ?)', array($matricule, $ss));
							}else{

								$DB->insert('INSERT INTO ssocialpers(numpers, montant) values(?, ?)', array($matricule, 0));

							}

							foreach ($niveau as $value) {								

								$DB->insert('INSERT INTO niveau(matricule, nom) values(?, ?)', array($matricule, $value));								
							}?>	

							<div class="alerteV">Personnel ajouté avec succée!!!</div><?php
						}
					}

				}else{?>	

					<div class="alertes">Remplissez les champs vides</div><?php
				}?>

				</div><?php
			}


		//Modifier un enseignant

		if (isset($_GET['modif_en'])) {?>

			<div class="col">
			
			    <form id="formulaire" method="POST" action="enseignant.php" style="width: 60%;">

			    	<fieldset><legend>Modifier un personnel</legend>
			    		<ol><?php
			    			if (isset($_GET['type'])) {

								$prodm=$DB->querys('SELECT  *from enseignant inner join contact on enseignant.matricule=contact.matricule inner join login on login.matricule=contact.matricule inner join salaireens on salaireens.numpers=enseignant.matricule inner join ssocialens on ssocialens.numpers=enseignant.matricule  where enseignant.matricule=:mat', array('mat'=>$_GET['modif_en']));?>

			    				<input type="hidden" name="perso" value="enseignant"/><?php

			    			}else{

								$prodm=$DB->querys('SELECT personnel.numpers as matricule, nom as nomen, prenom as prenomen, type, sexe, phone, email, niveau, pseudo, mdp, salaire, montant from personnel inner join contact on numpers=contact.matricule inner join login on login.matricule=contact.matricule inner join salairepers on salairepers.numpers=personnel.numpers inner join ssocialpers on ssocialpers.numpers=personnel.numpers where personnel.numpers=:mat', array('mat'=>$_GET['modif_en']));?>

			    				<li>

									<label>Fonction du Personnels</label><select type="text" name="perso">
										<option><?=$prodm['type'];?></option>
									<option value="enseignant">Enseignant</option>
									<option value="Admistrateur Général">Administrateur Général</option>
									<option value="secrétaire">Secrétaire</option>
									<option value="Directeur du primaire">Directeur du primaire</option>									
									<option value="coordinatrice maternelle">Coordinatrice Maternelle</option>
									<option value="proviseur">Proviseur</option>
									<option value="DE/Censeur">Directeur des etudes/Censeur</option>
									<option value="CPE">CPE</option>
									<option value="admin reseau">Admin Réseau</option>
									<option value="admin bd">Admin BD</option>
									<option value="Conseille à l'éducation">Conseiller à l'éducation</option>
									<option value="bibliothecaire">Bibliothécaire</option>
									<option value="comptable">Comptable</option>
									
									<option value="surveillant Général">Surveillant Général</option>

									<option value="électricien">Electricien</option>

									<option value="vigile">Vigile</option>

									<option value="conseiller pédogogique">Conseiller Pédagogique</option>

									<option value="informaticien">Informaticien</option>

									<option value="cuisinier">Cuisinier</option>

									<option value="hygieniste">Hygièniste</option>
									</select>
								</li><?php
							}?> 
						  	

							<li>
								<label>Nom</label>
								<input type="text" name="nom" value="<?=$prodm['nomen'];?>"/>

								<input type="hidden" name="mat" value="<?=$prodm['matricule'];?>"/>
							</li>

							<li>
								<label>Prénom</label>
								<input type="text" name="prenom" value="<?=$prodm['prenomen'];?>"/> 
						  	</li>

						  	<li>
								<label>Sexe</label><select type="text" name="sexe">
									<option><?=$prodm['sexe'];?></option>
									<option value="m">Masculin</option>
									<option value="f">feminin</option>
								</select> 
						  	</li>

							<li>
							    <label>Téléphone</label>
							    <input type="text" name="tel"  value="<?=$prodm['phone'];?>"/>
							</li>

							<li>							  	
								<label>Mail</label>
								<input type="text" name="email" value="<?=$prodm['email'];?>"/>  
							</li>

							<li>
							    <label>Salaire</label>
							    <input type="text" name="salaire" value="<?=$prodm['salaire'];?>" style="width: 100px;"> ou Taux Horaire <input type="text" name="thoraire" value="<?php if(!empty($prodm['thoraire'])){echo $prodm['thoraire'];}?>" style="width: 100px;">
							</li>

							<li>
							    <label>Sécurité Sociale</label>
							    <input type="text" name="ss" value="<?=$prodm['montant'];?>">
							</li>

							<li>
								<label>Niveau</label>
								<select type="number" name="niv[]"multiple>
									<option></option><?php

								$prodf=$DB->query('SELECT *from cursus order by(id)');

                                foreach ($prodf as $value) {?>

							    	<option value="<?=$value->nom;?>"><?=ucwords($value->nom);?></option><?php
								}?></select>
							</li>

							<li>
								<label>Autorisation</label>
								<select type="number" name="auto" required="">
									<option value="<?=$prodm['niveau'];?>">Niveau <?=$prodm['niveau'];?></option>
									<option value="1">Niveau 1</option>
									<option value="2">Niveau 2</option>
									<option value="3">Niveau 3</option>
									<option value="4">Niveau 4</option>
									<option value="5">Niveau 5</option>
								</select>  
							</li>

							<li>
							    <label>Pseudo</label>
							    <input type="text" name="pseudo"  value="<?=$prodm['pseudo'];?>"/>
							</li>

							<li>							  	
								<label>Mot de passe</label>
								<input type="text" name="mdp" value="<?=$prodm['mdp'];?>"/>  
							</li>

					  	</ol>

					</fieldset>

					<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Modifier" name="modifen" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
				</form>
			</div><?php
		}

		if(isset($_POST['modifen'])){
				
			$nom=addslashes(Htmlspecialchars($_POST['nom']));
			$prenom=addslashes(Htmlspecialchars($_POST['prenom']));
			$phone=addslashes(Htmlspecialchars($_POST['tel']));
			$email=addslashes(Nl2br(Htmlspecialchars($_POST['email'])));
			$sexe=addslashes(Nl2br(Htmlspecialchars($_POST['sexe'])));
			$type=addslashes(Nl2br(Htmlspecialchars($_POST['perso'])));
			
			$auto=addslashes(Nl2br(Htmlspecialchars($_POST['auto'])));

			$pseudo=addslashes(Nl2br(Htmlspecialchars($_POST['pseudo'])));
			$mdp=addslashes(Nl2br(Htmlspecialchars($_POST['mdp'])));
			$salaire=addslashes(Nl2br(Htmlspecialchars($_POST['salaire'])));
			$thoraire=addslashes(Nl2br(Htmlspecialchars($_POST['thoraire'])));

			$ss=addslashes(Nl2br(Htmlspecialchars($_POST['ss'])));

			if (empty($_POST['salaire'])) {
				$salaire=0;
			}

			if (empty($_POST['thoraire'])) {
				$thoraire=0;
			}

			if ($type=='enseignant') {			

				$DB->insert('UPDATE enseignant SET nomen = ?, prenomen=?, sexe=? WHERE matricule = ?', array($nom, $prenom, $sexe, $_POST['mat']));

				$DB->insert('UPDATE salaireens SET salaire=?, thoraire=? WHERE numpers = ?', array($salaire, $thoraire, $_POST['mat']));

				$DB->insert('UPDATE ssocialens SET montant=? WHERE numpers = ?', array($ss, $_POST['mat']));

			}else{

				$DB->insert('UPDATE personnel SET nom = ?, prenom=?, sexe=? WHERE numpers = ?', array($nom, $prenom, $sexe, $_POST['mat']));

				$DB->insert('UPDATE salairepers SET salaire=? WHERE numpers = ?', array($salaire, $_POST['mat']));

				$DB->insert('UPDATE ssocialpers SET montant=? WHERE numpers = ?', array($ss, $_POST['mat']));

			}

				/*		Modifier le num dans le contact    */

			$DB->insert('UPDATE contact SET phone = ?, email=? WHERE matricule = ?', array($phone, strtolower($email), $_POST['mat']));

			$DB->insert('UPDATE login SET pseudo=?, mdp=?, type = ?, niveau=? WHERE matricule = ?', array($pseudo, $mdp, $type, $auto, $_POST['mat']));

			if (!empty($_POST['niv'])) {
				
				$niveau=$_POST['niv'];
				foreach ($niveau as $value) {

					$DB->insert('UPDATE niveau SET nom=? WHERE matricule = ?', array($value, $_POST['mat']));							

					//$DB->insert('INSERT INTO niveau(matricule, nom) values(?, ?)', array($_POST['mat'], $value));
				}
			}?>	

			<div class="alerteV"> Modification effectuée avec succée!!!</div><?php
			
		}

		// fin modification

	    if (isset($_GET['enseig']) or isset($_POST['ajouteen']) or isset($_GET['termec'])  or isset($_GET['termep']) or isset($_GET['del_en']) or isset($_GET['del_pers']) or isset($_POST['modifen']) or isset($_GET['matiereen']) or isset($_GET['personnel']) or isset($_GET['payempcherc']) or isset($_GET['livrens'])) {

	    	if (isset($_GET['del_en'])) {

	          $DB->delete('DELETE FROM enseignant WHERE matricule = ?', array($_GET['del_en']));
	          $DB->delete('DELETE FROM contact WHERE matricule = ?', array($_GET['del_en']));
	          $DB->delete('DELETE FROM login WHERE matricule = ?', array($_GET['del_en']));

	          $DB->delete('DELETE FROM salaireens WHERE numpers = ?', array($_GET['del_en']));

	          $DB->delete('DELETE FROM ssocialens WHERE numpers = ?', array($_GET['del_en']));
	          
	          $DB->delete('DELETE FROM niveauc WHERE matricule = ?', array($_GET['del_en']));

	          $DB->delete('DELETE FROM niveau WHERE matricule = ?', array($_GET['del_en']));?>

	          <div class="alerteV">Suppression reussie!!!</div><?php 
	        }

	        if (isset($_GET['del_pers'])) {

	          $DB->delete('DELETE FROM personnel WHERE numpers = ?', array($_GET['del_pers']));
	          $DB->delete('DELETE FROM contact WHERE matricule = ?', array($_GET['del_pers']));
	          $DB->delete('DELETE FROM login WHERE matricule = ?', array($_GET['del_pers']));

	          $DB->delete('DELETE FROM salairepers WHERE numpers = ?', array($_GET['del_en']));

	          $DB->delete('DELETE FROM ssocialens WHERE numpers = ?', array($_GET['del_en']));

	          $DB->delete('DELETE FROM niveau WHERE matricule = ?', array($_GET['del_en']));?>

	          	<div class="alerteV">Suppression reussie!!!</div><?php 
	        }

	        if (!isset($_GET['personnel'])) {

	        	if (isset($_GET['termec'])) {
			      $_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
			      $terme = $_GET['termec'];
			      $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
			      $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
			      $terme = strtolower($terme);

			      $prodm =$DB->query('SELECT *from enseignant inner join contact on enseignant.matricule=contact.matricule inner join login on enseignant.matricule=login.matricule inner join salaireens on salaireens.numpers=enseignant.matricule WHERE enseignant.matricule LIKE? or nomen LIKE ? or prenomen LIKE ? or phone LIKE ? order by(prenomen)',array("%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
			      
			    }elseif(!empty($_SESSION['niveauf'])) {

		    		$prodm=$DB->query('SELECT enseignant.matricule as matricule, nomen, prenomen, phone, thoraire, salaire, pseudo, mdp from enseignant inner join contact on enseignant.matricule=contact.matricule inner join login on enseignant.matricule=login.matricule inner join salaireens on salaireens.numpers=enseignant.matricule inner join niveau on enseignant.matricule=niveau.matricule where nom=:niv and promo=:promo order by(prenomen)', array('niv'=>$_SESSION['niveauf'], 'promo'=>$_SESSION['promo']));

		    	}else{

		    		$prodm=$DB->query('SELECT  *from enseignant inner join contact on enseignant.matricule=contact.matricule inner join login on enseignant.matricule=login.matricule inner join salaireens on salaireens.numpers=enseignant.matricule where promo=:promo order by(prenomen)', array('promo'=>$_SESSION['promo']));
		    	}?>

	    		<div class="col" style="width: 100%;">
		    
			    	<table class="payement" style="width: 100%;">
			    		<thead>
			    			<form method="GET" action="enseignant.php" id="suitec" name="termc">
			    				<tr>
			                    	<th colspan="7" class="info" style="text-align: center">Liste des Enseignants <?=ucwords($_SESSION['niveaufl']);?>

			                    		<a style="margin-left: 10px;"href="printdoc.php?enseig&niveau=<?=$_SESSION['niveaufl'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

			                    		<a style="margin-left: 10px;"href="csv.php?enseignant" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>

			                    		<input id="reccode" style="width: 250px;" type = "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">

			                    		<input   type = "hidden" name = "effnav" value = "search">

					            	</th>
				                  	<th><input   type = "submit" name = "s" value = "search"></th>

			                    	<?php
								
									if ($products['niveau']>4) {?>

										<th colspan="3"><a href="enseignant.php?ajout_en" style="color: white;">Ajouter un enseignant</a></th><?php

									}?>
			                    	
			                  </tr>
							</form>

							<tr>
								<th></th>
								<th height="30">N°M</th>
								<th>Prénom & Nom</th>
								<th>Phone</th>
								<th>Matière</th>
								<th>Salaire</th>
								<th>T. Horaire</th>
								<th></th><?php

								if ($products['niveau']>4) {?>
									<th>Login</th>
									<th>Password</th>

									<th colspan="2"></th><?php

								}?>
							</tr>

						</thead>

						<tbody><?php
						if (empty($prodm)) {
							# code...
						}else{

							$totsalaire=0;
							foreach ($prodm as $key=> $formation) {

								$totsalaire+=$formation->salaire;

								$value=$DB->querys('SELECT  *from enseignement inner join matiere on matiere.codem=enseignement.codem where codens=:code order by (nomgr)', array('code'=>$formation->matricule));?>

								<tr>
									<td style="text-align: center;"><?=$key+1;?></td>
									<td style="text-align: center; font-size: 14px;"><?php
										if (isset($_GET['payempcherc'])) {?>
											
											<a href="comptabilite.php?payecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
										}elseif (isset($_GET['livrens'])) {?>
											
											<a href="emprunterlivre.php?enseig&payecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
										}else{?>

											<a href="comptabilite.php?horairecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
										}?>
									</td>

									<td><?=ucwords(strtolower($formation->prenomen)).' '.strtoupper($formation->nomen);?></td>

			                        <td><?=$formation->phone;?></td>

			                        <td><?=ucwords($value['nommat']);?></td>

			                        <td style="text-align: right;"><?=number_format($formation->salaire,0,',',' ');?></td>

			                        <td style="text-align: right;"><?=number_format($formation->thoraire,0,',',' ');?></td>

			                        <td>
			                        	<a href="enseignant.php?voir_elens=<?=$formation->matricule;?>"><input type="button" value="<?=$_SESSION['typeel'];?>" style="width: 48%; font-size: 16px; cursor: pointer"></a>

			                        	<a href="enseignement.php?voir_mate=<?=$formation->matricule;?>"><input type="button" value="+infos" style="width: 48%; font-size: 16px; cursor: pointer; background-color: orange; color: white;"></a>
			                        </td><?php

									if ($products['niveau']>4) {?>

										<td style="font-size: 14px;"><?=$formation->pseudo;?></td>
										<td style="font-size: 14px;"><?=$formation->mdp;?></td>

										<td colspan="2" style="width: 20%;">
											<a href="enseignant.php?modif_en=<?=$formation->matricule;?>&type=<?="enseignant";?>"><input type="button" value="Modifier" style="width: 45%; font-size: 16px; background-color: orange; color: white; cursor: pointer"></a>

			                        		<a href="enseignant.php?del_en=<?=$formation->matricule;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 50%; font-size: 16px; background-color: red; color: white; cursor: pointer"></a>
			                        	</td><?php

									}?>

								</tr><?php
							}
						}?>

							
						</tbody>
						<tfoot>
							<tr>
								<th height="30" colspan="4"></th>
								<th><?=number_format($totsalaire,0,',',' ');?></th>
							</tr>
						</tfoot>
					</table>
				</div><?php

			}else{

				if (isset($_GET['termep'])) {
			      $_GET["termep"] = htmlspecialchars($_GET["termep"]); //pour sécuriser le formulaire contre les failles html
			      $terme = $_GET['termep'];
			      $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
			      $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
			      $terme = strtolower($terme);

			      $prodm =$DB->query('SELECT *from personnel inner join contact on personnel.numpers=contact.matricule inner join salairepers on salairepers.numpers=personnel.numpers WHERE personnel.nom LIKE ? or prenom LIKE ? or phone LIKE ?',array("%".$terme."%", "%".$terme."%", "%".$terme."%"));
			      
			    }elseif (!empty($_SESSION['niveauf'])) {

		    		$prodm=$DB->query('SELECT personnel.numpers as matricule, personnel.nom as nom, prenom, type, phone, pseudo, mdp, salaire from personnel inner join contact on personnel.numpers=contact.matricule inner join salairepers on salairepers.numpers=personnel.numpers inner join niveau on personnel.numpers=niveau.matricule where niveau.nom=:niv and promo=:promo', array('niv'=>$_SESSION['niveauf'], 'promo'=>$_SESSION['promo']));

		    	}else{

		    		$prodm=$DB->query('SELECT  personnel.numpers as matricule, personnel.nom as nom, prenom, type, phone, pseudo, mdp, salaire from personnel inner join contact on personnel.numpers=contact.matricule inner join salairepers on salairepers.numpers=personnel.numpers where promo=:promo', array('promo'=>$_SESSION['promo']));
		    	}?>

				<div class="col">
		    
			    	<table class="payement" style="width: 100%;">

			    		<thead>
			    			<form method="GET" action="enseignant.php" id="suitec" name="termp">
		    				<tr>
		                    	<th colspan="4" class="info" style="text-align: center">Liste du personnels <?=ucwords($_SESSION['niveaufl']);?>
		                    		<a style="margin-left: 10px;"href="printdoc.php?perso" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>
		                    		<a style="margin-left: 10px;"href="csv.php?persodirec" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>

		                    		<input id="reccode" style="width: 250px;" type = "search" name = "termep" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">

			                    		<input   type = "hidden" name = "effnav" value = "search">
			                    		<input   type = "hidden" name = "personnel" value = "search">

					            </th>
				                  <th><input   type = "submit" name = "s" value = "search"></th><?php
							
								if ($products['niveau']>3) {?>

									<th colspan="5"><a href="enseignant.php?ajout_en" style="color: white;">Ajouter un personnel</a></th><?php

								}?>
		                    	
		                  	</tr>
		                  </form>

							<tr>
								<th></th>
								<th height="30">N°M</th>
								<th>Prénom & Nom</th>
								<th>Fonction</th>
								<th>Phone</th>
								<th>Salaire</th><?php

								if ($products['niveau']>3) {?>
									<th>Identifiant</th>
									<th>Mdp</th>

									<th colspan="2"></th><?php

								}?>
							</tr>

						</thead>

						<tbody><?php

							if (empty($prodm)) {
								# code...
							}else{
								$totsalaire=0;
								foreach ($prodm as $key=> $formation) {



									$totsalaire+=$formation->salaire;?>

									<tr>
										<td style="text-align: center;"><?=$key+1;?></td>
										<td style="font-size: 16px;"><?php
											if (isset($_GET['payempcherc'])) {?>
												
												<a href="payementpersonnel0.php?payecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
											}elseif (isset($_GET['livrepers'])) {?>
												
												<a href="emprunterlivre.php?personnel&payecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
											}else{?>

												<a href="comptabilite.php?horairecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
											}?>
										</td>

										<td><?=ucwords(strtolower($formation->prenom)).' '.strtoupper($formation->nom);?></td>

										<td><?=ucfirst($formation->type);?></td>

				                        <td><?=$formation->phone;?></td>

				                        <td style="text-align: right;"><?=number_format($formation->salaire,0,',',' ');?></td><?php

										if ($products['niveau']>3) {?>

											<td><?=$formation->pseudo;?></td>
											<td><?=$formation->mdp;?></td>

											<td colspan="2">
												<a href="enseignant.php?modif_en=<?=$formation->matricule;?>"><input type="button" value="Modifier" style="width: 45%; font-size: 16px; background-color: orange; color: white; cursor: pointer"></a>

				                        		<a href="enseignant.php?del_pers=<?=$formation->matricule;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 50%; font-size: 16px; background-color: red; color: white; cursor: pointer"></a>
				                        	</td><?php

										}?>

									</tr><?php
								}
							}?>

						
						</tbody>
						<tfoot>
							<tr>
								<th height="30" colspan="5"></th>
								<th><?=number_format($totsalaire,0,',',' ');?></th>
							</tr>
						</tfoot>
					</table>
				</div><?php
			}
		}

		// affichage des eleves de l'enseignants


		if (isset($_GET['voir_elens'])) {?>

			<div class="col">

				<table class="payement" style="width: 50%;">
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
								  		<a href="ajout_eleve.php?fiche_eleve=<?=$eleve->matricule;?>&promo=<?=$_SESSION['promo'];?>" class="btn btn-info"><input type="button" value="+infos" style="width: 98%; font-size: 16px;  cursor: pointer"></a>
								  	</td>

								</tr><?php
							}
						}
						?>
				</tbody>
			</table><?php
		}
	}?>
	</div><?php

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