<?php
if (isset($_GET['mateleve'])) {
	require 'headereleve.php';
}else{

	require 'header.php';
}

if ($products['niveau']<1) {?>

    <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

}else{?>

	<div class="col"><?php

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

		require 'fiche_eleve.php';?><?php 

		/*
		

		<table class="tranche" style="width:50%; margin-bottom: -10px; margin-top: -5px; ">
			<thead>
				<tr>
					<form id='formulaire' method="POST" action="discipline.php" name="termc" style="height: 30px;"><?php

	                    if (isset($_POST['j1'])) {?>

	                      <th style="border-right: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" value="<?=$_SESSION['date01'];?>" onchange="this.form.submit()"></th><?php

	                    }else{?>

	                      <th style="border-right: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j1" onchange="this.form.submit()"></th><?php

	                    }

	                    if (isset($_POST['j2'])) {?>

	                      <th colspan="1" style="border-left: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" value="<?=$_SESSION['date02'];?>" onchange="this.form.submit()"></th><?php

	                    }else{?>

	                      <th colspan="1" style="border-left: 0px;"><input id="reccode" style="width: 130px; font-size: 14px;" type = "date" name = "j2" onchange="this.form.submit()"></th><?php

	                    }?>
	                </form>
				</tr>
			</thead>
		</table>
		*/ ;?>
	</div><?php

	if ($prodtype=='semestre') {

		$limite=3;

	}else{

		$limite=4;

	}

	for ($i=1; $i <$limite ; $i++) {?>

		<div style="margin-right: 30px;"><?php 

		if ($i==1) {
			$semestre="1er ".$typerepart;
		}elseif($i==2){

			$semestre="2ème ".$typerepart;

		}elseif($i==3){

			$semestre="3ème ".$typerepart;

		}
			

			$prodabs=$DB->querys('SELECT count(matricule) as nbreabs from  absence  where matricule=:matr and promo=:promo and DATE_FORMAT(dateabs, \'%Y%m%d\') >= :date1 and DATE_FORMAT(dateabs, \'%Y%m%d\') <= :date2 and semestre=:sem and id not in(SELECT id_absence FROM justabsence)', array('matr'=>$_SESSION['fiche'], 'promo'=>$_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'sem'=>$i));

			$prodabj=$DB->querys('SELECT count(matricule) as nbreabs from  absence  where matricule=:matr and promo=:promo and DATE_FORMAT(dateabs, \'%Y%m%d\') >= :date1 and DATE_FORMAT(dateabs, \'%Y%m%d\') <= :date2 and semestre=:sem and id in(SELECT id_absence FROM justabsence)', array('matr'=>$_SESSION['fiche'], 'promo'=>$_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'sem'=>$i));

			$prodret=$DB->querys('SELECT count(matricule) as nbreabs from  retard  where matricule=:matr and promo=:promo and DATE_FORMAT(dateabs, \'%Y%m%d\') >= :date1 and DATE_FORMAT(dateabs, \'%Y%m%d\') <= :date2 and semestre=:sem and id not in(SELECT id_absence FROM justretard)', array('matr'=>$_SESSION['fiche'], 'promo'=>$_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'sem'=>$i));

			$prodretj=$DB->querys('SELECT count(matricule) as nbreabs from  retard  where matricule=:matr and promo=:promo and DATE_FORMAT(dateabs, \'%Y%m%d\') >= :date1 and DATE_FORMAT(dateabs, \'%Y%m%d\') <= :date2 and semestre=:sem and id in(SELECT id_absence FROM justretard)', array('matr'=>$_SESSION['fiche'], 'promo'=>$_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'sem'=>$i));

			$prodex=$DB->querys('SELECT count(matricule) as nbreabs from  exclus  where matricule=:matr and promo=:promo and DATE_FORMAT(dateexclus, \'%Y%m%d\') >= :date1 and DATE_FORMAT(dateexclus, \'%Y%m%d\') <= :date2 and semestre=:sem', array('matr'=>$_SESSION['fiche'], 'promo'=>$_SESSION['promo'], 'date1' => $_SESSION['date1'], 'date2' => $_SESSION['date2'], 'sem'=>$i));?>

			<table class="tranche" >
				<thead>

					<tr>

			            <th colspan="5" style="text-align: center; font-size: 16px;">Situation de <?=$panier->nomEleve($_SESSION['fiche']);?> N° <?=$_SESSION['fiche'];?> pour le <?=$semestre;?></th>
		          	</tr>

					<tr>
						<th height="25">Abs Non Justifiée(s)</th>
						<th>Abs Justifiée(s)</th>
						<th>Retard(s) Non Justifiés</th>
						<th>Retard(s) Justifiés</th>
						<th>Exclus</th>
						
					</tr>
				</thead>
				<tbody>

					<tr><?php 
						if (!isset($_GET['mateleve'])) {?>

							<td height="50"><a href="listeabsence.php?absmatri=<?=$_GET['disci'];?>&promo=<?=$_SESSION['promo'];?>&sem=<?=$i;?>"><input type="button" value="<?=$prodabs['nbreabs'];?>" style="width: 100%; height: 56px; font-size: 40px; font-weight: bold;  cursor: pointer; background-color: red; color: white;"></a></td>

						  	<td height="50"><a href="#?mateleve=<?=$_GET['disci'];?>&promo=<?=$_SESSION['promo'];?>&sem=<?=$i;?>"><input type="button" value="<?=$prodabj['nbreabs'];?>" style="width: 100%; height: 56px; font-size: 40px; font-weight: bold;  cursor: pointer; background-color: green; colo
						  	r: white;"></a></td>

						  	<td height="50"><a href="listeretard.php?retmatri=<?=$_GET['disci'];?>&promo=<?=$_SESSION['promo'];?>&sem=<?=$i;?>"><input type="button" value="<?=$prodret['nbreabs'];?>" style="width: 100%; height: 56px; font-size: 40px; font-weight: bold;  cursor: pointer; background-color: orange; color: white;"></a></td>

						  	<td height="50"><a href="#?mateleve=<?=$_GET['disci'];?>&promo=<?=$_SESSION['promo'];?>&sem=<?=$i;?>"><input type="button" value="<?=$prodretj['nbreabs'];?>" style="width: 100%; height: 56px; font-size: 40px; font-weight: bold;  cursor: pointer; background-color: green; colo
						  	r: white;"></a></td>

						  	<td height="50"><a href="listexclusion.php?exmatri=<?=$_GET['disci'];?>&promo=<?=$_SESSION['promo'];?>&sem=<?=$i;?>"><input type="button" value="<?=$prodex['nbreabs'];?>" style="width: 100%; height: 56px; font-size: 40px; font-weight: bold;  cursor: pointer; background-color: maroon; color: white;"></a></td><?php 
					  	}else{?>

							<td height="50"><a href="listediscipline.php?disci=<?=$_GET['disci'];?>&absmatri=<?=$_GET['disci'];?>&promo=<?=$_SESSION['promo'];?>&sem=<?=$i;?>"><input type="button" value="<?=$prodabs['nbreabs'];?>" style="width: 100%; height: 56px; font-size: 40px; font-weight: bold;  cursor: pointer; background-color: red; color: white;"></a></td>

						  	<td height="50"><a href="#?mateleve=<?=$_GET['disci'];?>&promo=<?=$_SESSION['promo'];?>&sem=<?=$i;?>"><input type="button" value="<?=$prodabj['nbreabs'];?>" style="width: 100%; height: 56px; font-size: 40px; font-weight: bold;  cursor: pointer; background-color: green; color: white;"></a></td>

						  	<td height="50"><a href="listediscipline.php?retmatri=<?=$_GET['disci'];?>&promo=<?=$_SESSION['promo'];?>&sem=<?=$i;?>"><input type="button" value="<?=$prodret['nbreabs'];?>" style="width: 100%; height: 56px; font-size: 40px; font-weight: bold;  cursor: pointer; background-color: orange; color: white;"></a></td>


						  	<td height="50"><a href="#?mateleve=<?=$_GET['disci'];?>&promo=<?=$_SESSION['promo'];?>&sem=<?=$i;?>"><input type="button" value="<?=$prodretj['nbreabs'];?>" style="width: 100%; height: 56px; font-size: 40px; font-weight: bold;  cursor: pointer; background-color: green; colo
						  	r: white;"></a></td>

						  	<td height="50"><a href="listediscipline.php?exmatri=<?=$_GET['disci'];?>&promo=<?=$_SESSION['promo'];?>&sem=<?=$i;?>"><input type="button" value="<?=$prodex['nbreabs'];?>" style="width: 100%; height: 56px; font-size: 40px; font-weight: bold;  cursor: pointer; background-color: maroon; color: white;"></a></td><?php 

					  	}?>
					</tr>

				</tbody>
			</table>

		</div><?php
	}
}?>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer cette facture ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>
