<?php
require 'headerv2.php';
$bdd='accesiteabsence'; 
$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mat_abs` varchar(50) DEFAULT NULL,
  `journee` varchar(50) DEFAULT NULL,
  `semestre` int(1) DEFAULT NULL,
  `date_abs` datetime DEFAULT NULL,
  `datesaisie` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `promo` varchar(50) DEFAULT '2024',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");

$bdd='accesiteretard'; 
$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mat_abs` varchar(50) DEFAULT NULL,
  `journee` varchar(50) DEFAULT NULL,
  `semestre` int(1) DEFAULT NULL,
  `timeretard` int(2) DEFAULT NULL,
  `date_abs` datetime DEFAULT NULL,
  `datesaisie` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `promo` varchar(50) DEFAULT '2024',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");

$bdd='accesiteexclus'; 
$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mat_abs` varchar(50) DEFAULT NULL,
  `journee` varchar(50) DEFAULT NULL,
  `motif` varchar(150) DEFAULT NULL,
  `semestre` int(1) DEFAULT NULL,
  `date_abs` datetime DEFAULT NULL,
  `datesaisie` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `promo` varchar(50) DEFAULT '2024',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");


if (isset($_POST['nheure']) or isset($_POST['matr']) or isset($_GET['modif_dev']) or isset($_GET['appelj'])) {
    $dateabs = date('Y-m-d');
    $semestre=$semcourant;
    $promo=$_SESSION['promo'];


    if (isset($_GET['classe'])) {
        $_SESSION['classe']=$_GET['classe'];
    }else{
        $_SESSION['classe']=$_SESSION['classe'];
    }

    if (isset($_POST['journee'])) {
        $_SESSION['journee']=$_POST['journee'];
    }


    if (isset($_POST['matr']) and isset($_POST['appel'])) {

        $matr=addslashes(Htmlspecialchars($_POST['matr']));
        
        $prodverif=$DB->query("SELECT  mat_abs from accesiteabsence where mat_abs='{$matr}'  and date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}'");

        if (!empty($prodverif)) {

            $DB->delete("DELETE FROM accesiteabsence WHERE mat_abs='{$matr}'  and date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}'");	
            
        }else{

            $DB->insert('INSERT INTO accesiteabsence(mat_abs, journee, semestre, promo, date_abs) values( ?, ?, ?, ?,  now())', array($matr, $_SESSION['journee'], $semestre, $promo));
        }
    
    }

    if (isset($_POST['matr']) and isset($_POST['retard'])) {

        $matr=addslashes(Htmlspecialchars($_POST['matr']));
		$retard=addslashes(Htmlspecialchars($_POST['retard']));
        
        $prodverif=$DB->query("SELECT  mat_abs from accesiteretard where mat_abs='{$matr}'  and date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}'");

        if (!empty($prodverif)) {

            $DB->delete("DELETE FROM accesiteretard WHERE mat_abs='{$matr}'  and date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}'");	
            
        }else{

            $DB->insert('INSERT INTO accesiteretard(mat_abs, journee, semestre, timeretard, promo, date_abs) values( ?, ?, ?, ?, ?,  now())', array($matr, $_SESSION['journee'], $semestre, $retard, $promo));
        }
    
    }

    if (isset($_POST['matr']) and isset($_POST['exclus'])) {

        $matr=addslashes(Htmlspecialchars($_POST['matr']));
		$exclus=addslashes(Htmlspecialchars($_POST['exclus']));

        
        $prodverif=$DB->query("SELECT  mat_abs from accesiteexclus where mat_abs='{$matr}'  and date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}'");

        if (!empty($prodverif)) {

            $DB->delete("DELETE FROM accesiteexclus WHERE mat_abs='{$matr}'  and date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}'");	
            
        }else{

            $DB->insert('INSERT INTO accesiteexclus(mat_abs, journee, semestre, motif, promo, date_abs) values( ?, ?, ?, ?, ?,  now())', array($matr, $_SESSION['journee'], $semestre, $exclus, $promo));
        }
    
    }

    if (!empty($_SESSION['journee'])) {
        $prodeleve=$DB->query('SELECT  *from inscription inner join eleve on eleve.matricule=inscription.matricule where inscription.nomgr=:nom and annee=:promo order by (prenomel)', array('nom'=>$_SESSION['classe'], 'promo'=>$_SESSION['promo']));
    }else{
        $prodeleve = array();
    }?>

    <table class="table table-bordered table-striped table-hover align-middle my-2" >
        <thead class="sticky-top text-center bg-light">
            <tr>
                <form action="?appelj" class="form" method="POST">
                    <th>
                        <select name="journee" id="" class="form-select" onchange="this.form.submit()"><?php 
                            if (empty($_SESSION['journee'])) {?>
                                <option value="">Selectionnez une période</option><?php
                            }else{?>
                                <option value="">Présence du <?=$_SESSION['journee'];?></option><?php
                            }?>
                            <option value="matin">Prsence du matin</option>
                            <option value="soir">Présence du soir</option>
                        </select>
                    </th>
                </form>
            </tr>
            <tr>
                <th>Matricule</th>
                <th height="30">Nom et Prénom</th>
                <th style="background-color: green;">Appel</th>
                <th style="background-color: orange;">Retard</th>
                <th style="background-color: red;">Exclusions</th>
            </tr>

        </thead><?php

        if (empty($prodeleve)){ 
        
        }else{?>

            <tbody><?php

                foreach ($prodeleve as $formation) {?>						

                    <tr>
                        <td class="text-center"><?=$formation->matricule;?></td>


                        <td><?=ucwords(strtolower($formation->prenomel)).' '.strtoupper($formation->nomel);?><input class="form-control" type="hidden" name="matr" value="<?=$formation->matricule;?>"/></td>

                        <form method="POST" action="?appelj">

                            <td>
                                <div style="display: flex;">
                                    <div style="margin-left: 50px;">

                                        <input class="form-control" type="hidden" name="matr" value="<?=$formation->matricule;?>"/>

                                        <input  type="checkbox" name="appel" value="abs" onchange="this.form.submit()" />
                                    </div>

                                    <div>

                                        <table style="height: 20px;">
                                            <tbody><?php
                                                $dateabs = date('Y-m-d');
                                                $semestre=$semcourant;
                                                $promo=$_SESSION['promo'];

                                                $prodabsence=$DB->query("SELECT  * from accesiteabsence inner join eleve on mat_abs=eleve.matricule where matricule='{$formation->matricule}'  and date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}' order by (matricule)");

                                                if (empty($prodabsence)) {

                                                }else{

                                                    foreach ($prodabsence as $note) {?>

                                                        <tr>

                                                            <td style="border: 0px;"><img  style="margin-top: 10px; height: 15px; width: 15px;" src="css/img/checkbox.jpg"></td>
                                                        </tr><?php

                                                    }
                                                }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </td>
                        </form>

                        <form method="POST" action="?appelj">

                            <td>
                                <div style="display: flex;">
                                    <div>
                                        <input class="form-control" type="hidden" name="matr" value="<?=$formation->matricule;?>"/>

                                        <input class="form-control" type="number" name="retard" placeholder="min" onchange="this.form.submit()"/>
                                    </div>

                                    <div>

                                        <table>
                                            <tbody><?php

                                                $prodabsence=$DB->query("SELECT  * from accesiteretard inner join eleve on mat_abs=eleve.matricule where matricule='{$formation->matricule}'  and date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}' order by (matricule)");

                            

                                                if (empty($prodabsence)) {

                                                }else{

                                                    foreach ($prodabsence as $note) {?>

                                                        <tr>

                                                            <td style="border: 0px;"><?=$note->timeretard;?></td>
                                                        </tr><?php

                                                    }
                                                }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </td>
                        </form>


                        <form method="POST" action="?appelj">

                            <td>
                                <div style="display: flex;">

                                    <div>
                                        <input class="form-control" type="hidden" name="matr" value="<?=$formation->matricule;?>"/>

                                        <select class="form-select" type="text" name="exclus" onchange="this.form.submit();" >

                                            <option></option>

                                            <option value="indiscipline caracteérisée">indiscipline caracteérisée</option>
                                            <option value="bagarre">Bagarre</option>

                                            <option value="refus doptemperer">Refus d'optemperer</option>

                                            <option value="absence non motivée">Absence non motivée</option>

                                            <option value="Retard de payements">Retard de payements</option>
                                            <option value="insolences">Insolences</option>
                                            <option value="bavardages">Bavardages</option>
                                            <option value="absences de fournitures">Absences de fournitures</option>

                                        </select>
                                    </div>

                                    <div>

                                        <table>
                                            <tbody><?php

                                                $prodabsence=$DB->query("SELECT  * from accesiteexclus inner join eleve on mat_abs=eleve.matricule where matricule='{$formation->matricule}'  and date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}' order by (matricule)");

                            

                                                if (empty($prodabsence)) {

                                                }else{

                                                    foreach ($prodabsence as $note) {?>

                                                        <tr>
                                                            <td style="border: 0px;"><?=$note->motif;?></td>
                                                        </tr><?php

                                                    }
                                                }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </form>

                    </tr><?php
                }?>

            </tbody><?php
            $prodpres=$DB->querys('SELECT  count(matricule) as nbrepres from inscription where nomgr=:nom', array('nom'=>$_SESSION['classe']));

            $prodabs=$DB->querys("SELECT  count(mat_abs) as nbreabs from accesiteabsence where date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}'");

            $prodret=$DB->querys("SELECT  sum(timeretard) as totretard from accesiteretard where date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}'");

            $prodex=$DB->querys("SELECT  count(mat_abs) as nbreex from accesiteexclus where date_format(date_abs,\"%Y-%m-%d \")='{$dateabs}' and journee='{$_SESSION['journee']}'");?>

            <tfoot>
                <tr>
                    <th colspan="2">Synthèse</th>

                    <th style="background-color: green;">Présent(s): <?=$prodpres['nbrepres']-$prodabs['nbreabs']-$prodex['nbreex'];?></th>

                    <th style="background-color: orange;">Retard: <?=$prodret['totretard'];?></th>

                    <th style="background-color: red;">Exclu(s): <?=$prodex['nbreex'];?></th>


                </tr>
            </tfoot><?php 
        }?>
            
    </table><?php

}
