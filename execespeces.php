<?php
require 'header.php';

$new='espèces';
$actu='especes';

$DB->insert("UPDATE histopayefrais SET typepaye='{$new}' where typepaye='{$actu}'");
$DB->insert("UPDATE payementfraiscol SET typepaye='{$new}' where typepaye='{$actu}'");
