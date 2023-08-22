<?php
require 'headerv3.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alert alert-danger">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

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
							$DB->delete("DELETE  FROM enseignantencours where matriculens='{$matricule}' and promo='{$_SESSION['promo']}' ");
						}else{
							$DB->insert("INSERT INTO enseignantencours(matriculens,cursus,promo)VALUES(?,?,?)",array($matricule,$cursus,$_SESSION['promo']));
						}?>
						<div class="alert alert-success">Opération éffectuée avec succée!!!</div><?php

					}	

					if (isset($_GET['termec'])) {
						$_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
						$terme = $_GET['termec'];
						$terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
						$terme = strip_tags($terme); //pour supprimer les balises html dans la requête
						$terme = strtolower($terme);
						$prodm =$DB->query('SELECT *from enseignant left join contact on enseignant.matricule=contact.matricule  WHERE enseignant.matricule LIKE? or nomen LIKE ? or prenomen LIKE ? or phone LIKE ? order by(prenomen)',array("%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));					
						
					}else{

						$prodm=$DB->query('SELECT  *from enseignant left join contact on enseignant.matricule=contact.matricule order by(prenomen)');
					}?>
					<div class="row" style="height:90vh;">

					<table class="table table-hover table-bordered table-striped table-responsive text-center">
						<thead class="sticky-top bg-light">
							<form class="form" method="GET" id="suitec" name="termc">
								<tr>
									<th colspan="8" class="info" style="text-align: center">Liste des Enseignants

										<a style="margin-left: 10px;"href="printdoc.php?enseig&niveau=<?=$_SESSION['niveaufl'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

										<a style="margin-left: 10px;"href="csv.php?enseignant" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
									</th>
								</tr>

								<tr>
									<th colspan="5">

										<input class="form-control" type = "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">

										<input class="form-control"  type = "hidden" name = "effnav" value = "search">

									</th>

									<th colspan="3"><?php 

										if ($products['type']=='admin' or $products['type']=='comptable' or $products['type']=='rh' or $products['type']=='informaticien' or $products['type']=='Proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='bibliothecaire') {?>
											<a href="enseignant.php?ajout_en" class="btn btn-info">Ajouter un enseignant</a>
											<a href="enseignantconfig.php?ajout_en" class="btn btn-info">Configuration</a><?php
										}?>
									</th>
									
								</tr>
							</form>

							<tr>
								<th>N°</th>
								<th>Matricule</th>
								<th>Prénom & Nom</th>
								<th>Téléphone</th>
								<th>Niveau</th>
								<th colspan="2"></th>
							</tr>

						</thead>

						<tbody><?php
							if (empty($prodm)) {
								# code...
							}else{
								$keye=1;
								foreach ($prodm as $key=> $formation) {

									$value=$DB->querys("SELECT  *from enseignantencours where matriculens='{$formation->matricule}' and promo='{$_SESSION['promo']}'");
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
											<td><?=ucwords(strtolower($formation->prenomen)).' '.strtoupper($formation->nomen);?></td>
											<td><?=$formation->phone;?></td>
											<td>
												<select class="form-select" name="cursus" required>
													<option value="<?=$value['cursus'];?>"><?=$value['cursus'];?></option>
													<option value="maternelle">Maternelle</option>
													<option value="primaire">Primaire</option>
													<option value="secondaire">Secondaire</option>
												</select>
												<input type="hidden" name="matricule" value="<?=$formation->matricule;?>"/>
											</td>
											<td><a class="btn btn-info" href="enseignant.php?ficheens=<?=$formation->matricule;?>">+infos</a></td>

											<td><?php 
												if ($products['type']=='admin' or $products['type']=='bibliothecaire' or $products['type']=='comptable' or $products['type']=='Proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle') {?>
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