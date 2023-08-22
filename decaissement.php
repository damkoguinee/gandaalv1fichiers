<?php
require 'header.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{
    	//require 'navcompta.php';?>

    	<div style="display: flex; flex-wrap: wrap;">
    		<div><?php 

			if (isset($_GET['ajoutdep']) or isset($_GET['ajout_scol'])) {?>
				
				<div>
					<form id="formulaire" method="POST" action="decaissement.php" enctype="multipart/form-data">

					    <fieldset><legend>Effectuez un décaissement</legend>
					    	<ol>

					    		<li><label>Motif</label>
									<textarea type="text" name="motif" required="" maxlength="150"></textarea>

									<input type="hidden" name="com" value="depense">
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

								<li><label>Date</label>
									<input type="date" name="datedep">
								</li>

							</ol>
						</fieldset>

						<fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajoutdep" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>
					</form>
				</div><?php
			}?>

			<div><?php

				if(isset($_POST['ajoutdep'])){

					if ($_POST['montant']<0){?>

		                <div class="alertes">Format incorrect</div><?php

		            }elseif ($_POST['montant']>$panier->montantCompteT($_POST['compte'])) {?>

		                <div class="alertes">Echec montant decaissé est > au montant disponible</div><?php

		            }else{

						if($_POST['motif']!="" and $_POST['montant']!=""){
							
							$montant=addslashes(Htmlspecialchars($_POST['montant']));
							$com=addslashes(Htmlspecialchars($_POST['com']));
							$motif=addslashes(Htmlspecialchars($_POST['motif']));
							$typep=addslashes(Htmlspecialchars($_POST['typep']));
							$numcheque=addslashes(Htmlspecialchars($_POST['bordereau']));
							$datedep=addslashes(Htmlspecialchars($_POST['datedep']));

							$maxid = $DB->querys('SELECT max(id) as id FROM decsortie');
		                            
		            		$numdec=$maxid['id']+1;

		            		if (empty($_POST['datedep'])) {

			                    $DB->insert('INSERT INTO decsortie(numdec, montant, coment, motif, typepaye, numcheque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, now())',array($numdec, $montant, $motif, $com, $typep, $numcheque, $_SESSION['promo']));

			                    $DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, now())', array($_POST['compte'], -$montant, 'depense', 'depdec'.$numdec, 'vide', $_SESSION['promo']));
			                }else{

			                	 $DB->insert('INSERT INTO decsortie(numdec, montant, coment, motif, typepaye, numcheque, promo, datepaye) VALUES(?, ?, ?, ?, ?, ?, ?, ?)',array($numdec, $montant, $motif, $com, $typep, $numcheque, $_SESSION['promo'], $datedep));

			                    $DB->insert('INSERT INTO banque (id_banque, montant, libelles, numero, matriculeb, promob,  date_versement) VALUES(?, ?, ?, ?, ?, ?, ?)', array($_POST['compte'], -$montant, 'depense', 'depdec'.$numdec, 'vide', $_SESSION['promo'], $datedep));

			                }?>

		                    <div class="alerteV">Décaissement enregistré avec succèe!!</div><?php

						}else{?>	

							<div class="alertes">Remplissez les champs vides</div><?php
						}
					}
				}


			    if (isset($_GET['dec']) or isset($_POST['j1']) or isset($_POST['ajoutdep'])  or isset($_GET['deledep']) or isset($_GET['modifdep'])) {

			    	if (isset($_GET['deledep'])) {

		                $DB->delete('DELETE FROM decsortie WHERE numdec = ?', array($_GET['deledep']));

		                $DB->delete('DELETE FROM banque WHERE numero=?', array(('depdec'.$_GET['deledep'])));?>

		                <div class="alerteV">decaissement supprimée avec succèe</div><?php
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

			        }else{

			          $_SESSION['date01']=$_POST['j1'];
			          $_SESSION['date1'] = new DateTime($_SESSION['date01']);
			          $_SESSION['date1'] = $_SESSION['date1']->format('Ymd');
			          
			          $_SESSION['date02']=$_POST['j2'];
			          $_SESSION['date2'] = new DateTime($_SESSION['date02']);
			          $_SESSION['date2'] = $_SESSION['date2']->format('Ymd');

			          $_SESSION['dates1']=(new DateTime($_SESSION['date01']))->format('d/m/Y');
			          $_SESSION['dates2']=(new DateTime($_SESSION['date02']))->format('d/m/Y');  
			        }


			        if (isset($_POST['j2'])) {

			          $datenormale='entre le '.$_SESSION['dates1'].' et le '.$_SESSION['dates2'];

			        }else{

			          $datenormale='Liste des depenses ';
			        }

			        $promotion=$_SESSION['promo'];

			    	$prodm=$DB->query('SELECT id, numdec, montant, coment, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye from decsortie where promo=:promo and DATE_FORMAT(datepaye, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datepaye, \'%Y%m%d\') <= :date2 order by(id)desc', array('promo'=>$promotion, 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2']));?>
			    
			    	<table class="payement" id="tableau" style="margin-left: 5px;">
			    		<thead>

		    				<tr>
		    					<form id='formulaire' method="POST" action="decaissement.php?sscol.php" name="termc" style="height: 30px;">

                                        <th colspan="4"><?php

                                            if (isset($_POST['j1']) or isset($_GET['date1'])) {?>

                                              <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"><?php

                                            }else{?>

                                              <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" onchange="this.form.submit()"><?php

                                            }

                                            if (isset($_POST['j2']) or isset($_GET['date2'])) {?>

                                              <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"><?php

                                            }else{?>

                                              <input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" onchange="this.form.submit()"><?php

                                            }?><a style="margin-left: 10px;"href="printdoc.php?printdec&date1=<?=$_SESSION['date1'];?>&date2=<?=$_SESSION['date2'];?>&datenormale=<?=$datenormale;?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>
                                        </th>
                                    </form>
		                    	

		                    	<th colspan="2"><a href="decaissement.php?ajoutdep" style="color: white;">Effectuez un décaissement</a></th>
		                  	</tr>

							<tr>
								<th>Motif</th>
								<th>Montant</th>
								<th>Paiement</th>
								<th>Date</th>
								<th></th>
								<th></th>
							</tr>

						</thead>

						<tbody><?php
						$totdep=0;
						if (empty($prodm)) {
							# code...
						}else{

							foreach ($prodm as $formation) {

								$totdep+=$formation->montant;?>

								<tr>								

									<td><?=ucwords(strtolower($formation->coment));?></a></td>

									<td style="text-align: right"><?=number_format($formation->montant,0,',',' ');?></td>

									<td style="text-align: center"><?=$formation->typepaye;?></td>

									<td style="text-align: center"><?=$formation->datepaye;?></td>

			                        <td colspan="2">
			                        	<a href="decaissement.php?dep" ><input type="button" value="Modifier" style="width: 40%; font-size: 16px; background-color: orange; color: white; cursor: pointer"></a>

			                        	<a href="decaissement.php?deledep=<?=$formation->numdec;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 40%; font-size: 16px; background-color: red; color: white; cursor: pointer"></a>
			                        </td>

								</tr><?php
							}
						}?>

							
						</tbody>

						<tfoot>
							<tr>
								<th>Totaux</th>
								<th colspan="2"><?=number_format($totdep,0,',',' ');?></th>
							</tr>
						</tfoot>
					</table><?php
				}?>
			</div>
		</div>
				</div><?php
		}
	}?>

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
