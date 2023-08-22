<?php $products = $DB->query('SELECT * FROM enseignant inner join personnel ');

if (!empty($_SESSION['niveauf'])) {

  $prodgroup=$DB->query('SELECT nomgr, codef from groupe where niveau=:niv', array('niv'=>$_SESSION['niveauf']));

}else{

  $prodgroup=$DB->query('SELECT nomgr, codef from groupe ');
}

if (isset($_POST['nomg'])) {
  $prodgroupmat=$DB->querys('SELECT nomgr, codef from groupe where nomgr=:nom ', array("nom"=>$_POST['nomg']));
  $prodmat=$DB->query('SELECT *from matiere where codef=:code order by(nommat) ', array("code"=>$prodgroupmat["codef"]));
  
}else{
  $prodmat=$DB->query('SELECT *from matiere order by(nommat) ');
}

if (isset($_POST['matriens'])) {
  $idclient=$_POST['matriens'];
  $_SESSION['idclient']=$_POST['matriens'];
  $productid = $DB->querys('SELECT numpers, nom, prenom FROM personnel where numpers=:ID', array('ID'=>$idclient));
}

if (isset($_GET['numc'])) {
  $idclient=$_GET['numc'];
  $_SESSION['idclient']=$_GET['numc'];              
  $productid = $DB->querys('SELECT numpers, nom, prenom FROM personnel where numpers=:ID', array('ID'=>$idclient));
}

if (isset($_GET['horairecherc'])) {
  $idclient=$_GET['horairecherc'];
  $_SESSION['idclient']=$_GET['horairecherc']; 
}?>

<form action="comptabilite.php" method="post" id="formulaire" style="background-color: red;">                
  <fieldset><legend>Saisie Horaires </legend> 
    <ol>
      <li><label>N°Matricule</label><?php

        if (empty($_SESSION['idclient'])) {?>

          <input type="text" name="matriens" onKeyUp="suivant(this,'clienth', 5)" onchange="document.getElementById('formulaire').submit()" /><?php

        }else{?>

          <input type="text" name="matriens" onKeyUp="suivant(this,'clienth', 5)" onchange="document.getElementById('formulaire').submit()" value="<?=$_SESSION['idclient']; ?>" /></td><?php
        }?><a href="enseignant.php?enseig=<?='hor';?>&effnav" style=" color: white; font-weight: bold;">Rechercher </a>
      </li>

      <li>

        <label>Classe</label>
        <select type="text" name="nomg" required="" onchange="this.form.submit()"><?php

          if (isset($_POST['nomg'])) {?>
            <option><?=$_POST['nomg'];?></option><?php
          }else{?>
            <option></option><?php
          }

          foreach ($prodgroup as $codef) {?>

            <option value="<?=$codef->nomgr;?>"><?=$codef->nomgr;?></option><?php
                        
          }?>
        </select>

      </li>

      <li>

        <label>Matiere</label>
        <select type="text" name="nommat" required="">
          <option></option><?php
          foreach ($prodmat as $mat) {?>

            <option value="<?=$mat->codem;?>"><?=ucwords($mat->nommat);?></option><?php
                        
          }?>
        </select>

      </li>

      <li><label>Heure de debut</label><input  type="time" name="heured" required=""  /></li>

      <li><label>Nbre d'heures</label><input  type="text" name="totheure" required=""  /></li>

      <li><label>Date</label><input  type="date" name="datet" required=""  /></li>

      

      <li><label>Année-scolaire</label>

        <select type="text" name="promo" required=""><?php
          
            $annee=date("Y")+1;

            for($i=2020;$i<=$annee ;$i++){
                $j=$i+1;?>

                <option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

            }?>
        </select>

      </li>

    </ol>

  </fieldset>

  <fieldset><input type="reset" value="Annuler" name="reseth" id="form" style="cursor: pointer;" /><input type="submit" value="Valider" name="horairet" id="form" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>

</form><?php

if (isset($_POST['horairet'])) {
 
  $DB->insert('INSERT INTO horairet(numens, heured, heuret, datet, groupe, matiere, annees, datesaisie) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($_POST['matriens'], $_POST['heured'], $_POST['totheure'], $_POST['datet'], $_POST['nomg'], $_POST['nommat'], $_POST['promo']));?>

  <div class="alerteV">Heure(s) enregistrées avec succèe!!</div><?php
}

if(!isset($_GET['horaire'])){

  if(isset($_POST['horairet'])){

    $numeen=$_POST['matriens'];

  }else{

    $numeen=$_SESSION['idclient'];

  }

  if ($numeen[0]=='p') {
            
    $products=$DB->querys('SELECT numpers as mat, nom as nomen, prenom as prenomen, date_format(datenaissance,\'%d/%m/%Y \') as naissance, phone, email from personnel inner join contact on numpers=matricule where numpers=:mat', array('mat'=>$numeen));

  }else{

    $products=$DB->querys('SELECT enseignant.matricule as mat, nomen, prenomen, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email from enseignant inner join contact on enseignant.matricule=contact.matricule where enseignant.matricule=:mat', array('mat'=>$numeen));
  }

  $nom=strtoupper($products['nomen']).' '.ucwords($products['prenomen']); //pour recuperer le nom dans le pdf?>

  <table class="payement" >
    <thead>
        <tr>
            <th></th>
            <th colspan="6">Heure(s) effectuées <a style="margin-left: 10px;"href="printdoc_mini.php?horairemp=<?=$numeen;?>&nomel=<?=$nom;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
        </tr>

        <tr>
            <th height="25">Jour</th>
            <th>Groupe</th>
            <th>Matiere</th>
            <th>H. de debut</th>
            <th>Nbre Heure(s)</th>
            <th></th>
        </tr>
    </thead>

    <tbody><?php

        $heuret=0;

        $prodpaye = $DB->query('SELECT horairet.id as id, numens, heuret, heured, nommat, DATE_FORMAT(datet, \'%d/%m/%Y\')AS datet, groupe FROM horairet inner join matiere on matiere=codem WHERE numens = :mat and annees=:promo ORDER BY(datet) DESC', array('mat'=> $numeen, 'promo'=>$_SESSION['promo']));

                                                      
        foreach ($prodpaye as $paye) {

            $heuret+=$paye->heuret; ?>

            <tr>

                <td style="text-align: center;"><?=$paye->datet;?> </td>

                <td style="text-align: center;"><?=$paye->groupe;?> </td>

                <td><?=ucwords($paye->nommat);?> </td>

                <td style="text-align: right;"><?=$paye->heured;?></td>

                <td style="text-align: center;"><?=$paye->heuret;?> h</td>

                <td>
                    <a href="printdoc_mini.php?heuretr=<?=$paye->id; ?>&date=<?=$paye->datet; ?>&numens=<?=$numeen;?>&nomel=<?=$nom;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                    <a href="comptabilite.php?delehoraire=<?=$paye->id;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 77%; font-size: 16px; background-color: red; color: white; cursor: pointer"></a>
                </td>
            </tr><?php
        }?>

        <tr>
            <th colspan="4"></th>
            <th><?=$heuret;?> h</th>
            <th></th>
        </tr>

    </tbody>
  </table><?php
}?>


<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer cette facture ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>

  