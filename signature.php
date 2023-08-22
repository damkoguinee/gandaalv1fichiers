  <?php

  $pers1=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where pseudo=:pseudo', array('pseudo'=>$_SESSION['pseudo']));

  $pers2=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'Proviseur'));?>
  

  <div  style="margin-top: 20px; color: #717375;"><label style="margin-left: 320px; font-size: 13px; font-style: italic;"><?=ucwords($pers1['type']);?></label></div>

  <div class="pied" style="margin-top: 85px; color: #717375;"><label style="margin-left: 30px; font-size: 13px; font-style: italic;"><?=strtoupper($pers1['nom']).' '.ucwords($pers1['prenom']);?></label></div>

  <div style="margin-top: 10px;border:dashed; "></div><?php

  if (!isset($_GET['courriert'])) {?>
  	</page><?php
  }

  



