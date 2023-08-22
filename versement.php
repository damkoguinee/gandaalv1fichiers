<?php require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {

  $pseudo=$_SESSION['pseudo'];
  

  if ($products['niveau']>=3) {?>

    <div class="container-fluid">

      <div class="row"><?php 
        require 'navcompta.php';?>

      <div class="col-sm-12 col-md-10" style="overflow: auto;"><?php 

        if (isset($_GET['deletevers'])) {

          $numero=$_GET['deletevers'];
          $DB->delete('DELETE FROM versement WHERE numcmd = ?', array($numero));

          $DB->delete('DELETE FROM banque WHERE numero = ?', array($numero));?>

          <div class="alert alert-success">LE VERSEMENT A BIEN ETE SUPPRIME</div><?php
        }

        if (!isset($_POST['categorie'])) {

          if (!isset($_POST['j1'])) {

            $_SESSION['date']=date("Ymd");  
            $dates = $_SESSION['date'];
            $dates = new DateTime( $dates );
            $dates = $dates->format('Ymd'); 
            $_SESSION['date']=$dates;
            $_SESSION['date1']=$dates;
            $_SESSION['date2']=$dates;
            $_SESSION['dates1']=$dates; 

          }else{

            $_SESSION['date01']=$_POST['j1'];
            $_SESSION['date1'] = new DateTime($_SESSION['date01']);
            $_SESSION['date1'] = $_SESSION['date1']->format('Ymd');
            
            $_SESSION['date02']=$_POST['j2'];
            $_SESSION['date2'] = new DateTime($_SESSION['date02']);
            $_SESSION['date2'] = $_SESSION['date2']->format('Ymd');

            $_SESSION['dates1']=(new DateTime($_SESSION['date01']))->format('d/m/Y');
            $_SESSION['dates2']=(new DateTime($_SESSION['date02']))->format('d/m/Y');  
          }
        }

        if (isset($_POST['j2'])) {

          $datenormale='entre le '.$_SESSION['dates1'].' et le '.$_SESSION['dates2'];

        }else{

          $datenormale=(new DateTime($_SESSION['date']))->format('d/m/Y');
        }

        if (isset($_POST['clientliv'])) {
          $_SESSION['clientliv']=$_POST['clientliv'];
        }


        if (isset($_POST["valid"])) {

          if (!empty($_POST["client"]) ) {?>

            <div class="alert alert-danger">Les Champs sont vides</div><?php

          }elseif($_POST['mode_payement']=='chèque' and empty($_POST['numcheque'])){
            header("Location: versement.php?ajout");

            $alertescheque='entrer le numéro du chèque';

            $_SESSION['alertescheque']=$alertescheque;

          }else{
            unset($_SESSION['alertescheque']);
            $montantp=$panier->h($_POST['montant']);
            $devise=$panier->h($_POST['devise']);
            $client=$panier->h($_POST['client']);
            $motif=$panier->h($_POST['motif']);
            $cat=$panier->h($_POST['cat']);
            $payement=$_POST['mode_payement'];
            $compte=$panier->h($_POST['compte']);
            $taux=$panier->h($_POST['taux']);
            $convert=$montantp*$taux;
            $numcheque=$panier->h($_POST['numcheque']);
            $banquecheque=$panier->h($_POST['banquecheque']);
            $montant=$montantp*$taux;        

            $maximum = $DB->querys('SELECT max(id) AS max_id FROM versement ');

            $max=$maximum['max_id']+1;
            $dateop=$_POST['datedep'];

            if (empty($dateop)) {

              $DB->insert('INSERT INTO versement (numcmd, nom_client, montant, taux, devisevers, numcheque, banquecheque, categorie, motif, type_versement, comptedep, personnel, promo, date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array('dep'.$max, $client, $montant, $taux, $devise, $numcheque, $banquecheque, $cat, $motif, $payement, $compte, $_SESSION['idpseudo'], $_SESSION['promo']));            

              $DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, devise, taux, typep, personnel, numeropaie, banqcheque, promob, date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())', array($compte, $montant, "Depot(".$motif.')', 'dep'.$max, $devise, $taux, $payement, $_SESSION['idpseudo'], $numcheque, $banquecheque, $_SESSION['promo']));              

            }else{

              $DB->insert('INSERT INTO versement (numcmd, nom_client, montant, taux, devisevers, numcheque, banquecheque, categorie, motif, type_versement, comptedep, personnel, promo, date_versement) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array('dep'.$max, $client, $montant, $taux, $devise, $numcheque, $banquecheque, $cat, $motif, $payement, $compte, $_SESSION['idpseudo'],$_SESSION['promo'], $dateop));            

              $DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, devise, taux, typep, personnel, numeropaie, banqcheque, date_versement, promob) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($compte, $montant, "Depot(".$motif.')', 'dep'.$max, $devise, $taux, $payement, $_SESSION['idpseudo'], $numcheque, $banquecheque, $dateop, $_SESSION['promo'])); 
            }

          }

        }else{

          
        }

        if(isset($_POST["categins"])){

          $cate=$_POST['cate'];

          $proddep=$DB->query('SELECT nom FROM categorievers WHERE nom= ?', array($cate));

          if (empty($proddep)) {

            $DB->insert('INSERT INTO categorievers (nom) VALUES (?)', array($cate));

          }else{?>

            <div class="alert alert-warning">Cette catégorie existe déjà</div><?php

          }
        }

        $prodep=$DB->query('SELECT id, nom FROM categorievers');

        if(isset($_GET["categ"])){?>

          <form id="formulaire" method="POST" action="versement.php?ajout" >
            <fieldset><legend>Ajouter une catégorie</legend>
              <ol>

                <li><label>Désignation</label>
                    <input type="text" name="cate" required="">
                </li>
              </ol>
            </fieldset>

            <fieldset>

              <input type="reset" value="Annuler" name="valid" id="form" style=" cursor: pointer;"/>

              <input type="submit" value="Ajouter" name="categins" id="form" onclick="return alerteV();" style="margin-left: 20px; cursor: pointer;" />

            </fieldset>
          </form><?php
        }

        if (isset($_GET['ajout']) or isset($_GET['searchclientvers']) or isset($_GET["categ"])) {

          if (isset($_GET['searchclientvers']) ) {

              $_SESSION['searchclientvers']=$_GET['searchclientvers'];
          }

          $prodep=$DB->query('SELECT id, nom FROM categorievers');?>

          <form id="formulaire" class="form" method="POST" action="versement.php">
              <ol>          
                <li><label>Elève*</label>
                  <select type="text" name="client">
                    <option></option>
                    <option value="autres">Autres</option><?php 

                    if (!empty($_SESSION['searchclientvers'])) {?>

                        <option value="<?=$_SESSION['searchclientvers'];?>"><?=$panier->nomEleve($_SESSION['searchclientvers']);?></option><?php
                    }else{?>
                        <option></option><?php 
                    }

                    foreach($panier->listeEleve() as $product){?>
                      <option value="<?=$product->matricule;?>"><?=ucwords(strtolower($product->nomel)).' '.strtoupper(strtolower($product->prenomel));?></option><?php
                    }?>
                  </select>

                  <input style="width:400px;" id="search-user" type="text" name="clients" placeholder="rechercher un élève" />

                  <div style="color:white; background-color: black; font-size: 16px; margin-left: 300px;" id="result-search"></div>
                </li>

                <li><label>Type de recette*</label>
                    <select name="cat" required="">
                        <option></option><?php
                        foreach ($prodep as $value) {?>

                          <option value="<?=$value->id;?>"><?=ucfirst($value->nom);?></option><?php 
                        }?>
                    </select>

                    <a href="versement.php?categ">Ajouter une catégorie</a>
                </li>
                <li><label>Motif*</label><textarea type="text"   name="motif" required=""></textarea></li>

                <div style="display: flex;">
                  <div style="width: 50%;">

                    <li><label>Montant*</label><input id="numberconvert" type="number"   name="montant" min="0" required="" style="font-size: 25px; width: 50%;"></li>
                  </div>

                  <li style="width:50%;"><label style="width:50%;"><div style="color:white; background-color: grey; font-size: 25px; color: orange; width:100%;" id="convertnumber"></div></li></label>
                </div>

                <li><label>Devise*</label>
                  <label><select name="devise" required="" ><?php 
                    foreach ($panier->monnaie as $valuem) {?>
                        <option value="<?=$valuem;?>"><?=strtoupper($valuem);?></option><?php 
                    }?>
                  </select></label>

                  <label>Taux*</label><input type="number" name="taux" value="1" required=""></li>

                <li><label>Type Paie*</label>
                  <select name="mode_payement" required="" ><?php 
                    foreach ($panier->modep as $value) {?>
                      <option value="<?=$value;?>"><?=$value;?></option><?php 
                    }?>
                  </select>
                </li>

                <li><label>N°Chèque</label><label><input style="width:100px;" type="text" name="numcheque"></label><label>Banque Chèque</label>
                  <select type="text" name="banquecheque" style="width: 20%;">
                    <option></option>
                    <option value="ecobank">Ecobank</option>
                    <option value="bicigui">Bicigui</option>
                    <option value="vistagui">Vistagui</option>
                    <option value="bsic">Bsic</option>
                    <option value="uba">UBA</option>
                    <option value="banque islamique">Banque islamique</option>
                    <option value="skye bank">Skye Banq</option>
                    <option value="bci">BCI</option>
                    <option value="fbn">FBN</option>
                    <option value="societe generale">Société Générale</option>
                    <option value="orabank">Orabank</option>
                    <option value="vistabank">Vista Bank</option>
                    <option value="asses">Asses</option>
                    <option value="bpmg">BPMG</option>
                    <option value="afriland">Afriland</option>
                  </select>
                </li> 

                <li><label>Compte de dépôt*</label>
                  <select  name="compte" required="">
                      <option></option><?php
                      $type='Banque';

                      foreach($panier->nomBanque() as $product){?>

                          <option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
                      }?>
                  </select>
                </li>

                <li><label>Date de dépôt</label><input type="date" name="datedep"></li>
              </ol>
            </fieldset>

            <fieldset><input type="submit" value="Valider" name="valid" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
          </form> <?php
        }

    if (!isset($_GET['ajout']) ) {?>  

      <table class="table table-hover table-bordered table-striped table-responsive text-center">

        <thead>
          <tr><th class="legende" colspan="11" height="30"><?="Liste des recettes " .$datenormale ?> <a class="btn btn-info" href="versement.php?ajout">Enregistrer une recette</a></th></tr>

          <tr>
            <form class="form" method="POST" action="versement.php" id="suitec" name="termc">

              <th colspan="2" ><?php

                if (isset($_POST['j1'])) {?>

                  <input class="form-control" type = "date" name = "j1" onchange="this.form.submit()" value="<?=$_POST['j1'];?>"><?php

                }else{?>

                  <input class="form-control" type = "date" name = "j1" onchange="this.form.submit()"><?php

                }?>
              </th>

              <th colspan="2"><?php

                if (isset($_POST['j2'])) {?>

                  <input class="form-control" type = "date" name = "j2" value="<?=$_POST['j2'];?>" onchange="this.form.submit()"><?php

                }else{?>

                  <input class="form-control" type = "date" name = "j2" onchange="this.form.submit()"><?php

                }?>
              </th>
            </form>

            <form method="POST" action="versement.php" id="suitec" name="termc">
              <th colspan="7">
                <div class="row">
                  <div class="col sm-12 col-md-10"><?php 
                    $prod=$DB->query('SELECT id, nom FROM categorievers');

                    if (!empty($_SESSION['date1'])) {?>
                  
                      <select class="form-select" name="categorie" onchange="this.form.submit()"><?php

                        if (isset($_POST['categorie']) and $_POST['categorie']=='general') {?>

                          <option value="<?=$_POST['categorie'];?>">Général</option><?php
                          
                        }elseif (isset($_POST['categorie'])) {?>

                          <option value="<?=$_POST['categorie'];?>"><?=$panier->nomCategorieVers($_POST['categorie']);?></option><?php
                          
                        }else{?>

                          <option>Selectionnez une Catégorie</option><?php

                        }

                        foreach($prod as $product){?>

                          <option value="<?=$product->id;?>"><?=strtoupper($product->nom);?></option><?php

                        }?>                      
                      </select><?php 
                    }?>
                  </div>
                  <div class="col sm-12 col-md-2">
                    <a style="margin-left: 10px;"href="printversegenerale.php?printdep&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>&type&datenormale=<?=$datenormale;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a><a style="margin-left: 10px;"href="exportversement.php?dec" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
                  </div>
                </div>
              </th>
            </form>            
          </tr>

          <tr>
            <th>N°</th>
            <th>Date</th>
            <th>Catégorie</th>
            <th>Commentaire</th>            
            <th>Montant GNF</th>
            <th>Taux</th>
            <th>Montant Dévise</th>
            <th>Devise</th>
            <th>Type de Paie</th>
            <th colspan="2">Actions</th>
          </tr>

        </thead>

        <tbody><?php
         
          $cumulmontant=0;
          if (isset($_POST['j1'])) {

            $prod= $DB->query("SELECT *FROM versement WHERE DATE_FORMAT(date_versement, \"%Y%m%d\")>= '{$_SESSION['date1']}' and DATE_FORMAT(date_versement, \"%Y%m%d\")<= '{$_SESSION['date2']}' order by(versement.id)");

          }elseif (isset($_POST['categorie'])) {
            
            $prod= $DB->query("SELECT *FROM versement WHERE categorie='{$_POST['categorie']}' and promo='{$_SESSION['promo']}' order by(versement.id)");
                           

          }else{

            $prod= $DB->query("SELECT *FROM versement where promo='{$_SESSION['promo']}' order by(versement.id) desc ");
          }

      
        $montantgnf=0;
        $montanteu=0;
        $montantus=0;
        $montantcfa=0;
        $virement=0;
        $cheque=0;
        foreach ($prod as $keyd=> $product ){?>

          <tr>
            <td style="text-align: center;"><?= $keyd+1; ?></td>
            <td style="text-align:center;"><?=(new DateTime($product->date_versement))->format("d/m/Y"); ?></td>
            <td style="width: 150px; font-size: 14px;"><?= $panier->nomCategorieVers($product->categorie); ?></td>
            <td style="width: 150px; font-size: 14px;"><?= ucwords(strtolower($product->motif)); ?></td>
            <?php

            $montantgnf+=$product->montant;
            $montanteu+=$product->montant/$product->taux;?>

            <td style="text-align: right; padding-right: 10px;"><?= number_format($product->montant,2,',',' '); ?></td>

            <td style="text-align: right; padding-right: 10px;"><?= number_format($product->taux,2,',',' '); ?></td>

            <td style="text-align: right; padding-right: 10px;"><?= number_format($product->montant/$product->taux,2,',',' '); ?></td>

            <td style="text-align: right; padding-right: 10px;"><?= $product->devisevers; ?></td>
            <td style="text-align: right; padding-right: 10px;"><?= $product->type_versement; ?></td>
            <td style="text-align: center"><a class="btn btn-info" href="printversement.php?numdec=<?=$product->id;?>&idc=<?=$product->nom_client;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></td>

            <td><?php if ($products['type']=='informaticien' or $products['type']=='comptable' or $products['type']=='admin') {?><a class="btn btn-danger" onclick="return alerteS();" href="versement.php?deletevers=<?=$product->numcmd;?>">Supprimer</a><?php };?></td>
            
          </tr><?php 
        }?>

      </tbody>

      <tfoot>
        <tr>
          <th colspan="4">Totaux Versements</th>
          <th style="text-align: right; padding-right: 10px;"><?= number_format($montantgnf,0,',',' ');?></th>
          <th></th>
          <th style="text-align: right; padding-right: 10px;"><?= number_format($montanteu,0,',',' ');?></th>
        </tr>
      </tfoot>

    </table><?php
  }

      

    }else{

      echo "VOUS N'AVEZ PAS LES AUTORISATIONS REQUISES";

    }

  }else{

  }?>

  <?php require 'footer.php';?>
    
