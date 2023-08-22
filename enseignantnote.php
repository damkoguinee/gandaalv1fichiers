<?php
require 'headerenseignant.php';


if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

        <div class="container"><?php

        	require 'navnoteenseignant.php';?>

		<div class="col"><?php

			if (isset($_GET['note']) or isset($_GET['enseignant']) or isset($_POST['semestren']) or isset($_POST['groupe']) or isset($_POST['matn']) or isset($_POST['devoir']) or isset($_POST['saisir']) or isset($_GET['modifier']) or isset($_POST['matr']) or isset($_GET['modif_dev']) or isset($_POST['note']) or isset($_GET['termec'])) {
				
				if ($products['type']!='admin') {

					if ($products['type']=='Secretaire' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='proviseur' or $products['type']=='Conseille a l\'éducation' or $products['type']=='Surveillant general' or $products['type']=='Comptable' or $products['type']=='informaticien') {

						if (!empty($_SESSION['niveauf'])) {

							$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe where promo=:promo and niveau=:niv order by(codef) desc', array('promo'=>$_SESSION['promo'], 'niv'=>$_SESSION['niveauf']));
							
						}else{

							$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe where promo=:promo order by(codef) desc', array('promo'=>$_SESSION['promo']));
						}

					}else{

						if (!empty($_SESSION['niveauf'])) {

							$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe inner join enseignement on groupe.nomgr=enseignement.nomgr where enseignement.codens=:code and groupe.promo=:promo and niveau=:niv order by(groupe.codef) desc', array('code'=>$products['matricule'], 'promo'=>$_SESSION['promo'], 'niv'=>$_SESSION['niveauf']));
							
						}else{

							$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe inner join enseignement on groupe.nomgr=enseignement.nomgr where enseignement.codens=:code and groupe.promo=:promo order by(groupe.codef) desc', array('code'=>$products['matricule'], 'promo'=>$_SESSION['promo']));
						}
					}

					
				}else{

					if (!empty($_SESSION['niveauf'])) {

						$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe where promo=:promo and niveau=:niv order by(codef) desc', array('promo'=>$_SESSION['promo'], 'niv'=>$_SESSION['niveauf']));
						
					}else{

						$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe where promo=:promo order by(codef) desc', array('promo'=>$_SESSION['promo']));
					}

					

				}

				if (isset($_POST['groupe'])){

					$_SESSION['groupe']=$_POST['groupe'];
					$_SESSION['semestre']="Choisissez le";
					$_SESSION['matn']="Choisissez la matière";
					$_SESSION['devoir']="Choisissez le devoir";
					$_SESSION['saisir']="Saisir une note";
				}

				if (isset($_POST['semestren'])){

					$_SESSION['semestre']=$_POST['semestren'];
					$_SESSION['matn']="Choisissez la matière";
					$_SESSION['devoir']="Choisissez le devoir";
					$_SESSION['saisir']="Saisir une note";

					
				}

				if (isset($_POST['matn'])){

					$_SESSION['matn']=$_POST['matn'];
					$_SESSION['devoir']="Choisissez le devoir";

					$matiere=$DB->querys('SELECT nommat from matiere where codem=:codem', array('codem'=>$_POST['matn']));

					$_SESSION['matn1']=$matiere['nommat'];

					$prodens=$DB->querys('SELECT codens from enseignement where codem=:codem and promo=:promo', array('codem'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));

					if ($products['type']=='admin' or $products['type']=='Secretaire' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='proviseur' or $products['type']=='Conseille a l\'éducation' or $products['type']=='Surveillant general' or $products['type']=='Comptable' or $products['type']=='informaticien') {

						$_SESSION['ens']=$prodens['codens'];

						$numens=$_SESSION['ens'];						

					}else{

						$_SESSION['ens']=$products['matricule'];
						$numens=$_SESSION['ens'];
					}
				}

				

				if (isset($_POST['devoir'])){

					$_SESSION['devoir']=$_POST['devoir'];

					$devoir=$DB->querys('SELECT nomdev from devoir where id=:code', array('code'=>$_POST['devoir']));

					$_SESSION['dev1']=$devoir['nomdev'];

					$prodens=$DB->querys('SELECT codens from enseignement where codem=:codem and promo=:promo', array('codem'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));

					if ($products['type']!='admin') {

						$_SESSION['ens']=$products['matricule'];
						$numens=$_SESSION['ens'];

					}else{

						$_SESSION['$ens']=$prodens['codens'];

						$numens=$_SESSION['$ens'];

					}
				}

				if (isset($_POST['saisir'])){

					$_SESSION['saisir']=$_POST['saisir'];
				}

				if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['devoir'])) {	

					if ($products['type']=='admin' or $products['type']=='Secretaire' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='proviseur' or $products['type']=='Conseille a l\'éducation' or $products['type']=='Surveillant general' or $products['type']=='Comptable' or $products['type']=='informaticien') {


						$prodm=$DB->query('SELECT  matiere.codem as codem, nommat from enseignement inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule where  enseignement.nomgr=:nom and enseignement.promo=:promo order by(nommat)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

						$prodev=$DB->query('SELECT  id, nomdev from devoir where nomgroupe=:nom and codem=:codem and trimes=:sem and promo=:promo ', array('nom'=>$_SESSION['groupe'], 'codem'=>$_SESSION['matn'], 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo']));

						
					}else{

						$prodm=$DB->query('SELECT  matiere.codem as codem, nommat from enseignement inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule where enseignant.matricule=:mat and enseignement.nomgr=:nom and enseignement.promo=:promo order by(nommat)', array('mat'=>$products['matricule'], 'nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

						$prodev=$DB->query('SELECT  id, nomdev from devoir where codens=:code and nomgroupe=:nom and codem=:codem and trimes=:sem and promo=:promo', array('code'=>$products['matricule'], 'nom'=>$_SESSION['groupe'], 'codem'=>$_SESSION['matn'], 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo']));

					}

				}?>

				
						
				<form id="formulaire" action="enseignantnote.php" method="POST" style="height: 70px; margin-top: 10px;">
					<ol>
		    			<li>
							<select type="text" name="groupe" required="" onchange="this.form.submit()"><?php

						    	if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['devoir']) or isset($_POST['saisir']) or isset($_GET['modifier']) or isset($_POST['note']) or isset($_GET['termec'])) {?>

						    		<option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php
						    	}else{?>

						    		<option>Choisissez la classe</option><?php
						    	}

						    	foreach ($prodgroup as $form) {?>

						    		<option><?=$form->nomgr;?></option><?php

						    	}?>
						    </select>
						</li>
					</ol>
				</form><?php

				if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['devoir']) or isset($_POST['saisir']) or isset($_GET['modifier']) or isset($_POST['note']) or isset($_GET['termec'])) {?>

					<form id="formulaire" action="enseignantnote.php" method="POST" style="height: 70px; margin-top: -30px;">
						<ol style="height: 70px; margin-top: -19px;">
							<li>

								<select type="text" name="semestren" required="" onchange="this.form.submit()"><?php

							    	if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['devoir']) or isset($_POST['saisir']) or isset($_GET['modifier']) or isset($_POST['note'])) {?>

							    		<option value="<?=$_SESSION['semestre'];?>"><?=$_SESSION['semestre'].' '.$typerepart;?></option><?php
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
							</li>
						</ol>
					</form><?php
				}

				if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['devoir']) or isset($_POST['saisir'])  or isset($_GET['modifier']) or isset($_POST['note']) or isset($_GET['termec'])) {?>
						
					<form id="formulaire" action="enseignantnote.php" method="POST" style="height: 70px;margin-top: -30px;">
						<ol style="height: 70px; margin-top: -53px;">
							<li>
								<select type="text" name="matn" required="" onchange="this.form.submit()"><?php

							    	if (isset($_POST['groupe'])) {?>

							    		<option value="<?=$_SESSION['matn'];?>">Choisissez la matière</option><?php

							    	}elseif (isset($_POST['matn']) or isset($_POST['devoir']) or isset($_POST['saisir']) or isset($_GET['modifier']) or isset($_POST['note'])) {?>

							    		<option value="<?=$_SESSION['matn'];?>"><?=$_SESSION['matn1'];?></option><?php
							    	}
							    	else{?>

							    		<option></option><?php

							    	}

							    	foreach ($prodm as $form) {?>

							    		<option value="<?=$form->codem;?>"><?=ucwords($form->nommat);?></option><?php

							    	}?>
							    </select>
							</li>
						</ol>
					</form><?php
				}

				if (isset($_POST['devoir']) or isset($_POST['matn']) or isset($_POST['saisir']) or isset($_GET['modifier']) or isset($_POST['note']) or isset($_GET['termec'])) {?>

					<form id="formulaire" action="enseignantnote.php" method="POST" style="height: 70px;margin-top: -30px;">
						<ol style="height: 70px; margin-top: -88px;">
							<li>

							    <select type="text" name="devoir" required="" onchange="this.form.submit()"><?php

							    	if (isset($_POST['matn'])) {?>

							    		<option value="<?=$_SESSION['devoir'];?>">Choisissez le devoir</option><?php

							    	}elseif (isset($_POST['devoir']) or isset($_POST['matn']) or isset($_POST['saisir']) or isset($_POST['note'])) {?>

							    		<option value="<?=$_SESSION['devoir'];?>"><?=$_SESSION['dev1'];?></option><?php
							    	}
							    	else{?>

							    		<option></option><?php
							    	}

							    	foreach ($prodev as $form) {?>

							    		<option value="<?=$form->id;?>"><?=$form->nomdev;?></option><?php

							    	}?>
							    </select>
							</li>
						</ol>
					</form>

					<form id="formulaire" action="enseignantnote.php?saisir" method="POST" style="margin-top: -30px;">
						<ol style="height: 70px; margin-top: -122px;">
							<li>
								<select type="text" name="saisir" required="" onchange="this.form.submit()"><?php

							    	if (isset($_POST['saisir'])) {?>

							    		<option value="<?=$_SESSION['saisir'];?>"><?=$_SESSION['saisir'];?></option><?php
							    	}else{?>

							    		<option>Saisir une note</option><?php
							    	}

							    	foreach ($prodev as $form) {?>

							    		<option value="<?=$form->id;?>"><?=$form->nomdev;?></option><?php

							    	}?>
							    </select>

								
								
							</li>
						</ol>

						<ol style=" margin-top: -40px;">
							<li>
								<a style="font-size: 16px; font-weight: bold; color: white; background-color: red;" href="ajout_devoir.php?ajout_dev">Ajouter ou supprimer un devoir</a>
							</li>

							<li>
								<a href="printnote.php?printnote" target="_blank" style="margin-left: 10px; cursor: pointer;"><img  style="height: 30px; width: 30px;" src="css/img/pdf.jpg"></a>

								<a href="csv.php?printnote" style="margin-left: 10px; cursor: pointer;"><img  style="height: 30px; width: 30px;" src="css/img/excel.jpg"></a>
							</li>
						</ol>
					</form><?php
				}
			}?>

		</div><?php

		if (isset($_POST['saisir']) or isset($_GET['modifier']) or isset($_POST['matr']) or isset($_GET['modif_dev']) or isset($_POST['note']) or isset($_GET['termec'])) {

			require 'ajout_note.php';
		}

		if (isset($_POST['matn']) or isset($_POST['devoir'])) {

			if (isset($_POST['matn'])) {

				$prodevoir=$DB->query('SELECT  nomdev, id, codem, coef, coefcom from devoir where codens=:code and nomgroupe=:nom and codem=:codem and trimes=:sem and promo=:promo', array('code'=>$numens, 'nom'=>$_SESSION['groupe'], 'codem'=>$_SESSION['matn'], 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo']));
				
			}

			if (isset($_POST['devoir'])) {

				$prodevoir=$DB->query('SELECT  nomdev, id, codem, coef, coefcom from devoir where codens=:code and nomgroupe=:nom and id=:codev and codem=:codem and promo=:promo', array('code'=>$numens, 'nom'=>$_SESSION['groupe'], 'codev'=>$_SESSION['devoir'], 'codem'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));
				
			}?>



			<div class="col" style="margin-left: 50px;">

				
				<table class="tabliste">
					<thead>
					<tr>
						<th>N</th>
						<th height="30">Prénom & Nom</th>
						<th height="30">Moyenne</th><?php 

						foreach ($prodevoir as $devoir) {

							if (empty($devoir->coef)) {?>

								<th height="30"><?=$devoir->nomdev;?> coef <?=$devoir->coefcom;?><?php

							}else{?>

								<th height="30"><?=$devoir->nomdev;?> coef <?=$devoir->coef;?><?php

							}

							if(isset($_POST['devoir'])){?>

								<a href="note.php?modif_dev=<?=$devoir->id;?>"><img src="css/img/modif.jpg" width="25" height="15"></a><?php
							}
						}?></th>
					</tr>
				</thead>
					<tbody><?php
						$moyengen=0;

						$prodniveaunote=$DB->querys('SELECT count(matricule) as countel, codef, niveau from inscription where nomgr=:nom and annee=:promo order by (matricule)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

	                    $niveauclasse=$prodniveaunote['niveau'];
	                    $_SESSION['niveauclassen']=$niveauclasse;

						$prodmoyeg=$DB->querys('SELECT count(DISTINCT(matricule)) as coef from effectifn where nomgr=:nom and codem=:code and promo=:promo', array('nom'=>$_SESSION['groupe'], 'code'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));

						$coeff=$prodmoyeg['coef'];// coeff moyenne générale


						$coefcompo=$DB->querys('SELECT  sum(coefcom) as coefc from devoir where type=:type and nomgroupe=:nom and trimes=:sem and promo=:promo and codem=:code', array('type'=>'composition', 'nom'=>$_SESSION['groupe'], 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo'], 'code'=>$_SESSION['matn'])); // Coefficient compo

						$coefnote=$DB->querys('SELECT  sum(coef) as coefn from devoir where type=:type and nomgroupe=:nom and trimes=:sem and promo=:promo and codem=:code', array('type'=>'note de cours', 'nom'=>$_SESSION['groupe'], 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo'], 'code'=>$_SESSION['matn']));// Coefficient note de cours

						
						$prodmat=$DB->query('SELECT  inscription.matricule as matricule, nomel, prenomel from inscription inner join eleve on eleve.matricule=inscription.matricule where nomgr=:nom and annee=:promo order by(prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

						$nbre=0;
						$moyenn=0;
						$moyenc=0;
						
						foreach ($prodmat as $matricule) {

							$prodmoyenne=$DB->querys('SELECT  sum(note*coef) as note, sum(compo*coefcom) as compo, note.matricule as matricule, nomel, prenomel from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule where nomgroupe=:nom and trimes=:sem and note.codens=:codens and note.matricule=:mat and devoir.codem=:code and devoir.promo=:promo order by(prenomel)', array('nom'=>$_SESSION['groupe'], 'sem'=>$_SESSION['semestre'], 'codens'=>$numens, 'mat'=>$matricule->matricule, 'code'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));


							if ($coefcompo['coefc']==0) {
								$compo=0; //Moyenne composition
							}else{
								$compo=($prodmoyenne['compo']/$coefcompo['coefc']); //Moyenne composition

							}

							if ($coefnote['coefn']==0) {
								$cours=0; //Moyenne composition
							}else{
								$cours=($prodmoyenne['note']/$coefnote['coefn']);//Moyenne note de cours

							}

							if (empty($prodmoyenne['compo'])) {

								$generale=$cours; //Moyenne générale

							}elseif (empty($prodmoyenne['note'])) {

								$generale=$compo; //Moyenne générale

							}else{

								if ($_SESSION['niveauclassen']=='primaire') {

                                     $generale=($compo); //Moyenne eleve
                                }else{

                                    $generale=($cours+2*$compo)/3; //Moyenne eleve

                                }

							}

							$moyengen+=$generale;?>

							<tr><?php

								if (!empty($prodmoyenne['matricule'])) {?>

									<td style="text-align: center;" height="26"><?=$matricule->matricule;?></td>

									<td style="text-align: left;" height="26"><?=ucfirst($prodmoyenne['prenomel']).' '.strtoupper($prodmoyenne['nomel']);?></td>

									<td height="26"><?=number_format($generale,2,',',' ');?></td><?php
								}else{?>
									<td style="text-align: center;" height="26"><?=$matricule->matricule;?></td>

									<td style="text-align: left;" height="26"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></td>

									<td height="26" style="color: white;">null</td><?php
								}

								foreach ($prodevoir as $devoir) {

									$prodnote=$DB->query('SELECT  *from note inner join eleve on note.matricule=eleve.matricule inner join devoir on note.codev=devoir.id where note.matricule=:mat and codev=:codev and trimes=:sem and note.codem=:code and devoir.promo=:promo order by(prenomel)', array('mat'=>$matricule->matricule, 'codev'=>$devoir->id, 'sem'=>$_SESSION['semestre'], 'code'=>$devoir->codem, 'promo'=>$_SESSION['promo']));

									if (empty($prodnote)) {?>

										<td height="26">null</td><?php
									}else{

										foreach ($prodnote as $note) {	

											if ($note->type=='composition') {

												$moyenc+=$note->compo;?>

												<td height="26"><?=number_format($note->compo,2,',',' ');?></td><?php

											}else{
												$moyenn+=$note->note;?>

											
												<td height="26"><?=number_format($note->note,2,',',' ');?></td><?php

											}	

										}
									} 
								}?>
								
								
							</tr><?php
							
						}


						if ($moyengen!=0) {?>
						 	<tr>
								<th height="30" colspan="2">Moyenne générale</th><?php

								if (!empty($coeff)) {?>
									
									<th height="31" style="text-align: right;"><?=number_format($moyengen/$coeff,2,',',' ');?></th><?php
								}else{?>
									
									<th height="31"></th><?php

								}

								foreach ($prodevoir as $devoir) {

									$prodmoyeg=$DB->querys('SELECT count(matricule) as coef from effectifn where codev=:code and nomgr=:nom and promo=:promo', array('code'=>$devoir->id, 'nom'=>$_SESSION['groupe'],'promo'=>$_SESSION['promo']));

									$coeff=$prodmoyeg['coef'];// coeff moyenne générale

									

									if (!empty($moyenn)) {

										if (!empty($coeff)) {?>

											<th height="30" style="text-align: right;"><?='  '.number_format($moyenn/$coeff,2,',',' ');?></th><?php
										}
							
										
									}else{

										if (!empty($coeff)) {?>

											<th height="30" style="text-align: right;"><?='  '.number_format($moyenc/$coeff,2,',',' ');?></th><?php
										}
									}
								}?>
							</tr><?php
						 
						} ?>

					</tbody>

				</table>
			</div><?php
		}
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


