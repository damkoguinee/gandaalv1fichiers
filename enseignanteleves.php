<?php require 'headerenseignant.php'?><?php

$prodm=$DB->query('SELECT  nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%d/%m/%Y\') AS naissance, inscription.matricule as matricule, nomgr, phone, codef from inscription inner join eleve on eleve.matricule=inscription.matricule inner join contact on contact.matricule=inscription.matricule where inscription.nomgr=:code and annee=:promo order by(prenomel) ', array('code'=>$_GET['voir_elg'], 'promo'=>$_SESSION['promo']));

	$prodf=$DB->querys('SELECT nomgr from groupe  where nomgr=:code', array('code'=>$_GET['voir_elg']));?>

<div>

	<div>

		<table class="payement" style="width: 100%; margin:auto; margin-top: 30px;">
    		<thead>
    			<tr>
    				<th colspan="6" class="info" style="text-align: center">Liste des <?=$_SESSION['typeel'].' en '.$prodf['nomgr'];?> <a style="margin-left: 10px;"href="printdoc.php?voir_elg=<?=$prodf['nomgr'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
    			</tr>

				<tr>
					<th height="30">N°</th>
					<th>Matricule</th>
					<th>Prénom & Nom</th>
					<th>Né(e)</th>
					<th>Téléphone</th>
					<th></th>
				</tr>

			</thead>

			<tbody><?php
			if (empty($prodm)) {
				# code...
			}else{

				foreach ($prodm as $key=> $formation) {?>

					<form method="GET" action="formation.php"> 

						<tr>
							<td style="text-align: center;"><?=$key+1;?></td>									

							<td><?=$formation->matricule;?><input type="hidden" name="elevecl" value="<?=$formation->matricule;?>"></td>

							<td><?=ucfirst(strtolower($formation->prenomel)).' '.strtoupper($formation->nomel);?></td>

							<td><?=$formation->naissance;?></td>

							<td><?=$formation->phone;?></td>

							<td colspan="2">

								<a href="ajout_eleve.php?fiche_eleve=<?=$formation->matricule;?>&promo=<?=$_SESSION['promo'];?>&enseignant"><input type="button" value="+infos" style="width: 100%; font-size: 16px;  cursor: pointer; color: orange;"></a>
							</td>

						</tr>
					</form><?php
				}
			}?>

				
			</tbody>
		</table>
	</div>
</div>