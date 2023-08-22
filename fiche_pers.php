<div class="row"><?php   

if (isset($_GET['fichepers'])) {
    $_SESSION['fichepers']=$_GET['fichepers'];
    $promoins=$_SESSION['promo'];
}

if (isset($_POST["ajoutimg"])) {
    $_SESSION['fichepers']=$_POST["env"];
    $promoins=$_SESSION['promo'];
}
$mat=$_SESSION['fichepers'];
$codeContent=$mat;
$fileName=$mat.".png";
$cheminQrcode='qrcode/'.$fileName;
if (!file_exists($cheminQrcode)) {
    QRcode::png($codeContent, $cheminQrcode);
}

$fiche=$DB->querys('SELECT  *from personnel left join contact on personnel.numpers=contact.matricule left join salairepers on salairepers.numpers=personnel.numpers where personnel.numpers=:mat and promo=:promo ', array('mat'=>$mat, 'promo'=>$_SESSION['promo']));?></div><?php

$nomel=ucfirst(strtolower($fiche['prenom'])).' '.strtoupper($fiche['nom']);

if ($_SESSION['type']=="admin" or $_SESSION['type']=="comptable") {
   
    $numbanque=$fiche['numbanq'];
    $agence=$fiche['agencebanq'];
    $salaire=$fiche['salaire'];
}

$filename1="photopers/".$mat.'.jpg';

if (file_exists($filename1)) {
    $image="photopers/".$mat.".jpg";
}else{
    $image="photopers/defaut.jpg";
}?>

<div class="card m-auto my-2" style="width: 55%;">
    <div style="width: 9rem; margin:auto;">
        <img src="<?=$image;?>" class="card-img-top" alt="photo-Personnel">
    </div>
    <div class="card-body">
        <h5 class="card-title text-center">Personnel</h5>
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <div class="row">
                    <div class="col-sm-12 col-md-5 fw-bold">Matricule </div><div class="col-sm-12 col-md-7"><?=strtoupper($mat);?></div>
                </div>
                <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Nom/Prénom</div><div class="col-sm-12 col-md-7"><?=strtoupper($fiche['nom']);?> <?=ucfirst(strtolower(($fiche['prenom'])));?></div></div>
                <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Né(e) le</div><div class="col-sm-12 col-md-7"><?=strtoupper($fiche['datenaiss']);?></div></div>
                <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Téléphone</div><div class="col-sm-12 col-md-7"><?=strtoupper($fiche['phone']);?></div></div>
                <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Email</div><div class="col-sm-12 col-md-7"><?=strtoupper($fiche['email']);?></div></div><?php 
                if ($_SESSION['type']=="admin" or $_SESSION['type']=="comptable") {?>
                    <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Agence</div><div class="col-sm-12 col-md-7"><?=strtoupper($agence);?></div></div>
                    <div class="row"><div class="col-sm-12 col-md-5 fw-bold">N°</div><div class="col-sm-12 col-md-7"><?=strtoupper($numbanque);?></div></div>
                    <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Salaire de Base</div><div class="col-sm-12 col-md-7"><?=strtoupper($salaire);?></div></div><?php
                }?>
            </div>
            <div class="col-sm-12 col-md-4">
                <img src="<?=$cheminQrcode;?>" class="card-img-top" alt="photo-enseignant">
            </div>
        </div>

    </div><?php

    if (isset($_GET['ficheens'])) {?>

        <div class="container-fluid">

            <a class="btn btn-primary my-1" href="enseignement.php?voir_mate=<?=$mat;?>">Matières</a>

            <a class="btn btn-primary my-1" href="#?matiereel=<?=$mat;?>">Emploi du temps</a>

            <a class="btn btn-primary my-1" href="#?matiereel=<?=$mat;?>">Mes Paiements</a>

            <a class="btn btn-primary my-1" href="document.php?docens=<?=$mat;?>&ficheens=<?=$mat;?>">Mes Documents</a>

        </div><?php
    }?>
</div>