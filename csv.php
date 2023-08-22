<?php
require '_header.php';
header('Content-Encoding: UTF-8');
header("Content-type: text/csv; charset=UTF-8");

if (isset($_GET['accompte'])) {
	header("Content-disposition: attachment; filename=payement accompte.csv");
	

	$newReservations=$DB->query('SELECT matricule, mois, montant, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye, anneescolaire from accompte where anneescolaire=:promo order by (matricule)', array('promo'=>$_SESSION['promo']));?>
	"Ordre";"Matricule";"Montant";"Payement";"Mois paye";"anneescolaire";"Date";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.$row->montant.'";"'.$row->typepaye.'";"'.$row->mois.'";"'.$row->anneescolaire.'";"'.$row->datepaye.'"';
	}
}




if (isset($_GET['listel'])) {
	header("Content-disposition: attachment; filename=liste.csv");
	

	$newReservations=$DB->query("SELECT nomel, prenomel, adresse, sexe, pere, telpere, mere, telmere, naissance, inscription.matricule as matricule, nomgr, classe, phone, nomf, montant, payement.remise, profp, profm, lieutp, lieutm, adressep, origine, pays, nationnalite, etat, statut, etatscol, dateinscription from eleve inner join inscription on eleve.matricule=inscription.matricule left join contact on contact.matricule=inscription.matricule left join formation on inscription.codef=formation.codef left join payement on payement.matricule=inscription.matricule where annee='{$_SESSION['promo']}' and promo='{$_SESSION['promo']}' order by (prenomel)");?>
	Ordre;"matricule";"Nom";"Prenom";"Sexe";"date de naissance";"Lieu de naissance";"Classe";"Niveau";"Anciennete";"statut";"etat scolarite";"Telephone";"ecole origine";"pays";"nationnalite";"Filiation";"tel pere";"profession pere";"lieu de travail pere";"profession mere";"lieu de travail mere";"tel mere";"Adresse des Parents";"dateinscription";<?php

	foreach($newReservations as $key => $row) {
		if ($row->etat=="inscription") {
			$anciennete='nouveau';
		}else{
			$anciennete='ancien';
		}

		if (!empty($row->naissance)) {
			$naissance=(new dateTime($row->naissance))->format('d/m/Y');
		}else{
			$naissance="";
		}
	    echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.ucwords($row->nomel).'";"'.strtoupper($row->prenomel).'";"'.strtoupper($row->sexe).'";"'.$naissance.'";"'.ucwords($row->adresse).'";"'.$row->nomgr.'";"'.$row->nomf.'";"'.$anciennete.'";"'.$row->statut.'";"'.$row->etatscol.'";"'.$row->phone.'";"'.$row->origine.'";"'.$row->pays.'";"'.$row->nationnalite.'";"'.ucwords($row->pere).' '.ucwords($row->mere).'";"'.$row->telpere.'";"'.$row->profp.'";"'.$row->lieutp.'";"'.$row->profm.'";"'.$row->lieutm.'";"'.$row->telmere.'";"'.$row->adressep.'";"'.$row->dateinscription.'"';
	}
}


if (isset($_GET['fraiscol'])) {
	header("Content-disposition: attachment; filename=liste frais de scolarite.csv");
	

	$newReservations=$DB->query("SELECT nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \"%Y\")AS naissance, inscription.matricule as matricule, nomgr, classe, nomf, montant, tranche, typepaye, DATE_FORMAT(datepaye, \"%d/%m/%Y\")AS datepaye from eleve inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join payementfraiscol on payementfraiscol.matricule=inscription.matricule where annee='{$_SESSION['promo']}' and promo='{$_SESSION['promo']}' order by (nomel)");?>
	"Ordre";"matricule";"Nom";"Prenom";"Sexe";"Ne(e)";"Lieu de N";"Filiation";"Filiere";"classe";"Tranche";"Montant";"Payement";"Date";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.strtoupper($row->nomel).'";"'.ucwords($row->prenomel).'";"'.strtoupper($row->sexe).'";"'.$row->naissance.'";"'.ucwords($row->adresse).'";"'.ucwords($row->pere).' '.ucwords($row->mere).'";"'.$row->nomf.'";"'.$row->nomgr.'";"'.$row->tranche.'";"'.$row->montant.'";"'.$row->typepaye.'";"'.$row->datepaye.'"';
	}
}


