<?php
require '_header.php';

function filterData(&$str){
    $str=preg_replace("/\t/", "\\t", $str);
    $str=preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str= '"' . str_replace('"', '""', $str). '"';
}

$filename="paiement enseignant".date("Y-m-d").".xls";

$fields=array("N°", "matricule","Prenom et nom","heures","salaire brut","prime","Avance","cotisation","salaire net");

$execlData=implode("\t", array_values($fields)). "\n";

$_SESSION['moisp']=$_GET['mois'];
    
$query=$DB->query('SELECT enseignant.matricule as matricule, prenomen, nomen from enseignant inner join salaireens on salaireens.numpers=enseignant.matricule where salaireens.promo=:promop and enseignant.matricule not in(SELECT matricule FROM payenseignant WHERE anneescolaire=:annee and mois=:mois) and promo=:promo order by(prenomen)', array('promop'=>$_SESSION['promo'], 'annee'=>$_SESSION['promo'], 'mois'=>$_SESSION['moisp'], 'promo'=>$_SESSION['promo']));

if ($_GET['mois']<10) {
                
    $cmois='0'.$_SESSION['moisp'];

}else{

    $cmois=$_SESSION['moisp'];
}
foreach($query as $key => $value) {
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

    $prodprime=$DB->querys('SELECT montant as montantp from primesplanifie where matricule=:mat and mois=:mois and anneescolaire=:promo', array('mat'=>$numeen, 'mois'=>$_SESSION['moisp'], 'promo'=>$_SESSION['promo']));
    
    if (empty($prodprime)) {
        $prime=0;
    }else{
        $prime=$prodprime['montantp'];
    }

    $prodautres=$DB->querys('SELECT id from liaisonenseigpers where matricule=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));

    //var_dump($numeen, $_SESSION['promo']);

    if (empty($prodautres['id'])) {
        $salairesautres=0;
    }else{
        $prodautres=$DB->querys('SELECT salaire as montantp from salairepers where numpers=:mat and promo=:promo', array('mat'=>$numeen, 'promo'=>$_SESSION['promo']));

        $salairesautres=$prodautres['montantp'];

    }
    
    $prodh=$DB->querys('SELECT sum(heuret) as heuret from horairet where numens=:mat and date_format(datet,\'%m\')=:datet and annees=:promo', array('mat'=>$numeen, 'datet'=>$cmois, 'promo'=>$_SESSION['promo'])); 
    
    $prodac=$DB->querys('SELECT montant from accompte where matricule=:mat and mois=:datet and anneescolaire=:promo', array('mat'=>$numeen, 'datet'=>$_SESSION['moisp'], 'promo'=>$_SESSION['promo']));

    if (empty($prodac)) {
        $accompte=0;
    }else{
        $accompte=$prodac['montant'];
    }

    if ($_SESSION['salaireact']=='not') {

        $salaireb=$_SESSION['salaire']*$prodh['heuret']+$salairesautres;
        
        $salairep=$_SESSION['salaire']*$prodh['heuret']+$salairesautres+$prime-$accompte-$_SESSION['prodsocial'];

    }else{
        $salaireb=$_SESSION['salaire']+($prodh['heuret']*$rapport->thoraire)+$salairesautres;

        $salairep=$salaireb+$prime-$accompte-$_SESSION['prodsocial'];
    }

    $lineData=array(($key+1), $value->matricule, ucwords(strtolower($value->prenomen)).' '.strtoupper($value->nomen),$prodh['heuret'],$salaireb, $prime, $accompte, $_SESSION['prodsocial'], $salairep);

    array_walk($lineData, 'filterData');
    $execlData .=implode("\t", array_values($lineData)). "\n";
}
header('Content-Encoding: UTF-8');
header("Content-Type: application/vnd.ms-excel. charset=UTF-8");
header("Content-Disposition: attachement; filename=\"$filename\"");
echo chr(255).chr(254).mb_convert_encoding( $execlData, 'UTF-16LE', 'UTF-8');
echo $execlData;
exit;

