<?php

require_once('config.inc');

$results = array(); 
$results["partnerId"] = PARTNER_ID; 
$results["uiConfId"] = PLAYER_UICONF_ID; 
$results["seconds"] = SECONDS; 
$results["questions"] = QUESTIONS; 

print json_encode($results);
?>
