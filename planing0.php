<?php
require '_header.php'
?><!DOCTYPE html>
<html>
<head>
  <title>GANDAAL Gestion de Scolarite</title>
  <meta charset="utf-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">    
  <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8">
  <link rel="stylesheet" href="css/form.css" type="text/css" media="screen" charset="utf-8">
</head>

<body>

	<nav class="navbar navbar-dark bg-primary mb-3">
		<a href="planing.php" class="navbar-barnd">Mon calendrier</a>
	</nav><?php
	$firsday=$panier->getStartingDay();
	if ($firsday->format('N')==1) {
		$firsday=$firsday;
	}else{
		$firsday=$panier->getStartingDay()->modify('last monday');	
	}

	$weeks=$panier->getWeeks();
	$end=(clone $firsday)->modify('+'.(6+7*($weeks-1)). 'days');

	$events=$panier->getEventsBetweenByDay($firsday, $end);?>

	<div class="d-flex flex-row-items-center justify-content-between mx-sm-3"><?php
		if (isset($_GET['moisp'])) {?>
			<h1><?=$panier->month[$_GET['moisp']].' '.$_GET['year'];?></h1><?php
		}else{?>
			<h1><?=date('m-Y');?></h1><?php			
		}?>

		<div>
			<a href="planing.php?moisp=<?=$panier->previousMonth()[0];?>&year=<?=$panier->previousMonth()[1];?>" class="btn btn-primary">&lt;</a>
			<a href="planing.php?moisp=<?=$panier->nexMonth()[0];?>&year=<?=$panier->nexMonth()[1];?>" class="btn btn-primary">&gt;</a>
		</div>
	</div>
	

	<table class="calendar__table"><?php

		for($i=0;$i<$panier->getWeeks();$i++){?>

			<tr><?php

				foreach ($panier->days as $k=> $jours) {

					$date=(clone $firsday)->modify("+".($k+$i*7)."days");
					$eventsForDay=$events[$date->format('Y-m-d')]??[]; ?>

					<td class="<?=$panier->withinMonth($date)?'':'calendar__othermonth';?>" ><?php
						if ($i===0) {?>
							<div class="calendar__weekday"><?=$jours;?></div><?php
						}?>
						
						<div class="calendar__day"><?=$date->format('d');?></div><?php

						foreach ($eventsForDay as $event) {?>

							<div class="calendar__event">

								<?=(new DateTime($event->debut))->format('H:i');?>-<a href="event.php?id=<?=$event->id;?>"><?=ucwords($event->name);?></a>
								
							</div><?php
						}?>
					</td><?php
				}?>
			</tr><?php

		}?>
	</table>

	<div class="d-flex flex-row-items-center justify-content-between mx-sm-3">
		<div></div>
		<div>
			<a href="event.php?ajoutEvent" class="btn btn-primary">+</a>
		</div>
	</div>

	
</body>
</html>

