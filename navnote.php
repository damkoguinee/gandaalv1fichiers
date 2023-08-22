<div class="col-sm-12 col-md-2" style="background-color: #253553;">

    <fieldset style="margin-top: 30px;"><legend></legend><?php 

        if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='informaticien' or $products['type']=='Directeur Général' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='surveillant Général' or $products['type']=='coordonateur bloc B' or $products['type']=='Directeur du primaire' or $products['type']=='bibliothecaire') {?>

            <!-- <div class="choixg">
                <div class="optiong">
                <a href="eventlist.php?horaire">
                <div class="descript_optiong">Planing</div></a>
            </div> -->

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

        if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='informaticien' or $products['type']=='Directeur Général' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='enseignant' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=="Conseille a l'éducation" or $products['type']=='coordonateur bloc B' or $products['type']=='Directeur du primaire'  or $products['type']=='secrétaire' or $products['type']=='bibliothecaire') {?>

            <div class="choixg">
                <div class="optiong">
                <a href="ajout_devoir.php?devoir">
                <div class="descript_optiong">Devoirs</div></a>
            </div>

            <div class="choixg">
                <div class="optiong">
                <a href="note.php?note">
                <div class="descript_optiong">Notes</div></a>
            </div>      

            <div class="optiong">
                <a href="bulletin.php?note">
                <div class="descript_optiong">Bulletin</div></a>
            </div>

            <div class="optiong">
                <a href="appreciation.php?appreciation">
                <div class="descript_optiong">Appréciations</div></a>
            </div>

            <div class="choixg">
                <div class="optiong">
                <a href="planingj.php?appel">
                <div class="descript_optiong">Faire Appel</div></a>
            </div>

            <div class="choixg">
                <div class="optiong">
                <a href="absence.php?appel">
                <div class="descript_optiong">Absences</div></a>
            </div><?php 
        }

        if ($products['type']=='admin' or $products['type']=='fondateur' or $products['type']=='Administrateur Général' or $products['type']=='informaticien' or $products['type']=='Directeur Général' or $products['type']=='proviseur' or $products['type']=='DE/Censeur' or $products['type']=='Directeur du primaire' or $products['type']=='coordinatrice maternelle' or $products['type']=='coordonateur bloc B' or $products['type']=='Directeur du primaire'  or $products['type']=='secrétaire' or $products['type']=='bibliothecaire') {?>

            <div class="choixg">
                <div class="optiong">
                <a href="planing.php">
                <div class="descript_optiong">Emploi du temps</div></a>
            </div><?php 
        }

        if ($products['type']=='admin' or $products['type']=='informaticien' or $products['type']=='bibliothecaire' ) {?>

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