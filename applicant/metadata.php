<?php

require_once('../config.inc');

/*
 * Add user details to the custom metadata profile for the entry 
 * Check first if profile with given name exists 
 * Calls createNewProfile() if it doesn't 
 * Then, add entry to playlist 
 */ 

function uploadMetadata($ks, $entryId, $fullName, $email, $linkedin) 
{
  $config = new KalturaConfiguration();
  $config->serviceUrl = SERVICE_URL;
  $client = new KalturaClient($config);
  $client->setKS($ks);

  $pager=null;
  $filter = new KalturaMetadataProfileFilter();
  $filter->systemNameEqual = METADATA_SYSTEM_NAME;
  $results = $client->metadataProfile->listAction($filter, $pager);
  if ($results->totalCount){
    $profileId = $results->objects[0]->id; 
  }   
  else $profileId = createNewProfile($client);

  $objectType = KalturaMetadataObjectType::ENTRY; 

  $metadata = sprintf(METADATA_TEMPLATE,$fullName,$email, $linkedin);

  // echo "logging ". $metadata; 

  // $metadata = rawurldecode($data); 

  try {
    $result = $client->metadata->add($profileId, $objectType, $entryId, $metadata);
  } catch (Exception $e) {
    echo $e->getMessage(); 
  }
  print json_encode(array("profileId"=>$profileId)); 
}

/*
 * Create new metadata profile with given name 
 * Import schema from ApplicationDetails.xml 
 */

function createNewProfile($client){
  $metadataProfile = new KalturaMetadataProfile(); 
  $metadataProfile->name = METADATA_SYSTEM_NAME;

  $metadataProfile->createMode = KalturaMetadataProfileCreateMode::KMC;
  $metadataProfile->systemName = METADATA_SYSTEM_NAME;
  $viewsData = null; 
  $metadataProfile->objectType = 1;
  $metadataProfile->metadataObjectType = 1;

  $xsd = file_get_contents(__DIR__ . '/ApplicantDetails.xml');

  try {
    $result = $client->metadataProfile->add($metadataProfile, $xsd, $viewsData);
  } catch (Exception $e) {
    echo "create: ". $e->getMessage(); 
  }
  return $result->id;
}

$mandatory_params=array('ks','entryId', 'fullName', 'email', 'linkedin');
foreach ($mandatory_params as $param){
  if (!isset($_POST[$param])){
   die("$param is mandatory");
 }
 $$param = strip_tags($_POST[$param]);
}

uploadMetadata($ks, $entryId, $fullName, $email, $linkedin);
?>
