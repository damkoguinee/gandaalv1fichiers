<?php
$moyengenerale=0;

foreach ($prodmat as $matricule) {
    $totm1=0;
    $coefm1=0;

    
    foreach ($prodmatiere as $matiere) {
        
        require 'requetebul.php';
                
        foreach ($prodm1 as $moyenne) {
            $totm1+=($moyenne->mgen*$moyenne->coef);

            $coefm1+=$moyenne->coef;
            
        }
    }

    if (!empty($coefm1)) {

        $moyenmat=($totm1/$coefm1);
        $moyengenerale+=$moyenmat;

        $moyeng=$totm1/$coefm1;        

        

        // $DB->insert('INSERT INTO rangel(matricule, moyenne, rang, pseudo) values( ?, ?, ?, ?)', array($matricule->matricule, $moyeng, 1, $_SESSION['pseudo']));
        

        $produ=$DB->query("SELECT  moyenne, matricule from rangel where pseudo='{$_SESSION['pseudo']}' order by(moyenne)desc");

        foreach ($produ as $key => $value) {

          $DB->insert('UPDATE rangel SET rang = ? where matricule=? and pseudo=?', array($key+1, $value->matricule, $_SESSION['pseudo']));
        
        }

    }else{

    }

     
}

foreach ($prodmat as $matricule) {

    $prodrg=$DB->query("SELECT  rang, matricule from rangel where matricule='{$matricule->matricule}' and pseudo='{$_SESSION['pseudo']}'");

    if (!empty($prodrg)) {

        foreach ($prodrg as $key => $rang) {?>        

            <tr>
                <td height="45" style="text-align: center;"><?=$rang->rang;?></td>
            </tr><?php
         }
    }else{?>        

        <tr>
            <td height="45" style="text-align: center;">neval</td>
        </tr><?php
    }
}
$DB->delete("DELETE FROM rangel where pseudo='{$_SESSION['pseudo']}'"); // Pour supprimer imediatement la liste des admis