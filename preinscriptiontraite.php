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

			if (isset($_GET['del'])) {

				$DB->delete('DELETE FROM elevepreinscription WHERE matricule = ?', array($_GET['del']));

				$DB->delete('DELETE FROM matricule WHERE matricule = ?', array($_GET['del']));?>

				<div class="alert alert-success">Suppression reussie!!!</div><?php 

			}

			if (isset($_GET['termec']) and empty($_SESSION['searchreinscript'])) {
				$_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
				$terme = $_GET['termec'];
				$terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
				$terme = strip_tags($terme); //pour supprimer les balises html dans la requête
				$terme = strtolower($terme);

				$prodeleve =$DB->query('SELECT * from elevepreinscription WHERE (matricule LIKE ? or nomel LIKE ? or prenomel LIKE ? or phone LIKE ?)', array("%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
			
			}else{

				$prodeleve =$DB->query("SELECT * from elevepreinscription order by(etat) ");

			}?>
			
			<table class="table table-hover table-bordered table-striped table-responsive">

				<thead>
					<tr><th colspan="9">Liste des élèves pré-inscrits</th></tr>
					<tr>
						<th colspan="9">
							<form method="GET" action="preinscriptiontraite.php" id="suitec" name="termc">
								<div class="container-fluid">
									<div class="row">
										<div class="col-sm-12 col-md-6"><?php 
											if (isset($_POST['termc'])) {?>
												<input class="form-control" type="text" name="termec" value="<?=$_POST['termc'];?>" onchange="this.form.submit()" ><?php 
											}else{?>
												<input class="form-control" type="text" name="termec" onchange="this.form.submit()" ><?php
											}?>
										</div>

										<div class="col-sm-12 col-md-6"><a class="btn btn-info" href="preinscription.php?ajoute">Ajouter un élève</a>
										</div>
										
									</div>
								</div>
							</form>
						</th>
					<tr>
					<tr>
						<th>N°</th>
						<th>Matricule</th>
						<th>Prénom & Nom</th>
						<th>Né(e)</th>
						<th>Téléphone</th>
						<th>Classe</th>
						<th colspan="3">Actions</th>
					</tr>
				</thead>
				<tbody><?php
					if (empty($prodeleve)) {
						
					}else{

						foreach ($prodeleve as $key=> $eleve) {
							if ($eleve->etat=='traite') {
								$color='success';
								$etat="traite";
							}else{
								$color='';
								$etat="nontraite";
							}?>

							<tr>
								<td class="text-<?=$color;?>" style="text-align: center; color:<?=$color;?>"><?=$key+1;?></td>
								<td class="text-<?=$color;?>" style="text-align: center; color:<?=$color;?>"><?=$eleve->matricule;?></td>
								<td class="text-<?=$color;?>" style="color:<?=$color;?>"><?=ucwords(strtolower($eleve->prenomel)).' '.strtoupper($eleve->nomel);?></td>
								<td class="text-<?=$color;?>" style=" text-align: center; color:<?=$color;?>"><?=$eleve->naissance;?></td>
								<td class="text-<?=$color;?>" style="color:<?=$color;?>"><?=$eleve->phone;?></td>
								<td class="text-<?=$color;?>" style="text-align: center; color:<?=$color;?>; width: 5%;"><?=$eleve->nomgr;?></td>

								<td><?php if ($etat!='traite' and $_SESSION['type']!='bibliothecaire') {?>
									<a href="ajout_eleve.php?preinscris=<?=$eleve->matricule;?>&ajoute&note&promo=<?=$_SESSION['promo'];?>" class="btn btn-success m-auto">Finaliser</a><?php 
								}?>
								</td>

								<td><?php if ($etat!='traite') {
								}?>
								</td>

								<td><?php

									if ($products['type']=='admin' or $products['type']=='comptable'  or $products['type']=='bibliothecaire') {?>

										<a class="btn btn-danger" href="preinscriptiontraite.php?del=<?=$eleve->matricule;?>" onclick="return alerteS();">Supprimer</a><?php 
									}?>
								</td>

							</tr><?php
						}
					}?>
				</tbody>
			</table>
		</div>
	</div>
<div>

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