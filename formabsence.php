<div class="col" style="display: flex; margin-top: -20px;">
				
	<form id="formulaire" action="absence.php" method="POST" style="height: 30px;">
        <ol style="margin-left: -50px; margin-top: -10px;">
			<li>
				<select type="text" name="groupe" required="" onchange="this.form.submit()"><?php

			    	if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {?>

			    		<option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php
			    	}else{?>

			    		<option>Choisissez la Classe</option><?php
			    	}

			    	foreach ($prodgroup as $form) {?>

			    		<option><?=$form->nomgr;?></option><?php

			    	}?>
			    </select>
			</li>
		</ol>
	</form><?php

	if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {?>

		<form id="formulaire" action="absence.php" method="POST" style="margin-left: -20px; height: 30px;">
            <ol style="margin-left: -50px; margin-top: -10px;">
				<li>

					<select type="text" name="semestren" required="" onchange="this.form.submit()"><?php

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
				</li>
			</ol>
		</form><?php
	}

	if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {?>
			
		<form id="formulaire" action="absence.php" method="POST" style="margin-left: -20px; height: 30px;">
            <ol style="margin-left: -50px; margin-top: -10px;">
				<li>
					<select type="text" name="matn" required="" onchange="this.form.submit()"><?php

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
				</li>
			</ol>
		</form><?php
	}?>

	</div>

	<div class="col" style="display: flex; margin-top: -40px;"><?php

	if (isset($_POST['hdebut']) or isset($_POST['matn']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {?>

		<form id="formulaire" action="absence.php" method="POST" style="margin-left: 0px; height: 30px;">
            <ol style="margin-left: -50px; margin-top: -10px;">
				<li><?php

			    	if (isset($_POST['matn'])) {?>

			    		Heure de Début: <input type="time" name="hdebut" onchange="this.form.submit()" style="width: 25%;" /><?php

			    	}elseif (isset($_POST['hdebut']) or isset($_POST['matn']) or isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard'])) {?>

			    		Heure de Début: <input type="time" name="hdebut" value="<?=$_SESSION['hdebut'];?>" onchange="this.form.submit()" style="width: 25%;"/><?php
			    	}else{?>

			    		Heure de Début: <input type="time" name="hdebut" onchange="this.form.submit()" style="width: 25%;" /><?php
			    	}?>
				</li>
			</ol>
		</form>

		<form id="formulaire" action="absence.php?nheure" method="POST" style="margin-left: -20px; height: 30px;">
            <ol style="margin-left: -50px; margin-top: -10px;">
				<li><?php
					if (isset($_POST['nheure']) or isset($_POST['appel']) or isset($_POST['retard'])) {?>

						Durée: <input type="text" name="nheure" placeholder="Nbre d'heures" required="" value="<?=$_SESSION['nheure'];?>" onchange="this.form.submit()" style="width: 25%;"/><?php

					}else{?>

						Durée: <input type="text" name="nheure" required="" onchange="this.form.submit()" style="width: 25%;"/><?php

					}?>
				</li>
			</ol>
		</form><?php
	}?>

</div>