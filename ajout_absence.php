<?php
//require 'header.php';

if ($_SESSION['cloture']=='en-cours') {?>

	<div class="col" style="display: flex; margin-top: -30px; margin-left: -80px;">

		<div><?php 

			if (isset($_POST['nheure']) or isset($_POST['matr']) or isset($_GET['modif_dev'])) {

				if (!isset($_POST['matr'])) {


					if (isset($_POST['nheure'])) {
						$_SESSION['nheure']=$_POST['nheure'];
					}

					if (isset($_GET['modif_dev'])) {
						$_SESSION['nheure']=$_GET['modif_dev'];
					}
				 	
				}


				if (isset($_POST['nheure']) or isset($_POST['matr']) or isset($_GET['modif_dev'])) {



					$prodeleve=$DB->query('SELECT  *from inscription inner join eleve on eleve.matricule=inscription.matricule where inscription.nomgr=:nom and annee=:promo order by (prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

				}

				$hdebut=$_SESSION['hdebut'];
				$nheure=$_SESSION['nheure'];
				$dateabs = date('Y-m-d');
				$semestre=$_SESSION['semestre'];
				$classe=$_SESSION['groupe'];
				$promo=$_SESSION['promo'];

				$prodens=$DB->querys('SELECT codens from enseignement where codem=:codem and promo=:promo', array('codem'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));

				
				if ($products['type']=='enseignant') {

					$_SESSION['ens']=$products['matricule'];
					$codens=$_SESSION['ens'];

				}else{

					$_SESSION['ens']=$prodens['codens'];

					$codens=$_SESSION['ens'];

				}
				
				
				if (isset($_POST['matr']) and isset($_POST['appel'])) {

					$matr=addslashes(Htmlspecialchars($_POST['matr']));
					$appel=addslashes(Htmlspecialchars($_POST['appel']));
					$codem=$_SESSION['matn'];
			
			
			
					$prodverif=$DB->query('SELECT  matricule from absence where matricule=:mat and codem=:codem and date_format(dateabs,\'%Y-%m-%d \')=:dateabs and hdebut=:hdeb', array('mat'=>$matr, 'codem'=>$_SESSION['matn'], 'dateabs'=>$dateabs, 'hdeb'=>$hdebut));

			


					if (!empty($prodverif)) {

						$DB->delete('DELETE FROM absence WHERE matricule = ? and codem=? and date_format(dateabs,\'%Y-%m-%d \')=? and hdebut=?', array($matr, $codem, $dateabs, $hdebut));	
						
					}else{

						$DB->insert('INSERT INTO absence(matricule, codem, nomgr, codens, hdebut, nbreheure, semestre, promo, dateabs) values( ?, ?, ?, ?, ?, ?, ?, ?, now())', array($matr, $codem, $classe, $codens, $hdebut, $nheure, $semestre, $promo));
					}
				
				}




		if (isset($_POST['matr']) and isset($_POST['exclus'])) {


			$matr=addslashes(Htmlspecialchars($_POST['matr']));
			$exclus=addslashes(Htmlspecialchars($_POST['exclus']));
			$codem=$_SESSION['matn'];
			
			
			
			$prodverif=$DB->query('SELECT  matricule from exclus where matricule=:mat and codem=:codem and date_format(dateexclus,\'%Y-%m-%d \')=:dateabs and hdebut=:hdeb', array('mat'=>$matr, 'codem'=>$_SESSION['matn'], 'dateabs'=>$dateabs, 'hdeb'=>$hdebut));

			


			if (!empty($prodverif)) {

				$DB->delete('DELETE FROM exclus WHERE matricule = ? and codem=? and date_format(dateexclus,\'%Y-%m-%d \')=? and hdebut=?', array($matr, $codem, $dateabs, $hdebut));					
				
			}else{

				$DB->insert('INSERT INTO exclus(matricule, codem, nomgr, codens, hdebut, motif, semestre, promo, dateexclus) values( ?, ?, ?, ?, ?, ?, ?, ?, now())', array($matr, $codem, $classe, $codens, $hdebut, $exclus, $semestre, $promo));
			}
		
		}


		if (isset($_POST['matr']) and isset($_POST['retard'])) {


			$matr=addslashes(Htmlspecialchars($_POST['matr']));
			$retard=addslashes(Htmlspecialchars($_POST['retard']));
			$codem=$_SESSION['matn'];
			
			
			
			$prodverif=$DB->query('SELECT  matricule from retard where matricule=:mat and codem=:codem and date_format(dateabs,\'%Y-%m-%d \')=:dateabs and hdebut=:hdeb', array('mat'=>$matr, 'codem'=>$_SESSION['matn'], 'dateabs'=>$dateabs, 'hdeb'=>$hdebut));

			


			if (!empty($prodverif)) {

				$DB->delete('DELETE FROM retard WHERE matricule = ? and codem=? and date_format(dateabs,\'%Y-%m-%d \')=? and hdebut=?', array($matr, $codem, $dateabs, $hdebut));					
				
			}else{

				$DB->insert('INSERT INTO retard(matricule, codem, nomgr, codens, hdebut, timeretard, semestre, promo, dateabs) values( ?, ?, ?, ?, ?, ?, ?, ?, now())', array($matr, $codem, $classe, $codens, $hdebut, $retard, $semestre, $promo));
			}
		
		}

				
				
		if (empty($prodeleve)){ 
			
		}else{?>

			<table class="payement" style=" margin-left: 90px; width: 100%;">
	    		<thead>
					<tr>
						<th>Matricule</th>
						<th height="30">Nom et Prénom</th>
						<th style="background-color: green;">Appel</th>
						<th style="background-color: orange;">Retard</th>
						<th style="background-color: red;">Exclusions</th>
					</tr>

				</thead>

				<tbody><?php

					foreach ($prodeleve as $formation) {?>						

						<tr>
							<td><?=$formation->matricule;?></td>


							<td><?=ucfirst(strtolower($formation->prenomel)).' '.strtoupper($formation->nomel);?><input type="hidden" name="matr" value="<?=$formation->matricule;?>"/></td>

							<form method="POST" action="absence.php?appelj">

								<td>
									<div style="display: flex;">
										<div style="margin-left: 50px;">

											<input type="hidden" name="matr" value="<?=$formation->matricule;?>"/>

											<input style="width: 20px;" type="checkbox" name="appel" value="abs" onchange="this.form.submit()" />
										</div>

										<div>

											<table style="height: 20px;">
												<tbody><?php

													$prodabsence=$DB->query('SELECT  absence.matricule from absence inner join eleve on absence.matricule=eleve.matricule where absence.matricule=:mat and codem=:codem and date_format(dateabs,\'%Y-%m-%d \')=:dateabs and hdebut=:hdeb order by (prenomel)', array('mat'=>$formation->matricule, 'codem'=>$_SESSION['matn'], 'dateabs'=>$dateabs, 'hdeb'=>$hdebut));

													if (empty($prodabsence)) {

													}else{

														foreach ($prodabsence as $note) {?>

															<tr>

																<td style="border: 0px;"><img  style="margin-top: 10px; height: 15px; width: 15px;" src="css/img/checkbox.jpg"></td>
															</tr><?php

														}
													}?>
												</tbody>
											</table>
										</div>
									</div>

								</td>
							</form>

							<form method="POST" action="absence.php?appelj">

								<td>
									<div style="display: flex;">
										<div>
											<input type="hidden" name="matr" value="<?=$formation->matricule;?>"/>

											<input type="number" name="retard" placeholder="min" onchange="this.form.submit()" style="width: 60px; height: 22px; border-radius: 2px; font-size: 14px; text-align: left;"/>
										</div>

										<div>

											<table>
												<tbody><?php

													$prodabsence=$DB->query('SELECT  retard.matricule, timeretard from retard inner join eleve on retard.matricule=eleve.matricule where retard.matricule=:mat and codem=:codem and date_format(dateabs,\'%Y-%m-%d \')=:dateabs and hdebut=:hdeb order by (prenomel)', array('mat'=>$formation->matricule, 'codem'=>$_SESSION['matn'], 'dateabs'=>$dateabs, 'hdeb'=>$hdebut));

								

													if (empty($prodabsence)) {

													}else{

														foreach ($prodabsence as $note) {?>

															<tr>

																<td style="border: 0px;"><?=$note->timeretard;?></td>
															</tr><?php

														}
													}?>
												</tbody>
											</table>
										</div>
									</div>

								</td>
							</form>


							<form method="POST" action="absence.php?appelj">

								<td>
									<div style="display: flex;">

										<div>
											<input type="hidden" name="matr" value="<?=$formation->matricule;?>"/>

											<select type="text" name="exclus" onchange="this.form.submit();"  style="width: 100%; height: 26px; border-radius: 2px; font-size: 14px; text-align: left;">

												<option></option>

												<option value="indiscipline caracteérisée">indiscipline caracteérisée</option>
												<option value="bagarre">Bagarre</option>

												<option value="refus doptemperer">Refus d'optemperer</option>

												<option value="absence non motivée">Absence non motivée</option>

												<option value="Retard de payements">Retard de payements</option>
												<option value="insolences">Insolences</option>
												<option value="bavardages">Bavardages</option>
												<option value="absences de fournitures">Absences de fournitures</option>

											</select>
										</div>

										<div>

											<table>
												<tbody><?php

													$prodabsence=$DB->query('SELECT  exclus.matricule, motif from exclus inner join eleve on exclus.matricule=eleve.matricule where exclus.matricule=:mat and codem=:codem and date_format(dateexclus,\'%Y-%m-%d \')=:dateabs and hdebut=:hdeb order by (prenomel)', array('mat'=>$formation->matricule, 'codem'=>$_SESSION['matn'], 'dateabs'=>$dateabs, 'hdeb'=>$hdebut));

								

													if (empty($prodabsence)) {

													}else{

														foreach ($prodabsence as $note) {?>

															<tr>
																<td style="border: 0px;"><?=$note->motif;?></td>
															</tr><?php

														}
													}?>
												</tbody>
											</table>
										</div>
									</div>
								</td>
							</form>

						</tr><?php
					}?>

				</tbody><?php

				$prodabs=$DB->querys('SELECT  count(matricule) as nbreabs from absence  where codem=:codem and date_format(dateabs,\'%Y-%m-%d \')=:dateabs and hdebut=:hdeb', array('codem'=>$_SESSION['matn'], 'dateabs'=>$dateabs, 'hdeb'=>$hdebut));

				$prodex=$DB->querys('SELECT  count(matricule) as nbreex from exclus  where codem=:codem and date_format(dateexclus,\'%Y-%m-%d \')=:dateabs and hdebut=:hdeb', array('codem'=>$_SESSION['matn'], 'dateabs'=>$dateabs, 'hdeb'=>$hdebut));

				$prodpres=$DB->querys('SELECT  count(matricule) as nbrepres from inscription where nomgr=:nom and annee=:promo', array('promo'=>$_SESSION['promo'], 'nom'=>$_SESSION['groupe']));

				$prodret=$DB->querys('SELECT  sum(timeretard) as totretard from retard  where codem=:codem and date_format(dateabs,\'%Y-%m-%d \')=:dateabs and hdebut=:hdeb', array('codem'=>$_SESSION['matn'], 'dateabs'=>$dateabs, 'hdeb'=>$hdebut));?>

				<tfoot>
					<tr>
						<th colspan="2">Synthèse</th>

						<th style="background-color: green;">Présent(s): <?=$prodpres['nbrepres']-$prodabs['nbreabs']-$prodex['nbreex'];?></th>

						<th style="background-color: orange;">Retard: <?=$prodret['totretard'];?></th>

						<th style="background-color: red;">Exclu(s): <?=$prodex['nbreex'];?></th>


					</tr>
				</tfoot>
				
			</table><?php

		}

	}

}else{?>

	<div class="alertes" style="background-color: red;">Les inscriptions sont fermées contacter le chef d'établissement </div><?php
}
