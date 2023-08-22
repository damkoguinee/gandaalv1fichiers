

  <div style="text-align: left; margin-left:50px;">
    <ol>

      <li style="margin-left: 100px; margin-bottom: 30px;"><label >Nom:</label><label style="color: white; background-color: white;">.............</label><?=strtoupper($fiche['nomel']);?><label style="margin-left:80px;">Prénom(s):</label><label style="color: white; background-color: white;">...........</label><?=$fiche['prenomel'];?></li>

      <li><label style="margin-top:5px;">Matricule:</label><label style="color: white; background-color: white; margin-top:5px;">......</label><label style="margin-top:5px;"><?=strtoupper($eleve->matricule);?></label><?php

        if ($fiche['classe']=='terminale') {?>

          <label style="margin-left:49px; margin-top: 5px;">Classe:</label><label style="color: white; background-color: white; margin-top: 5px;">.................</label><label style="margin-top: 5px;"><?=strtoupper($fiche['nomgr'])?></label><?php

        }elseif ($fiche['nomf']=='maternelle') {?>

          <label style="margin-left:49px; margin-top: 5px;">Classe:</label><label style="color: white; background-color: white; margin-top: 5px;">.................</label><label style="margin-top: 5px;"><?=strtoupper($fiche['nomgr'])?></label><?php
        }else{?>

          <label style="margin-left:49px; margin-top: 5px;">Classe:</label><label style="color: white; background-color: white; margin-top: 5px;">.................</label><label style="margin-top: 5px;"><?=strtoupper($fiche['nomgr'])?></label><?php
        }?>

      </li>

      

      <li><?php 

        $filename1="img/".$mat.'.jpg';
        /*

        if ($fiche['nomf']!='primaire') {

          if (file_exists($filename1)) {?>

            <img style="margin-left: 300px;" src="img/<?=$mat;?>.jpg" width="80" height="80"><?php

          }else{?>

            <img style="margin-left: 300px;" src="img/defaut.jpg" width="80" height="80"><?php

          }

        }
        */?></li>
      
    </ol>
    
  </div>
</div>