if (isset($_GET['inscrip'])) {
	header("Content-disposition: attachment; filename=inscription.csv");	

	$newReservations=$DB->query('SELECT nomel, prenomel, adresse, sexe, pere, mere, DATE_FORMAT(naissance, \'%Y\')AS naissance, inscription.matricule as matricule, nomgr, classe, nomf, formation.niveau as niveau, inscription.etat as etat, montant, payement.remise as remise, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye from eleve inner join inscription on eleve.matricule=inscription.matricule inner join formation on inscription.codef=formation.codef inner join payement on payement.matricule=inscription.matricule where annee=:promo order by (nomel)', array('promo'=>$_SESSION['promo']));?>
	Ordre;"matricule";"Nom";"Prenom";"Sexe";"Ne(e)";"Lieu de N";"Filiation";"Etat";"Niveau";"Formation";"Montant";"Remise";"Payement";"Date";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.strtoupper($row->nomel).'";"'.ucwords($row->prenomel).'";"'.strtoupper($row->sexe).'";"'.$row->naissance.'";"'.ucwords($row->adresse).'";"'.ucwords($row->pere).' '.ucwords($row->mere).'";"'.$row->etat.'";"'.$row->niveau.'";"'.$row->nomf.'";"'.$row->montant.'";"'.$row->remise.'";"'.$row->typepaye.'";"'.$row->datepaye.'"';
	}
}


if (isset($_GET['dec'])) {
	header("Content-disposition: attachment; filename=depenses.csv");
	

	$newReservations=$DB->query('SELECT * from decaissement where promo=:promo order by (numdec)', array('promo'=>$_SESSION['promo']));
	$anneescolaire=($_SESSION['promo']-1).'-'.$_SESSION['promo'];?>
	Ordre;"date";"categorie";"motif";"Montant";"type de paie";"numero";"Annee-Scolaire"; <?php

	foreach($newReservations as $key => $row) {
		$datepaie=(new dateTime($row->datepaye))->format("d/m/Y");
		$categorie=$panier->nomCategorie($row->motif);
	    echo "\n".'"'.($key+1).'";"'.$datepaie.'";"'.$categorie.'";"'.$row->coment.'";"'.$row->montant.'";"'.$row->typepaye.'";"'.$row->numcheque.'";"'.$anneescolaire.'"';
	}
}


if (isset($_GET['enseig'])) {
	header("Content-disposition: attachment; filename=payement enseignant.csv");
	

	$newReservations=$DB->query('SELECT matricule, heurep, montant, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye from payenseignant where anneescolaire=:promo order by (numdec)', array('promo'=>$_SESSION['promo']));?>
	"Ordre";"Matricule";"Montant";"Payement";"Heure paye";"Motif";"Date";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.$row->montant.'";"'.$row->typepaye.'";"'.$row->heurep.'";"'.'Payement des Enseignants'.'";"'.$row->datepaye.'"';
	}
}


if (isset($_GET['perso'])) {
	header("Content-disposition: attachment; filename=payement personnel.csv");
	

	$newReservations=$DB->query('SELECT matricule, mois, montant, typepaye, DATE_FORMAT(datepaye, \'%d/%m/%Y\')AS datepaye from payepersonnel where promo=:promo order by (numdec)', array('promo'=>$_SESSION['promo']));?>
	"Ordre";"Matricule";"Montant";"Payement";"Mois paye";"Motif";"Date";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.$row->montant.'";"'.$row->typepaye.'";"'.$row->mois.'";"'.'Payement du Personnels'.'";"'.$row->datepaye.'"';
	}
}





if (isset($_GET['persodirec'])) {
	header("Content-disposition: attachment; filename=personnel de direction.csv");

	$newReservations=$DB->query('SELECT numpers as matricule, nom, prenom, sexe, datenaiss as naissance, lieunaiss, phone, email, adresse, agencebanq, numbanq, embauche from personnel inner join contact on personnel.numpers=contact.matricule');?>
	Ordre;"matricule";"Nom";"Prenom";"Sexe";"Ne(e)";"lieu de naissance";"Telephone";"email";"adresse";"date de debut";"numero banque";"agence banque";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.strtoupper($row->nom).'";"'.ucwords($row->prenom).'";"'.strtoupper($row->sexe).'";"'.$panier->dateformat($row->naissance).'";"'.$row->lieunaiss.'";"'.$row->phone.'";"'.$row->email.'";"'.$row->adresse.'";"'.$row->embauche.'";"'.$row->numbanq.'";"'.$row->agencebanq.'"';
	}
}


