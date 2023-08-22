<?php require '_header.php';?>

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

  .rep{
    display: flex;
    flex-wrap: wrap;
  }

  .container{
    margin-left: 30px;
    width: 45%;
    margin-bottom: 45px;
  }

  .carte{
    border: 4px solid blue; 
    border-style: double;
    border-radius: 10px;
    width: 100%; 
  }

    label {
      font-size: 14px;
      font-weight: bold;
      width: 100px;
    }

    ol{
      list-style: none;
    }

    .pointille{

      color: white;

    }

    .infos{
      font-size: 14px;
      font-weight: bold;
      font-style: italic;
      font-family: time new roman;
      color: blue;
    }
  </style><?php

  if (isset($_GET['voircarte']) or isset($_GET['voircartel'])) {

    if (isset($_GET['voircarte'])) {

      $prodcarte=$DB->query('SELECT eleve.matricule as mat, nomel, prenomel, sexe, nationnalite, date_format(naissance,\'%Y \') as naiss, adresse, pere, mere, phone, telpere, telmere, annee, nomf, formation.codef as codef, classe, nomgr from eleve  inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join contact on contact.matricule=eleve.matricule where nomgr=:mat and annee=:promo ', array('mat'=>$_GET['voircarte'], 'promo'=>$_SESSION['promo']));

    }else{

      $prodcarte=$DB->query('SELECT eleve.matricule as mat, nomel, prenomel, sexe, nationnalite, date_format(naissance,\'%Y \') as naiss, adresse, pere, mere, phone, telpere, telmere, annee, nomf, formation.codef as codef, classe, nomgr from eleve  inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join contact on contact.matricule=eleve.matricule where inscription.matricule=:mat and annee=:promo ', array('mat'=>$_GET['voircartel'], 'promo'=>$_SESSION['promo']));

    }


    //$paye=$DB->querys('SELECT montant, typepaye, date_format(datepaye, \'%d/%m/%Y \') as datepaye from  payement where matricule=:mat and motif=:motif and payement.promo=:promop', array('mat'=>$_GET['ficheins'],'motif'=>'inscription', 'promop'=>$_SESSION['promo']));?>

    <div class="rep"><?php 

      foreach ($prodcarte as $fiche) {?>

      
        <div class="container">

          <div class="carte"><?php

            require 'entetecarte.php';?>

            <div >

            <div style="display: flex;">

              <div style="width:70%; ">

                <div style="margin-left: 1px; box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9);">

                  <div><label>Nom:</label><label class="pointille">.............</label></label><label class="infos"> <?=strtoupper($fiche->nomel);?></label></div>

                  <div><label>Prénom(s):</label><label class="pointille">...</label><label class="infos"> <?=ucwords($fiche->prenomel);?></label></div>

                  <div><label>Matricule:</label><label class="pointille">....</label><label class="infos"> <?=strtoupper($fiche->mat);?></label></div>

                  <div><label>Classe:</label><label class="pointille">.........</label><label class="infos"> <?=strtoupper($fiche->nomgr);?></label></div>

                </div>

              </div>

              <div>

                <div style="margin-left: 0px;"><?php

                  $filename="img/".$fiche->mat.'.jpg';
                  if (file_exists($filename)) {?>

                    <img style="margin-top: 1px; border-radius: 10px; box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9);" src="img/<?=$fiche->mat;?>.jpg" width="70" height="70"><?php

                  }else{?>

                    <img style="margin-top: 1px;  border-radius: 10px; box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9);" src="img/defaut.jpg" width="70" height="70"><?php

                  }?>
                </div>
              </div>
            </div>

            <div style="margin-left: 53%; font-style: italic; font-size: 10px;">L'Administrateur Général</div>

             <div style="margin-left: 60%; font-size: 10px; margin-top: 0px; font-style: italic;"><img src="img/signature.png" width="50" height="20"></div>

            <div style="margin-left: 50%; font-size: 10px; margin-top: 0px; font-style: italic;"><?=strtoupper($pers1['nom']).' '.ucwords($pers1['prenom']);?></div></div>

           
              

            <div style="box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9); border: 1px solid blue; border-radius: 5px; width: 99.99%; text-align: center; font-size: 7px; font-family: georgia; font-weight: bold; margin: 0px; background-color: white; color:red;">
              <div style="margin-top:1px; margin-bottom: 1px;">Sis à <?=ucwords($etab['adresse']).' / Commune de '.ucwords($etab['secteur']).', Tél: '.ucwords($etab['phone']);?><div>
                
            </div>

          
          </div>

          
        </div>
        </div></div><?php
      }?>
      </div><?php
  }?>
</body>
</html>


