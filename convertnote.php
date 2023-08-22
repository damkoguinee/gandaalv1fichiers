<?php require 'header.php';


	$products=$DB->query('SELECT id FROM devoir WHERE date_format(datedev,\'%m/%Y \')= ?', array('03/2021'));

	  foreach ($products as $key => $value) {
	    
	    $DB->insert('UPDATE devoir SET datedev= ?, type=?, coef=?, coefcom=?, nomdev=? WHERE id = ?', array('2021-03-21', 'composition', 0, 1, 'evalmars', $value->id));

	     $proddev2=$DB->query('SELECT note, matricule, codev FROM note WHERE codev= ?', array($value->id));

	    foreach ($proddev2 as $note1) {
	      
	      $DB->insert('UPDATE note SET compo= ?, note=? WHERE codev = ? and matricule=?', array($note1->note, 0, $note1->codev, $note1->matricule));
	    }
	 }