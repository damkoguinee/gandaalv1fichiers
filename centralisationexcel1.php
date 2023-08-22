<?php
require '_header.php';
header("Content-type: text/csv;");


header("Content-disposition: attachment; filename=centralisation centralisation.csv");?>
infos;"sexe";"";"";"";" ";" ";" ";" ";" ";" ";" ";" ";" ";" ";" ";" ";"Matiere";"Maths";"Physique";"Chimie";"Biologie";"Géologie";"Français";"Histoire";"Géographie";"Economie";"Philo";"Anglais";"L.Arabe";"Ens. Islam";"EPS";"ECM";"Sciences Physique";"Histoire/ Géographie";"SVT(Biologie/Géologie)";"Total";"Moyenne Generale";"Rang";
IRE;"DPE/DCE";"N";Prenoms et Nom;"sexe";"date_nais";"lieu_nais";"pere";"mere";"etablissement";"option";"classe";"Annee-Scolaire";"statut";"zone";"code ecole";" ";"coef";<?php

$codef=$_GET['codef'];
if ($codef=='7col') {
    $codemmaths='mat7col';
    $codemphysique='phy7col';
    $codemchimie='chi7col';
    $codembiologie='bio7col';
    $codemgeologie='geol7col';
    $codemfrancais='dicq7col';
    $codemhistoire='his7col';
    $codemgeographie='geo7col';
    $codemeconomie='eco7col';
    $codemphilo='phi7col';
    $codemanglais='ang7col';
    $codemeps='eps7col';
    $codemecm='ecm7col';
    $classe='7ème';
    $option='';
}elseif ($codef=='8col') {
    $codemmaths='mat8col';
    $codemphysique='phy8col';
    $codemchimie='chi8col';
    $codembiologie='bio8col';
    $codemgeologie='geol8col';
    $codemfrancais='dicq8col';
    $codemhistoire='his8col';
    $codemgeographie='geo8col';
    $codemeconomie='eco8col';
    $codemphilo='phi8col';
    $codemanglais='ang8col';
    $codemeps='eps8col';
    $codemecm='ecm8col';
    $classe='8ème';
    $option='';
}elseif ($codef=='9col') {
    $codemmaths='mat9col';
    $codemphysique='phy9col';
    $codemchimie='chi9col';
    $codembiologie='bio9col';
    $codemgeologie='geol9col';
    $codemfrancais='dicq9col';
    $codemhistoire='his9col';
    $codemgeographie='geo9col';
    $codemeconomie='eco9col';
    $codemphilo='phi9col';
    $codemanglais='ang9col';
    $codemeps='eps9col';
    $codemecm='ecm9col';
    $classe='9ème';
    $option='';
}elseif ($codef=='10col') {
    $codemmaths='mat10col';
    $codemphysique='phy10col';
    $codemchimie='chi10col';
    $codembiologie='bio10col';
    $codemgeologie='geol10col';
    $codemfrancais='dicq10col';
    $codemhistoire='his10col';
    $codemgeographie='geo10col';
    $codemeconomie='eco10col';
    $codemphilo='phi10col';
    $codemanglais='ang10col';
    $codemeps='eps10col';
    $codemecm='ecm10col';
    $classe='10ème';
    $option='';
}elseif ($codef=='11se') {
    $codemmaths='mat11se';
    $codemphysique='phy11se';
    $codemchimie='chi11se';
    $codembiologie='bio11se';
    $codemgeologie='geol11se';
    $codemfrancais='fr11se';
    $codemhistoire='his11se';
    $codemgeographie='geo11se';
    $codemeconomie='eco11se';
    $codemphilo='phi11se';
    $codemanglais='ang11se';
    $codemeps='eps11se';
    $codemecm='ecm11se';
    $classe='11ème';
    $option='série littéraire';
}elseif ($codef=='11sm') {
    $codemmaths='mat11sm';
    $codemphysique='phy11sm';
    $codemchimie='chi11sm';
    $codembiologie='bio11sm';
    $codemgeologie='geol11sm11sm';
    $codemfrancais='fr11sm';
    $codemhistoire='his11sm';
    $codemgeographie='geosm11sm';
    $codemeconomie='eco11sm';
    $codemphilo='phi11sm';
    $codemanglais='ang11sm';
    $codemeps='eps11sm';
    $codemecm='ecm11sm';
    $classe='11ème';
    $option='série scientifique';
}elseif ($codef=='12sm') {
    $codemmaths='mat12sm';
    $codemphysique='phy12sm';
    $codemchimie='chi12sm';
    $codembiologie='bio12sm';
    $codemgeologie='geol12sm';
    $codemfrancais='fr12sm';
    $codemhistoire='his12sm';
    $codemgeographie='geo12sm';
    $codemeconomie='eco12sm';
    $codemphilo='phi12sm';
    $codemanglais='ang12sm';
    $codemeps='eps12sm';
    $codemecm='ecm12sm';
    $classe='12ème';
    $option='sciences maths';
}elseif ($codef=='12se') {
    $codemmaths='mat12se';
    $codemphysique='phy12se';
    $codemchimie='chi12se';
    $codembiologie='bio12se';
    $codemgeologie='geol12se';
    $codemfrancais='fr12se';
    $codemhistoire='his12se';
    $codemgeographie='geo12se';
    $codemeconomie='eco12se';
    $codemphilo='phi12se';
    $codemanglais='ang12se';
    $codemeps='eps12se';
    $codemecm='ecm12se';
    $classe='12ème';
    $option='sciences expérimentales';
}elseif ($codef=='12ss') {
    $codemmaths='mat12ss';
    $codemphysique='phy12ss';
    $codemchimie='chi12ss';
    $codembiologie='bio12ss';
    $codemgeologie='geol12ss';
    $codemfrancais='fr12ss';
    $codemhistoire='his12ss';
    $codemgeographie='geo12ss';
    $codemeconomie='eco12ss';
    $codemphilo='phi12ss';
    $codemanglais='ang12ss';
    $codemeps='eps12ss';
    $codemecm='ecm12ss';
    $classe='12ème';
    $option='sciences sociales';
}elseif ($codef=='tsm') {
    $codemmaths='mattsm';
    $codemphysique='phytsm';
    $codemchimie='chitsm';
    $codembiologie='biotsm';
    $codemgeologie='geoltsm';
    $codemfrancais='frtsm';
    $codemhistoire='histsm';
    $codemgeographie='geotsm';
    $codemeconomie='ecotsm';
    $codemphilo='phitsm';
    $codemanglais='angtsm';
    $codemeps='epstsm';
    $codemecm='ecmtsm';
    $classe='Terminale';
    $option='sciences maths';
}elseif ($codef=='tse') {
    $codemmaths='mattse';
    $codemphysique='phytse';
    $codemchimie='chitse';
    $codembiologie='biotse';
    $codemgeologie='geoltse';
    $codemfrancais='frtse';
    $codemhistoire='histse';
    $codemgeographie='geotse';
    $codemeconomie='ecotse';
    $codemphilo='phitse';
    $codemanglais='angtse';
    $codemeps='epstse';
    $codemecm='ecmtse';
    $classe='terminale';
    $option='sciences expérimentales';
}elseif ($codef=='tss') {
    $codemmaths='mattss';
    $codemphysique='phytss';
    $codemchimie='chitss';
    $codembiologie='biotss';
    $codemgeologie='geoltss';
    $codemfrancais='frtss';
    $codemhistoire='histss';
    $codemgeographie='geotss';
    $codemeconomie='ecotss';
    $codemphilo='phitss';
    $codemanglais='angtss';
    $codemeps='epstss';
    $codemecm='ecmtss';
    $classe='Terminale';
    $option='sciences sociales';
}

