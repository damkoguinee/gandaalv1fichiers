<?php
require 'headerv2.php';
if (isset($_SESSION['pseudo'])) {
				
	if ($products['niveau']<1) {?>

		<div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

	}else{?>

		<div class="container-fluid p-0">
			<div class="row m-0"><?php 
				require 'navformation.php'; ?>
				<div class="col-sm-12 col-md-10 p-0" style="overflow:auto;">
					<div class="row"><?php
						if (isset($_GET['ajout_f']) or isset($_POST['niveau'])) {?>					
							<form class="form my-2 p-4 bg-secondary" method="POST" action="formation.php">
								<legend>Ajouter une formation</legend>
								<div class="row mb-1">
									<label class="form-label">Cursus</label>
									<select class="form-select" type="text" name="niveau" required=""  onchange="this.form.submit();"><?php
									if (isset($_POST['niveau'])){?>

										<option><?=$_POST['niveau'];?></option><?php

									}else{?>

										<option></option><?php

									}							

									foreach ($panier->cursus() as $value) {?>

										<option value="<?=$value->nom;?>"><?=ucwords($value->nom);?></option><?php
									}?></select>
								</div>

								<div class="row mb-1">
									<label class="form-label">Niveau</label>
									<select class="form-select" type="text" name="classe" required="" >
										<option></option><?php

										if (isset($_POST['niveau']) and $_POST['niveau']=='creche' ) {?>
											<option value="creche">Crèche</option><?php
										}

										if (isset($_POST['niveau']) and $_POST['niveau']=='maternelle' ) {?>
											<option value="toute petite section">Toute Petite Section</option>
											<option value="petite section">Petite Section</option>
											<option value="moyenne section">Moyenne Section</option>
											<option value="grande section">Grande Section</option><?php
										}

										if (isset($_POST['niveau']) and $_POST['niveau']=='primaire' or $_POST['niveau']=='universite' or $_POST['niveau']=='professionnelle') {?>
											<option value="CP1">CP1</option>
											<option value="CP2">CP2</option>
											<option value="CE1">CE1</option>
											<option value="CE2">CE2</option>
											<option value="CM1">CM1</option>
											<option value="CM2">CM2</option><?php
										}

										if (isset($_POST['niveau']) and $_POST['niveau']=='college'){?>
											<option value="6">6ème</option>
											<option value="5">5ème</option>
											<option value="4">4ème</option>
											<option value="3">3ème</option><?php
										}
										if (isset($_POST['niveau']) and $_POST['niveau']=='lycee'){?>
											<option value="2nde">2nde</option>
											<option value="1ere">1ère</option>
											<option value="terminale">Terminale</option><?php
										}?>
									</select>

								</div>

								<div class="row mb-1">
									<label class="form-label">Nom de la Formation</label>
									<input type="text" name="nomf" required="" placeholder="par exple: F Scientifique" class="form-control"/>

								</div>
								<button class="btn btn-primary" type="submit" name="ajoutef" onclick="return alerteV();">Ajouter</button>

							</form><?php
						}

						if(isset($_POST['ajoutef'])){

							if($_POST['nomf']!="" and $_POST['niveau']!="" and $_POST['classe']!=""){
								
								$niveau=addslashes(Htmlspecialchars($_POST['niveau']));
								$classe=addslashes(Htmlspecialchars($_POST['classe']));
								$nomf=ucwords(addslashes(Htmlspecialchars($_POST['nomf'])));
								$prodf=$DB->querys("SELECT max(id) as id FROM formation");
								$codef=$prodf['id']+1;									

								$nb=$DB->querys('SELECT nomf from formation where (nomf=:nom and classe=:clas) or codef=:code', array(
									'nom'=>$nomf,
									'clas'=>$classe,
									'code'=>$codef
								));

								if(!empty($nb)){?>
									<div class="alert alert-warning">Cette formation existe déjà</div><?php

								}else{

									$DB->insert('INSERT INTO formation(niveau, classe, nomf, codef) values( ?, ?, ?, ?)', array($niveau, $classe, $nomf, $codef));?>	

									<div class="alert alert-success">Formation ajoutée avec succée!!!</div><?php
								}

							}else{?>	

								<div class="alert alert-warning">Remplissez les champs vides</div><?php
							}
						}?>
					</div>
					<div class="row" style="overflow:auto;"><?php

						if (isset($_GET['form']) or isset($_POST['ajoutef'])  or isset($_GET['del_f']) or isset($_GET['modif_f']) or isset($_POST['niveau']) or isset($_GET['del_f'])) {?>
												
							<table class="table table-bordered table-striped table-hover align-middle mx-3 ">
								<thead class="sticky-top text-center bg-secondary">
									<tr>
										<th colspan="4" class="text-center">Liste des Formations</th>

										<th colspan="3"><?php 

										if ($products['type']=='admin' or $products['type']=='bibliothecaire') {?><a class="btn btn-warning" href="formation.php?ajout_f" class="btn btn-warning">Ajouter une formation</a><?php }?></th>
									</tr>
															
									<tr>
										<th>N°</th>
										<th>Classes</th>
										<th>Filières</th>
										<th colspan="4">Information sur les</th>
									</tr>
								</thead><?php

								if (!empty($_SESSION['niveauf'])) {

									$prodm=$DB->query('SELECT id, nom from cursus where nom=:niv order by(id)', array('niv'=>$_SESSION['niveauf']));

								}else{

									$prodm=$DB->query('SELECT id, nom from cursus  order by(cursus.id)');

								}

								
								$i=1;
								foreach ($prodm as $key=> $values) {

									if (!empty($_SESSION['niveauf'])) {

										$prodf=$DB->query('SELECT *from formation where niveau=:niv order by(id)', array('niv'=>$_SESSION['niveauf']));

									}else{

										$prodf=$DB->query('SELECT *from formation where niveau=:niv order by(id)', array('niv'=>$values->nom));
									}

									if(!empty($prodf)){?>
									
										<tbody>
											<tr>
												<th colspan="7" class="text-center bg-secondary">Niveau <?=ucwords($values->nom);?></th>
											</tr><?php

											if (empty($prodf)) {
												# code...
											}else{

												foreach ($prodf as $formation) {

													if ($formation->classe=='1') {

														$classe=ucwords($formation->classe.'ere ');

													}elseif($formation->classe=="2nde"){

														$classe=ucwords($formation->classe);

													}elseif(($formation->classe>=2 and $formation->classe<=20)){

														$classe=ucwords($formation->classe.'ème');

													}else{

														$classe=ucwords($formation->classe);?><?php
													}?>

													

													<tr>
														<td class="text-center"><?=$i;?></td>

														<td><?=ucwords($classe);?></td>

														<td><?=ucwords($formation->nomf);?></td>

														<td>
															<a class="btn btn-primary" href="formation.php?voir_m=<?=$formation->codef;?>">Matières</a>
														</td>

														<td>
															<a class="btn btn-primary" href="formation.php?voir_e=<?=$formation->codef;?>"><?=$_SESSION['typeel'];?></a>
														</td>

														<td>

															<a class="btn btn-primary" href="formation.php?voir_en=<?=$formation->codef;?>">Enseignants</a>
														</td><?php

														if ($products['niveau']>8) {?>

															<td>
																<a class="btn btn-danger" href="formation.php?del_f=<?=$formation->codef;?>" onclick="return alerteS();">Supprimer</a>
															</td><?php
														}?>

													</tr><?php
													$i++;
												}
											}?>

											
										</tbody><?php
									}
								}?>
							</table><?php 
						}?>
					</div><?php

					if (isset($_GET['voir_m'])) {

						$prodm=$DB->query('SELECT  *from formation inner join matiere on formation.codef=matiere.codef where matiere.codef=:code', array('code'=>$_GET['voir_m']));

						$prodf=$DB->querys('SELECT nomf, classe from formation  where codef=:code', array('code'=>$_GET['voir_m']));

						if ($prodf['classe']=='1') {

			                $classe=ucwords($prodf['classe'].'ère ');

						}elseif($prodf['classe']=='petite section' or $prodf['classe']=='moyenne section' or $prodf['classe']=='grande section' or $prodf['classe']=='terminale'){

						$classe=ucwords($prodf['classe']);

						}else{

						$classe=ucwords($prodf['classe'].'ème');?><?php
						}?>

						<table class="table table-bordered table-striped table-hover align-middle ">
				    		<thead class="sticky-top bg-secondary text-center align-middle">
				    			<tr>
				    				<th colspan="2" class="info">Liste des matiere en <?=$classe;?>
				    				<a class="btn btn-warning" href="matiere.php?ajout_m" >Ajouter une matière</a></th>
				    			</tr>
								<tr>
									<th>N°</th>
									<th>Matières</th>
								</tr>
							</thead>

							<tbody><?php
								if (empty($prodm)) {
									# code...
								}else{

									foreach ($prodm as $key =>$formation) {?>

										<tr>
											<td class="text-center"><?=ucwords($key+1);?></td>

											<td><?=ucwords($formation->nommat);?></td>

										</tr><?php
									}
								}?>

								
							</tbody>
						</table><?php
					}


					if (isset($_GET['voir_e']) or isset($_GET['searchelf']) or (isset($_GET['page']) and $_GET['typef']==1)) {

						if (!isset($_GET['searchelf'])) {

							$_SESSION['voir_e']=$_GET['voir_e'];
						}

						//require 'pagination.php';?><?php

						if (isset($_GET['searchelf'])) {

					      $_GET["searchelf"] = htmlspecialchars($_GET["searchelf"]); //pour sécuriser le formulaire contre les failles html
					      $terme = $_GET['searchelf'];
					      $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
					      $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
					      $terme = strtolower($terme);

					      $prodm=$DB->query('SELECT  nomel, prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, inscription.matricule as matricule, nomgr, adresse from inscription inner join eleve on eleve.matricule=inscription.matricule inner join contact on eleve.matricule=contact.matricule WHERE inscription.codef=? and annee=? and (eleve.matricule LIKE ? or nomel LIKE ? or prenomel LIKE ? or phone LIKE ?) ',array($_SESSION['voir_e'], $_SESSION['promo'], "%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));

					      $prodf=$DB->querys('SELECT codef, nomf, classe from formation  where codef=:code', array('code'=>$_SESSION['voir_e']));
					      
					    }else{

							$prodm=$DB->query('SELECT  nomel, prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, adresse, inscription.matricule as matricule, nomgr from inscription inner join eleve on eleve.matricule=inscription.matricule where inscription.codef=:code and annee=:promo order by(prenomel) ', array('code'=>$_GET['voir_e'], 'promo'=>$_SESSION['promo']));

							$prodf=$DB->querys('SELECT codef, nomf, classe from formation  where codef=:code', array('code'=>$_GET['voir_e']));
						}

						if ($prodf['classe']=='1') {

							$classe=ucwords($prodf['classe'].'ere '.ucwords($prodf['nomf']));

						}elseif($prodf['classe']=='creche' or $prodf['classe']=='petite section' or $prodf['classe']=='moyenne section' or $prodf['classe']=='grande section' or $prodf['classe']=='terminale'){

			                $classe=ucwords($prodf['classe'].' '.ucwords($prodf['nomf']));

			            }else{

							$classe=ucwords($prodf['classe'].'ème '.ucwords($prodf['nomf']));
						}?>

						<table class="table table-bordered table-striped table-hover align-middle">
				    		<thead class="sticky-top bg-secondary">
				    			<form class="form" method="GET" action="formation.php" id="suitec" name="termc">

									<tr>
						            	<th colspan="7">
											<div class="row">
												<div class="col-sm-12 col-md-4">
													<?=$_SESSION['typeel'].' '.$classe;?> <a class="btn btn-info" href="printdoc.php?voir_e=<?=$prodf['codef'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>
												</div>
												<div class="col-sm-12 col-md-4">
													<div class="row">
														<div class="col-7">
															<input class="form-control" type = "search" name= "searchelf" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">
														</div>
														<div class="col-5">
															<input class="form-control" type = "submit" name = "s" value = "Rechercher">
														</div>
													</div>
												</div>
												<div class="col-sm-12 col-md-4">
													<a class="btn btn-warning" href="ajout_eleve.php?ajoute">Ajouter un élève</a>
												</div>
											</div>

										</th>
						          	</tr>

								</form>

								<tr>
									<th>N°</th>
									<th>Classe</th>
									<th>N° M</th>
									<th>Nom</th>
									<th>Né(e)</th>								
									<th>Lieu de N</th>
									<th></th>
								</tr>

							</thead>

							<tbody><?php
								if (empty($prodm)) {
									# code...
								}else{

									foreach ($prodm as $key=> $formation) {?>

										<tr>
											<td class="text-center"><?=$key+1;?></td>
											<td><a href="formation.php?voir_elg=<?=$formation->nomgr;?>"><?=$formation->nomgr;?></a></td>

											<td><?=$formation->matricule;?></td>

											<td><?=ucfirst(strtolower($formation->prenomel)).' '.strtoupper($formation->nomel);?></td>

											<td><?=$formation->naissance;?></td>

											
											<td><?=ucwords($formation->adresse);?></td>

											<td>
												<a class="btn btn-primary" href="ajout_eleve.php?fiche_eleve=<?=$formation->matricule;?>&promo=<?=$_SESSION['promo'];?>">+infos</a>
											</td>

										</tr><?php
									}
								}?>
								
							</tbody>
						</table><?php

					}

					if (isset($_GET['voir_en'])) {

						$prodm=$DB->query('SELECT  *from formation inner join enseignement on formation.codef=enseignement.codef inner join enseignant on enseignant.matricule=enseignement.codens where enseignement.codef=:code and promo=:promo', array('code'=>$_GET['voir_en'], 'promo'=>$_SESSION['promo']));

						$prodf=$DB->querys('SELECT nomf, classe from formation  where codef=:code', array('code'=>$_GET['voir_en']));

						if ($prodf['classe']=='1') {

			                $classe=ucwords($prodf['classe'].'ère ');

			              }elseif($prodf['classe']=='petite section' or $prodf['classe']=='moyenne section' or $prodf['classe']=='grande section' or $prodf['classe']=='terminale'){

			                $classe=ucwords($prodf['classe']);

			              }else{

			                $classe=ucwords($prodf['classe'].'ème');?><?php
			              }?>

						<table class="table table-bordered table-striped table-hover align-middle my-2">
							<thead>
								<tr>
									<th colspan="2" class="info">Liste des enseignants: <?=$classe.' '.$prodf['nomf'];?> </th>
									<th><a class="btn btn-warning" href="enseignant.php?ajout_en">Ajouter un Enseignants</a></th>
								</tr>
								<tr>
									<th>N°</th>
									<th>Classe</th>
									<th>Enseignants</th>
								</tr>
							</thead>

							<tbody><?php
								if (empty($prodm)) {
									# code...
								}else{

									foreach ($prodm as $key=> $formation) {?>

										<tr>
											<td><?$key+1;?></td>
											<td><a href="formation.php?voir_elg=<?=$formation->nomgr;?>"><?=$formation->nomgr;?></a></td>
											<td><?=strtoupper($formation->nomen).' '.ucfirst(strtolower($formation->prenomen));?></td>
										</tr><?php
									}
								}?>								
							</tbody>
						</table><?php
					}

					if (isset($_GET['voir_elg']) or isset($_GET['modifclasse']) or (isset($_GET['page']) and $_GET['typef']==2)) {

						if (!isset($_GET['modifclasse'])) {

							$_SESSION['voir_elg']=$_GET['voir_elg'];
						}

						if (isset($_GET['modifclasse'])) {

							$DB->insert('UPDATE inscription SET nomgr= ? WHERE matricule = ? and annee= ?', array($_GET['modifclasse'], $_GET['elevecl'], $_SESSION['promo']));
						}

						$prodm=$DB->query('SELECT  nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%d/%m/%Y\') AS naissance, inscription.matricule as matricule, nomgr, phone, codef from inscription inner join eleve on eleve.matricule=inscription.matricule inner join contact on contact.matricule=inscription.matricule where inscription.nomgr=:code and annee=:promo order by(prenomel) ', array('code'=>$_SESSION['voir_elg'], 'promo'=>$_SESSION['promo']));

						$prodf=$DB->querys('SELECT nomgr from groupe  where nomgr=:code', array('code'=>$_SESSION['voir_elg']));?>
						
						<table class="table table-bordered table-striped table-hover align-middle my-2">
				    		<thead>
				    			<tr>
				    				<th colspan="5" class="info">Liste des <?=$_SESSION['typeel'].' en '.$prodf['nomgr'];?> <a class="btn btn-info" href="printdoc.php?voir_elg=<?=$prodf['nomgr'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>

				    				<th colspan="2"><a class="btn btn-warning" href="ajout_eleve.php?ajoute">Ajouter <?=$_SESSION['typeel'];?></a></th>
				    			</tr>

								<tr>
									<th>N°</th>
									<th>Matricule</th>
									<th>Prénom & Nom</th>
									<th>Né(e)</th>
									<th>Téléphone</th>
									<th>Modif Classe</th>
									<th></th>
								</tr>

							</thead>

							<tbody><?php
								if (empty($prodm)) {
									# code...
								}else{

									foreach ($prodm as $key=> $formation) {?>

										<form class="form" method="GET" action="formation.php"> 
											<tr>
												<td style="text-align: center;"><?=$key+1;?></td>									

												<td><?=$formation->matricule;?><input type="hidden" name="elevecl" value="<?=$formation->matricule;?>"></td>

												<td><?=ucfirst(strtolower($formation->prenomel)).' '.strtoupper($formation->nomel);?></td>

												<td><?=$formation->naissance;?></td>

												<td><?=$formation->phone;?></td>

												<td><select class="form-select" name="modifclasse" onchange="this.form.submit()
												">

												<option value="<?=$formation->nomgr;?>"><?=$formation->nomgr;?></option><?php
													$codef=$formation->codef;
													foreach ($panier->classeStat($codef, $_SESSION['promo']) as $value) {?>
														
														<option value="<?=$value->nomgr;?>"><?=strtoupper($value->nomgr);?></option><?php
													}?>
													
												</select></td>

												<td colspan="2">

													<a class="btn btn-primary" href="ajout_eleve.php?fiche_eleve=<?=$formation->matricule;?>&promo=<?=$_SESSION['promo'];?>">+Infos</a>
												</td>

											</tr>
										</form><?php
									}
								}?>								
							</tbody>
						</table><?php
					}


					if (isset($_GET['voir_cursus']) or (isset($_GET['page']) and $_GET['typef']==1)) {

						$_SESSION['voir_cursus']=$_GET['voir_cursus'];

						//require 'pagination.php';

						$prodm=$DB->query('SELECT  nomel, prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, adresse, inscription.matricule as matricule, nomgr from inscription inner join eleve on eleve.matricule=inscription.matricule where inscription.niveau=:code and annee=:promo order by(prenomel)', array('code'=>$_GET['voir_cursus'], 'promo'=>$_SESSION['promo']));?>

						<table class="table table-bordered table-striped table-hover align-middle">
				    		<thead class="sticky-top bg-secondary">
				    			<form class="form" method="GET" action="formation.php" id="suitec" name="termc">

									<tr>
						            	<th colspan="4">Liste des élèves niveau <?=$_GET['voir_cursus'];?> <a class="btn btn-info" href="printdoc.php?voir_cursus=<?=$_GET['voir_cursus'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

						            		<input class="form-control" type = "search" name = "searchelf" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">
						            	</th>
					                  	<th><input class="form-control"   type = "submit" name = "s" value = "search"></th>

						            	<th colspan="2"><a class="btn btn-warning" href="ajout_eleve.php?ajoute">Ajouter un étudiant</a></th>
						          	</tr>

								</form>

								<tr>
									<th>N°</th>
									<th>Classe</th>
									<th>Matricule</th>
									<th>Nom</th>
									<th>Né(e)</th>								
									<th>Lieu de N</th>								
									<th colspan="2"></th>
								</tr>

							</thead>

							<tbody><?php
								if (empty($prodm)) {
									# code...
								}else{

									foreach ($prodm as $key=> $formation) {?>

										<tr>
											<td><?=$key+1;?></td>
											<td><a href="formation.php?voir_elg=<?=$formation->nomgr;?>"><?=$formation->nomgr;?></a></td>

											<td><?=$formation->matricule;?></td>

											<td><?=ucfirst(strtolower($formation->prenomel)).' '.strtoupper($formation->nomel);?></td>

											<td><?=$formation->naissance;?></td>

											
											<td><?=ucwords($formation->adresse);?></td>

											<td colspan="2">

												<a href="ajout_eleve.php?fiche_eleve=<?=$formation->matricule;?>&promo=<?=$_SESSION['promo'];?>"><input type="button" value="+infos" style="width: 50%; font-size: 16px;  cursor: pointer"></a>
											</td>

										</tr><?php
									}
								}?>

								
							</tbody>
						</table><?php

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
