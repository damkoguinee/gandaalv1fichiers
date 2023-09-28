<?php
require '_header.php'
?><!DOCTYPE html>
<html>

<head>
    <title>damkosport</title>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta content="Page par défaut" name="description">
    <meta content="width=device-width, initial-scale=1" name="viewport">  
    <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> 
</head><?php

  if (!empty($_SESSION['pseudo'])) {

    if (isset($_GET['printnote'])) {?>

      <body  onload="window.print()"><?php

    }else{?>

      <body onload="return focus();"><?php
    }  

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

    $products = $DB->querys('SELECT type, matricule, niveau FROM login WHERE pseudo= :PSEUDO',array('PSEUDO'=>$_SESSION['pseudo']));

    if (isset($_SESSION['pseudo'])) {

      if (!isset($_GET['printnote'])) {?>

        <nav class="navbar navbar-expand-lg fs-5 fw-bold" style="background-color: #253553;">
          <div class="container-fluid">
            <a class="navbar-brand" href="deconnexion.php"><img src="css/img/deconn.jpg" width="30" alt="damko"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link active btn btn-danger text-light m-1" aria-current="page" href="index.php?form">Accueil</a>
                </li>

                <li class="nav-item"><?php 
                  if ( $panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_PERSONNEL")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {?>
                    <a class="nav-link active btn btn-danger text-light m-1" href="formation.php?form&note">Gestion</a> <?php
                  };?>
                </li>

                <li class="nav-item"><?php 
                  if ( $panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_PERSONNEL")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {?> 
                    <a class="nav-link active btn btn-danger text-light m-1" href="ajout_eleve.php?ajoute&note">Scolarite</a><?php
                  };?>
                </li>

                <li class="nav-item"><?php 
                  if ( $panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_PERSONNEL")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {?> 
                    <a class="nav-link active btn btn-danger text-light m-1" href="activitesgestion.php?ideleve">Activités</a><?php
                  };?>
                </li>
                <?php 
                if ( $panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_PERSONNEL")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {?>

                  <li class="nav-item">
                    <a class="nav-link active btn btn-danger text-light m-1" href="note.php?note&note">Pédagogie</a>
                  </li><?php 
                }

                if ( $panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_COMPTABLE")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {?>

                  <li class="nav-item">
                    <a class="nav-link active btn btn-danger text-light m-1" href="comptabilite.php?note">Comptabilite</a>
                  </li><?php 
                }

                if ( $panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_PERSONNEL")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {?>
                  <li class="nav-item">
                  
                    <a class="nav-link active btn btn-danger text-light m-1" href="rapport.php?rapport">Statistiques</a>
                  </li><?php
                }

                if ($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_PERSONNEL")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true") {                 

                  if ($products['niveau']>10) {?>
                    <li class="nav-item"><a class="nav-link active btn btn-danger text-light m-1 " href="csv.php?save">Sauvegarde</a></li><?php
                  }
                }?> 
              </ul>
              
            </div>
          </div>
        </nav><?php
      }

      $products = $DB->querys('SELECT type, matricule, niveau FROM login WHERE pseudo= :PSEUDO',array('PSEUDO'=>$_SESSION['pseudo']));
      $personnelsup=$products['matricule'];

    }else{

      require 'form_connexion.php';

    }
  }else{
      header("Location: form_connexion.php");
  }?>