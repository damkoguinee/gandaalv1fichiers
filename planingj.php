<?php require '_header.php'
?><!DOCTYPE html>
<html lang="fr">
<head>
    <title>gandaal</title>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta content="Page par défaut" name="description">
    <meta content="width=device-width, initial-scale=1" name="viewport">  
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> 
</head><?php

if (!empty($_SESSION['pseudo'])) {

	if (isset($_GET['groupe'])) {
		$_SESSION['groupe']=$_GET['groupe'];
		$_SESSION['groupep']=$_GET['groupe'];
		$_SESSION['enseigp']=array();
		$param=$_SESSION['groupe'];

	}elseif (isset($_GET['groupeeleve'])) {
		$_SESSION['groupe']=$_GET['groupeeleve'];
		$_SESSION['groupep']=$_GET['groupeeleve'];
		$_SESSION['enseigp']=array();
		$param=$_SESSION['groupe'];

	}elseif(isset($_GET['enseig'])){

		$_SESSION['enseig']=$_GET['enseig'];
		$_SESSION['enseigp']=$_GET['enseig'];
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

	}?>
	
	<div style="display: flex; justify-content:center;">
		<div style="margin:10px; ">
			<a class="btn btn-info" href="index.php">Accueil</a>
		</div>
		<form action="" id="groupe">
			<select id="groupe" name="groupe" onchange="document.getElementById('groupe').submit()"><?php

				if (isset($_GET['groupe']) ) {?>

					<option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php

				}else{?>

					<option>Choisissez la Classe</option><?php
				}

				foreach ($panier->classe() as $form) {?>

					<option value="<?=$form->nomgr;?>"><?=$form->nomgr;?></option><?php

				}?>
			</select>
		</form>

		<form class="form" action="" id="enseignant">

			<select id="enseignant"  name="enseig"  onchange="document.getElementById('enseignant').submit()"><?php

				if (isset($_GET['enseig']) or isset($_GET['enseignantplaning'])) {?>

					<option value="<?=$_SESSION['enseig'];?>"><?=$panier->nomEnseignant($_SESSION['enseig']);?></option><?php

				}else{?>

					<option>Chisissez un enseignant</option><?php
				}

				foreach ($panier->enseignant() as $prof) {?>

					<option value="<?=$prof->matricule;?>"><?=ucwords($prof->prenomen).' '.strtoupper($prof->nomen);?></option><?php
				}?>

			</select>
		</form>

		<div style="margin:10px; ">
			<a style="background-color: #ffb800;" class="btn btn-danger" href="event.php?ajoutEvent&type">Ajouter</a>
		</div>

	</div>
    <div id='calendar' class="container-fluid mt-2"></div>

    <div id="eventModal" class="modal">
        <div class="modal-content">
            <div class="bg-info text-center py-2 my-2">Information de la reservation:  <span id="eventTitle"></span></div>
            <p>Date : <span id="eventDate"></span></p>
            <p>Durée : <span id="eventDuree"></span>h</p>
            <p>Nombre de Personne : <span id="eventNbre"></span></p>
            <p>Montant Facture : <span id="eventMontant"></span></p>
            <p>Etat : <span id="eventEtat"></span></p>
            <!-- Ajoutez d'autres informations sur l'événement ici -->
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn">Fermer</a>
        </div>
    </div><?php
	
	if (isset($_GET['groupe']) or isset($_GET['enseig'])) {

		if (isset($_GET['groupe'])) {
			$_SESSION['groupe_planning']=$_GET['groupe'];
			$_SESSION['enseignant_planing']=0;
		}

		if (isset($_GET['enseig'])) {
			$_SESSION['enseignant_planing']=$_GET['enseig'];
			$_SESSION['groupe_planning']=0;
		}
	}else{
		$_SESSION['enseignant_planing']=0;
		$_SESSION['groupe_planning']="4eme";

	}


	require_once("footer.php");?>

	<script>
        // Récupérez la variable de session PHP en JavaScript
        var groupe= <?php echo json_encode($_SESSION['groupe_planning']); ?>;
    </script>

	<script>
        // Récupérez la variable de session PHP en JavaScript
        var enseignant= <?php echo json_encode($_SESSION['enseignant_planing']); ?>;
    </script>


    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

	<script src="js/moment/moment.js"></script>
	

    <script src="js/calendarj.js"></script><?php 

}else{
	header('Location:deconnexion.php');
}?>
</body>
</html>