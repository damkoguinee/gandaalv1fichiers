<?php
require '_header.php';

header('Content-Type: text/html; charset=UTF-8');

mb_internal_encoding('UTF-8'); 
mb_http_output('UTF-8'); 
mb_http_input('UTF-8'); 
mb_regex_encoding('UTF-8'); 
?><!DOCTYPE html>
<html>

<head>
  <title>GANDAAL Gestion de Scolarite</title>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
  <meta content="Page par défaut" name="description">
  <meta content="width=device-width, initial-scale=1" name="viewport">    
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head><?php

	
echo strlen('testé');

$products = $DB->querys('SELECT type, matricule, niveau FROM login WHERE pseudo= :PSEUDO',array('PSEUDO'=>$_SESSION['pseudo']));

$prodm=$DB->query('SELECT  *from enseignant inner join contact on enseignant.matricule=contact.matricule order by(prenomen)');
;?>
<table class="table table-hover table-bordered table-striped table-responsive text-center">
							    		<thead>
							    			<form class="form" method="GET" action="enseignant.php" id="suitec" name="termc">
							    				<tr>
							                    	<th colspan="8" class="info" style="text-align: center">Liste des Enseignants

							                    		<a style="margin-left: 10px;"href="printdoc.php?enseig&niveau=<?=$_SESSION['niveaufl'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

							                    		<a style="margin-left: 10px;"href="csv.php?enseignant" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
							                    	</th>
							                    </tr>

							                    <tr>
							                    	<th colspan="5">

							                    		<input class="form-control" type = "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">

							                    		<input class="form-control"  type = "hidden" name = "effnav" value = "search">

									            	</th>

													<th colspan="3"><?php 

														if ($products['type']=='admin' or $products['type']=='comptable' or $products['type']=='rh' or $products['type']=='informaticien' or $products['type']=='Proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle') {?>
															<a href="enseignant.php?ajout_en" class="btn btn-info">Ajouter un enseignant</a><?php
														}?>
													</th>
							                    	
							                  </tr>
											</form>

											<tr>
												<th>N°</th>
												<th>Matricule</th>
												<th>Prénom & Nom</th>
												<th>Téléphone</th>
												<th>Matière</th>
												<th colspan="3"></th>
											</tr>

										</thead>

										<tbody><?php
											if (empty($prodm)) {
												# code...
											}else{
												foreach ($prodm as $key=> $formation) {

													$value=$DB->querys('SELECT  *from enseignement inner join matiere on matiere.codem=enseignement.codem where codens=:code and promo=:promo order by (nomgr)', array('code'=>$formation->matricule, 'promo'=>$_SESSION['promo']));
													

													if (empty($value)) { ?>

														<tr>
															<td style="text-align: center;"><?=$key+1;?></td>
															<td style="text-align: center; font-size: 14px;"><?php
																if (isset($_GET['payempcherc'])) {?>
																	
																	<a href="comptabilite.php?payecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
																}elseif (isset($_GET['livrens'])) {?>
																	
																	<a href="emprunterlivre.php?enseig&payecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
																}else{?>

																	<a href="comptabilite.php?horairecherc=<?=$formation->matricule;?>"><?=$formation->matricule;?></a><?php
																}?>
															</td>

															<td><?=ucwords(strtolower($formation->prenomen)).' '.strtoupper($formation->nomen);?></td>

									                        <td><?=$formation->phone;?></td>

									                        <td><?=ucwords($value['nommat']);?></td>

									                        <td>
									                        	<a class="btn btn-info" href="enseignant.php?ficheens=<?=$formation->matricule;?>">+infos</a>
									                        </td>

															<td><?php 

																if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='Proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle') {?>
																	<a class="btn btn-warning" href="enseignant.php?modif_en=<?=$formation->matricule;?>&type=<?="enseignant";?>">Modifier</a><?php 
																}?>
															</td>

															<td><?php

																if ($products['type']=='admin' or $products['type']=='informaticien') {?>

								                        			<a class="btn btn-danger" href="enseignant.php?del_en=<?=$formation->matricule;?>" onclick="return alerteS();">Supprimer</a><?php
									                        	}?>
								                        	</td>

														</tr><?php
													}
												}
											}?>

												
										</tbody>
									</table>