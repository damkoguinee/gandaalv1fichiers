<?php
	require 'db.class.php';
	require 'panier.class.php';
	require 'rapportClass.php';
	require 'immobillierClass.php';
	$DB = new DB();
	$panier = new panier($DB);
	$rapport= new Rapport($DB);
	$immobillier= new Immobillier($DB);

	
	if (!isset($_GET["form_connexion"])) {
		if (empty($_SESSION['prodtype'])) {
			
			$prodtype=$DB->querys('SELECT id, type from repartition  where promo=:promo',array('promo'=>$_SESSION['promo']));
			if (is_array($prodtype)) {
			$_SESSION['prodtype']=$prodtype['type'];
	
			$typerepart=ucfirst($prodtype['type']);
			}else{
			$_SESSION['prodtype']="semestre";
	
			$typerepart=ucfirst("semestre");
	
			}
		}
	
		if (isset($_POST['groupe'])){
	
			$prodclass=$DB->querys('SELECT codef from groupe where nomgr=:nom and promo=:promo', array('nom'=>$_POST['groupe'], 'promo'=>$_SESSION['promo']));
	
			$prodform=$DB->querys('SELECT niveau from formation where codef=:code', array('code'=>$prodclass['codef']));
	
			$prodtype=$DB->querys('SELECT type from cursus inner join repartition on repartition.codecursus=cursus.codecursus where nom=:code', array('code'=>$prodform['niveau']));
	
			$prodtype=$prodtype['type'];
	
			$_SESSION['prodtype']=$prodtype;
	
			$prodtype=$_SESSION['prodtype'];
	
			$typerepart=ucfirst($_SESSION['prodtype']);
	
		}else{
	
			if (!isset($_GET['note'])){
	
				$prodtype=$_SESSION['prodtype'];
	
				$typerepart=ucfirst($_SESSION['prodtype']);
			}
		}
	
		if (isset($_GET['disci'])){
	
			$prodform=$DB->querys('SELECT niveau from inscription where matricule=:mat and annee=:promo', array('mat'=>$_GET['disci'], 'promo'=>$_SESSION['promo']));
	
			$prodtype=$DB->querys('SELECT type from cursus inner join repartition on repartition.codecursus=cursus.codecursus where nom=:code', array('code'=>$prodform['niveau']));
	
			$prodtype=$prodtype['type'];
	
			$_SESSION['prodtype']=$prodtype;
	
			$prodtype=$_SESSION['prodtype'];
	
			$typerepart=ucfirst($_SESSION['prodtype']);
	
		}
	
		if ($_SESSION['prodtype']=='trimestre') {
	
			if (date('m')=='11' or date('m')=='12' or date('m')=='01' or date('m')=='02' or date('m')=='03') {
				$semcourant='1';
			}elseif (date('m')=='04' or date('m')=='05') {
				$semcourant='2';
			}else{
				$semcourant='3';

			}
			}else{

			if (date('m')=='11' or date('m')=='12' or date('m')=='01' or date('m')=='02' or date('m')=='03') {
				$semcourant='1';
			}else{
				$semcourant='2';

			}
		}
	}
?>