<?php
require_once "lib/html2pdf.php";

ob_start(); ?>

<?php require '_header.php';
$etat = 'actif';
$prodmat=$DB->querys('SELECT  codef from inscription  where etatscol=:etat and nomgr=:nom and annee=:promo ', array('etat' => $etat, 'nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

$prodmatiere=$DB->query('SELECT nommat, codem, coef, cat from  matiere where codef=:nom order by(cat)', array('nom'=>$prodmat['codef']));

$nbremat=sizeof($prodmatiere);
$bdd='relevebulannuel'; 
$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matricule` varchar(50) NOT NULL,
  `codem` varchar(50) DEFAULT NULL,
  `coefm` float DEFAULT NULL,
  `moyenne` float DEFAULT NULL,
  `trimestre` int(11) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `promo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2121 DEFAULT CHARSET=utf8");

if ($_SESSION['niveauclasse']!='primaire') {
  if ($nbremat<=10) {
    $height='12px';
    $padding='5px';
  }else{
    $height='10px';
    $padding='0px';
  }

  $marginleft='50px';

}else{

  if ($nbremat<13) {
    $height='15px';
    $padding='5px';
  }elseif ($nbremat==12) {
    $height='13px';
    $padding='5px';
  }else{
    $height='10px';
    $padding='0px';
  }

  $marginleft='0px';
}
?>

<style type="text/css">

  body{
    margin: 0px;
    width: 100%;
    height:100%;
    padding:0px;
  }
  .entete{
    width: 100%;

  }

  .pied{
    text-align: center;
    margin-top: 20px;
    margin-right: 80px;
    font-size: 20px;
    font-style: italic;
  }

  .symbole{
    margin: 30px;
    margin-top: 500px;
    margin-left: 0px;
    margin-right: 100px;

  }

  .etat{
    margin-top: 30px;
    margin-left: 10px;
    font-weight: bold;
    font-size: 12px;
    color: #717375;
  }

  table.tablistebul{
    width: 100%;
    margin-left:<?=$marginleft;?>;
    border-collapse: collapse;
  }

  .tablistebul th {
    line-height: 3mm;
    border: 1px solid black;
    color: grey;
    font-size: 15px;
    text-align: center;
    padding: 10px;
  }
  .tablistebul td {
    height: <?=$height;?>;
    padding-top: <?=$padding;?>;
    border: 1px solid black;

    text-align: right;
    padding-right: 10px;
    font-size: 15px;
    padding-right: 5px;
  }

  label {
    float: right;
    font-size: 16px;
    
    width: 200px;
  }

  ol{
    list-style: none;
    font-size: 16px;
  }

  .checkbox{
    width:25px;
    height:15px;
    border:1px solid black;
    margin-left:5px;
  }
</style><?php

$prodmat=$DB->query('SELECT  inscription.matricule as matricule from inscription inner join eleve on inscription.matricule=eleve.matricule where etatscol=:etat and nomgr=:nom and annee=:promo order by (prenomel)', array('etat' => $etat, 'nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));


foreach ($prodmat as $eleve) {

  $prodgr=$DB->querys('SELECT codef from inscription where matricule=:mat and annee=:promo', array('mat'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));

  $prodmatiere=$DB->query('SELECT nommat, codem, coef, cat from  matiere where codef=:nom order by(cat)', array('nom'=>$prodgr['codef']));

  $tot1=0;
  $coefint=0;

  foreach ($prodmatiere as $matiere) {

    if ($_SESSION['niveauclasse']!='primaire') {

      $annuel=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));
    }else{

       $annuel=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));

    }

    foreach ($annuel as $moyenne) {
                    

      $tota=($moyenne->mgen*$moyenne->coefm);
      $tot1+=$tota;

      $coefint+=$moyenne->coefm;


    }
  }

    if (!empty($coefint)) {
      $moyeng=$tot1/$coefint;
    }else{
      $moyeng=0;
    }

    $DB->insert('INSERT INTO rangel(matricule, moyenne, rang, pseudo) values( ?, ?, ?, ?)', array($eleve->matricule, $moyeng, 1, $_SESSION['pseudo']));

    $produ=$DB->query("SELECT  moyenne, matricule from rangel where pseudo='{$_SESSION['pseudo']}' order by(moyenne)desc");

    foreach ($produ as $key => $value) {

      $DB->insert('UPDATE rangel SET rang = ? where matricule=? and pseudo=?', array($key+1, $value->matricule, $_SESSION['pseudo']));
    
    }
}

