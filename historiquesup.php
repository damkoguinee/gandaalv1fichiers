<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<5) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

    	</div>

		<div style="display:flex;"><?php

	    	if (!isset($_GET['matiere'])) {
	    		require 'navformation.php';
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

		      $datenormale=(new DateTime($dates))->format('Y');
		    }

				

		    $prodm=$DB->query('SELECT type, mateleve, executeur, datesup from historiquesup WHERE DATE_FORMAT(datesup, \'%Y%m%d\') >= :date1 and DATE_FORMAT(datesup, \'%Y%m%d\') <= :date2 and promo=:promo', array('date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'promo'=>$_SESSION['promo']));?>

    		<div>
    			<div>

		    
			    
				<table class="payement" style="width: 100%;">
					<thead>

			            <tr>
			                <form id='formulaire' method="POST" action="historiquesup.php" name="termc" style="height: 30px;">
			                	<th></th><?php

			                    if (isset($_POST['j1'])) {?>

			                      <th style="border-right: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"></th><?php

			                    }else{?>

			                      <th style="border-right: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" onchange="this.form.submit()"></th><?php

			                    }

			                    if (isset($_POST['j2'])) {?>

			                      <th style="border-left: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"></th><?php

			                    }else{?>

			                      <th style="border-left: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" onchange="this.form.submit()"></th><?php

			                    }?>

			                    <th colspan="3" height="30"><?='liste des suppressions '.$datenormale;?></th>
			                </form>
			            </tr>

			            <tr>
			            	<th height="30"></th>
			              	<th colspan="2">Type</th>
			              	<th>date de sup</th>
			              	<th>Pers Exec</th>	              
			            </tr>

			        </thead>

					<tbody><?php
					if (empty($prodm)) {

					}else{

						foreach ($prodm as $key => $value) {?>

							<tr>
								<td><?=$key+1;?></td>
								<td colspan="2"><?=ucfirst($value->type);?><a href="comptabilite.php?eleve=<?=$value->mateleve;?>"><?=$value->mateleve;?></a></td>
								<td><?=(new DateTime($value->datesup))->format('d/m/Y à H:i');?></td>
								<td><?=ucwords($value->executeur);?></td>

							</tr><?php
						}

							
					}?>

						
					</tbody>
				</table>
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
