<?php
require '_header.php';
header("Content-type: text/csv;");


	header("Content-disposition: attachment; filename=centralisation centralisation.csv");


	//$newReservations=$DB->query('SELECT note.matricule as matricule, note, compo, from note inner join inscription on eleve.matricule=inscription.matricule inner join contact on contact.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join payement on payement.matricule=inscription.matricule where annee=:promo order by (prenomel)', array('promo'=>$_SESSION['promo']));
	$codef='9col';
	$prodmat=$DB->query("SELECT  inscription.matricule as matricule, nomel, prenomel, DATE_FORMAT(naissance, \"%d/%m/%Y\")AS naissance, sexe, adresse, pere, mere, codef, nomgr from inscription inner join eleve on inscription.matricule=eleve.matricule where codef='{$codef}' and annee='{$_SESSION['promo']}' order by (prenomel)");

    //$prodmatiere=$DB->query('SELECT nommat, codem, coef from  matiere where codef=:nom order by(cat)', array('nom'=>$prodgr['codef']));

	$prodgr=$DB->querys('SELECT codef from  groupe where nomgr=:nom and promo=:promo', array('nom'=>$_SESSION['groupe'], 'promo'=>$_SESSION['promo']));


    $prodevoir=$DB->query('SELECT nommat, matiere.codem as codem, coef from  matiere inner join enseignement on enseignement.codem=matiere.codem where matiere.codef=:nom and nomgr=:nomgr order by(cat)', array('nom'=>$prodgr['codef'], 'nomgr'=>$_SESSION['groupe']));?>
	Ordre;"matricule";"Prenom & Nom";"Sexe";"date_nais";"lieu_nais";"pere";"mere";"option";"classe";"Matieres";"coef";"semestre";"cours";"compo";<?php

	foreach($prodmat as $key => $row) {

		foreach ($prodevoir as $devoir) {

            $type='note de cours';
            $trimes=1;

            $prodnote=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where type='{$type}'  and trimes='{$trimes}' and note.codem='{$devoir->codem}' and note.matricule='{$row->matricule}' and annee='{$_SESSION['promo']}' and devoir.promo='{$_SESSION['promo']}' ");

            if (empty($prodnote['coef'])) {
            	$notedecours=0;
            }else{

            	$notedecours=$prodnote['note']/($prodnote['coef']);

            }

            $type='composition';
            $trimes=1;

            $prodnote=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where type='{$type}'  and trimes='{$trimes}' and note.codem='{$devoir->codem}' and note.matricule='{$row->matricule}' and annee='{$_SESSION['promo']}' and devoir.promo='{$_SESSION['promo']}' ");

            if (empty($prodnote['coefc'])) {

            	$notecompo=0;
            }else{

            	$notecompo=$prodnote['compo']/($prodnote['coefc']);

            }

            $prodf=$DB->querys('SELECT *from  formation where codef=:nom ', array('nom'=>$prodgr['codef']));
        

	    	echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.ucwords($row->prenomel).' '.strtoupper($row->nomel).'";"'.$row->sexe.'";"'.$row->naissance.'";"'.$row->adresse.'";"'.ucwords($row->pere).'";"'.ucwords($row->mere).'";"'.$prodf['nomf'].'";"'.$row->nomgr.'";"'.$devoir->nommat.'";"'.$devoir->coef.'";"'.$trimes.'";"'.$notedecours.'";"'.$notecompo.'"';
	    }
	}


	foreach($prodmat as $key => $row) {

		foreach ($prodevoir as $devoir) {

            $type='note de cours';
            $trimes=2;

            $prodnote=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where type='{$type}'  and trimes='{$trimes}' and note.codem='{$devoir->codem}' and note.matricule='{$row->matricule}' and annee='{$_SESSION['promo']}' and devoir.promo='{$_SESSION['promo']}' ");

            if (empty($prodnote['coef'])) {

            	$notedecours=0;
            }else{

            	$notedecours=$prodnote['note']/($prodnote['coef']);

            }

            $type='composition';
            $trimes=2;

            $prodnote=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(compo*coefcom) as compo, count(coef) as coeft, sum(coef) as coef, sum(coefcom) as coefc from note inner join devoir on note.codev=devoir.id inner join inscription on note.matricule=inscription.matricule where type='{$type}'  and trimes='{$trimes}' and note.codem='{$devoir->codem}' and note.matricule='{$row->matricule}' and annee='{$_SESSION['promo']}' and devoir.promo='{$_SESSION['promo']}' ");

            if (empty($prodnote['coef'])) {

            	$notecompo=0;
            }else{

            	$notecompo=$prodnote['compo']/($prodnote['coefc']);

            }

            $prodf=$DB->querys('SELECT *from  formation where codef=:nom ', array('nom'=>$prodgr['codef']));
        

	    	echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.ucwords($row->prenomel).' '.strtoupper($row->nomel).'";"'.$row->sexe.'";"'.$row->naissance.'";"'.$row->adresse.'";"'.ucwords($row->pere).'";"'.ucwords($row->mere).'";"'.$prodf['nomf'].'";"'.$row->nomgr.'";"'.$devoir->nommat.'";"'.$devoir->coef.'";"'.$trimes.'";"'.$notedecours.'";"'.$notecompo.'"';
	    }
	}

