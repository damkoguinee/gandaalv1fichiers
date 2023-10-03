<?php
require 'headerv2.php';

if (isset($_SESSION['pseudo'])) {
    
    if ($products['niveau']<1) {?>

        <div class="alert alert-warning">Des autorisations sont requises pour consulter cette page</div><?php

    }else{?>
		<div class="container-fluid">
			<div class="row"><?php 
			
				require 'navnote.php';?>

				<div class="col-sm-12 col-md-10">

                    <div class="row"><legend>Selectionnez la classe</legend><?php

                        foreach ($panier->listeClasse() as $classe) {?>

                            <a class="col-sm-12 col-md-3 my-1 mx-1 btn btn-success" href="presence_classe_ajout.php?id_classe=<?=$classe->id;?>&classe=<?=$classe->nomgr;?>&appelj"><?=ucwords($classe->nomgr);?></a>

                            <?php
                        }?>
                    </div>
                </div>
            </div>
        </div><?php
					
	}
}?>


<script type="text/javascript">
    function alerteS(){
        return(confirm('Valider la suppression'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation'));
    }

    function focus(){
        document.getElementById('pointeur').focus();
    }

</script>
