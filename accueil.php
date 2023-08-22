<?php
require 'header.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<4) {?>

        <div class="alertes">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>

        <div class="col">

            <fieldset style="margin-top: 30px;"><legend>Type des Formations</legend>
                <div class="choixacc" style="display: flex;">

                    <div class="optionacc">
                        <a href="formation.php?form">
                        <div class="descript_optiong">Maternelle</div></a>
                    </div>

                    <div class="optionacc">
                        <a href="repartition.php?scol">
                        <div class="descript_optiong">Primaire</div></a>
                    </div>

                    <div class="optionacc">
                        <a href="repartition.php?scol">
                        <div class="descript_optiong">Collège</div></a>
                    </div>

                    <div class="optionacc">
                        <a href="repartition.php?scol">
                        <div class="descript_optiong">Lycée</div></a>
                    </div>

                    <div class="optionacc">
                        <a href="repartition.php?scol">
                        <div class="descript_optiong">Université</div></a>
                    </div>
               
                </div>
            </fieldset>

        </div><?php
	}
}?>

