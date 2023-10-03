<?php
require 'headerv3.php';

if ($products['niveau']<4) {?>

    <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

}else{?>

	<div class="container-fluid">
		<div class="row"><?php

			require 'navabsence.php';?>

		<div class="col-sm-12 col-md-10"><?php

			if (!isset($_POST['j1'])) {

			$_SESSION['date']=date("Y0101");  
			$dates = $_SESSION['date'];
			$dates = new DateTime( $dates );
			$dates = $dates->format('Y0101'); 
			$_SESSION['date']=$dates;
			$_SESSION['date1']=$dates;
			$_SESSION['date2']=date('Y1231'); ;
			$_SESSION['dates1']=$dates; 

			$_SESSION['classe']='7A/A';

			}else{

			$_SESSION['date01']=$_POST['j1'];
			$_SESSION['date1'] = new DateTime($_SESSION['date01']);
			$_SESSION['date1'] = $_SESSION['date1']->format('Ymd');
			
			$_SESSION['date02']=$_POST['j2'];
			$_SESSION['date2'] = new DateTime($_SESSION['date02']);
			$_SESSION['date2'] = $_SESSION['date2']->format('Ymd');

			$_SESSION['classe']=$_POST['classe'];

			$_SESSION['dates1']=(new DateTime($_SESSION['date01']))->format('d/m/Y');
			$_SESSION['dates2']=(new DateTime($_SESSION['date02']))->format('d/m/Y');  
			}


			if (isset($_POST['j2'])) {

			$datenormale='entre le '.$_SESSION['dates1'].' et le '.$_SESSION['dates2'];

			}else{

			$datenormale=(new DateTime($dates))->format('Y');
			}

			if (isset($_GET['nomel']) or isset($_POST['justabs']) or isset($_GET['exmatri'])) {
				$_SESSION['annee']=' ';
				$dateselect=' ';
			}

			if (isset($_GET['listeex']) or isset($_GET['supidabs'])) {
				$_SESSION['annee']=' ';
				$dateselect=' ce jour ';
			}

			if (isset($_POST['annee'])) {
				$_SESSION['annee']=$_POST['annee'];
				$_SESSION['mensuelle']="Selectionnez le mois !!";
				$_SESSION['datesm']=$_POST['annee'];

				$dateselect=$_POST['annee'];
			}

			if (isset($_POST['mensuelle'])) {
				$_SESSION['mensuelle']=$_POST['mensuelle'];
				$dateselect=$_POST['mensuelle'];
			}

			if (isset($_POST['jour'])) {
				$_SESSION['jour']=$_POST['jour'];

				$datefin = new DateTime( $_POST['jour'] );
				$dateselect = $datefin->format('d/m/Y');
			}

			if (isset($_POST['collabo'])) {
				$_SESSION['nomcollabo']=$_POST['collabo'];
			}

			if (isset($_POST['location'])) {
				$_SESSION['nomloca']=$_POST['location'];
			}

			if (isset($_GET['supidabs'])) {

				$DB->delete('DELETE FROM exclus WHERE id = ?', array($_GET['supidabs']));?>

				<div class="alert alert-success">Retard supprimé avec succèe</div><?php
			}

			if (isset($_POST['j1'])) {

				
				$prodeleve=$DB->query('SELECT exclus.id as id, nomel, prenomel, eleve.adresse as adresse, eleve.sexe as sexe, pere, mere, eleve.naissance AS naissance, eleve.matricule as matricule, phone, nommat, nomen, prenomen, motif, hdebut, date_format(dateexclus,\'%d/%m/%Y \') as dateabs, nomgr as classe, nomf from eleve inner join exclus on eleve.matricule=exclus.matricule inner join matiere on matiere.codem=exclus.codem inner join enseignant on enseignant.matricule=exclus.codens inner join contact on eleve.matricule=contact.matricule inner join formation on formation.codef=matiere.codef where exclus.nomgr=:classe and promo=:promo and DATE_FORMAT(dateexclus, \'%Y%m%d\') >= :date1 and DATE_FORMAT(dateexclus, \'%Y%m%d\') <= :date2  order by (prenomel)', array('classe'=>$_SESSION['classe'], 'promo'=>$_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));

			}elseif (isset($_GET['exmatri'])) {

				$prodeleve=$DB->query('SELECT exclus.id as id, nomel, prenomel, eleve.adresse as adresse, eleve.sexe as sexe, pere, mere, eleve.naissance AS naissance, eleve.matricule as matricule, phone, nommat, nomen, prenomen, motif, hdebut, date_format(dateexclus,\'%d/%m/%Y \') as dateabs, nomgr as classe, nomf from eleve inner join exclus on eleve.matricule=exclus.matricule inner join matiere on matiere.codem=exclus.codem inner join enseignant on enseignant.matricule=exclus.codens inner join contact on eleve.matricule=contact.matricule inner join formation on formation.codef=matiere.codef where promo=:promo and exclus.matricule=:matr and semestre=:sem', array('promo'=>$_GET['promo'], 'matr' => $_GET['exmatri'], 'sem'=>$_GET['sem']));

			}else{

				$prodeleve=$DB->query('SELECT exclus.id as id, nomel, prenomel, eleve.adresse as adresse, eleve.sexe as sexe, pere, mere, eleve.naissance AS naissance, eleve.matricule as matricule, phone, nommat, nomen, prenomen, motif, hdebut, date_format(dateexclus,\'%d/%m/%Y \') as dateabs, nomgr as classe, nomf from eleve inner join exclus on eleve.matricule=exclus.matricule inner join matiere on matiere.codem=exclus.codem inner join enseignant on enseignant.matricule=exclus.codens inner join contact on eleve.matricule=contact.matricule inner join formation on formation.codef=matiere.codef where promo=:promo and DATE_FORMAT(dateexclus, \'%Y%m%d\') >= :date1 and DATE_FORMAT(dateexclus, \'%Y%m%d\') <= :date2  order by (prenomel)', array('promo'=>$_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));
			} ?>

			<table class="table table-bordered table-striped table-hover align-middle my-2">
				<thead class="sticky-top bg-light text-center">

					<tr>
						<th colspan="10">
							<form class="form" method="POST" name="termc">
								<div class="d-flex justify-content-between"><?php

									if (isset($_POST['j1'])) {?>

										<input class="form-control" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"><?php

									}else{?>

										<input class="form-control" type = "date" name = "j1" onchange="this.form.submit()"><?php

									}

									if (isset($_POST['j2'])) {?>

										<input class="form-control" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"><?php

									}else{?>

										<input class="form-control" type = "date" name = "j2" onchange="this.form.submit()"><?php

									}?>

	                    
									<select class="form-select" name="classe" onchange="this.form.submit()"><?php

										if (isset($_POST['j1'])) {?>
											<option value="<?=$_SESSION['classe'];?>"><?=strtoupper($_SESSION['classe']);?></option><?php 
										}else{?>
											<option>selectionnez une classe</option><?php 
										}

										foreach ($panier->listeClasse() as $valueC) {?>

											<option value="<?=$valueC->nomgr;?>"><?=strtoupper($valueC->nomgr);?></option><?php
										}?>
									
									</select>
								</div>
							</form>
	                    </th>
	                </form>
	            </tr>
	            <tr>
		            <th colspan="10" class="text-center">Liste des exclus <?=$datenormale;?> 
		           	<a class="btn btn-info" href="printdoc.php?listeabs" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
	          	</tr>
				<tr>
					<th></th>
					<th height="25">N°</th>
					<th>Nom & Prénom</th>
					<th>Né(e)</th>
					<th>Inscrit en</th>
					<th>Téléphone</th>
					<th>Date</th>
					<th>Motif</th>
					<th>Matière</th>
					<th></th>
				</tr>
			</thead>
			<tbody><?php
				if (empty($prodeleve)) {
					$totex=0;
					
				}else{

					$totex=0;

					foreach ($prodeleve as $key=>$eleve) {?>

						<tr><?php

							$nomel=ucwords(strtolower($eleve->prenomel)).' '.strtoupper($eleve->nomel);?>

							<td style="text-align:center;"><?=$key+1;?></td>

							<td style="text-align: center"><a href="discipline.php?disci=<?=$eleve->matricule;?>&nomel=<?=$nomel;?>&promo=<?=$_SESSION['promo'];?>&note"><?=$eleve->matricule;?></a></td>
							

							<td><?=$nomel;?></td>

							<td style=" text-align: center;"><?=(new dateTime($eleve->naissance))->format('d/m/Y');?></td>

							<td height="20" style="text-align: center;"><?=$eleve->classe;?></td>

							<td><?=$eleve->phone;?></td>

							<td><?=$eleve->dateabs.' à '.$eleve->hdebut;?></td>

							<td><?=$eleve->motif;?></td>

							<td><?=$eleve->nommat;?></td>

							<td><a class="btn btn-danger" href="?supidabs=<?=$eleve->id;?>" onclick="return alerteS();">Annuler</a></td>

						</tr><?php
					}
				}?>
			</tbody>
		</table>
	</div><?php
}?>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer cette facture ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>
