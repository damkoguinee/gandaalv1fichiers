<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>
		<div class="container-fluid">
			<div class="row"><?php 
			
				require 'navformation.php';?>

				<div class="col-sm-12 col-md-10"><?php

					if (isset($_GET['ajout_m'])) {

						if (!empty($_SESSION['niveauf'])) {

							$form=$DB->query('SELECT *from formation where niveau=:niv', array('niv'=>$_SESSION['niveauf']));

						}else{

							$form=$DB->query('SELECT *from formation');
						}

						if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='Proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='surveillant Général' or $products['type']=='bibliothecaire') {?>

							<form id="formulaire" method="POST" action="matiere.php">

								<fieldset><legend>Ajouter une matière</legend>
									<ol>

										<li><label>Code formation</label>
											<select type="text" name="codef[]" multiple required="">
												<option></option><?php
												foreach ($form as $codef) {

													if ($codef->classe=='1') {?>

														<option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' ère année '.$codef->nomf;?></option><?php

													}elseif($codef->classe=='2nde'){?>

														<option value="<?=$codef->codef;?>"><?=$codef->classe;?></option><?php

													}elseif($codef->classe>=2 and $codef->classe<=20){?>

														<option value="<?=$codef->codef;?>"><?=$codef->classe.' ème '.$codef->nomf;?></option><?php

													}elseif($codef->niveau=='maternelle'){?>

														<option value="<?=$codef->codef;?>"><?=$codef->classe;?></option><?php

													}else{?>

														<option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' '.$codef->nomf;?></option><?php
													}

												}?>
											</select>

										</li> 

										<li>
											<label>Nom de la Matière</label>
											<input type="text" name="nomm" required=""/>
										</li>

										<li>
											<label>Coefficient</label>
											<select type="text" name="coefm" required="" style="width: 50px;">
												<option></option>
												<option value="0.5">0.5</option><?php
												$count=0
												;
												while ($count<= 10) {?>

													<option value="<?=$count;?>"><?=$count;?></option><?php

													$count++;
												}?>							
												
											</select>
										</li>

										<li>
											<label>Catégorie</label>
											<select type="text" name="cat" required="">
												<option></option>
												<option value="sciences exactes">Sciences exactes</option>
												<option value="sciences litteraires">Sciences Litteraires</option>
												<option value="c/svt">C/SVT</option>
												<option value="facultatives">Facultatives</option>
												<option value="catégorie essentielle">Catégorie Essentielle</option>
												<option value="catégorie francais">Catégorie Français</option>
												<option value="catégorie math/calcul">Catégorie Math/Calcul</option>
												<option value="catégorie léçon d éveil">Catégorie léçon d'éveil</option>
												<option value="autres">autres</option>
												<option value="non evalue">non évalué</option>
											</select>
										</li>
										<input type="hidden" name="codem" required="" style="width: 50px;"/>
										

										<li>
											<label>Nombre d'heure</label>
											<input type="text" name="heure" required="" style="width: 50px;"/>
										</li>

									</ol>
								</fieldset>

								<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajoutem" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
							</form><?php 
						}
					}

					if(isset($_POST['ajoutem'])){	

						if($_POST['nomm']!="" and $_POST['codem']!="" and $_POST['coefm']!="" and $_POST['codef']!="" ){
							
							$codem=$panier->h($_POST['codem']);
							$nommat=$_POST['nomm'];
							$codef=$_POST['codef'];
							$coefm=$panier->h($_POST['coefm']);

							$heure=$panier->h($_POST['heure']);
							$cat=$panier->h($_POST['cat']);

							$codem=substr($nommat, 0, 3);
							$codem=$codem.$codef[0];
							foreach($codef as $code){

								$nb=$DB->querys('SELECT nommat from matiere where (nommat=:nom and codem=:codem)', array(
									'nom'=>$nommat,
									'codem'=>$codem
								));

								if(!empty($nb)){?>
									<div class="alert alert-warning">Cette matière existe déjà</div><?php

								}else{

									$DB->insert('INSERT INTO matiere(codem, nommat, coef, cat, codef, nbre_heure) values( ?, ?, ?, ?, ?, ?)', array($codem.$code, $nommat, $coefm, $cat, $code, $heure));?>	

									<div class="alert alert-success">Matière ajoutée avec succée!!!</div><a href="matiere.php?ajout_ens" class="btn btn-info">Enregistrer un enseignement</a><?php
								}
							}

						}else{?>	

							<div class="alert alert-warning">Remplissez les champs vides</div><?php
						}
					}

		//Pour modifier une matière


					if (isset($_GET['modif_m'])) {

						$prodm=$DB->querys('SELECT  codem, nommat, coef, cat, codef, nbre_heure from matiere where codem=:code', array('code'=>$_GET['modif_m']));

						$form=$DB->query('SELECT *from formation ');?>

						<form id="formulaire" method="POST" action="matiere.php">

							<fieldset><legend>Modifier la matière</legend>
								<ol>

									<li><label>Code formation</label>
										<select type="text" name="codef" value="<?=$prodm['codem'];?>">
											<option value="<?=$prodm['codef'];?>"><?=$prodm['codef'];?></option><?php
											foreach ($form as $codef) {
												if ($codef->classe=='terminale') {?>

													<option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' '.$codef->nomf;?></option><?php
												}else{?>

													<option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' ème année '.$codef->nomf;?></option><?php
												}

											}?>
										</select>

									</li>

									<li>
										<label>Nom de la Matière</label>
										<input type="text" name="nomm" value="<?=$prodm['nommat'];?>"/>
									</li>

									<li>
										<label>Coefficient</label>
										<select type="text" name="coefm" value="<?=$prodm['coef'];?>"> style="width: 50px;">
											<option value="<?=$prodm['coef'];?>"><?=$prodm['coef'];?></option>

											<option value="0.5">0.5</option><?php
											$count=0;
											while ($count<= 10) {?>

												<option value="<?=$count;?>"><?=$count;?></option><?php

												$count++;
											}?>							
											
										</select>
									</li>

									<li>
										<label>Catégorie</label>
										<select type="text" name="catm" required="">
											<option value="<?=$prodm['cat'];?>"><?=$prodm['cat'];?></option>
											<option value="sciences exactes">Sciences exactes</option>
											<option value="sciences litteraires">Sciences Litteraires</option>
											<option value="c/svt">C/SVT</option>
											<option value="facultatives">Facultatives</option>
										</select>
									</li>
									<input type="hidden" name="codem" value="<?=$prodm['codem'];?>" style="width: 50px;"/>
									

									<li>
										<label>Nbre d'heures</label>
										<input type="text" name="heurem" value="<?=$prodm['nbre_heure'];?>" style="width: 50px;"/>
									</li>

								</ol>
							</fieldset>

							<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Modifier" name="modifm" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
						</form><?php
					}

					if(isset($_POST['modifm'])){
								
						$codem=addslashes(Htmlspecialchars($_POST['codem']));
						$nommat=$_POST['nomm'];
						$codef=addslashes(Htmlspecialchars($_POST['codef']));
						$coefm=addslashes(Htmlspecialchars($_POST['coefm']));
						$heurem=addslashes(Htmlspecialchars($_POST['heurem']));
						$catm=addslashes(Htmlspecialchars($_POST['catm']));

						

						$DB->insert('UPDATE matiere SET codef = ?, nommat=?, coef=?, cat=?, nbre_heure=? WHERE codem = ?', array($codef, $nommat, $coefm, $catm, $heurem, $codem));?>	

						<div class="alerteV">Matière modifiée avec succée!!!</div><?php
						
					}

					if (isset($_GET['matiere']) or isset($_POST['ajoutem'])  or isset($_GET['del_m']) or isset($_POST['modifm']) or isset($_GET['matiereel']) or isset($_GET['voir_m']) or isset($_GET['codefm'])) {

						if (isset($_GET['del_m'])) {

							$DB->delete('DELETE FROM matiere WHERE codem = ?', array($_GET['del_m']));

							$DB->delete('DELETE FROM enseignement WHERE codem = ? and promo=?', array($_GET['del_m'], $_SESSION['promo']));

							$prodev=$DB->query('SELECT id from devoir where codem=:code and promo=:promo', array('code'=>$_GET['del_m'] ,'promo'=> $_SESSION['promo']));

							$DB->delete('DELETE FROM devoir WHERE codem = ? and promo=?', array($_GET['del_m'], $_SESSION['promo']));

							foreach ($prodev as $value) {
									
								$DB->delete('DELETE FROM note WHERE codev = ?', array($value->id));
							}?>

						<div class="alert alert-success">Suppression reussie!!!</div><?php 
						}

						if (isset($_GET['matiere'])) {

							if (!empty($_SESSION['niveauf'])) {

								$prodmat=$DB->query('SELECT  codef, nomf, classe, niveau from formation where niveau=:niv order by(id)', array('niv'=>$_SESSION['niveauf']));

							}else{

								$prodmat=$DB->query('SELECT  codef, nomf, classe, niveau from formation order by(id)');
							}?>

							<div class="row"><legend>Selectionnez le niveau</legend><?php

								foreach ($prodmat as $matiere) {
									if ($matiere->classe==1) {

										$niveau=$matiere->classe.' ère '.$matiere->nomf;

									}elseif ($matiere->classe=='2nde') {

										$niveau=$matiere->classe;

									}elseif ($matiere->classe>=2 and $matiere->classe<=20) {

										$niveau=$matiere->classe.' ème '.$matiere->nomf;

									}elseif ($matiere->niveau=='maternelle') {

										$niveau=$matiere->classe;

									}else{

										$niveau=$matiere->classe.' '.$matiere->nomf;

									} ?>

									<a class="col-sm-12 col-md-3 my-1 mx-1 btn btn-success" href="matiere.php?codefm=<?=$matiere->codef;?>"><?=ucwords($niveau);?></a>

									<?php
								}?>
							</div><?php
						}else{

							if (isset($_GET['matiereel'])) {

								$prodm=$DB->query('SELECT  codem, nommat, nomf, coef, nbre_heure as heure, classe, formation.niveau as niveau from matiere inner join formation on matiere.codef=formation.codef inner join inscription on inscription.codef=matiere.codef where inscription.matricule=:mat and formation.codef=:code order by(matiere.id)', array(
									'mat'=>$_GET['matiereel'],'code'=>$_GET['codef']));

							}elseif (isset($_GET['voir_m'])) {

								$prodm=$DB->query('SELECT  codem, nommat, nomf, coef,  nbre_heure as heure, classe, niveau from matiere inner join formation on matiere.codef=formation.codef where matiere.codef=:code order by(matiere.id)', array(
									'code'=>$_GET['voir_m']
								));


							}elseif (isset($_GET['codefm'])) {

								$prodm=$DB->query('SELECT  codem, nommat, nomf, coef, classe, niveau,  nbre_heure as heure from matiere inner join formation on matiere.codef=formation.codef  where matiere.codef=:code order by(matiere.id)', array(
									'code'=>$_GET['codefm']
								));

							}?>
							<table class="payement" style="width: 100%;">
								<thead><?php
									if (isset($_GET['matiereel']) or isset($_GET['voir_m'])) {?>

										<tr>
											<th colspan="4" class="info" style="text-align: center">Liste des matières en <?php if (isset($_GET['voir_m'])) {echo "en ".$_GET['voir_m'];}?></th>
										</tr>
										<tr>
											<th>N°</th>
											<th>Matière</th>
											<th>Coef</th>
											<th>Heure</th>
										</tr><?php

									}else{?>

										<form >

											<tr>
												<th colspan="3" class="info" style="text-align: center">Liste des matières</th>

												<th><input   type = "submit" name = "s" value = "search"></th>
												<th colspan="2"><?php 

													if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='Proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='surveillant Général' or $products['type']=='bibliothecaire') {?><a href="matiere.php?ajout_m" style="color: white;">Ajouter une matière</a><?php }?><a style="margin-left: 10px;"href="printdoc.php?printmat=<?=$_GET['codefm'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
										</tr>

										</form>

										<tr>
											<th height="10">N°</th>
											<th>Matière</th>
											<th>Coef</th>
											<th>Heure</th>
											<th></th>
										</tr><?php

									}?>
								</thead>

								<tbody><?php
									if (empty($prodm)) {
										# code...
									}else{

										foreach ($prodm as $key=> $formation) {?>

											<tr>
												<td style="text-align: center;"><?=$key+1;?></td>
												<td><?=ucwords($formation->nommat);?></td>
												<td style="text-align: center;"><?=$formation->coef;?></td>
												<td style="text-align: center;"><?=$formation->heure;?>h</td><?php

												if (isset($_GET['matiereel']) or isset($_GET['voir_m'])) {?>

													<?php
												}else{?>

													<td colspan="2"><?php 

														if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='Proviseur' or $products['type']=='DE/Censeur' or $products['type']=='surveillant Général' or $products['type']=='bibliothecaire') {?>

															<a href="matiere.php?modif_m=<?=$formation->codem;?>"><input type="button" value="Modifier" style="width: 40%; font-size: 16px; text-align: center; background-color: orange; color: white; cursor: pointer"></a><?php 
														}

														if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='bibliothecaire') {?>

															<a href="matiere.php?del_m=<?=$formation->codem;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 40%; font-size: 16px; text-align: center; background-color: red; color: white; cursor: pointer"></a><?php 
														}?>
													</td><?php

												}?>

											</tr><?php
										}
									}?>

										
								</tbody>
							</table><?php
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
