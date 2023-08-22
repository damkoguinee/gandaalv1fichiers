<?php
require 'headereleve.php';

if ($products['niveau']<1) {?>

    <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

}else{?>

	<div><?php

		if (isset($_GET['absmatri'])) {

			$entete='Liste des absences non justifiées';

			$prodeleve=$DB->query('SELECT absence.id as id, nomel, prenomel, adresse, eleve.sexe as sexe, pere, mere, DATE_FORMAT(eleve.naissance, \'%Y\')AS naissance, eleve.matricule as matricule, phone, nommat, nomen, prenomen, nbreheure, hdebut, date_format(dateabs,\'%d/%m/%Y \') as dateabs, formation.niveau as classe, nomf from eleve inner join absence on eleve.matricule=absence.matricule inner join matiere on matiere.codem=absence.codem inner join enseignant on enseignant.matricule=absence.codens inner join contact on eleve.matricule=contact.matricule inner join formation on formation.codef=matiere.codef where promo=:promo and absence.matricule=:matr and semestre=:sem and absence.id not in(SELECT id_absence FROM justabsence)', array('promo'=>$_GET['promo'], 'matr' => $_GET['absmatri'], 'sem'=>$_GET['sem']));

		}elseif (isset($_GET['retmatri'])) {

			$entete='Liste des rétards';

			$prodeleve=$DB->query('SELECT retard.id as id, nomel, prenomel, adresse, eleve.sexe as sexe, pere, mere, DATE_FORMAT(eleve.naissance, \'%Y\')AS naissance, eleve.matricule as matricule, phone, nommat, nomen, prenomen, timeretard, hdebut, date_format(dateabs,\'%d/%m/%Y \') as dateabs, formation.niveau as classe, nomf from eleve inner join retard on eleve.matricule=retard.matricule inner join matiere on matiere.codem=retard.codem inner join enseignant on enseignant.matricule=retard.codens inner join contact on eleve.matricule=contact.matricule inner join formation on formation.codef=matiere.codef where promo=:promo and retard.matricule=:matr and semestre=:sem', array('promo'=>$_GET['promo'], 'matr' => $_GET['retmatri'], 'sem'=>$_GET['sem']));

		}else{
			$entete='Liste des exclusions';

			$prodeleve=$DB->query('SELECT exclus.id as id, nomel, prenomel, adresse, eleve.sexe as sexe, pere, mere, DATE_FORMAT(eleve.naissance, \'%Y\')AS naissance, eleve.matricule as matricule, phone, nommat, nomen, prenomen, motif, hdebut, date_format(dateexclus,\'%d/%m/%Y \') as dateabs, formation.niveau as classe, nomf from eleve inner join exclus on eleve.matricule=exclus.matricule inner join matiere on matiere.codem=exclus.codem inner join enseignant on enseignant.matricule=exclus.codens inner join contact on eleve.matricule=contact.matricule inner join formation on formation.codef=matiere.codef where promo=:promo and exclus.matricule=:matr and semestre=:sem', array('promo'=>$_GET['promo'], 'matr' => $_GET['exmatri'], 'sem'=>$_GET['sem']));
		} ?>

		<table class="tranche">
			<thead>

				<tr>
	            	<th colspan="8" class="info" style="text-align: center"><?=$entete;?>
	            	</th>
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
				</tr>
			</thead>
			<tbody><?php
			$toth=0;
			if (empty($prodeleve)) {
				
			}else{

				

				foreach ($prodeleve as $eleve) {

					

					if (isset($_GET['absmatri'])) {

						$nbreheure=$eleve->nbreheure.' H';
						$toth+=$eleve->nbreheure;

					}elseif (isset($_GET['retmatri'])) {

						$nbreheure=$eleve->timeretard.' min';
						$toth+=$eleve->timeretard;

					}else{

						$nbreheure=' ';
						$toth+=0;

					}?>

					<tr><?php

						$nomel=ucwords(strtolower($eleve->prenomel)).' '.strtoupper($eleve->nomel);?>

						<td style="text-align: center"><?=$eleve->matricule;?></td>
						

					  	<td><?=$nomel;?></td>

					  	<td style=" text-align: center;"><?=$eleve->naissance;?></td>

			            <td height="20"><?=$eleve->classe.' '.$eleve->nomf;?></td>

					  	<td><?=$eleve->phone;?></td>

					  	<td><?=$eleve->dateabs.' à '.$eleve->hdebut;?></td>

					  	<td style="text-align: center;"><?=$nbreheure;?></td>

					  	<td><?=$eleve->nommat;?></td>

					</tr><?php
				}
			}?>
			</tbody>

			<tfoot>
				<tr>
					<th height="25" colspan="6">Total</th>
					<th><?=$toth;?></th>
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
