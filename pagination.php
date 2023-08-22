<?php

	$nbreparpage = 15;

	if (isset($_GET['voir_elg'])) {

		$prodnbre=$DB->querys('SELECT  count(*) as total from inscription inner join eleve on eleve.matricule=inscription.matricule where inscription.nomgr=:code and annee=:promo', array('code'=>$_GET['voir_elg'], 'promo'=>$_SESSION['promo']));

		$lien='formation.php?typef=2&voir_elg='.$_GET['voir_elg'].'&page=';

	}elseif (isset($_GET['voir_e'])) {

		$prodnbre=$DB->querys('SELECT  count(*) as total from inscription inner join eleve on eleve.matricule=inscription.matricule where inscription.codef=:code and annee=:promo', array('code'=>$_GET['voir_e'], 'promo'=>$_SESSION['promo']));

		$lien='formation.php?typef=1&voir_e='.$_GET['voir_e'].'&page=';

	}elseif (isset($_GET['searchelf'])) {
			$_GET["searchelf"] = htmlspecialchars($_GET["searchelf"]); //pour sécuriser le formulaire contre les failles html
	      $terme = $_GET['searchelf'];
	      $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
	      $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
	      $terme = strtolower($terme);

		$prodnbre=$DB->querys('SELECT  count(*) as total from inscription inner join eleve on eleve.matricule=inscription.matricule inner join contact on eleve.matricule=contact.matricule WHERE inscription.codef=? and annee=? and (eleve.matricule LIKE ? or nomel LIKE ? or prenomel LIKE ? or phone LIKE ?)',array($_SESSION['voir_e'], $_SESSION['promo'], "%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));

		$lien='formation.php?typef=1&voir_e='.$_SESSION['voir_e'].'&page=';

	}elseif (isset($_GET['listelsear'])) {

		$prodnbre = $DB->querys('SELECT count(*) as total FROM eleve');

    	$lien='ajout_eleve.php?page=';

	}elseif (isset($_POST['annee'])) {

		$prodnbre=$DB->querys('SELECT count(*) as total FROM payement WHERE DATE_FORMAT(datepaye, \'%Y\')=:annee', array('annee' => $_POST['annee']));

	}elseif (isset($_POST['mensuelle'])) {

		$prodnbre=$DB->querys('SELECT count(*) as total FROM payement WHERE DATE_FORMAT(datepaye, \'%m/%Y\')=:annee', array('annee' => $_POST['mensuelle']));

	}elseif (isset($_POST['jour'])) {

		$prodnbre=$DB->querys('SELECT count(*) as total FROM payement WHERE DATE_FORMAT(datepaye, \'%Y-%m-%d\')=:annee', array('annee' => $_POST['jour']));

	}else{

		if (!empty($_SESSION['niveauf'])) {

    		$prodnbre = $DB->querys('SELECT count(*) as total FROM inscription where annee=:promo and niveau=:niv', array('promo'=>$_SESSION['promo'], 'niv'=>$_SESSION['niveauf']));

    	}else{

    		$prodnbre = $DB->querys('SELECT count(*) as total FROM inscription where annee=:promo', array('promo'=>$_SESSION['promo']));
    	}


    	$lien='ajout_eleve.php?page=';
    }
    $nbreeleve= $prodnbre['total'];
    $nbredepage = ceil($nbreeleve/$nbreparpage);

    if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $nbredepage) {

       $_GET['page'] = intval($_GET['page']);

       $pageCourante = $_GET['page'];

    }else{

       $pageCourante = 1;
    }

	$depart = ($pageCourante-1)*$nbreparpage;?>

	<div style="display: flex; font-size: 18px; margin-bottom: -30px;"><?php

		if ($nbredepage==1 or $nbredepage==0) {

		}else{

			if (!isset($_GET['derniere'])) {
		

				for($i=1;$i<=($pageCourante+1);$i++) {
			  
			        if($i == $pageCourante) {?>

			        	<div><?=' '.$i.' ';?></div><?php

			        }elseif($i == 1) {?>

			        	<div style="margin-right: 10px;">

			        		<a href="<?=$lien;?>=<?=$i;?>&supp"><img src="css/img/flecheg.jpg" width="100" height="25"></a>

			        	</div><?php

			        }elseif($i == $pageCourante+1) {?>
			        	<div style="margin-right: 10px; margin-left: 10px;">

			        		<a href="<?=$lien;?><?=$i;?>&supp"><?='  page suivante ';?></a>
			        	</div><?php

			        }else {?>
			        	<div style="margin-right: 10px;">

			        		<a href="<?=$lien;?><?=$i;?>&supp"><?=$i;?></a>
			        	</div><?php
			        }

			        $j=0;
			    }
			}

			if (!isset($_GET['supp'])) {

				if (!isset($_GET['premiere'])) {?>

					<div style="margin-right: 10px;">

						<a href="<?=$lien;?><?=1;?>&premiere"><img src="css/img/flecheg.jpg" width="100" height="25"></a>

					</div><?php
				}
			}?>

			<div style="margin-right: 10px;">

		    	<a href="<?=$lien;?><?=$nbredepage;?>&derniere"><img src="css/img/fleche.jpg" width="100" height="25" style="margin-left: 100px;"></a>
		    </div><?php
		}?>
	</div>	

