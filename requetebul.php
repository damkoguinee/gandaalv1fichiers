<?php

if ($_SESSION['niveauclasse']=='primaire' or $_SESSION['niveauclasse']=='maternelle') {

    if (isset($_POST['mois']) or isset($_GET['mois'])) {
            
        $prodverifdev=$DB->querys('SELECT type from devoir where DATE_FORMAT(datedev, \'%m\')=:sem and nomgroupe=:nom and codem=:code and promo=:promo', array('sem'=>$_SESSION['mois'], 'nom'=>$_SESSION['groupe'], 'code'=>$matiere->codem, 'promo'=>$_SESSION['promo']));

        if (!empty($prodverifdev)) {

            if ($prodverifdev['type']=='note de cours') {
                
                $prodm1=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and DATE_FORMAT(datedev, \'%m\')=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>$_SESSION['mois'], 'promo'=>$_SESSION['promo']));
            }else{
                $prodm1=$DB->query('SELECT ((sum(compo*devoir.coefcom)/sum(devoir.coefcom))) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and DATE_FORMAT(datedev, \'%m\')=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>$_SESSION['mois'], 'promo'=>$_SESSION['promo']));
            };
        }else{
            $prodm1=array();
        }

    }elseif (isset($_POST['semestre']) or isset($_GET['semestre'])) {

        $prodm1=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo']));

    //var_dump($prodm1);
    }else{

        $prodm1=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'promo'=>$_SESSION['promo']));

    }

    
}else{

    if (isset($_POST['mois']) or isset($_GET['mois'])) {
            
        $prodverifdev=$DB->querys('SELECT type from devoir where DATE_FORMAT(datedev, \'%m\')=:sem and nomgroupe=:nom and codem=:code and promo=:promo', array('sem'=>$_SESSION['mois'], 'nom'=>$_SESSION['groupe'], 'code'=>$matiere->codem, 'promo'=>$_SESSION['promo']));

        if (!empty($prodverifdev)) {

            if ($prodverifdev['type']=='note de cours') {
                
                $prodm1=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and DATE_FORMAT(datedev, \'%m\')=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>$_SESSION['mois'], 'promo'=>$_SESSION['promo']));
            }else{
                $prodm1=$DB->query('SELECT ((sum(compo*devoir.coefcom)/sum(devoir.coefcom))) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and DATE_FORMAT(datedev, \'%m\')=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>$_SESSION['mois'], 'promo'=>$_SESSION['promo']));
            };
        }else{
            $prodm1=array();
        }

    }elseif (isset($_POST['semestre']) or isset($_GET['semestre'])) {

        $prodm1=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>$_SESSION['semestre'], 'promo'=>$_SESSION['promo']));

    //var_dump($prodm1);
    }else{

        $prodm1=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'promo'=>$_SESSION['promo']));

    }
}