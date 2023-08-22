<?php
require 'header.php';

if (isset($_SESSION['pseudo'])) {

  if ($products['niveau']<4) {?>

    <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

  }else{?>
    
    <div style="display: flex;">

      <div><?php require 'headerbiblio.php';?></div><?php

      if(isset($_GET["categ"])){?>

        <form id="formulaire" method="POST" action="ajoutlivre.php" style="width: 100%; margin-top: 30px;" >
          <fieldset><legend>Ajouter une Position</legend>
            <ol>

              <li><label>Nom</label>
                  <input type="text" name="cate" required="">
              </li>
            </ol>
          </fieldset>

          <fieldset>

            <input type="reset" value="Annuler" name="valid" id="form" style="width:150px; cursor: pointer;"/>

            <input type="submit" value="Ajouter" name="categins" id="form" onclick="return alerteV();" style="margin-left: 20px; width:150px; cursor: pointer;" />

          </fieldset>
        </form><?php
      }

      if(isset($_POST["categins"])){

        $cate=$_POST['cate'];

        $products=$DB->query('SELECT nom FROM position WHERE nom= ?', array($cate));

        if (empty($products)) {

          $DB->insert('INSERT INTO position (nom) VALUES (?)', array($cate));

        }else{?>

          <div class="alerteV">Cette position existe déjà</div><?php

        }
        
      }


      if(isset($_GET["allee"])){?>

        <form id="formulaire" method="POST" action="ajoutlivre.php" style="width: 100%; margin-top: 30px;" >
          <fieldset><legend>Ajouter une Allée</legend>
            <ol>

              <li><label>Nom</label>
                  <input type="text" name="alle" required="">
              </li>
            </ol>
          </fieldset>

          <fieldset>

            <input type="reset" value="Annuler" name="valid" id="form" style="width:150px; cursor: pointer;"/>

            <input type="submit" value="Ajouter" name="alleins" id="form" onclick="return alerteV();" style="margin-left: 20px; width:150px; cursor: pointer;" />

          </fieldset>
        </form><?php
      }

      if(isset($_POST["alleins"])){

        $cate=$_POST['alle'];

        $products=$DB->query('SELECT nom FROM allee WHERE nom= ?', array($cate));

        if (empty($products)) {

          $DB->insert('INSERT INTO allee (nom) VALUES (?)', array($cate));

        }else{?>

          <div class="alerteV">Cette Allée existe déjà</div><?php

        }
        
      }?>

      <div><?php

        if(!isset($_GET["categ"]) or !isset($_GET["allee"])){

          $prodpos=$DB->query('SELECT nom FROM position');
          $prodallee=$DB->query('SELECT nom FROM allee');?>

          <form id="formulaire" method="POST" action="ajoutlivre.php" enctype="multipart/form-data" style="width: 100%; margin-top: 30px;" >
              <fieldset><legend>Ajouter un Livre</legend>
              <ol>

                <li><label>Code barre</label>
                    <input type="text" name="codeb">
                </li>

                <li><label>Nom du Livre</label>
                    <input type="text" name="nom" required="">
                </li>
                <li><label>Matière</label><select name="mat" required="">
                  <option></option><?php
                  foreach ($panier->matiere as $key => $value) {?>
                    <option value="<?=$value;?>"><?=$value;?></option><?php
                  }?></select>
                </li>

                <li><label>Niveau</label><select name="classe" required="">
                  <option></option><?php
                  foreach ($panier->niveau as $key => $value) {?>
                    <option value="<?=$value;?>"><?=$value;?></option><?php
                  }?></select>
                </li>

                <li><label>Allée</label>

                  <select type="text" name="allee" required="">
                    <option></option><?php
                    foreach ($prodallee as $value) {?>

                      <option value="<?=$value->nom;?>"><?=ucfirst($value->nom);?></option><?php 
                    }?>
                  </select>

                  <a href="ajoutlivre.php?allee">Ajouter une Allée</a>
                </li>

                <li><label>Position</label>

                  <select type="text" name="pos" required="">
                    <option></option><?php
                    foreach ($prodpos as $value) {?>

                      <option value="<?=$value->nom;?>"><?=ucfirst($value->nom);?></option><?php 
                    }?>
                  </select>

                  <a href="ajoutlivre.php?categ">Ajouter une Position</a>
                </li>

                <li><label>Quantité</label>
                  <input type="number" name="quantite" required="" min="0">
                </li>
              </ol>

              <fieldset><input type="reset" value="Annuler" name="valid" id="form" style="width:150px;"/><input type="submit" value="Ajouter"  id="form" onclick="return alerteV();" style="margin-left: 20px; width:150px;" /></fieldset>
            </form>
          </div>
          <div><?php

      if (empty($_POST['nom']) and empty($_POST['mat']) and empty($_POST['classe']) and empty($_POST['pos'])) {
            
      }else{

        $verifdesig=$DB->querys('SELECT nom FROM stocklivre WHERE nom= :nom and matiere=:mat and classe=:class', array('nom'=>$_POST['nom'], 'mat'=>$_POST['mat'], 'class'=>$_POST['classe']));

        if (empty($verifdesig)) {

          $DB->insert('INSERT INTO stocklivre (codeb, nom, matiere, classe, allee, position, quantite) VALUES(?, ?, ?, ?, ?, ?, ?)', array($_POST['codeb'], $_POST['nom'], $_POST['mat'], $_POST['classe'], $_POST['allee'], $_POST['pos'], $_POST['quantite']));
          

          $products=$DB->query('SELECT nom FROM stocklivre WHERE nom= :NOM', array('NOM'=>$_POST['nom']));

          if (!$products) {?>

            <div class="alerteS">Livre non ajouté</div><?php

          }else{?>

            <div class="alerteS">Livre ajouté avec sucèe!!</div><?php

          }
        }else{?>

          <div class="alertes">Ce Livre existe</div><?php
        }

      }?>

    </div><?php
  }

  }

}else{

}?>
</body>

</html>
