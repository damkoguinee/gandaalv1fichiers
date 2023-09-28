<?php
if (isset($_GET['mateleve'])) {
	require 'headereleve.php';
}elseif (isset($_GET['enseignantplaning'])) {
	require 'headerenseignant.php';
}else{

	require 'headerv3.php';
}?>

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
	  height: 70px;
	}

	.lienemp{
	  text-decoration: none;
	}

	.descriptemp{
	  text-align: center;
	}

	.heightval1{
	  font-size: 14px;
	  position:absolute;
	}

	.heightval2{
	  font-size: 14px;
	  height: 90px;
	  background-color: green;
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
	  height: 70.5px;
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
	</style>	<?php

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

	}elseif (isset($_GET['groupeeleve'])) {
		$_SESSION['groupe']=$_GET['groupeeleve'];
		$_SESSION['groupep']=$_GET['groupeeleve'];
		$_SESSION['enseigp']=array();
		$param=$_SESSION['groupe'];

	}elseif(isset($_POST['enseig'])){

		$_SESSION['enseig']=$_POST['enseig'];
		$_SESSION['enseigp']=$_POST['enseig'];
		$param=$_SESSION['enseig'];
		$_SESSION['groupep']=array();

	}elseif(isset($_GET['enseignantplaning'])){

		$_SESSION['enseig']=$_GET['enseignantplaning'];
		$_SESSION['enseigp']=$_GET['enseignantplaning'];
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


	$weeks=$panier->getWeeks();
	$end=(clone $firsday)->modify('+'.(6+7*($weeks-1)). 'days');

	$events=$panier->getEventsBetweenByDay($firsday, $end, $param);?>


	<div class="container-fluid mt-2">

		<div class="row">
			<div class="col-sm-6 col-md-4"><?php 

				if (!isset($_GET['enseignantplaning'])) {?>

					<div class="mb-1">


						<form class="form" action="planing.php" method="POST">

							<select class="form-select" type="text" name="groupe" required="" onchange="this.form.submit()"><?php

				                if (isset($_POST['groupe']) or isset($_GET['groupeeleve']) or isset($_POST['enseig'])) {?>

				                    <option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php

				                }else{?>

				                    <option>Choisissez la Classe</option><?php
				                }

				                if (!isset($_GET['groupeeleve'])) {

					                foreach ($panier->classe() as $form) {?>

					                    <option value="<?=$form->nomgr;?>"><?=$form->nomgr;?></option><?php

					                }
					            }?>
					        </select>
					    </form>
					</div><?php 
				}?>
			</div>

			<div class="col-sm-6 col-md-4"><?php

			    if (!isset($_GET['groupeeleve'])) {?>

			    	<div class="mb-1">

				    	<form class="form" action="planing.php" method="POST">

				    		<select class="form-select" type="text" name="enseig" required="" onchange="this.form.submit()"><?php

				                if (isset($_POST['enseig']) or isset($_GET['enseignantplaning'])) {?>

				                    <option value="<?=$_SESSION['enseig'];?>"><?=$_SESSION['enseig'];?></option><?php

				                }else{?>

				                    <option>Chisissez un enseignant</option><?php
				                }

				                foreach ($panier->enseignant() as $prof) {?>

				                    <option value="<?=$prof->matricule;?>"><?=ucwords($prof->prenomen).' '.strtoupper($prof->nomen);?></option><?php
				                }?>

				            </select>
				    	</form>
				    </div><?php
			    }?>
			</div>

			<div class="col-sm-6 col-md-2"><?php 
	            if ($products['niveau']>3 and (isset($_POST['groupe']) or isset($_POST['enseig']))) {?>
	            	<div class="btn btn-info"><a href="event.php?ajoutEvent&type=<?=$param;?>" class="btn-ajout">+</a></div><?php
	            }?>
	        </div><?php

			if (isset($_GET['semainep'])) {
				$datecours=$_GET['semainep'];
			}else{
				$datecours=date('W');
			}


			if (isset($_POST['groupe']) or isset($_GET['groupeeleve']) or isset($_POST['enseig'])or isset($_GET['semainep']) or isset($_GET['printnote']) or isset($_GET['enseignantplaning'])) {
				if (empty($_SESSION['groupep'])) {
					$legende=$panier->nomEnseignant($param);
					$print='enseignant';
					$suivant='enseig';
				}else{
					$legende=$param;
					$print='classe';
					$suivant='classe';
				}?>

				<div style="font-size: 20px; font-weight: bold; text-align: center; background-color: orange; color: white; padding:6px; box-shadow: 10px;">Emploi du temps de <?=$legende;?> de la semaine <?=$datecours;?><?php 

					if (isset($_GET['mateleve']) or isset($_GET['enseignantplaning'])) {

					}else{?>

						<a class="btn btn-info" href="planing.php?printnote&semainep=<?=$datecours;?>&print=<?=$print;?>" target="_blank"><img style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>
						<a class="btn btn-info" href="exportplanning.php?horairep" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>

						<div class="btn-suiv">

							<a href="planing.php?semainep=<?=$panier->nexSemaine();?>" class="btn-suiv">></a>

							<a href="planing.php?semainep=<?=$panier->previousSemaine();?>&appelj=<?=$suivant;?>" class="btn-suiv"><</a>
							
						</div><?php 
					}?>	
				</div>	

				<div style="display: flex; margin-top: 10px 20px 3px;">

					<div style="width: 80px;">
						<table class="heures">
							<tbody>
								<tr><div class="calendar__weekday">H</div><?php

								foreach($panier->times as $values){?>

									<tr><td class="tdprincipalh"><?=$values;?></td></tr><?php

								}?>

							
							</tbody>
						</table>
					</div><?php

					for($t=0; $t<=6; $t++){?>

						<div class="m-0 p-0 wDevice"><?php

							foreach($panier->times as $keyt=> $values){?>

								<table class="calendar__table wDevice">

									<tbody class="wDevice"><?php

										for($i=0;$i<$panier->getWeeks();$i++){?>
										

											<tr><?php

												foreach ($panier->days as $k=> $jours) {

													if ($k==$t) {

														$date=(clone $firsday)->modify("+".($k+$i*7)."days");
														$eventsForDay=$events[$date->format('Y-m-d')]??[];

														if ($date->format('W')==$datecours) {

															if ($keyt==0) {?>
																<div class="calendar__weekday"><?=$jours;?></div><?php
															}?>

															<td class="tdprincipal m-0 p-0"><?php

																foreach ($eventsForDay as $keye=> $event) {

																	$debut=(new DateTime($event->debut))->format('H:i');
																	$valdebut=(new DateTime($event->debut))->format('H');
																	$valfin=(new DateTime($event->fin))->format('H');
																	$deltaval=($valfin-$valdebut)*67;

																	if ($debut==$values) {

																		if ($products['niveau']>3){ //Pour permettre la modification des evenements?>

																			<a class="lienemp" href="event.php?id=<?=$event->id;?>&codens=<?=$event->codensp;?>">

																				<div class="heightval1 wTd bg-info text-dark mt-1 p-1" style="height:<?=$deltaval;?>px">

																					<?=ucwords($event->name.' '.$event->nommat).' ';?><?=$event->nomgrp;?><br>
																					<?=ucwords($event->prenomen.' '.$event->nomen[0]);?><br>
																					<?='Lieu '.ucwords($event->lieu);?>
																				</div>
																			</a><?php
																		}else{?>
																			<div class="heightval1">

																				<?=ucwords($event->name.' '.$event->nommat);?><?=$event->nomgrp;?><br>
																				<?=ucwords($event->prenomen.' '.$event->nomen[0]);?>
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
						</div><?php
					}?>
				</div><?php
			}?>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

	<script src="js/main.js"></script>
</body>
</html>

