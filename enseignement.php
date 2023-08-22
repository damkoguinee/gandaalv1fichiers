<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>
    	<div class="container-fluid">

    		<div class="row"><?php 

		    	require 'navformation.php';?>

				<div class="col-sm-12 col-md-8" style="overflow: auto;"><?php 

					if (isset($_GET['ajout_ens']) or isset($_POST['codef'])) {

						if (isset($_POST['codef'])) {

							$prodgroup=$DB->query('SELECT nomgr from groupe where codef=:code and promo=:promo order by(codef)', array('code'=>$_POST['codef'], 'promo'=>$_SESSION['promo']));
						}


						if (!empty($_SESSION['niveauf'])) {

				    		$form=$DB->query('SELECT codef, nomf, classe from formation where niveau=:niv', array('niv'=>$_SESSION['niveauf']));

				    		$prodprof=$DB->query('SELECT nomen, prenomen, enseignant.matricule as matricule from enseignant inner join niveau on enseignant.matricule=niveau.matricule where nom=:niv order by(prenomen)', array('niv'=>$_SESSION['niveauf']));

				    	}else{

				    		$form=$DB->query('SELECT codef, nomf, classe from formation');

				    		$prodprof=$DB->query('SELECT nomen, prenomen, matricule from enseignant order by(prenomen)');
				    	}

						if (isset($_POST['codef'])) {

							$prodmat=$DB->query('SELECT *from matiere where codef=:code', array('code'=>$_POST['codef']));

						}else{

							$prodmat=$DB->query('SELECT *from matiere ');
						}?>

						<form id="formulaire" method="POST" action="enseignement.php">

						    <fieldset><legend>Ajouter un Cours</legend>
						    	<ol>

									<li>

										<label>Code formation</label>
										<select type="text" name="codef" required="" class="form-control" onchange="this.form.submit()"><?php 

									    if (isset($_POST['codef'])) {?>

									    	<option><?=$_POST['codef'];?></option><?php

									    }else{?>

									    	<option></option><?php						    	
									    }

										    foreach ($form as $codef) {
										    	if ($codef->classe=='1') {?>

						                            <option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' ère année '.$codef->nomf;?></option><?php

												}elseif($codef->classe=='petite section' or $codef->classe=='moyenne section' or $codef->classe=='grande section' or $codef->classe=='terminale'){?>

						                            <option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' '.$codef->nomf;?></option><?php

												}else{?>

						                            <option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' ème année '.$codef->nomf;?></option><?php
												}

										    }?>
										</select>

									</li>

									<li>

										<label>Matières</label>
										<select type="text" name="nomm[]" multiple required="" class="form-control">
									    	<option></option><?php
										    foreach ($prodmat as $codef) {?>

						                        <option value="<?=$codef->codem;?>"><?=$codef->nommat;?></option><?php
						                        
										    }?>
										</select>

										<a href="matiere.php?ajout_m">Ajouter une matière</a>
									</li>

									<li>

										<label>Classe</label>
										<select type="text" name="nomg" required="" class="form-control">
									    	<option></option><?php
										    foreach ($prodgroup as $codef) {?>

						                        <option value="<?=$codef->nomgr;?>"><?=$codef->nomgr;?></option><?php
						                        
										    }?>
										</select>

									</li>

									<li>

										<label>Enseignant</label>
										<select type="text" name="prof" required="">
									    	<option></option><?php
										    foreach ($prodprof as $prof) {?>

										    	<option value="<?=$prof->matricule;?>"><?=ucfirst(strtolower($prof->prenomen)).' '.strtoupper($prof->nomen);?></option><?php

										    }?>
										</select>

									    <a href="enseignant.php?ajout_en">Ajouter enseignant</a>

									</li>

									<li><label>Année-Scolaire</label>

							            <select type="text" name="promo" required=""><?php
							              
								            $annee=date("Y")+1;

								            for($i=($_SESSION['promo']-1);$i<=$annee ;$i++){
								            	$j=$i+1;?>

								             	<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

								            }?>
								        </select>
							            
							        </li>

								</ol>
							</fieldset>

							<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajoutens" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
						</form><?php
					}

					if(isset($_POST['ajoutens'])){

						if($_POST['nomg']!="" and $_POST['nomm']!="" and $_POST['prof']!="" and $_POST['codef']!="" ){
							
							$nomg=addslashes(Htmlspecialchars($_POST['nomg']));
							$nomm=$_POST['nomm'];
							$prof=addslashes(Htmlspecialchars($_POST['prof']));
							$codef=addslashes(Htmlspecialchars($_POST['codef']));
							//$semestre=addslashes(Htmlspecialchars($_POST['semestre']));
							$promo=addslashes(Htmlspecialchars($_POST['promo']));

							$classe=$DB->querys("SELECT id from groupe where nomgr='{$nomg}' and promo='{$promo}'");
							$classe=$classe['id'];

							$verifsalaire=$DB->querys("SELECT id from salaireens where numpers='{$prof}' and promo='{$promo}'");
							$verifprime=$DB->querys("SELECT id from prime where numpersp='{$prof}' and promop='{$promo}'");

							foreach ($nomm as $value) {

								$nb=$DB->querys('SELECT nomgr from enseignement where (nomgr=:nom and codem=:code and promo=:promo)', array(
								'nom'=>$nomg,
								'code'=>$value,
								'promo'=>$promo
								));

								if(!empty($nb)){?>
									<div class="alert alert-warning">Cet enseignement existe déjà</div><?php

								}else{

									
										
									$DB->insert('INSERT INTO enseignement(idclasse, nomgr, codef, codem, codens, promo) values( ?, ?, ?, ?, ?, ?)', array($classe, $nomg, $codef, $value, $prof, $promo));

									if (empty($verifprime['id'])) {

										$DB->insert('INSERT INTO prime(numpersp, montantp, promop) values(?, ?, ?)', array($prof, 0, $promo));
									}

									
								}
							}

							if (empty($verifsalaire['id'])) {
								$DB->insert('INSERT INTO salaireens(numpers, salaire, thoraire, promo) values(?, ?, ?, ?)', array($prof, 0, 0, $_SESSION['promo']));
							}
							?>	

							<div class="alert alert-success">Enseignement ajouté avec succèe!!!</div><?php

						}else{?>	

							<div class="alert alert-warning">Remplissez les champs vides</div><?php
						}
					}


					//Modification

					if(isset($_GET['modif_ens'])){

						$prodprof=$DB->query('SELECT nomen, prenomen, matricule from enseignant ');

						$prodm=$DB->querys('SELECT nomen, prenomen, matricule, nomgr, codem from enseignant inner join enseignement on enseignement.codens=enseignant.matricule where codens=:pro and nomgr=:nom and codem=:code', array('pro'=>$_GET['modif_ens'], 'nom'=>$_GET['nomgr'], 'code'=>$_GET['codem']));?>

						<form id="formulaire" method="POST" action="enseignement.php">

						    <fieldset><legend>Modifier un enseignant</legend>
						    	<ol>

									<li>

										<label>Enseignant</label>
										<select type="text" name="prof" required="">
									    	<option value="<?=$prodm['matricule'];?>"><?=strtoupper($prodm['nomen']).' '.ucfirst(strtolower($prodm['prenomen']));?></option><?php
										    foreach ($prodprof as $prof) {?>

										    	<option value="<?=$prof->matricule;?>"><?=strtoupper($prof->nomen).' '.ucfirst(strtolower($prof->prenomen));?></option><?php

										    }?>
										</select>

									    <input type="hidden" name="mat" value="<?=$prodm['matricule'];?>"/>

									    <input type="hidden" name="nomgr" value="<?=$prodm['nomgr'];?>">

									    <input type="hidden" name="codem" value="<?=$prodm['codem'];?>">

									</li>

								</ol>

							</fieldset>

							<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Modifier" name="modifens" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
						</form><?php
					}

					if(isset($_POST['modifens'])){

						if($_POST['prof']!=""){
							
							$prof=addslashes(Htmlspecialchars($_POST['prof']));

							$DB->insert('UPDATE enseignement SET codens= ? WHERE codens = ? and nomgr=? and codem=?  and promo= ?', array($prof, $_POST['mat'], $_POST['nomgr'], $_POST['codem'], $_SESSION['promo']));

							$prodev=$DB->query('SELECT id from devoir where codens=:id and promo=:pro and nomgroupe=:nom and codem=:code', array('id'=>$_POST['mat'], 'pro'=>$_SESSION['promo'], 'nom'=>$_POST['nomgr'], 'code'=>$_POST['codem']));

							$DB->insert('UPDATE devoir SET codens= ? WHERE codens = ? and promo= ? and nomgroupe=? and codem=?', array($prof, $_POST['mat'], $_SESSION['promo'], $_POST['nomgr'], $_POST['codem']));

							foreach ($prodev as $value) {						

								$DB->insert('UPDATE note SET codens= ? WHERE codens = ? and codev= ?', array($prof, $_POST['mat'], $value->id));
							}?>	

							<div class="alert alert-success">Enseignement modifié avec succèe!!!</div><?php
							

						}else{?>	

							<div class="alert alert-warning">Remplissez les champs vides</div><?php
						}
					}

					//fin modif


					// Dupliquer une classe pour l'année suivnate

					if(isset($_GET['dupliq_f'])){

						$nomg=$_GET['nomgr'];
						$codef=$_GET['codef'];
						$value=$_GET['codem'];
						$prof=$_GET['codens'];
						$promo=($_SESSION['promo']+1);

						$nb=$DB->querys('SELECT nomgr from enseignement where (nomgr=:nom and codem=:code and promo=:promo)', array(
								'nom'=>$nomg,
								'code'=>$value,
								'promo'=>$promo
								));

						$classe=$DB->querys("SELECT id from groupe where nomgr='{$nomg}' and promo='{$promo}'");
						$classe=$classe['id'];

						if(!empty($nb)){?>
							<div class="alert alert-warning">Ce Cours est déjà planifié pour l'année prochaine</div><?php

						}else{
						
							$DB->insert('INSERT INTO enseignement(idclasse, nomgr, codef, codem, codens, promo) values( ?, ?, ?, ?, ?, ?)', array($classe, $nomg, $codef, $value, $prof, $promo));

							$verifprime=$DB->querys('SELECT id from prime where numpersp=? and promop=?', array($prof, $promo));

							if (empty($verifprime)) {

								$DB->insert('INSERT INTO prime(numpersp, montantp, promop) values(?, ?, ?)', array($prof, 0, $promo));
							}

					

							$verifens=$DB->querys('SELECT id from salaireens where numpers=? and promo=?', array($prof, $promo));

							if (empty($verifens)) {

								$DB->insert('INSERT INTO salaireens(numpers, salaire, thoraire, promo) values(?, ?, ?, ?)', array($prof, 0, 0, $promo));
							}?>	

							<div class="alert alert-success">Cours ajouté avec succèe!!!</div><?php
						}

					}


				    if (isset($_GET['enseign'])  or isset($_GET['del_ens']) or isset($_POST['modifens']) or isset($_GET['matiereel']) or isset($_GET['voir_mate']) or isset($_GET['termec']) or isset($_GET['dupliq_f'])) {

				    	if (isset($_GET['del_ens'])) {

				          $DB->delete('DELETE FROM enseignement WHERE id = ?', array($_GET['del_ens']));
				          $DB->delete('DELETE FROM salaireens WHERE numpers = ? and promo=?', array($_GET['matricule'], $_SESSION['promo']));?>

				          <div class="alert alert-success">Suppression reussie!!!</div><?php 
				        }
				        $promotion=$_SESSION['promo'];
				        if (isset($_GET['matiereel'])) {

				    		$prodm=$DB->query('SELECT enseignement.id as id, groupe.nomgr as nomgr, nomf, nommat, nomen, prenomen, codens, matiere.codem as codem, groupe.nomgr as nomgr from enseignement inner join groupe on enseignement.nomgr=groupe.nomgr inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule inner join formation on enseignement.codef=formation.codef inner join inscription on inscription.nomgr=enseignement.nomgr  where inscription.matricule=:mat and enseignement.promo=:promo and groupe.promo=:promog order by(prenomen)', array(
				    			'mat'=>$_GET['matiereel'], 'promo'=>$promotion, 'promog'=>$promotion
				    		));

				    	}elseif (isset($_GET['voir_mate'])) {

				    		$prodm=$DB->query('SELECT enseignement.id as id, groupe.nomgr as nomgr, nomf, nommat, nomen, prenomen, codens, matiere.codem as codem, groupe.nomgr as nomgr from enseignement inner join groupe on enseignement.nomgr=groupe.nomgr inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule inner join formation on enseignement.codef=formation.codef where enseignant.matricule=:code and enseignement.promo=:promo and groupe.promo=:promog order by(prenomen)', array('code'=>$_GET['voir_mate'], 'promo'=>$promotion, 'promog'=>$promotion));

				    	}elseif (isset($_GET['termec'])) {

					      $_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
					      $terme = $_GET['termec'];
					      $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
					      $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
					      $terme = strtolower($terme);

					      $prodm =$DB->query('SELECT enseignement.id as id, groupe.nomgr as nomgr, enseignement.codef as codef, nomf, nommat, nomen, prenomen, codens, matiere.codem as codem, groupe.nomgr as nomgr from enseignement inner join groupe on enseignement.nomgr=groupe.nomgr inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule inner join formation on enseignement.codef=formation.codef WHERE enseignement.promo LIKE ? and groupe.promo LIKE ? and (nomf LIKE ? or nommat LIKE ? or nomen LIKE ? or prenomen LIKE ? or groupe.nomgr LIKE ?)',array($promotion, $promotion, "%".$terme."%","%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
					      
					    }else{

				    		if (!empty($_SESSION['niveauf'])) {

					    		$prodm=$DB->query('SELECT  enseignement.id as id, groupe.nomgr as nomgr, enseignement.codef as codef, nomf, nommat, nomen, prenomen, codens, matiere.codem as codem, groupe.nomgr as nomgr from enseignement inner join groupe on enseignement.nomgr=groupe.nomgr inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule inner join formation on enseignement.codef=formation.codef where enseignement.promo=:promo and groupe.promo=:promog  and formation.niveau=:niv order by(nomf)',array('promo'=>$promotion, 'promog'=>$promotion, 'niv'=>$_SESSION['niveauf']));

					    	}else{

					    		$prodm=$DB->query('SELECT  enseignement.id as id, groupe.nomgr as nomgr, enseignement.codef as codef, nomf, nommat, nomen, prenomen, codens, matiere.codem as codem, groupe.nomgr as nomgr from enseignement inner join groupe on enseignement.nomgr=groupe.nomgr inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule inner join formation on enseignement.codef=formation.codef where enseignement.promo=:promo and groupe.promo=:promog order by(prenomen)',array('promo'=>$promotion, 'promog'=>$promotion));
					    	}

				    		

				    	}?>
		    
		    			<table class="table table-bordered table-striped table-hover align-middle">
				    		<thead class="sticky-top bg-light text-center">

				    			<form class="form">

				    				<tr>
				                    	<th colspan="5" >Liste des Cours <a class="btn btn-info" href="printdoc.php?enseigne" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

				                    		<input class="form-control" type = "search" name= "termec" placeholder="serach..." onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">
				                    	</th>

				                    	<th><input class="form-control"   type = "submit" name = "s" value = "search"></th>

				                    	<th colspan="2"><?php 

											if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='bibliothecaire') {?><a class="btn btn-warning" href="enseignement.php?ajout_ens" style="color: white;">Ajouter un cours</a><?php }?>
										</th>
				                  </tr>

								</form>

								<tr>
									<th>N°</th>
									<th>Classe</th>
									<th>Formation</th>
									<th>Matière</th>
									<th>Professeur</th>
									<th colspan="3"></th>
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
										<td><?=ucwords($formation->nomf);?></td>

										<td><?=ucwords($formation->nommat);?></td>

				                        <td><?=strtoupper($formation->nomen).' '.ucwords(strtolower($formation->prenomen));?></td>

				                        <td><?php 

											if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='bibliothecaire') {?>
				                        		<a class="btn btn-warning" href="enseignement.php?modif_ens=<?=$formation->codens;?>&codem=<?=$formation->codem;?>&nomgr=<?=$formation->nomgr;?>" >Modifier</a><?php 
				                        	}?>
				                        </td>

				                        <td><?php 

											if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='bibliothecaire') {?>

				                        		<a class="btn btn-success" href="enseignement.php?dupliq_f&nomgr=<?=$formation->nomgr;?>&codef=<?=$formation->codef;?>&codem=<?=$formation->codem;?>&codens=<?=$formation->codens;?>" onclick="return alerteV();">Reporter</a><?php
				                        	}?>
				                        </td>

				                        <td><?php

											if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='bibliothecaire') {?>

				                        		<a class="btn btn-danger" href="enseignement.php?del_ens=<?=$formation->id;?>&matricule=<?=$formation->codens;?>" onclick="return alerteS();">Annuler</a><?php
				                        	}?>
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
