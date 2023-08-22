<?php

require 'header.php';

$coef=1;

$prod=$DB->query('SELECT * from devoir');

foreach ($prod as $key => $value) {

	if ($value->coef>1) {
		$DB->insert('UPDATE devoir SET coef=? where id=?', array($coef, $value->id));
	}elseif ($value->coefcom>1) {
		$DB->insert('UPDATE devoir SET coefcom=? where id=?', array($coef, $value->id));
	}else{
		
	}

	
}