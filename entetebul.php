<div class="entete"><?php

  $etab=$DB->querys('SELECT *from etablissement');

  $pers=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'Admistrateur General'));?>

  <table style="width: 310px; margin-left: 10px;">
    <tr>
      <td class="etat" style="color: grey;">
         <img style="margin-left: 10px;" src="css/img/ministere.jpg" width="230" height="75">
      </td>

      <td style="padding-left: 80px;"></td>

        
      <td class="etat" style="color: grey;">REPUBLIQUE DE <?=strtoupper($etab['pays']);?><br/>

        <img style="margin-left: 50px;" src="css/img/symbole.png" width="30" height="30"><br/>

        <label style="font-size: 10px;">Travail - Justice - Solidarité</label>

      </td>
      <td></td>

      <td class="etat" style="color: grey;">
        <ol style="margin-top: -20px; margin-left: 50px; margin-bottom: 50px;">
          
          <li><label><img style="margin-left: 50px;" src="css/img/logo.jpg" width="60" height="60"></label></li>
          
          <li><label style="font-size: 10px; font-style: italic;"><?=$etab['email'];?></label> </li>

          <li><label style="font-size: 10px; font-style: italic;">Tél: <?=$etab['phone'];?></label></li>
        </ol>
      </td>

      

    </tr>
  </table>
</div>

<div style="width: 90%; padding-top: 5px; padding-bottom: 0px; font-size: 15px; font-weight: bold; margin: auto; margin-top: -15px; background-color: white; color: grey; text-align: center;"><?=strtoupper($etab['nom']);?></div>

<div style="margin-left: 320px; color: #717375; font-size: 10px;"><?=$etab['devise'];?></div>