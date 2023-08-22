<div class="entete" style="display: flex; margin-left: 10px; margin-right: 10px; "><?php

  $etab=$DB->querys('SELECT *from etablissement');
  $pers1=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'Admistrateur General'));

  $pers2=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'Proviseur'));

  $pers=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'directeur'));?> 

  <div><img style="margin-left: 10px; border-radius: 30px; box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9);" src="css/img/logo.jpg" width="60" height="40"></div>   

  <div style="margin: auto; font-size: 15px; color: blue; font-weight:bold;"><?=$etab['nom'];?><br/>

    <label style="font-size: 12px;font-style: italic; margin-left: 30px; color:black;"><?=ucwords($etab['devise']);?></label>

  </div>  
        
</div>

<div style="box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9); border-bottom: 4px solid blue; width: 100%; text-align: center; font-size: 14px; font-family: georgia; font-weight: bold; margin-top: 1px; background-color: white; color:red;">
	<div style="margin-left: 35px;">CARTE SCOLAIRE</div>

	<div style="margin-left: 35px; color: black; font-size: 11px;">Année-Scolaire <?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></div>
</div>


