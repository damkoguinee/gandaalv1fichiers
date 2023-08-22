<?php
require 'headerv2.php';?>

<div class="container-fluid">
	<div class="row"><?php

	   	require 'navnote.php';?>

	   	<div class="col-sm-12 col-md-10"><?php

			if (isset($_GET['ajout_dev'])) {?>

				<form id="formulaire" method="POST" action="ajout_devoir.php">

			    	<fieldset><legend>Ajouter une évaluation/intérro</legend>
				    	<ol>
							<li>
								<label>Nom</label>
								<input type="text" name="nomdev" required="" maxlength="30" />
							</li>

							<li>
								<label>Type devoir</label>
								<select type="number" name="type" required="">
							    	<option></option>
									<option value="note de cours">Note de cours</option>
									<option value="composition">Composition</option>
								</select>
							</li>

							<li>
								<label>Coefficient</label>
								<select type="number" name="coef" required=""><?php
									$i=1;
									while ($i<= 10) {?>
										<option value="<?=$i;?>"><?=$i;?></option><?php
										$i++;
									}?>
									
								</select>

								<input type="hidden" name="nomm" value="<?=$_SESSION['matn'];?>"  />

								<input type="hidden" name="nomgr" value="<?=$_SESSION['groupe'];?>"/>

								<input type="hidden" name="codens" value="<?=$_SESSION['ens'];?>"/>

								<input type="hidden" name="promo" value="<?=$_SESSION['promo'];?>"/>

								<input type="hidden" name="trim" value="<?=$_SESSION['semestre'];?>"/>
							</li>

							<li>

								<label>date</label>
							    <input type="date" name="datedev" required=""/>
							</li>
						</ol>

					</fieldset>

					<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajoutedev" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
				</form><?php
			}

			if(isset($_POST['ajoutedev'])){

				if($_POST['nomdev']!="" and $_POST['coef']!="" and $_POST['trim']!=""){
					
					$codens=addslashes(Htmlspecialchars($_POST['codens']));
					$nomdev=addslashes(Htmlspecialchars($_POST['nomdev']));
					$coef=addslashes(Htmlspecialchars($_POST['coef']));
					$trim=addslashes(Htmlspecialchars($_POST['trim']));
					$nomm=addslashes(Htmlspecialchars($_POST['nomm']));
					$nomgr=addslashes(Htmlspecialchars($_POST['nomgr']));
					$datedev=addslashes(Htmlspecialchars($_POST['datedev']));
					$type=addslashes(Htmlspecialchars($_POST['type']));

							

					$nb=$DB->querys('SELECT nomdev from devoir where type=:nom and codem=:code and nomgroupe=:nomgr and promo=:promo', array(
						'nom'=>$type,
						'code'=>$nomm,
						'nomgr'=>$nomgr,
						'promo'=>$_SESSION['promo']
					));

					if(!empty($nb)){?>
						<div class="alert alert-warning">Ce devoir existe déjà</div><?php

					}else{

						if ($type!='composition') {
							

							$DB->insert('INSERT INTO devoir(codens, nomdev, type, coef, trimes, codem, nomgroupe, datedev, promo) values( ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($codens, $nomdev, $type, $coef, $trim, $nomm, $nomgr, $datedev, $_SESSION['promo']));
						}else{

							$DB->insert('INSERT INTO devoir(codens, nomdev, type, coefcom, trimes, codem, nomgroupe, datedev, promo) values( ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($codens, $nomdev, $type, $coef, $trim, $nomm, $nomgr, $datedev, $_SESSION['promo']));
						}?>	

						<div class="alert alert-success">Dévoir ajouté avec succèe!!!</div><a href="note.php?note">Saisir une note</a><?php
					}

				}else{?>	

					<div class="alert alert-warning">Remplissez les champs vides</div><?php
				}
			}

			if (isset($_GET['del_dev'])) {

		      $DB->delete('DELETE FROM devoir WHERE id= ?', array($_GET['del_dev']));

		      $DB->delete('DELETE FROM note WHERE codev= ?', array($_GET['del_dev']));

		      $DB->delete('DELETE FROM effectifn WHERE codev= ?', array($_GET['del_dev']));?>

		      <div class="alert alert-warning">Suppression reussie!!!</div><?php 
		    }


		    if (isset($_GET['modif_dev'])) {    	

		    	$prodev=$DB->querys("SELECT *from devoir where id='{$_GET['modif_dev']}'");

		    	if ($prodev['type']=='composition') {
		    		$coef=$prodev['coefcom'];
		    	}else{
		    		$coef=$prodev['coef'];
		    	}

		    	$_SESSION['typemodifdev']=$prodev['type'];?>

		    	<form id="formulaire" method="POST" action="ajout_devoir.php?devoir">

			    	<fieldset><legend>Modifier un dévoir</legend>
				    	<ol>
							<li>
								<label>Nom du Dévoir</label>
								<input type="text" name="nomdev" value="<?=$prodev['nomdev'];?>" required="" maxlength="30" /><input type="hidden" name="id" value="<?=$prodev['id'];?>"/>
							</li>

							<li>
								<label>Type Dévoir</label>
								<select type="number" name="type" required="">
							    	<option value="<?=$prodev['type'];?>"><?=ucwords($prodev['type']);?></option>
									<option value="note de cours">Note de cours</option>
									<option value="composition">Composition</option>
								</select>
							</li>

							<li>
								<label>Coefficient</label>
								<select type="number" name="coef" required="">
									<option value="<?=$coef;?>"><?=$coef;?></option><?php
									$i=1;
									while ($i<= 1) {?>
										<option value="<?=$i;?>"><?=$i;?></option><?php
										$i++;
									}?>
									
								</select>
							</li>

							<li>

								<label>Date du Dévoir</label>
							    <input type="date" name="datedev" value="<?=$prodev['datedev'];?>" required=""/>
							</li>
						</ol>

					</fieldset>

					<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="modifdev" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
				</form><?php
			}

			if (isset($_POST['modifdev'])) {

				$nom=$_POST['nomdev'];
				$type=$_POST['type'];
				$date=$_POST['datedev'];
				$coef=$_POST['coef'];
				$id=$_POST['id'];

				if ($_SESSION['typemodifdev']!=$type) {

					$prodnotem=$DB->query("SELECT  *from note where codev='{$id}' ");

					$prodevm=$DB->querys("SELECT  *from devoir where id='{$id}' ");

					if ($type=='composition') {

						foreach ($prodnotem as $key => $value) {

							$DB->insert('UPDATE note SET note=?, compo= ? WHERE codev = ? and matricule=?', array(0, $value->note, $id, $value->matricule));
						}

						
					}else{

						foreach ($prodnotem as $key => $value) {

							$DB->insert('UPDATE note SET note=?, compo= ? WHERE codev = ? and matricule=?', array($value->compo, 0, $id, $value->matricule));
						}

						

					}
					
				}

				if ($type=='composition') {

					$DB->insert('UPDATE devoir SET nomdev=?, type= ?, datedev=?, coefcom=?, coef= ? WHERE id = ?', array($nom, $type, $date, $coef, 0, $id));
				}

				if ($type=='note de cours') {

					$DB->insert('UPDATE devoir SET nomdev=?, type= ?, datedev=?, coefcom=?, coef= ? WHERE id = ?', array($nom, $type, $date, 0, $coef, $id));
				}?>
				<div class="alert alert-success">Dévoir modifié avec succèe!!!</div><?php 
			}

	

			if (!empty($_SESSION['niveauf'])) {

				$prodmat=$DB->query('SELECT  *from groupe where niveau=:niv and promo=:promo order by(nomgr)', array('niv'=>$_SESSION['niveauf'], 'promo'=>$_SESSION['promo']));

			}else{

				$prodmat=$DB->query("SELECT *from groupe where promo='{$_SESSION['promo']}' order by(nomgr)");
			}

			if (isset($_GET['devoir'])) {?>

		    	<fieldset class="text-center" ><legend>Selectionnez la Classe</legend>

		    		<div class="container-fluid">
		    			<div class="row"><?php

					    	foreach ($prodmat as $matiere) {

					    		$niveau=$matiere->nomgr;?>
					    	 	
					        	<div class="col text-center p-2">

					        		<a href="ajout_devoir.php?classe=<?=$niveau;?>&codef=<?=$matiere->codef;?>&niveau=<?=$matiere->niveau;?>"><input type="button" value="<?=ucwords($niveau);?>" style="width: 300px; height: 60px; font-size: 16px; font-family: cursive; font-weight: bold; cursor: pointer"></a>

					        	</div><?php
					        }?>
					    </div>
					</div>
		    	</fieldset><?php
			}


			
			if (isset($_GET['classe'])) {	



				if ($products['type']=='enseignant') {
					
					$prodf=$DB->query("SELECT devoir.id as id, trimes, nomdev, type, devoir.coef as coef, coefcom, nommat, nomgroupe, datedev from devoir inner join matiere on devoir.codem=matiere.codem inner join enseignant on devoir.codens=enseignant.matricule where codens='{$products['matricule']}' and promo='{$_SESSION['promo']}' order by(nommat)");
				}else{

			    	$prodf=$DB->query("SELECT devoir.id as id, trimes, nomdev, type, devoir.coef as coef, coefcom, nommat, nomgroupe, datedev from devoir inner join matiere on devoir.codem=matiere.codem inner join enseignant on devoir.codens=enseignant.matricule where nomgroupe='{$_GET['classe']}' and devoir.promo='{$_SESSION['promo']}' order by(nommat)");
					

				} ?>

		    
				<table class="payement">
					<thead>

						<tr>
							<th colspan="8" class="info" style="text-align: center">Liste des dévoirs de la <?=$_GET['classe'];?> <a style="color: orange; font-size: 25px;" href="ajout_devoir.php?devoir"> Choisissez une Nouvelle Classe</a></th>
							<th colspan="2"><a style="color: white;" href="devoirgroupe.php?note&ajout_dev&classe=<?=$_GET['classe'];?>&codef=<?=$_GET['codef'];?>&niveau=<?=$_GET['niveau'];?>" class="btn btn-info">Ajouter un dévoir</a></th>
						</tr>
						<tr>
							<th><?=$typerepart;?></th>
							<th>Classe</th>
							<th>Matière</th>
							<th>Nom du Dévoir</th>
							<th>Type</th>
							<th>Coef</th>
							<th>Période</th>					
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody><?php
						if (empty($prodf)) {
							# code...
						}else{

							foreach ($prodf as $formation) {

							$datedev=(new dateTime($formation->datedev))->format("m");
							$datedev=$panier->obtenirLibelleMois($datedev)?>

							<tr>
								<td><?=$formation->trimes.' '.$typerepart;?></td>

								<td style="text-align:center;"><a href="formation.php?voir_elg=<?=$formation->nomgroupe;?>"><?=$formation->nomgroupe;?></a></td>

								<td><?=ucwords($formation->nommat);?></td>

								<td><?=ucwords($formation->nomdev);?></td>

								<td><?=ucwords($formation->type);?></td><?php

								if ($formation->type=='composition') {?>

									<td style="text-align: center;"><?=$formation->coefcom;?></td><?php

								}else{?>

									<td style="text-align: center;"><?=$formation->coef;?></td><?php
								}?>

								<td style="text-align:left;"><?=$datedev;?></td><?php 

		                        if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='Directeur Général' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='coordonateur bloc B' or $products['type']=='Directeur du primaire'  or $products['type']=='secrétaire') {?>

							  	
								  	<td><a href="note.php?note"><input type="button" value="Saisir les Notes" style="font-size: 16px; background-color: green; color: white; cursor: pointer"></a></td>

								  	<td><a href="ajout_devoir.php?modif_dev=<?=$formation->id;?>&codev=<?=$formation->id;?>"><input type="button" value="modifier" style="font-size: 16px; background-color: orange; color: white; cursor: pointer"></a></td>


								  	<td><a href="ajout_devoir.php?del_dev=<?=$formation->id;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="font-size: 16px; background-color: red; color: white; cursor: pointer"></a></td><?php
								}else{?>
									<td></td>
									<td></td>
									<td></td><?php 
								}?>

							</tr><?php
						}
					}?>

				
				</tbody>
			</table><?php 
		}?>
	</div>
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