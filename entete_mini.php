<div class="entete"><?php

  $etab=$DB->querys('SELECT *from etablissement');

  $pers=$DB->querys('SELECT *from personnel inner join login on numpers=matricule where type=:type', array('type'=>'directeur'));?>

  <table>
    <tr>
      <td class="etat">METFP-E<br/>
         METFP-ET<br/>
            &nbsp;&nbsp;&nbsp;&nbsp;----------------------------------<br/>

        INSPECTION REGIONALE<br/>  ETFP-E DE <?=strtoupper($etab['region']);?><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;----------------------------------<br/>
      </td>

      <td style="padding-left: 80px;"></td>

        
      

      <td class="etat">
        <ol style="margin-top: -20px; margin-left: 10px; margin-bottom: 50px;">
          <li><label><?=strtoupper($etab['nom']);?></label></li>
          <li><label><img style="margin-left: 80px;" src="css/img/logo.jpg" width="60" height="60"></label></li>
          <li><label><?=ucfirst($etab['adresse']);?></label> </li>
          <li><label><?=$etab['email'];?></label> </li>
          <li><label>Tél: <?=$etab['phone'];?></label></li>
        </ol>
      </td>

      

    </tr>
  </table>
</div>