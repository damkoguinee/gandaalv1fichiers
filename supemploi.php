<?php
require 'header.php';

$groupe='12eme S.M';
$codens='cspe21';


$DB->delete('DELETE FROM events WHERE nomgrp=? and codensp=?', array($groupe, $codens));