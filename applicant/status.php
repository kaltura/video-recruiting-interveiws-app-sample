<?php

require_once('../config.inc');

function getEntryStatus($ks, $entries, $entriesCount) 
{
  $config = new KalturaConfiguration();
  $config->serviceUrl = SERVICE_URL;
  $client = new KalturaClient($config);
  $client->setKS($ks);

  $filter = new KalturaMediaEntryFilter();
  $filter->idIn = $entries;
  $pager = new KalturaFilterPager();

  try {
    $result = $client->media->listAction($filter, $pager);
    if ($result->totalCount < $entriesCount){
	return 0;
    }
  else {
    foreach ($result->objects as $entry) {
    if ($entry->status != KalturaEntryStatus::READY) {
      return 0; 
    }
  }
}

  } catch (Exception $e) {
    echo $e->getMessage();
  }
  return 1; 
}

print '{ "status": "'.getEntryStatus($_GET["ks"], $_GET["entries"], $_GET["entriesCount"]).'"}';

?>
