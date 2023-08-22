  <?php

    $pers1=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=? or type=?', array('Directeur du primaire', 'Directrice du Préscolaire/Primaire'));

     $prodens=$DB->querys('SELECT codens from enseignement where nomgr=:nom and promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

    $enseignantsig=$prodens['codens'];
    $_SESSION['enseignantsig']=$enseignantsig;

    $pers2=$DB->querys('SELECT nomen as nom, prenomen as prenom, type from enseignant inner join login on enseignant.matricule=login.matricule where login.matricule=:type', array('type'=>$_SESSION['enseignantsig']));

  ?>
  

  <div  style="margin-top: 20px; color: grey;">

  	<label style="margin-left: 10px; font-style: italic;">Maitre/Maitresse</label>
  	
  	<label style="margin-left: 200px; font-style: italic;">Direction</label>

    <label style="margin-left: 200px; font-style: italic;">Parents</label>

  </div>
</page>
