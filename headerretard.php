<div><?php

		if (isset($_GET['nomel']) or isset($_POST['justabs']) or isset($_GET['retmatri'])) {
	        $_SESSION['annee']=' ';
	        $dateselect=' ';
	    }

	    if (isset($_GET['listeret']) or isset($_GET['supidabs'])) {
	        $_SESSION['annee']=' ';
	        $dateselect=' ce jour ';
	    }

	    if (isset($_POST['annee'])) {
	        $_SESSION['annee']=$_POST['annee'];
	        $_SESSION['mensuelle']="Selectionnez le mois !!";
	        $_SESSION['datesm']=$_POST['annee'];

	        $dateselect=$_POST['annee'];
	    }

	    if (isset($_POST['mensuelle'])) {
	        $_SESSION['mensuelle']=$_POST['mensuelle'];
	        $dateselect=$_POST['mensuelle'];
	    }

	    if (isset($_POST['jour'])) {
	        $_SESSION['jour']=$_POST['jour'];

	        $datefin = new DateTime( $_POST['jour'] );
	        $dateselect = $datefin->format('d/m/Y');
	    }

	    if (isset($_POST['collabo'])) {
	        $_SESSION['nomcollabo']=$_POST['collabo'];
	    }

	    if (isset($_POST['location'])) {
	        $_SESSION['nomloca']=$_POST['location'];
	    }?>

	    <div class="col" style="display: flex; margin-top: -20px;">

	        <form id='formulaire' method="POST" action="listeretard.php" name="termc" style="height: 30px;"> 

	            <ol style="margin-left: -50px; margin-top: -10px;">

	                <li>
	                    <?='<select style=" font-size: 14px;"  type="number" name="annee" required="" onchange="this.form.submit();">',"n";

	                          if (isset($_POST['annee']) or isset($_POST['mensuelle']) or isset($_POST['jour'])) {?>

	                            <option value=""><?="Année ".$_SESSION['annee'];?></option><?php

	                          }else{

	                            echo "\t",'<option value="">Choisir une année...</option>',"\n";

	                          }

	                        $annee=date("Y");

	                        for($i=2019;$i<=$annee ;$i++){

	                          echo "\t",'<option value="', $i,'">', $i,'</option>',"\n";

	                        }?>
	                    </select>
	                </li>
	            </ol>
	        </form>

	        <form id='formulaire' method="POST" action="listeretard.php" name="termc" style="margin-left: -20px; height: 30px;"> 

	            <ol style="margin-left: -50px; margin-top: -10px;">

	                <li>

	                    <select id="reccode" style=" font-size: 14px;" type = "number" name ="mensuelle" onchange="this.form.submit()"><?php

	                        if (isset($_POST['mensuelle']) or isset($_POST['jour'])) {?>

	                          <option value=""><?=$_SESSION['mensuelle'];?></option><?php

	                        }else{?>

	                          <option value="">Selectionnez le mois !!</option><?php

	                        }
	                        
	                        $mois=0;
	                        if ($_SESSION['datesm']==date('Y')) {
	                          
	                          while ( $mois<= date("m")-1) {
	                            $mois+=1;
	                            if ($mois<10) {?>
	                              <option value="<?='0'.$mois."/".$_SESSION['datesm']; ?>"><?='0'.$mois."/".$_SESSION['datesm']; ?></option><?php
	                            }else{?>
	                              <option value="<?=$mois."/".$_SESSION['datesm']; ?>"><?=$mois."/".$_SESSION['datesm']; ?></option><?php
	                            }
	                          }
	                        }else{
	                            while ( $mois<=11) {
	                                $mois+=1;
	                                if ($mois<10) {?>
	                                  <option value="<?='0'.$mois."/".$_SESSION['datesm']; ?>"><?='0'.$mois."/".$_SESSION['datesm']; ?></option><?php
	                                }else{?>
	                                  <option value="<?=$mois."/".$_SESSION['datesm']; ?>"><?=$mois."/".$_SESSION['datesm']; ?></option><?php
	                                }
	                            }

	                        }?>
	                    </select>
	                </li>
	            </ol>
	        </form>

	        <form id='form' method="POST" action="listeretard.php" name="termc" style="margin-left: -20px; height: 30px;"> 

	            <ol style="margin-left: -50px; margin-top: -10px;">

	                <li><?php

	                    if (isset($_POST['jour'])) {?>

	                        <input style=" font-size: 14px;" type = "date"  name="jour" value="<?= $_SESSION['jour']; ?>" onchange="document.getElementById('form').submit()"/><?php

	                    }else{?>

	                        <input style=" font-size: 14px;" type = "date"  name="jour" onchange="document.getElementById('form').submit()"/><?php
	                        
	                    }?>

	                        
	                </li>

	            </ol>

	        </form>
	    </div>