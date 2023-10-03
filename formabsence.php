<div class="d-flex" >
				
	<form class="form bg-light p-2" action="absence.php" method="POST" >
        
			
		<select class="form-select" type="text" name="groupe" required="" onchange="this.form.submit()"><?php

			if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {?>

				<option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php
			}else{?>

				<option>Choisissez la Classe</option><?php
			}

			foreach ($prodgroup as $form) {?>

				<option><?=$form->nomgr;?></option><?php

			}?>
		</select>
			
	</form><?php

	if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {?>

		<form class="form bg-light p-2" action="absence.php" method="POST" >
			<select class="form-select" type="text" name="semestren" required="" onchange="this.form.submit()"><?php

				if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard'])) {?>

					<option value="<?=$_SESSION['semestre'];?>"><?=$_SESSION['semestre'].'  '.$typerepart;?></option><?php
				}else{?>

					<option>Choix du <?=$typerepart;?></option><?php
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
		</form><?php
	}

	if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {?>
			
		<form class="form bg-light p-2" action="absence.php" method="POST" >
            
				
			<select class="form-select" type="text" name="matn" required="" onchange="this.form.submit()"><?php

				if (isset($_POST['groupe'])) {?>

					<option value="<?=$_SESSION['matn'];?>">Choix de la matière</option><?php

				}elseif (isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard'])) {?>

					<option value="<?=$_SESSION['matn'];?>"><?=$_SESSION['matn1'];?></option><?php
				}
				else{?>

					<option></option><?php

				}

				foreach ($prodm as $form) {?>

					<option value="<?=$form->codem;?>"><?=ucwords($form->nommat);?></option><?php

				}?>
			</select>
				
			
		</form><?php
	}?><?php

	if (isset($_POST['hdebut']) or isset($_POST['matn']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {?>

		<form class="form bg-light p-2" action="absence.php" method="POST"><?php

			if (isset($_POST['matn'])) {?>

				Heure de Début: <input class="form-control" type="time" name="hdebut" onchange="this.form.submit()"/><?php

			}elseif (isset($_POST['hdebut']) or isset($_POST['matn']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard'])) {?>

				Heure de Début: <input class="form-control" type="time" name="hdebut" value="<?=$_SESSION['hdebut'];?>" onchange="this.form.submit()"/><?php
			}else{?>

				Heure de Début: <input class="form-control" type="time" name="hdebut" onchange="this.form.submit()"/><?php
			}?>
		</form>

		<form class="form bg-light p-2" action="absence.php?nheure" method="POST" ><?php
			if (isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard'])) {?>

				Durée: <input class="form-control" type="text" name="nheure" placeholder="Nbre d'heures" required="" value="<?=$_SESSION['nheure'];?>" onchange="this.form.submit()"/><?php

			}else{?>

				Durée: <input class="form-control" type="text" name="nheure" required="" onchange="this.form.submit()"/><?php

			}?>			
			
		</form><?php
	}?>

</div>