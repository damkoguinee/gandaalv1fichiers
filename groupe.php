<?php
require 'headerv2.php';?>

<div class="container-fluid">
	<div class="row"><?php 
		require 'navformation.php'; ?>
		<div class="col-sm-12 col-md-10" style="overflow:auto;"><?php

			if (isset($_SESSION['pseudo'])) {
				
				if ($products['niveau']<1) {?>

					<div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

				}else{

				//require 'navformation.php';

				

				if (isset($_GET['ajout_gr']) or isset($_POST['codef'])) {

					if (!empty($_SESSION['niveauf'])) {

						$form=$DB->query('SELECT codef, nomf, classe, niveau from formation where niveau=:niv', array('niv'=>$_SESSION['niveauf']));

						$prodprof=$DB->query('SELECT nomen, prenomen, enseignant.matricule as matricule from enseignant inner join niveau on niveau.matricule=enseignant.matricule where nom=:niv', array('niv'=>$_SESSION['niveauf']));

						$_SESSION['nivclass']=$_SESSION['niveauf'];

					}else{

						$form=$DB->query('SELECT codef, nomf, classe, niveau from formation');
						$prodprof=$DB->query('SELECT nomen, prenomen, matricule from enseignant ');

						if (isset($_POST['codef'])) {

							$nivclass=$DB->querys('SELECT niveau from formation where codef=:code', array('code'=>$_POST['codef']));

							$_SESSION['nivclass']=$nivclass['niveau'];
						}else{
							$_SESSION['nivclass']="";
						}

						
					}?>
					<form id="formulaire" method="POST" action="groupe.php">

						<fieldset><legend>Ajouter une Classe</legend>
							<ol>

								<li>
									<label>Formation</label>
									<select type="text" name="codef" required="" onchange="this.form.submit()"><?php
										if (isset($_POST['codef'])) {?>

											<option value="<?=$_POST['codef'];?>"><?=$_POST['codef'];?></option><?php
										}else{?>

											<option></option><?php
										}
										foreach ($form as $codef) {
											if ($codef->classe=='1') {?>

												<option value="<?=$codef->codef;?>"><?=$codef->classe.' ère';?></option><?php

											}elseif($codef->classe=="2nde"){?>

												<option value="<?=$codef->codef;?>"><?=$codef->classe;?></option><?php

											}elseif(($codef->classe>=2 and $codef->classe<=20)){?>

												<option value="<?=$codef->codef;?>"><?=$codef->classe.'eme '.$codef->nomf;?></option><?php

											}elseif ($codef->niveau=='maternelle' or $codef->niveau=='primaire') {?>

												<option value="<?=$codef->codef;?>"><?=$codef->classe;?></option><?php
		
											}else{?>

												<option value="<?=$codef->codef;?>"><?=$codef->classe.' '.$codef->nomf;?></option><?php
											}

										}?>
									</select>

									<input type="hidden" name="niveau" value="<?=$_SESSION['nivclass'];?>">

								</li>

								<li>
									<label>Nom de la Classe</label>
									<input type="text" name="nomg" required=""/>
								</li>

								<li>

									<label>Prof Coordinateur</label>
									<select type="text" name="prof" required="">
										<option></option><?php
										foreach ($prodprof as $prof) {?>

											<option value="<?=$prof->matricule;?>"><?=ucfirst(strtolower($prof->prenomen)).' '.strtoupper($prof->nomen);?></option><?php

										}?>
									</select>
								</li>

								<li><label>Année-Scolaire</label>

									<select type="text" name="promo" required="">
										<option value="<?=$_SESSION['promo'];?>"><?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></option><?php
									
										$annee=date("Y")+1;

										for($i=2022;$i<=$annee ;$i++){
											$j=$i+1;?>

											<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

										}?>
									</select>
									
								</li>
							</ol>

						</fieldset>

						<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajoutegr" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
					</form><?php
				}

				if(isset($_POST['ajoutegr'])){

					if($_POST['nomg']!="" and $_POST['prof']!="" and $_POST['codef']!="" ){
						
						$nomg=addslashes(Htmlspecialchars($_POST['nomg']));
						$prof=addslashes(Htmlspecialchars($_POST['prof']));
						$codef=addslashes(Htmlspecialchars($_POST['codef']));
						$niveau=addslashes(Htmlspecialchars($_POST['niveau']));
								

						$nb=$DB->querys('SELECT nomgr from groupe where (nomgr=:nom and promo=:promo)', array(
							'nom'=>$nomg, 'promo'=>$_POST['promo']
						));

						if(!empty($nb)){?>
							<div class="alert alert-warning">Cette classe existe déjà</div><?php

						}else{

							$DB->insert('INSERT INTO groupe(nomgr, profcoor, codef, niveau, promo) values( ?, ?, ?, ?, ?)', array($nomg, $prof, $codef, $niveau, $_POST['promo']));?>	

							<div class="alerteV">Classe ajoutée avec succèe!!!</div><?php
						}

					}else{?>	

						<div class="alert alert-warning">Remplissez les champs vides</div><?php
					}
				}

				//Modification

				if(isset($_GET['modif_f'])){

					$prodm=$DB->querys('SELECT *from groupe where promo=:promo and nomgr=:nom', array('promo'=>$_SESSION['promo'], 'nom'=>$_GET['nomgr']));?>
					
					<div class="col">
						<form id="formulaire" method="POST" action="groupe.php">

							<fieldset><legend>Modifier le nom de la Classe</legend>
								<ol>
									<li>
										<label for="">Nom de la Classe</label>
										<input type="text" name="classeup" value="<?=$prodm['nomgr'];?>"/>
										<input type="hidden" name="classe" value="<?=$prodm['nomgr'];?>"/>									
									</li>
								</ol>

							</fieldset>
							<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Modifier" name="modifgr" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
						</form>
					</div><?php
				}

				if(isset($_POST['modifgr'])){

					if($_POST['classeup']!=""){
						
						$classeup=addslashes(Htmlspecialchars($_POST['classeup']));
						$classe=addslashes(Htmlspecialchars($_POST['classe']));

						$DB->insert("UPDATE groupe SET nomgr='{$classeup}' WHERE nomgr='{$classe}' and promo='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE absence SET nomgr='{$classeup}' WHERE nomgr='{$classe}' and promo='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE admis SET nomgr='{$classeup}' WHERE nomgr='{$classe}' and promo='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE devoir SET nomgroupe='{$classeup}' WHERE nomgroupe='{$classe}' and promo='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE devoirmaison SET classe='{$classeup}' WHERE classe='{$classe}' and promo='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE effectifn SET nomgr='{$classeup}' WHERE nomgr='{$classe}' and promo='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE elevepreinscription SET nomgr='{$classeup}' WHERE nomgr='{$classe}' and promo='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE enseignement SET nomgr='{$classeup}' WHERE nomgr='{$classe}' and promo='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE events SET nomgrp='{$classeup}' WHERE nomgrp='{$classe}' and promo='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE exclus SET nomgr='{$classeup}' WHERE nomgr='{$classe}' and promo='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE horairet SET groupe='{$classeup}' WHERE groupe='{$classe}' and annees='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE inscription SET nomgr='{$classeup}' WHERE nomgr='{$classe}' and annee='{$_SESSION['promo']}' ");
						$DB->insert("UPDATE retard SET nomgr='{$classeup}' WHERE nomgr='{$classe}' and promo='{$_SESSION['promo']}' ");?>	

						<div class="alerteV">Classe modifiée avec succèe!!!</div><?php
						

					}else{?>	

						<div class="alert alert-warning">Remplissez les champs vides</div><?php
					}
				}

				//fin modif

				// Dupliquer une classe pour l'année suivnate

				if(isset($_GET['dupliq_f'])){

					$promo=($_SESSION['promo']+1);

					$nb=$DB->querys('SELECT nomgr from groupe where (nomgr=:nom and promo=:promo)', array('nom'=>$_GET['nomgr'], 'promo'=>$promo));

						if(!empty($nb)){?>

							<div class="alert alert-warning">Cette classe existe déjà</div><?php

						}else{

							$DB->insert('INSERT INTO groupe(nomgr, codef, niveau, promo) values( ?, ?, ?, ?)', array($_GET['nomgr'], $_GET['codef'], $_GET['niveau'], $promo));?>	

							<div class="alert alert-success">Classe ajoutée avec succèe!!!</div><?php
						}
				}

				if (isset($_GET['group']) or isset($_POST['ajoutegr'])  or isset($_GET['del_gr']) or isset($_POST['modifgr']) or isset($_GET['dupliq_f'])) {

					if (isset($_GET['del_gr'])) {

						$prodg=$DB->querys('SELECT nomgr from groupe where id=:id', array('id'=>$_GET['del_gr']));

						$DB->delete('DELETE FROM groupe WHERE id = ?', array($_GET['del_gr']));

						$prodev=$DB->query('SELECT id from devoir where nomgroupe=:id and promo=:promo', array('id'=>$prodg['nomgr'] ,'promo'=> $_SESSION['promo']));

						$DB->delete('DELETE FROM devoir WHERE nomgroupe = ? and promo= ?', array($prodg['nomgr'], $_SESSION['promo']));

						foreach ($prodev as $value) {
							
							$DB->delete('DELETE FROM note WHERE codev = ?', array($value->id));
						}

						$DB->delete('DELETE FROM enseignement WHERE nomgr = ? and promo= ?', array($prodg['nomgr'], $_SESSION['promo']));

						//$prodins=$DB->query('SELECT matricule from inscription where nomgr=:id and annee=:promo', array('id'=>$prodg['nomgr'], 'promo'=>$_SESSION['promo']));

						$DB->delete('DELETE FROM inscription WHERE nomgr = ? and annee= ?', array($prodg['nomgr'], $_SESSION['promo']));

						//foreach ($prodins as $value) {
							
							//$DB->delete('DELETE FROM contact WHERE matricule = ?', array($value->matricule));

							//$DB->delete('DELETE FROM login WHERE matricule = ?', array($value->matricule));
						//}?>

					<div class="alerteV">Suppression reussie!!!</div><?php 
					}?>

		    
					<table class="table table-hover table-bordered table-striped table-responsive text-center">

						<thead>
							<tr>
								<th colspan="10" class="info" style="text-align: center">Liste des Classes <a class="btn btn-info mx-2" href="printdoc.php?groupe" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>	

								<th colspan="4" class="info" style="text-align: center"><a class="btn btn-warning mx-2 text-center" href="groupe.php?ajout_gr">Ajouter une classe</a></th>									
							</tr>

							<tr>
								<th colspan="2">N°</th>
								<th>Classes</th>
								<th colspan="3" class="bg-success bg-opacity-50">Effectif
									<table class="table table-hover table-bordered table-striped table-responsive text-center my-0 py-0">
										<thead>
											<th class="px-1">Fille</th>
											<th class="px-1">Garçon</th>
											<th class="px-1">Total</th>
										</thead>
									</table>

								</th>
								<th colspan="3" class="bg-info bg-opacity-50">Effectif
									<table class="table table-hover table-bordered table-striped table-responsive text-center my-0 py-0">
										<thead>
											<th class="px-1">Anciens</th>
											<th class="px-1">Nouveaux</th>
											<th class="px-1">Total</th>
										</thead>
									</table>

								</th>
								<th class="bg-warning bg-opacity-50">Redoublants</th>
								<th>% Nouveaux</th>
								<th>Elèves</th>
								<th colspan="2">Carte
								<table class="table table-hover table-bordered table-striped table-responsive text-center my-0 py-0">
										<thead>
											<tr>
												<th class="px-1">Retrait</th>
												<th class="px-1">Scolaire</th>
											</tr>
										</thead>
									</table>
								</th>
								<th colspan="3">Actions</th>								
							</tr>

						</thead><?php

						if (!empty($_SESSION['niveauf'])) {

							$prodm=$DB->query('SELECT id, nom from cursus where nom=:niv order by(id)', array('niv'=>$_SESSION['niveauf']));

						}else{

							$prodm=$DB->query('SELECT id, nom from cursus  order by(cursus.id)');

						}
						$count=1;
						$cumulgenseconfille=0;
						$cumulgensecongarcon=0;
						$cumulgenseconeffectif=0;
						$cumulgenseconanciens=0;
						$cumulgenseconnouveaux=0;
						$cumulgensecontotalins=0;
						$cumulgenseconredoublants=0;
						$cumulgenseconpourcentage=0;

						$cumulgenfille=0;
						$cumulgengarcon=0;
						$cumulgeneffectif=0;
						$cumulgenanciens=0;
						$cumulgennouveaux=0;
						$cumulgentotalins=0;
						$cumulgenredoublants=0;
						$cumulgenpourcentage=0;
						foreach ($prodm as $values) {
							if (!empty($_SESSION['niveauf'])) {

								$prodf=$DB->query('SELECT groupe.id as id, nomgr, groupe.niveau as niveau, groupe.codef as codef, nomf, classe from groupe inner join formation on formation.codef=groupe.codef where promo=:promo and groupe.niveau=:niv', array('promo'=>$_SESSION['promo'], 'niv'=>$_SESSION['niveauf']));

							}else{

								$prodf=$DB->query('SELECT groupe.id as id, nomgr, groupe.niveau as niveau, groupe.codef as codef, nomf, classe from groupe inner join formation on formation.codef=groupe.codef where promo=:promo and groupe.niveau=:niv', array('promo'=>$_SESSION['promo'], 'niv'=>$values->nom));
							}

							if(!empty($prodf)){?>

								<tbody>
									<tr>
										<th colspan="18" class="text-center bg-secondary">Niveau <?=ucwords($values->nom);?></th>
									</tr><?php

									if (empty($prodf)) {

									}else{
										$cumulfille=0;
										$cumulgarcon=0;
										$cumuleffectif=0;
										$cumulanciens=0;
										$cumulnouveaux=0;
										$cumultotalins=0;
										$cumulredoublants=0;
										$cumulpourcentage=0;
										foreach ($prodf as $key=> $formation) {
											$fille=$panier->effectifSexeClasse("f",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0];
											$garcon=$panier->effectifSexeClasse("m",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0];
											$totaleff=$fille+$garcon;

											$anciens=$panier->effectifInscritClasse("reinscription",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0];
											$nouveaux=$panier->effectifInscritClasse("inscription",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0];
											$totalins=$anciens+$nouveaux;
											
											$redoublants=$panier->effectifStatutClasse("admis",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0];
											
											$cumulfille+=$fille;
											$cumulgarcon+=$garcon;
											$cumuleffectif+=$totaleff;
											$cumulanciens+=$anciens;
											$cumulnouveaux+=$nouveaux;
											$cumultotalins+=$totalins;
											$cumulredoublants+=$redoublants;

											if ($values->nom=="college" or $values->nom=="lycee") {
												$cumulgenseconfille+=$fille;
												$cumulgensecongarcon+=$garcon;
												$cumulgenseconeffectif+=$totaleff;
												$cumulgenseconanciens+=$anciens;
												$cumulgenseconnouveaux+=$nouveaux;
												$cumulgensecontotalins+=$totalins;
												$cumulgenseconredoublants+=$redoublants;
											}else{
												$cumulgenseconfille+=0;
												$cumulgensecongarcon+=0;
												$cumulgenseconeffectif+=0;
												$cumulgenseconanciens+=0;
												$cumulgenseconnouveaux+=0;
												$cumulgensecontotalins+=0;
												$cumulgenseconredoublants+=0;
												$cumulgenseconpourcentage+=0;
											}

											$cumulgenfille+=$fille;
											$cumulgengarcon+=$garcon;
											$cumulgeneffectif+=$totaleff;
											$cumulgenanciens+=$anciens;
											$cumulgennouveaux+=$nouveaux;
											$cumulgentotalins+=$totalins;
											$cumulgenredoublants+=$redoublants;

											if (!empty($totalins)) {
												$percentNouveau=(($panier->effectifInscritClasse("inscription",$formation->codef,$formation->nomgr,$_SESSION['promo'])[0])/$totalins)*100;?><?php 
											}else{
												$percentNouveau=0;
											}
											$cumulpourcentage+=$percentNouveau;?>

											<tr>												
												<td><?=$count;?></td>
												<td><?=$key+1;?></td>
												<td><?=$formation->nomgr;?> <?=' '.ucfirst($formation->niveau).' ';?></td>
												<td><?=$fille;?></td>
												<td><?=$garcon;?></td>
												<td class="bg-success bg-opacity-50"><?=$totaleff;?></td>

												<td><?=$anciens;?></td>
												<td><?=$nouveaux;?></td>
												<td class="bg-info bg-opacity-50"><?=$totalins;?></td>

												<td><?=$redoublants;?></td>
												<td><?=number_format($percentNouveau,2,',',' ');?></td>

												<td><a class="btn btn-info" href="formation.php?voir_elg=<?=$formation->nomgr;?>">Consulter</a></td>
												<td><a class="btn btn-info" target="_blank" href="carte_scolaire.php?voircarte=<?=$formation->nomgr;?>"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></td>
												<td><a class="btn btn-info" target="_blank" href="carte_scolaire1.php?voircarte=<?=$formation->nomgr;?>"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></td>

												<td><?php 

													if ($_SESSION['type']=='admin' or $_SESSION['type']=='bibliothecaire') {?>
														<a class="btn btn-warning"  href="groupe.php?modif_f&nomgr=<?=$formation->nomgr;?>">Modifier</a><?php
													};?>
												</td>

												<td><?php 

													if ($_SESSION['type']=='admin' or $_SESSION['type']=='informaticien' or $_SESSION['type']=='bibliothecaire' or $_SESSION['type']=='proviseur' or $_SESSION['type']=='DE/Censeur' or $_SESSION['type']=='Directeur du primaire') {?>
														<a class="btn btn-warning" href="groupe.php?dupliq_f&nomgr=<?=$formation->nomgr;?>&niveau=<?=$formation->niveau;?>&codef=<?=$formation->codef;?>" onclick="return alerteV();">Reporter</a><?php
													};?>
												</td>

												<td><?php

													if ($_SESSION['type']=='admin') {?>
														
														<a class="btn btn-danger" href="groupe.php?del_gr=<?=$formation->id;?>" onclick="return alerteS();">Supprimer</a><?php
													}?>
												</td>

											</tr><?php
											$count++;

										}?>

										<tr>
											<th colspan="3">Total <?=ucwords($values->nom);?></th>
											<th><?=$cumulfille;?></th>
											<th><?=$cumulgarcon;?></th>
											<th><?=$cumulfille+$cumulgarcon;?></th>
											<th><?=$cumulanciens;?></th>
											<th><?=$cumulnouveaux;?></th>
											<th><?=$cumulanciens+$cumulnouveaux;?></th>
											<th><?=$cumulredoublants;?></th><?php 
											if (empty(($cumulanciens+$cumulnouveaux))) {?>
												<th>0,00</th><?php 
											}else{?>
												<th><?=number_format(($cumulnouveaux/($cumulanciens+$cumulnouveaux))*100,2,',',' ');?></th><?php 

											}?>
										</tr><?php
									}?>
								</tbody><?php
							}
						}?>
						<tfoot class="fs-8">
							<tr class="bg-warning bg-opacity-50">
								<th colspan="3">Total Secondaire (Collège + Lycée)</th>
								<th><?=$cumulgenseconfille;?></th>
								<th><?=$cumulgensecongarcon;?></th>
								<th><?=$cumulgenseconfille+$cumulgensecongarcon;?></th>
								<th><?=$cumulgenseconanciens;?></th>
								<th><?=$cumulgenseconnouveaux;?></th>
								<th><?=$cumulgenseconanciens+$cumulgenseconnouveaux;?></th>
								<th><?=$cumulgenseconredoublants;?></th>
								<th><?=number_format(($cumulgenseconnouveaux/($cumulgenseconanciens+$cumulgenseconnouveaux))*100,2,',',' ');?></th>
							</tr>

							<tr class="bg-success bg-opacity-50">
								<th colspan="3">Total Général</th>
								<th><?=$cumulgenfille;?></th>
								<th><?=$cumulgengarcon;?></th>
								<th><?=$cumulgenfille+$cumulgengarcon;?></th>
								<th><?=$cumulgenanciens;?></th>
								<th><?=$cumulgennouveaux;?></th>
								<th><?=$cumulgenanciens+$cumulgennouveaux;?></th>
								<th><?=$cumulgenredoublants;?></th>
								<th><?=number_format(($cumulgennouveaux/($cumulgenanciens+$cumulgennouveaux))*100,2,',',' ');?></th>
							</tr>
						</tfoot>
					</table><?php
				}
			}
		}?>
	</div>
</div>


<script type="text/javascript">
    function alerteS(){
        return(confirm('Attention la suppression de ce groupe entrainera la suppression des élèves, enseignements, devoir, note bref tout ce qui est en lien à ce groupe'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation'));
    }

    function focus(){
        document.getElementById('pointeur').focus();
    }

</script>