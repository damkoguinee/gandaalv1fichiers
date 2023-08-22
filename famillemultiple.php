<?php 

if ($_POST['famille']=='simple') {
    $_SESSION['famille']=array();
}

//var_dump($_SESSION['famille']);

if (empty($_SESSION['famille'])) {
   
    $maxid = $DB->querys('SELECT max(numpaye) as id FROM histopayefrais');
        
    $numpaye=$maxid['id']+1;

    $_SESSION['numpaye']=$numpaye;
}

//var_dump($_SESSION['numpaye']);

if ($_POST['famille']=='multiple') {
    
    $_SESSION['famille']=$_SESSION['numpaye'];
    $famille=$_SESSION['famille'];
}else{
    $famille=$_SESSION['numpaye'];
}
