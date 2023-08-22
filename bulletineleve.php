<?php
require_once "lib/html2pdf.php";

ob_start(); ?>

<?php require '_header.php';

$prodtype=$DB->querys('SELECT id, type from repartition  where promo=:promo',array('promo'=>$_SESSION['promo']));

  $typerepart=ucfirst($prodtype['type']);
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
  }

  table.tablistebul{
    width: 100%;
    margin-left: 20px;
    border-collapse: collapse;
  }

  .tablistebul th {
    line-height: 5mm;
    border: 2px solid black;
    font-size: 15px;
    font-weight: bold;
    text-align: center;
    padding: 10px;
  }
  .tablistebul td {
    border: 1px solid black;
    line-height: 5mm;
    text-align: right;
    padding-right: 10px; 
    font-size: 14px;
  }

  label {
    float: right;
    font-size: 14px;
    font-weight: bold;
    width: 200px;
  }

  ol{
    list-style: none;
  }
</style><?php

if (isset($_GET['bulele'])) {?>

  <page backtop="10mm" backleft="3mm" backright="3mm" backbottom="5mm" footer="page;">

    <div class="body"><?php

      require 'entete.php';?>

      <div style="width: 85%; padding-top: 10px; padding-bottom: 10px; text-align: center; font-size: 18px; font-weight: bold; margin-top: 30px; background-color: maroon">RELEVE DE NOTES</div><?php

      $fiche=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, pere, telpere, mere, telmere, date_format(naissance,\'%Y \') as naiss, phone, email , annee, nomf, classe, nomgr from eleve inner join contact on eleve.matricule=contact.matricule inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef where eleve.matricule=:mat and annee=:promo', array('mat'=>$_SESSION['fiche'], 'promo'=>$_SESSION['promo'])); 

      $mat=$_SESSION['fiche'];
      $filename1="img/".$mat.'.jpg';?>


      <div class="col">
        <ol>
          <li><label>N° Etudiant</label>.........<?=$_SESSION['fiche'];?></li><?php $filename1="img/".$mat.'.jpg';

          if (file_exists($filename1)) {?>

            <img style="margin-left: 100px;" src="img/<?=$mat;?>.jpg" width="80" height="80"><?php

          }else{?>

            <img style="margin-left: 100px;" src="img/defaut.jpg" width="80" height="80"><?php

          }?>

          <li><label>Nom</label>....................<?=strtoupper($fiche['nomel']);?></li>
          <li><label>Prénom</label>..............<?=ucfirst(strtolower($fiche['prenomel']));?></li>
          <li><label>Né(e) en</label>..............<?=$fiche['naiss'];?></li><?php

          if ($fiche['classe']=='terminale') {?>

              <li><label>Classe</label>............<?=$fiche['classe'];?></li><?php
          }else{?>

              <li><label>Classe </label>...............<?=$fiche['classe'].' ème ';?></li><?php
          }?>

          <li><label>Profil</label>...................<?=ucwords($fiche['nomf']);?></li>

          <li><label>Année-Scolaire</label> <?=$fiche['annee']-1;?> - <?=$fiche['annee'];?></li>
        </ol>
        
      </div>

      <div class="col" style=" margin-top: 30px;">

        <table class="tablistebul">
          <thead>
            <tr>
              <th>Matières</th>
              <th>Coef</th><?php
              if ($prodtype['type']=='semestre') {?>

                  <th>1er Semestre</th>
                  <th>2ème Semestre</th><?php

              }else{?>

                  <th>1er Trimestre</th>
                  <th>2ème Trimestre</th>
                  <th>3ème Trimestre</th><?php

              
              }?>
              <th width="100">Annuel</th>
            </tr>
          </thead>
          <tbody><?php

            $prodgr=$DB->querys('SELECT codef from inscription where matricule=:mat and annee=:promo', array('mat'=>$_SESSION['fiche'], 'promo'=>$_SESSION['promo']));

            $prodmatiere=$DB->query('SELECT nommat, codem, coef from  matiere where codef=:nom', array('nom'=>$prodgr['codef']));

            $tot1=0;
            $tot2=0;
            $tot22=0;
            $tot3=0;
            $coefint=0;
            $coefcomp=0;
            $coefmat=0;
            $coefgen=0;

            foreach ($prodmatiere as $matiere) {

              $prod1=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$_SESSION['fiche'], 'sem'=>1, 'promo'=>$_SESSION['promo']));

              //2ème semestre

              $prod2=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$_SESSION['fiche'], 'sem'=>2, 'promo'=>$_SESSION['promo']));

              //3ème semestre

              $prod3=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$_SESSION['fiche'], 'sem'=>3, 'promo'=>$_SESSION['promo']));

              // Annuel

              $annuel=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coefm from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by (eleve.matricule)', array('mat'=>$matiere->codem, 'matr'=>$_SESSION['fiche'], 'promo'=>$_SESSION['promo']));?>

                <tr>
                  <td style="text-align: left;"><?=ucfirst($matiere->nommat);?></td>
                  <td style="padding-right: 0px; text-align: center;"><?=$matiere->coef;?></td><?php

                  $coefmat+=$matiere->coef;
                          
                  foreach ($prod1 as $moyenne) {
                    $tot1+=($moyenne->mgen*$moyenne->coefm);

                    $coefint+=$moyenne->coefm;?>

                    <td style="padding-right: 30px;"><?=number_format($moyenne->mgen,2,',',' ');?></td><?php
                  }
                  //fin 1er semestre
                          
                  foreach ($prod2 as $moyenne) {

                    $tot2+=($moyenne->mgen*$moyenne->coefm);
                    $coefcomp+=$moyenne->coefm;?>

                    <td style="padding-right: 30px;"><?=number_format($moyenne->mgen,2,',',' ');?></td><?php
                  }

                  if ($prodtype['type']=='trimestre') {

                    foreach ($prod3 as $moyenne) {

                        $tot22+=($moyenne->mgen*$moyenne->coefm);
                        $coefcomp+=$moyenne->coefm;?>

                        <td style="padding-right: 30px;"><?=number_format($moyenne->mgen,2,',',' ');?></td><?php
                    }
                }

                  //Annuel

                  foreach ($annuel as $moyenne) {

                    $tot3+=($moyenne->mgen*$moyenne->coefm);
                    $coefgen+=$moyenne->coefm;?>

                    <td style="padding-right: 30px;"><?=number_format($moyenne->mgen,2,',',' ');?></td>
                        
                    </tr><?php
                  }


              }?>

              <tr>
                <th>Total</th>

                <th><?=$coefmat;?></th>

                <th style="padding: 5px;padding-right: 30px; text-align: right;"><?=number_format($tot1,2,',',' ');?></th>

                <th style="padding: 5px;padding-right: 30px; text-align: right;"><?=number_format($tot2,2,',',' ');?></th><?php

                if ($prodtype['type']=='trimestre') {?>

                  <th style="padding: 5px;padding-right: 30px; text-align: right;"><?=number_format($tot22,2,',',' ');?></th><?php
                }?>

                <th style="padding: 5px;padding-right: 30px; text-align: right;"><?=number_format($tot3,2,',',' ');?></th>
                  
              </tr>

              <tr>
                <th>Moyenne</th>
                <th></th><?php

                if (!empty($coefint)) {?>
                    
                  <th style="padding: 5px;padding-right: 30px; text-align: right;"><?=number_format($tot1/$coefint,2,',',' ');?></th><?php

                }else{?>

                  <th></th><?php

                }

                if (!empty($coefcomp)) {?>
                    
                  <th style="padding: 5px;padding-right: 30px; text-align: right;"><?=number_format($tot2/$coefcomp,2,',',' ');?></th><?php

                }else{?>

                  <th></th><?php

                }

                if ($prodtype['type']=='trimestre') {

                  if (!empty($coefcomp)) {?>
                        
                      <th style="padding: 5px;padding-right: 30px; text-align: right;"><?=number_format($tot22/$coefcomp,2,',',' ');?></th><?php

                  }else{?>

                      <th></th><?php

                  }
                }

                if (!empty($coefgen)) {?>

                  <th style="padding: 5px;padding-right: 30px; text-align: right;"><?=number_format($tot3/$coefgen,2,',',' ');?></th><?php

                }else{?>
                    <th></th><?php
                }?>
              </tr>

              <tr>
                <th height="50" >Appréciation</th><?php
                if ($prodtype['type']=='semestre') {?>

                    <th colspan="4"></th><?php

                }else{?>

                    <th colspan="5"></th><?php

                
                }?>
                    
                
              </tr>
            </tbody>
          </table>
        </div>
      </div><?php

      require 'pied.php';
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
  $pdf->Output('ticket'.date("d/m/y").date("H:i:s").'.pdf');
  // $pdf->Output('Devis.pdf', 'D');    
} catch (HTML2PDF_exception $e) {
  die($e);
}
//header("Refresh: 10; URL=index.php");
?>
