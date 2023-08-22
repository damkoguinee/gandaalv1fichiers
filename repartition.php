<?php
require 'header.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<3) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{
    	require 'navformation.php';

		if (isset($_GET['scol']) or  isset($_GET['ajout_scol']) or isset($_GET['trimestre'])or isset($_GET['semestre'])) {?>
			
			<div class="col">
				<form id="formulaire" method="POST" action="repartition.php" style="width: 60%; height: 150px;">

				    <fieldset style="height: 100px; margin-top: 10px; background: #384313; "><legend style="color: white; font-size: 25px; font-weight: bold; padding-top: 15px; ">Selectionnez votre Mode de Fonctionnement: </legend>

					<div class="row" style="margin-top: 0px;">

		        		<a href="repartition.php?trimestre=<?='trimestre';?>" onclick="return alerteV();"><input type="button" value="TRIMESTRE" style="width: 98%; height: 50px; font-size: 16px; font-weight: bold; cursor: pointer; background-color: gold;"></a>

		        		<input type="button" value="OU" style="width: 8%; height: 40px; font-size: 16px; font-weight: bold; cursor: pointer; margin-left: 30px;"></a>

		        		<a href="repartition.php?trimestre=<?='semestre';?>" onclick="return alerteV();"><input type="button" value="SEMESTRE" style="width: 98%; height: 50px; font-size: 16px; font-weight: bold; cursor: pointer; margin-left: 30px; background-color: orange;"></a>

		        	</div>

		        </fieldset>
				</form>
			</div><?php
		}?>

		<div class="col" ><?php

			if(isset($_GET['trimestre'])or isset($_GET['semestre'])){

				$promo=$_SESSION['promo'];
							

				$nb=$DB->querys('SELECT type from repartition where (promo=:promo)', array(
					'promo'=>$promo
				));

				if(!empty($nb)){

					$DB->insert('UPDATE repartition SET type=? WHERE promo = ?', array($_GET['trimestre'], $promo));

				}else{

					$DB->insert('INSERT INTO repartition(type, promo) values(?, ?)', array($_GET['trimestre'], $promo));?>	

					<div class="alerteV">Type ajouté avec succèe!!!</div><?php
				}
			}

			if(isset($_GET['scol']) or isset($_GET['trimestre'])or isset($_GET['semestre'])){
		        	

		    	$prodm=$DB->querys('SELECT id, type from repartition  where promo=:promo',array('promo'=>$_SESSION['promo']));?>
		    
	    		<table class="payement" id="tableau" style="margin-left: 190px; width: 50%; margin-top: -400px;">
		    		<thead>

						<tr>
							<th><?='Mode de Fonctionnement Année-Scolaire '.($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></th>
						</tr>

					</thead>

					<tbody>

						<tr>

							<td style="text-align: center; font-style: 35px; font-weight: bold;"><?=ucwords($prodm['type']);?></td>

						</tr>

						
					</tbody>

							
				</table><?php
			}
		}
	}?>
</div>



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
