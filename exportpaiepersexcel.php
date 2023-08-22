<?php
require '_header.php';

function filterData(&$str){
    $str=preg_replace("/\t/", "\\t", $str);
    $str=preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str= '"' . str_replace('"', '""', $str). '"';
}

$filename="paiement personnels".date("Y-m-d").".xls";

$fields=array("N°", "matricule","Prenom et nom","salaire brut","prime","Avance","cotisation","salaire net");

$execlData=implode("\t", array_values($fields)). "\n";

$_SESSION['moisp']=$_GET['mois'];

$totb=0;
$totp=0;
$totac=0;
$totcot=0;
$totn=0;
$toth=0;
    
$query=$DB->query("SELECT  *from personnel inner join salairepers on salairepers.numpers=personnel.numpers where salairepers.promo='{$_SESSION['promo']}' and personnel.numpers not in(SELECT matricule FROM payepersonnel WHERE promo='{$_SESSION['promo']}' and mois='{$_SESSION['mois']}') and personnel.numpers not in(SELECT matricule FROM liaisonenseigpers WHERE promo='{$_SESSION['promo']}') order by(prenom)");

if ($_GET['mois']<10) {
                
    $cmois='0'.$_SESSION['moisp'];

}else{

    $cmois=$_SESSION['moisp'];
}
foreach($query as $key => $value) {
    $_SESSION['numeen']=$value->numpers;
    $numeen=$_SESSION['numeen'];

    $prodsocial=$DB->querys('SELECT montant from ssocialpers where numpers=:mat', array('mat'=>$numeen));

    $_SESSION['prodsocial']=$prodsocial['montant'];

    $prodsalaire=$DB->querys('SELECT salaire from salairepers where numpers=:mat', array('mat'=>$numeen));

    $_SESSION['salaire']=$prodsalaire['salaire'];
    $_SESSION['salaireact']='ok';


    $prodprime=$DB->querys('SELECT montant as montantp from primesplanifie where matricule=:mat and mois=:mois and anneescolaire=:promo', array('mat'=>$numeen, 'mois'=>$_SESSION['mois'], 'promo'=>$_SESSION['promo']));

    if (empty($prodprime)) {
        $prime=0;
    }else{
        $prime=$prodprime['montantp'];
    }


    $prodac=$DB->querys('SELECT montant from accompte where matricule=:mat and mois=:datet and anneescolaire=:promo', array('mat'=>$numeen, 'datet'=>$_SESSION['mois'], 'promo'=>$_SESSION['promo']));

    if (empty($prodac)) {
        $accompte=0;
    }else{
        $accompte=$prodac['montant'];
    }

    $salaireb=$_SESSION['salaire'];

    $salairep=$_SESSION['salaire']+$prime-$accompte-$_SESSION['prodsocial'];

    $totb+=$salaireb;
    $totp+=$prime;
    $totac+=$accompte;
    $totcot+=$_SESSION['prodsocial'];
    $totn+=$salairep;

    $lineData=array(($key+1), $value->numpers, ucwords(strtolower($value->prenom)).' '.strtoupper($value->nom),$salaireb, $prime, $accompte, $_SESSION['prodsocial'], $salairep);

    array_walk($lineData, 'filterData');
    $execlData .=implode("\t", array_values($lineData)). "\n";
}
header('Content-Encoding: UTF-8');
header("Content-Type: application/vnd.ms-excel. charset=UTF-8");
header("Content-Disposition: attachement; filename=\"$filename\"");
echo chr(255).chr(254).mb_convert_encoding( $execlData, 'UTF-16LE', 'UTF-8');
echo $execlData;
exit;

