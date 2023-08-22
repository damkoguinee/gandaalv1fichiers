<?php require 'headerv2.php';

if ($products['niveau']<4) {?>

   <div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

}else{?>

   <div class="container-fluid">
      <div class="row"><?php

         require 'navformation.php';?>

         <div class="col-sm-12 col-md-10"><?php
            if (isset($_GET['ecrire']) or isset($_GET['sms'])) {
               unset($_SESSION['envoyer']);
            }
            
            if (!empty($_SESSION['envoyer'])) {?>
               <div class="alert alert-success">Message envoyé !!!</div><?php 
            }?>

            <form name="envoyersms" method="post" action="smstraitement.php" id="formulaire" style="background-color: coral;">
               <ol>
                  <li>Selectionnez le mode de contact <a href="sms.php?sms&ecrire=<?='sms';?>"><img style="width:5%; height: 5%; margin-right: 20px; margin-left: 20px;" src="css/img/sms.jpg"></a> Ou <a href="sms.php?email&ecrire=<?='email';?>"><img style="width:5%; height: 5%;  margin-right: 20px; margin-left: 20px;" src="css/img/email.jpg"></a></li><?php 
                  if (isset($_GET['ecrire'])) {
                     if ($_GET['ecrire']=='sms') {
                        $maxlenght=100;
                     }else{
                        $maxlenght=800;
                     }
                     ?>
                     <input type="hidden" name="type" value="<?=$_GET['ecrire'];?>" />

                     <li><label style="width: 100px;">Personnels</label>
                        <label style="padding-right: 10px;">
                           <select name="personnel">
                              <option></option><?php 
                              foreach ($panier->listePersonnel() as $value) {?>

                                 <option value="<?=$value->numpers;?>"><?=$panier->nomPersonnel($value->numpers);?></option><?php
                              }?>
                              <option value="personnel">A tous les Personnels</option>
                           </select>
                        </label>

                        <label style="width: 100px;">Enseignants</label>

                        <select name="enseignant">
                           <option></option>
                           <option value="enseigmat">Enseignants de la Maternelle</option>
                           <option value="enseigprim">Enseignants du Primaire</option>
                           <option value="enseigsec">Enseignants du Secondaire</option>
                           <option value="enseignants">A tous les Enseignants</option><?php 
                           foreach ($panier->listeEnseignant() as $value) {?>

                              <option value="<?=$value->matricule;?>"><?=$panier->nomEnseignant($value->matricule);?></option><?php
                           }?>
                           
                        </select>
                     </li>

                     <li><label style="width: 100px;">Par Cusrsus</label>
                        <label style="padding-right: 10px;">

                           <select name="cursus">
                              <option></option><?php 
                              foreach ($panier->cursus() as $value) {?>

                                 <option value="<?=$value->nom;?>"><?=ucwords($value->nom);?></option><?php
                              }?>
                              <option value="complexe">A tous les élèves</option>
                           </select>
                        </label>

                        <label style="width: 100px;">Par Niveau</label>

                        <select name="niveau">
                           <option></option><?php 
                           foreach ($panier->formation() as $value) {?>

                              <option value="<?=$value->codef;?>"><?=ucwords($panier->nomClasse($value->codef));?></option><?php
                           }?>
                        </select>                    

                     </li>

                     <li><label style="width: 100px;">Par Classe</label>
                        <label style="padding-right: 10px;">

                           <select name="classe">
                              <option></option><?php 
                              foreach ($panier->listeClasse() as $value) {?>

                                 <option value="<?=$value->nomgr;?>"><?=ucwords($value->nomgr);?></option><?php
                              }?>
                           </select>
                        </label>

                        <input type="text" name="numbereleve" placeholder="Par matricule élève" />

                     </li>
                     <li><label><?=strtoupper($_GET['ecrire']);?> : </label><textarea type='text'  name="message" required="" placeholder="Redigez votre <?=$_GET['ecrire'];?> ici !!!" maxlength="<?=$maxlenght;?>"></textarea></li><?php 
                  }?>

               </ol><?php 
               if (isset($_GET['ecrire'])) {?>
                  
                  <input type="submit" name="envoyer" value="Envoyer" style="cursor: pointer;" /><?php 
               }?>
            </form>
         </div>
      </div>
   </div><?php 
}?>