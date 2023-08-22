<?php
require 'header.php';

require 'navformation.php';

if (isset($_GET['licence'])) {

    $products = $DB->querys('SELECT * FROM licence ');?>

    <div>

        <form id="formulaire" method="post" action="admin.php" style="width: 50%; height: 200px;">
            <ol>
                <li><label>Selectionnez la licence</label>
                    <select name="licence" required="">
                        <option></option>
                        <option value="<?=$products['num_licence'];?>"><?=$products['num_licence'];?></option>
                    </select>
                </li>

                <li><label>Selectionnez la date de fin</label>
                    <input type="date" name="datel" required="" value="<?=$products['date_fin'];?>">
                </li>
            </ol>

            <fieldset><input type="reset" value="Annuler" name="annuldec" style="cursor: pointer;" /><input type="submit" value="Valider" name="ajoutef" onclick="return alerteV();" style="margin-left: 30px; cursor: pointer;"/></fieldset>

        </form>
    </div><?php
}

if (!isset($_POST['licence'])) {

}else{

    $datel = $_POST['datel'];
    $licence=$_POST['licence']; 
    $DB->insert('UPDATE licence SET date_fin = ? WHERE num_licence = ?', array($datel, $licence));?>

    <div class="alerteV">Votre licence est desormais valable jusqu'au <?=$datel;?></div><?php


}