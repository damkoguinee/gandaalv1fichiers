<table class="payement" style="width: 100%;">
        <thead>
            <form method="GET" action="etatsalaire.php" id="suitec" name="termc">
                <tr>
                    <th colspan="10" class="info" style="text-align: center">

                        <a style="margin-left: 10px;"href="printdoc.php?enseig" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/pdf.jpg"></a>

                        <a style="margin-left: 10px;"href="csv.php?enseignant" target="_blank"><img  style="height: 20px; width: 20px;" src="css/img/excel.jpg"></a>

                        <input id="reccode" style="width: 250px;" type = "search" name = "termec" placeholder="rechercher !!!!" onKeyUp="suite(this,'s', 4)" onchange="document.getElementById('suitec').submit()">

                        <input   type = "hidden" name = "effnav" value = "search">

                    </th>
                    <th><input   type = "submit" name = "s" value = "search"></th>
                    
              </tr>
            </form>

            <tr>
                <th></th>
                <th height="30">N°M</th>
                <th>Prénom & Nom</th>
                <th>Phone</th>
                <th>Heures</th>
                <th>Salaire Brut</th>
                <th>A/salaire</th>
                <th>Cotisations</th>
                <th style="background-color: green;">Salaire Net</th>
                <th colspan="2">Paiement</th>
            </tr>

        </thead>
<div class="col" style="display: flex; flex-wrap: wrap; margin-bottom: 30px; width: 100%; "><?php

if (isset($_POST['mois'])) {

    if ($_POST['mois']<10) {
        
        $cmois='0'.$_POST['mois'];

    }else{

        $cmois=$_POST['mois'];
    }

    $prodm=$DB->query('SELECT  *from enseignant inner join salaireens on salaireens.numpers=enseignant.matricule where promo=:promo order by(prenomen)', array('promo'=>$_SESSION['promo']));

    foreach ($prodm as $key => $value) {

        $_SESSION['numeen']=$value->matricule;
        $numeen=$_SESSION['numeen'];

        $prodsocial=$DB->querys('SELECT montant from ssocialens where numpers=:mat', array('mat'=>$numeen));

        $_SESSION['prodsocial']=$prodsocial['montant'];

        $prodsalaire=$DB->querys('SELECT salaire, thoraire from salaireens where numpers=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));


        if ($prodsalaire['salaire']==0) {
            
            $_SESSION['salaire']=$prodsalaire['thoraire'];
            $_SESSION['salaireact']='not';

        }else{

            $_SESSION['salaire']=$prodsalaire['salaire'];
            $_SESSION['salaireact']='ok';
        }

        $prodh=$DB->querys('SELECT sum(heuret) as heuret from horairet where numens=:mat and date_format(datet,\'%m\')=:datet and annees=:promo', array('mat'=>$numeen, 'datet'=>$cmois, 'promo'=>$_SESSION['promo']));

        $prodac=$DB->querys('SELECT montant from accompte where matricule=:mat and mois=:datet and anneescolaire=:promo', array('mat'=>$numeen, 'datet'=>$_POST['mois'], 'promo'=>$_SESSION['promo']));

        if ($_SESSION['salaireact']=='not') {
            
            $salairep=$_SESSION['salaire']*$prodh['heuret']-$prodac['montant']-$_SESSION['prodsocial'];

        }else{

            $salairep=$_SESSION['salaire']-$prodac['montant']-$_SESSION['prodsocial'];
        }?>

            <form action="comptabilite.php" method="post" id="formulaire" style="background-color: grey; "> 

            <tbody>
                <tr>
                    <td><?=$key+1;?></td>
                    <td><?=$value->matricule;?></td>
                    <td><?=strtoupper($value->nomen).' '.ucfirst($value->prenomen);?></td>
                    <td></td>
                    <td><?=$prodh['heuret'];?></td>
                    <td><?=$value->salaire;?></td>
                    <td><?=$prodac['montant'];?></td>
                    <td><?=$_SESSION['prodsocial'];?></td>
                    <td><?=$salairep;?></td>
                    <td><select name="typep" required="" >
                        <option value=""></option>
                        <option value="especes">Espèces</option>
                        <option value="cheque">Chèque</option>
                        <option value="virement">Virement</option></select>
                    </td>

                    <td><input type="submit" name="validpaye" value="Valider"></td>
                </tr>
            </tbody>

            </form><?php
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
            
        );?>

        <form action="comptabilite.php" method="post" id="formulaire" style="background-color: grey; "> 

<li><label>Selectionnez le mois</label>
    <input type="text" name="numeen">

                            <select name="mois" required="" onchange="this.form.submit()"><?php

                            if (isset($_POST['mois'])) {?>
                                
                                <option value="<?=$_POST['mois'];?>" ><?=$panier->moisbul();?></option><?php

                            }else{?>

                                <option></option><?php
                            }

                                foreach ($month as $key => $mois) {?>

                                    <option value="<?=$key;?>"><?=$mois;?></option><?php

                                }?>
                            </select>
                        </li>
                    </form>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Etes-vous sûr de vouloir supprimer cette facture ?'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation ?'));
    }
</script>


    
