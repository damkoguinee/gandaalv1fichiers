<?php require '_header.php'?>
<!DOCTYPE html>
<html>
    
    <head>
      <title>GANDAAL Gestion de Scolarite</title>
      <meta charset="utf-8">    
      <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8">
      <link rel="stylesheet" href="css/form.css" type="text/css" media="screen" charset="utf-8">
    </head>

    <body><?php

        $products = $DB->querys('SELECT type, matricule, niveau FROM login WHERE pseudo= :PSEUDO',array('PSEUDO'=>$_SESSION['pseudo']));

        if (isset($_SESSION['pseudo'])) {
        
            if ($products['niveau']<1) {?>

                <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

            }else{?>
            
                <div class="form_connexion" style="width: 100%;">
                    <fieldset><legend style="margin: auto;"><img width="80" height="80" src="css/img/logo.jpg"></legend><legend style="margin: auto;"><div style="font-family: cursive; font-size: 30px; text-align: center;"><?=ucwords($_SESSION['etab']);?></div></legend>

                        <div>
                            <img width="100%" height="30" src="css/img/drapeau.png">                
                        </div>

                        <fieldset style="border: 0px;"><legend style="margin: auto;"><img width="40" height="40" src="css/img/symbole.png"></legend>

                            <div class="choix"><?php

                                require 'enseignantinfos.php';

                                require 'navaccueileleve.php';

                                ?>

                            </div>
                        </fieldset>
                    </fieldset>
                </div><?php
            }     
        }else{
            header('Location:form_connexion.php');
        }?>     
    </body>
    
</html>