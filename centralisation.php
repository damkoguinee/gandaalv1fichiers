<?php

require 'headerv2.php';

$etab=$DB->querys('SELECT *from etablissement');

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<3) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{

        if (isset($_POST['groupe'])){

            $_SESSION['groupe']=$_POST['groupe'];
            $_SESSION['semestre']='choisissez le ';
        }

        if (isset($_POST['semestre'])){

            $_SESSION['semestre']=$_POST['semestre'];

        }

        

        if (isset($_GET['printnote'])){

            $_SESSION['semestre']=$_SESSION['semestre'];
            $_SESSION['groupe']=$_SESSION['groupe'];

        }

        if (isset($_POST['eleve'])){

            $_SESSION['eleve']=$_POST['eleve'];
        }?>

        <div class="container"><?php

            if (!isset($_GET['printnote'])){
                //require 'navnote.php';
            }?>            

            <div><?php
                if (!isset($_GET['printnote'])){

                    require 'formcentralisation.php';
                }

                if ((isset($_POST['groupe']) or isset($_GET['printnote']))) {

                    $prodevoir=$DB->query("SELECT nommat, matiere.codem as codem, coef from  matiere inner join enseignement on enseignement.codem=matiere.codem where matiere.codef='{$_SESSION['groupe']}' and promo='{$_SESSION['promo']}' order by(cat)");


                    $prodcount=$DB->querys('SELECT count(matricule) as countel, codef, niveau from inscription where codef=:nom and annee=:promo order by (matricule)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

                    $niveauclasse=$prodcount['niveau'];
                    $_SESSION['niveauclasse']=$niveauclasse;

                    $prodmat=$DB->query('SELECT  inscription.matricule as matricule, nomel, prenomel, DATE_FORMAT(naissance, \'%d/%m/%Y\')AS naissance from inscription inner join eleve on inscription.matricule=eleve.matricule where codef=:nom and annee=:promo order by (prenomel)', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

                    $prodmatiere=$DB->query('SELECT nommat, codem, coef from  matiere where codef=:nom order by(cat)', array('nom'=>$_SESSION['groupe']));

                    $nbremat=sizeof($prodmatiere);

                    
                    $moyengenerale=0;
                    $moyengen=0;?>

                    <div class="entete" style="font-size: 18px; display: flex;">

                        <div style="margin-right: 20px;"><?=$etab['nom'];?></div>

                        <div style="margin-right: 20px;">Période: Annuelle</div>

                        <div style="margin-right: 20px;">Classe: <?=$panier->nomClasse($_SESSION['groupe']);?></div>

                        <div style="margin-right: 20px;">Année-Scolaire: <?=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?></div><?php

                        if (!isset($_GET['printnote'])){?>

                            <div style="margin-right: 20px;"> 

                                <a href="centralisationexcel1.php?codef=<?=$_SESSION['groupe'];?>" target="_blank"><img style="height: 30px; width: 30px;" src="css/img/excel.jpg"></a>
                            </div><?php
                        }?>

                    </div><?php 

                    /*



                    <table class="tablistebul">

                        <thead>
                            <tr>
                                <th>
                                    <table style="width: 100%;">
                                        <tr>
                                            <th width="250">Nom</th>
                                            <th>
                                                <table>
                                                    <tr>
                                                        <th colspan="<?=$nbremat+1;?>">Moyenne des notes coefficiées par matière*</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Matiére</th><?php

                                                        foreach ($prodevoir as $value) {?>
                                                            <th><?=$value->nommat;?></th><?php
                                                        }?>

                                                    </tr>

                                                    <tr>
                                                        <th>Coef</th>
                                                        <?php

                                                        foreach ($prodevoir as $value) {?>
                                                            <th><?=$value->coef;?></th><?php
                                                        }?>
                                                    </tr>
                                                </table>
                                            </th>
                                        </tr>
                                    </table>
                                </th>
                            </tr>
                        </thead>

                        <tbody><?php

                            foreach ($prodmat as $matricule) {?>

                                <tr>
                                   

                                    <td>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td width="242" height="45" style="text-align: left"><?=ucfirst($matricule->prenomel).' '.strtoupper($matricule->nomel);?></td>

                                                <td>
                                                    <table>

                                                        <tr>
                                            
                                                            <td>
                                                                <div style="display: flex;">
                                                                    <div>1er S</div>
                                                                    <div>
                                                                        <table style="width: 100%;">
                                                                            <tr>
                                                                                <td>Cours</td><?php 
                                                                                foreach ($prodevoir as $devoir) {

                                                                                    $type='note de cours';
                                                                                    $trimes=1;

                                                                                    $prodnote=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where type='{$type}'  and trimes='{$trimes}' and note.codem='{$devoir->codem}' and note.matricule='{$matricule->matricule}' and annee='{$_SESSION['promo']}' and devoir.promo='{$_SESSION['promo']}' ");

                                                                                    $notedecours=$prodnote['note']/($prodnote['coef']);?>


                                                                                    <td><?=$notedecours;?></td><?php
                                                                                }?>
                                                                                
                                                                            </tr>

                                                                            <tr>
                                                                                <td>comp</td><?php 
                                                                                foreach ($prodevoir as $devoir) {

                                                                                    $type='composition';
                                                                                    $trimes=1;

                                                                                    $prodnote=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where type='{$type}' and trimes='{$trimes}' and note.codem='{$devoir->codem}' and note.matricule='{$matricule->matricule}' and annee='{$_SESSION['promo']}' and devoir.promo='{$_SESSION['promo']}' ");

                                                                                    $notedecompo=$prodnote['compo']/($devoir->coef);?>


                                                                                    <td><?=$notedecompo;?></td><?php
                                                                                }?>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>Moy 1</td><?php 
                                                                                foreach ($prodevoir as $devoir) {
                                                                                    $trimes=1;

                                                                                    $prodnote=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where trimes='{$trimes}' and note.codem='{$devoir->codem}' and note.matricule='{$matricule->matricule}' and annee='{$_SESSION['promo']}' and devoir.promo='{$_SESSION['promo']}' ");

                                                                                    $moyenne=(($prodnote['note']/($devoir->coef))+2*($prodnote['compo']/($devoir->coef)))/3;?>


                                                                                    <td><?=number_format($moyenne,2,',',' ');?></td><?php
                                                                                }?>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </td>


                                                        </tr>


                                                        <tr>
                                                            <td>
                                                                <div style="display: flex;">
                                                                    <div>2ème S</div>
                                                                    <div>
                                                                        <table style="width: 100%;">
                                                                            <tr>
                                                                                <td>Cours</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>comp</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>Moy 1</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr><td>Annuel</td></tr>
                                                    
                                                    </table>
                                                </td>
                                            </tr>                                            
                                        
                                        </table>

                                    </td>
                                </tr><?php
                            }?>
                            

                        </tbody>
                    </table><?php  
                    */
                        
                }
                
            }
        }?>
    </div>

</div>