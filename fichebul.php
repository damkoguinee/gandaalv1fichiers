

  <div style="text-align: left; margin-left:50px;">
    <ol>

      <li style="margin-left: 100px; margin-bottom: 30px;"><label >Nom:</label><label style="color: white; background-color: white;">.............</label><?=strtoupper($fiche['nomel']);?><label style="margin-left:80px;">Prénom(s):</label><label style="color: white; background-color: white;">...........</label><?=$fiche['prenomel'];?></li>

      <li><label style="margin-top:5px;">Matricule:</label><label style="color: white; background-color: white; margin-top:5px;">......</label><label style="margin-top:5px;"><?=strtoupper($eleve->matricule);?></label><?php

        if ($fiche['classe']=='terminale') {
          if ($etab['nom']=='Complexe Scolaire la Plume' and $_SESSION['niveauclasse']!='primaire'){
            if ($fiche['nomgr'] == "7A/A" ) {
              $classe_syntaxe = "7<sup>ème</sup> Année A";
            }elseif ($fiche['nomgr'] == "7A/B" ) {
              $classe_syntaxe = "7<sup>ème</sup> Année B";
            }elseif ($fiche['nomgr'] == "7A/C" ) {
              $classe_syntaxe = "7<sup>ème</sup> Année C";
            }elseif ($fiche['nomgr'] == "8A/A" ) {
              $classe_syntaxe = "8<sup>ème</sup> Année A";
            }elseif ($fiche['nomgr'] == "8A/B" ) {
              $classe_syntaxe = "8<sup>ème</sup> Année B";
            }elseif ($fiche['nomgr'] == "9A/A" ) {
              $classe_syntaxe = "9<sup>ème</sup> Année A";
            }elseif ($fiche['nomgr'] == "9A/B" ) {
              $classe_syntaxe = "9<sup>ème</sup> Année B";
            }elseif ($fiche['nomgr'] == "9A/C" ) {
              $classe_syntaxe = "9<sup>ème</sup> Année C";
            }elseif ($fiche['nomgr'] == "10A/A" ) {
              $classe_syntaxe = "10<sup>ème</sup> Année A";
            }elseif ($fiche['nomgr'] == "10A/B" ) {
              $classe_syntaxe = "10<sup>ème</sup> Année B";
            }elseif ($fiche['nomgr'] == "11ème Littéraire" ) {
              $classe_syntaxe = "11<sup>ème</sup> Année Série Littéraire";
            }elseif ($fiche['nomgr'] == "11ème Scientifique" ) {
              $classe_syntaxe = "11<sup>ème</sup> Année Série Scientifique";
            }elseif ($fiche['nomgr'] == "12SE" ) {
              $classe_syntaxe = "12<sup>ème</sup> Année Sciences Expérimentales";
            }elseif ($fiche['nomgr'] == "12SM" ) {
              $classe_syntaxe = "12<sup>ème</sup> Année Sciences Mathématiques";
            }elseif ($fiche['nomgr'] == "12SS" ) {
              $classe_syntaxe = "12<sup>ème</sup> Année Sciences Sociales";
            }elseif ($fiche['nomgr'] == "TSE" ) {
              $classe_syntaxe = "Terminale Sciences Expérimentales";
            }elseif ($fiche['nomgr'] == "TSM" ) {
              $classe_syntaxe = "Terminale Sciences Mathématiques";
            }else{
              $classe_syntaxe = strtoupper($fiche['nomgr']);
            }?>

            <label style="margin-left:49px; margin-top: 5px;">Classe:</label><label style="color: white; background-color: white; margin-top: 5px;">.................</label><label style="margin-top: 5px;"><?=$classe_syntaxe ;?></label><?php
          }else{?>

            <label style="margin-left:49px; margin-top: 5px;">Classe:</label><label style="color: white; background-color: white; margin-top: 5px;">.................</label><label style="margin-top: 5px;"><?=strtoupper($fiche['nomgr'])?></label><?php
          }

        }elseif ($fiche['nomf']=='maternelle') {?>

          <label style="margin-left:49px; margin-top: 5px;">Classe:</label><label style="color: white; background-color: white; margin-top: 5px;">.................</label><label style="margin-top: 5px;"><?=strtoupper($fiche['nomgr'])?></label><?php
        }else{
          if ($etab['nom']=='Complexe Scolaire la Plume' and $_SESSION['niveauclasse']!='primaire'){
            if ($fiche['nomgr'] == "7A/A" ) {
              $classe_syntaxe = "7<sup>ème</sup> Année A";
            }elseif ($fiche['nomgr'] == "7A/B" ) {
              $classe_syntaxe = "7<sup>ème</sup> Année B";
            }elseif ($fiche['nomgr'] == "7A/C" ) {
              $classe_syntaxe = "7<sup>ème</sup> Année C";
            }elseif ($fiche['nomgr'] == "8A/A" ) {
              $classe_syntaxe = "8<sup>ème</sup> Année A";
            }elseif ($fiche['nomgr'] == "8A/B" ) {
              $classe_syntaxe = "8<sup>ème</sup> Année B";
            }elseif ($fiche['nomgr'] == "9A/A" ) {
              $classe_syntaxe = "9<sup>ème</sup> Année A";
            }elseif ($fiche['nomgr'] == "9A/B" ) {
              $classe_syntaxe = "9<sup>ème</sup> Année B";
            }elseif ($fiche['nomgr'] == "9A/C" ) {
              $classe_syntaxe = "9<sup>ème</sup> Année C";
            }elseif ($fiche['nomgr'] == "10A/A" ) {
              $classe_syntaxe = "10<sup>ème</sup> Année A";
            }elseif ($fiche['nomgr'] == "10A/B" ) {
              $classe_syntaxe = "10<sup>ème</sup> Année B";
            }elseif ($fiche['nomgr'] == "11ème Littéraire" ) {
              $classe_syntaxe = "11<sup>ème</sup> Année Série Littéraire";
            }elseif ($fiche['nomgr'] == "11ème Scientifique" ) {
              $classe_syntaxe = "11<sup>ème</sup> Année Série Scientifique";
            }elseif ($fiche['nomgr'] == "12SE" ) {
              $classe_syntaxe = "12<sup>ème</sup> Année Sciences Expérimentales";
            }elseif ($fiche['nomgr'] == "12SM" ) {
              $classe_syntaxe = "12<sup>ème</sup> Année Sciences Mathématiques";
            }elseif ($fiche['nomgr'] == "12SS" ) {
              $classe_syntaxe = "12<sup>ème</sup> Année Sciences Sociales";
            }elseif ($fiche['nomgr'] == "TSE" ) {
              $classe_syntaxe = "Terminale Sciences Expérimentales";
            }elseif ($fiche['nomgr'] == "TSM" ) {
              $classe_syntaxe = "Terminale Sciences Mathématiques";
            }else{
              $classe_syntaxe = strtoupper($fiche['nomgr']);
            }?>

            <label style="margin-left:49px; margin-top: 5px;">Classe:</label><label style="color: white; background-color: white; margin-top: 5px;">.................</label><label style="margin-top: 5px;"><?=$classe_syntaxe ;?></label><?php
          }else{?>

            <label style="margin-left:49px; margin-top: 5px;">Classe:</label><label style="color: white; background-color: white; margin-top: 5px;">.................</label><label style="margin-top: 5px;"><?=strtoupper($fiche['nomgr'])?></label><?php
          }
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