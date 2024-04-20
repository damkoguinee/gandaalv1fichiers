<?php 

$prodgr=$DB->querys('SELECT codef from  groupe where nomgr=:nom and promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));

$prodevoir=$DB->query('SELECT nommat, matiere.codem as codem, coef from  matiere inner join enseignement on enseignement.codem=matiere.codem where coef!=:coef and matiere.codef=:nom and nomgr=:nomgr and promo=:promo order by(cat)', array('coef' => 0, 'nom'=>$prodgr['codef'], 'nomgr'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));
$nbreMat=sizeof($prodevoir);
$totalMoyenneGenerale=0;
foreach ($prodevoir as $devoir) {
    $moyenne=0;
    $tabcoef1=array();
    $tab_eleve_eval_moyenne =[];
    foreach ($prodmat as $mat) {//prod viens en haut dans le calcul de la moyenne générale

        if (isset($_POST['mois']) or isset($_GET['mois'])) {
                                                        
            $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and DATE_FORMAT(datedev, \'%m\')=:sem and annee=:promo and devoir.promo=:promo1', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'sem'=>$_SESSION['mois'], 'promo1'=>$_SESSION['promo']));

        }elseif (isset($_POST['semestre']) or isset($_GET['semestre'])) {
                                                        
            $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and trimes=:sem and annee=:promo and devoir.promo=:promo1', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'sem'=>$_SESSION['semestre'], 'promo1'=>$_SESSION['promo']));

        }else{

            $prodnote=$DB->query('SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where note.codem=:code and note.matricule=:mat and annee=:promo and devoir.promo=:promo1', array('code'=>$devoir->codem, 'mat'=>$mat->matricule, 'promo'=>$_SESSION['promo'], 'promo1'=>$_SESSION['promo']));
        }
        foreach ($prodnote as $note) {

            if (!empty($note->compo)) {

                $compo=($note->compo/$note->coefc); //Moyenne composition
            }else{
                $compo=0;

            }

            if (!empty($note->note)) {

                $cours=($note->note/$note->coef);//Moyenne note de cours
            }else{
                $cours=0;
            }

            if (!empty($compo) and !empty($cours)) {
                if ($_SESSION['niveauclasse']=='primaire') {

                        $generale=($compo); //Moyenne eleve
                }else{

                    $generale=($cours+2*$compo)/3; //Moyenne eleve

                }

            }elseif (!empty($note->compo)) {
                
                $generale=$compo;
            }else{
                $generale=($cours); //Moyenne eleve

            }
            if (isset($_POST['mois'])) {
                if (!empty($note->compo)) {
                    $generale=($compo); //Moyenne eleve
                }else{
                    $generale=($cours); //Moyenne eleve
                }
            }
            $etat = 'actif';
            $moyenne+=$generale;
            // if (!empty($note->id)) {
            //     $prodmoymat=$DB->querys('SELECT count(effectifn.matricule) as coef from effectifn inner join inscription on inscription.matricule = effectifn.matricule where etatscol=:etat and annee=:annee and effectifn.codev=:code and effectifn.nomgr=:nom and promo=:promo', array('etat'=>$etat, 'annee' => $_SESSION['promo'], 'code'=>$note->id, 'nom'=>$_SESSION['groupe'],'promo'=>$_SESSION['promo']));

            //     array_push($tabcoef1, $prodmoymat['coef']);
            // }else{
            //     $prodmoymat=$DB->querys('SELECT count(effectifn.matricule) as coef from effectifn inner join inscription on inscription.matricule = effectifn.matricule where etatscol=:etat and annee=:annee and effectifn.codev=:code and effectifn.nomgr=:nom and promo=:promo', array('etat'=>$etat, 'annee' => $_SESSION['promo'],'code'=>$note->id, 'nom'=>$_SESSION['groupe'],'promo'=>$_SESSION['promo'])); // a supprimer dès que les notes existent
            // }

            if (!empty($generale)) {
                $eleve_eval = 1;
            }else{
                $eleve_eval = 0;
            }
            $tab_eleve_eval_moyenne[]=$eleve_eval;
            

        }
    }

    $nbre_elev_eval_moyenne = array_sum($tab_eleve_eval_moyenne);

    // $maxcoef1=max($tabcoef1);
    // var_dump($prodmoymat['coef']." ".$devoir->nommat." ".$devoir->coef);
    
    if ($nbre_elev_eval_moyenne!=0) {
        $totalMoyenneGenerale+=$moyenne/($nbre_elev_eval_moyenne);
    }
    
}

$moyenneGenerale=$totalMoyenneGenerale/$nbreMat;
