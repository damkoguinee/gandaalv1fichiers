<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

        <div class="container-fluid">
            <div class="row"><?php

                require 'navnote.php';?>

                <div class="col-sm-12 col-md-10" style="overflow: auto;"><?php 

                    if (isset($_GET['deletec'])) {

                        $moist=(new dateTime($_GET['datet']))->format("m");
                        $datet=$_GET['datet'];
                        $heured=$_GET['heuret'];
                        $numenst=$_GET['deletec'];

                        $verifheure=$DB->querys("SELECT *from payenseignant where mois='{$moist}' and matricule='{$numenst}' and anneescolaire='{$_SESSION['promo']}'");

                        if (empty($verifheure)) {

                            $DB->delete("DELETE FROM horairet WHERE datet='{$datet}'and heured='{$heured}' and numens='{$numenst}'");?>

                            <div class="alert alert-success">Heure(s) supprimer avec succèe !!!</div><?php

                        }else{?>

                            <div class="alert alert-warning">Impossible de supprimer car un paiement à déjà été effectuer</div><?php 

                        }                        
                    }

                    $month = array(
                        10  => 'Octobre',
                        11  => 'Novembre',
                        12  => 'Décembre',
                        1   => 'Janvier',
                        2   => 'Février',
                        3   => 'Mars',
                        4   => 'Avril',
                        5   => 'Mai',
                        6   => 'Juin',
                        7   => 'Juillet',
                        8   => 'Août',
                        9   => 'Septembre'
                        
                    );

                    if (isset($_POST['mois'])) {
                        $_SESSION['moisp']=$_POST['mois'];
                    }else{
                        $_SESSION['moisp']=0;
                    }

                

                    if (isset($_POST['mois']) and empty($_SESSION['moisp'])){

                        $_SESSION['moisp']=$_POST['mois'];

                        $_SESSION['legende']='Heures Transmises pour le mois de '.$panier->moisbul();   

                    }else{
                        $_SESSION['legende']='Heures Transmises';
                    }

                    if (isset($_POST['mois'])) {

                        if ($_POST['mois']<10) {
                            
                            $cmois='0'.$_SESSION['moisp'];

                        }else{

                            $cmois=$_SESSION['moisp'];
                        }
                    }?>

                    <table class="payement" style="width: 100%;">
                        <thead>
                            
                            <tr>
                                <form method="POST" action="horaireenvoye.php" id="suitec" name="termc">
                                    <th colspan="4" class="info" style="text-align: center">
                                        
                                        <select style="width: 250px; height: 30px; font-size: 19px;" name="mois" required="" onchange="this.form.submit()"><?php

                                            if (isset($_POST['mois'])) {?>
                                                
                                                <option value="<?=$_SESSION['moisp'];?>" ><?=$panier->moisbul();?></option><?php

                                            }else{?>

                                                <option>Selectionnez le mois</option><?php
                                            }

                                            foreach ($month as $key => $mois) {?>

                                                <option value="<?=$key;?>"><?=$mois;?></option><?php

                                            }?>
                                        </select>
                                    </th>
                                </form>
                            </tr>
                            <tr>
                                <th colspan="4" style="font-size: 16px;"><?=$_SESSION['legende'];?></th>
                                <th colspan="4" class="info" style="text-align: center">

                                    <form method="POST" action="horaireenvoye.php" id="suitec" name="termc">
                                        <input type="hidden" name="mois" value="<?=$_SESSION['moisp'];?>">

                                        <input id="reccode" style="width: 250px;" type = "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">

                                        <input   type = "hidden" name = "effnav" value = "search">

                                    </th>
                                    
                                </tr>
                            </form>

                            <tr>
                                <th></th>
                                <th>Mat</th>
                                <th>Prénom & Nom</th>
                                <th>H. Trans</th>
                                <th>H. debut</th>
                                <th>Date</th>
                                <th>Matière</th>
                                <th></th>
                            </tr>

                        </thead><?php

                        if (isset($_POST['termec'])) {
                          $_POST['termec'] = htmlspecialchars($_POST['termec']); //pour sécuriser le formulaire contre les failles html
                          $terme = $_POST['termec'];
                          $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
                          $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
                          $terme = strtolower($terme);

                          $prodm=$DB->query('SELECT *from horairet inner join enseignant on numens=enseignant.matricule inner join matiere on codem=matiere where (date_format(datet,\'%m\') LIKE ? and annees LIKE ?) and (numens LIKE ? or enseignant.matricule LIKE ? or nomen LIKE ? or prenomen LIKE ?) ', array($cmois, $_SESSION['promo'], "%".$terme."%", "%".$terme."%", "%".$terme."%", "%".$terme."%"));
                          
                        }elseif (isset($_POST['mois'])) {

                            $prodm=$DB->query('SELECT *from horairet inner join enseignant on numens=enseignant.matricule inner join matiere on codem=matiere where date_format(datet,\'%m\')=:datet and annees=:promo order by(prenomen)', array('datet'=>$cmois, 'promo'=>$_SESSION['promo']));

                        }else{
                            $prodm=array();
                        }

                        if (isset($_POST['mois'])) {

                            if ($_POST['mois']<10) {
                                
                                $cmois='0'.$_SESSION['moisp'];

                            }else{

                                $cmois=$_SESSION['moisp'];
                            }

                            
                            $toth=0;
                            foreach ($prodm as $key => $value) {                         

                                $toth+=$value->heuret;?>

                                <tbody>

                                    <tr>
                                        <td style="text-align: center;"><?=$key+1;?></td>

                                        <td style="text-align: center;"><?=$value->numens;?></td>

                                        <td><?=ucwords($value->prenomen.' '.$value->nomen);?></td>

                                        <td style="text-align: center;"><?=$value->heuret;?></td>

                                        <td style="text-align: center;"><?=$value->heured;?></td>

                                        <td style="text-align: center;"><?=(new dateTime($value->datet))->format('d/m/Y');?></td>

                                        <td style="text-align: center;"><?=ucwords($value->nommat);?></td>

                                        <td><a class="btn btn-danger" onclick="return alerteS();" href="horaireenvoye.php?deletec=<?=$value->numens;?>&datet=<?=$value->datet;?>&heuret=<?=$value->heured;?>">Supprimer</a></td>                                     
                                        
                                    </tr>
                                </tbody><?php
                            }?>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th style="text-align: center;"><?=number_format($toth,2,',',' ');?></th>
                                </tr>
                            </tfoot><?php
                        }?>
                    </table>
                </div>
            </div>
        </div><?php 
    }
}?>



<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>


    
