<?php
require 'header.php';



if (isset($_GET['printnote'])){?>

    <style type="text/css">
        table.tabliste{
          background-color: white;
          width: 100%;
          color: black;
          font-family: helvetica;
          border-collapse: collapse; 
          margin-top: 30px;     
        }
        .tabliste th {
          border: 2px solid black;
          line-height: 5mm;
          background-color: white;
          color: black;
          font-size: 12px;
          font-weight: bold;
          text-align: center;
          padding-right: 10px;
        }
        .tabliste td {
          border: 2px solid black;
          line-height: 5mm;
          text-align: right;
          padding-right: 10px; 
          font-size: 14px;
        }

        label {
		    float: right;
		    font-size: 14px;
		    font-weight: bold;
		    width: 200px;
		  }

		  ol{
		    list-style: none;
		  }
    </style><?php
}

require 'enteteprint.php';?>



<div style="width: 100%; text-align: left; font-size: 14px; font-weight: bold; margin-top: 30px; margin-left: 50px; background-color: white;"><?='Notes de la '.$_SESSION['groupe'].' en '.$panier->nomMatiere($_SESSION['matn']).' Période: '.$typerepart. $_SESSION['semestre'].' / '. 'Année-Scolaire '.($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></div><?php

if (isset($_POST['matn']) or isset($_POST['devoir']) or isset($_GET['printnote'])) {

	if (isset($_POST['matn'])) {

		$prodevoir=$DB->query('SELECT  nomdev, id, codem, coef, coefcom from devoir where codens=:code and nomgroupe=:nom and codem=:codem and trimes=:sem and promo=:promo', array('code'=>$_SESSION['ens'], 'nom'=>$_SESSION['groupe'], 'codem'=>$_SESSION['matn'], 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo']));
		
	}

	if (isset($_POST['devoir'])) {

		$prodevoir=$DB->query('SELECT  nomdev, id, codem, coef, coefcom from devoir where codens=:code and nomgroupe=:nom and id=:codev and codem=:codem and promo=:promo', array('code'=>$_SESSION['ens'], 'nom'=>$_SESSION['groupe'], 'codev'=>$_SESSION['devoir'], 'codem'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));
		
	}

	$prodevoir=$DB->query('SELECT  nomdev, id, codem, coef, coefcom from devoir where codens=:code and nomgroupe=:nom and codem=:codem and trimes=:sem and promo=:promo', array('code'=>$_SESSION['ens'], 'nom'=>$_SESSION['groupe'], 'codem'=>$_SESSION['matn'], 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo']));?>


	<div class="container" style="display: flex; margin-top: -20px;">


		<div class="col" style="margin-left: 50px; display: flex;">

			<table class="tabliste">
				<thead>
					<tr>
						<th>N</th>
						<th height="30">Prénom & Nom</th>
						<th height="30">Moyenne</th><?php 

							foreach ($prodevoir as $devoir) {

								if (empty($devoir->coef)) {?>

									<th height="30"><?=$devoir->nomdev;?> coef <?=$devoir->coefcom;?><?php

								}else{?>

									<th height="30"><?=$devoir->nomdev;?> coef <?=$devoir->coef;?><?php

								}

								if(isset($_POST['devoir'])){?>

									<a href="note.php?modif_dev=<?=$devoir->id;?>"><img src="css/img/modif.jpg" width="25" height="15"></a><?php
								}
							}?>
						</th>
					</tr>
				</thead>

				<tbody><?php
					$moyengen=0;

						$prodmoyeg=$DB->querys('SELECT count(DISTINCT(matricule)) as coef from effectifn where nomgr=:nom and codem=:code and promo=:promo', array('nom'=>$_SESSION['groupe'], 'code'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));

						$coeff=$prodmoyeg['coef'];// coeff moyenne générale


						$coefcompo=$DB->querys('SELECT  sum(coefcom) as coefc from devoir where type=:type and nomgroupe=:nom and trimes=:sem and promo=:promo and codem=:code', array('type'=>'composition', 'nom'=>$_SESSION['groupe'], 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo'], 'code'=>$_SESSION['matn'])); // Coefficient compo

						$coefnote=$DB->querys('SELECT  sum(coef) as coefn from devoir where type=:type and nomgroupe=:nom and trimes=:sem and promo=:promo and codem=:code', array('type'=>'note de cours', 'nom'=>$_SESSION['groupe'], 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo'], 'code'=>$_SESSION['matn']));// Coefficient note de cours

						
						$prodmat=$DB->query('SELECT  inscription.matricule as matricule, prenomel, nomel from inscription inner join eleve on eleve.matricule=inscription.matricule where nomgr=:nom and annee=:promo order by(prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

						$nbre=0;
						$moyenn=0;
						$moyenc=0;
						
						foreach ($prodmat as $matricule) {

							$prodmoyenne=$DB->querys('SELECT  sum(note*coef) as note, sum(compo*coefcom) as compo, note.matricule as matricule, nomel, prenomel from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule where nomgroupe=:nom and trimes=:sem and note.codens=:codens and note.matricule=:mat and devoir.codem=:code and devoir.promo=:promo order by(prenomel)', array('nom'=>$_SESSION['groupe'], 'sem'=>$_SESSION['semestre'], 'codens'=>$_SESSION['ens'], 'mat'=>$matricule->matricule, 'code'=>$_SESSION['matn'], 'promo'=>$_SESSION['promo']));


							if ($coefcompo['coefc']==0) {
								$compo=0; //Moyenne composition
							}else{
								$compo=($prodmoyenne['compo']/$coefcompo['coefc']); //Moyenne composition

							}

							if ($coefnote['coefn']==0) {
								$cours=0; //Moyenne composition
							}else{
								$cours=($prodmoyenne['note']/$coefnote['coefn']);//Moyenne note de cours

							}

							if (empty($prodmoyenne['compo'])) {

								$generale=$cours; //Moyenne générale

							}elseif (empty($prodmoyenne['note'])) {

								$generale=$compo; //Moyenne générale

							}else{

								if ($_SESSION['niveauclassen']=='primaire') {

                  $generale=($compo); //Moyenne eleve
                }else{

                  $generale=($cours+2*$compo)/3; //Moyenne eleve

                }

							}

							$moyengen+=$generale;?>

							<tr><?php

								if (!empty($prodmoyenne['matricule'])) {?>

									<td style="text-align: center;" height="26"><?=$matricule->matricule;?></td>

									<td style="text-align: left;" height="26"><?=ucfirst($prodmoyenne['prenomel']).' '.strtoupper($prodmoyenne['nomel']);?></td>

									<td height="26"><?=number_format($generale,2,',',' ');?></td><?php
								}else{?>
									<td style="text-align: center;" height="26"><?=$matricule->matricule;?></td>

									<td style="text-align: left;" height="26"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></td>

									<td height="26" style="color: white; background-color: white;">null</td><?php
								}

								foreach ($prodevoir as $devoir) {

									$prodnote=$DB->query('SELECT  *from note inner join eleve on note.matricule=eleve.matricule inner join devoir on note.codev=devoir.id where note.matricule=:mat and codev=:codev and trimes=:sem and note.codem=:code and devoir.promo=:promo order by(prenomel)', array('mat'=>$matricule->matricule, 'codev'=>$devoir->id, 'sem'=>$_SESSION['semestre'], 'code'=>$devoir->codem, 'promo'=>$_SESSION['promo']));

									if (empty($prodnote)) {?>

										<td height="26" style="color: white; background-color: white;">null</td><?php
									}else{

										foreach ($prodnote as $note) {	

											if ($note->type=='composition') {

												$moyenc+=$note->compo;?>

												<td height="26"><?=number_format($note->compo,2,',',' ');?></td><?php

											}else{
												$moyenn+=$note->note;?>

											
												<td height="26"><?=number_format($note->note,2,',',' ');?></td><?php

											}	

										}
									} 
								}?>
								
								
							</tr><?php
							
						}



					if ($moyengen!=0) {?>
					 	<tr>
							<th height="30" colspan="2">Moyenne générale</th><?php

							if (!empty($coeff)) {?>
								
								<th height="31" style="text-align: right;"><?=number_format($moyengen/$coeff,2,',',' ');?></th><?php
							}else{?>
								
								<th height="31"></th><?php

							}

							foreach ($prodevoir as $devoir) {

								$prodmoyeg=$DB->querys('SELECT count(matricule) as coef from effectifn where codev=:code and nomgr=:nom and promo=:promo', array('code'=>$devoir->id, 'nom'=>$_SESSION['groupe'],'promo'=>$_SESSION['promo']));

								$coeff=$prodmoyeg['coef'];// coeff moyenne générale

								

								if (!empty($moyenn)) {

									if (!empty($coeff)) {?>

										<th height="30" style="text-align: right;"><?='  '.number_format($moyenn/$coeff,2,',',' ');?></th><?php
									}
						
									
								}else{

									if (!empty($coeff)) {?>

										<th height="30" style="text-align: right;"><?='  '.number_format($moyenc/$coeff,2,',',' ');?></th><?php
									}
								}
							}?>
						</tr><?php
					 
					} ?>

				</tbody>

			</table>
		</div><?php
}