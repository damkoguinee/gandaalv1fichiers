<?php
require 'header.php';?>
<body>

	<style type="text/css">
		table.calendar__table {
	  background-color: white;
	  width: 100%;
	  color: #717375;
	  font-family: helvetica;
	  border-collapse: collapse;    
	}

	.calendar__table td{
	  padding: 5px;
	  width: 150px;
	  border: 1px solid #ccc;
	  vertical-align: top;
	}

	.calendar__weekday{
	  font-weight: bold;
	  color: #000;
	  font-size: 1.1em;
	  border: 1px solid black;
	  text-align: center;
	  line-height: 30px;
	}

	.calendar__othermonth .calendar__day{
	  opacity: 0.3;
	}

	.horaireEvents{
	}

	.tdprincipal{
	  width: 100px;
	  height: 100px;
	}

	.lienemp{
	  text-decoration: none;
	}

	.descriptemp{
	  text-align: center;
	}

	.heightval1{
	  font-size: 14px;
	  height: 70px;
	  background-color: orange;
	}

	.heightval2{
	  font-size: 14px;
	  height: 100px;
	  background-color: yellow;
	}

	.heightval3{
	  height: 100px;
	  background-color: red;
	}

	.heightval4{
	  height: 100px;
	  background-color: red;
	}

	.heu td{
	  padding: 5px;
	  width: 150px;
	  border: 1px solid #ccc;
	  vertical-align: top;
	}

	table.heures {
	  background-color: white;
	  width: 100%;
	  color: #717375;
	  font-family: helvetica;
	  border-collapse: collapse;    
	}

	.heures td{
	  width: 150px;
	  height: 108px;
	  border: 1px solid #ccc;
	  vertical-align: top;
	  text-align: right;
	  font-weight: bold;
	}

	.btn-ajout{
	  float: right;
	  color: white;
	  background-color: maroon;
	  font-size: 25px;
	  text-align: center;
	  width: 30px;
	  border-radius: 50%;
	  box-shadow: 10px 2px 20px;
	  text-decoration: none;
	}

	.btn-suiv{
	  float: right;
	  color: white;
	  background-color: maroon;
	  font-size: 25px;
	  text-align: center;
	  text-decoration: none;
	  margin-right: 10px;
	  
	}
	</style><?php


