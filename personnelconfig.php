<?php
require 'headerv3.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alert alert-danger">Des autorisations sont requises pour consulter cette page</div><?php

    }else{       
        ?>

    	<div class="container-fluid">

    		<div class="row"><?php 
		    	require 'navformation.php';
		    	?>

				<div class="col-sm-12 col-md-10 col-lg-10" style="overflow: auto;"><?php 
					if (isset($_POST['matricule'])) {
						$matricule=$panier->h($_POST['matricule']);
						$etat=$panier->h($_POST['etat']);
						$cursus=$panier->h($_POST['cursus']);
						if ($etat!='Confirmer') {
							$DB->delete("DELETE  FROM personnelencours where matriculens='{$matricule}' and promo='{$_SESSION['promo']}' ");
						}else{
							$DB->insert("INSERT INTO personnelencours(matriculens,fonction,promo)VALUES(?,?,?)",array($matricule,$cursus,$_SESSION['promo']));
					        $DB->insert('UPDATE login SET type = ? WHERE matricule=?', array($cursus, $matricule));
                            
						}?>
						<div class="alert alert-success">Opération éffectuée avec succée!!!</div><?php

					}	

					if (isset($_GET['termec'])) {
						$_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
						$terme = $_GET['termec'];
						$terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
						$terme = strip_tags($terme); //pour supprimer les balises html dans la requête
						$terme = strtolower($terme);
						$prodm =$DB->query('SELECT *from personnel left join contact on numpers=contact.matricule  WHERE numpers LIKE? or nom LIKE ? or prenom LIKE ? or phone LIKE ? order by(prenom)',array("%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));					
						
					}else{

						$prodm=$DB->query('SELECT  *from personnel left join contact on numpers=contact.matricule order by(prenom)');
					}?>
					<div class="row" style="height:90vh;">

					<table class="table table-hover table-bordered table-striped table-responsive text-center">
						<thead class="sticky-top bg-light">
							<form class="form" method="GET" id="suitec" name="termc">
								<tr>
									<th colspan="8" class="info" style="text-align: center">Liste des Personnels

										<!-- <a style="margin-left: 10px;"href="printdoc.php?enseig&niveau=<?=$_SESSION['niveaufl'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

										<a style="margin-left: 10px;"href="csv.php?enseignant" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a> -->
									</th>
								</tr>

								<tr>
									<th colspan="5">

										<input class="form-control" type = "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">

										<input class="form-control"  type = "hidden" name = "effnav" value = "search">

									</th>

									<th colspan="3"><?php 

										if ($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true" OR $panier->searchRole("ROLE_COMPTABLE")=="true") {?>
											<a href="personnellist.php?ajout_en" class="btn btn-info">Ajouter un personnel</a>
											<a href="personnelconfig.php?ajout_en" class="btn btn-info">Configuration</a><?php
										}?>
									</th>
									
								</tr>
							</form>

							<tr>
								<th>N°</th>
								<th>Matricule</th>
								<th>Prénom & Nom</th>
								<th>Téléphone</th>
								<th>Fonction</th>
								<th colspan="2"></th>
							</tr>

						</thead>

						<tbody><?php
							if (empty($prodm)) {
								# code...
							}else{
								$keye=1;
								foreach ($prodm as $key=> $formation) {

									$value=$DB->querys("SELECT  *from personnelencours where matriculens='{$formation->matricule}' and promo='{$_SESSION['promo']}'");
									if (empty($value['id'])){
										$etat="Confirmer";
										$bg="success";
									}else{
										$etat="Retirer";
										$bg="warning";
									}?>
									<form action="" method="POST">

										<tr>
											<td><?=$keye;?></td>
											<td><?=$formation->matricule;?></td>
											<td><?=ucwords(strtolower($formation->prenom)).' '.strtoupper($formation->nom);?></td>
											<td><?=$formation->phone;?></td>
											<td>
												<select class="form-select" name="cursus" required>
													<option value="<?=$value['fonction'];?>"><?=$value['fonction'];?></option>
													<option value="fondation">Fondation</option>
													<option value="fondateur">Fondateur</option>
													<option value="Administrateur Général">Administrateur Général</option>
													<option value="rh">Ressources humaines</option>
													<option value="Directeur Général">Directeur Général</option>
													<option value="secrétaire">Secrétaire</option>
													<option value="Directeur du primaire">Directeur du primaire</option>
													<option value="coordonateur bloc B">coordonateur bloc B</option>									
													<option value="coordinatrice maternelle">Coordinatrice Maternelle</option>
													<option value="monitrice">Monitrice</option>
													<option value="proviseur">Proviseur</option>
													<option value="DE/Censeur">Directeur des études</option>
													<option value="Conseille a l'éducation">Conseiller à l'éducation</option>
													<option value="bibliothecaire">Bibliothécaire</option>
													<option value="comptable">Comptable</option>													
													<option value="surveillant Général">Surveillant Général</option>
													<option value="électricien">Electricien</option>
													<option value="technicien de surface">Technicien de Surface</option>
													<option value="vigile">Vigile</option>
													<option value="conseiller pédogogique">Conseiller Pédagogique</option>
													<option value="informaticien">Informaticien</option>
													<option value="cuisinier">Cuisinier</option>
													<option value="aide maitresse">Aide Maitresse</option>
													<option value="gardien">Gardien</option>
													<option value="chauffeur">Chauffeur</option>
													<option value="hygieniste">Hygièniste</option>
												</select>
												<input type="hidden" name="matricule" value="<?=$formation->matricule;?>"/>
											</td>
											<td><a class="btn btn-info" href="personnellist.php?fichepers=<?=$formation->matricule;?>">+infos</a></td>

											<td><?php 
												if ($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true" OR $panier->searchRole("ROLE_COMPTABLE")=="true") {?>
													<button onclick="return alerteV()" class="btn btn-<?=$bg;?>" name="etat" value="<?=$etat;?>"><?=$etat;?></button><?php 
												}?>
											</td>

										</tr>
									</form><?php
								}
							}?>

								
						</tbody>
					</table>
					</div>
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