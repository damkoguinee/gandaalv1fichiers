
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

            $prodgroup=$DB->query('SELECT classe, codef from formation where niveau=:niv order by(id) desc', array('niv'=>$_SESSION['niveauf']));
            
        }else{

            $prodgroup=$DB->query('SELECT classe, codef from formation order by(id)');
        }

        if ((isset($_POST['groupe']))) {

            $prodeleve=$DB->query('SELECT eleve.matricule as matricule, nomel, prenomel from inscription inner join eleve on inscription.matricule=eleve.matricule where codef=:nom and annee=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
        }?>           

        <form id="formulaire" action="centralisation.php" method="POST" style="height: 30px;">
            <ol style="margin-left: -50px; margin-top: -10px;">
                <li><select type="text" name="groupe" required="" onchange="this.form.submit()"><?php

                    if (isset($_POST['groupe']) or isset($_POST['semestre']) or isset($_POST['mois']) or isset($_POST['eleve'])) {?>

                        <option value="<?=$_SESSION['groupe'];?>"><?=$panier->nomClasse($_SESSION['groupe']);?></option><?php

                    }else{?>

                        <option>Choisissez le Niveau</option><?php
                    }

                    foreach ($prodgroup as $prodformation) {
                        if ($prodformation->classe>1 and $prodformation->classe<=10) {
                            $classe=$prodformation->classe.'ème Année';
                        }elseif ($prodformation->classe==1) {
                            $classe=$prodformation->classe.'ère Année';
                        }elseif ($prodformation->classe>10 and $prodformation->classe<=12) {
                            $classe=$prodformation->classe.'ème Année '.$prodformation->nomf;
                        }else{
                            $classe=$prodformation->classe;
                        }?>

                        <option value="<?=$prodformation->codef;?>"><?=$classe;?></option><?php

                    }?></select>
                </li>
            </ol>
        </form>  
    </div>