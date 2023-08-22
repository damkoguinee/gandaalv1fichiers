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
            $bdd='fraisinscription'; 
            $DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cursus` VARCHAR(50) NULL,
            `nature` VARCHAR(50) NULL,
            `montant` double NULL,
            `datesaisie` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `promo_ins` VARCHAR(10) DEFAULT '2024',
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");?>

	    	<div class="container-fluid">

	    		<div class="row"><?php 
	    			require 'navformation.php';?>
					<div class="col-sm-12 col-md-10"><?php 
                        if (isset($_POST['valid'])) {
                            $tabcursus=$_POST['cursus'];
                            $nature=$panier->h($_POST['nature']);
                            $montant=$panier->h($_POST['montant']);
                            $promo=$panier->h($_POST['promo']);

                            foreach ($tabcursus as $cursus) {
								$nb=$DB->querys("SELECT *from fraisinscription where cursus='{$cursus}' and promo_ins='{$promo}' and nature='{$nature}' ");

								if(!empty($nb['id'])){?>
									<div class="alert alert-warning">Les frais sont déjà saisies pour cette promotion</div><?php
								}else{
                                    $DB->insert("INSERT INTO fraisinscription(nature,cursus,montant,promo_ins)VALUES(?,?,?,?)", array($nature,$cursus, $montant,$promo));?>
                                    <div class="alert alert-success">Opération éffectuée avec succèe!!!</div><?php
								}
							}
                            
                        }?>
                        <div class="row my-2"><?php 
                            if (isset($_GET['ajout'])) {?>
                            
                                <div class="row">
                                    <form class="form my-1 " method="POST" role="search">
                                        <div class="mb-1">
                                            <label for="nature" class="form-label">Nature<sup>*</sup></label>
                                            <select name="nature" id="" class="form-select" required>
                                                <option value=""></option>
                                                <option value="inscription">Frais d'inscription</option>
                                                <option value="reinscription">Frais de réinscription</option>                                            
                                            </select>
                                        </div>
                                        <div class="mb-1">
                                            <label for="montant" class="form-label">Montant<sup>*</sup></label>
                                            <input type="text" name="montant" required class="form-control">
                                        </div>
                                        <div class="mb-1">
                                            <label for="cursus" class="form-label">Cursus<sup>*</sup></label>
                                            <select name="cursus[]"multiple id="" class="form-select" required>
                                                <option value=""></option><?php 
                                                foreach ($panier->cursus() as $key => $value) {?>
                                                    <option value="<?=$value->id;?>"><?=$value->nom;?></option><?php
                                                }?>
                                            </select>
                                        </div>
                                        <div class="mb-1">
                                            <label for="motif" class="form-label">Année-Scolaire<sup>*</sup></label>
                                            <select class="form-select" type="text" name="promo" required=""><?php                                    
                                                $annee=date("Y")+1;

                                                for($i=($_SESSION['promo']-1);$i<=$annee ;$i++){
                                                    $j=$i+1;?>

                                                    <option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

                                                }?>
                                            </select>                                        
                                        </div>
                                        <button class="btn btn-primary" name="valid" type="submit">Valider</button>
                                    </form>
                                </div><?php 
                            }?>
                            <div class="row" style="overflow: auto;"><?php 
                                if (isset($_GET['delete'])) {
                                    $DB->delete("DELETE FROM fraisinscription where id='{$_GET['delete']}'");?>
                                    <div class="alert alert-success">Opération éffectuée avec succèe!!!</div><?php
                                }?>
                                <table class="table table-bordered table-hover table-striped table-hover align-middle">
                                    <thead class="sticky-top bg-light text-center">
                                        <tr>
                                            <th colspan="5">Les frais d'inscriptions/réinscription de cette année <a href="?ajout" class="btn btn-warning">Enregistrer les frais</a></th>
                                        </tr>
                                        <tr>
                                            <th>N°</th>
                                            <th>Niveau</th>
                                            <th>Montant</th>
                                            <th>Nature</th>
                                            <th></th>
                                        </tr>

                                    </thead>
                                    <tbody><?php
                                        $date_acces=date("Ymd");
                                        //$panier->findEleveAbsent($date_acces, "matin");
                                        foreach ($panier->fraisInscription($_SESSION['promo']) as $key => $value) {
                                            ?>
                                            <tr>
                                                <td class="text-center" ><?=$key+1;?></td>
                                                <td class="text-center" ><?=$panier->nomCursus($value->cursus)['nom'];?></td>
                                                <td class="text-end"><?=number_format($value->montant,0,',',' ');?></td>
                                                <td class="text-center"><?=$value->nature;?></td>
                                                <td><a href="?delete=<?=$value->id;?>" class="btn btn-danger" onclick="return alerteS()" >Annuler</a></td>
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
