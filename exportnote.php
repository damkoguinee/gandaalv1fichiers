<?php
require '_header.php';

function filterData(&$str){
    $str=preg_replace("/\t/", "\\t", $str);
    $str=preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str= '"' . str_replace('"', '""', $str). '"';
}

// $replace = array(
//     "Å " => "?", "Å¡" => "?", "Å'" => "?", "Å" => "?",
//     "Å¸" => "?", "Ã¿" => "ÿ", "Ã?" => "À", "Ã " => "à", 
//     "Ã" => "Á", "Ã¡" => "á", "Ã?" => "Â", "Ã¢" => "â",
//     "Ã?" => "Ã", "Ã£" => "ã", "Ã?" => "Ä", "Ã¤" => "ä", 
//     "Ã?" => "Å", "Ã¥" => "å", "Ã?" => "Æ", "Ã¦" => "æ",
//     "Ã?" => "Ç", "Ã§" => "ç", "Ã?" => "È", "Ã¨" => "è", 
//     "Ã?" => "É", "Ã©" => "é", "Ã?" => "Ê", "Ãª" => "ê",
//     "Ã?" => "Ë", "Ã«" => "ë", "Ã?" => "Ì", "Ã¬" => "ì", 
//     "Ã" => "Í", "Ã­" => "í", "Ã?" => "Î", "Ã®" => "î",
//     "Ã" => "Ï", "Ã¯" => "ï", "Ã" => "Ð", "Ã°" => "ð", 
//     "Ã'" => "Ñ", "Ã±" => "ñ", "Ã'" => "Ò", "Ã²" => "ò",
//     "Ã" => "Ó", "Ã³" => "ó", "Ã" => "Ô", "Ã´" => "ô", 
//     "Ã?" => "Õ", "Ãµ" => "õ", "Ã?" => "Ö", "Ã?" => "Ø",
//     "Ã¸" => "ø", "Ã?" => "Ù", "Ã¹" => "ù", "Ã?" => "Ú", 
//     "Ãº" => "ú", "Ã?" => "Û", "Ã»" => "û", "Ã?" => "Ü",
//     "Ã¼" => "ü", "Ã" => "Ý", "Ã½" => "ý", "Ã?" => "Þ", 
//     "Ã¾" => "þ", "Ã?" => "ß", "Ã¶" => "ö"
// );
// function change($text) {
//     global $replace;
//     foreach($replace as $key => $val)
//         $text = str_replace($key, $val, $text);
//     return $text;
// }



$filename="notes".date("Y-m-d").".xls";

$fields=array("N°","matricule","nom","prenom","formation", "date devoir","type","note de cours","coef","note de compo","coef compo","trimestre","matiere","classe","Annee-Scolaire");

$execlData=implode("\t", array_values($fields)). "\n";

$query=$DB->query("SELECT * from devoir inner join note on devoir.id=codev where promo='{$_SESSION['promo']}' ");
$anneescolaire=($_SESSION['promo']-1).'-'.$_SESSION['promo'];

foreach($query as $key => $row) {
    $datedev=(new dateTime($row->datedev))->format("d/m/Y");
    $formation=$panier->infoEleve($row->matricule)[2];
    $lineData=array(($key+1), $row->matricule, $panier->infoEleve($row->matricule)[1], $panier->infoEleve($row->matricule)[0], $formation, $datedev, $row->type, $row->note, $row->coef, $row->compo, $row->coefcom, $row->trimes, $panier->nomMatiere($row->codem), $row->nomgroupe, $anneescolaire);

    array_walk($lineData, 'filterData');
    $execlData .=implode("\t", array_values($lineData)). "\n";
}
header('Content-Encoding: UTF-8');
header("Content-Type: application/vnd.ms-excel. charset=UTF-8");
header("Content-Disposition: attachement; filename=\"$filename\"");
echo chr(255).chr(254).mb_convert_encoding( $execlData, 'UTF-16LE', 'UTF-8');
echo $execlData;
exit;