$etab=$DB->querys("SELECT  *from etablissement");

$prodmat=$DB->query("SELECT  inscription.matricule as matricule, nomel, prenomel, DATE_FORMAT(naissance, \"%d/%m/%Y\")AS naissance, sexe, adresse, pere, mere, codef, nomgr from inscription inner join eleve on inscription.matricule=eleve.matricule where codef='{$codef}' and annee='{$_SESSION['promo']}' order by (prenomel)");



foreach($prodmat as $key => $row) {

    echo "\n".'"'.$etab['ire'].'";"'.$etab['dpe'].'";"'.($key+1).'";"'.ucwords($row->prenomel).' '.strtoupper($row->nomel).'";"'.$row->sexe.'";"'.$row->naissance.'";"'.$row->adresse.'";"'.$row->pere.'";"'.$row->mere.'";"'.''.'";"'.$option.'";"'.$classe.'";"'.(($_SESSION['promo']-1).'-'.$_SESSION['promo']).'";"'.''.'";"'.$etab['secteur'].'";"'.$etab['numero'].'"';


    //**********************************requete mathématique****************************************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnotemaths=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemmaths}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotemaths['note'])) {

        $notedecoursmaths='';

    }else{

       $notedecoursmaths=$prodnotemaths['note']/($prodnotemaths['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnotemaths=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemmaths}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotemaths['compo'])) {

        $compositionmaths='';
    }else{

       $compositionmaths=$prodnotemaths['compo']/($prodnotemaths['coefc']);; //

    }
    
    $note1cmaths=$notedecoursmaths;
    $compo1cmaths=$compositionmaths;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnotemaths=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemmaths}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotemaths['note'])) {

        $notedecoursmaths='';

    }else{

       $notedecoursmaths=$prodnotemaths['note']/($prodnotemaths['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnotemaths=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemmaths}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotemaths['compo'])) {

        $compositionmaths='';
    }else{

       $compositionmaths=$prodnotemaths['compo']/($prodnotemaths['coefc']);

    }
    
    $note2cmaths=$notedecoursmaths;
    $compo2cmaths=$compositionmaths;    


    //**********************************Requete Physique******************************************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnotephysique=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemphysique}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnotephysique['note'])) {

        $notedecoursphysique='';

    }else{

       $notedecoursphysique=$prodnotephysique['note']/($prodnotephysique['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnotephysique=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemphysique}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotephysique['compo'])) {

        $compositionphysique='';
    }else{

       $compositionphysique=$prodnotephysique['compo']/($prodnotephysique['coefc']); //

    }
    
    $note1cphysique=$notedecoursphysique;
    $compo1cphysique=$compositionphysique;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnotephysique=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemphysique}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotephysique['note'])) {

        $notedecoursphysique='';

    }else{

       $notedecoursphysique=$prodnotephysique['note']/($prodnotephysique['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnotephysique=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemphysique}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotephysique['compo'])) {

        $compositionphysique='';
    }else{

       $compositionphysique=$prodnotephysique['compo']/($prodnotephysique['coefc']);

    }
    
    $note2cphysique=$notedecoursphysique;
    $compo2cphysique=$compositionphysique;


    //*******************************requete Chimie*********************************************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnotechimie=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemchimie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnotechimie['note'])) {

        $notedecourschimie='';

    }else{

       $notedecourschimie=$prodnotechimie['note']/($prodnotechimie['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnotechimie=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemchimie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotechimie['compo'])) {

        $compositionchimie='';
    }else{

       $compositionchimie=$prodnotechimie['compo']/($prodnotechimie['coefc']); //

    }
    
    $note1cchimie=$notedecourschimie;
    $compo1cchimie=$compositionchimie;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnotechimie=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemchimie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotechimie['note'])) {

        $notedecourschimie='';

    }else{

       $notedecourschimie=$prodnotechimie['note']/($prodnotechimie['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnotechimie=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemchimie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotechimie['compo'])) {

        $compositionchimie='';
    }else{

       $compositionchimie=$prodnotechimie['compo']/($prodnotechimie['coefc']);

    }
    
    $note2cchimie=$notedecourschimie;
    $compo2cchimie=$compositionchimie;

    //******************************************requete biologie********************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnotebiologie=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codembiologie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnotebiologie['note'])) {

        $notedecoursbiologie='';

    }else{

       $notedecoursbiologie=$prodnotebiologie['note']/($prodnotebiologie['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnotebiologie=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codembiologie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotebiologie['compo'])) {

        $compositionbiologie='';
    }else{

       $compositionbiologie=$prodnotebiologie['compo']/($prodnotebiologie['coefc']); //

    }
    
    $note1cbiologie=$notedecoursbiologie;
    $compo1cbiologie=$compositionbiologie;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnotebiologie=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codembiologie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotebiologie['note'])) {

        $notedecoursbiologie='';

    }else{

       $notedecoursbiologie=$prodnotebiologie['note']/($prodnotebiologie['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnotebiologie=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codembiologie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotebiologie['compo'])) {

        $compositionbiologie='';
    }else{

       $compositionbiologie=$prodnotebiologie['compo']/($prodnotebiologie['coefc']);

    }
    
    $note2cbiologie=$notedecoursbiologie;
    $compo2cbiologie=$compositionbiologie;


    //******************************************requete géologie********************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnotegeologie=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemgeologie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnotegeologie['note'])) {

        $notedecoursgeologie='';

    }else{

       $notedecoursgeologie=$prodnotegeologie['note']/($prodnotegeologie['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnotegeologie=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemgeologie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotegeologie['compo'])) {

        $compositiongeologie='';
    }else{

       $compositiongeologie=$prodnotegeologie['compo']/($prodnotegeologie['coefc']); //

    }
    
    $note1cgeologie=$notedecoursgeologie;
    $compo1cgeologie=$compositiongeologie;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnotegeologie=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemgeologie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotegeologie['note'])) {

        $notedecoursgeologie='';

    }else{

       $notedecoursgeologie=$prodnotegeologie['note']/($prodnotegeologie['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnotegeologie=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemgeologie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotegeologie['compo'])) {

        $compositiongeologie='';
    }else{

       $compositiongeologie=$prodnotegeologie['compo']/($prodnotegeologie['coefc']);

    }
    
    $note2cgeologie=$notedecoursgeologie;
    $compo2cgeologie=$compositiongeologie;

    //******************************************requete français********************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnotefrancais=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemfrancais}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnotefrancais['note'])) {

        $notedecoursfrancais='';

    }else{

       $notedecoursfrancais=$prodnotefrancais['note']/($prodnotefrancais['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnotefrancais=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemfrancais}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotefrancais['compo'])) {

        $compositionfrancais='';
    }else{

       $compositionfrancais=$prodnotefrancais['compo']/($prodnotefrancais['coefc']); //

    }
    
    $note1cfrancais=$notedecoursfrancais;
    $compo1cfrancais=$compositionfrancais;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnotefrancais=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemfrancais}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotefrancais['note'])) {

        $notedecoursfrancais='';

    }else{

       $notedecoursfrancais=$prodnotefrancais['note']/($prodnotefrancais['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnotefrancais=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemfrancais}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotefrancais['compo'])) {

        $compositionfrancais='';
    }else{

       $compositionfrancais=$prodnotefrancais['compo']/($prodnotefrancais['coefc']);

    }
    
    $note2cfrancais=$notedecoursfrancais;
    $compo2cfrancais=$compositionfrancais;

    //******************************************requete histoire********************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnotehistoire=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemhistoire}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnotehistoire['note'])) {

        $notedecourshistoire='';

    }else{

       $notedecourshistoire=$prodnotehistoire['note']/($prodnotehistoire['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnotehistoire=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemhistoire}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotehistoire['compo'])) {

        $compositionhistoire='';
    }else{

       $compositionhistoire=$prodnotehistoire['compo']/($prodnotehistoire['coefc']); //

    }
    
    $note1chistoire=$notedecourshistoire;
    $compo1chistoire=$compositionhistoire;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnotehistoire=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemhistoire}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotehistoire['note'])) {

        $notedecourshistoire='';

    }else{

       $notedecourshistoire=$prodnotehistoire['note']/($prodnotehistoire['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnotehistoire=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemhistoire}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotehistoire['compo'])) {

        $compositionhistoire='';
    }else{

       $compositionhistoire=$prodnotehistoire['compo']/($prodnotehistoire['coefc']);

    }
    
    $note2chistoire=$notedecourshistoire;
    $compo2chistoire=$compositionhistoire;

    //******************************************requete géographie********************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnotegeographie=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemgeographie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnotegeographie['note'])) {

        $notedecoursgeographie='';

    }else{

       $notedecoursgeographie=$prodnotegeographie['note']/($prodnotegeographie['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnotegeographie=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemgeographie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotegeographie['compo'])) {

        $compositiongeographie='';
    }else{

       $compositiongeographie=$prodnotegeographie['compo']/($prodnotegeographie['coefc']); //

    }
    
    $note1cgeographie=$notedecoursgeographie;
    $compo1cgeographie=$compositiongeographie;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnotegeographie=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemgeographie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotegeographie['note'])) {

        $notedecoursgeographie='';

    }else{

       $notedecoursgeographie=$prodnotegeographie['note']/($prodnotegeographie['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnotegeographie=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemgeographie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotegeographie['compo'])) {

        $compositiongeographie='';
    }else{

       $compositiongeographie=$prodnotegeographie['compo']/($prodnotegeographie['coefc']);

    }
    
    $note2cgeographie=$notedecoursgeographie;
    $compo2cgeographie=$compositiongeographie;

    //******************************************requete economie********************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnoteeconomie=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemeconomie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnoteeconomie['note'])) {

        $notedecourseconomie='';

    }else{

       $notedecourseconomie=$prodnoteeconomie['note']/($prodnoteeconomie['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnoteeconomie=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemeconomie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteeconomie['compo'])) {

        $compositioneconomie='';
    }else{

       $compositioneconomie=$prodnoteeconomie['compo']/($prodnoteeconomie['coefc']); //

    }
    
    $note1ceconomie=$notedecourseconomie;
    $compo1ceconomie=$compositioneconomie;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnoteeconomie=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemeconomie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteeconomie['note'])) {

        $notedecourseconomie='';

    }else{

       $notedecourseconomie=$prodnoteeconomie['note']/($prodnoteeconomie['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnoteeconomie=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemeconomie}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteeconomie['compo'])) {

        $compositioneconomie='';
    }else{

       $compositioneconomie=$prodnoteeconomie['compo']/($prodnoteeconomie['coefc']);

    }
    
    $note2ceconomie=$notedecourseconomie;
    $compo2ceconomie=$compositioneconomie;

    //******************************************requete philosophie********************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnotephilo=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemphilo}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnotephilo['note'])) {

        $notedecoursphilo='';

    }else{

       $notedecoursphilo=$prodnotephilo['note']/($prodnotephilo['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnotephilo=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemphilo}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotephilo['compo'])) {

        $compositionphilo='';
    }else{

       $compositionphilo=$prodnotephilo['compo']/($prodnotephilo['coefc']); //

    }
    
    $note1cphilo=$notedecoursphilo;
    $compo1cphilo=$compositionphilo;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnotephilo=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemphilo}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotephilo['note'])) {

        $notedecoursphilo='';

    }else{

       $notedecoursphilo=$prodnotephilo['note']/($prodnotephilo['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnotephilo=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemphilo}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnotephilo['compo'])) {

        $compositionphilo='';
    }else{

       $compositionphilo=$prodnotephilo['compo']/($prodnotephilo['coefc']);

    }
    
    $note2cphilo=$notedecoursphilo;
    $compo2cphilo=$compositionphilo;

    //******************************************requete anglais********************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnoteanglais=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemanglais}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnoteanglais['note'])) {

        $notedecoursanglais='';

    }else{

       $notedecoursanglais=$prodnoteanglais['note']/($prodnoteanglais['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnoteanglais=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemanglais}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteanglais['compo'])) {

        $compositionanglais='';
    }else{

       $compositionanglais=$prodnoteanglais['compo']/($prodnoteanglais['coefc']); //

    }
    
    $note1canglais=$notedecoursanglais;
    $compo1canglais=$compositionanglais;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnoteanglais=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemanglais}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteanglais['note'])) {

        $notedecoursanglais='';

    }else{

       $notedecoursanglais=$prodnoteanglais['note']/($prodnoteanglais['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnoteanglais=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemanglais}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteanglais['compo'])) {

        $compositionanglais='';
    }else{

       $compositionanglais=$prodnoteanglais['compo']/($prodnoteanglais['coefc']);

    }
    
    $note2canglais=$notedecoursanglais;
    $compo2canglais=$compositionanglais;

    //******************************************requete EPS********************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnoteeps=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemeps}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnoteeps['note'])) {

        $notedecourseps='';

    }else{

       $notedecourseps=$prodnoteeps['note']/($prodnoteeps['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnoteeps=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemeps}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteeps['compo'])) {

        $compositioneps='';
    }else{

       $compositioneps=$prodnoteeps['compo']/($prodnoteeps['coefc']); //

    }
    
    $note1ceps=$notedecourseps;
    $compo1ceps=$compositioneps;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnoteeps=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemeps}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteeps['note'])) {

        $notedecourseps='';

    }else{

       $notedecourseps=$prodnoteeps['note']/($prodnoteeps['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnoteeps=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemeps}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteeps['compo'])) {

        $compositioneps='';
    }else{

       $compositioneps=$prodnoteeps['compo']/($prodnoteeps['coefc']);

    }
    
    $note2ceps=$notedecourseps;
    $compo2ceps=$compositioneps;

    //******************************************requete ECM********************************


    //**************************************1ere Semestre****************************************************

    $type='note de cours';
    $trimes=1;

    $prodnoteecm=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemecm}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");


    if (empty($prodnoteecm['note'])) {

        $notedecoursecm='';

    }else{

       $notedecoursecm=$prodnoteecm['note']/($prodnoteecm['coef']);

    }

    $type='composition';
    $trimes=1;

    $prodnoteecm=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemecm}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteecm['compo'])) {

        $compositionecm='';
    }else{

       $compositionecm=$prodnoteecm['compo']/($prodnoteecm['coefc']); //

    }
    
    $note1cecm=$notedecoursecm;
    $compo1cecm=$compositionecm;

    //**************************************************2eme semestre************************************************

    $type='note de cours';
    $trimes=2;

    $prodnoteecm=$DB->querys("SELECT devoir.id as id, sum(note*coef) as note, sum(coef) as coef from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemecm}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteecm['note'])) {

        $notedecoursecm='';

    }else{

       $notedecoursecm=$prodnoteecm['note']/($prodnoteecm['coef']);

    }

    $type='composition';
    $trimes=2;

    $prodnoteecm=$DB->querys("SELECT devoir.id as id, sum(compo*coefcom) as compo,  count(coefcom) as coefc from note inner join devoir on note.codev=devoir.id where type='{$type}'  and trimes='{$trimes}' and note.codem='{$codemecm}' and note.matricule='{$row->matricule}' and devoir.promo='{$_SESSION['promo']}' ");

    if (empty($prodnoteecm['compo'])) {

        $compositionecm='';
    }else{

       $compositionecm=$prodnoteecm['compo']/($prodnoteecm['coefc']);

    }
    
    $note2cecm=$notedecoursecm;
    $compo2cecm=$compositionecm;

    $colspan=2;
    while ($colspan<=8) {
        if ($colspan<=4) {
            $semestre='1er S';
        }else{
            $semestre='2eme S';
        }
        if ($colspan==2) {
            $type='cours';
        }
        if ($colspan==3) {
            $type='compo';
        }
        if ($colspan==4) {
            $type='Moy 1';
            if (empty($compo1cmaths)) {

                $moyennecoursmaths='';
                $moyennecours1maths='';
            }else{

               $moyennecoursmaths=($note1cmaths+$compo1cmaths)/2;
               $moyennecours1maths=$moyennecoursmaths;

            }

            if (empty($compo1cphysique)) {

                $moyennecoursphysique='';
                $moyennecours1physique='';
            }else{

               $moyennecoursphysique=($note1cphysique+$compo1cphysique)/2;
               $moyennecours1physique=$moyennecoursphysique;

            }

            if (empty($compo1cchimie)) {

                $moyennecourschimie='';
                $moyennecours1chimie='';
            }else{

               $moyennecourschimie=($note1cchimie+$compo1cchimie)/2;
               $moyennecours1chimie=$moyennecourschimie;

            }

            if (empty($compo1cbiologie)) {

                $moyennecoursbiologie='';
                $moyennecours1biologie='';
            }else{

               $moyennecoursbiologie=($note1cbiologie+$compo1cbiologie)/2;
               $moyennecours1biologie=$moyennecoursbiologie;

            }

            if (empty($compo1cgeologie)) {

                $moyennecoursgeologie='';
                $moyennecours1geologie='';
            }else{

               $moyennecoursgeologie=($note1cbiologie+$compo1cgeologie)/2;
               $moyennecours1geologie=$moyennecoursgeologie;

            }

            if (empty($compo1cfrancais)) {

                $moyennecoursfrancais='';
                $moyennecours1francais='';
            }else{

               $moyennecoursfrancais=($note1cfrancais+$compo1cfrancais)/2;
               $moyennecours1francais=$moyennecoursfrancais;

            }

            if (empty($compo1chistoire)) {

                $moyennecourshistoire='';
                $moyennecours1histoire='';
            }else{

               $moyennecourshistoire=($note1chistoire+$compo1chistoire)/2;
               $moyennecours1histoire=$moyennecourshistoire;

            }

            if (empty($compo1cgeographie)) {

                $moyennecoursgeographie='';
                $moyennecours1geographie='';
            }else{

               $moyennecoursgeographie=($note1cgeographie+$compo1cgeographie)/2;
               $moyennecours1geographie=$moyennecoursgeographie;

            }

            if (empty($compo1ceconomie)) {

                $moyennecourseconomie='';
                $moyennecours1economie='';
            }else{

               $moyennecourseconomie=($note1ceconomie+$compo1ceconomie)/2;
               $moyennecours1economie=$moyennecourseconomie;

            }
            if (empty($compo1cphilo)) {

                $moyennecoursphilo='';
                $moyennecours1philo='';
            }else{

               $moyennecoursphilo=($note1cphilo+$compo1cphilo)/2;
               $moyennecours1philo=$moyennecoursphilo;

            }
            if (empty($compo1canglais)) {

                $moyennecoursanglais='';
                $moyennecours1anglais='';
            }else{

               $moyennecoursanglais=($note1canglais+$compo1canglais)/2;
               $moyennecours1anglais=$moyennecoursanglais;

            }
            if (empty($compo1ceps)) {

                $moyennecourseps='';
                $moyennecours1eps='';
            }else{

               $moyennecourseps=($note1ceps+$compo1ceps)/2;
               $moyennecours1eps=$moyennecourseps;

            }

            if (empty($compo1cecm)) {

                $moyennecoursecm='';
                $moyennecours1ecm='';
            }else{

               $moyennecoursecm=($note1cecm+$compo1cecm)/2;
               $moyennecours1ecm=$moyennecoursecm;

            }
            
        }
        if ($colspan==5) {
            $type='cours';
        }
        if ($colspan==6) {
            $type='compo';
        }
        if ($colspan==7) {
            $type='Moy 2';
            if (empty($compo2cmaths)) {

                $moyennecoursmaths='';
                $moyennecours2maths='';
            }else{

               $moyennecoursmaths=($note2cmaths+$compo2cmaths)/2;
               $moyennecours2maths=$moyennecoursmaths;

            }

            if (empty($compo2cphysique)) {

                $moyennecoursphysique='';
                $moyennecours2physique='';
            }else{

               $moyennecoursphysique=($note2cphysique+$compo2cphysique)/2;
               $moyennecours2physique=$moyennecoursphysique;

            }

            if (empty($compo2cchimie)) {

                $moyennecourschimie='';
                $moyennecours2chimie='';
            }else{

               $moyennecourschimie=($note2cchimie+$compo2cchimie)/2;
               $moyennecours2chimie=$moyennecourschimie;

            }

            if (empty($compo2cbiologie)) {

                $moyennecoursbiologie='';
                $moyennecours2biologie='';
            }else{

               $moyennecoursbiologie=($note2cbiologie+$compo2cbiologie)/2;
               $moyennecours2biologie=$moyennecoursbiologie;

            }

            if (empty($compo2cgeologie)) {

                $moyennecoursgeologie='';
                $moyennecours2geologie='';
            }else{

               $moyennecoursgeologie=($note2cgeologie+$compo2cgeologie)/2;
               $moyennecours2geologie=$moyennecoursgeologie;

            }

            if (empty($compo2cfrancais)) {

                $moyennecoursfrancais='';
                $moyennecours2francais='';
            }else{

               $moyennecoursfrancais=($note2cfrancais+$compo2cfrancais)/2;
               $moyennecours2francais=$moyennecoursfrancais;

            }

            if (empty($compo2chistoire)) {

                $moyennecourshistoire='';
                $moyennecours2histoire='';
            }else{

               $moyennecourshistoire=($note2chistoire+$compo2chistoire)/2;
               $moyennecours2histoire=$moyennecourshistoire;

            }
            if (empty($compo2cgeographie)) {

                $moyennecoursgeographie='';
                $moyennecours2geographie='';
            }else{

               $moyennecoursgeographie=($note2cgeographie+$compo2cgeographie)/2;
               $moyennecours2geographie=$moyennecoursgeographie;

            }
            if (empty($compo2ceconomie)) {

                $moyennecourseconomie='';
                $moyennecours2economie='';
            }else{

               $moyennecourseconomie=($note2ceconomie+$compo2ceconomie)/2;
               $moyennecours2economie=$moyennecourseconomie;

            }
            if (empty($compo2cphilo)) {

                $moyennecoursphilo='';
                $moyennecours2philo='';
            }else{

               $moyennecoursphilo=($note2cphilo+$compo2cphilo)/2;
               $moyennecours2philo=$moyennecoursphilo;

            }
            if (empty($compo2canglais)) {

                $moyennecoursanglais='';
                $moyennecours2anglais='';
            }else{

               $moyennecoursanglais=($note2canglais+$compo2canglais)/2;
               $moyennecours2anglais=$moyennecoursanglais;

            }
            if (empty($compo2ceps)) {

                $moyennecourseps='';
                $moyennecours2eps='';
            }else{

               $moyennecourseps=($note2ceps+$compo2ceps)/2;
               $moyennecours2eps=$moyennecourseps;

            }
            if (empty($compo2cecm)) {

                $moyennecoursecm='';
                $moyennecours2ecm='';
            }else{

               $moyennecoursecm=($note2cecm+$compo2cecm)/2;
               $moyennecours2ecm=$moyennecoursecm;

            }
        }

        if ($colspan==8) {
            $type='Annuelle';
            if (empty($moyennecours2maths)) {
                $annuellemaths=($moyennecours1maths);
            }else{
                $annuellemaths=($moyennecours1maths+$moyennecours2maths)/2;
            }

            if (empty($moyennecours2physique)) {
                $annuellephysique=($moyennecours1physique);
            }else{
                $annuellephysique=($moyennecours1physique+$moyennecours2physique)/2;
            }

            if (empty($moyennecours2maths)) {
                $annuellechimie=($moyennecours1chimie);
            }else{
                $annuellechimie=($moyennecours1chimie+$moyennecours2chimie)/2;
            }
            if (empty($moyennecours2biologie)) {
                $annuellebiologie=($moyennecours1biologie);
            }else{
                $annuellebiologie=($moyennecours1biologie+$moyennecours2biologie)/2;
            }

            if (empty($moyennecours2geologie)) {
                $annuellegeologie=($moyennecours1geologie);
            }else{
                $annuellegeologie=($moyennecours1geologie+$moyennecours2geologie)/2;
            }

            if (empty($moyennecours2francais)) {
                $annuellefrancais=($moyennecours1francais);
            }else{
                $annuellefrancais=($moyennecours1francais+$moyennecours2francais)/2;
            }

            if (empty($moyennecours2histoire)) {
                $annuellehistoire=($moyennecours1histoire);
            }else{
                $annuellehistoire=($moyennecours1histoire+$moyennecours2histoire)/2;
            }

            if (empty($moyennecours2geographie)) {
                $annuellegeographie=($moyennecours1geographie);
            }else{
                $annuellegeographie=($moyennecours1geographie+$moyennecours2geographie)/2;
            }

            if (empty($moyennecours2economie)) {
                $annuelleeconomie=($moyennecours1economie);
            }else{
                $annuelleeconomie=($moyennecours1economie+$moyennecours2economie)/2;
            }

            if (empty($moyennecours2philo)) {
                $annuellephilo=($moyennecours1philo);
            }else{
                $annuellephilo=($moyennecours1philo+$moyennecours2philo)/2;
            }

            if (empty($moyennecours2anglais)) {
                $annuelleanglais=($moyennecours1anglais);
            }else{
                $annuelleanglais=($moyennecours1anglais+$moyennecours2anglais)/2;
            }

            if (empty($moyennecours2eps)) {
                $annuelleeps=($moyennecours1eps);
            }else{
                $annuelleeps=($moyennecours1eps+$moyennecours2eps)/2;
            }

            if (empty($moyennecours2ecm)) {
                $annuelleecm=($moyennecours1ecm);
            }else{
                $annuelleecm=($moyennecours1ecm+$moyennecours2ecm)/2;
            }
        }
        

        if ($type=='cours' and $semestre=='1er S') {
            $moynmaths=$note1cmaths;
            $moynphysique=$note1cphysique;
            $moynchimie=$note1cchimie;
            $moynbiologie=$note1cbiologie;
            $moyngeologie=$note1cgeologie;
            $moynfrancais=$note1cfrancais;
            $moynhistoire=$note1chistoire;
            $moyngeographie=$note1cgeographie;
            $moyneconomie=$note1ceconomie;
            $moynphilo=$note1cphilo;
            $moynanglais=$note1canglais;
            $moyneps=$note1ceps;
            $moynecm=$note1cecm;
            $position=1;
        }elseif ($type=='compo' and $semestre=='1er S') {
            $moynmaths=$compo1cmaths;
            $moynchimie=$compo1cchimie;
            $moynbiologie=$compo1cbiologie;
            $moyngeologie=$compo1cgeologie;
            $moynfrancais=$compo1cfrancais;
            $moynhistoire=$compo1chistoire;
            $moyngeographie=$compo1cgeographie;
            $moyneconomie=$compo1ceconomie;
            $moynphilo=$compo1cphilo;
            $moynanglais=$compo1canglais;
            $moyneps=$compo1cecm;
            $moynecm=$compo1cecm;
        }elseif ($type=='cours' and $semestre=='2eme S') {
            $moynmaths=$note2cmaths;
            $moynphysique=$note2cphysique;
            $moynchimie=$note2cchimie;
            $moynbiologie=$note2cbiologie;
            $moyngeologie=$note2cgeologie;
            $moynfrancais=$note2cfrancais;
            $moynhistoire=$note2chistoire;
            $moyngeographie=$note2cgeographie;
            $moyneconomie=$note2ceconomie;
            $moynphilo=$note2cphilo;
            $moynanglais=$note2canglais;
            $moyneps=$note2ceps;
            $moynecm=$note2cecm;
            $position=2;
        }elseif ($type=='compo' and $semestre=='2eme S') {
            $moynmaths=$compo2cmaths;
            $moynphysique=$compo2cphysique;
            $moynchimie=$compo2cchimie;
            $moynbiologie=$compo2cbiologie;
            $moyngeologie=$compo2cgeologie;
            $moynfrancais=$compo2cfrancais;
            $moynhistoire=$compo2chistoire;
            $moyngeographie=$compo2cgeographie;
            $moyneconomie=$compo2ceconomie;
            $moynphilo=$compo2cphilo;
            $moynanglais=$compo2canglais;
            $moyneps=$compo2ceps;
            $moynecm=$compo2cecm;
        }elseif ($colspan==4) {
            $moynmaths=$moyennecoursmaths;
            $moynphysique=$moyennecoursphysique;
            $moynchimie=$moyennecourschimie;
            $moynbiologie=$moyennecoursbiologie;
            $moyngeologie=$moyennecoursgeologie;
            $moynfrancais=$moyennecoursfrancais;
            $moynhistoire=$moyennecourshistoire;
            $moyngeographie=$moyennecoursgeographie;
            $moyneconomie=$moyennecourseconomie;
            $moynphilo=$moyennecoursphilo;
            $moynanglais=$moyennecoursanglais;
            $moyneps=$moyennecourseps;
            $moynecm=$moyennecoursecm;
        }elseif ($colspan==7) {
            $moynmaths=$moyennecoursmaths;
            $moynphysique=$moyennecoursphysique;
            $moynchimie=$moyennecourschimie;
            $moynbiologie=$moyennecoursbiologie;
            $moyngeologie=$moyennecoursgeologie;
            $moynfrancais=$moyennecoursfrancais;
            $moynhistoire=$moyennecourshistoire;
            $moyngeographie=$moyennecoursgeographie;
            $moyneconomie=$moyennecourseconomie;
            $moynphilo=$moyennecoursphilo;
            $moynanglais=$moyennecoursanglais;
            $moyneps=$moyennecourseps;
            $moynecm=$moyennecoursecm;
        }elseif ($colspan==8) {
            $moynmaths=$annuellemaths;
            $moynphysique=$annuellephysique;
            $moynchimie=$annuellechimie;
            $moynbiologie=$annuellebiologie;
            $moyngeologie=$annuellegeologie;
            $moynfrancais=$annuellefrancais;
            $moynhistoire=$annuellehistoire;
            $moyngeographie=$annuellegeographie;            
            $moyneconomie=$annuelleeconomie;
            $moynphilo=$annuellephilo;
            $moynanglais=$annuelleanglais;
            $moyneps=$annuelleeps;
            $moynecm=$annuelleecm;
        }


        //$moyennenotedecours=($note1cmaths+$note1cphysique+$note1cchimie+$note1cbiologie+$note1cgeologie+$note1cfrancais+$note1chistoire+$note1cgeographie+$note1ceconomie+$note1cphilo+$note1canglais+$note1ceps+$note1cecm)/();
        //$moyennenotedecompos=$compo1cmaths;


        echo "\n".'"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.''.'";"'.$semestre.'";"'.$type.'";"'.$moynmaths.'";"'.$moynphysique.'";"'.$moynchimie.'";"'.$moynbiologie.'";"'.$moyngeologie.'";"'.$moynfrancais.'";"'.$moynhistoire.'";"'.$moyngeographie.'";"'.$moyneconomie.'";"'.$moynphilo.'";"'.$moynanglais.'";"'.''.'";"'.''.'";"'.$moyneps.'";"'.$moynecm.'";"'.''.'";"'.''.'";"'.''.'"';
        $colspan+=1;
    }
    
}