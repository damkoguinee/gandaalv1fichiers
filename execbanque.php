<?php require 'header.php';

foreach ($panier->modep as  $value) {

	if ($value=="espèces") {
		$caisse=1;
	}else{
		$caisse=2;
	}

	$DB->insert("UPDATE payement SET caisse='{$caisse}' where typepaye='{$value}'")
	;
	$DB->insert("UPDATE histopayefrais SET caisse='{$caisse}' where typepaye='{$value}'")
	;

	$DB->insert("UPDATE activitespaiehistorique SET caisse='{$caisse}' where modep='{$value}'")
	;

	$DB->insert("UPDATE decaissement SET caisse='{$caisse}' where typepaye='{$value}'")
	;

	$DB->insert("UPDATE accompte SET caisse='{$caisse}' where typepaye='{$value}'")
	;

	$DB->insert("UPDATE payenseignant SET caisse='{$caisse}' where typepaye='{$value}'")
	;

	$DB->insert("UPDATE payepersonnel SET caisse='{$caisse}' where typepaye='{$value}'")
	;
}