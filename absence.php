<?php
require 'header.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{

    	require 'navabsence.php';?>

    	

        <div><?php

        	if (isset($_GET['abs']) or isset($_POST['semestren']) or isset($_POST['groupe']) or isset($_POST['matn']) or isset($_POST['hdebut']) or isset($_POST['nheure']) or isset($_POST['matr']) or isset($_GET['modif_dev']) or isset($_GET['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {

			if ($products['type']=='admin' or $products['type']=='Secretaire' or $products['type']=='Admistrateur General' or $products['type']=='DE/Censeur' or $products['type']=='proviseur' or $products['type']=='Directeur du primaire' or $products['type']=='Proviseur' or $products['type']=='Conseille a l\'éducation' or $products['type']=='Surveillant general' or $products['type']=='Comptable' or $products['type']=='coordonateur bloc B') {

				
				$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe where promo=:promo order by(codef) desc', array('promo'=>$_SESSION['promo']));

			}elseif ($products['type']=='eleve') {

				$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe where promo=:promo order by(codef) desc', array('promo'=>$_SESSION['promo']));

			}else{

				

				$prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe inner join enseignement on groupe.nomgr=enseignement.nomgr where enseignement.codens=:code and groupe.promo=:promo order by(groupe.id) desc', array('code'=>$products['matricule'], 'promo'=>$_SESSION['promo']));

			}

			if (isset($_POST['groupe'])){

				$_SESSION['groupe']=$_POST['groupe'];
				$_SESSION['semestre']="Choisissez le ";
				$_SESSION['matn']="Choisissez la matière";
				$_SESSION['hdebut']="Ajouter Heure de debut";
				$_SESSION['nheure']="Ajouter le nbre dheures";
			}

			if (isset($_POST['semestren'])){

				$_SESSION['semestre']=$_POST['semestren'];
				$_SESSION['matn']="CChoisissez la matière";
				$_SESSION['hdebut']="Ajouter Heure de debut";
				$_SESSION['nheure']="Ajouter le nbre dheures";				
			}

			if (isset($_POST['matn'])){

				$_SESSION['matn']=$_POST['matn'];
				$_SESSION['hdebut']="Ajouter Heure de debut";

				$matiere=$DB->querys('SELECT nommat from matiere where codem=:codem', array('codem'=>$_POST['matn']));

				$_SESSION['matn1']=$matiere['nommat'];

				$prodens=$DB->querys('SELECT codens from enseignement where codem=:codem and promo=:promo', array('codem'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));

				if ($products['type']=='admin' or $products['type']=='Secretaire' or $products['type']=='Admistrateur General' or $products['type']=='DE/Censeur' or $products['type']=='proviseur' or $products['type']=='Directeur du primaire' or $products['type']=='Proviseur' or $products['type']=='Conseille a l\'éducation' or $products['type']=='Surveillant general' or $products['type']=='Comptable' or $products['type']=='coordonateur bloc B') {

					$_SESSION['ens']=$products['matricule'];
					$numens=$_SESSION['ens'];

				}elseif ($products['type']=='eleve') {
					$_SESSION['$ens']=$prodens['codens'];

					$numens=$_SESSION['$ens'];

				}else{

					

				}
			}

			if (isset($_POST['hdebut'])){

				$_SESSION['hdebut']=$_POST['hdebut'];

				$prodens=$DB->querys('SELECT codens from enseignement where codem=:codem and promo=:promo', array('codem'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));
				
				if ($products['type']=='admin' or $products['type']=='Secretaire' or $products['type']=='Admistrateur General' or $products['type']=='DE/Censeur' or $products['type']=='proviseur' or $products['type']=='Directeur du primaire' or $products['type']=='Proviseur' or $products['type']=='Conseille a l\'éducation' or $products['type']=='Surveillant general' or $products['type']=='Comptable' or $products['type']=='eleve' or $products['type']=='coordonateur bloc B') {

					$_SESSION['$ens']=$prodens['codens'];

					$numens=$_SESSION['$ens'];

					

				}else{

					$_SESSION['ens']=$products['matricule'];
					$numens=$_SESSION['ens'];

				}
			}

			if (isset($_POST['nheure'])){

				$_SESSION['nheure']=$_POST['nheure'];
			}

			if (isset($_POST['groupe']) or isset($_POST['semestren']) or isset($_POST['matn']) or isset($_POST['hdebut'])) {	

				if ($products['type']=='admin' or $products['type']=='Secretaire' or $products['type']=='Admistrateur General' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='Proviseur' or $products['type']=='Conseille a l\'éducation' or $products['type']=='Surveillant general' or $products['type']=='Comptable' or $products['type']=='coordonateur bloc B') {

					$prodm=$DB->query('SELECT  matiere.codem as codem, nommat from enseignement inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule where  enseignement.nomgr=:nom and enseignement.promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

					
				}elseif ($products['type']=='eleve') {

					$prodm=$DB->query('SELECT  matiere.codem as codem, nommat from enseignement inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule where  enseignement.nomgr=:nom and enseignement.promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

				}else{

					

					$prodm=$DB->query('SELECT  matiere.codem as codem, nommat from enseignement inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule where enseignant.matricule=:mat and enseignement.nomgr=:nom and enseignement.promo=:promo', array('mat'=>$products['matricule'], 'nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

					

				}

			}
		}

        require 'formabsence.php';

		if (isset($_POST['nheure']) or isset($_POST['matr']) or isset($_GET['modif_dev']) or isset($_POST['appel']) or isset($_POST['retard']) or isset($_POST['exclus'])) {

			require 'ajout_absence.php';
		}?>
    </div><?php
	}
}