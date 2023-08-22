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
            $bdd='accesite'; 
            $DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `mat_acces` VARCHAR(50) NULL,
            `journee` VARCHAR(50) NULL,
            `date_acces` DATETIME,
            `datesaisie` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `promo` VARCHAR(50) DEFAULT '2024',
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");?>

	    	<div class="container-fluid">

	    		<div class="row"><?php 
	    			require 'navaccesite.php';?>
					<div class="col-sm-12 col-md-10"><?php
                        $date_acces=date("Ymd");
                        if (isset($_GET['confirmerMatin'])) {
                            $absencesMatin=$panier->findEleveAbsent($date_acces,"matin");
                            //var_dump($absencesMatin);
                            foreach ($absencesMatin as $key => $value) {
                                $phone=$panier->findEleveByMat($value->matricule)['phone'];
                                $email=$panier->findEleveByMat($value->matricule)['email'];
                                
                            }
                        } 
                        if (isset($_GET['search'])) {
                            $matricule=$panier->h($_GET['search']);
                            $eleve=$panier->findEleveByMat($matricule);
                            if (!empty($eleve['matricule'])) {
                                $matricule=$eleve['matricule'];;
                                $email=$eleve['email'];
                                $phone=$eleve['phone'];

                                $time=date("H");
                                
                                if ($time<13) {
                                    $journee="matin";
                                }else{
                                    $journee="soir";
                                }
                                $verif=$DB->querys("SELECT *FROM accesite where mat_acces='{$matricule}' and date_format(date_acces,\"%Y%m%d \")='{$date_acces}' and journee='{$journee}' and promo='{$_SESSION['promo']}'");
                                if (empty($verif['id'])) {
                                    $DB->insert("INSERT INTO accesite(mat_acces, journee, date_acces)VALUES(?,?,now())", array($matricule,$journee));

                                    if ($panier->isInternetConnected()) { // pour savoir si connexion à internet                                        
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
                                    }
                                      
                                }                              
                            }

                            $data=$DB->querys("SELECT *FROM accesite inner join inscription on mat_acces=inscription.matricule inner join eleve on eleve.matricule=mat_acces where mat_acces='{$matricule}' and  promo='{$_SESSION['promo']}' and  annee='{$_SESSION['promo']}' ");
                        }?>
                        <div class="row my-2">
                            
                            <div class="col-sm-12 col-md-4 my-4">
                                <form method="GET" class="d-flex my-4" role="search">
                                    <input id="cursor" class="form-control me-2" type="search" name="search" placeholder="Recherchez ou scanner" aria-label="Search" onchange="this.form.submit()" >
                                    <button class="btn btn-outline-success" type="submit">Search</button>
                                </form>
                            </div>
                            <div class="col-sm-12 col-md-6"><?php 
                                if (!empty($data['id'])) {
                                    $mat=$data['matricule'];
                                    $codeContent=$mat;
                                    $fileName=$mat.".png";
                                    $cheminQrcode='qrcode/'.$fileName;
                                    if (!file_exists($cheminQrcode)) {
                                        QRcode::png($codeContent, $cheminQrcode);
                                    }
                                    $filename1="img/".$mat.'.jpg';

                                    if (file_exists($filename1)) {
                                        $image="img/".$mat.".jpg";
                                    }else{
                                        $image="img/defaut.jpg";
                                    }?>
                                    <div class="card m-auto bg-light my-2" style="width: 100%;">
                                        <div style="width: 9rem; margin:auto;">
                                            <img src="<?=$image;?>" class="card-img-top" alt="photo-enseignant">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title text-center">Elève</h5>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-8">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-5 fw-bold">Matricule </div><div class="col-sm-12 col-md-7"><?=strtoupper($mat);?></div>
                                                    </div>
                                                    <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Nom/Prénom</div><div class="col-sm-12 col-md-7"><?=strtoupper($data['nomel']);?> <?=ucfirst(strtolower(($data['prenomel'])));?></div></div>
                                                    <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Né(e) le</div><div class="col-sm-12 col-md-7"><?=strtoupper($data['naissance']);?></div></div>
                                                    <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Téléphone</div><div class="col-sm-12 col-md-7"><?=strtoupper($data['telpere']);?></div></div>
                                                    <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Classe</div><div class="col-sm-12 col-md-7"><?=strtoupper($data['nomgr']);?></div></div>
                                                    <div class="alert alert-success fw-bold fs-4 text-center">Présent</div><?php 
                                                    require_once "etatmensualite.php";?>

                                                </div>
                                                <div class="col-sm-12 col-md-4">
                                                    <img src="<?=$cheminQrcode;?>" class="card-img-top" alt="photo-enseignant">
                                                </div>
                                            </div>

                                        </div>
                                    </div><?php

                                }?>
                            </div>

                        </div>

                        <div class="row"><?php 
                            if (isset($_GET['delete'])) {
                                $DB->delete("DELETE FROM accesite where id='{$_GET['delete']}'");?>
                                <div class="alert alert-success">Opération éffectuée avec succèe!!!</div><?php
                            }?>
                            <div class="col sm-12 col-md-6">
                                <table class="table table-bordered table-hover table-striped table-hover align-middle">
                                    <thead class="sticky-top bg-light text-center">
                                        <tr>
                                            <th colspan="6">Liste des Présents dans la matinée <?=date("d/m/Y H:i");?> <a href="?confirmerMatin" class="btn btn-warning" onclick="return alerteAbsence()">Confirmer</a></th>
                                        </tr>
                                        <tr>
                                            <th>N°</th>
                                            <th>Matricule</th>
                                            <th>Prénom & Nom</th>
                                            <th>Classe</th>
                                            <th>Heure d'entrée</th>
                                            <th></th>
                                        </tr>

                                    </thead>
                                    <tbody><?php
                                        $date_acces=date("Ymd");
                                        //$panier->findEleveAbsent($date_acces, "matin");
                                        foreach ($panier->findElevePresent($date_acces, "matin") as $key => $value) {?>
                                            <tr>
                                                <td class="text-center"><?=$key+1;?></td>
                                                <td class="text-center"><?=$value->matricule;?></td>
                                                <td><?=$panier->nomEleve($value->matricule);?></td>
                                                <td class="text-center"><?=$value->nomgr;?></td>
                                                <td class="text-center"><?=(new dateTime($value->date_acces))->format("H:i");?></td>
                                                <td><a href="?delete=<?=$value->id;?>" class="btn btn-danger" onclick="return alerteV()" >Annuler</a></td>
                                            </tr><?php 
                                        }?>
                                    </tbody>

                                </table>
                            </div>

                            <div class="col sm-12 col-md-6">
                                <table class="table table-bordered table-hover table-striped table-hover align-middle">
                                    <thead class="sticky-top bg-light text-center">
                                        <tr>
                                            <th colspan="6">Liste des Présents dans l'après-midi <?=date("d/m/Y H:i");?><a href="?confirmerSoir" class="btn btn-warning" onclick="return alerteAbsence()">Confirmer</a></th>
                                        </tr>
                                        <tr>
                                            <th>N°</th>
                                            <th>Matricule</th>
                                            <th>Prénom & Nom</th>
                                            <th>Classe</th>
                                            <th>Heure d'entrée</th>
                                            <th></th>
                                        </tr>

                                    </thead>
                                    <tbody><?php
                                        $date_acces=date("Ymd");
                                        //$panier->findEleveAbsent($date_acces, "matin");
                                        foreach ($panier->findElevePresent($date_acces, "soir") as $key => $value) {?>
                                            <tr>
                                                <td class="text-center"><?=$key+1;?></td>
                                                <td class="text-center"><?=$value->matricule;?></td>
                                                <td><?=$panier->nomEleve($value->matricule);?></td>
                                                <td class="text-center"><?=$value->nomgr;?></td>
                                                <td class="text-center"><?=(new dateTime($value->date_acces))->format("H:i");?></td>
                                                <td><a href="?delete=<?=$value->id;?>" class="btn btn-danger" onclick="return alerteV()" >Annuler</a></td>
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
    function alerteAbsence(){
        return(confirm("Attention, vous êtes sur le point de confirmer les présences du jour. Une notification d'absence sera transmise aux parents d'élèves et l'absence sera comptabilisé sur les bulletins de l'élèves"));
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
