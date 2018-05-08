<?php

require_once('../config.inc');

function login($email, $password) 
{
  $config = new KalturaConfiguration();
  $client = new KalturaClient($config);

  $partnerId;
  $expiry = 86400;
  $privileges = "disableentitlement";
  $otp = "";

  try { 
    $result = $client->user->loginByLoginId($email, $password, $partnerId, $expiry, $privileges, $otp);
    if ($result) {
    $ks = $client->session->start(
      ADMIN_SECRET,
      $email,
      KalturaSessionType::ADMIN,
      PARTNER_ID,
      $expiry, 
      $privileges);

      print json_encode(array("ks" => $ks));
    }

  } catch (Exception $e) {
    echo $e->getMessage();
  }
}

$mandatory_params=array('email','password');
foreach ($mandatory_params as $param){
  if (!isset($_POST[$param])){
   die("$param is mandatory");
 }
 $$param = strip_tags($_POST[$param]);
}

login($email, $password);
