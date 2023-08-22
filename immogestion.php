<?php

if (isset($_GET['enseignant'])) {
	require 'headerenseignant.php';
}else{
	require 'headerv3.php';
}
require_once "phpqrcode/qrlib.php";
require_once "phpqrcode/qrconfig.php";

if (isset($_SESSION['pseudo'])) {
    
    if (!empty($_SESSION['matricule'])) {
        $bdd='commune'; 
        $DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nom` VARCHAR(50) NOT NULL DEFAULT 'kaloum',
        `codepostal` VARCHAR(50) NULL,
        `datesaisie` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");
        
        $bdd='batiment'; 
        $DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
        `id_bat` int(11) NOT NULL AUTO_INCREMENT,
        `identifiant` VARCHAR(50) NOT NULL,
        `nom` VARCHAR(50) NOT NULL,
        `batiment_type` VARCHAR(50) NOT NULL,
        `nombre_etage` int NOT NULL,
        `nombre_pieces` int NOT NULL,
        `nom_proprietaire` VARCHAR(50) NOT NULL,
        `adresse` VARCHAR(150) NOT NULL,
        `commune` VARCHAR(150) NOT NULL,
        `pays` VARCHAR(150) NOT NULL,
        `phone` VARCHAR(15) NULL,
        `email` VARCHAR(50) NULL,
        `longitude` VARCHAR(50) NULL,
        `latitude` VARCHAR(50) NULL,
        `batim_description` VARCHAR(150) NULL,
        `datesaisie` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id_bat`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");?>

        <div class="container-fluid">

            <div class="row"><?php
                require 'navimmo.php';?>
                <div class="col-sm-12 col-md-10"><?php 
                    if (isset($_POST['valid'])) {
                        if (!empty($_POST['nom']) and !empty($_POST['typebat'])) {

                            $nb=$immobillier->batimentMaxId();
                            $initial='bat';
                            $anneeins=date('y');
                            $identifiant=$anneeins . '0000'+($nb['id']+1);
                            $identifiant=$initial.$identifiant;			 		
                            
                            $nom=strtolower($panier->h($_POST['nom']));
                            $batiment_type=strtolower($panier->h($_POST['typebat']));
                            $nombre_etage=$panier->h($_POST['nbretage']);
                            $nombre_pieces=$panier->h($_POST['nbreapart']);
                            $nom_proprietaire=$panier->h($_POST['proprietaire']);
                            $adresse=$panier->h($_POST['adresse']);
                            $commune=$panier->h($_POST['commune']);
                            $pays=$panier->h($_POST['pays']);
                            $phone=$panier->h($_POST['phone']);
                            $email=$panier->h($_POST['email']);
                            $longitude=$panier->h($_POST['long']);
                            $latitude=$panier->h($_POST['lat']);
                            $batiment_description=$panier->h($_POST['description']);
   
                            // $logo=$_FILES['photo']['name'];

                            // if($logo!=""){

                            //     require "upImageResidence.php";
                            
                            // }
                            $immobillier->batimentInsert($identifiant, $nom, $batiment_type, $nombre_etage, $nombre_pieces, $nom_proprietaire, $adresse, $commune, $pays, $phone, $email, $longitude, $latitude, $batiment_description);?>
                            <div class="alert alert-success" role="alert">Batiment ajouté avec succèe!!!</div><?php                             
                        }
                        
                    }
                    
                    if (isset($_GET['ajout'])) {?>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <fieldset><legend>Completez pour ajouter un Batiment</legend>

                                <div class="row">

                                    <div class="col-sm-12 col-md-6">							    

                                        <div class="mb-1">
                                            <label class="form-label">Nom du Batiment*</label>
                                            <input type="text"  class="form-control" name="nom" required="">
                                        </div>

                                        <div class="mb-1">
                                            <label class="form-label">Type de Batiment*</label>
                                            <select class="form-select" name="typebat" required="">*
                                                <option></option>
                                                <option value="etage">Etage</option>
                                                <option value="duplex">Duplex</option>
                                            </select>
                                        </div>	

                                        <div class="mb-1">
                                            <label class="form-label">Nombre d'etage*</label>
                                            <select class="form-select" name="nbretage" required="">
                                                <option></option><?php
                                                $i=0;
                                                while ($i<= 11) {?>
                                                    <option value="<?=$i;?>"><?=$i;?></option><?php
                                                    $i++;
                                                }?>
                                                
                                            </select>
                                        </div>

                                        <div class="mb-1">
                                            <label class="form-label">Nombre de Pièces*</label>
                                            <select class="form-select" name="nbreapart" required="">
                                                <option></option><?php
                                                $i=0;
                                                while ($i<= 101) {?>
                                                    <option value="<?=$i;?>"><?=$i;?></option><?php
                                                    $i++;
                                                }?>
                                                
                                            </select>
                                        </div>
                                        <input type="hidden"  class="form-control" name="proprietaire">
                                    
                                        <input type="hidden"  class="form-control" name="phone">
                                    
                                        <input type="hidden"  class="form-control" name="email">

                                        <div class="mb-1">
                                            <label class="form-label">Adresse*</label>
                                            <input type="text"  class="form-control" name="adresse" required="">
                                        </div>

                                        <div class="mb-1">
                                            <label class="form-label">Commune*</label>
                                            <select class="form-select" name="commune" required="">
                                                <option></option><?php 
                                                foreach ($immobillier->communeList() as $key => $value) {?>

                                                    <option value="<?=$value->nom;?>"><?=$value->nom;?></option><?php
                                                                                        
                                                }?>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-sm-12 col-md-6">
                                        <div class="mb-1">
                                            <label class="form-label">Pays*</label>
                                            <input class="form-control" type="text" value="guinee" name="pays" required="">
                                        </div>

                                        <div class="mb-1">
                                        <label class="form-label">Longitude</label>
                                        <input type="text"  class="form-control" name="long">
                                        </div>

                                        <div class="mb-1">
                                        <label class="form-label">Latitude</label>
                                        <input type="text"  class="form-control" name="lat">
                                        </div>

                                        <div class="mb-1">
                                        <label class="form-label">Description</label>
                                        <textarea type="text"  class="form-control" name="description" maxlength="150"></textarea>
                                        </div>

                                        <!-- <div class="mb-1">
                                        <label for="formFile" class="form-label">Photo</label>
                                        <input class="form-control" name="photo" type="file" id="formFile">
                                        </div> -->
                                    </div>
                                </div>

                                <button type="submit" name="valid" class="btn btn-primary">Valider</button>
                            </fieldset>
                        </form><?php 
                    }else{?>
                        <table class="table table-hover table-bordered table-striped table-responsive">
                            <thead>

                                <tr>
                                    <th colspan="8" scope="col" class="text-center"><a class="btn btn-warning" href="?ajout">Ajouter une Bâtiment</a></th>
                                    
                                </tr>
                                <tr>
                                    <th colspan="8" scope="col" class="text-center bg-primary">Liste des Bâtiments</th>
                                    <!-- <th colspan="3" scope="col" class="text-center bg-primary">
                                        <input class="form-control me-2" id="search-user" type="search" placeholder="Search client" aria-label="Search" >
                                        <div style="color:white; background-color: grey; font-size: 16px;" id="result-search"></div>
                                    </th> -->
                                </tr>
                                <tr>
                                <th scope="col" class="text-center">N°</th>
                                <th scope="col" class="w-auto">Batiment</th>
                                <th scope="col">type</th>
                                <th scope="col">Nbre d'étage</th>
                                <th scope="col">Nbre de pièces</th>
                                <th scope="col">Adresse</th>
                                <th scope="col" class="text-center" colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody><?php
                                foreach ($immobillier->batimentSelectAll() as $key => $value) {?>
                                    <tr>
                                    <th scope="row" class="text-center"><?=$key+1;?></th>

                                    <td><?=strtoupper($value->nom);?></td>

                                    <td class="text-center"><?=$value->batiment_type;?></td>
                                    <td class="text-center"><?=$value->nombre_etage;?></td>
                                    <td class="text-center"><?=$value->nombre_pieces;?></td>
                                    <td><?=$value->adresse;?></td>
                                    <!-- <td><a class="btn btn-info" href="batimentInfos.php?infos=<?=$value->id_bat;?>">+Infos</a></td> -->
                                    <td><a class="btn btn-warning" href="?update=<?=$value->id_bat;?>" onclick="return alerteV();">Modifier</a></td>                                    
                                    <td><?php if ($_SESSION['type']=='admin') {?><a class="btn btn-danger" href="?delete=<?=$value->id_bat;?>" onclick="return alerteS();">Supprimer</a><?php }?></td>
                                    </tr><?php 
                                }?>
                                
                            </tbody>
					    </table><?php 
                    }?>
                </div>
            </div>
        </div> <?php
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
