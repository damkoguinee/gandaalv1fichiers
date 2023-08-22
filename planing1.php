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
	  height: 125px;
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


	$weeks=$panier->getWeeks();
	$end=(clone $firsday)->modify('+'.(6+7*($weeks-1)). 'days');

	$events=$panier->getEventsBetweenByDay($firsday, $end, $param);?>


	<div class="col" style="display: flex; margin-top: -20px;">


		<form id="formulaire" action="planing1.php" method="POST" style="height: 30px;">
	        <ol style="margin-left: -50px; margin-top: -10px;">
	            <li><select type="text" name="groupe" required="" onchange="this.form.submit()"><?php

	                if (isset($_POST['groupe']) or isset($_POST['enseig'])) {?>

	                    <option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php

	                }else{?>

	                    <option>Choisissez la Classe</option><?php
	                }

	                foreach ($panier->classe() as $form) {?>

	                    <option value="<?=$form->nomgr;?>"><?=$form->nomgr;?></option><?php

	                }?></select>
	            </li>
	        </ol>
	    </form>

	    <form id="formulaire" action="planing1.php" method="POST" style="margin-left: -20px; height: 30px;">
	        <ol style="margin-left: -50px; margin-top: -10px;">
	            <li><select type="text" name="enseig" required="" onchange="this.form.submit()"><?php

	                if (isset($_POST['enseig'])) {?>

	                    <option value="<?=$_SESSION['enseig'];?>"><?=$_SESSION['enseig'];?></option><?php

	                }else{?>

	                    <option>Chisissez un enseignant</option><?php
	                }

	                foreach ($panier->enseignant() as $prof) {?>

	                    <option value="<?=$prof->matricule;?>"><?=ucwords($prof->prenomen).' '.strtoupper($prof->nomen);?></option><?php
	                }?>

	                </select><?php 
	                if ($products['niveau']>3 and (isset($_POST['groupe']) or isset($_POST['enseig']))) {?>
	                	<div class="btn-ajout"><a href="event.php?ajoutEvent&type=<?=$param;?>" class="btn-ajout">+</a></div><?php
	                }?></li>
	        </ol>
	    </form>
	</div></div><?php

	if (isset($_GET['semainep'])) {
		$datecours=$_GET['semainep'];
	}else{
		$datecours=date('W');
	}


	if (isset($_POST['groupe']) or isset($_POST['enseig'])or isset($_GET['semainep']) or isset($_GET['printnote'])) {
		if (empty($_SESSION['groupep'])) {
			$legende=$panier->nomEnseignant($param);
			$print='enseignant';
		}else{
			$legende=$param;
			$print='classe';
		}?>

		<div style="font-size: 20px; font-weight: bold; text-align: center; background-color: orange; color: white; padding:6px; box-shadow: 10px;">Emploi du temps de <?=$legende;?> de la semaine <?=$datecours;?><a href="planing1.php?printnote&semainep=<?=$datecours;?>&print=<?=$print;?>" target="_blank"><img style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

			<div class="btn-suiv">

				<a href="planing1.php?semainep=<?=$panier->nexSemaine();?>" class="btn-suiv">></a>

				<a href="planing1.php?semainep=<?=$panier->previousSemaine();?>" class="btn-suiv"><</a>
				
			</div>
		</div><?php

			for($i=0;$i<$panier->getWeeks();$i++){?>

				<div style="display: flex; margin-top: 10px 20px 3px;"><?php

					foreach ($panier->days as $k=> $jours) {

						$date=(clone $firsday)->modify("+".($k+$i*7)."days");
						$eventsForDay=$events[$date->format('Y-m-d')]??[];

						if ($date->format('W')==$datecours) {?>

							<div><?php

								if ($i!=0) {?>
									<div class="calendar__weekday" style='margin: 5px; width:180px;'><?=$jours;?></div><?php
								}

								foreach ($eventsForDay as $keye=> $event) {

									$debut=(new DateTime($event->debut))->format('H:i');
									$valdebut=(new DateTime($event->debut))->format('H');
									$valfin=(new DateTime($event->fin))->format('H');
									$deltaval=$valfin-$valdebut;

									

										if ($products['niveau']>3){ //Pour permettre la modification des evenements?>

											<a class="lienemp" href="event.php?id=<?=$event->id;?>&codens=<?=$event->codensp;?>">

												<div class="heightval<?=$deltaval;?>" style='margin: 5px; width:180px;' >

													<?=(new DateTime($event->debut))->format('H:i');?>-<?=ucwords($event->name.' '.$event->nommat);?><br>

													<div class="descriptemp"><?=$event->nomgrp;?><br>
													<?=ucwords($event->prenomen.' '.$event->nomen[0]);?><br>
													<?='Lieu '.ucwords($event->lieu);?></div>

													<?=(new DateTime($event->fin))->format('H:i');?>
												</div>
											</a><?php
											
										}else{

										}
								}?>
							</div><?php	
						}
					}?>
				</div><?php
			}
				
	}?>
 
</body>
</html>

