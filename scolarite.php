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

					if (isset($_GET['scol']) or isset($_POST['codef']) or isset($_GET['ajout_scol'])) {

						if (!empty($_SESSION['niveauf'])) {

							$form=$DB->query('SELECT *from formation where niveau=:niv order by(id)', array('niv'=>$_SESSION['niveauf']));

						}else{

							$form=$DB->query('SELECT *from formation order by(id)');
						}?>

						<form id="formulaire" method="POST" action="scolarite.php">

							<fieldset><legend>Enregistrer les frais de scolarité par tranche</legend>
								<ol>

									<li>

										<label>Code formation</label>
										<select type="text" name="codef[]"multiple required=""><?php

											foreach ($form as $codef) {

												if ($codef->classe=='1') {?>

													<option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' ère';?></option><?php

												}elseif ($codef->classe=="2nde") {?>

													<option value="<?=$codef->codef;?>"><?=' '.$codef->classe;?></option><?php
												}elseif (($codef->classe>=2 and $codef->classe<=20)) {?>

													<option value="<?=$codef->codef;?>"><?=$codef->classe.' ème';?></option><?php
												}elseif ($codef->niveau=='maternelle' or $codef->niveau=='primaire') {?>

													<option value="<?=$codef->codef;?>"><?=$codef->classe;?></option><?php
												}else{?>

													<option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' '.$codef->nomf;?></option><?php
												}

											}?>
										</select>

									</li>

									<li><label>Nom de la tranche</label>
										<select type="text" name="tranche" required="">
											<option>Selectionnez!!</option><?php
											foreach ($panier->tranche() as $value) {?>
												<option value="<?=$value->nom;?>"><?=ucwords($value->nom);?></option><?php
											}?>
										</select>
									</li>

									<div style="display: flex;">
									<div style="width: 50%;">

										<li><label>Montant de la tranche*</label><input id="numberconvert" type="number"   name="montant" min="0" required="" style="font-size: 25px; width: 50%;"></li>
									</div>

									<li style="width:50%;"><label style="width:50%;"><div style="color:white; background-color: grey; font-size: 25px; color: orange; width:100%;" id="convertnumber"></div></li></label>
									</div>

									<li><label>Date limite</label>
										<input type="date" name="limite" required=""/>
									</li>

									<li><label>Année-scolaire</label>

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

							<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajouttranche" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
						</form><?php
					}

					if(isset($_POST['ajouttranche'])){

						if($_POST['codef']!="" and $_POST['tranche']!="" and $_POST['montant']!=""){
							
							$montant=addslashes(Htmlspecialchars($_POST['montant']));
							$tranche=addslashes(Htmlspecialchars($_POST['tranche']));
							$promo=addslashes(Htmlspecialchars($_POST['promo']));
							$limite=addslashes(Htmlspecialchars($_POST['limite']));

							$tabcodef=$_POST['codef'];

							foreach ($tabcodef as $codef) {
														

								$nb=$DB->querys('SELECT codef from scolarite where (codef=:code and tranche=:tranche and promo=:promo)', array(
									'code'=>$codef,
									'tranche'=>$tranche,
									'promo'=>$promo
								));

								if(!empty($nb)){?>
									<div class="alert alert-warning">Les frais sont déjà saisies pour cette promotion</div><?php

								}else{

									$DB->insert('INSERT INTO scolarite(codef, montant, tranche, limite,  promo) values( ?, ?, ?, ?, ?)', array($codef, $montant, $tranche, $limite, $promo));?>	

									<div class="alert alert-success">Frais de scolarité ajouté avec succèe!!!</div><?php
								}
							}

						}else{?>	

							<div class="alert alert-warning">Remplissez les champs vides</div><?php
						}
					}


					if (isset($_GET['scol']) or isset($_POST['ajouttranche'])  or isset($_GET['del_scol']) or isset($_GET['modif_scol'])) {

						if (isset($_GET['del_scol'])) {

						$DB->delete('DELETE FROM scolarite WHERE id = ?', array($_GET['del_scol']));?>

						<div class="alert alert-success">Suppression reussie!!!</div><?php 
						}
						$promotion=$_SESSION['promo'];

						$prodform=$DB->query('SELECT codef, nomf, classe from formation');

						if (!empty($_SESSION['niveauf'])) {

							$prodform=$DB->query('SELECT codef, nomf, classe, niveau from formation where niveau=:niv', array('niv'=>$_SESSION['niveauf']));

						}else{

							$prodform=$DB->query('SELECT codef, nomf, classe, niveau from formation');
						}

						foreach ($prodform as $value) {

							$prodm=$DB->query('SELECT classe, nomf, montant, tranche, scolarite.id as id, DATE_FORMAT(limite, \'%d/%m/%Y\') as limite from scolarite inner join formation on scolarite.codef=formation.codef where scolarite.codef=:code and scolarite.promo=:promo',array('code'=>$value->codef, 'promo'=>$promotion));

							if ($value->classe=='1') {

								$classe=ucwords($value->classe.'ère ');

							}elseif($value->classe=='2nde'){

								$classe=ucwords($value->classe);

							}elseif($value->niveau=='maternelle' or $value->niveau=='primaire'){

								$classe=ucwords($value->classe);

							}else{

								$classe=ucwords($value->classe.' ');?><?php
							}?>


					
							<table class="payement" id="tableau">
								<thead>

									<tr>
										<th height="25" colspan="6" class="info" style="text-align: center"><?='Liste des frais de scolarité'.' en '.$classe.' Année Scolaire '.($_SESSION['promo']-1).'-'.$_SESSION['promo'];?><a style="margin-left: 10px;"href="printdoc.php?scolarite=<?=$_SESSION['promo'];?>&codef=<?=$value->codef;?>&nomf=<?=$value->nomf;?>&classe=<?=$classe;?> " target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
									</tr>

									<tr>
										<th>Classe</th>
										<th>Désignation</th>
										<th>Montant</th>
										<th>Date limite</th>
										<th></th>
									</tr>

								</thead>

								<tbody><?php
								if (empty($prodm)) {
									# code...
								}else{
									$cumultranche=0;
									foreach ($prodm as $formation) {

										$cumultranche+=$formation->montant;?>

										<tr>
											<td height="30"><?php

												if ($formation->classe=='1') {?>

													<?=ucwords($formation->classe.'ere '.strtolower($formation->nomf));?><?php
												}elseif ($formation->classe=='terminale' or $formation->nomf=='maternelle') {?>

													<?=ucwords($formation->classe.' '.strtolower($formation->nomf));?><?php
												}else{?>

													<?=ucwords($formation->classe.'ème '.strtolower($formation->nomf));?><?php
												}?>
											</td>

											<td style="text-align: center"><?=$formation->tranche;?></td>

											<td style="text-align: right"><?=number_format($formation->montant,0,',',' ');?></td>

											<td style="text-align: center"><?=$formation->limite;?></td>

											<td colspan="1"><?php 

												if ($products['type']=='admin' or $products['type']=='informaticien') {?>

												<a class="btn btn-danger" href="scolarite.php?del_scol=<?=$formation->id;?>" onclick="return alerteS();">Supprimer</a><?php 
											}?>
											</td>

										</tr><?php
									}?>

									
									</tbody>

									<tfoot>
										<tr>
											<th colspan="2" height="30">Total</th>
											<th style="text-align: right"><?=number_format($cumultranche,0,',',' ');?></th>
											<th></th>
										</tr>
									</tfoot>
								</table><?php
							}
						}
					}?>
				</div>
			</div>
		</div><?php
	}
}?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
        $('#numberconvert').keyup(function(){
            $('#convertnumber').html("");

            var utilisateur = $(this).val();

            if (utilisateur!='') {
                $.ajax({
                    type: 'GET',
                    url: 'convertnumber.php?convertvers',
                    data: 'user=' + encodeURIComponent(utilisateur),
                    success: function(data){
                        if(data != ""){
                          $('#convertnumber').append(data);
                        }else{
                          document.getElementById('convertnumber').innerHTML = "<div style='font-size: 20px; text-align: center; margin-top: 10px'>Aucun utilisateur</div>"
                        }
                    }
                })
            }
      
        });
    });
  </script>
