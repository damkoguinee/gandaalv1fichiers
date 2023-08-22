<?php
if (isset($_GET['mateleve'])) {
	require 'headereleve.php';
}else{

	require 'header.php';
}

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{
    	//require 'navformation.php';?></div><?php

    	if (isset($_GET['docens'])) {?>

    		<div style="width: 80%;">
    			<div style="display: flex;"><?php

					$nom_dossier="justificatifens/".$_GET['docens']."/";
					if (file_exists($nom_dossier)) {

						$dossier=opendir($nom_dossier);
						while ($fichier=readdir($dossier)) {

							if ($fichier!='.' && $fichier!='..') {?>

								<div style="margin-top: 30px; margin-left: 30px; border: 2px solid black; height: 100px;">

									<a href="justificatifens/<?=$_GET['docens'];?>/<?=$fichier;?>" target="_blank"><img  style="height: 80px; width: 80px;" src="css/img/pdf.jpg"><br/><?=$fichier;?></a>

								</div><?php
							}
						}closedir($dossier);
					}?>
				</div>
				<div style="width: 100%;"><?php

					require 'fiche_ens.php';?>
				</div>
			</div><?php
		}

		if (isset($_GET['docpers'])) {?>

    		<div style="width: 80%;">
    			<div style="display: flex;"><?php

					$nom_dossier="justificatifpers/".$_GET['docpers']."/";
					if (file_exists($nom_dossier)) {

						$dossier=opendir($nom_dossier);
						while ($fichier=readdir($dossier)) {

							if ($fichier!='.' && $fichier!='..') {?>

								<div style="margin-top: 30px; margin-left: 30px; border: 2px solid black; height: 100px;">

									<a href="justificatifpers/<?=$_GET['docpers'];?>/<?=$fichier;?>" target="_blank"><img  style="height: 80px; width: 80px;" src="css/img/pdf.jpg"><br/><?=$fichier;?></a>

								</div><?php
							}
						}closedir($dossier);
					}?>
				</div>
				<div style="width: 100%;"><?php

					require 'fiche_ens.php';?>
				</div>
			</div><?php
		}

		if (isset($_GET['docel'])) {?>

    		<div style="width: 80%;">
    			<div style="display: flex;"><?php

					$nom_dossier="justificatif/".$_GET['docel']."/";
					if (file_exists($nom_dossier)) {

						$dossier=opendir($nom_dossier);
						while ($fichier=readdir($dossier)) {

							if ($fichier!='.' && $fichier!='..') {?>

								<div style="margin-top: 30px; margin-left: 30px; border: 2px solid black; height: 100px;">

									<a href="justificatifpers/<?=$_GET['docel'];?>/<?=$fichier;?>" target="_blank"><img  style="height: 80px; width: 80px;" src="css/img/pdf.jpg"><br/><?=$fichier;?></a>

								</div><?php
							}
						}closedir($dossier);
					}?>
				</div>
				<div style="width: 100%;"><?php

					require 'fiche_eleve.php';?>
				</div>
			</div><?php
		}
	}
}
	