if (isset($_GET['annuel'])) {

  if (isset($_GET['indi'])) {

    $matriculeindi=$_GET['indi'];

    $prodmat=$DB->query('SELECT  inscription.matricule as matricule from inscription inner join eleve on inscription.matricule=eleve.matricule where eleve.matricule=:matr and nomgr=:nom and annee=:promo order by (prenomel)', array('matr'=>$matriculeindi, 'nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

  }else{

    $prodmat=$DB->query('SELECT  inscription.matricule as matricule from inscription inner join eleve on inscription.matricule=eleve.matricule where etatscol=:etat and nomgr=:nom and annee=:promo order by (prenomel)', array('etat' => $etat, 'nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
  }

  //$prodmatcount=$DB->query('SELECT  inscription.matricule as matricule from inscription inner join eleve on inscription.matricule=eleve.matricule where nomgr=:nom and annee=:promo order by (prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

  //$count=sizeof($prodmatcount);
  $prodmatcount=$DB->querys('SELECT count(DISTINCT(effectifn.matricule)) as coef from effectifn inner join inscription on inscription.matricule = effectifn.matricule where etatscol=:etat and annee=:annee and  effectifn.nomgr=:nom and promo=:promo', array('etat' => $etat, 'annee'=> $_SESSION['promo'], 'nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
              
  if ($prodmatcount['coef']!=0) {
       $count=$prodmatcount['coef'];// nbre élève
  }else{
       $count=1;
  }

  foreach ($prodmat as $eleve) {

    $prodeval=$DB->querys('SELECT note.id as id from note inner join devoir on devoir.id=codev where matricule=:mat and promo=:promo', array('mat'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));

    if (!empty($prodeval['id'])) {?>

      <page backtop="5mm" backleft="5mm" backright="5mm" backbottom="5mm">

        <div class="body"><?php

          require 'entetebul.php';

          $fiche=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, pere, telpere, mere, telmere, date_format(naissance,\'%d/%m/%Y \') as naiss, phone, email , annee, nomf, classe, nomgr from eleve inner join contact on eleve.matricule=contact.matricule inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef where eleve.matricule=:mat and annee=:promo', array('mat'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));?>

           <div style="width: 80%; background-color: white; color: #717375; border: 0.5px solid grey; border-style: dotted; margin-top:25px;">

            <div style="width: 100%; text-align: center; font-size: 16px; font-weight: bold; background-color: white;">BULLETIN DE NOTES ANNUEL</div>
            
            <div style="width: 100%; text-align: center; font-size: 16px; font-weight: bold; background-color: white;">Année-Scolaire <?=$fiche['annee']-1;?> - <?=$fiche['annee'];?></div><?php

          

          $mat=$eleve->matricule;
          $filename1="img/".$mat.'.jpg';

          require 'fichebul.php';

          if ($_GET['trimestre']!='Semestre'){?>        

            <div class="col" style="margin:auto; margin-top: 0px; ">

              <table class="tablistebul">
                <thead>
                  <tr>
                    <th>Matières</th>
                    <th>Coeff</th>
                    <th>Trimestre 1</th>
                    <th>Trimestre 2</th>
                    <th>Trimestre 3</th>
                    <th>Annuel</th>
                    <th>Appréciations</th>
                  </tr>
                </thead>
                <tbody><?php

                  $prodgr=$DB->querys('SELECT codef from inscription where matricule=:mat and annee=:promo', array('mat'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));


                  $cat = array(
                    1   => 'sciences exactes',
                    2   => 'sciences litteraires',
                    3   => 'c/svt',
                    4   => 'facultatives',
                    5   => 'catégorie essentielle',
                    6   => 'catégorie francais',
                    7   => 'catégorie math/calcul',
                    8   => 'catégorie leçon d éveil',
                    9   => 'autres'
                    
                  );

                  $tot1=0;
                  $tot2=0;
                  $tot3=0;
                  $tota=0;
                  $coefint=0;
                  $coefint1=0;
                  $coefint2=0;
                  $coefint3=0;
                  $coefcomp=0;
                  $coefmat=0;
                  $coefgen=0;

                  foreach ($cat as $value) {
                    
                    $prodmatiere=$DB->query('SELECT nommat, codem, coef, cat from  matiere where codef=:nom and cat=:cat order by(cat)', array('nom'=>$prodgr['codef'], 'cat'=>$value));

                    if (!empty($prodmatiere)) {?>

                      <tr>
                      <td colspan="7" style="text-align: center; color: #717375; "><?php 

                        if ($value=="catégorie leçon d éveil") {
                          $categoriemat="catégorie leçon d'éveil";
                        }else{
                          $categoriemat=$value;
                        }

                        if ($value=='autres') {
                          if ($fiche['classe']<=2) {
                            // code...
                          }else{
                            echo ucwords('catégorie '.$categoriemat);
                          }
                        }else{
                          echo ucwords($categoriemat);
                        }?>
                          
                        </td>
                      </tr><?php
                    }

                    
                    $tot1c=0;
                    $tot2c=0;
                    $tot3c=0;
                    $totac=0;
                    $coefintc=0;
                    $coefintc1=0;
                    $coefintc2=0;
                    $coefintc3=0;
                    $coefcompc=0;
                    $coefmatc=0;
                    $coefgenc=0;
                    foreach ($prodmatiere as $matiere) {

                      if ($_SESSION['niveauclasse']!='primaire') {

                        $prod1=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'sem'=>1, 'promo'=>$_SESSION['promo']));

                        //2ème semestre

                        $prod2=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'sem'=>2, 'promo'=>$_SESSION['promo']));

                        //3ème semestre

                        $prod3=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'sem'=>3, 'promo'=>$_SESSION['promo']));

                        // Annuel

                        $annuel=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));
                      }else{
                        $prod1=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'sem'=>1, 'promo'=>$_SESSION['promo']));

                        //2ème semestre

                        $prod2=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'sem'=>2, 'promo'=>$_SESSION['promo']));

                        //3ème semestre

                        $prod3=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'sem'=>3, 'promo'=>$_SESSION['promo']));

                        // Annuel

                        $annuel=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));
                      }

                      if ($matiere->coef>0) {?>

                        <tr>
                          <td style="text-align: left;"><?=ucfirst($matiere->nommat);?></td>
                          <td style="text-align: center;"><?=$matiere->coef;?></td><?php

                          $coefmat+=$matiere->coef;
                          $coefmatc+=$matiere->coef;
                                
                          foreach ($prod1 as $moyenne) {
                            $tot1+=($moyenne->mgen*$moyenne->coefm);
                            $tot1c+=($moyenne->mgen*$moyenne->coefm);

                            $coefint+=$moyenne->coefm;

                            $coefint1+=$moyenne->coefm;
                            $coefintc+=$moyenne->coefm;
                            $coefintc1+=$moyenne->coefm;?>

                            <td style="padding-right: 10px; text-align: right;"><?=number_format($moyenne->mgen,2,',',' ');?></td><?php
                          }
                          //fin 1er semestre
                                
                          foreach ($prod2 as $moyenne) {

                            $tot2+=($moyenne->mgen*$moyenne->coefm);
                            $coefcomp+=$moyenne->coefm;
                            $coefint2+=$moyenne->coefm;
                            $tot2c+=($moyenne->mgen*$moyenne->coefm);
                            $coefcompc+=$moyenne->coefm;

                            $coefintc2+=$moyenne->coefm;?>

                            <td style="padding-right: 10px; text-align: right;"><?=number_format($moyenne->mgen,2,',',' ');?></td><?php
                          }

                          //fin 2eme semestre

                          foreach ($prod3 as $moyenne) {

                            $tot3+=($moyenne->mgen*$moyenne->coefm);
                            $coefcomp+=$moyenne->coefm;
                            $coefint3+=$moyenne->coefm;
                            $tot3c+=($moyenne->mgen*$moyenne->coefm);
                            $coefcompc+=$moyenne->coefm;

                            $coefintc3+=$moyenne->coefm;?>

                            <td style="padding-right: 10px; text-align: right;"><?=number_format($moyenne->mgen,2,',',' ');?></td><?php
                          }

                        //Annuel

                        foreach ($annuel as $moyenne) {
                          if ($_SESSION['niveauclasse']!='primaire') {
                            if ($moyenne->mgen==0) {
                              $appreciation='';
                            }elseif ($moyenne->mgen>0 and $moyenne->mgen<=5) {
                              $appreciation='Faible';
                            }elseif ($moyenne->mgen>5 and $moyenne->mgen<10) {
                              $appreciation='Insuffisant';
                            }elseif ($moyenne->mgen>=10 and $moyenne->mgen<11) {
                              $appreciation='Passable';
                            }elseif ($moyenne->mgen>=11 and $moyenne->mgen<14) {
                              $appreciation='Assez-bien';
                            }elseif ($moyenne->mgen>=14 and $moyenne->mgen<16) {
                              $appreciation='Bien';
                            }elseif ($moyenne->mgen>=16 and $moyenne->mgen<=20) {
                              $appreciation='Très-Bien';
                            }else{
                            }
                          }else{

                            if ($moyenne->mgen==0) {
                              $appreciation='';
                            }elseif ($moyenne->mgen>0 and $moyenne->mgen<5) {
                              $appreciation='Insuffisant';
                            }elseif ($moyenne->mgen>=5 and $moyenne->mgen<6) {
                              $appreciation='Passable';
                            }elseif ($moyenne->mgen>=6 and $moyenne->mgen<8) {
                              $appreciation='Assez-Bien';
                            }elseif ($moyenne->mgen>=8 and $moyenne->mgen<10) {
                              $appreciation='Bien';
                            }elseif ($moyenne->mgen==10) {
                              $appreciation='Très-Bien';
                            }else{
                            }

                          }

                          $tota+=($moyenne->mgen*$moyenne->coefm);
                          $coefgen+=$moyenne->coefm;
                          $totac+=($moyenne->mgen*$moyenne->coefm);
                          $coefgenc+=$moyenne->coefm;?>

                          <td style="padding-right: 10px; text-align: right;"><?=number_format($moyenne->mgen,2,',',' ');?></td>

                          <td style="text-align: left; padding-left: 3px;"><?=$appreciation;?></td>
                              
                          </tr><?php
                        }
                      }

                    }
                    if (!empty($prodmatiere)) {

                      if ($coefgenc==0) {
                        $moyenc=0;
                      }else{
                        $moyenc=$totac/$coefgenc;
                      }

                      if ($_SESSION['niveauclasse']!='primaire') {

                        if ($moyenc>=0 and $moyenc<=5) {
                          $appreciation='Faible';
                        }elseif ($moyenc>5 and $moyenc<10) {
                          $appreciation='Insuffisant';
                        }elseif ($moyenc>=10 and $moyenc<11) {
                          $appreciation='Passable';
                        }elseif ($moyenc>=11 and $moyenc<14) {
                          $appreciation='Assez-bien';
                        }elseif ($moyenc>=14 and $moyenc<16) {
                          $appreciation='Bien';
                        }elseif ($moyenc>=16 and $moyenc<=20) {
                          $appreciation='Très-Bien';
                        }else{
                        }
                      }else{

                        if ($moyenc>=0 and $moyenc<5) {
                          $appreciation='Insuffisant';
                        }elseif ($moyenc>=5 and $moyenc<6) {
                          $appreciation='Passable';
                        }elseif ($moyenc>=6 and $moyenc<8) {
                          $appreciation='Assez-Bien';
                        }elseif ($moyenc>=8 and $moyenc<10) {
                          $appreciation='Bien';
                        }elseif ($moyenc==10) {
                          $appreciation='Très-Bien';
                        }else{
                        }

                      }

                      if ($_SESSION['niveauclasse']!='primaire') {?>

                        <tr>
                          <td style="border: 1px; text-align: center; color: grey;">Total</td>

                          <td style="border: 1px; text-align: center; color: grey;"><?=$coefmatc;?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;"><?=number_format(($tot1c),2,',',' ');?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;"><?=number_format(($tot2c),2,',',' ');?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;"><?=number_format(($tot3c),2,',',' ');?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;"><?=number_format(($totac),2,',',' ');?></td>

                          <td style="border: 1px; text-align: center; color: grey;" ><?=$appreciation;?></td>
                          
                        </tr><?php
                      }else{
                        if (empty($coefintc1)) {
                          $coefintc1=1;
                        }

                        if (empty($coefintc2)) {
                          $coefintc2=1;
                        }
                        if (empty($coefintc3)) {
                          $coefintc3=1;
                        }
                        if (empty($coefgenc)) {
                          $coefgenc=1;
                        }

                        if ($coefintc1==0) {
                          $moyencat1=$tot1c;
                          $moyencat2=$tot2c/$coefintc2;
                          $moyencat3=$tot3c/$coefintc3;
                          $moyencat=$totac/$coefgenc;
                        }elseif ($coefintc2==0) {
                          $moyencat2=$tot2c;
                          $moyencat1=$tot1c/$coefintc1;
                          $moyencat3=$tot3c/$coefintc3;
                          $moyencat=$totac/$coefgenc;
                        }elseif ($coefintc3==0) {
                          $moyencat3=$tot3c;
                          $moyencat2=$tot2c/$coefintc2;
                          $moyencat1=$tot1c/$coefintc1;
                          $moyencat=$totac/$coefgenc;
                        }else{
                          $moyencat1=$tot1c/$coefintc1;
                          $moyencat2=$tot2c/$coefintc2;
                          $moyencat3=$tot3c/$coefintc3;
                          $moyencat=$totac/$coefgenc;
                        }?>

                        <tr>
                          <td style="border: 1px; text-align: center; color: grey;">Moyenne</td>

                          <td style="border: 1px; text-align: center; color: grey;"><?=$coefmatc;?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;"><?=number_format(($moyencat1),2,',',' ');?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;"><?=number_format(($moyencat2),2,',',' ');?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;"><?=number_format(($moyencat3),2,',',' ');?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;"><?=number_format(($moyencat),2,',',' ');?></td>

                          <td style="border: 1px; text-align: center; color: grey;" ><?=$appreciation;?></td>
                          
                        </tr><?php

                      }
                    }
                  }
                  
                  if (empty($tot1)) {
                    $tot1=0;
                    $abst1=1;
                  }else{
                    $abst1=0;
                  }
                  if (empty($tot2)) {
                    $tot2=0;
                    $abst2=1;
                  }else{
                    $abst2=0;
                  }
                  if (empty($tot3)) {
                    $tot3=0;
                    $abst3=1;
                  }else{
                    $abst3=0;
                  }
                  $nbreabst=3-$abst1-$abst2-$abst3; ?>

                  <tr>
                      <th>Total</th>

                      <th style="padding-right: 10px; text-align: center;"><?=$coefmat;?></th>

                      <th style="padding-right: 10px; text-align: right;"><?=number_format($tot1,2,',',' ');?></th>

                      <th style="padding-right: 10px; text-align: right;"><?=number_format($tot2,2,',',' ');?></th>

                      <th style="padding-right: 10px; text-align: right;"><?=number_format($tot3,2,',',' ');?></th>

                      <th style="padding-right: 10px; text-align: right;"><?=number_format((($tot1+$tot2+$tot3)/$nbreabst),2,',',' ');?></th>

                      <th style="padding-right: 10px; text-align: right; border-bottom: 0px;"></th>
                      
                  </tr>

                  <tr>
                      <th colspan="2">Moyenne</th><?php
                      if (empty($coefint1)) {
                        $coefint1=1;
                        $abs1=1;
                      }else{
                        $abs1=0;
                      }
                      if (empty($coefint2)) {
                        $coefint2=1;
                        $abs2=1;
                      }else{
                        $abs2=0;
                      }
                      if (empty($coefint3)) {
                        $coefint3=1;
                        $abs3=1;
                      }else{
                        $abs3=0;
                      }

                      if (!empty($coefint1)) {?>
                          
                        <th style="padding-right: 10px; text-align: right;"><?=number_format($tot1/$coefint1,2,',',' ');?></th><?php

                      }else{?>

                        <th style="padding-right: 10px; text-align: right;"></th><?php

                      }

                      if (!empty($coefint2)) {?>
                          
                        <th style="padding-right: 10px; text-align: right;"><?=number_format($tot2/$coefint2,2,',',' ');?></th><?php

                      }else{?>

                        <th style="padding-right: 10px; text-align: right;"></th><?php

                      }

                      if (!empty($coefint3)) {?>
                          
                        <th style="padding-right: 10px; text-align: right;"><?=number_format($tot3/$coefint3,2,',',' ');?></th><?php

                      }else{?>

                        <th style=" padding-right: 3px; text-align: center;"></th><?php

                      }

                      if (!empty($coefgen)) {

                        $moyeng=$tota/$coefgen;
                        
                        $nbret=3-$abs1-$abs2-$abs3;
                        $totmoyeng=(($tot1/$coefint1)+($tot2/$coefint2)+($tot3/$coefint3))/$nbret;?>

                        <th style=" padding-right: 3px; text-align: center;"><?=number_format(($totmoyeng),2,',',' ');?></th><?php

                      }else{
                        $moyeng=0;?>
                          <th></th><?php
                      }

                      $prodrg=$DB->querys("SELECT  rang, count(rang) as countr from rangel where matricule='{$eleve->matricule}' and pseudo='{$_SESSION['pseudo']}' ");

                      if ($etab['nom']=='Complexe Scolaire la Plume') {

                        if ($prodrg['rang']==1) {?>
                          <th colspan="1"> <?php if ($_SESSION['niveauclasse']!='primaire') {?>Rang <?=$prodrg['rang'].'er/'.$count;?><?php }?></th><?php
                        }else{?>
                          <th colspan="1"><?php if ($_SESSION['niveauclasse']!='primaire') {?> Rang <?=$prodrg['rang'].'ème/'.$count;?><?php }?></th><?php
                        }
                      }else{

                        if ($prodrg['rang']==1) {?>
                          <th colspan="1"> Rang <?=$prodrg['rang'].'er/'.$count;?></th><?php
                        }else{?>
                          <th colspan="1"> Rang <?=$prodrg['rang'].'ème/'.$count;?></th><?php
                        }

                      }?>
                  </tr><?php


                  if ($_SESSION['niveauclasse']!='primaire') {

                    if ($moyeng>=0 and $moyeng<=5) {
                      $appreciation='Faible';
                    }elseif ($moyeng>5 and $moyeng<10) {
                      $appreciation='Insuffisant';
                    }elseif ($moyeng>=10 and $moyeng<11) {
                      $appreciation='Passable';
                    }elseif ($moyeng>=11 and $moyeng<14) {
                      $appreciation='Assez-bien';
                    }elseif ($moyeng>=14 and $moyeng<16) {
                      $appreciation='Bien';
                    }elseif ($moyeng>=16 and $moyeng<=20) {
                      $appreciation='Très-Bien';
                    }else{
                    }
                  }else{

                    if ($moyeng>=0 and $moyeng<5) {
                      $appreciation='Insuffisant';
                    }elseif ($moyeng>=5 and $moyeng<6) {
                      $appreciation='Passable';
                    }elseif ($moyeng>=6 and $moyeng<8) {
                      $appreciation='Assez-Bien';
                    }elseif ($moyeng>=8 and $moyeng<10) {
                      $appreciation='Bien';
                    }elseif ($moyeng==10) {
                      $appreciation='Très-Bien';
                    }else{
                    }

                  }

                  $prodabs=$DB->querys('SELECT count(nbreheure) as nbreh from absence where promo=:promo and matricule=:matr and absence.id not in(SELECT id_absence FROM justabsence)', array('promo'=>$_SESSION['promo'], 'matr'=>$eleve->matricule));

                  $prodret=$DB->querys('SELECT count(timeretard) as nbrer from retard where promo=:promo and matricule=:matr', array('promo'=>$_SESSION['promo'], 'matr'=>$eleve->matricule));?>

                  <tr>
                    <th colspan="3" >Appréciation: <?=$appreciation;?></th><?php

                    if (empty($prodabs['nbreh'])) {
                      $nbreh=0;
                    }else{
                      $nbreh=$prodabs['nbreh'];
                    }

                    if (empty($prodret['nbrer'])) {
                      $nbrer=0;
                    }else{
                      $nbrer=$prodret['nbrer'];
                    }?>
                        
                    <th colspan="2">Absence(s): <?=$nbreh;?></th>
                    <th colspan="2">Retard(s):  <?=$nbrer;?></th>
                  </tr><?php 
                  if ($etab['nom']=='Complexe Scolaire la Plume') {?>

                    <tr>
                      <th colspan="7" style="text-align:center;">Décision du conseil des maitres:</th>
                    </tr>
                    <tr>
                      <th colspan="2" style="text-align:left; border-right:0px solid white; font-size:12px;">Admis(e) en classe supérieure <div class="checkbox"></div></th>
                      <th colspan="2" style="text-align:center; border-right:0px solid white; font-size:12px;">Redouble sa classe <div class="checkbox"></div></th>
                      <th colspan="3" style="text-align:center; font-size:12px;">Sessionnaire à la rentrée <div class="checkbox"></div></th>
                    </tr><?php 
                  }?>

                </tbody>
              </table>
            </div><?php

          }else{?>

            <div class="col" style=" margin-top: 10px;">

              <table class="tablistebul">
                <thead>
                  <tr>
                    <th>Matières</th>
                    <th>Coeff</th>
                    <th>1er Semestre</th>
                    <th>2ème Semestre</th>
                    <th>Annuel</th>
                    <th>Appréciations</th>
                  </tr>
                </thead>
                <tbody><?php

                  $prodgr=$DB->querys('SELECT codef from inscription where matricule=:mat and annee=:promo', array('mat'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));
                 
                  $cat = array(
                    1   => 'sciences exactes',
                    2   => 'sciences litteraires',
                    3   => 'c/svt',
                    4   => 'facultatives',
                    5   => 'catégorie essentielle',
                    6   => 'catégorie francais',
                    7   => 'catégorie math/calcul',
                    8   => 'catégorie leçon d éveil',
                    9   => 'autres'
                    
                  );

                  $tot1=0;
                  $tot2=0;
                  $tot3=0;
                  $tota=0;
                  $coefint=0;
                  $coefcomp=0;
                  $coefmat=0;
                  $coefgen=0;

                  foreach ($cat as $value) {
                    
                    $prodmatiere=$DB->query('SELECT nommat, codem, coef, cat from  matiere where codef=:nom and cat=:cat order by(cat)', array('nom'=>$prodgr['codef'], 'cat'=>$value));

                    if (!empty($prodmatiere)) {?>

                      <tr>
                        <td colspan="6" style="text-align: center; color: #717375; "><?=ucwords($value);?></td>
                      </tr><?php
                    }
                    $tot1c=0;
                    $tot2c=0;
                    $tot3c=0;
                    $totac=0;
                    $coefintc=0;
                    $coefcompc=0;
                    $coefmatc=0;
                    $coefgenc=0;
                    foreach ($prodmatiere as $matiere) {

                      $prod1=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm, matiere.codem as codem from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'sem'=>1, 'promo'=>$_SESSION['promo']));
                      // foreach ($prod1 as $moyenne) {
                      //   $DB->insert("INSERT INTO relevebulannuel(matricule,moyenne, codem, trimestre, pseudo, promo)VALUES(?,?,?,?,?,?)",array($eleve->matricule,$moyenne->mgen, $moyenne->codem,1,$_SESSION['pseudo'],$_SESSION['promo']));
                      // }

                      //2ème semestre

                      $prod2=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm, matiere.codem as codem from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'sem'=>2, 'promo'=>$_SESSION['promo']));
                      

                      //3ème semestre

                      //$prod3=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'sem'=>3, 'promo'=>$_SESSION['promo']));

                      // Annuel

                      //$annuel=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));

                      foreach ($prod1 as $moyenne) {
                        $DB->insert("INSERT INTO relevebulannuel(matricule,moyenne,codem,coefm,trimestre, pseudo, promo)VALUES(?,?,?,?,?,?,?)",array($eleve->matricule,$moyenne->mgen, $moyenne->codem,$moyenne->coefm,1,$_SESSION['pseudo'],$_SESSION['promo']));

                      }

                      foreach ($prod2 as $moyenne) {
                        $DB->insert("INSERT INTO relevebulannuel(matricule,moyenne,codem,coefm,trimestre, pseudo, promo)VALUES(?,?,?,?,?,?,?)",array($eleve->matricule,$moyenne->mgen, $moyenne->codem,$moyenne->coefm,2,$_SESSION['pseudo'],$_SESSION['promo']));

                      }

                      $annuel=$DB->query('SELECT (sum(moyenne))/2 as mgen, coefm from relevebulannuel where codem=:mat and matricule=:matr and promo=:promo', array('mat'=>$matiere->codem, 'matr'=>$eleve->matricule, 'promo'=>$_SESSION['promo']));

                      
                      
                      if ($matiere->coef>0) {?>

                        <tr>
                          <td style="text-align: left;"><?=ucfirst($matiere->nommat);?></td>
                          <td style="text-align: center;"><?=$matiere->coef;?></td><?php

                          $coefmat+=$matiere->coef;
                          $coefmatc+=$matiere->coef;
                                
                          foreach ($prod1 as $moyenne) {
                            $tot1+=($moyenne->mgen*$moyenne->coefm);
                            $tot1c+=($moyenne->mgen*$moyenne->coefm);

                            $coefint+=$moyenne->coefm;
                            $coefintc+=$moyenne->coefm;?>

                            <td style="padding-right: 10px; text-align: right;"><?=number_format($moyenne->mgen,2,',',' ');?></td><?php
                          }
                          //fin 1er semestre
                                
                          foreach ($prod2 as $moyenne) {

                            $tot2+=($moyenne->mgen*$moyenne->coefm);
                            $coefcomp+=$moyenne->coefm;
                            $tot2c+=($moyenne->mgen*$moyenne->coefm);
                            $coefcompc+=$moyenne->coefm;?>
                            <td style="padding-right: 10px; text-align: right;"><?=number_format($moyenne->mgen,2,',',' ');?></td><?php
                          }                          

                          foreach ($annuel as $moyenne) {
                            if ($moyenne->mgen==0) {
                              $appreciation='';
                            }elseif ($moyenne->mgen>0 and $moyenne->mgen<=5) {
                              $appreciation='Faible';
                            }elseif ($moyenne->mgen>5 and $moyenne->mgen<10) {
                              $appreciation='Insuffisant';
                            }elseif ($moyenne->mgen>=10 and $moyenne->mgen<11) {
                              $appreciation='Acceptable';
                            }elseif ($moyenne->mgen>=11 and $moyenne->mgen<14) {
                              $appreciation='Assez-Bien';
                            }elseif ($moyenne->mgen>=14 and $moyenne->mgen<16) {
                              $appreciation='Bien';
                            }elseif ($moyenne->mgen>=16 and $moyenne->mgen<17) {
                              $appreciation='Très Bien';
                            }else{
                              $appreciation='Excellent';
                            }

                            $tota+=($moyenne->mgen*$moyenne->coefm);
                            $coefgen+=$moyenne->coefm;
                            $totac+=($moyenne->mgen*$moyenne->coefm);
                            $coefgenc+=$moyenne->coefm;?>

                            <td style="padding-right: 10px; text-align: right;"><?=number_format($moyenne->mgen,2,',',' ');?></td>

                            <td style="text-align: left; padding-left: 3px;"><?=$appreciation;?></td><?php
                          }?>
                        </tr><?php
                      }
                    }
                    if (!empty($prodmatiere)) {

                      if ($coefgenc==0) {
                        $moyenc=0;
                      }else{
                        $moyenc=$totac/$coefgenc;
                      }

                      if ($moyenc==0 and $moyenc<=5) {
                        $appreciation='Faible';
                      }elseif ($moyenc>5 and $moyenc<10) {
                        $appreciation='Insuffisant';
                      }elseif ($moyenc>=10 and $moyenc<11) {
                        $appreciation='Acceptable';
                      }elseif ($moyenc>=11 and $moyenc<14) {
                        $appreciation='Assez-Bien';
                      }elseif ($moyenc>=14 and $moyenc<16) {
                        $appreciation='Bien';
                      }elseif ($moyenc>=16 and $moyenc<17) {
                        $appreciation='Très Bien';
                      }else{
                        $appreciation='Excellent';
                      }
                      if (!empty($coefmatc)) {?>

                        <tr>
                          <td style="border: 1px; text-align: center; color: grey;">Total</td>

                          <td style="border: 1px; text-align: center; color: grey;"><?=$coefmatc;?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;">Moy <?=number_format(($tot1c/$coefmatc),2,',',' ');?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;">Moy <?=number_format(($tot2c/$coefmatc),2,',',' ');?></td>

                          <td style="padding-right: 10px; text-align: right;border: 1px; color: grey;">Moy <?=number_format(($moyenc),2,',',' ');?></td>

                          <td style="border: 1px; text-align: center; color: grey;" ><?=$appreciation;?></td>
                          
                        </tr><?php
                      }
                    }
                  }
                  
                  if (empty($tot1)) {
                    $tot1=0;
                    $abst1=1;
                  }else{
                    $abst1=0;
                  }
                  if (empty($tot2)) {
                    $tot2=0;
                    $abst2=1;
                  }else{
                    $abst2=0;
                  }
                  
                  $nbreabst=2-$abst1-$abst2;?>

                  <tr>
                    <th>Total</th>

                    <th style="padding-right: 10px; text-align: center;"><?=$coefmat;?></th>

                    <th style="padding-right: 10px; text-align: right;"><?=number_format($tot1,2,',',' ');?></th>

                    <th style="padding-right: 10px; text-align: right;"><?=number_format($tot2,2,',',' ');?></th>

                    <th style="padding-right: 10px; text-align: right;"><?=number_format(($tot1+$tot2)/$nbreabst,2,',',' ');?></th>

                    <th style="padding-right: 10px; text-align: right; border-bottom: 0px;"></th>
                      
                  </tr>

                  <tr>
                    <th colspan="2">Moyenne</th><?php

                    if (!empty($coefint)) {?>
                        
                      <th style="padding-right: 10px; text-align: right;"><?=number_format($tot1/$coefint,2,',',' ');?></th><?php

                    }else{?>

                      <th style="padding-right: 10px; text-align: right;">0.00</th><?php

                    }

                    if (!empty($coefcomp)) {?>
                        
                      <th style="padding-right: 10px; text-align: right;"><?=number_format($tot2/$coefcomp,2,',',' ');?></th><?php

                    }else{?>

                      <th style="padding-right: 10px; text-align: right;">0.00</th><?php

                    }

                    if (!empty($coefgen)) {
                      if (empty($coefint)) {
                        $coefint=1;
                        $abs1=1;
                      }else{
                        $abs1=0;
                      }
                      if (empty($coefcomp)) {
                        $coefcomp=1;
                        $abs2=1;
                      }else{
                        $abs2=0;
                      }
                      
                      $nbret=2-$abs1-$abs2;

                      $moyeng=$tota/$coefgen;
                      $totmoyeng=($tot1/$coefint)+($tot2/$coefcomp);?>

                      <th style=" padding-right: 3px; text-align: center;"><?=number_format($totmoyeng/$nbret,2,',',' ');?></th><?php

                    }else{
                      $moyeng=0?>
                        <th></th><?php
                    }
                    $prodrg=$DB->querys("SELECT  rang, count(rang) as countr from rangel where matricule='{$eleve->matricule}' and pseudo='{$_SESSION['pseudo']}' ");

                    if ($etab['nom']=='Complexe Scolaire la Plume') {

                      if ($prodrg['rang']==1) {?>
                        <th colspan="1"> <?php if ($_SESSION['niveauclasse']!='primaire') {?>Rang <?=$prodrg['rang'].'er/'.$count;?><?php }?></th><?php
                      }else{?>
                        <th colspan="1"><?php if ($_SESSION['niveauclasse']!='primaire') {?> Rang <?=$prodrg['rang'].'ème/'.$count;?><?php }?></th><?php
                      }
                    }else{

                      if ($prodrg['rang']==1) {?>
                        <th colspan="1"> Rang <?=$prodrg['rang'].'er/'.$count;?></th><?php
                      }else{?>
                        <th colspan="1"> Rang <?=$prodrg['rang'].'ème/'.$count;?></th><?php
                      }

                    }?>
                  </tr><?php
                  if ($_SESSION['niveauclasse']!='primaire') {

                    if ($moyeng>=0 and $moyeng<=5) {
                      $appreciation='Faible';
                    }elseif ($moyeng>5 and $moyeng<10) {
                      $appreciation='Insuffisant';
                    }elseif ($moyeng>=10 and $moyeng<11) {
                      $appreciation='Passable';
                    }elseif ($moyeng>=11 and $moyeng<14) {
                      $appreciation='Assez-bien';
                    }elseif ($moyeng>=14 and $moyeng<16) {
                      $appreciation='Bien';
                    }elseif ($moyeng>=16 and $moyeng<=20) {
                      $appreciation='Très-Bien';
                    }else{
                    }
                  }else{

                    if ($moyeng>=0 and $moyeng<5) {
                      $appreciation='Insuffisant';
                    }elseif ($moyeng>=5 and $moyeng<6) {
                      $appreciation='Passable';
                    }elseif ($moyeng>=6 and $moyeng<8) {
                      $appreciation='Assez-Bien';
                    }elseif ($moyeng>=8 and $moyeng<10) {
                      $appreciation='Bien';
                    }elseif ($moyeng==10) {
                      $appreciation='Très-Bien';
                    }else{
                    }

                  }

                  $prodabs=$DB->querys('SELECT count(nbreheure) as nbreh from absence where promo=:promo and matricule=:matr and absence.id not in(SELECT id_absence FROM justabsence)', array('promo'=>$_SESSION['promo'], 'matr'=>$eleve->matricule));

                  $prodret=$DB->querys('SELECT count(timeretard) as nbrer from retard where promo=:promo and matricule=:matr and retard.id not in(SELECT id_absence FROM justretard)', array('promo'=>$_SESSION['promo'], 'matr'=>$eleve->matricule));?>

                  <tr>
                    <th colspan="3" >Appréciation: <?=$appreciation;?></th><?php

                    if (empty($prodabs['nbreh'])) {
                      $nbreh=0;
                    }else{
                      $nbreh=$prodabs['nbreh'];
                    }

                    if (empty($prodret['nbrer'])) {
                      $nbrer=0;
                    }else{
                      $nbrer=$prodret['nbrer'];
                    }?>
                        
                    <th colspan="2">Absence(s): <?=$nbreh;?></th>
                    <th colspan="1">Retard(s):  <?=$nbrer;?></th>
                  </tr>
                </tbody>
              </table>
            </div><?php

          }?>
        </div><?php

      require 'piedbul.php';
    }
  }

  $DB->delete("DELETE FROM rangel where pseudo='{$_SESSION['pseudo']}' "); // Pour supprimer imediatement la liste des admis
  $DB->delete("DELETE FROM relevebulannuel where pseudo='{$_SESSION['pseudo']}' "); // Pour supprimer imediatement la liste des admis
}

$content = ob_get_clean();
try {
  $pdf = new HTML2PDF("p","A4","fr", true, "UTF-8" , 0);
  $pdf->pdf->SetAuthor('Amadou');
  $pdf->pdf->SetTitle(date("d/m/y"));
  $pdf->pdf->SetSubject('Création d\'un Portfolio');
  $pdf->pdf->SetKeywords('HTML2PDF, Synthese, PHP');
  //$pdf->pdf->IncludeJS("print(true);");
  $pdf->writeHTML($content);
  $pdf->Output('bulletin'.'.pdf');
  // $pdf->Output('Devis.pdf', 'D');    
} catch (HTML2PDF_exception $e) {
  die($e);
}
//header("Refresh: 10; URL=index.php");
?>

