<?php
require 'header.php';


$prod=$DB->query('SELECT * from login');

foreach ($prod as $value) {
    if ($value->type=="admin") {
        $role="ROLE_ADMIN";
    }elseif ($value->type=="enseignant") {
        $role="ROLE_ENSEIGNANT";
    }elseif ($value->type=="comptable") {
        $role="ROLE_COMPTABLE";
    }elseif ($value->type=="tuteur") {
        $role="ROLE_PARENT";
    }elseif ($value->type=="eleve") {
        $role="ROLE_ELEVE";
    }else{
        $role="ROLE_PERSONNEL";
    }

	$DB->insert("UPDATE login SET role = '{$role}' where matricule='{$value->matricule}'")
	;
}