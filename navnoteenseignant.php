<div style="width: 20%;">

    <fieldset style="margin-top: 30px;"><legend></legend><?php 

        if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='informaticien' or $products['type']=='Directeur Général' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='surveillant Général' or $products['type']=='coordonateur bloc B' or $products['type']=='Directeur du primaire') {?>

            <div class="choixg">
                <div class="optiong">
                <a href="horairegen.php?horaire">
                <div class="descript_optiong">Horaires</div></a>
            </div>

            <div class="choixg">
                <div class="optiong">
                <a href="horaireenvoye.php?horaire">
                <div class="descript_optiong">Heures Transmises</div></a>
            </div><?php 
        }

        if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='informaticien' or $products['type']=='Directeur Général' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='enseignant' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=="Conseille a l'éducation" or $products['type']=='coordonateur bloc B' or $products['type']=='Directeur du primaire'  or $products['type']=='secrétaire') {?>

            <div class="choixg">
                <div class="optiong">
                <a href="enseignantnote.php?enseignant">
                <div class="descript_optiong">Notes</div></a>
            </div>      

            <div class="optiong">
                <a href="bulletin.php?enseignant">
                <div class="descript_optiong">Bulletin</div></a>
            </div>

            <div class="choixg">
                <div class="optiong">
                <a href="planingj.php?appel&enseignant">
                <div class="descript_optiong">Faire Appel</div></a>
            </div>

            <div class="choixg">
                <div class="optiong">
                <a href="absence.php?appel&enseignant">
                <div class="descript_optiong">Absences</div></a>
            </div><?php 
        }

        if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='informaticien' or $products['type']=='Directeur Général' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='coordonateur bloc B' or $products['type']=='Directeur du primaire'  or $products['type']=='secrétaire') {?>

            <div class="choixg">
                <div class="optiong">
                <a href="planing.php">
                <div class="descript_optiong">Emploi du temps</div></a>
            </div><?php 
        }

        if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='bibliothécaire' ) {?>

            <div class="choixg">
                <div class="optiong">
                <a href="stocklivre.php">
                <div class="descript_optiong">Bibliothèque</div></a>
            </div><?php
        }
      
        if (isset($_POST['semestre']) and ($_SESSION['groupe']!='choix du groupe')) {?>

            <div class="optiong">
                <a href="bulletin.php?printnote" target="_blank">
                <div class="descript_optiong">Imprimer bulletin<img style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></div></a>
            </div>

            <div class="optiong">
                <a href="admis.php?listad" target="_blank">
                <div class="descript_optiong">Classement général<img style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></div></a>
            </div><?php
        }

        if (isset($_POST['eleve']) and ($_SESSION['groupe']!='choix du groupe' and $_SESSION['semestre']!='choix du semestre')) {?>

            <div class="optiong">
                <a href="bulletineleve.php?bulele=<?=$_SESSION['fiche'];?>" target="_blank">
                <div class="descript_optiong">Relevé des notes<img style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></div></a>
            </div><?php
        }

        if (isset($_POST['semestre']) and ($_SESSION['groupe']!='choix du groupe')) {?>

            <div class="optiong">
                <a href="releve_note.php?bulele" target="_blank">
                <div class="descript_optiong">Relevé général<img style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></div></a>
            </div><?php
        }?>               
           
    </div>
</fieldset>
</div>