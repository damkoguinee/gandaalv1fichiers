<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

    	<div style="display: flex; flex-wrap: wrap; overflow: auto;">

    		<div>

    			<table class="payement" style="margin-left: 10px;">
		    		<thead>
		    			<tr>
		    				<th colspan="3">Bilan Comptable</th>
				            <th colspan="5">

				                <form method="POST" action="bilancomptable.php" id="suitec" name="termc" style="display: flex;">
				                    <select style="width: 250px; height: 30px; font-size: 19px;" name="anneeff" required="" onchange="this.form.submit()"><?php

				                        if (isset($_POST['anneeff'])) {?>
				                            
				                            <option value="<?=$_POST['anneeff'];?>" ><?=$_POST['anneeff'];?></option><?php

				                        }else{?>

				                            <option><?=$_SESSION['promo'];?></option><?php
				                        }

				                        $anneef=$_SESSION['promo']+1;

				                        for($i=2021;$i<=$anneef ;$i++){?>

				                          <option value="<?=$i;?>"><?=$i;?></option><?php

				                        }?>
				                    </select>
				                </form>
			            	</th>
					   </tr>

					   <tr>
					   		<th></th><?php

							foreach ($panier->cursus() as $valuec) {?>

								
								<th colspan="4"><?=ucfirst($valuec->nom);?></th>
								<?php 
							}?>
						</tr>

					   	<tr>
					   		<th></th><?php

							foreach ($panier->cursus() as $valuec) {?>

								<th>Nbre</th>
								<th>Payé</th>
								<th>Reste</th>
								<th>TR</th>
								</th>
								<?php 
							}?>
						</tr>
					</thead>

					<tbody><?php 

						if (isset($_POST['anneeff'])) {
							$promo=$_POST['anneeff'];
						}else{
							$promo=$_SESSION['promo'];
						}?>

						<tr>
							<th style="text-align:left;">Inscrits</th><?php 

							foreach ($panier->cursus() as $valuec) {?>

								<td style="text-align:center;"><?=$rapport->bilanInscription($valuec->nom, 'inscription', $promo);?></td>

								<td style="text-align:right; color:green;"><?=number_format($rapport->bilanInscriptionApayer($valuec->nom, 'inscription', $promo)[1],0,',',' ');?></td>

								<td style="text-align:right; color: red;"><?=number_format($rapport->bilanInscriptionApayer($valuec->nom, 'inscription', $promo)[0],0,',',' ');?></td>

								<td style="text-align:right;">~<?=number_format($rapport->bilanInscriptionApayer($valuec->nom, 'inscription', $promo)[2],0,',',' ');?>%</td><?php
							}?>
						</tr>

						<tr>
							<th style="text-align:left;">Réinscrits</th><?php 

							foreach ($panier->cursus() as $valuec) {?>

								<td style="text-align:center;"><?=$rapport->bilanInscription($valuec->nom, 'reinscription', $promo);?></td>

								<td style="text-align:right; color: green;"><?=number_format($rapport->bilanInscriptionApayer($valuec->nom, 'reinscription', $promo)[1],0,',',' ');?></td>

								<td style="text-align:right; color: red;"><?=number_format($rapport->bilanInscriptionApayer($valuec->nom, 'reinscription', $promo)[0],0,',',' ');?></td>

								<td style="text-align:right;">~<?=number_format($rapport->bilanInscriptionApayer($valuec->nom, 'reinscription', $promo)[2],0,',',' ');?>%</td><?php
							}?>
						</tr>

						<tr>
							<th style="text-align:left;">Scolarité</th>

							<?php 

							foreach ($panier->cursus() as $valuec) {?>

								<td></td>

								<td style="text-align:right; color:green;"><?=number_format($rapport->bilanFraiscol($valuec->nom, 'reinscription', $promo)[1],0,',',' ');?></td>

								<td style="text-align:right; color:red;"><?=number_format($rapport->bilanFraiscol($valuec->nom, 'reinscription', $promo)[0],0,',',' ');?></td>

								<td style="text-align:right;">~<?=number_format($rapport->bilanFraiscol($valuec->nom, 'reinscription', $promo)[2],0,',',' ');?>%</td><?php
							}?>
						</tr>

						<tr>
							<th style="text-align:left;">Totaux</th>

							<?php 

							$entreepaye=0;

							$resteapayer=0;
							$tauxgeneral=0;

							$effectif=0;

							$prodac=$DB->querys("SELECT count(id) as nbre, sum(montantp) as montant FROM activitespaiehistorique where promoact='{$_SESSION['promo']}' ");
							$activitespaie=$prodac['montant'];

							$prodvers=$DB->querys("SELECT count(id) as nbre, sum(montant*taux) as montant FROM versement where promo='{$_SESSION['promo']}' ");
							$versement=$prodvers['montant'];

							foreach ($panier->cursus() as $valuec) {

								$entreepaye+=$rapport->bilanInscriptionApayer($valuec->nom, 'inscription', $promo)[1]+$rapport->bilanInscriptionApayer($valuec->nom, 'reinscription', $promo)[1]+$rapport->bilanFraiscol($valuec->nom, 'reinscription', $promo)[1];

								$resteapayer+=$rapport->bilanInscriptionApayer($valuec->nom, 'inscription', $promo)[0]+$rapport->bilanInscriptionApayer($valuec->nom, 'reinscription', $promo)[0]+$rapport->bilanFraiscol($valuec->nom, 'reinscription', $promo)[0];?><?php 

								$tauxgen=($rapport->bilanInscriptionApayer($valuec->nom, 'inscription', $promo)[2]+$rapport->bilanInscriptionApayer($valuec->nom, 'reinscription', $promo)[2]+$rapport->bilanFraiscol($valuec->nom, 'reinscription', $promo)[2])/3;

								$effectif+=$rapport->bilanInscription($valuec->nom, 'inscription', $promo)+$rapport->bilanInscription($valuec->nom, 'reinscription', $promo);

								$tauxgeneral+=$tauxgen;?>

								<th style="text-align:center;"><?=$rapport->bilanInscription($valuec->nom, 'inscription', $promo)+$rapport->bilanInscription($valuec->nom, 'reinscription', $promo);?></th>

								<td style="text-align:right; background-color:green; color: white;"><?=number_format($rapport->bilanInscriptionApayer($valuec->nom, 'inscription', $promo)[1]+$rapport->bilanInscriptionApayer($valuec->nom, 'reinscription', $promo)[1]+$rapport->bilanFraiscol($valuec->nom, 'reinscription', $promo)[1],0,',',' ');?></td>

								<td style="text-align:right; background-color:red; color:white"><?=number_format($rapport->bilanInscriptionApayer($valuec->nom, 'inscription', $promo)[0]+$rapport->bilanInscriptionApayer($valuec->nom, 'reinscription', $promo)[0]+$rapport->bilanFraiscol($valuec->nom, 'reinscription', $promo)[0],0,',',' ');?></td>

								<th style="text-align:right;">~<?=number_format($tauxgen,0,',',' ');?>%</th><?php
							}?>
						</tr>
					</tbody>
				</table>

				<table class="payement">
					<tbody>

						<tr>
							<th colspan="3" style="text-align:left;">Bilan des Activités</th>

							<th colspan="2" style="text-align:center;">Effectif: <?=$prodac['nbre'];?></th>

							<th colspan="3" style="text-align:center; background-color: orange;">Prév: --</th>

							<th colspan="3" style="text-align:center; background-color: green;">Payé: <?=number_format($activitespaie,0,',',' ');?></th>
 
							<th colspan="3" style="text-align:center; background-color: red;">Reste: --</th>

							<th colspan="4" style="text-align:center;;">TR:--</th>
						</tr>

						<tr>
							<th colspan="3" style="text-align:left;">Bilan des Recettes</th>

							<th colspan="2" style="text-align:center;">Effectif: <?=$prodvers['nbre'];?></th>

							<th colspan="3" style="text-align:center; background-color: orange;">Prév: --</th>

							<th colspan="3" style="text-align:center; background-color: green;">Payé: <?=number_format($versement,0,',',' ');?></th>
 
							<th colspan="3" style="text-align:center; background-color: red;">Reste: --</th>

							<th colspan="4" style="text-align:center;;">TR: --</th>
						</tr>

						<tr><?php $prev=$entreepaye+$resteapayer+$activitespaie+$versement;?>
							<th colspan="3" style="text-align:left;">Bilan des Entrées</th>

							<th colspan="2" style="text-align:center;">Effectif: <?=$effectif;?></th>

							<th colspan="3" style="text-align:center; background-color: orange;">Prév: <?=number_format($prev,0,',',' ');?></th>

							<th colspan="3" style="text-align:center; background-color: green;">Payé: <?=number_format($entreepaye+$activitespaie+$versement,0,',',' ');?></th>
 
							<th colspan="3" style="text-align:center; background-color: red;">Reste: <?=number_format($resteapayer,0,',',' ');?></th>

							<th colspan="4" style="text-align:center;;">TR: <?=number_format($tauxgeneral/5,0,',',' ');?>%</th>
						</tr>						


						<tr><?php 
							if (empty($rapport->salairePrevPersonnel($promo))) {
								$tauxpers=0;
							}else{
								$tauxpers=($rapport->salairePayePersonnel($promo)/$rapport->salairePrevPersonnel($promo))*100;

							}
							$resteapayerpers=$rapport->salairePrevPersonnel($promo)-$rapport->salairePayePersonnel($promo);
							?>

							<th colspan="3" style="text-align:left;">Paiements Personnels</th>
							<th colspan="2" style="text-align:center;">Effectif: <?=$rapport->nbrePersonnel();?></th>
							<th colspan="3" style="text-align:center; background-color: orange;">Prév: <?=number_format($rapport->salairePrevPersonnel($promo),0,',',' ');?></th>

							<th colspan="3" style="text-align:center; background-color: green;">Payé: <?=number_format($rapport->salairePayePersonnel($promo),0,',',' ');?></th>

							<th colspan="3" style="text-align:center; background-color: red;">Reste: <?=number_format($resteapayerpers,0,',',' ');?></th>

							<th colspan="4" style="text-align:center;;">TR: <?=number_format($tauxpers,0,',',' ');?>%</th>
						</tr>

						<tr><?php 
							$resteapayerens=$rapport->salairePrevEnseignant($promo)-$rapport->salairePayeEnseignant($promo);

							if ($rapport->salairePrevEnseignant($promo)==0) {
								$tauxpers=0;
							}else{

								$tauxpers=($rapport->salairePayeEnseignant($promo)/$rapport->salairePrevEnseignant($promo))*100;

							}
							?>

							<th colspan="3" style="text-align:left;">Paiements Enseignants</th>
							<th colspan="2" style="text-align:center;">Effectif: <?=$rapport->nbreEnseignant();?></th>
							<th colspan="3" style="text-align:center; background-color: orange;">Prév/10.5mois: <?=number_format($rapport->salairePrevEnseignant($promo),0,',',' ');?></th>

							<th colspan="3" style="text-align:center; background-color: green;">Payé: <?=number_format($rapport->salairePayeEnseignant($promo),0,',',' ');?></th>

							<th colspan="3" style="text-align:center; background-color: red;">Reste: <?=number_format($resteapayerens,0,',',' ');?></th>

							<th colspan="4" style="text-align:center;;">TR: <?=number_format($tauxpers,0,',',' ');?>%</th>
						</tr>

						<tr><?php ?>

							<th colspan="3" style="text-align:left;">Dépenses</th>
							<th colspan="2" style="text-align:center;">Nbre: <?=$rapport->bilanDepense($promo)[1];?></th>

							<th colspan="3" style="text-align:center; background-color: orange;">Prév: --</th>

							<th colspan="3" style="text-align:center; background-color: green;">Dépenses: <?=number_format($rapport->bilanDepense($promo)[0],0,',',' ');?></th>

							<th colspan="3" style="text-align:center; background-color: red;">Reste: --</th>

							<th colspan="4" style="text-align:center;;">TR: -- </th>
						</tr>

						<tr><?php
							$beneficeprev=($prev-$rapport->salairePrevPersonnel($promo)-$rapport->salairePrevEnseignant($promo)-$rapport->bilanDepense($promo)[0]);

							$beneficereal=(($entreepaye+$activitespaie+$versement)-$rapport->salairePayePersonnel($promo)-$rapport->salairePayeEnseignant($promo)-$rapport->bilanDepense($promo)[0]);

							if ($beneficeprev<0) {
							 	$color='danger';
							}elseif ($beneficeprev<0) {
								$color='warning';
							}else{

								$color='success';

							}

							if ($beneficereal<0) {
							 	$colorr='danger';
							}elseif ($beneficereal<0) {
								$colorr='warning';
							}else{

								$colorr='success';

							}?>

							<th colspan="5" class="text-start bg-<?=$color;?>">Bénéfice</th>

							<th colspan="6" class="text-center bg-<?=$color;?>">Prév: <?=number_format($beneficeprev,0,',',' ')?></th>

							<th colspan="6" class="text-center bg-<?=$colorr;?>">Réal: <?=number_format($beneficereal,0,',',' ')?></th>
						</tr>

						<tr><?php
							$catdette="dette";
							$catdettep="paiementdette";
							$proddette=$DB->querys("SELECT sum(montant) as montant FROM dettesCreances where categorie='{$catdette}' ");
							$proddettep=$DB->querys("SELECT sum(montant) as montant FROM dettesCreances where categorie='{$catdettep}' ");

							$dette=$proddette['montant'];
							$dettep=$proddettep['montant'];
							$dettes=$dette-$dettep;

							$catcreance="creance";
							$catcreancep="paiementcreance";
							$prodcreance=$DB->querys("SELECT sum(montant) as montant FROM dettesCreances where categorie='{$catcreance}' ");

							$prodcreancep=$DB->querys("SELECT sum(montant) as montant FROM dettesCreances where categorie='{$catcreancep}' ");

							$creance=$prodcreance['montant'];
							$creancep=$prodcreancep['montant'];
							$creances=$creance-$creancep;
							?>

							<th colspan="5" class="text-start bg-danger bg-opacity-50">Dettes/Créances</th>

							<th colspan="3" class="text-center bg-danger bg-opacity-50">Dettes: <?=number_format($dettes,0,',',' ')?></th>

							<th colspan="3" class="text-center bg-success bg-opacity-50">Créances: <?=number_format($creances,0,',',' ')?></th>

							<th colspan="6" class="text-center bg-danger bg-opacity-50">Solde: <?=number_format($dettes-$creances,0,',',' ')?></th>
						</tr>

						<tr><?php 
							$soldegeneral=$beneficereal-($dettes-$creances);
							if($soldegeneral >=0){
								$color="success";
							}else{
								$color="danger";
							}?>
							
							<th colspan="5" class="text-start bg-primary bg-opacity-75">SOLDE</th>

							<th colspan="12" class="text-center bg-<?=$color;?> bg-opacity-75"><?=number_format($soldegeneral,0,',',' ')?></th>
						</tr>
					
					</tbody>
				</table>
			</div>
		</div><?php 
	}
}
