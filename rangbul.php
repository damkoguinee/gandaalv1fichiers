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


        $DB->insert('INSERT INTO rangel(matricule, moyenne, rang) values( ?, ?, ?)', array($matricule->matricule, $moyeng, 1));

        $produ=$DB->query('SELECT  moyenne, matricule from rangel order by(moyenne)desc');

        foreach ($produ as $key => $value) {

          $DB->insert('UPDATE rangel SET rang = ? where matricule=?', array($key+1, $value->matricule));
        
        }

    }else{

    }

     
}

foreach ($prodmat as $matricule) {

    $prodrg=$DB->query('SELECT  rang, matricule from rangel where matricule=:matr', array('matr'=>$matricule->matricule));

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
$DB->delete('DELETE FROM rangel'); // Pour supprimer imediatement la liste des admis