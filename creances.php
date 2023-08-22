<?php require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {

  $pseudo=$_SESSION['pseudo'];
  

  if ($products['niveau']>=3) {?>

    <div class="container-fluid">

      <div class="row"><?php 
        require 'navDetteCreance.php';?>

      <div class="col-sm-12 col-md-10" style="overflow: auto;"><?php 

        if (isset($_GET['deletedette'])) {

          $numero=$_GET['deletedette'];
          $DB->delete('DELETE FROM dettesCreances WHERE numcmd = ?', array($numero));

          $DB->delete('DELETE FROM banque WHERE numero = ?', array($numero));?>

          <div class="alert alert-success">L'opération a bien été supprimé'</div><?php
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

          if (empty($_POST["client"]) or empty($_POST["montant"]) or empty($_POST['devise'])) {?>

            <div class="alert alert-danger">Les Champs sont vides</div><?php

          }elseif($_POST['mode_payement']=='chèque' and empty($_POST['numcheque'])){
            header("Location: creances.php?ajout");

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
            $dateop=$_POST['datedep'];
            $prodmax= $DB->querys("SELECT max(id) AS max_id FROM dettesCreances where categorie='{$cat}' ");
            $max=$prodmax['max_id']+1;
            if (empty($dateop)) {
                $dateop=date("Y-m-d h:i");
            }else{
                $dateop=$dateop;
            }

            $DB->insert('INSERT INTO dettesCreances (numcmd, nom_client, montant, taux, devise, numcheque, banquecheque, categorie, motif, typep, comptedep, personnel, dateop, promo) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array('creance'.$max, $client, $montant, $taux, $devise, $numcheque, $banquecheque, $cat, $motif, $payement, $compte, $_SESSION['idpseudo'], $dateop, $_SESSION['promo']));            

            $DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, devise, taux, typep, personnel, numeropaie, banqcheque, date_versement, promob) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($compte, -$montant, "creance(".$motif.')', 'creance'.$max, $devise, $taux, $payement, $_SESSION['idpseudo'], $numcheque, $banquecheque, $dateop, $_SESSION['promo'])); 
            

          }

        }

        if(isset($_POST["categins"])){

          $cate=$panier->h($_POST['cate']);
          $contact=$panier->h($_POST['contact']);

          $proddep=$DB->query('SELECT nom FROM collaborateur WHERE nom= ? and contact=?', array($cate, $contact));

          if (empty($proddep)) {

            $DB->insert('INSERT INTO collaborateur (nom, contact) VALUES (?, ?)', array($cate, $contact));

          }else{?>

            <div class="alert alert-warning">Ce collaborateur existe déjà</div> <?php

          }
        }

        $prodep=$DB->query('SELECT id, nom, contact FROM collaborateur');

        if(isset($_GET["categ"])){?>

          <form id="formulaire" method="POST" action="creances.php?ajout" >
            <fieldset><legend>Ajouter un collaborateur</legend>
              <ol>
                <li><label>Nom & Prénom</label>
                    <input type="text" name="cate" required="">
                </li>
                <li><label>Contact</label>
                    <input type="number" name="contact" required="">
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
          }?>

          <form id="formulaire" class="form" method="POST" action="creances.php">
              <ol>          
                <li><label>Collaborateur*</label>
                  <select type="text" name="client">
                    <option></option><?php 

                    if (!empty($_SESSION['searchclientvers'])) {?>

                        <option value="<?=$_SESSION['searchclientvers'];?>"><?=$panier->nomCollaborateur($_SESSION['searchclientvers'])[0];?></option><?php
                    }else{?>
                        <option></option><?php 
                    }

                    foreach($panier->listeCollaborateur() as $product){?>
                      <option value="<?=$product->id;?>"><?=ucwords(strtolower($product->nom));?></option><?php
                    }?>
                  </select>
                  <a href="creances.php?categ">Ajouter un collaborateur</a>
                </li>

                <li><label>Type*</label>
                    <select name="cat" required="">
                        <option value="creance">Créances</option>
                    </select>
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

                <li><label>Type de Paie*</label>
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

                <li><label>Compte*</label>
                  <select  name="compte" required="">
                      <option></option><?php
                      $type='Banque';

                      foreach($panier->nomBanque() as $product){?>

                          <option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
                      }?>
                  </select>
                </li>

                <li><label>Date d'opération</label><input type="date" name="datedep"></li>
              </ol>
            </fieldset>

            <fieldset><input type="submit" value="Valider" name="valid" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
          </form> <?php
        }

        if (isset($_POST["validpaie"])) {

            if (empty($_POST["client"]) or empty($_POST["montant"]) or empty($_POST['devise'])) {?>
  
              <div class="alert alert-danger">Les Champs sont vides</div><?php
  
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
                $dateop=$_POST['datedep'];
                $prodmax= $DB->querys("SELECT max(id) AS max_id FROM dettesCreances where categorie='{$cat}' ");
                $max=$prodmax['max_id']+1;
                if (empty($dateop)) {
                  $dateop=date("Y-m-d h:i");
                }else{
                  $dateop=$dateop;
                }
                $DB->insert('INSERT INTO dettesCreances (numcmd, nom_client, montant, taux, devise, numcheque, banquecheque, categorie, motif, typep, comptedep, personnel, dateop, promo) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array('paiecreance'.$max, $client, $montant, $taux, $devise, $numcheque, $banquecheque, $cat, $motif, $payement, $compte, $_SESSION['idpseudo'], $dateop, $_SESSION['promo']));            
  
                $DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, devise, taux, typep, personnel, numeropaie, banqcheque, date_versement, promob) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($compte, $montant, "paiecreance(".$motif.')', 'paiecreance'.$max, $devise, $taux, $payement, $_SESSION['idpseudo'], $numcheque, $banquecheque, $dateop, $_SESSION['promo']));
  
            }
  
        }

        if (isset($_GET['paiedette'])) {?>
  
            <form id="formulaire" class="form" method="POST" action="creances.php">
                <ol>          
                  <li><label>Collaborateur*</label>
                    <select type="text" name="client">  
                        <option value="<?=$_GET['paiedette'];?>"><?=$panier->nomCollaborateur($_GET['paiedette'])[0];?></option>
                    </select>
                    <input type="hidden" name="cat" value="<?=$_GET['cat'];?>">
                  </li>                 
  
                  <div style="display: flex;">
                    <div style="width: 50%;">
  
                      <li><label>Montant*</label><input id="numberconvert" type="number" name="montant" value="<?=$_GET['reste'];?>" min="0" max="<?=$_GET['reste'];?>" required="" style="font-size: 25px; width: 50%;"></li>
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
  
                  <li><label>Type de Paie*</label>
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

                  <li><label>Commentaire*</label><textarea type="text"   name="motif" required=""></textarea></li>
  
                  <li><label>Date d'opération</label><input type="date" name="datedep"></li>
                </ol>
              </fieldset>
  
              <fieldset><input type="submit" value="Valider" name="validpaie" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
            </form> <?php
        }

        if (!isset($_GET['ajout']) ) {?>  

            <table class="table table-hover table-bordered table-striped table-responsive text-center">

                <thead>

                    <tr>
                        <th colspan="7">
                            <div class="row">
                                <div class="col sm-12 col-md-10">
                                    <?="Liste de vos créances " .$datenormale ?> <a class="btn btn-info" href="creances.php?ajout">Enregistrer une créance</a>
                                </div>
                            </div>
                        </th>          
                    </tr>

                    <tr>
                        <th>N°</th>          
                        <th>Collaborateur</th>            
                        <th>Montant GNF</th>
                        <th>Payé GNF</th>
                        <th>Reste GNF</th>
                        <th colspan="2">Actions</th>
                    </tr>

                </thead>

                <tbody><?php
                    $cumuldettegnf=0;
                    $cumulpaiegnf=0;
                    $cumulrestegnf=0;
                    $categorie="creance";
                    $categorie1="paiementcreance";
                    foreach ($panier->listeCollaborateur() as $keyd => $valuec) {

                        $proddette= $DB->querys("SELECT sum(montant) as montant FROM dettesCreances where nom_client='{$valuec->id}' and categorie='{$categorie}' ");
                        $prodpaie= $DB->querys("SELECT sum(montant) as montant FROM dettesCreances where nom_client='{$valuec->id}' and categorie='{$categorie1}' ");
                        $dettegnf=$proddette['montant'];
                        $paiegnf=$prodpaie['montant'];
                        $restegnf=$dettegnf-$paiegnf;
                        $reste=0;
                        $cumuldettegnf+=$dettegnf;
                        $cumulpaiegnf+=$paiegnf;
                        $cumulrestegnf+=$restegnf;?>
                        <tr>
                            <td><?= $keyd+1; ?></td>
                            <td class="text-start"><?=$panier->nomCollaborateur($valuec->id)[0]; ?></td>
                            <td class="text-end"><?= number_format($dettegnf,0,',',' '); ?></td> 
                            <td class="text-end"><?= number_format($paiegnf,0,',',' '); ?></td> 
                            <td class="text-end text-danger"><?= number_format($restegnf,0,',',' '); ?></td> 
                            <td><a class="btn btn-primary" href="creances.php?consulter=<?=$valuec->id;?>">Consulter</a></td>          
                            <td><a onClick="alerteV()" class="btn btn-success" href="creances.php?paiedette=<?=$valuec->id;?>&reste=<?=$restegnf;?>&cat=<?="$categorie1";?>">Payé</a></td>          
                        </tr><?php 
                    }?>

                </tbody>

                <tfoot>
                    <tr>
                    <th colspan="2">Totaux</th>
                    <th class="text-end"><?= number_format($cumuldettegnf,0,',',' ');?></th>
                    <th class="text-end"><?= number_format($cumulpaiegnf,0,',',' ');?></th>
                    <th class="text-end bg-danger text-white p-2"><?= number_format($cumulrestegnf,0,',',' ');?></th>
                    </tr>
                </tfoot>

            </table><?php
        }

        if (isset($_GET['consulter']) ) {
            $categorie="creance";
            $categorie1="paiementcreance"; ?>

            <table class="table table-hover table-bordered table-striped table-responsive text-center my-4">

                <thead>

                    <tr>
                        <th colspan="7">Détail du Compte <span class="bg-warning text-white p-2"><?=$panier->nomCollaborateur($_GET['consulter'])[0];?></span></th>          
                    </tr>

                    <tr>
                        <th>N°</th>          
                        <th>Date</th>
                        <th>Commentaire</th>            
                        <th>Créances</th>
                        <th>Paiement</th>
                        <th>Solde Compte</th>                        
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody><?php
                    $cumulentree=0;
                    $cumulsortie=0;
                    $cumulsolde=0;

                    $prod= $DB->query("SELECT *FROM dettesCreances where nom_client='{$_GET['consulter']}' and (categorie='{$categorie}' or categorie='{$categorie1}') order by(dateop) ");
                    foreach ($prod as $key => $value) {
                        if ($value->categorie=="creance") {
                            $sortie=$value->montant;
                            $entree=0;
                        }else{
                            $sortie=0;
                            $entree=$value->montant;
                        }
                        $cumulentree+=$entree;
                        $cumulsortie+=$sortie;
                        $solde=$sortie-$entree;
                        $cumulsolde+=$solde;?>
                        <tr>
                            <td><?= $keyd+1; ?></td>
                            <td class="text-center"><?=(new DateTime($value->dateop))->format("d/m/Y"); ?></td>
                            <td class="text-start"><?=$value->motif; ?></td><?php 
                            if (empty($sortie)) {?>
                                <td></td><?php
                            }else{?>
                                <td class="text-end"><?= number_format($sortie,0,',',' '); ?></td> <?php 
                            }
                            if (empty($entree)) {?>
                                <td></td><?php
                            }else{?>
                                <td class="text-end"><?= number_format($entree,0,',',' '); ?></td> <?php 
                            }?> 
                            <td class="text-end text-danger"><?= number_format($cumulsolde,0,',',' '); ?></td>         
                            <td><?php 
                                if ($_SESSION['type']=='admin' or $_SESSION['type']=='comptable') {?>
                                    <a onClick="alerteS()" class="btn btn-danger" href="creances.php?deletedette=<?=$value->numcmd;?>">Annuler</a><?php 
                                }?>
                            </td>          
                        </tr><?php 
                    }?>

                </tbody>

                <tfoot>
                    <tr>
                    <th colspan="3">Totaux</th>
                    <th class="text-end"><?= number_format($cumulsortie,0,',',' ');?></th>
                    <th class="text-end"><?= number_format($cumulentree,0,',',' ');?></th>
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
