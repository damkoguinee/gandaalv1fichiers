<?php

if (isset($_GET['enseignant'])) {
	require 'headerenseignant.php';
}else{
	require 'headerv3.php';
}

if (isset($_SESSION['pseudo'])) {
	if (isset($_GET['enseignant'])) {
		require 'fiche_eleve.php';
	}else{
    
	    if ($products['niveau']<4) {?>

	        <div class="alert alert-danger">Des autorisations sont requises pour consulter cette page</div><?php

	    }else{?>

	    	<div class="container-fluid">

	    		<div class="row"><?php 
	    			require 'navlistedeseleves.php';?>					

					<div class="col-sm-12 col-md-10"><?php

						if (isset($_GET['delete'])) {
							$idact=$_GET['idactvoir'];
							$moisp=$_GET['moisp'];
							$id=$_GET['delete'];
							$matp=$_GET['matp'];
							$anneep=$_GET['promo'];
							$montants=$_GET['montantp'];

							$DB->delete('DELETE FROM activitespaiehistorique WHERE id = ?', array($id));

							$prodpaie=$DB->querys("SELECT id, montantp FROM activitespaiement WHERE matp='{$matp}' and moisp='{$moisp}' and idact='{$idact}' and anneep='{$anneep}' ");

							$payer=$prodpaie['montantp']-$montants;
                            var_dump($prodpaie['montantp'],$montants);
							$etat='encours';

							$DB->insert("UPDATE activitespaiement SET montantp='{$payer}' where matp='{$matp}' and idact='{$idact}' and moisp='{$moisp}' and anneep='{$anneep}' ");

							$DB->delete('DELETE FROM banque WHERE numero= ?', array($_GET['numeropaie']));

							//$DB->insert('INSERT INTO historiquedelpaie(idapart, client, montant, moisp, exect, idresidence, dateop) VALUES(?, ?, ?, ?, ?, ?, now())', array($idapart, $client, $montants, $moisp, $_SESSION['idpseudo'], $idresidence));?>

							<div class="alert alert-success">Paiement supprimé avec succèe!!</div><?php 
						}
						$etat='actif';

                        if (isset($_GET['listeeleve'])) {
                            $_SESSION['listeeleve']=$_GET['listeeleve'];
                        }else{
                            $_SESSION['listeeleve']=1;
                        }

                        if (isset($_POST['moisp'])) {
                            
                            $prod=$DB->query("SELECT * FROM activitespaiement left join inscription on matricule=matp left join elevexterne on matex=matp where moisp='{$_POST['moisp']}' and idact='{$_SESSION['listeeleve']}' and anneep='{$_SESSION['promo']}' and annee='{$_SESSION['promo']}'  order by(idact) ");
                        }else{

                            $prod=$DB->query("SELECT * FROM activitespaiement left join inscription on matricule=matp left join elevexterne on matex=matp where idact='{$_SESSION['listeeleve']}' and anneep='{$_SESSION['promo']}' and annee='{$_SESSION['promo']}'  order by(idact) ");
                        }?>

                        <table class="table table-hover table-bordered table-striped table-responsive text-center">
                            <thead>
                                <tr><th colspan="9">Paiements des activités</th></tr>

                                <tr>
                                	<th colspan="9">
                                		<div class="container-fluid">
					        				<div class="row">

					        					<div class="col">
							        				<input class="form-control me-2" id="search-user" type="search" placeholder="Search élève" aria-label="Search" >
				                					<div style="color:white; background-color: grey; font-size: 16px;" id="result-search"></div>
							        				
							        			</div>
								        		<div class="col">
								        			<form method="POST" action="listedeseleves.php?listeeleve=<?=$_SESSION['listeeleve'];?>">
								        				<select class="form-select" name="moisp" onchange="this.form.submit()"><?php 
								        					if (isset($_POST['moisp'])) {?>

								        						<option value="<?=$_POST['moisp'];?>"><?=$_POST['moisp'];?></option><?php
								        					}else{?>
								        						<option>Filtrer par mois</option><?php
								        					}

								        					foreach ($panier->moisenlettre as $value) {?>

								        						<option value="<?=$value;?>"><?=$value;?></option><?php
								        					}?>

								        					<option value="general">Tout afficher</option>
								        				
								        				</select>
								        			</form>
								        				
								        		</div>							        			
							        		</div>
							        	</div>
							        </th>
                                </tr>

                                <tr>
                                  <th scope="col" class="text-center">N°</th>
                                  <th scope="col">Matricule</th>
                                  <th scope="col">Prénom & Nom</th>
                                  <th scope="col">Classe</th>                                   
                                  <th scope="col">Mois</th>
                                  <th scope="col">Désignation</th>
                                  <th scope="col">Montant Payé</th>
                                  <th scope="col">Date de Paie</th>
                                  <th>Action</th>
                                </tr>
                            </thead>

                            <tbody><?php 
                                $montantcumul=0;
                                foreach ($prod as $key => $value) {
                                	if (empty($value->nomgr)) {
                                		$classe='externe';
                                	}else{
                                		$classe=$value->nomgr;
                                	}

                                	$eleve=$panier->nomElevex($value->matp);
                                	if (empty($eleve)) {
                                		$eleve=$panier->nomEleve($value->matp);
                                	}

                                    $montantcumul+=$value->montantp;

                                    $dated=(new DateTime($value->dateop))->format("d/m/Y")?>

                                    <tr>
                                        <td><?=$key+1;?></td>
                                        <td><?=$value->matp;?></td>
                                        <td><?=$eleve;?></td>
                                        <td><?=$classe;?></td>
                                        <td><?=$value->moisp;?></td>                                        
                                        <td><?=$panier->nomActivites($value->idact)[0];?></td>
                                        <td><?=number_format($value->montantp,0,',',' ');?></td>
                                        <td><?=$dated;?></td><?php

                                		if ($products['type']=='admin' or $products['type']=='comptable' or $products['type']=='bibliothecaire') {?>
                                        	<td><a class="btn btn-danger" href="?delete=<?=$value->id;?>&matp=<?=$value->matp;?>&promo=<?=$_SESSION['promo'];?>&idactvoir=<?=$value->idact;?>&moisp=<?=$value->moisp;?>&montantp=<?=$value->montantp;?>&numeropaie=<?=$value->numeropaie;?>" onclick="return alerteV();">Annuler</a></td><?php 
                                        }?>
                                    </tr><?php 
                                }?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="6">Totaux</th>
                                    <th><?=number_format($montantcumul,0,',',' ');?></th>
                                </tr>
                            </tfoot>
                        </table>

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
        document.getElementById('pointeur').focus();
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
                    url: 'searchelevegen.php?elevesearch',
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
