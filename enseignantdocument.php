<?php

require 'headerenseignant.php';


if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{
    	//require 'navformation.php';?></div><?php

    	if (isset($_GET['docens'])) {?>
    		

    		<fieldset><legend>Mes documents</legend>
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
				
			</fieldset><?php
		}

		
	}
}
	