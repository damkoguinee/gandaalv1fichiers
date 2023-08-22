<?php
require 'header.php';

if ($products['niveau']<4) {?>

    <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

}else{?>

    </div>

    <div style="display:flex;"><?php

		require 'navabsence.php';?>

		<div><?php

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

			<div class="col">
							
			    <form id="formulaire" method="POST" action="listeabsence.php" style="width: 80%;">

			    	<fieldset><legend>Justification d'absences de <?=$_GET['nomel'].' matricule N° '.$_GET['mateleve'];?></legend>
			    		<ol>
							<li>
							    <label>Motif d'absence</label>
							    <textarea type="text" name="motif" required="" maxlength="100" style="width: 80%;"></textarea>
							    <input type="hidden" name="id" value="<?=$_GET['idabs'];?>" />
							    <input type="hidden" name="mat" value="<?=$_GET['mateleve'];?>" />
							</li>
						</ol>
					</fieldset>

					<fieldset>

						<input type="submit" value="Valider" name="justabs" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/>
					</fieldset>

				</form>
			</div> <?php
			
		}

		if (isset($_POST['justabs'])) {

			$motif=addslashes(Htmlspecialchars($_POST['motif']));
			$id=addslashes(Htmlspecialchars($_POST['id']));
			$mat=addslashes(Htmlspecialchars($_POST['mat']));
			
			$DB->insert('INSERT INTO justabsence(id_absence, matricule, motif, datejust) values( ?, ?, ?, now())', array($id, $mat, $motif));?>


			<div class="alerteV">Absence justifiée avec succée!!!</div><?php
		}


		if (isset($_GET['supidabs'])) {

            $DB->delete('DELETE FROM absence WHERE id = ?', array($_GET['supidabs']));?>

            <div class="alerteV">Absence supprimé avec succèe</div><?php
        }

		if (isset($_POST['j1'])) {

			$prodeleve=$DB->query('SELECT absence.id as id, nomel, prenomel, adresse, eleve.sexe as sexe, pere, mere, eleve.naissance as naissance, eleve.matricule as matricule, phone, nommat, nomen, prenomen, nbreheure, hdebut, date_format(dateabs,\'%d/%m/%Y \') as dateabs, nomgr as classe, nomf from eleve inner join absence on eleve.matricule=absence.matricule inner join matiere on matiere.codem=absence.codem inner join enseignant on enseignant.matricule=absence.codens inner join contact on eleve.matricule=contact.matricule inner join formation on formation.codef=matiere.codef where absence.nomgr=:classe and promo=:promo and DATE_FORMAT(dateabs, \'%Y%m%d\') >= :date1 and DATE_FORMAT(dateabs, \'%Y%m%d\') <= :date2 and absence.id not in(SELECT id_absence FROM justabsence)  order by (prenomel)', array('classe'=>$_SESSION['classe'], 'promo'=>$_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));

		}elseif (isset($_GET['absmatri'])) {

			$prodeleve=$DB->query('SELECT absence.id as id, nomel, prenomel, adresse, eleve.sexe as sexe, pere, mere, eleve.naissance as naissance, eleve.matricule as matricule, phone, nommat, nomen, prenomen, nbreheure, hdebut, date_format(dateabs,\'%d/%m/%Y \') as dateabs, nomgr as classe, nomf from eleve inner join absence on eleve.matricule=absence.matricule inner join matiere on matiere.codem=absence.codem inner join enseignant on enseignant.matricule=absence.codens inner join contact on eleve.matricule=contact.matricule inner join formation on formation.codef=matiere.codef where promo=:promo and absence.matricule=:matr and semestre=:sem and absence.id not in(SELECT id_absence FROM justabsence)', array('promo'=>$_GET['promo'], 'matr' => $_GET['absmatri'], 'sem'=>$_GET['sem']));

		}else{

			$prodeleve=$DB->query('SELECT absence.id as id, nomel, prenomel, adresse, eleve.sexe as sexe, pere, mere, eleve.naissance as naissance, eleve.matricule as matricule, phone, nommat, nomen, prenomen, nbreheure, hdebut, date_format(dateabs,\'%d/%m/%Y \') as dateabs, nomgr as classe, nomf from eleve inner join absence on eleve.matricule=absence.matricule inner join matiere on matiere.codem=absence.codem inner join enseignant on enseignant.matricule=absence.codens inner join contact on eleve.matricule=contact.matricule inner join formation on formation.codef=matiere.codef where promo=:promo and DATE_FORMAT(dateabs, \'%Y%m%d\') >= :date1 and DATE_FORMAT(dateabs, \'%Y%m%d\') <= :date2  and absence.id not in(SELECT id_absence FROM justabsence)  order by (prenomel)', array('promo'=>$_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));
		} ?>

		<table class="tranche">
			<thead>

				<tr>
					<form id='formulaire' method="POST" action="listeabsence.php" name="termc" style="height: 30px;"><?php

	                    if (isset($_POST['j1'])) {?>

	                      <th style="border-right: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"></th><?php

	                    }else{?>

	                      <th style="border-right: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" onchange="this.form.submit()"></th><?php

	                    }

	                    if (isset($_POST['j2'])) {?>

	                      <th colspan="4" style="border-left: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"></th><?php

	                    }else{?>

	                      <th colspan="4" style="border-left: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" onchange="this.form.submit()"></th><?php

	                    }?>

	                    <th>
	                    	<select name="classe" onchange="this.form.submit()" style="width:200px;"><?php

		                    	if (isset($_POST['j1'])) {?>
		                    		<option value="<?=$_SESSION['classe'];?>"><?=strtoupper($_SESSION['classe']);?></option><?php 
		                    	}else{?>
		                    		<option>selectionnez une classe</option><?php 
		                    	}

		                    	foreach ($panier->listeClasse() as $valueC) {?>

		                    		<option value="<?=$valueC->nomgr;?>"><?=strtoupper($valueC->nomgr);?></option><?php
		                    	}?>
	                    	
	                    	</select>
	                    </th>
	                </form>
	            </tr>
	            <tr>
		            <th colspan="10" style="text-align: center, font-size: 12px;">Liste des Absences non Justifiées <?=$datenormale;?> 
		           	<a style="margin-left: 10px;"href="printdoc.php?listeabs" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
	          	</tr>

				<tr>
					<th height="25">N°</th>
					<th>Nom & Prénom</th>
					<th>Né(e)</th>
					<th>Inscrit en</th>
					<th>Téléphone</th>
					<th>Date</th>
					<th>Durée</th>
					<th>Matiere</th>
					<th>Etat</th>
					<th></th>
				</tr>
			</thead>
			<tbody><?php
			$toth=0;
			if (empty($prodeleve)) {
				
			}else{

				

				foreach ($prodeleve as $eleve) {

					$toth+=$eleve->nbreheure;?>

					<tr><?php

						$nomel=ucwords(strtolower($eleve->prenomel)).' '.strtoupper($eleve->nomel);?>

						<td style="text-align: center"><a href="discipline.php?disci=<?=$eleve->matricule;?>&nomel=<?=$nomel;?>&promo=<?=$_SESSION['promo'];?>&note"><?=$eleve->matricule;?></a></td>
						

					  	<td><?=$nomel;?></td>

					  	<td style=" text-align: center;"><?=(new dateTime($eleve->naissance))->format('d/m/Y');?></td>

			            <td height="20" style="text-align: center;"><?=$eleve->classe;?></td>

					  	<td><?=$eleve->phone;?></td>

					  	<td><?=$eleve->dateabs.' à '.$eleve->hdebut;?></td>

					  	<td style="text-align: center;"><?=$eleve->nbreheure;?> h</td>

					  	<td><?=$eleve->nommat;?></td>

					  	<td><a href="listeabsence.php?nomel=<?=$nomel;?>&mateleve=<?=$eleve->matricule;?>&idabs=<?=$eleve->id;?>&promo=<?=$_SESSION['promo'];?>"><input type="button" value="Gerer" style="width: 100%; font-size: 16px;  cursor: pointer; background-color: orange;"></a></td>

					  	<td><a href="listeabsence.php?supidabs=<?=$eleve->id;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 100%; font-size: 16px;  cursor: pointer; background-color: red;"></a></td>

					</tr><?php
				}
			}?>
			</tbody>

			<tfoot>
				<tr>
					<th height="25" colspan="6">Total</th>
					<th><?=$toth;?> h</th>
				</tr>
			</tfoot>
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
