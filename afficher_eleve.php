<div>

	<div><?php

		//Modification eleve

		if (isset($_GET['modif_eleve'])) {

			$prodel=$DB->querys('SELECT eleve.matricule as matricule, nomel, prenomel, naissance, sexe, pere, mere, telpere, telmere, profp, profm, origine, proft, nomtut, teltut,  pays, nationnalite, adresse, phone, email from eleve left join contact on eleve.matricule=contact.matricule left join tuteur on tuteur.matricule=eleve.matricule where eleve.matricule=:mat', array('mat'=>$_GET['modif_eleve']));

			$prodins=$DB->querys('SELECT formation.codef as codef, nomf, classe, nomgr, remise from formation inner join inscription on inscription.codef=formation.codef where matricule=:mat and annee=:promo', array('mat'=>$_GET['modif_eleve'], 'promo'=>$_SESSION['promo']));

			$prodpaie=$DB->querys('SELECT * from payement where matricule=:mat and promo=:promo', array('mat'=>$_GET['modif_eleve'], 'promo'=>$_SESSION['promo']));

			$prodlogin=$DB->querys('SELECT * from login where matricule=:mat', array('mat'=>$_GET['modif_eleve']));

			$prodf=$DB->query('SELECT codef, nomf, classe from formation');

			$prodgroupe=$DB->query('SELECT nomgr from groupe where promo=:promo', array('promo'=>$_SESSION['promo']));?>

			<div>
							
			    <form id="formulaire" method="POST" action="ajout_eleve.php">

			    	<fieldset><legend>Modifier un élève</legend>
			    		<ol>
			    			<li><label>Justificatifs</label>
			                	<input type="file" name="just[]"multiple id="photo" />
			                	<input type="hidden" value="b" name="env"/>
			              	</li>

			    			<li>
							    <label>Matricule</label>
							    <input type="text" name="matrichange" value="<?=$prodel['matricule'];?>"/>
							</li>

							<li>
							    <label>Nom</label>
							    <input type="text" name="nom" value="<?=$prodel['nomel'];?>"/>
							    <input type="hidden" name="mat" value="<?=$prodel['matricule'];?>"/>
							</li>

							<li>
								<label>Prénom</label>
								<input type="text" name="prenom" value="<?=$prodel['prenomel'];?>"/>
								   
						  	</li>

						  	<li>
								<label>Sexe</label>
								<select type="text" name="sexe">
									<option value="<?=$prodel['sexe'];?>"><?=$prodel['sexe'];?></option>
									<option value="m">M</option>
									<option value="f">F</option>
								</select>
								    
						  	</li>

							<li>
								<label>Né le</label>
								<input type="date" name="daten" value="<?=$prodel['naissance'];?>"/>
								    
						  	</li>


						  	<li>
								<label>Lieu de naissance</label>
								<input type="adde" name="adr" value="<?=$prodel['adresse'];?>"/>
								    
							</li>

						  	<li>
								<label>Nom du père </label>
								<input type="text" name="nomp" value="<?=$prodel['pere'];?>"/>
								   
							</li>

							<li>
								<label>Téléphone du père</label>
								<input type="text" name="telp" value="<?=$prodel['telpere'];?>"/>
							</li>

							<li>
								<label>Profession du Père</label>
								<input type="text" name="profp" value="<?=$prodel['profp'];?>">
							</li>

							<li>
								<label>Nom de la mère </label>
								<input type="text" name="nomm" value="<?=$prodel['mere'];?>"/>
								    
						  	</li>

						  	<li>
								<label>Téléphone de la mère</label>
								<input type="text" name="telm" value="<?=$prodel['telmere'];?>"/>
							</li>

							<li>
								<label>Profession de la mère</label>
								<input type="text" name="profm" value="<?=$prodel['profm'];?>">
							</li>

							<li>
								<label>Tuteur </label>
								<input type="text" name="tut" value="<?=$prodel['nomtut'];?>"/>
								    
						  	</li>

						  	<li>
								<label>Téléphone du Tuteur</label>
								<input type="text" name="telt" value="<?=$prodel['teltut'];?>"/>
							</li>

							<li>
								<label>Profession du tuteur</label>
								<input type="text" name="proft" value="<?=$prodel['proft'];?>">
							</li>


						  	<li>
								<label>Pays</label>
								<input type="text" name="pays" value="<?=$prodel['pays'];?>"/>
								    
							</li>

						  	<li>

								<label>Nationnalite</label>
								<input type="text" name="nation" value="<?=$prodel['nationnalite'];?>"/>
								    
						  	</li>

							<li>
								<label>Téléphone</label>
								<input type="text" name="tel"  value="<?=$prodel['phone'];?>"/>
							</li>

							<li>   
								<label>Mail</label>
								<input type="email" name="email" value="<?=$prodel['email'];?>"/>
							</li>

							<li>
								<label>Pseudo</label>
								<input type="text" name="pseudo"  value="<?=$prodlogin['pseudo'];?>"/>
							</li>

							<li>   
								<label>mot de Passe</label>
								<input type="text" name="mdp" value="<?=strtolower($prodlogin['matricule']);?>"/>
							</li>
					  	</ol>
					</fieldset>

				    <fieldset><legend>Inscription</legend>

				    	<ol><?php 
							if ($_SESSION['type']!="bibliothecaire") {?>
								<li>
									<label>Frais d'inscription</label>
									<input style="font-size:25px;" type="text" name="mp" value="<?=$prodpaie['montant'];?>"  required=""/>
								</li>

								<li>
									<label>Remise Inscription</label><input type="text" name="remiseins" value="<?=$prodpaie['remise'];?>"  required="">
										
								</li>
								

								<li>
									<label>Remise Scolarité</label><input type="text" name="remisem" value="<?=$prodins['remise'];?>">
									
								</li><?php 
							}else{?>
								
								<input style="font-size:25px;" type="hidden" name="mp" value="<?=$prodpaie['montant'];?>"  required=""/>
								<input type="hidden" name="remiseins" value="<?=$prodpaie['remise'];?>"  required="">
								<input type="hidden" name="remisem" value="<?=$prodins['remise'];?>"><?php 

							}?>
							<li>
								<label>Classe</label>
								<select type="text" name="group">
							    	<option value="<?=$prodins['nomgr'];?>"><?=$prodins['nomgr'];?></option><?php
							    	
							    	foreach ($prodgroupe as $form) {?>

										<option value="<?=$form->nomgr;?>"><?=$form->nomgr;?></option><?php

							    	}?>
							    </select>
								<input type="hidden" name="codef" value="<?=$prodins['codef'];?>">
							</li>

								
						</ol>

					</fieldset>

					<fieldset>
						<input type="submit" value="Modifier" name="modifel" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/>
					</fieldset>

				</form>
			</div><?php
		}?>

		<div class="col"><?php

		    if(isset($_POST['modifel'])){

				if($_POST['nom']!="" and $_POST['prenom']!="" and $_POST['daten']!="" and $_POST['codef']!=""){
					
					$nom=addslashes(Htmlspecialchars($_POST['nom']));
					$prenom=addslashes(Htmlspecialchars($_POST['prenom']));
					$daten=addslashes(Htmlspecialchars($_POST['daten']));
					$sexe=addslashes(Htmlspecialchars($_POST['sexe']));
					$nomp=addslashes(Htmlspecialchars($_POST['nomp']));
					$nomm=addslashes(Htmlspecialchars($_POST['nomm']));
					$phone=addslashes(Htmlspecialchars($_POST['tel']));
					$adresse=addslashes(Nl2br(Htmlspecialchars($_POST['adr'])));
					$email=addslashes(Nl2br(Htmlspecialchars($_POST['email'])));
					$pays=addslashes(Nl2br(Htmlspecialchars($_POST['pays'])));
					$nation=addslashes(Nl2br(Htmlspecialchars($_POST['nation'])));
					$mat=addslashes(Nl2br(Htmlspecialchars($_POST['mat'])));
					$telp=addslashes(Nl2br(Htmlspecialchars($_POST['telp'])));
					$telm=addslashes(Nl2br(Htmlspecialchars($_POST['telm'])));
					$tuteur=addslashes(Nl2br(Htmlspecialchars($_POST['tut'])));
					$telt=addslashes(Nl2br(Htmlspecialchars($_POST['telt'])));
					$remise=addslashes(Nl2br(Htmlspecialchars($_POST['remisem'])));
					$remiseins=addslashes(Nl2br(Htmlspecialchars($_POST['remiseins'])));
					$montant=$_POST['mp']*(1-($remiseins/100));

					//$origine=$_POST['origine'];
					$profm=$_POST['profm'];
					$profp=$_POST['profp'];
					$proft=$_POST['proft'];

					$matrichange=addslashes(Nl2br(Htmlspecialchars($_POST['matrichange'])));
					$groupe=$_POST['group'];
					$codef=$panier->classeInfos($groupe,$_SESSION['promo'])[0];
					$niveau=$panier->classeInfos($groupe,$_SESSION['promo'])[2];					

					$pseudo=addslashes(Nl2br(Htmlspecialchars($_POST['pseudo'])));

					$mdp=password_hash($_POST['mdp'], PASSWORD_DEFAULT);

					$DB->insert('UPDATE eleve SET eleve.matricule=?, nomel= ?, prenomel=?, naissance=?, sexe=?, pere=?, mere=?, telpere=?, telmere=?, profp=?, profm=?, pays=?, nationnalite=?, adresse=? WHERE eleve.matricule = ?', array($matrichange, $nom, $prenom, $daten, $sexe, $nomp, $nomm, $telp, $telm, $profp, $profm, $pays, $nation, $adresse, $mat));


					$DB->insert('UPDATE contact SET contact.matricule=?, phone=?, email=? WHERE contact.matricule=? ', array($matrichange, $phone , strtolower($email), $mat));

					$DB->insert('UPDATE login SET pseudo=?, mdp=? WHERE matricule=?', array($pseudo, $mdp, $mat));

					$DB->insert('UPDATE login SET pseudo=?, mdp=? WHERE matricule=?', array('tut'.$pseudo, $mdp, 'tut'.$mat));

					$DB->insert('UPDATE inscription SET inscription.matricule=?, codef=?, nomgr=?, remise=?, niveau=? WHERE inscription.matricule=? and annee= ?', array($matrichange, $codef, $groupe, $remise, $niveau, $mat,  $_SESSION['promo']));

					$DB->insert('UPDATE login SET login.matricule=? WHERE matricule=?', array($matrichange, $mat));

					$DB->insert('UPDATE  tuteur SET  tuteur.matricule=?, nomtut=?, teltut=?, proft=?  WHERE tuteur.matricule=? ', array($matrichange, $tuteur, $telt, $proft, $mat));

					$DB->insert('UPDATE payement SET montant=?, remise=? WHERE matricule = ?', array($montant, $remiseins, $mat));


					$DB->insert('UPDATE payement SET matricule=? WHERE matricule = ?', array($matrichange, $mat));

					$DB->insert('UPDATE payementfraiscol SET matricule=? WHERE matricule = ?', array($matrichange, $mat));

					$DB->insert('UPDATE histopayefrais SET matricule=? WHERE matricule = ?', array($matrichange, $mat));

					$DB->insert('UPDATE banque SET matriculeb=? WHERE matriculeb = ?', array($matrichange, $mat));

					$DB->insert('UPDATE note SET matricule=? WHERE matricule = ?', array($matrichange, $mat));

					$DB->insert('UPDATE absence SET matricule=?, nomgr=? WHERE matricule = ?', array($matrichange, $groupe, $mat));

					$DB->insert('UPDATE justabsence SET matricule=? WHERE matricule = ?', array($matrichange, $mat));

					$DB->insert('UPDATE retard SET matricule=?, nomgr=? WHERE matricule = ?', array($matrichange, $groupe, $mat));

					$DB->insert('UPDATE justretard SET matricule=? WHERE matricule = ?', array($matrichange, $mat));

					$DB->insert('UPDATE exclus SET matricule=?, nomgr=? WHERE matricule = ?', array($matrichange, $groupe, $mat));

					if(isset($_POST["env"])){

						$initiale='';

						$matricule=$matrichange;

			            require "uploadpdf.php";
			        }?>	

					<div class="alerteV">Etudiant modifié avec succée!!!</div><?php

				}else{?>	

					<div class="alertes">Remplissez les champs vides</div><?php
				}
			}?>
		</div><?php

			//fin modification

		if (isset($_GET['del_eleve'])) {

	      $DB->delete('DELETE FROM eleve WHERE matricule = ?', array($_GET['del_eleve']));

	      $DB->delete('DELETE FROM login WHERE matricule = ?', array($_GET['del_eleve']));
	      
	      $DB->delete('DELETE FROM login WHERE matricule = ?', array(('tut'.$_GET['del_eleve'])));

	      $DB->delete('DELETE FROM contact WHERE matricule = ?', array($_GET['del_eleve']));

	      $DB->delete('DELETE FROM inscription WHERE matricule = ?', array($_GET['del_eleve']));

	      $DB->delete('DELETE FROM payement WHERE matricule = ?', array($_GET['del_eleve']));

	      $DB->delete('DELETE FROM payementfraiscol WHERE matricule = ?', array($_GET['del_eleve']));

	      $DB->delete('DELETE FROM histopayefrais WHERE matricule = ?', array($_GET['del_eleve']));

	      $DB->delete('DELETE FROM note WHERE matricule = ?', array($_GET['del_eleve']));

	      $DB->delete('DELETE FROM tuteur WHERE matricule = ?', array($_GET['del_eleve']));

	      $DB->delete('DELETE FROM banque WHERE matriculeb = ? and libelles=?', array($_GET['del_eleve'], 'paiement frais inscription'));

	      	$filename="img/".$_GET['del_eleve'].'.jpg';

	    	if (file_exists($filename)) {

	        	unlink ($filename);//pour supprimer la photo
	        }?>

	      <div class="alerteV">Suppression reussie!!!</div><?php 

	    }?>
	</div>
