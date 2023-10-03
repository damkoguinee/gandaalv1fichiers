<?php
require 'headerv2.php';?>

<div class="container-fluid">
	<div class="row"><?php 

		if (!isset($_GET['devoir'])) {

			require 'navnote.php';
		}?><?php

		if (isset($_GET['ajout_dev']) or isset($_POST['ajoutedev'])) {
			if (isset($_GET['ajout_dev'])) {
				$classe=$_GET['classe'];
				$codef=$_GET['codef'];
				$_SESSION['classedev']=$classe;
				$_SESSION['codefdev']=$codef;
				$_SESSION['niveaudev']=$_GET['niveau'];
			}

			$prodtype=$DB->querys('SELECT type from cursus inner join repartition on repartition.codecursus=cursus.codecursus where nom=:code', array('code'=>$_SESSION['niveaudev']));
		
		

			$prodmat=$DB->query("SELECT *from matiere where codef='{$_SESSION['codefdev']}'");?>
		
			<div class="col-sm-12 col-md-10">
			<form class="form" method="POST" action="devoirgroupe.php">

			    <fieldset><legend>Ajouter une évaluation/intérro pour la <?=$_SESSION['classedev'];?> <a class="btn btn-warning" href="ajout_devoir.php?devoir"> Choisissez une Nouvelle Classe</a></legend>
			    	
					<div class="mb-1 d-flex ">
						<div>
							<input class="form-control" type="hidden" name="classe" value="<?=$_SESSION['classedev'];?>"/>
							<input class="form-control" type="hidden" name="codef" value="<?=$_SESSION['codefdev'];?>"/>
							<label class="form-label">Type devoir</label>
							<select class="form-select" type="number" name="type" required="">
								<option></option>
								<option value="note de cours">Note de cours</option>
								<option value="composition">Composition</option>
							</select>
						</div>


						<div class="mx-2">
							<label class="form-label">Trimestre</label>
							<select class="form-select"  name="trim" required="" required="">
								<option></option><?php 
								if ($prodtype['type']=='semestre') {?>

									<option value="1">1er Semestre</option>
									<option value="2">2ème Semestre</option><?php

								}else{?>
									<option value="1">1er Trimestre</option>
									<option value="2">2ème Trimestre</option>
									<option value="3">3ème Trimestre</option><?php

								
								}?>
							</select>
						</div>
					</div>

					<div class="mb-1">

						<label class="form-label">Matières</label>
						<select class="form-select" type="text" name="nomm[]" multiple required="" class="form-control">
							<option></option><?php
							foreach ($prodmat as $codef) {?>

								<option value="<?=$codef->codem;?>"><?=$codef->nommat;?></option><?php
								
							}?>
						</select>

						<a href="matiere.php?ajout_m">Ajouter une matière</a>
					</div>

					<div class="mb-1">

						<label class="form-label">Mois</label>
						<select class="form-select" type="text" name="datedev[]" multiple required="" class="form-control">
							<option></option>
							<option value="10">Octobre</option>
							<option value="11">Novembre</option>
							<option value="12">Decembre</option>
							<option value="01">Janvier</option>
							<option value="02">Février</option>
							<option value="03">Mars</option>
							<option value="04">Avril</option>
							<option value="05">Mai</option>
							<option value="06">Juin</option>
							<option value="07">Juillet</option>
						</select>

						<a href="matiere.php?ajout_m">Ajouter une matière</a>
					</div>

				</fieldset>

				<button class="btn btn-primary my-2" type="submit" name="ajoutedev" onclick="return alerteV();" >Ajouter</button>
			</form>
		</div><?php
	}?>

	<div><?php

		if(isset($_POST['ajoutedev'])){

			if($_POST['nomm']!="" and $_POST['type']!="" and $_POST['trim']!=""){
				
				$coef=1;
				$trim=addslashes(Htmlspecialchars($_POST['trim']));			
				$nomgr=addslashes(Htmlspecialchars($_POST['classe']));
				$datem=$_POST['datedev'];
				$type=addslashes(Htmlspecialchars($_POST['type']));
				
				if ($type=='composition') {
					$nomdev='composition';	
				}else{

					$nomdev='evaluation';
				}

				foreach ($datem as $datemois) {

					$datec=date("Y-").$datemois."-25";

					$datedev=$datec;

					$moischaine=$panier->obtenirLibelleMois($datemois);
					$moischaine=strtolower(substr($moischaine, 0, 3));

					$nomm=$_POST['nomm'];
				

					foreach ($nomm as $value) {						

						$prod=$DB->querys("SELECT *from enseignement where nomgr='{$nomgr}' and codem='{$value}' and promo='{$_SESSION['promo']}'");

						$codens=$prod['codens'];
						$nomm=$value;

						$nb=$DB->querys("SELECT *from devoir where datedev='{$datedev}' and type='{$type}' and codem='{$value}' and nomgroupe='{$nomgr}' and promo='{$_SESSION['promo']}'");
						if (!empty($codens)) {

							if(!empty($nb)){?>
								<div class="alertes">Ce devoir de <?=$panier->nomMatiere($nomm);?> existe déjà </div><?php

							}else{

								if ($type!='composition') {
									$nomdev='eval '.$moischaine;						

									$DB->insert('INSERT INTO devoir(codens, nomdev, type, coef, trimes, codem, nomgroupe, datedev, promo) values( ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($codens, $nomdev, $type, $coef, $trim, $nomm, $nomgr, $datedev, $_SESSION['promo']));
								}else{

									$nomdev='compo '.$moischaine;

									$DB->insert('INSERT INTO devoir(codens, nomdev, type, coefcom, trimes, codem, nomgroupe, datedev, promo) values( ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($codens, $nomdev, $type, $coef, $trim, $nomm, $nomgr, $datedev, $_SESSION['promo']));
								}?>	

								<div class="alerteV">Dévoir ajouté avec succèe!!!</div></br><?php
							}
						}else{?>	

							<div class="alertes">Crée le cours de <?=$panier->nomMatiere($nomm);?> avant de créer le devoir</div></br/><?php

						}


					}
				}

			}else{?>	

				<div class="alertes">Remplissez les champs vides</div><?php
			}
		}?>
	</div>
</div>