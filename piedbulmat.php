  <?php   
      

  $pers1=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=? or type=?', array('Directeur du primaire', 'Directrice du Préscolaire/Primaire'));

  $prodens=$DB->querys('SELECT codens from enseignement where nomgr=? and promo=? and codens!=? and codens!=? and codens!=?', array($_SESSION['groupe'], $_SESSION['promo'], 'cspe92', 'cspe132', 'cspe128'));

  $enseignantsig=$prodens['codens'];
  $_SESSION['enseignantsig']=$enseignantsig;

  $pers2=$DB->querys('SELECT nomen as nom, prenomen as prenom, type, sexe from enseignant inner join login on enseignant.matricule=login.matricule where login.matricule=:type', array('type'=>$_SESSION['enseignantsig']));?>

  <div  style="margin-top: 10px; color: grey;">

    <label style="margin-left: 160px;"><?php if ($pers2['sexe']=='f') { echo('La maitresse');}else{echo "Le maître";}?></label>


    <label style="margin-left: 230px;"><?php if ($pers1['sexe']=='f') { echo('La Directrice');}else{echo "Le Directeur";}?></label>

  </div>

  <div class="pied" style="margin-top: 90px; color: grey;">

    <label style="margin-left: 65px;"><?=strtoupper($pers2['nom']).' '.ucwords($pers2['prenom']);?></label>
    
    <label style="margin-left: 150px;"><?=strtoupper($pers1['nom']).' '.ucwords($pers1['prenom']);?></label>
  </div>
  

  
</page>
