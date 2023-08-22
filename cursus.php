<?php
require 'headerv2.php';
if (isset($_SESSION['pseudo'])) {
				
	if ($products['niveau']<1) {?>

		<div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

	}else{?>

		<div class="container-fluid">
			<div class="row"><?php 
				require 'navformation.php'; ?>
				<div class="col-sm-12 col-md-10" style="overflow:auto;"><?php
					if (isset($_GET['ajout_c'])) {

						if ($products['type']=='admin' or $products['type']=='informaticien') {?>
							<form class="form mx-2 py-2 bg-light" method="POST" action="cursus.php">

							    <legend>Ajouter un cursus scolaire</legend>
								<div class="row mb-1">	
									<label class="form-label px-0">Nom du Cursus</label>
									<select class="form-select" type="text" name="cursus" required="">
										<option></option>
										<option value="creche">Crèche</option>
										<option value="maternelle">Maternelle</option>
										<option value="primaire">Primaire</option>
										<option value="college">College</option>
										<option value="lycee">Lycee</option>
										<option value="universite">Université</option>
										<option value="professionnelle">Ecole-Professionnelle</option>
									</select>
								</div>
								<div class="row mb-1">	
									<label class="form-label px-0 ">Type de Fonctionnement</label>
									<select class="form-select" type="text" name="repart" required="">
										<option></option>
										<option value="trimestre">Trimestre</option>
										<option value="semestre">Semestre</option>
									</select>
								</div>
								<button class="btn btn-primary" type="submit" name="ajouttranche" onclick="return alerteV();">Ajouter</button>
							</form><?php 
						}
					}

					if(isset($_POST['ajouttranche'])){

						if($_POST['cursus']!=""){

							$cursus=addslashes(Htmlspecialchars($_POST['cursus']));
							$length=strlen($cursus);
							$length=$length-3;
							$codecursus=substr($cursus, 0, -$length);								

							$nb=$DB->querys('SELECT nom from cursus where (nom=:nom)', array(
								'nom'=>$cursus
							));

							if(!empty($nb)){?>
								<div class="alert alert-warning">Ce cursus existe</div><?php

							}else{

								$DB->insert('INSERT INTO cursus(codecursus, nom) values(?, ?)', array($codecursus, $cursus));?>	

								<div class="alert alert-success">Cursus ajouté avec succèe!!!</div><?php
							}

							$promo=$_SESSION['promo'];
									

							$nb=$DB->querys('SELECT type from repartition where (promo=:promo and codecursus=:code)', array(
								'promo'=>$promo,
								'code'=>$codecursus
							));

							if(!empty($nb)){

								$DB->insert('UPDATE repartition SET type=? WHERE promo = ? and codecursus=?', array($_POST['repart'], $promo, $codecursus));?>	

								<div class="alert alert-success">Type modifié avec succèe!!!</div><?php

							}else{

								$DB->insert('INSERT INTO repartition(codecursus, type, promo) values(?, ?, ?)', array($codecursus, $_POST['repart'], $promo));?>	

								<div class="alert alert-success">Type ajouté avec succèe!!!</div><?php
							}
						}else{?>	

							<div class="alert alert-warning">Remplissez les champs vides</div><?php
						}
					}

					if (isset($_GET['del_scol'])) {

						$DB->delete('DELETE FROM cursus WHERE id = ?', array($_GET['del_scol']));

						$DB->delete('DELETE FROM repartition WHERE codecursus = ?', array($_GET['del_scol']));?>

						<div class="alert alert-success">Suppression reussie!!!</div><?php 
					}


	    			if (isset($_GET['cursus']) or isset($_POST['cursus'])  or isset($_GET['del_scol']) or isset($_GET['modif_scol'])) {  	
		        	

		    			$prodm=$DB->query("SELECT cursus.codecursus as id, nom, type from cursus inner join repartition on repartition.codecursus=cursus.codecursus where promo='{$_SESSION['promo']}'  order by(cursus.id)");?>
		    
		    			<table class="table table-bordered table-hover table-striped">
							<thead>

								<tr>
									<th colspan="4" class="bg-info"><?='Liste des Cursus'.' Année Scolaire '.($_SESSION['promo']-1).'-'.$_SESSION['promo'];?> <a class="btn btn-warning" href="cursus.php?ajout_c">Ajouter un cursus</a></th>
								</tr>

								<tr>
									<th>Niveau</th>
									<th>Fonctionnement</th>
									<th>Voir</th>
									<th></th>
								</tr>

							</thead>

							<tbody><?php
								if (empty($prodm)) {
									# code...
								}else{
									$cumultranche=0;
									foreach ($prodm as $formation) {?>

										<tr>

											<td><?=ucwords($formation->nom);?></td>

											<td><?=ucwords($formation->type);?></td>

											<td>
												<a class="btn btn-info" href="formation.php?voir_cursus=<?=$formation->nom;?>"><?=$_SESSION['typeel'];?></a>
											</td>

											<td colspan="1"><?php 

												if ($products['type']=='admin' or $products['type']=='informaticien') {?>

													<a class="btn btn-danger" href="cursus.php?del_scol=<?=$formation->id;?>" onclick="return alerteS();">Annuler</a><?php 
												}?>
											</td>

										</tr><?php
									}?>

								
								</tbody>

							
						</table>
					</div>
				</div><?php
			}
		}
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
