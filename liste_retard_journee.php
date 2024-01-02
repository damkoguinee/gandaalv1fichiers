<?php
require 'headerv3.php';

if ($products['niveau']<4) {?>

    <div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

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

			//require 'headerabsence.php';

			if (isset($_GET['idabs'])) {?>
								
				<form class="form bg-light py-2" method="POST">

					<fieldset><legend>Justification d'absences de <?=$_GET['nomel'].' matricule N° '.$_GET['mateleve'];?></legend>
						<div class="form-floating">
							<textarea class="form-control" type="text" name="motif" required="" maxlength="100" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
							<label for="floatingTextarea">Motif d'absence</label>
						</div>

						<input class="form-control" type="hidden" name="id" value="<?=$_GET['idabs'];?>" />
						<input class="form-control" type="hidden" name="mat" value="<?=$_GET['mateleve'];?>" />
							
					</fieldset>

					<button class="btn btn-primary my-2" type="submit" name="justabs" onclick="return alerteV();" >Valider</button>

				</form> <?php
				
			}

			if (isset($_POST['justabs'])) {

				$motif=addslashes(Htmlspecialchars($_POST['motif']));
				$id=addslashes(Htmlspecialchars($_POST['id']));
				$mat=addslashes(Htmlspecialchars($_POST['mat']));
				
				$DB->insert('INSERT INTO just_retard_journee(id_absence, matricule, motif, datejust) values( ?, ?, ?, now())', array($id, $mat, $motif));?>


				<div class="alert alert-success">Absence justifiée avec succée!!!</div><?php
			}


			if (isset($_GET['supidabs'])) {

				$DB->delete('DELETE FROM accesiteretard WHERE id = ?', array($_GET['supidabs']));?>

				<div class="alert alert-success">Absence supprimé avec succèe</div><?php
			}

			if (isset($_POST['j1'])) {

                $prodeleve=$DB->query("SELECT *from inscription  inner join accesiteretard on matricule = mat_abs where nomgr='{$_SESSION['classe']}' and promo='{$_SESSION['promo']}' and annee='{$_SESSION['promo']}' and DATE_FORMAT(date_abs, \"%Y%m%d\") >= '{$_SESSION['date1']}' and DATE_FORMAT(date_abs, \"%Y%m%d\") <= '{$_SESSION['date2']}'  and accesiteretard.id not in(SELECT id_absence FROM just_retard_journee)  order by (mat_abs)");

			}elseif (isset($_GET['absmatri'])) {

				// $prodeleve=$DB->query('SELECT accesiteretard.id as id, nomel, prenomel, eleve.adresse as adresse, eleve.sexe as sexe, pere, mere, eleve.naissance as naissance, eleve.matricule as matricule, phone, nommat, nomen, prenomen, nbreheure, hdebut, date_format(date_abs,\'%d/%m/%Y \') as date_abs, nomgr as classe, nomf from eleve inner join absence on eleve.matricule=absence.matricule inner join matiere on matiere.codem=absence.codem inner join enseignant on enseignant.matricule=absence.codens inner join contact on eleve.matricule=contact.matricule inner join formation on formation.codef=matiere.codef where promo=:promo and absence.matricule=:matr and semestre=:sem and accesiteretard.id not in(SELECT id_absence FROM just_retard_journee)', array('promo'=>$_GET['promo'], 'matr' => $_GET['absmatri'], 'sem'=>$_GET['sem']));

                // $prodeleve=$DB->query("SELECT *from accesiteretard inner join inscription on matricule = mat_abs where promo='{$_SESSION['promo']}' and annee='{$_SESSION['promo']}' and DATE_FORMAT(date_abs, \"%Y%m%d\") >= '{$_SESSION['date1']}' and DATE_FORMAT(date_abs, \"%Y%m%d\") <= '{$_SESSION['date2']}'  and accesiteretard.id not in(SELECT id_absence FROM just_retard_journee)  order by (mat_abs)");

			}else{

				$prodeleve=$DB->query("SELECT *from inscription  inner join accesiteretard  on matricule = mat_abs where promo='{$_SESSION['promo']}' and annee='{$_SESSION['promo']}' and DATE_FORMAT(date_abs, \"%Y%m%d\") >= '{$_SESSION['date1']}' and DATE_FORMAT(date_abs, \"%Y%m%d\") <= '{$_SESSION['date2']}'  and accesiteretard.id not in(SELECT id_absence FROM just_retard_journee)  order by (mat_abs)");
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
		            <th colspan="10" class="text-center">Liste des Absences non Justifiées <?=$datenormale;?> 
		           	<a class="btn btn-info" href="printdoc.php?listeabs" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
	          	</tr>

				<tr>
					<th>N°</th>
					<th>Date</th>
                    <th>Période</th>
					<th>Matricule</th>
					<th>Prénom & Nom</th>
					<th>Classe</th>
					<th>Téléphone</th>
					<th></th>
				</tr>
			</thead>
			<tbody><?php
				$toth=0;
				if (empty($prodeleve)) {
					
				}else{
					foreach ($prodeleve as $key => $eleve) {?>

						<tr><?php
                            $info_eleve = $panier->findEleveByMat($eleve->mat_abs);
                            $nomel = ucwords($info_eleve["prenomel"])." ".strtoupper($info_eleve["nomel"]);?>
                            <td class="text-center"><?=($key+1);?></td>
                            <td class="text-center"><?=(new DateTime($eleve->date_abs))->format("d/m/Y");?></td>
                            <td class="text-center"><?=$eleve->journee;?></td>
							<td class="text-center"><a href="discipline.php?disci=<?=$eleve->mat_abs;?>&nomel=<?=$nomel;?>&promo=<?=$_SESSION['promo'];?>&note"><?=$eleve->mat_abs;?></a></td>
							<td><?=$nomel;?></td>
                            <td class="text-center"><?=$info_eleve["nomgr"];?></td>

							<td style=" text-align: center;"><?=$info_eleve["phone"];?></td>

							<td><a class="btn btn-warning" href="?nomel=<?=$nomel;?>&mateleve=<?=$eleve->mat_abs;?>&idabs=<?=$eleve->id;?>&promo=<?=$_SESSION['promo'];?>">Gestion</a></td>

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
