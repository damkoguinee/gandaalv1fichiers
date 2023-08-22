<div class="cmd"><?php


	if (isset($_GET['desig']) or isset($_GET['scanneurc']) or isset($_GET['retourpaye'])) {

		if (isset($_GET['scanneurc'])) {

			$prodstock = $DB->querys('SELECT id FROM stocklivre where codeb=:id', array('id'=>$_GET['scanneurc']));

			$id=$prodstock['id'];

		}elseif(isset($_GET['retourpaye'])){

			$id=$panier->h($_GET['ids']);

		}else{

			$id=$panier->h($_GET['idc']);
		}

		$prodverif = $DB->querys('SELECT etat FROM empruntlivre where id_produit=:id and numc=:num', array('id'=>$id, 'num'=>$_GET['retourpaye']));

		if ($prodverif['etat']=='clos') {?>

			<div class="alertes">Ce livre est déjà retourné</div><?php 
			
		}else{

			$DB->insert('UPDATE empruntlivre SET etat=? where id_produit=?', array('clos', $id));

			$prodstock = $DB->querys('SELECT quantite FROM stocklivre where id=:id', array('id'=>$id));

			$qtiteR=$prodstock['quantite']+1;


			$DB->insert('UPDATE stocklivre SET quantite=? where id=?', array($qtiteR, $id));?>

			<div class="alerteV">Retour pris en compte !!!!</div><?php
		}

		header("Location: listempruntlivre.php");
	}

	if (isset($_GET['matpaye'])) {?>

		<form action="retourlivre.php" method="post" id="formulaire" style="background-color: grey; width: 100%; ">                
	    	<fieldset><legend>Effectuer un Paiement</legend> 
		        <ol style="margin-top: -10px;">

		        	<li><label>N°Matricule</label><input  type="text" name="numeen" value="<?= $_GET['matpaye']; ?>" /><input  type="hidden" name="numc" value="<?= $_GET['retourpay']; ?>" /></li>

		            <li><label>Montant à Payer</label><input type="hidden" name="mpaye" value="<?= $_GET['mpaye']; ?>" required=""><strong><?=number_format($_GET['mpaye'],0,',',' '); ?></strong></li>

		            <li><label>Mode de Payement</label><select  name="typep" required="">
						<option value=""></option>
						<option value="differe">differé</option>
                        <option value="especes">Espèces</option>
                        <option value="cheque">Chèque</option>
                        <option value="virement">Virement</option></select>
                    </li>

		        </ol>

	    	</fieldset>

	    	<fieldset><input type="reset" value="Annuler" name="recnaiss" id="form" style="cursor: pointer;" /><input type="submit" value="Valider" name="validliv" id="form" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>

		</form><?php
	}


	if (isset($_POST['validliv'])) {
				
		$matricule=$panier->h($_POST['numeen']);
		$_SESSION['matpaye']=$matricule;
		$numc=$panier->h($_POST['numc']);
		$totalp=$panier->h($_POST['mpaye']);
		$typep=$panier->h($_POST['typep']);
		$etat='clos';
		$daten=date('Y-m-d H:i:00');
		

		$DB->insert('UPDATE payelivre SET totalp=?, typep=?, etat=?, datecmd=? WHERE numc=?', array($totalp, $typep, $etat, $daten, $numc));?>

		<div class="alerteV">Payement Validée !!!!</div><?php

		header("Location: listempruntlivre.php");
	}?>

<script>
    function alerteS(){
        return(confirm('Valider la suppression'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation'));
    }
    
    function suivant(enCours, suivant, limite){
        if (enCours.value.length >= limite)
        document.term[suivant].focus();
    }

    function focus(){
    document.getElementById('reccode').focus();
  }
</script>

</div>



	





