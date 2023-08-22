<?php
require 'header.php';

$classe='11SE';
$newclasse='11S';

$DB->insert('UPDATE groupe SET nomgr= ? WHERE nomgr = ?', array($newclasse, $classe));

$DB->insert('UPDATE inscription SET nomgr= ? WHERE nomgr = ?', array($newclasse, $$classe));

$DB->insert('UPDATE enseignement SET nomgr= ? WHERE nomgr = ?', array($newclasse, $$classe));

$DB->insert('UPDATE events SET nomgr= ? WHERE nomgr = ?', array($newclasse, $$classe));

$DB->insert('UPDATE absence SET nomgr= ? WHERE nomgr = ?', array($newclasse, $$classe));

$DB->insert('UPDATE exclus SET nomgr= ? WHERE nomgr = ?', array($newclasse, $$classe));

$DB->insert('UPDATE retard SET nomgr= ? WHERE nomgr = ?', array($newclasse, $$classe));

$DB->insert('UPDATE inscription SET nomgr= ? WHERE nomgr = ?', array($newclasse, $$classe));
    

    
