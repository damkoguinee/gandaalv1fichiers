<?php
require 'headerv2.php'
?>

	<div class="container-fluid">
        <div class="row"><?php

            require 'navnote.php';?>

            <div class="col-sm-12 col-md-10" style="overflow: auto;"><?php 

		
	
				if (isset($_GET['deleteEvent'])) {

			        $DB->delete('DELETE FROM events WHERE id = ?', array($_GET['deleteEvent']));
			        header("Location: planing.php");
			    }

			    if (isset($_GET['deleteEventout'])) {

			        $DB->delete('DELETE FROM events WHERE codemp = ? and nomgrp=? and codensp=? and DATE_FORMAT(debut, \'%H:%i\')=? and DATE_FORMAT(fin, \'%H:%i\')=? and promo=?', array($_GET['codem'], $_GET['nomgr'], $_GET['ense'], $_GET['hdebut'], $_GET['hfin'], $_SESSION['promo']));
			        
			        header("Location: planing.php");
			    }

				if (isset($_POST['validevent'])) {
					$codef=$panier->h($_POST['codef']);
					$nomm=$_POST['nomm'];
					$nomg=$_POST['nomg'];
					$prof=$panier->h($_POST['prof']);
					$titre=$panier->h($_POST['titre']);
					$debut=$_POST['datee'].' '.$_POST['hdebut'];
					$hdebut=$_POST['hdebut'];
					$hfin=$_POST['hfin'];
					$fin=$_POST['datee'].' '.$_POST['hfin'];
					$lieu=$panier->h($_POST['lieu']);
					$promo=$panier->h($_POST['promo']);

					$debutperiod=(new DateTime($debut))->format('Y-m-d');

					$DB->delete('DELETE FROM events WHERE codemp = ? and nomgrp=? and codensp=? and DATE_FORMAT(debut, \'%H:%i\')=? and DATE_FORMAT(fin, \'%H:%i\')=? and promo=?', array($nomm, $nomg, $prof, $hdebut, $hfin, $_SESSION['promo']));

					$i=0;

					if ($_POST['periode']=='periodique') {
						while ($i<(300)) {
							$periode=date('Y-m-d', strtotime($debutperiod. '+'.$i.' days'));

							$debut=$periode.' '.$_POST['hdebut'];
							$fin=$periode.' '.$_POST['hfin'];
							$i+=6;

							$DB->insert("INSERT INTO events (codefp, codemp, nomgrp, codensp, name, debut, fin, lieu, promo) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)",array($codef, $nomm, $nomg, $prof, $titre, $debut, $fin, $lieu, $promo));

							$i++;
						}
					}else{

						$DB->insert("INSERT INTO events (codefp, codemp, nomgrp, codensp, name, debut, fin, promo) VALUES(?, ?, ?, ?, ?, ?, ?, ?)",array($codef, $nomm, $nomg, $prof, $titre, $debut, $fin, $promo));
					}
					/*

					if ($_POST['periode']=='periodique') {
						while ($i<(300)) {
							$periode=date('Y-m-d', strtotime($debutperiod. '+'.$i.' days'));

							$debut=$periode.' '.$_POST['hdebut'];
							$fin=$periode.' '.$_POST['hfin'];
							$i+=6;

							$DB->insert("UPDATE events SET codefp=?, codemp=?, nomgrp=?, codensp=?, name=?, debut=?, fin=?, lieu=?, promo=? WHERE id=?",array($codef, $nomm, $nomg, $prof, $titre, $debut, $fin, $lieu, $promo, $_POST['id']));

							$i++;
						}
					}else{

						$DB->insert("UPDATE events SET codefp=?, codemp=?, nomgrp=?, codensp=?, name=?, debut=?, fin=?, lieu=?, promo=? WHERE id=?",array($codef, $nomm, $nomg, $prof, $titre, $debut, $fin, $lieu, $promo, $_POST['id']));
					}
					*/
					

					header("Location: planing.php");
				}

				if (isset($_POST['ajoutevent'])) {

					if($_POST['nomg']!="" and $_POST['nomm']!="" and $_POST['prof']!="" and $_POST['codef']!="" and $_POST['titre']!="" and $_POST['datee']!=""  and $_POST['hfin']!=""){

						$codef=$panier->h($_POST['codef']);
						$nomm=$_POST['nomm'];
						$nomg=$_POST['nomg'];
						$prof=$panier->h($_POST['prof']);
						$titre=$panier->h($_POST['titre']);
						$debut=$_POST['datee'].' '.$_POST['hdebut'];

						$fin=$_POST['datee'].' '.$_POST['hfin'];
						$lieu=$panier->h($_POST['lieu']);
						$promo=$panier->h($_POST['promo']);

						$prodprofverif=$DB->querys('SELECT * from events where codensp=:code and debut=:debut and promo=:promo', array('code'=>$prof, 'debut'=>$debut, 'promo'=>$promo));

						$prodprofverif=array();

						$debutperiod=(new DateTime($debut))->format('Y-m-d');

						if (empty($prodprofverif)) {

							$i=0;

							if ($_POST['periode']=='periodique') {
								while ($i<(300)) {
									$periode=date('Y-m-d', strtotime($debutperiod. '+'.$i.' days'));

									$debut=$periode.' '.$_POST['hdebut'];
									$fin=$periode.' '.$_POST['hfin'];
									$i+=6;

									$DB->insert("INSERT INTO events (codefp, codemp, nomgrp, codensp, name, debut, fin, lieu, promo) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)",array($codef, $nomm, $nomg, $prof, $titre, $debut, $fin, $lieu, $promo));

									$i++;
								}
							}else{

								$DB->insert("INSERT INTO events (codefp, codemp, nomgrp, codensp, name, debut, fin, promo) VALUES(?, ?, ?, ?, ?, ?, ?, ?)",array($codef, $nomm, $nomg, $prof, $titre, $debut, $fin, $promo));
							}

							//header("Location: planing.php");
						}else{?>

							<div class="alert alert-warning">Ce Cours est déjà planifié <a href="planing.php">Retour</a></div><?php
						}

						

					}else{?>
						<div class="alert alert-warning">Completer les champs vides</div><?php 
					}
				}

			if (isset($_GET['id'])) {

				$prodprof=$DB->query('SELECT * from enseignement inner join enseignant on matricule=codens inner join matiere on matiere.codem=enseignement.codem where codens=:code', array('code'=>$_GET['codens']));

				$event=$panier->find($_GET['id']);?>

				<form id="formulaire" method="POST">

					<fieldset><legend><?='Modification '.ucwords($panier->h($event['name']));?>
						<a href="event.php?deleteEvent=<?=$event['id'];?>" onclick="return alerteS();" style="margin-left: 30px; font-size: 20px; background-color: red; color: white; cursor: pointer">Supprimer</a>
						<a href="event.php?deleteEventout=<?=$event['id'];?>&codem=<?=$panier->h($event['codemp']);?>&nomgr=<?=$panier->h($event['nomgrp']);?>&ense=<?=$panier->h($event['codensp']);?>&hdebut=<?=(new DateTime($event['debut']))->format('H:i');?>&hfin=<?=(new DateTime($event['fin']))->format('H:i');?>" onclick="return alerteS();" style="margin-left: 30px; font-size: 20px; background-color: red; color: white; cursor: pointer">Tout Supprimer</a>
						</legend>

						<ol>

							<li>

								<label>Matières</label>
								<select type="text" name="nomm">
							    	<option value="<?=$panier->h($event['codemp']);?>"><?=ucwords($panier->h($event['codemp']));?></option><?php
								    foreach ($prodprof as $codef) {?>

				                        <option value="<?=$codef->codem;?>"><?=$codef->nommat.' '.$codef->codef;?></option><?php
				                        
								    }?>
								</select>
							</li>

							<li>

								<label>Classe</label>
								<select type="text" name="nomg">
							    	<option value="<?=$panier->h($event['nomgrp']);?>"><?=ucwords($panier->h($event['nomgrp']));?></option><?php
								    foreach ($prodprof as $codef) {?>

				                        <option value="<?=$codef->nomgr;?>"><?=$codef->nomgr;?></option><?php
				                        
								    }?>
								</select>

							</li>

							<li>

								<label>Enseignant</label>
								<select type="text" name="prof">
							    	<option value="<?=$panier->h($event['codensp']);?>"><?=ucwords($panier->h($event['codensp']));?></option><?php
								    foreach ($prodprof as $prof) {?>

								    	<option value="<?=$prof->matricule;?>"><?=ucfirst(strtolower($prof->prenomen)).' '.strtoupper($prof->nomen);?></option><?php

								    }?>
								</select>

							</li>

							<li><label>Titre:</label><input type="text" name="titre" value="<?=ucwords($panier->h($event['name']));?>"><input type="hidden" name="id" value="<?=$event['id'];?>"><input type="hidden" name="codef" value="<?=$event['codefp'];?>"/></li>

							<li><label>Date:</label><input type="date" name="datee" value="<?=(new DateTime($event['debut']))->format('Y-m-d');?>"></li>

							<li><label>Heure de démarrage:</label><input type="time" name="hdebut" value="<?=(new DateTime($event['debut']))->format('H:i');?>"><input type="hidden" name="ddebut" value="<?=(new DateTime($event['debut']))->format('Y-m-d');?>"></li>

							<li><label>Heure de fin:</label><input type="time" name="hfin" value="<?=(new DateTime($event['fin']))->format('H:i');?>"><input type="hidden" name="dfin" value="<?=(new DateTime($event['fin']))->format('Y-m-d');?>"></li>

							<li><label>Lieu:</label><input type="text" name="lieu" value="<?=$event['lieu'];?>"></li>

							<li><label>Fréquence</label><select type="text" name="periode" required="">
								<option></option>
								<option value="periodique">Périodique</option>
								<option value="jour defini">Jour défini</option></select>
							</li>

							<li><label>Année-Scolaire</label>

					            <select type="text" name="promo" required=""><?php
					              
						            $annee=date("Y")+1;

						            for($i=($_SESSION['promo']-1);$i<=$annee ;$i++){
						            	$j=$i+1;?>

						             	<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

						            }?>
						        </select>
					            
					        </li>
						</ol>
					</fieldset>

					<fieldset><input type="reset" value="Annuler" id="form" style="cursor: pointer;" /><input type="submit" value="Valider" name="validevent" id="form" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
				</form><?php
			}

			if (isset($_GET['ajoutEvent']) or isset($_GET['ajout_ens']) or isset($_POST['codef'])) {

				if (isset($_POST['codef'])) {

					$_SESSION['codefevent']=$_POST['codef'];

					$prodgroup=$DB->query("SELECT nomgr from groupe where promo='{$_SESSION['promo']}' and codef='{$_SESSION['codefevent']}' order by(codef)");
				}


				if (!empty($_SESSION['niveauf'])) {

		    		$form=$DB->query('SELECT codef, nomf, classe from formation where niveau=:niv', array('niv'=>$_SESSION['niveauf']));

		    		$prodprof=$DB->query("SELECT distinct(codens) as matricule from enseignement inner join enseignant on matricule=codens where codef='{$_SESSION['codefevent']}' and promo='{$_SESSION['promo']}' order by(prenomen)");

		    	}else{

		    		$form=$DB->query('SELECT codef, nomf, classe from formation');

		    		//$prodprof=$DB->query('SELECT nomen, prenomen, matricule from enseignant order by(prenomen)');

		    		$prodprof=$DB->query("SELECT distinct(codens) as matricule from enseignement inner join enseignant on matricule=codens where codef='{$_SESSION['codefevent']}' and promo='{$_SESSION['promo']}' order by(prenomen)");
		    	}

				if (isset($_POST['codef'])) {

					$prodmat=$DB->query('SELECT *from matiere where codef=:code', array('code'=>$_SESSION['codefevent']));

				}else{

					$prodmat=$DB->query('SELECT *from matiere ');
				}?>

				<form id="formulaire" method="POST">

					<fieldset>

						<ol>
							<li>

								<label>Code formation</label>
								<select type="text" name="codef" required="" class="form-control" onchange="this.form.submit()"><?php 

							    if (isset($_POST['codef'])) {?>

							    	<option><?=$_POST['codef'];?></option><?php

							    }else{?>

							    	<option></option><?php						    	
							    }

								    foreach ($form as $codef) {
								    	if ($codef->classe=='1') {?>

				                            <option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' ère année '.$codef->nomf;?></option><?php

										}elseif($codef->classe=='petite section' or $codef->classe=='moyenne section' or $codef->classe=='grande section' or $codef->classe=='terminale'){?>

				                            <option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' '.$codef->nomf;?></option><?php

										}else{?>

				                            <option value="<?=$codef->codef;?>"><?=' '.$codef->classe.' ème année '.$codef->nomf;?></option><?php
										}

								    }?>
								</select>

							</li>

							<li>

								<label>Matières</label>
								<select type="text" name="nomm" required="" class="form-control">
							    	<option></option><?php
								    foreach ($prodmat as $codef) {?>

				                        <option value="<?=$codef->codem;?>"><?=$codef->nommat;?></option><?php
				                        
								    }?>
								</select>

								<a href="matiere.php?ajout_m">Ajouter une matière</a>
							</li>

							<li>

								<label>Classe</label>
								<select type="text" name="nomg" required="" class="form-control">
							    	<option></option><?php
								    foreach ($prodgroup as $codef) {?>

				                        <option value="<?=$codef->nomgr;?>"><?=$codef->nomgr;?></option><?php
				                        
								    }?>
								</select>

							</li>

							<li>

								<label>Enseignant</label>
								<select type="text" name="prof" required="">
							    	<option></option><?php
								    foreach ($prodprof as $prof) {?>

								    	<option value="<?=$prof->matricule;?>"><?=$panier->nomEnseignant($prof->matricule);?></option><?php

								    }?>
								</select>

							    <a href="enseignant.php?ajout_en">Ajouter enseignant</a>

							</li>

							<li><label>Titre:</label><select type="text" name="titre" required="">
								<option value="cours">Cours</option>
								<option value="td">travaux dirigés</option>
								<option value="ds">Dévoir Surveillé</option></select>
							</li>

							<li><label>Date:</label><input type="date" name="datee" required=""></li>

							<li><label>Heure de début:</label><input type="time" name="hdebut" required=""></li>

							<li><label>Heure de fin:</label><input type="time" name="hfin" required=""></li>

							<li><label>Lieu:</label><input type="text" name="lieu" value="classe" ></li>

							<li><label>Fréquence</label><select type="text" name="periode">
								<option value="periodique">Périodique</option>
								<option value="jour defini">Jour défini</option></select>
							</li>

							<li><label>Année-Scolaire</label>

					            <select type="text" name="promo" required=""><?php
					              
						            $annee=date("Y")+1;

						            for($i=($_SESSION['promo']-1);$i<=$annee ;$i++){
						            	$j=$i+1;?>

						             	<option value="<?=$j;?>"><?=$i.'-'.$j;?></option><?php

						            }?>
						        </select>
					            
					        </li>
						</ol>
					</fieldset>

					<fieldset><input type="reset" value="Annuler" id="form" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajoutevent" id="form" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
				</form><?php
			}?>
		</div>
	</div>


</body>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer cette facture ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>