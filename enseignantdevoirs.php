<?php require 'headerenseignant.php'?><?php

if (isset($_GET['ficheens'])) {
    $_SESSION['numeendevoir']=$_GET['ficheens'];
}

if (isset($_GET['deledep'])) {

    $nom=addslashes(Htmlspecialchars($_GET['codens']));
    $nomat=addslashes(Htmlspecialchars($_GET['nomat']));
    $numdev=addslashes(Htmlspecialchars($_GET['iddevoir']));
    $classe=addslashes(Htmlspecialchars($_GET['classe']));

    //$filename="img/".$_GET['supimg'].'.jpg';

    $filename="devoirsmaison/".$nom."/".$nomat."/".$numdev."/".$classe ;

    //unlink ($filename);

    $DB->delete('DELETE FROM devoirmaison WHERE id = ?', array($_GET['deledep']));?>

    <div class="alerteV">devoir supprimé avec succèe</div><?php
}

$prodclasse=$DB->query("SELECT *from enseignement where codens='{$_SESSION['numeendevoir']}' and promo='{$_SESSION['promo']}' order by(nomgr)");

if(isset($_POST['ajoutdep'])){

    if($_POST['nomdev']!="" and $_POST['classe']!="" and $_POST['datedep']!=""){
        
        $nomdev=addslashes(Htmlspecialchars($_POST['nomdev']));
        $nomat=addslashes(Htmlspecialchars($_POST['nomat']));
        $classe=addslashes(Htmlspecialchars($_POST['classe']));
        $datedep=addslashes(Htmlspecialchars($_POST['datedep']));

        $maxid = $DB->querys('SELECT max(id) as id FROM devoirmaison');

        $numdev=$maxid['id']+1;

        if(isset($_POST["env"])){

            require "uploaddevoir.php";
        }
            
        $DB->insert('INSERT INTO devoirmaison(iddevoir, nom, matiere, classe, codens, datedepot, promo, datearendre) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numdev, $nomdev, $nomat, $classe, $_SESSION['numeendevoir'], $datedep, $_SESSION['promo']));?>

        <div class="alerteV">Devoir enregistré avec succèe!!</div><?php

    }else{?>    

        <div class="alertes">Remplissez les champs vides</div><?php
    }
    
}

if (isset($_GET['ajoutdevoir'])) {?>
                
    <div>
        <form id="formulaire" method="POST" action="enseignantdevoirs.php?enseignant" enctype="multipart/form-data">

            <fieldset><legend>Enregistrer un devoir</legend>
                <ol>
                    <li><label>Matiere</label><select name="nomat" required="" ><?php 
                    foreach ($prodclasse as $classe) {?>
                        <option value="<?=$classe->codem;?>"><?=$panier->nomMatiere($classe->codem);?></option><?php 
                    }?></select></li>

                    <li><label>Nom du devoir</label>
                        <input type="text" name="nomdev" required="" maxlength="150"></textarea>
                    </li>

                    <li><label>Classe</label><select name="classe" required="" >
                    <option value=""></option><?php 
                    foreach ($prodclasse as $classe) {?>
                        <option value="<?=$classe->idclasse;?>"><?=$classe->nomgr;?></option><?php 
                    }?></select></li>

                    <li><label>A rendre le</label>
                        <input type="date" name="datedep">
                    </li>

                    <li><label>Télécharger</label>
                        <input type="file" name="just[]"multiple id="photo" />
                        <input type="hidden" value="b" name="env"/>
                    </li>

                </ol>
            </fieldset>

            <fieldset style="margin-top: -30px;"><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajoutdep" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
        </form>
    </div><?php
}

if (!isset($_GET['ajoutdevoir'])) {?>

    <div style="display: flexx; flex-wrap: wrap;"><?php      


        foreach ($prodclasse as $classe) {?>


            <div style="margin-right: 3px;"><?php

                $proddevoir = $DB->query("SELECT *FROM devoirmaison WHERE codens='{$_SESSION['numeendevoir']}' and classe='{$classe->idclasse}' and promo='{$_SESSION['promo']}' order by(id)");?>

                <table class="payement" style="width: 70%;">
                    <thead>
                        <tr>
                            <th colspan="7">Liste des Devoirs de la <?=$classe->nomgr;?> <a href="enseignantdevoirs.php?ajoutdevoir&enseignant">Ajouter un devoir</a></th>
                        </tr>

                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Classe</th>
                            <th>Deposé le</th>
                            <th>A rendre le</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead><?php
                                                              
                    foreach ($proddevoir as $key=> $devoir) {?>

                        <tbody>
                            <tr>

                                <td style="text-align: center;"><?=ucfirst($key+1);?></td>

                                <td><?=ucfirst($devoir->nom);?></td>

                                <td style="text-align: center;"><?=$panier->nomClasseById($devoir->classe)[0];?></td>

                                <td style="text-align: center;"><?=(new dateTime($devoir->datedepot))->format('d/m/Y');?></td>

                                <td style="text-align: center;"><?=(new dateTime($devoir->datearendre))->format('d/m/Y');?></td>

                                <td style="text-align: center"><?php
                                    $num=$devoir->codens;
                                    $nomat=$devoir->matiere;
                                    $numdev=$devoir->iddevoir;
                                    $classe=$devoir->classe;
                                    $nom_dossier="devoirsmaison/".$num."/".$nomat."/".$numdev."/".$classe."/";
                                    if (file_exists($nom_dossier)) {

                                        $dossier=opendir($nom_dossier);
                                        while ($fichier=readdir($dossier)) {

                                            if ($fichier!='.' && $fichier!='..') {?>

                                                <a href="<?=$nom_dossier;?><?=$fichier;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a><?php
                                            }
                                        }closedir($dossier);
                                    }?>
                                </td>

                                <td>
                                    <a href="enseignantdevoirs.php?deledep=<?=$devoir->id;?>&enseignant&iddevoir=<?=$devoir->iddevoir;?>&codens=<?=$devoir->codens;?>&nomat=<?=$devoir->matiere;?>&classe=<?=$devoir->classe;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="font-size: 16px; background-color: red; color: white; cursor: pointer"></a>
                                </td>
                            </tr>
                        </tbody><?php
                    }?>
                </table>
            </div><?php
        }?>
    </div><?php 
}?>

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

</script>