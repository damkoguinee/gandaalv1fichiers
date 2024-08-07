<?php require '_header.php'?>
<!DOCTYPE html>
<html>
    
    <head>
      <title>GANDAAL Gestion de Scolarite</title>
      <meta charset="utf-8">
      <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
      <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">

        <!-- <script src="https://kit.fontawesome.com/8df11ad090.js" crossorigin="anonymous"></script> -->
      <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"> --> 
    </head>

    <body style="background-color: #253553;"><?php

        if (isset($_SESSION['pseudo'])) {

            if ($panier->users($_SESSION['matricule'])['type']!='enseignant') {

                $personnel=$panier->nomPersonnel($_SESSION['matricule']);
            }else{
                $personnel=$panier->nomEnseignant($_SESSION['matricule']);
            }?>
                
            <div class="container-fluid">

                <div class="alert alert-success text-center fs-5 fw-bold" style="display: flex; justify-content:space-around; ">
                    <label class="text-success ml-5">Utilsateur Connecté: <?=$personnel;?></label><?php 
                    if ($panier->searchRole("ROLE_ADMIN")=="true") {?>
                        <a class="btn btn-info" href="admin.php"><i class="fa-solid fa-screwdriver-wrench"></i></a><?php 
                    }?>
                    <label for=""><a class="bg-success" href="deconnexion.php"><img src="css/img/deconn.jpg" width="40" alt="damko"  ></a></label>
                </div>

                <div class="row align-items-center py-5" style="margin: auto; margin-top: 1rem; width:80%; background-image: url('css/img/fond.jpg');">
                    <div class=" fs-4 fw-bold text-center m-0 p-0"><?=ucwords($_SESSION['etab']);?></div>

                    <div class="row">
                        <img width="100%" height="30" src="css/img/drapeau.png">                
                    </div>

                    <div class="text-center mb-2"><img width="50" height="50" src="css/img/symbole.png"></div><?php
                    if ($panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_PERSONNEL")=="true" OR $panier->searchRole("ROLE_COMPTABLE")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true" OR $panier->searchRole("ROLE_DEV")=="true") {?>

                        <div class="col m-auto mt-1">
                            <a class="btn btn-light" href="formation.php?niveauc&form&note&typeel=<?="Elèves";?>&typeecole=<?="Elèves";?>">
                                <div class="card" style="width: 9rem;">
                                <img src="css/img/logo.jpg" class="card-img-top m-auto" alt="..."style="width: 7rem; height: 7rem">
                                <div class="card-bod m-auto" style="width: 9rem;">
                                    <h5 class="card-title" style="text-align: center;">COMPLEXE</h5>
                                </div>
                                </div>
                            </a>
                        </div><?php
                    }

                    if ($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true") {

                        $prodf=$DB->query("SELECT *from cursus order by(cursus.id)");

                    }else{

                        $prodf=$DB->query("SELECT *from cursus inner join niveau on cursus.nom=niveau.nom where matricule='{$_SESSION['matricule']}'  order by(cursus.id)");
                    }

                    foreach ($prodf as $value) {?>
                        
                        <div class="col m-auto mt-1">
                            <a  class="btn btn-light" href="formation.php?niveauf=<?=$value->nom;?>&form&note">
                                <div class="card" style="width: 9rem;">
                                <img src="css/img/<?=$value->nom;?>.jpg" class="card-img-top m-auto" alt="..."style="width: 7rem; height: 7rem">
                                <div class="card-bod m-auto" style="width: 9rem;">
                                    <h5 class="card-title" style="text-align: center;"><?=strtoupper($value->nom);?></h5>
                                </div>
                                </div>
                            </a>
                        </div><?php
                    }
                    if ($rapport->infoEtablissement()['nom']!='Groupe Scolaire Prive Franco-Guineen La Pepiniere "BIKAZ"') {?>
                        <div class="col m-auto mt-1">
                            <a  class="btn btn-light" href="accesite.php">
                                <div class="card" style="width: 9rem;">
                                <img src="css/img/acces.jpg" class="card-img-top m-auto" alt="..."style="width: 7rem; height: 7rem">
                                <div class="card-bod m-auto" style="width: 9rem;">
                                    <h5 class="card-title" style="text-align: center; text-transform:uppercase; ">accès site</h5>
                                </div>
                                </div>
                            </a>
                        </div>

                        <div class="col m-auto mt-1">
                            <a  class="btn btn-light" href="cantine/choix.php">
                                <div class="card" style="width: 9rem;">
                                <img src="css/img/cantine.jpg" class="card-img-top m-auto" alt="..."style="width: 7rem; height: 7rem">
                                <div class="card-bod m-auto" style="width: 9rem;">
                                    <h5 class="card-title" style="text-align: center;">CANTINE</h5>
                                </div>
                                </div>
                            </a>
                        </div>

                        <div class="col m-auto mt-1">
                            <a  class="btn btn-light" href="immogestion.php">
                                <div class="card" style="width: 9rem;">
                                <img src="css/img/immo.jpg" class="card-img-top m-auto" alt="..."style="width: 7rem; height: 7rem">
                                <div class="card-bod m-auto" style="width: 9rem;">
                                    <h5 class="card-title" style="text-align: center;">GEST IMMO</h5>
                                </div>
                                </div>
                            </a>
                        </div><?php 
                    }?>

                </div>
            </div><?php
                 
        }else{
            header('Location:form_connexion.php');
        }?>     
    </body>
    
</html>