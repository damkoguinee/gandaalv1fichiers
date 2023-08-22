<div><?php

    if (isset($_GET['enseignant'])) {

        if (isset($_GET['enseignant'])) {

            if (isset($_GET['enseignant'])) {
                $_SESSION['fiche']=$_SESSION['matricule'];
                $promoins=$_SESSION['promo'];
            }

           
            $mat=$_SESSION['fiche'];

            $fiche=$DB->querys('SELECT  *from enseignant inner join contact on enseignant.matricule=contact.matricule inner join login on enseignant.matricule=login.matricule inner join salaireens on salaireens.numpers=enseignant.matricule inner join prime on numpersp=enseignant.matricule where enseignant.matricule=:mat and promo=:promo ', array('mat'=>$mat, 'promo'=>$_SESSION['promo']));?></div>

                
            <div class="fichel" style="margin: auto; font-size: 20px; font-weight: bold; text-align: center; margin-top: 10px;" >Fiche de renseignements de l'enseignant</div><?php

            $nomel=ucfirst(strtolower($fiche['prenomen'])).' '.strtoupper($fiche['nomen']);?>

            <div class="row" style="margin: auto; box-shadow: 10px 2px 20px; margin-bottom: 10px;">

                <div style="margin: auto; display: flex; width: 100%; ">

                    <div style="width: 40%;">
                        <ol style="font-size: 18px;">
                            <li><label>Matricule</label><?=strtoupper($mat);?></li>
                            <li><label>Nom</label> <?=strtoupper($fiche['nomen']);?></li>
                            <li><label>Prénom</label> <?=ucwords(strtolower($fiche['prenomen']));?></li>
                            <li><label>Né(e) le</label> <?=$fiche['naissance'];?></li>
                            <li><label>Téléphone</label> <?=$fiche['phone'];?></li>
                            <li><label>Email</label> <?=$fiche['email'];?></li>                    
                        </ol>
                        
                    </div>


                    <div style="width: 20%;"><?php
                    
                        require 'imageens.php'?> 
                    </div>

                    
                    <div style="width: 40%;">
                        <ol style="font-size: 18px;">
                            <li><label>N° Bancaire</label> <?=$fiche['numbanq'];?></li>
                            
                            <li><label>A.Bancaire</label> <?=ucwords($fiche['agencebanq']);?></li>

                            <li><label>Taux Horaire</label> <?=number_format($fiche['thoraire'],0,',',' ');?></li>

                            <li><label>Salaire</label> <?=number_format($fiche['salaire'],0,',',' ');?></li>

                            <li><label>Prime</label> <?=number_format($fiche['montantp'],0,',',' ');?></li>
                            <li><label>Pseudo</label><?=$fiche['pseudo'];?></li>
                            <li><label>Mot de Passe</label><?=$panier->passworddecod($fiche['mdp']);?></li>
                        </ol>
                        
                    </div>
                </div>           

            </div><?php

            if (isset($_GET['enseignant'])) {?>

                <div class="row">

                    <a style="padding: 5px;" href="enseignantclasse.php?voir_mate=<?=$mat;?>&enseignant" ><input type="button" value="Mes Classes" style="width: 100%; font-size: 20px; font-weight: bold; cursor: pointer; padding: 5px;"></a>

                    <a style="padding: 5px;" href="planing.php?enseignantplaning=<?=$mat;?>"><input type="button" value="Mon Emploi du Temps" style="width: 100%; font-size: 20px; font-weight: bold;cursor: pointer; padding: 5px;"></a>

                    <a style="padding: 5px;" href="enseignantsalaires.php?enseignantsalaires=<?=$mat;?>&enseignant"><input type="button" value="Mes Paiements" style="width: 100%; font-size: 20px; font-weight: bold;cursor: pointer; padding: 5px;"></a>

                    <a style="padding: 5px;" href="#?enseignantsalaires=<?=$mat;?>&enseignant"><input type="button" value="Gestion des Notes" style="width: 100%; font-size: 20px; font-weight: bold;cursor: pointer; padding: 5px;"></a>

                    <a style="padding: 5px;" href="enseignantdocument.php?docens=<?=$mat;?>&ficheens=<?=$mat;?>&enseignant"><input type="button" value="Mes Documents" style="width: 100%; font-size: 20px; font-weight: bold;cursor: pointer; padding: 5px;"></a>

                    <a style="padding: 5px;" href="enseignantdevoirs.php?ficheens=<?=$mat;?>&enseignant"><input type="button" value="Devoirs" style="width: 100%; font-size: 20px; font-weight: bold;cursor: pointer; padding: 5px;"></a>

                </div><?php
            }
        }
    }?>
</div>