</body>

</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script><?php 

if (isset($_GET['client'])) {?>

  <script>
      $(document).ready(function(){
          $('#search-user').keyup(function(){
              $('#result-search').html("");

              var utilisateur = $(this).val();

              if (utilisateur!='') {
                  $.ajax({
                      type: 'GET',
                      url: 'recherche_utilisateur.php?clientvers',
                      data: 'user=' + encodeURIComponent(utilisateur),
                      success: function(data){
                          if(data != ""){
                            $('#result-search').append(data);
                          }else{
                            document.getElementById('result-search').innerHTML = "<div style='font-size: 20px; text-align: center; margin-top: 10px'>Aucun utilisateur</div>"
                          }
                      }
                  })
              }
        
          });
      });
  </script><?php 
}else{?>

  <script>
      $(document).ready(function(){
          $('#search-user').keyup(function(){
              $('#result-search').html("");

              var utilisateur = $(this).val();

              if (utilisateur!='') {
                  $.ajax({
                      type: 'GET',
                      url: 'recherche_utilisateur.php?versclient',
                      data: 'user=' + encodeURIComponent(utilisateur),
                      success: function(data){
                          if(data != ""){
                            $('#result-search').append(data);
                          }else{
                            document.getElementById('result-search').innerHTML = "<div style='font-size: 20px; text-align: center; margin-top: 10px'>Aucun utilisateur</div>"
                          }
                      }
                  })
              }
        
          });
      });
  </script><?php

} ?>

<script>
    $(document).ready(function(){
        $('#numberconvert').keyup(function(){
            $('#convertnumber').html("");

            var utilisateur = $(this).val();

            if (utilisateur!='') {
                $.ajax({
                    type: 'GET',
                    url: 'convertnumber.php?convertvers',
                    data: 'user=' + encodeURIComponent(utilisateur),
                    success: function(data){
                        if(data != ""){
                          $('#convertnumber').append(data);
                        }else{
                          document.getElementById('convertnumber').innerHTML = "<div style='font-size: 20px; text-align: center; margin-top: 10px'>Aucun utilisateur</div>"
                        }
                    }
                })
            }
      
        });
    });
  </script> 

<script type="text/javascript">
    function alerteS(){
        return(confirm('Valider la suppression'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation'));
    }

    function focus(){
        document.getElementById('pointeur').focus();
    }


    window.onload = function() { 
        for(var i = 0, l = document.getElementsByTagName('input').length; i < l; i++) { 
            if(document.getElementsByTagName('input').item(i).type == 'text') { 
                document.getElementsByTagName('input').item(i).setAttribute('autocomplete', 'off'); 
            }; 
        }; 
    };

</script>
