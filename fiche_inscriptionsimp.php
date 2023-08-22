<?php require '_header.php';?>

<!DOCTYPE html>
<html>
  <head>
    <title>Fiche d'inscription</title>
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
      color: grey;
    }

    ol{
      list-style: none;
      color: grey;
      margin: 0px;

    }

    .infos{
      font-size: 16px;
      font-weight: bold;
      font-style: italic;
      font-family: time new roman;
      color: blue;
    }


  </style><?php

if (isset($_GET['ficheins'])) {?>

   <?php require 'enteteprint.php';

  $etab=$DB->querys('SELECT *from etablissement');

  $pers=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'directeur'));

  $fiche=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, sexe, nationnalite, date_format(naissance,\'%d/%m/%Y \') as naiss, adresse, pere, mere, phone, annee, nomf, classe, nomgr, inscription.codef as codef from eleve  inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join contact on contact.matricule=eleve.matricule where eleve.matricule=:mat and annee=:promo ', array('mat'=>$_GET['ficheins'], 'promo'=>$_SESSION['promo']));

      $paye=$DB->querys('SELECT montant, typepaye, date_format(datepaye, \'%d/%m/%Y \') as datepaye from  payement where matricule=:mat and motif=:motif and payement.promo=:promop', array('mat'=>$_GET['ficheins'],'motif'=>'inscription', 'promop'=>$_SESSION['promo'])); ?>

      <div style="width: 100%; padding: 0px; text-align: center; font-size: 16px; font-weight: bold; margin-top: 0px; margin-bottom: 5px; background-color: white; color: grey;">INSCRIPTION / REINSCRIPTION <label>ANNEE-SCOLAIRE</label> <?=$fiche['annee']-1;?> - <?=$fiche['annee'];?></div>


      <div class="col">

        <table style="width: 100%;">
          <tr><td colspan="2" style="border:2px solid grey;"><div style="width: 100%; padding: 5px; text-align: center; font-size: 16px; font-weight: bold; color: grey;">VOTRE IDENTITE</div></td></tr>
          <tr>
            <td style="font-size: 16px;">

              <ol>

                <li><label>N° Matricule</label>...........<?=strtoupper($fiche['mat']);?></li><?php

                if ($fiche['sexe']=='m') {?>

                  <li><label>Civilité</label>.....................<?='M.';?></li><?php

                }else{?>

                  <li><label>Civilité</label>.....................<?='Mlle/Mme.';?></li><?php

                }?>
                
                <li><label>Nom</label>.........................<?=strtoupper($fiche['nomel']);?></li>

                <li><label>Prénom</label>...................<?=ucfirst(strtolower($fiche['prenomel']));?></li>

                <li><label>Né(e) le</label>...................<?=$fiche['naiss'];?></li>

                <li><label>Nationalité</label>.............<?=ucfirst($fiche['nationnalite']);?></li>
              </ol>
            </td>


            <td style="font-size: 16px; padding-left:50px;">

              <ol>
                <li><label>Nom du père</label>..........<?=ucwords($fiche['pere']);?></li>
                <li><label>Nom de la mère</label>......<?=ucwords($fiche['mere']);?></li>
                <li><label>Adresse</label>....................<?=ucfirst($fiche['adresse']);?></li>
                <li><label>Téléphone</label>...............<?=ucfirst(strtolower($fiche['phone']));?></li>

              </ol>
            </td>
          </tr>

          <tr><td colspan="2" style="border:2px solid grey;"><div style="width: 100%; padding: 5px; text-align: center; font-size: 16px; font-weight: bold; color: grey;">INFORMATION INSCRIPTION/REINSCRIPTION</div></td></tr>

          <tr>
            <td style="font-size: 16px;">

              <ol>

                <li><label>Profil</label>...................... <?=ucwords($fiche['nomf']);?></li><?php

                if ($fiche['classe']==1) {?>

                    <li><label>Niveau</label>.................... <?=ucwords($fiche['classe']).'ère ';?></li><?php
                }elseif($fiche['classe']=='petite section' or $fiche['classe']=='moyenne section' or $fiche['classe']=='grande section' or $fiche['classe']=='terminale'){?>

                  <li><label>Niveau</label>.................... <?=ucwords($fiche['classe']);?></li><?php

                }else{?>

                  <li><label>Niveau</label>.................... <?=ucwords($fiche['classe']).'ème ';?></li><?php
                }?>

                <li><label>Classe</label>......................<?=strtoupper($fiche['nomgr']);?></li>
                
                <li><label>Année-Scolaire</label>...... <?=$fiche['annee']-1;?> - <?=$fiche['annee'];?></li>
              </ol>
            </td>
          </tr>
        </table>

            <div style="display:flex;">
              <div>

                <div style="width: 100%; padding: 5px; text-align: center; font-size: 16px; font-weight: bold; color: grey; border:2px solid grey; margin-left: 2px;">FRAIS D'INSCRIPTION/REINSCRIPTION PAYES</div>

                <div>

                  <ol>
                    <li><label>Montant payé</label>....................<?=number_format($paye['montant'],0,',',' ');?></li>

                    <li><label>Type de payement</label>.............<?=$paye['typepaye'];?></li>

                    <li><label>Date de payement</label>.............<?=$paye['datepaye'];?></li>

                  </ol>
                </div>                  
              </div>

              <div style="margin-left:50px;">

                <div style="width: 100%; padding: 5px; text-align: center; font-size: 16px; font-weight: bold; color: grey; border:2px solid grey">FRAIS DE SCOLARITE 1ère TRANCHE PAYES</div><?php 

                $prodtot=$DB->querys('SELECT sum(montant) as montant, typepaye, datepaye FROM histopayefrais WHERE matricule= ? and promo=? and tranche=?', array($_GET['ficheins'], $_SESSION['promo'], '1ere tranche'));

                $prodtot=$DB->querys('SELECT sum(montant) as montant, typepaye, datepaye FROM histopayefrais WHERE matricule= ? and promo=?', array($_GET['ficheins'], $_SESSION['promo']));

                $prodscola=$DB->querys('SELECT sum(montant) as montant from scolarite where codef=:code and promo=:promo', array('code'=>$fiche['codef'], 'promo'=>$_SESSION['promo']));

                

                $resteannuel=$prodscola['montant']-$prodtot['montant'];?>

                <div>

                  <ol>
                    <li><label>Montant payé</label>....................<?=number_format($prodtot['montant'],0,',',' ');?></li>

                    <li><label>Type de payement</label>.............<?=$prodtot['typepaye'];?></li>

                    <li><label>Date de payement</label>.............<?=(new DateTime($prodtot['datepaye']))->format('d/m/Y');?></li>

                    <li style="color: red;"><label style="color: red;">Reste à Payer Annuel</label>.......<?=number_format($resteannuel,0,',',' ');?></li>

                  </ol>
                </div>                  
              </div>
            </div><?php 
        require 'piedcomptablefiche.php';?>
      </div>

      </div><?php

      if (isset($_GET['areactiver'])) {

        $prodcarte=$DB->query('SELECT eleve.matricule as mat, nomel, prenomel, sexe, nationnalite, date_format(naissance,\'%Y \') as naiss, adresse, pere, mere, phone, telpere, telmere, annee, nomf, classe, nomgr from eleve  inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join contact on contact.matricule=eleve.matricule where inscription.matricule=:mat and annee=:promo ', array('mat'=>$_GET['ficheins'], 'promo'=>$_SESSION['promo']));


      //$paye=$DB->querys('SELECT montant, typepaye, date_format(datepaye, \'%d/%m/%Y \') as datepaye from  payement where matricule=:mat and motif=:motif and payement.promo=:promop', array('mat'=>$_GET['ficheins'],'motif'=>'inscription', 'promop'=>$_SESSION['promo'])); 

      foreach ($prodcarte as $fiche) {?>

      
        <div class="container">

          <div class="carte"><?php

            require 'entetecarte.php';?>

            <div style="display: flex;">

              <div>

                <div style="box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9); width: 100%; padding-top: 5px; padding-bottom: 5px; text-align: center; font-size: 14px; font-family: georgia; font-weight: bold; margin-top: 10px; background-color: white;">CARTE SCOLAIRE</div>

                <div style="margin-left: 5px; margin-bottom: 10px; font-size: 14px; font-family: georgia; font-weight: bold;">Année <?=$fiche->annee-1;?> - <?=$fiche->annee;?></div>

                <div style="margin-left: 1px; box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9);">

                  <div><label>Matricule</label><label class="infos"> <?=strtoupper($fiche->mat);?></label></div>

                  <div><label>Option</label><label class="infos"> <?=ucwords($fiche->nomf);?></label></div>
                  <div><?php

                    if ($fiche->classe==1) {?>

                      <div><label>Classe </label><label class="infos"> <?=$fiche->classe.'ère ';?> Année</label></div><?php

                    }elseif($fiche->classe=='terminale'){?>

                      <div><label>Classe </label><label class="infos"> <?=ucwords($fiche->classe);?></label></div><?php

                    }else{?>

                      <div><label>Classe </label><label class="infos"> <?=$fiche->classe.'ème ';?> Année</label></div><?php

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

              <li><label>Nationalité</label>........<label class="infos"><?=ucwords($fiche->nationnalite);?></label></li>

              <li><label>Contact</label>.........<label class="infos"><?=$fiche->telpere.'/'.$fiche->telmere;?></label></li>

            </ol>
            <div style=" height: 50px; width: 50%; margin: auto; margin-top: -10px; text-align: center; box-shadow: 3px 0px 20px rgba(1, 255, 255, 0.9);">Signature de l'élève</div>

          </div>
        </div><?php
      }
    }
  }?>


