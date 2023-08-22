  <?php

  $pers1=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'Admistrateur General'));

  $pers3=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'Comptable'));?>
  

  <div  style="margin-top: 20px; color: grey;">
    <label style="margin-left: 100px;"><?=ucwords($pers3['type']);?></label>
    
    <label style="margin-left: 350px;"><?=ucwords($pers1['type']);?></label>
  </div>

  <div class="pied" style="margin-top: 95px; color: grey;">
    <label style="margin-left: 80px;"><?=strtoupper($pers3['nom']).' '.ucwords($pers3['prenom']);?></label>
    
    <label style="margin-left: 300px;"><?=strtoupper($pers1['nom']).' '.ucwords($pers1['prenom']);?></label>
  </div>

  <div style="text-align: center; font-style: italic;color: grey;">Edité à <?=ucfirst($etab['region']).', le '.date('d/m/Y');?></div>
</page>