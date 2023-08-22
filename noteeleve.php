<?php
require 'headereleve.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{

    	if (isset($_POST['matn'])) {
    		$matn=$_POST['matn'];
    	}else{

    		$matn='';

    	}

    	if (isset($_GET['classe'])){

    		$_SESSION['classel']=$_GET['classe'];
    		$_SESSION['matn']="Choisissez la matière";
    		$_SESSION['semestren']="Choisissez la matière";
    	}

    	if (isset($_POST['semestren'])){

			$_SESSION['semestren']=$_POST['semestren'];
			$_SESSION['matn']="Choisissez la matière";			
		}

		if (isset($_POST['matn'])){
			$_SESSION['matn']=$_POST['matn'];		
		}

		$matiere=$DB->querys('SELECT matricule, nommat, matiere.codem as codem, inscription.nomgr as classe from matiere inner join enseignement on enseignement.codem=matiere.codem inner join inscription on enseignement.codef=matiere.codef where matricule=:mat and matiere.codem=:code and annee=:promo ', array('mat'=>$_SESSION['matricule'], 'code'=>$matn, 'promo'=>$_SESSION['promo']));?>

		<div style="margin-left: 0px; width: 600px;">
			
			<table class="tabliste">
				<thead>
					<tr>
						<th>
							<form  action="noteeleve.php" method="POST">

								<select type="text" name="semestren" required="" onchange="this.form.submit()"><?php

							    	if (isset($_POST['semestren']) or isset($_POST['matn'])) {?>

							    		<option value="<?=$_SESSION['semestren'];?>"><?=$_SESSION['semestren'].' '.$typerepart;?></option><?php
							    	}else{?>

							    		<option>Semestre/Trimestre</option><?php
							    	}

									if ($prodtype=='semestre') {?>

										<option value="1">1er Semestre</option>
										<option value="2">2ème Semestre</option><?php

									}else{?>
										<option value="1">1er Trimestre</option>
										<option value="2">2ème Trimestre</option>
										<option value="3">3ème Trimestre</option><?php

							    	
									}?>

							    </select>
							</form>
						</th>

						<th>
							<form  action="noteeleve.php" method="POST">

								<select style="height: 30px;" type="text" name="matn" required="" onchange="this.form.submit()"><?php

							    	if (isset($_POST['matn'])) {?>

							    		<option value="<?=$_POST['matn'];?>"><?=ucwords($panier->nomMatiere($_POST['matn']));?></option><?php

							    	}else{?>

							    		<option>Selectionnez une matière</option><?php

							    	}

							    	foreach ($panier->nomMatiereCodef($_SESSION['codefcon']) as $form) {?>

							    		<option value="<?=$form->codem;?>"><?=ucwords($form->nommat);?></option><?php

							    	}?>
							    </select>
							</form>
						</th>

						<th height="30">Moy</th>
					</tr>
				</thead>

				<tbody><?php
					$moyengen=0;

					$prodmoyeg=$DB->querys('SELECT count(DISTINCT(matricule)) as coef from effectifn where nomgr=:nom and codem=:code and promo=:promo', array('nom'=>$_SESSION['classel'], 'code'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));

					$coeff=$prodmoyeg['coef'];// coeff moyenne générale


					$coefcompo=$DB->querys('SELECT  sum(coefcom) as coefc from devoir where type=:type and nomgroupe=:nom and trimes=:sem and promo=:promo and codem=:code', array('type'=>'composition', 'nom'=>$_SESSION['classel'], 'sem'=>$_SESSION['semestren'], 'promo'=>$_SESSION['promo'], 'code'=>$_SESSION['matn'])); // Coefficient compo

					$coefnote=$DB->querys('SELECT  sum(coef) as coefn from devoir where type=:type and nomgroupe=:nom and trimes=:sem and promo=:promo and codem=:code', array('type'=>'note de cours', 'nom'=>$_SESSION['classel'], 'sem'=>$_SESSION['semestren'], 'promo'=>$_SESSION['promo'], 'code'=>$_SESSION['matn']));// Coefficient note de cours

					$prodmat=$DB->query('SELECT DISTINCT(inscription.matricule) as matricule, prenomel from matiere inner join enseignement on enseignement.codem=matiere.codem inner join inscription on enseignement.codef=matiere.codef inner join eleve on eleve.matricule=inscription.matricule where inscription.matricule=:mat and inscription.nomgr=:nom and matiere.codem=:code and annee=:promo order by(prenomel)', array('mat'=>$_SESSION['matricule'], 'nom'=>$_SESSION['classel'], 'code'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));

					foreach ($prodmat as $value) {

						$prodmoyenne=$DB->query('SELECT  sum(note*coef) as note, sum(compo*coefcom) as compo, note.matricule as matricule, nomel, prenomel from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule where nomgroupe=:nom and trimes=:sem and note.matricule=:mat and devoir.codem=:code and devoir.promo=:promo ', array('nom'=>$_SESSION['classel'], 'sem'=>$_SESSION['semestren'], 'mat'=>$value->matricule, 'code'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));
						
						foreach ($prodmoyenne as $moyenne) {
							if ($coefcompo['coefc']==0) {
								$compo=0; //Moyenne composition
							}else{
								$compo=($moyenne->compo/$coefcompo['coefc']); //Moyenne composition

							}

							if ($coefnote['coefn']==0) {
								$cours=0; //Moyenne composition
							}else{
								$cours=($moyenne->note/$coefnote['coefn']);//Moyenne note de cours

							}

							if (empty($moyenne->compo)) {

								$generale=$cours; //Moyenne générale

							}elseif (empty($moyenne->note)) {

								$generale=$compo; //Moyenne générale

							}else{

								$generale=($cours+2*$compo)/3; //Moyenne générale

							}

							$moyengen+=$generale;?>

							<tr><?php
								if ($value->matricule==$_SESSION['matricule']) {

									if (!empty($moyenne->matricule)) {?>

										<td colspan="2" style="text-align: left; font-size: 14px; font-weight: bold;"><?=ucwords($matiere['nommat']);?></td>

										<td height="26" style="font-size: 16px; width: 20px; text-align: center; font-weight: bold;"><?=number_format($generale,2,',',' ');?></td><?php
									}else{?>
										<td colspan="2" style="text-align: left; font-size: 16px; font-weight: bold;"><?=ucwords($matiere['nommat']);?></td>

										<td height="26" style="font-size: 16px; width: 20px; text-align: center; font-weight: bold;">NR</td><?php
									}
								}?>
								
								
							</tr><?php
					
						}
					}?>

				</tbody>

			</table>
		</div><?php

		$prodevoir=$DB->query('SELECT  nomdev, id, codem, coef, coefcom from devoir where nomgroupe=:nom and codem=:codem and trimes=:sem and promo=:promo', array('nom'=>$_SESSION['classel'], 'codem'=>$_SESSION['matn'], 'sem'=>$_SESSION['semestren'], 'promo'=>$_SESSION['promo']));

		
		foreach ($prodevoir as $devoir) {?>

			<div class="col">

				<table class="tabliste">
					<thead>

							
						<tr><?php
							if (empty($devoir->coef)) {?>

								<th height="30"><?=$devoir->nomdev;?> coef <?=$devoir->coefcom;?><?php

							}else{?>

								<th height="30"><?=$devoir->nomdev;?> coef <?=$devoir->coef;?><?php

							}

							if(isset($_POST['devoir'])){?>

								<a href="note.php?modif_dev=<?=$devoir->id;?>"><img src="css/img/modif.jpg" width="25" height="15"></a><?php
							}?></th>
						</tr>
					</thead>

					<tbody><?php

						$nbre=0;
						$moyenn=0;
						$moyenc=0;

						foreach ($prodmat as $value) {


							$prodnote=$DB->query('SELECT  *from note inner join eleve on note.matricule=eleve.matricule inner join devoir on note.codev=devoir.id where note.matricule=:mat and codev=:codev and trimes=:sem and note.codem=:code and devoir.promo=:promo order by(prenomel)', array('mat'=>$value->matricule, 'codev'=>$devoir->id, 'sem'=>$_SESSION['semestren'], 'code'=>$devoir->codem, 'promo'=>$_SESSION['promo']));

							if (empty($prodnote)) {?>
								<tr>
									<td height="26">null</td>
								</tr><?php
							}else{

								foreach ($prodnote as $note) {	

									if ($note->type=='composition') {

										$moyenc+=$note->compo;?>

										<tr><?php 
											if ($value->matricule==$_SESSION['matricule']) {?>

												<td height="26" style="font-size: 18px; font-weight: bold;"><?=number_format($note->compo,2,',',' ');?></td><?php 
											}?>

										</tr><?php
									}else{
										$moyenn+=$note->note;?>

										<tr><?php 
											if ($value->matricule==$_SESSION['matricule']) {?>

												<td height="26" style="font-size: 18px; font-weight: bold;"><?=number_format($note->note,2,',',' ');?></td><?php 
											}?>
											

										</tr><?php

									}	

								}
							}
						}?>	

					</tbody>

				</table> 
			</div><?php
		}

		//*****************************fin pour les notes de cours et composition*********************


		//*****************************Bulletin*********************?>        

        

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



