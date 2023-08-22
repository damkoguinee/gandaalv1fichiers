
<div class="col" style="display: flex; margin-top: -20px;"><?php

    if (isset($_POST['groupe'])){

        $_SESSION['groupe']=$_POST['groupe'];
        $_SESSION['semestre']='choisissez le ';
        $_SESSION['eleveap']=1;

        $prodcodef=$DB->querys("SELECT codef from groupe where nomgr='{$_SESSION['groupe']}' and promo='{$_SESSION['promo']}'");
        $_SESSION['codefmat']=$prodcodef['codef'];
    }

    if (isset($_POST['mois'])){

        $_SESSION['mois']=$_POST['mois'];
        $_SESSION['eleveap']=1;
        $_SESSION['periodeap']=$panier->moisbul($_POST['mois']);
        $_SESSION['periodesaisie']=$_POST['mois'];

    }

    if (isset($_POST['semestre'])){

        $_SESSION['semestre']=$_POST['semestre'];
        $_SESSION['eleveap']=1;

        if ($_POST['semestre']=!'annuel') {

            if ($prodtype=='semestre') {

                if ($_POST['semestre']==1) {

                    $_SESSION['periodeap']=$_POST['semestre'].'er Semestre';

                }else{

                    $_SESSION['periodeap']=$_POST['semestre'].'ème Semestre';
                }

                $_SESSION['periodesaisie']= $_POST['semestre'].' semestre';

            }else{

                if ($_POST['semestre']==1) {

                    $_SESSION['periodeap']=$_POST['semestre'].'er Trimestre';

                }else{

                    $_SESSION['periodeap']=$_POST['semestre'].'ème Trimestre';
                }

                $_SESSION['periodesaisie']= $_POST['semestre'].' trimestre';        
            }

        }else{

            $_SESSION['periodeap']='Annuel';

            $_SESSION['periodesaisie']= $_POST['semestre'];

        }

    }

    if (isset($_POST['eleveap'])){

        $_SESSION['eleveap']=$_POST['eleveap'];
    }

    $month = array(
        1   => 'Janvier',
        2   => 'Février',
        3   => 'Mars',
        4   => 'Avril',
        5   => 'Mai',
        6   => 'Juin',
        7   => 'Juillet',
        8   => 'Août',
        9   => 'Septembre',
        10  => 'Octobre',
        11  => 'Novembre',
        12  => 'Décembre'
        
    );

    $prodgroup=$DB->query("SELECT *from groupe where promo='{$_SESSION['promo']}'");


    if ((isset($_POST['semestre']) or isset($_POST['mois']) or isset($_POST['eleveap']))) {

        $prodeleve=$DB->query('SELECT eleve.matricule as matricule, nomel, prenomel from inscription inner join eleve on inscription.matricule=eleve.matricule where nomgr=:nom and annee=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
    }?>           

    <form id="formulaire" action="appreciation.php" method="POST" style="height: 30px;">
        <ol style="margin-left: -50px; margin-top: -10px;">
            <li><select type="text" name="groupe" required="" onchange="this.form.submit()"><?php

                if (isset($_POST['groupe']) or isset($_POST['semestre']) or isset($_POST['mois']) or isset($_POST['eleveap'])) {?>

                    <option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php

                }else{?>

                    <option>Choisissez la Classe</option><?php
                }

                foreach ($prodgroup as $form) {?>

                    <option><?=$form->nomgr;?></option><?php

                }?></select>
            </li>
        </ol>
    </form><?php

    if (isset($_POST['groupe']) or isset($_POST['mois']) or isset($_POST['semestre'])) {

        if (isset($_POST['mois']) or isset($_POST['groupe'])) {?>

            <form id="formulaire" action="appreciation.php" method="POST" style="margin-left: -20px; height: 30px;">
                <ol style="margin-left: -50px; margin-top: -10px;">
                    <li><select type="text" name="mois" required="" onchange="this.form.submit()"><?php

                        if (isset($_POST['mois'])) {?>

                            <option value="<?=$_SESSION['mois'];?>"><?=$panier->moisbul();?></option><?php

                        }else{?>

                            <option>Choisissez le mois</option><?php
                        }

                        foreach ($month as $key => $mois) {

                            if ($key<10) {?>

                                <option value="<?="0".$key;?>"><?=$mois;?></option><?php
                                 
                            }else{?>

                                <option value="<?=$key;?>"><?=$mois;?></option><?php
                            }

                        }?>

                        </select></li>
                </ol>
            </form><?php 
        }

        if (isset($_POST['semestre']) or isset($_POST['groupe'])) {?>


            <form id="formulaire" action="appreciation.php" method="POST" style="margin-left: -20px; height: 30px;">
                <ol style="margin-left: -50px; margin-top: -10px;">
                    <li><select type="text" name="semestre" required="" onchange="this.form.submit()"><?php

                        if (isset($_POST['semestre']) or isset($_POST['mois']) or isset($_POST['eleve'])) {?>

                            <option value="<?=$_SESSION['semestre'];?>"><?=$_SESSION['semestre'].' '.$typerepart;?></option><?php

                        }else{?>

                            <option>Choisissez le</option><?php
                        }

                        if ($prodtype=='semestre') {?>

                            <option value="1">1er Semestre</option>
                            <option value="2">2ème Semestre</option><?php

                        }else{?>
                            <option value="1">1er Trimestre</option>
                            <option value="2">2ème Trimestre</option>
                            <option value="3">3ème Trimestre</option><?php
                        
                        }?>
                        <option value="annuel">Annuel</option>
                    </select></li>
                </ol>
            </form><?php
        } 
    }

    if (isset($_POST['mois']) or isset($_POST['semestre']) or isset($_POST['eleveap'])) {?>

        <form id="formulaire" action="appreciation.php" method="POST" style="margin-left: -20px; height: 30px;">
            <ol style="margin-left: -50px; margin-top: -10px;">
                <li>
                    <select type="text" name="eleveap" required="" onchange="this.form.submit()"><?php

                        if (isset($_POST['semestre']) or isset($_POST['mois']) or isset($_POST['eleveap'])) {?>

                            <option value="<?=$_SESSION['eleveap'];?>"><?=$panier->nomEleve($_SESSION['eleveap']);?></option><?php

                        }else{?>

                            <option>Choisissez l' élève</option><?php
                        }

                        foreach ($prodeleve as $value) {?>

                            <option value="<?=$value->matricule;?>"><?=$panier->nomEleve($value->matricule);?></option><?php
                            
                        } ?>
                    </select>
                </li>
            </ol>
        </form><?php 
    }?>
    
</div>