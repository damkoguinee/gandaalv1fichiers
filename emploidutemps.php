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

	$firsday=$panier->getStartingDay()->modify('last monday');?>
	<h1>Semaine 1</h1>

	<table class="calendar__table"><?php

		foreach($panier->times as $i=>$values){?>
			

			<tr>
				<td><?=$values;?></td><?php

				foreach ($panier->days as $k=> $jours) {?>
			
					<td><?php
						if ($i===0) {?>
							<div class="calendar__weekday"><?=$jours;?></div><?php
						}?>

						<div>Maths</div>
					</td><?php
				}?>
			</tr><?php

		}?>
	</table>

	
</body>
</html>

