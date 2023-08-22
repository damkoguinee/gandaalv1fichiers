<div><?php 
	if ($products['type']=='admin' or $products['type']=='eleve' or $products['type']=='tuteur') { ?>
		<fieldset style="margin-top: 30px;"><legend></legend>
		    <div class="choixg" style="display: flex;">

		    	<div class="optiong">
		            <a href="planing.php?mateleve=<?=$_SESSION['matricule'];?>&groupeeleve=<?=$fiche['nomgr'];?>&promo=<?=$_SESSION['promo'];?>">
		            <div class="descript_optiong">Mon Emploi du temps</div></a>
		        </div>

		        <div class="optiong">
		            <a href="noteeleve.php?mateleve&disci=<?=$_SESSION['matricule'];?>&promo=<?=$_SESSION['promo'];?>&classe=<?=$fiche['nomgr'];?>">
		            <div class="descript_optiong">Mes Notes</div></a>
		        </div>

		        <div class="optiong">
		            <a href="discipline.php?mateleve&disci=<?=$_SESSION['matricule'];?>&promo=<?=$_SESSION['promo'];?>">
		            <div class="descript_optiong">Mes Absences</div></a>
		        </div>

		    	<div class="optiong">
		            <a href="paiementeleve.php?mateleve=<?=$_SESSION['matricule'];?>&groupeeleve=<?=$fiche['nomgr'];?>&promo=<?=$_SESSION['promo'];?>">
		            <div class="descript_optiong">Mes Paiements</div></a>
		        </div>  

		        <div class="optiong">
		            <a href="document.php?docel=<?=$_SESSION['matricule'];?>&fiche_eleve=<?=$_SESSION['matricule'];?>&mateleve=<?=$_SESSION['matricule'];?>&promo=<?=$_SESSION['promo'];?>"><div class="descript_optiong">Mes Documents</div></a>
		            </a>
		        </div>


				<div class="optiong">
		            <a href="elevedevoirs.php?docel=<?=$_SESSION['matricule'];?>&fiche_eleve=<?=$_SESSION['matricule'];?>&mateleve=<?=$_SESSION['matricule'];?>&promo=<?=$_SESSION['promo'];?>"><div class="descript_optiong">Mes Devoirs</div></a>
		            </a>
		        </div>		        
		    </div>
		</fieldset><?php
	}?>
	

</div><?php