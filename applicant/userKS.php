<?php

require_once('../config.inc');

function getUploadKS($email, $name, $linkedin) 
{
  $config = new KalturaConfiguration(PARTNER_ID);
  $client = new KalturaClient($config);

  $ks = $client->session->start(
    ADMIN_SECRET,
    $email,
    KalturaSessionType::ADMIN,
    PARTNER_ID);
  $client->setKS($ks);

  $newUser = new KalturaUser(); 
  $newUser->description = $linkedin; 
  $newUser->fullName = $name; 
  $client->user->update($email, $newUser);

  print json_encode(array("ks" => $ks));
}

$mandatory_params=array('email','name','linkedin');
foreach ($mandatory_params as $param){
  if (!isset($_GET[$param])){
    die("$param is mandatory");
  }
  $$param = strip_tags($_GET[$param]);
}

getUploadKS($email, $name, $linkedin);