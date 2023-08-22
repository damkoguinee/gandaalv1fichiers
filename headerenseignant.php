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

  if (isset($_SESSION['pseudo'])) {

    if (!isset($_GET['printnote'])) {?>

      <div id="header">

        <div class="menu"><a href="accueilenseignant.php?enseignant=<?=$_SESSION['matricule'];?>" class="logo">ACCUEIL</a></div>

        

        <div class="dec" style="margin-left: 80%;"><a href="deconnexion.php" class="deconnexion"></a></div>

      </div><?php
    }
  }else{

    require 'form_connexion.php';

  }

  $products = $DB->querys('SELECT type, matricule, niveau FROM login WHERE pseudo= :PSEUDO',array('PSEUDO'=>$_SESSION['pseudo']));?>

<div class="container">

  <div style="width: 100%;"><?php
        require 'enseignantinfos.php';?>
      </div><?php
  