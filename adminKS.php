<?php

require_once('config.inc');

function getAdminKS() 
{
  $config = new KalturaConfiguration(PARTNER_ID);
  $client = new KalturaClient($config);

  $ks = $client->session->start(
    ADMIN_SECRET,
    USER_ID,
    KalturaSessionType::ADMIN,
    PARTNER_ID,
    86400, 
    "disableentitlement");

  return $ks;
}

getAdminKS();