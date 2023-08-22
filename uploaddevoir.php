<?php

	$nom=$_SESSION['numeendevoir'];
	$classe=$classe;

	if (!is_dir("devoirsmaison/".$nom)) {

		mkdir("devoirsmaison/".$nom);

	}

	if (!is_dir("devoirsmaison/".$nom."/".$nomat)) {

		mkdir("devoirsmaison/".$nom."/".$nomat);

	}

	if (!is_dir("devoirsmaison/".$nom."/".$nomat."/".$numdev)) {

		mkdir("devoirsmaison/".$nom."/".$nomat."/".$numdev);

	}

	if (!is_dir("devoirsmaison/".$nom."/".$nomat."/".$numdev."/".$classe)) {

		mkdir("devoirsmaison/".$nom."/".$nomat."/".$numdev."/".$classe);

	}

	if (isset($_FILES['just'] ) ) {

		$value=$_FILES['just'];
		$count=count($value['name']);

		for ($i=0; $i<$count; $i++) {
			

			if ($value['type'][$i] == "application/pdf") {
				$source_file = $value['tmp_name'][$i];
				
				$dest_file = "devoirsmaison/".$nom."/".$nomat."/".$numdev."/".$classe."/".$value['name'][$i];

				if (file_exists($dest_file)) {
					print "Le dossier selctionné existe";

				}else {
					move_uploaded_file( $source_file, $dest_file )
					or die ("Error!!");
				}

			}else {

				if ( $value['type'][$i] != "application/pdf") {
					print "Error occured while uploading file : ".$value['name'][$i]."<br/>";
					print "Invalid  file extension, should be pdf !!"."<br/>";
					print "Error Code : ".$value['error'][$i]."<br/>";
				}
			}
		}
	}


	?>
</body>
</html>