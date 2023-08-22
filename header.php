<?php
require '_header.php'
?><!DOCTYPE html>
<html>
<head>
  <title>GANDAAL Gestion de Scolarite</title>
  <meta charset="utf-8">    
  <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8">
  <link rel="stylesheet" href="css/form.css" type="text/css" media="screen" charset="utf-8">
</head><?php

  if (!empty($_SESSION['pseudo'])) {

    if (isset($_GET['printnote'])) {?>

      <body  onload="window.print()"><?php

    }else{?>

      <body onload="return focus();"><?php
    }  

    if (empty($_SESSION['prodtype'])) {
      
      $prodtype=$DB->querys('SELECT id, type from repartition  where promo=:promo',array('promo'=>$_SESSION['promo']));

      $_SESSION['prodtype']=$prodtype['type'];

      $typerepart=ucfirst($prodtype['type']);
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

    $products = $DB->querys('SELECT type, matricule, niveau FROM login WHERE pseudo= :PSEUDO',array('PSEUDO'=>$_SESSION['pseudo']));

    if (isset($_SESSION['pseudo'])) {

      if (!isset($_GET['printnote'])) {?>

        <div id="header">

          <div class="menu"><a href="index.php?form" class="logo">ACCUEIL</a></div>

          <div class="nav"><?php 
            if ($products['type']!='enseignant'){?>
              <a class="logo" href="formation.php?form&note">Gestion</a> 
              <a class="logo" href="ajout_eleve.php?ajoute&note">Scolarite</a><?php
            }

            if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='informaticien' or $products['type']=='Directeur Général' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='surveillant Général' or $products['type']=="Conseille a l'éducation" or $products['type']=='coordonateur bloc B' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='secrétaire' or $products['type']=='enseignant' or $products['type']=='bibliothecaire') {?>


                <a class="logo" href="note.php?note&note">Pédagogie</a><?php
              }

            if ($products['type']=='admin' or $products['type']=='fondation' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='Directeur Général' or $products['type']=='comptable' or $products['type']=='informaticien' or $products['type']=='secrétaire') {?>

                <a class="logo" href="comptabilite.php?note">Comptabilite</a><?php 
              }

            if ($products['type']!='enseignant'){?>
              
              <a class="logo" href="rapport.php?rapport">Statistiques</a><?php
              

              if ($products['niveau']>4) {?>


                <a class="logo" href="csv.php?save">Sauvegarde</a><?php

              }
            }?>

          </div>

          <div class="dec"><a href="deconnexion.php" class="deconnexion"></a></div>

        </div><?php
      }

        $products = $DB->querys('SELECT type, matricule, niveau FROM login WHERE pseudo= :PSEUDO',array('PSEUDO'=>$_SESSION['pseudo']));
        $personnelsup=$products['matricule'];

      }else{

        require 'form_connexion.php';

      }
  }else{
      header("Location: form_connexion.php");
  }?>

<div class="container">