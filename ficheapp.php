

  <div style="text-align: center;">
    <ol>
      <li><label style="margin-left:30px;">Matricule</label>.........<?=strtoupper($eleve->matricule);?><?php

      if ($fiche['classe']=='terminale') {?>

        <label style="margin-left:30px;">Classe</label>.................<?=strtoupper($fiche['nomgr']);?><?php

      }elseif ($fiche['nomf']=='maternelle') {?>

        <label style="margin-left:30px;">Classe</label>..................<?=strtoupper($fiche['nomgr']);?><?php
      }else{?>

        <label style="margin-left:30px;">Classe</label>..................<?=strtoupper($fiche['nomgr'])?><?php
      }?>

      </li>

      <li><label >Nom</label>.....................<?=strtoupper($fiche['nomel']);?><label style="margin-left:30px;">Prénom(s)</label>................<?=ucwords(strtolower($fiche['prenomel']));?></li>
      
    </ol>
    
  </div>
</div>