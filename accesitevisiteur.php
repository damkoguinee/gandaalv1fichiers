<?php

if (isset($_GET['enseignant'])) {
	require 'headerenseignant.php';
}else{
	require 'headerv3.php';
}
require_once "phpqrcode/qrlib.php";
require_once "phpqrcode/qrconfig.php";

if (isset($_SESSION['pseudo'])) {
	if (isset($_GET['enseignant'])) {
		require 'fiche_eleve.php';
	}else{
    
	    if (!empty($_SESSION['matricule'])) {
            $bdd='accesitevisiteur'; 
            $DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nom` VARCHAR(50) NULL,
            `sexe` VARCHAR(20) NULL,
            `phone` VARCHAR(20) NULL,
            `motif` VARCHAR(20) NULL,
            `services` VARCHAR(50) NULL,
            `etat` VARCHAR(20) NULL DEFAULT 'non traite',
            `date_acces` DATETIME,
            `idpers` int(4) NULL,
            `datesaisie` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `promo` VARCHAR(50) DEFAULT '2024',
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");?>

	    	<div class="container-fluid">

	    		<div class="row"><?php 
	    			require 'navaccesite.php';?>
					<div class="col-sm-12 col-md-10"><?php 
                        if (isset($_POST['valid'])) {
                            $nom=$panier->h($_POST['nom']);
                            $sexe=$panier->h($_POST['sexe']);
                            $motif=$panier->h($_POST['motif']);
                            $service=$panier->h($_POST['service']);
                            $phone=$panier->h($_POST['phone']);

                            $DB->insert("INSERT INTO accesitevisiteur(nom, sexe, motif, services, phone, date_acces)VALUES(?,?,?,?,?,now())", array($nom, $sexe,$motif,$service,$phone));
                            // $destinataire=$email;
                            // $message="bonjour, votre fils/fille ".$panier->nomEleve($matricule)." est présent(e) à l'école ";
                            // ini_set( 'display_errors', 1);
                            // error_reporting( E_ALL );
                            // $from = "damkoguinee.com";
                            // $to =$destinataire;
                            // $subject = "notification de présence";
                            // $message = $message;
                            // $headers = "From:" . $from;
                            // mail($to,$subject,$message, $headers);
                        }?>
                        <div class="row my-2">
                            
                            <div class="row">
                                <form class="form my-1 " method="POST" role="search">
                                    <div class="row mb-1">
                                        <div class="col-sm-12 col-md-4">
                                            <label for="phone" class="form-label">Téléphone<sup>*</sup></label>
                                            <input type="text" name="phone" required class="form-control">
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <label for="nom" class="form-label">Nom du Visiteur<sup>*</sup></label>
                                            <input type="text" name="nom" required placeholder="entrer le nom du visisteur" class="form-control">
                                        </div>

                                        <div class="col-sm-12 col-md-4">
                                            <label for="sexe" class="form-label">Sexe<sup>*</sup></label>
                                            <select name="sexe" id="" class="form-select" required>
                                                <option value=""></option>
                                                <option value="monsieur">Monsieur</option>
                                                <option value="madame">Madame</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="motif" class="form-label">Motif<sup>*</sup></label>
                                            <select name="motif" id="" class="form-select" required>
                                                <option value=""></option>
                                                <option value="eleve">Parent d'élève</option>
                                                <option value="societe">Société</option>
                                                <option value="autres">Autres</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="service" class="form-label">Service<sup>*</sup></label>
                                            <select name="service" id="" class="form-select">
                                                <option value=""></option>
                                                <option value="administrateur">Administration</option>
                                                <option value="comptabilite">Comptabilite</option>
                                                <option value="maternelle">Maternelle</option>
                                                <option value="primaire">Primaire</option>
                                                <option value="college">Collège</option>
                                                <option value="lycee">Lycée</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" name="valid" type="submit">Valider</button>
                                </form>
                            </div>
                            <div class="row" style="overflow: auto;"><?php 
                                if (isset($_GET['update'])) {
                                    $etat="traite";
                                    $DB->insert("UPDATE accesitevisiteur SET etat='{$etat}', idpers='{$_SESSION['idpseudo']}' where id='{$_GET['update']}'");?>
                                    <div class="alert alert-success">Opération éffectuée avec succèe!!!</div><?php
                                }?>
                                <table class="table table-bordered table-hover table-striped table-hover align-middle">
                                    <thead class="sticky-top bg-light text-center">
                                        <tr>
                                            <th colspan="9">Liste des Visiteurs <?=date("d/m/Y H:i");?></th>
                                        </tr>
                                        <tr>
                                            <th>N°</th>
                                            <th>Téléphone</th>
                                            <th>Nom du visiteur</th>
                                            <th>Sexe</th>
                                            <th>Motif</th>
                                            <th>Services</th>
                                            <th>Heure d'entrée</th>
                                            <th>Traité par</th>
                                            <th></th>
                                        </tr>

                                    </thead>
                                    <tbody><?php
                                        $date_acces=date("Ymd");
                                        //$panier->findEleveAbsent($date_acces, "matin");
                                        foreach ($panier->findVisiteurPresent($date_acces) as $key => $value) {
                                            if ($value->etat=="traite") {
                                                $bg="success";
                                            }else{
                                                $bg="warning";
                                            }                                        
                                            $nomVisiteur=$value->nom;?>
                                            <tr>
                                                <td class="text-center bg-<?=$bg;?>"><?=$key+1;?></td>
                                                <td class="text-center bg-<?=$bg;?>"><?=$value->phone?></td>
                                                <td class=" bg-<?=$bg;?>"><?=$nomVisiteur;?></td>
                                                <td class="text-center bg-<?=$bg;?>"><?=$value->sexe;?></td>
                                                <td class=" bg-<?=$bg;?>"><?=$value->motif;?></td>
                                                <td class=" bg-<?=$bg;?>"><?=$value->services;?></td>
                                                <td class="text-center bg-<?=$bg;?>"><?=(new dateTime($value->date_acces))->format("H:i");?></td>
                                                <td class=" bg-<?=$bg;?>"><?=$panier->nomPersonnel($value->idpers);?></td>
                                                <td><a href="?update=<?=$value->id;?>" class="btn btn-success" onclick="return alerteV()" >Traiter</a></td>
                                            </tr><?php 
                                        }?>
                                    </tbody>

                                </table>
                            </div>
					    </div>
				    </div>
			    </div>
            </div> <?php
		}
	}
}?>

<?php require 'footer.php';?>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Valider la suppression'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation'));
    }

    function focus(){
        document.getElementById('cursor').focus();
    }

</script>



<script>
    $(document).ready(function(){
        $('#search-user').keyup(function(){
            $('#result-search').html("");

            var utilisateur = $(this).val();

            if (utilisateur!='') {
                $.ajax({
                    type: 'GET',
                    url: 'searcheleve.php?elevesearch',
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
  </script>
