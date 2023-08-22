<?php

if (isset($_GET['enseignant'])) {
	require 'headerenseignant.php';
}else{
	require 'headerv2.php';
}?>

<div class="container-fluid">

	<div class="row"><?php 
		require "navpreinscris.php";?>

		<div class="col-sm-12 col-md-10"><?php

			if(isset($_POST['ajouteins'])){

				if($_POST['group']!="" and $_POST['annee']!=""){
					$groupe=$panier->h($_POST['group']);
					$annee=$panier->h($_POST['annee']);
					$codef=$panier->classeInfos($groupe,$annee)[0];
					$cursus=$panier->classeInfos($groupe,$annee)[2];
					$matricule=$panier->h($_POST['searchmat']);
					$remise=$panier->h($_POST['remise']);
					$remisescol=$panier->h($_POST['remisescol']);
					$bordereau=$panier->h($_POST['bord']);
					$banque=$panier->h($_POST['banque']);
					$devise=$panier->h($_POST['devise']);
					$taux=$panier->h($_POST['taux']);			
					$compte=$panier->h($_POST['compte']);					

					$nb=$DB->querys('SELECT codef from inscription where annee=:annee and matricule=:mat', array(
						'annee'=>$annee,
						'mat'=>$matricule
					));

					if(!empty($nb)){?>
						<div class="alert alert-danger">Elève déjà inscrit à cette formation</div><?php

					}else{

						$DB->insert('INSERT INTO inscription(matricule, codef, niveau, nomgr, etat, remise, annee) values( ?, ?, ?, ?, ?, ?, ?)', array($matricule, $codef, $cursus, $groupe, 'reinscription', $remisescol, $annee));

						$maxid = $DB->querys('SELECT max(id) as id FROM payement');
								
						$numpaye=$maxid['id']+1;

						$montant=$_POST['mp']*(1-($remise/100));

						$DB->insert('INSERT INTO payement(caisse, numpaye, matricule, montant, devise, taux, remise, motif, typepaye, numpaie, banque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())',array($compte, $numpaye, $matricule, $montant, $devise, $taux, $remise, 'reinscription', $_POST['typep'], $bordereau, $banque, $annee));

						if (!empty($_POST['mp'])) {

							$DB->insert('INSERT INTO banque (id_banque, montant, devise, taux, personnel, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $montant, $devise, $taux, $_SESSION['idpseudo'], 'paiement frais reinscription', 'depreins'.$numpaye, $matricule, $annee));
						}

						if (!empty($_POST["activites"])) {
							$activites=$_POST['activites'];

							foreach ($activites as $valueact) {

								$mens=$DB->querys("SELECT mensualite FROM activites WHERE id='{$valueact}' and promoact='{$annee}' ");
								$mensualite=$mens['mensualite'];
								$idact=$panier->h($valueact);
								
								$DB->insert('INSERT INTO inscriptactivites (idact, matinscrit, mensualite, promoact,  dateop) VALUES(?, ?, ?, ?, now())', array($valueact, $initiale.$matricule, $mensualite, $annee));
							}
						}?>

						<div class="alert alert-success">Reinscription reussie !!!</div><?php

						//require ('fiche_elevegen.php');
					}

				}else{?>	

					<div class="alert alert-warning">Remplissez les champs vides</div><?php
				}
			}

			if (!empty($_SESSION['niveauf'])) {

				$prodf=$DB->query('SELECT codef, nomf, classe from formation where niveau=:niv', array('niv'=>$_SESSION['niveauf']));

				$prodgroupe=$DB->query('SELECT nomgr from groupe where promo=:promo and niveau=:niv order by(niveau)', array('promo'=>$_SESSION['promo'], 'niv'=>$_SESSION['niveauf']));
				
			}else{		

				$prodf=$DB->query('SELECT codef, nomf, classe from formation');

				$prodgroupe=$DB->query('SELECT nomgr from groupe where promo=:promo order by(niveau)', array('promo'=>$_SESSION['promo']));
			}
				

			if ((isset($_POST['searchmat']) or isset($_GET['searchel'])) and !isset($_POST['ajouteins'])){

				if (isset($_POST['searchmat'])) {
					$matsearch=$_POST['searchmat'];
				}else{

					$matsearch=$_GET['searchel'];

				}

				$prodeleve=$DB->querys('SELECT eleve.matricule as matricule, nomel, prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, codef, nomgr from eleve inner join inscription on inscription.matricule=eleve.matricule where eleve.matricule=:mat and annee=:promo', array('mat'=>$matsearch, 'promo'=>($_SESSION['promo']-1)));

				$prodfok=$DB->query("SELECT codef, nomf, classe from formation ");

				$prodclasseok=$DB->query("SELECT *from groupe where promo='{$_SESSION['promo']}'");

				//$prodfok=$DB->query("SELECT codef, nomf, classe from formation where niveau='{$rapport->codefSuivant($prodeleve['codef'])[2]}' ");

				//$prodclasseok=$DB->query("SELECT *from groupe where promo='{$_SESSION['promo']}' and codef='{$rapport->codefSuivant($prodeleve['codef'])[1]}' ");

				

				$seuil=$rapport->seuilClasse($rapport->codefSuivant($prodeleve['codef'])[1], $_SESSION['promo']);

				if ($seuil<10) {?>
					<div class="alertes" style="font-size: 25px; color: red; width: 80%;">Il reste <?=$seuil ;?> élève(s) à inscrire pour ce niveau</div><?php
				}else{?>
					<div class="alerteV" style="font-size: 25px; color: green; width: 80%;">Il reste <?=$seuil ;?> élève(s) à inscrire pour ce niveau</div><?php

				}
			}else{
				$prodeleve=$DB->querys('SELECT eleve.matricule as matricule, nomel, prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, codef, nomgr from eleve inner join inscription on inscription.matricule=eleve.matricule where eleve.matricule=:mat and annee=:promo', array('mat'=>1, 'promo'=>($_SESSION['promo']-1)));
			}

			if ($rapport->codefSuivant($prodeleve['codef'])[2]=='creche') {
				$fraisreins=1;
			}elseif ($rapport->codefSuivant($prodeleve['codef'])[2]=='maternelle') {
				$fraisreins=2;
			}elseif ($rapport->codefSuivant($prodeleve['codef'])[2]=='primaire') {
				$fraisreins=3;
			}elseif ($rapport->codefSuivant($prodeleve['codef'])[2]=='college') {
				$fraisreins=4;
			}elseif ($rapport->codefSuivant($prodeleve['codef'])[2]=='lycee') {
				$fraisreins=5;
			}else{
				$fraisreins=0;
			}?>

			<form id="formulaire" method="POST" action="inscription.php?inscript" style="margin-top:2px;">

				<fieldset><legend style="font-size:20px;">Reinscription<?php
				if (isset($_POST['ajouteins'])) {
					$_SESSION['bordereau']=$_POST['bord'];
					$_SESSION['banque']=$_POST['banque'];
					$_SESSION['mpaiement']=$_POST['typep'];
					$_SESSION['typer']='';
				}

					if ((isset($_POST['searchmat']) or isset($_GET['searchel'])) and !isset($_POST['ajouteins'])) {
						echo " de ".strtoupper($prodeleve['nomel'])." ".ucfirst($prodeleve['prenomel'])." né(e) le ".$prodeleve['naissance']." Matricule N°: ".$prodeleve['matricule']." Classe ".$prodeleve['nomgr'];
					}?></legend>

					<ol>
						<li><label>Matricule*</label><?php
							if (isset($_POST['searchmat'])) {
								$searchmat=$_POST['searchmat'];
							}
							if (isset($_GET['searchel'])) {
								$searchmat=$_GET['searchel'];
								unset($_SESSION['searchreinscript']);//Pour vider la session dans la recherche des eleves
							}

							if (isset($_GET['inscriptfic'])) {
								$searchmat=$_GET['inscriptfic'];
							}

							if (isset($_POST['searchmat']) or isset($_GET['searchel']) or isset($_GET['inscriptfic'])) {?>
								<input type="text" name="searchmat"  value="<?=$searchmat;?>" onchange="this.form.submit()" /><?php
							}else{?>

								<input type="text" name="searchmat" required="" onchange="this.form.submit()" /><?php
							}?>
							<a class="btn btn-success" href="ajout_eleve.php?listelsear">Recherchez un élève</a>
						</li>

						<li>
							<label>Classe*</label>
							<select type="text" name="group" required="">
								<option></option><?php
								foreach ($prodclasseok as $form) {?>

									<option value="<?=$form->nomgr;?>"><?=$form->nomgr;?></option><?php

								}?>
							</select>
						</li>

						<li>
							<label>Frais d'inscription*</label>
							<input style="font-size: 25px;" type="text" name="mp" value="<?=$rapport->fraisins($fraisreins,"reinscription")['montant'];?>" required=""/>
						</li>

						<li><label>Dévise*</label>
							<select name="devise" required="">
								<option value="gnf">GNF</option>
								<option value="us">$</option>
								<option value="eu">€</option>
								<option value="cfa">CFA</option>
							</select>
						</li>

						<li><label>Taux</label><input type="text" name="taux" value="1"></li>

						<li>
							<label>Remise Inscription*</label><input style="font-size: 20px;" type="text" name="remise" value="0"  required=""/>
						</li>

						<li>
							<label>Type de payement*</label><select name="typep" required="" ><?php 

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

						<li><label>Compte depôt*</label>
							<select  name="compte" required="">
								<option></option><?php
								$type='Banque';

								foreach($panier->nomBanque() as $product){?>

									<option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
								}?>
							</select>
						</li>

						<li>
							<label>Remise Scolarite*</label><input style="font-size: 20px;" type="text" name="remisescol" value="0" required="" />
							
						</li>			

						<li><label>Activites</label>
							<select name="activites[]"multiple><?php 

								foreach ($panier->activites($_SESSION['promo']) as $value) {?>
									<option value="<?=$value->id;?>"><?=ucfirst($value->nomact);?> Mensualité: <?=number_format($value->mensualite,0,',',' ');?></option><?php 
								}?>
								
							</select>
						</li>

						<li><label>Année-Scolaire*</label>

							<select type="text" name="annee" required="">
								<option value="<?=$_SESSION['promo'];?>"><?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></option><?php
							
								$annee=date("Y")+1;

								for($i=2020;$i<=$annee ;$i++){
									$j=$i+1;?>

									<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

								}?>
							</select>
							
						</li>
					</ol>

				</fieldset><?php

					if ($products['niveau']>3) {

						if ($panier->licence()!="expiree" and $panier->cloture()!='cloturer') {?>

							<input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajouteins" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/><?php

						}else{?>

							<div class="alert alert-warning">Les inscriptions sont fermées contacter le chef d'établissement </div>

							<div class="alert alert-warning">OU</div>

							<div class="alert alert-warning">la licence est expirée contacter DAMKO </div><?php

						}
					}?>
					
				</fieldset>
			</form>
		</div>
	</div>
</div>

