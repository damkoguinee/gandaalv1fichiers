<?php

if (isset($_GET['enseignant'])) {
	require 'headerenseignant.php';
}else{
	require 'headerv3.php';
}

if (isset($_SESSION['pseudo'])) {
	if (isset($_GET['enseignant'])) {
		require 'fiche_eleve.php';
	}else{
    
	    if ($products['niveau']<4) {?>

	        <div class="alert alert-danger">Des autorisations sont requises pour consulter cette page</div><?php

	    }else{?>

	    	<div class="container-fluid">

	    		<div class="row"><?php 
	    			require 'navactivites.php';?>

					<div class="col-sm-12 col-md-10"><?php 

						if (isset($_POST['valid'])) {
							 	
						 	if (!empty($_POST['nom']) and !empty($_POST['prenom']) and !empty($_POST['sexe'])) {
						 		$nom=$panier->h($_POST['nom']);
						 		$prenom=$panier->h($_POST['prenom']);
						 		$sexe=$panier->h($_POST['sexe']);			 		
						 		$naissance=$panier->h($_POST['naissance']);
						 		$pere=$panier->h($_POST['pere']);
						 		$mere=$panier->h($_POST['mere']);
						 		$tel=$panier->h($_POST['tel']);
						 		$telp=$panier->h($_POST['telp']);
						 		$telm=$panier->h($_POST['telm']);

						 		$nb=$DB->querys("SELECT count(id) as id FROM elevexterne");

						 		$anneeins=(new dateTime($_SESSION['promo']))->format("y");

								$matricule=$anneeins . '000'+($nb['id']+1);
								$initiale='ext';

				 				$prodverif=$DB->querys("SELECT id FROM elevexterne where nom='{$nom}' and prenom='{$prenom}' and naissance='{$naissance}' and pere='{$pere}' ");

				 				if (empty($prodverif['id'])) {

				 					$DB->insert('INSERT INTO elevexterne (matex, nom, prenom, sexe, naissance, pere, mere, tel, telpere, telmere,  dateenreg) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($initiale.$matricule, $nom, $prenom, $sexe, $naissance, $pere, $mere, $tel, $telp, $telm));?>

				 					<div class="alert alert-success" role="alert">élève inscrit avec succèe!!!</div><?php

				 				}else{?>

				 					<div class="alert alert-warning" role="alert">Cet élève existe</div><?php
				 				}
				 					

						 	}
						}

						if (isset($_GET['ajout'])) {?>

							<form method="POST" action="inscriptionext.php" enctype="multipart/form-data">

						  		<fieldset><legend>Inscription des élèves externes</legend>
						  			<div class="container">

							  			<div class="row">

								    		<div class="col-sm-12 col-md-6">

								    			<div class="mb-1">
									      			<label class="form-label">Nom*</label>
				                    				<input class="form-control" type="text" name="nom" required="">	
											    </div>

											    <div class="mb-1">
									      			<label class="form-label">Prénom*</label>
				                    				<input class="form-control" type="text" name="prenom" required="">	
											    </div>

											    <div class="mb-1">
											    	<label class="form-label">Sexe</label>
													<select class="form-select" name="sexe" required="" >
														<option value="m">Masculin</option> 
														<option value="f">Feminin</option>
													</select>
											    </div>

											    <div class="mb-1">
									      			<label class="form-label">Date de Naissance</label>
				                    				<input class="form-control" type="date" name="naissance">	
											    </div>

											    <div class="mb-1">
									      			<label class="form-label">Père</label>
				                    				<input class="form-control" type="text" name="pere" >	
											    </div>

											    <div class="mb-1">
									      			<label class="form-label">Mère</label>
				                    				<input class="form-control" type="text" name="mere">	
											    </div>							                
								            </div>

								    		<div class="col-sm-12 col-md-6">
								    			<div class="mb-1">
									      			<label class="form-label">Téléphone de l'élève*</label>
				                    				<input class="form-control" type="text" name="tel">	
											    </div>

											    <div class="mb-1">
									      			<label class="form-label">Téléphone du Père</label>
				                    				<input class="form-control" type="text" name="telp">	
											    </div>

											    <div class="mb-1">
									      			<label class="form-label">Téléphone de la Mère</label>
				                    				<input class="form-control" type="text" name="telm">	
											    </div>

											    <div class="mb-1"><label class="form-label">Photo élève</label>
								                	<input class="form-control" type="file" name="photo" id="photo" />
								                	<input type="hidden" value="b" name="env"/>
								              	</div>
							            	</div>
							        	</div>
							        </div>

						    		<button type="submit" name="valid" class="btn btn-primary">Valider</button>
						  		</fieldset>
							</form><?php 
						}

						if (!isset($_GET['ajout'])) {?>


							<table class="table table-hover table-bordered table-striped table-responsive">
							  <thead>

							  	<tr>
							  		<th colspan="6" scope="col" class="text-center bg-warning"><a class="text-white" href="inscriptionext.php?ajout">Ajouter un élève externe</a></th>
							        
							    </tr>
							  	<tr>
							  		<th colspan="3" scope="col" class="text-center bg-primary">Liste des élèves externes <a class="text-white" href="contrat.php?contrat" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
							        <th colspan="3" scope="col" class="text-center bg-primary">
							        	<input class="form-control me-2" id="search-user" type="search" placeholder="Search élève" aria-label="Search" >
				                		<div style="color:white; background-color: grey; font-size: 16px;" id="result-search"></div>
							        </th>
							    </tr>
							    <tr>
							      <th scope="col" class="text-center">N°</th>
							      <th scope="col" class="w-auto">Prénom & Nom</th>
							      <th scope="col">Né(e) le</th>
							      <th scope="col">Téléphone</th>
							      <th scope="col" class="text-center" colspan="2">Actions</th>
							    </tr>
							  </thead>
							  <tbody><?php 

							  	$prod=$DB->query("SELECT *FROM elevexterne ");
							  	foreach ($prod as $key => $value) {

							  		$color="";?>
								    <tr>
								      <th scope="row" class="text-center text-<?=$color;?>"><?=$key+1;?></th>

								      <td class="text-<?=$color;?>"><?=ucwords(strtolower($value->prenom)).' '.strtoupper(strtolower($value->nom));?></td>

								      <td class="text-<?=$color;?>"><?=(new dateTime($value->naissance))->format("d/m/Y");?></td>

								      <td class="text-<?=$color;?>"><?=$value->tel;?></td>

								      <td><a class="btn btn-info" href="clientinfos.php?infos=<?=$value->id;?>">+Infos</a></td>

								      <td><a class="btn btn-warning" href="client.php?update=<?=$value->id;?>" onclick="return alerteV();">Modifier</a></td>
								    </tr><?php 
								}?>
							    
							  </tbody>
							</table><?php 
						}?>

					</div>
				</div>
			</div> <?php
		}
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



<script>
    $(document).ready(function(){
        $('#search-user').keyup(function(){
            $('#result-search').html("");

            var utilisateur = $(this).val();

            if (utilisateur!='') {
                $.ajax({
                    type: 'GET',
                    url: 'searcheleve.php?elevesearch',
                    data: 'user=' + encodeURIComponent(utilisateur),
                    success: function(data){
                        if(data != ""){
                          $('#result-search').append(data);
                        }else{
                          document.getElementById('result-search').innerHTML = "<div style='font-size: 20px; text-align: center; margin-top: 10px'>Aucun utilisateur</div>"
                        }
                    }
                })
            }
      
        });
    });
  </script>
