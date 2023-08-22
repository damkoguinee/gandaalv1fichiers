<?php require 'headerv2.php';?>

<script>
  function suivant(enCours, suivant, limite){
    if (enCours.value.length >= limite)
    document.term[suivant].focus();
  }
</script>

<div class="container-fluid">

  <div class="row">

    <?php require 'headerbiblio.php';?><?php

    if (isset($_GET['del'])) {

      $DB->delete('DELETE FROM stocklivre WHERE id = ?', array($_GET['del']));
            
      $products=$DB->querys('SELECT id, nom FROM stocklivre WHERE id= ?', array($_GET['del']));

      if (empty($products['id'])) {?>

        <div class="alert alert-success" role="alert">Produit supprimé!!!</div><?php

      }else{?>

        <div class="alert alert-warnin" role="alert">Produit non supprimé</div><?php

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

    <div class="col-sm-12 col-md-10 mt-2" style="overflow:auto">

      <table class="table table-hover table-bordered table-striped table-responsive text-center">

      <thead>

        <tr>
          <th colspan="12" scope="col" class="text-center bg-light"><?="Livres disponible le " .date('d/m/Y'); ?><a href="printstock.php?stock" target="_blank" ></a></th>
            
        </tr>
        <form class="form" method="GET" action="stocklivre.php" id="suite" name="term">
          <tr>

            <th colspan="4" scope="col" class="text-center">
              <input class="form-control me-2" id="search-user" type="search" name="terme" placeholder="rechercher un livre" aria-label="Search" onchange="document.getElementById('suite').submit()">
            </th>

            <th colspan="8" >Gestion des Produits</th>
          </tr>
        </form>
        <tr>
          <th>N°</th>
          <th>Nom</th>
          <th>Matière</th>
          <th>Niveau</th>
          <th colspan="2">Emplacement</th>
          <th>Qtité</th>
          <th colspan="2">Ajouter</th>
          <th colspan="2">Retirer</th>
          <th></th>
        </tr>

      </thead>

      <tbody>

        <?php
        $tot_achat=0;
        $tot_revient=0;
        $tot_vente=0;
        $qtiteR=0;
        $qtiteS=0;

        if (isset($_GET['terme'])) {

          if (isset($_GET["terme"])){

              $_GET["terme"] = htmlspecialchars($_GET["terme"]); //pour sécuriser le formulaire contre les failles html
              $terme = $_GET['terme'];
              $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
              $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
          }

          if (isset($terme)){

              $terme = strtolower($terme);
              $products = $DB->query("SELECT * FROM stocklivre WHERE codeb LIKE ? nom LIKE ? OR matiere LIKE ? OR classe LIKE ?",array($terme, "%".$terme."%", "%".$terme."%", "%".$terme."%"));
          }else{

           $message = "Vous devez entrer votre requete dans la barre de recherche";

          }

          if (empty($products)) {?>

            <div class="alert alert-warning">Livre indisponible<a class="btn btn-info" href="ajoutlivre.php">Ajouter un livre</a></div><?php

          }

        }else{

          $products = $DB->query('SELECT * FROM stocklivre WHERE quantite!=0 order by(classe)');
        }
        

        foreach ($products as $key=> $product){

          $qtiteR+=$product->quantite;?>

          <tr>
            <td><?=$key+1;?></td>   

            <td><?= ucwords($product->nom); ?></td>

            <td><?= ucwords($product->matiere); ?></td>

            <td><?= $product->classe; ?></td>

            <td><?= $product->allee; ?></td>

            <td><?= $product->position; ?></td>

            <td><?= $product->quantite; ?></td>

            <form action="stocklivre.php" method="POST">

              <td><input class="form-control" type="number" name="qtiteajouter"  max="<?=$product->quantite;?>"  /><input type="hidden" name="id" value="<?=$product->id;?>"></td>

              <td><input class="form-control" type="submit" name="valids" onclick="return alerteM();" value="+"></td>

            </form>

            <form action="stocklivre.php" method="POST">

              <td><input class="form-control" type="number" name="qtiteperte" /><input type="hidden" name="id" value="<?=$product->id;?>"></td>

              <td><input class="form-control" type="submit" name="valids" onclick="return alerteM();" value="-"></td>

            </form>

            <td><a class="btn btn-danger" href="stockgeneral.php?del=<?=$product->id;?>" onclick="return alerteS();">Supprimer</a></td>


          </tr><?php
        }?>


      </tbody>

      <tfoot>

        <tr>
          <th colspan="6">TOTAUX</th>
          <th><?= number_format($qtiteR,0,',',' ') ; ?> </th>

        </tr>

      </tfoot>

    </table>

  </div>
</div>

<?php require 'footer.php';?>


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