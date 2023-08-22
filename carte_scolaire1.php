<?php require '_header.php';
require_once "phpqrcode/qrlib.php";
require_once "phpqrcode/qrconfig.php";?>

<!DOCTYPE html>
<html>
  <head>
    <title>Carte Scolaire</title>
    <meta charset="utf-8">    
  </head>

  <body  onload="window.print()">

  <style type="text/css">

  body{
    margin: 0px;
    width: 100%;
    height:100%;
    padding:0px;
     
  }

  .container{
    display: flex;
    border: 3px solid black;
    border-style: dashed;
    margin-left: 1px;
    margin-bottom: 70px;
  }

  .carte{
    border: 8px solid blue; 
    border-style: double;
    border-radius: 30px;
    width: 50%; 
    margin-top: 10px;
    margin-right: 5px;
    margin-left: 2px;
  }

  .carte1{
    border: 8px solid blue; 
    border-style: double;
    border-radius: 30px;
    width: 50%; 
    margin-right: 2px;
    margin-top: 10px;

  }

    label {
      font-size: 16px;
      font-weight: bold;
      width: 100px;
    }

    ol{
      list-style: none;
    }

    .infos{
      font-size: 16px;
      font-weight: bold;
      font-style: italic;
      font-family: time new roman;
      color: blue;
    }
  </style><?php

  if (isset($_GET['voircarte']) or isset($_GET['voircartel'])) {

    if (isset($_GET['voircarte'])) {

      $prodcarte=$DB->query('SELECT eleve.matricule as mat, nomel, prenomel, sexe, nationnalite, date_format(naissance,\'%Y \') as naiss, adresse, pere, mere, phone, telpere, telmere, annee, nomf, formation.niveau as niveau, formation.codef as codef, classe, nomgr from eleve  inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join contact on contact.matricule=eleve.matricule where nomgr=:mat and annee=:promo ', array('mat'=>$_GET['voircarte'], 'promo'=>$_SESSION['promo']));

    }else{

      $prodcarte=$DB->query('SELECT eleve.matricule as mat, nomel, prenomel, sexe, nationnalite, date_format(naissance,\'%Y \') as naiss, adresse, pere, mere, phone, telpere, telmere, annee, nomf, formation.codef as codef, classe, nomgr, formation.niveau as niveau from eleve  inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join contact on contact.matricule=eleve.matricule where inscription.matricule=:mat and annee=:promo ', array('mat'=>$_GET['voircartel'], 'promo'=>$_SESSION['promo']));

    }


    //$paye=$DB->querys('SELECT montant, typepaye, date_format(datepaye, \'%d/%m/%Y \') as datepaye from  payement where matricule=:mat and motif=:motif and payement.promo=:promop', array('mat'=>$_GET['ficheins'],'motif'=>'inscription', 'promop'=>$_SESSION['promo'])); 

    foreach ($prodcarte as $key=> $fiche) {
      $mat=$fiche->mat;
      $codeContent=$mat;
      $fileName=$mat.".png";
      $cheminQrcode='qrcode/'.$fileName;
      if (!file_exists($cheminQrcode)) {
          QRcode::png($codeContent, $cheminQrcode);
      }?>

    
      <div class="container">

        <div class="carte"><?php

          require 'entetecarte1.php';?>

          <div style="display: flex;">

            <div>

              <div style="margin-left: 1px; margin-top: 20px; box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9);">

                <div><label>Matricule</label><label class="infos"> <?=strtoupper($fiche->mat);?></label></div>
                
                <div><?php

                  if ($fiche->classe==1) {?>

                    <div><label>Niveau</label><label class="infos"> <?=ucwords($fiche->nomf);?></label></div>

                    <div><label>Classe </label><label class="infos"> <?=$fiche->classe.'ère ';?> Année</label></div><?php

                  }elseif($fiche->classe=='terminale'){?>

                    <div><label>Option</label><label class="infos"> <?=ucwords($fiche->nomf);?></label></div>

                    <div><label>Classe </label><label class="infos"> <?=ucwords($fiche->classe);?></label></div><?php

                  }elseif($fiche->niveau=='maternelle'){?>

                    <div><label>Niveau</label><label class="infos"> <?=ucwords($fiche->nomf);?></label></div>

                    <div><label>Classe </label><label class="infos"> <?=ucwords($fiche->classe);?></label></div><?php

                  }else{

                    if($fiche->niveau=='lycee'){?>

                      <div><label>Option</label><label class="infos"> <?=ucwords($fiche->nomf);?></label></div>
                      <div><label>Classe </label><label class="infos"> <?=$fiche->classe.'ème ';?> Année</label></div><?php

                    }else{?>
                      <div><label>Niveau</label><label class="infos"> <?=ucwords($fiche->nomf);?></label></div>
                      <div><label>Classe </label><label class="infos"> <?=$fiche->classe.'ème ';?> Année</label></div><?php
                    }

                  }?>
                </div>
              </div>

            </div>

            <div>

              <div style="margin-left: 70px;"><?php

                $filename="img/".$fiche->mat.'.jpg';
                if (file_exists($filename)) {?>

                  <img style="margin-top: 1px; border-radius: 30px; box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9);" src="img/<?=$fiche->mat;?>.jpg" width="85" height="80"><?php

                }else{?>

                  <img style="margin-top: 5px;  border-radius: 30px; box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9);" src="img/defaut.jpg" width="85" height="80"><?php

                }?>
              </div>
              <div style="margin-left: 80px; font-style: italic;">Le Directeur</div>
              <div style="font-size: 14px; margin-top: 30px; margin-left: 10px; font-style: italic;"><?=strtoupper($pers1['nom']).' '.ucwords($pers1['prenom']);?></div>            
              
            </div>
            <div style="margin-left: 5px;">
              <img src="<?=$cheminQrcode;?>" class="card-img-top" alt="photo-enseignant">
            </div>  
          </div>
          

        
        </div>
        

        <div class="carte1">

          <ol><?php

            if ($fiche->sexe=='m') {?>

              <li><label>Civilité</label>.................<label class="infos"><?='Mr.';?></label></li><?php

            }else{?>

              <li><label>Civilité</label>.................<label class="infos"><?='Mlle/Mme.';?></label></li><?php

            }?>
        
            <li><label>Nom</label>.....................<label class="infos"><?=strtoupper($fiche->nomel);?></label></li>

            <li><label>Prénom</label>...............<label class="infos"><?=ucwords(strtolower($fiche->prenomel));?></label></li>

            <li><label>Né(e) en</label>..............<label class="infos"><?=$fiche->naiss;?></label></li>

            <li><label>A</label>..........................<label class="infos"><?=ucwords($fiche->adresse);?></label></li>

            <li><label>Fils de</label>.................<label class="infos"><?=ucwords($fiche->pere);?></label></li>

            <li><label>Et de</label>...................<label class="infos"><?=ucwords($fiche->mere);?></label></li>

            <li><label>Nationalité</label>.........<label class="infos"><?=ucwords($fiche->nationnalite);?></label></li>

            <li><label>Contact</label>...............<label class="infos"><?=$fiche->telpere.'/'.$fiche->telmere;?></label></li>

          </ol>
          <div style=" height: 50px; width: 50%; margin: auto; margin-top: -15px; text-align: center; box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9);">Signature de l'élève</div>

        </div>
      </div><?php
    }
  }?>
</body>
</html>


