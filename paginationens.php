<?php 
if (!isset($_POST['modifen'])) {
	$nbreparpage = 30;

	if (!empty($_SESSION['niveauf'])) {

 		$prodnbre=$DB->querys('SELECT count(enseignant.id) as total from enseignant  inner join niveau on enseignant.matricule=niveau.matricule where nom=:niv order by(prenomen)', array('niv'=>$_SESSION['niveauf']));

 	}else{

 		$prodnbre= $DB->querys('SELECT count(id) as total FROM enseignant');


 	}


   	$lien='enseignant.php?effnav&page=';

    $nbreeleve= $prodnbre['total'];
    $nbredepage = ceil($nbreeleve/$nbreparpage);

    if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $nbredepage) {

       $_GET['page'] = intval($_GET['page']);

       $pageCourante = $_GET['page'];

    }else{

       $pageCourante = 1;
    }

	$depart = ($pageCourante-1)*$nbreparpage;?>

	<div style="display: flex; font-size: 18px; margin-bottom:10px;"><?php

		if ($nbredepage==1 or $nbredepage==0) {

		}else{

			if (!isset($_GET['derniere'])) {
		

				for($i=1;$i<=($pageCourante+1);$i++) {
			  
			        if($i == $pageCourante) {?>

			        	<div><?=' '.$i.' ';?></div><?php

			        }elseif($i == 1) {?>

			        	<div style="margin-right: 10px;">

			        		<a href="<?=$lien;?>=<?=$i;?>&supp"><img src="css/img/flecheg.jpg" width="100" height="25"></a>

			        	</div><?php

			        }elseif($i == $pageCourante+1) {?>
			        	<div style="margin-right: 10px; margin-left: 10px; ">

			        		<a href="<?=$lien;?><?=$i;?>&supp"><?='  page suivante ';?></a>
			        	</div><?php

			        }else {?>
			        	<div style="margin-right: 10px;">

			        		<a href="<?=$lien;?><?=$i;?>&supp"><?=$i;?></a>
			        	</div><?php
			        }

			        $j=0;
			    }
			}

			if (!isset($_GET['supp'])) {

				if (!isset($_GET['premiere'])) {?>

					<div style="margin-right: 10px;">

						<a href="<?=$lien;?><?=1;?>&premiere"><img src="css/img/flecheg.jpg" width="100" height="25"></a>

					</div><?php
				}
			}?>

			<div style="margin-right: 10px;">

		    	<a href="<?=$lien;?><?=$nbredepage;?>&derniere"><img src="css/img/fleche.jpg" width="100" height="25" style="margin-left: 100px;"></a>
		    </div><?php
		}?>
	</div>	<?php 
}

