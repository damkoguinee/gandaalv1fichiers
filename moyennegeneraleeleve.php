<?php

$moyengen=0;
$moyengenerale=0;

foreach ($prodmat as $matricule) {
    $totm1t=0;
    $coefm1t=0;
    
    foreach ($prodmatiere as $matiere) {

        if ($_SESSION['niveauclasse']!='primaire') {

            $prodm1t=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>1, 'promo'=>$_SESSION['promo']));

            
        }else{                  

            $prodm1t=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>1, 'promo'=>$_SESSION['promo']));
            
        }
                
        foreach ($prodm1t as $moyenne) {
            $totm1t+=($moyenne->mgen*$moyenne->coef);

            $coefm1t+=$moyenne->coef;
            
        }
    }

    if (!empty($coefm1t)) {

        $moyenmat=($totm1t/$coefm1t);
        $moyengenerale+=$moyenmat;

        $DB->insert('INSERT INTO relevegeneralebul(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 1, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

    }else{

        $DB->insert('INSERT INTO relevegeneralebul(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, 0, 1, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

    }
}

 //**********************************2eme trimestre************************************************

$moyengenerale=0;

foreach ($prodmat as $matricule) {
    $totm2t=0;
    $coefm2t=0;
    
    foreach ($prodmatiere as $matiere) {

        if ($_SESSION['niveauclasse']!='primaire') {           

            $prodm2t=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>2, 'promo'=>$_SESSION['promo']));            
        }else{           

            $prodm2t=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>2, 'promo'=>$_SESSION['promo']));            
        }
                
        foreach ($prodm2t as $moyenne) {
            $totm2t+=($moyenne->mgen*$moyenne->coef);

            $coefm2t+=$moyenne->coef;
            
        }
    }

    if (!empty($coefm2t)) {

        $moyenmat=($totm2t/$coefm2t);
        $moyengenerale+=$moyenmat;

        $DB->insert('INSERT INTO relevegeneralebul(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 2, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

    }else{

        $DB->insert('INSERT INTO relevegeneralebul(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, 0, 2, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

    }
}

//***************************************************3ème trimestre**********************************
$moyengenerale=0;

foreach ($prodmat as $matricule) {
    $totm2t=0;
    $coefm2t=0;
    
    foreach ($prodmatiere as $matiere) {

        if ($_SESSION['niveauclasse']!='primaire') {            

            $prodm2t=$DB->query('SELECT ((sum(note*devoir.coef)/sum(devoir.coef))+2*(sum(compo*devoir.coefcom)/sum(devoir.coefcom)))/3 as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>3, 'promo'=>$_SESSION['promo']));
            
        }else{           

            $prodm2t=$DB->query('SELECT (sum(compo*devoir.coefcom)/sum(devoir.coefcom)) as mgen, nommat, matiere.coef as coef from note inner join devoir on note.codev=devoir.id inner join eleve on note.matricule=eleve.matricule inner join matiere on matiere.codem=note.codem where matiere.codem=:mat and note.matricule=:matr and trimes=:sem and devoir.promo=:promo order by(prenomel)', array('mat'=>$matiere->codem, 'matr'=>$matricule->matricule, 'sem'=>3, 'promo'=>$_SESSION['promo']));            
        }
                
        foreach ($prodm2t as $moyenne) {
            $totm2t+=($moyenne->mgen*$moyenne->coef);

            $coefm2t+=$moyenne->coef;
            
        }
    }

    if (!empty($coefm2t)) {

        $moyenmat=($totm2t/$coefm2t);
        $moyengenerale+=$moyenmat;

        $DB->insert('INSERT INTO relevegeneralebul(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmat, 3, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

    }else{

        $DB->insert('INSERT INTO relevegeneralebul(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, 0, 3, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

    }
}

//**********************************Annuel************************************************

$moyengenerale=0;

foreach ($prodmat as $matricule) {

    $prodm2t=$DB->querys('SELECT sum(moyenne) as moyenne from relevegeneralebul  where matricule=:matr and codef=:codef and pseudo=:pseudo and promo=:promo ', array('matr'=>$matricule->matricule, 'codef'=>$matricule->codef, 'pseudo'=>$_SESSION['pseudo'], 'promo'=>$_SESSION['promo']));

    $trim1t=$DB->querys('SELECT moyenne from relevegeneralebul  where matricule=:matr and codef=:codef and pseudo=:pseudo and promo=:promo and trimestre=:trim', array('matr'=>$matricule->matricule, 'codef'=>$matricule->codef, 'pseudo'=>$_SESSION['pseudo'], 'promo'=>$_SESSION['promo'], 'trim'=>1));

    $trim2t=$DB->querys('SELECT moyenne from relevegeneralebul  where matricule=:matr and codef=:codef and pseudo=:pseudo and promo=:promo and trimestre=:trim', array('matr'=>$matricule->matricule, 'codef'=>$matricule->codef, 'pseudo'=>$_SESSION['pseudo'], 'promo'=>$_SESSION['promo'], 'trim'=>2));

    $trim3t=$DB->querys('SELECT moyenne from relevegeneralebul  where matricule=:matr and codef=:codef and pseudo=:pseudo and promo=:promo and trimestre=:trim', array('matr'=>$matricule->matricule, 'codef'=>$matricule->codef, 'pseudo'=>$_SESSION['pseudo'], 'promo'=>$_SESSION['promo'], 'trim'=>3));

    if (empty($trim1t['moyenne'])) {
        $coeft1=0;
    }else{
        $coeft1=1;
    }

    if (empty($trim2t['moyenne'])) {
        $coeft2=0;
    }else{
        $coeft2=1;
    }

    if (empty($trim3t['moyenne'])) {
        $coeft3=0;
    }else{
        $coeft3=1;
    }
    $coeftgen=($coeft1+$coeft2+$coeft3);
    if (empty($coeftgen)) {
        $moyenmat=1;
    }else{

        $moyenmat=$prodm2t['moyenne']/$coeftgen;
    }
    // var_dump($moyenmat, $coeftgen);


    $moyenmatar=number_format($moyenmat,2,'.','');

    $DB->insert('INSERT INTO relevegeneralebul(matricule, moyenne, trimestre, codef, pseudo, promo) values(?, ?, ?, ?, ?, ?)', array($matricule->matricule, $moyenmatar, 4, $matricule->codef, $_SESSION['pseudo'], $_SESSION['promo']));

}
$trime = 4;
$moyenne_max=$DB->querys("SELECT Max(moyenne) as moyenne from relevegeneralebul  where pseudo='{$_SESSION['pseudo']}' and promo='{$_SESSION['promo']}' and trimestre = '{$trime}' ");
