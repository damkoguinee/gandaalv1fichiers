<?php

if (isset($_GET['enseignant'])) {
	require 'headerenseignant.php';
}else{
	require 'headerv2.php';
}

if (isset($_SESSION['pseudo'])) {
    
	if ($_SESSION['niveaupers']<4) {?>

		<div class="alert alert-danger">Des autorisations sont requises pour consulter cette page</div><?php

	}else{?>

		<div class="container-fluid">

			<div class="row"><?php 
				require "navpreinscris.php";?>
				<div class="col-sm-12 col-md-10"><?php

					$nb=$DB->querys('SELECT count(id) as id from matricule where etat=? and annee=?', array('inscription', $_SESSION['promo']));

					$matnew=(date('y') . '000')+($nb['id']+1);
					$matnew=$rapport->infoEtablissement()["initial"].$matnew;
					$matnew=$matnew;
					if (isset($_POST['group'])) {
						$codef=$panier->classeInfos($_POST['group'], $_SESSION['promo'])[0];						
						$niveau=$panier->classeInfos($_POST['group'], $_SESSION['promo'])[2];						
					}

					$prodgroupe=$DB->query("SELECT nomgr from groupe where promo='{$_SESSION['promo']}'");
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
							
							$niveau=$panier->h($_POST['niveau']);

							$origine=$panier->h($_POST['origine']);
							$profm=$panier->h($_POST['profm']);
							$profp=$panier->h($_POST['profp']);
							$proft=$panier->h($_POST['proft']);

							$codef=$panier->h($_POST['codef']);
							$annee=$panier->h($_POST['annee']);
							$groupe=$panier->h($_POST['group']);

							$nb=$DB->querys('SELECT count(id) as id from matricule where etat=? and annee=?', array('inscription', $annee));

							if (!empty($_POST['mat'])) {

								$matricule=$_POST['mat'];
								$initiale='';

							}else{

								$anneeins=substr($annee, -2);

								$matricule=($anneeins . '000')+($nb['id']+1);
								$initiale=$rapport->infoEtablissement()["initial"];
							}
							
							$pseudo=$prenom[0].$nome.$matricule;
							$mdp=$initiale.$matricule;
							$mdp=password_hash($mdp, PASSWORD_DEFAULT);

							$verifel=$DB->querys('SELECT nomel from elevepreinscription where nomel=:nom and prenomel=:prenom and naissance=:naiss and pere=:pere and mere=:mere ', array(
								'nom'=>$nome,
								'prenom'=>$prenom,
								'naiss'=>$daten,
								'pere'=>$nomp,
								'mere'=>$nomm
							));

							$verifmat=$DB->querys('SELECT nomel from elevepreinscription where matricule=:nom ', array(
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

								$matuteur=$matricule;
								$matuteur='tut'.$matuteur;
								$pseudo=$matuteur;
								$mdp=$matuteur;

								$DB->insert('INSERT INTO elevepreinscription(matricule, nomel, prenomel, sexe, naissance, pere, mere, telpere, telmere, profp, profm, origine, pays, nationnalite, adresse, phone, email, matuteur, nomtut, teltut, proft, codef, niveau, nomgr, promo, lieutp, lieutm, adressep, dateenreg) values( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array(($initiale.$matricule), $nome, $prenom, $sexe, $daten, $nomp, $nomm, $telp, $telm, $profp, $profm, $origine, $pays, $nation, $adresse, $phone, $email, $matuteur, $nomp, $telp, $proft, $codef, $niveau, $groupe, $annee, $lieutp, $lieutm, $adressep));
								
								$DB->insert('INSERT INTO matricule(matricule, etat, annee) values( ?, ?, ?)', array(($initiale.$matricule),'inscription', $annee));
								?>	

								<div class="alert alert-success">Elève enregistré avec succèe!!!</div><?php

							}

						}else{?>	

							<div class="alert alert-danger">Remplissez les champs vides</div><?php
						}
					}

					// fin inscription

					if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire' or $products['type']=='bibliothecaire') {?>
						
						<form class="mt-1" id="formulaire" method="POST" action="preinscription.php" enctype="multipart/form-data" style="display: flex; flex-wrap: wrap;">

							<fieldset class="m-1"><legend class="text-center bg-success bg-opacity-50">Infos Administratives</legend>
								<ol>

									<li>
										<label>Classe</label>
										<select type="text" name="group" required="" onchange="this.form.submit()"><?php 
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
										<label>Lieu de travail Père</label>
										<input type="text" name="lieutp" maxlength="100">
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
										<label>Lieu de travail Mère</label>
										<input type="text" name="lieutm" maxlength="100" >
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

									<li>
										<label>Adresse des Parents</label>
										<input type="text" name="adressep" maxlength="100">
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
										<input type="date" name="daten" max="<?=$panier->datemin(2)[0];?>"  required="">
											
									</li>

									<li>
										<label>Lieu de naissance</label>
										<input type="adde" name="adr" >
											
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

								<ol>
									<li><label>Année-Scolaire</label>

										<select type="text" name="annee" required="">
											<option><option><?php
											
											$annee=date("Y")+1;
											$anneei=date("Y")-1;

											for($i=$anneei;$i<=$annee ;$i++){
												$j=$i+1;?>

												<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

											}?>
										</select>
										
									</li>
								</ol>
							</fieldset><?php

							if ($_SESSION['niveaupers']>=4) {

								if ($panier->licence()!="expiree" and $panier->cloture()!='cloturer') {?>
									<button class="btn btn-primary"  type="submit" name="ajoutel" onclick="return alerteV();">Valider</div><?php

								}else{?>

									<div class="alert alert-warning">Les inscriptions sont fermées contacter le chef d'établissement </div>

									<div class="alert alert-warning">OU</div>

									<div class="alert alert-warning">la licence est expirée contacter DAMKO </div><?php

								}
							}?>

							

						</form><?php 
					}?>
				</div>
			</div>
		</div> <?php

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
