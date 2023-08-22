<?php require 'header.php';

    $nbreparpage = 10;

    $prodnbre = $DB->querys('SELECT count(*) as total FROM eleve');

    $nbreeleve= $prodnbre['total'];
    $nbredepage = ceil($nbreeleve/$nbreparpage);

    if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $nbredepage) {

       $_GET['page'] = intval($_GET['page']);

       $pageCourante = $_GET['page'];

    } else {

       $pageCourante = 1;
    }

    $depart = ($pageCourante-1)*$nbreparpage;?> 

    <html>
       <head>
          <title>TUTO PHP</title>
          <meta charset="utf-8">
       </head>
       <body>
          <?php
          $prodeleve = $DB->query('SELECT * FROM eleve ORDER BY id DESC LIMIT '.$depart.','.$nbreparpage);

          foreach ($prodeleve as $eleve) {?>

            <b>N°<?php echo $eleve->id; ?> - <?php echo $eleve->nomel; ?></b><br />
            <i><?php echo $eleve->prenomel; ?></i>
            <br /><br />
            <?php
          }

          for($i=1;$i<=($pageCourante+2);$i++) {
          
             if($i == $pageCourante) {
                echo $i.' ';
             } else {
                echo '<a href="index_pagination.php?page='.$i.'">'.$i.'</a> ';
             }
          }
          ?>
       </body>
    </html>