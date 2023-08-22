
    <div class="col" style="display: flex; margin-top: -20px;"><?php

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

        if (!empty($_SESSION['niveauf'])) {

            $prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe where promo=:promo and niveau=:niv order by(codef) desc', array('promo'=>$_SESSION['promo'], 'niv'=>$_SESSION['niveauf']));
            
        }else{

            $prodgroup=$DB->query('SELECT groupe.nomgr as nomgr from groupe where promo=:promo order by(codef) desc', array('promo'=>$_SESSION['promo']));
        }

        if ((isset($_POST['groupe']) or isset($_POST['semestre']) or isset($_POST['mois']) or isset($_POST['eleve'])) and $_SESSION['semestre']!='Choisissez le semestre') {

            $prodeleve=$DB->query('SELECT eleve.matricule as matricule, nomel, prenomel from inscription inner join eleve on inscription.matricule=eleve.matricule where nomgr=:nom and annee=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
        }?>           

        <form id="formulaire" action="bulletin.php" method="POST" style="height: 30px;">
            <ol style="margin-left: -50px; margin-top: -10px;">
                <li><select type="text" name="groupe" required="" onchange="this.form.submit()"><?php

                    if (isset($_POST['groupe']) or isset($_POST['semestre']) or isset($_POST['mois']) or isset($_POST['eleve'])) {?>

                        <option value="<?=$_SESSION['groupe'];?>"><?=$_SESSION['groupe'];?></option><?php

                    }else{?>

                        <option>Choisissez la Classe</option><?php
                    }

                    foreach ($prodgroup as $form) {?>

                        <option><?=$form->nomgr;?></option><?php

                    }?></select>
                </li>
            </ol>
        </form>

        <form id="formulaire" action="bulletin.php" method="POST" style="margin-left: -20px; height: 30px;">
            <ol style="margin-left: -50px; margin-top: -10px;">
                <li><select type="text" name="mois" required="" onchange="this.form.submit()"><?php

                    if (isset($_POST['mois']) or isset($_POST['eleve'])) {?>

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
        </form>

        <form id="formulaire" action="bulletin.php" method="POST" style="margin-left: -20px; height: 30px;">
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

                    
                    }?></select></li>
            </ol>
        </form>

        

        
    </div>