if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{

    	if (isset($_GET['appel'])) {

    		require 'navabsence.php';
    	}
	}

	$firsday=$panier->getStartingDay();

	if ($firsday->format('N')==1) {
		$firsday=$firsday;
	}else{
		$firsday=$panier->getStartingDay()->modify('last monday');	
	}

	if (isset($_POST['groupe'])) {
		$_SESSION['groupe']=$_POST['groupe'];
		$_SESSION['groupep']=$_POST['groupe'];
		$_SESSION['enseigp']=array();
		$param=$_SESSION['groupe'];

	}elseif(isset($_POST['enseig'])){

		$_SESSION['enseig']=$_POST['enseig'];
		$_SESSION['enseigp']=$_POST['enseig'];
		$param=$_SESSION['enseig'];
		$_SESSION['groupep']=array();

	}else{
		if (!empty($_SESSION['groupep'])) {
			$param=$_SESSION['groupe'];
		}elseif (!empty($_SESSION['enseigp'])) {
			$param=$_SESSION['enseig'];
		}else{
			$param='';
		}

	}

	if (isset($_GET['classe'])) {

		$_SESSION['groupe']=$_GET['classe'];
	}


	$weeks=$panier->getWeeks();
	$end=(clone $firsday)->modify('+'.(6+7*($weeks-1)). 'days');

	$events=$panier->getEventsBetweenByDay($firsday, $end, $param);?>


	<div class="col" style="display: flex; margin-top: -20px;">

		<div>

			<form id="formulaire" action="planingj.php" method="POST" style="height: 35px;">
		        <ol style="margin-left: -50px; margin-top: -10px;">
		            <li><select type="text" name="groupe" required="" onchange="this.form.submit()"><?php

		                if (isset($_POST['groupe'])  or (isset($_GET['appelj']) and $_GET['appelj']=='classe')) {

		                	$appelj='classe';?>

		                    <option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php

		                }else{?>

		                    <option>Choisissez la Classe</option><?php

		                    $appelj='classe';
		                }

		                foreach ($panier->classe() as $form) {?>

		                    <option><?=$form->nomgr;?></option><?php

		                }?></select> ou
		            </li>
		        </ol>
		    </form>


		    <form id="formulaire" action="planingj.php" method="POST" style="height: 20px; margin-top: -20px;">
		        <ol style="margin-left: -50px; margin-top: -10px;">
	            <li><select type="text" name="enseig" required="" onchange="this.form.submit()"><?php

	                if (isset($_POST['enseig']) or (isset($_GET['appelj']) and $_GET['appelj']=='enseig')) {

	                	$appelj='enseig';?>

	                    <option value="<?=$_SESSION['enseig'];?>"><?=$_SESSION['enseig'];?></option><?php

	                }else{?>

	                    <option>Chisissez un enseignant</option><?php

	                    $appelj='enseig';
	                }

	                foreach ($panier->enseignant() as $prof) {?>

	                    <option value="<?=$prof->matricule;?>"><?=ucwords($prof->prenomen).' '.strtoupper($prof->nomen);?></option><?php
	                }?>

	                </select></li>
	        </ol>
	    </form><?php

			if (isset($_GET['semainep'])) {
				$datecours=$_GET['semainep'];
			}else{
				$datecours=date('W');
			}


			if (isset($_POST['groupe']) or isset($_POST['enseig'])or isset($_GET['semainep']) or isset($_GET['printnote']) or isset($_GET['appelj'])) {

				if (empty($_SESSION['groupep'])) {
					$legende=$panier->nomEnseignant($param);
				}else{
					$legende=$param;
				}?>

				<div style="display: flex;">

					<div style="width: 22%;">
						<table class="heures">
							<tbody>
								<tr><div class="calendar__weekday">Heures</div><?php

								foreach($panier->times as $values){?>

									<tr><td class="tdprincipalh"><?=$values;?></td></tr><?php

								}?>

							
							</tbody>
						</table>
					</div>

					<div style="width: 80%;"><?php

						foreach($panier->times as $keyt=> $values){?>

							<table class="calendar__table">

								<tbody><?php

									for($i=0;$i<$panier->getWeeks();$i++){?>
									

										<tr><?php

											foreach ($panier->days as $k=> $jours) {

												$datej=date('N');//Pour recuperer le jour

												if ($k==($datej-1)) {

													$date=(clone $firsday)->modify("+".($k+$i*7)."days");
													$eventsForDay=$events[$date->format('Y-m-d')]??[];

													if ($date->format('W')==$datecours) {

														if ($keyt==0) {?>
															<div class="calendar__weekday"><?=$jours;?></div><?php
														}?>

														<td class="tdprincipal"><?php

															foreach ($eventsForDay as $keye=> $event) {

																$debut=(new DateTime($event->debut))->format('H:i');
																$valdebut=(new DateTime($event->debut))->format('H');
																$valfin=(new DateTime($event->fin))->format('H');
																$deltaval=$valfin-$valdebut;

																if ($debut==$values) {

																	if ($products['niveau']>3){ //Pour permettre la modification des evenements?>

																		<a class="lienemp" href="planingj.php?id=<?=$event->id;?>&codens=<?=$event->codensp;?>&appelj=<?=$appelj;?>&nheure=<?=$deltaval;?>&hdebut=<?=$debut;?>&matn=<?=$event->codem;?>&semestre=<?=$semcourant;?>&classe=<?=$event->nomgrp;?>">

																			<div class="heightval<?=$deltaval;?>">

																				<?=(new DateTime($event->debut))->format('H:i');?>-<?=ucwords($event->name.' '.$event->nommat);?><br>

																				<div class="descriptemp"><?=$event->nomgrp;?><br>
																				<?=ucwords($event->prenomen.' '.$event->nomen[0]);?><br>
																				<?='Lieu '.ucwords($event->lieu);?></div>

																				<?=(new DateTime($event->fin))->format('H:i');?>
																			</div>
																		</a><?php
																	}else{?>
																		<div class="heightval<?=$deltaval;?>">

																			<?=(new DateTime($event->debut))->format('H:i');?>-<?=ucwords($event->name.' '.$event->nommat);?><br>

																			<div class="descriptemp"><?=$event->nomgrp;?><br>
																			<?=ucwords($event->prenomen.' '.$event->nomen[0]);?></div>

																			<?=(new DateTime($event->fin))->format('H:i');?>
																		</div><?php
																	}
																}else{}
															}?>
														</td><?php
													}
												}
											}?>
										</tr><?php
									}?>
								</tbody>
							</table><?php
						}?>
					</div>
				</div><?php
			}?>
		</div>


		<div style="width: 90%;"><?php

        	if (isset($_GET['abs']) or isset($_POST['semestren']) or isset($_POST['groupe']) or isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_POST['nheure']) or isset($_POST['matr']) or isset($_GET['modif_dev']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {

			if ($products['type']=='admin') {

				
				$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe where promo=:promo', array('promo'=>$_SESSION['promo']));

			}elseif ($products['type']=='eleve') {

				$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe where promo=:promo', array('promo'=>$_SESSION['promo']));

			}else{

				

				$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe inner join enseignement on groupe.nomgr=enseignement.nomgr where enseignement.codens=:code and groupe.promo=:promo', array('code'=>$products['matricule'], 'promo'=>$_SESSION['promo']));

			}

			if (isset($_POST['groupe'])){

				$_SESSION['groupe']=$_POST['groupe'];
				$_SESSION['semestre']="Choisissez le ";
				$_SESSION['matn']="Choisissez la matière";
				$_SESSION['hdebutj']="Ajouter Heure de debut";
				$_SESSION['nheurej']="Ajouter le nbre dheures";
			}

			if (isset($_POST['semestren'])){

				$_SESSION['semestre']=$_POST['semestren'];
				$_SESSION['matn']="CChoisissez la matière";
				$_SESSION['hdebutj']="Ajouter Heure de debut";
				$_SESSION['nheurej']="Ajouter le nbre dheures";				
			}

			if (isset($_POST['matn'])){

				$_SESSION['matn']=$_POST['matn'];
				$_SESSION['hdebutj']="Ajouter Heure de debut";

				$matiere=$DB->querys('SELECT nommat from matiere where codem=:codem', array('codem'=>$_POST['matn']));

				$_SESSION['matn1']=$matiere['nommat'];

				$prodens=$DB->querys('SELECT codens from enseignement where codem=:codem and promo=:promo', array('codem'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));

				if ($products['type']=='admin') {

					$_SESSION['ens']=$products['matricule'];
					$numens=$_SESSION['ens'];

				}elseif ($products['type']=='eleve') {
					$_SESSION['$ens']=$prodens['codens'];

					$numens=$_SESSION['$ens'];

				}else{

					

				}
			}

			if (isset($_POST['hdebut']) or isset($_GET['hdebut'])){

				if (isset($_POST['hdebut'])){

					$_SESSION['hdebutj']=$_POST['hdebut'];
				}

				if (isset($_GET['hdebut'])){

					$_SESSION['hdebutj']=$_GET['hdebut'];
				}



				$prodens=$DB->querys('SELECT codens from enseignement where codem=:codem and promo=:promo', array('codem'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));

				if ($products['type']!='admin' or $products['type']!='eleve') {

					$_SESSION['ens']=$products['matricule'];
					$numens=$_SESSION['ens'];

				}else{

					$_SESSION['$ens']=$prodens['codens'];

					$numens=$_SESSION['$ens'];

				}
			}

			if (isset($_POST['nheure'])){

				$_SESSION['nheurej']=$_POST['nheure'];
			}

			if (isset($_GET['nheure'])) {
				$_SESSION['nheurej']=$_GET['nheure'];
			}

			if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_GET['hdebut'])) {	

				if ($products['type']=='admin') {

					$prodm=$DB->query('SELECT  matiere.codem as codem, nommat from enseignement inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule where  enseignement.nomgr=:nom and enseignement.promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

					
				}elseif ($products['type']=='eleve') {

					$prodm=$DB->query('SELECT  matiere.codem as codem, nommat from enseignement inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule where  enseignement.nomgr=:nom and enseignement.promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

				}else{

					

					$prodm=$DB->query('SELECT  matiere.codem as codem, nommat from enseignement inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule where enseignant.matricule=:mat and enseignement.nomgr=:nom and enseignement.promo=:promo', array('mat'=>$products['matricule'], 'nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

					

				}

			}
		}

		if (isset($_POST['nheure']) or isset($_POST['matr']) or isset($_GET['modif_dev']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus']) or isset($_GET['appelj'])) {

			require 'ajout_absencej.php';
		}?>
    </div><?php
}?>