if (isset($_GET['enseignant'])) {
	header("Content-disposition: attachment; filename=enseignant.csv");

	$newReservations=$DB->query('SELECT enseignant.matricule as matricule, nomen, prenomen, sexe, datenaiss AS naissance, lieunaiss, phone, email, adresse, agencebanq, numbanq, embauche from enseignant inner join contact on enseignant.matricule=contact.matricule');?>
	Ordre;"matricule";"Nom";"Prenom";"Sexe";"Ne(e)";"lieu de naissance";"Telephone";"email";"adresse";"date de debut";"numero banque";"agence banque";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.strtoupper($row->nomen).'";"'.ucwords($row->prenomen).'";"'.strtoupper($row->sexe).'";"'.$panier->dateformat($row->naissance).'";"'.$row->lieunaiss.'";"'.$row->phone.'";"'.$row->email.'";"'.$row->adresse.'";"'.$row->embauche.'";"'.$row->numbanq.'";"'.$row->agencebanq.'"';
	}
}


if (isset($_GET['printnote'])) {
	header("Content-disposition: attachment; filename=notes.csv");
	

	$newReservations=$DB->query('SELECT note.matricule as matricule, nomel, prenomel, eleve.sexe as sexe, DATE_FORMAT(eleve.naissance, \'%Y\')AS naissance,  note, compo, nomen, prenomen, nommat, nomdev, type, trimes, nomgroupe, DATE_FORMAT(datedev, \'%d/%m/%Y\')AS datepaye  from note inner join devoir on codev=devoir.id inner join matiere on matiere.codem=note.codem inner join eleve on eleve.matricule=note.matricule inner join enseignant on enseignant.matricule=note.codens where promo=:promo order by (prenomel)', array('promo'=>$_SESSION['promo']));?>
	Ordre;"matricule";"Nom";"Prenom";"Sexe";"Ne(e)";"note de cours";"note de compo";"enseignant";"matiere";"nom du devoir";"type";"semestre";"classe";"date eval";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($key+1).'";"'.$row->matricule.'";"'.strtoupper($row->nomel).'";"'.ucwords($row->prenomel).'";"'.strtoupper($row->sexe).'";"'.$row->naissance.'";"'.$row->note.'";"'.$row->compo.'";"'.$row->prenomen.'";"'.$row->nommat.'";"'.$row->nomdev.'";"'.$row->type.'";"'.$row->trimes.'";"'.$row->nomgroupe.'";"'.$row->datepaye.'"';
	}
}



