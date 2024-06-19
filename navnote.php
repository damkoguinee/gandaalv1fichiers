<div class="col-sm-12 col-md-2" style="background-color: #253553;"><?php 

    if (($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true" OR $panier->searchRole("ROLE_PERSONNEL")=="true") AND $panier->users($_SESSION['matricule'])['niveau']>1 ) {?>

        <!-- <div class="choixg">
            <div class="optiong">
            <a href="eventlist.php?horaire">
            <div class="descript_optiong">Planing</div></a>
        </div> -->

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="horairegen.php?horaire">Horaires</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="horaireenvoye.php?horaire">Heures Transmises</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="ajout_devoir.php?devoir">Dévoirs</a></div></div>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="note.php?note">Notes</a></div></div>  

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="bulletin.php?note&annuel">Bulletins</a></div></div> 

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="appreciation.php?appreciation">Appréciations</a></div></div> 

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="planingj.php?appel">Faire Appel</a></div></div> 
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="presence_liste_classe.php">Controle de Présence</a></div></div>  

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="absence.php?appel">Absences</a></div></div> <?php 
    }

    if (($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" OR $panier->searchRole("ROLE_RESPONSABLE")=="true" OR $panier->searchRole("ROLE_PERSONNEL")=="true")) {?>
        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="planing.php">Emploi du temps</a></div></div><?php 
    }

    if ($panier->searchRole("ROLE_DEV")=="true" OR $panier->searchRole("ROLE_ADMIN")=="true" or $products['type']=='bibliothecaire' ) {?>

        <div class="row mt-3"><div class=" col text-center"><a style="width: 100%; " class="btn btn-light text-center fw-bold" href="stocklivre.php">Bibliothèque</a></div></div><?php
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