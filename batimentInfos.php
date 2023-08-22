<?php
require_once "phpqrcode/qrlib.php";
require_once "phpqrcode/qrconfig.php";
require 'headerv3.php';

if (empty($_SESSION['pseudo'])) {
	header('Location:form_connexion.php');
}else{?>
	<div class="container-fluid mt-1"><?php

	    $id_bat=$_GET['infos'];
	    $idresidence=$id_bat;
	    $idclient=$id_bat;

	    $fiche=$immobillier->batimentSelectById($id_bat);            
	    
	    $id_bat=$fiche['identifiant'];

	    $codeContent=$id_bat;

		$fileName=$id_bat.".png";

		$chemin = 'qrcode/'.$fileName;


		if (!file_exists($chemin)) {

			QRcode::png($codeContent, $chemin);
		}?>

	    <div class="row"><?php
	    	require 'navimmo.php';?>

		    <div class="col-sm-12 col-md-10">

		    	<div class="col rounded-end text-dark text-opacity-75" style="box-shadow: 10px 2px 20px; margin-bottom: 10px; ">   		

		    		<div class="fichel" style="font-size: 20px; font-weight: bold; text-align: center; margin-top: 10px;" ><?=strtoupper($fiche['nom']);?></div>

		    		<div class="row">    	

				        <div class="col-sm-12 col-md-4">
				            <ol style="font-size: 15px;">
				                <li class="fw-bold"><label class="label">Identifiant </label><?=strtoupper($id_bat);?></li>
				                <li class="fw-bold"><label class="label">Type</label> <?=strtoupper($fiche['batiment_type']);?></li>
				                <li class="fw-bold"><label class="label">Etage</label> <?=$fiche['nombre_etage'];?></li>
				                <li class="fw-bold"><label class="label">Nbre Appart</label> <?=$fiche['nombre_pieces'];?></li>		                
				            </ol>
				            
				        </div><?php 

				        $filename1="img/".$id_bat.'.jpg';

		        		if (file_exists($filename1)) {?>

					        <div class="col-sm-12 col-md-3 ">
								<div class="card m-auto bg-primary bg-opacity-25" >
								  <img class="card-img-top w-50 h-50 m-auto" src="img/<?=$id_bat;?>.jpg" alt="Card image cap">
								</div>
							</div><?php 
						}else{?>

							<div class="col-sm-12 col-md-3 ">
								<div class="card m-auto bg-primary bg-opacity-25" >
								  <img class="card-img-top w-50 h-50 m-auto" src="img/default.jpg" alt="Card image cap">
								</div>
							</div><?php

						} ?>



				        <div class="col-sm-12 col-md-5">
				            <ol style="font-size: 15px;">

				            	<li class="fw-bold"><label class="labell"><img style="width: 30px;" class="card-img-left" src="css/img/phone.jpg"></label> <?=$fiche['phone'];?></li>
				                <li class="fw-bold"><label class="labell"><img style="width: 30px;" class="card-img-left" src="css/img/email.jpg"></label> <?=$fiche['email'];?></li>
				                <a href="index.php?adresse=<?=$fiche['id'];?>"><li class="fw-bold"><label class="labell"><img style="width: 30px;" class="card-img-left" src="css/img/adresse.jpg"></label> <?=ucwords($fiche['adresse']);?></li></a>
				            </ol>
				            
				        </div>
				    </div>

				    <div class="alert alert-success text-center fw-bold fs-5 ">
				    	<a class="btn btn-primary" href="residencesAppartement.php?ajout">Ajouter un Appartement</a>
				    	<a class="btn btn-primary" href="residencesInfos.php?infos=<?=$idresidence;?>">Liste des Appartements</a>
				    	<a class="btn btn-primary" href="residencesInfos.php?listelocataire&infos=<?=$idresidence;?>">Liste des Locataires</a>
				    	<a class="btn btn-primary" href="client.php?ajout&idresidence=<?=$idresidence;?>">Ajouter un Locataire</a>
				    	
				    </div>
			    </div><?php 

			    if (isset($_GET['delete'])) {

			    	$idresidence=$_GET['infos'];
			    	$idapart=$_GET['idapart'];
			    	$idclient=$_GET['idclient'];
	    			$DB->delete("DELETE FROM client WHERE id='{$idclient}' ");

			    	$DB->delete("DELETE FROM residappartlocataire WHERE idresidence ='{$idresidence}' and idapart='{$idapart}' and idclient='{$idclient}' ");

			    	$DB->insert('UPDATE residencesappart SET etat=? where id=?', array('ok', $idapart));?>

			    	<div class="alert alert-success">Client Supprimé avec succèe!!</div><?php 
		  		}

		  		if (isset($_GET['listelocataire'])) {

			  		$prod=$DB->query("SELECT *FROM batimentappart inner join residappartlocataire on residencesappart.id=idapart  where residencesappart.idresidence='{$idresidence}' order by(residencesappart.etat) desc ");

			  		if (!empty($prod)) {?>
		    	
			    		<div class="col-sm-12 col-md-10" style="overflow:auto">
							<table class="table table-hover table-bordered table-striped table-responsive text-center">
								  <thead>
								  	<tr><th colspan="8">Liste des Locataires</th></tr>

								  	
								    <tr>
								      <th scope="col" class="text-center">N°</th>								      
								      <th scope="col">Locataire</th>
								      <th scope="col" class="w-auto">N° Appart</th>
								      <th scope="col">Type</th>
								      <th scope="col">Niveau</th>
								      <th scope="col">m²</th>
								      <th scope="col">Loyer Mensuel</th>
								      <th scope="col" class="text-center" colspan="2">Actions</th>
								    </tr>
								  </thead>
								  <tbody><?php 
								  	foreach ($prod as $key => $value) {
								  		if (!empty($value->idclient)) {
								  		 	$client=$panier->nomClient($value->idclient)[0];
								  		 	$color='';
								  		}else{
								  			$client='Disponible';
								  			$color='success';
								  		} ?>
									    <tr>
									      <th scope="row" class="text-center text-<?=$color;?>"><?=$key+1;?></th>	

									      <td class="text-<?=$color;?>"><?=$client;?></td>	

									      <td class="text-<?=$color;?>"><?=strtoupper($value->numapart);?></td>				      

									      <td class="text-<?=$color;?>">F<?=$value->typeapart;?></td>

									      <td class="text-<?=$color;?>"><?=$value->nivapart;?></td>

									      <td class="text-<?=$color;?>"><?=$value->supapart;?></td>

									      <td class="text-<?=$color;?>"><?=number_format($value->loyer,0,',',' ');?></td>

									      <td><a class="btn btn-info" href="clientinfos.php?infos=<?=$value->idclient;?>">+Infos</a></td>
									      
									      <td><?php if ($_SESSION['level']>7) {?><a class="btn btn-danger" href="residencesInfos.php?delete=<?=$value->id;?>&infos=<?=$value->idresidence;?>&idpart=<?=$value->idapart;?>&idclient=<?=$value->idclient;?>" onclick="return alerteS();">Supprimer</a><?php }?></td>
									    </tr><?php 
									}?>
								    
								  </tbody>
								</table>
						</div><?php 
					}
				}else{


					$prod=$DB->query("SELECT *FROM batimentappart left join residappartlocataire on residencesappart.id=idapart  where residencesappart.idresidence='{$idresidence}' order by(residencesappart.etat) desc ");

			  		if (!empty($prod)) {?>
		    	
			    		<div class="col-sm-12 col-md-10" style="overflow:auto">
							<table class="table table-hover table-bordered table-striped table-responsive text-center">
								  <thead>

								  	
								    <tr>
								      <th scope="col" class="text-center">N°</th>
								      <th scope="col" class="w-auto">N° Appart</th>
								      <th scope="col">Locataire</th>
								      <th scope="col">Type</th>
								      <th scope="col">Niveau</th>
								      <th scope="col">m²</th>
								      <th scope="col">Loyer Mensuel</th>
								      <th scope="col" class="text-center">Actions</th>
								    </tr>
								  </thead>
								  <tbody><?php 
								  	foreach ($prod as $key => $value) {
								  		if (!empty($value->idclient)) {
								  		 	$client=$panier->nomClient($value->idclient)[0];
								  		 	$color='';
								  		}else{
								  			$client='Disponible';
								  			$color='success';
								  		} ?>
									    <tr>
									      <th scope="row" class="text-center text-<?=$color;?>"><?=$key+1;?></th>

									      <td class="text-<?=$color;?>"><?=strtoupper($value->numapart);?></td>

									      <td class="text-<?=$color;?>"><?=$client;?></td>						      

									      <td class="text-<?=$color;?>">F<?=$value->typeapart;?></td>

									      <td class="text-<?=$color;?>"><?=$value->nivapart;?></td>

									      <td class="text-<?=$color;?>"><?=$value->supapart;?></td>

									      <td class="text-<?=$color;?>"><?=number_format($value->loyer,0,',',' ');?></td>
									      
									      <td><?php if ($_SESSION['level']>7) {?><a class="btn btn-danger" href="residencesInfos.php?delete=<?=$value->id;?>&infos=<?=$value->idresidence;?>&idpart=<?=$value->idapart;?>&idclient=<?=$value->idclient;?>" onclick="return alerteS();">Supprimer</a><?php }?></td>
									    </tr><?php 
									}?>
								    
								  </tbody>
								</table>
						</div><?php 
					}
				}?>
	    	</div>
		</div>
	</div>



	</body>
	</html>

	<?php require 'footer.php';
}?>