</div><?php 

if (!isset($_GET['modif_eleve'])) {

    require 'pagination.php';

    if (isset($_GET['termec']) and empty($_SESSION['searchreinscript'])) {
      $_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
      $terme = $_GET['termec'];
      $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
      $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
      $terme = strtolower($terme);

      $prodeleve =$DB->query('SELECT nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%Y\')AS naissance, inscription.matricule as matricule, phone, nomgr, etatscol from eleve inner join inscription on eleve.matricule=inscription.matricule left join contact on inscription.matricule=contact.matricule WHERE annee LIKE ? and (eleve.matricule LIKE ? or nomel LIKE ? or prenomel LIKE ? or phone LIKE ?) LIMIT '.$depart.','.$nbreparpage,array($_SESSION['promo'],"%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
      
    }elseif (isset($_GET['inscrip'])) {

        $mat=$_GET['inscrip'];

		$prodeleve=$DB->query('SELECT nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%Y\')AS naissance, inscription.matricule as matricule, phone, nomgr, etatscol from eleve inner join inscription on eleve.matricule=inscription.matricule left join contact on inscription.matricule=contact.matricule where matricule=:mat LIMIT '.$depart.','.$nbreparpage, array('mat'=>$mat));
		
	}elseif (isset($_POST['ajoutel'])) {

		$mat=$_SESSION['matricule'];

		$prodeleve=$DB->query('SELECT nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%Y\')AS naissance, inscription.matricule as matricule, phone, nomgr, etatscol from eleve inner join inscription on eleve.matricule=inscription.matricule left join contact on inscription.matricule=contact.matricule where inscription.matricule=:mat LIMIT '.$depart.','.$nbreparpage, array('mat'=>$mat));
		
	}elseif (isset($_GET['listelsear']) or !empty($_SESSION['searchreinscript'])) {

		$_SESSION['searchreinscript']='en-cours';

		if (isset($_GET['termec'])) {
	      $_GET["termec"] = htmlspecialchars($_GET["termec"]); //pour sécuriser le formulaire contre les failles html
	      $terme = $_GET['termec'];
	      $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
	      $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
	      $terme = strtolower($terme);

	      $prodeleve =$DB->query('SELECT inscription.matricule as matricule,nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%Y\')AS naissance, phone, nomgr, etatscol from eleve inner join inscription on eleve.matricule=inscription.matricule left join contact on inscription.matricule=contact.matricule WHERE annee LIKE ? AND (eleve.matricule LIKE ? or nomel LIKE ? or prenomel LIKE ? or phone LIKE ?) ',array(($_SESSION['promo']-1),"%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
	      
	    }else{

			$prodeleve=array();
		}

	}else{

		if (!empty($_SESSION['niveauf'])) {

    		$prodeleve=$DB->query('SELECT nomel, prenomel, adresse, sexe, pere, mere, date_format(naissance,\'%d/%m/%Y \') AS naissance, inscription.matricule as matricule, phone, nomgr, etatscol from eleve inner join inscription on eleve.matricule=inscription.matricule left join contact on inscription.matricule=contact.matricule where annee=:promo and niveau=:niv order by (prenomel) LIMIT '.$depart.','.$nbreparpage, array('promo'=>$_SESSION['promo'], 'niv'=>$_SESSION['niveauf']));

    	}else{

    		$prodeleve=$DB->query('SELECT nomel, prenomel, adresse, sexe, pere, mere, date_format(naissance,\'%d/%m/%Y \') AS naissance, inscription.matricule as matricule, phone, nomgr, etatscol from eleve inner join inscription on eleve.matricule=inscription.matricule left join contact on inscription.matricule=contact.matricule where annee=:promo order by (prenomel) LIMIT '.$depart.','.$nbreparpage, array('promo'=>$_SESSION['promo']));
    	}

		
	}?>

	<div style="overflow: auto;">
			
		<table class="payement" style="width:100%;">
			<thead>

				<form method="GET" action="ajout_eleve.php" id="suitec" name="termc">

					<tr>
		            	<th colspan="7" class="info" style="text-align: center">Liste des <?=$_SESSION['typeel'];?> <?=ucwords($_SESSION['niveaufl']);?> 
		            		<a style="margin-left: 10px;"href="printdoc.php?listel" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

		            		<a style="margin-left: 10px;"href="csv.php?listel" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
		            	</th>
		            </tr>

		            <tr>
		            	<th colspan="4">
		            		<div class="container">
		            			<div class="row">
		            				<div class="col">
		            					<input class="form-control me-2 text-center" type = "search" name = "termec" placeholder="rechercher !!!!" onchange="document.getElementById('suitec').submit()">
		            				</div>

		            				<div class="col">
		            					<input style="width: 105px;" class="form-control"  type = "submit" name = "s" value = "search">
		            				</div>
		            			</div>
		            		</div>
		            	</th>

		            	<th colspan="3"><a style="color: white;" href="ajout_eleve.php?ajoute">Ajouter un élève</a></th>
		          	</tr>

				</form>

				<tr>
					<th height="25">N°</th>
					<th>Prénom & Nom</th>
					<th>Né(e)</th>
					<th>Téléphone</th>
					<th>Classe</th>
					<th colspan="2">Actions</th>
				</tr>
			</thead>
			<tbody><?php
			if (empty($prodeleve)) {
				
			}else{

				foreach ($prodeleve as $eleve) {
					if ($eleve->etatscol=='actif') {
					 	$color='';
					}else{
						$color='red';
					} ?>

					<tr><?php
						if (isset($_GET['listelsear'])or !empty($_SESSION['searchreinscript'])) {?>

							<td style="text-align: center; color:<?=$color;?> "><a href="inscription.php?searchel=<?=$eleve->matricule;?>"><?=$eleve->matricule;?></a></td><?php

						}elseif (isset($_GET['cherceleve']) or isset($_GET['termec'])) {?>

							<td style="text-align: center; color:<?=$color;?>"><a href="comptabilite.php?eleve=<?=$eleve->matricule;?>"><?=$eleve->matricule;?></a></td><?php

						}elseif (isset($_GET['livrel']) or isset($_GET['termec'])) {?>

							<td style="text-align: center; color:<?=$color;?>"><a href="emprunterlivre.php?eleve&payecherc=<?=$eleve->matricule;?>"><?=$eleve->matricule;?></a></td><?php
						}else{?>

							<td style="text-align: center; color:<?=$color;?>"><?=$eleve->matricule;?></td><?php

						}?>
						

					  	<td style="color:<?=$color;?>"><?=ucwords(strtolower($eleve->prenomel)).' '.strtoupper($eleve->nomel);?></td>

					  	<td style=" text-align: center; color:<?=$color;?>"><?=$eleve->naissance;?></td>

					  	<td style="color:<?=$color;?>"><?=$eleve->phone;?></td>

					  	<td style="text-align: center; color:<?=$color;?>; width: 5%;"><?=$eleve->nomgr;?></td>

					  	<td>
					  		<a href="fiche_elevegen.php?fiche_eleve=<?=$eleve->matricule;?>&promo=<?=$_SESSION['promo'];?>" class="btn btn-info">+Infos</a>
					  	</td>

					  	<td><?php

				  			if ($products['type']=='admin' or $products['type']=='comptable'  or $products['type']=='bibliothecaire') {?>

						  		<a class="btn btn-warning m-auto" href="ajout_eleve.php?modif_eleve=<?=$eleve->matricule;?>">Modifier</a><?php 
						  	}?>
						</td><?php 
						/*

						<td><?php

						  	if ($products['type']=='admin' or $products['type']=='informaticien') {?>

						  		<a href="ajout_eleve.php?del_eleve=<?=$eleve->matricule;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style=" font-size: 16px; background-color: red; color: white; cursor: pointer"></a><?php
						  	}?>
					  	</td>
					  	*/;?>

					</tr><?php
				}
			}?>
			</tbody>

			<tfoot>
				<tr>
					<th height="25" colspan="7"><?=number_format($nbreeleve,0,',',' '). $_SESSION['typeel'].' inscrits année-scolaire ';?><?=($_SESSION['promo']-1).' - '.$_SESSION['promo'];?>
						
					</th>
				</tr>
			</tfoot>
		</table>
	</div><?php 
}