if (isset($_GET['save'])) {
	header("Content-disposition: attachment; filename=bdd.csv");
	

	$newReservations=$DB->query('SELECT *from absence');?>

			Table Absence

	matricule;"codem";"nomgr";"codens";"hdebut";"nbreheure";"dateabs";"semestre";"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->codem.'";"'.$row->nomgr.'";"'.$row->hdebut.'";"'.$row->nbreheure.'";"'.$row->dateabs.'";"'.$row->semestre.'";"'.$row->promo.'"';
	}

	$newReservations=$DB->query('SELECT *from accompte');?>

			Table accompte

	matricule;"montant";"mois";"moischaine";"typepaye";datepaye";"anneescolaire";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->montant.'";"'.$row->mois.'";"'.$row->moischaine.'";"'.$row->typepaye.'";"'.$row->datepaye.'";"'.$row->anneescolaire.'"';
	}

	$newReservations=$DB->query('SELECT *from admis');?>

			Table Admis

	matricule;"nomel";"moyenne";"nomgr";"semestre";"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->nomel.'";"'.$row->moyenne.'";"'.$row->nomgr.'";"'.$row->semestre.'";"'.$row->promo.'"';
	}

	$newReservations=$DB->query('SELECT *from banque');?>

			Table Banque

	id_banque;"numero";"libelles";"matriculeb";"montant";"promob";"date_versement";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->id_banque).'";"'.$row->numero.'";"'.$row->libelles.'";"'.$row->matriculeb.'";"'.$row->montant.'";"'.$row->promob.'","'.$row->date_versement.'"';
	}


	$newReservations=$DB->query('SELECT *from cloture');?>

			Table Cloture

	nomcloture;"promo";"date_cloture";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->nomcloture).'";"'.$row->promo.'";"'.$row->date_cloture.'"';
	}

	$newReservations=$DB->query('SELECT *from contact');?>

			Table Contact

	matricule;"phone";"email";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->phone.'";"'.$row->email.'"';
	}



	$newReservations=$DB->query('SELECT *from cursus');?>

			Table Cursus

	codecursus;"nom";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->codecursus).'";"'.$row->nom.'"';
	}



	$newReservations=$DB->query('SELECT *from decaissement');?>


			Table decaissement

	numdec;"matricule";"montant";"mois";"motif";"coment";"typepaye";"promo";"datepaye";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numdec).'";"'.$row->matricule.'";"'.$row->montant.'";"'.$row->mois.'";"'.$row->motif.'";"'.$row->coment.'";"'.$row->typepaye.'";"'.$row->promo.'";"'.$row->datepaye.'"';
	}



	$newReservations=$DB->query('SELECT *from devoir');?>


			Table devoir

	codens;"nomdev";"type";"coef";"coefcom";"trimes";"codem";"nomgroupe";"datedev";"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->codens).'";"'.$row->nomdev.'";"'.$row->type.'";"'.$row->coef.'";"'.$row->coefcom.'";"'.$row->trimes.'";"'.$row->codem.'";"'.$row->nomgroupe.'";"'.$row->datedev.'";"'.$row->promo.'"';
	}



	$newReservations=$DB->query('SELECT *from effectifn');?>

			Table effectifn

	matricule;"codev";"codem";"nomgr";"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->codev.'";"'.$row->codem.'";"'.$row->nomgr.'";"'.$row->promo.'"';
	}



	$newReservations=$DB->query('SELECT *from eleve');?>


			Table eleve

	matricule;"nomel";"prenomel";"sexe";"naissance";"pere";"mere";"telpere";"telmere";"pays";"nationnalite";"adresse";"dateenreg";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->nomel.'";"'.$row->prenomel.'";"'.$row->sexe.'";"'.$row->naissance.'";"'.$row->pere.'";"'.$row->mere.'";"'.$row->telpere.'";"'.$row->telmere.'";"'.$row->pays.'";"'.$row->nationnalite.'";"'.$row->adresse.'";"'.$row->dateenreg.'"';
	}


	$newReservations=$DB->query('SELECT *from enseignant');?>


			Table enseignant

	matricule;"nomen";"prenomen";"sexe";"naissance";"dateenreg";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->nomen.'";"'.$row->prenomen.'";"'.$row->sexe.'";"'.$row->naissance.'";"'.$row->dateenreg.'"';
	}


	$newReservations=$DB->query('SELECT *from enseignement');?>


			Table eleve

	nomgr;"codef";"codem";"codens";"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->nomgr).'";"'.$row->codef.'";"'.$row->codem.'";"'.$row->codens.'";"'.$row->promo.'"';
	}


	$newReservations=$DB->query('SELECT *from etablissement');?>


			Table etablissement

	nom;"numero";"phone";"email";"adresse";"pays";"region";"secteur";"devise";"cbanque";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->nom).'";"'.$row->numero.'";"'.$row->phone.'";"'.$row->email.'";"'.$row->adresse.'";"'.$row->pays.'";"'.$row->region.'";"'.$row->secteur.'";"'.$row->devise.'";"'.$row->cbanque.'"';
	}



	$newReservations=$DB->query('SELECT *from exclus');?>

			Table exclusions

	matricule;"codem";"codens";"hdebut";"motif";"semestre";"promo";"dateexclus";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->codem.'";"'.$row->codens.'";"'.$row->hdebut.'";"'.$row->motif.'";"'.$row->semestre.'";"'.$row->promo.'";'.$row->dateexclus.'"';
	}


	$newReservations=$DB->query('SELECT *from formation');?>


			Table formation

	niveau;"classe";"nomf";"codef";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->niveau).'";"'.$row->classe.'";"'.$row->nomf.'";"'.$row->codef.'"';
	}



	$newReservations=$DB->query('SELECT *from groupe');?>


			Table Groupe

	nomgr;"niveau";"codef";"profcoor";"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->nomgr).'";"'.$row->niveau.'";"'.$row->codef.'";"'.$row->profcoor.'";"'.$row->promo.'"';
	}



	$newReservations=$DB->query('SELECT *from histopayefrais');?>


			Table histopayefrais

	numpaye;"matricule";"montant";"tranche";"typepaye";"numpaie";"banque";promo";"famille";"datepaye";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numpaye).'";"'.$row->matricule.'";"'.$row->montant.'";"'.$row->tranche.'";"'.$row->typepaye.'";"'.$row->numpaie.'";"'.$row->banque.'";"'.$row->promo.'";"'.$row->famille.'";"'.$row->datepaye.'"';
	}


	$newReservations=$DB->query('SELECT *from histopayenseignant');?>

			Table histopayeenseignant

	numdec;"matricule";"montant";"mois";"heurep";"typepaye";"anneescolaire";"datepaye";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numdec).'";"'.$row->matricule.'";"'.$row->montant.'";"'.$row->mois.'";"'.$row->heurep.'";"'.$row->typepaye.'";"'.$row->anneescolaire.'";'.$row->datepaye.'"';
	}


	$newReservations=$DB->query('SELECT *from historiquesup');?>

			Table historiquesup

	type;"executeur";"promo";"datesup";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->type).'";"'.$row->executeur.'";"'.$row->promo.'";"'.$row->datesup.'"';
	}


	$newReservations=$DB->query('SELECT *from horairet');?>


			Table horairet

	numens;"heured";"heuret";"datet";"groupe";"matiere";"annees";"datesaisie";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numens).'";"'.$row->heured.'";"'.$row->heuret.'";"'.$row->datet.'";"'.$row->groupe.'";"'.$row->matiere.'";"'.$row->annees.'";"'.$row->datesaisie.'"';
	}



	$newReservations=$DB->query('SELECT *from inscription');?>


			Table inscription

	matricule;"codef";"niveau";"nomgr";"etat";"remise";"annee";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->codef.'";"'.$row->niveau.'";"'.$row->nomgr.'";"'.$row->etat.'";"'.$row->remise.'";"'.$row->annee.'"';
	}


	$newReservations=$DB->query('SELECT *from justabsence');?>

			Table justAbsence

	id_absence;"matricule";"motif";"datejust";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->id_absence).'";"'.$row->matricule.'";"'.$row->motif.'";"'.$row->datejust.'"';
	}



	$newReservations=$DB->query('SELECT *from licence');?>


			Table licence

	num_licence;"nom_societe";"date_souscription";"date_fin";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->num_licence).'";"'.$row->nom_societe.'";"'.$row->date_souscription.'";"'.$row->date_fin.'"';
	}



	$newReservations=$DB->query('SELECT *from listel');?>

			Table listel

	matricule;"nomel";"prenomel";"sexe";"naissance";"adresse";"pere";"mere";"nationnalite";"dateenreg";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->nomel.'";"'.$row->prenomel.'";"'.$row->sexe.'";"'.$row->naissance.'";"'.$row->adresse.'";"'.$row->pere.'";"'.$row->mere.'";"'.$row->nationnalite.'";"'.$row->dateenreg.'"';
	}


	$newReservations=$DB->query('SELECT *from login');?>


			Table login

	matricule;"pseudo";"mdp";"type";"niveau";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->pseudo.'";"'.$row->mdp.'";"'.$row->type.'";"'.$row->niveau.'"';
	}



	$newReservations=$DB->query('SELECT *from matiere');?>


			Table matiere

	codem;"nommat";"coef";"cat";"codef";"nbre_heure";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->codem).'";"'.$row->nommat.'";"'.$row->coef.'";"'.$row->cat.'";"'.$row->codef.'";"'.$row->nbre_heure.'"';
	}


	$newReservations=$DB->query('SELECT *from niveau');?>

			Table niveau

	matricule;"nom";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->nom.'"';
	}


	$newReservations=$DB->query('SELECT *from niveauc');?>

			Table niveauc

	matricule;"nom";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->nom.'"';
	}

	$newReservations=$DB->query('SELECT *from nombanque');?>

			Table nombanque

	nomb;"numero";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->nomb).'";"'.$row->numero.'"';
	}



	$newReservations=$DB->query('SELECT *from note');?>


			Table note

	matricule;"note";"compo";"codens";"codem";"codev";"datesaisie";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->note.'";"'.$row->compo.'";"'.$row->codens.'";"'.$row->codem.'";"'.$row->codev.'";"'.$row->datesaisie.'"';
	}


	$newReservations=$DB->query('SELECT *from payement');?>


			Table payement

	numpaye;"matricule";"montant";"remise";"mois";"motif";"typepaye";"numpaie";"banque";"promo";"datepaye";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numpaye).'";"'.$row->matricule.'";"'.$row->montant.'";"'.$row->remise.'";"'.$row->mois.'";"'.$row->motif.'";"'.$row->typepaye.'";"'.$row->numpaie.'";"'.$row->banque.'";"'.$row->promo.'";"'.$row->datepaye.'"';
	}



	$newReservations=$DB->query('SELECT *from payementfraiscol');?>


			Table payementfraiscol

	numpaye;"matricule";"montant";"tranche";"typepaye";"numpaie";"banque";"promo";"famille";"datepaye";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numpaye).'";"'.$row->matricule.'";"'.$row->montant.'";"'.$row->tranche.'";"'.$row->typepaye.'";"'.$row->numpaie.'";"'.$row->banque.'";"'.$row->promo.'";"'.$row->famille.'";"'.$row->datepaye.'"';
	}



	$newReservations=$DB->query('SELECT *from payenseignant');?>


			Table payenseignant

	numdec;"matricule";"montant";"mois";"heurep";"motif";"typepaye";"anneescolaire";"datepaye";"etat";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numdec).'";"'.$row->matricule.'";"'.$row->montant.'";"'.$row->mois.'";"'.$row->heurep.'";"'.$row->motif.'";"'.$row->typepaye.'";"'.$row->anneescolaire.'";"'.$row->datepaye.'";"'.$row->etat.'"';
	}


	$newReservations=$DB->query('SELECT *from payepersonnel');?>


			Table payepersonnel

	numdec;"matricule";"montant";"mois";"motif";"typepaye";"promo";"datepaye";"etat";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numdec).'";"'.$row->matricule.'";"'.$row->montant.'";"'.$row->mois.'";"'.$row->motif.'";"'.$row->typepaye.'";"'.$row->promo.'";"'.$row->datepaye.'";"'.$row->etat.'"';
	}



	$newReservations=$DB->query('SELECT *from personnel');?>


			Table personnel

	numpers;"nom";"prenom";"sexe";"datenaissance";"dateenreg";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numpers).'";"'.$row->nom.'";"'.$row->prenom.'";"'.$row->sexe.'";"'.$row->datenaissance.'";"'.$row->dateenreg.'"';
	}



	$newReservations=$DB->query('SELECT *from rangel');?>

			Table rangel

	matricule;"rang";"moyenne";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->rang.'";"'.$row->moyenne.'"';
	}



	$newReservations=$DB->query('SELECT *from repartition');?>

			Table repartition

	codecursus;"type";"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->codecursus).'";"'.$row->type.'";"'.$row->promo.'"';
	}



	$newReservations=$DB->query('SELECT *from retard');?>

			Table retard

	matricule;"codem";"codens";"hdebut";"timeretard";"semestre";"promo";"dateabs";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matricule).'";"'.$row->codem.'";"'.$row->hdebut.'";"'.$row->timeretard.'";"'.$row->semestre.'";"'.$row->promo.'";"'.$row->dateabs.'"';
	}



	$newReservations=$DB->query('SELECT *from salaireens');?>

			Table salaireens

	numpers;"salaire";"thoraire";"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numpers).'";"'.$row->salaire.'";"'.$row->thoraire.'";"'.$row->promo.'"';
	}



	$newReservations=$DB->query('SELECT *from salairepers');?>

			Table salairepers

	numpers;"salaire";"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numpers).'";"'.$row->salaire.'";"'.$row->promo.'"';
	}



	$newReservations=$DB->query('SELECT *from scolarite');?>


			Table scolarite

	codef;"montant";"tranche";"limite";"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->codef).'";"'.$row->montant.'";"'.$row->tranche.'";"'.$row->limite.'";"'.$row->promo.'"';
	}



	$newReservations=$DB->query('SELECT *from ssocialens');?>

			Table ssocialens

	numpers;"montant";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numpers).'";"'.$row->montant.'"';
	}



	$newReservations=$DB->query('SELECT *from ssocialpers');?>

			Table ssocialpers

	numpers;"montant";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->numpers).'";"'.$row->montant.'"';
	}




	$newReservations=$DB->query('SELECT *from tranche');?>


			Table tranche

	nom;"promo";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->nom).'";"'.$row->promo.'"';
	}



	$newReservations=$DB->query('SELECT *from tuteur');?>

			Table tuteur remplacer id par id_tut

	matuteur;"nomtut";"teltut";"matricule";<?php

	foreach($newReservations as $key => $row) {

	    echo "\n".'"'.($row->matuteur).'";"'.$row->nomtut.'";"'.$row->teltut.'";"'.$row->matricule.'"';
	}
}





?>