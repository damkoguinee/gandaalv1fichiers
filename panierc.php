<?php
if (isset($_POST['numeen'])) {

    $_SESSION['numeen']=$_POST['numeen'];
    $numeen=$_SESSION['numeen'];
    $numel=$_SESSION['numeen'];

    $prodsocial=$DB->querys('SELECT montant from ssocialens where numpers=:mat', array('mat'=>$numeen));

    $_SESSION['prodsocial']=$prodsocial['montant'];

    $prodsalaire=$DB->querys('SELECT salaire, thoraire from salaireens where numpers=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));


    if ($prodsalaire['salaire']==0) {
        
        $_SESSION['salaire']=$prodsalaire['thoraire'];
        $_SESSION['salaireact']='not';

    }else{

        $_SESSION['salaire']=$prodsalaire['salaire'];
        $_SESSION['salaireact']='ok';
    }


}elseif (isset($_POST['payen'])) {
    $numeen='csp';
    $numel='csp';
}else{

}

if (isset($_GET['payecherc'])) {
  
  $_SESSION['numeen']=$_GET['payecherc'];
  $numeen=$_SESSION['numeen'];
  $numel=$_SESSION['numeen'];
  
}


if (isset($_GET['delepayeens'])) {

    $numeen=$_SESSION['numeen'];
    $numel=$_SESSION['numeen'];
}

if (isset($_GET['delehoraire'])) {

    $numeen=$_SESSION['numeen'];
    $numel=$_SESSION['numeen'];
}

if (isset($_POST['numeen']) or !empty($numeen) or isset($_GET['delepayeens']) or isset($_GET['enseignant']) or isset($_GET['payecherc'])) {

    if (isset($_POST['numeen']) and $_POST['numeen'][3]=='e') {

        $products=$DB->querys('SELECT enseignant.matricule as mat, nomen as nomel, prenomen as prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email from enseignant inner join contact on enseignant.matricule=contact.matricule where enseignant.matricule=:mat', array('mat'=>$numeen));
    }elseif (isset($_POST['numeen']) and $_POST['numeen'][3]=='p') {

        $products=$DB->querys('SELECT numpers as mat, nom as nomel, prenom as prenomel, date_format(datenaissance,\'%d/%m/%Y \') as naissance, phone, email from personnel inner join contact on numpers=contact.matricule where numpers=:mat', array('mat'=>$numeen));
    }else{

    	$products=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email, nomgr from eleve inner join contact on eleve.matricule=contact.matricule inner join inscription on inscription.matricule=eleve.matricule where eleve.matricule=:mat', array('mat'=>$numeen));

    }

    if (isset($_GET['enseig'])) {

        $products=$DB->querys('SELECT enseignant.matricule as mat, nomen as nomel, prenomen as prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email from enseignant inner join contact on enseignant.matricule=contact.matricule where enseignant.matricule=:mat', array('mat'=>$numeen));
    }

    if (isset($_GET['eleve'])) {

        $products=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, date_format(naissance,\'%d/%m/%Y \') as naissance, phone, email, nomgr from eleve inner join contact on eleve.matricule=contact.matricule inner join inscription on inscription.matricule=eleve.matricule where eleve.matricule=:mat', array('mat'=>$numeen));
    }

    if (isset($_GET['personnel'])) {

        $products=$DB->querys('SELECT numpers as mat, nom as nomel, prenom as prenomel, date_format(datenaissance,\'%d/%m/%Y \') as naissance, phone, email from personnel inner join contact on numpers=contact.matricule where numpers=:mat', array('mat'=>$numeen));
    }
     
}else{
    $products = array();
}?>

<div class="cmd"><?php

	if (isset($_GET['delpc'])) {

		$DB->delete('DELETE FROM validcomande WHERE id_produit = ?', array($_GET['delpc']));
	}

	if (isset($_GET['desig'])) {

		$prodvalidcverif = $DB->querys('SELECT quantite FROM validcomande where id_produit=:id', array('id'=>$_GET['idc']));

		if (empty($prodvalidcverif)) {
					
			$DB->insert('INSERT INTO validcomande (id_produit, designation, quantite, pvente, ddebut, dfin, datecmd) VALUES(?, ?, ?, ?, now(), now(), now())', array($_GET['idc'], $_GET['desig'], 1,'0'));

		}else{

			$qtitesup=$prodvalidcverif['quantite']+1;

			$DB->insert('UPDATE validcomande SET quantite=? where id_produit=?', array($qtitesup, $_GET['idc']));

		}
	}

	if (isset($_GET['scanneurc'])) {

		$_SESSION['scannerc']=$_GET['scanneurc'];

		$prodstock = $DB->querys('SELECT *FROM stocklivre where codeb=:id', array('id'=>$_GET['scanneurc']));

		$prodvalidcverif = $DB->querys('SELECT quantite FROM validcomande where codebvc=:id', array('id'=>$_GET['scanneurc']));

		if (empty($prodvalidcverif)) {
					
			$DB->insert('INSERT INTO validcomande (id_produit, designation, quantite, pvente, ddebut, dfin, datecmd) VALUES(?, ?, ?, ?, now(), now(), now())', array($_GET['idc'], $_GET['desig'], 1,'0'));

		}else{

			$qtitesup=$prodvalidcverif['quantite']+1;

			$DB->insert('UPDATE validcomande SET quantite=? where codebvc=?', array($qtitesup, $_GET['scanneurc']));

		}
		# code...
	}

	if (isset($_POST['modifcom']) or isset($_GET['modifcom'])) {
		
		$DB->insert('UPDATE validcomande SET quantite=?, pvente=? where id_produit=?', array($_POST['quantity'], $_POST['plocation'],  $_POST['id']));
	}


	if (isset($_POST['validliv'])) {

		$maximum = $DB->querys('SELECT max(id) AS max_id FROM payelivre ');

		$numc =$maximum['max_id'] + 1;
		$init='b';

		$prodlivre = $DB->query('SELECT *FROM validcomande');

		if (empty($prodlivre)) {?>

			<div class="alertes">Aucun livre ajouter. <a href="emprunterlivre.php">Ajouter</a></div><?php					
			
		}else{

			foreach ($prodlivre as $value) {

				$id=$value->id_produit;
				$qtite=$value->quantite;
				$pv=$value->pvente;
				$ddebut=$value->ddebut;
				$dfin=$value->dfin;
				$matricule=$panier->h($_POST['numeen']);
				$totalc=$panier->h($_POST['totc']);
				$totalp=$panier->h($_POST['mpaye']);
				$typep=$panier->h($_POST['typep']);

				if ($totalc>$totalp) {
					$etat='en-cours';
				}else{
					$etat='clos';
				}


				$DB->insert('INSERT INTO empruntlivre (id_produit, numc, quantite, pvente, ddebut, dfin, matricule, etat, datecmd) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())', array($id, ($init.$numc), $qtite, $pv, $ddebut, $dfin, $matricule, 'en-cours'));

				$prodstock = $DB->querys('SELECT quantite FROM stocklivre where id=:id', array('id'=>$id));

				$qtiteR=$prodstock['quantite']-$qtite;


				$DB->insert('UPDATE stocklivre SET quantite=? where id=?', array($qtiteR, $id));


			}

			$DB->insert('INSERT INTO payelivre (numc, totalc, totalp, typep, etat, datecmd) VALUES(?, ?, ?, ?, ?, now())', array(($init.$numc), $totalc, $totalp, $typep, $etat));

			$DB->delete('DELETE FROM validcomande');?>

			<div class="alerteV">Commande Validée !!!!</div><?php

		}
	}

	$prodvalidc = $DB->query('SELECT stocklivre.id as id,  id_produit, validcomande.quantite as quantite, validcomande.designation as designation, classe, matiere, pvente, ddebut, dfin FROM validcomande inner join stocklivre on stocklivre.id=validcomande.id_produit order by(validcomande.id)desc');

	if (!empty($prodvalidc)) {?>
	 	
	 	<table class="payement" style="margin-top: 30px;">

	 		<thead>			
				<th height="30">désignation</th>
				<th>Matiere</th>
				<th>Niveau</th>
				<th>Date Début</th>
				<th>Date Fin</th>
				<th>Qtite</th>				
				<th>P. Loc</th>
				<th>P. Total</th>
				<th></th>				
				<th class="sup">Sup</th>

			</thead>

			<?php

			$ptotalht=0;

			foreach($prodvalidc as $key=> $product){

				$ptotal=$product->quantite*$product->pvente;

				$ptotalht+=$ptotal;?>

				<form id="modifcom" action="emprunterlivre.php?modifcom" method="POST">

					<tbody>

						<td style="text-align: left;"><?= ucwords($product->designation); ?><input  type="hidden" name="id" value="<?=$product->id;?>"></td>

						<td style="text-align: left;"><?= ucwords($product->matiere); ?></td>

						<td style="text-align: center;"><?= ucwords($product->classe); ?></td>

						<td><input style="width: 90%;" type="date" name="dated" value="<?=$product->ddebut;?>"></td>

						<td><input style="width: 90%;" type="date" name="datef" value="<?=$product->dfin;?>"></td>

						<td style="text-align: center;"><input style="width: 90%; text-align: center;" type="text" min="0" name="quantity" value="<?=$product->quantite;?>"></td>

						<td style="width: 10%;"><input style="width: 90%; text-align: right;" type="text" name="plocation" value="<?=$product->pvente;?>" onchange="this.form.submit();" ></td>

						

						<td style="text-align: right;"><?=number_format($ptotal,0,',',' ');?></td>

						<td><input type="submit" name="modifcom" value="Valider" style="background-color: green; color: white;"></td>					

						<td class="supc">
							<a onclick="return alerteS();" href="emprunterlivre.php?delpc=<?= $product->id_produit; ?>" class="del"><input style="color: white; background-color: red; text-align: center; font-size: 18px; cursor: pointer; width: 90%;" type="text" name="payen" value="Supprimer"></a>
						</td>

					</tbody>
				</form><?php
			}?>

			<tfoot>
				<tr>
					<th colspan="5" height="30">Montant à payer</th>
					<th colspan="3" style="text-align: center;"><?=number_format($ptotalht,0,',',' '); ?></th>
				</tr>
			</tfoot>

			
		</table>

		<form action="emprunterlivre.php" method="post" id="formulaire" style="background-color: grey; width: 50%; ">                
	    	<fieldset><legend> Rechercher <a href="ajout_eleve.php?listeeleve&livrel=<?='payemp';?>&effnav" style=" color: white; font-weight: bold;"><?=$_SESSION['typeel'];?> </a> ou <a href="enseignant.php?livrens=<?='payemp';?>&effnav" style=" color: white; font-weight: bold;">Enseignant </a> ou <a href="enseignant.php?personnel&livrepers=<?='payemp';?>&effnav" style=" color: white; font-weight: bold;">Personnels </a><?php

		        if (isset($_POST['numeen']) or isset($_POST['payen']) or isset($_GET['enseignant']) or isset($_GET['personnel']) or isset($_GET['payecherc'])) {

		            require 'ficheeleve.php';

		        }?></legend> 
		        <ol style="margin-top: -10px;"><?php

		            if (isset($_POST['numeen']) AND empty($products)) {?>

		                <div class="alertes">Numéro incorrect, <a style="color: red;" href="emprunterlivre.php?paye">réessayer ici</a></div><?php
		            }else{
		                    
		                if (isset($_POST['numeen']) or isset($_POST['payen']) or isset($_GET['enseignant']) or isset($_GET['personnel']) or isset($_GET['payecherc'])) {?>

		                    <li><label>N°Matricule</label><input  type="text" name="numeen" placeholder="N° matricule" onchange="document.getElementById('formulaire').submit()" value="<?= $numeen; ?>" /></li><?php

		                }else{?>
		                    <li><label>N°Matricule</label><input  type="text" name="numeen" placeholder="N° matricule" onchange="document.getElementById('formulaire').submit()" /></li><?php                                
		                }
		            }?>

		            <li><label>Montant Payé</label><input style="font-size: 25px;" type="text" name="mpaye" required=""><input type="hidden" name="totc" value="<?=$ptotalht;?>"></li>

		            <li><label>Mode de Payement</label><select  name="typep">
						<option value=""></option><option value=""></option><?php 
                        foreach ($panier->modep as $value) {?>
                            <option value="<?=$value;?>"><?=$value;?></option><?php 
                        }?></select>
                    </li>

		        </ol>

	    	</fieldset>

	    	<fieldset><input type="reset" value="Annuler" name="recnaiss" id="form" style="cursor: pointer;" /><input type="submit" value="Valider" name="validliv" id="form" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>

		</form><?php
	}?>

</div>



	





