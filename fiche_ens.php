<div class="row"><?php

    if (isset($_GET['ficheens'])) {

        if (isset($_GET['ficheens']) or isset($_GET['supimg'])  or isset($_POST["ajoutimg"])) {

            if (isset($_GET['ficheens'])) {
                $_SESSION['fiche']=$_GET['ficheens'];
                $promoins=$_SESSION['promo'];
            }

            if (isset($_POST["ajoutimg"])) {
                $_SESSION['fiche']=$_POST["env"];
                $promoins=$_SESSION['promo'];
            }

           
            $mat=$_SESSION['fiche'];
            $codeContent=$mat;
            $fileName=$mat.".png";
            $cheminQrcode='qrcode/'.$fileName;
            if (!file_exists($cheminQrcode)) {
                QRcode::png($codeContent, $cheminQrcode);
            }

            $fiche=$DB->querys('SELECT  *from enseignant left join contact on enseignant.matricule=contact.matricule left join salaireens on salaireens.numpers=enseignant.matricule where enseignant.matricule=:mat and promo=:promo ', array('mat'=>$mat, 'promo'=>$_SESSION['promo']));?></div>

                
            <!-- <div class="fichel" style="font-size: 20px; font-weight: bold; text-align: center; margin-top: 10px;" >Fiche de renseignements de l'enseignant</div> -->
            <?php

            $nomel=ucfirst(strtolower($fiche['prenomen'])).' '.strtoupper($fiche['nomen']);
            if ($_SESSION['type']=="admin" or $_SESSION['type']=="comptable") {
                $numbanque=$fiche['numbanq'];
                $agence=$fiche['agencebanq'];
                $thoraire=$fiche['thoraire'];
                $salaire=$fiche['salaire'];
            }
            

            $filename1="photoens/".$mat.'.jpg';

            if (file_exists($filename1)) {
                $image="photoens/".$mat.".jpg";
            }else{
                $image="photoens/defaut.jpg";
            }?>

            <div class="card m-auto bg-light my-2" style="width: 55%;">
                <div style="width: 9rem; margin:auto;">
                    <img src="<?=$image;?>" class="card-img-top" alt="photo-enseignant">
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center">Enseignant</h5>
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            <div class="row">
                                <div class="col-sm-12 col-md-5 fw-bold">Matricule </div><div class="col-sm-12 col-md-7"><?=strtoupper($mat);?></div>
                            </div>
                            <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Nom/Prénom</div><div class="col-sm-12 col-md-7"><?=strtoupper($fiche['nomen']);?> <?=ucfirst(strtolower(($fiche['prenomen'])));?></div></div>
                            <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Né(e) le</div><div class="col-sm-12 col-md-7"><?=strtoupper($fiche['naissance']);?></div></div>
                            <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Téléphone</div><div class="col-sm-12 col-md-7"><?=strtoupper($fiche['phone']);?></div></div>
                            <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Email</div><div class="col-sm-12 col-md-7"><?=strtoupper($fiche['email']);?></div></div><?php 
                            if ($_SESSION['type']=="admin" or $_SESSION['type']=="comptable") {?>
                                <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Agence</div><div class="col-sm-12 col-md-7"><?=strtoupper($agence);?></div></div>
                                <div class="row"><div class="col-sm-12 col-md-5 fw-bold">N°</div><div class="col-sm-12 col-md-7"><?=strtoupper($numbanque);?></div></div>
                                <div class="row"><div class="col-sm-12 col-md-5 fw-bold">Taux Horaire</div><div class="col-sm-12 col-md-7"><?=strtoupper($thoraire);?></div></div>
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
            </div><?php
        }
    }?>
</div>