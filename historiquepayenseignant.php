<?php
require 'header.php';

require 'navcompta.php';

$month = array(
    10  => 'Octobre',
    11  => 'Novembre',
    12  => 'Décembre',
    1   => 'Janvier',
    2   => 'Février',
    3   => 'Mars',
    4   => 'Avril',
    5   => 'Mai',
    6   => 'Juin',
    7   => 'Juillet',
    8   => 'Août',
    9   => 'Septembre'
    
);?>

<div>


	<table class="payement" style="width: 100%; margin-left: 30px;" >
		<thead>
		    <tr>
		        <th></th>
		        <th colspan="4">Historique des paiements pour le mois de <?=$_GET['periode'];?> <a href="printdoc.php?histopaytotemp&mois=<?=$_GET['mois'];?>&mat=<?=$_GET['mat'];?>&periode=<?=$_GET['periode'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a></th>
		    </tr>

		    <tr>
		        <th>Heure(s)</th>
		        <th>Montant</th>
		        <th>Date paye</th>
		        <th></th>
		    </tr>
		</thead>

		<tbody><?php

		    $montant=0;
		    $heuret=0;

		    $prodpaye = $DB->query('SELECT id, numdec, matricule, montant, mois, heurep, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye FROM histopayenseignant WHERE matricule = :mat and mois=:mois and anneescolaire=:promo ORDER BY(datepaye) DESC', array('mat'=> $_GET['mat'], 'mois'=>$_GET['mois'], 'promo'=>$_SESSION['promo']));

	            if (!empty($prodpaye)) {
	                                                  
	                foreach ($prodpaye as $paye) {

	                    $montant+=$paye->montant;
	                    $heuret+=$paye->heurep; ?>

	                    <tr>

		                    <td style="text-align: center;"><?=$paye->heurep;?> h</td>

		                    <td style="text-align: right;"><?=number_format($paye->montant,0,',',' ');?></td>

		                    <td><?='Payé le '.$paye->datepaye;?></td>

		                    <td>
		                        <a href="printdoc.php?payehemp=<?=$paye->numdec; ?>&date=<?=$paye->datepaye; ?>&mat=<?=$_GET['mat'];?>&type=<?=$paye->typepaye; ?>&mois=<?=$_GET['periode'];?>" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

		                        <a href="comptabilite.php?delepayeens=<?=$paye->id;?>&numdec=<?=$paye->numdec;?>&montant=<?=$paye->montant;?>&mois=<?=$_GET['mois'];?>&mat=<?=$_GET['mat'];?>&heurep=<?=$paye->heurep;?>" onclick="return alerteS();"><input type="button" value="Supprimer" style="width: 77%; font-size: 16px; background-color: red; color: white; cursor: pointer"></a>
		                    </td>
		                </tr><?php
	                }
	            }?>
		    <tr>
		        <th><?=$heuret;?> h</th>
		        <th style="text-align: right;"><?=number_format($montant,0,',',' ');?></th>
		        <th></th>

		        <th></th>
		    </tr>

		</tbody>
	</table>
</div>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer cette facture ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>