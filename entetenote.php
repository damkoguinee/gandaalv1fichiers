<div class="entete"><?php

  $etab=$DB->querys('SELECT *from etablissement');

  $pers=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'directeur'));?>

  <table style="width: 100%; margin-left: 10px;">
    <tr>
      <td class="etat">METFP-E <br/>
         METFP-ET<br/>
         <img style="margin-left: 10px;" src="css/img/ministere.jpg" width="50" height="50"><br/>

        INSPECTION REGIONALE ETFP-E<br/> DE <?=strtoupper($etab['region']);?><br/>
      </td>

      <td style="padding-left: 100px;"></td>

        
      <td class="etat">REPUBLIQUE DE <?=strtoupper($etab['pays']);?><br/>

        <img style="margin-left: 60px;" src="css/img/symbole.png" width="50" height="50"><br/>

        <label style="font-size: 10px; margin-right: 150px;">Travail - Justice - Solidarité</label>

      </td>
      <td></td>

      <td class="etat">
        <ol style="margin-top: -10px; margin-left: 50px; margin-bottom: 50px;">
          <li><label style="font-size: 14px;"><?=strtoupper($etab['nom']);?></label></li>
          <li><label><img style="margin-left: 50px;" src="css/img/logo.jpg" width="60" height="60"></label></li>
          <li><label style="font-size: 14px; font-style: italic;"><?=ucfirst($etab['adresse']);?></label> </li>
          <li><label style="font-size: 14px; font-style: italic;"><?=$etab['email'];?></label> </li>
          <li><label>Tél: <?=$etab['phone'];?></label></li>
        </ol>
      </td>

      

    </tr>
  </table>
</div>

<div style="width: 80%; padding-top: 5px; padding-bottom: 5px; text-align: center; font-size: 16px; font-weight: bold; margin-top: -10px; background-color: white;">ECOLE PRIVE DE SANTE SAFI</div>