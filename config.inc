<?php
require_once('KalturaPHP5/KalturaClient.php');
define('PARTNER_ID', 00000);
define('ADMIN_SECRET','XXXXX');
define('USER_ID','XXXXX');
define('SERVICE_URL','https://www.kaltura.com');
define('PLAYER_UICONF_ID', 00000);
define('CATEGORY_ID', 00000);
define('SECONDS', 3); 
define('METADATA_SYSTEM_NAME', 'recruiterApplication');
define('METADATA_TEMPLATE', "<metadata><ApplicantFullName>%s</ApplicantFullName><ApplicantEmail>%s</ApplicantEmail><ApplicantLinkedInProfileLink>%s</ApplicantLinkedInProfileLink></metadata>");
define('QUESTIONS', array("Tell us about yourself",
"Why do you think you'll be a good fit for this role?",
"What can you contribute to Kaltura?", 
"Where would you want to see your career in five years?")); 
