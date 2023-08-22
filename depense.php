<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{
    	//require 'navcompta.php';?>

    	<div class="container-fluid">

    		<div class="row"><?php

    			require 'navcompta.php';?>

    			<div class="col-sm-12 col-md-10" style="overflow: auto;"><?php

			    	if(isset($_POST["categins"])){

			          $cate=$_POST['cate'];

			          $proddep=$DB->query('SELECT nom FROM categoriedep WHERE nom= ?', array($cate));

			          if (empty($proddep)) {

			            $DB->insert('INSERT INTO categoriedep (nom) VALUES (?)', array($cate));

			          }else{?>

			            <div class="alert alert-warning">Cette catégorie existe déjà</div><?php

			          }
			        }

			        $prodep=$DB->query('SELECT id, nom FROM categoriedep');

			        if(isset($_GET["categ"])){?>

			          <form id="formulaire" method="POST" action="depense.php?ajout" style="margin-top: 30px;" >
			            <fieldset><legend>Ajouter une catégorie</legend>
			              <ol>

			                <li><label>Désignation</label>
			                    <input type="text" name="cate" required="">
			                </li>
			              </ol>
			            </fieldset>

			            <fieldset>

			              <input type="reset" value="Annuler" name="valid" id="form" style=" cursor: pointer;"/>

			              <input type="submit" value="Ajouter" name="categins" id="form" onclick="return alerteV();" style="margin-left: 20px; cursor: pointer;" />

			            </fieldset>
			          </form><?php
			        }

					if (isset($_GET['ajoutdep']) or isset($_GET['ajout_scol']) or isset($_POST['categins']) or isset($_GET["categ"])) {

						$prodep=$DB->query('SELECT id, nom FROM categoriedep');?>

						<form id="formulaire" method="POST" action="depense.php" enctype="multipart/form-data">

						    <fieldset><legend>Effectuez un décaissement</legend>
						    	<ol>
						    		<li><label>Catégorie de dépense</label>
			                            <select name="com" required="">
			                                <option></option><?php
			                                foreach ($prodep as $value) {?>

			                                  <option value="<?=$value->id;?>"><?=ucfirst($value->nom);?></option><?php 
			                                }?>
			                            </select>

			                            <a href="depense.php?categ">Ajouter une catégorie</a>
			                        </li>

						    		<li><label>Motif</label>
										<textarea type="text" name="motif" required="" maxlength="150"></textarea>
									</li>

									<li><label>Montant à décaisser</label>
										<input style="font-size: 25px;" type="text" name="montant" required="">
									</li>

									<li><label>Type de payement</label><select name="typep" required="" >
		                            <option value=""></option><?php 
		                            foreach ($panier->modep as $value) {?>
		                                <option value="<?=$value;?>"><?=$value;?></option><?php 
		                            }?></select></li>

		                            <li><label>N° Chèque/Bordereau</label>
										<input style="font-size: 25px;" type="text" name="bordereau">
									</li>

		                            <li><label>Compte à prélever</label>
										<select  name="compte" required="">
											<option></option><?php
	                                    	$type='Banque';

		                                    foreach($panier->nomBanque() as $product){?>

		                                        <option value="<?=$product->id;?>"><?=strtoupper($product->nomb);?></option><?php
		                                    }?>
		                                </select>
									</li>

									<li><label>Date dépense</label>
										<input type="date" name="datedep">
									</li>

									<li><label>Justificatifs</label>
					                	<input type="file" name="just[]"multiple id="photo" />
					                	<input type="hidden" value="b" name="env"/>
					              	</li>

								</ol>
							</fieldset><?php 

							if ($products['type']=='informaticien' or $products['type']=='comptable' or $products['type']=='admin') {?>

								<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajoutdep" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset><?php 
							}?>
						</form><?php
					}

					if(isset($_POST['ajoutdep'])){

						if ($_POST['montant']<0){?>

			                <div class="alert alert-warning">Format incorrect</div><?php

			            }elseif ($_POST['montant']>$panier->montantCompteT($_POST['compte'])) {?>

			                <div class="alert alert-warning">Echec montant decaissé est > au montant disponible</div><?php

			            }else{

							if($_POST['motif']!="" and $_POST['montant']!=""){
								
								$montant=addslashes(Htmlspecialchars($_POST['montant']));
								$com=addslashes(Htmlspecialchars($_POST['com']));
								$motif=addslashes(Htmlspecialchars($_POST['motif']));
								$typep=addslashes(Htmlspecialchars($_POST['typep']));
								$numcheque=addslashes(Htmlspecialchars($_POST['bordereau']));
								$datedep=addslashes(Htmlspecialchars($_POST['datedep']));

								$maxid = $DB->querys('SELECT max(id) as id FROM decaissement');
			                            
			            		$numdec=$maxid['id']+1;

			            		if(isset($_POST["env"])){

						              require "uploadep.php";
						        }

			            		if (empty($_POST['datedep'])) {

				                    $DB->insert('INSERT INTO decaissement(caisse, numdec, montant, coment, motif, typepaye, numcheque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())',array($_POST['compte'], $numdec, $montant, $motif, $com, $typep, $numcheque, $_SESSION['promo']));

				                    $DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, now())', array($_POST['compte'], -$montant, 'depense', 'depdep'.$numdec, 'vide', $_SESSION['promo']));
				                }else{

				                	 $DB->insert('INSERT INTO decaissement(caisse, numdec, montant, coment, motif, typepaye, numcheque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)',array($_POST['compte'], $numdec, $montant, $motif, $com, $typep, $numcheque, $_SESSION['promo'], $datedep));

				                    $DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?)', array($_POST['compte'], -$montant, 'depense', 'depdep'.$numdec, 'vide', $_SESSION['promo'], $datedep));

				                }?>

			                    <div class="alert alert-success">Dépense enregistrée avec succèe!!</div><?php

							}else{?>	

								<div class="alert alert-warning">Remplissez les champs vides</div><?php
							}
						}
					}


				    if (isset($_GET['dep']) or isset($_POST['j1']) or isset($_POST['com']) or isset($_POST['ajoutdep'])  or isset($_GET['deledep']) or isset($_GET['modifdep'])) {

				    	if (isset($_GET['deledep'])) {

			                $DB->delete('DELETE FROM decaissement WHERE numdec = ?', array($_GET['deledep']));

			                $DB->delete('DELETE FROM banque WHERE numero=?', array(('depdep'.$_GET['deledep'])));?>

			                <div class="alert alert-success">depense supprimée avec succèe</div><?php
			            }

			            if (!isset($_POST['j1'])) {

				          $_SESSION['date']=date("Y0101");  
				          $dates = $_SESSION['date'];
				          $dates = new DateTime( $dates );
				          $dates = $dates->format('Y0101'); 
				          $_SESSION['date']=$dates;
				          $_SESSION['date1']=$dates;
				          $_SESSION['date2']=date('Y1231'); ;
				          $_SESSION['dates1']=$dates;
				          $_SESSION['com']=''; 

				        }else{

				          $_SESSION['date01']=$_POST['j1'];
				          $_SESSION['date1'] = new DateTime($_SESSION['date01']);
				          $_SESSION['date1'] = $_SESSION['date1']->format('Ymd');
				          
				          $_SESSION['date02']=$_POST['j2'];
				          $_SESSION['date2'] = new DateTime($_SESSION['date02']);
				          $_SESSION['date2'] = $_SESSION['date2']->format('Ymd');

				          $_SESSION['dates1']=(new DateTime($_SESSION['date01']))->format('d/m/Y');
				          $_SESSION['dates2']=(new DateTime($_SESSION['date02']))->format('d/m/Y'); 

				          if (empty($_SESSION['com'])) {
							$_SESSION['com']='';
						  } else {
							$_SESSION['com']=$_POST['com']; 
							
						  }
				        }


				        if (isset($_POST['j2'])) {

				          $datenormale='entre le '.$_SESSION['dates1'].' et le '.$_SESSION['dates2'];

				        }else{

				          $datenormale='Liste des depenses ';
				        }

				        $promotion=$_SESSION['promo'];

				        if (isset($_POST['j1'])) {
				        	
				        	$prodm=$DB->query('SELECT id, motif, numdec, montant, coment, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye from decaissement where promo=:promo and DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 order by(id)desc', array('promo'=>$promotion, 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));

				        }elseif(isset($_POST['com'])) {
				        	
				        	$prodm=$DB->query('SELECT id, motif, numdec, montant, coment, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye from decaissement where promo=:promo and motif=:motif order by(id)desc', array('promo'=>$promotion, 'motif'=>$_POST['com']));

				        }else{

				        	$prodm=$DB->query('SELECT id, motif, numdec, montant, coment, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye from decaissement where promo=:promo order by(id)desc', array('promo'=>$promotion));
				        }?>
				    
				    	<table class="table table-hover table-bordered table-striped table-responsive text-center">
				    		<thead class="">

				    			<tr><th colspan="9"><?php if ($products['type']=='informaticien' or $products['type']=='comptable' or $products['type']=='admin') {?><a class="btn btn-info" href="depense.php?ajoutdep" style="color: white;">Effectuez un décaissement</a><?php }?></th>
				    			</tr>

			    				<tr>
	                                <th colspan="6">
	                                	<form class="form" method="POST" action="depense.php?sscol.php" name="termc">
                                    		<div class="container-fluid">
                                    			<div class="row">
                                    				<div class="col-6"><?php

				                                        if (isset($_POST['j1']) or isset($_GET['date1'])) {?>

				                                          <input class="form-control" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"><?php

				                                        }else{?>

				                                          <input class="form-control" type = "date" name = "j1" onchange="this.form.submit()"><?php

				                                        }?>
				                                    </div>

				                                    <div class="col-6"><?php

				                                        if (isset($_POST['j2']) or isset($_GET['date2'])) {?>

				                                          <input class="form-control" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"><?php

				                                        }else{?>

				                                          <input class="form-control" type = "date" name = "j2" onchange="this.form.submit()"><?php

				                                        }?>
				                                    </div>
				                                </div>
				                            </div>
	                                    </form>
	                                </th>

	                                <th colspan="3">

	                                   	<form class="form" method="POST" action="depense.php?sscol.php" name="termc">
                                    		<div class="container-fluid">
                                    			<div class="row">
                                    				<div class="col">
	                                        
	                                        			<select class="form-select" type="text" name="com" onchange="this.form.submit()"><?php

				                                            if (isset($_POST['com'])) {?>
																<option value="<?=$_SESSION['com'];?>"><?=$panier->nomCategorie($_SESSION['com']);?></option><?php 
															}else{?>
																<option></option><?php
															}

															foreach ($prodep as $key => $value) {?>
																<option value="<?=$value->id;?>"><?=$panier->nomCategorie($value->id);?></option><?php 
															}?>
														</select>
													</div>

													<div class="col">

														<a style="margin-left: 10px;"href="printdepenses.php?printdep&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>&type=<?=$_SESSION['com'];?>&datenormale=<?=$datenormale;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a><a style="margin-left: 10px;"href="exportdepenses.php?dec" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>
													</div>
												</div>
											</div>
										</form>
									</th>
			                  	</tr>

								<tr>
									<th>N°</th>
									<th>Date</th>
									<th>Catégorie</th>
									<th>Motif</th>
									<th>Montant</th>
									<th>Paiement</th>
									<th colspan="3"></th>
								</tr>

							</thead>

							<tbody><?php
								$totdep=0;
								if (empty($prodm)) {
									# code...
								}else{

									foreach ($prodm as $key=> $formation) {

										$totdep+=$formation->montant;?>

										<tr>
											<td><?=$key+1;?></a></td>
											<td style="text-align: center"><?=$formation->datepaye;?></td>
											<td><?=$panier->nomCategorie($formation->motif);?></td>
											<td><?=ucfirst(strtolower($formation->coment));?></a></td>

											<td style="text-align: right"><?=number_format($formation->montant,0,',',' ');?></td>

											<td style="text-align: center"><?=$formation->typepaye;?></td>
											<td style="text-align: center"><?php
												$num=$formation->numdec;
												$nom_dossier="justificatifdep/".$formation->numdec."/";
												if (file_exists($nom_dossier)) {

													$dossier=opendir($nom_dossier);
													while ($fichier=readdir($dossier)) {

														if ($fichier!='.' && $fichier!='..') {?>

															<a href="justificatifdep/<?=$formation->numdec;?>/<?=$fichier;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a><?php
														}
													}closedir($dossier);
												}?>
											</td>

					                        <td><a class="btn btn-warning" href="depense.php?dep" >Modifier</a></td>

					                        <td><a  class="btn btn-danger" href="depense.php?deledep=<?=$formation->numdec;?>" onclick="return alerteS();">Supprimer</a></td>

										</tr><?php
									}
								}?>

								
							</tbody>

							<tfoot>
								<tr>
									<th colspan="4">Totaux</th>
									<th colspan="2"><?=number_format($totdep,0,',',' ');?></th>
								</tr>
							</tfoot>
						</table><?php
					}?>
				</div>
			</div><?php
		}
	}?>

	<?php require 'footer.php';?>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Valider la suppression'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation'));
    }

    function focus(){
        document.getElementById('pointeur').focus();
    }

</script>
