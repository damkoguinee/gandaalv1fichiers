<?php
//require 'header.php';

if ($_SESSION['cloture']=='en-cours') {

	if (isset($_POST['saisir']) or isset($_GET['modifier']) or isset($_POST['matr']) or isset($_GET['modif_dev']) or isset($_GET['termec'])) {

		if (!isset($_POST['matr'])) {


			if (isset($_POST['saisir'])) {
				$_SESSION['saisir']=$_POST['saisir'];
			}

			if (isset($_GET['modif_dev'])) {
				$_SESSION['saisir']=$_GET['modif_dev'];
			}
		 	
		}


		if (isset($_POST['saisir']) or isset($_GET['modifier']) or isset($_POST['matr']) or isset($_GET['modif_dev']) or isset($_GET['termec'])) {

			if (isset($_GET['termec'])) {
		      $_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
		      $terme = $_GET['termec'];
		      $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
		      $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
		      $terme = strtolower($terme);

		      $prodeleve =$DB->query('SELECT *from inscription inner join eleve on eleve.matricule=inscription.matricule inner join contact on contact.matricule=inscription.matricule WHERE inscription.nomgr LIKE ? and annee LIKE ? and (eleve.matricule LIKE ? or nomel LIKE ? or prenomel LIKE ? or phone LIKE ?)',array($_SESSION['groupe'], $_SESSION['promo'], "%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
		      
		    }else{

				$prodeleve=$DB->query('SELECT  *from inscription inner join eleve on eleve.matricule=inscription.matricule where inscription.nomgr=:nom and annee=:promo order by (prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
			}

			if (isset($_POST['saisir']) or isset($_GET['modifier']) or isset($_POST['matr']) or isset($_GET['modif_dev'])) {

				$prodevoir=$DB->query('SELECT  devoir.id as id, nomdev, coef, coefcom, type from devoir  where devoir.codem=:code and devoir.codens=:codes and devoir.id=:codev and promo=:promo', array('code'=>$_SESSION['matn'], 'codes'=>$_SESSION['ens'], 'codev'=>$_SESSION['saisir'], 'promo'=>$_SESSION['promo']));

			}else{

				$prodevoir=$DB->query('SELECT  devoir.id as id, nomdev, coef, coefcom, type from devoir  where devoir.codem=:code and devoir.codens=:codes and promo=:promo', array('code'=>$_SESSION['matn'], 'codes'=>$_SESSION['ens'], 'promo'=>$_SESSION['promo']));
			}

		}
		
		if (empty($prodeleve)){ 
			
		}else{

			if (isset($_POST['matr']) and isset($_POST['note'])) {

				$matr=addslashes(Htmlspecialchars($_POST['matr']));
				$note=addslashes(Htmlspecialchars($_POST['note']));
				$codev=addslashes(Htmlspecialchars($_POST['codev']));
				$codens=$_SESSION['ens'];
				$codem=$_SESSION['matn'];

				if ( $_SESSION['niveauclassen']!='primaire') {
					$min=0;
					$max=20;
				}else{

					$min=0;
					$max=10;

				}

				if ($note<=$min or $note>$max) {?>

					<div class="alert alert-warning">les notes doivent être comprises entre <?=0;?> et <?=$max;?></div><?php 
					# code...
				}else{
						
					$prodverif=$DB->query('SELECT  matricule from note where matricule=:mat and codev=:codev', array('mat'=>$matr, 'codev'=>$_SESSION['saisir']));


					if (!empty($prodverif)) {

						if ($_SESSION['type']!='composition') {

							$DB->insert('UPDATE note SET note = ? WHERE matricule = ? and codev=?', array($note, $matr, $codev)); 
						}else{

							$DB->insert('UPDATE note SET compo = ? WHERE matricule = ? and codev=?', array($note, $matr, $codev));
						}
						
					}else{

						if ($_SESSION['type']!='composition') {

							$DB->insert('INSERT INTO note(matricule, note, codens, codem, codev, datesaisie) values( ?, ?, ?, ?, ?, now())', array($matr, $note, $codens, $codem, $codev));
						}else{

							$DB->insert('INSERT INTO note(matricule, compo, codens, codem, codev, datesaisie) values( ?, ?, ?, ?, ?, now())', array($matr, $note, $codens, $codem, $codev));
						}

						$DB->insert('INSERT INTO effectifn(matricule, codev, codem, nomgr, promo) values( ?, ?, ?, ?, ?)', array($matr, $codev, $codem, $_SESSION['groupe'], $_SESSION['promo']));
					}
				}
			
			}

			$devoir=$DB->querys('SELECT  id, codem from devoir where codens=:code and nomgroupe=:nom and id=:codev', array('code'=>$_SESSION['ens'], 'nom'=>$_SESSION['groupe'], 'codev'=>$_SESSION['saisir']));?>

			<table class="table table-hover table-bordered table-striped table-responsive text-center mt-2 ">
	    		<thead>
	    			<form class="form" method="GET" action="note.php" id="suitec" name="termc">

						<tr>
			            	<th colspan="3" class="bg-primary">

			            		<div class="container-fluid">
			            			<div class="row">

			            				<div class="col">

			            					<input class="form-control" type = "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">
			            				</div>

			            				<div class="col">


			            					<button class="btn btn-primary" class="form-control"   type = "submit" name = "s">Rechercher</button>
			            				</div>
			            			</div>
			            		</div>
					        </th>

					    </tr>

					</form>

					<tr>
						<th>N° M</th>
						<th height="30">Prénom & Nom</th><?php

						if (!empty($prodevoir)) {							

							foreach ($prodevoir as $nom) {
								if (!empty($nom->coef)) {
									
									$_SESSION['coef']=$nom->coef;
								}else{
									$_SESSION['coef']=$nom->coefcom;
								}
								$_SESSION['type']=$nom->type; ?>

								<th><a class="btn btn-info" href="note.php?modifier">Modifier</a></th><?php

							}
						}?>


								
					</tr>

				</thead>

				<tbody><?php

					foreach ($prodeleve as $formation) {

						$prodnoteverif=$DB->query('SELECT  note, compo from note inner join eleve on note.matricule=eleve.matricule where note.matricule=:mat and codev=:codev and codem=:code order by ((eleve.prenomel))', array('mat'=>$formation->matricule, 'codev'=>$_SESSION['saisir'], 'code'=>$_SESSION['matn']));

						if (isset($_GET['modifier'])) {?>						

							<tr>
								<td><?=$formation->matricule;?></td>
								<td style="text-align: left;"><?=ucfirst(strtolower($formation->prenomel)).' '.strtoupper($formation->nomel);?><input type="hidden" name="matr" value="<?=$formation->matricule;?>"/></td><?php

								if (!empty($prodevoir)) {

									foreach ($prodevoir as $nom) {
												?>

										<form method="POST" id="<?=$formation->id;?>" action="note.php">

											<td style="text-align: center; height: 26px;">
												<div style="display:flex;">
													<div>

														<input type="hidden" name="matr" value="<?=$formation->matricule;?>"/>

														<input type="hidden" name="codev" value="<?=$nom->id;?>"/><?php 

														if ( $_SESSION['niveauclassen']!='primaire') {?>

															<input class="form-control" id="pointeur" type="text" name="note" min="0" max="20"  onchange="document.getElementById('<?=$formation->id;?>').submit()"><?php 
														}else{?>

															<input class="form-control" type="text" name="note" min="0" max="10"  onchange="document.getElementById('<?=$formation->id;?>').submit()"><?php 

														}?>
													</div>

													<div>
														<table>
															<tbody><?php

																$prodnote=$DB->query('SELECT  note, compo from note inner join eleve on note.matricule=eleve.matricule where note.matricule=:mat and codev=:codev and codem=:code order by ((eleve.prenomel))', array('mat'=>$formation->matricule, 'codev'=>$devoir['id'], 'code'=>$devoir['codem']));

																if (empty($prodnote)) {

																}else{

																	foreach ($prodnote as $note) {?>
																		<tr><?php

																			if ($_SESSION['type']!='composition'){?> 
																				<td style="border:0px;"><?=$note->note;?></td><?php 
																			}else{?>
																				<td style="border:0px;"><?=$note->compo;?></td><?php 
																			}?>
																		</tr><?php 
																	}
																}?>

															</tbody>
														</table>
													</div>

											</td>
										</form><?php
										
									}

								}?>

							</tr><?php

						}else{

							if (empty($prodnoteverif)) {?>						

								<tr>
									<td><?=$formation->matricule;?></td>
									<td style="text-align: left;"><?=ucfirst(strtolower($formation->prenomel)).' '.strtoupper($formation->nomel);?><input type="hidden" name="matr" value="<?=$formation->matricule;?>"/></td><?php

									if (!empty($prodevoir)) {

										foreach ($prodevoir as $nom) {
											?>

											<form method="POST" id="<?=$formation->id;?>" action="note.php">

												<td style="text-align: center; height: 26px;">
													<div style="display:flex;">
														<div>

															<input type="hidden" name="matr" value="<?=$formation->matricule;?>"/>

															<input type="hidden" name="codev" value="<?=$nom->id;?>"/><?php 

															if ( $_SESSION['niveauclassen']!='primaire') {?>

																<input class="form-control" id="pointeur" type="text" name="note" min="0" max="20"  onchange="document.getElementById('<?=$formation->id;?>').submit()"><?php 
															}else{?>

																<input class="form-control" type="text" name="note" min="0" max="10"  onchange="document.getElementById('<?=$formation->id;?>').submit()"><?php 

															}?>
														</div>

														<div>
															<table>
																<tbody><?php

																	$prodnote=$DB->query('SELECT  note, compo from note inner join eleve on note.matricule=eleve.matricule where note.matricule=:mat and codev=:codev and codem=:code order by ((eleve.prenomel))', array('mat'=>$formation->matricule, 'codev'=>$devoir['id'], 'code'=>$devoir['codem']));

																	if (empty($prodnote)) {

																	}else{

																		foreach ($prodnote as $note) {?>
																			<tr><?php

																				if ($_SESSION['type']!='composition'){?> 
																					<td style="border:0px;"><?=$note->note;?></td><?php 
																				}else{?>
																					<td style="border:0px;"><?=$note->compo;?></td><?php 
																				}?>
																			</tr><?php 
																		}
																	}?>

																</tbody>
															</table>
														</div>

												</td>
											</form><?php
											
										}

									}?>

								</tr><?php
							}
						}
					}?>

				</tbody>
				
			</table><?php

		}

	}
}else{?>

	<div class="alert alert-warning" style="background-color: red;">Les inscriptions sont fermées contacter le chef d'établissement </div><?php
}
