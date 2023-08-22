<?php

if (isset($_GET['ajout_dev']) or isset($_POST['ajoutedev']) or isset($_GET['del_dev'])) {

	require 'ajout_devoir.php';
}?>
	
<div class="col-md-12"><?php

	if (isset($_GET['note']) or isset($_POST['groupe']) or isset($_POST['matn']) or isset($_POST['saisin'])) {

		$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe inner join enseignement on groupe.nomgr=enseignement.nomgr where enseignement.codens=:code', array('code'=>$products['matricule']));

		if (isset($_POST['groupe'])){

				$_SESSION['groupe']=$_POST['groupe'];
				$_SESSION['matn']="selectionnez";
		}

		if (isset($_POST['matn'])){

			$_SESSION['matn']=$_POST['matn'];
		}

		if (isset($_POST['groupe']) or isset($_POST['matn']) or isset($_POST['saisin'])) {

			$prodeleve=$DB->query('SELECT  *from inscription inner join eleve on eleve.matricule=inscription.matricule where inscription.nomgr=:nom', array('nom'=>$_SESSION['groupe']));

			if (isset($_POST['saisin'])) {

				$prodevoir=$DB->query('SELECT  devoir.id as id, nomdev from devoir  where devoir.codem=:code and devoir.codens=:codes and devoir.id=:codev', array('code'=>$_SESSION['matn'], 'codes'=>$products['matricule'], 'codev'=>$_POST['saisin']));

				$prodnote=$DB->query('SELECT  *from note inner join eleve on note.matricule=eleve.matricule where note.codev=:codem', array('codem'=>$_POST['saisin']));

			}else{

				$prodevoir=$DB->query('SELECT  devoir.id as id, nomdev from devoir  where devoir.codem=:code and devoir.codens=:codes', array('code'=>$_SESSION['matn'], 'codes'=>$products['matricule']));

				$prodnote=$DB->query('SELECT  *from note inner join eleve on note.matricule=eleve.matricule');
			}
			

			$prodm=$DB->query('SELECT  *from enseignement inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule where enseignant.matricule=:mat and enseignement.nomgr=:nom', array('mat'=>$products['matricule'], 'nom'=>$_SESSION['groupe']));

		}?>

		<ol>
    		<li>
				<div class="form-group">
					<form class="" method="POST" action="index.php">
					    <label class="col-sm-1 control-label">Groupe</label>
					    <div class="col-sm-1"><select type="text" name="groupe" required="" class="form-control" onchange="this.form.submit()"><?php

					    	if (isset($_POST['groupe']) or isset($_POST['matn'])) {?>

					    		<option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php
					    	}else{?>

					    		<option></option><?php
					    	}

					    	foreach ($prodgroup as $form) {?>

					    		<option><?=$form->nomgr;?></option><?php

					    	}?></select>
					    </div>
					</form>
					
					<form class="" method="POST" action="index.php">
					    <label class="col-sm-1 control-label">Matiere</label>
					    <div class="col-sm-2"><select type="text" name="matn" required="" class="form-control" onchange="this.form.submit()"><?php

					    	if (isset($_POST['groupe']) or isset($_POST['matn'])) {?>

					    		<option value="<?=$_SESSION['matn'];?>"><?=$_SESSION['matn'];?></option><?php
					    	}else{?>

					    		<option></option><?php

					    	}

					    	foreach ($prodm as $form) {?>

					    		<option value="<?=$form->codem;?>"><?=$form->nommat;?></option><?php

					    	}?></select>
					    </div>
					</form><?php

					if (!empty($_SESSION['groupe']) and $_SESSION['matn']!='selectionnez') {?>
						
						<form class="" method="POST" action="index.php">
							<div class="col-sm-2">
							<a href="ajout_devoir.php?ajout_dev" class="btn btn-warning">Ajout devoir</a></div>
						</form><?php
					}?>

					<form class="" method="POST" action="index.php">
					    <div class="col-sm-2"><select type="text" name="saisin" required="" class="form-control" onchange="this.form.submit()">
					    	<option>Saisir note</option><?php

					    foreach ($prodevoir as $saisie) {?>
					    	<option value="<?=$saisie->id;?>"><?=$saisie->nomdev;?></option><?php
					    }?></select>
					    </div>
					</form>
					
			  	</div>

			</li>
		</ol><?php
		
		if (empty($prodeleve)){ 
			
		}else{?>

			<table class="table table-hover">
	    		<thead>
					<tr class="active">
						<th>Elèves</th><?php

						if (!empty($prodevoir)) {							

							foreach ($prodevoir as $nom) {?>

								<th><?=$nom->nomdev;?></th><?php

							}
						}?>
					</tr>

				</thead>

				<tbody><?php

					foreach ($prodeleve as $formation) {?>						

							<tr>
								<td><?=strtoupper($formation->nomel).' '.ucfirst(strtolower($formation->prenomel));?><input type="text" name="matr" value="<?=$formation->matricule;?>"/></td><?php

								if (!empty($prodevoir)) {

									foreach ($prodevoir as $nom) {
										?>

										<form class="form-horizontal" method="POST" id="<?=$formation->id;?>" action="index.php">

											<td>
												<input type="hidden" name="matr" value="<?=$formation->matricule;?>"/>

												<input type="hidden" name="codev" value="<?=$nom->id;?>"/>

												<input type="number" name="note" onchange="document.getElementById('<?=$formation->id;?>').submit()" value="<?=$nom->note;?>" class="form-control" style="width: 70px;">

											</td>
										</form><?php
										
									}

								}?>

							</tr>
						<?php
					}?>

				</tbody>
				<tfoot>
					<th>15</th><?php

					if (!empty($prodevoir)) {

						foreach ($prodevoir as $nom) {?>
							<th>15</th><?php
						}
					}?>
				</tfoot>
			</table>



			<table class="table table-hover">
	    		<thead>
					<tr class="active">
						<th>Elèves</th>
						<th>Notes</th>
					</tr>

				</thead>

				<tbody><?php

					foreach ($prodnote as $formation) {?>						

							<tr>
								<td><?=strtoupper($formation->nomel).' '.ucfirst(strtolower($formation->prenomel));?></td>

								<td><?=$formation->note;?></td>
							</tr>
						<?php
					}?>

				</tbody>
			</table><?php

		}

		

	}

		if (isset($_POST['matr']) and isset($_POST['note'])) {

			$matr=addslashes(Htmlspecialchars($_POST['matr']));
			$note=addslashes(Htmlspecialchars($_POST['note']));
			$codev=addslashes(Htmlspecialchars($_POST['codev']));
			$codens=$products['matricule'];
			$codem=$_SESSION['matn'];
			
			$DB->insert('INSERT INTO note(matricule, note, codens, codem, codev, datesaisie) values( ?, ?, ?, ?, ?, now())', array($matr, $note, $codens, $codem, $codev));
		}?>
</div>
