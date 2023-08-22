<?php
require 'header.php';

if (isset($_SESSION['pseudo'])) {

    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

        <div style="display: flex;">

            <div><?php require 'headerbiblio.php';?></div>

            <div><?php

            if (isset($_POST['payer'])) {
                $_SESSION['motif']="Commande Fournisseur";
                $type="FOURNISSEUR";
                require 'insertcmd.php';
            }

            if (isset($_GET['delcmd'])) {

              $DB->delete('DELETE FROM achat WHERE id = ?', array($_GET['delcmd']));
            }

            
                
            if (isset($_POST['etat']) AND $_POST['etat']=="livre" ) {
            
            }else{?>

                    <div class="expoc" style="width: 100%;"><fieldset><legend>Rechercher un Livre</legend><?php

                        if (isset($_GET['ventec'])) {

                            unset($_SESSION['scannerc']); // Pour faire la vente normale
                        }
                    
                        if (isset($_GET['scannerc']) or !empty($_SESSION['scannerc'])) {?>

                            <div class="navsearch" >                        
                                <div class="search">
                                    <form method="GET" action="emprunterlivre.php" id="suite">

                                        <input id="reccode" type = "search" name = "scanneurc" placeholder="scanner" onchange="document.getElementById('suite').submit()">
                                    </form>
                                </div>

                                <a href="emprunterlivre.php?ventec"><input style="width: 100px;height: 30px; font-size: 20px; background-color: red;color: white;"  type="submit" value="Saisir"></a>

                            </div><?php

                        }else{?>

                            <div class="navsearch">
                                <div class="search">

                                    <form method="GET" action="emprunterlivre.php" id="suite" name="term">

                                        <input id="reccode" type = "search" name = "terme" placeholder="rechercher" onKeyUp="suivant(this,'s', 9)" onchange="document.getElementById('suite').submit()">
                                        <input name = "s" style="width: 0px; height: 0px;" >
                                    </form>
                                </div>

                                <a href="emprunterlivre.php?scannerc"><input style="width: 100px;height: 30px; font-size: 20px; background-color: red;color: white;"  type="submit" value="Scanner"></a>

                            </div><?php
                        }
                        if (isset($_GET['terme'])) {

                            if (isset($_GET["terme"])){

                                $_GET["terme"] = htmlspecialchars($_GET["terme"]); //pour sécuriser le formulaire contre les failles html
                                $terme = $_GET['terme'];
                                $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
                                $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
                            }

                            if (isset($terme)){

                                $terme = strtolower($terme);
                                $products=$DB->query("SELECT * FROM stocklivre WHERE nom LIKE ? OR classe LIKE ? OR matiere LIKE ? OR classe LIKE ?", array("%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));

                            }else{

                                $message = "Vous devez entrer votre requete dans la barre de recherche";

                            }?>

                            <div class="expoline"><?php

                                foreach ( $products as $product ){?>

                                    <div class="boxc">
                                        <ul style="list-style-type: none;">                            
                                            <li class="logo"><?php if ($product->quantite>0) {?><a href="emprunterlivre.php?desig=<?= $product->nom;?>&idc=<?=$product->id;?>"><?php }?>

                                                <div class="descript_logo">
                                                    <div class="designation"><?= ucwords($product->nom); ?></div>
                                                    <div class="reste">Allée:<?= strtoupper($product->allee);?></div>

                                                    <div class="reste">Position: <?= strtoupper($product->position); ?></div>

                                                    <div class="reste">Stock: <?= $product->quantite; ?></div>
                                                    <div class="pricebox"><?=ucwords($product->classe); ?></div>
                                                </div></a>

                                            </li>

                                        </ul>
                                    </div><?php
                                }?>

                            </div><?php
               
                        }else{
                    }?>

                    </div></fieldset><?php
                }?>

            <div id="panierc"><?php

                require 'panierc.php';?>
        
            </div><?php
        }

    }else{


    }?>

<script>
    function alerteS(){
        return(confirm('Valider la suppression'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation'));
    }
    
    function suivant(enCours, suivant, limite){
        if (enCours.value.length >= limite)
        document.term[suivant].focus();
    }

    function focus(){
    document.getElementById('reccode').focus();
  }
</script>



