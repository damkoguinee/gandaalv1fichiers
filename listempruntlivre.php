<?php require 'header.php';?>

<div style="display: flex;">

  <div><?php require 'headerbiblio.php';?></div><?php

  if (isset($_GET['del'])) {

    $DB->delete('DELETE FROM stocklivre WHERE id = ?', array($_GET['del']));
          
    $products=$DB->query('SELECT nom FROM stocklivre WHERE id= ?', array($_GET['del']));

    if (!$products) {

      echo "Le produit a bien été supprimer";

    }else{

      echo "La suppression a echouée";

    }

  }

  if (isset($_POST['qtiteajouter']) and !empty($_POST['qtiteajouter'])) {


    $prodrayon = $DB->querys('SELECT quantite as qtite FROM stocklivre WHERE id=:id', array('id' => $_POST['id']));

    $qtitesn=$prodrayon['qtite']+$_POST['qtiteajouter'];

    $DB->insert('UPDATE stocklivre SET quantite= ? WHERE id = ?', array($qtitesn, $_POST['id']));

  }


  if (isset($_POST['qtiteperte']) and !empty($_POST['qtiteperte'])) {


    $prodrayon = $DB->querys('SELECT quantite as qtite FROM stocklivre WHERE id=:id', array('id' => $_POST['id']));

    $qtitesr=$prodrayon['qtite']-$_POST['qtiteperte'];

    $DB->insert('UPDATE stocklivre SET quantite= ? WHERE id = ?', array($qtitesr, $_POST['id']));

  }?>

  <div>

    

        <?php
        $montant=0;
        $montantp=0;
        $restep=0;
        $qtite=0;


        if (isset($_GET['terme']) or !empty($_SESSION['matpaye'])) {

          if (isset($_GET["terme"])){

              $_GET["terme"] = htmlspecialchars($_GET["terme"]); //pour sécuriser le formulaire contre les failles html
              $terme = $_GET['terme'];
              $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
              $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
          }else{
            $terme=$_SESSION['matpaye'];
            unset($_SESSION['matpaye']);
          }

          $terme = strtolower($terme);
          $products = $DB->query('SELECT stocklivre.id as ids, empruntlivre.matricule as matricule, payelivre.numc as numc, prenomel, nomel, nom, matiere, empruntlivre.quantite as qtite, totalc, totalp, payelivre.etat as etat, typep, DATE_FORMAT(payelivre.datecmd, \'%d/%m/%Y\') as datep FROM empruntlivre inner join eleve on eleve.matricule=empruntlivre.matricule inner join payelivre on payelivre.numc=empruntlivre.numc inner join stocklivre on stocklivre.id=empruntlivre.id_produit WHERE empruntlivre.etat LIKE ? and(codeb LIKE ? OR empruntlivre.matricule LIKE ? OR prenomel LIKE ? OR nomel LIKE ?)',array('en-cours', $terme, "%".$terme."%", "%".$terme."%", "%".$terme."%"));
          

          if (empty($products)) {?>

            <div class="alertes">Recherche non trouvée </div><?php

          }

        }else{

          $products = $DB->query('SELECT stocklivre.id as ids, empruntlivre.matricule as matricule, payelivre.numc as numc, prenomel, nomel, nom, matiere, empruntlivre.quantite as qtite, totalc, totalp, payelivre.etat as etat, typep, DATE_FORMAT(payelivre.datecmd, \'%d/%m/%Y\') as datep FROM empruntlivre inner join eleve on eleve.matricule=empruntlivre.matricule inner join payelivre on payelivre.numc=empruntlivre.numc inner join stocklivre on stocklivre.id=empruntlivre.id_produit where empruntlivre.etat=:etat order by(empruntlivre.etat)', array('etat'=>'en-cours'));
        }

        if (!empty($products)) {?>

          <table class="payement" style="width: 100%; margin-top: 30px;">

          <thead>

            <tr>
              <th class="legende" colspan="7" height="30"><?="Liste des emprunts en-cours du " .date('d/m/Y'); ?><a href="printstock.php?stock" target="_blank" ><div class="printstock" style="width: 40px;"></div></a></th>

              <form method="GET" action="listempruntlivre.php" id="suite" name="term">
             
                <th colspan="5">

                  <input id="reccode" type = "search" name = "terme" placeholder="rechercher !!!" onchange="document.getElementById('suite').submit()"/>

                  <input style="width: 100px;" type="submit" value="Rechercher" />

                </th>
            </form>
            
             

            <tr>
              <th>N°</th>
              <th>Matricule</th>
              <th>Prénom & nom</th>
              <th>Désignation</th>
              <th>Matière</th>
              <th>Qtité</th>
              <th>Montant</th>
              <th>Montant Payé</th>
              <th>Reste</th>
              <th>Mode P</th>
              <th>Date</th>
              <th></th>
              
            </tr>

          </thead>

          <tbody><?php
        

          foreach ($products as $key=> $product){

            $qtite+=$product->qtite;
            $montant+=$product->totalc;
            $montantp+=$product->totalp;
            $restep+=$product->totalc-$product->totalp;?>

            <tr>
              <td><?=$key+1;?></td>   

              <td><?=$product->matricule; ?></td>

              <td><?= ucwords($product->prenomel).' '.strtoupper($product->nomel); ?></td>

              <td style="text-align: center;"><?= ucwords($product->nom); ?></td>

              <td style="text-align: center;"><?= ucwords($product->matiere); ?></td>

              <td style="text-align: center;"><?= $product->qtite; ?></td>

              <td style="text-align: right;"><?= number_format($product->totalc,0,',',' '); ?></td>

              <td style="text-align: right;"><?= number_format($product->totalp,0,',',' '); ?></td>

              <td style="text-align: right; color: red;"><?= number_format($product->totalc-$product->totalp,0,',',' '); ?></td>

              <td style="text-align: center;"><?= $product->typep; ?></td>

              <td style="text-align: center;"><?= $product->datep; ?></td>            

              <td><?php
                if ($product->totalc-$product->totalp==0) {?>
                  <a onclick="return alerteV();" href="retourlivre.php?retourpaye=<?= $product->numc; ?>&ids=<?=$product->ids;?>">Retour</a><?php
                }else{?>
                  <a onclick="return alerteV();" href="retourlivre.php?retourpay=<?= $product->numc; ?>&idp=<?=$product->ids;?>&matpaye=<?=$product->matricule;?>&mpaye=<?=$product->totalc;?>">Payer</a><?php

                }?>
              </td>


            </tr><?php
          }?>


        </tbody>

        <tfoot>

          <tr>
            <th colspan="5">TOTAL</th>
            <th style="text-align: center;"><?= number_format($qtite,0,',',' ') ; ?> </th>
            <th style="text-align: right;"><?= number_format($montant,0,',',' ') ; ?> </th>
            <th style="text-align: right;"><?= number_format($montantp,0,',',' ') ; ?> </th>
            <th style="text-align: right; color: red;"><?= number_format($restep,0,',',' ') ; ?> </th>

          </tr>

        </tfoot>

      </table><?php

    }else{?>
      <div class="alerteV">Aucun emprunt en-cours</div><?php
    }?>
  </div>
</div>


<script>
function suivant(enCours, suivant, limite){
  if (enCours.value.length >= limite)
  document.term[suivant].focus();
}

function focus(){
document.getElementById('reccode').focus();
}

function alerteS(){
  return(confirm('Confirmer la suppression?'));
}

function alerteM(){
  return(confirm('Confirmer la modification'));
}
</script>   