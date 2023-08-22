<?php
require 'header.php';

$codensp='19PB01';
$codensp2='22PB02';
$codensp3='cspe45';
$codensp4='22PB05';


$DB->delete("DELETE FROM events WHERE codensp='{$codensp}'or codensp='{$codensp2}' or codensp='{$codensp3}' or codensp='{$codensp4}'");