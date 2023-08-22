<div class="entete" style="width:100%; background-color: white; margin-top: 5px;"><?php

  $etab=$DB->querys('SELECT *from etablissement');

  $pers=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'directeur'));?>

  <table style="width: 100%; margin-left: 10px; background-color: white;">
    <tr>
      <td class="etat" style="color: grey;">
         <img style="margin-left: 0px;" src="css/img/ministere.jpg" width="220" height="60">
      </td>

      <td style="padding-left: 80px;"></td>

        
      <td class="etat" style="color: grey;">REPUBLIQUE DE <?=strtoupper($etab['pays']);?><br/>

        <img style="margin-left: 50px;" src="css/img/symbole.png" width="30" height="30"><br/>

        <label style="font-size: 10px;">Travail - Justice - Solidarité</label>

      </td>
      <td></td>

      <td class="etat" style="color: grey;">
        <ol style="margin-top: 0px; margin-left: 50px; margin-bottom: 50px;">
          
          <li><label><img style="margin-left: 50px;" src="css/img/logo.jpg" width="60" height="60"></label></li>
          
          <li><label style="font-size: 10px; font-style: italic;"><?=$etab['email'];?></label> </li>

          <li><label style="font-size: 10px; font-style: italic;margin-top: 5px;">Tél: <?=$etab['phone'];?></label></li>
        </ol>
      </td>

      

    </tr>
  </table>
</div>

<div style="width: 100%; padding-top: 5px; padding-bottom: 5px; text-align: center; font-size: 14px; font-weight: bold; margin: auto; margin-top: 10px; margin-bottom: 30px; color: #717375; "><?=strtoupper($etab['nom']);?></div>