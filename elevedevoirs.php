<?php require 'headereleve.php'?><?php 

$inscrit=$DB->querys("SELECT nomgr, codef from inscription where matricule='{$_SESSION['matricule']}' and annee='{$_SESSION['promo']}'");

$prodclasse=$DB->query("SELECT *from enseignement where nomgr='{$inscrit['nomgr']}' and promo='{$_SESSION['promo']}' order by(codem)");?>

<div style="margin-top: 30px;">
    <div style="display: flex; flex-wrap: wrap;">
        <fieldset style="display: flex;"><legend>Liste des Matières</legend><?php

            foreach ($prodclasse as $classe) {?>

                <div class="optiong" >
                    <a href="elevedevoirs.php?matiere=<?=$classe->codem;?>&classe=<?=$classe->idclasse;?>&codens=<?=$classe->codens;?>">
                    <div class="descript_optiong"><?=$panier->nomMatiere($classe->codem);?></div></a>
                </div><?php 
            }?>
        </fieldset>
    </div>
</div>

    <div><?php

        if (isset($_GET['matiere'])) {

            $proddevoir = $DB->query("SELECT *FROM devoirmaison WHERE matiere='{$_GET['matiere']}' and classe='{$_GET['classe']}' and promo='{$_SESSION['promo']}' order by(id)"); 
            if (!empty($proddevoir)) {?>

                <table class="payement">
                    <thead>
                        <tr>
                            <th colspan="6">Liste des Devoirs en <?=$panier->nomMatiere($_GET['matiere']);?> / Enseignant(e): <?=$panier->nomEnseignant($_GET['codens']);?></th>
                        </tr>

                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Classe</th>
                            <th>Deposé le</th>
                            <th>A rendre le</th>
                            <th>Télécharger le devoir</th>
                        </tr>
                    </thead><?php
                                                                  
                    foreach ($proddevoir as $key=> $devoir) {?>

                        <tbody>
                            <tr>

                                <td style="text-align: center;"><?=ucfirst($key+1);?></td>

                                <td><?=ucfirst($devoir->nom);?></td>

                                <td style="text-align: center;"><?=$panier->nomClasseById($devoir->classe)[0];?></td>

                                <td style="text-align: center;"><?=(new dateTime($devoir->datedepot))->format('d/m/Y');?></td>

                                <td style="text-align: center; color: red;"><?=(new dateTime($devoir->datearendre))->format('d/m/Y');?></td>

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

                                                <a href="<?=$nom_dossier;?><?=$fichier;?>" target="_blank"><img  style="height: 50px; width: 50px;" src="css/img/pdf.jpg"></a><?php
                                            }
                                        }closedir($dossier);
                                    }?>
                                </td>
                            </tr>
                        </tbody><?php
                    }?>
                </table><?php
            }else{?>
                <div class="alerteV">Vous n'avez acun devoir</div><?php 

            }
            
        }?>
    </div>
</div>

