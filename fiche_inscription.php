<?php
require_once "lib/html2pdf.php";

ob_start(); ?>

<?php require '_header.php';?>

<style type="text/css">

  table.border {
    color: #717375;
    font-family: helvetica;
    border-collapse: collapse;
    width: 49%;
    margin-top: 50px;
    
  }


  .border th {
    border: 2px solid black;
    border-style: dotted;
    height: 25px;
    padding: 2px;
    font-size: 16px;
    background: white;
    color: grey;
    text-align: center; 
  }

  .border td {
    padding: 2px;
    border: 2px solid black; 
    border-style: dotted;   
    font-size: 16px;
    background: white;
    color: grey;
    text-align: left;
    font-weight: bold;
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

  ol{
    list-style: none;
    color: grey;
    margin: 0px;

  }

  li{
    height: 25px;
  }

  label{
    width: 200px;
  }

  .infos{
    font-size: 16px;
    font-weight: bold;
    font-style: italic;
    font-family: time new roman;
    color: blue;
  }
</style>

<page backtop="8mm" backleft="8mm" backright="8mm" backbottom="8mm"><?php

if (isset($_GET['ficheins'])) {?>

  <?php require 'entetebul.php';

  $etab=$DB->querys('SELECT *from etablissement');

  $pers=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'directeur'));

  $fiche=$DB->querys('SELECT eleve.matricule as mat, nomel, prenomel, sexe, nationnalite, date_format(naissance,\'%d/%m/%Y \') as naiss, adresse, pere, mere, phone, annee, nomf, classe, nomgr, inscription.codef as codef, etat, statut, dateinscription from eleve  inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join contact on contact.matricule=eleve.matricule where eleve.matricule=:mat and annee=:promo ', array('mat'=>$_GET['ficheins'], 'promo'=>$_SESSION['promo']));

  $paye=$DB->querys('SELECT montant, typepaye, date_format(datepaye, \'%d/%m/%Y \') as datepaye from  payement where matricule=:mat  and payement.promo=:promop', array('mat'=>$_GET['ficheins'], 'promop'=>$_SESSION['promo'])); 

  $prodtot=$DB->querys('SELECT sum(montant) as montant, typepaye, datepaye FROM histopayefrais WHERE matricule= ? and promo=? and tranche=?', array($_GET['ficheins'], $_SESSION['promo'], '1ere tranche'));

  $prodtot=$DB->querys('SELECT sum(montant) as montant, typepaye, datepaye FROM histopayefrais WHERE matricule= ? and promo=?', array($_GET['ficheins'], $_SESSION['promo']));

  $prodscola=$DB->querys('SELECT sum(montant) as montant from scolarite where codef=:code and promo=:promo', array('code'=>$fiche['codef'], 'promo'=>$_SESSION['promo']));

  $resteannuel=$prodscola['montant']-$prodtot['montant'];
  if ($fiche['etat']=='inscription') {
    $etat="FICHE D'INSCRIPTION";
    $etatp="FRAIS D'INSCRIPTION";

  }else{
    $etat="FICHE DE REINSCRIPTION";
    $etatp="FRAIS DE REINSCRIPTION";

  }?>

  <table class="border">

    <thead>
      <tr>
        <th colspan="2"><?=$etat;?> ANNEE-SCOLAIRE <?=$fiche['annee']-1;?> - <?=$fiche['annee'];?>
        <span style="margin-left:10px; color:red;">Date d'inscription: <?=(new dateTime($fiche['dateinscription']))->format("d/m/Y");?></span></th>
      </tr>
    

      <tr>
        <th colspan="2">VOTRE IDENTITE</th>
      </tr>
    </thead>

    <tbody>

      <tr>
        <td>

          <ol>

            <li><label>N° Matricule</label>............<?=strtoupper($fiche['mat']);?></li><?php

            if ($fiche['sexe']=='m') {?>

              <li><label>Civilité</label>.....................<?='M.';?></li><?php

            }else{?>

              <li><label>Civilité</label>.....................<?='Mlle/Mme.';?></li><?php

            }?>
            
            <li><label>Nom</label>........................<?=strtoupper($fiche['nomel']);?></li>

            <li><label>Prénom</label>...................<?=ucfirst(strtolower($fiche['prenomel']));?></li>

            <li><label>Né(e) le</label>...................<?=$fiche['naiss'];?></li>

            <li><label>Nationalité</label>..............<?=ucfirst($fiche['nationnalite']);?></li>
          </ol>
        </td>

        <td>

          <ol>
            <li><label>Père</label>...........<label class="infolabel"><?=ucwords($fiche['pere']);?></label></li>
            <li><label>Mère</label>...........<label class="infolabel"><?=ucwords($fiche['mere']);?></label></li>
            <li><label>Adresse</label>......  <label class="infolabel"><?=ucfirst($fiche['adresse']);?></label></li>
            <li><label>Téléphone</label>...   <label class="infolabel"><?=ucfirst(strtolower($fiche['phone']));?></label></li>

          </ol>
        </td>
      </tr>

      <tr>
        <th>Votre Cursus</th>
        <th>Frais Payés</th>
      </tr>

      <tr>

        <td>

          <ol>

            <li><label>Niveau</label>.....................<?=ucwords($fiche['nomf']);?></li>

            <li><label>Classe</label>.....................<?=strtoupper($fiche['nomgr']);?></li>
            
            <li><label>Année-Scolaire</label>...... <?=$fiche['annee']-1;?> - <?=$fiche['annee'];?></li>
          </ol>
          
        </td>

        <td>

          <fieldset style="width: 97%; margin-bottom: 5px;"><?=$etatp;?>

            <ol>
              <li><label>Montant payé</label>....................<?=number_format($paye['montant'],0,',',' ');?></li>

              <li><label>Type de paiement</label>.............<?=$paye['typepaye'];?></li>

              <li><label>Date de paiement</label>.............<?=$paye['datepaye'];?></li>

            </ol>
          </fieldset>

          <fieldset style="width: 97%;">Frais Scolarité

            <ol>
              <li><label>Montant payé</label>....................<?=number_format($prodtot['montant'],0,',',' ');?></li>

              <li><label>Type de paiement</label>.............<?=$prodtot['typepaye'];?></li>

              <li><label>Date de paiement</label>.............<?=(new DateTime($prodtot['datepaye']))->format('d/m/Y');?></li>

              <li style="color: red;"><label style="color: red;">Reste à Payer Annuel</label>.......<?=number_format($panier->resteAnnuel($_GET['ficheins'], $_SESSION['promo'], $fiche['codef']),0,',',' ');?></li>

            </ol>
        </fieldset>
          
        </td>
      </tr>
    </tbody>
    
  </table><?php 

  require 'piedcomptable.php';

  
}?><?php

$content = ob_get_clean();
try {
  $pdf = new HTML2PDF("p","A4","fr", true, "UTF-8" , 0);
  $pdf->pdf->SetAuthor('Amadou');
  $pdf->pdf->SetTitle(date("d/m/y"));
  $pdf->pdf->SetSubject('Création d\'un Portfolio');
  $pdf->pdf->SetKeywords('HTML2PDF, Synthese, PHP');
  //$pdf->pdf->IncludeJS("print(true);");
  $pdf->writeHTML($content);
  $pdf->Output('ficheins'.date("d/m/y").date("H:i:s").'.pdf');
  // $pdf->Output('Devis.pdf', 'D');    
} catch (HTML2PDF_exception $e) {
  die($e);
}


