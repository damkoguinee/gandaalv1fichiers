<?php
//Upload photo

if(isset($_POST["ajoutimg"])){

    $logo=$_FILES['photo']['name'];

    if($logo!=""){

      require "uploadImageens.php";
     
    }
}

if (isset($_GET['supimg'])) {
    
    $filename="photoens/".$_GET['supimg'].'.jpg';

    if (file_exists($filename)) {

        unlink ($filename);?>

        <div>

          <div class="col"><img src="photoens/defaut.jpg" width="120" height="120"></div>

          <div style="margin-left: 60px; margin-top: -5px;"><a href="enseignant.php?ajoutimg=<?=$mat;?>&ficheens=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>"><img src="css/img/modif.jpg" width="20" height="20"></a></div>

        </div><?php

    }else{?>

        <div>

          <div class="col"><img src="photoens/defaut.jpg" width="120" height="120"></div>

          <div style="margin-left: 60px; margin-top: -5px;"><a href="enseignant.php?supimg=<?=$mat;?>&ficheens=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>" onclick="return alerteS();"><img src="css/img/modif.jpg" width="20" height="20"></a></div>

        </div><?php
    }


}else{?>

    <div><?php

        $filename1="photoens/".$mat.'.jpg';

        if (file_exists($filename1)) {?>

            <div class="col"><img src="photoens/<?=$mat;?>.jpg" width="120" height="120"></div><?php

            if ($products['niveau']>3) {?>

                <div style="margin-left: 60px; margin-top: -5px;">
                    <a href="enseignant.php?supimg=<?=$mat;?>&ficheens=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>" onclick="return alerteS();">

                    <img src="css/img/sup.jpg" width="20" height="20"></a>
                </div><?php
                
            }

        }else{?>

            <div class="col"><img src="photoens/defaut.jpg" width="120" height="120"></div><?php

            if (!isset($_GET['bulele'])) {
               
                if ($products['niveau']>3) {?>

                    <div style="margin-left: 60px; margin-top: -5px;">
                        <a href="enseignant.php?ajoutimg=<?=$mat;?>&ficheens=<?=$mat;?>&promo=<?=$_SESSION['promo'];?>">

                        <img src="css/img/modif.jpg" width="20" height="20"></a><?php
                        if (isset($_GET['ajoutimg']) or isset($_POST["ajoutimg"])) {?>

                            <form method="POST" action="enseignant.php?ficheens=<?=$mat;?>" enctype="multipart/form-data">

                                <input type="file" name="photo" id="photo" />
                                <input type="hidden" value="<?=$mat;?>" name="env"/>

                                <input type="submit" value="Valider" name="ajoutimg" style="margin-left: 30px; cursor: pointer;"/>

                            </form><?php

                        }?>
                        
                    </div><?php
                    
                }
            }
        }?>

    </div><?php
}?>