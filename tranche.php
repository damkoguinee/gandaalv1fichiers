<?php
require 'headerv3.php';
if (isset($_SESSION['pseudo'])) {
				
	if ($products['niveau']<1) {?>

		<div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

	}else{?>

		<div class="container-fluid p-0">
			<div class="row m-0"><?php 
				require 'navformation.php'; ?>
				<div class="col-sm-12 col-md-10 p-0" ><?php

					if (isset($_GET['ajout_t'])) {
						if ($products['type']=='admin' or $products['type']=='informaticien') {?>
							<form class="form bg-secondary my-2 p-4" method="POST" action="tranche.php">									
								<div class="mb-1">
									<label class="form-label">Nom de la tranche</label>
									<select class="form-select" type="text" name="tranche" required="">
										<option>Selectionnez!!</option>
										<option value="1ere tranche">1ere Tranche</option>
										<option value="2eme tranche">2ème Tranche</option>
										<option value="3eme tranche">3ème Tranche</option>
										<option value="4eme tranche">4ème Tranche</option>
										<option value="5eme tranche">5ème Tranche</option>
										<option value="6eme tranche">6ème Tranche</option>
										<option value="7eme tranche">7ème Tranche</option>
										<option value="8eme tranche">8ème Tranche</option>
										<option value="9eme tranche">9ème Tranche</option>
										<option value="10eme tranche">10ème Tranche</option>
										<option value="11eme tranche">11ème Tranche</option>
									</select>
								</div>

								<div class="mb-1">
									<label class="form-label">Date limite</label>
									<input class="form-control" type="date" name="limite" required=""/>
								</div>

								<div class="mb-2">
									<label class="form-label">Année-scolaire</label>
									<select class="form-select" type="text" name="promo" required=""><?php
									
										$annee=date("Y")+1;

										for($i=($_SESSION['promo']-1);$i<=$annee ;$i++){
											$j=$i+1;?>

											<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

										}?>
									</select>									
								</div>
								<button class="btn btn-primary" type="submit" name="ajouttranche" onclick="return alerteV();" >Ajouter</button>
							</form><?php
						}
					}

					if(isset($_POST['ajouttranche'])){

						if($_POST['tranche']!=""){

							$tranche=addslashes(Htmlspecialchars($_POST['tranche']));
							$promo=addslashes(Htmlspecialchars($_POST['promo']));
									

							$nb=$DB->querys('SELECT nom from tranche where (nom=:tranche and promo=:promo)', array(
								'tranche'=>$tranche,
								'promo'=>$promo
							));

							if(!empty($nb)){?>
								<div class="alertes">La tranche existe pour cette promotion</div><?php

							}else{

								$DB->insert('INSERT INTO tranche(nom, promo) values(?, ?)', array($tranche, $promo));?>	

								<div class="alert alert-success">Tranche ajoutée avec succèe!!!</div><?php
							}

						}else{?>	

							<div class="alertes">Remplissez les champs vides</div><?php
						}
					}
					if (isset($_GET['scol']) or isset($_POST['ajouttranche'])  or isset($_GET['del_scol']) or isset($_GET['modif_scol'])) {

						if (isset($_GET['del_scol'])) {

							$DB->delete('DELETE FROM tranche WHERE id = ?', array($_GET['del_scol']));?>

							<div class="alert alert-success">Suppression reussie!!!</div><?php 
						}
							

						$prodm=$DB->query('SELECT id, nom from tranche  where promo=:promo order by(nom)',array('promo'=>$_SESSION['promo']));?>
					
						<table class="table table-bodered table-striped table-hover ">
							<thead class="text-center">

								<tr>
									<th colspan="2"><?='Liste des tranches'.' année scolaire '.($_SESSION['promo']-1).'-'.$_SESSION['promo'];?> <a class="btn btn-warning" href="tranche.php?ajout_t" >Ajouter une tranche</a></th>
								</tr>

								<tr>
									<th>Nom de la tranche</th>
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

											<td style="text-align: left"><?=ucwords($formation->nom);?></td>

											<td colspan="1"><?php 

												if ($products['type']=='admin' or $products['type']=='informaticien') {?>

												<a class="btn btn-danger" href="tranche.php?del_scol=<?=$formation->id;?>" onclick="return alerteS();">Annuler</a><?php }?>
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
