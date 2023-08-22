<?php
require 'header.php';
$matricule="PB029/06";
$matriculec="06PB01";

// $DB->insert("UPDATE events SET codensp='{$matriculec}' where codensp='{$matricule}' ");
// $DB->insert("UPDATE enseignement SET codens='{$matriculec}' where codens='{$matricule}' ");

// $matricule="PB34/09";
// $matriculec="09PB03";

// $DB->insert("UPDATE events SET codensp='{$matriculec}' where codensp='{$matricule}' ");
// $DB->insert("UPDATE enseignement SET codens='{$matriculec}' where codens='{$matricule}' ");

// $matricule="cspe48";
// $matriculec="12PB03";

// $DB->insert("UPDATE events SET codensp='{$matriculec}' where codensp='{$matricule}' ");
// $DB->insert("UPDATE enseignement SET codens='{$matriculec}' where codens='{$matricule}' ");

// $matricule="12PB03";
// $matriculec="cspe50";
// $codef="12ss";

// $DB->insert("UPDATE events SET codensp='{$matriculec}' where codensp='{$matricule}' and codefp='{$codef}' ");
// $DB->insert("UPDATE enseignement SET codens='{$matriculec}' where codens='{$matricule}' and codef='{$codef}' ");

$matricule="cspe43";
$matriculec="03PB02";

$DB->insert("UPDATE events SET codensp='{$matriculec}' where codensp='{$matricule}' ");
$DB->insert("UPDATE enseignement SET codens='{$matriculec}' where codens='{$matricule}' ");

$matricule="cspe42";
$matriculec="04PB06";

$DB->insert("UPDATE events SET codensp='{$matriculec}' where codensp='{$matricule}' ");
$DB->insert("UPDATE enseignement SET codens='{$matriculec}' where codens='{$matricule}' ");
	
