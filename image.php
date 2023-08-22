<?php
//Upload photo

if(isset($_POST["ajoutimg"])){

    $logo=$_FILES['photo']['name'];

    if($logo!=""){

      require "uploadImage.php";
     
    }
}?>

<div class="col-sm-12 col-md-2">
    <div class="row"><?php
        if (isset($_GET['supimg'])) {
            
            $filename="img/".$_GET['supimg'].'.jpg';

            if (file_exists($filename)) {

                unlink ($filename);?>

                <div class="col-sm-12">
                    <div class="card m-auto bg-primary bg-opacity-25" >
                    <img class="card-img-top w-50 h-50 m-auto" src="img/defaut.jpg" alt="Card image cap">
                    <a href="fiche_elevegen.php?ajoutimg=<?=$mat;?>&fiche_eleve=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>"><img src="css/img/modif.jpg" width="20" height="20"></a>
                    </div>
                </div><?php

            }else{?>

                <div class="col-sm-12 ">
                    <div class="card m-auto bg-primary bg-opacity-25" >
                    <img class="card-img-top w-50 h-50 m-auto" src="img/defaut.jpg" alt="Card image cap">
                    <a href="fiche_elevegen.php?supimg=<?=$mat;?>&fiche_eleve=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>" onclick="return alerteS();"><img src="css/img/modif.jpg" width="20" height="20"></a>
                    </div>
                </div><?php
            }


        }else{

            $filename1="img/".$mat.'.jpg';

            if (file_exists($filename1)) {?>

                <div class="col-sm-12">
                    <div class="card  m-auto bg-primary bg-opacity-25" >
                    <img class="card-img-top m-auto" src="img/<?=$mat;?>.jpg" alt="Card image cap"><?php

                        if ($products['niveau']>3) {?>
                            <a class="btn m-auto" href="fiche_elevegen.php?supimg=<?=$mat;?>&fiche_eleve=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>"><img src="css/img/sup.jpg" width="20" height="20"></a><?php 
                        }?>
                    </div>
                </div><?php

            }else{?>

                <div class="col-sm-12 ">
                    <div class="card m-auto bg-primary bg-opacity-25" >
                    <img class="card-img-top w-50 h-50 m-auto" src="img/defaut.jpg" alt="Card image cap"><?php

                        if (!isset($_GET['bulele'])) {
                        
                            if ($products['niveau']>3) {?>

                                <div style="margin-left: 60px; margin-top: -5px;">
                                    <a href="fiche_elevegen.php?ajoutimg=<?=$mat;?>&fiche_eleve=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>">

                                    <img src="css/img/modif.jpg" width="20" height="20"></a><?php
                                    if (isset($_GET['ajoutimg']) or isset($_POST["ajoutimg"])) {?>

                                        <form method="POST" action="fiche_elevegen.php" enctype="multipart/form-data">

                                            <input class="form-control" type="file" name="photo" id="photo" />
                                            <input class="form-control" type="hidden" value="<?=$mat;?>" name="env"/>

                                            <input class="form-control" type="submit" value="Valider" name="ajoutimg"/>

                                        </form><?php

                                    }?>
                                    
                                </div><?php
                                
                            }
                        }?>
                    </div>
                </div><?php
            }
        }?>
    </div>
    <div class="row">
        <img src="<?=$cheminQrcode;?>" class="card-img-top" alt="photo-enseignant">
    </div>
</div>

