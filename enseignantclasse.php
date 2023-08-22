<?php require 'headerenseignant.php'?><?php
    $promotion=$_SESSION['promo'];
    

	$prodm=$DB->query('SELECT enseignement.id as id, nomgr, nommat, nomen, prenomen, codens, matiere.codem as codem from enseignement inner join matiere on enseignement.codem=matiere.codem inner join enseignant on enseignement.codens=enseignant.matricule where enseignant.matricule=:code and enseignement.promo=:promo order by(prenomen)', array('code'=>$_GET['voir_mate'], 'promo'=>$promotion));?>

	
	    
	<table class="payement" style="width: 60%; margin: auto;" >
		<thead>

			<form>

				<tr>
                	<th colspan="3" class="info" style="text-align: center">Liste de mes classes
                	</th>
              </tr>

			</form>

			<tr>
				<th height="30">Classe</th>
				<th>Matière</th>
				<th></th>
			</tr>

		</thead>

		<tbody><?php
		if (empty($prodm)) {
			# code...
		}else{

			foreach ($prodm as $formation) {?>

				<tr>
					<td><?=$formation->nomgr;?></td>

					<td><?=ucwords($formation->nommat);?></td>

					<td><a href="enseignanteleves.php?voir_elg=<?=$formation->nomgr;?>">Voir les élèves</a></td>

				</tr><?php
			}
		}?>

			
		</tbody>
	</table>