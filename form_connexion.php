<?php require '_header.php'?>
<!DOCTYPE html>
<html>
    
    <head>
      <title>GANDAAL Gestion de Scolarite</title>
      <meta charset="utf-8">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>

	<body><?php
		$product = $DB->querys('SELECT num_licence, nom_societe, DATE_FORMAT(date_souscription, \'%d/%m/%Y\') AS datesouscript, DATE_FORMAT(date_fin, \'%d/%m/%Y\') AS datefin, date_fin FROM licence');?>
		<div class="container-fluid"><?php

			if ($panier->licence()=="expiree") {?>
				<div class="alert alert-warning" style="font-size: 20px; color: black;">Licence expirée contacter la société gestionnaire</div><?php	
			}
			if ($panier->licence()!="expiree") {
				if ($panier->licencea()<0 and $panier->licencea()>=-2) {?>
					<div class="alert alert-warning" style="font-size: 20px; color: black;">Votre licence expire dans moins de <?=-$panier->licencea();?> mois</div><?php	
				}
			}?>
			<div class="row align-items-center py-1" style="margin: auto; margin-top: 1rem; width:80%; background-image: url('img/fond.jpg');">

				<div class="row my-1">
		          <a style="text-decoration: none" href="table.php?surplace">
		              <div class="card m-auto" style="width: 8rem;">
		                <img src="css/img/gandaal.jpg" class="card-img-top m-auto" alt="..." style="width: 6rem; height: 6rem">
		                <div class="card-bod m-auto text-center">
		                </div>
		              </div>
		          </a>
		        </div>

				<div  style="background-color: #253553; color: white; display:flex; justify-content:space-around;align-items: center; ">
					
					<div class="row m-auto my-2"><img src="css/img/logo.jpg" class="card-img-top m-auto" alt="..." style="width: 6rem; height: 6rem; border-radius:10px;"></div>			

					<div class="col-10 m-auto p-3 " style="background-color: #253553; color: white">

						<div class="text-center"><img width="100%" height="40" src="css/img/drapeau.png"></div>

						<form class="form" action="connexion1.php" method="post" name="connexion">
							<fieldset><legend class="text-center ">Acceder à votre espace <?=ucwords(strtolower($panier->etablissement()));?></legend>

								<div class="col-sm-12 col-md-6 m-auto">

									<div class="mb-1">
										<label class="form-label">Nom d'utilisateur<sup>*</sup></label> 
										<input class="form-control"  type="text" name="pseudo" id="pseudo" required=""  />
									</div>

									<div class="mb-1">
										<label class="form-label">Mot de passe<sup>*</sup></label>
										<input  class="form-control" type="password" name="mdp" id="mdp" required=""  />
									</div>
									<div class="mb-1">
										<label for="" class="form-label">Année-Scolaire<sup>*</sup></label>
										<select class="form-select" type="text" name="promo" required="">
											<option></option><?php
										
											$annee=date("Y");
											$anneei=date("Y")-1;
											for($i=$anneei;$i<=$annee ;$i++){
												$j=$i+1;?>

												<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

											}?>
										</select>
									</div>

									<input class="btn btn-info" type="submit" value="Connexion" style="cursor: pointer;" />

								</div>
							</fieldset>
						</form>
					</div>					
				</div>

				<div class="col p-3 mt-5 bg-opacity-5 border border-danger border-3 rounded" style="box-shadow: 2px 1px 10px;">

		        	<legend class="text-center" style="background-color: #253553; color: white; font-size: 18px;">À Propos de la licence et du logiciel GANDAAL</legend>
		        	<div class="col">Gandaal est un logiciel de gestion scolaire développé par la société DAMKO</div>
		            <div class="col">Siège Social: Labé République de Guinée</div>
		            <div class="col">Matricule N°: 11978 </div>
		            <div class="col">NIF: 482907474</div>		            
		            <div class="col">Tel:(00224) 628 19 66 28</div>
		            <div class="col">Email:gestcomdev@gmail.com</div>
		            <div class="col">Numéro licence: <?= $product['num_licence']; ?> </div>
		            <div class="col">Date de souscription: <?= $product['datesouscript']; ?> </div>
		            <div class="col" style="color: red;">Valable jusqu'au: <?= $product['datefin']; ?> à 23H59</div>
		            <div class="copyright"><img src="img/copyright.jpg" height="40"> </div>	
		        </div>
	    	</div>    	
		</div>    	
	</body>
	
</html>